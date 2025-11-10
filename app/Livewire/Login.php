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

    public function login()
    {
        // Coba login
        if (Auth::attempt([
            'email' => $this->email,
            'password' => $this->password
        ])) {
            $user = Auth::user();

            // ✅ Tampilkan pesan sukses
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Berhasil Login!',
                'text' => 'Selamat datang kembali 👋'
            ]);

            // Delay sedikit agar SweetAlert muncul sebelum redirect
            usleep(500000); // 0.5 detik

            // 🔁 Redirect berdasarkan role
            if ($user->role === 'admin') {
                return $this->redirect('/admin/dashboard', navigate: true);
            } else {
                return $this->redirect('/dashboard', navigate: true);
            }
        }

        // ❌ Jika gagal login
        $this->dispatch('swal', [
            'icon' => 'error',
            'title' => 'Gagal Login!',
            'text' => 'Email atau password salah.'
        ]);
    }
}
