<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'friend_id',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // RELATIONSHIPS
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    // SCOPES
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeBetweenUsers($query, $user1, $user2)
    {
        return $query->where(function($q) use ($user1, $user2) {
            $q->where('user_id', $user1)->where('friend_id', $user2);
        })->orWhere(function($q) use ($user1, $user2) {
            $q->where('user_id', $user2)->where('friend_id', $user1);
        });
    }

    // ✅ TAMBAH: Method helper untuk mencari friendship spesifik
    public static function findBetweenUsers($user1, $user2)
    {
        return static::betweenUsers($user1, $user2)->first();
    }

    // ✅ TAMBAH: Method untuk cek apakah friendship exists
    public static function existsBetween($user1, $user2)
    {
        return static::betweenUsers($user1, $user2)->exists();
    }
}