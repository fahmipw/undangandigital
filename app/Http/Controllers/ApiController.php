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
            'no_hp' => $no_hp
        ]);

        return response()->json([
            'success' => true,
            'data' => $guest
        ]);
    }
}
