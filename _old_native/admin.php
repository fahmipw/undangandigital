<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard V2 | Elvy & Rokim</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#361f1a',
                        secondary: '#775a19'
                    }
                }
            }
        };
    </script>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&family=Playfair+Display:wght@700&family=Instrument+Serif&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        body {
            background-color: #fbf9f5;
            min-height: 100vh;
        }

        .sidebar {
            background: rgba(251, 249, 245, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(119, 90, 25, 0.1);
        }

        .tab-btn.active {
            background-color: #775a19;
            color: white;
            box-shadow: 0 4px 12px rgba(119, 90, 25, 0.2);
        }

        .data-card {
            background: white;
            border: 1px solid rgba(119, 90, 25, 0.05);
        }

        .btn-theme {
            background-color: #775a19;
            color: white;
            transition: all 0.3s;
        }

        .btn-theme:hover {
            background-color: #4e342e;
            transform: translateY(-1px);
        }

        .btn-theme:active {
            transform: scale(0.98);
        }

        .modal {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .gallery-item:hover .delete-overlay {
            opacity: 1;
        }
    </style>
</head>

<body class="flex">
    <!-- Sidebar -->
    <aside class="sidebar w-64 fixed h-full p-6 flex flex-col hidden md:flex z-50">
        <div class="mb-10 text-center">
            <h2 class="font-display-lg text-xl text-primary mb-4">Admin Panel V2</h2>

            <div class="space-y-3">
                <div class="relative group">
                    <select id="inv-selector" onchange="changeInvitation()"
                        class="w-full bg-secondary/10 border border-secondary/20 rounded-xl px-4 py-3 text-xs font-bold text-primary outline-none appearance-none cursor-pointer">
                    </select>
                    <span
                        class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-secondary pointer-events-none text-sm">expand_more</span>
                </div>

                <div class="flex gap-2">
                    <button onclick="addNewInvitation()"
                        class="flex-1 bg-secondary/5 hover:bg-secondary/10 border border-dashed border-secondary/30 rounded-lg py-2 text-[10px] font-bold text-secondary uppercase transition-all">+
                        Baru</button>
                    <button onclick="deleteInvitation()"
                        class="px-3 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg py-2 text-red-500 transition-all">
                        <span class="material-symbols-outlined text-sm">delete</span>
                    </button>
                </div>
            </div>
        </div>

        <nav class="space-y-1 flex-1 overflow-y-auto">
            <button onclick="showTab('settings')"
                class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all active"
                id="btn-settings">
                <span class="material-symbols-outlined">settings</span> Data Utama
            </button>
            <button onclick="showTab('gallery')"
                class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
                id="btn-gallery">
                <span class="material-symbols-outlined">image</span> Galeri Foto
            </button>
            <button onclick="showTab('guests')"
                class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
                id="btn-guests">
                <span class="material-symbols-outlined">group</span> Daftar Tamu
            </button>
            <button onclick="showTab('rsvp')"
                class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
                id="btn-rsvp">
                <span class="material-symbols-outlined">how_to_reg</span> RSVP
            </button>
            <button onclick="showTab('messages')"
                class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
                id="btn-messages">
                <span class="material-symbols-outlined">chat</span> Ucapan
            </button>
            <button onclick="showTab('music')"
                class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
                id="btn-music">
                <span class="material-symbols-outlined">music_note</span> Musik
            </button>
            <button onclick="showTab('stories')"
                class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
                id="btn-stories">
                <span class="material-symbols-outlined">auto_stories</span> Kisah Kasih
            </button>
            <button onclick="showTab('theme')"
                class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
                id="btn-theme">
                <span class="material-symbols-outlined">palette</span> Tema Desain
            </button>
        </nav>

        <div class="pt-6 border-t border-outline-variant/30">
            <a href="logout.php"
                class="flex items-center gap-3 px-4 py-3 text-red-600 text-sm font-semibold hover:bg-red-50 rounded-xl transition-all">
                <span class="material-symbols-outlined">logout</span> Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 md:ml-64 p-8">
        <div class="max-w-4xl mx-auto">

            <!-- Settings Tab -->
            <div id="tab-settings" class="tab-content">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="font-display-lg text-3xl text-primary">Pengaturan Undangan</h2>
                    <button onclick="saveSettings()"
                        class="btn-theme px-8 py-3 rounded-full font-bold text-xs uppercase tracking-widest shadow-lg">
                        Simpan Perubahan
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="data-card p-8 rounded-3xl space-y-6">
                        <h3
                            class="font-title-sm text-secondary flex items-center gap-2 uppercase text-xs tracking-widest">
                            <span class="material-symbols-outlined">favorite</span> Mempelai
                        </h3>

                        <!-- Groom Info -->
                        <div class="space-y-4">
                            <div
                                class="flex items-center gap-2 text-primary font-bold text-xs uppercase tracking-wider mb-2">
                                <span class="material-symbols-outlined text-sm">man</span> Calon Pengantin Pria
                            </div>
                            <div class="flex gap-4 items-start">
                                <div class="flex-1 space-y-4">
                                    <div class="rsvp-field"><label>NAMA LENGKAP PRIA</label><input type="text"
                                            id="set-groom_name"></div>
                                    <div class="rsvp-field"><label>PANGGILAN PRIA</label><input type="text"
                                            id="set-groom_nickname"></div>
                                </div>
                                <div class="w-24 text-center">
                                    <label
                                        class="block aspect-[3/4] bg-secondary/5 rounded-xl border border-dashed border-secondary/30 flex flex-col items-center justify-center cursor-pointer overflow-hidden group">
                                        <img id="preview-groom_photo" src="cowok.jpeg"
                                            class="w-full h-full object-cover hidden">
                                        <div id="placeholder-groom_photo" class="text-secondary opacity-40">
                                            <span class="material-symbols-outlined">add_a_photo</span>
                                            <p class="text-[8px] font-bold">FOTO</p>
                                        </div>
                                        <input type="file" class="hidden" accept="image/*"
                                            onchange="uploadProfilePhoto('groom')">
                                        <input type="hidden" id="set-groom_photo">
                                    </label>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4">
                                <div class="rsvp-field"><label>URUTAN ANAK (PRIA)</label><input type="text"
                                        id="set-groom_child_of" placeholder="Contoh: Putra Kedua dari"></div>
                                <div class="rsvp-field"><label>NAMA ORANG TUA (PRIA)</label><input type="text"
                                        id="set-groom_parents" placeholder="Contoh: Bapak ... & Ibu ..."></div>
                            </div>
                        </div>

                        <hr class="opacity-10">

                        <!-- Bride Info -->
                        <div class="space-y-4">
                            <div
                                class="flex items-center gap-2 text-primary font-bold text-xs uppercase tracking-wider mb-2">
                                <span class="material-symbols-outlined text-sm">woman</span> Calon Pengantin Wanita
                            </div>
                            <div class="flex gap-4 items-start">
                                <div class="flex-1 space-y-4">
                                    <div class="rsvp-field"><label>NAMA LENGKAP WANITA</label><input type="text"
                                            id="set-bride_name"></div>
                                    <div class="rsvp-field"><label>PANGGILAN WANITA</label><input type="text"
                                            id="set-bride_nickname"></div>
                                </div>
                                <div class="w-24 text-center">
                                    <label
                                        class="block aspect-[3/4] bg-secondary/5 rounded-xl border border-dashed border-secondary/30 flex flex-col items-center justify-center cursor-pointer overflow-hidden group">
                                        <img id="preview-bride_photo" src="cewek.jpeg"
                                            class="w-full h-full object-cover hidden">
                                        <div id="placeholder-bride_photo" class="text-secondary opacity-40">
                                            <span class="material-symbols-outlined">add_a_photo</span>
                                            <p class="text-[8px] font-bold">FOTO</p>
                                        </div>
                                        <input type="file" class="hidden" accept="image/*"
                                            onchange="uploadProfilePhoto('bride')">
                                        <input type="hidden" id="set-bride_photo">
                                    </label>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4">
                                <div class="rsvp-field"><label>URUTAN ANAK (WANITA)</label><input type="text"
                                        id="set-bride_child_of" placeholder="Contoh: Putri Pertama dari"></div>
                                <div class="rsvp-field"><label>NAMA ORANG TUA (WANITA)</label><input type="text"
                                        id="set-bride_parents" placeholder="Contoh: Bapak ... & Ibu ..."></div>
                            </div>
                        </div>
                    </div>

                    <div class="data-card p-8 rounded-3xl space-y-6">
                        <h3
                            class="font-title-sm text-secondary flex items-center gap-2 uppercase text-xs tracking-widest">
                            <span class="material-symbols-outlined">event</span> Jadwal
                        </h3>
                        <div class="rsvp-field"><label>TGL AKAD</label><input type="date" id="set-wedding_date"></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="rsvp-field"><label>JAM MULAI (ANGKA)</label><input type="text"
                                    id="set-wedding_time_start" placeholder="08:00"></div>
                            <div class="rsvp-field"><label>SAMPAI (ANGKA/TEKS)</label><input type="text"
                                    id="set-wedding_time_end" placeholder="10:00 atau Selesai"></div>
                        </div>

                        <!-- Lokasi Akad -->
                        <div class="rsvp-field">
                            <label>LOKASI AKAD</label>
                            <input type="text" id="set-wedding_location" placeholder="Contoh: Kediaman Mempelai Wanita">
                        </div>
                        <div class="rsvp-field">
                            <label>LINK GOOGLE MAPS AKAD</label>
                            <input type="text" id="set-wedding_map_link"
                                placeholder="Contoh: https://maps.app.goo.gl/xxxxx">
                        </div>

                        <hr class="border-t border-dashed border-outline-variant/30 my-4">

                        <div class="rsvp-field"><label>TGL RESEPSI</label><input type="date" id="set-reception_date">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="rsvp-field"><label>JAM MULAI (ANGKA)</label><input type="text"
                                    id="set-reception_time_start" placeholder="11:00"></div>
                            <div class="rsvp-field"><label>SAMPAI (ANGKA/TEKS)</label><input type="text"
                                    id="set-reception_time_end" placeholder="14:00 atau Selesai"></div>
                        </div>

                        <!-- Lokasi Resepsi -->
                        <div class="rsvp-field">
                            <label>LOKASI RESEPSI</label>
                            <input type="text" id="set-reception_location" placeholder="Contoh: Gedung Pernikahan ABC">
                        </div>
                        <div class="rsvp-field">
                            <label>LINK GOOGLE MAPS RESEPSI</label>
                            <input type="text" id="set-reception_map_link"
                                placeholder="Contoh: https://maps.app.goo.gl/xxxxx">
                        </div>
                    </div>

                    <div class="data-card p-8 rounded-3xl space-y-6 md:col-span-2">
                        <h3
                            class="font-title-sm text-secondary flex items-center gap-2 uppercase text-xs tracking-widest">
                            <span class="material-symbols-outlined">payments</span> Gift & Alamat
                        </h3>

                        <!-- Bank Information with Logo -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                            <div class="rsvp-field"><label>BANK</label><input type="text" id="set-gift_bank"></div>
                            <div class="rsvp-field"><label>NOREK</label><input type="text" id="set-gift_account"></div>
                            <div class="rsvp-field"><label>A/N</label><input type="text" id="set-gift_owner"></div>

                            <!-- Bank Logo Upload -->
                            <div class="text-center">
                                <label
                                    class="block aspect-[3/2] bg-secondary/5 rounded-xl border border-dashed border-secondary/30 flex flex-col items-center justify-center cursor-pointer overflow-hidden group max-w-[120px] mx-auto">
                                    <img id="preview-gift_bank_logo" src="" class="w-full h-full object-contain hidden">
                                    <div id="placeholder-gift_bank_logo" class="text-secondary opacity-40 text-center">
                                        <span class="material-symbols-outlined text-2xl">account_balance</span>
                                        <p class="text-[8px] font-bold mt-1">LOGO BANK</p>
                                    </div>
                                    <input type="file" class="hidden" accept="image/*" onchange="uploadBankLogo()">
                                    <input type="hidden" id="set-gift_bank_logo">
                                </label>
                                <p class="text-[8px] text-secondary/60 mt-1 italic">Opsional</p>
                            </div>
                        </div>

                        <div class="rsvp-field">
                            <label>ALAMAT PENGIRIMAN</label>
                            <textarea id="set-gift_address" rows="3"
                                class="w-full bg-transparent border-b border-outline-variant py-2 outline-none text-sm mt-4"></textarea>
                        </div>
                        <div class="rsvp-field">
                            <label>TEMPLATE PESAN WHATSAPP</label>
                            <textarea id="set-wa_template" rows="4"
                                class="w-full bg-transparent border-b border-outline-variant py-2 outline-none text-sm mt-4"
                                placeholder="Halo [nama], kami mengundang Anda ke pernikahan kami. Cek undangan di: [link]"></textarea>
                            <p class="text-[10px] opacity-50 mt-2 italic">*Gunakan [nama] untuk nama tamu dan [link]
                                untuk link undangan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gallery Tab -->
            <div id="tab-gallery" class="tab-content hidden">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="font-display-lg text-3xl text-primary">Galeri Foto</h2>
                    <label class="btn-theme px-6 py-3 rounded-xl font-bold text-xs uppercase cursor-pointer">
                        + Unggah Foto
                        <input type="file" id="gallery-upload" class="hidden" accept="image/*" onchange="uploadPhoto()">
                    </label>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="gallery-list">
                    <!-- Photos will load here -->
                </div>
            </div>

            <!-- Guests Tab -->
            <div id="tab-guests" class="tab-content hidden">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="font-display-lg text-3xl text-primary">Daftar Tamu</h2>
                    <div class="flex gap-2">
                        <input type="text" id="new-guest-name" placeholder="Nama Tamu"
                            class="bg-white border border-secondary/20 rounded-xl px-4 py-2 text-sm outline-none w-40">
                        <input type="text" id="new-guest-hp" placeholder="No HP (628...)"
                            class="bg-white border border-secondary/20 rounded-xl px-4 py-2 text-sm outline-none w-32">
                        <button onclick="addGuest()"
                            class="btn-theme px-6 py-2 rounded-xl font-bold text-xs uppercase">Tambah</button>
                        <button onclick="openModal('bulk-guests')"
                            class="bg-secondary/10 text-secondary hover:bg-secondary/20 px-4 py-2 rounded-xl font-bold text-[10px] uppercase transition-all">Tambah
                            Banyak</button>
                    </div>
                </div>
                <div class="bg-white rounded-3xl border border-outline-variant/30 overflow-hidden shadow-sm">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-secondary/5 text-secondary font-label-caps">
                            <tr>
                                <th class="px-6 py-4">Nama Tamu</th>
                                <th class="px-6 py-4">No HP</th>
                                <th class="px-6 py-4">Link Undangan</th>
                                <th class="px-6 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="guest-list"></tbody>
                    </table>
                </div>
            </div>

            <!-- RSVP Tab -->
            <div id="tab-rsvp" class="tab-content hidden">
                <h2 class="font-display-lg text-3xl text-primary mb-8">RSVP</h2>
                <div class="bg-white rounded-3xl overflow-hidden shadow-sm">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-secondary/5 text-secondary font-label-caps">
                            <tr>
                                <th class="px-6 py-4">Nama</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Tamu</th>
                                <th class="px-6 py-4">Keterangan</th>
                                <th class="px-6 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="rsvp-list"></tbody>
                    </table>
                </div>
            </div>

            <!-- Ucapan Tab -->
            <div id="tab-messages" class="tab-content hidden">
                <h2 class="font-display-lg text-3xl text-primary mb-8">Ucapan & Doa</h2>
                <div class="grid grid-cols-1 gap-4" id="ucapan-list"></div>
            </div>

            <!-- Musik Tab -->
            <div id="tab-music" class="tab-content hidden">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="font-display-lg text-3xl text-primary">Musik Latar</h2>
                    <label class="btn-theme px-6 py-3 rounded-xl font-bold text-xs uppercase cursor-pointer">
                        + Upload Lagu
                        <input type="file" id="music-upload" class="hidden" accept="audio/*" onchange="uploadMusic()">
                    </label>
                </div>

                <div class="bg-white rounded-3xl border border-outline-variant/30 overflow-hidden shadow-sm mb-6">
                    <div class="p-6 border-b border-outline-variant/20">
                        <h3
                            class="font-title-sm text-secondary flex items-center gap-2 uppercase text-xs tracking-widest mb-4">
                            <span class="material-symbols-outlined">settings</span> Pengaturan Musik
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="rsvp-field">
                                <label>VOLUME MUSIK (0-100)</label>
                                <input type="number" id="set-music_volume" min="0" max="100" value="50">
                            </div>
                            <div class="rsvp-field">
                                <label>AUTO PLAY</label>
                                <select id="set-music_autoplay">
                                    <option value="1">Ya, putar otomatis</option>
                                    <option value="0">Tidak, tunggu klik user</option>
                                </select>
                            </div>
                        </div>
                        <button onclick="saveMusicSettings()"
                            class="mt-4 btn-theme px-6 py-2 rounded-xl font-bold text-xs uppercase">
                            Simpan Pengaturan
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-outline-variant/30 overflow-hidden shadow-sm">
                    <div class="p-6 border-b border-outline-variant/20">
                        <h3
                            class="font-title-sm text-secondary flex items-center gap-2 uppercase text-xs tracking-widest">
                            <span class="material-symbols-outlined">library_music</span> Daftar Lagu
                        </h3>
                    </div>
                    <div id="music-list" class="divide-y divide-outline-variant/20">
                        <!-- Music files will load here -->
                    </div>
                </div>
            </div>

            <!-- Stories Tab -->
            <div id="tab-stories" class="tab-content hidden">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="font-display-lg text-3xl text-primary">Kisah Kasih (Love Journey)</h2>
                </div>

                <div class="bg-white rounded-3xl border border-outline-variant/30 overflow-hidden shadow-sm mb-8">
                    <div class="p-6 border-b border-outline-variant/20 bg-secondary/5">
                        <h3
                            class="font-title-sm text-secondary flex items-center gap-2 uppercase text-xs tracking-widest font-semibold">
                            <span class="material-symbols-outlined">add_circle</span> Tambah Kisah Baru
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="rsvp-field">
                                <label>TAHUN / ICON</label>
                                <input type="text" id="new-story-tahun"
                                    placeholder="Contoh: 2025, 2026, atau nama icon seperti diamond">
                            </div>
                            <div class="rsvp-field">
                                <label>JUDUL KISAH</label>
                                <input type="text" id="new-story-judul" placeholder="Contoh: Pertemuan Pertama">
                            </div>
                        </div>
                        <div class="rsvp-field">
                            <label>ISI CERITA / KISAH</label>
                            <textarea id="new-story-isi" rows="3"
                                class="w-full bg-transparent border-b border-outline-variant py-2 outline-none text-sm mt-4"
                                placeholder="Ceritakan kisah kasih Anda pada tahun tersebut..."></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button onclick="addStory()"
                                class="btn-theme px-8 py-3 rounded-xl font-bold text-xs uppercase tracking-wider shadow-md">
                                Tambah Kisah
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-outline-variant/30 overflow-hidden shadow-sm">
                    <div class="p-6 border-b border-outline-variant/20">
                        <h3
                            class="font-title-sm text-secondary flex items-center gap-2 uppercase text-xs tracking-widest font-semibold">
                            <span class="material-symbols-outlined">list_alt</span> Daftar Kisah Kasih
                        </h3>
                    </div>
                    <table class="w-full text-left text-sm">
                        <thead class="bg-secondary/5 text-secondary font-label-caps">
                            <tr>
                                <th class="px-6 py-4 w-24">Tahun</th>
                                <th class="px-6 py-4 w-48">Judul</th>
                                <th class="px-6 py-4">Isi Kisah</th>
                                <th class="px-6 py-4 w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="stories-list"></tbody>
                    </table>
                </div>
            </div>

            <!-- Theme Tab -->
            <div id="tab-theme" class="tab-content hidden">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="font-display-lg text-3xl text-primary">Tema & Warna Undangan</h2>
                    <button onclick="saveSettings()"
                        class="btn-theme px-8 py-3 rounded-full font-bold text-xs uppercase tracking-widest shadow-lg">
                        Simpan Tema
                    </button>
                </div>

                <div class="space-y-8">
                    <!-- Preset Themes Selection -->
                    <div class="data-card p-8 rounded-3xl space-y-6">
                        <h3
                            class="font-title-sm text-secondary flex items-center gap-2 uppercase text-xs tracking-widest font-semibold">
                            <span class="material-symbols-outlined">palette</span> Pilih Preset Tema Desain
                        </h3>

                        <input type="hidden" id="set-theme_preset" value="classic-gold">

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <!-- Classic Gold -->
                            <div onclick="selectPreset('classic-gold')" id="preset-classic-gold"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-classic-gold" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Classic Gold</h4>
                                    <p class="text-[9px] text-gray-500">Cokelat & Emas Bawaan</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #361f1a;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #775a19;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #fbf9f5;"></span>
                                </div>
                            </div>

                            <!-- Royal Emerald -->
                            <div onclick="selectPreset('royal-emerald')" id="preset-royal-emerald"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-royal-emerald" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Royal Emerald</h4>
                                    <p class="text-[9px] text-gray-500">Hijau Zamrud & Emas</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #064e3b;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #0f766e;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #f0fdf4;"></span>
                                </div>
                            </div>

                            <!-- Navy Sapphire -->
                            <div onclick="selectPreset('navy-sapphire')" id="preset-navy-sapphire"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-navy-sapphire" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Navy Sapphire</h4>
                                    <p class="text-[9px] text-gray-500">Biru Safir & Perak</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #1e3a8a;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #3b82f6;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #f8fafc;"></span>
                                </div>
                            </div>

                            <!-- Burgundy Ruby -->
                            <div onclick="selectPreset('burgundy-ruby')" id="preset-burgundy-ruby"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-burgundy-ruby" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Burgundy Ruby</h4>
                                    <p class="text-[9px] text-gray-500">Merah Marun Romantis</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #4c0519;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #9f1239;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #fff5f5;"></span>
                                </div>
                            </div>

                            <!-- Sakura Rose -->
                            <div onclick="selectPreset('sakura-rose')" id="preset-sakura-rose"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-sakura-rose" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Sakura Rose</h4>
                                    <p class="text-[9px] text-gray-500">Merah Jambu & Emas</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #831843;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #db2777;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #fff1f2;"></span>
                                </div>
                            </div>

                            <!-- Elegant Charcoal -->
                            <div onclick="selectPreset('elegant-charcoal')" id="preset-elegant-charcoal"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-elegant-charcoal" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Elegant Charcoal</h4>
                                    <p class="text-[9px] text-gray-500">Hitam Abu & Putih</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #18181b;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #52525b;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #fafafa;"></span>
                                </div>
                            </div>

                            <!-- Forest Sage -->
                            <div onclick="selectPreset('forest-sage')" id="preset-forest-sage"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-forest-sage" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Forest Sage</h4>
                                    <p class="text-[9px] text-gray-500">Hijau Sage & Krem</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #2f3e22;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #708238;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #fcfbfa;"></span>
                                </div>
                            </div>

                            <!-- Sunset Ochre -->
                            <div onclick="selectPreset('sunset-ochre')" id="preset-sunset-ochre"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-sunset-ochre" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Sunset Ochre</h4>
                                    <p class="text-[9px] text-gray-500">Oranye Bata & Senja</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #5f1d0a;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #b85906;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #fdfaf7;"></span>
                                </div>
                            </div>

                            <!-- Sweet Lavender -->
                            <div onclick="selectPreset('sweet-lavender')" id="preset-sweet-lavender"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-sweet-lavender" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Sweet Lavender</h4>
                                    <p class="text-[9px] text-gray-500">Lavender Romantis</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #3b1d5f;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #8b5cf6;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #faf8ff;"></span>
                                </div>
                            </div>

                            <!-- Terracotta Rust -->
                            <div onclick="selectPreset('terracotta-rust')" id="preset-terracotta-rust"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-terracotta-rust" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Terracotta Rust</h4>
                                    <p class="text-[9px] text-gray-500">Warna Bata & Mustard</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #451a03;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #ca8a04;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #fffbeb;"></span>
                                </div>
                            </div>

                            <!-- Ocean Turquoise -->
                            <div onclick="selectPreset('ocean-turquoise')" id="preset-ocean-turquoise"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-ocean-turquoise" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Ocean Turquoise</h4>
                                    <p class="text-[9px] text-gray-500">Biru Toska Pantai</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #065f46;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #0ea5e9;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #f0f9ff;"></span>
                                </div>
                            </div>

                            <!-- Vintage Plum -->
                            <div onclick="selectPreset('vintage-plum')" id="preset-vintage-plum"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-vintage-plum" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Vintage Plum</h4>
                                    <p class="text-[9px] text-gray-500">Ungu Plum Klasik</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #471825;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #a21caf;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #fdf4ff;"></span>
                                </div>
                            </div>

                            <!-- Midnight Gold -->
                            <div onclick="selectPreset('midnight-gold')" id="preset-midnight-gold"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-midnight-gold" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Midnight Gold</h4>
                                    <p class="text-[9px] text-gray-500">Biru Gelap & Emas</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #0f172a;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #d97706;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #f8fafc;"></span>
                                </div>
                            </div>

                            <!-- Blossom Sakura -->
                            <div onclick="selectPreset('blossom-sakura')" id="preset-blossom-sakura"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-blossom-sakura" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Blossom Sakura</h4>
                                    <p class="text-[9px] text-gray-500">Soft Pink & Kelopak</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #500724;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #fb7185;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #fff1f2;"></span>
                                </div>
                            </div>

                            <!-- Autumn Maple -->
                            <div onclick="selectPreset('autumn-maple')" id="preset-autumn-maple"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-autumn-maple" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Autumn Maple</h4>
                                    <p class="text-[9px] text-gray-500">Merah Maple & Emas</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #781a08;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #d97706;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #fffbeb;"></span>
                                </div>
                            </div>

                            <!-- Desert Sand -->
                            <div onclick="selectPreset('desert-sand')" id="preset-desert-sand"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-desert-sand" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Desert Sand</h4>
                                    <p class="text-[9px] text-gray-500">Gurun Pasir & Cokelat</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #451a03;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #a16207;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #fafaf9;"></span>
                                </div>
                            </div>

                            <!-- Soft Mint -->
                            <div onclick="selectPreset('soft-mint')" id="preset-soft-mint"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-soft-mint" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Soft Mint</h4>
                                    <p class="text-[9px] text-gray-500">Hijau Mint & Sage</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #064e3b;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #34d399;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #f0fdf4;"></span>
                                </div>
                            </div>

                            <!-- Royal Purple -->
                            <div onclick="selectPreset('royal-purple')" id="preset-royal-purple"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-royal-purple" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Royal Purple</h4>
                                    <p class="text-[9px] text-gray-500">Ungu Royal & Emas</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #4c1d95;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #d97706;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #faf5ff;"></span>
                                </div>
                            </div>

                            <!-- Espresso Caramel -->
                            <div onclick="selectPreset('espresso-caramel')" id="preset-espresso-caramel"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-espresso-caramel" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Espresso Caramel</h4>
                                    <p class="text-[9px] text-gray-500">Kopi Gelap & Karamel</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #2d1e18;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #b45309;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #fafaf9;"></span>
                                </div>
                            </div>

                            <!-- Steel Blue -->
                            <div onclick="selectPreset('steel-blue')" id="preset-steel-blue"
                                class="cursor-pointer border-2 border-secondary/20 rounded-2xl p-4 bg-white hover:border-secondary hover:shadow-md transition-all flex flex-col justify-between h-28 relative shadow-sm group">
                                <div id="check-steel-blue" class="absolute top-2 right-2 text-secondary hidden">
                                    <span class="material-symbols-outlined text-base"
                                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">Steel Blue</h4>
                                    <p class="text-[9px] text-gray-500">Biru Baja & Perak</p>
                                </div>
                                <div class="flex gap-1">
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #0f2d4a;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #5b80a4;"></span>
                                    <span class="w-5 h-5 rounded-full inline-block border border-gray-200"
                                        style="background-color: #f4f7fa;"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Color Configuration -->
                    <div class="data-card p-8 rounded-3xl space-y-6">
                        <h3
                            class="font-title-sm text-secondary flex items-center gap-2 uppercase text-xs tracking-widest font-semibold">
                            <span class="material-symbols-outlined">tune</span> Kustomisasi Warna Detail
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="rsvp-field flex flex-col">
                                <label>WARNA UTAMA (PRIMARY)</label>
                                <div class="flex items-center gap-2 mt-4">
                                    <input type="color" id="set-theme_primary"
                                        class="w-10 h-10 border-none outline-none cursor-pointer rounded"
                                        onchange="markCustom()">
                                    <input type="text" id="set-theme_primary_text"
                                        class="border border-secondary/20 rounded-xl px-3 py-2 text-xs w-28 uppercase font-bold text-primary"
                                        onchange="updateColorFromText('theme_primary')">
                                </div>
                            </div>

                            <div class="rsvp-field flex flex-col">
                                <label>WARNA SEKUNDER (SECONDARY)</label>
                                <div class="flex items-center gap-2 mt-4">
                                    <input type="color" id="set-theme_secondary"
                                        class="w-10 h-10 border-none outline-none cursor-pointer rounded"
                                        onchange="markCustom()">
                                    <input type="text" id="set-theme_secondary_text"
                                        class="border border-secondary/20 rounded-xl px-3 py-2 text-xs w-28 uppercase font-bold text-primary"
                                        onchange="updateColorFromText('theme_secondary')">
                                </div>
                            </div>

                            <div class="rsvp-field flex flex-col">
                                <label>WARNA LATAR (BACKGROUND)</label>
                                <div class="flex items-center gap-2 mt-4">
                                    <input type="color" id="set-theme_background"
                                        class="w-10 h-10 border-none outline-none cursor-pointer rounded"
                                        onchange="markCustom()">
                                    <input type="text" id="set-theme_background_text"
                                        class="border border-secondary/20 rounded-xl px-3 py-2 text-xs w-28 uppercase font-bold text-primary"
                                        onchange="updateColorFromText('theme_background')">
                                </div>
                            </div>
                        </div>

                        <hr class="opacity-10 my-4">

                        <h4 class="text-xs font-bold text-primary uppercase tracking-wider mb-2">Warna Tambahan
                            Kontainer</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="rsvp-field flex flex-col">
                                <label>PRIMARY CONTAINER</label>
                                <div class="flex items-center gap-2 mt-4">
                                    <input type="color" id="set-theme_primary_container"
                                        class="w-10 h-10 border-none outline-none cursor-pointer rounded"
                                        onchange="markCustom()">
                                    <input type="text" id="set-theme_primary_container_text"
                                        class="border border-secondary/20 rounded-xl px-3 py-2 text-xs w-28 uppercase font-bold text-primary"
                                        onchange="updateColorFromText('theme_primary_container')">
                                </div>
                            </div>

                            <div class="rsvp-field flex flex-col">
                                <label>SECONDARY CONTAINER</label>
                                <div class="flex items-center gap-2 mt-4">
                                    <input type="color" id="set-theme_secondary_container"
                                        class="w-10 h-10 border-none outline-none cursor-pointer rounded"
                                        onchange="markCustom()">
                                    <input type="text" id="set-theme_secondary_container_text"
                                        class="border border-secondary/20 rounded-xl px-3 py-2 text-xs w-28 uppercase font-bold text-primary"
                                        onchange="updateColorFromText('theme_secondary_container')">
                                </div>
                            </div>

                            <div class="rsvp-field flex flex-col">
                                <label>SURFACE CONTAINER LOW</label>
                                <div class="flex items-center gap-2 mt-4">
                                    <input type="color" id="set-theme_surface_container_low"
                                        class="w-10 h-10 border-none outline-none cursor-pointer rounded"
                                        onchange="markCustom()">
                                    <input type="text" id="set-theme_surface_container_low_text"
                                        class="border border-secondary/20 rounded-xl px-3 py-2 text-xs w-28 uppercase font-bold text-primary"
                                        onchange="updateColorFromText('theme_surface_container_low')">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Edit Guest -->
    <div id="modal-guest" class="modal fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-sm rounded-3xl p-8 shadow-2xl">
            <h3 class="font-display-lg text-2xl text-primary mb-6">Edit Tamu</h3>
            <div class="space-y-4">
                <input type="hidden" id="edit-guest-id">
                <div class="rsvp-field"><label>NAMA TAMU</label><input type="text" id="edit-guest-nama"></div>
                <div class="rsvp-field"><label>NO HP (628...)</label><input type="text" id="edit-guest-hp"></div>
            </div>
            <div class="flex gap-3 mt-8">
                <button onclick="closeModal('guest')"
                    class="flex-1 py-3 rounded-xl font-bold text-xs uppercase bg-gray-100">Batal</button>
                <button onclick="updateGuest()"
                    class="flex-1 py-3 rounded-xl font-bold text-xs uppercase btn-theme">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Modal Edit RSVP -->
    <div id="modal-rsvp" class="modal fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-lg rounded-3xl p-8 shadow-2xl">
            <h3 class="font-display-lg text-2xl text-primary mb-6">Edit RSVP</h3>
            <div class="space-y-4">
                <input type="hidden" id="edit-rsvp-id">
                <div class="rsvp-field"><label>NAMA</label><input type="text" id="edit-rsvp-nama"></div>
                <div class="rsvp-field"><label>JUMLAH TAMU</label><input type="number" id="edit-rsvp-jumlah"></div>
                <div class="rsvp-field">
                    <label>STATUS</label>
                    <select id="edit-rsvp-status"
                        class="w-full bg-transparent border-b border-outline-variant py-2 outline-none">
                        <option value="hadir">Hadir</option>
                        <option value="tidak">Tidak Hadir</option>
                    </select>
                </div>
                <div class="pt-2">
                    <label
                        class="text-[10px] font-bold text-secondary tracking-widest uppercase">Keterangan/Alasan</label>
                    <textarea id="edit-rsvp-alasan" rows="2"
                        class="w-full bg-transparent border-b border-outline-variant py-2 outline-none text-sm"></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-8">
                <button onclick="closeModal('rsvp')"
                    class="flex-1 py-3 rounded-xl font-bold text-xs uppercase bg-gray-100">Batal</button>
                <button onclick="updateRSVP()"
                    class="flex-1 py-3 rounded-xl font-bold text-xs uppercase btn-theme">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Modal Edit Ucapan -->
    <div id="modal-ucapan" class="modal fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-lg rounded-3xl p-8 shadow-2xl">
            <h3 class="font-display-lg text-2xl text-primary mb-6">Edit Ucapan</h3>
            <div class="space-y-4">
                <input type="hidden" id="edit-ucapan-id">
                <div class="rsvp-field"><label>NAMA</label><input type="text" id="edit-ucapan-nama"></div>
                <div class="pt-2">
                    <label class="text-[10px] font-bold text-secondary tracking-widest uppercase">Pesan Ucapan</label>
                    <textarea id="edit-ucapan-pesan" rows="4"
                        class="w-full bg-transparent border-b border-outline-variant py-2 outline-none text-sm"></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-8">
                <button onclick="closeModal('ucapan')"
                    class="flex-1 py-3 rounded-xl font-bold text-xs uppercase bg-gray-100">Batal</button>
                <button onclick="updateUcapan()"
                    class="flex-1 py-3 rounded-xl font-bold text-xs uppercase btn-theme">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kisah -->
    <div id="modal-story" class="modal fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-lg rounded-3xl p-8 shadow-2xl">
            <h3 class="font-display-lg text-2xl text-primary mb-6">Edit Kisah</h3>
            <div class="space-y-4">
                <input type="hidden" id="edit-story-id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rsvp-field"><label>TAHUN / ICON</label><input type="text" id="edit-story-tahun"></div>
                    <div class="rsvp-field"><label>JUDUL KISAH</label><input type="text" id="edit-story-judul"></div>
                </div>
                <div class="pt-2">
                    <label class="text-[10px] font-bold text-secondary tracking-widest uppercase block mb-1">Isi
                        Kisah</label>
                    <textarea id="edit-story-isi" rows="4"
                        class="w-full bg-transparent border border-outline-variant rounded-xl p-3 text-sm focus:outline-none focus:ring-1 focus:ring-secondary"></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-8">
                <button onclick="closeModal('story')"
                    class="flex-1 py-3 rounded-xl font-bold text-xs uppercase bg-gray-100">Batal</button>
                <button onclick="updateStory()"
                    class="flex-1 py-3 rounded-xl font-bold text-xs uppercase btn-theme">Simpan</button>
            </div>
        </div>
    </div>

    <script>
        let currentInvId = localStorage.getItem('lastInvId') || 1;
        let currentSlug = '';

        async function init() {
            await loadInvitations();
            const activeTab = localStorage.getItem('activeTab') || 'settings';
            showTab(activeTab);
        }

        async function loadInvitations() {
            const res = await fetch('api/admin_api.php?action=get_invitations');
            const data = await res.json();
            const selector = document.getElementById('inv-selector');

            // Check if saved ID still exists, if not fallback to first
            if (!data.data.find(inv => inv.id == currentInvId)) {
                currentInvId = data.data[0]?.id || 1;
            }

            selector.innerHTML = data.data.map(inv => `<option value="${inv.id}" data-slug="${inv.slug}" ${inv.id == currentInvId ? 'selected' : ''}>${inv.title}</option>`).join('');

            const selectedOpt = selector.options[selector.selectedIndex];
            currentSlug = selectedOpt ? selectedOpt.dataset.slug : '';
        }

        function changeInvitation() {
            const sel = document.getElementById('inv-selector');
            currentInvId = sel.value;
            currentSlug = sel.options[sel.selectedIndex].dataset.slug;
            localStorage.setItem('lastInvId', currentInvId);
            const activeTab = localStorage.getItem('activeTab') || 'settings';
            showTab(activeTab);
        }

        async function addNewInvitation() {
            const title = prompt("Masukkan Judul Undangan (misal: Pernikahan A & B):");
            if (!title) return;
            const slug = title.toLowerCase().replace(/[^a-z0-9]/g, '-');
            const res = await fetch('api/admin_api.php?action=add_invitation', {
                method: 'POST',
                body: JSON.stringify({ title, slug })
            });
            const data = await res.json();
            if (data.success) {
                currentInvId = data.id;
                currentSlug = slug;
                await loadInvitations();
                const activeTab = localStorage.getItem('activeTab') || 'settings';
                showTab(activeTab);
            }
        }

        async function deleteInvitation() {
            if (currentInvId == 1) return alert('Undangan utama tidak dapat dihapus.');
            if (!confirm(`Hapus seluruh data undangan "${document.getElementById('inv-selector').options[document.getElementById('inv-selector').selectedIndex].text}"? Tindakan ini tidak dapat dibatalkan.`)) return;

            const res = await fetch(`api/admin_api.php?action=delete_invitation&id=${currentInvId}`);
            const data = await res.json();
            if (data.success) {
                alert('Undangan berhasil dihapus.');
                currentInvId = 1; // Reset to default
                await loadInvitations();
                changeInvitation();
            } else {
                alert(data.message || 'Gagal menghapus undangan.');
            }
        }

        function showTab(tabId) {
            localStorage.setItem('activeTab', tabId);
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

            const contentEl = document.getElementById('tab-' + tabId);
            const btnEl = document.getElementById('btn-' + tabId);
            if (contentEl) contentEl.classList.remove('hidden');
            if (btnEl) btnEl.classList.add('active');

            if (tabId === 'settings' || tabId === 'theme') loadSettings();
            if (tabId === 'gallery') loadGallery();
            if (tabId === 'guests') loadGuests();
            if (tabId === 'rsvp') loadRSVP();
            if (tabId === 'messages') loadUcapan();
            if (tabId === 'music') loadMusic();
            if (tabId === 'stories') loadStories();
        }

        async function loadSettings() {
            const res = await fetch(`api/admin_api.php?action=get_settings&inv_id=${currentInvId}`);
            const data = await res.json();
            const keys = ['groom_name', 'groom_nickname', 'groom_child_of', 'groom_parents', 'groom_photo', 'bride_name', 'bride_nickname', 'bride_child_of', 'bride_parents', 'bride_photo', 'wedding_date', 'wedding_time_start', 'wedding_time_end', 'wedding_location', 'wedding_map_link', 'reception_date', 'reception_time_start', 'reception_time_end', 'reception_location', 'reception_map_link', 'gift_bank', 'gift_account', 'gift_owner', 'gift_address', 'gift_bank_logo', 'wa_template', 'music_volume', 'music_autoplay', 'theme_preset', 'theme_primary', 'theme_secondary', 'theme_background', 'theme_primary_container', 'theme_secondary_container', 'theme_surface_container_low'];
            keys.forEach(k => {
                const el = document.getElementById('set-' + k);
                if (el) el.value = data.data[k] || (k === 'music_volume' ? '50' : (k === 'music_autoplay' ? '1' : ''));

                // Update photo previews
                if (k === 'groom_photo' || k === 'bride_photo' || k === 'gift_bank_logo') {
                    const preview = document.getElementById('preview-' + k);
                    const placeholder = document.getElementById('placeholder-' + k);
                    if (data.data[k]) {
                        preview.src = data.data[k];
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    } else {
                        preview.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                    }
                }
            });

            // Synchronize design theme tab color inputs
            const presetVal = data.data['theme_preset'] || 'classic-gold';
            const presetField = document.getElementById('set-theme_preset');
            if (presetField) presetField.value = presetVal;

            // Highlight active preset card
            document.querySelectorAll('[id^="preset-"]').forEach(el => {
                el.classList.remove('border-secondary', 'bg-secondary/5', 'ring-4', 'ring-secondary/20', 'shadow-lg', '-translate-y-1');
                el.classList.add('border-secondary/20', 'bg-white', 'shadow-sm');
            });
            const activeCard = document.getElementById('preset-' + presetVal);
            if (activeCard) {
                activeCard.classList.remove('border-secondary/20', 'bg-white', 'shadow-sm');
                activeCard.classList.add('border-secondary', 'bg-secondary/5', 'ring-4', 'ring-secondary/20', 'shadow-lg', '-translate-y-1');
            }

            // Toggle checkmark icons
            document.querySelectorAll('[id^="check-"]').forEach(el => {
                el.classList.add('hidden');
            });
            const activeCheck = document.getElementById('check-' + presetVal);
            if (activeCheck) {
                activeCheck.classList.remove('hidden');
            }

            // Sync color picker values & hex texts
            const themeFields = ['primary', 'secondary', 'background', 'primary_container', 'secondary_container', 'surface_container_low'];
            themeFields.forEach(f => {
                const picker = document.getElementById('set-theme_' + f);
                const txt = document.getElementById('set-theme_' + f + '_text');
                const defaultColors = themePresets[presetVal] || themePresets['classic-gold'];
                const savedVal = data.data['theme_' + f] || defaultColors[f];

                if (picker) picker.value = savedVal;
                if (txt) txt.value = savedVal;
            });
        }

        async function saveSettings() {
            const settings = {};
            document.querySelectorAll('[id^="set-"]').forEach(el => {
                settings[el.id.replace('set-', '')] = el.value;
            });
            const res = await fetch(`api/admin_api.php?action=update_settings&inv_id=${currentInvId}`, {
                method: 'POST',
                body: JSON.stringify(settings)
            });
            const data = await res.json();
            if (data.success) alert('Berhasil disimpan!');
        }

        async function loadGallery() {
            const res = await fetch(`api/admin_api.php?action=get_gallery&inv_id=${currentInvId}`);
            const data = await res.json();
            const list = document.getElementById('gallery-list');
            list.innerHTML = data.data.map(img => `
                <div class="relative gallery-item group aspect-square overflow-hidden rounded-2xl bg-gray-100">
                    <img src="${img.image_path}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center delete-overlay">
                        <button onclick="deletePhoto(${img.id})" class="bg-red-500 text-white p-2 rounded-full hover:scale-110 transition-all">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>
                </div>
            `).join('');
        }

        async function uploadPhoto() {
            const input = document.getElementById('gallery-upload');
            if (!input.files.length) return;

            const formData = new FormData();
            formData.append('image', input.files[0]);

            const res = await fetch(`api/admin_api.php?action=upload_gallery&inv_id=${currentInvId}`, {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                loadGallery();
            } else {
                alert(data.message || 'Gagal unggah foto');
            }
            input.value = '';
        }

        async function uploadProfilePhoto(type) {
            const input = event.target;
            if (!input.files.length) return;

            const formData = new FormData();
            formData.append('image', input.files[0]);

            // Use upload_profile_photo to save file to settings (NOT to gallery table)
            const res = await fetch(`api/admin_api.php?action=upload_profile_photo&inv_id=${currentInvId}`, {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                // Save directly to settings (not to gallery)
                document.getElementById('set-' + type + '_photo').value = data.path;

                // Also update preview
                const preview = document.getElementById('preview-' + type + '_photo');
                const placeholder = document.getElementById('placeholder-' + type + '_photo');
                preview.src = data.path;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');

                alert('Foto berhasil diunggah ! Jangan lupa simpan perubahan.');
            }
        }

        async function deletePhoto(id) {
            if (!confirm('Hapus foto ini dari galeri?')) return;
            await fetch(`api/admin_api.php?action=delete_gallery&id=${id}`);
            loadGallery();
        }

        async function loadGuests() {
            const res = await fetch(`api/admin_api.php?action=get_guests&inv_id=${currentInvId}`);
            const data = await res.json();
            const list = document.getElementById('guest-list');
            const baseUrl = window.location.origin + window.location.pathname.replace('admin.php', 'index.php');
            list.innerHTML = data.data.map(g => {
                const link = `${baseUrl}?inv=${currentSlug}&to=${g.slug}`;
                return `
                <tr class="border-t border-outline-variant/20 hover:bg-secondary/5 transition-all">
                    <td class="px-6 py-4 font-semibold">${g.nama}</td>
                    <td class="px-6 py-4 text-xs">${g.no_hp || '-'}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="${link}" class="bg-secondary/5 text-[10px] p-2 rounded w-64 border-none outline-none">
                            <button onclick="copyToClipboard('${link}')" class="text-secondary hover:text-primary"><span class="material-symbols-outlined text-sm">content_copy</span></button>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="shareWA('${g.nama}', '${link}', '${g.no_hp}')" class="text-green-500 hover:scale-110" title="Kirim WA"><span class="material-symbols-outlined text-sm">chat</span></button>
                            <button onclick="openEditGuest(${JSON.stringify(g).replace(/"/g, '&quot;')})" class="text-secondary hover:scale-110"><span class="material-symbols-outlined text-sm">edit</span></button>
                            <button onclick="deleteGuest(${g.id})" class="text-red-400 hover:scale-110"><span class="material-symbols-outlined text-sm">delete</span></button>
                        </div>
                    </td>
                </tr>
            `}).join('');
        }

        function shareWA(nama, link, hp) {
            let template = document.getElementById('set-wa_template').value;
            if (!template) template = "Halo [nama], kami mengundang Anda ke pernikahan kami. Cek undangan di: [link]";

            const message = template.replace('[nama]', nama).replace('[link]', link);
            const waUrl = hp ? `https://wa.me/${hp}?text=${encodeURIComponent(message)}` : `https://wa.me/?text=${encodeURIComponent(message)}`;
            window.open(waUrl, '_blank');
        }

        async function addGuest() {
            const nama = document.getElementById('new-guest-name').value;
            const no_hp = document.getElementById('new-guest-hp').value;
            if (!nama) return;
            await fetch(`api/admin_api.php?action=add_guest&inv_id=${currentInvId}`, {
                method: 'POST',
                body: JSON.stringify({ nama, no_hp })
            });
            document.getElementById('new-guest-name').value = '';
            document.getElementById('new-guest-hp').value = '';
            loadGuests();
        }

        async function bulkAddGuests() {
            const raw = document.getElementById('bulk-names').value;
            if (!raw.trim()) return;
            const names = raw.split('\n').map(n => n.trim()).filter(n => n !== '');

            if (names.length === 0) return;

            await fetch(`api/admin_api.php?action=bulk_add_guests&inv_id=${currentInvId}`, {
                method: 'POST',
                body: JSON.stringify({ names })
            });

            document.getElementById('bulk-names').value = '';
            closeModal('bulk-guests');
            loadGuests();
        }

        function openEditGuest(g) {
            document.getElementById('edit-guest-id').value = g.id;
            document.getElementById('edit-guest-nama').value = g.nama;
            document.getElementById('modal-guest').classList.remove('hidden');
        }

        async function updateGuest() {
            const id = document.getElementById('edit-guest-id').value;
            const nama = document.getElementById('edit-guest-nama').value;
            await fetch('api/admin_api.php?action=update_guest', {
                method: 'POST',
                body: JSON.stringify({ id, nama })
            });
            closeModal('guest');
            loadGuests();
        }

        async function deleteGuest(id) {
            if (!confirm('Hapus tamu?')) return;
            await fetch(`api/admin_api.php?action=delete_guest&id=${id}`);
            loadGuests();
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => alert('Link disalin!'));
        }

        async function loadRSVP() {
            const res = await fetch(`api/admin_api.php?action=get_rsvp&inv_id=${currentInvId}`);
            const data = await res.json();
            document.getElementById('rsvp-list').innerHTML = data.data.map(item => `
                <tr class="border-t border-outline-variant/20 hover:bg-secondary/5 transition-all">
                    <td class="px-6 py-4 font-semibold">${item.nama}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase ${item.status === 'hadir' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">${item.status}</span></td>
                    <td class="px-6 py-4">${item.status === 'tidak' ? '-' : item.jumlah_tamu}</td>
                    <td class="px-6 py-4 text-xs italic opacity-70">${item.alasan || '-'}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="openEditRSVP(${JSON.stringify(item).replace(/"/g, '&quot;')})" class="text-secondary hover:scale-110"><span class="material-symbols-outlined text-sm">edit</span></button>
                            <button onclick="deleteItem('rsvp', ${item.id})" class="text-red-400 hover:scale-110"><span class="material-symbols-outlined text-sm">delete</span></button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        async function loadUcapan() {
            const res = await fetch(`api/admin_api.php?action=get_ucapan&inv_id=${currentInvId}`);
            const data = await res.json();
            document.getElementById('ucapan-list').innerHTML = data.data.map(item => `
                <div class="data-card p-6 rounded-2xl flex justify-between items-center hover:shadow-md transition-all">
                    <div>
                        <p class="font-semibold text-sm mb-1">${item.nama}</p>
                        <p class="text-xs opacity-70">"${item.pesan}"</p>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="openEditUcapan(${JSON.stringify(item).replace(/"/g, '&quot;')})" class="text-secondary"><span class="material-symbols-outlined text-sm">edit</span></button>
                        <button onclick="deleteItem('ucapan', ${item.id})" class="text-red-400"><span class="material-symbols-outlined text-sm">delete</span></button>
                    </div>
                </div>
            `).join('');
        }

        function openEditRSVP(item) {
            document.getElementById('edit-rsvp-id').value = item.id;
            document.getElementById('edit-rsvp-nama').value = item.nama;
            document.getElementById('edit-rsvp-jumlah').value = item.jumlah_tamu;
            document.getElementById('edit-rsvp-status').value = item.status;
            document.getElementById('edit-rsvp-alasan').value = item.alasan;
            document.getElementById('modal-rsvp').classList.remove('hidden');
        }

        function openEditUcapan(item) {
            document.getElementById('edit-ucapan-id').value = item.id;
            document.getElementById('edit-ucapan-nama').value = item.nama;
            document.getElementById('edit-ucapan-pesan').value = item.pesan;
            document.getElementById('modal-ucapan').classList.remove('hidden');
        }

        function openModal(type) {
            document.getElementById('modal-' + type).classList.remove('hidden');
        }

        function closeModal(type) {
            document.getElementById('modal-' + type).classList.add('hidden');
        }

        async function updateRSVP() {
            const id = document.getElementById('edit-rsvp-id').value;
            const nama = document.getElementById('edit-rsvp-nama').value;
            const jumlah = document.getElementById('edit-rsvp-jumlah').value;
            const status = document.getElementById('edit-rsvp-status').value;
            const alasan = document.getElementById('edit-rsvp-alasan').value;

            await fetch('api/admin_api.php?action=update_rsvp', {
                method: 'POST',
                body: JSON.stringify({ id, nama, jumlah_tamu: jumlah, status, alasan })
            });
            closeModal('rsvp');
            loadRSVP();
        }

        async function updateUcapan() {
            const id = document.getElementById('edit-ucapan-id').value;
            const nama = document.getElementById('edit-ucapan-nama').value;
            const pesan = document.getElementById('edit-ucapan-pesan').value;

            await fetch('api/admin_api.php?action=update_ucapan', {
                method: 'POST',
                body: JSON.stringify({ id, nama, pesan })
            });
            closeModal('ucapan');
            loadUcapan();
        }

        async function deleteItem(type, id) {
            if (!confirm(`Hapus data ini?`)) return;
            await fetch(`api/admin_api.php?action=delete_${type}&id=${id}`);
            if (type === 'rsvp') loadRSVP();
            if (type === 'ucapan') loadUcapan();
        }

        // Music Management Functions
        async function loadMusic() {
            const res = await fetch(`api/admin_api.php?action=get_music&inv_id=${currentInvId}`);
            const data = await res.json();
            const list = document.getElementById('music-list');

            if (data.data.length === 0) {
                list.innerHTML = '<div class="p-6 text-center text-secondary/60 italic">Belum ada lagu yang diupload</div>';
                return;
            }

            list.innerHTML = data.data.map(music => `
                <div class="p-6 flex items-center justify-between hover:bg-secondary/5 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-secondary/10 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-secondary">${music.is_active ? 'music_note' : 'music_off'}</span>
                        </div>
                        <div>
                            <p class="font-semibold text-sm">${music.file_name}</p>
                            <p class="text-xs text-secondary/60">${formatFileSize(music.file_size)} • ${music.is_active ? 'Aktif' : 'Tidak Aktif'}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <audio controls class="h-8">
                            <source src="${music.file_path}" type="audio/mpeg">
                        </audio>
                        <button onclick="toggleActiveMusic(${music.id})" class="px-3 py-1 rounded-lg text-xs font-bold ${music.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'} hover:scale-105 transition-all">
                            ${music.is_active ? 'Aktif' : 'Nonaktif'}
                        </button>
                        <button onclick="deleteMusic(${music.id})" class="text-red-400 hover:scale-110 p-2">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </div>
                </div>
            `).join('');
        }

        async function uploadMusic() {
            const input = document.getElementById('music-upload');
            if (!input.files.length) return;

            const file = input.files[0];

            // Validate file type
            if (!file.type.startsWith('audio/')) {
                alert('Hanya file audio yang diperbolehkan!');
                return;
            }

            // Validate file size (max 10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('Ukuran file maksimal 10MB!');
                return;
            }

            const formData = new FormData();
            formData.append('music', file);

            try {
                const res = await fetch(`api/admin_api.php?action=upload_music&inv_id=${currentInvId}`, {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.success) {
                    alert('Lagu berhasil diupload!');
                    loadMusic();
                } else {
                    alert(data.message || 'Gagal upload lagu');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }

            input.value = '';
        }

        async function toggleActiveMusic(id) {
            const res = await fetch(`api/admin_api.php?action=toggle_active_music&id=${id}`, {
                method: 'POST'
            });
            const data = await res.json();

            if (data.success) {
                loadMusic();
            } else {
                alert(data.message || 'Gagal mengubah status musik');
            }
        }

        async function deleteMusic(id) {
            if (!confirm('Hapus file musik ini?')) return;

            const res = await fetch(`api/admin_api.php?action=delete_music&id=${id}`, {
                method: 'POST'
            });
            const data = await res.json();

            if (data.success) {
                loadMusic();
            } else {
                alert(data.message || 'Gagal menghapus musik');
            }
        }

        async function saveMusicSettings() {
            const volume = document.getElementById('set-music_volume').value;
            const autoplay = document.getElementById('set-music_autoplay').value;

            const settings = {
                music_volume: volume,
                music_autoplay: autoplay
            };

            const res = await fetch(`api/admin_api.php?action=update_settings&inv_id=${currentInvId}`, {
                method: 'POST',
                body: JSON.stringify(settings)
            });
            const data = await res.json();

            if (data.success) {
                alert('Pengaturan musik berhasil disimpan!');
            } else {
                alert('Gagal menyimpan pengaturan musik');
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Bank Logo Upload Function
        async function uploadBankLogo() {
            const input = event.target;
            if (!input.files.length) return;

            const file = input.files[0];

            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Hanya file gambar yang diperbolehkan!');
                return;
            }

            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB!');
                return;
            }

            const formData = new FormData();
            formData.append('image', file);

            try {
                const res = await fetch(`api/admin_api.php?action=upload_bank_logo&inv_id=${currentInvId}`, {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.success) {
                    document.getElementById('set-gift_bank_logo').value = data.path;
                    const preview = document.getElementById('preview-gift_bank_logo');
                    const placeholder = document.getElementById('placeholder-gift_bank_logo');
                    preview.src = data.path;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    alert('Logo bank berhasil diupload! Jangan lupa simpan perubahan.');
                } else {
                    alert(data.message || 'Gagal upload logo bank');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }

            input.value = '';
        }

        // Kisah Kasih (Stories) Management Functions
        async function loadStories() {
            const res = await fetch(`api/admin_api.php?action=get_stories&inv_id=${currentInvId}`);
            const data = await res.json();
            const list = document.getElementById('stories-list');

            if (data.data.length === 0) {
                list.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-secondary/60 italic">Belum ada kisah kasih yang ditambahkan. Silakan tambah di atas.</td></tr>';
                return;
            }

            list.innerHTML = data.data.map(story => {
                // Safely sanitize story object for attributes
                const storyJson = JSON.stringify(story).replace(/"/g, '&quot;');
                return `
                <tr class="border-t border-outline-variant/20 hover:bg-secondary/5 transition-all">
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-secondary/10 text-secondary rounded-full font-semibold text-xs">${story.tahun}</span>
                    </td>
                    <td class="px-6 py-4 font-semibold text-primary">${story.judul}</td>
                    <td class="px-6 py-4 text-xs leading-relaxed max-w-md line-clamp-2">${story.isi}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="openEditStory(${storyJson})" class="text-secondary hover:scale-110" title="Edit"><span class="material-symbols-outlined text-sm">edit</span></button>
                            <button onclick="deleteStory(${story.id})" class="text-red-400 hover:scale-110" title="Hapus"><span class="material-symbols-outlined text-sm">delete</span></button>
                        </div>
                    </td>
                </tr>
            `}).join('');
        }

        async function addStory() {
            const tahun = document.getElementById('new-story-tahun').value.trim();
            const judul = document.getElementById('new-story-judul').value.trim();
            const isi = document.getElementById('new-story-isi').value.trim();

            if (!tahun || !judul || !isi) {
                alert('Semua field kisah harus diisi!');
                return;
            }

            const res = await fetch(`api/admin_api.php?action=add_story&inv_id=${currentInvId}`, {
                method: 'POST',
                body: JSON.stringify({ tahun, judul, isi })
            });
            const data = await res.json();
            if (data.success) {
                document.getElementById('new-story-tahun').value = '';
                document.getElementById('new-story-judul').value = '';
                document.getElementById('new-story-isi').value = '';
                loadStories();
            } else {
                alert('Gagal menambahkan kisah.');
            }
        }

        function openEditStory(story) {
            document.getElementById('edit-story-id').value = story.id;
            document.getElementById('edit-story-tahun').value = story.tahun;
            document.getElementById('edit-story-judul').value = story.judul;
            document.getElementById('edit-story-isi').value = story.isi;
            document.getElementById('modal-story').classList.remove('hidden');
        }

        async function updateStory() {
            const id = document.getElementById('edit-story-id').value;
            const tahun = document.getElementById('edit-story-tahun').value.trim();
            const judul = document.getElementById('edit-story-judul').value.trim();
            const isi = document.getElementById('edit-story-isi').value.trim();

            if (!tahun || !judul || !isi) {
                alert('Semua field kisah harus diisi!');
                return;
            }

            const res = await fetch('api/admin_api.php?action=update_story', {
                method: 'POST',
                body: JSON.stringify({ id, tahun, judul, isi })
            });
            const data = await res.json();
            if (data.success) {
                closeModal('story');
                loadStories();
            } else {
                alert('Gagal memperbarui kisah.');
            }
        }

        async function deleteStory(id) {
            if (!confirm('Hapus kisah ini?')) return;
            const res = await fetch(`api/admin_api.php?action=delete_story&id=${id}`);
            const data = await res.json();
            if (data.success) {
                loadStories();
            } else {
                alert('Gagal menghapus kisah.');
            }
        }

        // Design Theme (Tema Desain) JavaScript functions
        const themePresets = {
            'classic-gold': {
                primary: '#361f1a',
                secondary: '#775a19',
                background: '#fbf9f5',
                primary_container: '#4e342e',
                secondary_container: '#fed488',
                surface_container_low: '#f5f3ef'
            },
            'royal-emerald': {
                primary: '#064e3b',
                secondary: '#0f766e',
                background: '#f0fdf4',
                primary_container: '#022c22',
                secondary_container: '#ccfbf1',
                surface_container_low: '#e8f5e9'
            },
            'navy-sapphire': {
                primary: '#1e3a8a',
                secondary: '#3b82f6',
                background: '#f8fafc',
                primary_container: '#172554',
                secondary_container: '#dbeafe',
                surface_container_low: '#f1f5f9'
            },
            'burgundy-ruby': {
                primary: '#4c0519',
                secondary: '#9f1239',
                background: '#fff5f5',
                primary_container: '#310410',
                secondary_container: '#ffe4e6',
                surface_container_low: '#ffebee'
            },
            'sakura-rose': {
                primary: '#831843',
                secondary: '#db2777',
                background: '#fff1f2',
                primary_container: '#500724',
                secondary_container: '#fce7f3',
                surface_container_low: '#ffe0b2'
            },
            'elegant-charcoal': {
                primary: '#18181b',
                secondary: '#52525b',
                background: '#fafafa',
                primary_container: '#09090b',
                secondary_container: '#f4f4f5',
                surface_container_low: '#f4f4f5'
            },
            'forest-sage': {
                primary: '#2f3e22',
                secondary: '#708238',
                background: '#fcfbfa',
                primary_container: '#1a2413',
                secondary_container: '#e9f2d1',
                surface_container_low: '#f4f3ef'
            },
            'sunset-ochre': {
                primary: '#5f1d0a',
                secondary: '#b85906',
                background: '#fdfaf7',
                primary_container: '#3d1004',
                secondary_container: '#ffe5d3',
                surface_container_low: '#f7f0e9'
            },
            'sweet-lavender': {
                primary: '#3b1d5f',
                secondary: '#8b5cf6',
                background: '#faf8ff',
                primary_container: '#230f3c',
                secondary_container: '#ede9fe',
                surface_container_low: '#f3f0fa'
            },
            'terracotta-rust': {
                primary: '#451a03',
                secondary: '#ca8a04',
                background: '#fffbeb',
                primary_container: '#2d1000',
                secondary_container: '#fef9c3',
                surface_container_low: '#fef3c7'
            },
            'ocean-turquoise': {
                primary: '#065f46',
                secondary: '#0ea5e9',
                background: '#f0f9ff',
                primary_container: '#022c22',
                secondary_container: '#e0f2fe',
                surface_container_low: '#e5f3f9'
            },
            'vintage-plum': {
                primary: '#471825',
                secondary: '#a21caf',
                background: '#fdf4ff',
                primary_container: '#300c16',
                secondary_container: '#fae8ff',
                surface_container_low: '#fbf0fc'
            },
            'midnight-gold': {
                primary: '#0f172a',
                secondary: '#d97706',
                background: '#f8fafc',
                primary_container: '#020617',
                secondary_container: '#fef3c7',
                surface_container_low: '#f1f5f9'
            },
            'blossom-sakura': {
                primary: '#500724',
                secondary: '#fb7185',
                background: '#fff1f2',
                primary_container: '#310415',
                secondary_container: '#ffe4e6',
                surface_container_low: '#ffe4e6'
            },
            'autumn-maple': {
                primary: '#781a08',
                secondary: '#d97706',
                background: '#fffbeb',
                primary_container: '#4c1005',
                secondary_container: '#fef3c7',
                surface_container_low: '#fef8e2'
            },
            'desert-sand': {
                primary: '#451a03',
                secondary: '#a16207',
                background: '#fafaf9',
                primary_container: '#291002',
                secondary_container: '#fef08a',
                surface_container_low: '#f5f5f4'
            },
            'soft-mint': {
                primary: '#064e3b',
                secondary: '#34d399',
                background: '#f0fdf4',
                primary_container: '#022c22',
                secondary_container: '#d1fae5',
                surface_container_low: '#e6f9f0'
            },
            'royal-purple': {
                primary: '#4c1d95',
                secondary: '#d97706',
                background: '#faf5ff',
                primary_container: '#2e1065',
                secondary_container: '#f3e8ff',
                surface_container_low: '#f3e8ff'
            },
            'espresso-caramel': {
                primary: '#2d1e18',
                secondary: '#b45309',
                background: '#fafaf9',
                primary_container: '#1c120e',
                secondary_container: '#ffedd5',
                surface_container_low: '#f4f3f2'
            },
            'steel-blue': {
                primary: '#0f2d4a',
                secondary: '#5b80a4',
                background: '#f4f7fa',
                primary_container: '#081a2c',
                secondary_container: '#e2edf8',
                surface_container_low: '#e9eff5'
            }
        };

        function selectPreset(name) {
            const presetField = document.getElementById('set-theme_preset');
            if (presetField) presetField.value = name;

            // Highlight active preset card
            document.querySelectorAll('[id^="preset-"]').forEach(el => {
                el.classList.remove('border-secondary', 'bg-secondary/5', 'ring-4', 'ring-secondary/20', 'shadow-lg', '-translate-y-1');
                el.classList.add('border-secondary/20', 'bg-white', 'shadow-sm');
            });
            const activeCard = document.getElementById('preset-' + name);
            if (activeCard) {
                activeCard.classList.remove('border-secondary/20', 'bg-white', 'shadow-sm');
                activeCard.classList.add('border-secondary', 'bg-secondary/5', 'ring-4', 'ring-secondary/20', 'shadow-lg', '-translate-y-1');
            }

            // Toggle checkmark icons
            document.querySelectorAll('[id^="check-"]').forEach(el => {
                el.classList.add('hidden');
            });
            const activeCheck = document.getElementById('check-' + name);
            if (activeCheck) {
                activeCheck.classList.remove('hidden');
            }

            // Apply preset colors to inputs
            const colors = themePresets[name];
            if (colors) {
                for (const [key, val] of Object.entries(colors)) {
                    const picker = document.getElementById('set-theme_' + key);
                    const txt = document.getElementById('set-theme_' + key + '_text');
                    if (picker) picker.value = val;
                    if (txt) txt.value = val;
                }
            }
        }

        function markCustom() {
            const presetField = document.getElementById('set-theme_preset');
            if (presetField) presetField.value = 'custom';

            // Remove highlight from all presets
            document.querySelectorAll('[id^="preset-"]').forEach(el => {
                el.classList.remove('border-secondary', 'bg-secondary/5', 'ring-4', 'ring-secondary/20', 'shadow-lg', '-translate-y-1');
                el.classList.add('border-secondary/20', 'bg-white', 'shadow-sm');
            });

            // Hide all checkmarks
            document.querySelectorAll('[id^="check-"]').forEach(el => {
                el.classList.add('hidden');
            });

            // Sync values from picker to text fields
            const fields = ['primary', 'secondary', 'background', 'primary_container', 'secondary_container', 'surface_container_low'];
            fields.forEach(f => {
                const picker = document.getElementById('set-theme_' + f);
                const txt = document.getElementById('set-theme_' + f + '_text');
                if (picker && txt) {
                    txt.value = picker.value;
                }
            });
        }

        function updateColorFromText(key) {
            const txt = document.getElementById('set-' + key + '_text');
            const picker = document.getElementById('set-' + key);

            if (txt && picker) {
                let val = txt.value.trim();
                if (!val.startsWith('#')) val = '#' + val;
                if (/^#[0-9A-F]{6}$/i.test(val)) {
                    picker.value = val;
                    txt.value = val;
                    markCustom();
                } else {
                    txt.value = picker.value; // revert
                }
            }
        }

        init();
    </script>
    <!-- Modal Bulk Guests -->
    <div id="modal-bulk-guests" class="modal fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-lg rounded-3xl p-8 shadow-2xl">
            <h3 class="font-display-lg text-2xl text-primary mb-2">Tambah Banyak Tamu</h3>
            <p class="text-xs text-secondary/60 mb-6 italic">Masukkan daftar nama tamu (satu nama per baris).<br>Gunakan
                format <b>Nama, No HP</b> jika ingin sekaligus mengisi nomornya.</p>
            <textarea id="bulk-names" rows="10"
                class="w-full p-4 bg-secondary/5 border border-secondary/20 rounded-2xl outline-none text-sm font-semibold mb-6"
                placeholder="Contoh:&#10;Budi Santoso, 628123456789&#10;Siti Aminah, 628987654321&#10;Keluarga Bapak Ahmad"></textarea>
            <div class="flex gap-3">
                <button onclick="closeModal('bulk-guests')"
                    class="flex-1 py-3 rounded-xl font-bold text-xs uppercase bg-gray-100">Batal</button>
                <button onclick="bulkAddGuests()"
                    class="flex-1 py-3 rounded-xl font-bold text-xs uppercase btn-theme">Impor Sekarang</button>
            </div>
        </div>
    </div>
</body>

</html>