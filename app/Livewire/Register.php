<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Register extends Component
{
    #[Layout('components.layouts.auth')]

    public $name;
    public $email;
    public $password;

    public function register()
    {
        // Validasi input sederhana
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5',
        ]);

        // Simpan user ke database (data asli)
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Arahkan ke login setelah daftar
        session()->flash('success', 'Akun berhasil dibuat! Silakan login.');
        return $this->redirect('/login', navigate: true);
    }

    public function render()
    {
        return view('livewire.register');
    }
}
