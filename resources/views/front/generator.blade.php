<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Guest Link Generator - {{ $bride }} & {{ $groom }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;600&family=Work+Sans:wght@400;500&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <style>
        body {
            font-family: 'Work Sans', sans-serif;
            background-color: #f5f3ef;
            color: #1b1c1a;
        }

        h1,
        h2,
        h3 {
            font-family: 'Noto Serif', serif;
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
                    <span class="material-symbols-outlined">link</span>
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
                            <span class="material-symbols-outlined text-base">content_copy</span>
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
                        <span class="material-symbols-outlined text-base">content_copy</span>
                        Copy Pesan
                    </button>
                    <button type="button" onclick="sendWhatsapp()" id="btn-wa"
                        class="btn-primary font-bold uppercase tracking-widest py-3 rounded-xl flex items-center justify-center gap-2 text-xs">
                        <span class="material-symbols-outlined text-base">send</span>
                        Kirim via WA
                    </button>
                </div>
            </div>


            <!-- Generate Massal -->
            <div class="mt-10 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-bold text-[#775a19] mb-2">Generate Massal</h3>
                <p class="text-sm text-gray-600 mb-4">Tempel daftar tamu, satu tamu per baris. Format: <span class="font-semibold">Nama | nomor WA</span> (nomor boleh kosong). Pemisah juga bisa titik koma atau tab. Maksimal 100 tamu sekali generate; nama yang sudah ada tidak dibuat ulang.</p>
                <textarea id="bulk-input" rows="6" placeholder="Keluarga Bpk. Budi | 628123456789&#10;Ibu Sari&#10;Bpk. Ahmad | 628987654321"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#775a19] focus:ring-1 focus:ring-[#775a19] transition-all text-sm"></textarea>
                <button type="button" id="btn-bulk" onclick="generateBulk()"
                    class="mt-4 w-full btn-primary font-bold uppercase tracking-widest py-3 rounded-xl flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">groups</span>
                    Generate Massal
                </button>

                <div id="bulk-result" class="hidden mt-6">
                    <p id="bulk-summary" class="text-sm font-semibold text-gray-700 mb-3"></p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <button type="button" onclick="copyAllBulkLinks()" id="btn-copy-all"
                            class="btn-outline font-bold uppercase tracking-widest py-2 px-4 rounded-xl text-xs flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">content_copy</span> Salin Semua Link
                        </button>
                        <button type="button" onclick="downloadBulkCsv()"
                            class="btn-outline font-bold uppercase tracking-widest py-2 px-4 rounded-xl text-xs flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">download</span> Unduh CSV
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
                    <button type="button" onclick="loadGeneratorGuests()"
                        class="btn-outline font-bold uppercase tracking-widest py-2 px-4 rounded-xl text-xs flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">refresh</span> Muat Ulang
                    </button>
                </div>
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
                        <tbody id="generator-guest-tbody"></tbody>
                    </table>
                </div>
                <p id="generator-guest-empty" class="hidden text-sm text-gray-500 mt-3">Belum ada tamu yang diinput untuk undangan ini.</p>
            </div>

        </div>
    </div>
    </div>

    <script>
        const invitationId = {{ $invitation->id }};
        const invSlug = "{{ $invitation->slug }}";
        const bride = "{{ $bride }}";
        const groom = "{{ $groom }}";
        const baseUrl = "{{ url('/') }}";

        let currentGuestPhone = "";
        let currentGuestSlug = "";
        let bulkResults = [];
        let generatorGuests = [];

        function escHtml(s) {
            return String(s == null ? '' : s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
        }

        function buildWaMessage(name, link) {
            return `Kepada Yth,\n*${name}*\nDitempat\n\nTanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i, teman dan juga sahabat, untuk menghadiri acara Resepsi kami\n\n*${groom} & ${bride}*\n\nPesan ini merupakan undangan resmi dari kami. Silahkan kunjungi link berikut untuk membuka undangan anda:\n${link}\n\nMerupakan suatu kebahagiaan jika Bapak/Ibu/Saudara/i berkenan untuk hadir dan memberikan doa restu untuk kami. Terima kasih banyak atas waktu & perhatiannya.`;
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
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Generating...';

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
                btn.innerHTML = '<span class="material-symbols-outlined">link</span> Generate Link';
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

        async function loadGeneratorGuests() {
            try {
                const res = await fetch(`{{ url('api/generator_guests') }}?invitation_id=${invitationId}`);
                const data = await res.json();
                if (!data.success) return;
                generatorGuests = (data.data || []).map(g => ({ ...g, link: `${baseUrl}/${invSlug}?to=${g.slug}` }));
                renderGeneratorGuests();
            } catch (e) { /* abaikan */ }
        }

        function renderGeneratorGuests() {
            document.getElementById('generator-guest-count').textContent = generatorGuests.length;
            document.getElementById('generator-guest-empty').classList.toggle('hidden', generatorGuests.length > 0);
            document.getElementById('generator-guest-tbody').innerHTML = generatorGuests.map((g, i) =>
                `<tr class="border-t border-gray-100">
                    <td data-label="Nama" class="px-3 py-2 font-semibold">${escHtml(g.nama)}</td>
                    <td data-label="Link" class="px-3 py-2"><input type="text" readonly value="${escHtml(g.link)}" class="bg-gray-50 text-xs p-2 rounded w-full min-w-0 outline-none"></td>
                    <td data-label="Status WA" class="px-3 py-2">${waBadge(g)}</td>
                    <td data-label="Aksi" class="px-3 py-2"><div class="flex gap-1 shrink-0">
                        <button type="button" onclick="copyGeneratorLink(${i})" class="p-1.5 text-[#775a19] hover:bg-gray-100 rounded-lg" title="Salin link"><span class="material-symbols-outlined text-base">content_copy</span></button>
                        <button type="button" onclick="sendGeneratorWA(${i})" class="p-1.5 text-green-600 hover:bg-gray-100 rounded-lg" title="Kirim WA"><span class="material-symbols-outlined text-base">send</span></button>
                        <button type="button" onclick="toggleGeneratorWA(${i})" class="p-1.5 text-amber-600 hover:bg-gray-100 rounded-lg" title="${g.wa_sent_at ? 'Batalkan tanda terkirim' : 'Tandai sudah dikirim'}"><span class="material-symbols-outlined text-base">${g.wa_sent_at ? 'undo' : 'check'}</span></button>
                    </div></td>
                </tr>`
            ).join('');
        }

        function copyGeneratorLink(i) {
            if (!generatorGuests[i]) return;
            navigator.clipboard.writeText(generatorGuests[i].link);
        }

        async function sendGeneratorWA(i) {
            const g = generatorGuests[i];
            if (!g) return;
            window.open(bulkWaUrl(g.nama, g.link, g.no_hp || ''), '_blank');
            const r = await markWa(g.slug, true);
            if (r.ok) { g.wa_sent_at = r.wa_sent_at; renderGeneratorGuests(); }
            else alert(r.message);
        }

        async function toggleGeneratorWA(i) {
            const g = generatorGuests[i];
            if (!g) return;
            const r = await markWa(g.slug, !g.wa_sent_at);
            if (r.ok) { g.wa_sent_at = r.wa_sent_at; renderGeneratorGuests(); }
            else alert(r.message);
        }

        function parseBulkInput(text) {
            const guests = [];
            text.split('\n').forEach(line => {
                const trimmed = line.trim();
                if (!trimmed) return;
                const parts = trimmed.split(/[|;\t]/).map(p => p.trim());
                const nama = parts[0] || '';
                if (!nama) return;
                guests.push({ nama, no_hp: parts[1] || '' });
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
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Generating...';
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
                btn.innerHTML = '<span class="material-symbols-outlined">groups</span> Generate Massal';
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
                        <button type="button" onclick="copyBulkLink(${i})" class="p-1.5 text-[#775a19] hover:bg-gray-100 rounded-lg" title="Salin link"><span class="material-symbols-outlined text-base">content_copy</span></button>
                        <button type="button" onclick="sendBulkWA(${i})" class="p-1.5 text-green-600 hover:bg-gray-100 rounded-lg" title="Kirim WA"><span class="material-symbols-outlined text-base">send</span></button>
                    </div></td>
                </tr>`
            ).join('');
        }

        function copyBulkLink(i) {
            if (!bulkResults[i]) return;
            navigator.clipboard.writeText(bulkResults[i].link);
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
            navigator.clipboard.writeText(bulkResults.map(r => r.link).join('\n')).then(() => {
                const btn = document.getElementById('btn-copy-all');
                const ori = btn.innerHTML;
                btn.innerHTML = '<span class="material-symbols-outlined text-base">check</span> Tersalin';
                setTimeout(() => { btn.innerHTML = ori; }, 2000);
            });
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
            const msg = document.getElementById('result-message').value;
            navigator.clipboard.writeText(msg).then(() => {
                const btn = document.getElementById('btn-copy');
                const oriText = btn.innerHTML;
                btn.innerHTML = '<span class="material-symbols-outlined text-base">check</span> Tersalin';
                setTimeout(() => { btn.innerHTML = oriText; }, 2000);
            });
        }

        function copyLink() {
            const link = document.getElementById('result-link').value;
            navigator.clipboard.writeText(link).then(() => {
                const btn = document.getElementById('btn-copy-link');
                const oriText = btn.innerHTML;
                btn.innerHTML = '<span class="material-symbols-outlined text-base">check</span>';
                setTimeout(() => { btn.innerHTML = oriText; }, 2000);
            });
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
    </script>
</body>

</html>