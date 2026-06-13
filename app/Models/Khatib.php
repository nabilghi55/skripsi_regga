<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Khatib extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'no_hp',
        'alamat',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
