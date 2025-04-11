<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $error = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $this->dispatch('login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
} 