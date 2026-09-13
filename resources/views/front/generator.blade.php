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
                    <input type="text" id="guest_name" required placeholder="Contoh: Keluarga Bpk. Budi"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#775a19] focus:ring-1 focus:ring-[#775a19] transition-all">
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

                    const message = `Kepada Yth,\n*${nameInput}*\nDitempat\n\nTanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i, teman dan juga sahabat, untuk menghadiri acara Resepsi kami\n\n*${groom} & ${bride}*\n\nPesan ini merupakan undangan resmi dari kami. Silahkan kunjungi link berikut untuk membuka undangan anda:\n${inviteLink}\n\nMerupakan suatu kebahagiaan jika Bapak/Ibu/Saudara/i berkenan untuk hadir dan memberikan doa restu untuk kami. Terima kasih banyak atas waktu & perhatiannya.`;

                    document.getElementById('result-link').value = inviteLink;
                    document.getElementById('result-message').value = message;

                    currentGuestPhone = phoneInput;

                    document.getElementById('result-container').classList.remove('hidden');
                    document.getElementById('result-container').scrollIntoView({ behavior: 'smooth' });
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

        function sendWhatsapp() {
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
        }
    </script>
</body>

</html>