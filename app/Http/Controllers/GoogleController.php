<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $google = Socialite::driver('google')->user();
            $se_registro = User::where('email', $google->email)->where('estado', '!=', 0)->first();
            if (empty($se_registro)) {
                $user = new User();
                $user->name = $google->user['given_name'];
                $user->apellidos = $google->user['family_name'];
                $user->email = $google->email;
                $user->google_id = $google->id;
                $user->estado = 1;
                $user->password = bcrypt($this->usuarioCorreo($google->email));
                $user->email_verified_at = time();
                $user->save();
                $user->assignRole('PACIENTE');
                Auth::login($user);
                return redirect()->intended(config('fortify.home'));
            } else {
                if ($se_registro->estado == 2) {
                    throw new \Exception('Su cuenta se encuentra suspendida.');
                }
                Auth::login($se_registro);
                return redirect()->intended(config('fortify.home'));
            }
        } catch (\Exception $e) {
            return redirect('login')
                ->withErrors(['google' => $e->getMessage()]);
        }
    }

    private function usuarioCorreo($correo)
    {
        return strtok($correo, '@');
    }
}
