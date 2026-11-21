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
     * Otorisasi endpoint check-in: token dari URL halaman panitia harus
     * cocok dengan settings.checkin_token (hash_equals), ATAU pemanggil
     * adalah admin yang sudah login (tab check-in admin memanggil
     * checkin_stats tanpa token).
     */
    private function checkinAuthorized($inv_id, $token)
    {
        if (auth()->guard('admin')->check()) return true;
        if (!$inv_id || $token === null || $token === '') return false;
        $saved = Setting::where('invitation_id', $inv_id)->where('key_name', 'checkin_token')->value('key_value');
        if (!$saved) return false;
        return hash_equals((string) $saved, (string) $token);
    }

    /**
     * QR Check-in tamu (dipakai halaman scanner panitia).
     * POST {invitation_id, code, token, companions?, operator?}
     * Token = checkin_token dari URL halaman panitia; admin yang sudah
     * login juga diizinkan (tab check-in admin memanggil endpoint ini
     * tanpa token). companions (0-20) + operator (nama petugas) hanya
     * dicatat pada check-in pertama; scan ulang tidak menimpa data.
     */
    public function checkin(Request $request)
    {
        $json = json_decode($request->getContent(), true) ?? [];
        if (!empty($json)) {
            $request->merge($json);
        }

        $invitation_id = $request->input('invitation_id');
        $code = strtoupper(trim($request->input('code', '')));

        if (!$this->checkinAuthorized($invitation_id, $request->input('token', ''))) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid.'], 403);
        }

        $guest = \App\Models\Guest::where('invitation_id', $invitation_id)
            ->where('qr_code', $code)->first();

        if (!$guest) {
            return response()->json(['success' => false, 'message' => 'Kode QR tidak ditemukan.'], 404);
        }

        $already = !empty($guest->checked_in_at);
        // Pendamping (opsional, default 0) + nama petugas (opsional) hanya
        // dicatat pada check-in pertama; scan ulang tidak menimpa data.
        $companions = max(0, min(20, (int) $request->input('companions', 0)));
        $operator = trim((string) $request->input('operator', ''));
        if (!$already) {
            $guest->checked_in_at = now();
            $guest->companions = $companions;
            if ($operator !== '') $guest->checked_in_by = mb_substr($operator, 0, 100);
            $guest->save();
            $guest->refresh();
        }

        return response()->json([
            'success' => true,
            'nama' => $guest->nama,
            'already' => $already,
            // ISO berzona UTC (bukan string polos) agar frontend menampilkannya
            // dalam WIB, bukan waktu UTC mentah — akar masalah yang sama
            // seperti waktu kirim WA sebelumnya.
            'checked_in_at' => $this->waIso($guest->checked_in_at),
            'companions' => (int) ($guest->companions ?? 0),
            'checked_in_by' => $guest->checked_in_by,
        ]);
    }

    /**
     * Statistik check-in untuk dashboard admin.
     * GET ?inv_id=&token= — token wajib kecuali pemanggil admin login.
     */
    public function checkinStats(Request $request)
    {
        $inv_id = $request->query('inv_id', $request->input('invitation_id'));
        if (!$this->checkinAuthorized($inv_id, $request->query('token', $request->input('token', '')))) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid.'], 403);
        }
        $total = \App\Models\Guest::where('invitation_id', $inv_id)->count();
        $checked = \App\Models\Guest::where('invitation_id', $inv_id)->whereNotNull('checked_in_at')->count();
        // Total orang yang benar-benar hadir = tamu + pendamping mereka.
        $totalPeople = $this->checkinPeopleTotal($inv_id);
        $recentCols = ['nama', 'checked_in_at', 'companions', 'checked_in_by'];
        if (\Illuminate\Support\Facades\Schema::hasColumn('guests', 'category')) $recentCols[] = 'category';
        $recent = \App\Models\Guest::where('invitation_id', $inv_id)->whereNotNull('checked_in_at')
            ->orderBy('checked_in_at', 'DESC')->limit(20)->get($recentCols)
            ->map(function ($g) {
                return [
                    'nama' => $g->nama,
                    'checked_in_at' => $this->waIso($g->checked_in_at),
                    'companions' => (int) ($g->companions ?? 0),
                    'checked_in_by' => $g->checked_in_by,
                    'category' => isset($g->category) ? $g->category : null,
                ];
            })->values();

        return response()->json([
            'success' => true,
            'total' => $total,
            'checked_in' => $checked,
            'total_people' => $totalPeople,
            'recent' => $recent,
        ]);
    }

    /**
     * Daftar lengkap tamu untuk pencarian + daftar belum hadir di halaman
     * check-in panitia. GET ?inv_id=&token= — dimuat sekali saat load,
     * lalu difilter client-side. Token wajib kecuali pemanggil admin login.
     * qr_code ikut dikembalikan agar tombol "Check-in" per baris bisa
     * memakai jalur /api/checkin yang sama (dengan token yang sama).
     */
    public function checkinGuests(Request $request)
    {
        $inv_id = $request->query('inv_id', $request->input('invitation_id'));
        if (!$this->checkinAuthorized($inv_id, $request->query('token', $request->input('token', '')))) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid.'], 403);
        }
        $rows = \App\Models\Guest::where('invitation_id', $inv_id)
            ->orderBy('nama', 'ASC')->get(['nama', 'qr_code', 'checked_in_at', 'companions', 'checked_in_by'])
            ->map(function ($g) {
                return [
                    'nama' => $g->nama,
                    'qr_code' => $g->qr_code,
                    'checked_in_at' => $this->waIso($g->checked_in_at),
                    'companions' => (int) ($g->companions ?? 0),
                    'checked_in_by' => $g->checked_in_by,
                ];
            })->values();

        return response()->json([
            'success' => true,
            'guests' => $rows,
        ]);
    }

    /** Total orang hadir (tamu + pendamping) untuk satu undangan. */
    private function checkinPeopleTotal($inv_id)
    {
        $checked = \App\Models\Guest::where('invitation_id', $inv_id)->whereNotNull('checked_in_at')->count();
        return $checked + (int) \App\Models\Guest::where('invitation_id', $inv_id)
            ->whereNotNull('checked_in_at')->sum('companions');
    }

    /**
     * Simpan jumlah pendamping untuk tamu yang sudah check-in.
     * POST {invitation_id, code, companions, token, operator?}
     * Dipakai stepper di overlay sukses halaman panitia; token wajib
     * kecuali pemanggil admin login.
     */
    public function checkinCompanions(Request $request)
    {
        $json = json_decode($request->getContent(), true) ?? [];
        if (!empty($json)) {
            $request->merge($json);
        }

        $invitation_id = $request->input('invitation_id');
        $code = strtoupper(trim($request->input('code', '')));

        if (!$this->checkinAuthorized($invitation_id, $request->input('token', ''))) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid.'], 403);
        }

        $guest = \App\Models\Guest::where('invitation_id', $invitation_id)
            ->where('qr_code', $code)->first();

        if (!$guest) {
            return response()->json(['success' => false, 'message' => 'Kode QR tidak ditemukan.'], 404);
        }
        if (empty($guest->checked_in_at)) {
            return response()->json(['success' => false, 'message' => 'Tamu belum check-in.'], 422);
        }

        $guest->companions = max(0, min(20, (int) $request->input('companions', 0)));
        // Petugas diisi bila belum tercatat (check-in pertama yang
        // menyertakan nama petugas).
        $operator = trim((string) $request->input('operator', ''));
        if ($operator !== '' && empty($guest->checked_in_by)) {
            $guest->checked_in_by = mb_substr($operator, 0, 100);
        }
        $guest->save();

        return response()->json([
            'success' => true,
            'nama' => $guest->nama,
            'companions' => (int) $guest->companions,
            'total_people' => $this->checkinPeopleTotal($invitation_id),
        ]);
    }

    /**
     * Laporan statistik kehadiran untuk tab Check-in admin.
     * GET ?inv_id=&token= — token wajib kecuali pemanggil admin login.
     * Mengembalikan total, hadir, persen, total orang (termasuk
     * pendamping), ringkasan per petugas, daftar belum hadir, dan
     * kedatangan per jam dalam zona waktu acara (Data Utama).
     */
    public function checkinReport(Request $request)
    {
        $inv_id = $request->query('inv_id', $request->input('invitation_id'));
        if (!$this->checkinAuthorized($inv_id, $request->query('token', $request->input('token', '')))) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid.'], 403);
        }
        $total = \App\Models\Guest::where('invitation_id', $inv_id)->count();
        $checked = \App\Models\Guest::where('invitation_id', $inv_id)->whereNotNull('checked_in_at')->count();
        $totalPeople = $this->checkinPeopleTotal($inv_id);

        $byOperator = \App\Models\Guest::where('invitation_id', $inv_id)->whereNotNull('checked_in_at')
            ->selectRaw('checked_in_by as operator, COUNT(*) as count, COALESCE(SUM(companions), 0) as companions')
            ->groupBy('checked_in_by')->orderByDesc('count')->get()
            ->map(function ($r) {
                return [
                    'operator' => $r->operator ?: '(tanpa nama)',
                    'count' => (int) $r->count,
                    'companions' => (int) $r->companions,
                ];
            })->values();

        $pending = \App\Models\Guest::where('invitation_id', $inv_id)->whereNull('checked_in_at')
            ->orderBy('nama', 'ASC')->pluck('nama')->values();

        // Kedatangan per jam dalam zona waktu acara (bukan UTC).
        $tzLabel = Setting::where('invitation_id', $inv_id)->where('key_name', 'wedding_timezone')->value('key_value') ?: 'WIB';
        $iana = ['WIB' => 'Asia/Jakarta', 'WITA' => 'Asia/Makassar', 'WIT' => 'Asia/Jayapura'][$tzLabel] ?? 'Asia/Jakarta';
        $buckets = [];
        $times = \App\Models\Guest::where('invitation_id', $inv_id)->whereNotNull('checked_in_at')->pluck('checked_in_at');
        foreach ($times as $t) {
            $h = \Carbon\Carbon::parse($t, 'UTC')->setTimezone($iana)->format('H:00');
            $buckets[$h] = ($buckets[$h] ?? 0) + 1;
        }
        ksort($buckets);
        $hourly = [];
        foreach ($buckets as $h => $c) $hourly[] = ['hour' => $h, 'count' => $c];

        return response()->json([
            'success' => true,
            'total' => $total,
            'checked_in' => $checked,
            'percent' => $total ? (int) round($checked / $total * 100) : 0,
            'total_people' => $totalPeople,
            'by_operator' => $byOperator,
            'pending_list' => $pending,
            'hourly_buckets' => $hourly,
        ]);
    }

    /**
     * Unduh laporan kehadiran sebagai CSV (dari tab Check-in admin).
     * GET ?inv_id=&token= — token wajib kecuali pemanggil admin login.
     * Kolom: Nama, Status, Jam Check-in (zona acara), Pendamping, Petugas.
     */
    public function checkinExport(Request $request)
    {
        $inv_id = $request->query('inv_id', $request->input('invitation_id'));
        if (!$this->checkinAuthorized($inv_id, $request->query('token', $request->input('token', '')))) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid.'], 403);
        }
        $tzLabel = Setting::where('invitation_id', $inv_id)->where('key_name', 'wedding_timezone')->value('key_value') ?: 'WIB';
        $iana = ['WIB' => 'Asia/Jakarta', 'WITA' => 'Asia/Makassar', 'WIT' => 'Asia/Jayapura'][$tzLabel] ?? 'Asia/Jakarta';
        $rows = \App\Models\Guest::where('invitation_id', $inv_id)->orderBy('nama', 'ASC')
            ->get(['nama', 'checked_in_at', 'companions', 'checked_in_by']);

        $out = fopen('php://temp', 'r+');
        // BOM UTF-8 agar Excel membuka file tanpa karakter rusak.
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, ['Nama', 'Status', 'Jam Check-in', 'Pendamping', 'Petugas']);
        foreach ($rows as $g) {
            $time = $g->checked_in_at
                ? \Carbon\Carbon::parse($g->checked_in_at, 'UTC')->setTimezone($iana)->format('d M Y H:i') . ' ' . $tzLabel
                : '-';
            fputcsv($out, [
                $g->nama,
                $g->checked_in_at ? 'Hadir' : 'Belum',
                $time,
                (int) ($g->companions ?? 0),
                $g->checked_in_by ?: '-',
            ]);
        }
        rewind($out);
        $csv = stream_get_contents($out);
        fclose($out);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="checkin-' . $inv_id . '-' . date('Ymd') . '.csv"',
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
        $category = mb_substr(trim($request->input('category', '')), 0, 50);

        if ($nama === '') {
            return response()->json(['success' => false, 'message' => 'Nama tidak boleh kosong.'], 422);
        }

        $slug = \Illuminate\Support\Str::slug($nama);
        
        // pastikan slug unik
        $count = \App\Models\Guest::where('invitation_id', $invitation_id)->where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }

        $createData = [
            'invitation_id' => $invitation_id,
            'nama' => $nama,
            'slug' => $slug,
            'no_hp' => $no_hp,
            'qr_code' => \App\Models\Guest::newQrCode($invitation_id)
        ];
        if (\Illuminate\Support\Facades\Schema::hasColumn('guests', 'category')) {
            $createData['category'] = $category === '' ? null : $category;
        }
        $guest = \App\Models\Guest::create($createData);

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

        $hasWaCol = \Illuminate\Support\Facades\Schema::hasColumn('guests', 'wa_sent_at');
        $existingCols = ['nama', 'slug'];
        if ($hasWaCol) $existingCols[] = 'wa_sent_at';
        $hasCatCol = \Illuminate\Support\Facades\Schema::hasColumn('guests', 'category');
        if ($hasCatCol) $existingCols[] = 'category';
        $existing = \App\Models\Guest::where('invitation_id', $invitation_id)->get($existingCols);
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
            $category = mb_substr(trim(is_array($g) ? ($g['category'] ?? '') : ''), 0, 50);

            if (isset($existingByName[$key])) {
                $results[] = ['nama' => $existingByName[$key]->nama, 'slug' => $existingByName[$key]->slug, 'no_hp' => $no_hp, 'category' => $hasCatCol ? ($existingByName[$key]->category ?? '') : $category, 'existed' => true, 'wa_sent_at' => $this->waIso($hasWaCol ? $existingByName[$key]->wa_sent_at : null)];
                continue;
            }

            $slug = \Illuminate\Support\Str::slug($nama);
            if ($slug === '') $slug = 'tamu';
            $count = \App\Models\Guest::where('invitation_id', $invitation_id)->where('slug', $slug)->count();
            if ($count > 0) {
                $slug = $slug . '-' . time() . '-' . count($results);
            }

            $createData = [
                'invitation_id' => $invitation_id,
                'nama' => $nama,
                'slug' => $slug,
                'no_hp' => $no_hp,
                'qr_code' => \App\Models\Guest::newQrCode($invitation_id)
            ];
            if ($hasCatCol) {
                $createData['category'] = $category === '' ? null : $category;
            }
            $guest = \App\Models\Guest::create($createData);
            $existingByName[$key] = $guest;
            $results[] = ['nama' => $guest->nama, 'slug' => $guest->slug, 'no_hp' => $no_hp, 'category' => $category, 'existed' => false, 'wa_sent_at' => null];
        }

        return response()->json(['success' => true, 'data' => $results]);
    }

    /**
     * Daftar tamu untuk halaman Link Generator, urutan terbaru dulu.
     * Kolom wa_sent_at hanya diambil bila migrasinya sudah dijalankan,
     * supaya halaman tidak fatal bila Fahmi belum menjalankan migrate.
     */
    public function generatorGuests(Request $request)
    {
        $invitation_id = $request->query('invitation_id', 1);
        $cols = ['id', 'nama', 'slug', 'no_hp'];
        if (\Illuminate\Support\Facades\Schema::hasColumn('guests', 'wa_sent_at')) {
            $cols[] = 'wa_sent_at';
        }
        $hasCatCol = \Illuminate\Support\Facades\Schema::hasColumn('guests', 'category');
        if ($hasCatCol) $cols[] = 'category';
        $rows = \App\Models\Guest::where('invitation_id', $invitation_id)
            ->orderBy('id', 'DESC')->get($cols)
            ->map(function ($g) {
                return ['id' => $g->id, 'nama' => $g->nama, 'slug' => $g->slug, 'no_hp' => $g->no_hp, 'category' => isset($g->category) ? $g->category : null, 'wa_sent_at' => $this->waIso(isset($g->wa_sent_at) ? $g->wa_sent_at : null)];
            })->values();
        return response()->json(['success' => true, 'data' => $rows]);
    }

    /**
     * Tandai tamu sudah / belum dikirim via WhatsApp.
     * Catatan jujur: yang bisa dilacak hanyalah klik tombol Kirim WA
     * (atau penandaan manual) — pengiriman di dalam aplikasi WhatsApp
     * sendiri tidak bisa diverifikasi dari server.
     */
    public function markGuestWa(Request $request)
    {
        $json = json_decode($request->getContent(), true) ?? [];
        if (!empty($json)) {
            $request->merge($json);
        }
        if (!\Illuminate\Support\Facades\Schema::hasColumn('guests', 'wa_sent_at')) {
            return response()->json(['success' => false, 'message' => 'Kolom wa_sent_at belum ada. Jalankan php artisan migrate dulu.'], 422);
        }
        $invitation_id = $request->input('invitation_id', 1);
        $slug = trim($request->input('slug', ''));
        $sent = (bool) $request->input('sent', true);
        if ($slug === '') {
            return response()->json(['success' => false, 'message' => 'Slug tamu kosong.'], 422);
        }
        $updated = \App\Models\Guest::where('invitation_id', $invitation_id)
            ->where('slug', $slug)
            ->update(['wa_sent_at' => $sent ? now() : null]);
        if (!$updated) {
            return response()->json(['success' => false, 'message' => 'Tamu tidak ditemukan.'], 404);
        }
        $guest = \App\Models\Guest::where('invitation_id', $invitation_id)->where('slug', $slug)->first(['wa_sent_at']);
        return response()->json(['success' => true, 'wa_sent_at' => $this->waIso($guest ? $guest->wa_sent_at : null)]);
    }

    /**
     * Edit nama tamu dari halaman Link Generator. Slug (dan link) SENGAJA
     * tidak diubah supaya link yang sudah terkirim tidak rusak. Nama baru
     * yang persis sama dengan tamu lain ditolak agar tidak dobel.
     */
    public function updateGeneratorGuest(Request $request)
    {
        $json = json_decode($request->getContent(), true) ?? [];
        if (!empty($json)) {
            $request->merge($json);
        }
        $invitation_id = $request->input('invitation_id', 1);
        $slug = trim($request->input('slug', ''));
        $nama = trim($request->input('nama', ''));
        $category = mb_substr(trim($request->input('category', '')), 0, 50);
        if ($slug === '' || $nama === '') {
            return response()->json(['success' => false, 'message' => 'Slug dan nama tamu tidak boleh kosong.'], 422);
        }
        $guest = \App\Models\Guest::where('invitation_id', $invitation_id)->where('slug', $slug)->first();
        if (!$guest) {
            return response()->json(['success' => false, 'message' => 'Tamu tidak ditemukan.'], 404);
        }
        $others = \App\Models\Guest::where('invitation_id', $invitation_id)->where('id', '!=', $guest->id)->get(['nama']);
        foreach ($others as $o) {
            if (mb_strtolower(trim($o->nama)) === mb_strtolower($nama)) {
                return response()->json(['success' => false, 'message' => 'Nama tersebut sudah dipakai tamu lain.'], 422);
            }
        }
        $guest->nama = $nama;
        if ($request->has('category') && \Illuminate\Support\Facades\Schema::hasColumn('guests', 'category')) {
            $guest->category = $category === '' ? null : $category;
        }
        $guest->save();
        return response()->json(['success' => true, 'data' => ['id' => $guest->id, 'nama' => $guest->nama, 'slug' => $guest->slug, 'no_hp' => $guest->no_hp, 'category' => $guest->category ?? null, 'wa_sent_at' => $this->waIso($guest->wa_sent_at ?? null)]]);
    }

    /** Hapus tamu dari halaman Link Generator (linknya ikut tidak berlaku). */
    public function deleteGeneratorGuest(Request $request)
    {
        $json = json_decode($request->getContent(), true) ?? [];
        if (!empty($json)) {
            $request->merge($json);
        }
        $invitation_id = $request->input('invitation_id', 1);
        $slug = trim($request->input('slug', ''));
        if ($slug === '') {
            return response()->json(['success' => false, 'message' => 'Slug tamu kosong.'], 422);
        }
        $deleted = \App\Models\Guest::where('invitation_id', $invitation_id)->where('slug', $slug)->delete();
        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Tamu tidak ditemukan.'], 404);
        }
        return response()->json(['success' => true]);
    }

    /**
     * Format wa_sent_at untuk frontend: aplikasi memakai timezone UTC
     * (config/app.php), jadi nilai mentah "08:30:00" adalah UTC, bukan WIB.
     * Kalau dikirim mentah, JS menafsirkannya sebagai waktu lokal dan
     * jamnya meleset 7 jam (tanggal biasanya tetap benar). Karena itu
     * kirim sebagai ISO berzona UTC (+00:00) agar JS mengonversinya
     * dengan benar ke WIB.
     */
    // Helper generik: nilai waktu DB (UTC) -> ISO berzona UTC.
    // Dipakai wa_sent_at maupun checked_in_at agar frontend menampilkannya
    // dalam WIB dengan benar.
    private function waIso($value)
    {
        if (empty($value)) return null;
        try {
            return \Carbon\Carbon::parse($value, 'UTC')->toIso8601String();
        } catch (\Exception $e) {
            return null;
        }
    }
}
