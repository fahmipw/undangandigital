@php
// Harga paket ($harga, $hargaCoret) + hero ($hero) + $videoCount datang dari
// FrontController::landing() — diatur lewat tab "Landing Page" di admin.
// Jumlah tema per filter katalog.
$filterCounts = ['Semua' => count($themes)];
foreach ($themes as $th) {
    $filterCounts[$th['cat']] = ($filterCounts[$th['cat']] ?? 0) + 1;
    if ($th['video']) $filterCounts['Motion'] = ($filterCounts['Motion'] ?? 0) + 1;
}
// Urutan pill: kategori baku dulu, lalu kategori baru (urutan kemunculan),
// lalu Motion. Kategori baru yang dibuat dari admin otomatis dapat pill.
$filterOrder = ['Semua'];
foreach (['Jawa', 'Nusantara', 'Islami', 'Alam', 'Modern', 'Game'] as $c) {
    if (isset($filterCounts[$c])) $filterOrder[] = $c;
}
foreach ($themes as $th) {
    if (!in_array($th['cat'], $filterOrder, true)) $filterOrder[] = $th['cat'];
}
$filterOrder[] = 'Motion';
$waText = fn($msg) => 'https://wa.me/' . $waNumber . '?text=' . urlencode($msg);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>undangankamu — Undangan Digital Pernikahan</title>
<meta name="description" content="Undangan digital pernikahan: 35 tema cantik, RSVP, galeri, musik, gift QRIS, dan check-in QR untuk panitia. Pesan mudah lewat WhatsApp.">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script>
tailwind.config = {
  theme: { extend: {
    colors: { ivory: '#FDFBF7', cream: '#F5EFE3', pine: '#1E4D33', pinedark: '#143826', gold: '#C9A227', goldsoft: '#E7D9A8' },
    fontFamily: { serif: ['"Playfair Display"', 'Georgia', 'serif'], sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'] }
  } }
};
</script>
<style>
  html { scroll-behavior: smooth; }
  body { font-family: "Plus Jakarta Sans", system-ui, sans-serif; }
  .gold-grad { background: linear-gradient(135deg, #C9A227, #E7D9A8 55%, #C9A227); -webkit-background-clip: text; background-clip: text; color: transparent; }
  .filter-pill.active { background: #1E4D33; color: #fff; border-color: #1E4D33; }
  .pill-count { background: #F5EFE3; color: #1E4D33; }
  .filter-pill.active .pill-count { background: #C9A227; color: #143826; }
  /* Hero ala kekondangan: 2 kolom kartu tema bergulir vertikal, geser -50% mulus */
  .hero-cols { -webkit-mask-image: linear-gradient(to bottom, transparent, #000 10%, #000 90%, transparent); mask-image: linear-gradient(to bottom, transparent, #000 10%, #000 90%, transparent); }
  .hero-col { flex: 1; min-width: 0; overflow: hidden; }
  .hero-col-track { display: flex; flex-direction: column; animation: hero-col-up 120s linear infinite; }
  .hero-col-b { animation-duration: 150s; animation-direction: reverse; }
  .hero-mini { display: block; padding-bottom: 1rem; }
  .hero-mini-box { position: relative; display: block; width: 100%; aspect-ratio: 3/4; border-radius: 12px; overflow: hidden; background: linear-gradient(160deg, #1E4D33, #3E2618); }
  .hero-mini-box img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: top; -webkit-user-drag: none; user-select: none; }
  .hero-mini-name { display: block; margin-top: .45rem; font-size: .72rem; font-weight: 700; color: #E7E5E4; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  @keyframes hero-col-up { from { transform: translateY(0); } to { transform: translateY(-50%); } }
  .hero-fallback-name { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; text-align: center; padding: 1rem; font-family: "Playfair Display", Georgia, serif; font-weight: 700; font-size: 1.1rem; color: #E7D9A8; }
  @media (prefers-reduced-motion: reduce) { .hero-col-track { animation: none; } }
</style>
</head>
<body class="bg-ivory text-stone-800 antialiased">

<!-- ============ NAV ============ -->
<header class="sticky top-0 z-50 bg-ivory/90 backdrop-blur border-b border-stone-200/70">
  <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
    <a href="#beranda" class="flex items-center gap-2">
      <span class="w-9 h-9 rounded-full bg-pine text-goldsoft flex items-center justify-center font-serif font-bold text-lg">U</span>
      <span class="font-serif font-bold text-xl text-pinedark">undangankamu</span>
    </a>
    <nav class="hidden md:flex items-center gap-7 text-sm font-semibold text-stone-600">
      <a href="#katalog" class="hover:text-pine">Katalog</a>
      <a href="#harga" class="hover:text-pine">Harga</a>
      <a href="#fitur" class="hover:text-pine">Fitur</a>
      <a href="#cara-pesan" class="hover:text-pine">Cara Pesan</a>
      <a href="#faq" class="hover:text-pine">FAQ</a>
      <a href="#syarat" class="hover:text-pine">S&amp;K</a>
    </nav>
    <div class="flex items-center gap-2">
      <a href="/pesan" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-pine text-white text-sm font-bold hover:bg-pinedark transition">Pesan Sekarang</a>
      <button type="button" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="md:hidden p-2 rounded-lg text-stone-600 hover:bg-cream" aria-label="Menu">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
      </button>
    </div>
  </div>
  <div id="mobileMenu" class="hidden md:hidden border-t border-stone-200/70 px-4 py-3 space-y-1 text-sm font-semibold">
    <a href="#katalog" class="block py-2">Katalog</a>
    <a href="#harga" class="block py-2">Harga</a>
    <a href="#fitur" class="block py-2">Fitur</a>
    <a href="#cara-pesan" class="block py-2">Cara Pesan</a>
    <a href="#faq" class="block py-2">FAQ</a>
    <a href="#syarat" class="block py-2">S&amp;K</a>
  </div>
</header>

<!-- ============ HERO ============ -->
<section id="beranda" class="relative overflow-hidden bg-[#1C2B21]">
  <div class="relative max-w-6xl mx-auto px-4 pt-14 pb-16 md:pt-20 md:pb-24 grid md:grid-cols-2 gap-10 items-center">
    <div>
      <p class="text-xs font-extrabold tracking-[0.25em] text-gold">UNDANGAN DIGITAL PERNIKAHAN</p>
      <h1 class="font-serif font-bold text-4xl md:text-5xl leading-tight text-white mt-4">{{ $hero['title'] }} <span class="gold-grad">{{ $hero['highlight'] }}</span></h1>
      <p class="text-stone-300 leading-relaxed mt-4">{{ $hero['subtitle'] }}</p>
      <div class="flex flex-wrap gap-3 mt-7">
        <a href="#katalog" class="px-6 py-3 rounded-full bg-gold text-pinedark font-bold text-sm hover:brightness-110 transition">Pilih tema undangan &rarr;</a>
        <a href="#cara-pesan" class="px-6 py-3 rounded-full border-2 border-white/25 text-white font-bold text-sm hover:border-white/60 transition">Cara pesan</a>
      </div>
      <p class="text-xs text-stone-400 mt-6">Jawa &middot; Nusantara &middot; Islami &middot; Alam &middot; Modern &middot; Game &middot; Motion</p>
      <p class="text-xs text-stone-400 mt-2">Mulai {{ $harga['basic'] }} &bull; Tanpa perlu install aplikasi &bull; Dibuka lewat link</p>
    </div>
    <div class="hero-cols relative h-[420px] md:h-[540px] overflow-hidden">
      <div class="flex gap-4 h-full">
        <div class="hero-col">
          <div class="hero-col-track hero-col-a">
            @foreach (array_merge($themes, $themes) as $t)
              <a href="/hawa-adam?tema={{ $t['slug'] }}&to=tamu-demo" target="_blank" rel="noopener" class="hero-mini" title="Demo tema {{ $t['name'] }}">
                <span class="hero-mini-box">
                  <span class="hero-fallback-name">{{ $t['name'] }}</span>
                  <img src="{{ asset('images/landing/themes/' . $t['slug'] . '.jpg') }}" alt="Tema {{ $t['name'] }}" draggable="false" onerror="this.remove()">
                </span>
                <span class="hero-mini-name">{{ $t['name'] }}</span>
              </a>
            @endforeach
          </div>
        </div>
        <div class="hero-col">
          <div class="hero-col-track hero-col-b">
            @foreach (array_merge($themes, $themes) as $t)
              <a href="/hawa-adam?tema={{ $t['slug'] }}&to=tamu-demo" target="_blank" rel="noopener" class="hero-mini" title="Demo tema {{ $t['name'] }}">
                <span class="hero-mini-box">
                  <span class="hero-fallback-name">{{ $t['name'] }}</span>
                  <img src="{{ asset('images/landing/themes/' . $t['slug'] . '.jpg') }}" alt="Tema {{ $t['name'] }}" draggable="false" onerror="this.remove()">
                </span>
                <span class="hero-mini-name">{{ $t['name'] }}</span>
              </a>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ KATALOG ============ -->
<section id="katalog" class="max-w-6xl mx-auto px-4 py-16">
  <div class="max-w-2xl">
    <p class="text-xs font-extrabold tracking-[0.25em] text-gold">KOLEKSI TEMA</p>
    <h2 class="font-sans font-extrabold text-3xl md:text-4xl text-pinedark mt-2">Buka demonya dulu, baru pilih</h2>
    <p class="text-stone-600 mt-3">Setiap tema punya demo yang bisa kamu buka seperti tamu membukanya. Harga sudah termasuk semua fitur. Klik gambar untuk membuka demonya.</p>
  </div>

  <div class="flex flex-wrap gap-2 mt-8" id="filterBar">
    @foreach ($filterOrder as $f)
      <button type="button" data-filter="{{ $f }}" class="filter-pill px-4 py-1.5 rounded-full border border-stone-300 bg-white text-xs font-bold hover:border-pine transition {{ $f === 'Semua' ? 'active' : '' }}">{{ $f }} <span class="pill-count ml-1 px-1.5 py-0.5 rounded-full text-[10px]">{{ $filterCounts[$f] ?? 0 }}</span></button>
    @endforeach
  </div>

  <div id="themeGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5 mt-8">
    @foreach ($themes as $t)
      <article class="theme-card bg-white rounded-[20px] overflow-hidden border border-stone-200/80 shadow-sm hover:shadow-xl transition" data-cat="{{ $t['cat'] }}" data-video="{{ $t['video'] ? '1' : '0' }}">
        <a href="/hawa-adam?tema={{ $t['slug'] }}&to=tamu-demo" target="_blank" rel="noopener" class="relative block bg-gradient-to-b from-pine to-pinedark aspect-square" title="Buka demo tema {{ $t['name'] }}">
          <img src="{{ asset('images/landing/themes/' . $t['slug'] . '.jpg') }}" alt="Tema {{ $t['name'] }}" loading="lazy" class="absolute inset-0 w-full h-full object-cover" onerror="this.remove()">
          @if ($t['badge'])
            <span class="absolute top-3 right-3 text-[10px] font-bold px-2 py-1 rounded-full bg-pinedark/85 text-goldsoft backdrop-blur-sm">{{ $t['badge'] }}</span>
          @endif
        </a>
        <div class="p-4">
          <div class="flex items-center gap-2">
            <span class="card-cart shrink-0 w-7 h-7 rounded-lg bg-[#F5EFE3] flex items-center justify-center">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1E4D33" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h2l2.4 12.4A2 2 0 0 0 9.36 17H18a2 2 0 0 0 1.96-1.6L21.5 8H6"/><circle cx="10" cy="21" r="1.6"/><circle cx="18" cy="21" r="1.6"/></svg>
            </span>
            <h3 class="font-bold text-[15px] text-stone-900 leading-snug">{{ $t['name'] }}</h3>
          </div>
          <p class="text-[11px] text-stone-500 mt-0.5">Tema {{ $t['cat'] }}</p>
          <p class="mt-2"><span class="font-extrabold text-lg text-stone-900">{{ $t['video'] ? $harga['premium'] : $harga['basic'] }}</span></p>
          <p class="mt-1 flex items-center gap-2">
            <span class="card-disc text-[10px] font-bold px-1.5 py-0.5 rounded bg-rose-100 text-rose-600">50%</span>
            <span class="text-xs text-stone-400 line-through">{{ $t['video'] ? $hargaCoret['premium'] : $hargaCoret['basic'] }}</span>
          </p>
          <div class="border-t border-stone-200/80 mt-3 pt-3 space-y-2">
            <a href="/hawa-adam?tema={{ $t['slug'] }}&to=tamu-demo" target="_blank" rel="noopener" class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-full bg-white border border-stone-300 text-stone-700 text-xs font-bold hover:border-pine transition">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#C9A227" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
              Demo
            </a>
            <a href="/pesan?tema={{ $t['slug'] }}&paket={{ $t['video'] ? 'Premium' : 'Basic' }}" class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-full bg-pine text-white text-xs font-bold hover:bg-pinedark transition">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#C9A227" stroke-width="2"><path d="M3 5h18v14H3z"/><path d="m3 7 9 6 9-6"/></svg>
              Pesan
            </a>
          </div>
        </div>
      </article>
    @endforeach
  </div>
  <div class="text-center mt-8">
    <button id="showMoreBtn" type="button" class="px-8 py-3 rounded-full bg-pine text-white text-sm font-bold hover:bg-pinedark transition">Tampilkan Lainnya</button>
  </div>
  <p id="noResult" class="hidden text-center text-sm text-stone-500 py-10">Tidak ada tema pada kategori ini.</p>
</section>

<!-- ============ HARGA ============ -->
<section id="harga" class="bg-cream/60 border-y border-stone-200/70">
  <div class="max-w-6xl mx-auto px-4 py-16">
    <div class="text-center max-w-2xl mx-auto">
      <h2 class="font-serif font-bold text-3xl md:text-4xl text-pinedark">Harga Paket</h2>
      <p class="text-stone-600 mt-3">Sekali bayar, undangan aktif sampai acara selesai. Tidak ada biaya bulanan.</p>
    </div>
    <div class="grid md:grid-cols-3 gap-5 mt-10 items-stretch">
      <!-- Basic -->
      <div class="bg-white rounded-3xl border border-stone-200 p-6 flex flex-col">
        <h3 class="font-bold text-lg">Basic</h3>
        <p class="font-serif font-bold text-4xl text-pinedark mt-3">{{ $harga['basic'] }}</p>
        <p class="text-xs text-stone-500 mt-1">per undangan</p>
        <ul class="text-sm text-stone-600 space-y-2.5 mt-6 flex-1">
          <li>✓ 1 tema standar (non-motion)</li>
          <li>✓ RSVP &amp; ucapan tamu</li>
          <li>✓ Galeri foto + musik latar</li>
          <li>✓ Amplop digital / gift &amp; QRIS</li>
          <li>✓ Link undangan + nama tamu</li>
        </ul>
        <a href="/pesan?paket=Basic" class="mt-7 block text-center px-4 py-3 rounded-full border-2 border-pine text-pine font-bold text-sm hover:bg-pine hover:text-white transition">Pesan Basic</a>
      </div>
      <!-- Premium -->
      <div class="bg-pinedark text-white rounded-3xl p-6 flex flex-col relative shadow-xl md:-translate-y-2">
        <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-gold text-pinedark text-[11px] font-bold">PALING LARIS</span>
        <h3 class="font-bold text-lg">Premium</h3>
        <p class="font-serif font-bold text-4xl text-goldsoft mt-3">{{ $harga['premium'] }}</p>
        <p class="text-xs text-white/60 mt-1">per undangan</p>
        <ul class="text-sm text-white/85 space-y-2.5 mt-6 flex-1">
          <li>✓ Semua di paket Basic</li>
          <li>✓ Semua {{ count($themes) }} tema, termasuk {{ $videoCount }} tema motion</li>
          <li>✓ Check-in QR + monitor panitia</li>
          <li>✓ Link generator + kirim WA massal</li>
          <li>✓ Hitung mundur &amp; live streaming</li>
        </ul>
        <a href="/pesan?paket=Premium" class="mt-7 block text-center px-4 py-3 rounded-full bg-gold text-pinedark font-bold text-sm hover:brightness-110 transition">Pesan Premium</a>
      </div>
      <!-- Eksklusif -->
      <div class="bg-white rounded-3xl border border-stone-200 p-6 flex flex-col">
        <h3 class="font-bold text-lg">Eksklusif</h3>
        <p class="font-serif font-bold text-4xl text-pinedark mt-3">{{ $harga['eksklusif'] }}</p>
        <p class="text-xs text-stone-500 mt-1">per undangan</p>
        <ul class="text-sm text-stone-600 space-y-2.5 mt-6 flex-1">
          <li>✓ Semua di paket Premium</li>
          <li>✓ Motion opening custom (pakai video/foto sendiri)</li>
          <li>✓ Penyesuaian warna &amp; detail tema</li>
          <li>✓ Prioritas pengerjaan</li>
          <li>✓ Konsultasi sampai hari H</li>
        </ul>
        <a href="/pesan?paket=Eksklusif" class="mt-7 block text-center px-4 py-3 rounded-full border-2 border-pine text-pine font-bold text-sm hover:bg-pine hover:text-white transition">Pesan Eksklusif</a>
      </div>
    </div>
    <p class="text-center text-xs text-stone-500 mt-6">Harga dapat berubah — hubungi kami untuk penawaran terbaru dan promo.</p>
  </div>
</section>

<!-- ============ FITUR ============ -->
<section id="fitur" class="max-w-6xl mx-auto px-4 py-16">
  <div class="text-center max-w-2xl mx-auto">
    <h2 class="font-serif font-bold text-3xl md:text-4xl text-pinedark">Fitur Lengkap</h2>
    <p class="text-stone-600 mt-3">Bukan sekadar kartu online — satu sistem untuk undangan dan panitia.</p>
  </div>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-10">
    @foreach ([
      ['RSVP & Ucapan', 'Tamu konfirmasi hadir & kirim doa, langsung masuk WhatsApp mempelai.'],
      ['Galeri & Motion', 'Galeri foto, video prewedding, dan 4 tema motion sinematik.'],
      ['Musik Latar', 'Musik pengiring yang menyala saat undangan dibuka.'],
      ['Amplop Digital', 'Gift transfer bank & QRIS, lengkap dengan tombol salin rekening.'],
      ['Check-in QR', 'Panitia scan QR tamu di pintu masuk, tercatat otomatis.'],
      ['Monitor Panitia', 'Layar sambutan + kartu tamu saat check-in, lengkap dengan foto galeri.'],
      ['Link Generator', 'Buat link personal per tamu + kirim WA massal sekali klik.'],
      ['Hitung Mundur', 'Countdown acara mengikuti zona waktu (WIB/WITA/WIT).'],
    ] as $f)
      <div class="bg-white rounded-2xl border border-stone-200/80 p-5">
        <h3 class="font-bold text-sm text-pinedark">{{ $f[0] }}</h3>
        <p class="text-xs text-stone-600 leading-relaxed mt-1.5">{{ $f[1] }}</p>
      </div>
    @endforeach
  </div>
</section>

<!-- ============ CARA PESAN ============ -->
<section id="cara-pesan" class="bg-cream/60 border-y border-stone-200/70">
  <div class="max-w-6xl mx-auto px-4 py-16">
    <div class="text-center max-w-2xl mx-auto">
      <h2 class="font-serif font-bold text-3xl md:text-4xl text-pinedark">Cara Pesan</h2>
      <p class="text-stone-600 mt-3">Empat langkah, undanganmu siap dibagikan.</p>
    </div>
    <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-4 mt-10">
      @foreach ([
        ['1', 'Pilih Tema', 'Pilih tema favoritmu dari katalog di atas.'],
        ['2', 'Isi Form Pesanan', 'Klik Pesan — isi tema, paket, data mempelai, dan metode pembayaran.'],
        ['3', 'Isi Data & Bayar', 'Lengkapi data acara, foto, dan lakukan pembayaran.'],
        ['4', 'Undangan Jadi', 'Terima link undangan, bagikan ke tamu lewat WhatsApp.'],
      ] as $s)
        <div class="bg-white rounded-2xl border border-stone-200/80 p-5 text-center">
          <p class="w-10 h-10 mx-auto rounded-full bg-pine text-white font-serif font-bold text-lg flex items-center justify-center">{{ $s[0] }}</p>
          <h3 class="font-bold text-sm mt-3">{{ $s[1] }}</h3>
          <p class="text-xs text-stone-600 leading-relaxed mt-1.5">{{ $s[2] }}</p>
        </div>
      @endforeach
    </div>
    <div class="text-center mt-10">
      <a href="/pesan" class="inline-block px-8 py-3.5 rounded-full bg-pine text-white font-bold text-sm hover:bg-pinedark transition">Mulai Pesan Sekarang</a>
    </div>
  </div>
</section>

<!-- ============ FAQ ============ -->
<section id="faq" class="max-w-3xl mx-auto px-4 py-16">
  <h2 class="font-serif font-bold text-3xl md:text-4xl text-pinedark text-center">Pertanyaan Umum</h2>
  <div class="space-y-3 mt-8">
    <details class="bg-white rounded-2xl border border-stone-200/80 p-5">
      <summary class="font-bold text-sm cursor-pointer">Berapa lama undangan saya jadi?</summary>
      <p class="text-sm text-stone-600 leading-relaxed mt-2">Umumnya 1 hari setelah data dan pembayaran lengkap. Paket Eksklusif dengan motion custom menyesuaikan antrean.</p>
    </details>
    <details class="bg-white rounded-2xl border border-stone-200/80 p-5">
      <summary class="font-bold text-sm cursor-pointer">Apakah bisa revisi setelah jadi?</summary>
      <p class="text-sm text-stone-600 leading-relaxed mt-2">Bisa — perbaikan data (nama, tanggal, foto) dilayani selama masa aktif undangan. Ganti tema bisa dibicarakan saat pemesanan.</p>
    </details>
    <details class="bg-white rounded-2xl border border-stone-200/80 p-5">
      <summary class="font-bold text-sm cursor-pointer">Bagaimana tamu menerima undangan?</summary>
      <p class="text-sm text-stone-600 leading-relaxed mt-2">Tamu menerima link personal lewat WhatsApp — dibuka di browser HP, tanpa install aplikasi. Nama tamu tampil otomatis di undangan.</p>
    </details>
    <details class="bg-white rounded-2xl border border-stone-200/80 p-5">
      <summary class="font-bold text-sm cursor-pointer">Bagaimana cara pembayarannya?</summary>
      <p class="text-sm text-stone-600 leading-relaxed mt-2">Pembayaran lunas di awal via <b class="text-stone-800">Transfer Bank, QRIS, DANA, GoPay, atau ShopeePay</b>. Pilih metodenya di halaman pesanan — detail rekening/nomor tampil di sana, atau dikirim via WhatsApp setelah pesanan dikonfirmasi.</p>
    </details>
    <details class="bg-white rounded-2xl border border-stone-200/80 p-5">
      <summary class="font-bold text-sm cursor-pointer">Apa itu check-in QR?</summary>
      <p class="text-sm text-stone-600 leading-relaxed mt-2">Panitia di pintu masuk cukup scan QR di undangan tamu (atau ketik kode undangan). Kehadiran tercatat otomatis dan tampil di layar monitor acara.</p>
    </details>
  </div>
</section>

<!-- ============ SYARAT & KETENTUAN ============ -->
<section id="syarat" class="bg-[#E9EDE7]">
  <div class="max-w-6xl mx-auto px-4 py-16">
    <p class="text-xs font-extrabold tracking-[0.25em] text-gold">SYARAT &amp; KETENTUAN</p>
    <h2 class="font-serif font-bold text-3xl md:text-4xl text-pinedark mt-2">Syarat &amp; Ketentuan</h2>
    <p class="text-stone-600 mt-3 text-sm">Dengan memesan undangan di undangankamu, kamu menyetujui ketentuan berikut.</p>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 mt-10">
      <article class="sk-card bg-white rounded-[20px] shadow-sm p-6">
        <div class="flex items-start justify-between gap-3">
          <span class="shrink-0 w-10 h-10 rounded-xl bg-[#E4EAE2] text-pinedark font-bold flex items-center justify-center">01</span>
          <span class="text-[13px] font-medium text-stone-700 text-right">Masa Aktif &amp; Kuota</span>
        </div>
        <h3 class="font-serif font-bold text-[22px] text-stone-900 leading-snug mt-5">Ketentuan Umum &amp; Masa Aktif</h3>
        <p class="text-sm text-stone-600 leading-relaxed mt-3">Masa aktif undangan <b class="text-stone-800">6 bulan (Basic)</b> sampai <b class="text-stone-800">1 (satu) tahun (Premium &amp; Eksklusif)</b> sejak tanggal pemesanan/aktivasi, dengan kuota sebar nama tamu <b class="text-stone-800">tanpa batas (unlimited)</b> serta kapasitas foto dan fitur sesuai paket yang dipilih — check-in QR dan Link Generator hanya untuk paket Premium dan Eksklusif.</p>
      </article>
      <article class="sk-card bg-white rounded-[20px] shadow-sm p-6">
        <div class="flex items-start justify-between gap-3">
          <span class="shrink-0 w-10 h-10 rounded-xl bg-[#E4EAE2] text-pinedark font-bold flex items-center justify-center">02</span>
          <span class="text-[13px] font-medium text-stone-700 text-right">Pengerjaan &amp; Revisi</span>
        </div>
        <h3 class="font-serif font-bold text-[22px] text-stone-900 leading-snug mt-5">Pemesanan &amp; Pengerjaan</h3>
        <p class="text-sm text-stone-600 leading-relaxed mt-3">Pesan lewat halaman pesanan (diteruskan ke WhatsApp) dengan menyebutkan tema dan paket pilihan. Undangan dikerjakan dalam estimasi <b class="text-stone-800">1 hari</b> setelah data dan pembayaran lengkap (paket Eksklusif dengan motion custom menyesuaikan antrean), termasuk revisi minor &amp; mayor gratis dalam batas ketentuan selama masa aktif.</p>
      </article>
      <article class="sk-card bg-white rounded-[20px] shadow-sm p-6">
        <div class="flex items-start justify-between gap-3">
          <span class="shrink-0 w-10 h-10 rounded-xl bg-[#E4EAE2] text-pinedark font-bold flex items-center justify-center">03</span>
          <span class="text-[13px] font-medium text-stone-700 text-right">Tanggung Jawab Materi</span>
        </div>
        <h3 class="font-serif font-bold text-[22px] text-stone-900 leading-snug mt-5">Kelengkapan &amp; Akurasi Data</h3>
        <p class="text-sm text-stone-600 leading-relaxed mt-3">Pemesan wajib memastikan kebenaran penulisan nama, gelar, tanggal, jam acara, titik koordinat Google Maps, serta resolusi foto sebelum undangan dipublikasikan atau diproses.</p>
      </article>
      <article class="sk-card bg-white rounded-[20px] shadow-sm p-6">
        <div class="flex items-start justify-between gap-3">
          <span class="shrink-0 w-10 h-10 rounded-xl bg-[#E4EAE2] text-pinedark font-bold flex items-center justify-center">04</span>
          <span class="text-[13px] font-medium text-stone-700 text-right">Perlindungan Data</span>
        </div>
        <h3 class="font-serif font-bold text-[22px] text-stone-900 leading-snug mt-5">Privasi &amp; Keamanan Data</h3>
        <p class="text-sm text-stone-600 leading-relaxed mt-3">Kami menjamin kerahasiaan seluruh data pribadi mempelai, nomor rekening amplop digital, maupun daftar tamu undangan dan <b class="text-stone-800">tidak membagikannya ke pihak ketiga</b>.</p>
      </article>
      <article class="sk-card bg-white rounded-[20px] shadow-sm p-6">
        <div class="flex items-start justify-between gap-3">
          <span class="shrink-0 w-10 h-10 rounded-xl bg-[#E4EAE2] text-pinedark font-bold flex items-center justify-center">05</span>
          <span class="text-[13px] font-medium text-stone-700 text-right">Hak Cipta &amp; Etika</span>
        </div>
        <h3 class="font-serif font-bold text-[22px] text-stone-900 leading-snug mt-5">Kekayaan Intelektual &amp; Larangan</h3>
        <p class="text-sm text-stone-600 leading-relaxed mt-3">Seluruh kode sumber, template, ilustrasi, dan animasi motion dilindungi hak cipta. Dilarang memperbanyak template atau mengunggah konten bermuatan SARA/ilegal. Foto, musik, dan data yang dikirim pemesan tetap menjadi milik pemesan.</p>
      </article>
      <article class="sk-card bg-white rounded-[20px] shadow-sm p-6">
        <div class="flex items-start justify-between gap-3">
          <span class="shrink-0 w-10 h-10 rounded-xl bg-[#E4EAE2] text-pinedark font-bold flex items-center justify-center">06</span>
          <span class="text-[13px] font-medium text-stone-700 text-right">Pembayaran &amp; Refund</span>
        </div>
        <h3 class="font-serif font-bold text-[22px] text-stone-900 leading-snug mt-5">Pembayaran &amp; Pengembalian Dana</h3>
        <p class="text-sm text-stone-600 leading-relaxed mt-3">Pembayaran lunas di awal sebelum aktivasi atau pengerjaan dimulai. Dana yang telah dibayarkan tidak dapat dikembalikan <b class="text-stone-800">(non-refundable)</b> setelah layanan aktif/diproses. Harga dapat berubah — harga yang berlaku adalah yang disepakati saat pemesanan.</p>
      </article>
    </div>
    <p class="text-xs text-stone-500 mt-8 text-center">Ada yang kurang jelas? Tanyakan langsung lewat WhatsApp sebelum memesan.</p>
  </div>
</section>

<!-- ============ FOOTER ============ -->
<footer class="bg-pinedark text-white">
  <div class="max-w-6xl mx-auto px-4 py-12 grid md:grid-cols-3 gap-8">
    <div>
      <p class="font-serif font-bold text-2xl">undangankamu</p>
      <p class="text-sm text-white/70 leading-relaxed mt-2">Undangan digital pernikahan — cantik, praktis, dan lengkap dengan sistem check-in panitia.</p>
    </div>
    <div>
      <p class="font-bold text-sm">Menu</p>
      <ul class="text-sm text-white/70 space-y-2 mt-3">
        <li><a href="#katalog" class="hover:text-white">Katalog Tema</a></li>
        <li><a href="#harga" class="hover:text-white">Harga</a></li>
        <li><a href="#fitur" class="hover:text-white">Fitur</a></li>
        <li><a href="#cara-pesan" class="hover:text-white">Cara Pesan</a></li>
        <li><a href="#syarat" class="hover:text-white">Syarat &amp; Ketentuan</a></li>
      </ul>
    </div>
    <div>
      <p class="font-bold text-sm">Pesan Sekarang</p>
      <p class="text-sm text-white/70 mt-3">Klik tombol di bawah untuk chat WhatsApp — sebutkan tema dan paket pilihanmu.</p>
      <a href="{{ $waText('Halo, saya mau pesan undangan digital.') }}" target="_blank" rel="noopener" class="inline-block mt-4 px-5 py-2.5 rounded-full bg-gold text-pinedark font-bold text-sm hover:brightness-110 transition">Chat WhatsApp</a>
    </div>
  </div>
  <div class="border-t border-white/10 py-4 text-center text-xs text-white/50">&copy; {{ date('Y') }} undangankamu — Undangan Digital Pernikahan.</div>
</footer>

<!-- Tombol WA melayang -->
<a href="{{ $waText('Halo, saya mau pesan undangan digital.') }}" target="_blank" rel="noopener" aria-label="Chat WhatsApp" class="fixed bottom-5 right-5 z-[80] w-14 h-14 rounded-full bg-[#25D366] text-white shadow-xl flex items-center justify-center hover:scale-105 transition">
  <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5.1-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.6-6.1c-.3-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1-.2.3-.6.8-.8 1-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4 0-.5.2-.8l.4-.5c.1-.2.1-.4 0-.5l-.8-1.9c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.2.3-.9.9-.9 2.2s.9 2.5 1.1 2.7c.1.2 1.9 2.9 4.5 4 .6.3 1.1.4 1.5.6.6.2 1.2.2 1.6.1.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2 0-.1-.2-.2-.5-.3Z"/></svg>
</a>

<script>
// Filter katalog + Tampilkan Lainnya (8 awal, +8 per klik, hormat filter)
var SHOW_STEP = 8, showLimit = 8, currentFilter = 'Semua', matchingCount = 0;
function applyCatalog(){
  var matching = [];
  document.querySelectorAll('#themeGrid .theme-card').forEach(function(card){
    var ok = (currentFilter === 'Semua') || (currentFilter === 'Motion' ? card.dataset.video === '1' : card.dataset.cat === currentFilter);
    if (ok) matching.push(card);
  });
  matchingCount = matching.length;
  matching.forEach(function(card, i){ card.style.display = i < showLimit ? '' : 'none'; });
  document.querySelectorAll('#themeGrid .theme-card').forEach(function(card){
    if (matching.indexOf(card) === -1) card.style.display = 'none';
  });
  document.getElementById('noResult').classList.toggle('hidden', matchingCount > 0);
  var btn = document.getElementById('showMoreBtn');
  if (matchingCount <= SHOW_STEP) {
    btn.classList.add('hidden');
  } else {
    btn.classList.remove('hidden');
    btn.textContent = showLimit >= matchingCount
      ? 'Tampilkan Lebih Sedikit'
      : 'Tampilkan Lainnya (' + (matchingCount - showLimit) + ' lagi)';
  }
}
document.querySelectorAll('#filterBar .filter-pill').forEach(function(btn){
  btn.addEventListener('click', function(){
    document.querySelectorAll('#filterBar .filter-pill').forEach(function(b){ b.classList.remove('active'); });
    btn.classList.add('active');
    currentFilter = btn.dataset.filter;
    showLimit = SHOW_STEP;
    applyCatalog();


  });
});
document.getElementById('showMoreBtn').addEventListener('click', function(){
  showLimit = showLimit >= matchingCount ? SHOW_STEP : showLimit + SHOW_STEP;
  applyCatalog();
});
applyCatalog();
</script>
</body>
</html>
