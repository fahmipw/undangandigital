<?php
/* ============================================================
   TEMA: TAMAN SURGA — garden heaven, taman surgawi yang terang
   Dipilih via settings: theme = taman-surga
   ============================================================ */
$GLOBALS['jkl_settings'] = $settings ?? [];
if (!function_exists('jklGet')) {
  function jklGet($key, $default = '') {
    $s = $GLOBALS['jkl_settings'];
    if (!isset($s[$key]) || trim((string)$s[$key]) === '') return $default;
    return $s[$key];
  }
}
if (!function_exists('jklDate')) {
  function jklDate($dateStr) {
    if (!$dateStr) return '';
    $days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($dateStr);
    if (!$ts) return $dateStr;
    return $days[date('w',$ts)].', '.date('j',$ts).' '.$months[date('n',$ts)].' '.date('Y',$ts);
  }
}
if (!function_exists('jklTime')) {
  function jklTime($t) {
    $t = trim((string)$t); if ($t === '') return '08:00';
    $t = str_replace('.', ':', $t);
    return preg_match('/^\d{1,2}(:\d{2})?$/', $t) ? (strpos($t, ':') === false ? $t.':00' : $t) : '08:00';
  }
}
if (!function_exists('jklDayNum')) {
  function jklDayNum($d) { $ts = strtotime($d); return $ts ? date('j', $ts) : ''; }
  function jklDayName($d) { $ts = strtotime($d); $days=['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; return $ts ? $days[date('w',$ts)] : ''; }
  function jklMonthName($d) { $ts = strtotime($d); $m=['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; return $ts ? $m[date('n',$ts)] : ''; }
  function jklYear($d) { $ts = strtotime($d); return $ts ? date('Y', $ts) : ''; }
}
$groomNick = jklGet('groom_nickname') ?: jklGet('groom_name', 'Mempelai Pria');
$brideNick = jklGet('bride_nickname') ?: jklGet('bride_name', 'Mempelai Wanita');
$groomFull = jklGet('groom_name', 'Mempelai Pria');
$brideFull = jklGet('bride_name', 'Mempelai Wanita');
$toName = (!empty($guestName) && $guestName !== 'Bapak/Ibu/Saudara/i') ? $guestName : request('to', 'Tamu Undangan');
$wDate  = jklGet('wedding_date', date('Y-m-d'));
$wTime  = jklTime(jklGet('wedding_time_start', '08:00'));
$wTimeE = jklTime(jklGet('wedding_time_end', '10:00'));
$rDate  = jklGet('reception_date', $wDate);
$rTimeS = jklTime(jklGet('reception_time_start', '11:00'));
$rTimeE = jklTime(jklGet('reception_time_end', '13:00'));
$tzLabel = jklGet('wedding_timezone', 'WIB');
$sameLoc = (jklGet('event_location_mode', 'same') === 'same');
$mainMapLink = trim((string)jklGet('wedding_map_link')) ?: trim((string)jklGet('reception_map_link'));
$giftRecipient = jklGet('gift_recipient_name', jklGet('gift_owner', ''));
$countdownISO = $rDate.'T'.$rTimeS.':00';
$accounts = [];
$__gbanks = json_decode(jklGet('gift_banks', ''), true) ?: [];
foreach ($__gbanks as $__b) {
  if (empty($__b['account'])) continue;
  $accounts[] = ['bank'=>($__b['name'] ?? 'Bank'),'no'=>$__b['account'],'an'=>($__b['owner'] ?? ''),'logo'=>($__b['logo'] ?? '')];
}
if (!$accounts) {
  if (jklGet('gift_account')) $accounts[] = ['bank'=>jklGet('gift_bank','Bank'),'no'=>jklGet('gift_account'),'an'=>jklGet('gift_owner',''),'logo'=>jklGet('gift_bank_logo','')];
  if (jklGet('gift_account2')) $accounts[] = ['bank'=>jklGet('gift_bank2','Bank'),'no'=>jklGet('gift_account2'),'an'=>jklGet('gift_owner2',''),'logo'=>jklGet('gift_bank_logo','')];
}
$qrisEnabled = jklGet('gift_qris_enabled', '') === '1' && trim((string)jklGet('gift_qris_image', '')) !== '';
$qrisImage = jklGet('gift_qris_image', '');
$qrisName = jklGet('gift_qris_name', '');
$musicUrl = null;
if (!empty($musicRecord) && !empty($musicRecord->file_path)) {
  $mp = $musicRecord->file_path;
  $musicUrl = (str_starts_with($mp,'http') || str_starts_with($mp,'/')) ? $mp : asset('storage/'.ltrim($mp,'/'));
}
$musVol = (int) jklGet('music_volume', 50);
$musAuto = jklGet('music_autoplay', '1') !== '0';
$invId = $inv_id ?? 0;
$gcalDates = str_replace('-','',$rDate).'T'.str_replace(':','',$rTimeS).'00/'.str_replace('-','',$rDate).'T'.str_replace(':','',$rTimeE).'00';
$gcalUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text='.urlencode('Pernikahan '.$brideNick.' & '.$groomNick).'&dates='.$gcalDates.'&details='.urlencode(jklGet('reception_location', jklGet('wedding_location','')));
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
  <link href="https://fonts.googleapis.com/css2?family=Pinyon+Script&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
  <script>
    tailwind.config = {
      theme: { extend: {
        fontFamily: {
          script: ['"Pinyon Script"','cursive'],
          serif: ['"Cormorant Garamond"','serif'],
          sans: ['"Plus Jakarta Sans"','sans-serif'],
        },
        colors: { surga: {
          sky:'#eaf4fb', cream:'#fffdf8', sage:'#7a9b76', deepsage:'#4e6e4b',
          blush:'#e8a2b0', lavender:'#a78bda', gold:'#c9a227', ink:'#4a4458'
        } }
      }}
    }
  </script>
  <style>
    *{ -webkit-tap-highlight-color:transparent; box-sizing:border-box; }
    html{ scroll-behavior:smooth; }
    body.is-locked{ overflow:hidden !important; height:100dvh !important; }
    body.is-locked main{ touch-action:none; }
    .invitation-frame{ width:100%; max-width:430px; margin:0 auto; position:relative;
      overflow-x:hidden; background:linear-gradient(180deg,#dceefb 0%,#f2f8fd 30%,#fffdf8 70%,#fdf6ec 100%);
      box-shadow:0 0 80px rgba(120,150,180,.35); }
    .text-gold-grad{ background:linear-gradient(135deg,#e3b93c 0%,#c9a227 55%,#9a7a14 100%);
      -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; color:transparent; }
    .card-surga{ background:rgba(255,255,255,.82); backdrop-filter:blur(8px);
      border:1px solid rgba(201,162,39,.4); border-radius:1.6rem; position:relative;
      box-shadow:0 20px 45px -20px rgba(90,120,100,.35), inset 0 0 0 5px rgba(255,255,255,.5); }
    .btn-gold{ background:linear-gradient(135deg,#dcb33a 0%,#f4dd8f 50%,#b28c1a 100%);
      box-shadow:0 8px 24px rgba(201,162,39,.4); transition:transform .2s, box-shadow .2s; color:#3d3413; }
    .btn-gold:hover{ box-shadow:0 10px 30px rgba(201,162,39,.55); }
    .btn-gold:active{ transform:scale(.96); }
    .btn-outline{ border:1.5px solid rgba(122,155,118,.65); color:#4e6e4b; transition:.25s; background:rgba(255,255,255,.6); }
    .btn-outline:hover{ background:rgba(122,155,118,.14); }
    .hairline{ height:1px; background:linear-gradient(90deg,transparent,rgba(201,162,39,.75),transparent); }
    .field-surga{ width:100%; background:rgba(255,255,255,.9); border:1.5px solid rgba(122,155,118,.4);
      border-radius:1rem; padding:.8rem 1rem; color:#4a4458; font-size:.9rem; outline:none; transition:.25s; }
    .field-surga::placeholder{ color:#a8a29a; }
    .field-surga:focus{ border-color:rgba(201,162,39,.8); box-shadow:0 0 0 3px rgba(201,162,39,.14); }
    select.field-surga option{ background:#fff; }
    /* kanvas kelopak */
    #petalCanvas{ position:fixed; inset:0; z-index:44; pointer-events:none; }
    /* kupu-kupu */
    @keyframes flutter{ 0%,100%{ transform:translate(0,0) rotate(-6deg); } 50%{ transform:translate(14px,-22px) rotate(8deg); } }
    @keyframes wingflap{ 0%,100%{ transform:scaleX(1); } 50%{ transform:scaleX(.35); } }
    .butterfly{ animation:flutter 7s ease-in-out infinite; transform-origin:center; }
    .butterfly .wing{ animation:wingflap .55s ease-in-out infinite; transform-origin:right center; transform-box:fill-box; }
    .butterfly .wing.r{ transform-origin:left center; }
    @keyframes swaySlow{ 0%,100%{ transform:rotate(-3deg); } 50%{ transform:rotate(3deg); } }
    .sway-slow{ animation:swaySlow 8s ease-in-out infinite; transform-origin:50% 100%; }
    @keyframes drift{ 0%{ transform:translateX(-12%); } 100%{ transform:translateX(112%); } }
    .cloud{ animation:drift 70s linear infinite; }
    .cloud.c2{ animation-duration:95s; animation-delay:-30s; }
    @media (prefers-reduced-motion:reduce){ .butterfly,.sway-slow,.cloud{ animation:none !important; } }
    /* musik + menu melayang */
    #musicFloat{ position:fixed; z-index:45; right:max(14px,calc(50% - 215px + 14px)); bottom:24px;
      width:48px; height:48px; border-radius:9999px; display:flex; align-items:center; justify-content:center;
      background:linear-gradient(135deg,#dcb33a,#b28c1a); color:#3d3413; cursor:pointer;
      box-shadow:0 8px 22px rgba(201,162,39,.45); border:1px solid rgba(255,255,255,.7); }
    #musicFloat[hidden]{ display:none !important; }
    #musicFloat .bars{ display:none; align-items:flex-end; gap:2px; height:16px; }
    #musicFloat.playing .bars{ display:flex; }
    #musicFloat.playing .note{ display:none; }
    #musicFloat .bars span{ width:3px; background:#3d3413; border-radius:2px; animation:eq 0.9s ease-in-out infinite; }
    #musicFloat .bars span:nth-child(2){ animation-delay:.2s; } #musicFloat .bars span:nth-child(3){ animation-delay:.45s; }
    @keyframes eq{ 0%,100%{height:6px;} 50%{height:16px;} }
    #floatMenu{ position:fixed; z-index:45; right:max(14px,calc(50% - 215px + 14px)); bottom:84px;
      display:flex; flex-direction:column; gap:10px; }
    #floatMenu[hidden]{ display:none !important; }
    #floatMenu a{ width:48px; height:48px; border-radius:9999px; display:flex; align-items:center; justify-content:center;
      background:rgba(255,255,255,.9); backdrop-filter:blur(6px); color:#4e6e4b;
      border:1.5px solid rgba(201,162,39,.5); box-shadow:0 8px 20px rgba(90,120,100,.25); transition:.25s; }
    #floatMenu a:active{ transform:scale(.92); }
    #toast{ position:fixed; left:50%; bottom:150px; transform:translate(-50%,16px); z-index:60;
      background:#fffdf8; color:#4a4458; border:1.5px solid rgba(201,162,39,.55); border-radius:9999px;
      padding:.65rem 1.4rem; font-size:.8rem; letter-spacing:.05em; opacity:0; transition:.3s;
      pointer-events:none; max-width:86vw; text-align:center; white-space:nowrap;
      overflow:hidden; text-overflow:ellipsis; box-shadow:0 10px 30px rgba(90,120,100,.25); }
    #toast.show{ opacity:1; transform:translate(-50%,0); }
    ::-webkit-scrollbar{ width:0; background:transparent; }
    .orn{ will-change:transform; }
  
/* Standar menu klasik emas: bottom bar */
#floatMenu,#bottomNav{position:fixed;left:50%;transform:translateX(-50%);right:auto;bottom:10px;z-index:46;display:flex;flex-direction:row;gap:2px 0;background:rgba(255,253,247,.94);border:1px solid rgba(151,128,69,.4);border-radius:26px;padding:7px 8px;backdrop-filter:blur(10px);box-shadow:0 14px 34px rgba(90,70,20,.25);width:min(96vw,500px);max-width:96vw;flex-wrap:wrap;justify-content:center;overflow:visible;row-gap:2px}
#floatMenu::-webkit-scrollbar,#bottomNav::-webkit-scrollbar{display:none}
#floatMenu a,#bottomNav a{display:flex;flex-direction:column;align-items:center;gap:3px;flex:1 1 0;min-width:36px;max-width:54px;height:auto;padding:7px 0;border-radius:13px;color:#978045!important;background:transparent!important;border:none!important;box-shadow:none!important;text-decoration:none;font-size:7.5px;font-weight:700;letter-spacing:.06em;text-transform:uppercase}
#floatMenu a svg,#bottomNav a svg{width:21px;height:21px}
#floatMenu a.active,#bottomNav a.active{background:rgba(151,128,69,.14)!important;box-shadow:inset 0 0 0 1.5px #b8934a!important;color:#7c652f!important}
#floatMenu.hidden,#bottomNav[hidden]{display:none!important}
#musicFloat{bottom:130px!important;background:linear-gradient(160deg,rgba(43,33,24,.95),rgba(23,18,8,.97))!important;border:1px solid rgba(201,164,92,.8)!important;color:#e6c887!important;box-shadow:0 10px 24px rgba(90,70,20,.45)!important}
#musicFloat .bars span{background:#e6c887!important}
#vinyl{bottom:96px!important}
</style>
</head>
<body class="is-locked bg-[#cfe3f2] text-surga-ink font-sans antialiased">

<!-- ======== DEFS SVG: bunga, kupu, awan, gapura ======== -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
  <defs>
    <symbol id="ts-flower" viewBox="0 0 100 100">
      <g>
        <ellipse cx="50" cy="26" rx="13" ry="20" fill="#f7c8d3"/>
        <ellipse cx="50" cy="26" rx="13" ry="20" fill="#f7c8d3" transform="rotate(72 50 50)"/>
        <ellipse cx="50" cy="26" rx="13" ry="20" fill="#fbdce3" transform="rotate(144 50 50)"/>
        <ellipse cx="50" cy="26" rx="13" ry="20" fill="#fbdce3" transform="rotate(216 50 50)"/>
        <ellipse cx="50" cy="26" rx="13" ry="20" fill="#f7c8d3" transform="rotate(288 50 50)"/>
        <circle cx="50" cy="50" r="11" fill="#e9b949"/>
        <circle cx="50" cy="50" r="5.5" fill="#c98f1b"/>
      </g>
    </symbol>
    <symbol id="ts-flower-white" viewBox="0 0 100 100">
      <g>
        <ellipse cx="50" cy="26" rx="13" ry="20" fill="#ffffff"/>
        <ellipse cx="50" cy="26" rx="13" ry="20" fill="#ffffff" transform="rotate(72 50 50)"/>
        <ellipse cx="50" cy="26" rx="13" ry="20" fill="#fdf3f5" transform="rotate(144 50 50)"/>
        <ellipse cx="50" cy="26" rx="13" ry="20" fill="#fdf3f5" transform="rotate(216 50 50)"/>
        <ellipse cx="50" cy="26" rx="13" ry="20" fill="#ffffff" transform="rotate(288 50 50)"/>
        <circle cx="50" cy="50" r="11" fill="#e9b949"/>
        <circle cx="50" cy="50" r="5.5" fill="#c98f1b"/>
      </g>
    </symbol>
    <symbol id="ts-leaf" viewBox="0 0 100 100">
      <path d="M50 95 Q48 55 50 8 Q72 30 68 62 Q64 88 50 95 Z" fill="#8fb996"/>
      <path d="M50 90 L50 15" stroke="#5c7a5e" stroke-width="2.5"/>
      <path d="M50 70 Q60 62 66 50 M50 55 Q60 47 64 36 M50 40 Q58 33 61 24" stroke="#5c7a5e" stroke-width="2" fill="none"/>
    </symbol>
    <symbol id="ts-butterfly" viewBox="0 0 100 80">
      <g class="wing">
        <ellipse cx="30" cy="38" rx="24" ry="28" fill="#e8a2b0"/>
        <ellipse cx="30" cy="38" rx="14" ry="17" fill="#f6cdd6"/>
      </g>
      <g class="wing r">
        <ellipse cx="70" cy="38" rx="24" ry="28" fill="#a78bda"/>
        <ellipse cx="70" cy="38" rx="14" ry="17" fill="#c9b8ec"/>
      </g>
      <ellipse cx="50" cy="40" rx="5" ry="16" fill="#4a4458"/>
      <circle cx="50" cy="22" r="5" fill="#4a4458"/>
    </symbol>
    <symbol id="ts-cloud" viewBox="0 0 200 100">
      <g fill="#ffffff" opacity=".9">
        <ellipse cx="70" cy="65" rx="45" ry="26"/>
        <ellipse cx="115" cy="52" rx="40" ry="30"/>
        <ellipse cx="150" cy="68" rx="32" ry="20"/>
      </g>
    </symbol>
    <symbol id="ts-divider" viewBox="0 0 300 40">
      <g>
        <line x1="10" y1="20" x2="120" y2="20" stroke="#c9a227" stroke-width="1.5"/>
        <line x1="180" y1="20" x2="290" y2="20" stroke="#c9a227" stroke-width="1.5"/>
        <circle cx="150" cy="20" r="6" fill="#c9a227"/>
        <path d="M138 20 Q130 8 120 12 Q130 16 138 20 Q130 24 120 28 Q130 32 138 20" fill="#8fb996"/>
        <path d="M162 20 Q170 8 180 12 Q170 16 162 20 Q170 24 180 28 Q170 32 162 20" fill="#8fb996"/>
      </g>
    </symbol>
  </defs>
</svg>

<main class="invitation-frame min-h-screen">

  <!-- ================= 1. PRELOADER ================= -->
  <section id="preloader" class="fixed inset-0 z-50 mx-auto max-w-[430px] flex flex-col items-center justify-center px-6 text-center"
    style="background:linear-gradient(180deg,#dceefb,#fffdf8)">
    <svg class="w-20 h-20 mb-5 sway-slow"><use href="#ts-flower"/></svg>
    <p class="text-[10px] uppercase tracking-[0.4em] text-surga-gold mb-2">Wedding Invitation</p>
    <h1 class="font-script text-4xl text-surga-ink mb-1">{{ $groomNick }} <span class="text-gold-grad">&amp;</span> {{ $brideNick }}</h1>
    <div class="w-40 h-[3px] bg-surga-sage/20 rounded-full overflow-hidden mb-2 mt-5">
      <div id="loader-bar" class="h-full w-0" style="background:linear-gradient(90deg,#7a9b76,#c9a227)"></div>
    </div>
    <span id="loader-percent" class="text-[11px] font-mono tracking-[0.25em] text-surga-deepsage">0%</span>
  </section>

  <!-- ================= 2. COVER ================= -->
  <section id="cover-gate" class="fixed inset-0 z-40 mx-auto max-w-[430px] h-[100dvh] flex flex-col justify-between py-10 px-6 text-center overflow-hidden"
    style="background:linear-gradient(180deg,#cfe6f7 0%,#eef6fd 45%,#fffdf8 100%)">
    <svg class="orn pointer-events-none absolute -top-6 -left-10 w-56 opacity-80 cloud"><use href="#ts-cloud"/></svg>
    <svg class="orn pointer-events-none absolute top-24 -right-12 w-64 opacity-70 cloud c2"><use href="#ts-cloud"/></svg>
    <svg class="butterfly pointer-events-none absolute top-[18%] left-[12%] w-10 z-10"><use href="#ts-butterfly"/></svg>
    <svg class="butterfly pointer-events-none absolute top-[30%] right-[10%] w-12 z-10" style="animation-delay:-3s"><use href="#ts-butterfly"/></svg>

    <div id="gate-header" class="relative z-10 pt-8">
      <p class="text-[10px] uppercase tracking-[0.42em] text-surga-deepsage mb-3">The Wedding Of</p>
      <h2 class="font-script text-[2.8rem] leading-tight text-surga-ink">{{ $groomNick }} <span class="text-gold-grad">&amp;</span> {{ $brideNick }}</h2>
    </div>

    <div id="gate-center" class="relative z-10 flex flex-col items-center">
      <!-- gapura taman -->
      <svg class="sway-slow w-[62%] max-w-[250px] h-auto mb-2" viewBox="0 0 200 170" fill="none">
        <path d="M30 170 Q30 60 100 30 Q170 60 170 170" stroke="#7a9b76" stroke-width="7" fill="none" stroke-linecap="round"/>
        <path d="M44 170 Q44 72 100 46 Q156 72 156 170" stroke="#a9c7a5" stroke-width="3" fill="none" stroke-linecap="round"/>
        <g>
          <use href="#ts-flower" x="18" y="96" width="34" height="34"/>
          <use href="#ts-flower-white" x="150" y="96" width="34" height="34"/>
          <use href="#ts-flower" x="52" y="38" width="30" height="30"/>
          <use href="#ts-flower-white" x="118" y="38" width="30" height="30"/>
          <use href="#ts-flower" x="86" y="14" width="30" height="30"/>
          <use href="#ts-leaf" x="8" y="130" width="26" height="26" transform="rotate(-24 21 143)"/>
          <use href="#ts-leaf" x="166" y="130" width="26" height="26" transform="rotate(24 179 143)"/>
        </g>
      </svg>
      <div class="card-surga mx-auto w-full max-w-[300px] px-6 py-5 -mt-4">
        <p class="text-[10px] tracking-[0.3em] uppercase text-surga-deepsage/80 mb-2">Kepada Yth<br>Bapak/Ibu/Saudara/i</p>
        <p class="font-serif text-2xl font-semibold text-surga-ink">{{ $toName }}</p>
      </div>
    </div>

    <div id="gate-footer" class="relative z-10 pb-4">
      <button id="btn-open" class="btn-gold font-semibold text-[12px] tracking-[0.25em] px-10 py-3.5 rounded-full">
        BUKA UNDANGAN
      </button>
      <p class="text-[10px] text-surga-ink/50 mt-4 tracking-widest">* Mohon maaf apabila ada kesalahan penulisan nama &amp; gelar</p>
    </div>

    <!-- semak bunga bawah -->
    <svg class="pointer-events-none absolute bottom-0 left-0 w-full h-28 z-0" viewBox="0 0 430 110" preserveAspectRatio="xMidYMax slice">
      <ellipse cx="40" cy="115" rx="90" ry="55" fill="#8fb996"/>
      <ellipse cx="150" cy="120" rx="70" ry="45" fill="#a9c7a5"/>
      <ellipse cx="280" cy="118" rx="80" ry="50" fill="#8fb996"/>
      <ellipse cx="390" cy="115" rx="90" ry="55" fill="#7a9b76"/>
      <use href="#ts-flower" x="30" y="52" width="30" height="30"/>
      <use href="#ts-flower-white" x="120" y="62" width="26" height="26"/>
      <use href="#ts-flower" x="230" y="55" width="32" height="32"/>
      <use href="#ts-flower-white" x="330" y="60" width="28" height="28"/>
      <use href="#ts-flower" x="380" y="48" width="26" height="26"/>
    </svg>
  </section>

  <!-- ================= 3. HERO ================= -->
  <section id="hero-section" class="relative h-[100dvh] min-h-[600px] w-full flex flex-col items-center justify-center text-center px-6 overflow-hidden"
    style="background:linear-gradient(180deg,#cfe6f7 0%,#eef6fd 50%,#fffdf8 100%)">
    <svg class="orn pointer-events-none absolute top-10 -left-10 w-52 opacity-80 cloud"><use href="#ts-cloud"/></svg>
    <svg class="orn pointer-events-none absolute top-40 -right-14 w-60 opacity-70 cloud c2"><use href="#ts-cloud"/></svg>
    <svg class="butterfly pointer-events-none absolute top-[16%] right-[14%] w-11 z-10"><use href="#ts-butterfly"/></svg>
    <svg class="butterfly pointer-events-none absolute top-[42%] left-[10%] w-9 z-10" style="animation-delay:-4s"><use href="#ts-butterfly"/></svg>

    <div class="hero-content relative z-10 flex flex-col items-center px-4 w-full">
      <svg class="hero-stagger orn pointer-events-none w-[68%] max-w-[270px] h-auto opacity-0 mb-1" data-speed="0.7" viewBox="0 0 200 170" fill="none">
        <path d="M30 170 Q30 60 100 30 Q170 60 170 170" stroke="#7a9b76" stroke-width="7" fill="none" stroke-linecap="round"/>
        <path d="M44 170 Q44 72 100 46 Q156 72 156 170" stroke="#a9c7a5" stroke-width="3" fill="none" stroke-linecap="round"/>
        <use href="#ts-flower" x="18" y="96" width="34" height="34"/>
        <use href="#ts-flower-white" x="150" y="96" width="34" height="34"/>
        <use href="#ts-flower" x="52" y="38" width="30" height="30"/>
        <use href="#ts-flower-white" x="118" y="38" width="30" height="30"/>
        <use href="#ts-flower" x="86" y="14" width="30" height="30"/>
      </svg>
      <p class="hero-stagger text-[10px] uppercase tracking-[0.42em] text-surga-deepsage mb-3 opacity-0">The Wedding Of</p>
      <h2 class="hero-stagger font-script text-[clamp(2.8rem,12vw,3.8rem)] leading-tight text-surga-ink opacity-0">{{ $groomNick }} <span class="text-gold-grad">&amp;</span> {{ $brideNick }}</h2>
      <svg class="hero-stagger w-56 h-8 opacity-0 mt-2" viewBox="0 0 300 40"><use href="#ts-divider"/></svg>
      <div class="hero-stagger mt-4 opacity-0 flex items-stretch justify-center gap-4 text-surga-ink">
        <div class="flex flex-col justify-center"><span class="font-serif text-lg">{{ jklDayName($rDate) }}</span></div>
        <div class="flex flex-col justify-center border-x border-surga-gold/60 px-4"><span class="font-serif text-4xl font-semibold text-gold-grad leading-none">{{ jklDayNum($rDate) }}</span><span class="text-[11px] tracking-[0.2em] text-surga-deepsage mt-1">{{ jklYear($rDate) }}</span></div>
        <div class="flex flex-col justify-center"><span class="font-serif text-lg">{{ jklMonthName($rDate) }}</span></div>
      </div>
    </div>

    <svg class="pointer-events-none absolute bottom-0 left-0 w-full h-32 z-0" viewBox="0 0 430 130" preserveAspectRatio="xMidYMax slice">
      <ellipse cx="60" cy="140" rx="110" ry="60" fill="#8fb996"/>
      <ellipse cx="200" cy="145" rx="90" ry="50" fill="#a9c7a5"/>
      <ellipse cx="340" cy="140" rx="110" ry="60" fill="#8fb996"/>
      <use href="#ts-flower" x="40" y="70" width="32" height="32"/>
      <use href="#ts-flower-white" x="140" y="80" width="28" height="28"/>
      <use href="#ts-flower" x="250" y="72" width="34" height="34"/>
      <use href="#ts-flower-white" x="350" y="78" width="28" height="28"/>
    </svg>
    <p class="hero-stagger absolute bottom-[19%] z-10 opacity-0 text-[9px] tracking-[0.4em] text-surga-deepsage/70 uppercase">Geser ke bawah</p>
  </section>

  <!-- ================= 4. QUOTES ================= -->
  <section class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden">
    <svg class="butterfly pointer-events-none absolute top-10 left-[8%] w-9 z-0"><use href="#ts-butterfly"/></svg>
    <div class="anim-zoom relative z-10 w-full max-w-[340px] card-surga px-7 py-9">
      <svg class="anim-zoom w-14 h-14 mx-auto mb-4 sway-slow"><use href="#ts-flower"/></svg>
      <p class="anim-fade font-serif text-[16px] leading-[1.9] text-surga-ink/85 italic">
        &ldquo;Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang.&rdquo;
      </p>
      <svg class="w-48 h-6 mx-auto my-3" viewBox="0 0 300 40"><use href="#ts-divider"/></svg>
      <p class="anim-fade text-[11px] font-semibold tracking-[0.25em] uppercase text-surga-gold">QS. Ar-R&umacr;m : 21</p>
    </div>
  </section>

  <!-- ================= 5. MEMPELAI ================= -->
  <section id="sec-profil" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden"
    style="background:linear-gradient(180deg,transparent,rgba(232,162,176,.12),transparent)">
    <svg class="butterfly pointer-events-none absolute top-16 right-[10%] w-10 z-0" style="animation-delay:-2s"><use href="#ts-butterfly"/></svg>
    <p class="anim-fade text-[10px] uppercase tracking-[0.4em] text-surga-deepsage mb-2 relative z-10">Assalamu&rsquo;alaikum Warahmatullaahi Wabarakaatuh</p>
    <h2 class="anim-zoom font-script text-[2.6rem] text-surga-ink mb-2 relative z-10">Mempelai</h2>
    <svg class="anim-fade w-48 h-6 relative z-10 mb-3" viewBox="0 0 300 40"><use href="#ts-divider"/></svg>
    <p class="anim-fade font-serif italic text-surga-ink/65 text-[15px] leading-relaxed mb-10 max-w-[300px] relative z-10">Dengan penuh sukacita, kami mengundang Bapak/Ibu/Saudara/i untuk hadir dan memberikan do&rsquo;a restu pada pernikahan kami.</p>

    <!-- Mempelai wanita -->
    <div class="anim-zoom relative z-10 w-full max-w-[300px] card-surga px-6 pt-9 pb-7 mb-8">
      <div class="relative w-40 h-48 mx-auto mb-4" style="border-radius:50%; overflow:hidden; border:3px solid rgba(201,162,39,.55); box-shadow:0 12px 30px rgba(122,155,118,.3)">
        @if(jklGet('bride_photo'))
        <img src="{{ jklGet('bride_photo') }}" class="w-full h-full object-cover" alt="{{ $brideFull }}" />
        @else
        <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(160deg,#fbdce3,#fdf3f5)">
          <svg class="w-20 h-20 opacity-80"><use href="#ts-flower"/></svg>
        </div>
        @endif
      </div>
      <svg class="w-10 h-10 mx-auto -mt-8 mb-1 relative"><use href="#ts-flower-white"/></svg>
      <h3 class="font-script text-[2.2rem] text-surga-ink leading-tight">{{ $brideNick }}</h3>
      <p class="text-[11px] text-surga-ink/55 mt-2">{{ jklGet('bride_child_of','Putri') }} dari</p>
      <p class="text-[13px] font-medium text-surga-deepsage mt-0.5">{{ jklGet('bride_parents','') }}</p>
      @if(jklGet('bride_instagram'))
      <a href="https://instagram.com/{{ ltrim(jklGet('bride_instagram'),'@') }}" target="_blank" rel="noopener" class="btn-outline inline-block mt-4 text-[10px] tracking-[0.2em] px-5 py-2 rounded-full"><svg class="inline-block w-[13px] h-[13px] mr-1 -mt-[2px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="0.8" fill="currentColor" stroke="none"/></svg>&#64;{{ ltrim(jklGet('bride_instagram'),'@') }}</a>
      @endif
    </div>

    <div class="anim-zoom font-script text-4xl text-gold-grad my-2 relative z-10">&amp;</div>

    <!-- Mempelai pria -->
    <div class="anim-zoom relative z-10 w-full max-w-[300px] card-surga px-6 pt-9 pb-7 mt-8">
      <div class="relative w-40 h-48 mx-auto mb-4" style="border-radius:50%; overflow:hidden; border:3px solid rgba(201,162,39,.55); box-shadow:0 12px 30px rgba(122,155,118,.3)">
        @if(jklGet('groom_photo'))
        <img src="{{ jklGet('groom_photo') }}" class="w-full h-full object-cover" alt="{{ $groomFull }}" />
        @else
        <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(160deg,#dfeee0,#f2f8fd)">
          <svg class="w-20 h-20 opacity-80"><use href="#ts-flower-white"/></svg>
        </div>
        @endif
      </div>
      <svg class="w-10 h-10 mx-auto -mt-8 mb-1 relative"><use href="#ts-flower"/></svg>
      <h3 class="font-script text-[2.2rem] text-surga-ink leading-tight">{{ $groomNick }}</h3>
      <p class="text-[11px] text-surga-ink/55 mt-2">{{ jklGet('groom_child_of','Putra') }} dari</p>
      <p class="text-[13px] font-medium text-surga-deepsage mt-0.5">{{ jklGet('groom_parents','') }}</p>
      @if(jklGet('groom_instagram'))
      <a href="https://instagram.com/{{ ltrim(jklGet('groom_instagram'),'@') }}" target="_blank" rel="noopener" class="btn-outline inline-block mt-4 text-[10px] tracking-[0.2em] px-5 py-2 rounded-full"><svg class="inline-block w-[13px] h-[13px] mr-1 -mt-[2px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="0.8" fill="currentColor" stroke="none"/></svg>&#64;{{ ltrim(jklGet('groom_instagram'),'@') }}</a>
      @endif
    </div>
  </section>

  <!-- ================= 6. COUNTDOWN ================= -->
@if(jklGet('quotes'))
  <section id="sec-quotes" class="scroll-section relative py-16 px-6 flex flex-col items-center text-center overflow-hidden" style="background:linear-gradient(180deg,transparent,rgba(201,164,92,.10),transparent)">
    <p class="anim-fade text-[10px] uppercase tracking-[0.4em] text-surga-deepsage mb-3 relative z-10">Quotes</p>
    <p class="anim-zoom font-serif italic text-surga-ink/80 text-lg leading-relaxed max-w-[330px] relative z-10">&ldquo;{{ jklGet('quotes') }}&rdquo;</p>
  </section>
  @endif
  <section id="sec-acara" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden">
    <svg class="butterfly pointer-events-none absolute top-12 left-[10%] w-9 z-0" style="animation-delay:-5s"><use href="#ts-butterfly"/></svg>
    <svg class="anim-zoom relative z-10 w-14 h-14 mb-3 sway-slow"><use href="#ts-flower-white"/></svg>
    <h2 class="anim-zoom font-script text-[2.6rem] text-surga-ink mb-8 relative z-10">Counting The Days</h2>
    <div class="anim-zoom relative z-10 grid grid-cols-4 gap-2 w-full max-w-[340px] mb-8">
      <div class="card-surga py-4"><div class="font-serif text-[1.7rem] font-semibold text-gold-grad" id="cd-d">00</div><div class="text-[9px] tracking-[0.25em] text-surga-ink/55 mt-1">HARI</div></div>
      <div class="card-surga py-4"><div class="font-serif text-[1.7rem] font-semibold text-gold-grad" id="cd-h">00</div><div class="text-[9px] tracking-[0.25em] text-surga-ink/55 mt-1">JAM</div></div>
      <div class="card-surga py-4"><div class="font-serif text-[1.7rem] font-semibold text-gold-grad" id="cd-m">00</div><div class="text-[9px] tracking-[0.25em] text-surga-ink/55 mt-1">MENIT</div></div>
      <div class="card-surga py-4"><div class="font-serif text-[1.7rem] font-semibold text-gold-grad" id="cd-s">00</div><div class="text-[9px] tracking-[0.25em] text-surga-ink/55 mt-1">DETIK</div></div>
    </div>
    <a href="{{ $gcalUrl }}" target="_blank" rel="noopener" class="anim-fade btn-outline relative z-10 text-[10px] tracking-[0.25em] px-7 py-3 rounded-full mb-4">+ SIMPAN KE GOOGLE CALENDAR</a>

    <!-- Akad -->
    <div class="anim-zoom relative z-10 w-full max-w-[340px] card-surga px-6 py-8 mt-8">
      <svg class="w-12 h-12 mx-auto mb-2"><use href="#ts-flower"/></svg>
      <h3 class="font-script text-[2.1rem] text-surga-ink">Akad Nikah</h3>
      <svg class="w-44 h-6 mx-auto my-2" viewBox="0 0 300 40"><use href="#ts-divider"/></svg>
      <p class="font-serif text-lg text-surga-ink/80 mb-1">{{ jklDate($wDate) }}</p>
      <p class="font-serif text-lg text-surga-deepsage font-semibold mb-4">{{ $wTime }} &ndash; {{ $wTimeE }} {{ $tzLabel }}</p>
      <div class="hairline w-24 mx-auto my-4"></div>
      <p class="text-[11px] tracking-[0.25em] uppercase text-surga-deepsage/80 mb-1">Lokasi Acara</p>
      <p class="font-semibold text-surga-ink">{{ jklGet('wedding_location','Kediaman Mempelai') }}</p>
      @if(!$sameLoc && jklGet('wedding_map_link'))
      <a href="{{ jklGet('wedding_map_link') }}" target="_blank" rel="noopener" class="btn-gold inline-flex items-center gap-2 mt-5 text-[11px] font-semibold tracking-[0.2em] px-8 py-3 rounded-full">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
        LIHAT LOKASI
      </a>
      @endif
    </div>

    <!-- Resepsi -->
    <div class="anim-zoom relative z-10 w-full max-w-[340px] card-surga px-6 py-8 mt-6">
      <svg class="w-12 h-12 mx-auto mb-2"><use href="#ts-flower-white"/></svg>
      <h3 class="font-script text-[2.1rem] text-surga-ink">Resepsi</h3>
      <svg class="w-44 h-6 mx-auto my-2" viewBox="0 0 300 40"><use href="#ts-divider"/></svg>
      <p class="font-serif text-lg text-surga-ink/80 mb-1">{{ jklDate($rDate) }}</p>
      <p class="font-serif text-lg text-surga-deepsage font-semibold mb-4">{{ $rTimeS }} &ndash; {{ $rTimeE }} {{ $tzLabel }}</p>
      <div class="hairline w-24 mx-auto my-4"></div>
      <p class="text-[11px] tracking-[0.25em] uppercase text-surga-deepsage/80 mb-1">Lokasi Acara</p>
      <p class="font-semibold text-surga-ink">{{ jklGet('reception_location', jklGet('wedding_location','Kediaman Mempelai')) }}</p>
      <div class="mt-5 flex flex-wrap justify-center gap-2">
        @if($sameLoc)
          @if($mainMapLink)
          <a href="{{ $mainMapLink }}" target="_blank" rel="noopener" class="btn-gold inline-flex items-center gap-2 text-[11px] font-semibold tracking-[0.2em] px-8 py-3 rounded-full">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
            LIHAT LOKASI ACARA
          </a>
          @endif
        @else
          @if(jklGet('reception_map_link'))
          <a href="{{ jklGet('reception_map_link') }}" target="_blank" rel="noopener" class="btn-gold inline-flex items-center gap-2 text-[11px] font-semibold tracking-[0.2em] px-8 py-3 rounded-full">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
            LIHAT LOKASI
          </a>
          @endif
        @endif
      </div>
    </div>
  </section>

  <!-- ================= 7. GALERI ================= -->
  <section id="sec-galeri" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden"
    style="background:linear-gradient(180deg,transparent,rgba(167,139,218,.1),transparent)">
    <svg class="butterfly pointer-events-none absolute top-14 right-[8%] w-10 z-0" style="animation-delay:-1s"><use href="#ts-butterfly"/></svg>
    <svg class="anim-zoom relative z-10 w-14 h-14 mb-3 sway-slow"><use href="#ts-flower"/></svg>
    <h2 class="anim-zoom font-script text-[2.6rem] text-surga-ink mb-2 relative z-10">Memorable Moment</h2>
    <p class="anim-fade text-[11px] tracking-[0.3em] uppercase text-surga-deepsage/80 mb-8 relative z-10">Galeri kenangan kami</p>
    @if(!empty($gallery) && count($gallery))
    <div class="relative z-10 w-full max-w-[340px] grid grid-cols-2 gap-3">
      @foreach(array_slice($gallery, 0, 8) as $i => $img)
      <div class="anim-zoom gal-item relative overflow-hidden cursor-pointer {{ $i % 3 === 0 ? 'row-span-2' : '' }}" data-full="{{ $img }}"
        style="border-radius:1.2rem; border:2px solid rgba(255,255,255,.9); box-shadow:0 12px 28px -12px rgba(90,120,100,.4)">
        <img src="{{ $img }}" alt="Galeri {{ $i+1 }}" loading="lazy" class="w-full {{ $i % 3 === 0 ? 'h-64' : 'h-[7.5rem]' }} object-cover transition-transform duration-700 hover:scale-110" />
      </div>
      @endforeach
    </div>
    @else
    <p class="anim-fade font-serif italic text-surga-ink/55 relative z-10">Galeri kenangan akan segera hadir.</p>
    @endif
  </section>

  <!-- ================= 8. KISAH CINTA ================= -->
  <section class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden" id="sec-kisah">
    <svg class="anim-zoom relative z-10 w-14 h-14 mb-3 sway-slow"><use href="#ts-flower-white"/></svg>
    <h2 class="anim-zoom font-script text-[2.6rem] text-surga-ink mb-2 relative z-10">Our Story</h2>
    <p class="anim-fade text-[11px] tracking-[0.3em] uppercase text-surga-deepsage/80 mb-10 relative z-10">Perjalanan cinta kami</p>
    @if(!empty($stories) && count($stories))
    <div class="relative z-10 w-full max-w-[320px] pl-8 pb-10 text-left">
      <div class="absolute left-[9px] top-2 bottom-2 w-0.5 rounded" style="background:linear-gradient(180deg,#c9a227,#7a9b76)"></div>
      @foreach($stories as $st)
      <div class="anim-fade relative mb-8">
        <div class="absolute -left-8 top-1 w-[19px] h-[19px] rounded-full bg-white flex items-center justify-center" style="border:2.5px solid #c9a227">
          <div class="w-1.5 h-1.5 rounded-full" style="background:#c9a227"></div>
        </div>
        @if(!empty($st->tahun))
        <p class="font-script text-3xl text-gold-grad mb-1">{{ $st->tahun }}</p>
        @endif
        <h4 class="font-serif font-semibold text-surga-ink tracking-wide mb-1">{{ $st->judul }}</h4>
        <p class="text-[13px] text-surga-ink/65 leading-relaxed">{{ $st->isi }}</p>
      </div>
      @endforeach
    </div>
    @endif
  </section>

  <!-- ================= 9. WEDDING GIFT ================= -->
  <section id="sec-gift" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden"
    style="background:linear-gradient(180deg,transparent,rgba(232,162,176,.12),transparent)">
    <svg class="butterfly pointer-events-none absolute top-12 left-[10%] w-9 z-0" style="animation-delay:-6s"><use href="#ts-butterfly"/></svg>
    <svg class="anim-zoom relative z-10 w-14 h-14 mb-3 sway-slow"><use href="#ts-flower"/></svg>
    <h2 class="anim-zoom font-script text-[2.6rem] text-surga-ink mb-3 relative z-10">Wedding Gift</h2>
    <p class="anim-fade font-serif italic text-surga-ink/65 text-[15px] leading-relaxed mb-8 max-w-[300px] relative z-10">Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Dan jika memberi adalah ungkapan tanda kasih Anda, Anda dapat memberi kado secara cashless.</p>
    @if(!empty($giftRecipient))
    <p class="anim-fade text-xs text-surga-ink/55 mb-6 relative z-10">Penerima: <span class="font-semibold text-surga-deepsage">{{ $giftRecipient }}</span></p>
    @endif
    @if(!empty($accounts))
    <div class="relative z-10 w-full max-w-[340px] space-y-4">
@if($qrisEnabled)
<div style="max-width:340px;margin:0 auto 18px;background:#ffffff;border-radius:16px;padding:20px;text-align:center;box-shadow:0 10px 30px rgba(0,0,0,.18);color:#222222;grid-column:1/-1">
<div style="font-weight:800;letter-spacing:.18em;font-size:13px;color:#c0392b">QRIS</div>
<p style="font-size:12px;opacity:.65;margin:4px 0 12px">Scan untuk kirim hadiah &mdash; semua e-wallet &amp; m-banking</p>
<img src="{{ $qrisImage }}" alt="QRIS" style="width:210px;height:210px;object-fit:contain;margin:0 auto;display:block">
@if($qrisName)<p style="font-size:13px;font-weight:600;margin-top:10px">{{ $qrisName }}</p>@endif
<p style="font-size:11px;opacity:.55;margin-top:4px">atau pilih transfer bank di bawah ini</p>
</div>
@endif
      @foreach($accounts as $i => $a)
      <div class="anim-zoom card-surga p-5 text-left" style="border-width:2px">
        <div class="flex items-center justify-between mb-3">
          <p class="text-surga-gold tracking-[0.25em] text-[11px] font-semibold">{{ $a['bank'] }}</p>
          @if(!empty($a['logo']))<img src="{{ $a['logo'] }}" alt="Logo bank" class="h-7 max-w-[100px] object-contain bg-white rounded-lg px-1 border border-surga-sage/30" />@endif
        </div>
        <p class="font-serif text-surga-ink text-xl tracking-[0.12em]" id="accNo{{ $i }}">{{ $a['no'] }}</p>
        <p class="text-surga-ink/55 text-xs mt-1 mb-4">a.n. {{ $a['an'] }}</p>
        <button class="btn-gold copy-btn w-full text-[10px] font-semibold tracking-[0.25em] py-3 rounded-xl" data-i="{{ $i }}">SALIN NOMOR</button>
      </div>
      @endforeach
    </div>
    @else
    <p class="anim-fade font-serif italic text-surga-ink/55 relative z-10">Informasi amplop digital akan segera hadir.</p>
    @endif
    @if(jklGet('gift_address'))
    <div class="anim-zoom relative z-10 w-full max-w-[340px] card-surga p-5 mt-5 text-left">
      <p class="text-surga-gold tracking-[0.25em] text-[10px] font-semibold mb-2">KIRIM HADIAH FISIK</p>
      <p class="text-surga-ink/75 text-sm leading-relaxed">{{ jklGet('gift_address') }}</p>
      @if(jklGet('gift_maps_link'))
      <a href="{{ jklGet('gift_maps_link') }}" target="_blank" rel="noopener" class="btn-outline inline-block mt-3 text-[10px] tracking-[0.25em] px-5 py-2.5 rounded-full">BUKA PETA</a>
      @endif
    </div>
    @endif
  </section>

  <!-- ================= 10. RSVP & UCAPAN ================= -->
  @if(jklGet('guest_qr_enabled') && !empty($guestQrCode))
<section id="sec-qr" style="position:relative;padding:72px 24px;display:flex;flex-direction:column;align-items:center;text-align:center">
  <h2 style="font-family:serif;font-size:30px;margin-bottom:10px">QR Check-In</h2>
  <p style="opacity:.75;max-width:340px;line-height:1.7;margin-bottom:22px">Simpan dan tunjukkan QR ini kepada panitia saat check-in di lokasi acara.</p>
  <div style="background:#fff;padding:16px;border-radius:18px;box-shadow:0 14px 34px rgba(0,0,0,.25)"><div id="qrBox"></div></div>
  <p style="margin-top:16px;font-weight:600">{{ $guestName }}</p>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>if(window.QRCode){new QRCode(document.getElementById('qrBox'),{text:"{{ $guestQrCode }}",width:200,height:200,correctLevel:QRCode.CorrectLevel.M});}</script>
@endif
<section id="sec-ucapan" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden">
    <svg class="anim-zoom relative z-10 w-14 h-14 mb-3 sway-slow"><use href="#ts-flower-white"/></svg>
    <h2 class="anim-zoom font-script text-[2.6rem] text-surga-ink mb-3 relative z-10">RSVP</h2>
    <p class="anim-fade font-serif italic text-surga-ink/65 text-[14px] leading-relaxed mb-8 max-w-[300px] relative z-10">Tanpa mengurangi rasa hormat kami, mohon konfirmasi kehadiran Anda terlebih dahulu.</p>
    <form id="rsvpForm" class="anim-zoom relative z-10 w-full max-w-[340px] card-surga p-5 text-left space-y-3 mb-10">
      <input type="text" name="nama" required placeholder="Nama lengkap" class="field-surga" />
      <select name="kehadiran" class="field-surga" required>
        <option value="">Konfirmasi kehadiran</option>
        <option value="Hadir">Hadir, Insya Allah</option>
        <option value="Tidak Hadir">Berhalangan</option>
      </select>
      <input type="number" name="jumlah" min="1" max="10" value="1" class="field-surga" />
      <button type="submit" class="btn-gold w-full text-[11px] font-semibold tracking-[0.25em] py-3.5 rounded-xl">KIRIM RSVP</button>
    </form>

    <h2 class="anim-zoom font-script text-[2.6rem] text-surga-ink mb-3 relative z-10">Best Wishes</h2>
    <p class="anim-fade font-serif italic text-surga-ink/65 text-[14px] mb-8 relative z-10">Kirimkan ucapan &amp; do&rsquo;a restu.</p>
    <form id="wishForm" class="anim-zoom relative z-10 w-full max-w-[340px] card-surga p-5 text-left space-y-3 mb-6">
      <input type="text" name="nama" required placeholder="Nama Anda" class="field-surga" />
      <textarea name="pesan" required rows="3" placeholder="Tulis ucapan & doa restu..." class="field-surga"></textarea>
      <button type="submit" class="btn-gold w-full text-[11px] font-semibold tracking-[0.25em] py-3.5 rounded-xl">KIRIM UCAPAN</button>
    </form>
    <div id="wishList" class="anim-fade relative z-10 w-full max-w-[340px] space-y-3 max-h-80 overflow-y-auto pr-1 text-left"></div>
  </section>

  <!-- ================= 11. FOOTER ================= -->
@if(jklGet('live_stream_link'))
@php
  $liveUrl = jklGet('live_stream_link');
  $liveEmbed = $liveUrl;
  if (preg_match('~(?:youtube\.com/(?:watch\?v=|live/|shorts/|embed/)|youtu\.be/)([\w-]{6,})~', (string)$liveUrl, $lm)) { $liveEmbed = 'https://www.youtube-nocookie.com/embed/' . $lm[1]; }
  $liveId = $lm[1] ?? null;
  $liveThumb = $liveId ? 'https://i.ytimg.com/vi/' . $liveId . '/hqdefault.jpg' : '';
@endphp
<section class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden" style="background:linear-gradient(180deg,transparent,rgba(201,164,92,.10),transparent)" id="sec-live">
    <p class="anim-fade text-[10px] uppercase tracking-[0.4em] text-surga-deepsage mb-2 relative z-10">Live Streaming</p>
    <h2 class="anim-zoom font-script text-[2.6rem] text-surga-ink mb-3 relative z-10">Saksikan Langsung</h2>
    <p class="anim-fade font-serif italic text-surga-ink/65 text-[15px] leading-relaxed mb-9 max-w-[320px] relative z-10">Bagi Bapak/Ibu/Saudara/i yang berhalangan hadir, silakan saksikan acara kami secara langsung melalui tayangan berikut.</p>
    <div class="anim-zoom relative z-10 w-full max-w-[340px]" style="border-radius:16px;overflow:hidden;border:1px solid rgba(201,164,92,.55);box-shadow:0 18px 40px rgba(0,0,0,.3)">
<div style="position:relative;padding-top:56.25%">
@if($liveId)
<button type="button" onclick="playLive(this, '{{ $liveId }}')" aria-label="Putar Live Streaming" style="position:absolute;inset:0;width:100%;height:100%;border:0;padding:0;cursor:pointer;background:#000">
<img src="{{ $liveThumb }}" alt="Live Streaming" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.85">
<span style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:68px;height:48px;border-radius:12px;background:rgba(204,0,0,.93);display:flex;align-items:center;justify-content:center;box-shadow:0 10px 24px rgba(0,0,0,.45)"><span style="width:0;height:0;border-left:18px solid #fff;border-top:11px solid transparent;border-bottom:11px solid transparent;margin-left:4px"></span></span>
</button>
@else
<iframe src="{{ $liveEmbed }}" style="position:absolute;inset:0;width:100%;height:100%;border:0" allow="accelerometer; autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin" title="Live Streaming"></iframe>
@endif
</div>
</div>
<a href="{{ $liveUrl }}" target="_blank" rel="noopener" class="anim-fade relative z-10 inline-block mt-5 text-[11px] font-bold tracking-[0.22em] px-8 py-3 rounded-full" style="background:linear-gradient(105deg,#b8934a,#e6c887 55%,#c9a45c);color:#241a08;box-shadow:0 10px 24px rgba(90,70,20,.35)">BUKA LIVE STREAMING</a>
  </section>
@endif
<script>
function playLive(btn, id){
  var wrap = btn.parentNode;
  wrap.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0" style="position:absolute;inset:0;width:100%;height:100%;border:0" allow="accelerometer; autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen referrerpolicy="strict-origin-when-cross-origin" title="Live Streaming"></iframe>';
}
</script>
  <footer class="relative py-16 px-6 flex flex-col items-center text-center overflow-hidden"
    style="background:linear-gradient(180deg,transparent,rgba(122,155,118,.18))">
    <svg class="w-16 h-16 mb-4 sway-slow"><use href="#ts-flower"/></svg>
    <p class="font-serif italic text-surga-ink/65 text-[15px] leading-relaxed max-w-[300px] mb-4">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan do&rsquo;a restu kepada kedua mempelai.</p>
    <h2 class="font-script text-[2.4rem] text-surga-ink mb-2">{{ $groomNick }} <span class="text-gold-grad">&amp;</span> {{ $brideNick }}</h2>
    <p class="text-[10px] tracking-[0.3em] uppercase text-surga-deepsage/70">Terima kasih atas do&rsquo;a &amp; kehadirannya</p>
    <svg class="pointer-events-none absolute bottom-0 left-0 w-full h-24" viewBox="0 0 430 100" preserveAspectRatio="xMidYMax slice">
      <ellipse cx="60" cy="110" rx="100" ry="50" fill="#8fb996"/>
      <ellipse cx="210" cy="115" rx="80" ry="42" fill="#a9c7a5"/>
      <ellipse cx="360" cy="110" rx="100" ry="50" fill="#8fb996"/>
      <use href="#ts-flower" x="50" y="45" width="28" height="28"/>
      <use href="#ts-flower-white" x="180" y="52" width="26" height="26"/>
      <use href="#ts-flower" x="300" y="46" width="30" height="30"/>
    </svg>
    <div class="h-24"></div>
  </footer>
</main>

<canvas id="petalCanvas"></canvas>

<!-- musik melayang -->
<button id="musicFloat" aria-label="Putar / jeda musik" hidden>
  <svg class="note w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55A4 4 0 1 0 14 17V7h4V3h-6z"/></svg>
  <span class="bars"><span></span><span></span><span></span></span>
</button>

<!-- menu melayang: RSVP -> Ucapan -> Gift -->
<nav id="floatMenu" class="hidden" aria-label="Menu undangan">
        <a href="#sec-profil" data-sec="sec-profil"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7.5-4.7-9.5-9C1 8.5 3 5 6.5 5c2 0 3.5 1 4.5 2.5C12 6 13.5 5 15.5 5 19 5 21 8.5 20.5 12c-2 4.3-8.5 9-8.5 9z"/></svg><span>Mempelai</span></a>
        <a href="#sec-quotes" data-sec="sec-quotes"><svg class="w-[21px] h-[21px]" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 5.5C4.9 6.7 4 8.6 4 10.9V18h6.4v-6.4H7.2c0-1.9.8-3.2 2.3-4.2l-3.1-1.8zm9 0c-1.7 1.2-2.6 3.1-2.6 5.4V18h6.4v-6.4h-3.2c0-1.9.8-3.2 2.3-4.2l-3.1-1.8z"/></svg><span>Quotes</span></a>
        <a href="#sec-acara" data-sec="sec-acara"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path stroke-linecap="round" d="M8 3v4M16 3v4M3 10h18"/></svg><span>Acara</span></a>
        <a href="#sec-galeri" data-sec="sec-galeri"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.6"/><path stroke-linecap="round" stroke-linejoin="round" d="m5 19 5.5-5.5 3 3L19 11l2 2"/></svg><span>Galeri</span></a>
        <a href="#sec-kisah" data-sec="sec-kisah"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.5C10 4.8 7 4.3 4.5 4.5v13c2.5-.2 5.5.3 7.5 2 2-1.7 5-2.2 7.5-2v-13C17 4.3 14 4.8 12 6.5z"/><path stroke-linecap="round" d="M12 6.5v13"/></svg><span>Kisah</span></a>
        <a href="#sec-gift" data-sec="sec-gift"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8v13H3V8M1 3h22v5H1zM12 8v13M12 8H7.5a2.5 2.5 0 0 1 0-5C11 3 12 8 12 8zm0 0h4.5a2.5 2.5 0 0 0 0-5C13 3 12 8 12 8z"/></svg><span>Gift</span></a>
        <a href="#sec-qr" data-sec="sec-qr"><svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3.5" y="3.5" width="6.5" height="6.5" rx="1.5"/><rect x="14" y="3.5" width="6.5" height="6.5" rx="1.5"/><rect x="3.5" y="14" width="6.5" height="6.5" rx="1.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 14h3.5v3.5H14zM20.5 14v.5M14 20.5h.5M17.5 17.5h3v3h-3z"/></svg><span>QR</span></a>
        <a href="#sec-ucapan" data-sec="sec-ucapan"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg><span>RSVP</span></a>
        <a href="#sec-ucapan" data-sec="sec-ucapan"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg><span>Ucapan</span></a>
        <a href="#sec-live" data-sec="sec-live"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path fill="currentColor" stroke="none" d="M10 8.3v7.4c0 .6.7 1 1.2.7l6-3.7c.5-.3.5-1 0-1.3l-6-3.7c-.5-.3-1.2.1-1.2.6z"/></svg><span>Live</span></a>
      </nav>
<div id="toast"></div>

<!-- lightbox -->
<div id="lightbox" class="fixed inset-0 z-[80] hidden items-center justify-center p-4" style="background:rgba(74,68,88,.94)">
  <button id="lbClose" class="absolute top-4 right-4 text-white text-3xl leading-none px-2" aria-label="Tutup">&times;</button>
  <button id="lbPrev" class="absolute left-2 text-white text-4xl px-2" aria-label="Sebelumnya">&#8249;</button>
  <img id="lbImg" class="max-w-full max-h-[80vh] rounded-2xl object-contain bg-white p-1" alt="Galeri" />
  <button id="lbNext" class="absolute right-2 text-white text-4xl px-2" aria-label="Berikutnya">&#8250;</button>
  <div id="lbCount" class="absolute bottom-5 text-white/80 text-sm tracking-widest"></div>
</div>

<script>
"use strict";
/* ================= DATA ================= */
const JWA = {
  invId: {{ (int)$invId }},
  countdownISO: @json($countdownISO),
  musicUrl: @json($musicUrl),
  musicVol: {{ $musVol }},
  musicAuto: @json($musAuto),
  guestName: @json($toName)
};
const reduced = matchMedia("(prefers-reduced-motion: reduce)").matches;
function toast(m){ window.alert(m); return; const t=document.getElementById("toast"); t.textContent=m; t.classList.add("show");
  clearTimeout(t._h); t._h=setTimeout(()=>t.classList.remove("show"),2600); }
function esc(s){ return String(s??"").replace(/[&<>"']/g,c=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"}[c])); }
function timeAgo(s){ const d=new Date(String(s).replace(" ","T")+":00"); if(isNaN(d)) return "";
  const n=(Date.now()-d.getTime())/1000; if(n<60) return "Baru saja";
  if(n<3600) return Math.floor(n/60)+" menit yang lalu";
  if(n<86400) return Math.floor(n/3600)+" jam yang lalu";
  return Math.floor(n/86400)+" hari yang lalu"; }

/* ---------- 0. KANVAS KELOPAK JATUH ---------- */
(function(){
  const cv=document.getElementById("petalCanvas"), ctx=cv.getContext("2d");
  let W,H,ps=[];
  const COLORS=["#f7c8d3","#fbdce3","#ffffff","#e8a2b0","#f6e7ec"];
  function size(){ W=cv.width=innerWidth; H=cv.height=innerHeight; }
  size(); addEventListener("resize",size);
  function spawn(top){ return {
    x:Math.random()*W, y:top? -20 : Math.random()*H,
    s:6+Math.random()*10, vy:.5+Math.random()*1.1, vx:(Math.random()-.5)*.6,
    ph:Math.random()*6.28, sw:.5+Math.random()*1.4, rot:Math.random()*6.28, vr:(Math.random()-.5)*.03,
    c:COLORS[Math.random()*COLORS.length|0], a:.55+Math.random()*.4 }; }
  for(let i=0;i<26;i++) ps.push(spawn(false));
  function petal(p){
    ctx.save(); ctx.translate(p.x,p.y); ctx.rotate(p.rot); ctx.globalAlpha=p.a;
    ctx.fillStyle=p.c; ctx.beginPath();
    ctx.ellipse(0,0,p.s*.55,p.s,0,0,6.29); ctx.fill();
    ctx.globalAlpha=p.a*.5; ctx.fillStyle="#fff";
    ctx.beginPath(); ctx.ellipse(-p.s*.15,-p.s*.2,p.s*.2,p.s*.38,0,0,6.29); ctx.fill();
    ctx.restore();
  }
  (function loop(t){
    requestAnimationFrame(loop);
    if(reduced) return;
    ctx.clearRect(0,0,W,H);
    const time=(t||0)/1000;
    for(const p of ps){
      p.y+=p.vy; p.x+=p.vx+Math.sin(time*p.sw+p.ph)*.5; p.rot+=p.vr;
      if(p.y>H+24) Object.assign(p,spawn(true));
      petal(p);
    }
  })();
})();

/* ---------- 1. PRELOADER ---------- */
(function(){
  const bar=document.getElementById("loader-bar"), pct=document.getElementById("loader-percent");
  let p=0; const iv=setInterval(()=>{
    p=Math.min(100, p+Math.random()*22);
    bar.style.width=p+"%"; pct.textContent=Math.floor(p)+"%";
    if(p>=100){ clearInterval(iv);
      setTimeout(()=>{ const pl=document.getElementById("preloader");
        gsap.to(pl,{opacity:0,duration:.5,onComplete:()=>pl.remove()}); },250); }
  },180);
})();

/* ---------- 2. PARALLAX ORNAMEN ---------- */
function initParallax(){
  if(reduced) return;
  gsap.utils.toArray(".orn").forEach(el=>{
    const sp=parseFloat(el.dataset.speed||"1");
    const dist=()=>Math.round(window.innerHeight*0.10*sp);
    gsap.fromTo(el,{y:()=>-dist()},{y:()=>dist(),ease:"none",
      scrollTrigger:{trigger:el.closest("section")||el,
        start:()=> "top bottom", end:()=> "bottom top", scrub:0.8}});
  });
}

/* ---------- 3. ANIMASI SCROLL ---------- */
function initSectionScrollAnimations(){
  gsap.utils.toArray(".scroll-section").forEach(sec=>{
    const Z=sec.querySelectorAll(".anim-zoom"), F=sec.querySelectorAll(".anim-fade");
    const tl=gsap.timeline({ scrollTrigger:{ trigger:sec, start:"top 78%", end:"bottom 25%",
      toggleActions:"play none none reverse" }});
    if(Z.length) tl.fromTo(Z,{scale:.82,y:28,opacity:0},{scale:1,y:0,opacity:1,duration:1.1,ease:"power3.out",stagger:.12},.05);
    if(F.length) tl.fromTo(F,{y:20,opacity:0},{y:0,opacity:1,duration:.9,ease:"power2.out",stagger:.08},.18);
  });
  ScrollTrigger.refresh();
}

/* ---------- 4. BUKA UNDANGAN ---------- */
document.getElementById("btn-open").addEventListener("click",()=>{
  gsap.set(".hero-stagger",{y:24,opacity:0});
  const tl=gsap.timeline({ onComplete:()=>{
    document.body.classList.remove("is-locked");
    const g=document.getElementById("cover-gate"); if(g) g.remove();
    document.getElementById("musicFloat").hidden=false;
    document.getElementById("floatMenu").hidden=false;
    initSectionScrollAnimations();
    initParallax();
  }});
  tl.to(["#gate-header","#gate-center","#gate-footer"],{y:-14,opacity:0,duration:.5,stagger:.06,ease:"power2.in"},0)
    .to("#cover-gate",{opacity:0,duration:.6,ease:"power2.inOut"},.45)
    .to(".hero-stagger",{y:0,opacity:1,duration:1.1,ease:"expo.out",stagger:.12},.55);
  startMusic(true);
});

/* ---------- 5. COUNTDOWN ---------- */
(function(){
  const target=new Date(JWA.countdownISO).getTime(), pad=n=>String(n).padStart(2,"0");
  const el={ d:document.getElementById("cd-d"), h:document.getElementById("cd-h"),
    m:document.getElementById("cd-m"), s:document.getElementById("cd-s") };
  function tick(){ const d=Math.max(0,target-Date.now());
    el.d.textContent=pad(Math.floor(d/864e5)); el.h.textContent=pad(Math.floor(d/36e5)%24);
    el.m.textContent=pad(Math.floor(d/6e4)%60); el.s.textContent=pad(Math.floor(d/1e3)%60); }
  tick(); setInterval(tick,1000);
})();

/* ---------- 6. MUSIK ---------- */
let audioEl=null, musicOn=false;
async function resolveMusicUrl(){
  if(JWA.musicUrl) return JWA.musicUrl;
  try{ const r=await fetch("/api/music?inv_id="+JWA.invId); const j=await r.json();
    if(j&&j.url) return j.url; if(j&&j.music&&j.music.file_path) return j.music.file_path; }catch(e){}
  return null;
}
async function startMusic(auto){
  const url=await resolveMusicUrl(); if(!url) return;
  if(auto && JWA.musicAuto === false) return;
  if(!audioEl){ audioEl=new Audio(url); audioEl.loop=true; audioEl.volume=(JWA.musicVol||50)/100; }
  try{ await audioEl.play(); musicOn=true;
    document.getElementById("musicFloat").classList.add("playing"); }
  catch(e){ if(!auto) toast("Musik tidak dapat diputar"); }
}
document.getElementById("musicFloat").addEventListener("click", async function(){
  if(musicOn && audioEl){ audioEl.pause(); musicOn=false; this.classList.remove("playing"); return; }
  startMusic(false);
});

/* ---------- 7. RSVP ---------- */
document.getElementById("rsvpForm").addEventListener("submit", async function(e){
  e.preventDefault();
  const fd=new FormData(this);
  const payload={ nama:fd.get("nama"), kehadiran:fd.get("kehadiran"),
    jumlah:fd.get("jumlah"), inv_id:JWA.invId };
  try{
    const r=await fetch("/api/rsvp",{method:"POST",headers:{"Content-Type":"application/json"},
      body:JSON.stringify(payload)});
    const j=await r.json();
    toast(j&&j.message?j.message:"Terima kasih atas konfirmasinya!");
    this.reset();
  }catch(err){ toast("Gagal mengirim. Coba lagi."); }
});

/* ---------- 8. UCAPAN ---------- */
async function loadWishes(){
  const box=document.getElementById("wishList");
  try{
    const r=await fetch("/api/ucapan?inv_id="+JWA.invId); const j=await r.json();
    const list=Array.isArray(j)?j:(j.data||[]);
    if(!list.length){ box.innerHTML='<p class="text-center text-surga-ink/50 text-sm font-serif italic">Belum ada ucapan. Jadilah yang pertama!</p>'; return; }
    box.innerHTML=list.map(w=>
      '<div class="card-surga p-4"><p class="font-semibold text-surga-deepsage text-sm">'+esc(w.nama)+'</p>'+
      '<p class="text-surga-ink/70 text-[13px] mt-1 leading-relaxed">'+esc(w.pesan)+'</p>'+
      '<p class="text-surga-gold/80 text-[10px] mt-2 tracking-wider">'+esc(timeAgo(w.created_at))+'</p></div>'
    ).join("");
  }catch(e){ box.innerHTML='<p class="text-center text-surga-ink/50 text-sm">Gagal memuat ucapan.</p>'; }
}
document.getElementById("wishForm").addEventListener("submit", async function(e){
  e.preventDefault();
  const fd=new FormData(this);
  try{
    const r=await fetch("/api/ucapan",{method:"POST",headers:{"Content-Type":"application/json"},
      body:JSON.stringify({nama:fd.get("nama"),pesan:fd.get("pesan"),inv_id:JWA.invId})});
    const j=await r.json();
    toast(j&&j.message?j.message:"Ucapan terkirim!");
    this.reset(); loadWishes();
  }catch(err){ toast("Gagal mengirim. Coba lagi."); }
});
loadWishes();

/* ---------- 9. SALIN REKENING ---------- */
document.querySelectorAll(".copy-btn").forEach(btn=>{
  btn.addEventListener("click",()=>{
    const i=btn.dataset.i, el=document.getElementById("accNo"+i);
    const txt=el?el.textContent.trim():"";
    if(navigator.clipboard){ navigator.clipboard.writeText(txt).then(()=>toast("Nomor rekening tersalin")); }
    else{ const ta=document.createElement("textarea"); ta.value=txt; document.body.appendChild(ta);
      ta.select(); try{document.execCommand("copy"); toast("Nomor rekening tersalin");}catch(e){}
      ta.remove(); }
  });
});

/* ---------- 10. LIGHTBOX ---------- */
(function(){
  const lb=document.getElementById("lightbox"), img=document.getElementById("lbImg"),
    count=document.getElementById("lbCount");
  const items=[...document.querySelectorAll(".gal-item")];
  let idx=0, sx=0;
  function show(i){ idx=(i+items.length)%items.length;
    img.src=items[idx].dataset.full; count.textContent=(idx+1)+" / "+items.length; }
  items.forEach((it,i)=>it.addEventListener("click",()=>{
    lb.classList.remove("hidden"); lb.classList.add("flex"); show(i); }));
  function close(){ lb.classList.add("hidden"); lb.classList.remove("flex"); }
  document.getElementById("lbClose").addEventListener("click",close);
  document.getElementById("lbPrev").addEventListener("click",e=>{e.stopPropagation();show(idx-1);});
  document.getElementById("lbNext").addEventListener("click",e=>{e.stopPropagation();show(idx+1);});
  lb.addEventListener("click",e=>{ if(e.target===lb) close(); });
  addEventListener("keydown",e=>{ if(lb.classList.contains("hidden"))return;
    if(e.key==="Escape")close(); if(e.key==="ArrowLeft")show(idx-1); if(e.key==="ArrowRight")show(idx+1); });
  img.addEventListener("touchstart",e=>{sx=e.touches[0].clientX;},{passive:true});
  img.addEventListener("touchmove",e=>{ const dx=e.touches[0].clientX-sx;
    if(Math.abs(dx)>60){ show(idx+(dx<0?1:-1)); sx=e.touches[0].clientX; } },{passive:true});
})();
</script>

<script>
(function(){
  var bar = document.getElementById('floatMenu') || document.getElementById('bottomNav');
  if(!bar) return;
  var links = [].slice.call(bar.querySelectorAll('a[data-sec]'));
  links = links.filter(function(a){ var s = document.getElementById(a.getAttribute('data-sec')); if(!s){ a.remove(); return false; } a._sec = s; return true; });
  function spy(){
    var y = window.scrollY + window.innerHeight * 0.4, cur = null;
    links.forEach(function(a){ if(a._sec.offsetTop <= y) cur = a; });
    links.forEach(function(a){ a.classList.toggle('active', a === cur); });
  }
  window.addEventListener('scroll', spy, {passive:true});
  links.forEach(function(a){ a.addEventListener('click', function(e){ e.preventDefault(); a._sec.scrollIntoView({behavior:'smooth'}); }); });
  spy();
  if(bar.classList.contains('hidden')){
    var tries = 0;
    var watch = setInterval(function(){
      tries++;
      var gate = document.getElementById('cover-gate') || document.getElementById('cover');
      var gateGone = !gate || gate.classList.contains('hidden') || gate.style.display === 'none' || getComputedStyle(gate).display === 'none' || getComputedStyle(gate).visibility === 'hidden';
      if(gateGone){ bar.classList.remove('hidden'); clearInterval(watch); }
      if(tries > 60) clearInterval(watch);
    }, 400);
  }
})();
</script>
</body>
</html>
