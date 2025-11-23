<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Rekening, Kredit, Deposito, User};
use App\Notifications\StatusChanged;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PengajuanController extends Controller
{
    // Mapping kolom nominal untuk setiap tipe
    protected const NOMINAL_COLUMNS = [
        'rekening' => 'setoran_awal',
        'kredit'   => 'jumlah_pinjaman',
        'deposito' => 'nominal',
    ];

    protected function resolve(string $type){
        return match($type){
            'rekening' => [Rekening::class, 'rekening'],
            'kredit'   => [Kredit::class,   'kredit'],
            'deposito' => [Deposito::class, 'deposito'],
            default    => abort(404)
        };
    }

    public function index(Request $r){
        $sort = $r->input('sort', 'created_at');
        $dir = $r->input('dir', 'desc');

        $build = function($model, $type) use ($sort, $dir){
            $query = $model::query()
                ->with(['nasabah','processor'])
                ->when(request('q'), function($x,$q){
                    $x->where(function($w) use ($q){
                        $w->whereHas('nasabah', fn($n)=>$n->where('nama','like',"%$q%")->orWhere('nik','like',"%$q%"))
                          ->orWhere('catatan','like',"%$q%");
                    });
                })
                ->when(request('status'), fn($x,$st)=>$x->where('status',$st))
                ->when(request('date_from'), fn($x,$d)=>$x->whereDate('created_at','>=',$d))
                ->when(request('date_to'), fn($x,$d)=>$x->whereDate('created_at','<=',$d));

            // Handle sorting dengan mapping kolom nominal
            $sortColumn = $sort;
            if ($sort === 'nominal') {
                $sortColumn = self::NOMINAL_COLUMNS[$type];
            }

            return $query->orderBy($sortColumn, $dir);
        };

        $tabs = [
            'rekening' => $build(Rekening::class, 'rekening')->paginate(10, ['*'], 'rekening_page'),
            'kredit'   => $build(Kredit::class, 'kredit')->paginate(10, ['*'], 'kredit_page'),
            'deposito' => $build(Deposito::class, 'deposito')->paginate(10, ['*'], 'deposito_page'),
        ];

        return view('admin.pengajuan.index', [
            'tabs'=>$tabs,
        ]);
    }

    public function show(string $type, int $id){
        [$klass, $key] = $this->resolve($type);
        $item = $klass::with(['nasabah','statusHistories.changedBy','notes.user','processor'])->findOrFail($id);

        if (request()->ajax()){
            return view('admin.pengajuan._detail', compact('item','type'));
        }
        return view('admin.pengajuan.show', compact('item','type'));
    }

    public function updateStatus(Request $r, string $type, int $id){
        $r->validate([
            'to'     => 'required|in:pending,diterima,ditolak',
            'reason' => 'nullable|string'
        ]);

        [$klass] = $this->resolve($type);
        $item = $klass::with('nasabah.user')->findOrFail($id);
        $item->setStatus($r->to, $r->reason, $r->user()->id);

        if (optional($item->nasabah)->user) {
            Notification::send($item->nasabah->user, new StatusChanged(
                ucfirst($type), $item->id, $r->to, $r->reason
            ));
        }
        return back()->with('ok','Status diperbarui.');
    }

    public function addNote(Request $r, string $type, int $id){
        $r->validate(['body'=>'required|string|min:2']);
        [$klass] = $this->resolve($type);
        $item = $klass::findOrFail($id);
        $item->notes()->create(['user_id'=>$r->user()->id,'body'=>$r->body]);
        return back()->with('ok','Catatan ditambahkan.');
    }

    public function assign(Request $r, string $type, int $id){
        $r->validate(['processed_by'=>'required|exists:users,id']);
        [$klass] = $this->resolve($type);
        $item = $klass::findOrFail($id);
        $item->processed_by = $r->processed_by;
        $item->save();
        return back()->with('ok','Pengajuan di-assign.');
    }
    public function preview(string $type, int $id, Request $req)
    {
        [$disk, $path, $label] = $this->resolveAdminDoc($type, $id, $req);

        $mime = $disk->mimeType($path) ?? 'application/octet-stream';
        $allowed = ['application/pdf','image/jpeg','image/png','image/jpg','image/webp','image/gif'];
        if (!in_array($mime, $allowed, true)) {
            throw new HttpException(415, 'Tipe file tidak didukung untuk preview.');
        }

        return response()->file($disk->path($path), [
            'Content-Type'           => $mime,
            'Content-Disposition'    => 'inline; filename="'.basename($path).'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
    public function download(string $type, int $id, Request $req)
    {
        [$disk, $path, $label] = $this->resolveAdminDoc($type, $id, $req);
        return $disk->download($path);
    }

    private function resolveAdminDoc(string $type, int $id, Request $req): array
    {
        $type = strtolower($type);
        if (!in_array($type, ['rekening','kredit','deposito'], true)) {
            throw new HttpException(400, 'Tipe pengajuan tidak valid.');
        }

        switch ($type) {
            case 'kredit':
                $model = Kredit::with('nasabah')->findOrFail($id);
                // doc=jaminan|pendukung (default jaminan)
                $doc = $req->string('doc', 'jaminan')->lower()->value();
                if (!in_array($doc, ['jaminan','pendukung'], true)) {
                    throw new HttpException(400, 'Parameter doc tidak valid.');
                }
                $path  = $doc === 'pendukung' ? $model->dokumen_pendukung : $model->jaminan_dokumen;
                $label = $doc === 'pendukung' ? 'Dokumen Pendukung' : 'Dokumen Jaminan';
                break;

            case 'deposito':
                $model = Deposito::with('nasabah')->findOrFail($id);
                // doc=bukti_transfer (default)
                $doc = $req->string('doc', 'bukti_transfer')->lower()->value();
                if ($doc !== 'bukti_transfer') {
                    throw new HttpException(400, 'Parameter doc tidak valid.');
                }
                $path  = $model->bukti_transfer;
                $label = 'Bukti Transfer';
                break;

            case 'rekening':
                $model = Rekening::with('nasabah')->findOrFail($id);
                // sesuaikan mapping field yang kamu punya
                $doc = $req->string('doc', 'ktp')->lower()->value();
                $map = [
                    'ktp'      => 'foto_ktp',
                    'kk'       => 'foto_kk',
                    'npwp'     => 'foto_npwp',
                    'lampiran' => 'lampiran', // opsional kalau ada
                ];
                if (!array_key_exists($doc, $map)) {
                    throw new HttpException(400, 'Parameter doc tidak valid.');
                }
                $field = $map[$doc];
                $path  = $model->{$field} ?? null;
                $label = 'Dokumen '.strtoupper($doc);
                break;
        }

        if (!$path) {
            throw new HttpException(404, "$label tidak tersedia.");
        }

        $disk = Storage::disk('private');
        if (!$disk->exists($path)) {
            throw new HttpException(404, "$label tidak ditemukan.");
        }

        return [$disk, $path, $label];
    }
    // public function export(Request $r): StreamedResponse {
    //     $type = $r->string('type');
    //     $filename = 'pengajuan_'.($type ?: 'semua').'_'.now()->format('Ymd_His').'.csv';

    //     $callback = function() use ($r, $type){
    //         $out = fopen('php://output', 'w');
    //         fputcsv($out, ['Tipe','ID','Nasabah','NIK','Status','Nominal/Setoran','Created At','Processed By']);

    //         $dump = function($model, $typeName) use($r, $out){
    //             $sort = $r->sort ?? 'created_at';
    //             $dir = $r->dir ?? 'desc';
                
    //             // Handle sorting dengan mapping kolom nominal
    //             $sortColumn = $sort;
    //             if ($sort === 'nominal' && isset(self::NOMINAL_COLUMNS[$typeName])) {
    //                 $sortColumn = self::NOMINAL_COLUMNS[$typeName];
    //             }

    //             $q = $model::query()->with(['nasabah','processor'])
    //                 ->when($r->q, fn($x)=>$x->whereHas('nasabah',fn($n)=>$n->where('nama','like',"%{$r->q}%")->orWhere('nik','like',"%{$r->q}%")))
    //                 ->when($r->status, fn($x)=>$x->where('status',$r->status))
    //                 ->when($r->date_from, fn($x)=>$x->whereDate('created_at','>=',$r->date_from))
    //                 ->when($r->date_to, fn($x)=>$x->whereDate('created_at','<=',$r->date_to))
    //                 ->orderBy($sortColumn, $dir)
    //                 ->cursor();

    //             foreach($q as $row){
    //                 $nominal = $row->nominal ?? $row->setoran_awal ?? $row->jumlah_pinjaman ?? null;
    //                 fputcsv($out, [
    //                     ucfirst($typeName),
    //                     $row->id,
    //                     $row->nasabah->nama ?? '-',
    //                     $row->nasabah->nik ?? '-',
    //                     $row->status,
    //                     $nominal,
    //                     $row->created_at,
    //                     $row->processor->name ?? '-',
    //                 ]);
    //             }
    //         };

    //         if ($type) {
    //             $map = ['rekening'=>Rekening::class,'kredit'=>Kredit::class,'deposito'=>Deposito::class];
    //             $dump($map[$type], $type);
    //         } else {
    //             $dump(Rekening::class,'rekening');
    //             $dump(Kredit::class,'kredit');
    //             $dump(Deposito::class,'deposito');
    //         }
    //         fclose($out);
    //     };

    //     return response()->streamDownload($callback, $filename, ['Content-Type'=>'text/csv']);
    // }
}