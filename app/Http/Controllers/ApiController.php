<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rsvp;
use App\Models\Ucapan;
use App\Models\Music;
use App\Models\Setting;

class ApiController extends Controller
{
    public function rsvp(Request $request)
    {
        $json = json_decode($request->getContent(), true) ?? [];
        if (!empty($json)) {
            $request->merge($json);
        }

        if ($request->isMethod('post')) {
            $invitation_id = $request->input('invitation_id', 1);
            $nama = trim($request->input('nama', ''));
            $jumlah_tamu = (int) $request->input('jumlah_tamu', 1);
            $status = trim($request->input('status', ''));
            $alasan = trim($request->input('alasan', ''));

            if ($nama === '') {
                return response()->json(['success' => false, 'message' => 'Nama tidak boleh kosong.'], 422);
            }
            if (!in_array($status, ['hadir', 'tidak'])) {
                return response()->json(['success' => false, 'message' => 'Status tidak valid.'], 422);
            }
            if ($jumlah_tamu < 1 || $jumlah_tamu > 20) {
                return response()->json(['success' => false, 'message' => 'Jumlah tamu tidak valid.'], 422);
            }
            if ($status === 'hadir') $alasan = '';

            $rsvp = Rsvp::create([
                'invitation_id' => $invitation_id,
                'nama' => $nama,
                'jumlah_tamu' => $jumlah_tamu,
                'status' => $status,
                'alasan' => $alasan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Konfirmasi kehadiran berhasil disimpan.',
                'id' => $rsvp->id
            ]);
        }

        if ($request->isMethod('get')) {
            $inv_id = $request->query('inv_id', 1);
            $rows = Rsvp::where('invitation_id', $inv_id)->orderBy('created_at', 'DESC')->get();
            return response()->json(['success' => true, 'data' => $rows]);
        }

        return response()->json(['success' => false, 'message' => 'Method not allowed.'], 405);
    }

    /**
     * QR Check-in tamu (dipakai halaman scanner panitia).
     * POST {invitation_id, code}
     */
    public function checkin(Request $request)
    {
        $json = json_decode($request->getContent(), true) ?? [];
        if (!empty($json)) {
            $request->merge($json);
        }

        $invitation_id = $request->input('invitation_id');
        $code = strtoupper(trim($request->input('code', '')));

        $guest = \App\Models\Guest::where('invitation_id', $invitation_id)
            ->where('qr_code', $code)->first();

        if (!$guest) {
            return response()->json(['success' => false, 'message' => 'Kode QR tidak ditemukan.'], 404);
        }

        $already = !empty($guest->checked_in_at);
        if (!$already) {
            $guest->checked_in_at = now();
            $guest->save();
            $guest->refresh();
        }

        return response()->json([
            'success' => true,
            'nama' => $guest->nama,
            'already' => $already,
            'checked_in_at' => $guest->checked_in_at ? (string) $guest->checked_in_at : null,
        ]);
    }

    /**
     * Statistik check-in untuk dashboard admin.
     * GET ?inv_id=
     */
    public function checkinStats(Request $request)
    {
        $inv_id = $request->query('inv_id', $request->input('invitation_id'));
        $total = \App\Models\Guest::where('invitation_id', $inv_id)->count();
        $checked = \App\Models\Guest::where('invitation_id', $inv_id)->whereNotNull('checked_in_at')->count();
        $recent = \App\Models\Guest::where('invitation_id', $inv_id)->whereNotNull('checked_in_at')
            ->orderBy('checked_in_at', 'DESC')->limit(20)->get(['nama', 'checked_in_at']);

        return response()->json([
            'success' => true,
            'total' => $total,
            'checked_in' => $checked,
            'recent' => $recent,
        ]);
    }

    public function ucapan(Request $request)
    {
        $json = json_decode($request->getContent(), true) ?? [];
        if (!empty($json)) {
            $request->merge($json);
        }

        if ($request->isMethod('post')) {
            $invitation_id = $request->input('invitation_id', 1);
            $nama = trim($request->input('nama', ''));
            $pesan = trim($request->input('pesan', ''));

            if ($nama === '' || $pesan === '') {
                return response()->json(['success' => false, 'message' => 'Nama dan pesan tidak boleh kosong.'], 422);
            }

            $ucapan = Ucapan::create([
                'invitation_id' => $invitation_id,
                'nama' => $nama,
                'pesan' => $pesan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ucapan berhasil dikirim!',
                'data' => $ucapan
            ]);
        }

        if ($request->isMethod('get')) {
            $inv_id = $request->query('inv_id', 1);
            $rows = Ucapan::where('invitation_id', $inv_id)->orderBy('created_at', 'DESC')->get();
            return response()->json(['success' => true, 'data' => $rows]);
        }

        return response()->json(['success' => false, 'message' => 'Method not allowed.'], 405);
    }

    public function music(Request $request)
    {
        $inv_id = $request->query('inv_id', 1);
        $music = Music::where('invitation_id', $inv_id)->where('is_active', 1)->first();
        
        $settingsRaw = Setting::where('invitation_id', $inv_id)->whereIn('key_name', ['music_volume', 'music_autoplay'])->get();
        $settings = ['music_volume' => 50, 'music_autoplay' => 1];
        foreach ($settingsRaw as $s) {
            $settings[$s->key_name] = $s->key_value;
        }

        if ($music) {
            return response()->json([
                'success' => true,
                'music' => [
                    'file_path' => $music->file_path,
                    'file_name' => $music->file_name,
                    'volume' => intval($settings['music_volume']) / 100,
                    'autoplay' => intval($settings['music_autoplay']) === 1
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'music' => [
                'file_path' => 'lagu.mp3',
                'file_name' => 'Default Music',
                'volume' => intval($settings['music_volume']) / 100,
                'autoplay' => intval($settings['music_autoplay']) === 1
            ]
        ]);
    }

    public function getSettings(Request $request)
    {
        $inv_id = $request->query('inv_id', 1);
        $settings = Setting::where('invitation_id', $inv_id)->get();
        $data = [];
        foreach ($settings as $s) {
            $data[$s->key_name] = $s->key_value;
        }
        return response()->json(['success' => true, 'data' => $data]);
    }
    public function generateGuest(Request $request)
    {
        $json = json_decode($request->getContent(), true) ?? [];
        if (!empty($json)) {
            $request->merge($json);
        }

        $invitation_id = $request->input('invitation_id', 1);
        $nama = trim($request->input('nama', ''));
        $no_hp = trim($request->input('no_hp', ''));

        if ($nama === '') {
            return response()->json(['success' => false, 'message' => 'Nama tidak boleh kosong.'], 422);
        }

        $slug = \Illuminate\Support\Str::slug($nama);
        
        // pastikan slug unik
        $count = \App\Models\Guest::where('invitation_id', $invitation_id)->where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }

        $guest = \App\Models\Guest::create([
            'invitation_id' => $invitation_id,
            'nama' => $nama,
            'slug' => $slug,
            'no_hp' => $no_hp,
            'qr_code' => \App\Models\Guest::newQrCode($invitation_id)
        ]);

        return response()->json([
            'success' => true,
            'data' => $guest
        ]);
    }

    /**
     * Cari tamu yang sudah ada (untuk Link Generator, poin "cari tamu").
     * Sengaja dibatasi: minimal 3 karakter, maksimal 5 hasil, dan TIDAK
     * mengembalikan no_hp / qr_code — hanya nama + slug pembentuk link.
     */
    public function searchGuest(Request $request)
    {
        $invitation_id = $request->query('invitation_id', 1);
        $q = trim($request->query('q', ''));
        if (mb_strlen($q) < 3) {
            return response()->json(['success' => true, 'data' => []]);
        }
        $rows = \App\Models\Guest::where('invitation_id', $invitation_id)
            ->where('nama', 'LIKE', '%' . $q . '%')
            ->orderBy('nama', 'ASC')->limit(5)
            ->get(['nama', 'slug']);
        return response()->json(['success' => true, 'data' => $rows]);
    }

    /**
     * Generate massal untuk Link Generator: satu request, banyak tamu.
     * Dibatasi 100 tamu per request. Nama yang persis sama (abaikan
     * huruf besar/kecil) dengan tamu yang sudah ada TIDAK dibuat ulang,
     * melainkan dikembalikan dengan existed=true agar tidak dobel.
     */
    public function generateGuestsBulk(Request $request)
    {
        $json = json_decode($request->getContent(), true) ?? [];
        if (!empty($json)) {
            $request->merge($json);
        }

        $invitation_id = $request->input('invitation_id', 1);
        $guests = $request->input('guests', []);
        if (!is_array($guests) || empty($guests)) {
            return response()->json(['success' => false, 'message' => 'Daftar tamu kosong.'], 422);
        }
        if (count($guests) > 100) {
            return response()->json(['success' => false, 'message' => 'Maksimal 100 tamu per generate massal.'], 422);
        }

        $existing = \App\Models\Guest::where('invitation_id', $invitation_id)->get(['nama', 'slug']);
        $existingByName = [];
        foreach ($existing as $e) {
            $existingByName[mb_strtolower(trim($e->nama))] = $e;
        }

        $results = [];
        $seenInBatch = [];
        foreach ($guests as $g) {
            $nama = trim(is_array($g) ? ($g['nama'] ?? '') : '');
            if ($nama === '') continue;
            $key = mb_strtolower($nama);
            if (isset($seenInBatch[$key])) continue;
            $seenInBatch[$key] = true;

            $no_hp = trim(is_array($g) ? ($g['no_hp'] ?? '') : '');
            $no_hp = preg_replace('/\D/', '', $no_hp);
            if (substr($no_hp, 0, 1) === '0') {
                $no_hp = '62' . substr($no_hp, 1);
            }

            if (isset($existingByName[$key])) {
                $results[] = ['nama' => $existingByName[$key]->nama, 'slug' => $existingByName[$key]->slug, 'no_hp' => $no_hp, 'existed' => true];
                continue;
            }

            $slug = \Illuminate\Support\Str::slug($nama);
            if ($slug === '') $slug = 'tamu';
            $count = \App\Models\Guest::where('invitation_id', $invitation_id)->where('slug', $slug)->count();
            if ($count > 0) {
                $slug = $slug . '-' . time() . '-' . count($results);
            }

            $guest = \App\Models\Guest::create([
                'invitation_id' => $invitation_id,
                'nama' => $nama,
                'slug' => $slug,
                'no_hp' => $no_hp,
                'qr_code' => \App\Models\Guest::newQrCode($invitation_id)
            ]);
            $existingByName[$key] = $guest;
            $results[] = ['nama' => $guest->nama, 'slug' => $guest->slug, 'no_hp' => $no_hp, 'existed' => false];
        }

        return response()->json(['success' => true, 'data' => $results]);
    }
}
