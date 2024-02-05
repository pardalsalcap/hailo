<?php

namespace Pardalsalcap\Hailo\Livewire\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Component;

class ResetApp extends Component
{
    public string $email = '';

    public string $token = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount()
    {
        $this->token = request()->route('token');
        $this->email = request()->get('email');
    }

    public function doReset()
    {
        $this->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            $this->dispatch('toast-success', ['title' => __($status)]);
            $this->addError('password', __($status));
        } else {
            $this->addError('email', __($status));
        }
    }

    public function render()
    {
        return view('hailo::livewire.auth.reset')
            ->layout('hailo::layouts.guest')
            ->title(__('hailo::hailo.login_welcome', ['name' => config('app.name')]));
    }

    public function recover(): void
    {
        $this->validate($this->rules());

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            [
                'email' => $this->email,
            ]
        );
        if ($status == Password::RESET_LINK_SENT) {
            $this->redirect(route('hailo.login'));
        } else {
            $this->addError('email', __($status));
        }
        /*return $status == Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);*/

    }

    public function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }

    public function authenticate()
    {
        // Perform authentication logic here
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->redirect($this->intended(route('hailo.dashboard')));
        } else {
            $this->addError('email', __('auth.failed'));
        }
    }

    protected function intended($default = '/')
    {
        return session()->pull('url.intended', $default);
    }
}
