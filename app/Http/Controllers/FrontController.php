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
        
        $viewData = compact('invitation', 'settings', 'guestName', 'gallery', 'stories', 'musicRecord', 'guestSlug', 'inv_id', 'invSlug');

        // Pilihan tema via settings: 'theme' => key di bawah.
        // Tema default (front.index) tetap dipakai bila key 'theme' kosong / tidak dikenal.
        $themeViews = [
            'minecraft'           => 'front.themes.minecraft',
            'minecraft-adventure' => 'front.themes.minecraft-adventure',
            'royal-emerald'       => 'front.themes.royal-emerald',
            'jawa-premium'        => 'front.themes.jawa-premium',
            'islamic-elegance'    => 'front.themes.islamic-elegance',
            'sakura-dream'        => 'front.themes.sakura-dream',
            'midnight-galaxy'     => 'front.themes.midnight-galaxy',
            'rustic-boho'         => 'front.themes.rustic-boho',
            'minang-gadang'       => 'front.themes.minang-gadang',
            'taman-janur'         => 'front.themes.taman-janur',
            'senja-pesisir'       => 'front.themes.senja-pesisir',
            'empat-musim'         => 'front.themes.empat-musim',
            'bawah-laut'          => 'front.themes.bawah-laut',
            'gunung-berkabut'     => 'front.themes.gunung-berkabut',
            'sogan-parallax'      => 'front.themes.sogan-parallax',
            'rimba-watercolor'    => 'front.themes.rimba-watercolor',
            'neon-metropolis'     => 'front.themes.neon-metropolis',
            'arabian-nights'      => 'front.themes.arabian-nights',
            'origami-dreams'      => 'front.themes.origami-dreams',
            'galaksi-cinta'       => 'front.themes.galaksi-cinta',
            'winter-aurora'       => 'front.themes.winter-aurora',
        ];
        $theme = $settings['theme'] ?? 'default';
        if (isset($themeViews[$theme])) {
            return view($themeViews[$theme], $viewData);
        }

        return view('front.index', $viewData);
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
