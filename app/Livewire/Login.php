<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component
{
    #[Layout('components.layouts.auth')]

public $email;

public $password;

    public function render()
    {
        return view('livewire.login');
    }
    public function login(){
    
    if(Auth::attempt([
        'email' => $this->email,
        'password' => $this->password
    ])){
        return $this->redirect('/dashboard',navigate:true);
    }
    return $this->redirect('login',navigate:true);
    }
}

