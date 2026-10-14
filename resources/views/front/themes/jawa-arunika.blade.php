<?php
/* ============================================================
   TEMA: JAWA ARUNIKA — undangan Jawa gelap mewah
   Aset: tibrainvitation.id (sesuai permintaan)
   Dipilih via settings: theme = jawa-arunika
   ============================================================ */
$GLOBALS['jwa_settings'] = $settings ?? [];
if (!function_exists('jwaGet')) {
  function jwaGet($key, $default = '') {
    $s = $GLOBALS['jwa_settings'];
    if (!isset($s[$key]) || trim((string)$s[$key]) === '') return $default;
    return $s[$key];
  }
}
if (!function_exists('jwaDate')) {
  function jwaDate($dateStr) {
    if (!$dateStr) return '';
    $days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($dateStr);
    if (!$ts) return $dateStr;
    return $days[date('w',$ts)].', '.date('j',$ts).' '.$months[date('n',$ts)].' '.date('Y',$ts);
  }
}
if (!function_exists('jwaTime')) {
  function jwaTime($t) {
    $t = trim((string)$t); if ($t === '') return '08:00';
    $t = str_replace('.', ':', $t);
    return preg_match('/^\d{1,2}(:\d{2})?$/', $t) ? (strpos($t, ':') === false ? $t.':00' : $t) : '08:00';
  }
}
$GLOBALS['jwa_asset_base'] = '';
if (!function_exists('jwaFiligree')) {
  /* Ornamen filigree emas sudut — SVG digambar khusus, tanpa library luar */
  function jwaFiligree($variant = 0, $mirror = false) {
    $mir = $mirror ? ' style="transform:scaleX(-1)"' : '';
    if ($variant % 2 == 0) {
      $inner = '<defs><linearGradient id="jgoldA" x1="0" y1="200" x2="200" y2="0" gradientUnits="userSpaceOnUse">'
        .'<stop offset="0" stop-color="#9c7618"/><stop offset=".55" stop-color="#d4af37"/><stop offset="1" stop-color="#f9e6a7"/></linearGradient></defs>'
        .'<g stroke="url(#jgoldA)" stroke-width="2.5" stroke-linecap="round" fill="none">'
        .'<path d="M16 184 C16 118 44 68 100 42 C134 26 160 22 186 20"/>'
        .'<path d="M16 184 C54 174 92 156 120 128 C138 110 148 92 152 72"/>'
        .'<path d="M44 184 C62 150 92 130 122 122" stroke-width="2"/></g>'
        .'<g fill="url(#jgoldA)">'
        .'<ellipse cx="54" cy="116" rx="10" ry="4.5" transform="rotate(-38 54 116)"/>'
        .'<ellipse cx="88" cy="80" rx="10" ry="4.5" transform="rotate(-28 88 80)"/>'
        .'<ellipse cx="128" cy="56" rx="9" ry="4" transform="rotate(-18 128 56)"/>'
        .'<ellipse cx="72" cy="156" rx="9" ry="4" transform="rotate(-52 72 156)"/>'
        .'<ellipse cx="106" cy="136" rx="8" ry="3.6" transform="rotate(-45 106 136)"/>'
        .'<circle cx="186" cy="20" r="4"/><circle cx="152" cy="72" r="3"/>'
        .'<circle cx="16" cy="184" r="4"/><circle cx="122" cy="122" r="2.5"/></g>';
    } else {
      $inner = '<defs><linearGradient id="jgoldB" x1="0" y1="200" x2="200" y2="0" gradientUnits="userSpaceOnUse">'
        .'<stop offset="0" stop-color="#9c7618"/><stop offset=".55" stop-color="#d4af37"/><stop offset="1" stop-color="#f9e6a7"/></linearGradient></defs>'
        .'<g stroke="url(#jgoldB)" stroke-width="2" fill="none" stroke-linecap="round">'
        .'<path d="M18 182 A150 150 0 0 1 168 32"/><path d="M18 182 A105 105 0 0 1 123 77"/>'
        .'<path d="M18 182 A60 60 0 0 1 78 122"/><path d="M18 182 L168 32" stroke-width="1" opacity=".55"/></g>'
        .'<g fill="url(#jgoldB)">'
        .'<circle cx="18" cy="182" r="4"/><circle cx="168" cy="32" r="4"/>'
        .'<path d="M93 100 l7 7 -7 7 -7 -7 z"/>'
        .'<circle cx="60" cy="140" r="2.5"/><circle cx="140" cy="60" r="2.5"/>'
        .'<circle cx="100" cy="50" r="2.5"/><circle cx="50" cy="100" r="2.5"/></g>';
    }
    return '<svg'.$mir.' viewBox="0 0 200 200" fill="none" class="w-full h-auto" style="filter:drop-shadow(0 0 6px rgba(212,175,55,.35))">'.$inner.'</svg>';
  }
}
if (!function_exists('jwaJanur')) {
  /* Janur kuning: pelepah melengkung ala gerbang pernikahan Jawa, digambar prosedural */
  function jwaJanur($mirror = false) {
    $p0=[12,8]; $c=[95,-8]; $p1=[196,118];
    $g='<defs><linearGradient id="jjanur" x1="0" y1="0" x2="1" y2="1">'
      .'<stop offset="0" stop-color="#fdf6cf"/><stop offset="1" stop-color="#ecd27f"/></linearGradient></defs>';
    $g.='<path d="M12 8 Q95 -8 196 118" stroke="#d9b94a" stroke-width="3.5" fill="none" stroke-linecap="round"/>';
    for ($i=1; $i<=11; $i++) {
      $t=$i/12;
      $x=(1-$t)*(1-$t)*$p0[0]+2*(1-$t)*$t*$c[0]+$t*$t*$p1[0];
      $y=(1-$t)*(1-$t)*$p0[1]+2*(1-$t)*$t*$c[1]+$t*$t*$p1[1];
      $dx=2*(1-$t)*($c[0]-$p0[0])+2*$t*($p1[0]-$c[0]);
      $dy=2*(1-$t)*($c[1]-$p0[1])+2*$t*($p1[1]-$c[1]);
      $ang=atan2($dy,$dx)*180/M_PI;
      $len=round(30-12*$t,1);
      foreach ([-1,1] as $sd) {
        $a=round($ang+$sd*62,1);
        $g.='<ellipse cx="'.round($x,1).'" cy="'.round($y,1).'" rx="'.$len.'" ry="4.6" transform="rotate('.$a.' '.round($x,1).' '.round($y,1).')" fill="url(#jjanur)" opacity="0.95"/>';
      }
    }
    $mir=$mirror?' style="transform:scaleX(-1)"':'';
    return '<svg'.$mir.' viewBox="0 0 210 140" fill="none" class="w-full h-auto" style="filter:drop-shadow(0 0 8px rgba(240,216,138,.35))">'.$g.'</svg>';
  }
}
if (!function_exists('jwaCorners')) {
  /* Ornamen di sekeliling frame: $items = all|trees|flowers */
  function jwaCorners($variant = 0, $extra = '', $items = 'all') {
    $A = $GLOBALS['jwa_asset_base'];
    if ($variant % 2 == 0) {
      $flL='ASSET-JAWA-BUNGA1.png';  $flR='ASSET-JAWA-BUNGA4.png';
    } else {
      $flL='ASSET-JAWA-BUNGA19.png'; $flR='ASSET-JAWA-BUNGA5-768x330.png';
    }
    $bloom = function($pos, $img, $op) use ($A) {
      return '<div class="bloom absolute" style="'.$pos.'"><img src="'.$A.$img.'" class="w-full h-auto" style="opacity:'.$op.'" alt=""></div>';
    };
    $h = '<div class="corners orn pointer-events-none absolute inset-0 '.($extra ?: 'z-0').'" data-speed="1.15" data-rot="0">';
    if ($items === 'all' || $items === 'trees') {
      $h .= '<div class="bloom absolute" style="top:-4%;left:-6%;width:44%">'.jwaFiligree($variant).'</div>';
      $h .= '<div class="bloom absolute" style="top:-4%;right:-6%;width:44%">'.jwaFiligree($variant, true).'</div>';
    }
    if ($items === 'all' || $items === 'flowers') {
      /* kiri & kanan SELALU simetris: aset sama, kanan di-mirror */
      $flLimg = '<img src="'.$A.$flL.'" class="w-full h-auto" style="opacity:.85" alt="">';
      $flRimg = '<img src="'.$A.$flL.'" class="w-full h-auto scale-x-[-1]" style="opacity:.85" alt="">';
      if ($items === 'flowers') { /* sudut saja, kecil, tidak menutupi teks */
        $h .= '<div class="bloom absolute" style="bottom:-2%;left:-13%;width:30%">'.$flLimg.'</div>';
        $h .= '<div class="bloom absolute" style="bottom:-2%;right:-13%;width:30%">'.$flRimg.'</div>';
      } else {
        $h .= '<div class="bloom absolute" style="bottom:-3%;left:-9%;width:38%">'.$flLimg.'</div>';
        $h .= '<div class="bloom absolute" style="bottom:-3%;right:-9%;width:38%">'.$flRimg.'</div>';
      }
    }
    return $h.'</div>';
  }
}
$groomNick = jwaGet('groom_nickname') ?: jwaGet('groom_name', 'Mempelai Pria');
$brideNick = jwaGet('bride_nickname') ?: jwaGet('bride_name', 'Mempelai Wanita');
$groomFull = jwaGet('groom_name', 'Mempelai Pria');
$brideFull = jwaGet('bride_name', 'Mempelai Wanita');
$toName = (!empty($guestName) && $guestName !== 'Bapak/Ibu/Saudara/i') ? $guestName : request('to', 'Tamu Undangan');
$wDate  = jwaGet('wedding_date', date('Y-m-d'));
$wTime  = jwaTime(jwaGet('wedding_time_start', '08:00'));
$wTimeE = jwaTime(jwaGet('wedding_time_end', '10:00'));
$rDate  = jwaGet('reception_date', $wDate);
$rTimeS = jwaTime(jwaGet('reception_time_start', '11:00'));
$rTimeE = jwaTime(jwaGet('reception_time_end', '13:00'));
$tzLabel = jwaGet('wedding_timezone', 'WIB');
$sameLoc = (jwaGet('event_location_mode', 'same') === 'same');
$mainMapLink = trim((string)jwaGet('wedding_map_link')) ?: trim((string)jwaGet('reception_map_link'));
$giftRecipient = jwaGet('gift_recipient_name', jwaGet('gift_owner', ''));
$countdownISO = $rDate.'T'.$rTimeS.':00';
$dateShort = date('d . m . y', strtotime($rDate));
$accounts = [];
$__gbanks = json_decode(jwaGet('gift_banks', ''), true) ?: [];
foreach ($__gbanks as $__b) {
  if (empty($__b['account'])) continue;
  $accounts[] = ['bank'=>($__b['name'] ?? 'Bank'),'no'=>$__b['account'],'an'=>($__b['owner'] ?? ''),'logo'=>($__b['logo'] ?? '')];
}
if (!$accounts) {
  if (jwaGet('gift_account')) $accounts[] = ['bank'=>jwaGet('gift_bank','Bank'),'no'=>jwaGet('gift_account'),'an'=>jwaGet('gift_owner',''),'logo'=>jwaGet('gift_bank_logo','')];
  if (jwaGet('gift_account2')) $accounts[] = ['bank'=>jwaGet('gift_bank2','Bank'),'no'=>jwaGet('gift_account2'),'an'=>jwaGet('gift_owner2',''),'logo'=>jwaGet('gift_bank_logo','')];
}
$musicUrl = null;
if (!empty($musicRecord) && !empty($musicRecord->file_path)) {
  $mp = $musicRecord->file_path;
  $musicUrl = (str_starts_with($mp,'http') || str_starts_with($mp,'/')) ? $mp : asset('storage/'.ltrim($mp,'/'));
}
$invId = $inv_id ?? 0;
$gcalDates = str_replace('-','',$rDate).'T'.str_replace(':','',$rTimeS).'00/'.str_replace('-','',$rDate).'T'.str_replace(':','',$rTimeE).'00';
$gcalUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text='.urlencode('Pernikahan '.$brideNick.' & '.$groomNick).'&dates='.$gcalDates.'&details='.urlencode(jwaGet('reception_location', jwaGet('wedding_location','')));
$ASSET = 'https://tibrainvitation.id/wp-content/uploads/2026/02/';
$GLOBALS['jwa_asset_base'] = $ASSET;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>The Wedding of {{ $groomNick }} &amp; {{ $brideNick }}</title>
  <meta property="og:type" content="website">
  <meta property="og:title" content="Undangan Pernikahan {{ $brideNick }} & {{ $groomNick }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,500&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
  <script>
    tailwind.config = {
      theme: { extend: {
        fontFamily: {
          cinzel: ['"Cinzel Decorative"','serif'],
          serif: ['"Cormorant Garamond"','serif'],
          sans: ['"Plus Jakarta Sans"','sans-serif'],
        },
        colors: { jawa: { bg:'#120d0a', card:'#1b130e', gold:'#d4af37', lightgold:'#f5df98', cream:'#f7efe6' } }
      }}
    }
  </script>
  <style>
    *{ -webkit-tap-highlight-color:transparent; box-sizing:border-box; }
    html{ scroll-behavior:smooth; }
    body.is-locked{ overflow:hidden !important; height:100dvh !important; }
    body.is-locked main{ touch-action:none; }
    .invitation-frame{ width:100%; max-width:430px; margin:0 auto; position:relative;
      overflow-x:hidden; background-color:#16100c; box-shadow:0 0 80px rgba(0,0,0,.95); }
    .sway-node{ transform-origin:bottom center; will-change:transform; backface-visibility:hidden; }
    .bloom{ will-change:transform,opacity; }
    .text-gold-gradient{ background:linear-gradient(135deg,#f9e6a7 0%,#d4af37 50%,#9c7618 100%);
      -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; color:transparent; }
    .btn-gold-gradient{ background:linear-gradient(135deg,#e0bb43 0%,#f7e3a1 50%,#b38618 100%);
      box-shadow:0 6px 24px rgba(212,175,55,.4); transition:transform .2s, box-shadow .2s; }
    .btn-gold-gradient:hover{ box-shadow:0 8px 30px rgba(212,175,55,.55); }
    .btn-gold-gradient:active{ transform:scale(.96); }
    .btn-outline-gold{ border:1px solid rgba(212,175,55,.55); color:#f5df98; transition:.25s; }
    .btn-outline-gold:hover{ background:rgba(212,175,55,.12); }
    .sec-head-orn{ width:34%; max-width:132px; margin:0 auto 10px; opacity:.9; }
    .hairline-gold{ height:1px; background:linear-gradient(90deg,transparent,rgba(212,175,55,.7),transparent); }
    .card-dark{ background:rgba(27,19,14,.88); backdrop-filter:blur(8px);
      border:1px solid rgba(212,175,55,.28); border-radius:1.25rem;
      box-shadow:0 18px 45px -18px rgba(0,0,0,.8); }
    .field-dark{ width:100%; background:rgba(0,0,0,.35); border:1px solid rgba(212,175,55,.3);
      border-radius:.8rem; padding:.8rem 1rem; color:#f7efe6; font-size:.9rem; outline:none; transition:.25s; }
    .field-dark::placeholder{ color:#8d8378; }
    .field-dark:focus{ border-color:rgba(212,175,55,.8); box-shadow:0 0 0 3px rgba(212,175,55,.12); }
    select.field-dark option{ background:#1b130e; }
    /* musik melayang */
    #musicFloat{ position:fixed; z-index:45; right:max(14px,calc(50% - 215px + 14px)); bottom:18px;
      width:48px; height:48px; border-radius:9999px; display:flex; align-items:center; justify-content:center;
      background:linear-gradient(135deg,#e0bb43,#b38618); color:#1b130e; cursor:pointer;
      box-shadow:0 8px 22px rgba(212,175,55,.45); border:1px solid rgba(245,223,152,.6); }
    #musicFloat[hidden]{ display:none !important; }
    #musicFloat .bars{ display:none; align-items:flex-end; gap:2px; height:16px; }
    #musicFloat.playing .bars{ display:flex; }
    #musicFloat.playing .note{ display:none; }
    #musicFloat .bars span{ width:3px; background:#1b130e; border-radius:2px; animation:eq 0.9s ease-in-out infinite; }
    #musicFloat .bars span:nth-child(2){ animation-delay:.2s; } #musicFloat .bars span:nth-child(3){ animation-delay:.45s; }
    @keyframes eq{ 0%,100%{height:6px;} 50%{height:16px;} }
    #toast{ position:fixed; left:50%; bottom:84px; transform:translate(-50%,16px); z-index:60;
      background:#1b130e; color:#f7efe6; border:1px solid rgba(212,175,55,.6); border-radius:9999px;
      padding:.65rem 1.4rem; font-size:.8rem; letter-spacing:.05em; opacity:0; transition:.3s;
      pointer-events:none; max-width:86vw; text-align:center; white-space:nowrap;
      overflow:hidden; text-overflow:ellipsis; }
    #toast.show{ opacity:1; transform:translate(-50%,0); }
    ::-webkit-scrollbar{ width:0; background:transparent; }
    @media (prefers-reduced-motion:reduce){ .sway-node{ animation:none !important; } }
  </style>
</head>
<body class="is-locked bg-[#080605] text-jawa-cream font-sans antialiased">
<main class="invitation-frame min-h-screen border-x border-jawa-gold/15">

  <!-- ================= 1. PRELOADER ================= -->
  <section id="preloader" class="fixed inset-0 z-50 mx-auto max-w-[430px] flex flex-col items-center justify-center bg-jawa-bg px-6 text-center">
    <div class="relative mb-6 w-[30%] max-w-[120px]">
      <div class="absolute inset-0 rounded-full bg-jawa-gold/20 blur-2xl"></div>
      <img src="{{ $ASSET }}ASSET-GUNUNGAN.png" alt="Gunungan" class="relative w-full h-auto drop-shadow-[0_8px_20px_rgba(212,175,55,0.3)]" />
    </div>
    <p class="text-[10px] uppercase tracking-[0.4em] text-jawa-gold mb-2">Wedding Invitation</p>
    <h1 class="font-cinzel text-xl text-gold-gradient mb-6">{{ $groomNick }} &amp; {{ $brideNick }}</h1>
    <div class="w-40 h-[2px] bg-stone-800 rounded-full overflow-hidden mb-2">
      <div id="loader-bar" class="h-full w-0 bg-gradient-to-r from-jawa-gold to-jawa-lightgold"></div>
    </div>
    <span id="loader-percent" class="text-[11px] font-mono tracking-[0.25em] text-jawa-lightgold">0%</span>
  </section>

  <!-- ================= 2. COVER / GATE ================= -->
  <section id="cover-gate" class="fixed inset-0 z-40 mx-auto max-w-[430px] h-[100dvh] flex flex-col justify-between py-8 px-6 text-center bg-[#140e0a] overflow-hidden">
    <img src="{{ $ASSET }}BG-ID-4.png" class="pointer-events-none absolute inset-0 w-full h-full object-cover opacity-20" alt="" />
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-black/85 via-black/25 to-black/90"></div>

    <div id="gate-left-tree" class="pointer-events-none absolute left-[3%] top-[2%] w-[36%] opacity-80">
      {!! jwaFiligree(0) !!}
    </div>
    <div id="gate-right-tree" class="pointer-events-none absolute right-[3%] top-[2%] w-[36%] opacity-80">
      {!! jwaFiligree(0, true) !!}
    </div>
    <div id="gate-left-gunungan" class="pointer-events-none absolute -left-[14%] bottom-[2%] w-[44%] opacity-50">
      <img src="{{ $ASSET }}1225-GUNUNGAN-JAWA-576x1024.png" class="w-full h-auto" alt="" />
    </div>
    <div id="gate-right-gunungan" class="pointer-events-none absolute -right-[14%] bottom-[2%] w-[44%] opacity-50">
      <img src="{{ $ASSET }}1225-GUNUNGAN-JAWA-576x1024.png" class="w-full h-auto scale-x-[-1]" alt="" />
    </div>

    <div id="gate-header" class="relative z-10 pt-1">
      <img src="{{ $ASSET }}ASSET-JAWA-REV-8-REVISII.png" class="w-[34%] max-w-[132px] mx-auto mb-3 opacity-90" alt="" />
      <p class="text-[10px] uppercase tracking-[0.4em] text-jawa-gold">The Wedding Of</p>
      <h2 class="font-cinzel text-[clamp(1.6rem,6.5vw,2.1rem)] mt-2 text-gold-gradient leading-snug">{{ $groomNick }} &amp; {{ $brideNick }}</h2>
    </div>

    <div id="gate-center" class="relative z-10 py-1">
      <img src="{{ $ASSET }}ASSET-GUNUNGAN.png" class="sway-node sway-subtle w-[30%] max-w-[122px] mx-auto drop-shadow-[0_12px_28px_rgba(212,175,55,0.35)]" alt="Gunungan" />
    </div>

    <div id="gate-footer" class="relative z-10 w-full max-w-[320px] mx-auto bg-black/55 backdrop-blur-md border border-jawa-gold/30 rounded-2xl p-5 shadow-2xl">
      <p class="text-[11px] text-stone-300">Kepada Yth. Bapak/Ibu/Saudara/i</p>
      <h3 class="font-serif text-[1.35rem] font-semibold my-1 text-jawa-lightgold leading-snug px-2">{{ $toName }}</h3>
      <p class="text-[10px] text-stone-400 mb-4 leading-relaxed">Tanpa mengurangi rasa hormat, kami mengundang Anda<br/>untuk hadir di acara pernikahan kami</p>
      <button id="btn-open" class="btn-gold-gradient w-full py-3 px-6 rounded-full text-stone-950 font-semibold text-xs uppercase tracking-[0.15em] flex items-center justify-center gap-2">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        Buka Undangan
      </button>
    </div>
  </section>

  <!-- ================= 3. HERO ================= -->
  <section id="hero-section" class="relative h-[100dvh] min-h-[600px] w-full flex flex-col items-center justify-center text-center px-6 overflow-hidden">
    <div class="janur-grow pointer-events-none absolute top-[1%] left-[-5%] w-[60%] z-[1]" data-origin="top left">{!! jwaJanur() !!}</div>
    <div class="janur-grow pointer-events-none absolute top-[1%] right-[-5%] w-[60%] z-[1]" data-origin="top right">{!! jwaJanur(true) !!}</div>
    <img id="hero-bg" src="{{ $ASSET }}BG-ID-4.png" class="orn pointer-events-none absolute inset-0 w-full h-full object-cover opacity-25 scale-110" alt=""  data-speed="0.4" data-rot="0" />
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-jawa-bg/90 via-transparent to-[#16100c]"></div>
    <canvas id="gold-particles" class="pointer-events-none absolute inset-0 w-full h-full z-10"></canvas>

    {!! jwaCorners(0, 'hero-ornament z-10 opacity-0') !!}
    <img src="{{ $ASSET }}ASSET-JAWA-REV-7-REV.png" class="orn hero-stagger hero-joglo pointer-events-none absolute w-[68%] max-w-[290px] opacity-0 z-10" alt=""  data-speed="0.6" data-rot="0" />

    <div class="hero-content relative z-20 flex flex-col items-center px-4">
      <p class="hero-stagger text-[10px] uppercase tracking-[0.42em] text-jawa-gold mb-4 opacity-0">The Wedding Of</p>
      <h2 class="hero-stagger font-cinzel text-[clamp(2rem,8.5vw,2.9rem)] leading-tight text-gold-gradient opacity-0">{{ $groomNick }}</h2>
      <span class="hero-stagger font-serif italic text-2xl text-jawa-lightgold my-2 opacity-0">&amp;</span>
      <h2 class="hero-stagger font-cinzel text-[clamp(2rem,8.5vw,2.9rem)] leading-tight text-gold-gradient opacity-0">{{ $brideNick }}</h2>
      <div class="hero-stagger mt-7 opacity-0">
        <span class="inline-block border-y border-jawa-gold/40 py-2 px-7 text-[11px] tracking-[0.4em] text-jawa-lightgold">{{ $dateShort }}</span>
      </div>
    </div>


    <div class="hero-stagger absolute bottom-[13%] z-20 opacity-0">
      <p class="text-[9px] tracking-[0.4em] text-jawa-gold/70 uppercase">Geser ke bawah</p>
    </div>
  </section>

  <!-- ================= 4. QUOTES ================= -->
  <section class="scroll-section relative min-h-[100dvh] py-24 px-6 flex flex-col items-center justify-center text-center overflow-hidden">
    {!! jwaCorners(1) !!}

    <div class="anim-scale relative z-10 w-full max-w-[340px] mx-auto card-dark px-6 pb-9 pt-7 mt-10">
      <div class="orn -mt-[4.7rem] mb-4" data-speed="0.8" data-rot="0">
        <img src="{{ $ASSET }}1225-GUNUNGAN-JAWA-576x1024.png" class="sway-node sway-subtle w-[30%] max-w-[100px] mx-auto drop-shadow-[0_8px_18px_rgba(212,175,55,0.35)]" alt="Gunungan" />
      </div>
      <h3 class="anim-fade font-cinzel text-xl text-gold-gradient mb-4">With Love</h3>
      <p class="anim-fade font-serif text-[16px] leading-[1.9] text-stone-200 italic">
        &ldquo;Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang.&rdquo;
      </p>
      <div class="hairline-gold w-24 mx-auto my-4"></div>
      <p class="anim-fade text-[11px] font-semibold tracking-[0.25em] uppercase text-jawa-gold">QS. Ar-R&umacr;m : 21</p>
    </div>

    <div class="orn anim-left pointer-events-none absolute bottom-[9%] left-[2%] w-[26%] z-20" data-speed="1.6" data-rot="0">
      <img src="{{ $ASSET }}1225-ASSET-JAWA-WAYANG-1.png" class="sway-node sway-subtle w-full drop-shadow-xl" alt="" />
    </div>
    <div class="orn anim-right pointer-events-none absolute bottom-[9%] right-[2%] w-[26%] z-20" data-speed="1.8" data-rot="0">
      <img src="{{ $ASSET }}1225-ASSET-JAWA-WAYANG.png" class="sway-node sway-subtle w-full drop-shadow-xl" alt="" />
    </div>
  </section>

  <!-- ================= 5. MEMPELAI ================= -->
  <section class="scroll-section relative min-h-[100dvh] py-24 px-6 flex flex-col items-center justify-center text-center overflow-hidden bg-jawa-bg/60">
    <div class="orn anim-left pointer-events-none absolute top-[20%] -left-[20%] w-[46%] opacity-15 z-0" data-speed="0.9" data-rot="0">
      <img src="{{ $ASSET }}ASSET-JAWA-GUNUNGAN.png" class="sway-node sway-slow w-full" alt="" />
    </div>
    <div class="orn anim-right pointer-events-none absolute top-[20%] -right-[20%] w-[46%] opacity-15 z-0" data-speed="0.9" data-rot="0">
      <img src="{{ $ASSET }}ASSET-JAWA-GUNUNGAN.png" class="sway-node sway-slow w-full scale-x-[-1]" alt="" />
    </div>
    {!! jwaCorners(0) !!}

    <div class="relative z-10 w-full max-w-[330px] mx-auto mb-10">
      <img src="{{ $ASSET }}ASSET-JAWA-REV-8-REVISII.png" class="orn anim-scale sec-head-orn" alt=""  data-speed="0.7" data-rot="0" />
      <h2 class="anim-fade font-cinzel text-[1.65rem] text-gold-gradient mb-3">Kedua Mempelai</h2>
      <p class="anim-fade text-[13px] leading-relaxed text-stone-300 px-2">
        Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud mengundang Bapak/Ibu/Saudara/i pada acara pernikahan kami:
      </p>
    </div>

    <div class="anim-scale relative z-10 w-full max-w-[310px] card-dark p-6">
      <div class="relative w-32 h-44 mx-auto mb-5 rounded-t-full overflow-hidden border-2 border-jawa-gold/80 bg-black/60 flex items-center justify-center">
        @if(jwaGet('bride_photo'))
        <img src="{{ jwaGet('bride_photo') }}" class="w-full h-full object-cover" alt="{{ $brideFull }}" />
        @else
        <img src="{{ $ASSET }}1225-ASSET-JAWA-WAYANG.png" class="w-24 h-auto object-contain mt-4 opacity-90" alt="Mempelai Wanita" />
        @endif
        <div class="absolute inset-0 rounded-t-full border border-jawa-lightgold/30 pointer-events-none" style="margin:5px;"></div>
      </div>
      <h3 class="font-cinzel text-[1.15rem] text-gold-gradient leading-snug">{{ $brideFull }}</h3>
      <p class="text-[11px] text-stone-400 mt-2">{{ jwaGet('bride_child_of','Putri') }} dari</p>
      <p class="text-[13px] font-medium text-jawa-lightgold mt-0.5">{{ jwaGet('bride_parents','') }}</p>
      @if(jwaGet('bride_instagram'))
      <a href="https://instagram.com/{{ ltrim(jwaGet('bride_instagram'),'@') }}" target="_blank" rel="noopener" class="btn-outline-gold inline-block mt-4 text-[10px] tracking-[0.2em] px-5 py-2 rounded-full">&#64;{{ ltrim(jwaGet('bride_instagram'),'@') }}</a>
      @endif
    </div>

    <div class="anim-fade font-cinzel text-[1.7rem] text-gold-gradient my-6 z-10">&amp;</div>

    <div class="anim-scale relative z-10 w-full max-w-[310px] card-dark p-6 mb-12">
      <div class="relative w-32 h-44 mx-auto mb-5 rounded-t-full overflow-hidden border-2 border-jawa-gold/80 bg-black/60 flex items-center justify-center">
        @if(jwaGet('groom_photo'))
        <img src="{{ jwaGet('groom_photo') }}" class="w-full h-full object-cover" alt="{{ $groomFull }}" />
        @else
        <img src="{{ $ASSET }}1225-ASSET-JAWA-WAYANG-1.png" class="w-24 h-auto object-contain mt-4 opacity-90" alt="Mempelai Pria" />
        @endif
        <div class="absolute inset-0 rounded-t-full border border-jawa-lightgold/30 pointer-events-none" style="margin:5px;"></div>
      </div>
      <h3 class="font-cinzel text-[1.15rem] text-gold-gradient leading-snug">{{ $groomFull }}</h3>
      <p class="text-[11px] text-stone-400 mt-2">{{ jwaGet('groom_child_of','Putra') }} dari</p>
      <p class="text-[13px] font-medium text-jawa-lightgold mt-0.5">{{ jwaGet('groom_parents','') }}</p>
      @if(jwaGet('groom_instagram'))
      <a href="https://instagram.com/{{ ltrim(jwaGet('groom_instagram'),'@') }}" target="_blank" rel="noopener" class="btn-outline-gold inline-block mt-4 text-[10px] tracking-[0.2em] px-5 py-2 rounded-full">&#64;{{ ltrim(jwaGet('groom_instagram'),'@') }}</a>
      @endif
    </div>

  </section>

  <!-- ================= 6. ACARA & COUNTDOWN ================= -->
  <section class="scroll-section relative min-h-[100dvh] py-24 px-6 flex flex-col items-center justify-center text-center overflow-hidden">
    {!! jwaCorners(1) !!}

    <div class="relative z-10 w-full max-w-[340px] mx-auto">
      <img src="{{ $ASSET }}ASSET-JAWA-REV-8-REVISII.png" class="orn anim-scale sec-head-orn" alt=""  data-speed="0.7" data-rot="0" />
      <h2 class="anim-fade font-cinzel text-[1.65rem] text-gold-gradient mb-2">Rangkaian Acara</h2>
      <p class="anim-fade text-[11px] tracking-[0.3em] uppercase text-jawa-gold/80 mb-8">Menuju hari bahagia</p>

      <div class="anim-scale grid grid-cols-4 gap-2 mb-10">
        <div class="card-dark py-4"><div class="font-cinzel text-[1.5rem] text-gold-gradient" id="cd-d">00</div><div class="text-[9px] tracking-[0.25em] text-stone-400 mt-1">HARI</div></div>
        <div class="card-dark py-4"><div class="font-cinzel text-[1.5rem] text-gold-gradient" id="cd-h">00</div><div class="text-[9px] tracking-[0.25em] text-stone-400 mt-1">JAM</div></div>
        <div class="card-dark py-4"><div class="font-cinzel text-[1.5rem] text-gold-gradient" id="cd-m">00</div><div class="text-[9px] tracking-[0.25em] text-stone-400 mt-1">MENIT</div></div>
        <div class="card-dark py-4"><div class="font-cinzel text-[1.5rem] text-gold-gradient" id="cd-s">00</div><div class="text-[9px] tracking-[0.25em] text-stone-400 mt-1">DETIK</div></div>
      </div>

<div class="anim-scale card-dark px-6 py-8 mb-5 text-center">
        <h3 class="font-serif text-[1.3rem] tracking-[0.3em] text-jawa-lightgold" style="padding-left:0.3em">AKAD NIKAH</h3>
        <div class="hairline-gold w-28 mx-auto my-5"></div>
        <p class="font-serif text-[1.1rem] text-stone-100">{{ jwaDate($wDate) }}</p>
        <p class="text-[13px] text-stone-400 mt-1.5">{{ $wTime }} &ndash; {{ $wTimeE }} {{ $tzLabel }}</p>
        <div class="flex items-center justify-center gap-3 my-6">
          <div class="hairline-gold w-14"></div>
          <svg class="w-5 h-5 text-jawa-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M4 21V10l8-6 8 6v11M9 21v-6h6v6M9 13h.01M15 13h.01"/></svg>
          <div class="hairline-gold w-14"></div>
        </div>
        <p class="font-semibold text-[15px] text-jawa-lightgold leading-snug px-2">{{ jwaGet('wedding_location','Kediaman Mempelai') }}</p>
        @if(!$sameLoc && jwaGet('wedding_map_link'))
        <a href="{{ jwaGet('wedding_map_link') }}" target="_blank" rel="noopener" class="btn-gold-gradient inline-flex items-center gap-2 mt-6 text-stone-950 text-[11px] font-semibold tracking-[0.2em] pl-[0.2em] px-8 py-3 rounded-full"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>LIHAT LOKASI</a>
        @endif
      </div>

<div class="anim-scale card-dark px-6 py-8 mb-5 text-center">
        <h3 class="font-serif text-[1.3rem] tracking-[0.3em] text-jawa-lightgold" style="padding-left:0.3em">RESEPSI</h3>
        <div class="hairline-gold w-28 mx-auto my-5"></div>
        <p class="font-serif text-[1.1rem] text-stone-100">{{ jwaDate($rDate) }}</p>
        <p class="text-[13px] text-stone-400 mt-1.5">{{ $rTimeS }} &ndash; {{ $rTimeE }} {{ $tzLabel }}</p>
        <div class="flex items-center justify-center gap-3 my-6">
          <div class="hairline-gold w-14"></div>
          <svg class="w-5 h-5 text-jawa-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M4 21V10l8-6 8 6v11M9 21v-6h6v6M9 13h.01M15 13h.01"/></svg>
          <div class="hairline-gold w-14"></div>
        </div>
        <p class="font-semibold text-[15px] text-jawa-lightgold leading-snug px-2">{{ jwaGet('reception_location', jwaGet('wedding_location','Kediaman Mempelai')) }}</p>
        <div class="mt-6 flex flex-wrap justify-center gap-2">
          @if($sameLoc)
            @if($mainMapLink)
            <a href="{{ $mainMapLink }}" target="_blank" rel="noopener" class="btn-gold-gradient inline-flex items-center gap-2 text-stone-950 text-[11px] font-semibold tracking-[0.2em] px-8 py-3 rounded-full"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>LIHAT PETA LOKASI</a>
            @endif
          @else
            @if(jwaGet('reception_map_link'))
            <a href="{{ jwaGet('reception_map_link') }}" target="_blank" rel="noopener" class="btn-gold-gradient inline-flex items-center gap-2 text-stone-950 text-[11px] font-semibold tracking-[0.2em] px-8 py-3 rounded-full"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>LIHAT LOKASI</a>
            @endif
          @endif
          @if(jwaGet('live_stream_link'))
          <a href="{{ jwaGet('live_stream_link') }}" target="_blank" rel="noopener" class="btn-outline-gold text-[11px] tracking-[0.2em] px-7 py-3 rounded-full">LIVE STREAMING</a>
          @endif
        </div>
      </div>

      <a href="{{ $gcalUrl }}" target="_blank" rel="noopener" class="anim-fade btn-outline-gold inline-block text-[10px] tracking-[0.25em] px-7 py-3 rounded-full mb-20">+ SIMPAN KE GOOGLE CALENDAR</a>
    </div>

  </section>

  <!-- ================= 7. GALERI ================= -->
  <section class="scroll-section relative py-24 px-6 flex flex-col items-center text-center overflow-hidden bg-jawa-bg/60">
    {!! jwaCorners(0) !!}
    <img src="{{ $ASSET }}ASSET-JAWA-REV-8-REVISII.png" class="orn anim-scale sec-head-orn relative z-10" alt=""  data-speed="0.7" data-rot="0" />
    <h2 class="anim-fade font-cinzel text-[1.65rem] text-gold-gradient mb-2 relative z-10">Galeri Kenangan</h2>
    <p class="anim-fade text-[11px] tracking-[0.3em] uppercase text-jawa-gold/80 mb-8">Momen berharga kami</p>
    @if(!empty($gallery) && count($gallery))
    <div class="relative z-10 w-full max-w-[340px] grid grid-cols-2 gap-3">
      @foreach(array_slice($gallery, 0, 8) as $i => $img)
      <div class="anim-scale gal-item relative overflow-hidden rounded-2xl border border-jawa-gold/25 cursor-pointer {{ $i % 3 === 0 ? 'row-span-2' : '' }}" data-full="{{ $img }}">
        <img src="{{ $img }}" alt="Galeri {{ $i+1 }}" loading="lazy" class="w-full {{ $i % 3 === 0 ? 'h-64' : 'h-[7.5rem]' }} object-cover transition-transform duration-700 hover:scale-110" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 hover:opacity-100 transition"></div>
      </div>
      @endforeach
    </div>
    @else
    <p class="anim-fade font-serif italic text-stone-400">Galeri kenangan akan segera hadir.</p>
    @endif
  </section>

  <!-- ================= 8. KISAH CINTA ================= -->
  <section class="scroll-section relative py-24 px-6 flex flex-col items-center text-center overflow-hidden">
    <div class="orn anim-left pointer-events-none absolute top-[14%] -left-[24%] w-[36%] opacity-10 z-0" data-speed="0.9" data-rot="0">
      <img src="{{ $ASSET }}ASSET-JAWA-GUNUNGAN.png" class="sway-node sway-slow w-full" alt="" />
    </div>
    <div class="orn anim-right pointer-events-none absolute top-[14%] -right-[24%] w-[36%] opacity-10 z-0" data-speed="0.9" data-rot="0">
      <img src="{{ $ASSET }}ASSET-JAWA-GUNUNGAN.png" class="sway-node sway-slow w-full scale-x-[-1]" alt="" />
    </div>
    {!! jwaCorners(1, '', 'flowers') !!}
    <img src="{{ $ASSET }}ASSET-JAWA-REV-8-REVISII.png" class="orn anim-scale sec-head-orn relative z-10" alt=""  data-speed="0.7" data-rot="0" />
    <h2 class="anim-fade font-cinzel text-[1.65rem] text-gold-gradient mb-2 relative z-10">Kisah Cinta</h2>
    <p class="anim-fade text-[11px] tracking-[0.3em] uppercase text-jawa-gold/80 mb-10 relative z-10">Perjalanan kami berdua</p>
    @if(!empty($stories) && count($stories))
    <div class="relative z-10 w-full max-w-[320px] pl-8 pb-24 text-left">
      <div class="absolute left-[9px] top-2 bottom-2 w-px bg-jawa-gold/40"></div>
      @foreach($stories as $st)
      <div class="anim-fade relative mb-9">
        <div class="absolute -left-8 top-1 w-[18px] h-[18px] rounded-full bg-jawa-gold border-2 border-jawa-lightgold shadow-[0_0_14px_rgba(212,175,55,.6)]"></div>
        @if(!empty($st->tahun))
        <p class="text-jawa-gold tracking-[0.3em] text-[10px] font-semibold mb-1">{{ $st->tahun }}</p>
        @endif
        <h4 class="font-cinzel text-lg text-jawa-lightgold mb-1">{{ $st->judul }}</h4>
        <p class="font-serif italic text-[15px] text-stone-300 leading-relaxed">{{ $st->isi }}</p>
      </div>
      @endforeach
    </div>
    @else
    <p class="anim-fade font-serif italic text-stone-400 relative z-10">Kisah cinta kami akan segera ditulis di sini.</p>
    @endif
  </section>

  <!-- ================= 9. RSVP & BUKU TAMU ================= -->
  <section class="scroll-section relative py-24 px-6 flex flex-col items-center text-center overflow-hidden bg-jawa-bg/60">
    {!! jwaCorners(0) !!}
    <img src="{{ $ASSET }}ASSET-JAWA-REV-8-REVISII.png" class="orn anim-scale sec-head-orn relative z-10" alt=""  data-speed="0.7" data-rot="0" />
    <h2 class="anim-fade font-cinzel text-[1.65rem] text-gold-gradient mb-2 relative z-10">RSVP &amp; Buku Tamu</h2>
    <p class="anim-fade text-[11px] tracking-[0.3em] uppercase text-jawa-gold/80 mb-8">Konfirmasi kehadiran Anda</p>

    <form id="rsvpForm" class="anim-scale relative z-10 w-full max-w-[340px] card-dark p-6 mb-6 text-left">
      <label class="block text-[10px] tracking-[0.25em] text-jawa-gold font-semibold mb-2">NAMA LENGKAP</label>
      <input id="rsvpNama" required maxlength="60" placeholder="Tulis nama Anda..." class="field-dark mb-4" />
      <label class="block text-[10px] tracking-[0.25em] text-jawa-gold font-semibold mb-2">KEHADIRAN</label>
      <div class="grid grid-cols-2 gap-2 mb-4" id="attendWrap">
        <button type="button" data-val="hadir" class="attend-btn border rounded-xl py-3 text-[11px] tracking-[0.2em] font-semibold transition border-jawa-gold bg-jawa-gold/15 text-jawa-lightgold">HADIR</button>
        <button type="button" data-val="tidak" class="attend-btn border rounded-xl py-3 text-[11px] tracking-[0.2em] font-semibold transition border-stone-600 text-stone-400">BERHALANGAN</button>
      </div>
      <label class="block text-[10px] tracking-[0.25em] text-jawa-gold font-semibold mb-2">JUMLAH TAMU</label>
      <select id="rsvpJumlah" class="field-dark mb-5">
        <option>1</option><option>2</option><option>3</option><option>4</option><option>5</option>
      </select>
      <button type="submit" id="rsvpBtn" class="btn-gold-gradient w-full text-stone-950 font-semibold tracking-[0.2em] text-xs py-3.5 rounded-xl">KIRIM KONFIRMASI</button>
    </form>

    <div class="anim-scale relative z-10 w-full max-w-[340px] card-dark p-6 text-left">
      <p class="text-jawa-gold tracking-[0.3em] text-[10px] font-semibold mb-5 text-center">BUKU TAMU</p>
      <form id="wishForm" class="mb-5">
        <input id="wishNama" required maxlength="40" placeholder="Nama Anda..." class="field-dark mb-3" />
        <textarea id="wishPesan" required maxlength="300" rows="3" placeholder="Tulis ucapan & doa terbaik..." class="field-dark mb-3 resize-none"></textarea>
        <button type="submit" id="wishBtn" class="btn-outline-gold w-full text-[11px] tracking-[0.25em] font-semibold py-3 rounded-xl">KIRIM UCAPAN</button>
      </form>
      <div id="wishList" class="space-y-3 max-h-80 overflow-y-auto pr-1">
        <p class="text-center text-stone-500 font-serif italic text-sm" id="wishLoading">Memuat ucapan...</p>
      </div>
    </div>
  </section>

  <!-- ================= 10. AMPLOP DIGITAL ================= -->
  <section class="scroll-section relative py-24 px-6 flex flex-col items-center text-center overflow-hidden">
    {!! jwaCorners(1) !!}
    <img src="{{ $ASSET }}ASSET-JAWA-REV-8-REVISII.png" class="orn anim-scale sec-head-orn relative z-10" alt=""  data-speed="0.7" data-rot="0" />
    <h2 class="anim-fade font-cinzel text-[1.65rem] text-gold-gradient mb-3 relative z-10">Amplop Digital</h2>
    <p class="anim-fade font-serif italic text-stone-300 text-[15px] leading-relaxed mb-8 max-w-[300px] relative z-10">Doa restu Anda adalah hadiah terindah. Namun jika berkenan memberi tanda kasih, kami sediakan dengan penuh syukur.</p>
    @if(!empty($giftRecipient))
    <p class="anim-fade text-xs text-stone-400 mb-6 relative z-10">Penerima: <span class="font-semibold text-jawa-lightgold">{{ $giftRecipient }}</span></p>
    @endif
    @if(!empty($accounts))
    <div class="relative z-10 w-full max-w-[340px] space-y-4">
      @foreach($accounts as $i => $a)
      <div class="anim-scale rounded-2xl p-[1.5px]" style="background:linear-gradient(135deg,#8a6a1f,#d4af37,#f6e27a,#d4af37,#8a6a1f)">
        <div class="bg-[#100b08] rounded-2xl p-5 text-left">
          <div class="flex items-center justify-between mb-3">
            <p class="text-jawa-gold tracking-[0.25em] text-[11px] font-semibold">{{ $a['bank'] }}</p>
            @if(!empty($a['logo']))<img src="{{ $a['logo'] }}" alt="Logo bank" class="h-7 max-w-[100px] object-contain bg-jawa-cream rounded px-1" />@endif
          </div>
          <p class="font-cinzel text-jawa-lightgold text-xl tracking-[0.12em]" id="accNo{{ $i }}">{{ $a['no'] }}</p>
          <p class="text-stone-400 text-xs mt-1 mb-4">a.n. {{ $a['an'] }}</p>
          <button class="btn-gold-gradient copy-btn w-full text-stone-950 text-[10px] font-semibold tracking-[0.25em] py-3 rounded-xl" data-i="{{ $i }}">SALIN NOMOR</button>
        </div>
      </div>
      @endforeach
    </div>
    @else
    <p class="anim-fade font-serif italic text-stone-400 relative z-10">Informasi amplop digital akan segera hadir.</p>
    @endif
    @if(jwaGet('gift_address'))
    <div class="anim-scale relative z-10 w-full max-w-[340px] card-dark p-5 mt-5 mb-24 text-left">
      <p class="text-jawa-gold tracking-[0.25em] text-[10px] font-semibold mb-2">KIRIM HADIAH FISIK</p>
      <p class="text-stone-300 text-sm leading-relaxed">{{ jwaGet('gift_address') }}</p>
      @if(jwaGet('gift_maps_link'))
      <a href="{{ jwaGet('gift_maps_link') }}" target="_blank" rel="noopener" class="btn-outline-gold inline-block mt-3 text-[10px] tracking-[0.25em] px-5 py-2.5 rounded-full">BUKA PETA</a>
      @endif
    </div>
    @endif

  </section>

  <!-- ================= 11. FOOTER ================= -->
  <footer class="relative px-6 pt-20 pb-28 text-center overflow-hidden bg-jawa-bg/80">
    <img src="{{ $ASSET }}ASSET-GUNUNGAN.png" class="orn sway-node sway-subtle w-[26%] max-w-[110px] mx-auto mb-5 opacity-90" alt=""  data-speed="0.8" data-rot="0" />
    <p class="anim-fade font-serif italic text-stone-300 text-[16px] max-w-[300px] mx-auto leading-relaxed mb-6">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu kepada kami.</p>
    <p class="anim-fade text-stone-500 text-xs mb-2 tracking-[0.2em] uppercase">Kami yang berbahagia,</p>
    <h4 class="anim-fade font-cinzel text-[1.7rem] text-gold-gradient mb-6">{{ $groomNick }} &amp; {{ $brideNick }}</h4>
    <div class="hairline-gold w-40 mx-auto mb-5"></div>
    <p class="anim-fade text-stone-500 text-[10px] tracking-[0.35em] uppercase">Matur Nuwun &bull; {{ jwaDate($rDate) }}</p>
  </footer>
</main>

<!-- musik melayang -->
<button id="musicFloat" aria-label="Putar / jeda musik" hidden>
  <svg class="note w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55A4 4 0 1 0 14 17V7h4V3h-6z"/></svg>
  <span class="bars"><span></span><span></span><span></span></span>
</button>
<div id="toast"></div>

<!-- lightbox -->
<div id="lightbox" class="fixed inset-0 z-[80] bg-black/95 hidden items-center justify-center p-4">
  <button id="lbClose" class="absolute top-5 right-5 text-jawa-cream text-3xl leading-none px-2" aria-label="Tutup">&times;</button>
  <button id="lbPrev" class="absolute left-2 text-jawa-cream text-4xl px-3" aria-label="Sebelumnya">&#8249;</button>
  <img id="lbImg" src="" alt="Galeri" class="max-h-[85vh] max-w-full rounded-lg border border-jawa-gold/40 object-contain" />
  <button id="lbNext" class="absolute right-2 text-jawa-cream text-4xl px-3" aria-label="Berikutnya">&#8250;</button>
  <p id="lbCount" class="absolute bottom-6 text-jawa-cream/70 tracking-[0.3em] text-xs"></p>
</div>

<script>
"use strict";
const JWA = {
  invId: {{ (int)$invId }},
  countdownISO: @json($countdownISO),
  musicUrl: @json($musicUrl),
  guestName: @json($toName)
};
const reduced = matchMedia("(prefers-reduced-motion: reduce)").matches;
function toast(m){ const t=document.getElementById("toast"); t.textContent=m; t.classList.add("show");
  clearTimeout(t._h); t._h=setTimeout(()=>t.classList.remove("show"),2600); }
function esc(s){ return String(s??"").replace(/[&<>"']/g,c=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"}[c])); }
function timeAgo(s){ const d=new Date(String(s).replace(" ","T")+"+07:00"); if(isNaN(d)) return "";
  const n=(Date.now()-d.getTime())/1000; if(n<60) return "Baru saja";
  if(n<3600) return Math.floor(n/60)+" menit yang lalu";
  if(n<86400) return Math.floor(n/3600)+" jam yang lalu";
  return Math.floor(n/86400)+" hari yang lalu"; }

gsap.registerPlugin(ScrollTrigger);

/* ---------- 1. PRELOADER ---------- */
(function(){
  const bar=document.getElementById("loader-bar"), pct=document.getElementById("loader-percent");
  const prog={v:0};
  gsap.to(prog,{ v:100, duration:1.3, ease:"power2.inOut",
    onUpdate:()=>{ const n=Math.round(prog.v); pct.textContent=n+"%"; bar.style.width=n+"%"; },
    onComplete:()=> gsap.to("#preloader",{ opacity:0, duration:.55, ease:"power2.out",
      onComplete:()=>{ const p=document.getElementById("preloader"); if(p) p.remove(); } })
  });
})();

/* ---------- 1b. JANUR TUMBUH (scroll-scrub, reversibel, tanpa looping) ---------- */
(function(){
  if(reduced) return;
  gsap.utils.toArray(".janur-grow").forEach(function(el){
    var trig = el.closest("section") || el;
    gsap.fromTo(el, {scale:0, transformOrigin: el.getAttribute("data-origin") || "top left"},
      {scale:1, ease:"none", scrollTrigger:{trigger: trig, start:"top bottom", end:"top 30%", scrub:0.6}});
  });
})();

/* ---------- 2. PARALLAX SCROLL (semua ornamen bergerak ikut scroll, tanpa looping) ---------- */
function initParallax(){
  if(reduced) return;
  gsap.utils.toArray(".orn").forEach(el=>{
    const sp=parseFloat(el.dataset.speed||"1"), rot=parseFloat(el.dataset.rot||"0");
    const dist=()=>Math.round(window.innerHeight*0.13*sp);
    gsap.fromTo(el,{y:()=>-dist()},{y:()=>dist(),rotation:rot,ease:"none",
      scrollTrigger:{trigger:el.closest("section, footer")||el,
        start:"top bottom",end:"bottom top",scrub:1.1,invalidateOnRefresh:true}});
  });
  // konten hero melayang & memudar saat discroll
  gsap.to(".hero-content",{yPercent:-16,opacity:0,ease:"none",
    scrollTrigger:{trigger:"#hero-section",start:"top top",end:"bottom 35%",scrub:true}});
  /* mekar tumbuh: rangkaian bunga/pohon tiap section mekar saat masuk layar */
  gsap.utils.toArray(".scroll-section, #hero-section").forEach(sec=>{
    const blooms=sec.querySelectorAll(":scope .bloom");
    if(!blooms.length) return;
    gsap.fromTo(blooms,{scale:0,opacity:0,transformOrigin:"50% 50%"},
      {scale:1,opacity:1,duration:.9,ease:"back.out(1.5)",stagger:.12,
       scrollTrigger:{trigger:sec,start:"top 72%",toggleActions:"play none none reverse"}});
  });
}

/* ---------- 3. ANIMASI SCROLL PER SECTION ---------- */
function initSectionScrollAnimations(){
  gsap.utils.toArray(".scroll-section").forEach(sec=>{
    const L=sec.querySelectorAll(".anim-left"), R=sec.querySelectorAll(".anim-right"),
          U=sec.querySelectorAll(".anim-up"), S=sec.querySelectorAll(".anim-scale"),
          F=sec.querySelectorAll(".anim-fade");
    const tl=gsap.timeline({ scrollTrigger:{ trigger:sec, start:"top 78%", end:"bottom 25%",
      toggleActions:"play none none reverse" }});
    if(L.length) tl.fromTo(L,{x:-36,opacity:0},{x:0,opacity:1,duration:1.1,ease:"expo.out",stagger:.1},0);
    if(R.length) tl.fromTo(R,{x:36,opacity:0},{x:0,opacity:1,duration:1.1,ease:"expo.out",stagger:.1},0);
    if(S.length) tl.fromTo(S,{scale:.94,y:26,opacity:0},{scale:1,y:0,opacity:1,duration:1,ease:"power3.out",stagger:.12},.08);
    if(F.length) tl.fromTo(F,{y:20,opacity:0},{y:0,opacity:1,duration:.9,ease:"power2.out",stagger:.08},.18);
    if(U.length) tl.fromTo(U,{y:36,opacity:0},{y:0,opacity:1,duration:1,ease:"expo.out",stagger:.08},.12);
  });
  ScrollTrigger.refresh();
}

/* ---------- 4. BUKA UNDANGAN ---------- */
document.getElementById("btn-open").addEventListener("click",()=>{
  gsap.set(".hero-ornament",{opacity:0});
  gsap.set(".hero-stagger",{y:22,opacity:0});
  const tl=gsap.timeline({ onComplete:()=>{
    document.body.classList.remove("is-locked");
    const g=document.getElementById("cover-gate"); if(g) g.remove();
    document.getElementById("musicFloat").hidden=false;
    initSectionScrollAnimations();
    initParallax();
  }});
  tl.to(["#gate-header","#gate-center","#gate-footer"],{y:-14,opacity:0,duration:.5,stagger:.06,ease:"power2.in"},0)
    .to(["#gate-left-tree","#gate-left-gunungan"],{xPercent:-38,opacity:0,duration:1,ease:"expo.inOut"},.12)
    .to(["#gate-right-tree","#gate-right-gunungan"],{xPercent:38,opacity:0,duration:1,ease:"expo.inOut"},.12)
    .to("#cover-gate",{opacity:0,duration:.6,ease:"power2.inOut"},.45)
    .to("#hero-bg",{scale:1,duration:1.8,ease:"expo.out"},.5)
    .to(".hero-ornament",{opacity:1,duration:1.2,ease:"expo.out"},.55)
    .to(".hero-joglo",{opacity:.32,duration:1,ease:"power2.out"},.62)
    .to(".hero-stagger:not(.hero-joglo)",{y:0,opacity:1,duration:.9,stagger:.1,ease:"power3.out"},.65);
});

/* ---------- 5. PARTIKEL EMAS HERO ---------- */
(function(){
  const cv=document.getElementById("gold-particles"); if(!cv) return;
  const cx=cv.getContext("2d"); let ps=[];
  function init(){ cv.width=cv.offsetWidth; cv.height=cv.offsetHeight;
    const n = cv.width<380?22:30;
    ps=Array.from({length:n},()=>({ x:Math.random()*cv.width, y:Math.random()*cv.height,
      r:Math.random()*1.8+.6, vy:-(Math.random()*.35+.12), vx:(Math.random()-.5)*.2,
      a:Math.random()*.55+.2 }));
  }
  function draw(){ cx.clearRect(0,0,cv.width,cv.height);
    for(const p of ps){ cx.beginPath(); cx.arc(p.x,p.y,p.r,0,6.29);
      cx.fillStyle=`rgba(245,223,152,${p.a})`; cx.fill();
      p.y+=p.vy; p.x+=p.vx;
      if(p.y<-5){p.y=cv.height+5;p.x=Math.random()*cv.width;}
      if(p.x<-5)p.x=cv.width+5; if(p.x>cv.width+5)p.x=-5;
    }
    if(!reduced) requestAnimationFrame(draw);
  }
  addEventListener("resize",init); init(); draw();
})();

/* ---------- 6. COUNTDOWN ---------- */
(function(){
  const target=new Date(JWA.countdownISO).getTime(), pad=n=>String(n).padStart(2,"0");
  const el={ d:document.getElementById("cd-d"), h:document.getElementById("cd-h"),
             m:document.getElementById("cd-m"), s:document.getElementById("cd-s") };
  function tick(){ const d=Math.max(0,target-Date.now());
    el.d.textContent=pad(Math.floor(d/864e5)); el.h.textContent=pad(Math.floor(d/36e5)%24);
    el.m.textContent=pad(Math.floor(d/6e4)%60); el.s.textContent=pad(Math.floor(d/1e3)%60); }
  tick(); setInterval(tick,1000);
})();

/* ---------- 7. MUSIK ---------- */
let audioEl=null, musicOn=false;
async function resolveMusicUrl(){
  if(JWA.musicUrl) return JWA.musicUrl;
  try{ const r=await fetch("/api/music?inv_id="+JWA.invId); const j=await r.json();
    if(j&&j.url) return j.url; }catch(e){}
  return null;
}
document.getElementById("musicFloat").addEventListener("click", async function(){
  musicOn=!musicOn; this.classList.toggle("playing",musicOn);
  if(musicOn){
    const url=await resolveMusicUrl();
    if(url){ audioEl=new Audio(url); audioEl.loop=true; audioEl.volume=.7;
      try{ await audioEl.play(); toast("Musik diputar"); }
      catch(e){ musicOn=false; this.classList.remove("playing"); toast("Musik tidak dapat diputar"); }
    } else { musicOn=false; this.classList.remove("playing"); toast("Belum ada musik pengiring"); }
  } else { if(audioEl){ audioEl.pause(); audioEl=null; } toast("Musik dijeda"); }
});

/* ---------- 8. RSVP ---------- */
let attendVal="hadir";
document.querySelectorAll(".attend-btn").forEach(b=>b.addEventListener("click",()=>{
  attendVal=b.dataset.val;
  document.querySelectorAll(".attend-btn").forEach(x=>{ const on=x===b;
    x.classList.toggle("border-jawa-gold",on); x.classList.toggle("bg-jawa-gold/15",on);
    x.classList.toggle("text-jawa-lightgold",on); x.classList.toggle("border-stone-600",!on);
    x.classList.toggle("text-stone-400",!on); });
}));
document.getElementById("rsvpForm").addEventListener("submit", async e=>{
  e.preventDefault();
  const nama=document.getElementById("rsvpNama").value.trim();
  if(!nama){ toast("Mohon isi nama Anda"); return; }
  const btn=document.getElementById("rsvpBtn"); btn.disabled=true; btn.textContent="MENGIRIM...";
  try{
    const r=await fetch("/api/rsvp",{ method:"POST", headers:{"Content-Type":"application/json"},
      body:JSON.stringify({ invitation_id:JWA.invId, nama,
        jumlah_tamu:parseInt(document.getElementById("rsvpJumlah").value)||1, status:attendVal, alasan:"" }) });
    const j=await r.json(); if(!j.success) throw new Error(j.message||"Gagal");
    e.target.reset(); toast("Matur nuwun! Konfirmasi terkirim.");
  }catch(err){ toast("Gagal: "+err.message); }
  btn.disabled=false; btn.textContent="KIRIM KONFIRMASI";
});

/* ---------- 9. UCAPAN ---------- */
async function loadWishes(){
  const box=document.getElementById("wishList");
  try{
    const r=await fetch("/api/ucapan?inv_id="+JWA.invId+"&limit=20"); const j=await r.json();
    const rows=j.data||[];
    box.innerHTML=rows.length?rows.map(x=>
      `<div class="bg-black/30 border border-jawa-gold/20 rounded-xl p-4 text-left">
        <p class="text-jawa-gold text-[10px] tracking-[0.2em] font-semibold mb-1">${esc(x.nama)}</p>
        <p class="font-serif italic text-[15px] text-stone-200 leading-relaxed">${esc(x.pesan)}</p>
        <p class="text-stone-500 text-[11px] mt-2">${timeAgo(x.created_at)}</p>
      </div>`).join("")
      :'<p class="text-center text-stone-500 font-serif italic text-sm">Belum ada ucapan. Jadilah yang pertama!</p>';
  }catch(e){ box.innerHTML='<p class="text-center text-stone-500 font-serif italic text-sm">Gagal memuat ucapan.</p>'; }
}
loadWishes();
document.getElementById("wishForm").addEventListener("submit", async e=>{
  e.preventDefault();
  const nama=document.getElementById("wishNama").value.trim(), pesan=document.getElementById("wishPesan").value.trim();
  if(!nama||!pesan){ toast("Lengkapi nama & ucapan"); return; }
  const btn=document.getElementById("wishBtn"); btn.disabled=true;
  try{
    const r=await fetch("/api/ucapan",{ method:"POST", headers:{"Content-Type":"application/json"},
      body:JSON.stringify({ invitation_id:JWA.invId, nama, pesan }) });
    const j=await r.json(); if(!j.success) throw new Error(j.message||"Gagal");
    e.target.reset(); toast("Ucapan terkirim. Matur nuwun!"); loadWishes();
  }catch(err){ toast("Gagal: "+err.message); }
  btn.disabled=false;
});

/* ---------- 10. SALIN REKENING ---------- */
document.querySelectorAll(".copy-btn").forEach(b=>b.addEventListener("click", async ()=>{
  const t=document.getElementById("accNo"+b.dataset.i).textContent.replace(/\s/g,"");
  try{ await navigator.clipboard.writeText(t); }
  catch(e){ const ta=document.createElement("textarea"); ta.value=t; document.body.appendChild(ta);
    ta.select(); document.execCommand("copy"); ta.remove(); }
  toast("Nomor rekening tersalin");
}));

/* ---------- 11. LIGHTBOX ---------- */
(function(){
  const items=Array.from(document.querySelectorAll(".gal-item"));
  if(!items.length) return;
  const lb=document.getElementById("lightbox"), img=document.getElementById("lbImg"),
        count=document.getElementById("lbCount");
  let idx=0;
  function show(i){ idx=(i+items.length)%items.length;
    img.src=items[idx].dataset.full; count.textContent=(idx+1)+" / "+items.length;
    gsap.fromTo(img,{opacity:0,scale:.96},{opacity:1,scale:1,duration:.35,ease:"power2.out"}); }
  items.forEach((it,i)=>it.addEventListener("click",()=>{ lb.classList.remove("hidden"); lb.classList.add("flex"); show(i); }));
  const close=()=>{ lb.classList.add("hidden"); lb.classList.remove("flex"); };
  document.getElementById("lbClose").addEventListener("click",close);
  document.getElementById("lbPrev").addEventListener("click",e=>{e.stopPropagation();show(idx-1);});
  document.getElementById("lbNext").addEventListener("click",e=>{e.stopPropagation();show(idx+1);});
  lb.addEventListener("click",e=>{ if(e.target===lb) close(); });
  addEventListener("keydown",e=>{ if(lb.classList.contains("hidden")) return;
    if(e.key==="Escape")close(); if(e.key==="ArrowLeft")show(idx-1); if(e.key==="ArrowRight")show(idx+1); });
  let tx=0;
  lb.addEventListener("touchstart",e=>{tx=e.touches[0].clientX;},{passive:true});
  lb.addEventListener("touchmove",e=>{ const dx=e.touches[0].clientX-tx;
    if(Math.abs(dx)>48){ show(idx+(dx<0?1:-1)); tx=e.touches[0].clientX; } },{passive:true});
})();
</script>
</body>
</html>
