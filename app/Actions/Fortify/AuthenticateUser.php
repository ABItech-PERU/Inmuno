<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AuthenticateUser
{
    private const RECAPTCHA_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Autenticar al usuario con validación de reCAPTCHA.
     */
    public function __invoke(Request $request)
    {
        // $this->validateRecaptcha($request);

        return $this->authenticateUser($request);
    }

    /**
     * Validar la respuesta de reCAPTCHA.
     */
    private function validateRecaptcha(Request $request): void
    {
        if (empty($request->recaptcha)) {
            throw ValidationException::withMessages([
                'recaptcha' => 'Completa el captcha para continuar.'
            ]);
        }

        try {
            $response = Http::asForm()->post(self::RECAPTCHA_VERIFY_URL, [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $request->recaptcha,
                'remoteip' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'recaptcha' => 'Error al verificar el captcha. Inténtalo de nuevo.'
            ]);
        }

        if (!$response->successful() || !($json = $response->json())['success']) {
            throw ValidationException::withMessages([
                'recaptcha' => $this->getRecaptchaErrorMessage($json['error-codes'] ?? [])
            ]);
        }
    }

    /**
     * Autenticar al usuario con email y contraseña.
     */
    private function authenticateUser(Request $request): ?User
    {
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            return $user;
        }

        return null;
    }

    /**
     * Obtener un mensaje de error amigable para fallos de reCAPTCHA.
     */
    private function getRecaptchaErrorMessage(array $errorCodes): string
    {
        if (in_array('timeout-or-duplicate', $errorCodes)) {
            return 'El captcha ha expirado. Completa el captcha nuevamente.';
        }

        $defaultMessage = 'Captcha inválido. Inténtalo de nuevo.';

        return $defaultMessage;
    }
}
