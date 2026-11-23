<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Override default alert with SweetAlert2
        window.alert = function (message) {
            let iconType = 'info';
            let msgStr = String(message).toLowerCase();
            if (msgStr.includes('berhasil') || msgStr.includes('sukses') || msgStr.includes('disalin') || msgStr.includes('disimpan')) {
                iconType = 'success';
            } else if (msgStr.includes('gagal') || msgStr.includes('error') || msgStr.includes('salah') || msgStr.includes('maksimal')) {
                iconType = 'error';
            } else if (msgStr.includes('peringatan') || msgStr.includes('harus diisi') || msgStr.includes('pilih')) {
                iconType = 'warning';
            }

            Swal.fire({
                text: message,
                icon: iconType,
                confirmButtonColor: '#775a19',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-6 py-2 rounded-xl text-sm tracking-wider font-bold uppercase'
                }
            });
        };
    </script>
    <script>
        // Helper SweetAlert2 bertema emas & krem, ala prototipe v2.
        function showSwalSuccess(title, text) {
            return Swal.fire({
                icon: 'success',
                title: title,
                text: text,
                iconColor: '#10B981',
                confirmButtonText: 'Selesai',
                confirmButtonColor: '#7A5B1D',
                timer: 2600,
                timerProgressBar: true
            });
        }
        function showSwalInfo(title, text) {
            return Swal.fire({
                icon: 'info',
                title: title,
                text: text,
                iconColor: '#7A5B1D',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#7A5B1D'
            });
        }
    </script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#361f1a',
                        secondary: '#775a19',
                        cream: { 50: '#FDFCFA', 100: '#FAF8F5', 200: '#F3EFEA', 300: '#E6DFD5' },
                        gold: { 50: '#FAF6EE', 100: '#F2E8D5', 200: '#E3CFA6', 500: '#9A7426', 600: '#7A5B1D', 700: '#624815', 800: '#4B360F' }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif']
                    },
                    boxShadow: {
                        soft: '0 4px 24px -4px rgba(61, 47, 24, 0.06)',
                        card: '0 2px 12px -2px rgba(61, 47, 24, 0.04)'
                    }
                }
            }
        };
    </script>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&family=Playfair+Display:wght@700&family=Instrument+Serif&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

        .rsvp-field input:focus,
        .rsvp-field select:focus,
        .rsvp-field textarea:focus {
            border-color: #775a19 !important;
            outline: none !important;
            box-shadow: 0 0 0 1px #775a19 !important;
        }

        .rsvp-field input,
        .rsvp-field select,
        .rsvp-field textarea {
            accent-color: #775a19;
        }

        .location-mode-status {
            border-left: 3px solid #775a19;
            background: rgba(119, 90, 25, 0.08);
            color: #4e342e;
        }

        /* Dropdown berikon di Data Utama:
           ikon kiri + chevron kanan, di luar <select> (option native tak bisa berikon).
           Select .rsvp-field punya padding atas 1.6rem / bawah 0.5rem, jadi ikon
           digeser 0.55rem dari tengah agar sejajar garis teks. */
        .select-icon-wrap { position: relative; }
        .rsvp-field .select-icon-wrap select {
            padding-left: 2.25rem;
            padding-right: 2rem;
        }
        .select-icon-wrap .sel-ico,
        .select-icon-wrap .sel-chev {
            position: absolute;
            top: 50%;
            margin-top: 0.55rem;
            transform: translateY(-50%);
            pointer-events: none;
            color: #775a19;
            font-size: 1.15rem;
            line-height: 1;
        }
        .select-icon-wrap .sel-ico { left: 0.5rem; }
        .select-icon-wrap .sel-chev { right: 0.5rem; }

        .mobile-admin-bar {
            display: none;
        }

        @media (max-width: 767px) {
            body {
                display: block;
            }

            .mobile-admin-bar {
                display: flex;
                position: fixed;
                inset: 0 0 auto;
                height: 4rem;
                align-items: center;
                justify-content: space-between;
                padding: 0 1rem;
                background: rgba(251, 249, 245, 0.96);
                border-bottom: 1px solid rgba(119, 90, 25, 0.15);
                backdrop-filter: blur(10px);
                z-index: 40;
            }

            .mobile-sidebar {
                display: flex !important;
                width: min(85vw, 20rem);
                transform: translateX(-105%);
                transition: transform 0.25s ease;
                box-shadow: 12px 0 30px rgba(78, 52, 46, 0.12);
            }

            .mobile-sidebar.is-open {
                transform: translateX(0);
            }

            .admin-sidebar-backdrop {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(27, 28, 26, 0.35);
                z-index: 45;
            }

            .admin-sidebar-backdrop.is-visible {
                display: block;
            }

            main {
                width: 100%;
                margin-left: 0 !important;
                padding: 5.25rem 1rem 2rem !important;
            }

            main>div {
                width: 100%;
            }

            .tab-content h2 {
                font-size: 1.75rem;
                line-height: 1.2;
            }

            .tab-content>div:first-child {
                align-items: flex-start;
                flex-wrap: wrap;
                gap: 1rem;
                margin-bottom: 1.5rem;
            }

            .tab-content>div:first-child>button,
            .tab-content>div:first-child>label {
                width: 100%;
                text-align: center;
            }

            .data-card,
            .bg-white.rounded-3xl {
                min-width: 0;
            }

            .data-card {
                padding: 1.25rem !important;
            }

            .data-card .grid-cols-3 {
                grid-template-columns: 1fr;
            }

            .data-card .grid-cols-4 {
                grid-template-columns: 1fr;
            }

            .rsvp-field input,
            .rsvp-field select,
            .rsvp-field textarea {
                max-width: 100%;
            }

            .table-scroll {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table-scroll table {
                min-width: 38rem;
            }

            #tab-guests>div:first-child>div {
                display: grid;
                grid-template-columns: 1fr;
                width: 100%;
            }

            #tab-guests>div:first-child input,
            #tab-guests>div:first-child button {
                width: 100%;
            }

            .modal {
                padding: 1rem;
            }

            .modal>div {
                max-height: calc(100vh - 2rem);
                overflow-y: auto;
            }
        }

        /* === Admin v2 (prototipe Fahmi): palet krem/emas, font Plus Jakarta Sans === */
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #DDD5C9; border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #B8AC9B; }
        @keyframes slideUpFade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: slideUpFade 0.25s ease-out forwards; }
        .tab-btn.active:hover { background-color: #775a19; color: white; }

        /* Input ala prototipe: membulat, krem, fokus emas (menimpa styles.css) */
        .rsvp-field label { display: block; position: static; pointer-events: auto; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #78716C; margin-bottom: 6px; }
        .rsvp-field input, .rsvp-field select, .rsvp-field textarea {
            width: 100%; padding: 0.625rem 0.875rem; border-radius: 0.75rem;
            background: rgba(250, 248, 245, 0.7); border: 1px solid #E6DFD5;
            font-size: 0.875rem; font-weight: 500; color: #1C1917;
        }
        .rsvp-field input:focus, .rsvp-field select:focus, .rsvp-field textarea:focus {
            background: #fff; border-color: #7A5B1D !important;
            box-shadow: 0 0 0 3px rgba(122, 91, 29, 0.15) !important; outline: none !important;
        }
        /* Ikon select kini sejajar tengah (padding simetris, bukan 1.6rem atas) */
        .select-icon-wrap .sel-ico, .select-icon-wrap .sel-chev { margin-top: 0; }
        /* Kustomisasi tema SweetAlert2 agar menyatu dengan palet emas & krem (ala prototipe) */
        .swal2-popup { font-family: 'Plus Jakarta Sans', sans-serif !important; border-radius: 1.5rem !important; background-color: #FDFCFA !important; border: 1px solid #E6DFD5 !important; color: #292524 !important; padding: 1.75rem !important; }
        .swal2-title { font-weight: 700 !important; font-size: 1.25rem !important; color: #1C1917 !important; }
        .swal2-html-container { font-size: 0.875rem !important; color: #57534E !important; }
        .swal2-confirm { border-radius: 0.75rem !important; font-weight: 700 !important; font-size: 0.8125rem !important; padding: 0.65rem 1.35rem !important; box-shadow: 0 2px 6px rgba(122, 91, 29, 0.2) !important; }
        .swal2-cancel { border-radius: 0.75rem !important; font-weight: 700 !important; font-size: 0.8125rem !important; padding: 0.65rem 1.35rem !important; }
    </style>
</head>

<body class="flex">
    <div class="mobile-admin-bar">
        <button type="button" onclick="toggleAdminSidebar()" class="text-primary p-2" aria-label="Buka menu admin">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <span id="mobileCurrentEvent" class="font-title-sm text-primary text-sm truncate">Admin Panel</span>
        <span class="w-10"></span>
    </div>
    <div class="admin-sidebar-backdrop" onclick="toggleAdminSidebar()"></div>
    <!-- Sidebar -->
    <aside class="sidebar mobile-sidebar w-80 fixed h-full flex flex-col z-50">
        <div class="px-5 pt-5 pb-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-gold-600 to-gold-700 text-white flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-lg">auto_awesome</span>
                </div>
                <div>
                    <h1 class="font-bold text-stone-900 text-base tracking-tight">Admin Panel V2</h1>
                    <p class="text-[11px] text-stone-500 font-medium">Manajemen Undangan Digital</p>
                </div>
            </div>
            <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden p-1.5 rounded-lg text-stone-400 hover:text-stone-700 hover:bg-cream-200">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Daftar Undangan (pengganti dropdown) -->
        <div class="px-4 py-3 border-b border-cream-300/70 bg-cream-100/50">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-stone-500 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm text-gold-600">layers</span>
                    Daftar Undangan
                </span>
                <div class="flex items-center gap-1.5">
                    <button onclick="addNewInvitation()" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gold-600 hover:bg-gold-700 text-white text-xs font-semibold shadow-sm transition cursor-pointer" title="Buat Undangan Baru">
                        <span class="material-symbols-outlined text-sm">add</span>
                        <span>Baru</span>
                    </button>
                </div>
            </div>
            <div class="space-y-2 mb-2.5">
                <div class="relative">
                    <span class="material-symbols-outlined text-sm text-stone-400 absolute left-2.5 top-1/2 -translate-y-1/2">search</span>
                    <input type="text" id="inv-search" oninput="renderInvitationList()" placeholder="Cari nama mempelai..." class="w-full pl-8 pr-3 py-1.5 text-xs bg-white border border-cream-300 rounded-lg focus:outline-none focus:border-gold-600 focus:ring-2 focus:ring-gold-600/15 placeholder:text-stone-400 transition">
                </div>
                <div class="grid grid-cols-3 gap-1 p-0.5 bg-cream-200/80 rounded-lg text-[11px] font-semibold">
                    <button type="button" onclick="setInvFilter('all')" id="filter-all" class="py-1 rounded-md bg-white text-gold-700 shadow-sm transition cursor-pointer">Semua (0)</button>
                    <button type="button" onclick="setInvFilter('active')" id="filter-active" class="py-1 rounded-md text-stone-600 hover:text-stone-900 transition cursor-pointer">Aktif (0)</button>
                    <button type="button" onclick="setInvFilter('past')" id="filter-past" class="py-1 rounded-md text-stone-600 hover:text-stone-900 transition cursor-pointer">Selesai (0)</button>
                </div>
            </div>
            <div id="invitationCardList" class="space-y-1.5 max-h-48 overflow-y-auto custom-scrollbar pr-1">
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto custom-scrollbar px-3 py-3 space-y-1">
            <div class="px-2.5 pb-1 text-[10px] font-bold uppercase tracking-wider text-stone-400">Pengaturan Konten</div>
            <button onclick="showTab('settings')"
                class="tab-btn w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-left text-sm font-medium text-stone-600 hover:bg-cream-200/70 hover:text-stone-900 transition cursor-pointer active"
                id="btn-settings">
                <span class="material-symbols-outlined">settings</span> Data Utama
            </button>
            <button onclick="showTab('gallery')"
                class="tab-btn w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-left text-sm font-medium text-stone-600 hover:bg-cream-200/70 hover:text-stone-900 transition cursor-pointer"
                id="btn-gallery">
                <span class="material-symbols-outlined">image</span> Galeri Foto
            </button>
            <button onclick="showTab('guests')"
                class="tab-btn w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-left text-sm font-medium text-stone-600 hover:bg-cream-200/70 hover:text-stone-900 transition cursor-pointer"
                id="btn-guests">
                <span class="material-symbols-outlined">group</span> Daftar Tamu
            </button>
            <button onclick="showTab('checkin')"
                class="tab-btn w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-left text-sm font-medium text-stone-600 hover:bg-cream-200/70 hover:text-stone-900 transition cursor-pointer"
                id="btn-checkin">
                <span class="material-symbols-outlined">qr_code_scanner</span> Check-in Tamu
            </button>
            <button onclick="showTab('rsvp')"
                class="tab-btn w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-left text-sm font-medium text-stone-600 hover:bg-cream-200/70 hover:text-stone-900 transition cursor-pointer"
                id="btn-rsvp">
                <span class="material-symbols-outlined">how_to_reg</span> RSVP
            </button>
            <button onclick="showTab('messages')"
                class="tab-btn w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-left text-sm font-medium text-stone-600 hover:bg-cream-200/70 hover:text-stone-900 transition cursor-pointer"
                id="btn-messages">
                <span class="material-symbols-outlined">chat</span> Ucapan
            </button>
            <div class="pt-2.5 pb-1 px-2.5 text-[10px] font-bold uppercase tracking-wider text-stone-400">Personalisasi</div>
            <button onclick="showTab('music')"
                class="tab-btn w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-left text-sm font-medium text-stone-600 hover:bg-cream-200/70 hover:text-stone-900 transition cursor-pointer"
                id="btn-music">
                <span class="material-symbols-outlined">music_note</span> Musik
            </button>
            <button onclick="showTab('stories')"
                class="tab-btn w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-left text-sm font-medium text-stone-600 hover:bg-cream-200/70 hover:text-stone-900 transition cursor-pointer"
                id="btn-stories">
                <span class="material-symbols-outlined">auto_stories</span> Kisah Kasih
            </button>
            <button onclick="showTab('theme')"
                class="tab-btn w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-left text-sm font-medium text-stone-600 hover:bg-cream-200/70 hover:text-stone-900 transition cursor-pointer"
                id="btn-theme">
                <span class="material-symbols-outlined">palette</span> Tema Desain
            </button>
            <button onclick="openGenerator()"
                class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-left text-sm font-medium text-stone-600 hover:bg-cream-200/70 hover:text-stone-900 transition cursor-pointer">
                <span class="material-symbols-outlined">link</span> Link Generator
            </button>
        </nav>

        <div class="p-3 border-t border-cream-300/70 bg-cream-50">
            <a href="logout"
                class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-left text-sm font-semibold text-red-600 hover:bg-red-50 transition cursor-pointer">
                <span class="material-symbols-outlined">logout</span> Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 md:ml-80 p-4 sm:p-6 lg:p-8">
        <div class="max-w-6xl mx-auto space-y-6">

            <!-- Statistik Dashboard -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl p-5 border border-cream-300/80 shadow-card flex items-center justify-between hover:border-emerald-300/80 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-2xl">celebration</span>
                        </div>
                        <div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl font-bold text-stone-900" id="stat-active">&ndash;</span>
                                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Tayang</span>
                            </div>
                            <p class="text-xs font-bold tracking-wider uppercase text-stone-500 mt-0.5">Undangan Aktif</p>
                        </div>
                    </div>
                    <span class="relative flex h-3 w-3 shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-cream-300/80 shadow-card flex items-center justify-between hover:border-gold-300 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gold-50 border border-gold-200/70 text-gold-600 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-2xl">mail</span>
                        </div>
                        <div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl font-bold text-stone-900" id="stat-total">&ndash;</span>
                                <span class="text-xs font-medium text-gold-700 bg-gold-100/80 px-2 py-0.5 rounded-full">Semua</span>
                            </div>
                            <p class="text-xs font-bold tracking-wider uppercase text-stone-500 mt-0.5">Total Undangan</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-gold-500/60">trending_up</span>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-cream-300/80 shadow-card flex items-center justify-between hover:border-stone-300 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-stone-100 border border-stone-200/80 text-stone-600 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-2xl">history</span>
                        </div>
                        <div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl font-bold text-stone-900" id="stat-past">&ndash;</span>
                                <span class="text-xs font-medium text-stone-600 bg-stone-100 px-2 py-0.5 rounded-full">Arsip</span>
                            </div>
                            <p class="text-xs font-bold tracking-wider uppercase text-stone-500 mt-0.5">Sudah Berlangsung</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-stone-400">check_circle</span>
                </div>
            </div>

            <!-- Banner: undangan terpilih sudah lewat tanggal acara -->
            <div id="pastEventBanner" class="hidden rounded-2xl bg-amber-50/90 border border-amber-200 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 animate-fade-in">
                <div class="flex items-start sm:items-center gap-3">
                    <div class="p-2 rounded-xl bg-amber-100 text-amber-700 shrink-0">
                        <span class="material-symbols-outlined">event_busy</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-amber-900">Acara Undangan Ini Telah Berlangsung</h3>
                        <p class="text-xs text-amber-800/90 mt-0.5">Tanggal acara telah terlewati. Anda tetap dapat melihat riwayat data, ucapan, dan tamu, atau memperbarui tanggal jika acara dijadwalkan ulang.</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-stone-200/80 text-stone-700 self-start sm:self-center shrink-0">
                    <span class="material-symbols-outlined text-sm">archive</span> Status: Selesai
                </span>
            </div>

            <!-- Settings Tab -->
            <div id="tab-settings" class="tab-content">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div>
                        <div class="flex flex-wrap items-center gap-2.5">
                            <h2 class="font-serif text-3xl font-bold text-stone-900 tracking-tight">Pengaturan Undangan</h2>
                            <span id="headerStatusBadge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif</span>
                        </div>
                        <p class="text-sm text-stone-500 mt-1">Mengelola undangan untuk <strong id="headerCoupleSubtitle" class="text-stone-800 font-semibold">&mdash;</strong></p>
                    </div>
                    <div class="flex items-center gap-2.5 self-start md:self-auto">
                        <button onclick="previewInvitation()"
                            class="inline-flex items-center gap-2 px-4 py-3 rounded-full bg-white hover:bg-cream-200/60 text-stone-700 border border-cream-300 text-xs font-bold uppercase tracking-widest shadow-lg transition cursor-pointer">
                            <span class="material-symbols-outlined text-base text-gold-600">open_in_new</span>
                            <span>Preview Undangan</span>
                        </button>
                        <button onclick="saveSettings()"
                            class="btn-theme px-8 py-3 rounded-full font-bold text-xs uppercase tracking-widest shadow-lg">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                    <!-- Mempelai -->
                    <div class="xl:col-span-7 bg-white rounded-3xl border border-cream-300/80 shadow-soft p-5 sm:p-7 space-y-7">
                        <div class="flex items-center justify-between border-b border-cream-200 pb-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-gold-50 text-gold-600 flex items-center justify-center">
                                    <span class="material-symbols-outlined">favorite</span>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-stone-900">Data Mempelai</h3>
                                    <p class="text-xs text-stone-500">Informasi lengkap kedua calon pengantin</p>
                                </div>
                            </div>
                        </div>

                        <!-- Calon Pengantin Pria -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gold-700">
                                <span class="material-symbols-outlined text-base">man</span>
                                <span>Calon Pengantin Pria</span>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-5 items-start">
                                <div class="flex-1 w-full space-y-3.5">
                                    <div class="rsvp-field"><label>NAMA LENGKAP PRIA</label><input type="text" id="set-groom_name"></div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                        <div class="rsvp-field"><label>PANGGILAN PRIA</label><input type="text" id="set-groom_nickname"></div>
                                        <div class="rsvp-field"><label>URUTAN ANAK (PRIA)</label><input type="text" id="set-groom_child_of" placeholder="Contoh: Putra Kedua dari"></div>
                                    </div>
                                    <div class="rsvp-field"><label>NAMA ORANG TUA (PRIA)</label><input type="text" id="set-groom_parents" placeholder="Contoh: Bapak ... & Ibu ..."></div>
                                    <div class="rsvp-field"><label>INSTAGRAM (PRIA) — OPSIONAL</label>
                                        <div class="relative">
                                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-stone-400 text-sm font-medium">@</span>
                                            <input type="text" id="set-groom_instagram" placeholder="Contoh: nama.username" class="!pl-8">
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full sm:w-36 shrink-0 flex flex-row sm:flex-col items-center gap-3 bg-cream-100/70 p-3 rounded-2xl border border-cream-300/70">
                                    <div class="relative w-24 h-28 sm:w-full sm:h-36">
                                        <label class="block w-full h-full bg-secondary/5 rounded-xl border border-dashed border-secondary/30 flex flex-col items-center justify-center cursor-pointer overflow-hidden group">
                                            <img id="preview-groom_photo" src="cowok.jpeg" class="w-full h-full object-cover hidden">
                                            <div id="placeholder-groom_photo" class="text-secondary opacity-40 text-center">
                                                <span class="material-symbols-outlined">add_a_photo</span>
                                                <p class="text-[8px] font-bold">FOTO</p>
                                            </div>
                                            <input type="file" class="hidden" accept="image/*" onchange="uploadProfilePhoto('groom')">
                                            <input type="hidden" id="set-groom_photo">
                                        </label>
                                        <button onclick="deletePhoto('groom')"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 shadow-md hover:bg-red-600 transition-colors z-10"
                                            id="btn-delete-groom_photo" style="display:none;">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                        </button>
                                    </div>
                                    <p class="text-[11px] font-semibold text-stone-700">Foto Mempelai Pria</p>
                                </div>
                            </div>
                        </div>

                        <hr class="border-cream-200">

                        <!-- Calon Pengantin Wanita -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gold-700">
                                <span class="material-symbols-outlined text-base">woman</span>
                                <span>Calon Pengantin Wanita</span>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-5 items-start">
                                <div class="flex-1 w-full space-y-3.5">
                                    <div class="rsvp-field"><label>NAMA LENGKAP WANITA</label><input type="text" id="set-bride_name"></div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                        <div class="rsvp-field"><label>PANGGILAN WANITA</label><input type="text" id="set-bride_nickname"></div>
                                        <div class="rsvp-field"><label>URUTAN ANAK (WANITA)</label><input type="text" id="set-bride_child_of" placeholder="Contoh: Putri Pertama dari"></div>
                                    </div>
                                    <div class="rsvp-field"><label>NAMA ORANG TUA (WANITA)</label><input type="text" id="set-bride_parents" placeholder="Contoh: Bapak ... & Ibu ..."></div>
                                    <div class="rsvp-field"><label>INSTAGRAM (WANITA) — OPSIONAL</label>
                                        <div class="relative">
                                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-stone-400 text-sm font-medium">@</span>
                                            <input type="text" id="set-bride_instagram" placeholder="Contoh: nama.username" class="!pl-8">
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full sm:w-36 shrink-0 flex flex-row sm:flex-col items-center gap-3 bg-cream-100/70 p-3 rounded-2xl border border-cream-300/70">
                                    <div class="relative w-24 h-28 sm:w-full sm:h-36">
                                        <label class="block w-full h-full bg-secondary/5 rounded-xl border border-dashed border-secondary/30 flex flex-col items-center justify-center cursor-pointer overflow-hidden group">
                                            <img id="preview-bride_photo" src="cewek.jpeg" class="w-full h-full object-cover hidden">
                                            <div id="placeholder-bride_photo" class="text-secondary opacity-40 text-center">
                                                <span class="material-symbols-outlined">add_a_photo</span>
                                                <p class="text-[8px] font-bold">FOTO</p>
                                            </div>
                                            <input type="file" class="hidden" accept="image/*" onchange="uploadProfilePhoto('bride')">
                                            <input type="hidden" id="set-bride_photo">
                                        </label>
                                        <button onclick="deletePhoto('bride')"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 shadow-md hover:bg-red-600 transition-colors z-10"
                                            id="btn-delete-bride_photo" style="display:none;">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                        </button>
                                    </div>
                                    <p class="text-[11px] font-semibold text-stone-700">Foto Mempelai Wanita</p>
                                </div>
                            </div>
                        </div>

                        <hr class="border-cream-200">

                        <!-- Foto Preview (OG) -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gold-700">
                                <span class="material-symbols-outlined text-base">share</span>
                                <span>Foto Preview (Link WhatsApp)</span>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4 items-start">
                                <div class="w-full sm:w-48 shrink-0 relative">
                                    <label class="block aspect-[120/63] bg-secondary/5 rounded-xl border border-dashed border-secondary/30 flex flex-col items-center justify-center cursor-pointer overflow-hidden group">
                                        <img id="preview-og_preview_photo" class="w-full h-full object-cover hidden">
                                        <div id="placeholder-og_preview_photo" class="text-secondary opacity-40 text-center">
                                            <span class="material-symbols-outlined">add_a_photo</span>
                                            <p class="text-[8px] font-bold">FOTO PREVIEW</p>
                                        </div>
                                        <input type="file" class="hidden" accept="image/*" onchange="uploadProfilePhoto('og_preview')">
                                        <input type="hidden" id="set-og_preview_photo">
                                    </label>
                                    <button onclick="deletePhoto('og_preview')"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 shadow-md hover:bg-red-600 transition-colors z-10"
                                        id="btn-delete-og_preview_photo" style="display:none;">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </div>
                                <p class="text-[11px] opacity-60 leading-relaxed flex-1">Foto ini yang tampil saat link undangan dibagikan di WhatsApp. Unggah satu foto kedua mempelai (disarankan 1200×630). Jika kosong, dipakai foto mempelai wanita, lalu foto pria.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal -->
                    <div class="xl:col-span-5 bg-white rounded-3xl border border-cream-300/80 shadow-soft p-5 sm:p-7 space-y-5">
                        <div class="flex items-center justify-between border-b border-cream-200 pb-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-gold-50 text-gold-600 flex items-center justify-center">
                                    <span class="material-symbols-outlined">event</span>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-stone-900">Jadwal Acara</h3>
                                    <p class="text-xs text-stone-500">Tanggal, waktu &amp; lokasi pernikahan</p>
                                </div>
                            </div>
                            <span id="daysCountdownChip" class="text-[11px] font-semibold px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 whitespace-nowrap shrink-0"></span>
                        </div>
                        <div class="rsvp-field">
                            <label>MODE LOKASI ACARA</label>
                            <div class="select-icon-wrap">
                                <span id="ico-event_location_mode" class="material-symbols-outlined sel-ico">location_on</span>
                                <select id="set-event_location_mode">
                                    <option value="same">Akad dan resepsi satu tempat</option>
                                    <option value="different">Akad dan resepsi berbeda tempat</option>
                                </select>
                                <span class="material-symbols-outlined sel-chev">expand_more</span>
                            </div>
                        </div>
                        <div class="rsvp-field"><label>TGL AKAD</label><input type="date" id="set-wedding_date" onchange="updateInvHeaderFromForm()"></div>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="rsvp-field"><label>MULAI (ANGKA)</label><input type="text" id="set-wedding_time_start" placeholder="08:00"></div>
                            <div class="rsvp-field"><label>SAMPAI (TEKS)</label><input type="text" id="set-wedding_time_end" placeholder="10:00 atau Selesai"></div>
                            <div class="rsvp-field">
                                <label>ZONA WAKTU</label>
                                <div class="select-icon-wrap">
                                    <span class="material-symbols-outlined sel-ico">schedule</span>
                                    <select id="set-wedding_timezone">
                                        <option value="WIB">WIB</option>
                                        <option value="WITA">WITA</option>
                                        <option value="WIT">WIT</option>
                                    </select>
                                    <span class="material-symbols-outlined sel-chev">expand_more</span>
                                </div>
                            </div>
                        </div>
                        <div class="rsvp-field">
                            <label>LOKASI AKAD</label>
                            <input type="text" id="set-wedding_location" placeholder="Contoh: Kediaman Mempelai Wanita">
                        </div>
                        <div class="rsvp-field">
                            <label>LINK GOOGLE MAPS AKAD</label>
                            <div class="flex gap-2">
                                <input type="text" id="set-wedding_map_link" placeholder="Contoh: https://maps.app.goo.gl/xxxxx" class="flex-1 min-w-0">
                                <button type="button" onclick="openMapsLink('set-wedding_map_link')" class="px-3 py-2.5 rounded-xl bg-white hover:bg-gold-50 text-gold-700 border border-cream-300 text-xs font-semibold shrink-0 transition cursor-pointer flex items-center gap-1" title="Uji Tautan Peta">
                                    <span class="material-symbols-outlined text-base">navigation</span>
                                    <span>Cek</span>
                                </button>
                            </div>
                        </div>

                        <hr class="border-t border-dashed border-cream-300">

                        <div class="rsvp-field"><label>TGL RESEPSI</label><input type="date" id="set-reception_date" onchange="updateInvHeaderFromForm()"></div>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="rsvp-field"><label>MULAI (ANGKA)</label><input type="text" id="set-reception_time_start" placeholder="11:00"></div>
                            <div class="rsvp-field"><label>SAMPAI (TEKS)</label><input type="text" id="set-reception_time_end" placeholder="14:00 atau Selesai"></div>
                            <div class="rsvp-field">
                                <label>ZONA WAKTU</label>
                                <div class="select-icon-wrap">
                                    <span class="material-symbols-outlined sel-ico">schedule</span>
                                    <select id="set-reception_timezone">
                                        <option value="WIB">WIB</option>
                                        <option value="WITA">WITA</option>
                                        <option value="WIT">WIT</option>
                                    </select>
                                    <span class="material-symbols-outlined sel-chev">expand_more</span>
                                </div>
                            </div>
                        </div>
                        <div class="rsvp-field">
                            <label>LOKASI RESEPSI</label>
                            <input type="text" id="set-reception_location" placeholder="Contoh: Gedung Pernikahan ABC">
                        </div>
                        <div class="rsvp-field">
                            <label>LINK GOOGLE MAPS RESEPSI</label>
                            <div class="flex gap-2">
                                <input type="text" id="set-reception_map_link" placeholder="Contoh: https://maps.app.goo.gl/xxxxx" class="flex-1 min-w-0">
                                <button type="button" onclick="openMapsLink('set-reception_map_link')" class="px-3 py-2.5 rounded-xl bg-white hover:bg-gold-50 text-gold-700 border border-cream-300 text-xs font-semibold shrink-0 transition cursor-pointer flex items-center gap-1" title="Uji Tautan Peta">
                                    <span class="material-symbols-outlined text-base">navigation</span>
                                    <span>Cek</span>
                                </button>
                            </div>
                        </div>
                        <div id="location-mode-status"
                            class="location-mode-status rounded-lg px-4 py-3 text-xs leading-relaxed">
                            Pilih mode lokasi acara untuk menentukan tampilan peta pada undangan.
                        </div>
                        <div class="rsvp-field pt-2">
                            <label>LINK LIVE STREAMING (OPSIONAL)</label>
                            <input type="text" id="set-live_stream_link" placeholder="Contoh: https://youtube.com/live/xxxxx atau https://zoom.us/...">
                        </div>
                    </div>

                    <!-- Gift & Alamat -->
                    <div class="xl:col-span-12 bg-white rounded-3xl border border-cream-300/80 shadow-soft p-5 sm:p-7 space-y-6">
                        <div class="flex justify-between items-center">
                            <h3 class="text-base font-bold text-stone-900 flex items-center gap-2">
                                <span class="material-symbols-outlined text-gold-600">payments</span> Gift &amp; Alamat
                            </h3>
                            <button onclick="addBankRow()"
                                class="text-[10px] bg-secondary text-white px-3 py-1 rounded-full font-bold uppercase hover:bg-secondary/80 transition-all">+
                                Tambah Bank</button>
                        </div>

                        <div id="bank-list-container" class="space-y-4">
                            <!-- Bank rows will be injected here -->
                        </div>

                        <div class="border-t border-dashed border-secondary/30 pt-5 space-y-4">
                            <h4 class="text-stone-900 flex items-center gap-2 uppercase text-[11px] font-bold tracking-widest">
                                <span class="material-symbols-outlined text-gold-600">qr_code_2</span> QRIS (Opsional)
                            </h4>
                            <div class="rsvp-field">
                                <label>STATUS QRIS</label>
                                <div class="select-icon-wrap">
                                    <span id="ico-gift_qris_enabled" class="material-symbols-outlined sel-ico">block</span>
                                    <select id="set-gift_qris_enabled">
                                        <option value="0">Nonaktif — hanya transfer bank</option>
                                        <option value="1">Aktif — tamu bisa memilih QRIS atau transfer</option>
                                    </select>
                                    <span class="material-symbols-outlined sel-chev">expand_more</span>
                                </div>
                                <p class="text-[10px] opacity-50 mt-2 italic">*Jika aktif, kartu QRIS tampil di bagian amplop digital undangan, di atas daftar rekening.</p>
                            </div>
                            <div class="flex gap-4 items-start">
                                <div class="w-24 text-center relative">
                                    <label class="block aspect-square bg-secondary/5 rounded-xl border border-dashed border-secondary/30 flex flex-col items-center justify-center cursor-pointer overflow-hidden group">
                                        <img id="preview-gift_qris_image" class="w-full h-full object-contain hidden">
                                        <div id="placeholder-gift_qris_image" class="text-secondary opacity-40">
                                            <span class="material-symbols-outlined">qr_code_2</span>
                                            <p class="text-[8px] font-bold">QRIS</p>
                                        </div>
                                        <input type="file" class="hidden" accept="image/*" onchange="uploadQrisImage()">
                                        <input type="hidden" id="set-gift_qris_image">
                                    </label>
                                </div>
                                <div class="flex-1 space-y-4">
                                    <div class="rsvp-field">
                                        <label>NAMA MERCHANT QRIS</label>
                                        <input type="text" id="set-gift_qris_name" placeholder="Contoh: Nama toko / penerima di QRIS">
                                    </div>
                                    <p class="text-[10px] opacity-50 italic">*Unggah gambar kode QRIS dari aplikasi pembayaran (GoPay, OVO, DANA, m-banking, dll). Tamu tinggal scan untuk mengirim hadiah.</p>
                                </div>
                            </div>
                        </div>

                        <div class="rsvp-field">
                            <label>NAMA PENERIMA HADIAH</label>
                            <input type="text" id="set-gift_recipient_name" placeholder="Nama penerima hadiah">
                        </div>
                        <div class="rsvp-field">
                            <label>ALAMAT PENGIRIMAN</label>
                            <textarea id="set-gift_address" rows="3"></textarea>
                        </div>
                        <div class="rsvp-field">
                            <label>LINK GOOGLE MAPS ALAMAT PENGIRIMAN</label>
                            <div class="flex gap-2">
                                <input type="text" id="set-gift_maps_link" placeholder="Contoh: https://maps.app.goo.gl/xxxxx" class="flex-1 min-w-0">
                                <button type="button" onclick="openMapsLink('set-gift_maps_link')" class="px-3 py-2.5 rounded-xl bg-white hover:bg-gold-50 text-gold-700 border border-cream-300 text-xs font-semibold shrink-0 transition cursor-pointer flex items-center gap-1" title="Uji Tautan Peta">
                                    <span class="material-symbols-outlined text-base">navigation</span>
                                    <span>Cek</span>
                                </button>
                            </div>
                        </div>
                        <div class="rsvp-field">
                            <label>NOMOR WHATSAPP RSVP</label>
                            <input type="text" id="set-rsvp_whatsapp" placeholder="Contoh: 628123456789">
                            <p class="text-[10px] opacity-50 mt-2 italic">*Nomor WhatsApp untuk menerima konfirmasi kehadiran tamu</p>
                        </div>
                        <div class="rsvp-field">
                            <label>QUOTES (OPSIONAL)</label>
                            <textarea id="set-quotes" rows="3" placeholder="Contoh: Allah akan memudahkan jalan bagi hamba-Nya yang bersungguh-sungguh"></textarea>
                            <p class="text-[10px] opacity-50 mt-2 italic">*Quotes akan ditampilkan di halaman utama undangan dengan tanda — Hawa &amp; Adam</p>
                        </div>
                        <div class="rsvp-field">
                            <label>TEMPLATE PESAN WHATSAPP</label>
                            <textarea id="set-wa_template" rows="4" placeholder="Halo [nama], kami mengundang Anda ke pernikahan kami. Cek undangan di: [link]"></textarea>
                            <p class="text-[10px] opacity-50 mt-2 italic">*Gunakan [nama] untuk nama tamu dan [link] untuk link undangan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gallery Tab -->
            <div id="tab-gallery" class="tab-content hidden">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="font-display-lg text-3xl text-primary">Galeri Foto</h2>
                    <div class="flex items-center gap-3">
                        <button onclick="saveSettings()"
                            class="btn-theme px-8 py-3 rounded-full font-bold text-xs uppercase tracking-widest shadow-lg">
                            Simpan Perubahan
                        </button>
                        <label class="btn-theme px-6 py-3 rounded-xl font-bold text-xs uppercase cursor-pointer">
                            + Unggah Foto
                            <input type="file" id="gallery-upload" class="hidden" accept="image/*" onchange="uploadPhoto()">
                        </label>
                    </div>
                </div>
                <p class="text-xs text-gray-500 -mt-4 mb-4">Seret foto untuk mengubah urutannya — urutan ini yang tampil di undangan.</p>
                        <div class="rsvp-field pt-2">
                            <label style="position:static;display:block">VIDEO PREWEDDING (OPSIONAL)</label>
                            <input type="hidden" id="set-prewedding_video_file">
                            <div class="flex flex-col gap-2 mt-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <button type="button" onclick="document.getElementById('prewedding-video-upload').click()"
                                        class="px-4 py-2 rounded-xl bg-secondary text-white text-xs font-bold uppercase hover:bg-secondary/80 transition-all">Pilih File Video</button>
                                    <span id="prewedding-video-name" class="text-xs opacity-60">Belum ada video</span>
                                    <button type="button" id="btn-delete-prewedding_video" onclick="deletePreweddingVideo()"
                                        class="px-3 py-2 rounded-xl bg-red-500 text-white text-xs font-bold uppercase hover:bg-red-600 transition-all" style="display:none;">Hapus</button>
                                </div>
                                <input type="file" id="prewedding-video-upload" class="hidden" accept="video/mp4,video/webm,video/quicktime,.mov,.mkv" onchange="uploadPreweddingVideo()">
                                <video id="preview-prewedding_video" controls preload="metadata" class="hidden w-full max-w-md rounded-xl border border-outline-variant/30"></video>
                                <input type="text" id="set-prewedding_video_link"
                                    placeholder="atau tempel link YouTube, contoh: https://www.youtube.com/watch?v=xxxxx">
                                <p class="text-[11px] opacity-60 leading-relaxed">Unggah file video (MP4/WebM/MOV, maks 100MB) atau tempel link YouTube. Jika keduanya diisi, file yang diunggah yang ditampilkan di undangan.</p>
                            </div>
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
                <div class="table-scroll bg-white rounded-3xl border border-outline-variant/30 shadow-sm">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-secondary/5 text-secondary font-label-caps">
                            <tr>
                                <th class="px-6 py-4">Nama Tamu</th>
                                <th class="px-6 py-4">No HP</th>
                                <th class="px-6 py-4">Kehadiran</th>
                                <th class="px-6 py-4">Link Undangan</th>
                                <th class="px-6 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="guest-list"></tbody>
                    </table>
                </div>
            </div>

            <!-- Check-in Tab -->
            <div id="tab-checkin" class="tab-content hidden">
                <div class="mb-8">
                    <h2 class="font-display-lg text-3xl text-primary">Check-in Tamu</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola kehadiran tamu dengan QR code.</p>
                </div>

                <div class="data-card p-8 rounded-3xl mb-6 space-y-6">
                    <h3 class="font-title-sm text-secondary flex items-center gap-2 uppercase text-xs tracking-widest font-semibold">
                        <span class="material-symbols-outlined">settings</span> Pengaturan Check-in
                    </h3>
                    <label class="flex items-center justify-between gap-4 cursor-pointer bg-secondary/5 rounded-2xl px-5 py-4">
                        <span>
                            <span class="font-bold text-primary">Aktifkan QR Check-in</span><br>
                            <span class="text-xs text-gray-500">Bila aktif, kartu QR muncul di undangan tiap tamu (link personal).</span>
                        </span>
                        <input type="checkbox" id="set-guest_qr_enabled" onchange="toggleQrEnabled(this.checked)" class="w-7 h-7 accent-[#775a19] shrink-0">
                    </label>
                    <div>
                        <div class="text-xs uppercase tracking-widest text-gray-500 mb-2 font-semibold">Link khusus panitia <span class="normal-case font-normal">(tanpa login admin)</span></div>
                        <div class="flex gap-2 flex-wrap">
                            <input type="text" id="ci-public-link" readonly
                                class="flex-1 min-w-[200px] bg-secondary/5 text-xs p-3 rounded-xl border-none outline-none" value="Memuat...">
                            <button onclick="copyCiLink()"
                                class="btn-theme px-5 py-2 rounded-xl font-bold text-xs uppercase flex items-center gap-1"><span class="material-symbols-outlined text-sm">content_copy</span>Salin</button>
                            <button onclick="regenCiToken()"
                                class="bg-amber-100 text-amber-800 hover:bg-amber-200 px-5 py-2 rounded-xl font-bold text-xs uppercase transition-all">Buat Baru</button>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-2">Bagikan link ini ke panitia untuk scan QR di lokasi acara. Buat baru bila link tersebar ke pihak yang tidak berkepentingan.</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="data-card p-6 rounded-3xl text-center">
                        <div class="text-3xl font-bold text-primary" id="ci-total">0</div>
                        <div class="text-xs text-gray-500 uppercase tracking-widest mt-1">Total Tamu</div>
                    </div>
                    <div class="data-card p-6 rounded-3xl text-center">
                        <div class="text-3xl font-bold text-green-700" id="ci-checked">0</div>
                        <div class="text-xs text-gray-500 uppercase tracking-widest mt-1">Sudah Hadir</div>
                    </div>
                    <div class="data-card p-6 rounded-3xl text-center">
                        <div class="text-3xl font-bold text-amber-700" id="ci-pending">0</div>
                        <div class="text-xs text-gray-500 uppercase tracking-widest mt-1">Belum Hadir</div>
                    </div>
                    <div class="data-card p-6 rounded-3xl text-center">
                        <div class="text-3xl font-bold text-sky-700" id="ci-people">0</div>
                        <div class="text-xs text-gray-500 uppercase tracking-widest mt-1">Total Orang Hadir</div>
                    </div>
                </div>

                <div class="data-card p-6 rounded-3xl mb-8">
                    <div class="flex justify-between text-xs text-gray-500 mb-2"><span class="uppercase tracking-widest font-semibold">Progres Kehadiran</span><span id="ci-pct" class="font-bold text-green-800">0%</span></div>
                    <div class="h-3 bg-gray-100 rounded-full overflow-hidden"><div id="ci-prog-fill" class="h-full rounded-full" style="width:0%;background:#15803d"></div></div>
                    <p class="text-xs text-gray-500 mt-2" id="ci-prog-lbl">—</p>
                </div>

                <div class="data-card p-8 rounded-3xl">
                    <h3 class="font-title-sm text-secondary flex items-center gap-2 uppercase text-xs tracking-widest font-semibold mb-2">
                        <span class="material-symbols-outlined">history</span> Baru Saja Hadir
                    </h3>
                    <p class="text-xs text-gray-400 mb-4">Scan dilakukan lewat <strong>link khusus panitia</strong> di atas (tanpa login admin).</p>
                    <div id="ci-recent" class="space-y-2 max-h-[380px] overflow-y-auto">
                        <p class="text-sm text-gray-400 italic">Belum ada check-in.</p>
                    </div>
                </div>

            </div>

            <!-- RSVP Tab -->
            <div id="tab-rsvp" class="tab-content hidden">
                <h2 class="font-display-lg text-3xl text-primary mb-8">RSVP</h2>
                <div class="table-scroll bg-white rounded-3xl shadow-sm">
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

                <div class="table-scroll bg-white rounded-3xl border border-outline-variant/30 shadow-sm">
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
                                <label>TAHUN / LABEL</label>
                                <input type="text" id="new-story-tahun"
                                    placeholder="Contoh: 2025, 2026, atau Pertemuan">
                            </div>
                        </div>
                        <div class="rsvp-field">
                            <label>JUDUL KISAH</label>
                            <input type="text" id="new-story-judul" placeholder="Contoh: Pertemuan Pertama">
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
                    <!-- Model Tampilan Tema (template undangan) -->
                    <div class="data-card p-8 rounded-3xl space-y-4">
                        <h3
                            class="font-title-sm text-secondary flex items-center gap-2 uppercase text-xs tracking-widest font-semibold">
                            <span class="material-symbols-outlined">style</span> Model Tampilan Tema
                        </h3>
                        <p class="text-xs text-gray-500">Pilih desain tampilan undangan. Pilihan warna di bawah hanya berlaku untuk tema Default.</p>
                        <div class="flex gap-2">
                            <input type="text" id="theme-search" autocomplete="off" placeholder="Cari nama tema… (mis. jawa, game, boho)"
                                class="flex-1 border border-gray-200 rounded-2xl px-4 py-3 text-sm bg-white focus:border-secondary focus:outline-none">
                            <button type="button" id="theme-search-clear" title="Hapus pencarian"
                                class="border border-gray-200 rounded-2xl px-4 py-3 text-sm bg-white hover:border-secondary">✕</button>
                        </div>
                        <p id="theme-search-count" class="text-xs text-gray-500"></p>
                        <select id="set-theme" class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm bg-white focus:border-secondary focus:outline-none">
                            <option value="default">Default — Klasik Emas</option>
                            <option value="minecraft">Premium — Minecraft</option>
                            <option value="minecraft-adventure">Premium — Minecraft Petualangan</option>
                            <option value="royal-emerald">Premium — Royal Emerald</option>
                            <option value="jawa-premium">Premium — Jawa Premium</option>
                            <option value="islamic-elegance">Premium — Islamic Elegance</option>
                            <option value="sakura-dream">Premium — Sakura Dream</option>
                            <option value="midnight-galaxy">Premium — Midnight Galaxy</option>
                            <option value="rustic-boho">Premium — Rustic Boho</option>
                            <option value="minang-gadang">Premium — Minang Gadang</option>
                            <option value="taman-janur">Premium — Taman Janur</option>
                            <option value="senja-pesisir">Premium — Senja Pesisir (scroll)</option>
                            <option value="empat-musim">Premium — Empat Musim (scroll)</option>
                            <option value="bawah-laut">Premium — Bawah Laut (scroll)</option>
                            <option value="gunung-berkabut">Premium — Gunung Berkabut (scroll)</option>
                            <option value="sogan-parallax">Premium — Sogan Parallax</option>
                            <option value="rimba-watercolor">Premium — Rimba Watercolor</option>
                            <option value="neon-metropolis">Premium — Neon Metropolis (3D)</option>
                            <option value="arabian-nights">Premium — Arabian Nights (3D)</option>
                            <option value="origami-dreams">Premium — Origami Dreams (3D)</option>
                            <option value="galaksi-cinta">Premium — Galaksi Cinta (3D)</option>
                            <option value="winter-aurora">Premium — Winter Aurora (3D)</option>
                            <option value="stardew-valley">Premium — Stardew Valley (Game)</option>
                            <option value="stardew-adventure">Premium — Stardew Adventure (Main)</option>
                            <option value="jawa-cinematic">Premium — Jawa Cinematic (Mewah)</option>
                            <option value="jawa-arunika">Premium — Jawa Arunika</option>
                            <option value="jawa-klasik">Premium — Jawa Klasik</option>
                            <option value="jawa-pendopo">Premium — Jawa Pendopo (Video Opening)</option>
                            <option value="jawa-gerbang">Premium — Jawa Gerbang (Video Opening)</option>
                            <option value="betawi">Premium — Betawi (Video Opening)</option>
                            <option value="jawa-gunungan">Premium — Jawa Gunungan (Video Opening)</option>
                            <option value="taman-surga">Premium — Taman Surga (Garden Heaven)</option>
                            <option value="jawa-biru">Premium — Jawa Biru (Blue Java)</option>
                            <option value="jawa-puspa">Premium — Jawa Puspa (Klasik Terang)</option>
                            <option value="pixel-quest">Premium — Pixel Quest (Game)</option>
                        </select>
                        <script>
                        /* Cari & urutkan tema (abjad) di dropdown set-theme.
                           Daftar opsi disimpan di JS, diurutkan abjad dari labelnya,
                           lalu dirender ulang setiap pencarian diketik.
                           Pengaman: tema yang sedang terpilih TIDAK PERNAH hilang dari
                           daftar meski tidak cocok dengan kata kunci, supaya nilai yang
                           tersimpan tidak pernah berubah diam-diam saat filter aktif.
                           loadSettings() memanggil renderThemeOptions(nilaiTema) setelah
                           memuat, supaya tema hasil load selalu tampil meski sedang
                           ada filter aktif. */
                        (function () {
                            var sel = document.getElementById('set-theme');
                            var search = document.getElementById('theme-search');
                            var count = document.getElementById('theme-search-count');
                            var clearBtn = document.getElementById('theme-search-clear');
                            if (!sel || !search) return;
                            var all = Array.prototype.map.call(sel.options, function (o) {
                                return { value: o.value, text: o.text };
                            });
                            all.sort(function (a, b) {
                                return a.text.localeCompare(b.text, 'id', { sensitivity: 'base' });
                            });
                            function renderThemeOptions(forceValue) {
                                var q = (search.value || '').trim().toLowerCase();
                                var cur = forceValue || sel.value;
                                var list = all.filter(function (o) {
                                    return !q
                                        || o.text.toLowerCase().indexOf(q) !== -1
                                        || o.value.toLowerCase().indexOf(q) !== -1
                                        || o.value === cur;
                                });
                                sel.innerHTML = '';
                                list.forEach(function (o) {
                                    var op = document.createElement('option');
                                    op.value = o.value;
                                    op.textContent = o.text;
                                    sel.appendChild(op);
                                });
                                if (!list.length) {
                                    var op = document.createElement('option');
                                    op.value = '';
                                    op.textContent = 'Tidak ada tema yang cocok';
                                    op.disabled = true;
                                    sel.appendChild(op);
                                }
                                if (cur) sel.value = cur;
                                if (count) count.textContent = list.length + ' dari ' + all.length + ' tema';
                            }
                            search.addEventListener('input', function () { renderThemeOptions(); });
                            if (clearBtn) clearBtn.addEventListener('click', function () {
                                search.value = '';
                                renderThemeOptions();
                                search.focus();
                            });
                            window.renderThemeOptions = renderThemeOptions;
                            renderThemeOptions();
                        })();
                        </script>
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
                    <div class="rsvp-field"><label>TAHUN / LABEL</label><input type="text" id="edit-story-tahun"></div>
                </div>
                <div class="rsvp-field"><label>JUDUL KISAH</label><input type="text" id="edit-story-judul"></div>
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

        async function loadStats() {
            try {
                const res = await fetch('api/admin_api?action=get_stats');
                const data = await res.json();
                if (data.success) {
                    document.getElementById('stat-active').textContent = data.data.active;
                    document.getElementById('stat-total').textContent = data.data.total;
                    document.getElementById('stat-past').textContent = data.data.past;
                }
            } catch (e) { /* statistik gagal dimuat, abaikan */ }
        }

        // --- Daftar Undangan ala prototipe v2 (kartu, bukan dropdown) ---
        // Tanggal acara per undangan diambil lewat endpoint get_settings yang
        // sudah ada (tanpa mengubah backend) untuk status Aktif/Selesai.
        let invitationsCache = [];
        let invDates = {}; // inv_id -> tanggal acara (reception_date || wedding_date)
        let invGuestCounts = {}; // inv_id -> jumlah tamu (via get_guests)
        let invFilter = 'all';

        function todayStr(){
            const d = new Date();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return d.getFullYear() + '-' + m + '-' + day;
        }
        function isEventPast(dateStr){ return !!dateStr && dateStr < todayStr(); }
        function formatIndoDate(dateStr){
            if (!dateStr) return '-';
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const parts = dateStr.split('-');
            if (parts.length !== 3) return dateStr;
            return parseInt(parts[2], 10) + ' ' + months[parseInt(parts[1], 10) - 1] + ' ' + parts[0];
        }
        function getDaysDiffText(dateStr){
            if (!dateStr) return '';
            const today = new Date(todayStr() + 'T00:00:00');
            const target = new Date(dateStr + 'T00:00:00');
            const diffDays = Math.round((target - today) / 86400000);
            if (diffDays > 0) return diffDays + ' hari lagi';
            if (diffDays === 0) return 'Hari ini!';
            return Math.abs(diffDays) + ' hari lalu';
        }

        async function loadInvitations() {
            loadStats();
            const res = await fetch('api/admin_api?action=get_invitations');
            const data = await res.json();
            invitationsCache = data.data || [];

            if (!invitationsCache.find(inv => inv.id == currentInvId)) {
                currentInvId = invitationsCache[0]?.id || 1;
            }
            const sel = invitationsCache.find(inv => inv.id == currentInvId);
            currentSlug = sel ? sel.slug : '';

            await Promise.all(invitationsCache.map(async (inv) => {
                try {
                    const r = await fetch(`api/admin_api?action=get_settings&inv_id=${inv.id}`);
                    const d = await r.json();
                    invDates[inv.id] = (d.data && (d.data.reception_date || d.data.wedding_date)) || '';
                } catch (e) { invDates[inv.id] = ''; }
                try {
                    const r = await fetch(`api/admin_api?action=get_guests&inv_id=${inv.id}`);
                    const d = await r.json();
                    invGuestCounts[inv.id] = Array.isArray(d.data) ? d.data.length : 0;
                } catch (e) { invGuestCounts[inv.id] = 0; }
            }));
            renderInvitationList();
            updateInvBanner();
        }

        function setInvFilter(status){
            invFilter = status;
            ['all', 'active', 'past'].forEach(key => {
                const btn = document.getElementById('filter-' + key);
                if (!btn) return;
                btn.className = key === status
                    ? 'py-1 rounded-md bg-white text-gold-700 shadow-sm transition cursor-pointer'
                    : 'py-1 rounded-md text-stone-600 hover:text-stone-900 transition cursor-pointer';
            });
            renderInvitationList();
        }

        function renderInvitationList(){
            const container = document.getElementById('invitationCardList');
            if (!container) return;
            const query = (document.getElementById('inv-search')?.value || '').toLowerCase().trim();
            const total = invitationsCache.length;
            const pastCount = invitationsCache.filter(inv => isEventPast(invDates[inv.id] || '')).length;
            const activeCount = total - pastCount;
            const setLbl = (id, txt) => { const el = document.getElementById(id); if (el) el.textContent = txt; };
            setLbl('filter-all', 'Semua (' + total + ')');
            setLbl('filter-active', 'Aktif (' + activeCount + ')');
            setLbl('filter-past', 'Selesai (' + pastCount + ')');

            const filtered = invitationsCache.filter(inv => {
                const past = isEventPast(invDates[inv.id] || '');
                const okFilter = invFilter === 'all' || (invFilter === 'active' && !past) || (invFilter === 'past' && past);
                const okSearch = !query || String(inv.title || '').toLowerCase().includes(query) || String(inv.slug || '').toLowerCase().includes(query);
                return okFilter && okSearch;
            });

            if (!filtered.length) {
                container.innerHTML = '<div class="text-center py-5 px-3 rounded-xl border border-dashed border-cream-300 bg-white/60"><p class="text-xs font-medium text-stone-500">Tidak ada undangan ditemukan</p></div>';
                return;
            }

            const esc = s => String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            container.innerHTML = filtered.map(inv => {
                const past = isEventPast(invDates[inv.id] || '');
                const isSelected = inv.id == currentInvId;
                let cardStyle = '';
                if (isSelected) {
                    cardStyle = past ? 'bg-stone-100 border-stone-400 ring-2 ring-stone-400/20 shadow-sm' : 'bg-white border-gold-600 ring-2 ring-gold-600/20 shadow-sm';
                } else {
                    cardStyle = past ? 'bg-cream-200/40 border-cream-300/60 opacity-75 hover:opacity-100 hover:bg-stone-100/70' : 'bg-white/90 border-cream-300 hover:border-gold-300 hover:bg-white';
                }
                const statusBadge = past
                    ? '<span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-stone-200 text-stone-600 shrink-0">Selesai</span>'
                    : '<span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shrink-0"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif</span>';
                return '<div class="inv-card w-full text-left p-2.5 rounded-xl border transition-all flex items-center justify-between gap-2 ' + cardStyle + '">'
                    + '<button type="button" onclick="selectInvitation(' + inv.id + ')" class="inv-card-select min-w-0 flex-1 text-left cursor-pointer">'
                    + '<span class="block text-xs font-bold truncate ' + (past ? 'text-stone-600' : 'text-stone-900') + '">' + esc(inv.title) + '</span>'
                    + '<span class="flex items-center gap-1.5 mt-0.5 text-[11px] ' + (past ? 'text-stone-400' : 'text-stone-500') + '"><span>' + formatIndoDate(invDates[inv.id] || '') + '</span><span>\u2022</span><span>' + (invGuestCounts[inv.id] ?? 0) + ' tamu</span></span>'
                    + '</button>'
                    + '<span class="flex flex-col items-end gap-1 shrink-0">' + statusBadge
                    + '<span class="flex items-center gap-1">'
                    + '<button type="button" onclick="editInvitation(' + inv.id + ')" class="p-1 rounded-md text-stone-400 hover:text-gold-700 hover:bg-cream-200 transition cursor-pointer" title="Edit nama undangan"><span class="material-symbols-outlined text-sm">edit</span></button>'
                    + '<button type="button" onclick="deleteInvitationById(' + inv.id + ')" class="p-1 rounded-md text-stone-400 hover:text-red-600 hover:bg-red-50 transition cursor-pointer" title="Hapus undangan"><span class="material-symbols-outlined text-sm">delete</span></button>'
                    + '</span></span>'
                    + '</div>';
            }).join('');
        }

        function selectInvitation(id) {
            currentInvId = id;
            const sel = invitationsCache.find(inv => inv.id == id);
            currentSlug = sel ? sel.slug : '';
            localStorage.setItem('lastInvId', currentInvId);
            const mob = document.getElementById('mobileCurrentEvent');
            if (mob && sel) mob.textContent = sel.title;
            renderInvitationList();
            updateInvBanner();
            const activeTab = localStorage.getItem('activeTab') || 'settings';
            showTab(activeTab);
        }

        // Preview undangan: buka halaman publik undangan terpilih di tab baru.
        function previewInvitation() {
            if (!currentSlug) {
                showSwalInfo('Belum Ada Undangan', 'Pilih undangan terlebih dahulu sebelum melihat pratinjau.');
                return;
            }
            window.open('/' + currentSlug, '_blank', 'noopener');
        }

        // Uji tautan Google Maps: buka di tab baru, atau ingatkan bila kosong.
        function openMapsLink(inputId) {
            const url = (document.getElementById(inputId)?.value || '').trim();
            if (url) {
                window.open(url, '_blank', 'noopener');
            } else {
                showSwalInfo('Tautan Kosong', 'Masukkan tautan Google Maps terlebih dahulu sebelum mengecek lokasi.');
            }
        }

        // Banner "telah berlangsung" di atas konten (tanggal acara dari cache;
        // override = tanggal dari form yang sedang diedit).
        function updateInvBanner(dateOverride){
            const banner = document.getElementById('pastEventBanner');
            if (!banner) return;
            const date = (dateOverride !== undefined) ? dateOverride : (invDates[currentInvId] || '');
            banner.classList.toggle('hidden', !isEventPast(date));
        }

        // Header Data Utama: badge status + subtitle + chip hitung mundur,
        // dibaca dari nilai form yang sedang tampil.
        function updateInvHeaderFromForm(){
            const date = (document.getElementById('set-reception_date')?.value || document.getElementById('set-wedding_date')?.value || '');
            const past = isEventPast(date);
            const badge = document.getElementById('headerStatusBadge');
            if (badge) {
                badge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold ' + (past ? 'bg-stone-200/80 text-stone-700 border border-stone-300' : 'bg-emerald-50 text-emerald-700 border border-emerald-200');
                badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full ' + (past ? 'bg-stone-500' : 'bg-emerald-500') + '"></span> ' + (past ? 'Selesai' : 'Aktif') + (date ? ' \u2022 ' + formatIndoDate(date) : '');
            }
            const bride = document.getElementById('set-bride_name')?.value || '';
            const groom = document.getElementById('set-groom_name')?.value || '';
            const sub = document.getElementById('headerCoupleSubtitle');
            if (sub) sub.textContent = (bride && groom) ? (bride + ' & ' + groom) : (invitationsCache.find(inv => inv.id == currentInvId)?.title || '\u2014');
            const chip = document.getElementById('daysCountdownChip');
            if (chip) {
                chip.textContent = getDaysDiffText(date);
                chip.className = 'text-[11px] font-semibold px-2 py-0.5 rounded-md whitespace-nowrap shrink-0 ' + (past ? 'bg-stone-200 text-stone-600' : 'bg-emerald-100 text-emerald-800');
            }
            updateInvBanner(date);
            const mob = document.getElementById('mobileCurrentEvent');
            if (mob) mob.textContent = invitationsCache.find(inv => inv.id == currentInvId)?.title || 'Admin Panel';
        }

        function addNewInvitation() {
            document.getElementById('new-inv-title').value = '';
            document.getElementById('new-inv-slug').value = '';
            openModal('new-invitation');
        }

        async function submitNewInvitation() {
            const title = document.getElementById('new-inv-title').value.trim();
            let slug = document.getElementById('new-inv-slug').value.trim();

            if (!title) {
                alert('Judul undangan harus diisi!');
                return;
            }

            if (!slug) {
                slug = title.toLowerCase().replace(/[^a-z0-9]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
            }

            const res = await fetch('api/admin_api?action=add_invitation', {
                method: 'POST',
                body: JSON.stringify({ title, slug })
            });
            const data = await res.json();
            if (data.success) {
                closeModal('new-invitation');
                currentInvId = data.id;
                currentSlug = slug;
                await loadInvitations();
                const activeTab = localStorage.getItem('activeTab') || 'settings';
                showTab(activeTab);
                alert('Undangan baru berhasil dibuat!');
            } else {
                alert(data.message || 'Gagal membuat undangan.');
            }
        }

        // Edit nama undangan (judul saja; slug tidak berubah supaya tautan
        // tamu yang sudah terkirim tetap jalan).
        async function editInvitation(id) {
            const inv = invitationsCache.find(v => v.id == id);
            if (!inv) return;
            const { value: title } = await Swal.fire({
                title: 'Edit Nama Undangan',
                input: 'text',
                inputValue: inv.title || '',
                inputPlaceholder: 'Nama undangan',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#775a19',
                inputValidator: (v) => { if (!v || !v.trim()) return 'Nama undangan harus diisi!'; },
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-6 py-2 rounded-xl text-sm font-bold uppercase',
                    cancelButton: 'px-6 py-2 rounded-xl text-sm font-bold uppercase'
                }
            });
            if (title === undefined) return;
            const res = await fetch('api/admin_api?action=update_invitation', {
                method: 'POST',
                body: JSON.stringify({ id: id, title: title.trim() })
            });
            const data = await res.json();
            if (data.success) {
                inv.title = title.trim();
                renderInvitationList();
                if (id == currentInvId) {
                    const mob = document.getElementById('mobileCurrentEvent');
                    if (mob) mob.textContent = inv.title;
                }
                showSwalSuccess('Nama Diubah!', 'Nama undangan berhasil diperbarui.');
            } else {
                alert(data.message || 'Gagal mengubah nama undangan.');
            }
        }

        function deleteInvitationById(id) {
            if (id == 1) {
                showSwalInfo('Tidak Dapat Dihapus', 'Undangan utama tidak dapat dihapus.');
                return;
            }

            Swal.fire({
                title: 'Hapus undangan?',
                text: "Semua data terkait undangan ini akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#775a19',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-6 py-2 rounded-xl text-sm font-bold uppercase',
                    cancelButton: 'px-6 py-2 rounded-xl text-sm font-bold uppercase'
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const res = await fetch(`api/admin_api?action=delete_invitation&id=${id}`);
                    const data = await res.json();
                    if (data.success) {
                        showSwalSuccess('Undangan Dihapus!', 'Undangan beserta datanya berhasil dihapus.');
                        const wasCurrent = (id == currentInvId);
                        if (wasCurrent) currentInvId = 1;
                        await loadInvitations();
                        if (wasCurrent) selectInvitation(1);
                    } else {
                        alert(data.message || 'Gagal menghapus undangan.');
                    }
                }
            });
        }

        function openGenerator() {
            if (currentSlug) {
                window.open('/generator/' + currentSlug, '_blank');
            } else {
                alert('Pilih undangan terlebih dahulu');
            }
        }

        function toggleAdminSidebar() {
            document.querySelector('.mobile-sidebar')?.classList.toggle('is-open');
            document.querySelector('.admin-sidebar-backdrop')?.classList.toggle('is-visible');
        }

        function showTab(tabId) {
            if (!document.getElementById('tab-' + tabId)) tabId = 'settings';
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
            if (tabId === 'checkin') loadCheckin();

            document.querySelector('.mobile-sidebar')?.classList.remove('is-open');
            document.querySelector('.admin-sidebar-backdrop')?.classList.remove('is-visible');
        }

        async function loadSettings() {
            const res = await fetch(`api/admin_api?action=get_settings&inv_id=${currentInvId}`);
            const data = await res.json();
            const keys = ['theme', 'groom_name', 'groom_nickname', 'groom_child_of', 'groom_parents', 'groom_photo', 'groom_instagram', 'bride_name', 'bride_nickname', 'bride_child_of', 'bride_parents', 'bride_photo', 'bride_instagram', 'og_preview_photo', 'event_location_mode', 'wedding_date', 'wedding_time_start', 'wedding_time_end', 'wedding_timezone', 'wedding_location', 'wedding_map_link', 'reception_date', 'reception_time_start', 'reception_time_end', 'reception_timezone', 'reception_location', 'reception_map_link', 'live_stream_link', 'prewedding_video_file', 'prewedding_video_link', 'gift_address', 'gift_maps_link', 'gift_recipient_name', 'gift_qris_enabled', 'gift_qris_image', 'gift_qris_name', 'rsvp_whatsapp', 'quotes', 'wa_template', 'music_volume', 'music_autoplay'];
            keys.forEach(k => {
                const el = document.getElementById('set-' + k);
                if (el) el.value = data.data[k] || (k === 'music_volume' ? '50' : (k === 'music_autoplay' ? '1' : ''));

                // Update photo previews
                if (k === 'gift_qris_image') {
                    const preview = document.getElementById('preview-gift_qris_image');
                    const placeholder = document.getElementById('placeholder-gift_qris_image');
                    if (data.data[k]) {
                        preview.src = data.data[k];
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    } else {
                        preview.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                    }
                }
                if (k === 'prewedding_video_file') {
                    updatePreweddingPreview(data.data[k] || '');
                }
                if (k === 'groom_photo' || k === 'bride_photo' || k === 'og_preview_photo') {
                    const preview = document.getElementById('preview-' + k);
                    const placeholder = document.getElementById('placeholder-' + k);
                    const btnDelete = document.getElementById('btn-delete-' + k);
                    if (data.data[k]) {
                        preview.src = data.data[k];
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                        if (btnDelete) btnDelete.style.display = 'block';
                    } else {
                        preview.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                        if (btnDelete) btnDelete.style.display = 'none';
                    }
                }
            });

            // Tema: pastikan nilai hasil load tampil meski daftar sedang difilter pencarian
            if (window.renderThemeOptions && data.data['theme']) window.renderThemeOptions(data.data['theme']);

            let banksData = [];
            if (data.data['gift_banks']) {
                try {
                    banksData = JSON.parse(data.data['gift_banks']);
                } catch (e) {
                    banksData = [];
                }
            } else if (data.data['gift_bank']) {
                banksData = [{
                    name: data.data['gift_bank'],
                    account: data.data['gift_account'] || '',
                    owner: data.data['gift_owner'] || '',
                    logo: data.data['gift_bank_logo'] || ''
                }];
            }
            bankRows = banksData;
            renderBankRows();

            updateLocationModeStatus();
            updateQrisIcon();
            updateInvHeaderFromForm();
        }

        function updateLocationModeStatus() {
            const locationMode = document.getElementById('set-event_location_mode')?.value;
            const status = document.getElementById('location-mode-status');
            const ico = document.getElementById('ico-event_location_mode');
            if (ico) ico.textContent = locationMode === 'different' ? 'alt_route' : 'location_on';
            if (!status || !locationMode) return;

            if (locationMode === 'same') {
                status.textContent = 'Satu tempat: undangan akan menampilkan satu peta dan satu tombol navigasi utama di section Peta Lokasi.';
            } else {
                status.textContent = 'Lokasi berbeda: undangan akan menampilkan tombol navigasi pada masing-masing card dan menyembunyikan section Peta Lokasi.';
            }
        }

        function updateQrisIcon() {
            const ico = document.getElementById('ico-gift_qris_enabled');
            if (!ico) return;
            const v = document.getElementById('set-gift_qris_enabled')?.value;
            ico.textContent = v === '1' ? 'qr_code_2' : 'block';
        }

        async function saveSettings() {
            const settings = {};
            document.querySelectorAll('[id^="set-"]').forEach(el => {
                settings[el.id.replace('set-', '')] = el.value;
            });

            const banks = collectBankData();
            settings['gift_banks'] = JSON.stringify(banks);

            const res = await fetch(`api/admin_api?action=update_settings&inv_id=${currentInvId}`, {
                method: 'POST',
                body: JSON.stringify(settings)
            });
            const data = await res.json();
            if (data.success) {
                if (data.new_slug && data.new_slug !== currentSlug) {
                    currentSlug = data.new_slug;
                }
                // Segarkan daftar undangan (judul/tanggal bisa berubah) + header.
                loadInvitations();
                updateInvHeaderFromForm();
                showSwalSuccess('Perubahan Disimpan!', 'Pengaturan undangan berhasil disimpan.');
            }
        }

        async function loadGallery() {
            const res = await fetch(`api/admin_api?action=get_gallery&inv_id=${currentInvId}`);
            const data = await res.json();
            const list = document.getElementById('gallery-list');
            list.innerHTML = data.data.map((img, idx) => `
                <div class="relative gallery-item group aspect-square overflow-hidden rounded-2xl bg-gray-100 cursor-grab active:cursor-grabbing" draggable="true" data-gid="${img.id}" title="Seret untuk mengubah urutan">
                    <span class="gal-num absolute top-2 left-2 z-10 bg-black/55 text-white text-[10px] font-bold w-6 h-6 rounded-full flex items-center justify-center pointer-events-none">${idx + 1}</span>
                    <img src="${img.image_path}" class="w-full h-full object-cover" draggable="false">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center delete-overlay">
                        <button onclick="deleteGalleryItem(${img.id})" class="bg-red-500 text-white p-2 rounded-full hover:scale-110 transition-all">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>
                </div>
            `).join('');
            initGalleryDrag();
        }

        function initGalleryDrag() {
            const list = document.getElementById('gallery-list');
            if (!list || list.dataset.dragInit) return;
            list.dataset.dragInit = '1';
            let dragEl = null;
            list.addEventListener('dragstart', e => {
                const item = e.target.closest('.gallery-item');
                if (!item) return;
                dragEl = item;
                e.dataTransfer.effectAllowed = 'move';
                try { e.dataTransfer.setData('text/plain', item.dataset.gid); } catch (_) {}
                setTimeout(() => item.classList.add('opacity-40'), 0);
            });
            list.addEventListener('dragend', () => {
                if (dragEl) dragEl.classList.remove('opacity-40');
                dragEl = null;
            });
            list.addEventListener('dragover', e => {
                if (!dragEl) return;
                e.preventDefault();
                const over = e.target.closest('.gallery-item');
                if (!over || over === dragEl) return;
                const rect = over.getBoundingClientRect();
                const after = (e.clientX - rect.left) > rect.width / 2;
                list.insertBefore(dragEl, after ? over.nextSibling : over);
            });
            list.addEventListener('drop', async e => {
                e.preventDefault();
                const items = [...list.querySelectorAll('.gallery-item')];
                items.forEach((el, i) => {
                    const num = el.querySelector('.gal-num');
                    if (num) num.textContent = i + 1;
                });
                const ids = items.map(el => parseInt(el.dataset.gid));
                const res = await fetch(`api/admin_api?action=reorder_gallery&inv_id=${currentInvId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ inv_id: currentInvId, ids })
                });
                const data = await res.json();
                if (!data.success) {
                    alert(data.message || 'Gagal menyimpan urutan foto.');
                    loadGallery();
                }
            });
        }

        function deleteGalleryItem(id) {
            Swal.fire({
                title: 'Hapus foto galeri?',
                text: "Foto akan dihapus dari galeri secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#775a19',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-6 py-2 rounded-xl text-sm font-bold uppercase',
                    cancelButton: 'px-6 py-2 rounded-xl text-sm font-bold uppercase'
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const res = await fetch(`api/admin_api?action=delete_gallery&id=${id}`);
                    const data = await res.json();
                    if (data.success) {
                        alert('Foto berhasil dihapus!');
                        loadGallery();
                    } else {
                        alert(data.message || 'Gagal menghapus foto.');
                    }
                }
            });
        }

        function deletePhoto(type) {
            Swal.fire({
                title: 'Hapus foto?',
                text: "Foto akan dihapus dari data utama.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#775a19',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-6 py-2 rounded-xl text-sm font-bold uppercase',
                    cancelButton: 'px-6 py-2 rounded-xl text-sm font-bold uppercase'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const input = document.getElementById('set-' + type + '_photo');
                    const preview = document.getElementById('preview-' + type + '_photo');
                    const placeholder = document.getElementById('placeholder-' + type + '_photo');
                    const btnDelete = document.getElementById('btn-delete-' + type + '_photo');

                    input.value = '';
                    preview.src = '';
                    preview.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                    if (btnDelete) btnDelete.style.display = 'none';

                    saveSettings();
                }
            });
        }

        async function uploadPhoto() {
            const input = document.getElementById('gallery-upload');
            if (!input.files.length) return;

            const formData = new FormData();
            formData.append('image', input.files[0]);

            const res = await fetch(`api/admin_api?action=upload_gallery&inv_id=${currentInvId}`, {
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

        async function uploadQrisImage() {
            const input = event.target;
            if (!input.files.length) return;
            const formData = new FormData();
            formData.append('image', input.files[0]);
            const res = await fetch(`api/admin_api?action=upload_qris_image&inv_id=${currentInvId}`, {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                document.getElementById('set-gift_qris_image').value = data.path;
                const preview = document.getElementById('preview-gift_qris_image');
                const placeholder = document.getElementById('placeholder-gift_qris_image');
                preview.src = data.path;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                alert('Gambar QRIS berhasil diunggah! Jangan lupa simpan perubahan.');
            }
        }

        async function uploadProfilePhoto(type) {
            const input = event.target;
            if (!input.files.length) return;

            const formData = new FormData();
            formData.append('image', input.files[0]);

            // Use upload_profile_photo to save file to settings (NOT to gallery table)
            const res = await fetch(`api/admin_api?action=upload_profile_photo&inv_id=${currentInvId}`, {
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

                alert('Foto berhasil diunggah! Jangan lupa simpan perubahan.');
            }
        }

        // Fungsi ini sudah digantikan oleh deleteGalleryItem di atas
        // async function deletePhoto(id) { }

        async function uploadPreweddingVideo() {
            const input = document.getElementById('prewedding-video-upload');
            if (!input.files.length) return;
            const formData = new FormData();
            formData.append('video', input.files[0]);
            const res = await fetch(`api/admin_api?action=upload_prewedding_video&inv_id=${currentInvId}`, {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                document.getElementById('set-prewedding_video_file').value = data.path;
                updatePreweddingPreview(data.path, data.name);
                await saveSettings();
            } else {
                alert(data.message || 'Gagal mengunggah video.');
            }
            input.value = '';
        }

        function updatePreweddingPreview(path, name) {
            const nameEl = document.getElementById('prewedding-video-name');
            const preview = document.getElementById('preview-prewedding_video');
            const btnDelete = document.getElementById('btn-delete-prewedding_video');
            if (path) {
                if (nameEl) nameEl.textContent = name || path.split('/').pop();
                if (preview) { preview.src = path; preview.classList.remove('hidden'); }
                if (btnDelete) btnDelete.style.display = 'inline-block';
            } else {
                if (nameEl) nameEl.textContent = 'Belum ada video';
                if (preview) { preview.removeAttribute('src'); preview.load(); preview.classList.add('hidden'); }
                if (btnDelete) btnDelete.style.display = 'none';
            }
        }

        function deletePreweddingVideo() {
            Swal.fire({
                title: 'Hapus video prewedding?',
                text: "Video akan dihapus dari data utama.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#775a19',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-6 py-2 rounded-xl text-sm font-bold uppercase',
                    cancelButton: 'px-6 py-2 rounded-xl text-sm font-bold uppercase'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('set-prewedding_video_file').value = '';
                    updatePreweddingPreview('');
                    saveSettings();
                }
            });
        }

        async function loadGuests() {
            const res = await fetch(`api/admin_api?action=get_guests&inv_id=${currentInvId}`);
            const data = await res.json();
            const list = document.getElementById('guest-list');
            const baseUrl = window.location.origin + '/';
            window._guestList = data.data || [];
            list.innerHTML = data.data.map((g, i) => {
                const link = `${baseUrl}${currentSlug}?to=${g.slug}`;
                return `
                <tr class="border-t border-outline-variant/20 hover:bg-secondary/5 transition-all">
                    <td class="px-6 py-4 font-semibold">${g.nama}</td>
                    <td class="px-6 py-4 text-xs">${g.no_hp || '-'}</td>
                    <td class="px-6 py-4 text-center">${g.checked_in_at ? `<span class="material-symbols-outlined text-green-600" title="Hadir • ${ciTime(g.checked_in_at)}">check_circle</span>` : `<span class="material-symbols-outlined text-gray-300" title="Belum hadir">radio_button_unchecked</span>`}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="${link}" class="bg-secondary/5 text-[10px] p-2 rounded w-64 border-none outline-none">
                            <button onclick="copyToClipboard('${link}')" class="text-secondary hover:text-primary"><span class="material-symbols-outlined text-sm">content_copy</span></button>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="shareWA('${g.nama}', '${link}', '${g.no_hp}')" class="text-green-500 hover:scale-110" title="Kirim WA"><span class="material-symbols-outlined text-sm">chat</span></button>
                            ${g.qr_code ? `<button onclick="downloadGuestQr(${i})" class="text-secondary hover:scale-110" title="Unduh QR"><span class="material-symbols-outlined text-sm">download</span></button>` : ''}
                            <button onclick="openEditGuest(${JSON.stringify(g).replace(/"/g, '&quot;')})" class="text-secondary hover:scale-110"><span class="material-symbols-outlined text-sm">edit</span></button>
                            <button onclick="deleteGuest(${g.id})" class="text-red-400 hover:scale-110"><span class="material-symbols-outlined text-sm">delete</span></button>
                        </div>
                    </td>
                </tr>
            `}).join('');
        }

        // Unduh QR tamu sebagai PNG (512x512, cocok untuk cetak).
        // Library qrcodejs dimuat malas dari CDN yang sama dengan yang
        // dipakai tema undangan; QR dirender di elemen sementara lalu
        // diunduh lewat canvas, tanpa mengubah halaman.
        function loadQrLib(cb) {
            if (window.QRCode) return cb(true);
            const s = document.createElement('script');
            s.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
            s.onload = () => cb(true);
            s.onerror = () => cb(false);
            document.head.appendChild(s);
        }

        function downloadGuestQr(i) {
            const g = (window._guestList || [])[i];
            if (!g || !g.qr_code) { alert('QR tamu ini belum dibuat.'); return; }
            loadQrLib(ok => {
                if (!ok) { alert('Gagal memuat library QR. Periksa koneksi internet.'); return; }
                const holder = document.createElement('div');
                holder.style.cssText = 'position:absolute;left:-9999px;top:0;';
                document.body.appendChild(holder);
                new QRCode(holder, { text: g.qr_code, width: 512, height: 512, correctLevel: QRCode.CorrectLevel.M });
                const canvas = holder.querySelector('canvas');
                const img = holder.querySelector('img');
                const url = canvas ? canvas.toDataURL('image/png') : (img ? img.src : '');
                document.body.removeChild(holder);
                if (!url) { alert('Gagal membuat gambar QR.'); return; }
                const a = document.createElement('a');
                a.href = url;
                a.download = 'QR-' + String(g.nama).replace(/[^a-zA-Z0-9]+/g, '-') + '.png';
                a.click();
            });
        }

        function shareWA(nama, link, hp) {
            let template = document.getElementById('set-wa_template').value;
            if (!template) template = "Halo [nama], kami mengundang Anda ke pernikahan kami. Cek undangan di: [link]";

            const message = template.replace('[nama]', nama).replace('[link]', link);
            const waUrl = hp ? `https://wa.me/${hp}?text=${encodeURIComponent(message)}` : `https://wa.me/?text=${encodeURIComponent(message)}`;
            window.open(waUrl, '_blank');
        }

        /* ============ QR CHECK-IN ============ */

        function escapeHtml(s) {
            return String(s == null ? '' : s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
        }

        async function toggleQrEnabled(on) {
            const res = await fetch(`api/admin_api?action=update_settings&inv_id=${currentInvId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ guest_qr_enabled: on ? '1' : '0' })
            });
            const data = await res.json();
            if (!data.success) {
                alert('Gagal menyimpan pengaturan.');
                document.getElementById('set-guest_qr_enabled').checked = !on;
            }
        }

        async function loadCheckin() {
            try {
                const sres = await fetch(`api/admin_api?action=get_settings&inv_id=${currentInvId}`);
                const sdata = await sres.json();
                const st = (sdata.data) || {};
                document.getElementById('set-guest_qr_enabled').checked = (st.guest_qr_enabled === '1');
                let token = st.checkin_token || '';
                if (!token) {
                    token = genToken(32);
                    await fetch(`api/admin_api?action=update_settings&inv_id=${currentInvId}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ checkin_token: token })
                    });
                }
                setCiLink(token);
            } catch (e) {}
            refreshCiStats();
        }

        function genToken(n) {
            const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
            let s = '';
            for (let i = 0; i < n; i++) s += chars.charAt(Math.floor(Math.random() * chars.length));
            return s;
        }

        function ciLinkFor(token) {
            return window.location.origin + '/checkin/' + currentSlug + '/' + token;
        }

        function setCiLink(token) {
            document.getElementById('ci-public-link').value = ciLinkFor(token);
        }

        function copyCiLink() {
            const el = document.getElementById('ci-public-link');
            el.select();
            document.execCommand('copy');
            alert('Link panitia disalin!');
        }

        async function regenCiToken() {
            if (!confirm('Buat link baru? Link lama tidak akan berfungsi lagi.')) return;
            const token = genToken(32);
            await fetch(`api/admin_api?action=update_settings&inv_id=${currentInvId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ checkin_token: token })
            });
            setCiLink(token);
            alert('Link panitia baru dibuat.');
        }

        // Server menyimpan waktu dalam UTC; string tanpa zona dibaca sebagai
        // UTC lalu ditampilkan dalam zona waktu acara dari Data Utama
        // (wedding_timezone: WIB/WITA/WIT), bukan hardcode WIB.
        function ciTime(s) {
            if (!s) return '';
            let t = String(s).trim().replace(' ', 'T');
            if (!/[+-]\d{2}:?\d{2}$/.test(t) && !t.endsWith('Z')) t += 'Z';
            const d = new Date(t);
            if (isNaN(d.getTime())) return s;
            const label = (document.getElementById('set-wedding_timezone')?.value || 'WIB');
            const iana = { WIB: 'Asia/Jakarta', WITA: 'Asia/Makassar', WIT: 'Asia/Jayapura' }[label] || 'Asia/Jakarta';
            return d.toLocaleString('id-ID', { timeZone: iana, day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false }).replace(',', '') + ' ' + label;
        }

        async function refreshCiStats() {
            try {
                const res = await fetch(`api/checkin_stats?inv_id=${currentInvId}`);
                const d = await res.json();
                if (!d.success) return;
                document.getElementById('ci-total').textContent = d.total;
                document.getElementById('ci-checked').textContent = d.checked_in;
                document.getElementById('ci-pending').textContent = d.total - d.checked_in;
                const tp = (typeof d.total_people === 'number') ? d.total_people : d.checked_in;
                document.getElementById('ci-people').textContent = tp;
                const pct = d.total ? Math.round(d.checked_in / d.total * 100) : 0;
                document.getElementById('ci-pct').textContent = pct + '%';
                document.getElementById('ci-prog-fill').style.width = pct + '%';
                document.getElementById('ci-prog-lbl').innerHTML = '<span class="text-green-800 font-bold">' + pct + '%</span> (' + d.checked_in + ' dari ' + d.total + ' tamu sudah hadir) \u2022 Total orang hadir: ' + tp + ' orang';
                const box = document.getElementById('ci-recent');
                box.innerHTML = d.recent.length ? d.recent.map(r => {
                    const meta = [];
                    if (r.companions > 0) meta.push('+' + r.companions + ' pendamping');
                    if (r.checked_in_by) meta.push('Petugas: ' + r.checked_in_by);
                    return `<div class="flex justify-between items-center bg-white border border-outline-variant/30 rounded-xl px-4 py-2 text-sm">
                        <span class="font-semibold text-primary">${escapeHtml(r.nama)}${meta.length ? `<span class="block text-[11px] font-normal text-gray-500">${escapeHtml(meta.join(' \u2022 '))}</span>` : ''}</span>
                        <span class="text-xs text-gray-500">${escapeHtml(ciTime(r.checked_in_at))}</span>
                    </div>`;
                }).join('') : '<p class="text-sm text-gray-400 italic">Belum ada check-in.</p>';
            } catch (e) {}
        }


        async function addGuest() {
            const nama = document.getElementById('new-guest-name').value;
            const no_hp = document.getElementById('new-guest-hp').value;
            if (!nama) return;
            await fetch(`api/admin_api?action=add_guest&inv_id=${currentInvId}`, {
                method: 'POST',
                body: JSON.stringify({ nama, no_hp })
            });
            document.getElementById('new-guest-name').value = '';
            document.getElementById('new-guest-hp').value = '';
            loadGuests();
        }

        // Load initial data
        document.addEventListener('DOMContentLoaded', () => {
            loadInvitations();

            ['set-event_location_mode'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.addEventListener('change', updateLocationModeStatus);
            });

            const qrisEl = document.getElementById('set-gift_qris_enabled');
            if (qrisEl) qrisEl.addEventListener('change', updateQrisIcon);

            // Masking waktu HH:mm otomatis hanya untuk waktu mulai
            ['set-wedding_time_start', 'set-reception_time_start'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', function (e) {
                        let v = this.value.replace(/\D/g, ''); // Hapus semua karakter non-angka
                        if (v.length > 4) v = v.slice(0, 4);   // Batasi maksimal 4 digit angka
                        if (v.length >= 3) {
                            v = v.slice(0, 2) + ':' + v.slice(2);
                        }
                        this.value = v;
                    });
                }
            });
        });

        async function bulkAddGuests() {
            const raw = document.getElementById('bulk-names').value;
            if (!raw.trim()) return;
            const names = raw.split('\n').map(n => n.trim()).filter(n => n !== '');

            if (names.length === 0) return;

            await fetch(`api/admin_api?action=bulk_add_guests&inv_id=${currentInvId}`, {
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
            await fetch('api/admin_api?action=update_guest', {
                method: 'POST',
                body: JSON.stringify({ id, nama })
            });
            closeModal('guest');
            loadGuests();
        }

        function deleteGuest(id) {
            Swal.fire({
                title: 'Hapus tamu?',
                text: "Tamu ini akan dihapus dari daftar undangan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#775a19',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-6 py-2 rounded-xl text-sm font-bold uppercase',
                    cancelButton: 'px-6 py-2 rounded-xl text-sm font-bold uppercase'
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await fetch(`api/admin_api?action=delete_guest&id=${id}`);
                    loadGuests();
                }
            });
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => alert('Link disalin!'));
        }

        async function loadRSVP() {
            const res = await fetch(`api/admin_api?action=get_rsvp&inv_id=${currentInvId}`);
            const data = await res.json();
            const list = document.getElementById('rsvp-list');
            if (data.data.length === 0) {
                list.innerHTML = '<tr><td colspan="5" class="px-6 py-12 text-center text-secondary/60 italic"><span class="material-symbols-outlined text-4xl block mb-2 opacity-40">how_to_reg</span>Belum ada konfirmasi kehadiran (RSVP) dari tamu.</td></tr>';
                return;
            }
            list.innerHTML = data.data.map(item => `
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
            const res = await fetch(`api/admin_api?action=get_ucapan&inv_id=${currentInvId}`);
            const data = await res.json();
            const list = document.getElementById('ucapan-list');
            if (data.data.length === 0) {
                list.innerHTML = '<div class="text-center py-12 text-secondary/60 italic"><span class="material-symbols-outlined text-4xl block mb-4 opacity-40">chat</span>Belum ada ucapan dan doa yang dikirim oleh tamu.</div>';
                return;
            }
            list.innerHTML = data.data.map(item => `
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

            await fetch('api/admin_api?action=update_rsvp', {
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

            await fetch('api/admin_api?action=update_ucapan', {
                method: 'POST',
                body: JSON.stringify({ id, nama, pesan })
            });
            closeModal('ucapan');
            loadUcapan();
        }

        function deleteItem(type, id) {
            Swal.fire({
                title: 'Hapus data?',
                text: "Data ini akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#775a19',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-6 py-2 rounded-xl text-sm font-bold uppercase',
                    cancelButton: 'px-6 py-2 rounded-xl text-sm font-bold uppercase'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`api/admin_api?action=delete_${type}&id=${id}`).then(() => {
                        if (type === 'gallery') loadGallery();
                        if (type === 'rsvp') loadRSVP();
                        if (type === 'ucapan') loadUcapan();
                        if (type === 'music') loadMusic();
                        if (type === 'story') loadStories();
                    });
                }
            });
        }

        // Music Management Functions
        async function loadMusic() {
            const res = await fetch(`api/admin_api?action=get_music&inv_id=${currentInvId}`);
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
                const res = await fetch(`api/admin_api?action=upload_music&inv_id=${currentInvId}`, {
                    method: 'POST',
                    body: formData
                });

                const contentType = res.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await res.text();
                    console.error('Response is not JSON:', text);
                    alert('Server error: Response bukan JSON. Cek console untuk detail.');
                    return;
                }

                const data = await res.json();

                if (data.success) {
                    alert('Lagu berhasil diupload!');
                    loadMusic();
                } else {
                    alert(data.message || 'Gagal upload lagu');
                }
            } catch (error) {
                console.error('Upload error:', error);
                alert('Error: ' + error.message);
            }

            input.value = '';
        }

        async function toggleActiveMusic(id) {
            const res = await fetch(`api/admin_api?action=toggle_active_music&id=${id}`, {
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
            Swal.fire({
                title: 'Hapus musik?',
                text: "File audio ini akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#775a19',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-6 py-2 rounded-xl text-sm font-bold uppercase',
                    cancelButton: 'px-6 py-2 rounded-xl text-sm font-bold uppercase'
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const res = await fetch(`api/admin_api?action=delete_music&id=${id}`, {
                        method: 'POST'
                    });
                    const data = await res.json();

                    if (data.success) {
                        loadMusic();
                    } else {
                        alert(data.message || 'Gagal menghapus musik');
                    }
                }
            });
        }

        async function saveMusicSettings() {
            const volume = document.getElementById('set-music_volume').value;
            const autoplay = document.getElementById('set-music_autoplay').value;

            const settings = {
                music_volume: volume,
                music_autoplay: autoplay
            };

            const res = await fetch(`api/admin_api?action=update_settings&inv_id=${currentInvId}`, {
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
        async function uploadBankLogo(index) {
            const input = event.target;
            if (!input.files.length) return;

            const file = input.files[0];
            if (!file.type.startsWith('image/')) {
                alert('Hanya file gambar yang diperbolehkan!');
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB!');
                return;
            }

            const formData = new FormData();
            formData.append('image', file);

            try {
                const res = await fetch(`api/admin_api?action=upload_bank_logo&inv_id=${currentInvId}`, {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.success) {
                    document.getElementById(`bank-logo-val-${index}`).value = data.path;
                    const preview = document.getElementById(`bank-preview-${index}`);
                    const placeholder = document.getElementById(`bank-placeholder-${index}`);
                    preview.src = data.path;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    alert('Logo bank berhasil diupload!');
                } else {
                    alert(data.message || 'Gagal upload logo bank');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
            input.value = '';
        }

        let bankRows = [];

        function renderBankRows() {
            const container = document.getElementById('bank-list-container');
            if (!container) return;

            if (bankRows.length === 0) {
                container.innerHTML = '<p class="text-xs text-secondary/60 italic">Belum ada bank ditambahkan. Klik "+ Tambah Bank".</p>';
                return;
            }

            container.innerHTML = bankRows.map((bank, index) => `
                <div class="p-4 bg-secondary/5 rounded-2xl border border-secondary/10 grid grid-cols-1 md:grid-cols-4 gap-4 items-end relative">
                    <button type="button" onclick="removeBankRow(${index})" class="absolute top-2 right-2 text-red-400 hover:text-red-600">
                        <span class="material-symbols-outlined text-sm">delete</span>
                    </button>
                    <div class="rsvp-field"><label>NAMA BANK</label><input type="text" class="bank-name" value="${bank.name || ''}" placeholder="BCA / BRI"></div>
                    <div class="rsvp-field"><label>NO REKENING</label><input type="text" class="bank-account" value="${bank.account || ''}" placeholder="123456789"></div>
                    <div class="rsvp-field"><label>ATAS NAMA</label><input type="text" class="bank-owner" value="${bank.owner || ''}" placeholder="Nama Pemilik"></div>
                    
                    <div class="text-center">
                        <label class="block aspect-[3/2] bg-white rounded-xl border border-dashed border-secondary/30 flex flex-col items-center justify-center cursor-pointer overflow-hidden max-w-[100px] mx-auto">
                            <img id="bank-preview-${index}" src="${bank.logo || ''}" class="w-full h-full object-contain ${bank.logo ? '' : 'hidden'}">
                            <div id="bank-placeholder-${index}" class="text-secondary opacity-40 text-center ${bank.logo ? 'hidden' : ''}">
                                <span class="material-symbols-outlined text-xl">account_balance</span>
                                <p class="text-[7px] font-bold mt-1">LOGO</p>
                            </div>
                            <input type="file" class="hidden" accept="image/*" onchange="uploadBankLogo(${index})">
                            <input type="hidden" id="bank-logo-val-${index}" value="${bank.logo || ''}">
                        </label>
                    </div>
                </div>
            `).join('');
        }

        function addBankRow(data = {}) {
            bankRows.push(data);
            renderBankRows();
        }

        function removeBankRow(index) {
            bankRows.splice(index, 1);
            renderBankRows();
        }

        function collectBankData() {
            const container = document.getElementById('bank-list-container');
            if (!container) return [];
            const rows = container.querySelectorAll('.grid');
            const list = [];
            rows.forEach((row, index) => {
                const name = row.querySelector('.bank-name').value;
                const account = row.querySelector('.bank-account').value;
                const owner = row.querySelector('.bank-owner').value;
                const logo = document.getElementById(`bank-logo-val-${index}`).value;
                if (name || account || owner) {
                    list.push({ name, account, owner, logo });
                }
            });
            return list;
        }

        // Kisah Kasih (Stories) Management Functions
        async function loadStories() {
            const res = await fetch(`api/admin_api?action=get_stories&inv_id=${currentInvId}`);
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
            const icon = 'favorite';
            const judul = document.getElementById('new-story-judul').value.trim();
            const isi = document.getElementById('new-story-isi').value.trim();

            if (!tahun || !judul || !isi) {
                alert('Semua field kisah harus diisi!');
                return;
            }

            const res = await fetch(`api/admin_api?action=add_story&inv_id=${currentInvId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ tahun, icon, judul, isi })
            });
            const data = await res.json();
            if (data.success) {
                document.getElementById('new-story-tahun').value = '';
                document.getElementById('new-story-judul').value = '';
                document.getElementById('new-story-isi').value = '';
                loadStories();
                alert('Kisah berhasil ditambahkan!');
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
            const icon = 'favorite';
            const judul = document.getElementById('edit-story-judul').value.trim();
            const isi = document.getElementById('edit-story-isi').value.trim();

            if (!tahun || !judul || !isi) {
                alert('Semua field kisah harus diisi!');
                return;
            }

            try {
                const res = await fetch(`api/admin_api?action=update_story&inv_id=${currentInvId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, tahun, icon, judul, isi })
                });
                const data = await res.json();
                if (data.success) {
                    closeModal('story');
                    loadStories();
                    alert('Kisah berhasil diperbarui!');
                } else {
                    alert(data.message || 'Gagal memperbarui kisah.');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function deleteStory(id) {
            if (!confirm('Hapus kisah ini?')) return;
            const res = await fetch(`api/admin_api?action=delete_story&id=${id}`);
            const data = await res.json();
            if (data.success) {
                loadStories();
            } else {
                alert('Gagal menghapus kisah.');
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

    <!-- Modal Tambah Undangan -->
    <div id="modal-new-invitation" class="modal fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-sm rounded-3xl p-8 shadow-2xl">
            <h3 class="font-display-lg text-2xl text-primary mb-6">Undangan Baru</h3>
            <div class="space-y-4">
                <div class="rsvp-field">
                    <label>JUDUL UNDANGAN</label>
                    <input type="text" id="new-inv-title" placeholder="Pernikahan A & B">
                </div>
                <div class="rsvp-field">
                    <label>SLUG / URL (OPSIONAL)</label>
                    <input type="text" id="new-inv-slug" placeholder="pernikahan-a-b">
                    <p class="text-[8px] text-secondary/60 mt-1 italic">Biarkan kosong untuk generate otomatis</p>
                </div>
            </div>
            <div class="flex gap-3 mt-8">
                <button onclick="closeModal('new-invitation')"
                    class="flex-1 py-3 rounded-xl font-bold text-xs uppercase bg-gray-100">Batal</button>
                <button onclick="submitNewInvitation()"
                    class="flex-1 py-3 rounded-xl font-bold text-xs uppercase btn-theme">Buat Sekarang</button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Undangan -->
    <div id="modal-delete-invitation" class="modal fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-md rounded-3xl p-8 shadow-2xl">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-red-600 text-4xl">delete_forever</span>
                </div>
                <h3 class="font-display-lg text-2xl text-primary mb-2">Hapus Undangan?</h3>
                <p class="text-sm text-on-surface-variant" id="delete-inv-title-text">Undangan ini akan dihapus permanen
                </p>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <p class="text-xs text-red-800 font-semibold">⚠️ Peringatan:</p>
                <p class="text-xs text-red-700 mt-1">Semua data terkait (tamu, RSVP, ucapan, foto) akan terhapus dan
                    tidak dapat dikembalikan.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="closeModal('delete-invitation')"
                    class="flex-1 py-3 rounded-xl font-bold text-xs uppercase bg-gray-100">Batal</button>
                <button onclick="confirmDeleteInvitation()"
                    class="flex-1 py-3 rounded-xl font-bold text-xs uppercase bg-red-600 text-white hover:bg-red-700">Hapus
                    Sekarang</button>
            </div>
        </div>
    </div>
    <!-- Modal Konfirmasi Hapus Generik -->
    <div id="modal-confirm-delete" class="modal fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-sm rounded-3xl p-8 shadow-2xl text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-red-600 text-3xl">delete</span>
            </div>
            <h3 class="font-display-lg text-2xl text-primary mb-2">Hapus Data?</h3>
            <p class="text-xs text-secondary/70 mb-6" id="confirm-delete-text">Data yang dihapus tidak dapat
                dikembalikan.</p>
            <div class="flex gap-3">
                <button onclick="closeModal('confirm-delete')"
                    class="flex-1 py-3 rounded-xl font-bold text-xs uppercase bg-gray-100">Batal</button>
                <button id="btn-confirm-delete-action"
                    class="flex-1 py-3 rounded-xl font-bold text-xs uppercase bg-red-600 text-white hover:bg-red-700">Hapus</button>
            </div>
        </div>
    </div>
</body>

</html>