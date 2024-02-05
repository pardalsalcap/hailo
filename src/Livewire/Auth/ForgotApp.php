<?php

namespace Pardalsalcap\Hailo\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotApp extends Component
{
    public string $email = '';

    public function render()
    {
        return view('hailo::livewire.auth.forgot')
            ->layout('hailo::layouts.guest')
            ->title(__('hailo::hailo.login_welcome', ['name' => config('app.name')]));
    }

    public function recover(): void
    {
        $this->validate($this->rules());

        $status = Password::sendResetLink(
            [
                'email' => $this->email,
            ]
        );
        if ($status == Password::RESET_LINK_SENT) {
            $this->dispatch('toast-success', ['title' => __($status)]);
            $this->addError('email', __($status));
        } else {
            $this->addError('email', __($status));
        }
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
