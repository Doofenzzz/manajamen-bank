<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Generate 6 digit OTP
        $otp = rand(100000, 999999);

        // Store data temporarily in session
        $request->session()->put('registration_data', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        // Send OTP via email
        $this->sendOTP($request->email, $request->name, $otp);

        session(['otp_email' => $request->email]);


        return redirect()->route('verify.otp.form')->with([
            'otp_email' => $request->email,
            'success' => 'Kode OTP telah dikirim ke email Anda!'
        ]);
    }

    /**
     * Show OTP verification form
     */
    public function showVerifyForm(): View
    {
        if (!session()->has('registration_data')) {
            abort(403, 'Unauthorized');
        }

        return view('auth.verify-otp');
    }

    /**
     * Verify OTP and create user
     */
    public function verifyOTP(Request $request): RedirectResponse
    {
        $registrationData = session('registration_data');

        if (!$registrationData) {
            return redirect()->route('register')->with('error', 'Sesi telah berakhir. Silakan daftar ulang.');
        }

        // Check if OTP expired
        if (now()->greaterThan($registrationData['otp_expires_at'])) {
            return back()->with('error', 'Kode OTP telah kadaluarsa. Silakan kirim ulang.');
        }

        // Get OTP from inputs
        $otpInputs = $request->input('otp');
        $enteredOTP = implode('', $otpInputs);

        // Verify OTP
        if ($enteredOTP != $registrationData['otp']) {
            return back()->with('error', 'Kode OTP tidak valid. Silakan coba lagi.');
        }

        // Create user
        $user = User::create(attributes: [
            'name' => $registrationData['name'],
            'email' => $registrationData['email'],
            'password' => $registrationData['password'],
            'email_verified_at' => now(),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Clear session
        $request->session()->forget('registration_data');

        return redirect()->route('welcome')->with('success', 'Akun berhasil dibuat!');
    }

    /**
     * Resend OTP
     */
    public function resendOTP(Request $request)
    {
        $registrationData = session('registration_data');

        if (!$registrationData) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak ditemukan'], 404);
        }

        // Generate new OTP
        $otp = rand(100000, 999999);

        // Update session with new OTP
        $registrationData['otp'] = $otp;
        $registrationData['otp_expires_at'] = now()->addMinutes(5);
        session(['registration_data' => $registrationData]);

        // Send OTP via email
        $this->sendOTP($registrationData['email'], $registrationData['name'], $otp);

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP baru telah dikirim!'
        ]);
    }

    /**
     * Send OTP email
     */
    private function sendOTP(string $email, string $name, int $otp): void
    {
        Mail::send('emails.otp', ['name' => $name, 'otp' => $otp], function ($message) use ($email) {
            $message->to($email)
                    ->subject('Kode Verifikasi OTP - PT BPR Sarimadu');
        });
    }
}
