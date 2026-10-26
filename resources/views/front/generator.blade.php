<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Guest Link Generator - {{ $bride }} & {{ $groom }}</title>
    <style>
        body {
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f5f3ef;
            color: #1b1c1a;
        }

        h1,
        h2,
        h3 {
            font-family: Georgia, 'Times New Roman', serif;
        }

        .btn-primary {
            background: #775a19;
            color: white;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #5d4201;
        }

        .btn-outline {
            border: 1px solid #775a19;
            color: #775a19;
            transition: all 0.2s;
        }

        .btn-outline:hover {
            background: #775a19;
            color: white;
        }

        /* Notifikasi toast (salin & hapus) */
        #toast {
            position: fixed;
            left: 50%;
            bottom: 24px;
            transform: translate(-50%, 20px);
            background: #1b1c1a;
            color: #fff;
            padding: 12px 20px;
            border-radius: 999px;
            font-size: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .25);
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s, transform .25s;
            z-index: 100;
            max-width: calc(100vw - 32px);
            text-align: center;
        }

        #toast.show {
            opacity: 1;
            transform: translate(-50%, 0);
        }

        /* Tabel tamu muat penuh tanpa geser ke kanan */
        .responsive-table {
            table-layout: fixed;
            width: 100%;
        }

        .responsive-table th,
        .responsive-table td {
            overflow-wrap: anywhere;
            vertical-align: middle;
        }

        .responsive-table input {
            min-width: 0;
        }

        @media (max-width: 640px) {
            .responsive-table thead {
                display: none;
            }

            .responsive-table,
            .responsive-table tbody,
            .responsive-table tr,
            .responsive-table td {
                display: block;
                width: 100%;
            }

            .responsive-table tr {
                border: 1px solid #e5e7eb;
                border-radius: .75rem;
                margin-bottom: .75rem;
                padding: .5rem .75rem;
            }

            .responsive-table td {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: .5rem;
                padding: .3rem 0;
                border: none;
            }

            .responsive-table td::before {
                content: attr(data-label);
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .05em;
                color: #6b7280;
                flex-shrink: 0;
            }

            .responsive-table td> * {
                min-width: 0;
            }
        }

        /* Utility subset (pengganti Tailwind CDN agar halaman ringan) */
        * { box-sizing: border-box; }
        body { margin: 0; }
        h1, h2, h3, p, table { margin: 0; }
        button, input, textarea, select { font: inherit; }
        .icon { width: 1.25em; height: 1.25em; flex-shrink: 0; vertical-align: middle; }
        .spin { animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        [class~="block"] { display:block }
        [class~="flex"] { display:flex }
        [class~="grid"] { display:grid }
        [class~="hidden"] { display:none }
        [class~="flex-wrap"] { flex-wrap:wrap }
        [class~="items-center"] { align-items:center }
        [class~="justify-center"] { justify-content:center }
        [class~="justify-between"] { justify-content:space-between }
        [class~="shrink-0"] { flex-shrink:0 }
        [class~="flex-shrink-0"] { flex-shrink:0 }
        [class~="grid-cols-2"] { grid-template-columns:repeat(2,minmax(0,1fr)) }
        [class~="min-h-screen"] { min-height:100vh }
        [class~="w-full"] { width:100% }
        [class~="max-w-xl"] { max-width:36rem }
        [class~="min-w-0"] { min-width:0 }
        [class~="max-h-96"] { max-height:24rem }
        [class~="overflow-hidden"] { overflow:hidden }
        [class~="overflow-y-auto"] { overflow-y:auto }
        [class~="border"] { border:1px solid }
        [class~="border-t"] { border-top:1px solid }
        [class~="rounded"] { border-radius:0.25rem }
        [class~="rounded-lg"] { border-radius:0.5rem }
        [class~="rounded-xl"] { border-radius:0.75rem }
        [class~="rounded-3xl"] { border-radius:1.5rem }
        [class~="shadow-xl"] { box-shadow:0 20px 25px -5px rgba(0,0,0,.1), 0 8px 10px -6px rgba(0,0,0,.1) }
        [class~="font-bold"] { font-weight:700 }
        [class~="font-semibold"] { font-weight:600 }
        [class~="uppercase"] { text-transform:uppercase }
        [class~="tracking-widest"] { letter-spacing:0.1em }
        [class~="text-center"] { text-align:center }
        [class~="text-left"] { text-align:left }
        [class~="whitespace-pre-line"] { white-space:pre-line }
        [class~="outline-none"] { outline:none }
        [class~="resize-none"] { resize:none }
        [class~="cursor-pointer"] { cursor:pointer }
        [class~="relative"] { position:relative }
        [class~="sticky"] { position:sticky }
        [class~="top-0"] { top:0 }
        [class~="align-middle"] { vertical-align:middle }
        [class~="transition-all"] { transition:all .2s }
        [class~="bg-transparent"] { background-color:transparent }
        [class~="accent-[#775a19]"] { accent-color:#775a19 }
        [class~="text-xs"] { font-size:.75rem }
        [class~="text-sm"] { font-size:.875rem }
        [class~="text-base"] { font-size:1rem }
        [class~="text-lg"] { font-size:1.125rem }
        [class~="text-xl"] { font-size:1.25rem }
        [class~="text-2xl"] { font-size:1.5rem }
        [class~="text-[10px]"] { font-size:10px }
        [class~="text-[11px]"] { font-size:11px }
        [class~="p-0"] { padding:0 }
        [class~="p-0.5"] { padding:0.125rem }
        [class~="p-1"] { padding:0.25rem }
        [class~="p-1.5"] { padding:0.375rem }
        [class~="p-2"] { padding:0.5rem }
        [class~="p-3"] { padding:0.75rem }
        [class~="p-4"] { padding:1rem }
        [class~="p-6"] { padding:1.5rem }
        [class~="p-8"] { padding:2rem }
        [class~="p-10"] { padding:2.5rem }
        [class~="px-0"] { padding-left:0; padding-right:0 }
        [class~="px-0.5"] { padding-left:0.125rem; padding-right:0.125rem }
        [class~="px-1"] { padding-left:0.25rem; padding-right:0.25rem }
        [class~="px-1.5"] { padding-left:0.375rem; padding-right:0.375rem }
        [class~="px-2"] { padding-left:0.5rem; padding-right:0.5rem }
        [class~="px-3"] { padding-left:0.75rem; padding-right:0.75rem }
        [class~="px-4"] { padding-left:1rem; padding-right:1rem }
        [class~="px-6"] { padding-left:1.5rem; padding-right:1.5rem }
        [class~="px-8"] { padding-left:2rem; padding-right:2rem }
        [class~="px-10"] { padding-left:2.5rem; padding-right:2.5rem }
        [class~="py-0"] { padding-top:0; padding-bottom:0 }
        [class~="py-0.5"] { padding-top:0.125rem; padding-bottom:0.125rem }
        [class~="py-1"] { padding-top:0.25rem; padding-bottom:0.25rem }
        [class~="py-1.5"] { padding-top:0.375rem; padding-bottom:0.375rem }
        [class~="py-2"] { padding-top:0.5rem; padding-bottom:0.5rem }
        [class~="py-3"] { padding-top:0.75rem; padding-bottom:0.75rem }
        [class~="py-4"] { padding-top:1rem; padding-bottom:1rem }
        [class~="py-6"] { padding-top:1.5rem; padding-bottom:1.5rem }
        [class~="py-8"] { padding-top:2rem; padding-bottom:2rem }
        [class~="py-10"] { padding-top:2.5rem; padding-bottom:2.5rem }
        [class~="pt-0"] { padding-top:0 }
        [class~="pt-0.5"] { padding-top:0.125rem }
        [class~="pt-1"] { padding-top:0.25rem }
        [class~="pt-1.5"] { padding-top:0.375rem }
        [class~="pt-2"] { padding-top:0.5rem }
        [class~="pt-3"] { padding-top:0.75rem }
        [class~="pt-4"] { padding-top:1rem }
        [class~="pt-6"] { padding-top:1.5rem }
        [class~="pt-8"] { padding-top:2rem }
        [class~="pt-10"] { padding-top:2.5rem }
        [class~="pb-0"] { padding-bottom:0 }
        [class~="pb-0.5"] { padding-bottom:0.125rem }
        [class~="pb-1"] { padding-bottom:0.25rem }
        [class~="pb-1.5"] { padding-bottom:0.375rem }
        [class~="pb-2"] { padding-bottom:0.5rem }
        [class~="pb-3"] { padding-bottom:0.75rem }
        [class~="pb-4"] { padding-bottom:1rem }
        [class~="pb-6"] { padding-bottom:1.5rem }
        [class~="pb-8"] { padding-bottom:2rem }
        [class~="pb-10"] { padding-bottom:2.5rem }
        [class~="mt-0"] { margin-top:0 }
        [class~="mt-0.5"] { margin-top:0.125rem }
        [class~="mt-1"] { margin-top:0.25rem }
        [class~="mt-1.5"] { margin-top:0.375rem }
        [class~="mt-2"] { margin-top:0.5rem }
        [class~="mt-3"] { margin-top:0.75rem }
        [class~="mt-4"] { margin-top:1rem }
        [class~="mt-6"] { margin-top:1.5rem }
        [class~="mt-8"] { margin-top:2rem }
        [class~="mt-10"] { margin-top:2.5rem }
        [class~="mb-0"] { margin-bottom:0 }
        [class~="mb-0.5"] { margin-bottom:0.125rem }
        [class~="mb-1"] { margin-bottom:0.25rem }
        [class~="mb-1.5"] { margin-bottom:0.375rem }
        [class~="mb-2"] { margin-bottom:0.5rem }
        [class~="mb-3"] { margin-bottom:0.75rem }
        [class~="mb-4"] { margin-bottom:1rem }
        [class~="mb-6"] { margin-bottom:1.5rem }
        [class~="mb-8"] { margin-bottom:2rem }
        [class~="mb-10"] { margin-bottom:2.5rem }
        [class~="ml-0"] { margin-left:0 }
        [class~="ml-0.5"] { margin-left:0.125rem }
        [class~="ml-1"] { margin-left:0.25rem }
        [class~="ml-1.5"] { margin-left:0.375rem }
        [class~="ml-2"] { margin-left:0.5rem }
        [class~="ml-3"] { margin-left:0.75rem }
        [class~="ml-4"] { margin-left:1rem }
        [class~="ml-6"] { margin-left:1.5rem }
        [class~="ml-8"] { margin-left:2rem }
        [class~="ml-10"] { margin-left:2.5rem }
        [class~="mr-0"] { margin-right:0 }
        [class~="mr-0.5"] { margin-right:0.125rem }
        [class~="mr-1"] { margin-right:0.25rem }
        [class~="mr-1.5"] { margin-right:0.375rem }
        [class~="mr-2"] { margin-right:0.5rem }
        [class~="mr-3"] { margin-right:0.75rem }
        [class~="mr-4"] { margin-right:1rem }
        [class~="mr-6"] { margin-right:1.5rem }
        [class~="mr-8"] { margin-right:2rem }
        [class~="mr-10"] { margin-right:2.5rem }
        [class~="gap-0"] { gap:0 }
        [class~="gap-0.5"] { gap:0.125rem }
        [class~="gap-1"] { gap:0.25rem }
        [class~="gap-1.5"] { gap:0.375rem }
        [class~="gap-2"] { gap:0.5rem }
        [class~="gap-3"] { gap:0.75rem }
        [class~="gap-4"] { gap:1rem }
        [class~="gap-6"] { gap:1.5rem }
        [class~="gap-8"] { gap:2rem }
        [class~="gap-10"] { gap:2.5rem }
        [class~="bg-white"] { background-color:#fff }
        [class~="text-white"] { color:#fff }
        [class~="border-white"] { border-color:#fff }
        [class~="bg-gray-50"] { background-color:#f9fafb }
        [class~="text-gray-50"] { color:#f9fafb }
        [class~="border-gray-50"] { border-color:#f9fafb }
        [class~="bg-gray-100"] { background-color:#f3f4f6 }
        [class~="text-gray-100"] { color:#f3f4f6 }
        [class~="border-gray-100"] { border-color:#f3f4f6 }
        [class~="bg-gray-200"] { background-color:#e5e7eb }
        [class~="text-gray-200"] { color:#e5e7eb }
        [class~="border-gray-200"] { border-color:#e5e7eb }
        [class~="bg-gray-300"] { background-color:#d1d5db }
        [class~="text-gray-300"] { color:#d1d5db }
        [class~="border-gray-300"] { border-color:#d1d5db }
        [class~="bg-gray-400"] { background-color:#9ca3af }
        [class~="text-gray-400"] { color:#9ca3af }
        [class~="border-gray-400"] { border-color:#9ca3af }
        [class~="bg-gray-500"] { background-color:#6b7280 }
        [class~="text-gray-500"] { color:#6b7280 }
        [class~="border-gray-500"] { border-color:#6b7280 }
        [class~="bg-gray-600"] { background-color:#4b5563 }
        [class~="text-gray-600"] { color:#4b5563 }
        [class~="border-gray-600"] { border-color:#4b5563 }
        [class~="bg-gray-700"] { background-color:#374151 }
        [class~="text-gray-700"] { color:#374151 }
        [class~="border-gray-700"] { border-color:#374151 }
        [class~="bg-green-500"] { background-color:#22c55e }
        [class~="text-green-500"] { color:#22c55e }
        [class~="border-green-500"] { border-color:#22c55e }
        [class~="bg-green-600"] { background-color:#16a34a }
        [class~="text-green-600"] { color:#16a34a }
        [class~="border-green-600"] { border-color:#16a34a }
        [class~="bg-red-400"] { background-color:#f87171 }
        [class~="text-red-400"] { color:#f87171 }
        [class~="border-red-400"] { border-color:#f87171 }
        [class~="bg-red-500"] { background-color:#ef4444 }
        [class~="text-red-500"] { color:#ef4444 }
        [class~="border-red-500"] { border-color:#ef4444 }
        [class~="bg-amber-50"] { background-color:#fffbeb }
        [class~="text-amber-50"] { color:#fffbeb }
        [class~="border-amber-50"] { border-color:#fffbeb }
        [class~="bg-amber-600"] { background-color:#d97706 }
        [class~="text-amber-600"] { color:#d97706 }
        [class~="border-amber-600"] { border-color:#d97706 }
        [class~="bg-amber-800"] { background-color:#92400e }
        [class~="text-amber-800"] { color:#92400e }
        [class~="border-amber-800"] { border-color:#92400e }
        [class~="bg-#775a19"] { background-color:#775a19 }
        [class~="text-#775a19"] { color:#775a19 }
        [class~="border-#775a19"] { border-color:#775a19 }
        [class~="opacity-50"] { opacity:.50 }
        [class~="opacity-60"] { opacity:.60 }
        [class~="opacity-75"] { opacity:.75 }
        [class~="opacity-80"] { opacity:.80 }
        [class~="hover:bg-gray-50"]:hover { background-color:#f9fafb }
        [class~="hover:bg-gray-100"]:hover { background-color:#f3f4f6 }
        [class~="hover:bg-gray-200"]:hover { background-color:#e5e7eb }
        [class~="focus:outline-none"]:focus { outline:none }
        [class~="focus:border-[#775a19]"]:focus { border-color:#775a19 }
        [class~="focus:ring-1"]:focus { box-shadow:0 0 0 1px #775a19 }
        [class~="focus:ring-[#775a19]"]:focus { box-shadow:0 0 0 1px #775a19 }
        [class~="disabled:bg-gray-100"]:disabled { background-color:#f3f4f6 }
        [class~="disabled:text-gray-400"]:disabled { color:#9ca3af }
        [class~="bg-[#775a19]"] { background-color:#775a19 }
        [class~="text-[#775a19]"] { color:#775a19 }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full bg-white rounded-3xl shadow-xl overflow-hidden">

        <div class="bg-[#775a19] text-white p-6 text-center relative">
            <h1 class="text-2xl font-bold mb-1">Link Generator Tamu</h1>
            <p class="text-sm opacity-80">{{ $bride }} & {{ $groom }}</p>
        </div>

        <div class="p-8">
            <p class="text-sm text-gray-600 mb-6">Gunakan halaman ini untuk menambahkan tamu undangan dan membuat pesan
                WhatsApp otomatis.</p>

            <!-- Template Pesan WhatsApp -->
            <div class="mb-6 border border-gray-200 rounded-xl p-4 bg-gray-50">
                <h3 class="text-sm font-bold text-[#775a19] uppercase tracking-widest mb-3">Template Pesan WhatsApp</h3>
                <div class="flex flex-wrap gap-4 mb-3 text-sm">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="wa-template-mode" value="sistem" id="tpl-mode-sistem" class="accent-[#775a19]">
                        Sistem (otomatis ada)
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="wa-template-mode" value="sendiri" id="tpl-mode-sendiri" class="accent-[#775a19]">
                        Buat sendiri
                    </label>
                </div>
                <textarea id="custom-template" rows="6" disabled
                    placeholder="Tulis template sendiri di sini. Gunakan [nama] untuk nama tamu dan [link] untuk link undangan.&#10;&#10;Contoh: Halo [nama], kami mengundang Anda ke pernikahan kami. Silakan buka undangan di: [link]"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#775a19] focus:ring-1 focus:ring-[#775a19] transition-all text-sm bg-white disabled:bg-gray-100 disabled:text-gray-400"></textarea>
                <p class="text-[10px] text-gray-500 mt-1">Pilihan dan template sendiri tersimpan di perangkat ini saja. Template yang dipilih berlaku untuk semua pesan WA dari halaman ini (tunggal, massal, dan daftar tamu).</p>
                <div class="mt-3" id="template-preview-wrap">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Pratinjau</label>
                    <div id="template-preview" class="w-full bg-white border border-gray-200 rounded-xl p-3 text-sm text-gray-700 whitespace-pre-line"></div>
                </div>
            </div>

            <form id="generator-form" onsubmit="generateLink(event)">
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Nama Tamu
                        *</label>
                    <input type="text" id="guest_name" required placeholder="Contoh: Keluarga Bpk. Budi" autocomplete="off"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#775a19] focus:ring-1 focus:ring-[#775a19] transition-all">
                    <div id="guest-suggestions" class="hidden mt-2 border border-gray-200 rounded-xl overflow-hidden"></div>
                    <p class="text-[10px] text-gray-500 mt-1">Ketik minimal 3 huruf: bila tamu sudah ada, link lamanya akan muncul agar tidak dobel.</p>
                </div>
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">No. WhatsApp
                        (Opsional)</label>
                    <input type="text" id="guest_phone" placeholder="Contoh: 628123456789"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#775a19] focus:ring-1 focus:ring-[#775a19] transition-all">
                    <p class="text-[10px] text-gray-500 mt-1">Gunakan format 628xxx (tanpa + atau 0 di depan).</p>
                </div>

                <button type="submit" id="btn-generate"
                    class="w-full btn-primary font-bold uppercase tracking-widest py-3 rounded-xl flex items-center justify-center gap-2">
                    <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/></svg>
                    Generate Link
                </button>
            </form>

            <div id="result-container" class="mt-8 hidden border-t border-gray-200 pt-6">
                <h3 class="text-lg font-bold text-[#775a19] mb-4">Hasil Generate</h3>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Link
                        Undangan</label>
                    <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl p-3">
                        <input type="text" id="result-link" readonly
                            class="bg-transparent w-full outline-none text-sm text-gray-700">
                        <button type="button" onclick="copyLink()" id="btn-copy-link"
                            class="flex-shrink-0 p-2 text-[#775a19] hover:bg-gray-200 rounded-lg transition-all"
                            title="Salin link">
                            <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Pesan
                        WhatsApp</label>
                    <textarea id="result-message" rows="12" readonly
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm text-gray-700 outline-none resize-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="copyMessage()" id="btn-copy"
                        class="btn-outline font-bold uppercase tracking-widest py-3 rounded-xl flex items-center justify-center gap-2 text-xs">
                        <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
                        Copy Pesan
                    </button>
                    <button type="button" onclick="sendWhatsapp()" id="btn-wa"
                        class="btn-primary font-bold uppercase tracking-widest py-3 rounded-xl flex items-center justify-center gap-2 text-xs">
                        <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                        Kirim via WA
                    </button>
                </div>
            </div>


            <!-- Generate Massal -->
            <div class="mt-10 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-bold text-[#775a19] mb-2">Generate Massal</h3>
                <p class="text-sm text-gray-600 mb-4">Tempel daftar tamu, satu tamu per baris. Format: <span class="font-semibold">Nama, nomor WA</span> (pisahkan dengan koma; nomor boleh kosong). Contoh: Budi, 08123456789. Maksimal 100 tamu sekali generate; nama yang sudah ada tidak dibuat ulang.</p>
                <textarea id="bulk-input" rows="6" placeholder="Budi, 08123456789&#10;Ibu Sari&#10;Ahmad, 628987654321"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#775a19] focus:ring-1 focus:ring-[#775a19] transition-all text-sm"></textarea>
                <button type="button" id="btn-bulk" onclick="generateBulk()"
                    class="mt-4 w-full btn-primary font-bold uppercase tracking-widest py-3 rounded-xl flex items-center justify-center gap-2">
                    <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    Generate Massal
                </button>

                <div id="bulk-result" class="hidden mt-6">
                    <p id="bulk-summary" class="text-sm font-semibold text-gray-700 mb-3"></p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <button type="button" onclick="copyAllBulkLinks()" id="btn-copy-all"
                            class="btn-outline font-bold uppercase tracking-widest py-2 px-4 rounded-xl text-xs flex items-center gap-2">
                            <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg> Salin Semua Link
                        </button>
                        <button type="button" onclick="downloadBulkCsv()"
                            class="btn-outline font-bold uppercase tracking-widest py-2 px-4 rounded-xl text-xs flex items-center gap-2">
                            <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg> Unduh CSV
                        </button>
                    </div>
                    <div class="border border-gray-200 rounded-xl">
                        <table class="responsive-table w-full text-sm text-left">
                            <colgroup><col style="width:20%"><col style="width:32%"><col style="width:12%"><col style="width:18%"><col style="width:18%"></colgroup>
                            <thead class="bg-gray-50 text-xs uppercase tracking-widest text-gray-500">
                                <tr>
                                    <th class="px-3 py-2">Nama</th>
                                    <th class="px-3 py-2">Link</th>
                                    <th class="px-3 py-2">Status</th>
                                    <th class="px-3 py-2">Status WA</th>
                                    <th class="px-3 py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="bulk-tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Daftar Tamu yang Sudah Diinput -->
            <div class="mt-10 border-t border-gray-200 pt-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-bold text-[#775a19]">Daftar Tamu yang Sudah Diinput</h3>
                    <button type="button" id="btn-refresh-guests" onclick="loadGeneratorGuests()"
                        class="btn-outline font-bold uppercase tracking-widest py-2 px-4 rounded-xl text-xs flex items-center gap-2">
                        <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg> Muat Ulang
                    </button>
                </div>
                <input type="text" id="generator-guest-search" placeholder="Cari nama tamu..." autocomplete="off"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#775a19] focus:ring-1 focus:ring-[#775a19] transition-all text-sm mb-4">
                <p class="text-sm text-gray-600 mb-4"><span id="generator-guest-count">0</span> tamu. Tanda <span class="font-semibold text-green-600">Sudah dikirim</span> muncul saat tombol Kirim WA diklik, atau saat ditandai manual — pengiriman di dalam aplikasi WhatsApp sendiri tidak bisa dilacak dari sini.</p>
                <div class="border border-gray-200 rounded-xl max-h-96 overflow-y-auto">
                    <table class="responsive-table w-full text-sm text-left">
                        <colgroup><col style="width:24%"><col style="width:36%"><col style="width:22%"><col style="width:18%"></colgroup>
                        <thead class="bg-gray-50 text-xs uppercase tracking-widest text-gray-500 sticky top-0">
                            <tr>
                                <th class="px-3 py-2 bg-gray-50">Nama</th>
                                <th class="px-3 py-2 bg-gray-50">Link</th>
                                <th class="px-3 py-2 bg-gray-50">Status WA</th>
                                <th class="px-3 py-2 bg-gray-50">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="generator-guest-tbody"><tr><td colspan="4" class="px-3 py-4 text-sm text-gray-500 text-center">Memuat daftar tamu...</td></tr></tbody>
                    </table>
                </div>
                <p id="generator-guest-empty" class="hidden text-sm text-gray-500 mt-3">Belum ada tamu yang diinput untuk undangan ini.</p>
            </div>

        </div>
    </div>
    </div>

    <div id="toast" role="status" aria-live="polite"></div>

    <script>
        const invitationId = {{ $invitation->id }};
        const invSlug = "{{ $invitation->slug }}";
        const bride = "{{ $bride }}";
        const groom = "{{ $groom }}";
        const baseUrl = "{{ url('/') }}";
        const ICONS = {
    link: `<svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/></svg>`,
    content_copy: `<svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>`,
    send: `<svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z"/></svg>`,
    groups: `<svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>`,
    download: `<svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>`,
    refresh: `<svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg>`,
    edit: `<svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>`,
    delete: `<svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>`,
    check: `<svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M9 16.17 4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>`,
    close: `<svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>`,
    undo: `<svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.5 8c-2.65 0-5.05.99-6.9 2.6L2 7v9h9l-3.62-3.62c1.39-1.16 3.16-1.88 5.12-1.88 3.54 0 6.55 2.31 7.6 5.5l2.37-.78C21.08 11.03 17.15 8 12.5 8z"/></svg>`
};

        let currentGuestPhone = "";
        let currentGuestSlug = "";
        let toastTimer = null;

        function showToast(msg) {
            const el = document.getElementById('toast');
            if (!el) return;
            el.textContent = msg;
            el.classList.add('show');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => el.classList.remove('show'), 2500);
        }

        function copyTextWithToast(text, okMsg) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(() => showToast(okMsg)).catch(() => showToast('Gagal menyalin, salin manual dari kolom.'));
            } else {
                showToast('Gagal menyalin, salin manual dari kolom.');
            }
        }
        let bulkResults = [];
        let generatorGuests = [];

        function escHtml(s) {
            return String(s == null ? '' : s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
        }

        // Template WA: 'sistem' = template bawaan di bawah (otomatis ada),
        // 'sendiri' = teks dari kolom template dengan placeholder [nama]/[link].
        // Pilihan + teks sendiri disimpan di perangkat ini (localStorage per
        // undangan), bukan di server, supaya halaman publik ini tidak bisa
        // dipakai orang lain untuk mengubah template undangan.
        function safeStorageGet(k) { try { return localStorage.getItem(k); } catch (e) { return null; } }
        function safeStorageSet(k, v) { try { localStorage.setItem(k, v); } catch (e) {} }
        let templateMode = safeStorageGet(`gen_tpl_mode_${invSlug}`) || 'sistem';
        let customTemplate = safeStorageGet(`gen_tpl_custom_${invSlug}`) || '';

        function buildWaMessage(name, link) {
            if (templateMode === 'sendiri' && customTemplate.trim()) {
                return customTemplate.replace(/\[nama\]/g, name).replace(/\[link\]/g, link);
            }
            return `Kepada Yth,\n*${name}*\nDitempat\n\nTanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i, teman dan juga sahabat, untuk menghadiri acara Resepsi kami\n\n*${groom} & ${bride}*\n\nPesan ini merupakan undangan resmi dari kami. Silahkan kunjungi link berikut untuk membuka undangan anda:\n${link}\n\nMerupakan suatu kebahagiaan jika Bapak/Ibu/Saudara/i berkenan untuk hadir dan memberikan doa restu untuk kami. Terima kasih banyak atas waktu & perhatiannya.`;
        }

        // Saat mode Sendiri dipilih, pratinjau sistem tidak ditampilkan:
        // kotak pratinjau disembunyikan sampai template sendiri ditulis,
        // dan setelah itu pratinjau menampilkan template sendiri tersebut
        // (bukan template sistem). Pengiriman dengan template sendiri yang
        // masih kosong tetap memakai template sistem sebagai pengaman.
        function updateTemplatePreview() {
            const wrap = document.getElementById('template-preview-wrap');
            const el = document.getElementById('template-preview');
            if (!wrap || !el) return;
            if (templateMode === 'sendiri' && !customTemplate.trim()) {
                wrap.classList.add('hidden');
                return;
            }
            wrap.classList.remove('hidden');
            el.textContent = buildWaMessage('Nama Tamu', `${baseUrl}/${invSlug}?to=nama-tamu`);
        }

        function initTemplateUI() {
            const radioSistem = document.getElementById('tpl-mode-sistem');
            const radioSendiri = document.getElementById('tpl-mode-sendiri');
            const area = document.getElementById('custom-template');
            if (!radioSistem || !radioSendiri || !area) return;
            if (templateMode === 'sendiri') radioSendiri.checked = true; else radioSistem.checked = true;
            area.value = customTemplate;
            area.disabled = templateMode !== 'sendiri';
            document.querySelectorAll('input[name="wa-template-mode"]').forEach(r => {
                r.addEventListener('change', () => {
                    templateMode = document.querySelector('input[name="wa-template-mode"]:checked').value;
                    safeStorageSet(`gen_tpl_mode_${invSlug}`, templateMode);
                    area.disabled = templateMode !== 'sendiri';
                    updateTemplatePreview();
                });
            });
            area.addEventListener('input', () => {
                customTemplate = area.value;
                safeStorageSet(`gen_tpl_custom_${invSlug}`, customTemplate);
                updateTemplatePreview();
            });
            updateTemplatePreview();
        }

        function showExistingGuest(name, slug) {
            const link = `${baseUrl}/${invSlug}?to=${slug}`;
            currentGuestSlug = slug;
            const known = generatorGuests.find(g => g.slug === slug);
            currentGuestPhone = known ? (known.no_hp || '') : '';
            document.getElementById('guest_name').value = name;
            document.getElementById('result-link').value = link;
            document.getElementById('result-message').value = buildWaMessage(name, link);
            currentGuestPhone = '';
            document.getElementById('result-container').classList.remove('hidden');
            document.getElementById('guest-suggestions').classList.add('hidden');
            document.getElementById('result-container').scrollIntoView({ behavior: 'smooth' });
        }

        let searchTimer = null;
        let lastSearchResults = [];
        document.getElementById('guest_name').addEventListener('input', function () {
            const q = this.value.trim();
            const box = document.getElementById('guest-suggestions');
            clearTimeout(searchTimer);
            if (q.length < 3) { box.classList.add('hidden'); box.innerHTML = ''; return; }
            searchTimer = setTimeout(async () => {
                try {
                    const res = await fetch(`{{ url('api/search_guest') }}?invitation_id=${invitationId}&q=${encodeURIComponent(q)}`);
                    const data = await res.json();
                    if (!data.success || !data.data.length) { box.classList.add('hidden'); box.innerHTML = ''; return; }
                    lastSearchResults = data.data;
                    // Tombol saran TIDAK boleh memakai onclick="...JSON.stringify..."
                    // karena string JSON bertanda petik ganda akan memotong atribut
                    // onclick yang juga bertanda petik ganda sehingga kliknya mati.
                    // Sebagai gantinya pakai data-idx + addEventListener.
                    box.innerHTML = `<div class="px-3 py-2 bg-amber-50 text-[11px] font-bold text-amber-800 uppercase tracking-widest">Tamu sudah ada — pakai link ini supaya tidak dobel:</div>` + data.data.map((g, i) =>
                        `<button type="button" data-idx="${i}" class="suggestion-btn w-full text-left px-3 py-2 hover:bg-gray-50 text-sm border-t border-gray-100">${escHtml(g.nama)}</button>`
                    ).join('');
                    box.querySelectorAll('.suggestion-btn').forEach(btn => {
                        btn.addEventListener('click', () => {
                            const g = lastSearchResults[Number(btn.dataset.idx)];
                            if (g) showExistingGuest(g.nama, g.slug);
                        });
                    });
                    box.classList.remove('hidden');
                } catch (e) { /* abaikan */ }
            }, 300);
        });
        document.addEventListener('click', function (e) {
            if (!e.target.closest('#guest-suggestions') && e.target.id !== 'guest_name') {
                document.getElementById('guest-suggestions').classList.add('hidden');
            }
        });

        async function generateLink(e) {
            e.preventDefault();

            const btn = document.getElementById('btn-generate');
            const nameInput = document.getElementById('guest_name').value.trim();
            const phoneInput = document.getElementById('guest_phone').value.trim();

            if (!nameInput) return;

            btn.disabled = true;
            btn.innerHTML = '<svg class="icon spin" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg> Generating...';

            try {
                const res = await fetch("{{ url('api/generate_guest') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ invitation_id: invitationId, nama: nameInput, no_hp: phoneInput })
                });

                const data = await res.json();

                if (data.success) {
                    const guestSlug = data.data.slug;
                    const inviteLink = `${baseUrl}/${invSlug}?to=${guestSlug}`;

                    const message = buildWaMessage(nameInput, inviteLink);

                    document.getElementById('result-link').value = inviteLink;
                    document.getElementById('result-message').value = message;

                    currentGuestPhone = phoneInput;
                    currentGuestSlug = guestSlug;

                    document.getElementById('result-container').classList.remove('hidden');
                    document.getElementById('result-container').scrollIntoView({ behavior: 'smooth' });
                    loadGeneratorGuests();
                } else {
                    alert(data.message || "Gagal membuat link.");
                }
            } catch (err) {
                alert("Terjadi kesalahan sistem. Pastikan server berjalan.");
                console.error(err);
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/></svg> Generate Link';
            }
        }

        // Tandai WA terkirim / batalkan tanda. Mengembalikan {ok, wa_sent_at, message}.
        // Gagal yang paling mungkin: migrasi wa_sent_at belum dijalankan.
        async function markWa(slug, sent) {
            try {
                const res = await fetch("{{ url('api/mark_guest_wa') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ invitation_id: invitationId, slug, sent })
                });
                const data = await res.json();
                if (!data.success) return { ok: false, wa_sent_at: null, message: data.message || 'Gagal menandai.' };
                return { ok: true, wa_sent_at: data.wa_sent_at, message: '' };
            } catch (e) {
                return { ok: false, wa_sent_at: null, message: 'Terjadi kesalahan sistem.' };
            }
        }

        // Waktu server disimpan dalam UTC (config Laravel: UTC), sementara
        // kode lama menafsirkan "2026-10-26 08:30:00" sebagai waktu lokal,
        // sehingga jamnya meleset 7 jam padahal tanggalnya tetap benar.
        // Perbaikan: string tanpa zona dianggap UTC (tambah 'Z'), string
        // berzona ISO dipakai apa adanya, lalu ditampilkan eksplisit WIB.
        function parseServerTime(s) {
            if (!s) return null;
            let t = String(s).trim().replace(' ', 'T');
            if (!/[Zz]$/.test(t) && !/[+-]\d{2}:?\d{2}$/.test(t)) t += 'Z';
            const d = new Date(t);
            return isNaN(d) ? null : d;
        }

        function formatWaDate(s) {
            const d = parseServerTime(s);
            if (!d) return '';
            return d.toLocaleString('id-ID', { timeZone: 'Asia/Jakarta', day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        }

        function waBadge(g) {
            return g.wa_sent_at
                ? `<span class="text-[11px] font-bold text-green-600">SUDAH DIKIRIM</span><div class="text-[11px] opacity-60">${escHtml(formatWaDate(g.wa_sent_at))}</div>`
                : `<span class="text-[11px] font-bold text-gray-400">BELUM DIKIRIM</span>`;
        }

        let guestSearchTerm = '';
        let filteredGeneratorGuests = [];
        let editingSlug = null;
        let editingName = '';

        function syncGuestWa(slug, wa) {
            const orig = generatorGuests.find(g => g.slug === slug);
            if (orig) orig.wa_sent_at = wa;
        }

        async function loadGeneratorGuests() {
            const btn = document.getElementById('btn-refresh-guests');
            const tbody = document.getElementById('generator-guest-tbody');
            const emptyEl = document.getElementById('generator-guest-empty');
            if (btn) { btn.disabled = true; btn.innerHTML = '<svg class="icon spin" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg> Memuat...'; }
            if (tbody) tbody.innerHTML = '<tr><td colspan="4" class="px-3 py-4 text-sm text-gray-500 text-center"><svg class="icon spin" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg> Memuat daftar tamu...</td></tr>';
            if (emptyEl) emptyEl.classList.add('hidden');
            try {
                const res = await fetch(`{{ url('api/generator_guests') }}?invitation_id=${invitationId}`);
                const data = await res.json();
                if (!data.success) throw new Error('gagal');
                generatorGuests = (data.data || []).map(g => ({ ...g, link: `${baseUrl}/${invSlug}?to=${g.slug}` }));
                renderGeneratorGuests();
            } catch (e) {
                if (tbody) tbody.innerHTML = '<tr><td colspan="4" class="px-3 py-4 text-sm text-red-500 text-center">Gagal memuat daftar tamu. Coba muat ulang.</td></tr>';
            } finally {
                if (btn) { btn.disabled = false; btn.innerHTML = '<svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg> Muat Ulang'; }
            }
        }

        function renderGeneratorGuests() {
            filteredGeneratorGuests = generatorGuests.filter(g => !guestSearchTerm || g.nama.toLowerCase().includes(guestSearchTerm) || g.slug.toLowerCase().includes(guestSearchTerm));
            document.getElementById('generator-guest-count').textContent = guestSearchTerm ? `${filteredGeneratorGuests.length} dari ${generatorGuests.length}` : `${generatorGuests.length}`;
            const emptyEl = document.getElementById('generator-guest-empty');
            emptyEl.classList.toggle('hidden', filteredGeneratorGuests.length > 0);
            emptyEl.textContent = guestSearchTerm ? 'Tidak ada tamu yang cocok dengan pencarian.' : 'Belum ada tamu yang diinput untuk undangan ini.';
            document.getElementById('generator-guest-tbody').innerHTML = filteredGeneratorGuests.map((g, i) => {
                const isEditing = g.slug === editingSlug;
                const actions = isEditing
                    ? `<button type="button" onclick="saveEditGuest()" class="p-1.5 text-green-600 hover:bg-gray-100 rounded-lg" title="Simpan"><svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M9 16.17 4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></button>
                       <button type="button" onclick="cancelEditGuest()" class="p-1.5 text-gray-500 hover:bg-gray-100 rounded-lg" title="Batal"><svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg></button>`
                    : `<button type="button" onclick="copyGeneratorLink(${i})" class="p-1.5 text-[#775a19] hover:bg-gray-100 rounded-lg" title="Salin link"><svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg></button>
                       <button type="button" onclick="sendGeneratorWA(${i})" class="p-1.5 text-green-600 hover:bg-gray-100 rounded-lg" title="Kirim WA"><svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z"/></svg></button>
                       <button type="button" onclick="toggleGeneratorWA(${i})" class="p-1.5 text-amber-600 hover:bg-gray-100 rounded-lg" title="${g.wa_sent_at ? 'Batalkan tanda terkirim' : 'Tandai sudah dikirim'}">${g.wa_sent_at ? ICONS.undo : ICONS.check}</button>
                       <button type="button" onclick="startEditGuest(${i})" class="p-1.5 text-[#775a19] hover:bg-gray-100 rounded-lg" title="Edit nama"><svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg></button>
                       <button type="button" onclick="deleteGeneratorGuest(${i})" class="p-1.5 text-red-400 hover:bg-gray-100 rounded-lg" title="Hapus tamu"><svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg></button>`;
                return `<tr class="border-t border-gray-100">
                    <td data-label="Nama" class="px-3 py-2 font-semibold">${isEditing ? `<input type="text" id="edit-guest-name" value="${escHtml(editingName)}" class="w-full min-w-0 px-2 py-1.5 border border-gray-300 rounded-lg text-sm outline-none">` : escHtml(g.nama)}</td>
                    <td data-label="Link" class="px-3 py-2"><input type="text" readonly value="${escHtml(g.link)}" class="bg-gray-50 text-xs p-2 rounded w-full min-w-0 outline-none"></td>
                    <td data-label="Status WA" class="px-3 py-2">${waBadge(g)}</td>
                    <td data-label="Aksi" class="px-3 py-2"><div class="flex gap-1 shrink-0">${actions}</div></td>
                </tr>`;
            }).join('');
        }

        function copyGeneratorLink(i) {
            if (!filteredGeneratorGuests[i]) return;
            copyTextWithToast(filteredGeneratorGuests[i].link, 'Link tersalin.');
        }

        async function sendGeneratorWA(i) {
            const g = filteredGeneratorGuests[i];
            if (!g) return;
            window.open(bulkWaUrl(g.nama, g.link, g.no_hp || ''), '_blank');
            const r = await markWa(g.slug, true);
            if (r.ok) { g.wa_sent_at = r.wa_sent_at; syncGuestWa(g.slug, r.wa_sent_at); renderGeneratorGuests(); }
            else alert(r.message);
        }

        async function toggleGeneratorWA(i) {
            const g = filteredGeneratorGuests[i];
            if (!g) return;
            const r = await markWa(g.slug, !g.wa_sent_at);
            if (r.ok) { g.wa_sent_at = r.wa_sent_at; syncGuestWa(g.slug, r.wa_sent_at); renderGeneratorGuests(); }
            else alert(r.message);
        }

        function startEditGuest(i) {
            const g = filteredGeneratorGuests[i];
            if (!g) return;
            editingSlug = g.slug;
            editingName = g.nama;
            renderGeneratorGuests();
            const input = document.getElementById('edit-guest-name');
            if (input) { input.focus(); try { input.setSelectionRange(input.value.length, input.value.length); } catch (e) {} }
        }

        function cancelEditGuest() {
            editingSlug = null;
            editingName = '';
            renderGeneratorGuests();
        }

        async function saveEditGuest() {
            const input = document.getElementById('edit-guest-name');
            const nama = input ? input.value.trim() : '';
            if (!nama) { alert('Nama tamu tidak boleh kosong.'); return; }
            if (!editingSlug) return;
            try {
                const res = await fetch("{{ url('api/update_generator_guest') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ invitation_id: invitationId, slug: editingSlug, nama })
                });
                const data = await res.json();
                if (!data.success) { alert(data.message || 'Gagal menyimpan.'); return; }
                const orig = generatorGuests.find(g => g.slug === editingSlug);
                if (orig) orig.nama = nama;
                const bulk = bulkResults.find(r => r.slug === editingSlug);
                if (bulk) { bulk.nama = nama; renderBulkTable(); }
                if (currentGuestSlug === editingSlug) {
                    document.getElementById('guest_name').value = nama;
                    document.getElementById('result-message').value = buildWaMessage(nama, `${baseUrl}/${invSlug}?to=${editingSlug}`);
                }
                editingSlug = null;
                editingName = '';
                renderGeneratorGuests();
            } catch (e) {
                alert('Terjadi kesalahan sistem.');
            }
        }

        async function deleteGeneratorGuest(i) {
            const g = filteredGeneratorGuests[i];
            if (!g) return;
            if (!confirm(`Hapus tamu "${g.nama}"? Link undangannya tidak akan berfungsi lagi.`)) return;
            try {
                const res = await fetch("{{ url('api/delete_generator_guest') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ invitation_id: invitationId, slug: g.slug })
                });
                const data = await res.json();
                if (!data.success) { alert(data.message || 'Gagal menghapus.'); return; }
                generatorGuests = generatorGuests.filter(x => x.slug !== g.slug);
                bulkResults = bulkResults.filter(r => r.slug !== g.slug);
                renderBulkTable();
                if (editingSlug === g.slug) { editingSlug = null; editingName = ''; }
                if (currentGuestSlug === g.slug) {
                    currentGuestSlug = '';
                    currentGuestPhone = '';
                    document.getElementById('result-container').classList.add('hidden');
                }
                renderGeneratorGuests();
                showToast(`Tamu "${g.nama}" dihapus.`);
            } catch (e) {
                alert('Terjadi kesalahan sistem.');
            }
        }

        document.getElementById('generator-guest-search').addEventListener('input', function () {
            guestSearchTerm = this.value.trim().toLowerCase();
            renderGeneratorGuests();
        });

        // Format massal: "Nama, nomor WA" — koma saja sebagai pemisah.
        // Baris tanpa koma berarti nama saja (nomor kosong).
        function parseBulkInput(text) {
            const guests = [];
            text.split('\n').forEach(line => {
                const trimmed = line.trim();
                if (!trimmed) return;
                const comma = trimmed.indexOf(',');
                const nama = (comma === -1 ? trimmed : trimmed.slice(0, comma)).trim();
                const no_hp = comma === -1 ? '' : trimmed.slice(comma + 1).trim();
                if (!nama) return;
                guests.push({ nama, no_hp });
            });
            return guests;
        }

        function bulkWaUrl(name, link, phone) {
            let waUrl = `https://wa.me/`;
            if (phone) {
                let cleanPhone = String(phone).replace(/\D/g, '');
                if (cleanPhone.startsWith('0')) cleanPhone = '62' + cleanPhone.substring(1);
                waUrl += cleanPhone;
            }
            waUrl += `?text=${encodeURIComponent(buildWaMessage(name, link))}`;
            return waUrl;
        }

        async function generateBulk() {
            const btn = document.getElementById('btn-bulk');
            const guests = parseBulkInput(document.getElementById('bulk-input').value);
            if (!guests.length) { alert('Isi dulu daftar tamunya, satu tamu per baris.'); return; }
            if (guests.length > 100) { alert('Maksimal 100 tamu sekali generate. Daftar ini berisi ' + guests.length + ' tamu.'); return; }
            btn.disabled = true;
            btn.innerHTML = '<svg class="icon spin" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg> Generating...';
            try {
                const res = await fetch("{{ url('api/generate_guests_bulk') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ invitation_id: invitationId, guests })
                });
                const data = await res.json();
                if (!data.success) { alert(data.message || 'Gagal generate massal.'); return; }
                bulkResults = (data.data || []).map(r => ({ ...r, link: `${baseUrl}/${invSlug}?to=${r.slug}` }));
                const baru = bulkResults.filter(r => !r.existed).length;
                const sudah = bulkResults.length - baru;
                document.getElementById('bulk-summary').textContent = `Berhasil: ${bulkResults.length} link (${baru} baru, ${sudah} sudah ada). Baris kosong dan nama dobel di dalam daftar dilewati.`;
                renderBulkTable();
                document.getElementById('bulk-result').classList.remove('hidden');
                loadGeneratorGuests();
            } catch (err) {
                alert('Terjadi kesalahan sistem. Pastikan server berjalan.');
                console.error(err);
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg> Generate Massal';
            }
        }

        function renderBulkTable() {
            document.getElementById('bulk-tbody').innerHTML = bulkResults.map((r, i) =>
                `<tr class="border-t border-gray-100">
                    <td data-label="Nama" class="px-3 py-2 font-semibold">${escHtml(r.nama)}</td>
                    <td data-label="Link" class="px-3 py-2"><input type="text" readonly value="${escHtml(r.link)}" class="bg-gray-50 text-xs p-2 rounded w-full min-w-0 outline-none"></td>
                    <td data-label="Status" class="px-3 py-2"><span class="text-[11px] font-bold ${r.existed ? 'text-amber-600' : 'text-green-600'}">${r.existed ? 'SUDAH ADA' : 'BARU'}</span></td>
                    <td data-label="Status WA" class="px-3 py-2">${waBadge(r)}</td>
                    <td data-label="Aksi" class="px-3 py-2"><div class="flex gap-1 shrink-0">
                        <button type="button" onclick="copyBulkLink(${i})" class="p-1.5 text-[#775a19] hover:bg-gray-100 rounded-lg" title="Salin link"><svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg></button>
                        <button type="button" onclick="sendBulkWA(${i})" class="p-1.5 text-green-600 hover:bg-gray-100 rounded-lg" title="Kirim WA"><svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z"/></svg></button>
                    </div></td>
                </tr>`
            ).join('');
        }

        function copyBulkLink(i) {
            if (!bulkResults[i]) return;
            copyTextWithToast(bulkResults[i].link, 'Link tersalin.');
        }

        async function sendBulkWA(i) {
            const r = bulkResults[i];
            if (!r) return;
            window.open(bulkWaUrl(r.nama, r.link, r.no_hp || ''), '_blank');
            const res = await markWa(r.slug, true);
            if (res.ok) { r.wa_sent_at = res.wa_sent_at; renderBulkTable(); loadGeneratorGuests(); }
            else alert(res.message);
        }

        function copyAllBulkLinks() {
            if (!bulkResults.length) return;
            copyTextWithToast(bulkResults.map(r => r.link).join('\n'), `Semua link tersalin (${bulkResults.length} link).`);
        }

        function downloadBulkCsv() {
            if (!bulkResults.length) return;
            const escCsv = v => '"' + String(v ?? '').replace(/"/g, '""') + '"';
            const rows = [['Nama', 'Link', 'Status']];
            bulkResults.forEach(r => rows.push([r.nama, r.link, r.existed ? 'Sudah ada' : 'Baru']));
            const csv = '\uFEFF' + rows.map(r => r.map(escCsv).join(';')).join('\r\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'link-tamu-' + invSlug + '.csv';
            a.click();
            URL.revokeObjectURL(a.href);
        }

        function copyMessage() {
            copyTextWithToast(document.getElementById('result-message').value, 'Pesan tersalin.');
        }

        function copyLink() {
            copyTextWithToast(document.getElementById('result-link').value, 'Link tersalin.');
        }

        async function sendWhatsapp() {
            const msg = document.getElementById('result-message').value;
            let waUrl = `https://wa.me/`;
            if (currentGuestPhone) {
                // Bersihkan no_hp, pastikan format 62
                let cleanPhone = currentGuestPhone.replace(/\D/g, '');
                if (cleanPhone.startsWith('0')) {
                    cleanPhone = '62' + cleanPhone.substring(1);
                }
                waUrl += `${cleanPhone}`;
            }
            waUrl += `?text=${encodeURIComponent(msg)}`;
            window.open(waUrl, '_blank');
            if (currentGuestSlug) {
                const res = await markWa(currentGuestSlug, true);
                if (res.ok) loadGeneratorGuests();
                else alert(res.message);
            }
        }

        loadGeneratorGuests();
        initTemplateUI();
    </script>
</body>

</html>