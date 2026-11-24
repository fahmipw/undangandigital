@php
// Foto hero: og_preview_photo -> bride_photo -> groom_photo (fallback sama
// seperti OG tags). Nilai relatif -> asset(), absolut (http / awal '/') apa adanya.
// Bila tidak ada foto, hero memakai gradasi gelap saja (tanpa hotlink eksternal).
$__genSettings = [];
foreach ($invitation->settings as $__s) { $__genSettings[$__s->key_name] = $__s->key_value; }
$__heroRaw = !empty($__genSettings['og_preview_photo']) ? $__genSettings['og_preview_photo'] : (!empty($__genSettings['bride_photo']) ? $__genSettings['bride_photo'] : (!empty($__genSettings['groom_photo']) ? $__genSettings['groom_photo'] : ''));
$heroPhoto = '';
if ($__heroRaw !== '') {
    $heroPhoto = (str_starts_with($__heroRaw, 'http') || str_starts_with($__heroRaw, '/')) ? $__heroRaw : asset(ltrim($__heroRaw, '/'));
}
$brideInitial = mb_strtoupper(mb_substr($bride, 0, 1));
$groomInitial = mb_strtoupper(mb_substr($groom, 0, 1));
@endphp
@php
// Palet tema undangan — disalin dari checkin.blade.php (sumber data sama);
// bila menambah tema, ubah di kedua file. Kunci: $theme dari
// FrontController::generator; tak dikenal -> 'default'.
$genPalettes = [
    'default' => ['accent' => '#775a19', 'accentDark' => '#5d4201', 'bg' => '#f5f3ef', 'ink' => '#1b1c1a', 'inkSoft' => '#4b5563', 'muted' => '#6b7280', 'card' => '#ffffff', 'soft' => '#f9fafb', 'border' => '#e5e7eb', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'arabian-nights' => ['accent' => '#d4af37', 'accentDark' => '#997e28', 'bg' => '#120a20', 'ink' => '#f3eefc', 'inkSoft' => '#b0aaba', 'muted' => '#777183', 'card' => '#1a0f2e', 'soft' => '#160c27', 'border' => '#2d253a', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'bawah-laut' => ['accent' => '#16617f', 'accentDark' => '#10465b', 'bg' => '#dff2f7', 'ink' => '#0e2f3d', 'inkSoft' => '#4d6a75', 'muted' => '#819aa3', 'card' => '#f2fbfe', 'soft' => '#e8f6fa', 'border' => '#c6dbe1', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'betawi' => ['accent' => '#1E4D33', 'accentDark' => '#163725', 'bg' => '#E4EDE1', 'ink' => '#1F2A24', 'inkSoft' => '#5a645d', 'muted' => '#8b958c', 'card' => '#FAF9F4', 'soft' => '#eff3ea', 'border' => '#ccd6ca', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'empat-musim' => ['accent' => '#3e7d44', 'accentDark' => '#2d5a31', 'bg' => '#f0f4ea', 'ink' => '#1e2b1e', 'inkSoft' => '#5d675b', 'muted' => '#929a8e', 'card' => '#fffdf7', 'soft' => '#f8f8f0', 'border' => '#d7dcd2', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'galaksi-cinta' => ['accent' => '#b18cff', 'accentDark' => '#7f65b8', 'bg' => '#0f0f28', 'ink' => '#efecff', 'inkSoft' => '#acaabe', 'muted' => '#747289', 'card' => '#16163a', 'soft' => '#121231', 'border' => '#2a2a42', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'gunung-berkabut' => ['accent' => '#5b7a8a', 'accentDark' => '#425863', 'bg' => '#eef2f4', 'ink' => '#1f2a30', 'inkSoft' => '#5d666b', 'muted' => '#91989c', 'card' => '#ffffff', 'soft' => '#f6f8fa', 'border' => '#d5dadc', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'islamic-elegance' => ['accent' => '#c9a24b', 'accentDark' => '#917536', 'bg' => '#072a25', 'ink' => '#eef7f2', 'inkSoft' => '#a9b9b4', 'muted' => '#6f8681', 'card' => '#0a3a32', 'soft' => '#08322c', 'border' => '#23433e', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'jawa-arunika' => ['accent' => '#7c4a18', 'accentDark' => '#593511', 'bg' => '#f3e7d6', 'ink' => '#2b2118', 'inkSoft' => '#675c51', 'muted' => '#998e80', 'card' => '#f7efe6', 'soft' => '#f5ebde', 'border' => '#dbcfbf', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'jawa-biru' => ['accent' => '#e6c887', 'accentDark' => '#a69061', 'bg' => '#071224', 'ink' => '#eef2f7', 'inkSoft' => '#a9afb8', 'muted' => '#6f7783', 'card' => '#0a1b30', 'soft' => '#08162a', 'border' => '#232d3d', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'jawa-cinematic' => ['accent' => '#D4AF37', 'accentDark' => '#997e28', 'bg' => '#071223', 'ink' => '#f2ead8', 'inkSoft' => '#aba9a2', 'muted' => '#717374', 'card' => '#0A192F', 'soft' => '#081629', 'border' => '#232c39', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'jawa-gerbang' => ['accent' => '#77603a', 'accentDark' => '#56452a', 'bg' => '#f5f0e1', 'ink' => '#2b2118', 'inkSoft' => '#685f54', 'muted' => '#9a9387', 'card' => '#fffdf7', 'soft' => '#faf6ec', 'border' => '#ddd7c9', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'jawa-gunungan' => ['accent' => '#5A3620', 'accentDark' => '#412717', 'bg' => '#EAD9C8', 'ink' => '#3E2618', 'inkSoft' => '#725c4d', 'muted' => '#9d8879', 'card' => '#F5EAD9', 'soft' => '#f0e2d0', 'border' => '#d5c4b3', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'jawa-klasik' => ['accent' => '#d4af37', 'accentDark' => '#997e28', 'bg' => '#1e150c', 'ink' => '#f5ead9', 'inkSoft' => '#b4aa9b', 'muted' => '#7f7568', 'card' => '#2a1c11', 'soft' => '#24180e', 'border' => '#382f25', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'jawa-pendopo' => ['accent' => '#77603a', 'accentDark' => '#56452a', 'bg' => '#f5f0e1', 'ink' => '#2b2118', 'inkSoft' => '#685f54', 'muted' => '#9a9387', 'card' => '#fffdf7', 'soft' => '#faf6ec', 'border' => '#ddd7c9', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'jawa-premium' => ['accent' => '#d4af37', 'accentDark' => '#997e28', 'bg' => '#2a140a', 'ink' => '#f5ead9', 'inkSoft' => '#b8aa9b', 'muted' => '#857467', 'card' => '#3a1c0e', 'soft' => '#32180c', 'border' => '#422e23', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'jawa-puspa' => ['accent' => '#77603a', 'accentDark' => '#56452a', 'bg' => '#f5f0e1', 'ink' => '#2b2118', 'inkSoft' => '#685f54', 'muted' => '#9a9387', 'card' => '#fffdf7', 'soft' => '#faf6ec', 'border' => '#ddd7c9', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'midnight-galaxy' => ['accent' => '#a78bfa', 'accentDark' => '#7864b4', 'bg' => '#0d0920', 'ink' => '#efecff', 'inkSoft' => '#aba8bc', 'muted' => '#736f84', 'card' => '#140e2c', 'soft' => '#100c26', 'border' => '#28243b', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'minang-gadang' => ['accent' => '#d4af37', 'accentDark' => '#997e28', 'bg' => '#0c3a22', 'ink' => '#f2f7ee', 'inkSoft' => '#adbeb1', 'muted' => '#748f7e', 'card' => '#12502f', 'soft' => '#0f4528', 'border' => '#28513a', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'minecraft' => ['accent' => '#7bff5e', 'accentDark' => '#59b844', 'bg' => '#0b0f1a', 'ink' => '#e8f5e9', 'inkSoft' => '#a6b0ab', 'muted' => '#6e7677', 'card' => '#101010', 'soft' => '#0e1015', 'border' => '#262b33', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'minecraft-adventure' => ['accent' => '#fcee5e', 'accentDark' => '#b5ab44', 'bg' => '#0d1420', 'ink' => '#f5f5f5', 'inkSoft' => '#afb2b5', 'muted' => '#757980', 'card' => '#1a2333', 'soft' => '#141c2a', 'border' => '#292f3a', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'neon-metropolis' => ['accent' => '#00e5ff', 'accentDark' => '#00a5b8', 'bg' => '#0a0a1e', 'ink' => '#e8fbff', 'inkSoft' => '#a5b3bc', 'muted' => '#6e7683', 'card' => '#12122b', 'soft' => '#0e0e24', 'border' => '#252739', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'origami-dreams' => ['accent' => '#d15f7c', 'accentDark' => '#964459', 'bg' => '#fbeef2', 'ink' => '#2b1e22', 'inkSoft' => '#695c60', 'muted' => '#9d9094', 'card' => '#fdfaf2', 'soft' => '#fcf4f2', 'border' => '#e2d5d9', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#1b1c1a'],
    'pixel-quest' => ['accent' => '#ffd93b', 'accentDark' => '#b89c2a', 'bg' => '#101010', 'ink' => '#f5f5f5', 'inkSoft' => '#b0b0b0', 'muted' => '#777777', 'card' => '#1e1e1e', 'soft' => '#171717', 'border' => '#2b2b2b', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'rimba-watercolor' => ['accent' => '#2f4a3a', 'accentDark' => '#22352a', 'bg' => '#eef0e6', 'ink' => '#1e2a22', 'inkSoft' => '#5c655d', 'muted' => '#90978e', 'card' => '#fbf9f2', 'soft' => '#f4f4ec', 'border' => '#d5d8ce', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'royal-emerald' => ['accent' => '#c9a24b', 'accentDark' => '#917536', 'bg' => '#082a20', 'ink' => '#eef7f2', 'inkSoft' => '#a9b9b3', 'muted' => '#70867e', 'card' => '#0b3a2c', 'soft' => '#0a3226', 'border' => '#244339', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'ruang-jawa' => ['accent' => '#8e1b12', 'accentDark' => '#66130d', 'bg' => '#efe6d0', 'ink' => '#2b2118', 'inkSoft' => '#665c4f', 'muted' => '#978d7d', 'card' => '#fdf6dd', 'soft' => '#f6eed6', 'border' => '#d7ceba', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'rustic-boho' => ['accent' => '#b4653a', 'accentDark' => '#82492a', 'bg' => '#f5ecdc', 'ink' => '#2b2118', 'inkSoft' => '#685e53', 'muted' => '#9a9184', 'card' => '#efe4d0', 'soft' => '#f2e8d6', 'border' => '#ddd4c4', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'sakura-dream' => ['accent' => '#bc7182', 'accentDark' => '#87515e', 'bg' => '#fdf0f3', 'ink' => '#2b1e22', 'inkSoft' => '#6a5d61', 'muted' => '#9e9295', 'card' => '#ffffff', 'soft' => '#fef8f9', 'border' => '#e4d7da', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#1b1c1a'],
    'senja-pesisir' => ['accent' => '#d16843', 'accentDark' => '#964b30', 'bg' => '#fdf0e8', 'ink' => '#2b2118', 'inkSoft' => '#6a5f56', 'muted' => '#9e938a', 'card' => '#fffdf7', 'soft' => '#fef6f0', 'border' => '#e4d7cf', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#1b1c1a'],
    'sogan-parallax' => ['accent' => '#d4af37', 'accentDark' => '#997e28', 'bg' => '#1e1008', 'ink' => '#f5ead9', 'inkSoft' => '#b4a99a', 'muted' => '#7f7266', 'card' => '#2b180d', 'soft' => '#24140a', 'border' => '#382a21', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'stardew-adventure' => ['accent' => '#4a9a32', 'accentDark' => '#356f24', 'bg' => '#dfe8d2', 'ink' => '#1e2a1e', 'inkSoft' => '#586354', 'muted' => '#889281', 'card' => '#fffdf4', 'soft' => '#eff2e3', 'border' => '#c8d1bc', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#1b1c1a'],
    'stardew-valley' => ['accent' => '#8b5a2b', 'accentDark' => '#64411f', 'bg' => '#f5ecd7', 'ink' => '#2b2118', 'inkSoft' => '#685e51', 'muted' => '#9a9181', 'card' => '#fffdf4', 'soft' => '#faf4e6', 'border' => '#ddd4c0', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'taman-janur' => ['accent' => '#3e7d44', 'accentDark' => '#2d5a31', 'bg' => '#f8f6e8', 'ink' => '#1e2b1e', 'inkSoft' => '#5f685b', 'muted' => '#969b8d', 'card' => '#fffdf7', 'soft' => '#fcfaf0', 'border' => '#deded0', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'taman-surga' => ['accent' => '#7a9b76', 'accentDark' => '#587055', 'bg' => '#f0f4ec', 'ink' => '#1e2b1e', 'inkSoft' => '#5d675c', 'muted' => '#929a8f', 'card' => '#fffdf8', 'soft' => '#f8f8f2', 'border' => '#d7dcd3', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#1b1c1a'],
    'winter-aurora' => ['accent' => '#3a6ea5', 'accentDark' => '#2a4f77', 'bg' => '#eef4f9', 'ink' => '#1e2a33', 'inkSoft' => '#5c676e', 'muted' => '#9099a0', 'card' => '#ffffff', 'soft' => '#f6fafc', 'border' => '#d5dce1', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    ];
$gen = $genPalettes[$theme ?? 'default'] ?? $genPalettes['default'];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Link Generator Tamu — {{ $bride }} &amp; {{ $groom }}</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Fonts: Playfair Display, Cormorant Garamond & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
    rel="stylesheet"
  />

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <!-- SweetAlert2 (notifikasi sukses edit/hapus tamu) -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            display: ['"Playfair Display"', 'Georgia', 'serif'],
            serif: ['"Cormorant Garamond"', 'Georgia', 'serif'],
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
          },
          colors: {
            // Warna mengikuti palet tema undangan ($genPalettes) via CSS var;
            // dark mode menimpa var di html.dark (lihat <style> di bawah).
            ivory: 'var(--gen-bg)',
            espresso: 'var(--gen-ink)',
            gold: {
              DEFAULT: 'var(--gen-accent)',
              light: 'color-mix(in srgb, var(--gen-accent) 45%, white)',
              dark: 'var(--gen-accent-dark)',
            },
            onaccent: 'var(--gen-on-accent)',
            card: 'var(--gen-card)',
            charcoal: {
              DEFAULT: '#161412',
              card: '#211E1B',
              border: '#332E29',
            },
          },
        },
      },
    };
  </script>

  <style>
    /* Palet tema undangan: terang = palet tema, gelap = netral gelap + aksen tema. */
    :root {
      --gen-accent: {{ $gen['accent'] }};
      --gen-accent-dark: {{ $gen['accentDark'] }};
      --gen-bg: {{ $gen['bg'] }};
      --gen-ink: {{ $gen['ink'] }};
      --gen-ink-soft: {{ $gen['inkSoft'] }};
      --gen-muted: {{ $gen['muted'] }};
      --gen-card: {{ $gen['card'] }};
      --gen-soft: {{ $gen['soft'] }};
      --gen-border: {{ $gen['border'] }};
      --gen-hover: {{ $gen['hover'] }};
      --gen-on-accent: {{ $gen['onAccent'] }};
    }
    html.dark {
      --gen-bg: #0E0D0B;
      --gen-card: #211E1B;
      --gen-ink: #F3EFE6;
      --gen-ink-soft: #C9C2B4;
      --gen-muted: #8B8578;
      --gen-soft: #161412;
      --gen-border: #332E29;
      --gen-hover: rgba(255, 255, 255, .08);
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      -webkit-tap-highlight-color: transparent;
    }

    @keyframes popIn {
      0% {
        opacity: 0;
        transform: scale(0.94) translateY(8px);
      }
      100% {
        opacity: 1;
        transform: scale(1) translateY(0);
      }
    }

    .animate-pop-in {
      animation: popIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* Custom Scrollbar halus */
    ::-webkit-scrollbar {
      width: 5px;
      height: 5px;
    }
    ::-webkit-scrollbar-track {
      background: transparent;
    }
    ::-webkit-scrollbar-thumb {
      background: var(--gen-accent);
      border-radius: 9999px;
    }

    /* Daftar tamu: scroll di dalam wadah (Firefox + cegah rantai scroll ke halaman) */
    .guest-scroll {
      scrollbar-width: thin;
      scrollbar-color: var(--gen-accent) transparent;
      overscroll-behavior: contain;
    }
  </style>
</head>

<body class="bg-[var(--gen-bg)] text-espresso dark:text-[#F3EFE6] min-h-screen transition-colors duration-300">
  <!-- Main Mobile-First App Shell -->
  <div class="max-w-md md:max-w-3xl lg:max-w-6xl mx-auto min-h-screen bg-ivory dark:bg-charcoal shadow-2xl relative flex flex-col transition-colors duration-300 pb-12">

    <!-- ==================== HERO BANNER HEADER ==================== -->
    <header class="relative overflow-hidden rounded-b-[2rem] shadow-lg">
      <!-- Background Foto Pengantin (dari Foto Preview / foto mempelai; tanpa foto = gradasi gelap) -->
      <div class="absolute inset-0 bg-[#2C2115]">
        @if($heroPhoto)
        <img
          src="{{ $heroPhoto }}"
          alt="{{ $bride }} &amp; {{ $groom }}"
          class="w-full h-full object-cover object-center opacity-50 scale-105"
        />
        @endif
        <div
          class="absolute inset-0"
          style="background: linear-gradient(180deg, rgba(28, 20, 13, 0.72) 0%, rgba(45, 33, 21, 0.86) 55%, rgba(28, 20, 13, 0.96) 100%);"
        ></div>
      </div>

      <!-- Ornamen Sudut Floral Tipis -->
      <svg class="absolute top-2 left-2 w-16 h-16 text-gold-light pointer-events-none" viewBox="0 0 100 100" fill="none">
        <path d="M5 5 C35 5, 55 25, 55 55 C25 55, 5 35, 5 5 Z" stroke="currentColor" stroke-width="1.2" />
        <circle cx="22" cy="22" r="2.5" fill="currentColor" />
      </svg>
      <svg class="absolute top-2 right-2 w-16 h-16 text-gold-light pointer-events-none rotate-90" viewBox="0 0 100 100" fill="none">
        <path d="M5 5 C35 5, 55 25, 55 55 C25 55, 5 35, 5 5 Z" stroke="currentColor" stroke-width="1.2" />
        <circle cx="22" cy="22" r="2.5" fill="currentColor" />
      </svg>

      <div class="relative z-10 px-5 pt-5 pb-8 text-center text-white">
        <!-- Top Bar -->
        <div class="flex items-center justify-between gap-2 mb-3">
          <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-md border border-white/15 rounded-full px-3 py-1 text-[11px] text-gold-light font-medium">
            <i data-lucide="send" class="w-3 h-3"></i>
            <span>E-Invitation Desk</span>
          </span>

          <button
            id="themeToggleBtn"
            onclick="toggleDarkMode()"
            class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/15 rounded-full px-3 py-1.5 text-xs text-gold-light transition active:scale-95"
          >
            <i id="themeIcon" data-lucide="moon" class="w-3.5 h-3.5"></i>
            <span id="themeLabel" class="text-[11px] font-medium">Mode Malam</span>
          </button>
        </div>

        <!-- Monogram Crest -->
        <div class="w-11 h-11 mx-auto mb-2 rounded-full border border-gold bg-black/25 backdrop-blur-sm flex items-center justify-center shadow-[0_0_20px_color-mix(in_srgb,var(--gen-accent)_28%,transparent)] relative">
          <div class="absolute inset-1 rounded-full border border-gold"></div>
          <span class="font-display text-xs font-semibold tracking-widest text-gold-light">
            {{ $brideInitial }}<span class="font-serif italic text-gold-light mx-0.5">&amp;</span>{{ $groomInitial }}
          </span>
        </div>

        <p class="text-[10px] tracking-[0.26em] uppercase text-gold-light font-semibold mb-0.5">
          Link Generator Tamu
        </p>

        <h1 class="font-serif text-4xl font-bold tracking-wide text-white leading-tight drop-shadow">
          {{ $bride }} <span class="text-gold-light italic font-normal">&amp;</span> {{ $groom }}
        </h1>

        <p class="text-[11px] text-white/75 mt-1.5 max-w-xs mx-auto leading-relaxed">
          Buat tautan undangan personal &amp; kirim pesan WhatsApp otomatis dalam sekali klik.
        </p>
      </div>
    </header>

    <!-- ==================== MAIN CONTENT AREA ==================== -->
    <main class="px-4 md:px-6 -mt-5 relative z-20 space-y-4 flex-1 lg:grid lg:grid-cols-5 lg:gap-5 lg:space-y-0">

      <!-- RINGKASAN STATUS PENGIRIMAN (FLOATING GLASS) -->
      <section class="grid grid-cols-3 gap-2.5 lg:col-span-5">
        <div class="bg-card dark:bg-charcoal-card/95 backdrop-blur-md rounded-2xl p-3 text-center shadow-md border border-[var(--gen-border)] dark:border-charcoal-border border-t-[3px] border-t-gold">
          <div id="statTotalGuests" class="text-xl font-bold text-espresso dark:text-white leading-none">0</div>
          <div class="text-[10px] font-semibold tracking-wider uppercase text-espresso/60 dark:text-white/60 mt-1.5">
            Total Link
          </div>
        </div>

        <div class="bg-card dark:bg-charcoal-card/95 backdrop-blur-md rounded-2xl p-3 text-center shadow-md border border-[var(--gen-border)] dark:border-charcoal-border border-t-[3px] border-t-emerald-600">
          <div id="statSentGuests" class="text-xl font-bold text-emerald-700 dark:text-emerald-400 leading-none">0</div>
          <div class="text-[10px] font-semibold tracking-wider uppercase text-espresso/60 dark:text-white/60 mt-1.5">
            Terkirim
          </div>
        </div>

        <div class="bg-card dark:bg-charcoal-card/95 backdrop-blur-md rounded-2xl p-3 text-center shadow-md border border-[var(--gen-border)] dark:border-charcoal-border border-t-[3px] border-t-amber-600">
          <div id="statUnsentGuests" class="text-xl font-bold text-amber-700 dark:text-amber-400 leading-none">0</div>
          <div class="text-[10px] font-semibold tracking-wider uppercase text-espresso/60 dark:text-white/60 mt-1.5">
            Belum Kirim
          </div>
        </div>
      </section>

      <!-- ==================== CARD 1: INPUT TAMU (SATUAN / MASSAL) ==================== -->
      <section class="bg-card dark:bg-charcoal-card rounded-2xl p-4 shadow-sm border border-[var(--gen-border)] dark:border-charcoal-border lg:col-start-1 lg:row-start-2 lg:col-span-2">
        <!-- Segmented Switch: Satuan vs Massal -->
        <div class="grid grid-cols-2 gap-1.5 bg-[var(--gen-soft)] dark:bg-charcoal p-1 rounded-xl mb-4">
          <button
            id="modeBtnSingle"
            onclick="switchInputMode('single')"
            class="py-2 px-3 rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 bg-gold dark:bg-gold text-onaccent shadow-sm transition"
          >
            <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
            <span>Tambah Satuan</span>
          </button>

          <button
            id="modeBtnBulk"
            onclick="switchInputMode('bulk')"
            class="py-2 px-3 rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 text-espresso/75 dark:text-white/75 hover:bg-white/40 dark:hover:bg-white/5 transition"
          >
            <i data-lucide="users" class="w-3.5 h-3.5"></i>
            <span>Generate Massal</span>
          </button>
        </div>

        <!-- FORM SATUAN -->
        <div id="singleFormPanel" class="space-y-3">
          <div>
            <label class="block text-[11px] font-bold tracking-wider uppercase text-espresso/75 dark:text-white/75 mb-1">
              Nama Tamu <span class="text-rose-600">*</span>
            </label>
            <div class="relative">
              <i data-lucide="user" class="w-4 h-4 text-gold-dark dark:text-gold absolute left-3.5 top-1/2 -translate-y-1/2"></i>
              <input
                id="singleGuestName"
                type="text"
                oninput="handleSingleNameInput()"
                placeholder="Contoh: Keluarga Bpk. Budi"
                autocomplete="off"
                class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-ivory dark:bg-charcoal border border-[var(--gen-border)] dark:border-charcoal-border text-xs text-espresso dark:text-white placeholder:text-[color-mix(in_srgb,var(--gen-ink)_40%,transparent)] dark:placeholder:text-white/35 focus:outline-none focus:border-gold"
              />
            </div>
            <!-- Saran pencarian tamu (dari server, min 3 huruf) -->
            <div id="guest-suggestions" class="hidden mt-2 rounded-xl overflow-hidden border border-[var(--gen-border)] dark:border-charcoal-border bg-card dark:bg-charcoal-card"></div>
            <!-- Peringatan Duplikat Otomatis -->
            <p id="duplicateWarning" class="hidden text-[11px] text-amber-700 dark:text-amber-400 mt-1 flex items-center gap-1">
              <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
              <span>Nama ini sudah ada di daftar! Link lama akan ditampilkan agar tidak dobel.</span>
            </p>
            <p id="singleNameHint" class="text-[10px] text-espresso/55 dark:text-white/50 mt-1">
              Ketik minimal 3 huruf. Otomatis mendeteksi jika nama tamu sudah pernah dibuat.
            </p>
          </div>

          <div>
            <label class="block text-[11px] font-bold tracking-wider uppercase text-espresso/75 dark:text-white/75 mb-1">
              No. WhatsApp <span class="font-normal text-espresso/50 dark:text-white/45">(Opsional)</span>
            </label>
            <div class="relative">
              <i data-lucide="phone" class="w-4 h-4 text-emerald-700 dark:text-emerald-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
              <input
                id="singleGuestPhone"
                type="tel"
                placeholder="Contoh: 628123456789"
                class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-ivory dark:bg-charcoal border border-[var(--gen-border)] dark:border-charcoal-border text-xs text-espresso dark:text-white placeholder:text-[color-mix(in_srgb,var(--gen-ink)_40%,transparent)] dark:placeholder:text-white/35 focus:outline-none focus:border-gold"
              />
            </div>
            <p class="text-[10px] text-espresso/55 dark:text-white/50 mt-1">
              Gunakan format <code class="font-mono text-espresso/80 dark:text-gold-light">628xxx</code> (atau 08xxx akan otomatis dikonversi ke 62).
            </p>
          </div>

          <div>
            <label class="block text-[11px] font-bold tracking-wider uppercase text-espresso/75 dark:text-white/75 mb-1">
              Kategori <span class="font-normal text-espresso/50 dark:text-white/45">(Opsional)</span>
            </label>
            <div class="relative">
              <i data-lucide="tag" class="w-4 h-4 text-gold-dark dark:text-gold absolute left-3.5 top-1/2 -translate-y-1/2"></i>
              <input
                id="singleGuestCategory"
                type="text"
                list="categoryList"
                maxlength="50"
                placeholder="Contoh: Keluarga"
                autocomplete="off"
                class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-ivory dark:bg-charcoal border border-[var(--gen-border)] dark:border-charcoal-border text-xs text-espresso dark:text-white placeholder:text-[color-mix(in_srgb,var(--gen-ink)_40%,transparent)] dark:placeholder:text-white/35 focus:outline-none focus:border-gold"
              />
            </div>
            <p class="text-[10px] text-espresso/55 dark:text-white/50 mt-1">
              Pilih dari saran atau ketik kategori sendiri.
            </p>
          </div>

          <button
            id="btn-generate-single"
            onclick="generateSingleGuest()"
            class="w-full py-3 px-4 rounded-xl bg-gold hover:bg-gold-dark dark:bg-gold dark:hover:bg-gold-light text-onaccent font-semibold text-xs flex items-center justify-center gap-2 shadow-md transition active:scale-[0.99]"
          >
            <i data-lucide="link" class="w-4 h-4"></i>
            <span>Generate Link Undangan</span>
          </button>
        </div>

        <!-- FORM MASSAL -->
        <div id="bulkFormPanel" class="hidden space-y-3">
          <div>
            <div class="flex items-center justify-between mb-1">
              <label class="text-[11px] font-bold tracking-wider uppercase text-espresso/75 dark:text-white/75">
                Daftar Tamu (Satu Tamu Per Baris)
              </label>
              <span class="text-[10px] text-gold-dark dark:text-gold font-medium">Maks. 100 baris</span>
            </div>
            <p class="text-[11px] text-espresso/65 dark:text-white/60 mb-2 leading-relaxed">
              Format: <strong>Nama, nomor WA, kategori</strong> (pisahkan dengan koma; nomor dan kategori boleh kosong). Nama yang sudah ada tidak dibuat ulang.
            </p>
            <textarea
              id="bulkGuestTextarea"
              rows="4"
              placeholder="Budi, 08123456789, Keluarga&#10;Ibu Sari, , Alumni&#10;Ahmad, 628987654321"
              class="w-full p-3 rounded-xl bg-ivory dark:bg-charcoal border border-[var(--gen-border)] dark:border-charcoal-border text-xs font-mono text-espresso dark:text-white placeholder:text-[color-mix(in_srgb,var(--gen-ink)_40%,transparent)] dark:placeholder:text-white/35 focus:outline-none focus:border-gold leading-relaxed"
            ></textarea>
          </div>

          <button
            id="btn-generate-bulk"
            onclick="generateBulkGuests()"
            class="w-full py-3 px-4 rounded-xl bg-gold hover:bg-gold-dark dark:bg-gold dark:hover:bg-gold-light text-onaccent font-semibold text-xs flex items-center justify-center gap-2 shadow-md transition active:scale-[0.99]"
          >
            <i data-lucide="list-plus" class="w-4 h-4"></i>
            <span>Generate Massal Sekarang</span>
          </button>
        </div>
      </section>

      <!-- ==================== PANEL HASIL GENERATE SATUAN ==================== -->
      <section id="singleResultPanel" class="hidden bg-card dark:bg-charcoal-card rounded-2xl p-4 shadow-sm border border-[var(--gen-border)] dark:border-charcoal-border space-y-3 lg:col-span-2">
        <h2 class="text-xs font-bold tracking-wider uppercase text-espresso dark:text-white">Hasil Generate</h2>
        <div>
          <label class="block text-[11px] font-bold tracking-wider uppercase text-espresso/75 dark:text-white/75 mb-1">Link Undangan</label>
          <div class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl bg-ivory dark:bg-charcoal border border-[var(--gen-border)] dark:border-charcoal-border">
            <i data-lucide="link-2" class="w-3.5 h-3.5 text-gold-dark dark:text-gold shrink-0"></i>
            <input id="singleResultLink" type="text" readonly onclick="this.select()" class="w-full bg-transparent text-[11px] font-mono text-espresso/80 dark:text-white/80 focus:outline-none truncate" />
            <button type="button" onclick="copySingleLink()" title="Salin link" class="p-1.5 rounded-lg text-gold-dark dark:text-gold hover:bg-[color-mix(in_srgb,var(--gen-accent)_15%,transparent)] shrink-0"><i data-lucide="copy" class="w-4 h-4"></i></button>
          </div>
        </div>
        <div>
          <label class="block text-[11px] font-bold tracking-wider uppercase text-espresso/75 dark:text-white/75 mb-1">Pesan WhatsApp</label>
          <textarea id="singleResultMessage" rows="9" readonly class="w-full p-3 rounded-xl bg-ivory dark:bg-charcoal border border-[var(--gen-border)] dark:border-charcoal-border text-xs text-espresso dark:text-white focus:outline-none leading-relaxed resize-none"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-2">
          <button type="button" onclick="copySingleMessage()" class="py-2.5 px-3 rounded-xl border border-[var(--gen-border)] dark:border-charcoal-border bg-ivory dark:bg-charcoal hover:bg-[color-mix(in_srgb,var(--gen-accent)_15%,transparent)] text-espresso dark:text-white text-[11px] font-semibold flex items-center justify-center gap-1.5 transition active:scale-95"><i data-lucide="copy" class="w-3.5 h-3.5 text-gold-dark dark:text-gold"></i><span>Salin Pesan</span></button>
          <button type="button" onclick="sendSingleWhatsapp()" class="py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-semibold flex items-center justify-center gap-1.5 shadow-sm transition active:scale-95"><i data-lucide="send" class="w-3.5 h-3.5"></i><span>Kirim via WA</span></button>
        </div>
      </section>

      <!-- ==================== PANEL HASIL GENERATE MASSAL ==================== -->
      <section id="bulkResultPanel" class="hidden bg-card dark:bg-charcoal-card rounded-2xl p-4 shadow-sm border border-[var(--gen-border)] dark:border-charcoal-border space-y-3 lg:col-span-2">
        <h2 class="text-xs font-bold tracking-wider uppercase text-espresso dark:text-white">Hasil Generate Massal</h2>
        <p id="bulkSummary" class="text-xs font-semibold text-espresso/80 dark:text-white/80"></p>
        <div class="flex flex-wrap gap-2">
          <button type="button" onclick="copyAllBulkLinks()" class="px-3 py-2 rounded-xl border border-[var(--gen-border)] dark:border-charcoal-border bg-ivory dark:bg-charcoal hover:bg-[color-mix(in_srgb,var(--gen-accent)_15%,transparent)] text-espresso dark:text-white text-[11px] font-semibold flex items-center gap-1.5 transition active:scale-95"><i data-lucide="copy" class="w-3.5 h-3.5 text-gold-dark dark:text-gold"></i><span>Salin Semua Link</span></button>
          <button type="button" onclick="downloadBulkCsv()" class="px-3 py-2 rounded-xl border border-[var(--gen-border)] dark:border-charcoal-border bg-ivory dark:bg-charcoal hover:bg-[color-mix(in_srgb,var(--gen-accent)_15%,transparent)] text-espresso dark:text-white text-[11px] font-semibold flex items-center gap-1.5 transition active:scale-95"><i data-lucide="download" class="w-3.5 h-3.5 text-gold-dark dark:text-gold"></i><span>Unduh CSV</span></button>
        </div>
        <div id="bulkResultList" class="space-y-2.5"></div>
      </section>

      <!-- ==================== CARD 2: TEMPLATE & PRATINJAU WHATSAPP ==================== -->
      <section class="bg-card dark:bg-charcoal-card rounded-2xl p-4 shadow-sm border border-[var(--gen-border)] dark:border-charcoal-border lg:col-span-2">
        <button
          onclick="toggleTemplateSection()"
          class="w-full flex items-center justify-between text-left"
        >
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-emerald-600/15 text-emerald-700 dark:text-emerald-400 flex items-center justify-center">
              <i data-lucide="message-square-text" class="w-4 h-4"></i>
            </div>
            <div>
              <h2 class="text-xs font-bold tracking-wider uppercase text-espresso dark:text-white">
                Template Pesan WhatsApp
              </h2>
              <p id="templateModeSubtitle" class="text-[11px] text-espresso/60 dark:text-white/60">
                Menggunakan Template Sistem • Klik untuk atur &amp; pratinjau
              </p>
            </div>
          </div>
          <i id="templateChevron" data-lucide="chevron-down" class="w-4 h-4 text-espresso/60 dark:text-white/60 transition-transform duration-200 rotate-180"></i>
        </button>

        <div id="templateSectionBody" class="mt-3.5 pt-3.5 border-t border-[var(--gen-border)] dark:border-charcoal-border space-y-3">
          <!-- Radio Pilihan Sistem vs Buat Sendiri -->
          <div class="grid grid-cols-2 gap-2">
            <label
              onclick="setTemplateMode('sistem')"
              class="cursor-pointer flex items-center gap-2 p-2.5 rounded-xl border border-[var(--gen-border)] dark:border-charcoal-border bg-ivory dark:bg-charcoal/60 text-xs font-semibold"
            >
              <input type="radio" name="tplMode" id="radioTplSystem" checked class="accent-[var(--gen-accent)]" />
              <span>Sistem (Otomatis)</span>
            </label>

            <label
              onclick="setTemplateMode('sendiri')"
              class="cursor-pointer flex items-center gap-2 p-2.5 rounded-xl border border-[var(--gen-border)] dark:border-charcoal-border bg-ivory dark:bg-charcoal/60 text-xs font-semibold"
            >
              <input type="radio" name="tplMode" id="radioTplCustom" class="accent-[var(--gen-accent)]" />
              <span>Buat Sendiri</span>
            </label>
          </div>

          <!-- Custom Template Editor (Hidden when System mode) -->
          <div id="customTemplateEditor" class="hidden space-y-2">
            <textarea
              id="customTemplateText"
              rows="4"
              placeholder="Tulis template sendiri di sini. Gunakan [nama] untuk nama tamu dan [link] untuk link undangan."
              class="w-full p-3 rounded-xl bg-ivory dark:bg-charcoal border border-[var(--gen-border)] dark:border-charcoal-border text-xs text-espresso dark:text-white focus:outline-none focus:border-gold leading-relaxed"
            ></textarea>

            <!-- Helper Chip Sisipkan Variabel -->
            <div class="flex items-center gap-1.5 text-[11px]">
              <span class="text-espresso/60 dark:text-white/60">Sisipkan variabel:</span>
              <button
                type="button"
                onclick="insertVariable('[nama]')"
                class="px-2 py-0.5 rounded-md bg-[color-mix(in_srgb,var(--gen-accent)_20%,transparent)] text-gold-dark dark:text-gold font-mono font-semibold hover:bg-gold/30"
              >
                [nama]
              </button>
              <button
                type="button"
                onclick="insertVariable('[link]')"
                class="px-2 py-0.5 rounded-md bg-[color-mix(in_srgb,var(--gen-accent)_20%,transparent)] text-gold-dark dark:text-gold font-mono font-semibold hover:bg-gold/30"
              >
                [link]
              </button>
            </div>
          </div>

          <!-- WhatsApp Chat Bubble Preview -->
          <div id="template-preview-wrap">
            <div class="flex items-center justify-between mb-1.5">
              <span class="text-[10px] font-bold tracking-[0.15em] uppercase text-espresso/65 dark:text-white/65">
                Pratinjau Pesan WhatsApp
              </span>
              <span class="text-[10px] text-emerald-700 dark:text-emerald-400 font-medium flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Live Preview
              </span>
            </div>

            <!-- Simulated WhatsApp Bubble -->
            <div class="rounded-2xl p-3.5 bg-[#E7F8E9] dark:bg-[#172B21] border border-emerald-600/20 text-[#1B2B20] dark:text-[#E4F2E8] text-xs leading-relaxed shadow-inner relative">
              <div id="waPreviewBox" class="whitespace-pre-line break-words font-sans text-[12px]"></div>
              <div class="flex items-center justify-end gap-1 mt-2 text-[10px] text-emerald-800/60 dark:text-emerald-300/60">
                <span>19:15</span>
                <i data-lucide="check-check" class="w-3.5 h-3.5 text-sky-600 dark:text-sky-400"></i>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ==================== CARD 3: DAFTAR TAMU YANG SUDAH DIINPUT ==================== -->
      <section class="bg-card dark:bg-charcoal-card rounded-2xl p-4 shadow-sm border border-[var(--gen-border)] dark:border-charcoal-border space-y-3 lg:col-start-3 lg:row-start-2 lg:row-span-4 lg:col-span-3">
        <!-- Header Daftar Tamu -->
        <div class="flex items-center justify-between gap-2">
          <div>
            <h2 class="text-xs font-bold tracking-wider uppercase text-espresso dark:text-white">
              Daftar Tamu Undangan
            </h2>
            <p id="listCountSubtitle" class="text-[11px] text-espresso/60 dark:text-white/60">
              Memuat daftar tamu...
            </p>
          </div>

          <div class="flex items-center gap-1.5 shrink-0">
            <button
              onclick="downloadGuestCsv()"
              class="px-3 py-1.5 rounded-xl border border-[var(--gen-border)] dark:border-charcoal-border bg-ivory dark:bg-charcoal hover:bg-[color-mix(in_srgb,var(--gen-accent)_15%,transparent)] text-espresso dark:text-gold-light text-[11px] font-semibold flex items-center gap-1.5 transition active:scale-95"
            >
              <i data-lucide="download" class="w-3 h-3"></i>
              <span>Unduh CSV</span>
            </button>

            <button
              onclick="reloadGuestList()"
              class="px-3 py-1.5 rounded-xl border border-[var(--gen-border)] dark:border-charcoal-border bg-ivory dark:bg-charcoal hover:bg-[color-mix(in_srgb,var(--gen-accent)_15%,transparent)] text-espresso dark:text-gold-light text-[11px] font-semibold flex items-center gap-1.5 transition active:scale-95"
            >
              <i id="reloadIcon" data-lucide="refresh-cw" class="w-3 h-3"></i>
              <span>Muat Ulang</span>
            </button>
          </div>
        </div>

        <!-- Search & Filter Pills -->
        <div class="space-y-2">
          <div class="relative">
            <i data-lucide="search" class="w-4 h-4 text-espresso/45 dark:text-white/45 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
            <input
              id="searchGuestInput"
              type="text"
              oninput="renderGuestCards()"
              placeholder="Cari nama, nomor WA, atau kategori..."
              class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-ivory dark:bg-charcoal border border-[var(--gen-border)] dark:border-charcoal-border text-xs text-espresso dark:text-white placeholder:text-[color-mix(in_srgb,var(--gen-ink)_45%,transparent)] dark:placeholder:text-white/40 focus:outline-none focus:border-gold"
            />
          </div>

          <div class="flex items-center justify-between gap-2 flex-wrap">
            <div class="flex items-center gap-1.5">
              <button
                onclick="setStatusFilter('ALL')"
                id="filter-ALL"
                class="status-pill px-3 py-1 rounded-full text-[11px] font-semibold bg-gold dark:bg-gold text-onaccent transition"
              >
                Semua
              </button>
              <button
                onclick="setStatusFilter('UNSENT')"
                id="filter-UNSENT"
                class="status-pill px-3 py-1 rounded-full text-[11px] font-semibold bg-[var(--gen-soft)] dark:bg-charcoal text-espresso/75 dark:text-white/75 transition"
              >
                Belum Dikirim
              </button>
              <button
                onclick="setStatusFilter('SENT')"
                id="filter-SENT"
                class="status-pill px-3 py-1 rounded-full text-[11px] font-semibold bg-[var(--gen-soft)] dark:bg-charcoal text-espresso/75 dark:text-white/75 transition"
              >
                Sudah Dikirim
              </button>
            </div>

            <select id="categoryFilter" onchange="setCategoryFilter(this.value)" title="Filter kategori tamu" class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[var(--gen-soft)] dark:bg-charcoal text-espresso/75 dark:text-white/75 border border-[var(--gen-border)] dark:border-charcoal-border focus:outline-none">
              <option value="">Semua Kategori</option>
            </select>

            <!-- Tombol Legenda Ikon (buka modal) -->
            <button
              onclick="openLegendModal()"
              class="text-[11px] font-semibold text-gold-dark dark:text-gold flex items-center gap-1 hover:underline"
            >
              <i data-lucide="help-circle" class="w-3.5 h-3.5"></i>
              <span>Info Ikon</span>
            </button>
          </div>
        </div>

        <p class="text-[11px] text-espresso/55 dark:text-white/50 leading-relaxed">Tanda <strong>Sudah dikirim</strong> muncul saat tombol Kirim WA diklik, atau saat ditandai manual — pengiriman di dalam aplikasi WhatsApp sendiri tidak bisa dilacak dari sini.</p>

        <!-- Mobile-Friendly Guest Cards Container (scroll di dalam, halaman tidak memanjang) -->
        <div id="guestCardsContainer" class="space-y-2.5 md:space-y-0 md:grid md:grid-cols-2 md:gap-3 pt-1 pr-1 pb-1 max-h-[520px] lg:max-h-[720px] overflow-y-auto guest-scroll"></div>
      </section>
    </main>
  </div>

  <!-- ==================== MODAL KONFIRMASI HAPUS (NO NATIVE CONFIRM) ==================== -->
  <div
    id="deleteConfirmModal"
    class="hidden fixed inset-0 z-50 bg-black/65 backdrop-blur-sm flex items-center justify-center p-4"
  >
    <div class="bg-card dark:bg-charcoal-card border border-[var(--gen-border)] dark:border-charcoal-border rounded-2xl max-w-xs w-full p-5 shadow-xl animate-pop-in text-center">
      <div class="w-11 h-11 mx-auto mb-3 rounded-full bg-rose-500/15 text-rose-600 flex items-center justify-center">
        <i data-lucide="trash-2" class="w-5 h-5"></i>
      </div>
      <h3 class="text-sm font-bold text-espresso dark:text-white">Hapus Tamu Undangan?</h3>
      <p id="deleteConfirmText" class="text-xs text-espresso/70 dark:text-white/70 mt-1 mb-4">
        Tautan undangan untuk tamu ini akan dihapus dari daftar.
      </p>
      <div class="flex gap-2">
        <button
          onclick="closeDeleteModal()"
          class="flex-1 py-2.5 rounded-xl border border-[var(--gen-border)] dark:border-charcoal-border text-xs font-semibold text-espresso/75 dark:text-white/75"
        >
          Batal
        </button>
        <button
          onclick="confirmDeleteGuest()"
          class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold"
        >
          Ya, Hapus
        </button>
      </div>
    </div>
  </div>

  <!-- ==================== MODAL KETERANGAN IKON AKSI ==================== -->
  <div
    id="legendModal"
    class="hidden fixed inset-0 z-50 bg-black/65 backdrop-blur-sm flex items-center justify-center p-4"
    onclick="if (event.target === this) closeLegendModal()"
  >
    <div class="bg-card dark:bg-charcoal-card border border-[var(--gen-border)] dark:border-charcoal-border rounded-2xl max-w-xs w-full p-5 shadow-xl animate-pop-in">
      <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-xl bg-[color-mix(in_srgb,var(--gen-accent)_20%,transparent)] text-gold-dark dark:text-gold flex items-center justify-center">
            <i data-lucide="help-circle" class="w-4 h-4"></i>
          </div>
          <h3 class="text-sm font-bold text-espresso dark:text-white">Keterangan Tombol Aksi</h3>
        </div>
        <button onclick="closeLegendModal()" title="Tutup" class="p-1.5 rounded-lg text-espresso/50 dark:text-white/50 hover:bg-[color-mix(in_srgb,var(--gen-accent)_10%,transparent)] dark:hover:bg-white/10 transition">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>
      <div class="space-y-2.5 text-[11px] text-espresso/80 dark:text-white/80">
        <div class="flex items-center gap-2"><i data-lucide="copy" class="w-3.5 h-3.5 text-espresso dark:text-gold shrink-0"></i> <span><strong>Salin</strong> — menyalin tautan undangan tamu.</span></div>
        <div class="flex items-center gap-2"><i data-lucide="send" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i> <span><strong>Kirim WA</strong> — membuka WhatsApp dengan pesan siap kirim.</span></div>
        <div class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-amber-600 shrink-0"></i> <span><strong>Centang</strong> — menandai tamu sudah dikirimi undangan.</span></div>
        <div class="flex items-center gap-2"><i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-amber-600 shrink-0"></i> <span><strong>Panah Melingkar</strong> — membatalkan tanda <em>Sudah Dikirim</em> (muncul jika sudah terkirim).</span></div>
        <div class="flex items-center gap-2"><i data-lucide="pencil" class="w-3.5 h-3.5 text-sky-600 shrink-0"></i> <span><strong>Pensil</strong> — mengubah nama &amp; kategori tamu (tautan lama tetap berlaku).</span></div>
        <div class="flex items-center gap-2"><i data-lucide="trash-2" class="w-3.5 h-3.5 text-rose-600 shrink-0"></i> <span><strong>Hapus</strong> — menghapus tamu dari daftar.</span></div>
      </div>
      <button
        onclick="closeLegendModal()"
        class="w-full mt-4 py-2.5 rounded-xl bg-gold hover:bg-gold-dark dark:bg-gold dark:hover:bg-gold-light text-onaccent text-xs font-semibold transition active:scale-[0.99]"
      >
        Tutup
      </button>
    </div>
  </div>

  <!-- Toast Notification -->
  <div
    id="toastMessage"
    class="hidden fixed bottom-5 left-1/2 -translate-x-1/2 z-50 px-4 py-2.5 rounded-full bg-gold dark:bg-gold text-onaccent text-xs font-semibold shadow-lg transition-all whitespace-nowrap"
  ></div>

  <!-- Daftar kategori tamu (dipakai input satuan + edit; boleh ketik bebas) -->
  <datalist id="categoryList">
    <option value="Keluarga"></option>
    <option value="Alumni"></option>
    <option value="Sahabat"></option>
    <option value="Teman"></option>
    <option value="Rekan Kerja"></option>
    <option value="Tetangga"></option>
    <option value="Lainnya"></option>
  </datalist>

  <!-- ==================== JAVASCRIPT LOGIC ==================== -->
  <script>
    const invitationId = {{ $invitation->id }};
    const invSlug = {!! json_encode($invitation->slug) !!};
    const bride = {!! json_encode($bride, JSON_UNESCAPED_UNICODE) !!};
    const groom = {!! json_encode($groom, JSON_UNESCAPED_UNICODE) !!};
    const baseUrl = {!! json_encode(url('/'), JSON_UNESCAPED_UNICODE) !!};
    const API = {
      search: "{{ url('api/search_guest') }}",
      generate: "{{ url('api/generate_guest') }}",
      bulk: "{{ url('api/generate_guests_bulk') }}",
      list: "{{ url('api/generator_guests') }}",
      mark: "{{ url('api/mark_guest_wa') }}",
      update: "{{ url('api/update_generator_guest') }}",
      remove: "{{ url('api/delete_generator_guest') }}"
    };

    // Daftar tamu dari server (diisi loadGeneratorGuests saat halaman dibuka).
    let generatorGuests = [];
    let bulkResults = [];
    let currentSingleGuest = null; // {nama, slug, link, no_hp} tamu di panel hasil satuan
    let currentFilter = 'ALL'; // 'ALL' | 'UNSENT' | 'SENT'
    let currentCategory = ''; // '' = semua kategori
    let editingGuestId = null;
    let pendingDeleteId = null;

    // Template WA: 'sistem' = template bawaan (otomatis ada), 'sendiri' = teks
    // dari kolom template dengan placeholder [nama]/[link]. Pilihan + teks
    // sendiri disimpan di perangkat ini (localStorage per undangan), bukan di
    // server, supaya halaman publik ini tidak bisa dipakai orang lain untuk
    // mengubah template undangan.
    function safeStorageGet(k) { try { return localStorage.getItem(k); } catch (e) { return null; } }
    function safeStorageSet(k, v) { try { localStorage.setItem(k, v); } catch (e) {} }
    let templateMode = safeStorageGet('gen_tpl_mode_' + invSlug) || 'sistem';
    let customTemplate = safeStorageGet('gen_tpl_custom_' + invSlug) || '';

    function escHtml(s) {
      return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; });
    }

    function refreshIcons() { if (window.lucide) lucide.createIcons(); }

    // Slug ala Laravel Str::slug (untuk pratinjau saja; link asli memakai slug server).
    function jsSlug(name) {
      var s = String(name || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
      return s || 'tamu';
    }

    function guestLink(g) { return baseUrl + '/' + invSlug + '?to=' + g.slug; }
    function withLink(g) { return Object.assign({}, g, { link: guestLink(g) }); }

    // Helper: Normalisasi Nomor WA ke 628xxx
    function normalizePhone(rawPhone) {
      var cleaned = String(rawPhone || '').replace(/[^0-9]/g, '');
      if (cleaned.charAt(0) === '0') cleaned = '62' + cleaned.slice(1);
      return cleaned;
    }

    // Waktu server disimpan dalam UTC (config Laravel: UTC), sementara
    // kode lama menafsirkan "2026-10-26 08:30:00" sebagai waktu lokal,
    // sehingga jamnya meleset 7 jam padahal tanggalnya tetap benar.
    // Perbaikan: string tanpa zona dianggap UTC (tambah 'Z'), string
    // berzona ISO dipakai apa adanya, lalu ditampilkan eksplisit WIB.
    function parseServerTime(s) {
      if (!s) return null;
      var t = String(s).trim().replace(' ', 'T');
      if (!/[Zz]$/.test(t) && !/[+-]\d{2}:?\d{2}$/.test(t)) t += 'Z';
      var d = new Date(t);
      return isNaN(d) ? null : d;
    }

    function formatWaDate(s) {
      var d = parseServerTime(s);
      if (!d) return '';
      return d.toLocaleString('id-ID', { timeZone: 'Asia/Jakarta', day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    // Badge Status WA ala versi lama (dipakai baris hasil massal).
    function waBadgeHtml(g) {
      if (g.wa_sent_at) {
        return '<span class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400">SUDAH DIKIRIM</span>'
          + '<div class="text-[10px] text-espresso/55 dark:text-white/50">' + escHtml(formatWaDate(g.wa_sent_at)) + '</div>';
      }
      return '<span class="text-[11px] font-bold text-espresso/40 dark:text-white/40">BELUM DIKIRIM</span>';
    }

    // Generate Teks Pesan WA Lengkap untuk Tamu Tertentu
    function buildWaMessage(guestName, guestUrl) {
      if (templateMode === 'sendiri' && customTemplate.trim()) {
        return customTemplate.replace(/\[nama\]/gi, guestName).replace(/\[link\]/gi, guestUrl);
      }
      return 'Kepada Yth,\n*' + guestName + '*\nDitempat\n\nTanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i, teman dan juga sahabat, untuk menghadiri acara Resepsi kami\n\n*' + groom + ' & ' + bride + '*\n\nPesan ini merupakan undangan resmi dari kami. Silahkan kunjungi link berikut untuk membuka undangan anda:\n' + guestUrl + '\n\nMerupakan suatu kebahagiaan jika Bapak/Ibu/Saudara/i berkenan untuk hadir dan memberikan doa restu untuk kami. Terima kasih banyak atas waktu & perhatiannya.';
    }

    // Update Pratinjau WhatsApp Live (nama dari kolom Satuan, min 2 huruf).
    // Saat mode Sendiri dipilih tapi template sendiri masih kosong, pratinjau
    // disembunyikan sampai template ditulis (pengiriman tetap memakai sistem).
    function updateWhatsAppPreview() {
      var wrap = document.getElementById('template-preview-wrap');
      var typedName = (document.getElementById('singleGuestName') ? document.getElementById('singleGuestName').value : '').trim();
      var previewName = typedName.length >= 2 ? typedName : 'Nama Tamu';
      var previewUrl = baseUrl + '/' + invSlug + '?to=' + jsSlug(previewName);
      if (templateMode === 'sendiri' && !customTemplate.trim()) {
        if (wrap) wrap.classList.add('hidden');
        return;
      }
      if (wrap) wrap.classList.remove('hidden');
      var message = buildWaMessage(previewName, previewUrl);
      var escaped = message
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/\*([^*]+)\*/g, '<strong>$1</strong>')
        .replace(/(https?:\/\/[^\s]+)/g, '<span class="underline text-sky-700 dark:text-sky-300 break-all">$1</span>');
      document.getElementById('waPreviewBox').innerHTML = escaped;
    }

    // Toggle Dark Mode (hanya sesi ini, tidak disimpan)
    function toggleDarkMode() {
      var isDark = document.documentElement.classList.toggle('dark');
      var iconEl = document.getElementById('themeIcon');
      var labelEl = document.getElementById('themeLabel');
      if (isDark) {
        iconEl.setAttribute('data-lucide', 'sun');
        labelEl.textContent = 'Mode Siang';
      } else {
        iconEl.setAttribute('data-lucide', 'moon');
        labelEl.textContent = 'Mode Malam';
      }
      refreshIcons();
    }

    // Switch Mode Tambah Satuan vs Generate Massal
    function switchInputMode(mode) {
      var singleBtn = document.getElementById('modeBtnSingle');
      var bulkBtn = document.getElementById('modeBtnBulk');
      var singlePanel = document.getElementById('singleFormPanel');
      var bulkPanel = document.getElementById('bulkFormPanel');
      var activeCls = 'py-2 px-3 rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 bg-gold dark:bg-gold text-onaccent shadow-sm transition';
      var idleCls = 'py-2 px-3 rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 text-espresso/75 dark:text-white/75 hover:bg-white/40 dark:hover:bg-white/5 transition';
      if (mode === 'single') {
        singleBtn.className = activeCls;
        bulkBtn.className = idleCls;
        singlePanel.classList.remove('hidden');
        bulkPanel.classList.add('hidden');
      } else {
        bulkBtn.className = activeCls;
        singleBtn.className = idleCls;
        bulkPanel.classList.remove('hidden');
        singlePanel.classList.add('hidden');
      }
    }

    // Deteksi Duplikat Real-time & Update Pratinjau saat mengetik Nama Tamu
    var searchTimer = null;
    var lastSearchResults = [];
    function handleSingleNameInput() {
      var val = document.getElementById('singleGuestName').value.trim();
      var warningEl = document.getElementById('duplicateWarning');
      var hintEl = document.getElementById('singleNameHint');
      updateWhatsAppPreview();
      if (val.length >= 3) {
        var exists = generatorGuests.some(function (g) { return g.nama.toLowerCase() === val.toLowerCase(); });
        if (exists) {
          warningEl.classList.remove('hidden');
          hintEl.classList.add('hidden');
        } else {
          warningEl.classList.add('hidden');
          hintEl.classList.remove('hidden');
        }
      } else {
        warningEl.classList.add('hidden');
        hintEl.classList.remove('hidden');
      }
      // Saran pencarian dari server (debounce 300ms, min 3 huruf).
      var box = document.getElementById('guest-suggestions');
      clearTimeout(searchTimer);
      if (val.length < 3) { box.classList.add('hidden'); box.innerHTML = ''; return; }
      searchTimer = setTimeout(function () {
        fetch(API.search + '?invitation_id=' + invitationId + '&q=' + encodeURIComponent(val))
          .then(function (res) { return res.json(); })
          .then(function (data) {
            if (!data.success || !data.data.length) { box.classList.add('hidden'); box.innerHTML = ''; return; }
            lastSearchResults = data.data;
            // Tombol saran TIDAK boleh memakai onclick="...JSON.stringify..."
            // karena string JSON bertanda petik ganda akan memotong atribut
            // onclick yang juga bertanda petik ganda sehingga kliknya mati.
            // Sebagai gantinya pakai data-idx + addEventListener.
            box.innerHTML = '<div class="px-3 py-2 bg-amber-50 dark:bg-amber-500/10 text-[11px] font-bold text-amber-800 dark:text-amber-300 uppercase tracking-widest">Tamu sudah ada — pakai link ini supaya tidak dobel:</div>' + data.data.map(function (g, i) {
              return '<button type="button" data-idx="' + i + '" class="suggestion-btn w-full text-left px-3 py-2 hover:bg-[color-mix(in_srgb,var(--gen-accent)_10%,transparent)] text-xs border-t border-[var(--gen-border)] dark:border-charcoal-border">' + escHtml(g.nama) + '</button>';
            }).join('');
            box.querySelectorAll('.suggestion-btn').forEach(function (btn) {
              btn.addEventListener('click', function () {
                var g = lastSearchResults[Number(btn.dataset.idx)];
                if (g) showExistingGuest(g.nama, g.slug);
              });
            });
            box.classList.remove('hidden');
          })
          .catch(function () { /* abaikan */ });
      }, 300);
    }
    document.addEventListener('click', function (e) {
      var box = document.getElementById('guest-suggestions');
      if (box && !e.target.closest('#guest-suggestions') && e.target.id !== 'singleGuestName') {
        box.classList.add('hidden');
      }
    });

    // Panel hasil satuan: tampilkan link + pesan WA tamu (baru maupun duplikat).
    function showSingleResult(name, slug, phone) {
      var link = baseUrl + '/' + invSlug + '?to=' + slug;
      currentSingleGuest = { nama: name, slug: slug, link: link, no_hp: phone || '' };
      document.getElementById('singleResultLink').value = link;
      document.getElementById('singleResultMessage').value = buildWaMessage(name, link);
      document.getElementById('singleResultPanel').classList.remove('hidden');
      document.getElementById('singleResultPanel').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function copySingleLink() {
      if (!currentSingleGuest) return;
      copyTextWithToast(currentSingleGuest.link, 'Link tersalin.');
    }

    function copySingleMessage() {
      var v = document.getElementById('singleResultMessage').value;
      if (v) copyTextWithToast(v, 'Pesan tersalin.');
    }

    async function sendSingleWhatsapp() {
      if (!currentSingleGuest) return;
      var msg = document.getElementById('singleResultMessage').value;
      var waUrl = 'https://wa.me/';
      var cleanPhone = normalizePhone(currentSingleGuest.no_hp);
      if (cleanPhone) waUrl += cleanPhone;
      waUrl += '?text=' + encodeURIComponent(msg);
      window.open(waUrl, '_blank', 'noopener,noreferrer');
      var r = await markWa(currentSingleGuest.slug, true);
      if (r.ok) {
        var g = generatorGuests.find(function (x) { return x.slug === currentSingleGuest.slug; });
        if (g) g.wa_sent_at = r.wa_sent_at;
        syncGuestWa(currentSingleGuest.slug, r.wa_sent_at);
        syncBulkWa(currentSingleGuest.slug, r.wa_sent_at);
        renderBulkResults();
        refreshUI();
      } else {
        showToast(r.message);
      }
    }

    // Tamu sudah ada: tampilkan kartu lamanya di daftar + panel hasil berisi link lama (jangan dobel).
    function showExistingGuest(name, slug) {
      document.getElementById('searchGuestInput').value = name;
      setStatusFilter('ALL');
      document.getElementById('guest-suggestions').classList.add('hidden');
      var known = generatorGuests.find(function (g) { return g.slug === slug; });
      showSingleResult(name, slug, known ? (known.no_hp || '') : '');
      showToast('Tamu "' + name + '" sudah ada, menampilkan link lamanya');
    }

    // Generate Link Satuan
    async function generateSingleGuest() {
      var nameInput = document.getElementById('singleGuestName');
      var phoneInput = document.getElementById('singleGuestPhone');
      var rawName = nameInput.value.trim();
      if (rawName.length < 3) {
        showToast('Masukkan nama tamu minimal 3 huruf');
        nameInput.focus();
        return;
      }
      var existing = generatorGuests.find(function (g) { return g.nama.toLowerCase() === rawName.toLowerCase(); });
      if (existing) {
        showExistingGuest(existing.nama, existing.slug);
        return;
      }
      var phone = normalizePhone(phoneInput.value);
      var category = (document.getElementById('singleGuestCategory') ? document.getElementById('singleGuestCategory').value : '').trim().slice(0, 50);
      var btn = document.getElementById('btn-generate-single');
      if (btn) btn.disabled = true;
      try {
        var res = await fetch(API.generate, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ invitation_id: invitationId, nama: rawName, no_hp: phone, category: category })
        });
        var data = await res.json();
        if (!data.success) {
          showToast(data.message || 'Gagal membuat link.');
          return;
        }
        var g = data.data;
        generatorGuests.unshift(withLink({ id: g.id, nama: g.nama, slug: g.slug, no_hp: g.no_hp || phone, category: g.category || category, wa_sent_at: g.wa_sent_at || null }));
        nameInput.value = '';
        phoneInput.value = '';
        var catInput = document.getElementById('singleGuestCategory');
        if (catInput) catInput.value = '';
        handleSingleNameInput();
        refreshUI();
        showSingleResult(rawName, g.slug, phone);
        showToast('Link undangan untuk "' + rawName + '" berhasil dibuat!');
      } catch (err) {
        showToast('Terjadi kesalahan sistem. Pastikan server berjalan.');
      } finally {
        if (btn) btn.disabled = false;
      }
    }

    // Format massal: "Nama, nomor WA, kategori" — koma saja sebagai pemisah.
    // Baris tanpa koma berarti nama saja (nomor + kategori kosong);
    // kategori adalah kolom ke-3 (opsional, boleh diketik bebas).
    function parseBulkInput(text) {
      var guests = [];
      text.split('\n').forEach(function (line) {
        var trimmed = line.trim();
        if (!trimmed) return;
        var parts = trimmed.split(',').map(function (p) { return p.trim(); });
        var nama = parts[0] || '';
        var no_hp = parts[1] || '';
        var category = parts.slice(2).join(',').trim();
        if (!nama) return;
        guests.push({ nama: nama, no_hp: no_hp, category: category });
      });
      return guests;
    }

    // Generate Massal (Maks 100 baris; duplikat dilewati server)
    async function generateBulkGuests() {
      var textarea = document.getElementById('bulkGuestTextarea');
      var guests = parseBulkInput(textarea.value);
      if (!guests.length) {
        showToast('Tempel daftar nama tamu terlebih dahulu');
        return;
      }
      if (guests.length > 100) {
        showToast('Maksimal 100 tamu sekali generate. Daftar ini berisi ' + guests.length + ' tamu.');
        return;
      }
      var btn = document.getElementById('btn-generate-bulk');
      if (btn) btn.disabled = true;
      try {
        var res = await fetch(API.bulk, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ invitation_id: invitationId, guests: guests })
        });
        var data = await res.json();
        if (!data.success) {
          showToast(data.message || 'Gagal generate massal.');
          return;
        }
        var results = data.data || [];
        bulkResults = results.map(function (r) {
          return withLink({ id: r.id, nama: r.nama, slug: r.slug, no_hp: r.no_hp || '', category: r.category || '', wa_sent_at: r.wa_sent_at || null, existed: !!r.existed });
        });
        var addedCount = bulkResults.filter(function (r) { return !r.existed; }).length;
        var skippedCount = bulkResults.length - addedCount;
        document.getElementById('bulkSummary').textContent = 'Berhasil: ' + bulkResults.length + ' link (' + addedCount + ' baru, ' + skippedCount + ' sudah ada). Baris kosong dan nama dobel di dalam daftar dilewati.';
        renderBulkResults();
        document.getElementById('bulkResultPanel').classList.remove('hidden');
        document.getElementById('bulkResultPanel').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        textarea.value = '';
        await loadGeneratorGuests();
        showToast(addedCount + ' tamu baru ditambahkan' + (skippedCount > 0 ? ' (' + skippedCount + ' duplikat dilewati)' : ''));
      } catch (err) {
        showToast('Terjadi kesalahan sistem. Pastikan server berjalan.');
      } finally {
        if (btn) btn.disabled = false;
      }
    }

    // Toggle Accordion Template Pesan WA
    function toggleTemplateSection() {
      var body = document.getElementById('templateSectionBody');
      var chevron = document.getElementById('templateChevron');
      body.classList.toggle('hidden');
      chevron.classList.toggle('rotate-180');
    }

    // Set Mode Template (Sistem vs Buat Sendiri) + simpan pilihan
    function setTemplateMode(mode) {
      templateMode = mode;
      safeStorageSet('gen_tpl_mode_' + invSlug, mode);
      var customEditor = document.getElementById('customTemplateEditor');
      var subtitle = document.getElementById('templateModeSubtitle');
      if (mode === 'sendiri') {
        document.getElementById('radioTplCustom').checked = true;
        customEditor.classList.remove('hidden');
        subtitle.textContent = 'Menggunakan Template Kustom • Tersimpan otomatis';
      } else {
        document.getElementById('radioTplSystem').checked = true;
        customEditor.classList.add('hidden');
        subtitle.textContent = 'Menggunakan Template Sistem • Klik untuk atur & pratinjau';
      }
      updateWhatsAppPreview();
    }

    // Sisipkan Variabel [nama] / [link] ke Textarea Custom
    function insertVariable(token) {
      var textarea = document.getElementById('customTemplateText');
      var start = textarea.selectionStart;
      var end = textarea.selectionEnd;
      var text = textarea.value;
      textarea.value = text.substring(0, start) + token + text.substring(end);
      textarea.focus();
      textarea.selectionStart = textarea.selectionEnd = start + token.length;
      customTemplate = textarea.value;
      safeStorageSet('gen_tpl_custom_' + invSlug, customTemplate);
      updateWhatsAppPreview();
    }

    function initTemplateUI() {
      var area = document.getElementById('customTemplateText');
      if (area) {
        area.value = customTemplate;
        area.addEventListener('input', function () {
          customTemplate = area.value;
          safeStorageSet('gen_tpl_custom_' + invSlug, customTemplate);
          updateWhatsAppPreview();
        });
      }
      if (templateMode === 'sendiri') {
        document.getElementById('radioTplCustom').checked = true;
        document.getElementById('customTemplateEditor').classList.remove('hidden');
        document.getElementById('templateModeSubtitle').textContent = 'Menggunakan Template Kustom • Tersimpan otomatis';
      } else {
        document.getElementById('radioTplSystem').checked = true;
      }
      updateWhatsAppPreview();
    }

    // Filter Daftar Tamu
    function setStatusFilter(filter) {
      currentFilter = filter;
      document.querySelectorAll('.status-pill').forEach(function (btn) {
        btn.className = 'status-pill px-3 py-1 rounded-full text-[11px] font-semibold bg-[var(--gen-soft)] dark:bg-charcoal text-espresso/75 dark:text-white/75 transition';
      });
      var active = document.getElementById('filter-' + filter);
      if (active) {
        active.className = 'status-pill px-3 py-1 rounded-full text-[11px] font-semibold bg-gold dark:bg-gold text-onaccent transition';
      }
      renderGuestCards();
    }

    // Filter Kategori (opsi dibangun dari kategori yang ada di daftar tamu).
    function rebuildCategoryOptions() {
      var sel = document.getElementById('categoryFilter');
      if (!sel) return;
      var seen = {};
      generatorGuests.forEach(function (g) { if (g.category) seen[g.category] = true; });
      var cats = Object.keys(seen).sort(function (a, b) { return a.localeCompare(b, 'id'); });
      var cur = sel.value;
      sel.innerHTML = '<option value="">Semua Kategori</option>' + cats.map(function (c) { return '<option value="' + escHtml(c) + '"' + (c === cur ? ' selected' : '') + '>' + escHtml(c) + '</option>'; }).join('');
      if (cur && !seen[cur]) { currentCategory = ''; }
    }
    function setCategoryFilter(v) {
      currentCategory = v || '';
      renderGuestCards();
    }

    // Modal Keterangan Ikon Aksi
    function openLegendModal() {
      document.getElementById('legendModal').classList.remove('hidden');
    }

    function closeLegendModal() {
      document.getElementById('legendModal').classList.add('hidden');
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeLegendModal();
    });

    // Muat Ulang Daftar Tamu dari Server
    async function loadGeneratorGuests() {
      try {
        var res = await fetch(API.list + '?invitation_id=' + invitationId);
        var data = await res.json();
        if (!data.success) throw new Error('gagal');
        generatorGuests = (data.data || []).map(withLink);
        refreshUI();
      } catch (e) {
        var container = document.getElementById('guestCardsContainer');
        if (container) {
          container.innerHTML = '<div class="text-center py-8 px-4 rounded-2xl bg-ivory dark:bg-charcoal/60 border border-dashed border-[var(--gen-border)] dark:border-charcoal-border"><p class="text-xs font-semibold text-rose-600">Gagal memuat daftar tamu. Coba muat ulang.</p></div>';
        }
        showToast('Gagal memuat daftar tamu.');
      }
    }

    // Muat Ulang Animasi
    async function reloadGuestList() {
      var icon = document.getElementById('reloadIcon');
      if (icon) icon.classList.add('animate-spin');
      await loadGeneratorGuests();
      if (icon) icon.classList.remove('animate-spin');
      showToast('Daftar tamu diperbarui');
    }

    // Refresh Angka & Kartu Tamu
    function refreshUI() {
      var total = generatorGuests.length;
      var sent = generatorGuests.filter(function (g) { return !!g.wa_sent_at; }).length;
      var unsent = total - sent;
      document.getElementById('statTotalGuests').textContent = total;
      document.getElementById('statSentGuests').textContent = sent;
      document.getElementById('statUnsentGuests').textContent = unsent;
      rebuildCategoryOptions();
      renderGuestCards();
    }

    // Tamu yang sedang tampil (sesudah cari + filter) — dipakai render + Unduh CSV.
    function getFilteredGuests() {
      var query = (document.getElementById('searchGuestInput') ? document.getElementById('searchGuestInput').value : '').toLowerCase();
      return generatorGuests.filter(function (g) {
        var matchQuery = g.nama.toLowerCase().includes(query) || (g.no_hp && String(g.no_hp).includes(query)) || (g.slug && g.slug.toLowerCase().includes(query)) || (g.category && g.category.toLowerCase().includes(query));
        if (!matchQuery) return false;
        if (currentCategory && (g.category || '') !== currentCategory) return false;
        if (currentFilter === 'SENT') return !!g.wa_sent_at;
        if (currentFilter === 'UNSENT') return !g.wa_sent_at;
        return true;
      });
    }

    // Render Kartu Daftar Tamu Mobile-First
    function renderGuestCards() {
      var filtered = getFilteredGuests();

      document.getElementById('listCountSubtitle').textContent = 'Menampilkan ' + filtered.length + ' dari ' + generatorGuests.length + ' tamu';

      var container = document.getElementById('guestCardsContainer');
      if (filtered.length === 0) {
        container.innerHTML = '<div class="text-center py-8 px-4 rounded-2xl bg-ivory dark:bg-charcoal/60 border border-dashed border-[var(--gen-border)] dark:border-charcoal-border"><p class="text-xs font-semibold text-espresso/70 dark:text-white/70">Tidak ada tamu yang cocok</p><p class="text-[11px] text-espresso/50 dark:text-white/50 mt-0.5">Coba ubah kata kunci pencarian atau filter status.</p></div>';
        return;
      }

      container.innerHTML = filtered.map(function (g, index) {
        var sent = !!g.wa_sent_at;
        var isEditing = editingGuestId === g.id;
        var editHtml = '';
        if (isEditing) {
          editHtml = '<div class="flex-1 min-w-0 space-y-1.5">'
            + '<div class="flex items-center gap-1.5">'
            + '<input id="editInput-' + g.id + '" type="text" value="' + escHtml(g.nama) + '" class="w-full px-2.5 py-1 rounded-lg bg-card dark:bg-charcoal-card border border-gold text-xs font-semibold text-espresso dark:text-white focus:outline-none" />'
            + '<button onclick="saveEditGuest(' + g.id + ')" title="Simpan" class="p-1.5 rounded-lg bg-emerald-600 text-white shrink-0"><i data-lucide="check" class="w-3.5 h-3.5"></i></button>'
            + '<button onclick="cancelEditGuest()" title="Batal" class="p-1.5 rounded-lg bg-[color-mix(in_srgb,var(--gen-accent)_15%,transparent)] dark:bg-white/15 text-espresso dark:text-white shrink-0"><i data-lucide="x" class="w-3.5 h-3.5"></i></button>'
            + '</div>'
            + '<input id="editCategory-' + g.id + '" type="text" list="categoryList" maxlength="50" value="' + escHtml(g.category || '') + '" placeholder="Kategori (opsional)" class="w-full px-2.5 py-1 rounded-lg bg-card dark:bg-charcoal-card border border-[var(--gen-border)] dark:border-charcoal-border text-xs text-espresso dark:text-white placeholder:text-[color-mix(in_srgb,var(--gen-ink)_40%,transparent)] dark:placeholder:text-white/35 focus:outline-none focus:border-gold" />'
            + '</div>';
        } else {
          editHtml = '<div class="min-w-0"><p class="text-xs font-bold text-espresso dark:text-white truncate">' + escHtml(g.nama) + '</p>'
            + '<p class="text-[10px] text-espresso/55 dark:text-white/50 font-mono">' + (g.no_hp ? '+' + escHtml(g.no_hp) : 'Tanpa nomor WA') + '</p>'
            + (g.category ? '<span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[color-mix(in_srgb,var(--gen-accent)_15%,transparent)] text-gold-dark dark:text-gold"><i data-lucide="tag" class="w-3 h-3"></i>' + escHtml(g.category) + '</span>' : '') + '</div>';
        }
        var statusPill = sent
          ? '<div class="shrink-0 text-right"><span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-600/15 text-emerald-700 dark:text-emerald-400"><i data-lucide="check-check" class="w-3 h-3"></i> Sudah Dikirim</span><div class="text-[10px] text-espresso/55 dark:text-white/50 mt-0.5">' + escHtml(formatWaDate(g.wa_sent_at)) + '</div></div>'
          : '<span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-600/15 text-amber-800 dark:text-amber-300">Belum Dikirim</span>';
        return '<div class="p-3.5 rounded-2xl bg-ivory dark:bg-charcoal border border-[var(--gen-border)] dark:border-charcoal-border transition hover:border-gold">'
          + '<div class="flex items-start justify-between gap-2 mb-2">'
          + '<div class="flex items-center gap-2.5 min-w-0 flex-1">'
          + '<span class="w-6 h-6 rounded-full bg-[color-mix(in_srgb,var(--gen-accent)_20%,transparent)] text-gold-dark dark:text-gold font-bold text-[11px] flex items-center justify-center shrink-0">' + (index + 1) + '</span>'
          + editHtml
          + '</div>' + statusPill + '</div>'
          + '<div class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl bg-card dark:bg-charcoal-card border border-[var(--gen-border)] dark:border-charcoal-border mb-2.5">'
          + '<i data-lucide="link-2" class="w-3.5 h-3.5 text-gold-dark dark:text-gold shrink-0"></i>'
          + '<input type="text" readonly value="' + escHtml(g.link) + '" onclick="this.select()" class="w-full bg-transparent text-[11px] font-mono text-espresso/80 dark:text-white/80 focus:outline-none truncate" />'
          + '</div>'
          + '<div class="flex items-center justify-between gap-1.5">'
          + '<div class="flex items-center gap-1.5">'
          + '<button onclick="copyGuestLink(' + g.id + ')" class="px-2.5 py-1.5 rounded-xl bg-card dark:bg-charcoal-card hover:bg-[color-mix(in_srgb,var(--gen-accent)_15%,transparent)] border border-[var(--gen-border)] dark:border-charcoal-border text-espresso dark:text-white text-[11px] font-semibold flex items-center gap-1 transition active:scale-95"><i data-lucide="copy" class="w-3.5 h-3.5 text-gold-dark dark:text-gold"></i><span>Salin</span></button>'
          + '<button onclick="sendWhatsAppGuest(' + g.id + ')" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-semibold flex items-center gap-1 shadow-xs transition active:scale-95"><i data-lucide="send" class="w-3.5 h-3.5"></i><span>Kirim WA</span></button>'
          + '</div>'
          + '<div class="flex items-center gap-1">'
          + '<button onclick="toggleSentStatus(' + g.id + ')" title="' + (sent ? 'Batalkan tanda sudah dikirim' : 'Tandai sudah dikirim') + '" class="p-2 rounded-xl border border-[var(--gen-border)] dark:border-charcoal-border bg-card dark:bg-charcoal-card hover:bg-amber-500/10 text-amber-700 dark:text-amber-400 transition active:scale-95"><i data-lucide="' + (sent ? 'rotate-ccw' : 'check') + '" class="w-3.5 h-3.5"></i></button>'
          + '<button onclick="startEditGuest(' + g.id + ')" title="Edit nama tamu" class="p-2 rounded-xl border border-[var(--gen-border)] dark:border-charcoal-border bg-card dark:bg-charcoal-card hover:bg-sky-500/10 text-sky-700 dark:text-sky-400 transition active:scale-95"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></button>'
          + '<button onclick="promptDeleteGuest(' + g.id + ')" title="Hapus tamu" class="p-2 rounded-xl border border-[var(--gen-border)] dark:border-charcoal-border bg-card dark:bg-charcoal-card hover:bg-rose-500/10 text-rose-600 dark:text-rose-400 transition active:scale-95"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>'
          + '</div></div></div>';
      }).join('');

      refreshIcons();
    }

    // Unduh CSV daftar tamu yang sedang tampil (sesudah cari + filter).
    // Logika dari versi lama: kutip ganda, pemisah ';' (Excel Indonesia), BOM UTF-8.
    function downloadGuestCsv() {
      var rows = getFilteredGuests();
      if (!rows.length) { showToast('Tidak ada tamu untuk diunduh.'); return; }
      var escCsv = function (v) { return '"' + String(v == null ? '' : v).replace(/"/g, '""') + '"'; };
      var data = [['Nama', 'No WA', 'Kategori', 'Link Undangan', 'Status']];
      rows.forEach(function (g) {
        data.push([g.nama, g.no_hp ? '+' + g.no_hp : '', g.category || '', g.link || guestLink(g), g.wa_sent_at ? 'Sudah Dikirim' : 'Belum Dikirim']);
      });
      var csv = '\uFEFF' + data.map(function (r) { return r.map(escCsv).join(';'); }).join('\r\n');
      var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
      var a = document.createElement('a');
      a.href = URL.createObjectURL(blob);
      a.download = 'link-tamu-' + invSlug + '.csv';
      a.click();
      URL.revokeObjectURL(a.href);
      showToast('CSV diunduh (' + rows.length + ' tamu).');
    }

    function syncBulkWa(slug, wa) {
      var r = bulkResults.find(function (x) { return x.slug === slug; });
      if (r) r.wa_sent_at = wa;
    }

    // Render Panel Hasil Massal (kartu per tamu, gaya prototipe)
    function renderBulkResults() {
      var container = document.getElementById('bulkResultList');
      if (!container) return;
      container.innerHTML = bulkResults.map(function (r, i) {
        var link = r.link || guestLink(r);
        return '<div class="p-3.5 rounded-2xl bg-ivory dark:bg-charcoal border border-[var(--gen-border)] dark:border-charcoal-border">'
          + '<div class="flex items-start justify-between gap-2 mb-2">'
          + '<div class="min-w-0"><p class="text-xs font-bold text-espresso dark:text-white truncate">' + escHtml(r.nama) + '</p>'
          + '<p class="text-[10px] text-espresso/55 dark:text-white/50 font-mono">' + (r.no_hp ? '+' + escHtml(r.no_hp) : 'Tanpa nomor WA') + '</p>'
          + (r.category ? '<span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[color-mix(in_srgb,var(--gen-accent)_15%,transparent)] text-gold-dark dark:text-gold"><i data-lucide="tag" class="w-3 h-3"></i>' + escHtml(r.category) + '</span>' : '') + '</div>'
          + (r.existed
            ? '<span class="shrink-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-600/15 text-amber-800 dark:text-amber-300">SUDAH ADA</span>'
            : '<span class="shrink-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-600/15 text-emerald-700 dark:text-emerald-400">BARU</span>')
          + '</div>'
          + '<div class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl bg-card dark:bg-charcoal-card border border-[var(--gen-border)] dark:border-charcoal-border mb-2.5">'
          + '<i data-lucide="link-2" class="w-3.5 h-3.5 text-gold-dark dark:text-gold shrink-0"></i>'
          + '<input type="text" readonly value="' + escHtml(link) + '" onclick="this.select()" class="w-full bg-transparent text-[11px] font-mono text-espresso/80 dark:text-white/80 focus:outline-none truncate" />'
          + '</div>'
          + '<div class="flex items-center justify-between gap-1.5">'
          + '<div>' + waBadgeHtml(r) + '</div>'
          + '<div class="flex items-center gap-1">'
          + '<button onclick="copyBulkLink(' + i + ')" title="Salin link" class="p-2 rounded-xl border border-[var(--gen-border)] dark:border-charcoal-border bg-card dark:bg-charcoal-card hover:bg-[color-mix(in_srgb,var(--gen-accent)_15%,transparent)] text-espresso dark:text-white transition active:scale-95"><i data-lucide="copy" class="w-3.5 h-3.5 text-gold-dark dark:text-gold"></i></button>'
          + '<button onclick="sendBulkWA(' + i + ')" title="Kirim WA" class="p-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition active:scale-95"><i data-lucide="send" class="w-3.5 h-3.5"></i></button>'
          + '</div></div></div>';
      }).join('');
      refreshIcons();
    }

    function copyBulkLink(i) {
      if (!bulkResults[i]) return;
      copyTextWithToast(bulkResults[i].link || guestLink(bulkResults[i]), 'Link tersalin.');
    }

    async function sendBulkWA(i) {
      var r = bulkResults[i];
      if (!r) return;
      window.open(bulkWaUrl(r.nama, r.link || guestLink(r), r.no_hp || ''), '_blank', 'noopener,noreferrer');
      var res = await markWa(r.slug, true);
      if (res.ok) {
        r.wa_sent_at = res.wa_sent_at;
        syncGuestWa(r.slug, res.wa_sent_at);
        renderBulkResults();
        refreshUI();
      } else {
        showToast(res.message);
      }
    }

    function copyAllBulkLinks() {
      if (!bulkResults.length) return;
      copyTextWithToast(bulkResults.map(function (r) { return r.link || guestLink(r); }).join('\n'), 'Semua link tersalin (' + bulkResults.length + ' link).');
    }

    // Unduh CSV hasil massal saja (kolom versi lama: Nama;Link;Status).
    function downloadBulkCsv() {
      if (!bulkResults.length) { showToast('Tidak ada hasil massal untuk diunduh.'); return; }
      var escCsv = function (v) { return '"' + String(v == null ? '' : v).replace(/"/g, '""') + '"'; };
      var rows = [['Nama', 'Kategori', 'Link', 'Status']];
      bulkResults.forEach(function (r) {
        rows.push([r.nama, r.category || '', r.link || guestLink(r), r.existed ? 'Sudah ada' : 'Baru']);
      });
      var csv = '\uFEFF' + rows.map(function (r) { return r.map(escCsv).join(';'); }).join('\r\n');
      var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
      var a = document.createElement('a');
      a.href = URL.createObjectURL(blob);
      a.download = 'link-tamu-' + invSlug + '.csv';
      a.click();
      URL.revokeObjectURL(a.href);
      showToast('CSV hasil massal diunduh (' + bulkResults.length + ' tamu).');
    }

    // Salin Link ke Clipboard (clipboard API, fallback execCommand)
    function copyTextFallback(text, okMsg) {
      var tempInput = document.createElement('input');
      tempInput.value = text;
      document.body.appendChild(tempInput);
      tempInput.select();
      try { document.execCommand('copy'); showToast(okMsg); } catch (e) { showToast('Gagal menyalin, salin manual dari kolom.'); }
      document.body.removeChild(tempInput);
    }
    function copyTextWithToast(text, okMsg) {
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function () { showToast(okMsg); }).catch(function () { copyTextFallback(text, okMsg); });
      } else {
        copyTextFallback(text, okMsg);
      }
    }

    function copyGuestLink(id) {
      var guest = generatorGuests.find(function (g) { return g.id === id; });
      if (!guest) return;
      copyTextWithToast(guest.link, 'Link undangan "' + guest.nama + '" tersalin!');
    }

    function bulkWaUrl(name, link, phone) {
      var waUrl = 'https://wa.me/';
      var cleanPhone = normalizePhone(phone);
      if (cleanPhone) waUrl += cleanPhone;
      waUrl += '?text=' + encodeURIComponent(buildWaMessage(name, link));
      return waUrl;
    }

    // Tandai WA terkirim / batalkan tanda. Mengembalikan {ok, wa_sent_at, message}.
    async function markWa(slug, sent) {
      try {
        var res = await fetch(API.mark, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ invitation_id: invitationId, slug: slug, sent: sent })
        });
        var data = await res.json();
        if (!data.success) return { ok: false, wa_sent_at: null, message: data.message || 'Gagal menandai.' };
        return { ok: true, wa_sent_at: data.wa_sent_at, message: '' };
      } catch (e) {
        return { ok: false, wa_sent_at: null, message: 'Terjadi kesalahan sistem.' };
      }
    }

    function syncGuestWa(slug, wa) {
      var orig = generatorGuests.find(function (g) { return g.slug === slug; });
      if (orig) orig.wa_sent_at = wa;
    }

    // Kirim WA & Otomatis Tandai "Sudah Dikirim"
    async function sendWhatsAppGuest(id) {
      var guest = generatorGuests.find(function (g) { return g.id === id; });
      if (!guest) return;
      window.open(bulkWaUrl(guest.nama, guest.link, guest.no_hp || ''), '_blank', 'noopener,noreferrer');
      var r = await markWa(guest.slug, true);
      if (r.ok) {
        guest.wa_sent_at = r.wa_sent_at;
        syncGuestWa(guest.slug, r.wa_sent_at);
        syncBulkWa(guest.slug, r.wa_sent_at);
        renderBulkResults();
        refreshUI();
      } else {
        showToast(r.message);
      }
      showToast('Membuka WhatsApp untuk "' + guest.nama + '"');
    }

    // Toggle Manual Status Sudah Dikirim
    async function toggleSentStatus(id) {
      var guest = generatorGuests.find(function (g) { return g.id === id; });
      if (!guest) return;
      var r = await markWa(guest.slug, !guest.wa_sent_at);
      if (!r.ok) { showToast(r.message); return; }
      guest.wa_sent_at = r.wa_sent_at;
      syncGuestWa(guest.slug, r.wa_sent_at);
      syncBulkWa(guest.slug, r.wa_sent_at);
      renderBulkResults();
      refreshUI();
      showToast(guest.wa_sent_at ? '"' + guest.nama + '" ditandai sudah dikirim' : 'Status kirim "' + guest.nama + '" dibatalkan');
    }

    // Edit Nama Tamu Inline (slug & link SENGAJA tidak berubah supaya link yang
    // sudah terkirim tidak rusak — server menolak nama dobel).
    function startEditGuest(id) {
      editingGuestId = id;
      renderGuestCards();
      var inputEl = document.getElementById('editInput-' + id);
      if (inputEl) {
        inputEl.focus();
        inputEl.select();
      }
    }

    function cancelEditGuest() {
      editingGuestId = null;
      renderGuestCards();
    }

    async function saveEditGuest(id) {
      var inputEl = document.getElementById('editInput-' + id);
      var newName = (inputEl ? inputEl.value : '').trim();
      if (newName.length < 2) {
        showToast('Nama tamu terlalu pendek');
        return;
      }
      var catEl = document.getElementById('editCategory-' + id);
      var newCategory = (catEl ? catEl.value : '').trim().slice(0, 50);
      var guest = generatorGuests.find(function (g) { return g.id === id; });
      if (!guest) return;
      try {
        var res = await fetch(API.update, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ invitation_id: invitationId, slug: guest.slug, nama: newName, category: newCategory })
        });
        var data = await res.json();
        if (!data.success) {
          showToast(data.message || 'Gagal menyimpan.');
          return;
        }
        guest.nama = newName;
        guest.category = newCategory;
        var bulk = bulkResults.find(function (r) { return r.slug === guest.slug; });
        if (bulk) { bulk.nama = newName; bulk.category = newCategory; renderBulkResults(); }
        if (currentSingleGuest && currentSingleGuest.slug === guest.slug) {
          currentSingleGuest.nama = newName;
          document.getElementById('singleResultMessage').value = buildWaMessage(newName, currentSingleGuest.link);
        }
        editingGuestId = null;
        refreshUI();
        showSwalNotif('Nama & tautan tamu diperbarui');
      } catch (e) {
        showToast('Terjadi kesalahan sistem.');
      }
    }

    // Modal Konfirmasi Hapus Tamu
    function promptDeleteGuest(id) {
      var guest = generatorGuests.find(function (g) { return g.id === id; });
      if (!guest) return;
      pendingDeleteId = id;
      document.getElementById('deleteConfirmText').textContent = 'Hapus "' + guest.nama + '" dan tautan undangannya dari daftar?';
      document.getElementById('deleteConfirmModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
      pendingDeleteId = null;
      document.getElementById('deleteConfirmModal').classList.add('hidden');
    }

    async function confirmDeleteGuest() {
      if (pendingDeleteId === null) { closeDeleteModal(); return; }
      var guest = generatorGuests.find(function (g) { return g.id === pendingDeleteId; });
      if (!guest) { closeDeleteModal(); return; }
      try {
        var res = await fetch(API.remove, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ invitation_id: invitationId, slug: guest.slug })
        });
        var data = await res.json();
        if (!data.success) {
          showToast(data.message || 'Gagal menghapus.');
          closeDeleteModal();
          return;
        }
        generatorGuests = generatorGuests.filter(function (x) { return x.slug !== guest.slug; });
        bulkResults = bulkResults.filter(function (r) { return r.slug !== guest.slug; });
        renderBulkResults();
        if (currentSingleGuest && currentSingleGuest.slug === guest.slug) {
          currentSingleGuest = null;
          document.getElementById('singleResultPanel').classList.add('hidden');
        }
        if (editingGuestId === guest.id) editingGuestId = null;
        refreshUI();
        showSwalNotif('Tamu "' + guest.nama + '" berhasil dihapus');
      } catch (e) {
        showToast('Terjadi kesalahan sistem.');
      }
      closeDeleteModal();
    }

    // Notifikasi sukses SweetAlert (edit/hapus tamu): popup otomatis tutup
    // 2 detik, tanpa tombol. Bila Swal gagal dimuat (offline), jatuh ke toast.
    function showSwalNotif(msg) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({ text: msg, icon: 'success', timer: 2000, showConfirmButton: false, customClass: { popup: 'rounded-2xl' } });
      } else {
        showToast(msg);
      }
    }

    // Toast Notification Helper
    function showToast(msg) {
      var toast = document.getElementById('toastMessage');
      toast.textContent = msg;
      toast.classList.remove('hidden');
      clearTimeout(window.toastTimer);
      window.toastTimer = setTimeout(function () {
        toast.classList.add('hidden');
      }, 2600);
    }

    refreshIcons();
    initTemplateUI();
    loadGeneratorGuests();
  </script>
</body>
</html>
