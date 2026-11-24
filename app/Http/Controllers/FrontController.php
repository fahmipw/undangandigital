<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Models\Guest;
use App\Models\Gallery;
use App\Models\Story;
use App\Models\Music;
use Illuminate\Support\Facades\Schema;

class FrontController extends Controller
{
    /**
     * Peta tema terdaftar (dipakai index() + katalog landing).
     */
    private function themeViews()
    {
        return [
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
            'stardew-valley'      => 'front.themes.stardew-valley',
            'stardew-adventure'   => 'front.themes.stardew-adventure',
            'jawa-cinematic'        => 'front.themes.jawa-cinematic',
            'jawa-arunika'          => 'front.themes.jawa-arunika',
            'jawa-klasik'           => 'front.themes.jawa-klasik',
            'jawa-pendopo'          => 'front.themes.jawa-pendopo',
            'jawa-gerbang'         => 'front.themes.jawa-gerbang',
            'betawi'                => 'front.themes.betawi',
            'jawa-gunungan'         => 'front.themes.jawa-gunungan',
            'taman-surga'           => 'front.themes.taman-surga',
            'jawa-biru'             => 'front.themes.jawa-biru',
            'jawa-puspa'            => 'front.themes.jawa-puspa',
            'pixel-quest'           => 'front.themes.pixel-quest',
        ];
    }

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
        $guestQrCode = null;
        if ($guestSlug) {
            $guest = Guest::where('invitation_id', $invitation->id)
                          ->where(function($q) use ($guestSlug) {
                              $q->where('slug', $guestSlug)->orWhere('slug', urlencode($guestSlug));
                          })->first();
            if ($guest) {
                $guestName = $guest->nama;
                // Backfill kode QR untuk tamu lama yang belum punya
                if (empty($guest->qr_code)) {
                    $guest->qr_code = Guest::newQrCode($invitation->id);
                    $guest->save();
                }
                $guestQrCode = $guest->qr_code;
            } else {
                $guestName = htmlspecialchars(str_replace(['+', '-'], ' ', $guestSlug));
                // Tamu demo untuk tombol Demo di landing (?to=tamu-demo): nama +
                // kode QR contoh TANPA menulis ke database. Bila Fahmi nanti
                // membuat tamu sungguhan berslug tamu-demo di admin, tamu itu
                // yang dipakai (pencarian DB di atas menang).
                if ($guestSlug === 'tamu-demo') {
                    $guestName = 'Tamu Demo';
                    $guestQrCode = 'DEMO1';
                }
            }
        }

        $galleryQuery = $invitation->galleries();
        if (Schema::hasColumn('galleries', 'sort_order')) {
            $galleryQuery->orderBy('sort_order', 'ASC');
        }
        $gallery = $galleryQuery->orderBy('created_at', 'ASC')->pluck('image_path')->toArray();
        $stories = $invitation->stories()->orderBy('id', 'ASC')->get();
        $musicRecord = $invitation->music()->where('is_active', 1)->first();
        $inv_id = $invitation->id;
        $invSlug = $invitation->slug;
        
        $viewData = compact('invitation', 'settings', 'guestName', 'guestQrCode', 'gallery', 'stories', 'musicRecord', 'guestSlug', 'inv_id', 'invSlug');

        // Pilihan tema via settings: 'theme' => key di bawah.
        // Tema default (front.index) tetap dipakai bila key 'theme' kosong / tidak dikenal.
        $themeViews = $this->themeViews();
        $theme = $settings['theme'] ?? 'default';
        // Demo tema dari katalog landing: /{slug}?tema={key} — hanya key terdaftar yang berlaku.
        $demoTheme = $request->query('tema', '');
        if ($demoTheme === 'default' || isset($themeViews[$demoTheme])) {
            $theme = $demoTheme;
        }
        // Demo landing: musik per tema (diatur dari tab Landing admin) menimpa
        // musik undangan hawa-adam — HANYA saat demo (?tema=), tamu biasa
        // yang buka /{slug} tanpa ?tema= tetap mendengar musik undangan itu.
        // Tema memakai $musicRecord->file_path (stardew-valley: file_path ?? url).
        if ($demoTheme !== '' && $theme === $demoTheme) {
            $landingOver = [];
            $landingRaw = self::landingSettings()['landing_themes'] ?? null;
            if ($landingRaw) {
                $landingDecoded = json_decode($landingRaw, true);
                if (is_array($landingDecoded)) $landingOver = $landingDecoded;
            }
            $demoMusic = trim((string) ($landingOver[$theme]['music'] ?? ''));
            if ($demoMusic !== '') {
                $musicRecord = (object) ['file_path' => $demoMusic];
                $viewData['musicRecord'] = $musicRecord;
            }
        }
        if (isset($themeViews[$theme])) {
            return view($themeViews[$theme], $viewData);
        }

        return view('front.index', $viewData);
    }

    /**
     * Landing page penjualan "undangankamu" — katalog tema + harga.
     * URL: / (root). Undangan default tetap bisa dibuka lewat /hawa-adam,
     * jadi tidak ada tautan tamu yang rusak.
     * Harga, hero, nomor WA, dan tampilan tema diatur dari tab "Landing Page"
     * di admin (disimpan di settings undangan utama/ID 1).
     */
    public function landing()
    {
        $themes = self::catalogThemes();
        $eff = self::landingSettingsEffective($themes);
        $waNumber = $eff['wa'];
        $hargaNum = [
            'basic' => $eff['harga_basic'],
            'premium' => $eff['harga_premium'],
            'eksklusif' => $eff['harga_eksklusif'],
        ];
        $fmt = function ($n) { return 'Rp ' . number_format($n, 0, ',', '.'); };
        $harga = [
            'basic' => $fmt($hargaNum['basic']),
            'premium' => $fmt($hargaNum['premium']),
            'eksklusif' => $fmt($hargaNum['eksklusif']),
        ];
        $hargaCoret = [
            'basic' => $fmt($hargaNum['basic'] * 2),
            'premium' => $fmt($hargaNum['premium'] * 2),
            'eksklusif' => $fmt($hargaNum['eksklusif'] * 2),
        ];
        $hero = [
            'title' => $eff['hero_title'],
            'highlight' => $eff['hero_highlight'],
            'subtitle' => $eff['hero_subtitle'],
        ];
        $videoCount = $eff['video_count'];
        return view('front.landing', compact('waNumber', 'themes', 'harga', 'hargaNum', 'hargaCoret', 'hero', 'videoCount'));
    }

    /**
     * Halaman pesanan "undangankamu" — form pilih tema + paket + data
     * mempelai + metode pembayaran, lalu diteruskan ke WhatsApp pemesanan.
     * URL: /pesan (preselect: ?tema={slug}&paket={Basic|Premium|Eksklusif}).
     * Tanpa backend baru: pesanan TIDAK disimpan di database, hanya
     * disusun jadi pesan WA. Detail rekening/QRIS/e-wallet diatur dari
     * tab "Landing Page" di admin (kunci landing_pay_*).
     */
    public function order(Request $request)
    {
        $themes = self::catalogThemes();
        $eff = self::landingSettingsEffective($themes);
        $waNumber = $eff['wa'];
        $hargaNum = [
            'basic' => $eff['harga_basic'],
            'premium' => $eff['harga_premium'],
            'eksklusif' => $eff['harga_eksklusif'],
        ];
        $fmt = function ($n) { return 'Rp ' . number_format($n, 0, ',', '.'); };
        $harga = [
            'basic' => $fmt($hargaNum['basic']),
            'premium' => $fmt($hargaNum['premium']),
            'eksklusif' => $fmt($hargaNum['eksklusif']),
        ];
        $hargaCoret = [
            'basic' => $fmt($hargaNum['basic'] * 2),
            'premium' => $fmt($hargaNum['premium'] * 2),
            'eksklusif' => $fmt($hargaNum['eksklusif'] * 2),
        ];
        // Tema terpilih: hanya slug katalog yang dikenal (default = Klasik Emas).
        $selSlug = (string) $request->query('tema', 'default');
        $selTheme = null;
        foreach ($themes as $t) {
            if ($t['slug'] === $selSlug) { $selTheme = $t; break; }
        }
        if ($selTheme === null) $selTheme = $themes[0];
        // Paket terpilih: ?paket=; tanpa itu, default Basic.
        $paket = (string) $request->query('paket', '');
        if (!in_array($paket, ['Basic', 'Premium', 'Eksklusif'], true)) {
            $paket = 'Basic';
        }
        return view('front.order', compact('waNumber', 'themes', 'harga', 'hargaNum', 'hargaCoret', 'selTheme', 'paket'));
    }

    /**
     * Settings landing page (disimpan di undangan utama/ID 1): key => value.
     * Kosong bila undangan 1 belum ada — pemanggil memakai fallback.
     */
    public static function landingSettings()
    {
        $inv = Invitation::with('settings')->find(1);
        $settings = [];
        if ($inv) {
            foreach ($inv->settings as $s) {
                $settings[$s->key_name] = $s->key_value;
            }
        }
        return $settings;
    }


    /**
     * Nilai EFEKTIF pengaturan landing (harga/hero/WA/video): settings
     * undangan 1 bila terisi, kalau kosong pakai default. Dipakai landing()
     * dan admin (get_landing_themes) — satu sumber kebenaran.
     */
    public static function landingSettingsEffective($themes = null)
    {
        $s = self::landingSettings();
        if ($themes === null) $themes = self::catalogThemes();
        $num = function ($k, $d) use ($s) { $v = (int) ($s[$k] ?? 0); return $v > 0 ? $v : $d; };
        $video = 0;
        foreach ($themes as $th) { if (!empty($th['video'])) $video++; }
        return [
            'wa' => !empty($s['landing_wa']) ? $s['landing_wa'] : '6288210841990',
            'harga_basic' => $num('landing_harga_basic', 15000),
            'harga_premium' => $num('landing_harga_premium', 45000),
            'harga_eksklusif' => $num('landing_harga_eksklusif', 50000),
            'hero_title' => !empty($s['landing_hero_title']) ? $s['landing_hero_title'] : 'Undangan cantik,',
            'hero_highlight' => !empty($s['landing_hero_highlight']) ? $s['landing_hero_highlight'] : 'tanpa ribet.',
            'hero_subtitle' => !empty($s['landing_hero_subtitle']) ? $s['landing_hero_subtitle'] : 'Pilih dari ' . count($themes) . ' tema — Jawa klasik sampai game — lengkap dengan RSVP, galeri foto, musik, amplop digital, sampai check-in QR untuk panitia. Jadi dalam hitungan jam, bukan minggu.',
            'video_count' => $video,
        ];
    }

    /**
     * Katalog tema landing: semua tema terdaftar (ikut themeViews, tanpa
     * tema yang di-skip) + tema default, digabung override admin dari settings
     * undangan utama (landing_themes JSON: nama/kategori/urutan/badge/tampil).
     * Tema baru yang didaftarkan otomatis muncul di katalog (kategori
     * default Modern, tanpa badge motion) sampai diatur dari admin.
     * Dipakai landing() dan admin (get_landing_themes).
     */
    public static function catalogThemes($onlyVisible = true)
    {
        $defaults = [
            ['slug' => 'default', 'name' => 'Klasik Emas', 'cat' => 'Modern', 'video' => false],
            ['slug' => 'minecraft', 'name' => 'Minecraft', 'cat' => 'Game', 'video' => false],
            ['slug' => 'minecraft-adventure', 'name' => 'Minecraft Petualangan', 'cat' => 'Game', 'video' => false],
            ['slug' => 'royal-emerald', 'name' => 'Royal Emerald', 'cat' => 'Modern', 'video' => false],
            ['slug' => 'jawa-premium', 'name' => 'Jawa Premium', 'cat' => 'Jawa', 'video' => false],
            ['slug' => 'islamic-elegance', 'name' => 'Islamic Elegance', 'cat' => 'Islami', 'video' => false],
            ['slug' => 'sakura-dream', 'name' => 'Sakura Dream', 'cat' => 'Alam', 'video' => false],
            ['slug' => 'midnight-galaxy', 'name' => 'Midnight Galaxy', 'cat' => 'Modern', 'video' => false],
            ['slug' => 'rustic-boho', 'name' => 'Rustic Boho', 'cat' => 'Modern', 'video' => false],
            ['slug' => 'minang-gadang', 'name' => 'Minang Gadang', 'cat' => 'Nusantara', 'video' => false],
            ['slug' => 'taman-janur', 'name' => 'Taman Janur', 'cat' => 'Jawa', 'video' => false],
            ['slug' => 'senja-pesisir', 'name' => 'Senja Pesisir', 'cat' => 'Alam', 'video' => false],
            ['slug' => 'empat-musim', 'name' => 'Empat Musim', 'cat' => 'Alam', 'video' => false],
            ['slug' => 'bawah-laut', 'name' => 'Bawah Laut', 'cat' => 'Alam', 'video' => false],
            ['slug' => 'gunung-berkabut', 'name' => 'Gunung Berkabut', 'cat' => 'Alam', 'video' => false],
            ['slug' => 'sogan-parallax', 'name' => 'Sogan Parallax', 'cat' => 'Jawa', 'video' => false],
            ['slug' => 'rimba-watercolor', 'name' => 'Rimba Watercolor', 'cat' => 'Alam', 'video' => false],
            ['slug' => 'neon-metropolis', 'name' => 'Neon Metropolis', 'cat' => 'Modern', 'video' => false],
            ['slug' => 'arabian-nights', 'name' => 'Arabian Nights', 'cat' => 'Modern', 'video' => false],
            ['slug' => 'origami-dreams', 'name' => 'Origami Dreams', 'cat' => 'Modern', 'video' => false],
            ['slug' => 'galaksi-cinta', 'name' => 'Galaksi Cinta', 'cat' => 'Modern', 'video' => false],
            ['slug' => 'winter-aurora', 'name' => 'Winter Aurora', 'cat' => 'Modern', 'video' => false],
            ['slug' => 'stardew-valley', 'name' => 'Stardew Valley', 'cat' => 'Game', 'video' => false],
            ['slug' => 'stardew-adventure', 'name' => 'Stardew Adventure', 'cat' => 'Game', 'video' => false],
            ['slug' => 'jawa-cinematic', 'name' => 'Jawa Cinematic', 'cat' => 'Jawa', 'video' => false],
            ['slug' => 'jawa-arunika', 'name' => 'Jawa Arunika', 'cat' => 'Jawa', 'video' => false],
            ['slug' => 'jawa-klasik', 'name' => 'Jawa Klasik', 'cat' => 'Jawa', 'video' => false],
            ['slug' => 'jawa-pendopo', 'name' => 'Jawa Pendopo', 'cat' => 'Jawa', 'video' => true],
            ['slug' => 'jawa-gerbang', 'name' => 'Jawa Gerbang', 'cat' => 'Jawa', 'video' => true],
            ['slug' => 'betawi', 'name' => 'Betawi', 'cat' => 'Nusantara', 'video' => true],
            ['slug' => 'jawa-gunungan', 'name' => 'Jawa Gunungan', 'cat' => 'Jawa', 'video' => true],
            ['slug' => 'taman-surga', 'name' => 'Taman Surga', 'cat' => 'Alam', 'video' => false],
            ['slug' => 'jawa-biru', 'name' => 'Jawa Biru', 'cat' => 'Jawa', 'video' => false],
            ['slug' => 'jawa-puspa', 'name' => 'Jawa Puspa', 'cat' => 'Jawa', 'video' => false],
            ['slug' => 'pixel-quest', 'name' => 'Pixel Quest', 'cat' => 'Game', 'video' => false],
        ];
        $bySlug = [];
        foreach ($defaults as $i => $d) {
            $d['order'] = $i;
            $bySlug[$d['slug']] = $d;
        }
        $slugs = array_merge(['default'], array_keys((new static)->themeViews()));
        $list = [];
        foreach ($slugs as $slug) {
            if ($slug === 'ruang-jawa') continue;
            if (isset($bySlug[$slug])) {
                $list[] = $bySlug[$slug];
            } else {
                $list[] = [
                    'slug' => $slug,
                    'name' => ucwords(str_replace('-', ' ', $slug)),
                    'cat' => 'Modern',
                    'video' => false,
                    'order' => 1000,
                ];
            }
        }
        $over = [];
        $raw = self::landingSettings()['landing_themes'] ?? null;
        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) $over = $decoded;
        }
        foreach ($list as &$t) {
            $o = $over[$t['slug']] ?? [];
            if (!empty($o['name'])) $t['name'] = $o['name'];
            if (!empty($o['cat'])) $t['cat'] = $o['cat'];
            if (isset($o['order'])) $t['order'] = (int) $o['order'];
            if (isset($o['badge'])) $t['badge'] = (string) $o['badge'];
            if (array_key_exists('visible', $o)) $t['visible'] = (bool) $o['visible'];
            if (isset($o['music'])) $t['music'] = (string) $o['music'];
            if (!isset($t['badge'])) $t['badge'] = !empty($t['video']) ? 'MOTION' : '';
            if (!isset($t['music'])) $t['music'] = '';
            if (!isset($t['visible'])) $t['visible'] = true;
        }
        unset($t);
        if ($onlyVisible) {
            $list = array_values(array_filter($list, function ($t) { return $t['visible']; }));
        }
        usort($list, function ($a, $b) { return $a['order'] <=> $b['order']; });
        return $list;
    }

    /**
     * Halaman check-in khusus panitia (tanpa login admin).
     * URL: /checkin/{slug}/{token} — token disimpan di setting checkin_token.
     */
    public function checkinPage($slug, $token)
    {
        $invitation = Invitation::with('settings')->where('slug', $slug)->first();
        if (!$invitation) abort(404);

        $settings = [];
        foreach ($invitation->settings as $s) {
            $settings[$s->key_name] = $s->key_value;
        }

        $savedToken = $settings['checkin_token'] ?? '';
        if (!$savedToken || !hash_equals((string) $savedToken, (string) $token)) {
            abort(403, 'Link tidak valid.');
        }

        $bride = !empty($settings['bride_nickname']) ? $settings['bride_nickname'] : (!empty($settings['bride_name']) ? $settings['bride_name'] : 'Mempelai Wanita');
        $groom = !empty($settings['groom_nickname']) ? $settings['groom_nickname'] : (!empty($settings['groom_name']) ? $settings['groom_name'] : 'Mempelai Pria');

        return view('front.checkin', [
            'inv_id' => $invitation->id,
            'title' => $groom . ' & ' . $bride,
            // Zona waktu acara dari Data Utama (WIB/WITA/WIT); dipakai
            // halaman check-in untuk menampilkan jam, bukan hardcode WIB.
            'event_timezone' => $settings['wedding_timezone'] ?? 'WIB',
            // Tema undangan + tanggal acara + slug/token (untuk tautan Mode
            // Monitor) — halaman check-in mengikuti palet tema yang dipilih.
            'theme' => $settings['theme'] ?? 'default',
            'event_date' => $settings['wedding_date'] ?? ($settings['reception_date'] ?? ''),
            'slug' => $slug,
            'token' => $token,
        ]);
    }

    /**
     * Halaman monitor check-in read-only untuk pemilik acara.
     * URL: /checkin/{slug}/{token}/monitor — token sama dengan halaman
     * panitia; tanpa scanner dan tanpa tombol check-in, hanya memantau
     * statistik via polling /api/checkin_stats.
     */
    public function checkinMonitorPage($slug, $token)
    {
        $invitation = Invitation::with('settings')->where('slug', $slug)->first();
        if (!$invitation) abort(404);

        $settings = [];
        foreach ($invitation->settings as $s) {
            $settings[$s->key_name] = $s->key_value;
        }

        $savedToken = $settings['checkin_token'] ?? '';
        if (!$savedToken || !hash_equals((string) $savedToken, (string) $token)) {
            abort(403, 'Link tidak valid.');
        }

        $bride = !empty($settings['bride_nickname']) ? $settings['bride_nickname'] : (!empty($settings['bride_name']) ? $settings['bride_name'] : 'Mempelai Wanita');
        $groom = !empty($settings['groom_nickname']) ? $settings['groom_nickname'] : (!empty($settings['groom_name']) ? $settings['groom_name'] : 'Mempelai Pria');

        // Foto slideshow latar welcome screen: foto galeri dulu
        // (urut sort_order), fallback foto preview + mempelai bila kosong.
        $galleryQuery = $invitation->galleries();
        if (Schema::hasColumn('galleries', 'sort_order')) {
            $galleryQuery->orderBy('sort_order', 'ASC');
        }
        $monPhotos = $galleryQuery->orderBy('created_at', 'ASC')->pluck('image_path')->toArray();
        if (empty($monPhotos)) {
            $monPhotos = array_values(array_filter([
                $settings['og_preview_photo'] ?? '',
                $settings['bride_photo'] ?? '',
                $settings['groom_photo'] ?? '',
            ]));
        }

        return view('front.checkin-monitor', [
            'inv_id' => $invitation->id,
            'title' => $groom . ' & ' . $bride,
            'event_timezone' => $settings['wedding_timezone'] ?? 'WIB',
            'theme' => $settings['theme'] ?? 'default',
            'event_date' => $settings['wedding_date'] ?? ($settings['reception_date'] ?? ''),
            'slug' => $slug,
            'token' => $token,
            // Foto slideshow latar welcome screen (foto galeri, fallback preview + mempelai).
            'photos' => $monPhotos,
        ]);
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
            'groom' => $groom,
            'theme' => $settings['theme'] ?? 'default'
        ]);
    }
}
