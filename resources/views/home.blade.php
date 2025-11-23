@extends('layouts.app')
@section('content')
<div class="grid lg:grid-cols-2 gap-8 items-center">
  <div>
    <h1 class="text-3xl sm:text-4xl font-bold mb-3">Selamat datang di BPR XYZ</h1>
    <p class="text-gray-600 mb-6">Layanan keuangan sederhana dan aman: tabungan, kredit, dan deposito. Akses informasi
      produk tanpa login, ajukan layanan setelah masuk.</p>
    <div class="flex flex-wrap gap-3">
      <a href="/rekening" class="px-4 py-2 rounded-xl border">Lihat Tabungan</a>
      <a href="/kredit" class="px-4 py-2 rounded-xl border">Lihat Kredit</a>
      <a href="/deposito" class="px-4 py-2 rounded-xl border">Lihat Deposito</a>
    </div>
  </div>
  <div class="rounded-2xl border bg-white p-6">
    <div class="grid sm:grid-cols-3 gap-4 text-center">
      <div class="p-4 rounded-xl bg-blue-50 border">
        <div class="text-xl font-semibold">Tabungan</div>
        <div class="text-xs text-gray-500">Setor tarik fleksibel</div>
      </div>
      <div class="p-4 rounded-xl bg-yellow-50 border">
        <div class="text-xl font-semibold">Kredit</div>
        <div class="text-xs text-gray-500">Bunga kompetitif</div>
      </div>
      <div class="p-4 rounded-xl bg-red-50 border">
        <div class="text-xl font-semibold">Deposito</div>
        <div class="text-xs text-gray-500">Imbal hasil tetap</div>
      </div>
    </div>
  </div>
</div>
@endsection