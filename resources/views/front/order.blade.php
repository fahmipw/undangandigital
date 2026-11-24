@php
// Halaman pesanan "undangankamu" — form -> WhatsApp.
// $themes, $harga, $hargaNum, $hargaCoret, $selTheme, $paket, $waNumber
// datang dari FrontController::order(). Pesanan TIDAK disimpan di database.
$methods = [
    ['key' => 'bank', 'label' => 'Transfer Bank'],
    ['key' => 'qris', 'label' => 'QRIS'],
    ['key' => 'dana', 'label' => 'DANA'],
    ['key' => 'gopay', 'label' => 'GoPay'],
    ['key' => 'shopeepay', 'label' => 'ShopeePay'],
];
$paketList = ['basic' => 'Basic', 'premium' => 'Premium', 'eksklusif' => 'Eksklusif'];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesan Undangan — undangankamu</title>
<meta name="description" content="Pesan undangan digital pernikahan: pilih tema, paket, dan metode pembayaran (Transfer Bank, QRIS, DANA, GoPay, ShopeePay), lalu kirim via WhatsApp.">
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
  .ord-field { width: 100%; border: 1px solid #E7E5E4; border-radius: 14px; padding: .75rem 1rem; font-size: .9rem; background: #fff; }
  .ord-field:focus { border-color: #1E4D33; outline: none; }
  .ord-label { display: block; font-size: .72rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; color: #57534E; margin-bottom: .4rem; }
  .pick-card:has(input:checked) { border-color: #1E4D33; background: #F5EFE3; }
  .pay-card:has(input:checked) { border-color: #1E4D33; background: #F5EFE3; }
</style>
</head>
<body class="bg-ivory text-stone-800 antialiased">

<!-- ============ NAV ============ -->
<header class="sticky top-0 z-50 bg-ivory/90 backdrop-blur border-b border-stone-200/70">
  <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
    <a href="/" class="flex items-center gap-2">
      <span class="w-9 h-9 rounded-full bg-pine text-goldsoft flex items-center justify-center font-serif font-bold text-lg">U</span>
      <span class="font-serif font-bold text-xl text-pinedark">undangankamu</span>
    </a>
    <nav class="hidden md:flex items-center gap-7 text-sm font-semibold text-stone-600">
      <a href="/#katalog" class="hover:text-pine">Katalog</a>
      <a href="/#harga" class="hover:text-pine">Harga</a>
      <a href="/#faq" class="hover:text-pine">FAQ</a>
      <a href="/#syarat" class="hover:text-pine">S&amp;K</a>
    </nav>
    <a href="/" class="text-sm font-bold text-pine hover:text-pinedark">&larr; Kembali ke Beranda</a>
  </div>
</header>

<main class="max-w-3xl mx-auto px-4 py-10 md:py-14">
  <h1 class="font-serif font-bold text-3xl md:text-4xl text-pinedark">Pesan Undangan</h1>
  <p class="text-stone-600 mt-3 text-sm leading-relaxed">Isi form di bawah ini, lalu pesananmu diteruskan ke WhatsApp kami. Pembayaran lunas di awal — detail rekening/nomor dikirim via WhatsApp setelah pesanan dikonfirmasi.</p>

  <form id="orderForm" class="mt-8 space-y-8" novalidate>
    <!-- 1. Tema -->
    <section class="bg-white rounded-3xl border border-stone-200/80 p-6">
      <h2 class="font-bold text-pinedark"><span class="inline-flex w-7 h-7 rounded-full bg-pine text-white text-sm items-center justify-center mr-2">1</span>Pilih Tema</h2>
      <div class="mt-4">
        <label class="ord-label" for="ordTema">Tema undangan</label>
        <select id="ordTema" class="ord-field" required>
          @foreach ($themes as $t)
            <option value="{{ $t['slug'] }}" data-name="{{ $t['name'] }}" data-cat="{{ $t['cat'] }}" data-video="{{ $t['video'] ? '1' : '0' }}" data-img="/images/landing/themes/{{ $t['slug'] }}.jpg" @if ($t['slug'] === $selTheme['slug']) selected @endif>{{ $t['name'] }} — {{ $t['cat'] }}{{ $t['video'] ? ' (Motion)' : '' }}</option>
          @endforeach
        </select>
      </div>
      <div class="flex gap-4 items-center mt-4">
        <div class="relative shrink-0 w-20 h-20 rounded-2xl overflow-hidden bg-gradient-to-br from-pine to-[#3E2618]">
          <span class="absolute inset-0 flex items-center justify-center text-center px-1 font-serif font-bold text-[11px] leading-tight text-goldsoft" id="ordPrevName">{{ $selTheme['name'] }}</span>
          <img id="ordPrevImg" src="/images/landing/themes/{{ $selTheme['slug'] }}.jpg" alt="Preview {{ $selTheme['name'] }}" class="absolute inset-0 w-full h-full object-cover object-top" onerror="this.style.display='none'">
        </div>
        <div class="min-w-0">
          <p class="font-bold text-sm" id="ordPrevTitle">{{ $selTheme['name'] }}</p>
          <p class="text-xs text-stone-500" id="ordPrevCat">Tema {{ $selTheme['cat'] }}{{ $selTheme['video'] ? ' · Motion' : '' }}</p>
          <a id="ordPrevDemo" href="/hawa-adam?tema={{ $selTheme['slug'] }}&to=tamu-demo" target="_blank" rel="noopener" class="inline-block mt-1.5 text-xs font-bold text-pine underline underline-offset-2">Lihat demo tema ini &rarr;</a>
        </div>
      </div>
    </section>

    <!-- 2. Paket -->
    <section class="bg-white rounded-3xl border border-stone-200/80 p-6">
      <h2 class="font-bold text-pinedark"><span class="inline-flex w-7 h-7 rounded-full bg-pine text-white text-sm items-center justify-center mr-2">2</span>Pilih Paket</h2>
      <div class="grid sm:grid-cols-3 gap-3 mt-4">
        @foreach ($paketList as $key => $label)
          <label class="pick-card block border border-stone-200 rounded-2xl p-4 cursor-pointer transition">
            <input type="radio" name="paket" value="{{ $label }}" data-price="{{ $hargaNum[$key] }}" data-price-text="{{ $harga[$key] }}" class="accent-[#1E4D33]" @if ($paket === $label) checked @endif required>
            <span class="block font-bold text-sm mt-1">{{ $label }}</span>
            <span class="block font-extrabold text-pinedark mt-0.5">{{ $harga[$key] }}</span>
            <span class="block text-xs text-stone-400 line-through">{{ $hargaCoret[$key] }}</span>
            <span class="block text-[11px] text-stone-500 mt-1.5 leading-relaxed">
              @if ($key === 'basic') Semua 35 tema + fitur dasar.
              @elseif ($key === 'premium') Semua tema + check-in QR &amp; Link Generator.
              @else Motion opening custom + semua fitur.
              @endif
            </span>
          </label>
        @endforeach
      </div>
    </section>

    <!-- 3. Data -->
    <section class="bg-white rounded-3xl border border-stone-200/80 p-6">
      <h2 class="font-bold text-pinedark"><span class="inline-flex w-7 h-7 rounded-full bg-pine text-white text-sm items-center justify-center mr-2">3</span>Data Mempelai &amp; Acara</h2>
      <div class="grid sm:grid-cols-2 gap-4 mt-4">
        <div>
          <label class="ord-label" for="ordPria">Nama mempelai pria</label>
          <input id="ordPria" type="text" class="ord-field" placeholder="cth: Adam" required>
        </div>
        <div>
          <label class="ord-label" for="ordWanita">Nama mempelai wanita</label>
          <input id="ordWanita" type="text" class="ord-field" placeholder="cth: Hawa" required>
        </div>
        <div>
          <label class="ord-label" for="ordTanggal">Tanggal acara</label>
          <input id="ordTanggal" type="date" class="ord-field" required>
        </div>
        <div>
          <label class="ord-label" for="ordWa">Nomor WhatsApp pemesan</label>
          <input id="ordWa" type="tel" class="ord-field" placeholder="cth: 081234567890" required>
        </div>
        <div class="sm:col-span-2">
          <label class="ord-label" for="ordCatatan">Catatan (opsional)</label>
          <textarea id="ordCatatan" rows="2" class="ord-field" placeholder="cth: warna tema mau disesuaikan, ada permintaan khusus…"></textarea>
        </div>
      </div>
    </section>

    <!-- 4. Pembayaran -->
    <section class="bg-white rounded-3xl border border-stone-200/80 p-6">
      <h2 class="font-bold text-pinedark"><span class="inline-flex w-7 h-7 rounded-full bg-pine text-white text-sm items-center justify-center mr-2">4</span>Metode Pembayaran</h2>
      <p class="text-xs text-stone-500 mt-2">Pembayaran lunas di awal. Pilih salah satu — detail rekening/nomor dikirim via WhatsApp setelah pesanan dikonfirmasi.</p>
      <div class="space-y-3 mt-4">
        @foreach ($methods as $m)
          <label class="pay-card flex items-center gap-3 border border-stone-200 rounded-2xl p-4 cursor-pointer transition">
            <input type="radio" name="pay" value="{{ $m['label'] }}" class="accent-[#1E4D33]" required>
            <span class="font-bold text-sm">{{ $m['label'] }}</span>
          </label>
        @endforeach
      </div>
    </section>

    <!-- Ringkasan -->
    <section class="bg-cream rounded-3xl border border-stone-200/80 p-6">
      <h2 class="font-bold text-pinedark">Ringkasan Pesanan</h2>
      <dl class="text-sm mt-3 space-y-1.5">
        <div class="flex justify-between gap-4"><dt class="text-stone-500">Tema</dt><dd class="font-bold text-right" id="sumTema">{{ $selTheme['name'] }}</dd></div>
        <div class="flex justify-between gap-4"><dt class="text-stone-500">Paket</dt><dd class="font-bold text-right" id="sumPaket">{{ $paket }}</dd></div>
        <div class="flex justify-between gap-4"><dt class="text-stone-500">Metode pembayaran</dt><dd class="font-bold text-right" id="sumPay">—</dd></div>
        <div class="flex justify-between gap-4 border-t border-stone-300/70 pt-2 mt-2"><dt class="text-stone-500">Total bayar</dt><dd class="font-extrabold text-pinedark text-base" id="sumTotal">{{ $harga[strtolower($paket)] }}</dd></div>
      </dl>
      <button id="ordSubmit" type="submit" class="w-full mt-5 px-6 py-3.5 rounded-full bg-pine text-white font-bold text-sm hover:bg-pinedark transition">Kirim Pesanan via WhatsApp</button>
      <p class="text-[11px] text-stone-500 text-center leading-relaxed mt-3">Pesanan diteruskan ke WhatsApp — tanpa akun, tanpa password. Dengan mengirim pesanan, kamu menyetujui <a href="/#syarat" class="underline underline-offset-2 font-semibold text-pine">Syarat &amp; Ketentuan</a>.</p>
    </section>
  </form>
</main>

<footer class="border-t border-stone-200/70 py-8">
  <p class="text-center text-xs text-stone-400">undangankamu — Undangan digital pernikahan.</p>
</footer>

<script>
const WA_NUMBER = '{{ $waNumber }}';
const form = document.getElementById('orderForm');
const temaSel = document.getElementById('ordTema');
const prevImg = document.getElementById('ordPrevImg');
const prevName = document.getElementById('ordPrevName');
const prevTitle = document.getElementById('ordPrevTitle');
const prevCat = document.getElementById('ordPrevCat');
const prevDemo = document.getElementById('ordPrevDemo');

function fmtRp(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); }

function updateSummary() {
  const opt = temaSel.options[temaSel.selectedIndex];
  const name = opt.dataset.name, cat = opt.dataset.cat, video = opt.dataset.video === '1';
  prevImg.src = opt.dataset.img; prevImg.style.display = '';
  prevName.textContent = name; prevTitle.textContent = name;
  prevCat.textContent = 'Tema ' + cat + (video ? ' · Motion' : '');
  prevDemo.href = '/hawa-adam?tema=' + encodeURIComponent(opt.value) + '&to=tamu-demo';
  document.getElementById('sumTema').textContent = name;
  const pr = form.querySelector('input[name="paket"]:checked');
  if (pr) {
    document.getElementById('sumPaket').textContent = pr.value;
    document.getElementById('sumTotal').textContent = pr.dataset.priceText;
  }
  const pay = form.querySelector('input[name="pay"]:checked');
  document.getElementById('sumPay').textContent = pay ? pay.value : '—';
}
temaSel.addEventListener('change', updateSummary);
form.addEventListener('change', updateSummary);

form.addEventListener('submit', function (e) {
  e.preventDefault();
  if (!form.reportValidity()) return;
  const opt = temaSel.options[temaSel.selectedIndex];
  const pr = form.querySelector('input[name="paket"]:checked');
  const pay = form.querySelector('input[name="pay"]:checked');
  const pria = document.getElementById('ordPria').value.trim();
  const wanita = document.getElementById('ordWanita').value.trim();
  const tglRaw = document.getElementById('ordTanggal').value;
  const tgl = tglRaw ? new Date(tglRaw + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
  const wa = document.getElementById('ordWa').value.trim();
  const catatan = document.getElementById('ordCatatan').value.trim() || '-';
  const msg = 'Halo, saya mau pesan undangan digital.\n\n'
    + 'Tema: ' + opt.dataset.name + ' (' + opt.dataset.cat + ')\n'
    + 'Paket: ' + pr.value + ' — ' + pr.dataset.priceText + '\n'
    + 'Mempelai: ' + pria + ' & ' + wanita + '\n'
    + 'Tanggal acara: ' + tgl + '\n'
    + 'No. WA pemesan: ' + wa + '\n'
    + 'Metode pembayaran: ' + pay.value + '\n'
    + 'Catatan: ' + catatan;
  window.open('https://wa.me/' + WA_NUMBER + '?text=' + encodeURIComponent(msg), '_blank');
});
updateSummary();
</script>
</body>
</html>
