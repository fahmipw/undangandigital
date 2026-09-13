<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Default Admin
        DB::table('admins')->insert([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Default Invitation
        DB::table('invitations')->insert([
            'id' => 1,
            'slug' => 'elvy-rokim',
            'title' => 'Elvy & Rokim Wedding',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Default Settings
        $settings = [
            ['invitation_id' => 1, 'key_name' => 'groom_name', 'key_value' => 'Mukamat Abdul Rokim'],
            ['invitation_id' => 1, 'key_name' => 'bride_name', 'key_value' => 'Elvy Nur Fauziyah'],
            ['invitation_id' => 1, 'key_name' => 'event_location_mode', 'key_value' => 'same'],
            ['invitation_id' => 1, 'key_name' => 'wedding_date', 'key_value' => '2026-06-09'],
            ['invitation_id' => 1, 'key_name' => 'wedding_time_start', 'key_value' => '08:00'],
            ['invitation_id' => 1, 'key_name' => 'wedding_time_end', 'key_value' => '10:00'],
            ['invitation_id' => 1, 'key_name' => 'wedding_location', 'key_value' => 'Kediaman Mempelai Wanita'],
            ['invitation_id' => 1, 'key_name' => 'wedding_map_link', 'key_value' => ''],
            ['invitation_id' => 1, 'key_name' => 'reception_date', 'key_value' => '2026-06-11'],
            ['invitation_id' => 1, 'key_name' => 'reception_time_start', 'key_value' => '11:00'],
            ['invitation_id' => 1, 'key_name' => 'reception_time_end', 'key_value' => 'Selesai'],
            ['invitation_id' => 1, 'key_name' => 'reception_location', 'key_value' => 'Kediaman Mempelai Wanita'],
            ['invitation_id' => 1, 'key_name' => 'reception_map_link', 'key_value' => ''],
            ['invitation_id' => 1, 'key_name' => 'gift_bank', 'key_value' => 'BANK BRI'],
            ['invitation_id' => 1, 'key_name' => 'gift_account', 'key_value' => '00550 11527 30502'],
            ['invitation_id' => 1, 'key_name' => 'gift_owner', 'key_value' => 'Elvy Nur Fauziyah'],
            ['invitation_id' => 1, 'key_name' => 'gift_address', 'key_value' => 'Ds. Pagerwojo Dsn. Pagerwojo Kec. Perak Kab. Jombang RT/RW. 05/04'],
            ['invitation_id' => 1, 'key_name' => 'gift_bank_logo', 'key_value' => ''],
            ['invitation_id' => 1, 'key_name' => 'music_volume', 'key_value' => '50'],
            ['invitation_id' => 1, 'key_name' => 'music_autoplay', 'key_value' => '1'],
        ];
        DB::table('settings')->insert($settings);

        // Default Stories
        $stories = [
            [
                'id' => 1,
                'invitation_id' => 1,
                'tahun' => '2025',
                'judul' => 'Pertemuan Pertama',
                'isi' => 'Kami bermula dari sebuah DM sederhana di Instagram. Pesan singkat yang tak disangka menjadi awal perjalanan dua hati yang saling menemukan. Dari obrolan ringan setiap hari, kami belajar saling mengenal, memahami, hingga tumbuh rasa nyaman yang perlahan berubah menjadi cinta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'invitation_id' => 1,
                'tahun' => '2026',
                'judul' => 'Janji Suci',
                'isi' => 'Mengukir janji untuk saling mendukung dalam suka dan duka, melangkah bersama menuju masa depan yang cerah.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'invitation_id' => 1,
                'tahun' => 'diamond',
                'judul' => 'Hari Kemenangan',
                'isi' => 'Menyatukan dua keluarga besar dalam ikatan suci pernikahan yang langgeng, selamanya.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('stories')->insert($stories);
    }
}
