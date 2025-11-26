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
    public $username; // ✅ TAMBAH INI
    public $email;
    public $password;
    public $birth_date;

    public function register()
    {
        $this->validate([
            'name' => 'required|min:3',
            'username' => 'required|min:5|unique:users,username|regex:/^[a-zA-Z0-9_]+$/', // ✅ VALIDASI USERNAME
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5',
            'birth_date' => 'required|date',
        ], [
            'username.regex' => 'Username hanya boleh mengandung huruf, angka, dan underscore',
            'username.unique' => 'Username sudah digunakan, silakan pilih yang lain',
            'username.min' => 'Username minimal 5 karakter'
        ]);

        User::create([
            'name' => $this->name,
            'username' => $this->username, // ✅ SIMPAN USERNAME
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'birth_date' => $this->birth_date,
            'role' => 'user',
        ]);

        session()->flash('success', 'Akun berhasil dibuat! Silakan login.');
        return $this->redirect('/login', navigate: true);
    }

    public function render()
    {
        return view('livewire.register');
    }
}