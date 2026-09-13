<?php
require_once 'api/db.php';
$invSlug = $_GET['inv'] ?? 'elvy-rokim';
$guestSlug = $_GET['to'] ?? '';

try {
  $db = getDB();

  // 1. Get Invitation
  $stmt = $db->prepare("SELECT id, title FROM invitations WHERE slug = ?");
  $stmt->execute([$invSlug]);
  $invitation = $stmt->fetch();

  if (!$invitation) {
    die("<div style='text-align:center; padding:50px; font-family:sans-serif;'><h2>Undangan Tidak Ditemukan</h2><p>Pastikan URL yang Anda masukkan benar.</p></div>");
  }

  $inv_id = $invitation['id'];

  // 2. Get Settings
  $stmt = $db->prepare("SELECT * FROM settings WHERE invitation_id = ?");
  $stmt->execute([$inv_id]);
  $settingsRaw = $stmt->fetchAll();
  $settings = [];
  foreach ($settingsRaw as $s) {
    $settings[$s['key_name']] = $s['key_value'];
  }

  // 3. Get Guest Name
  $guestName = "Bapak/Ibu/Saudara/i";
  if ($guestSlug) {
    $stmt = $db->prepare("SELECT nama FROM guests WHERE invitation_id = ? AND (slug = ? OR slug = ?)");
    $stmt->execute([$inv_id, $guestSlug, urlencode($guestSlug)]);
    $g = $stmt->fetch();
    if ($g)
      $guestName = $g['nama'];
    else
      $guestName = htmlspecialchars(str_replace(['+', '-'], ' ', $guestSlug));
  }

  // 4. Get Gallery (ASC so newest photos at bottom)
  $stmt = $db->prepare("SELECT image_path FROM gallery WHERE invitation_id = ? ORDER BY created_at ASC");
  $stmt->execute([$inv_id]);
  $gallery = $stmt->fetchAll(PDO::FETCH_COLUMN);

  // 5. Get Stories (Kisah Kasih)
  $stmt = $db->prepare("SELECT * FROM stories WHERE invitation_id = ? ORDER BY id ASC");
  $stmt->execute([$inv_id]);
  $stories = $stmt->fetchAll();

} catch (PDOException $e) {
  die("Koneksi bermasalah.");
}

function getSet($key, $default = '')
{
  global $settings;
  if (!isset($settings[$key]) || trim($settings[$key]) === '') {
    return $default;
  }
  return htmlspecialchars($settings[$key]);
}

function formatIndoDate($dateStr)
{
  if (!$dateStr)
    return '';
  $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  $timestamp = strtotime($dateStr);
  if (!$timestamp)
    return $dateStr;
  $day = $days[date('w', $timestamp)];
  $d = date('j', $timestamp);
  $m = $months[date('n', $timestamp)];
  $y = date('Y', $timestamp);
  return "$day, $d $m $y";
}

function hexToRgb($hex) {
  $hex = str_replace("#", "", $hex);
  if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
  } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
  }
  return "$r, $g, $b";
}

$theme_primary = getSet('theme_primary', '#361f1a');
$theme_secondary = getSet('theme_secondary', '#775a19');
$theme_background = getSet('theme_background', '#fbf9f5');
$theme_primary_container = getSet('theme_primary_container', '#4e342e');
$theme_secondary_container = getSet('theme_secondary_container', '#fed488');
$theme_surface_container_low = getSet('theme_surface_container_low', '#f5f3ef');
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Undangan Pernikahan —
    <?php echo (getSet('bride_nickname') ?: getSet('bride_name', 'Elvy')) . ' & ' . (getSet('groom_nickname') ?: getSet('groom_name', 'Rokim')); ?>
  </title>
  <meta name="description"
    content="Undangan pernikahan digital <?php echo getSet('bride_name', 'Elvy Nur Fauziyah') . ' & ' . getSet('groom_name', 'Mukamat Abdul Rokim'); ?>. Kayon Semat, Manunggaling Katresnan." />

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link
    href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;500;600;700&family=Work+Sans:wght@400;500;600&display=swap"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet" />
  <link href="styles.css" rel="stylesheet" />

  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "on-error": "#ffffff", "surface-container-lowest": "#ffffff", "error-container": "#ffdad6",
            "primary-fixed": "#ffdad2", "on-tertiary": "#ffffff", "on-secondary-fixed": "#261900",
            "on-tertiary-fixed": "#2b160f", "secondary": "<?php echo $theme_secondary; ?>", "on-tertiary-container": "#c09d91",
            "primary-container": "<?php echo $theme_primary_container; ?>", "outline": "#827471", "on-secondary-container": "<?php echo $theme_secondary; ?>",
            "surface-container-high": "#eae8e4", "surface-variant": "#e4e2de", "inverse-primary": "#e5beb5",
            "tertiary-container": "#4e352c", "surface": "<?php echo $theme_background; ?>", "background": "<?php echo $theme_background; ?>",
            "on-primary-fixed-variant": "#5c403a", "on-background": "#1b1c1a",
            "on-primary-container": "#c19c94", "on-secondary": "#ffffff", "surface-container": "#efeeea",
            "primary": "<?php echo $theme_primary; ?>", "on-error-container": "#93000a", "surface-container-highest": "#e4e2de",
            "on-primary": "#ffffff", "on-surface-variant": "#504442", "outline-variant": "#d4c3bf",
            "surface-container-low": "<?php echo $theme_surface_container_low; ?>", "inverse-surface": "#30312e", "surface-bright": "<?php echo $theme_background; ?>",
            "secondary-fixed": "#ffdea5", "secondary-container": "<?php echo $theme_secondary_container; ?>", "secondary-fixed-dim": "#e9c176",
            "on-secondary-fixed-variant": "#5d4201", "surface-dim": "#dbdad6", "surface-tint": "#755750",
            "inverse-on-surface": "#f2f0ed", "on-primary-fixed": "#2b1611", "tertiary-fixed-dim": "#e4beb2",
            "error": "#ba1a1a", "tertiary": "#352017", "on-tertiary-fixed-variant": "#5b4137",
            "on-surface": "#1b1c1a", "primary-fixed-dim": "#e5beb5", "tertiary-fixed": "#ffdbce"
          },
          borderRadius: { DEFAULT: "0.125rem", lg: "0.25rem", xl: "0.5rem", full: "0.75rem" },
          spacing: { "section-gap": "80px", "gutter": "16px", "unit": "8px", "margin-page": "32px" },
          fontFamily: {
            "display-lg": ["Noto Serif"], "title-sm": ["Noto Serif"],
            "label-caps": ["Work Sans"], "headline-md": ["Noto Serif"], "body-md": ["Work Sans"]
          },
          fontSize: {
            "display-lg": ["48px", { lineHeight: "1.2", letterSpacing: "-0.02em", fontWeight: "700" }],
            "title-sm": ["20px", { lineHeight: "1.4", letterSpacing: "0.05em", fontWeight: "500" }],
            "label-caps": ["12px", { lineHeight: "1.0", letterSpacing: "0.2em", fontWeight: "600" }],
            "headline-md": ["32px", { lineHeight: "1.3", fontWeight: "600" }],
            "body-md": ["16px", { lineHeight: "1.6", fontWeight: "400" }]
          }
        }
      }
    };
  </script>
  <style>
    :root {
      --primary-color: <?php echo $theme_primary; ?>;
      --secondary-color: <?php echo $theme_secondary; ?>;
      --bg-color: <?php echo $theme_background; ?>;
    }
    
    /* Override static stylesheet rules with user's theme colors */
    .dot-item.active {
      border-color: var(--secondary-color) !important;
    }
    .dot-item .dot-icon, .dot-item .dot-label {
      color: var(--secondary-color) !important;
    }
    .dot-track {
      border-color: rgba(<?php echo hexToRgb($theme_secondary); ?>, 0.15) !important;
    }
    .header-menu a:hover, .header-menu a.active {
      color: var(--secondary-color) !important;
    }
    .header-menu a.active::after {
      background: var(--secondary-color) !important;
    }
    #music-toggle {
      background: var(--secondary-color) !important;
    }
    #music-toggle .ping-ring {
      border-color: var(--secondary-color) !important;
    }
    .section-divider .divider-line {
      background: var(--secondary-color) !important;
    }
    .countdown-sep {
      color: var(--secondary-color) !important;
    }
    .attendance-btn.selected-hadir {
      background: var(--secondary-color) !important;
      box-shadow: 0 4px 16px rgba(<?php echo hexToRgb($theme_secondary); ?>, 0.3) !important;
    }
    .rsvp-field label {
      color: var(--secondary-color) !important;
    }
    .rsvp-field input:focus, .rsvp-field select:focus {
      border-color: var(--secondary-color) !important;
    }
    #scroll-progress {
      background: linear-gradient(90deg, var(--secondary-color), #D4AF37) !important;
    }
    #cover-gate { display: none !important; }

    #opening-overlay {
      position: fixed;
      inset: 0;
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      background: #0b132b;
      isolation: isolate;
    }
    #opening-overlay .door-left,
    #opening-overlay .door-right {
      position: absolute;
      top: 0;
      width: 50vw;
      height: 100%;
      overflow: hidden;
      background: #0b132b;
      transition: transform 1.2s cubic-bezier(0.77, 0, 0.175, 1);
    }
    #opening-overlay .door-left { left: 0; }
    #opening-overlay .door-right { right: 0; }
    #opening-overlay .door-left img,
    #opening-overlay .door-right img {
      position: absolute;
      top: 0;
      width: 100vw;
      max-width: none;
      height: 100%;
      object-fit: cover;
    }
    #opening-overlay .door-left img { left: 0; }
    #opening-overlay .door-right img { right: 0; }
    #opening-overlay::after {
      content: '';
      position: absolute;
      top: 0;
      left: 50%;
      width: 2px;
      height: 100%;
      background: #f4d58a;
      box-shadow: 0 0 12px #f4d58a, 0 0 28px rgba(244, 213, 138, 0.8);
      transform: translateX(-50%);
      transition: opacity 0.35s ease;
    }
    #opening-overlay .opening-copy {
      position: relative;
      z-index: 2;
      color: #fff;
      text-align: center;
      padding: 2rem 2.25rem;
      background: rgba(11, 19, 43, 0.88);
      border: 1px solid rgba(244, 213, 138, 0.7);
      border-radius: 1rem;
      box-shadow: 0 0 28px rgba(244, 213, 138, 0.18);
    }
    #opening-overlay .opening-copy h2 { color: #f4d58a; }
    #opening-overlay .opening-guest-card { max-width: 24rem; margin-left: auto; margin-right: auto; }
    #opening-overlay .opening-button { background: var(--secondary-color); border: 1px solid #f4d58a; color: #fff; }
    #opening-overlay.opened .door-left { transform: translateX(-100%); }
    #opening-overlay.opened .door-right { transform: translateX(100%); }
    #opening-overlay.opened .opening-copy { opacity: 0; transform: scale(0.82); transition: opacity 0.45s ease, transform 0.65s ease; }
    #opening-overlay.opened::after { opacity: 0; }
    .countdown-number {
      color: var(--primary-color) !important;
    }
    .top-header {
      border-bottom: 1px solid rgba(<?php echo hexToRgb($theme_secondary); ?>, 0.2) !important;
    }
    .top-header h1 {
      color: var(--primary-color) !important;
    }
    .top-header .header-icon {
      color: var(--primary-color) !important;
    }

    /* Background Animations Styling */
    #animation-container {
      position: fixed;
      inset: 0;
      z-index: 1; /* behind content (z-10) but above bg (z-0) */
      pointer-events: none;
      overflow: hidden;
    }
    
    .floating-petal {
      position: absolute;
      top: -40px;
      pointer-events: none;
      opacity: 0;
      animation-iteration-count: 1;
      animation-timing-function: linear;
      animation-fill-mode: forwards;
    }

    @keyframes fall {
      0% {
        top: -40px;
        transform: translateX(0) rotate(0deg);
        opacity: 0;
      }
      10% {
        opacity: 0.8;
      }
      90% {
        opacity: 0.8;
      }
      100% {
        top: 105vh;
        transform: translateX(100px) rotate(360deg);
        opacity: 0;
      }
    }

    /* Silhouette Birds Animation */
    .bird-container {
      position: fixed;
      top: 20%;
      left: -10%;
      z-index: 1;
      pointer-events: none;
      animation: fly-across-1 45s linear infinite;
    }
    
    .bird-container-2 {
      position: fixed;
      top: 30%;
      left: -10%;
      z-index: 1;
      pointer-events: none;
      animation: fly-across-2 38s linear infinite;
      animation-delay: 15s;
    }
    
    .bird {
      width: 42px;
      height: 42px;
      fill: var(--primary-color) !important;
      opacity: 0.08;
      animation: bird-fly 0.7s ease-in-out infinite alternate;
    }
    
    @keyframes bird-fly {
      0% {
        transform: translateY(0) scaleY(0.4);
      }
      100% {
        transform: translateY(-8px) scaleY(1);
      }
    }
    
    @keyframes fly-across-1 {
      0% {
        left: -10%;
        top: 20%;
        transform: scale(0.5);
      }
      50% {
        top: 10%;
      }
      100% {
        left: 110%;
        top: 15%;
        transform: scale(0.5);
      }
    }
    
    @keyframes fly-across-2 {
      0% {
        left: -10%;
        top: 30%;
        transform: scale(0.4);
      }
      50% {
        top: 22%;
      }
      100% {
        left: 110%;
        top: 26%;
        transform: scale(0.4);
      }
    }

    .attendance-btn {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .attendance-btn.selected-hadir {
      background: #775a19;
      color: #fff;
      box-shadow: 0 4px 16px rgba(119, 90, 25, 0.3);
      transform: translateY(-2px);
    }

    .attendance-btn.selected-tidak {
      background: #4e342e;
      color: #fff;
      box-shadow: 0 4px 16px rgba(78, 52, 46, 0.3);
      transform: translateY(-2px);
    }

    .message-card {
      animation: slideInMessage 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes slideInMessage {
      from {
        opacity: 0;
        transform: translateX(-16px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .copy-success {
      animation: flashGreen 0.6s ease;
    }

    @keyframes flashGreen {

      0%,
      100% {
        color: inherit;
      }

      50% {
        color: #22c55e;
      }
    }

    .rsvp-field {
      position: relative;
    }

    .rsvp-field label {
      position: absolute;
      top: 0.75rem;
      left: 0;
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: #775a19;
      transition: all 0.2s;
      pointer-events: none;
    }

    .rsvp-field input,
    .rsvp-field select {
      width: 100%;
      background: transparent;
      border: 0;
      border-bottom: 1px solid #d4c3bf;
      padding: 1.6rem 0 0.5rem;
      font-size: 1rem;
      outline: none;
      transition: border-color 0.2s;
    }

    .rsvp-field input:focus,
    .rsvp-field select:focus {
      border-color: #775a19;
    }

    select {
      appearance: none;
      cursor: pointer;
    }
  </style>
</head>

<body class="bg-background text-on-surface font-body-md no-scroll selection:bg-secondary-container">

  <div id="opening-overlay" aria-label="Pembuka undangan">
    <div class="door-left" aria-hidden="true"><img src="../public/gunungan_utuh.png" alt=""></div>
    <div class="door-right" aria-hidden="true"><img src="../public/gunungan_utuh.png" alt=""></div>
    <div class="opening-copy">
      <span class="font-label-caps text-xs uppercase tracking-[0.3em]">THE WEDDING OF</span>
      <h2 class="font-display-lg mt-4 text-4xl"><?php echo (getSet('bride_nickname') ?: getSet('bride_name', 'Elvy')) . ' & ' . (getSet('groom_nickname') ?: getSet('groom_name', 'Rokim')); ?></h2>
      <div style="width: 4rem; height: 1px; margin: 1.5rem auto 0; background: #f4d58a;"></div>
      <div class="opening-guest-card mt-8">
        <div style="width: 3rem; height: 3rem; margin: 0 auto 1.25rem; display: flex; align-items: center; justify-content: center; border-radius: 999px; background: var(--secondary-color);">
          <span class="material-symbols-outlined" style="color: #fff;">temple_hindu</span>
        </div>
        <p class="font-label-caps text-xs uppercase tracking-wider opacity-80">Kepada Yth. Bapak/Ibu/Saudara/i</p>
        <h3 class="mt-4 text-2xl font-semibold"><?php echo $guestName; ?></h3>
        <button id="btn-buka" type="button" class="opening-button mt-7 inline-flex items-center gap-3 rounded-full px-7 py-3.5 text-xs font-bold uppercase tracking-[0.18em] shadow-lg">
          <span>Buka Undangan</span><span class="material-symbols-outlined text-base">arrow_forward</span>
        </button>
        <p style="margin-top: 1.25rem; font-size: 0.75rem; opacity: 0.7;">Mohon maaf apabila ada kesalahan pada penulisan nama/gelar</p>
      </div>
    </div>
  </div>

  <!-- COVER GATE (legacy markup hidden; kept for compatibility) -->
  <div id="cover-gate" class="flex flex-col items-center justify-center px-margin-page text-center">

    <div class="absolute inset-0 z-0 opacity-20">
      <img alt="Javanese Temple Gate" class="w-full h-full object-cover object-center grayscale sepia"
        src="https://lh3.googleusercontent.com/aida-public/AB6AXuDBxGC-GARZVHxmy-x08BZSe-wyKb_parej4avdLcdNa_yCBrpqEGcNbSzPVi5Mvw9dj-XNM2O9iMtYmKkAr8LOlBTXDrAgMPmDgA725AnOttue9JKsqw32-i90Qd7zh1kY9yYSkNfXli-SbtgI0bcOHO1Ib5t34CcpIFqh2NMkGDcZBhYEFRNh6x2vBjANsKLYkAnOrFcr-4ZEIVYixABPqPcrT12Yjgeyg8nJMkcmtpX4Sr5Geub1PXgCcPwUQ0ZuYfychb0LoFpS" />
    </div>

    <div class="relative z-10 space-y-unit max-w-md ">
      <span class="font-label-caps text-label-caps text-secondary uppercase tracking-[0.3em]">
        The Wedding of
      </span>

      <h2 class="font-display-lg text-display-lg text-primary mt-4 mb-2">
        <?php echo (getSet('bride_nickname') ?: getSet('bride_name', 'Elvy')) . ' & ' . (getSet('groom_nickname') ?: getSet('groom_name', 'Rokim')); ?>
      </h2>

      <div class="w-16 h-px bg-secondary mx-auto my-6"></div>

      <p class="font-title-sm text-title-sm text-on-surface-variant italic">
        "Penyatuan dua hati dalam satu cinta yang abadi"
      </p>
    </div>

    <!-- Invitation Card -->
    <div
      class="mt-section-gap relative z-10 w-full max-w-sm bg-surface/80 backdrop-blur-sm p-8 rounded-xl shadow-[0_20px_50px_rgba(78,52,46,0.15)] border border-outline-variant/30 card-3d float-3d">

      <div
        class="absolute -top-6 left-1/2 -ml-6 w-12 h-12 bg-secondary flex items-center justify-center gunungan-shape shadow-lg pulse-3d">
        <span class="material-symbols-outlined text-white text-xl">temple_hindu</span>
      </div>

      <p class="font-label-caps text-label-caps text-on-surface-variant mb-4">
        Kepada Yth. Bapak/Ibu/Saudara/i
      </p>

      <h3
        class="font-headline-md text-[24px] text-primary mb-8 underline decoration-secondary/30 underline-offset-8 text-3d">
        <?php echo $guestName; ?>
      </h3>

      <button id="legacy-btn-buka"
        class="w-full py-4 bg-secondary text-surface-container-lowest font-label-caps tracking-widest rounded-lg flex items-center justify-center gap-3 shadow-md hover:bg-primary transition-all active:scale-95 border-b-2 border-primary-container btn-3d">
        BUKA UNDANGAN
        <span class="material-symbols-outlined text-[18px]">drafts</span>
      </button>

      <p class="font-body-md text-[12px] text-on-surface-variant mt-6 opacity-70">
        Mohon maaf apabila ada kesalahan pada penulisan nama/gelar
      </p>
    </div>
  </div>

  <header class="top-header">
    <div class="top-header-inner">
      <div class="header-left">
        <button onclick="backToHome()"
          class="flex items-center justify-center cursor-pointer hover:scale-110 transition-transform duration-200"
          title="Kembali ke Awal">
          <span class="material-symbols-outlined header-icon">home</span>
        </button>
      </div>
      <div class="header-center">
        <h1>
          <?php echo (getSet('bride_nickname') ?: getSet('bride_name', 'Elvy')) . ' & ' . (getSet('groom_nickname') ?: getSet('groom_name', 'Rokim')); ?>
        </h1>
      </div>
      <div class="header-right">
        <button id="music-toggle" aria-label="Toggle Musik">
          <span class="material-symbols-outlined">music_note</span>
          <div class="ping-ring"></div>
        </button>
      </div>
    </div>
  </header>

  <!-- DOT NAV -->
  <nav id="dot-nav"></nav>

  <!-- SCROLL PROGRESS -->
  <div id="scroll-progress-container" class="fixed top-0 left-0 w-full h-1 z-50">
    <div id="scroll-progress" class="h-full bg-secondary w-0 transition-all duration-100 ease-out"></div>
  </div>

  <!-- MAIN CONTENT -->
  <main class="min-h-screen relative overflow-hidden pb-32">
    <!-- Background Texture -->
    <div class="fixed inset-0 main-bg-overlay pointer-events-none z-0"></div>

    <!-- Background Animations -->
    <div id="animation-container"></div>
    <div class="bird-container">
      <svg class="bird" viewBox="0 0 100 100">
        <path d="M10,30 Q30,10 50,30 Q70,10 90,30 Q50,45 10,30 Z"/>
      </svg>
    </div>
    <div class="bird-container-2">
      <svg class="bird" viewBox="0 0 100 100">
        <path d="M10,30 Q30,10 50,30 Q70,10 90,30 Q50,45 10,30 Z"/>
      </svg>
    </div>

    <!-- Central Content Wrapper (Fix for messy layout) -->
    <div class="relative z-10 pt-16 max-w-md mx-auto">

      <section id="mempelai" class="px-margin-page mb-section-gap relative z-10 pt-24">

        <div class="text-center mb-12 reveal">
          <span class="font-label-caps text-label-caps text-secondary mb-2 block">MEMPELAI</span>
          <h2 class="font-display-lg text-headline-md text-primary text-3d">Pasangan Berbahagia</h2>
        </div>

        <div class="space-y-16">

          <!-- Bride -->
          <div class="relative group reveal">
            <div
              class="aspect-[4/5] overflow-hidden rounded-xl border-[8px] border-surface-container-high shadow-lg relative gallery-3d">
              <img alt="Bride Portrait" class="w-full h-full object-cover"
                src="<?php echo getSet('bride_photo', 'cewek.jpeg'); ?>" />
              <div class="absolute inset-0 border-[1px] border-[#D4AF37]/30 pointer-events-none m-2"></div>
            </div>

            <div class="mt-6 text-center">
              <h3 class="font-display-lg text-title-sm text-primary mb-2 text-3d">
                <?php echo getSet('bride_name', 'Elvy Nur Fauziyah'); ?>
              </h3>
              <p class="font-body-md text-on-surface-variant italic mb-1">
                <?php echo getSet('bride_child_of', 'Putri Pertama dari'); ?>
              </p>
              <p class="font-title-sm text-body-md font-semibold">
                <?php echo getSet('bride_parents', 'Bapak Novi Harianto & Ibu Siti Masfufahh'); ?>
              </p>
            </div>


            <div class="absolute -top-4 -right-4 w-20 h-20 opacity-20 text-secondary float-3d">
              <span class="material-symbols-outlined text-6xl">settings_heart</span>
            </div>
          </div>

          <!-- Heart Separator -->
          <div class="flex justify-center py-4 reveal-scale">
            <span class="material-symbols-outlined text-secondary text-4xl"
              style="font-variation-settings: 'FILL' 1;">favorite</span>
          </div>

          <!-- Groom -->
          <div class="relative group reveal">
            <div
              class="aspect-[4/5] overflow-hidden rounded-xl border-[8px] border-surface-container-high shadow-lg relative gallery-3d">
              <img alt="Groom Portrait" class="w-full h-full object-cover"
                src="<?php echo getSet('groom_photo', 'cowok.jpeg'); ?>" />
              <div class="absolute inset-0 border-[1px] border-[#D4AF37]/30 pointer-events-none m-2"></div>
            </div>
            <div class="mt-6 text-center">
              <h3 class="font-display-lg text-title-sm text-primary mb-2 text-3d">
                <?php echo getSet('groom_name', 'Mukamat Abdul Rokim'); ?>
              </h3>
              <p class="font-body-md text-on-surface-variant italic mb-1">
                <?php echo getSet('groom_child_of', 'Putra Kedua dari'); ?>
              </p>
              <p class="font-title-sm text-body-md font-semibold">
                <?php echo getSet('groom_parents', 'Bapak Masngat & Ibu Galisah'); ?>
              </p>
            </div>
            <div class="absolute -bottom-4 -left-4 w-20 h-20 opacity-20 text-secondary rotate-180 float-3d">
              <span class="material-symbols-outlined text-6xl">settings_heart</span>
            </div>
          </div>

        </div>
      </section>
      <section class="py-12 reveal" id="gallery">
        <div class="text-center mb-10">
          <span class="font-label-caps text-label-caps text-secondary mb-2 block">MEMORABILIA</span>
          <h2 class="font-headline-md text-headline-md text-primary text-3d">Galeri Prewedding</h2>
          <div class="w-16 h-[2px] bg-secondary-container mx-auto mt-4"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 reveal-stagger perspective-container">
          <?php if (!empty($gallery)): ?>
            <?php foreach ($gallery as $index => $img): ?>
              <?php
              $isLarge = ($index === 0); // First image is large
              $class = $isLarge ? "col-span-2 md:col-span-2 md:row-span-2 aspect-[4/5]" : "col-span-1 aspect-square";
              ?>
              <div
                class="<?php echo $class; ?> bg-surface-container overflow-hidden rounded-xl cursor-pointer group relative gallery-3d"
                onclick="openLightbox('<?php echo $img; ?>')">
                <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                  alt="Gallery image" src="<?php echo $img; ?>" />
                <div
                  class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                  <span class="material-symbols-outlined text-white text-4xl drop-shadow-md">zoom_in</span>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <!-- Default Placeholders if empty -->
            <div
              class="col-span-2 md:col-span-2 md:row-span-2 aspect-[4/5] bg-surface-container overflow-hidden rounded-xl gallery-3d">
              <img src="adat.jpeg" class="w-full h-full object-cover opacity-50 grayscale" alt="Placeholder">
            </div>
            <div class="col-span-1 aspect-square bg-surface-container overflow-hidden rounded-xl gallery-3d">
              <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc"
                class="w-full h-full object-cover opacity-50 grayscale" alt="Placeholder">
            </div>
            <div class="col-span-1 aspect-square bg-surface-container overflow-hidden rounded-xl gallery-3d">
              <img src="https://images.unsplash.com/photo-1519741497674-611481863552"
                class="w-full h-full object-cover opacity-50 grayscale" alt="Placeholder">
            </div>
          <?php endif; ?>
        </div>
      </section>      <section class="px-margin-page mb-section-gap relative z-10 reveal" id="kisah">

        <div class="bg-surface-container-low p-8 rounded-xl border border-[#D4AF37]/20 relative">

          <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-background px-4">
            <span class="material-symbols-outlined text-secondary text-3xl">auto_stories</span>
          </div>

          <div class="text-center mb-8 pt-4">
            <span class="font-label-caps text-label-caps text-secondary mb-2 block">JOURNEY</span>
            <h2 class="font-display-lg text-headline-md text-primary">Kisah Kasih</h2>
          </div>

          <div class="space-y-12 reveal-stagger">

            <?php if (!empty($stories)): ?>
              <?php foreach ($stories as $index => $story): ?>
                <?php
                $isLast = ($index === count($stories) - 1);
                $isNumeric = is_numeric(trim($story['tahun']));
                $bgClass = $isLast ? 'bg-primary-container text-on-primary-container shadow-md' : 'bg-secondary-container text-on-secondary-container shadow-inner';
                $borderClass = $isLast ? '' : 'border-b border-outline-variant pb-6';
                ?>
                <div class="flex gap-6 items-start">
                  <div class="flex-none w-16 h-16 rounded-full <?php echo $bgClass; ?> flex items-center justify-center">
                    <?php if ($isNumeric): ?>
                      <span class="font-display-lg text-title-sm"><?php echo htmlspecialchars($story['tahun']); ?></span>
                    <?php else: ?>
                      <span class="material-symbols-outlined"><?php echo htmlspecialchars(trim($story['tahun'])); ?></span>
                    <?php endif; ?>
                  </div>
                  <div class="flex-1 <?php echo $borderClass; ?>">
                    <h4 class="font-title-sm text-primary mb-1"><?php echo htmlspecialchars($story['judul']); ?></h4>
                    <p class="font-body-md text-on-surface-variant leading-relaxed">
                      <?php echo nl2br(htmlspecialchars($story['isi'])); ?>
                    </p>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-center text-on-surface-variant text-sm italic py-4">Kisah kasih belum diatur.</p>
            <?php endif; ?>

          </div>

        </div>
      </section>

      <!-- QUOTES SECTION -->
      <section id="quotes" class="py-12 reveal">
        <div class="text-center mb-10">
          <span class="font-label-caps text-label-caps text-secondary mb-2 block">QUOTES</span>
        </div>
        <div class="mt-4 text-center">
          <div class="inline-block p-6 border border-[#D4AF37]/30 rounded-xl bg-surface shadow-sm max-w-sm mx-auto">
            <p class="font-serif text-lg leading-relaxed text-secondary mb-3" dir="rtl" lang="ar">
              وَمِنْ آيَاتِهِ أَنْ خَلَقَ لَكُم مِّنْ أَنفُسِكُمْ أَزْوَاجًا لِّتَسْكُنُوا إِلَيْهَا وَجَعَلَ
              بَيْنَكُم
              مَّوَدَّةً وَرَحْمَةً
            </p>
            <p class="font-body-md text-sm italic text-on-surface-variant leading-relaxed">
              "Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu pasangan hidup dari jenismu
              sendiri
              supaya kamu cenderung dan merasa tenteram kepadanya, dan dijadikan-Nya di antaramu rasa kasih dan
              sayang."
            </p>
            <p class="font-label-caps text-[10px] text-secondary mt-3 tracking-widest">
              QS. AR-RUM : 21
            </p>
          </div>
        </div>
      </section>

      <section id="acara" class="px-margin-page pt-28 pb-8 text-center reveal">
        <div class="inline-block px-4 py-1 border-b border-secondary/30 mb-4">
          <p class="font-label-caps text-label-caps text-secondary uppercase">The Holy Union</p>
        </div>
        <h2 class="font-display-lg text-display-lg text-primary mb-4">Detail Acara</h2>
        <div class="flex justify-center items-center gap-4 opacity-30">
          <div class="h-[1px] w-12 bg-secondary"></div>
          <span class="material-symbols-outlined text-secondary">settings_ethernet</span>
          <div class="h-[1px] w-12 bg-secondary"></div>
        </div>
      </section>
      <section class="px-margin-page mb-section-gap reveal-scale">
        <div
          class="bg-surface-container-low rounded-xl p-8 border border-outline-variant/30 text-center relative overflow-hidden card-3d">
          <div class="absolute top-0 left-0 w-full h-full opacity-5 pointer-events-none"
            style="background-image:radial-gradient(circle at 2px 2px,#775a19 1px,transparent 0);background-size:24px 24px;">
          </div>
          <p class="font-label-caps text-label-caps text-secondary mb-6 tracking-[0.3em]">MENGHITUNG HARI</p>
          <div id="countdown" class="countdown-grid"></div>
        </div>
      </section>
      <section class="px-margin-page mb-12 reveal">
        <div class="relative group">
          <div
            class="absolute -inset-1 bg-gradient-to-r from-secondary/10 to-transparent rounded-xl blur opacity-25 group-hover:opacity-50 transition">
          </div>
          <div class="relative bg-surface border border-outline-variant/40 rounded-xl overflow-hidden card-3d">
            <div class="h-2 bg-secondary"></div>
            <div class="p-8">
              <div class="flex items-center gap-3 mb-6">
                <span class="material-symbols-outlined text-secondary text-3xl">auto_awesome</span>
                <h3 class="font-title-sm text-title-sm text-primary uppercase tracking-widest text-3d">Akad Nikah</h3>
              </div>
              <div class="space-y-6 reveal-stagger">
                <div class="flex gap-4">
                  <span class="material-symbols-outlined text-secondary/70">calendar_month</span>
                  <div>
                    <p class="font-label-caps text-label-caps text-secondary mb-1">HARI & TANGGAL</p>
                    <p class="font-title-sm text-on-surface">
                      <?php echo formatIndoDate(getSet('wedding_date', '2026-06-09')); ?>
                    </p>
                  </div>
                </div>
                <div class="flex gap-4">
                  <span class="material-symbols-outlined text-secondary/70">schedule</span>
                  <div>
                    <p class="font-label-caps text-label-caps text-secondary mb-1">WAKTU</p>
                    <p class="font-title-sm text-on-surface"><?php echo getSet('wedding_time_start', '08:00'); ?> —
                      <?php echo getSet('wedding_time_end', '10:00'); ?> WIB
                    </p>
                  </div>
                </div>
                <div class="flex gap-4">
                  <span class="material-symbols-outlined text-secondary/70">location_on</span>
                  <div>
                    <p class="font-label-caps text-label-caps text-secondary mb-1">LOKASI</p>
                    <p class="font-title-sm text-on-surface">
                      <?php echo getSet('wedding_location', 'Kediaman Mempelai Wanita'); ?>
                    </p>
                    <?php if (getSet('wedding_map_link')): ?>
                      <a href="<?php echo getSet('wedding_map_link'); ?>" target="_blank"
                        class="font-body-md text-secondary text-sm mt-1 hover:underline">Buka di Google Maps →</a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="px-margin-page mb-section-gap reveal">
        <div class="relative group">
          <div
            class="absolute -inset-1 bg-gradient-to-r from-secondary/10 to-transparent rounded-xl blur opacity-25 group-hover:opacity-50 transition">
          </div>
          <div class="relative bg-surface border border-outline-variant/40 rounded-xl overflow-hidden card-3d">
            <div class="h-2 bg-secondary"></div>
            <div class="p-8">
              <div class="flex items-center gap-3 mb-6">
                <span class="material-symbols-outlined text-secondary text-3xl">celebration</span>
                <h3 class="font-title-sm text-title-sm text-primary uppercase tracking-widest text-3d">Resepsi</h3>
              </div>
              <div class="space-y-6 reveal-stagger">
                <div class="flex gap-4">
                  <span class="material-symbols-outlined text-secondary/70">calendar_month</span>
                  <div>
                    <p class="font-label-caps text-label-caps text-secondary mb-1">HARI & TANGGAL</p>
                    <p class="font-title-sm text-on-surface">
                      <?php echo formatIndoDate(getSet('reception_date', '2026-06-11')); ?>
                    </p>
                  </div>
                </div>
                <div class="flex gap-4">
                  <span class="material-symbols-outlined text-secondary/70">schedule</span>
                  <div>
                    <p class="font-label-caps text-label-caps text-secondary mb-1">WAKTU</p>
                    <p class="font-title-sm text-on-surface"><?php echo getSet('reception_time_start', '08:00'); ?> —
                      <?php echo getSet('reception_time_end', 'Selesai'); ?>
                    </p>
                  </div>
                </div>
                <div class="flex gap-4">
                  <span class="material-symbols-outlined text-secondary/70">location_on</span>
                  <div>
                    <p class="font-label-caps text-label-caps text-secondary mb-1">LOKASI</p>
                    <p class="font-title-sm text-on-surface">
                      <?php echo getSet('reception_location', 'Kediaman Mempelai Wanita'); ?>
                    </p>
                    <?php if (getSet('reception_map_link')): ?>
                      <a href="<?php echo getSet('reception_map_link'); ?>" target="_blank"
                        class="font-body-md text-secondary text-sm mt-1 hover:underline">Buka di Google Maps →</a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="px-margin-page mb-section-gap reveal">
        <h3 class="font-headline-md text-title-sm text-primary text-center mb-8 uppercase tracking-[0.2em]">Peta Lokasi
        </h3>
        <div
          class="rounded-xl overflow-hidden border border-outline-variant/30 shadow-sm mb-6 aspect-video relative bg-surface-container-low flex flex-col items-center justify-center gap-4">
          <div class="w-24 h-24 flex items-center justify-center">
            <img
              src="https://www.freepnglogos.com/uploads/lokasi-logo-png/lokasi-logo-red-map-location-icon-map-png-0.png"
              alt="Lokasi Icon" class="w-full h-full object-contain" />
          </div>

        </div>
        <?php
        $map_link = getSet('wedding_map_link', 'https://maps.app.goo.gl/GqgJsPwwrGwJ4vLd6');
        ?>
        <a href="<?php echo $map_link; ?>" target="_blank"
          class="w-full flex items-center justify-center gap-3 bg-secondary text-on-secondary py-4 px-8 rounded-full font-label-caps transition active:scale-95 btn-3d">
          <span class="material-symbols-outlined">directions</span> BUKA PETUNJUK ARAH
        </a>
        <p class="text-center mt-6 text-on-surface-variant text-sm px-4">"Barangsiapa yang diundang ke walimah maka
          hendaklah ia menghadirinya." (HR. Muslim)</p>
      </section>
      <section class="px-margin-page pb-12 text-center reveal">
        <div class="opacity-10 mb-8"><span class="material-symbols-outlined text-6xl">yard</span></div>
        <p class="font-serif italic text-primary/80 max-w-xs mx-auto">Dengan penuh hormat, kehadiran Bapak/Ibu/Saudara
          sekalian menjadi berkah
          yang menyempurnakan kebahagiaan kedua mempelai.</p>
      </section>

      <section id="rsvp" class="mb-10 reveal pt-24">

        <!-- Header -->
        <div class="text-center mb-8">
          <span class="font-label-caps text-label-caps text-secondary mb-2 block">RESERVASI</span>
          <h2 class="font-headline-md text-headline-md text-primary">Konfirmasi Kehadiran</h2>
          <p class="font-body-md text-on-surface-variant mt-2 text-sm">Kehadiran Anda adalah kebahagiaan kami.</p>
        </div>

        <!-- Card -->
        <div class="relative rounded-3xl overflow-hidden shadow-xl">
          <!-- Decorative top gradient band -->
          <div class="h-2 bg-gradient-to-r from-secondary via-[#D4AF37] to-secondary"></div>

          <!-- Background blurred ornament -->
          <div class="absolute -top-10 -right-10 opacity-[0.06] pointer-events-none">
            <span class="material-symbols-outlined text-[200px] text-secondary">auto_awesome</span>
          </div>
          <div class="absolute -bottom-8 -left-8 opacity-[0.06] pointer-events-none">
            <span class="material-symbols-outlined text-[160px] text-secondary">favorite</span>
          </div>

          <div class="bg-surface-container-low px-8 py-10 relative z-10">

            <!-- Name field -->
            <div class="rsvp-field mb-8">
              <label>NAMA LENGKAP</label>
              <input id="rsvp-name" type="text" placeholder="Masukkan nama lengkap Anda" autocomplete="name" />
              <div
                class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-secondary to-[#D4AF37] scale-x-0 origin-left transition-transform duration-300"
                id="rsvp-name-line"></div>
            </div>

            <!-- Guest count -->
            <div id="jumlah-box" class="overflow-hidden transition-all duration-300"
              style="max-height:200px;opacity:1;">
              <div class="rsvp-field mb-10">
                <label>JUMLAH TAMU</label>
                <select id="rsvp-count">
                  <option value="1">1 Orang</option>
                  <option value="2">2 Orang</option>
                  <option value="3">3 Orang</option>
                  <option value="4">4 Orang atau lebih</option>
                </select>
                <div class="absolute bottom-0 right-0 pointer-events-none pr-1 pb-2 text-secondary">
                  <span class="material-symbols-outlined text-base">expand_more</span>
                </div>
              </div>
            </div>

            <!-- Attendance toggle -->
            <div class="mb-10">
              <p class="font-label-caps text-[10px] text-secondary mb-4 tracking-widest">STATUS KEHADIRAN</p>
              <div class="grid grid-cols-2 gap-3">
                <button id="btn-hadir" type="button"
                  class="attendance-btn flex items-center justify-center gap-2 py-4 rounded-2xl border-2 border-secondary text-secondary font-label-caps tracking-wider btn-3d"
                  onclick="selectAttendance('hadir')">
                  <span class="material-symbols-outlined text-xl">done_all</span>
                  HADIR
                </button>
                <button id="btn-tidak" type="button"
                  class="attendance-btn flex items-center justify-center gap-2 py-4 rounded-2xl border-2 border-outline-variant text-on-surface-variant font-label-caps tracking-wider btn-3d"
                  onclick="selectAttendance('tidak')">
                  <span class="material-symbols-outlined text-xl">close</span>
                  TIDAK HADIR
                </button>
              </div>

              <!-- Alasan tidak hadir (muncul saat pilih Tidak Hadir) -->
              <div id="alasan-box" class="overflow-hidden transition-all duration-300" style="max-height:0;opacity:0;">
                <div class="mt-4 pt-4 border-t border-outline-variant/30">
                  <label class="font-label-caps text-[10px] text-secondary mb-2 block">ALASAN TIDAK HADIR</label>
                  <textarea id="rsvp-alasan"
                    class="w-full bg-surface border border-outline-variant/40 rounded-xl p-3 text-sm font-body-md focus:ring-1 focus:ring-secondary focus:outline-none resize-none transition-all"
                    rows="3" placeholder="Tuliskan alasan Anda..."></textarea>
                </div>
              </div>

            </div>

            <!-- Send button -->
            <button id="btn-kirim-rsvp" type="button" onclick="kirimRSVP()"
              class="w-full bg-gradient-to-r from-secondary to-[#5a4010] text-white py-5 rounded-2xl font-label-caps tracking-widest shadow-lg shadow-secondary/30 flex justify-center items-center gap-3 active:scale-95 transition-transform btn-3d">
              <span class="material-symbols-outlined"></span>
              KIRIM KONFIRMASI
            </button>

            <p class="text-center text-[10px] text-on-surface-variant mt-4 opacity-60">
              Konfirmasi dikirim melalui WhatsApp
            </p>

          </div>
        </div>
      </section>
      <section class="py-4 mb-6 text-center reveal" id="gift">
        <div class="bg-surface-container-highest/50 p-8 rounded-3xl border-dashed border-2 border-outline-variant/30">
          <span class="material-symbols-outlined text-secondary text-4xl mb-3">featured_seasonal_and_gifts</span>
          <h3 class="font-title-sm text-primary mb-2">Gift</h3>
          <p class="font-body-md text-on-surface-variant mb-6 text-sm">Doa restu Anda adalah hadiah terindah, namun jika
            ingin memberi lebih:</p>
          <div class="flex flex-col gap-4 max-w-xs mx-auto">
            <div
              class="bg-surface p-4 rounded-xl flex items-center justify-between border border-secondary-container/20 shadow-sm">
              <div class="flex items-center gap-3 flex-1">
                <!-- Bank Logo -->
                <?php if (getSet('gift_bank_logo')): ?>
                  <div class="w-12 h-8 flex items-center justify-center overflow-hidden flex-shrink-0">
                    <img src="<?php echo getSet('gift_bank_logo'); ?>"
                      alt="<?php echo getSet('gift_bank', 'Bank'); ?> Logo" class="w-full h-full object-contain">
                  </div>
                <?php else: ?>
                  <div
                    class="w-12 h-8 flex items-center justify-center bg-secondary/10 rounded border border-secondary/20 flex-shrink-0">
                    <span class="material-symbols-outlined text-secondary text-sm">account_balance</span>
                  </div>
                <?php endif; ?>

                <!-- Bank Info -->
                <div class="text-left flex-1">
                  <p class="font-label-caps text-[10px] text-secondary">
                    <?php echo getSet('gift_bank', 'BANK BRI'); ?>
                  </p>
                  <p class="font-title-sm text-sm mt-1" id="norek">
                    <?php echo getSet('gift_account', '00550 11527 30502'); ?>
                  </p>
                  <p class="font-body-md text-[10px] opacity-60">A/N
                    <?php echo getSet('gift_owner', 'Elvy Nur Fauziyah'); ?>
                  </p>
                </div>
              </div>

              <!-- Copy Button -->
              <button onclick="copyText('norek')"
                class="text-secondary hover:scale-110 transition-transform p-2 rounded-lg hover:bg-secondary/10 flex-shrink-0">
                <span class="material-symbols-outlined" id="copy-icon-norek">content_copy</span>
              </button>
            </div>

            <div
              class="bg-surface p-4 rounded-xl flex items-center justify-between border border-secondary-container/20 shadow-sm">
              <div class="text-left">
                <p class="font-label-caps text-[10px] text-secondary">ALAMAT PENGIRIMAN HADIAH</p>
                <p class="font-title-sm text-sm mt-1 leading-snug" id="alamat-gift">
                  <?php echo getSet('gift_address', 'Ds. Pagerwojo Dsn. Pagerwojo Kec. Perak Kab. Jombang RT/RW. 05/04'); ?>
                </p>
                <p class="font-body-md text-[10px] opacity-60">Penerima:
                  <?php echo getSet('gift_owner', 'Elvy Nur Fauziyah'); ?>
                </p>
              </div>
              <div class="flex items-center gap-1">
                <a href="https://maps.app.goo.gl/GqgJsPwwrGwJ4vLd6" target="_blank"
                  class="text-secondary transition active:scale-110 p-2" title="Buka di Google Maps">
                  <span class="material-symbols-outlined text-2xl">location_on</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="py-6 border-t border-secondary-container/10 reveal" id="guestbook">
        <div class="text-center mb-8">
          <span class="font-label-caps text-label-caps text-secondary mb-2 block">WISHES</span>
          <h2 class="font-headline-md text-headline-md text-primary">Ucapan & Doa</h2>
          <p class="text-sm text-on-surface-variant mt-2 font-body-md">Tuliskan doa dan ucapan terbaik Anda</p>
        </div>

        <!-- Input form -->
        <div class="bg-surface p-6 rounded-2xl border border-secondary-container/20 shadow-sm mb-6">

          <!-- Name input -->
          <div class="rsvp-field mb-6">
            <label>NAMA ANDA</label>
            <input id="guest-name" type="text" placeholder="Masukkan nama Anda" autocomplete="name" />
          </div>

          <!-- Message textarea -->
          <textarea id="guest-message"
            class="w-full bg-surface-container-low border border-outline-variant/30 rounded-xl p-4 focus:ring-1 focus:ring-secondary focus:outline-none min-h-[100px] font-body-md resize-none transition-all"
            placeholder="Tuliskan pesan dan doa restu Anda..."></textarea>

          <!-- Real-time date display -->
          <div class="flex items-center gap-2 mt-3 mb-4 text-on-surface-variant">
            <span class="material-symbols-outlined text-sm text-secondary">schedule</span>
            <span id="realtime-date" class="text-[11px] font-label-caps tracking-wider"></span>
          </div>

          <!-- Send button -->
          <button onclick="kirimUcapan()"
            class="w-full bg-secondary text-on-secondary px-6 py-3 rounded-full font-label-caps tracking-widest flex items-center justify-center gap-2 active:scale-95 transition-transform shadow-md shadow-secondary/20 btn-3d">
            <span class="material-symbols-outlined text-base">send</span>
            KIRIM UCAPAN
          </button>
        </div>

        <!-- Message list -->
        <div id="message-list" class="space-y-4">
          <!-- Seed messages -->
          <div class="message-card p-5 border-l-2 border-secondary bg-surface-container-low rounded-r-xl">
            <div class="flex justify-between items-start mb-2">
              <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-secondary/20 flex items-center justify-center">
                  <span class="text-xs font-bold text-secondary">B</span>
                </div>
                <h4 class="font-title-sm text-sm text-primary">Budi Santoso</h4>
              </div>
              <span class="text-[10px] font-label-caps text-outline">2 JAM LALU</span>
            </div>
            <p class="font-body-md text-sm text-on-surface-variant italic">"Selamat menempuh hidup baru Elvy & Rokim.
              Semoga menjadi keluarga yang sakinah, mawaddah, wa rahmah. Amin."</p>
          </div>
          <div class="message-card p-5 border-l-2 border-outline-variant bg-surface-container-low rounded-r-xl">
            <div class="flex justify-between items-start mb-2">
              <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-secondary/20 flex items-center justify-center">
                  <span class="text-xs font-bold text-secondary">L</span>
                </div>
                <h4 class="font-title-sm text-sm text-primary">Larasati Putri</h4>
              </div>
              <span class="text-[10px] font-label-caps text-outline">5 JAM LALU</span>
            </div>
            <p class="font-body-md text-sm text-on-surface-variant italic">"Turut berbahagia untuk kalian berdua! Semoga
              lancar sampai hari H ya. See you there!"</p>
          </div>
        </div>
      </section>

      <!-- Quote from Index -->
      <div class="pt-16"></div>
      <section class="px-margin-page py-section-gap relative flex flex-col items-center text-center reveal">

        <div class="max-w-xl space-y-6">
          <span class="material-symbols-outlined text-secondary text-4xl">format_quote</span>

          <p class="font-body-md text-body-md text-on-surface italic leading-relaxed">
            "Allah akan memudahkan jalan bagi hamba-Nya yang bersungguh-sungguh menapaki jalan kebaikan"<br />
          </p>

          <div class="font-label-caps text-label-caps text-secondary tracking-widest">
            — Elvy & Rokim
          </div>
        </div>
      </section>
      <footer class="spa-footer">
        <div class="font-label-caps text-[10px] text-secondary tracking-[0.2em] opacity-80">
          &copy; 2026 Elvy & Rokim. All Rights Reserved.
        </div>

        <div
          class="mt-2 flex items-center justify-center gap-1 font-body-md text-[9px] text-on-surface-variant opacity-60">
          <span>Design by</span>

          <a href="https://instagram.com/fahmiprdn16" target="_blank"
            class="flex items-center gap-1 font-semibold hover:text-secondary transition-colors">

            fahmiprdn16
          </a>
        </div>
      </footer>


      <!-- Lightbox Modal -->
      <div id="lightbox"
        class="fixed inset-0 z-[999] bg-black/90 hidden flex-col items-center justify-center opacity-0 transition-opacity duration-300"
        onclick="closeLightbox()">
        <button onclick="closeLightbox()"
          class="absolute top-6 right-6 text-white bg-white/10 p-2 rounded-full hover:bg-white/20 transition backdrop-blur z-50">
          <span class="material-symbols-outlined text-3xl">close</span>
        </button>
        <img id="lightbox-img" src=""
          class="max-w-[95vw] max-h-[90vh] object-contain rounded-lg shadow-2xl scale-95 transition-transform duration-300"
          onclick="event.stopPropagation()" />
      </div>

      <!-- Make variables available before nav.js loads -->
      <script>
        // Define these first so nav.js can access them
        window.invId = <?php echo $inv_id; ?>;
        window.invSlug = "<?php echo $invSlug; ?>";
        window.receptionDateStr = "<?php echo getSet('reception_date', '2026-06-11'); ?> <?php echo getSet('reception_time_start', '11:00'); ?>";
      </script>

      <script src="nav.js"></script>
      <script>
        // These are also available for other scripts
        const invId = <?php echo $inv_id; ?>;
        const invSlug = "<?php echo $invSlug; ?>";
        const receptionDateStr = "<?php echo getSet('reception_date', '2026-06-11'); ?> <?php echo getSet('reception_time_start', '11:00'); ?>";

        /* =========================================
           LIGHTBOX LOGIC
           ========================================= */

        function openLightbox(src) {
          const lb = document.getElementById('lightbox');
          const img = document.getElementById('lightbox-img');
          img.src = src;
          lb.classList.remove('hidden');
          lb.classList.add('flex');

          // Trigger reflow for animation
          void lb.offsetWidth;

          lb.classList.remove('opacity-0');
          img.classList.remove('scale-95');
          img.classList.add('scale-100');
          document.body.style.overflow = 'hidden'; // Lock scroll
        }

        function closeLightbox() {
          const lb = document.getElementById('lightbox');
          const img = document.getElementById('lightbox-img');

          lb.classList.add('opacity-0');
          img.classList.remove('scale-100');
          img.classList.add('scale-95');

          setTimeout(() => {
            lb.classList.add('hidden');
            lb.classList.remove('flex');
            document.body.style.overflow = ''; // Unlock scroll
          }, 300);
        }


        /* =========================================
           RSVP & GUESTBOOK LOGIC
           ========================================= */

        /* ---- Attendance toggle ---- */
        var attendance = "";
        function selectAttendance(val) {
          attendance = val;
          var hadir = document.getElementById("btn-hadir");
          var tidak = document.getElementById("btn-tidak");
          var alasanBox = document.getElementById("alasan-box");
          var jumlahBox = document.getElementById("jumlah-box");
          // Reset classes
          hadir.className = "attendance-btn flex items-center justify-center gap-2 py-4 rounded-2xl border-2 font-label-caps tracking-wider border-secondary text-secondary";
          tidak.className = "attendance-btn flex items-center justify-center gap-2 py-4 rounded-2xl border-2 font-label-caps tracking-wider border-outline-variant text-on-surface-variant";
          if (val === "hadir") {
            hadir.classList.add("selected-hadir");
            jumlahBox.style.maxHeight = "200px";
            jumlahBox.style.opacity = "1";
            alasanBox.style.maxHeight = "0";
            alasanBox.style.opacity = "0";
          } else if (val === "tidak") {
            tidak.classList.add("selected-tidak");
            jumlahBox.style.maxHeight = "0";
            jumlahBox.style.opacity = "0";
            alasanBox.style.maxHeight = "200px";
            alasanBox.style.opacity = "1";
            document.getElementById("rsvp-alasan").focus();
          } else {
            // Reset semua
            jumlahBox.style.maxHeight = "200px";
            jumlahBox.style.opacity = "1";
            alasanBox.style.maxHeight = "0";
            alasanBox.style.opacity = "0";
          }
        }

        /* ---- RSVP → API + WhatsApp ---- */
        function kirimRSVP() {
          var name = document.getElementById("rsvp-name").value.trim();
          var count = parseInt(document.getElementById("rsvp-count").value, 10);
          var alasan = attendance === "tidak" ? document.getElementById("rsvp-alasan").value.trim() : "";
          if (!name) { alert("Mohon isi nama terlebih dahulu."); return; }
          if (!attendance) { alert("Mohon pilih status kehadiran."); return; }

          var btn = document.getElementById("btn-kirim-rsvp");
          btn.disabled = true;
          btn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Menyimpan...';

          fetch("api/rsvp.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ invitation_id: invId, nama: name, jumlah_tamu: count, status: attendance, alasan: alasan })
          })
            .then(function (res) { return res.json(); })
            .then(function (data) {
              btn.disabled = false;
              btn.innerHTML = '<span class="material-symbols-outlined">whatsapp</span> KIRIM KONFIRMASI';
              if (data.success) {
                var statusTxt = attendance === "hadir" ? "HADIR ✅" : "TIDAK HADIR ❌";
                var msg = "Halo, saya *" + name + "* ingin mengkonfirmasi kehadiran saya.\n\n"
                  + "Status : " + statusTxt;
                if (attendance === "hadir") msg += "\nJumlah Tamu : " + count + " orang";
                if (alasan) msg += "\nAlasan : " + alasan;
                msg += "\n\nTerima kasih atas undangannya 🙏🏻";
                window.open("https://wa.me/6288210841990?text=" + encodeURIComponent(msg), "_blank");
                document.getElementById("rsvp-name").value = "";
                document.getElementById("rsvp-alasan").value = "";
                attendance = "";
                selectAttendance(""); // reset visual
              } else {
                alert("Gagal menyimpan: " + (data.message || "Error"));
              }
            })
            .catch(function (err) {
              btn.disabled = false;
              btn.innerHTML = '<span class="material-symbols-outlined">whatsapp</span> KIRIM KONFIRMASI';
              alert("Koneksi error. Pastikan XAMPP berjalan.\n" + err);
            });
        }

        /* ---- Copy norek ---- */
        /* ---- Generic Copy function ---- */
        function copyText(id) {
          var text = document.getElementById(id).textContent.trim();
          var iconId = id === "norek" ? "copy-icon-norek" : "copy-icon-alamat";
          navigator.clipboard.writeText(text).then(function () {
            var icon = document.getElementById(iconId);
            icon.textContent = "done";
            icon.classList.add("copy-success");
            setTimeout(function () {
              icon.textContent = "content_copy";
              icon.classList.remove("copy-success");
            }, 2000);
          });
        }

        /* ---- Real-time date ---- */
        function updateDate() {
          var now = new Date();
          var days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
          var months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
          var h = now.getHours().toString().padStart(2, "0");
          var m = now.getMinutes().toString().padStart(2, "0");
          var s = now.getSeconds().toString().padStart(2, "0");
          document.getElementById("realtime-date").textContent =
            days[now.getDay()] + ", " + now.getDate() + " " + months[now.getMonth()] + " " + now.getFullYear()
            + "  •  " + h + ":" + m + ":" + s + " WIB";
        }
        updateDate();
        setInterval(updateDate, 1000);

        /* ---- Render 1 message card ---- */
        function renderCard(item, prepend) {
          var initial = item.nama.charAt(0).toUpperCase();
          var timeStr = formatTime(item.created_at);
          var isBorder = prepend ? "border-[#D4AF37]" : "border-secondary";
          var card = document.createElement("div");
          card.className = "message-card p-5 border-l-2 " + isBorder + " bg-surface-container-low rounded-r-xl";
          card.innerHTML =
            '<div class="flex justify-between items-start mb-2">' +
            '<div class="flex items-center gap-2">' +
            '<div class="w-7 h-7 rounded-full bg-secondary flex items-center justify-center">' +
            '<span class="text-xs font-bold text-white">' + escapeHtml(initial) + '</span>' +
            '</div>' +
            '<h4 class="font-title-sm text-sm text-primary">' + escapeHtml(item.nama) + '</h4>' +
            '</div>' +
            '<span class="text-[10px] font-label-caps text-outline">' + escapeHtml(timeStr) + '</span>' +
            '</div>' +
            '<p class="font-body-md text-sm text-on-surface-variant italic">"' + escapeHtml(item.pesan) + '"</p>';
          return card;
        }

        function formatTime(created_at) {
          if (!created_at) return "Baru saja";
          var d = new Date(created_at.replace(" ", "T") + "+07:00");
          var now = new Date();
          var diff = Math.floor((now - d) / 1000);
          if (diff < 60) return "Baru saja";
          if (diff < 3600) return Math.floor(diff / 60) + " menit lalu";
          if (diff < 86400) return Math.floor(diff / 3600) + " jam lalu";
          return Math.floor(diff / 86400) + " hari lalu";
        }

        /* ---- Load ucapan from DB ---- */
        function loadUcapan() {
          fetch(`api/ucapan.php?inv_id=${invId}&limit=20`)
            .then(function (res) { return res.json(); })
            .then(function (data) {
              if (!data.success || !data.data.length) return;
              var list = document.getElementById("message-list");
              list.innerHTML = ""; // hapus seed messages
              data.data.forEach(function (item) {
                list.appendChild(renderCard(item, false));
              });
            })
            .catch(function () {
              // Jika API belum siap, seed messages tetap tampil
            });
        }

        /* ---- Send guestbook message → API ---- */
        function kirimUcapan() {
          var name = document.getElementById("guest-name").value.trim();
          var msg = document.getElementById("guest-message").value.trim();
          if (!name) { alert("Mohon isi nama Anda terlebih dahulu."); return; }
          if (!msg) { alert("Mohon tulis ucapan terlebih dahulu."); return; }

          fetch("api/ucapan.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ invitation_id: invId, nama: name, pesan: msg })
          })
            .then(function (res) { return res.json(); })
            .then(function (data) {
              if (data.success) {
                var list = document.getElementById("message-list");
                var card = renderCard(data.data, true);
                list.insertBefore(card, list.firstChild);
                card.scrollIntoView({ behavior: "smooth", block: "start" });
                document.getElementById("guest-name").value = "";
                document.getElementById("guest-message").value = "";
              } else {
                alert("Gagal menyimpan: " + (data.message || "Error"));
              }
            })
            .catch(function (err) {
              alert("Koneksi error. Pastikan XAMPP berjalan.\n" + err);
            });
        }

        function escapeHtml(str) {
          return String(str)
            .replace(/&/g, "&amp;").replace(/</g, "&lt;")
            .replace(/>/g, "&gt;").replace(/"/g, "&quot;");
        }

        /* ---- Name field focus line ---- */
        var rsvpName = document.getElementById("rsvp-name");
        var nameLine = document.getElementById("rsvp-name-line");
        if (rsvpName && nameLine) {
          rsvpName.addEventListener("focus", function () { nameLine.style.transform = "scaleX(1)"; });
          rsvpName.addEventListener("blur", function () { nameLine.style.transform = "scaleX(0)"; });
        }

        // Load ucapan dari DB saat halaman dimuat
        loadUcapan();

        /* ---- Back to Home Function ---- */
        function backToHome() {
          // Stop music if playing
          if (window.bgMusic && window.musicPlaying) {
            window.bgMusic.pause();
            window.musicPlaying = false;
            if (window.updateMusicUI) {
              window.updateMusicUI(false);
            }
          }

          // Hide header, dot nav, scroll progress
          var header = document.querySelector(".top-header");
          var dotNav = document.getElementById("dot-nav");
          var scrollProg = document.getElementById("scroll-progress");

          if (header) header.classList.remove("visible");
          if (dotNav) dotNav.classList.remove("active");
          if (scrollProg) scrollProg.classList.remove("active");

          // Show cover gate again
          var gate = document.getElementById("opening-overlay");
          if (gate) {
            gate.style.display = "flex";
            gate.classList.remove("opened");

            // Add no-scroll back to body
            document.body.classList.add("no-scroll");

            // Scroll to top smoothly
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Small delay to ensure smooth transition
            setTimeout(function () {
              // Reset any scroll reveals
              var reveals = document.querySelectorAll('.reveal, .reveal-scale, .reveal-stagger');
              reveals.forEach(function (el) {
                el.classList.remove('visible');
              });
            }, 300);
          }
        }

        /* ---- Falling Leaf/Petals Animation ---- */
        var leafInterval = null;
        function startLeafFall() {
          if (leafInterval) return;
          // Spawn a petal/leaf every 400ms
          leafInterval = setInterval(createPetal, 400);
        }
        
        // Stop leaf fall when cover gate is closed/reopened
        function stopLeafFall() {
          if (leafInterval) {
            clearInterval(leafInterval);
            leafInterval = null;
          }
        }
        
        // Check if gate is already open or bypass exists
        var checkGate = document.getElementById('opening-overlay');
        if (!checkGate || checkGate.style.display === 'none' || checkGate.classList.contains('opened')) {
          startLeafFall();
        } else {
          var btnBuka = document.getElementById('btn-buka');
          if (btnBuka) {
            btnBuka.addEventListener('click', startLeafFall);
          }
        }
        
        function createPetal() {
          var container = document.getElementById('animation-container');
          if (!container) return;

          var petal = document.createElement('div');
          petal.className = 'floating-petal';
          
          var size = Math.random() * 15 + 10; // 10px to 25px
          var left = Math.random() * 100; // 0% to 100%
          var duration = Math.random() * 8 + 6; // 6s to 14s
          var delay = Math.random() * 2; // 0s to 2s
          var rotate = Math.random() * 360;
          
          petal.style.width = size + 'px';
          petal.style.height = size + 'px';
          petal.style.left = left + '%';
          petal.style.animationName = 'fall';
          petal.style.animationDuration = duration + 's';
          petal.style.animationDelay = delay + 's';
          petal.style.transform = `rotate(${rotate}deg)`;
          
          // Get the dynamic secondary color from document root computed styles (CSS variables)
          var secondaryColor = getComputedStyle(document.documentElement).getPropertyValue('--secondary-color').trim() || '#775a19';
          
          // Create SVG leaf/petal shape
          petal.innerHTML = `
            <svg viewBox="0 0 30 30" width="100%" height="100%">
              <path d="M15,2 C22,10 22,20 15,28 C8,20 8,10 15,2" fill="${secondaryColor}" opacity="0.35"/>
            </svg>
          `;
          
          container.appendChild(petal);
          
          // Remove elements after their falling animation completes
          setTimeout(function() {
            petal.remove();
          }, (duration + delay) * 1000);
        }

        // Hook stop into backToHome
        var originalBackToHome = backToHome;
        backToHome = function() {
          stopLeafFall();
          originalBackToHome();
        };

      </script>
</body>

</html>