<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }
}
