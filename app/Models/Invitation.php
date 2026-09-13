<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    public function rsvps()
    {
        return $this->hasMany(Rsvp::class);
    }

    public function ucapans()
    {
        return $this->hasMany(Ucapan::class);
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function music()
    {
        return $this->hasMany(Music::class);
    }

    public function stories()
    {
        return $this->hasMany(Story::class);
    }
}
