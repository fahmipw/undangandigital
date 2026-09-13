<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Models\Guest;
use App\Models\Gallery;
use App\Models\Story;
use App\Models\Music;

class FrontController extends Controller
{
    public function index(Request $request, $slug = 'hawa-adam')
    {
        $guestSlug = $request->query('to', '');
        
        $invitation = Invitation::with(['settings', 'galleries', 'stories', 'music'])->where('slug', $slug)->first();
        
        if (!$invitation) {
            return response("<div style='text-align:center; padding:50px; font-family:sans-serif;'><h2>Undangan Tidak Ditemukan</h2><p>Pastikan URL yang Anda masukkan benar.</p></div>", 404);
        }

        $settingsRaw = $invitation->settings;
        $settings = [];
        foreach ($settingsRaw as $s) {
            $settings[$s->key_name] = $s->key_value;
        }

        $guestName = "Bapak/Ibu/Saudara/i";
        if ($guestSlug) {
            $guest = Guest::where('invitation_id', $invitation->id)
                          ->where(function($q) use ($guestSlug) {
                              $q->where('slug', $guestSlug)->orWhere('slug', urlencode($guestSlug));
                          })->first();
            if ($guest) {
                $guestName = $guest->nama;
            } else {
                $guestName = htmlspecialchars(str_replace(['+', '-'], ' ', $guestSlug));
            }
        }

        $gallery = $invitation->galleries()->orderBy('created_at', 'ASC')->pluck('image_path')->toArray();
        $stories = $invitation->stories()->orderBy('id', 'ASC')->get();
        $musicRecord = $invitation->music()->where('is_active', 1)->first();
        $inv_id = $invitation->id;
        $invSlug = $invitation->slug;
        
        return view('front.index', compact('invitation', 'settings', 'guestName', 'gallery', 'stories', 'musicRecord', 'guestSlug', 'inv_id', 'invSlug'));
    }
    public function generator(Request $request, $slug)
    {
        $invitation = Invitation::with('settings')->where('slug', $slug)->first();
        if (!$invitation) {
            return response("Undangan tidak ditemukan", 404);
        }

        $settingsRaw = $invitation->settings;
        $settings = [];
        foreach ($settingsRaw as $s) {
            $settings[$s->key_name] = $s->key_value;
        }

        $bride = !empty($settings['bride_nickname']) ? $settings['bride_nickname'] : (!empty($settings['bride_name']) ? $settings['bride_name'] : 'Mempelai Wanita');
        $groom = !empty($settings['groom_nickname']) ? $settings['groom_nickname'] : (!empty($settings['groom_name']) ? $settings['groom_name'] : 'Mempelai Pria');
        
        return view('front.generator', [
            'invitation' => $invitation,
            'bride' => $bride,
            'groom' => $groom
        ]);
    }
}
