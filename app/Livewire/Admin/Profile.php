<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\Avatar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin')]
class Profile extends Component
{
    use WithFileUploads;

    // Profile Information
    public $name;
    public $username;
    public $email;
    public $phone;
    public $birth_date;
    public $selectedAvatar;
    
    // Avatar Management
    public $availableAvatars;
    public $avatars; // ✅ TAMBAH VARIABLE INI UNTUK ADMIN AVATAR MANAGEMENT
    public $newAvatar;
    
    // Verification Status
    public $emailVerified = false;
    public $phoneVerified = false;
    
    // Password Change
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    /**
     * Initialize component
     */
    public function mount()
    {
        $user = Auth::user();
        
        // Initialize form fields
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->birth_date = $user->birth_date ? $user->birth_date->format('Y-m-d') : null;
        $this->selectedAvatar = $user->avatar_id;
        
        // Load available avatars untuk dipilih
        $this->availableAvatars = Avatar::where('is_active', true)
            ->orderBy('created_by') // Default avatars first
            ->orderBy('created_at', 'desc')
            ->get();
            
        // ✅ TAMBAH: Load semua avatars untuk admin management
        $this->avatars = Avatar::orderBy('created_by') // Default avatars first
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Verification status
        $this->emailVerified = !is_null($user->email_verified_at);
        $this->phoneVerified = !is_null($user->phone_verified_at);
    }

    /**
     * Update profile information
     */
    public function updateProfile()
    {
        $user = Auth::user();
        
        $this->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|alpha_dash|unique:users,username,' . $user->id,
            'birth_date' => 'nullable|date|before:today',
        ], [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, dash, dan underscore.',
            'username.unique' => 'Username sudah digunakan oleh user lain.',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
        ]);

        $user->update([
            'name' => $this->name,
            'username' => $this->username,
            'birth_date' => $this->birth_date,
            'avatar_id' => $this->selectedAvatar,
        ]);

        session()->flash('message', 'Profile updated successfully!');
    }

    /**
     * Select avatar
     */
    public function selectAvatar($avatarId)
    {
        $this->selectedAvatar = $avatarId;
        
        $user = Auth::user();
        $user->update(['avatar_id' => $avatarId]);
        
        session()->flash('message', 'Avatar updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed|different:current_password',
        ], [
            'new_password.different' => 'New password must be different from current password.',
            'new_password.min' => 'Password must be at least 8 characters.',
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        // Update password
        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        // Reset password fields
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('message', 'Password updated successfully!');
    }

    /**
     * Upload custom avatar (Admin bisa upload default avatars)
     */
    public function uploadAvatar()
    {
        $this->validate([
            'newAvatar' => 'required|image|max:2048|mimes:jpg,jpeg,png,gif,webp',
        ], [
            'newAvatar.image' => 'File must be an image.',
            'newAvatar.max' => 'Image size must not exceed 2MB.',
            'newAvatar.mimes' => 'Supported formats: JPG, PNG, GIF, WEBP.',
        ]);

        try {
            // Store the image
            $avatarPath = $this->newAvatar->store('avatars', 'public');
            
            // Generate avatar name
            $originalName = $this->newAvatar->getClientOriginalName();
            $name = pathinfo($originalName, PATHINFO_FILENAME);
            
            // ✅ ADMIN: Upload sebagai default avatar (created_by = null)
            $avatar = Avatar::create([
                'name' => $name,
                'image_url' => $avatarPath,
                'is_active' => true,
                'created_by' => null, // Default avatar untuk semua user
            ]);

            // Refresh both avatar lists
            $this->refreshAvatars();

            // Reset file input
            $this->newAvatar = null;

            // Close modal
            $this->dispatch('close-modal', modal: 'uploadAvatarModal');
            
            session()->flash('message', 'Default avatar uploaded successfully! 🎉');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to upload avatar: ' . $e->getMessage());
        }
    }

    /**
     * Delete avatar (Admin bisa hapus default avatars)
     */
    public function deleteAvatar($avatarId)
    {
        $avatar = Avatar::find($avatarId);
        
        if (!$avatar) {
            session()->flash('error', 'Avatar not found!');
            return;
        }

        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($avatar->image_url)) {
                Storage::disk('public')->delete($avatar->image_url);
            }
            
            // Delete avatar record
            $avatar->delete();
            
            // Refresh both avatar lists
            $this->refreshAvatars();
            
            // Reset selection if deleted avatar was selected
            if ($this->selectedAvatar == $avatarId) {
                $this->selectedAvatar = null;
                Auth::user()->update(['avatar_id' => null]);
            }
            
            session()->flash('message', 'Avatar deleted successfully!');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete avatar: ' . $e->getMessage());
        }
    }

    /**
     * Update contact information
     */
    public function updateContact()
    {
        $user = Auth::user();
        
        $this->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|regex:/^\+?[0-9\s\-\(\)]+$/',
        ], [
            'email.unique' => 'Email already taken by another user.',
            'phone.regex' => 'Please enter a valid phone number.',
        ]);

        $updates = [
            'email' => $this->email,
            'phone' => $this->phone,
        ];

        // Reset verification if email changed
        if ($user->email !== $this->email) {
            $updates['email_verified_at'] = null;
            $this->emailVerified = false;
        }

        // Reset verification if phone changed
        if ($user->phone !== $this->phone) {
            $updates['phone_verified_at'] = null;
            $this->phoneVerified = false;
        }

        $user->update($updates);

        session()->flash('message', 'Contact information updated successfully!');
    }

    /**
     * Refresh available avatars list
     */
    private function refreshAvatars()
    {
        // Refresh avatars untuk dipilih
        $this->availableAvatars = Avatar::where('is_active', true)
            ->orderBy('created_by')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // ✅ TAMBAH: Refresh semua avatars untuk admin management
        $this->avatars = Avatar::orderBy('created_by')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Generate username suggestions
     */
    public function generateUsername()
    {
        if (!empty($this->name)) {
            $this->username = User::generateUsername($this->name);
        }
    }

    /**
     * Check username availability
     */
    public function checkUsername()
    {
        if (!empty($this->username)) {
            $exists = User::where('username', $this->username)
                         ->where('id', '!=', Auth::id())
                         ->exists();
            
            if ($exists) {
                $this->addError('username', 'Username is already taken.');
            } else {
                $this->resetErrorBag('username');
            }
        }
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.admin.profile');
    }
}