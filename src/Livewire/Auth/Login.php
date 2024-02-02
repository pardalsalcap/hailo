<?php

namespace Pardalsalcap\Hailo\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $email2 = '';

    public string $password = '';

    public function render()
    {
        return view('hailo::livewire.auth.login')
            ->layout('hailo::layouts.guest')
            ->title(__('hailo::hailo.login_welcome', ['name' => config('app.name')]));
    }

    public function login()
    {
        $this->validate($this->rules());
        $this->authenticate();
    }

    public function rules()
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
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
