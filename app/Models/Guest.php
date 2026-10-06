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

    /**
     * Generate kode QR unik per undangan (10 karakter alfanumerik).
     */
    public static function newQrCode($invitationId)
    {
        do {
            $code = strtoupper(\Illuminate\Support\Str::random(10));
            $exists = static::where('invitation_id', $invitationId)
                ->where('qr_code', $code)->exists();
        } while ($exists);
        return $code;
    }
}
