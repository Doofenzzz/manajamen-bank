    <?php

    use App\Http\Controllers\ProfileController;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Auth\RegisteredUserController;

    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('/kredit', fn () => view('kredit.kredit'))->name('kredit');
    Route::get('/deposito', fn () => view('deposito.deposito'))->name('deposito');
    Route::get('/rekening', fn () => view('rekening.rekening'))->name('rekening');
    Route::get('/about-us', fn () => view('about-us'))->name('about');


    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    // Guest auth (custom tambahan di luar auth.php)
    Route::middleware('guest')->group(function () {
        Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('register', [RegisteredUserController::class, 'store']);

        // OTP (kalau memang kamu tambahkan di controller itu)
        Route::get('verify-otp',  [RegisteredUserController::class, 'showVerifyForm'])->name('verify.otp.form');
        Route::post('verify-otp', [RegisteredUserController::class, 'verifyOTP'])->name('verify.otp');
        Route::post('resend-otp', [RegisteredUserController::class, 'resendOTP'])->name('resend.otp');
    });

    require __DIR__.'/auth.php';
