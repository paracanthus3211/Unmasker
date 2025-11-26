<?php
// app/Models/Avatar.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    protected $fillable = [
        'name', 
        'image_url', 
        'is_active',
        'created_by' // ✅ TAMBAHKAN INI
    ];
    
    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationship dengan user yang membuat avatar
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope untuk avatar default (dari admin - created_by = null)
    public function scopeDefault($query)
    {
        return $query->whereNull('created_by');
    }

    // Scope untuk avatar custom user tertentu
    public function scopeUserCustom($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    // Scope untuk avatar aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor untuk avatar URL
    public function getImageUrlAttribute($value)
    {
        if (str_starts_with($value, 'http')) {
            return $value;
        }
        return asset('storage/' . $value);
    }

    // Method untuk cek apakah avatar default (dari admin)
    public function isDefault()
    {
        return is_null($this->created_by);
    }

    // Method untuk cek apakah avatar custom user
    public function isCustom()
    {
        return !is_null($this->created_by);
    }
}