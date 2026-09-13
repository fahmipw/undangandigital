<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Models\Guest;
use App\Models\Gallery;
use App\Models\Story;
use App\Models\Music;
use App\Models\Setting;
use App\Models\Rsvp;
use App\Models\Ucapan;
use Illuminate\Support\Facades\Storage;

class LegacyApiController extends Controller
{
    public function adminApi(Request $request)
    {
        if (!auth()->guard('admin')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $json = json_decode($request->getContent(), true) ?? [];
        if (!empty($json)) {
            $request->merge($json);
        }

        $action = $request->query('action', '') ?: $request->input('action', '');
        $inv_id = $request->query('inv_id', 1) ?: $request->input('inv_id', 1);

        if (!$action) {
            return response()->json(['success' => false, 'message' => 'Action parameter required'], 400);
        }

        switch ($action) {
            case 'get_invitations':
                return response()->json([
                    'success' => true,
                    'data' => Invitation::orderBy('created_at', 'DESC')->get()
                ]);

            case 'add_invitation':
                $inv = Invitation::create([
                    'slug' => $request->input('slug'),
                    'title' => $request->input('title')
                ]);
                return response()->json(['success' => true, 'id' => $inv->id]);

            case 'delete_invitation':
                $id = $request->query('id');
                if ($id == 1) {
                    return response()->json(['success' => false, 'message' => 'Undangan utama tidak dapat dihapus'], 403);
                }
                Invitation::destroy($id);
                return response()->json(['success' => true]);

            case 'get_settings':
                $settings = Setting::where('invitation_id', $inv_id)->get();
                $data = [];
                foreach ($settings as $s) {
                    $data[$s->key_name] = $s->key_value;
                }
                return response()->json(['success' => true, 'data' => $data]);

            case 'update_settings':
                $postData = $request->all();
                unset($postData['action'], $postData['inv_id']);
                
                $newSlug = null;
                if (!empty($postData['bride_nickname']) && !empty($postData['groom_nickname'])) {
                    $title = $postData['bride_nickname'] . ' & ' . $postData['groom_nickname'];
                    $slug = \Illuminate\Support\Str::slug($title);
                    
                    // pastikan unik
                    $count = \App\Models\Invitation::where('slug', $slug)->where('id', '!=', $inv_id)->count();
                    if ($count > 0) {
                        $slug = $slug . '-' . time();
                    }
                    
                    \App\Models\Invitation::where('id', $inv_id)->update([
                        'title' => $title,
                        'slug' => $slug
                    ]);
                    $newSlug = $slug;
                }
                
                foreach ($postData as $key => $value) {
                    \Illuminate\Support\Facades\DB::table('settings')->updateOrInsert(
                        ['invitation_id' => $inv_id, 'key_name' => $key],
                        ['key_value' => $value]
                    );
                }
                return response()->json(['success' => true, 'new_slug' => $newSlug]);

            case 'get_guests':
                return response()->json([
                    'success' => true,
                    'data' => Guest::where('invitation_id', $inv_id)->orderBy('nama', 'ASC')->get()
                ]);

            case 'add_guest':
                Guest::create([
                    'invitation_id' => $inv_id,
                    'nama' => $request->input('nama'),
                    'no_hp' => $request->input('no_hp'),
                    'slug' => urlencode($request->input('nama'))
                ]);
                return response()->json(['success' => true]);

            case 'bulk_add_guests':
                $names = $request->input('names', []);
                foreach ($names as $line) {
                    if (trim($line) === '') continue;
                    $parts = explode(',', $line);
                    $nama = trim($parts[0]);
                    $no_hp = isset($parts[1]) ? trim($parts[1]) : null;
                    Guest::create([
                        'invitation_id' => $inv_id,
                        'nama' => $nama,
                        'no_hp' => $no_hp,
                        'slug' => urlencode($nama)
                    ]);
                }
                return response()->json(['success' => true]);

            case 'delete_guest':
                Guest::destroy($request->query('id'));
                return response()->json(['success' => true]);

            case 'get_rsvp':
                return response()->json([
                    'success' => true,
                    'data' => Rsvp::where('invitation_id', $inv_id)->orderBy('created_at', 'DESC')->get()
                ]);

            case 'get_ucapan':
                return response()->json([
                    'success' => true,
                    'data' => Ucapan::where('invitation_id', $inv_id)->orderBy('created_at', 'DESC')->get()
                ]);

            case 'delete_rsvp':
                Rsvp::destroy($request->query('id'));
                return response()->json(['success' => true]);

            case 'delete_ucapan':
                Ucapan::destroy($request->query('id'));
                return response()->json(['success' => true]);

            case 'update_rsvp':
                $rsvp = Rsvp::find($request->input('id'));
                if ($rsvp) {
                    $rsvp->update([
                        'nama' => $request->input('nama'),
                        'jumlah_tamu' => $request->input('jumlah_tamu'),
                        'status' => $request->input('status'),
                        'alasan' => $request->input('alasan')
                    ]);
                }
                return response()->json(['success' => true]);

            case 'update_ucapan':
                $ucapan = Ucapan::find($request->input('id'));
                if ($ucapan) {
                    $ucapan->update([
                        'nama' => $request->input('nama'),
                        'pesan' => $request->input('pesan')
                    ]);
                }
                return response()->json(['success' => true]);

            case 'update_guest':
                $guest = Guest::find($request->input('id'));
                if ($guest) {
                    $guest->update([
                        'nama' => $request->input('nama'),
                        'no_hp' => $request->input('no_hp'),
                        'slug' => urlencode($request->input('nama'))
                    ]);
                }
                return response()->json(['success' => true]);

            case 'get_gallery':
                return response()->json([
                    'success' => true,
                    'data' => Gallery::where('invitation_id', $inv_id)->orderBy('created_at', 'ASC')->get()
                ]);

            case 'upload_gallery':
                if (!$request->hasFile('image')) {
                    return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
                }
                $filename = $this->convertToWebp($request->file('image'), public_path('uploads'), 'img_');
                Gallery::create([
                    'invitation_id' => $inv_id,
                    'image_path' => 'uploads/' . $filename
                ]);
                return response()->json(['success' => true, 'path' => 'uploads/' . $filename]);

            case 'delete_gallery':
                $id = $request->query('id');
                $gallery = Gallery::find($id);
                if ($gallery) {
                    $filePath = public_path($gallery->image_path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $gallery->delete();
                }
                return response()->json(['success' => true]);

            case 'get_music':
                return response()->json([
                    'success' => true,
                    'data' => Music::where('invitation_id', $inv_id)->orderBy('created_at', 'DESC')->get()
                ]);

            case 'upload_music':
                if (!$request->hasFile('music')) {
                    return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
                }
                $file = $request->file('music');
                
                $allowedTypes = ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg', 'audio/m4a'];
                if (!in_array($file->getMimeType(), $allowedTypes) && !in_array($file->getClientMimeType(), $allowedTypes)) {
                    return response()->json(['success' => false, 'message' => 'Tipe file tidak didukung.'], 400);
                }
                
                if ($file->getSize() > 10 * 1024 * 1024) {
                    return response()->json(['success' => false, 'message' => 'Ukuran file maksimal 10MB'], 400);
                }

                $filename = uniqid('music_') . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);

                Music::where('invitation_id', $inv_id)->update(['is_active' => 0]);
                
                Music::create([
                    'invitation_id' => $inv_id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => 'uploads/' . $filename,
                    'file_size' => $file->getSize(),
                    'is_active' => 1
                ]);
                
                return response()->json(['success' => true, 'path' => 'uploads/' . $filename]);

            case 'toggle_active_music':
                $id = $request->input('id') ?? $request->query('id');
                $music = Music::find($id);
                if (!$music) {
                    return response()->json(['success' => false, 'message' => 'Musik tidak ditemukan'], 404);
                }
                if ($music->is_active) {
                    $music->update(['is_active' => 0]);
                } else {
                    Music::where('invitation_id', $music->invitation_id)->update(['is_active' => 0]);
                    $music->update(['is_active' => 1]);
                }
                return response()->json(['success' => true]);

            case 'delete_music':
                $id = $request->input('id') ?? $request->query('id');
                $music = Music::find($id);
                if ($music) {
                    $filePath = public_path($music->file_path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $music->delete();
                }
                return response()->json(['success' => true]);

            case 'upload_bank_logo':
                if (!$request->hasFile('image')) {
                    return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
                }
                $file = $request->file('image');
                
                if ($file->getSize() > 2 * 1024 * 1024) {
                    return response()->json(['success' => false, 'message' => 'Ukuran file maksimal 2MB'], 400);
                }
                
                $filename = $this->convertToWebp($file, public_path('uploads'), 'bank_logo_');
                return response()->json(['success' => true, 'path' => 'uploads/' . $filename]);

            case 'upload_profile_photo':
                if (!$request->hasFile('image')) {
                    return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
                }
                $file = $request->file('image');
                if ($file->getSize() > 5 * 1024 * 1024) {
                    return response()->json(['success' => false, 'message' => 'Ukuran file maksimal 5MB'], 400);
                }
                $filename = $this->convertToWebp($file, public_path('uploads'), 'profile_');
                return response()->json(['success' => true, 'path' => 'uploads/' . $filename]);

            case 'get_stories':
                return response()->json([
                    'success' => true,
                    'data' => Story::where('invitation_id', $inv_id)->orderBy('id', 'ASC')->get()
                ]);

            case 'add_story':
                $story = Story::create([
                    'invitation_id' => $inv_id,
                    'tahun' => $request->input('tahun'),
                    'icon' => $request->input('icon', 'favorite'),
                    'judul' => $request->input('judul'),
                    'isi' => $request->input('isi')
                ]);
                return response()->json(['success' => true, 'id' => $story->id]);

            case 'update_story':
                $story = Story::find($request->input('id'));
                if ($story) {
                    $story->update([
                        'tahun' => $request->input('tahun'),
                        'icon' => $request->input('icon', 'favorite'),
                        'judul' => $request->input('judul'),
                        'isi' => $request->input('isi')
                    ]);
                }
                return response()->json(['success' => true]);

            case 'delete_story':
                $id = $request->input('id') ?? $request->query('id');
                Story::destroy($id);
                return response()->json(['success' => true]);

            default:
                return response()->json(['success' => false, 'message' => 'Action not found'], 404);
        }
    }

    private function convertToWebp($file, $destinationPath, $prefix = 'img_')
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();
        
        if (!function_exists('imagewebp') || !in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $filename = uniqid($prefix) . '.' . $extension;
            $file->move($destinationPath, $filename);
            return $filename;
        }

        if ($extension === 'webp') {
            $filename = uniqid($prefix) . '.webp';
            $file->move($destinationPath, $filename);
            return $filename;
        }

        $image = false;
        if (in_array($extension, ['jpg', 'jpeg'])) {
            $image = @imagecreatefromjpeg($path);
        } elseif ($extension === 'png') {
            $image = @imagecreatefrompng($path);
        } elseif ($extension === 'gif') {
            $image = @imagecreatefromgif($path);
        }

        if ($image !== false) {
            if ($extension === 'png' || $extension === 'gif') {
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            }
            $filename = uniqid($prefix) . '.webp';
            $destination = rtrim($destinationPath, '/') . '/' . $filename;
            $success = imagewebp($image, $destination, 80);
            imagedestroy($image);
            
            if ($success) {
                return $filename;
            }
        }

        $filename = uniqid($prefix) . '.' . $extension;
        $file->move($destinationPath, $filename);
        return $filename;
    }
}
