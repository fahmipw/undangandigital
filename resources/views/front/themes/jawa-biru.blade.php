<?php
/* ============================================================
   TEMA: JAWA BIRU — adat Jawa elegan biru navy + emas
   Dipilih via settings: theme = jawa-biru
   Ornamen (gunungan, sudut ukiran, damask) digambar sebagai
   SVG orisinal di dalam file ini.
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
if (!function_exists('jklDayNum')) {
  function jklDayNum($dateStr) { $ts = strtotime((string)$dateStr); return $ts ? date('j', $ts) : ''; }
}
if (!function_exists('jklDayName')) {
  function jklDayName($dateStr) {
    $days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $ts = strtotime((string)$dateStr); return $ts ? $days[date('w', $ts)] : '';
  }
}
if (!function_exists('jklMonthName')) {
  function jklMonthName($dateStr) {
    $months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime((string)$dateStr); return $ts ? $months[date('n', $ts)] : '';
  }
}
if (!function_exists('jklYear')) {
  function jklYear($dateStr) { $ts = strtotime((string)$dateStr); return $ts ? date('Y', $ts) : ''; }
}
if (!function_exists('jklTime')) {
  function jklTime($t) {
    $t = trim((string)$t); if ($t === '') return '08:00';
    $t = str_replace('.', ':', $t);
    return preg_match('/^\d{1,2}(:\d{2})?$/', $t) ? (strpos($t, ':') === false ? $t.':00' : $t) : '08:00';
  }
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
$monoBride = strtoupper(substr(trim($brideNick), 0, 1));
$monoGroom = strtoupper(substr(trim($groomNick), 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>The Wedding of {{ $brideNick }} &amp; {{ $groomNick }}</title>
<meta property="og:type" content="website">
<meta property="og:title" content="Undangan Pernikahan {{ $brideNick }} & {{ $groomNick }}">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        jbiru: { 950:'#060f1d', 900:'#0a1b30', 800:'#10283f', 700:'#1b3a5c', 100:'#dbe6f2' },
        emas:  { DEFAULT:'#c9a45c', light:'#e6c887', deep:'#9a7b3f' },
        gading:'#fdfbf7'
      },
      fontFamily: {
        script: ['"Pinyon Script"','cursive'],
        serif:  ['"Cormorant Garamond"','serif'],
        sans:   ['"Plus Jakarta Sans"','sans-serif']
      }
    }
  }
}
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pinyon+Script&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<style>
  html{scroll-behavior:smooth}
  body{background:#060f1d;font-family:'Plus Jakarta Sans',sans-serif}
  .invitation-frame{max-width:430px;margin:0 auto;position:relative;background:#0a1b30;
    box-shadow:0 0 60px rgba(0,0,0,.7);overflow:hidden;min-height:100vh}
  body.is-locked{overflow:hidden}
  .text-gold-grad{background:linear-gradient(105deg,#9a7b3f 0%,#e6c887 35%,#c9a45c 55%,#f3dfae 75%,#9a7b3f 100%);
    -webkit-background-clip:text;background-clip:text;color:transparent}
  .damask{position:absolute;inset:0;pointer-events:none;opacity:.5;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='72' height='72' viewBox='0 0 72 72'%3E%3Cg fill='none' stroke='%231b3a5c' stroke-width='1.1'%3E%3Cpath d='M36 8 L48 24 L36 40 L24 24 Z'/%3E%3Ccircle cx='36' cy='24' r='4'/%3E%3Cpath d='M36 40 L44 52 L36 64 L28 52 Z'/%3E%3Ccircle cx='36' cy='52' r='2.6'/%3E%3Cpath d='M8 36 L20 44 L8 52 L0 44 Z' opacity='.6'/%3E%3Cpath d='M64 36 L72 44 L64 52 L52 44 Z' opacity='.6'/%3E%3C/g%3E%3C/svg%3E")}
  .card-gading{background:#fdfbf7;color:#22364c;border-radius:1.1rem;position:relative;
    box-shadow:0 24px 50px -18px rgba(0,0,0,.65)}
  .card-navy{background:linear-gradient(160deg,rgba(27,58,92,.85),rgba(10,27,48,.92));
    border:1px solid rgba(201,164,92,.4);border-radius:1.1rem;position:relative;
    box-shadow:0 24px 50px -18px rgba(0,0,0,.7);backdrop-filter:blur(4px)}
  .btn-gold{background:linear-gradient(105deg,#b8934a,#e6c887 55%,#c9a45c);color:#0a1b30;
    box-shadow:0 10px 24px -8px rgba(201,164,92,.55);transition:transform .25s,box-shadow .25s}
  .btn-gold:hover{transform:translateY(-2px)}
  .btn-outline{border:1px solid rgba(201,164,92,.7);color:#e6c887;transition:all .25s;background:rgba(201,164,92,.06)}
  .btn-outline:hover{background:rgba(201,164,92,.18)}
  .btn-outline-navy{border:1px solid rgba(154,123,63,.65);color:#7c6228;transition:all .25s}
  .btn-outline-navy:hover{background:rgba(201,164,92,.14)}
  .hairline{height:1px;background:linear-gradient(90deg,transparent,rgba(201,164,92,.75),transparent)}
  .field-biru{width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(201,164,92,.35);
    border-radius:.8rem;color:#eef3f9;padding:.8rem 1rem;font-size:.9rem;outline:none;transition:border-color .25s}
  .field-biru::placeholder{color:rgba(219,230,242,.4)}
  .field-biru:focus{border-color:#c9a45c}
  select.field-biru option{color:#0a1b30}
  .corner{position:absolute;width:52px;height:52px;pointer-events:none;color:#c9a45c;opacity:.9}
  .corner.tl{top:8px;left:8px}
  .corner.tr{top:8px;right:8px;transform:scaleX(-1)}
  .corner.bl{bottom:8px;left:8px;transform:scaleY(-1)}
  .corner.br{bottom:8px;right:8px;transform:scale(-1)}
  #musicFloat{position:fixed;bottom:96px;right:max(14px,calc(50% - 215px + 14px));z-index:45;
    width:52px;height:52px;border-radius:50%;border:1px solid rgba(230,200,135,.7);
    background:radial-gradient(circle at 32% 30%,#274a6e,#0a1b30 70%);color:#e6c887;
    display:flex;align-items:center;justify-content:center;box-shadow:0 12px 28px rgba(0,0,0,.5);cursor:pointer}
  #musicFloat .bars{display:none;gap:2.5px;align-items:flex-end;height:16px}
  #musicFloat .bars span{width:3px;background:#e6c887;animation:eqB 0.9s ease-in-out infinite}
  #musicFloat .bars span:nth-child(2){animation-delay:.25s}
  #musicFloat .bars span:nth-child(3){animation-delay:.5s}
  #musicFloat.playing .bars{display:flex}
  #musicFloat.playing .note{display:none}
  @keyframes eqB{0%,100%{height:5px}50%{height:16px}}
  #floatMenu{position:fixed;right:max(14px,calc(50% - 215px + 14px));bottom:168px;z-index:45;
    display:flex;flex-direction:column;gap:9px}
  #floatMenu a{width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;
    background:rgba(10,27,48,.82);border:1px solid rgba(201,164,92,.55);color:#e6c887;
    box-shadow:0 10px 22px rgba(0,0,0,.45);backdrop-filter:blur(4px)}
  #floatMenu.hidden, #musicFloat.hidden{display:none !important}
  #toast{position:fixed;left:50%;bottom:110px;transform:translate(-50%,20px);z-index:90;
    background:#fdfbf7;color:#0a1b30;border:1px solid #c9a45c;border-radius:999px;
    padding:.65rem 1.2rem;font-size:.8rem;font-weight:600;opacity:0;pointer-events:none;transition:all .35s;
    box-shadow:0 14px 30px rgba(0,0,0,.5);max-width:88vw;text-align:center}
  #toast.show{opacity:1;transform:translate(-50%,0)}
  .orn{will-change:transform}
  #petalCanvas{position:fixed;top:0;left:50%;transform:translateX(-50%);width:min(430px,100vw);
    height:100dvh;z-index:44;pointer-events:none}
  #wish-list > div{animation:jbFadeUp .6s ease both}
  @keyframes jbFadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
  @media (prefers-reduced-motion: reduce){
    #musicFloat .bars span{animation:none}
  }

/* Standar menu klasik emas: bottom bar */
#floatMenu,#bottomNav{position:fixed;left:50%;transform:translateX(-50%);right:auto;bottom:10px;z-index:46;display:flex;flex-direction:row;gap:2px;background:rgba(255,253,247,.94);border:1px solid rgba(151,128,69,.4);border-radius:20px;padding:7px 8px;backdrop-filter:blur(10px);box-shadow:0 14px 34px rgba(90,70,20,.25);width:min(94vw,460px);max-width:94vw;flex-wrap:wrap;justify-content:center;overflow:visible;row-gap:4px}
#floatMenu::-webkit-scrollbar,#bottomNav::-webkit-scrollbar{display:none}
#floatMenu a,#bottomNav a{display:flex;flex-direction:column;align-items:center;gap:3px;flex:none;width:54px;height:auto;padding:7px 0;border-radius:13px;color:#978045!important;background:transparent!important;border:none!important;box-shadow:none!important;text-decoration:none;font-size:8px;font-weight:700;letter-spacing:.1em;text-transform:uppercase}
#floatMenu a svg,#bottomNav a svg{width:21px;height:21px}
#floatMenu a.active,#bottomNav a.active{background:rgba(151,128,69,.14)!important;box-shadow:inset 0 0 0 1.5px #b8934a!important;color:#7c652f!important}
#floatMenu.hidden,#bottomNav[hidden]{display:none!important}
#musicFloat{bottom:130px!important;background:linear-gradient(160deg,rgba(43,33,24,.95),rgba(23,18,8,.97))!important;border:1px solid rgba(201,164,92,.8)!important;color:#e6c887!important;box-shadow:0 10px 24px rgba(90,70,20,.45)!important}
#musicFloat .bars span{background:#e6c887!important}
#vinyl{bottom:96px!important}
</style>
</head>
<body class="is-locked antialiased">
<svg width="0" height="0" class="absolute" aria-hidden="true">
  <defs>
    <linearGradient id="goldLin" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="#9a7b3f"/><stop offset=".38" stop-color="#e6c887"/>
      <stop offset=".6" stop-color="#c9a45c"/><stop offset=".8" stop-color="#f3dfae"/>
      <stop offset="1" stop-color="#9a7b3f"/>
    </linearGradient>
    <!-- gunungan orisinal: siluet api-daun simetris + pohon hayat -->
    <symbol id="jb-gunungan" viewBox="0 0 200 250">
      <path fill="url(#goldLin)" d="M100 2 C112 26 128 38 140 58 C156 84 166 108 168 140 C170 172 162 205 148 232 L52 232 C38 205 30 172 32 140 C34 108 44 84 60 58 C72 38 88 26 100 2 Z"/>
      <path fill="#0a1b30" opacity=".28" d="M100 22 C109 40 121 50 130 66 C142 87 149 106 150 134 C151 160 145 186 135 206 L65 206 C55 186 49 160 50 134 C51 106 58 87 70 66 C79 50 91 40 100 22 Z"/>
      <g fill="url(#goldLin)">
        <path d="M97 206 L97 150 C97 138 90 132 82 126 C90 126 97 130 100 138 C103 130 110 126 118 126 C110 132 103 138 103 150 L103 206 Z"/>
        <circle cx="100" cy="112" r="7"/>
        <circle cx="82" cy="128" r="5"/><circle cx="118" cy="128" r="5"/>
        <circle cx="74" cy="104" r="4.4"/><circle cx="126" cy="104" r="4.4"/>
        <circle cx="84" cy="84" r="4"/><circle cx="116" cy="84" r="4"/>
        <circle cx="100" cy="66" r="4.6"/>
        <path d="M100 196 C88 188 76 188 66 196 C76 202 90 202 100 196 Z"/>
        <path d="M100 196 C112 188 124 188 134 196 C124 202 110 202 100 196 Z"/>
      </g>
      <rect x="46" y="232" width="108" height="6" fill="url(#goldLin)"/>
      <rect x="58" y="242" width="84" height="4" fill="url(#goldLin)" opacity=".7"/>
    </symbol>
    <!-- ornamen sudut ukiran -->
    <symbol id="jb-corner" viewBox="0 0 60 60">
      <g fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
        <path d="M4 56 L4 22 C4 12 12 4 22 4 L56 4"/>
        <path d="M10 56 L10 26 C10 17 17 10 26 10 L56 10" opacity=".55"/>
        <path d="M4 34 C14 32 20 26 22 16 C30 20 32 30 27 37 C22 43 12 42 4 34 Z" fill="currentColor" stroke="none" opacity=".85"/>
        <circle cx="15" cy="15" r="2.6" fill="currentColor" stroke="none"/>
        <path d="M30 4 C32 10 38 13 44 12" opacity=".8"/>
      </g>
    </symbol>
    <!-- divider emas -->
    <symbol id="jb-divider" viewBox="0 0 300 24">
      <g stroke="url(#goldLin)" stroke-width="1.4">
        <line x1="0" y1="12" x2="112" y2="12"/><line x1="188" y1="12" x2="300" y2="12"/>
      </g>
      <path fill="url(#goldLin)" d="M150 3 L159 12 L150 21 L141 12 Z"/>
      <circle cx="126" cy="12" r="2.4" fill="url(#goldLin)"/>
      <circle cx="174" cy="12" r="2.4" fill="url(#goldLin)"/>
      <path fill="url(#goldLin)" d="M112 12 C120 6 128 6 134 10 C128 14 120 14 112 12 Z"/>
      <path fill="url(#goldLin)" d="M188 12 C180 6 172 6 166 10 C172 14 180 14 188 12 Z"/>
    </symbol>
  </defs>
</svg>
<main class="invitation-frame min-h-screen">
  <canvas id="petalCanvas" aria-hidden="true"></canvas>

  <!-- ================= 1. PRELOADER ================= -->
  <section id="preloader" class="fixed inset-0 z-50 mx-auto max-w-[430px] flex flex-col items-center justify-center px-6 text-center bg-jbiru-900">
    <div class="damask"></div>
    <svg class="w-16 h-[5rem] mb-5 relative"><use href="#jb-gunungan"/></svg>
    <p class="relative text-[10px] uppercase tracking-[0.4em] text-emas mb-2">Wedding Invitation</p>
    <h1 class="relative font-serif text-3xl tracking-[0.14em] text-gading">{{ strtoupper($groomNick) }} <span class="text-gold-grad">&amp;</span> {{ strtoupper($brideNick) }}</h1>
    <div class="relative w-40 h-[3px] bg-white/10 rounded-full overflow-hidden mb-2 mt-6">
      <div id="loader-bar" class="h-full w-0" style="background:linear-gradient(90deg,#9a7b3f,#e6c887)"></div>
    </div>
    <span id="loader-percent" class="relative text-[11px] font-mono tracking-[0.25em] text-emas-light">0%</span>
  </section>

  <!-- ================= 2. COVER ================= -->
  <section id="cover-gate" class="fixed inset-0 z-40 mx-auto max-w-[430px] h-[100dvh] flex flex-col justify-between py-9 px-6 text-center overflow-hidden bg-jbiru-900">
    <div class="damask"></div>
    <svg class="corner tl" style="width:64px;height:64px;top:12px;left:12px"><use href="#jb-corner"/></svg>
    <svg class="corner tr" style="width:64px;height:64px;top:12px;right:12px"><use href="#jb-corner"/></svg>
    <svg class="corner bl" style="width:64px;height:64px;bottom:12px;left:12px"><use href="#jb-corner"/></svg>
    <svg class="corner br" style="width:64px;height:64px;bottom:12px;right:12px"><use href="#jb-corner"/></svg>

    <div id="gate-header" class="relative z-10 pt-6">
      <p class="font-script text-4xl text-gold-grad mb-2">Wedding Invitation</p>
      <p class="text-[11px] tracking-[0.35em] text-jbiru-100/80">{{ jklDate($rDate) }}</p>
    </div>

    <div id="gate-center" class="relative z-10 flex flex-col items-center">
      <img src="{{ asset('images/jawa-puspa/gunungan-divider.webp') }}" alt="Gunungan" class="orn w-40 mb-3" data-speed="0.5" />
      <h2 class="font-serif text-[2rem] leading-snug tracking-[0.12em] text-gading">{{ strtoupper($groomNick) }} <span class="text-gold-grad">&amp;</span> {{ strtoupper($brideNick) }}</h2>
      <svg class="w-52 h-5 mt-3" viewBox="0 0 300 24"><use href="#jb-divider"/></svg>
      <div class="card-gading mx-auto w-full max-w-[300px] px-6 py-5 mt-5">
        <svg class="corner tl" style="width:34px;height:34px"><use href="#jb-corner"/></svg>
        <svg class="corner br" style="width:34px;height:34px"><use href="#jb-corner"/></svg>
        <p class="text-[10px] tracking-[0.3em] uppercase text-[#7c6228] mb-2">Kepada Yth<br>Bapak/Ibu/Saudara/i</p>
        <p class="font-serif text-2xl font-semibold text-jbiru-900">{{ $toName }}</p>
      </div>
    </div>

    <div id="gate-footer" class="relative z-10 pb-3">
      <button id="btn-open" class="btn-gold font-bold text-[12px] tracking-[0.25em] px-10 py-3.5 rounded-full">
        BUKA UNDANGAN
      </button>
      <p class="text-[10px] text-jbiru-100/50 mt-4 tracking-widest">* Mohon maaf apabila ada kesalahan penulisan nama &amp; gelar</p>
    </div>
  </section>

  <!-- ================= 3. HERO ================= -->
  <section id="hero-section" class="relative h-[100dvh] min-h-[600px] w-full flex flex-col items-center justify-center text-center px-6 overflow-hidden">
    <div class="damask"></div>
    <svg class="corner tl" style="width:64px;height:64px;top:12px;left:12px"><use href="#jb-corner"/></svg>
    <svg class="corner tr" style="width:64px;height:64px;top:12px;right:12px"><use href="#jb-corner"/></svg>
    <svg class="corner bl" style="width:64px;height:64px;bottom:12px;left:12px"><use href="#jb-corner"/></svg>
    <svg class="corner br" style="width:64px;height:64px;bottom:12px;right:12px"><use href="#jb-corner"/></svg>

    <div class="hero-content relative z-10 flex flex-col items-center px-4 w-full">
      <p class="hero-stagger font-script text-4xl text-gold-grad opacity-0 mb-1">The Wedding Of</p>
      <img src="{{ asset('images/jawa-puspa/gunungan-divider.webp') }}" alt="Gunungan" class="hero-stagger orn w-36 opacity-0 my-2" data-speed="0.6" />
      <h2 class="hero-stagger font-serif text-[1.9rem] leading-snug tracking-[0.12em] text-gading opacity-0">{{ strtoupper($groomNick) }} <span class="text-gold-grad">&amp;</span> {{ strtoupper($brideNick) }}</h2>
      <svg class="hero-stagger w-56 h-5 opacity-0 mt-3" viewBox="0 0 300 24"><use href="#jb-divider"/></svg>
      <div class="hero-stagger mt-5 opacity-0 flex items-stretch justify-center gap-4 text-gading">
        <div class="flex flex-col justify-center"><span class="font-serif text-lg">{{ jklDayName($rDate) }}</span></div>
        <div class="flex flex-col justify-center border-x border-emas/60 px-4"><span class="font-serif text-4xl font-semibold text-gold-grad leading-none">{{ jklDayNum($rDate) }}</span><span class="text-[11px] tracking-[0.2em] text-jbiru-100/70 mt-1">{{ jklYear($rDate) }}</span></div>
        <div class="flex flex-col justify-center"><span class="font-serif text-lg">{{ jklMonthName($rDate) }}</span></div>
      </div>
    </div>
    <p class="hero-stagger absolute bottom-8 z-10 opacity-0 text-[9px] tracking-[0.4em] text-jbiru-100/50 uppercase">Geser ke bawah</p>
  </section>

  <!-- ================= 4. QUOTES ================= -->
  <section class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden">
    <div class="anim-zoom relative z-10 w-full max-w-[340px]">
      <div class="mx-auto w-20 h-20 rounded-full flex items-center justify-center mb-5"
        style="border:1.5px solid rgba(201,164,92,.75);background:rgba(201,164,92,.07)">
        <span class="font-serif text-2xl tracking-[0.15em] text-gold-grad font-semibold">{{ $monoBride }}{{ $monoGroom }}</span>
      </div>
      <img src="{{ asset('images/jawa-puspa/gunungan-divider.webp') }}" alt="" class="anim-zoom w-14 mx-auto mb-4" />
      <p class="anim-fade font-serif text-[17px] leading-[1.9] text-jbiru-100/90 italic">
        &ldquo;Dan segala sesuatu Kami ciptakan berpasang-pasangan agar kamu mengingat (kebesaran Allah).&rdquo;
      </p>
      <svg class="w-48 h-5 mx-auto my-4" viewBox="0 0 300 24"><use href="#jb-divider"/></svg>
      <p class="anim-fade text-[11px] font-semibold tracking-[0.25em] uppercase text-emas">QS. Az-Zariyat : 49</p>
      @if(jklGet('quotes'))
      <p class="anim-fade font-serif italic text-jbiru-100/70 text-[15px] leading-relaxed mt-6" id="sec-quotes">&ldquo;{{ jklGet('quotes') }}&rdquo;</p>
      @endif
    </div>
  </section>

  <!-- ================= 5. MEMPELAI ================= -->
  <section id="sec-profil" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden">
    <p class="anim-fade text-[10px] uppercase tracking-[0.4em] text-emas mb-2 relative z-10">Assalamu&rsquo;alaikum Warahmatullaahi Wabarakaatuh</p>
    <h2 class="anim-zoom font-script text-5xl text-gold-grad mb-2 relative z-10">Mempelai</h2>
    <svg class="anim-fade w-48 h-5 relative z-10 mb-3" viewBox="0 0 300 24"><use href="#jb-divider"/></svg>
    <p class="anim-fade font-serif italic text-jbiru-100/70 text-[15px] leading-relaxed mb-10 max-w-[300px] relative z-10">Dengan memohon rahmat dan ridha Allah SWT, kami bermaksud menyelenggarakan pernikahan kami.</p>

    <!-- Mempelai wanita -->
    <div class="anim-zoom relative z-10 w-full max-w-[320px] card-gading px-6 pt-8 pb-10 mb-8">
      <svg class="corner tl"><use href="#jb-corner"/></svg>
      <svg class="corner tr"><use href="#jb-corner"/></svg>
      <svg class="corner bl"><use href="#jb-corner"/></svg>
      <svg class="corner br"><use href="#jb-corner"/></svg>
      <div class="relative w-40 h-52 mx-auto mb-4" style="border-radius:999px 999px 14px 14px; overflow:hidden; border:2.5px solid #c9a45c; box-shadow:0 14px 30px rgba(10,27,48,.35)">
        @if(jklGet('bride_photo'))
        <img src="{{ jklGet('bride_photo') }}" class="w-full h-full object-cover" alt="{{ $brideFull }}" />
        @else
        <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(160deg,#e9eef5,#fdfbf7)">
          <svg class="w-14 h-[4.4rem] opacity-70"><use href="#jb-gunungan"/></svg>
        </div>
        @endif
      </div>
      <h3 class="font-serif text-[1.55rem] font-semibold tracking-[0.08em] text-jbiru-900 leading-tight">{{ strtoupper($brideFull) }}</h3>
      <p class="text-[11px] text-jbiru-900/55 mt-2">{{ jklGet('bride_child_of','Putri') }} dari</p>
      <p class="text-[13px] font-medium text-[#7c6228] mt-0.5 px-6">{{ jklGet('bride_parents','') }}</p>
      @if(jklGet('bride_instagram'))
      <a href="https://instagram.com/{{ ltrim(jklGet('bride_instagram'),'@') }}" target="_blank" rel="noopener" class="btn-outline-navy inline-block mt-4 text-[10px] tracking-[0.2em] px-5 py-2 rounded-full"><svg class="inline-block w-[13px] h-[13px] mr-1 -mt-[2px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="0.8" fill="currentColor" stroke="none"/></svg>&#64;{{ ltrim(jklGet('bride_instagram'),'@') }}</a>
      @endif
    </div>

    <div class="anim-zoom font-script text-5xl text-gold-grad my-1 relative z-10">&amp;</div>

    <!-- Mempelai pria -->
    <div class="anim-zoom relative z-10 w-full max-w-[320px] card-gading px-6 pt-8 pb-10 mt-8">
      <svg class="corner tl"><use href="#jb-corner"/></svg>
      <svg class="corner tr"><use href="#jb-corner"/></svg>
      <svg class="corner bl"><use href="#jb-corner"/></svg>
      <svg class="corner br"><use href="#jb-corner"/></svg>
      <div class="relative w-40 h-52 mx-auto mb-4" style="border-radius:999px 999px 14px 14px; overflow:hidden; border:2.5px solid #c9a45c; box-shadow:0 14px 30px rgba(10,27,48,.35)">
        @if(jklGet('groom_photo'))
        <img src="{{ jklGet('groom_photo') }}" class="w-full h-full object-cover" alt="{{ $groomFull }}" />
        @else
        <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(160deg,#e9eef5,#fdfbf7)">
          <svg class="w-14 h-[4.4rem] opacity-70"><use href="#jb-gunungan"/></svg>
        </div>
        @endif
      </div>
      <h3 class="font-serif text-[1.55rem] font-semibold tracking-[0.08em] text-jbiru-900 leading-tight">{{ strtoupper($groomFull) }}</h3>
      <p class="text-[11px] text-jbiru-900/55 mt-2">{{ jklGet('groom_child_of','Putra') }} dari</p>
      <p class="text-[13px] font-medium text-[#7c6228] mt-0.5 px-6">{{ jklGet('groom_parents','') }}</p>
      @if(jklGet('groom_instagram'))
      <a href="https://instagram.com/{{ ltrim(jklGet('groom_instagram'),'@') }}" target="_blank" rel="noopener" class="btn-outline-navy inline-block mt-4 text-[10px] tracking-[0.2em] px-5 py-2 rounded-full"><svg class="inline-block w-[13px] h-[13px] mr-1 -mt-[2px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="0.8" fill="currentColor" stroke="none"/></svg>&#64;{{ ltrim(jklGet('groom_instagram'),'@') }}</a>
      @endif
    </div>
  </section>

  <!-- ================= 6. COUNTDOWN ================= -->
  <section id="sec-acara" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden">
    <div class="damask"></div>
    <h2 class="anim-zoom font-script text-5xl text-gold-grad mb-8 relative z-10">Save The Date</h2>
    <div class="anim-zoom relative z-10 grid grid-cols-4 gap-2 w-full max-w-[340px] mb-8">
      <div class="py-4 rounded-xl" style="background:linear-gradient(160deg,#d9b36c,#b8934a);box-shadow:0 12px 24px -10px rgba(201,164,92,.6)"><div class="font-serif text-[1.6rem] font-bold text-jbiru-900" id="cd-d">00</div><div class="text-[8px] tracking-[0.22em] text-jbiru-900/70 mt-1 font-semibold">DAYS</div></div>
      <div class="py-4 rounded-xl" style="background:linear-gradient(160deg,#d9b36c,#b8934a);box-shadow:0 12px 24px -10px rgba(201,164,92,.6)"><div class="font-serif text-[1.6rem] font-bold text-jbiru-900" id="cd-h">00</div><div class="text-[8px] tracking-[0.22em] text-jbiru-900/70 mt-1 font-semibold">HOURS</div></div>
      <div class="py-4 rounded-xl" style="background:linear-gradient(160deg,#d9b36c,#b8934a);box-shadow:0 12px 24px -10px rgba(201,164,92,.6)"><div class="font-serif text-[1.6rem] font-bold text-jbiru-900" id="cd-m">00</div><div class="text-[8px] tracking-[0.22em] text-jbiru-900/70 mt-1 font-semibold">MINUTES</div></div>
      <div class="py-4 rounded-xl" style="background:linear-gradient(160deg,#d9b36c,#b8934a);box-shadow:0 12px 24px -10px rgba(201,164,92,.6)"><div class="font-serif text-[1.6rem] font-bold text-jbiru-900" id="cd-s">00</div><div class="text-[8px] tracking-[0.22em] text-jbiru-900/70 mt-1 font-semibold">SECONDS</div></div>
    </div>
    <a href="{{ $gcalUrl }}" target="_blank" rel="noopener" class="anim-fade btn-outline relative z-10 text-[10px] tracking-[0.25em] px-7 py-3 rounded-full mb-4">+ SIMPAN KE GOOGLE CALENDAR</a>

    <!-- Akad -->
    <div class="anim-zoom relative z-10 w-full max-w-[340px] card-gading px-6 py-8 mt-8">
      <svg class="corner tl"><use href="#jb-corner"/></svg>
      <svg class="corner tr"><use href="#jb-corner"/></svg>
      <svg class="corner bl"><use href="#jb-corner"/></svg>
      <svg class="corner br"><use href="#jb-corner"/></svg>
      <h3 class="font-serif text-[1.7rem] font-semibold tracking-[0.14em] text-jbiru-900">AKAD NIKAH</h3>
      <svg class="w-44 h-5 mx-auto my-2" viewBox="0 0 300 24"><use href="#jb-divider"/></svg>
      <p class="font-serif text-lg text-jbiru-900/80 mb-1">{{ jklDate($wDate) }}</p>
      <p class="font-serif text-lg text-[#7c6228] font-semibold mb-4">{{ $wTime }} &ndash; {{ $wTimeE }} {{ $tzLabel }}</p>
      <div class="hairline w-24 mx-auto my-4"></div>
      <p class="text-[11px] tracking-[0.25em] uppercase text-jbiru-900/50 mb-1">Lokasi Acara</p>
      <p class="font-semibold text-jbiru-900">{{ jklGet('wedding_location','Kediaman Mempelai') }}</p>
      @if(!$sameLoc && jklGet('wedding_map_link'))
      <a href="{{ jklGet('wedding_map_link') }}" target="_blank" rel="noopener" class="btn-gold inline-flex items-center gap-2 mt-5 text-[11px] font-bold tracking-[0.2em] px-8 py-3 rounded-full">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
        OPEN MAPS
      </a>
      @endif
    </div>

    <!-- Resepsi -->
    <div class="anim-zoom relative z-10 w-full max-w-[340px] card-gading px-6 py-8 mt-6">
      <svg class="corner tl"><use href="#jb-corner"/></svg>
      <svg class="corner tr"><use href="#jb-corner"/></svg>
      <svg class="corner bl"><use href="#jb-corner"/></svg>
      <svg class="corner br"><use href="#jb-corner"/></svg>
      <h3 class="font-script text-4xl text-[#7c6228]">Ceremonial</h3>
      <p class="font-serif text-[1.3rem] font-semibold tracking-[0.14em] text-jbiru-900 mt-1">RESEPSI</p>
      <svg class="w-44 h-5 mx-auto my-2" viewBox="0 0 300 24"><use href="#jb-divider"/></svg>
      <p class="font-serif text-lg text-jbiru-900/80 mb-1">{{ jklDate($rDate) }}</p>
      <p class="font-serif text-lg text-[#7c6228] font-semibold mb-4">{{ $rTimeS }} &ndash; {{ $rTimeE }} {{ $tzLabel }}</p>
      <div class="hairline w-24 mx-auto my-4"></div>
      <p class="text-[11px] tracking-[0.25em] uppercase text-jbiru-900/50 mb-1">Lokasi Acara</p>
      <p class="font-semibold text-jbiru-900">{{ jklGet('reception_location', jklGet('wedding_location','Kediaman Mempelai')) }}</p>
      <div class="mt-5 flex flex-wrap justify-center gap-2">
        @if($sameLoc)
          @if($mainMapLink)
          <a href="{{ $mainMapLink }}" target="_blank" rel="noopener" class="btn-gold inline-flex items-center gap-2 text-[11px] font-bold tracking-[0.2em] px-8 py-3 rounded-full">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
            OPEN MAPS
          </a>
          @endif
        @else
          @if(jklGet('reception_map_link'))
          <a href="{{ jklGet('reception_map_link') }}" target="_blank" rel="noopener" class="btn-gold inline-flex items-center gap-2 text-[11px] font-bold tracking-[0.2em] px-8 py-3 rounded-full">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
            OPEN MAPS
          </a>
          @endif
        @endif
      </div>
    </div>
  </section>

  <!-- ================= 7. GALERI ================= -->
  <section id="sec-galeri" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden">
    <h2 class="anim-zoom font-script text-5xl text-gold-grad mb-2 relative z-10">Our Moment</h2>
    <p class="anim-fade font-serif text-[1.15rem] tracking-[0.25em] text-jbiru-100/80 mb-2 relative z-10">GALERI FOTO</p>
    <svg class="anim-fade w-48 h-5 relative z-10 mb-10" viewBox="0 0 300 24"><use href="#jb-divider"/></svg>
    <div class="relative z-10 grid grid-cols-2 gap-2 w-full max-w-[340px]">
      @if(!empty($gallery) && count($gallery))
        @foreach(array_slice($gallery, 0, 8) as $i => $src)
        <div class="gal-item anim-fade relative overflow-hidden rounded-xl cursor-pointer {{ $i === 0 ? 'col-span-2 aspect-[16/10]' : 'aspect-[4/5]' }}" data-full="{{ $src }}" style="border:1px solid rgba(201,164,92,.45)">
          <img src="{{ $src }}" class="w-full h-full object-cover" alt="Galeri {{ $i+1 }}" loading="lazy" />
        </div>
        @endforeach
      @else
        @foreach(range(1,5) as $i)
        <div class="gal-item anim-fade relative overflow-hidden rounded-xl cursor-pointer {{ $i === 1 ? 'col-span-2 aspect-[16/10]' : 'aspect-[4/5]' }}" data-full="https://picsum.photos/seed/jbgal{{ $i }}/600/450" style="border:1px solid rgba(201,164,92,.45)">
          <img src="https://picsum.photos/seed/jbgal{{ $i }}/600/450" class="w-full h-full object-cover" alt="Galeri {{ $i }}" loading="lazy" />
        </div>
        @endforeach
      @endif
    </div>
  </section>

  <!-- ================= 8. LOVE STORY ================= -->
  @if(!empty($stories) && count($stories))
  <section class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden" id="sec-kisah">
    <h2 class="anim-zoom font-script text-5xl text-gold-grad mb-2 relative z-10">Love Story</h2>
    <p class="anim-fade font-serif text-[1.15rem] tracking-[0.25em] text-jbiru-100/80 mb-2 relative z-10">KISAH KAMI</p>
    <svg class="anim-fade w-48 h-5 relative z-10 mb-10" viewBox="0 0 300 24"><use href="#jb-divider"/></svg>
    <div class="relative z-10 w-full max-w-[340px] flex flex-col gap-5 text-left">
      @foreach($stories as $story)
      <div class="anim-zoom card-gading px-6 pt-6 pb-10">
        <svg class="corner tl" style="width:34px;height:34px"><use href="#jb-corner"/></svg>
        <svg class="corner br" style="width:34px;height:34px"><use href="#jb-corner"/></svg>
        <p class="font-serif text-xl font-semibold tracking-[0.06em] text-jbiru-900">{{ $story->judul }}</p>
        @if(!empty($story->tahun))<p class="text-[11px] font-bold tracking-[0.25em] text-[#7c6228] mt-0.5">{{ $story->tahun }}</p>@endif
        <div class="hairline w-16 my-3" style="margin-left:0"></div>
        <p class="text-[13.5px] leading-relaxed text-jbiru-900/70">{{ $story->isi }}</p>
      </div>
      @endforeach
    </div>
  </section>
  @endif

  <!-- ================= 9. GIFT ================= -->
  <section id="sec-gift" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden">
    <div class="damask"></div>
    <h2 class="anim-zoom font-script text-5xl text-gold-grad mb-2 relative z-10">Wedding Gift</h2>
    <p class="anim-fade font-serif text-[1.15rem] tracking-[0.25em] text-jbiru-100/80 mb-2 relative z-10">GIFTS</p>
    <svg class="anim-fade w-48 h-5 relative z-10 mb-6" viewBox="0 0 300 24"><use href="#jb-divider"/></svg>
    <p class="anim-fade font-serif italic text-jbiru-100/70 text-[15px] leading-relaxed mb-10 max-w-[320px] relative z-10">Doa restu Bapak/Ibu/Saudara/i merupakan karunia terindah bagi kami. Namun apabila berkenan memberikan tanda kasih, kami sediakan dengan penuh terima kasih.</p>

    <div class="relative z-10 w-full max-w-[340px] flex flex-col gap-5">
      @if($qrisEnabled)
      <div class="anim-zoom card-navy px-6 py-7" style="border-style:solid">
        <p class="font-serif text-xl tracking-[0.2em] text-gold-grad font-semibold mb-1">QRIS</p>
        <p class="text-[11px] text-jbiru-100/60 mb-4">Scan untuk memberi tanda kasih{{ $qrisName ? ' — '.$qrisName : '' }}</p>
        <img src="{{ $qrisImage }}" alt="QRIS" class="w-48 h-48 object-contain mx-auto rounded-xl bg-white p-2">
      </div>
      @endif
      @foreach($accounts as $acc)
      <div class="anim-zoom card-navy px-6 py-6" style="border-style:solid">
        <div class="flex items-center justify-center gap-2.5 mb-1">
          @if(!empty($acc['logo']))
          <img src="{{ $acc['logo'] }}" alt="Logo {{ $acc['bank'] }}" class="h-7 w-7 object-contain rounded-md bg-white/90 p-0.5" loading="lazy" onerror="this.style.display='none'">
          @endif
          <p class="font-serif text-[1.3rem] tracking-[0.2em] text-gold-grad font-semibold">{{ strtoupper($acc['bank']) }}</p>
        </div>
        <p class="text-[11px] text-jbiru-100/55 mb-3">a.n {{ !empty($acc['an']) ? $acc['an'] : $giftRecipient }}</p>
        <p class="font-mono text-xl tracking-[0.14em] text-gading mb-4">{{ $acc['no'] }}</p>
        <button onclick="copyText('{{ $acc['no'] }}')" class="btn-outline text-[10px] tracking-[0.25em] px-6 py-2.5 rounded-full">SALIN NOMOR</button>
      </div>
      @endforeach

      @if(jklGet('gift_address'))
      <div class="anim-zoom card-navy px-6 py-6" style="border-style:solid">
        <p class="font-serif text-[1.2rem] tracking-[0.15em] text-gold-grad font-semibold mb-2">KIRIM KADO FISIK</p>
        @if($giftRecipient)<p class="text-[12px] font-semibold text-gading mb-1">{{ $giftRecipient }}</p>@endif
        <p class="text-[12.5px] leading-relaxed text-jbiru-100/75">{{ jklGet('gift_address') }}</p>
        <button onclick="copyText('{{ $giftRecipient }} — {{ jklGet('gift_address') }}')" class="btn-outline text-[10px] tracking-[0.25em] px-6 py-2.5 rounded-full mt-4">SALIN ALAMAT</button>
      </div>
      @endif
    </div>
  </section>

  <!-- ================= 10. RSVP ================= -->
  <section id="sec-ucapan" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden">
    <h2 class="anim-zoom font-serif text-[1.7rem] font-semibold tracking-[0.3em] text-gading mb-2 relative z-10">RSVP</h2>
    <svg class="anim-fade w-48 h-5 relative z-10 mb-4" viewBox="0 0 300 24"><use href="#jb-divider"/></svg>
    <p class="anim-fade font-serif italic text-jbiru-100/70 text-[15px] leading-relaxed mb-8 max-w-[320px] relative z-10">Merupakan suatu kehormatan dan kebahagiaan bagi kami beserta keluarga apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>

    <form id="rsvp-form" class="anim-zoom relative z-10 w-full max-w-[340px] card-navy px-6 py-7 text-left mb-12" style="border-style:solid" onsubmit="submitRSVP(event)">
      <label class="block text-[10px] tracking-[0.25em] uppercase text-emas-light/80 mb-1.5">Nama Lengkap</label>
      <input type="text" id="rsvp-name" required class="field-biru mb-4" placeholder="Nama Anda" value="{{ $toName !== 'Tamu Undangan' ? $toName : '' }}">
      <label class="block text-[10px] tracking-[0.25em] uppercase text-emas-light/80 mb-1.5">Konfirmasi Kehadiran</label>
      <div class="grid grid-cols-2 gap-2 mb-4" id="rsvp-status-group">
        <button type="button" data-status="hadir" class="rsvp-status-btn py-3 rounded-lg text-[12px] font-semibold tracking-wider transition-all" style="border:1px solid rgba(201,164,92,.5);color:#e6c887">Hadir</button>
        <button type="button" data-status="tidak" class="rsvp-status-btn py-3 rounded-lg text-[12px] font-semibold tracking-wider transition-all" style="border:1px solid rgba(201,164,92,.5);color:#e6c887">Berhalangan</button>
      </div>
      <div id="rsvp-count-wrap" class="hidden">
        <label class="block text-[10px] tracking-[0.25em] uppercase text-emas-light/80 mb-1.5">Jumlah Tamu</label>
        <select id="rsvp-count" class="field-biru mb-4">
          <option value="1">1 Orang</option>
          <option value="2">2 Orang</option>
          <option value="3">3 Orang</option>
          <option value="4">4 Orang</option>
          <option value="5">5 Orang</option>
        </select>
      </div>
      <button type="submit" id="rsvp-submit" class="btn-gold w-full py-3.5 rounded-full text-[12px] font-bold tracking-[0.25em]">KONFIRMASI KEHADIRAN</button>
      <p id="rsvp-msg" class="hidden text-center text-[13px] text-emas-light mt-4">Terima kasih, konfirmasi Anda telah kami terima.</p>
    </form>

    <!-- Ucapan -->
    <h2 class="anim-zoom font-serif text-[1.7rem] font-semibold tracking-[0.3em] text-gading mb-2 relative z-10">UCAPAN</h2>
    <svg class="anim-fade w-48 h-5 relative z-10 mb-4" viewBox="0 0 300 24"><use href="#jb-divider"/></svg>
    <p class="anim-fade font-serif italic text-jbiru-100/70 text-[15px] leading-relaxed mb-8 max-w-[320px] relative z-10">Berikan ucapan &amp; doa terbaik untuk kedua mempelai</p>

    <form id="wish-form" class="anim-zoom relative z-10 w-full max-w-[340px] card-navy px-6 py-7 text-left" style="border-style:solid" onsubmit="submitWish(event)">
      <label class="block text-[10px] tracking-[0.25em] uppercase text-emas-light/80 mb-1.5">Nama</label>
      <input type="text" id="wish-name" required maxlength="60" class="field-biru mb-4" placeholder="Nama Anda" value="{{ $toName !== 'Tamu Undangan' ? $toName : '' }}">
      <label class="block text-[10px] tracking-[0.25em] uppercase text-emas-light/80 mb-1.5">Ucapan &amp; Doa</label>
      <textarea id="wish-text" rows="3" required maxlength="500" class="field-biru mb-1.5 resize-none" placeholder="Tulis ucapan terbaik Anda..."></textarea>
      <p class="text-right text-[10px] text-jbiru-100/40 mb-4"><span id="wish-count">0</span>/500</p>
      <button type="submit" id="wish-submit" class="btn-gold w-full py-3.5 rounded-full text-[12px] font-bold tracking-[0.25em]">KIRIM UCAPAN</button>
    </form>

    <div id="wish-list" class="relative z-10 w-full max-w-[340px] mt-8 flex flex-col gap-3 text-left"></div>
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
<section class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden" id="sec-live">
    <div class="damask"></div>
    <p class="anim-fade text-[10px] uppercase tracking-[0.4em] text-emas mb-2 relative z-10">Live Streaming</p>
    <h2 class="anim-zoom font-script text-5xl text-gold-grad mb-3 relative z-10">Saksikan Langsung</h2>
    <p class="anim-fade font-serif italic text-jbiru-100/70 text-[15px] leading-relaxed mb-9 max-w-[320px] relative z-10">Bagi Bapak/Ibu/Saudara/i yang berhalangan hadir, silakan saksikan acara kami secara langsung melalui tayangan berikut.</p>
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
  <footer class="scroll-section relative py-20 px-6 pb-32 flex flex-col items-center text-center overflow-hidden">
    <div class="damask"></div>
    <img src="{{ asset('images/jawa-puspa/gunungan-divider.webp') }}" alt="" class="anim-zoom w-16 relative z-10 mb-5" />
    <p class="anim-fade font-serif italic text-jbiru-100/75 text-[15px] leading-[1.9] max-w-[320px] relative z-10 mb-8">Merupakan suatu kehormatan dan kebahagiaan bagi kami beserta keluarga besar apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu kepada kedua mempelai.</p>
    <h3 class="anim-zoom font-script text-5xl text-gold-grad relative z-10 mb-3">{{ $groomNick }} &amp; {{ $brideNick }}</h3>
    <p class="anim-fade text-[10px] tracking-[0.3em] uppercase text-jbiru-100/50 relative z-10">Beserta Keluarga Besar</p>
    <div class="hairline w-40 mt-8 relative z-10"></div>
    <img src="{{ asset('images/jawa-puspa/ornamen-bawah.webp') }}" alt="" class="absolute bottom-0 left-0 w-full opacity-80 pointer-events-none" />
  </footer>

  <!-- Floating: musik + menu -->
  <button id="musicFloat" class="hidden" aria-label="Musik" onclick="toggleMusic()">
    <svg class="note w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18V6l10-2v11.5M9 18a3 3 0 1 1-2-2.83A3 3 0 0 1 9 18zm10-2.5a3 3 0 1 1-2-2.83A3 3 0 0 1 19 15.5z"/></svg>
    <span class="bars"><span></span><span></span><span></span></span>
  </button>
  <nav id="floatMenu" class="hidden" aria-label="Menu undangan">
  <a href="#sec-profil" data-sec="sec-profil"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7.5-4.7-9.5-9C1 8.5 3 5 6.5 5c2 0 3.5 1 4.5 2.5C12 6 13.5 5 15.5 5 19 5 21 8.5 20.5 12c-2 4.3-8.5 9-8.5 9z"/></svg><span>Mempelai</span></a>
  @if(!empty($gallery))<a href="#sec-galeri" data-sec="sec-galeri"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.6"/><path stroke-linecap="round" stroke-linejoin="round" d="m5 19 5.5-5.5 3 3L19 11l2 2"/></svg><span>Galeri</span></a>@endif
  @if(!empty($stories))<a href="#sec-kisah" data-sec="sec-kisah"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.5C10 4.8 7 4.3 4.5 4.5v13c2.5-.2 5.5.3 7.5 2 2-1.7 5-2.2 7.5-2v-13C17 4.3 14 4.8 12 6.5z"/><path stroke-linecap="round" d="M12 6.5v13"/></svg><span>Kisah</span></a>@endif
  @if(jklGet('quotes'))<a href="#sec-quotes" data-sec="sec-quotes"><svg class="w-[21px] h-[21px]" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 5.5C4.9 6.7 4 8.6 4 10.9V18h6.4v-6.4H7.2c0-1.9.8-3.2 2.3-4.2l-3.1-1.8zm9 0c-1.7 1.2-2.6 3.1-2.6 5.4V18h6.4v-6.4h-3.2c0-1.9.8-3.2 2.3-4.2l-3.1-1.8z"/></svg><span>Quotes</span></a>@endif
  <a href="#sec-acara" data-sec="sec-acara"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path stroke-linecap="round" d="M8 3v4M16 3v4M3 10h18"/></svg><span>Acara</span></a>
  <a href="#sec-ucapan" data-sec="sec-ucapan"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg><span>RSVP</span></a>
  @if(jklGet('live_stream_link'))<a href="#sec-live" data-sec="sec-live"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path fill="currentColor" stroke="none" d="M10 8.3v7.4c0 .6.7 1 1.2.7l6-3.7c.5-.3.5-1 0-1.3l-6-3.7c-.5-.3-1.2.1-1.2.6z"/></svg><span>Live</span></a>@endif
  <a href="#sec-gift" data-sec="sec-gift"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8v13H3V8M1 3h22v5H1zM12 8v13M12 8H7.5a2.5 2.5 0 0 1 0-5C11 3 12 8 12 8zm0 0h4.5a2.5 2.5 0 0 0 0-5C13 3 12 8 12 8z"/></svg><span>Gift</span></a>
  <a href="#sec-ucapan" data-sec="sec-ucapan"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg><span>Ucapan</span></a>
</nav>

  <!-- Lightbox -->
  <div id="lightbox" class="fixed inset-0 z-[80] hidden items-center justify-center p-4" style="background:rgba(4,10,20,.96)">
    <button id="lbClose" class="absolute top-4 right-4 z-10 text-white/80 hover:text-white text-4xl leading-none px-2" aria-label="Tutup">&times;</button>
    <button id="lbPrev" class="absolute left-2 z-10 w-11 h-11 rounded-full text-emas-light text-3xl flex items-center justify-center" style="background:rgba(201,164,92,.15);border:1px solid rgba(201,164,92,.5)" aria-label="Sebelumnya">&#8249;</button>
    <img id="lbImg" class="max-w-full max-h-[82vh] rounded-xl object-contain select-none" style="border:1px solid rgba(201,164,92,.5)" alt="Galeri" draggable="false" />
    <button id="lbNext" class="absolute right-2 z-10 w-11 h-11 rounded-full text-emas-light text-3xl flex items-center justify-center" style="background:rgba(201,164,92,.15);border:1px solid rgba(201,164,92,.5)" aria-label="Berikutnya">&#8250;</button>
    <div id="lbCount" class="absolute bottom-5 left-1/2 -translate-x-1/2 text-white/70 text-sm tracking-[0.25em]"></div>
  </div>

  <div id="toast" role="status"></div>
  <audio id="bgMusic" loop preload="auto"></audio>
</main>

<script>
"use strict";
const INV_ID = {{ $invId }};
const MUSIC_URL = @json($musicUrl);
const MUS_VOL = {{ $musVol }} / 100;
const MUS_AUTO = @json($musAuto);
const COUNTDOWN_TARGET = '{{ $countdownISO }}';

document.addEventListener('DOMContentLoaded', () => {
  // --- Preloader ---
  const loaderBar = document.getElementById('loader-bar');
  const loaderPercent = document.getElementById('loader-percent');
  let progress = 0;
  const interval = setInterval(() => {
    progress = Math.min(100, progress + Math.random() * 22 + 6);
    if (loaderBar) loaderBar.style.width = progress + '%';
    if (loaderPercent) loaderPercent.innerText = Math.floor(progress) + '%';
    if (progress >= 100) {
      clearInterval(interval);
      setTimeout(() => {
        const pl = document.getElementById('preloader');
        if (pl) { pl.style.opacity = '0'; pl.style.transition = 'opacity .6s'; setTimeout(() => pl.remove(), 650); }
        animateGate();
      }, 350);
    }
  }, 180);

  // --- Gate animation ---
  gsap.registerPlugin(ScrollTrigger);
  function animateGate() {
    gsap.fromTo('#gate-header', { y: -26, opacity: 0 }, { y: 0, opacity: 1, duration: 1, ease: 'power3.out' });
    gsap.fromTo('#gate-center', { scale: 0.9, opacity: 0 }, { scale: 1, opacity: 1, duration: 1.1, ease: 'power3.out', delay: 0.15 });
    gsap.fromTo('#gate-footer', { y: 26, opacity: 0 }, { y: 0, opacity: 1, duration: 1, ease: 'power3.out', delay: 0.3 });
  }

  // --- Musik ---
  const audioEl = document.getElementById('bgMusic');
  const musicFloat = document.getElementById('musicFloat');
  let audioStarted = false;
  async function initAudio() {
    try {
      const res = await fetch('/api/music?inv_id=' + (typeof INV_ID !== 'undefined' ? INV_ID : ''));
      const data = await res.json();
      const fp = data && data.music ? data.music.file_path : (data ? data.file_path : null);
      if (fp) {
        audioEl.src = (fp.startsWith('http') || fp.startsWith('/')) ? fp : '/storage/' + fp.replace(/^\//, '');
        audioEl.load();
      }
    } catch (e) { /* abaikan */ }
    if (!audioEl.src && MUSIC_URL) { audioEl.src = MUSIC_URL; audioEl.load(); }
    if (audioEl.src && musicFloat) musicFloat.classList.remove('hidden');
  }
  initAudio();

  function playMusic() {
    if (!audioEl || !audioEl.src) return;
    audioEl.volume = MUS_VOL;
    audioEl.play().then(() => { audioStarted = true; if (musicFloat) musicFloat.classList.add('playing'); }).catch(() => {});
  }
  window.toggleMusic = function () {
    if (!audioEl) return;
    if (audioEl.paused) { playMusic(); }
    else { audioEl.pause(); if (musicFloat) musicFloat.classList.remove('playing'); }
  };

  // --- Buka undangan ---
  const btnOpen = document.getElementById('btn-open');
  if (btnOpen) {
    btnOpen.addEventListener('click', () => {
      const gate = document.getElementById('cover-gate');
      gsap.to(gate, {
        yPercent: -100, duration: 1.15, ease: 'power4.inOut',
        onStart: () => { if (MUS_AUTO) playMusic(); },
        onComplete: () => {
          gate.style.display = 'none';
          document.body.classList.remove('is-locked');
          const fm = document.getElementById('floatMenu');
          if (fm) fm.classList.remove('hidden');
          animateHero();
          ScrollTrigger.refresh();
        }
      });
    });
  }

  function animateHero() {
    gsap.timeline({ defaults: { ease: 'power3.out' } })
      .to('.hero-stagger', { opacity: 1, y: 0, duration: 1, stagger: 0.12, startAt: { y: 26 } });
    // parallax gunungan hero
    document.querySelectorAll('#hero-section .orn').forEach(orn => {
      const speed = parseFloat(orn.dataset.speed || 0.5);
      gsap.to(orn, {
        y: () => 90 * speed, ease: 'none',
        scrollTrigger: { trigger: '#hero-section', start: 'top top', end: 'bottom top', scrub: 1.2 }
      });
    });
  }

  // --- Animasi scroll: SEMUA teks & kartu muncul smooth, per elemen ---
  function jbReveal(el, fromVars, toVars, dur) {
    gsap.fromTo(el, fromVars, Object.assign({}, toVars, {
      duration: dur, ease: 'power3.out',
      scrollTrigger: { trigger: el, start: 'top 92%', once: true }
    }));
  }
  document.querySelectorAll('.anim-zoom').forEach(el =>
    jbReveal(el, { scale: 0.88, opacity: 0 }, { scale: 1, opacity: 1 }, 1.05));
  document.querySelectorAll('.anim-fade').forEach(el =>
    jbReveal(el, { opacity: 0, y: 24 }, { opacity: 1, y: 0 }, 0.95));
  // Teks lain yang belum punya kelas animasi ikut fade-in halus
  document.querySelectorAll('.scroll-section h2, .scroll-section h3, .scroll-section h4, .scroll-section p, .scroll-section label, .scroll-section button, footer h2, footer h3, footer p').forEach(el => {
    if (el.classList.contains('anim-zoom') || el.classList.contains('anim-fade') || el.classList.contains('hidden')) return;
    if (el.closest('.anim-zoom') || el.closest('.anim-fade')) return; // ikut animasi kartu induknya
    jbReveal(el, { opacity: 0, y: 18 }, { opacity: 1, y: 0 }, 0.9);
  });

  // --- Kelopak bunga berjatuhan ---
  (function () {
    const cv = document.getElementById('petalCanvas');
    if (!cv || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    const ctx = cv.getContext('2d');
    let W = 0, H = 0, petals = [];
    const COLORS = ['rgba(253,251,247,', 'rgba(230,200,135,', 'rgba(201,164,92,'];
    function size() { const r = cv.getBoundingClientRect(); W = cv.width = Math.round(r.width); H = cv.height = Math.round(r.height); }
    function mk(init) {
      return { x: Math.random() * W, y: init ? Math.random() * H : -24, s: 5 + Math.random() * 7,
        vy: 0.35 + Math.random() * 0.7, ph: Math.random() * Math.PI * 2, sw: 0.4 + Math.random() * 0.9,
        rot: Math.random() * Math.PI * 2, vr: (Math.random() - 0.5) * 0.012,
        c: COLORS[Math.floor(Math.random() * COLORS.length)], a: 0.35 + Math.random() * 0.4 };
    }
    function draw(p) {
      ctx.save(); ctx.translate(p.x, p.y); ctx.rotate(p.rot);
      ctx.fillStyle = p.c + p.a + ')';
      ctx.beginPath();
      ctx.moveTo(0, -p.s);
      ctx.bezierCurveTo(p.s * 0.8, -p.s * 0.3, p.s * 0.6, p.s * 0.7, 0, p.s);
      ctx.bezierCurveTo(-p.s * 0.6, p.s * 0.7, -p.s * 0.8, -p.s * 0.3, 0, -p.s);
      ctx.fill(); ctx.restore();
    }
    size(); addEventListener('resize', size);
    for (let i = 0; i < 16; i++) petals.push(mk(true));
    (function loop() {
      ctx.clearRect(0, 0, W, H);
      petals.forEach((p, i) => {
        p.y += p.vy; p.ph += 0.016 * p.sw; p.x += Math.sin(p.ph) * p.sw * 0.5; p.rot += p.vr;
        if (p.y > H + 24) petals[i] = mk(false);
        draw(p);
      });
      requestAnimationFrame(loop);
    })();
  })();

  // --- Countdown ---
  const cdIds = { d: document.getElementById('cd-d'), h: document.getElementById('cd-h'), m: document.getElementById('cd-m'), s: document.getElementById('cd-s') };
  const pad = n => String(Math.max(0, n)).padStart(2, '0');
  function targetDate() {
    const parts = COUNTDOWN_TARGET.split(/[-T:]/).map(Number);
    return new Date(parts[0], (parts[1] || 1) - 1, parts[2] || 1, parts[3] || 0, parts[4] || 0, parts[5] || 0);
  }
  function tick() {
    const diff = Math.max(0, targetDate() - new Date());
    const d = Math.floor(diff / 86400000), h = Math.floor(diff / 3600000) % 24, m = Math.floor(diff / 60000) % 60, s = Math.floor(diff / 1000) % 60;
    if (cdIds.d) cdIds.d.textContent = pad(d);
    if (cdIds.h) cdIds.h.textContent = pad(h);
    if (cdIds.m) cdIds.m.textContent = pad(m);
    if (cdIds.s) cdIds.s.textContent = pad(s);
  }
  tick(); setInterval(tick, 1000);

  // --- RSVP ---
  let rsvpStatus = 'hadir';
  const statusBtns = document.querySelectorAll('.rsvp-status-btn');
  const countWrap = document.getElementById('rsvp-count-wrap');
  function paintStatus() {
    statusBtns.forEach(b => {
      const on = b.dataset.status === rsvpStatus;
      b.style.background = on ? 'linear-gradient(105deg,#b8934a,#e6c887 55%,#c9a45c)' : 'transparent';
      b.style.color = on ? '#0a1b30' : '#e6c887';
    });
    if (countWrap) countWrap.classList.toggle('hidden', rsvpStatus !== 'hadir');
  }
  statusBtns.forEach(b => b.addEventListener('click', () => { rsvpStatus = b.dataset.status; paintStatus(); }));
  paintStatus();

  window.submitRSVP = async function (e) {
    e.preventDefault();
    const name = document.getElementById('rsvp-name').value.trim();
    if (!name) return showToast('Mohon isi nama Anda');
    const btn = document.getElementById('rsvp-submit');
    btn.disabled = true; btn.textContent = 'MENGIRIM...';
    const payload = { invitation_id: INV_ID, nama: name, status: rsvpStatus, konfirmasi: rsvpStatus, kehadiran: rsvpStatus, hadir: rsvpStatus === 'hadir', jumlah_tamu: rsvpStatus === 'hadir' ? parseInt(document.getElementById('rsvp-count').value || '1') : 0 };
    try {
      const res = await fetch('/api/rsvp', { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify(payload) });
      if (!res.ok) throw new Error('rsvp failed');
      document.getElementById('rsvp-msg').classList.remove('hidden');
      btn.textContent = 'TERKIRIM ✓';
      showToast('Konfirmasi kehadiran terkirim');
    } catch (err) {
      btn.disabled = false; btn.textContent = 'KONFIRMASI KEHADIRAN';
      showToast('Gagal mengirim. Coba lagi ya.');
    }
  };

  // --- Ucapan ---
  const wishInput = document.getElementById('wish-text');
  const wishCount = document.getElementById('wish-count');
  wishInput?.addEventListener('input', () => { wishCount.textContent = wishInput.value.length; });

  window.submitWish = async function (e) {
    e.preventDefault();
    const name = document.getElementById('wish-name').value.trim();
    const text = wishInput.value.trim();
    if (!name || !text) return showToast('Mohon isi nama dan ucapan');
    const btn = document.getElementById('wish-submit');
    btn.disabled = true; btn.textContent = 'MENGIRIM...';
    try {
      const res = await fetch('/api/ucapan', { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ invitation_id: INV_ID, nama: name, pesan: text, ucapan: text }) });
      if (!res.ok) throw new Error('wish failed');
      wishInput.value = ''; wishCount.textContent = '0';
      btn.disabled = false; btn.textContent = 'KIRIM UCAPAN';
      showToast('Ucapan terkirim. Terima kasih!');
      loadWishes();
    } catch (err) {
      btn.disabled = false; btn.textContent = 'KIRIM UCAPAN';
      showToast('Gagal mengirim ucapan. Coba lagi ya.');
    }
  };

  function relTime(iso) {
    if (!iso) return '';
    const t = new Date(String(iso).replace(' ', 'T'));
    if (isNaN(t)) return '';
    const s = Math.max(0, Math.floor((Date.now() - t.getTime()) / 1000));
    if (s < 60) return 'Baru saja';
    const m = Math.floor(s / 60); if (m < 60) return m + ' menit yang lalu';
    const h = Math.floor(m / 60); if (h < 24) return h + ' jam yang lalu';
    const d = Math.floor(h / 24); if (d < 30) return d + ' hari yang lalu';
    return t.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
  }
  function wishCard(w) {
    const name = (w.nama || w.name || 'Tamu').toString();
    const msg = (w.pesan || w.ucapan || w.message || '').toString();
    const when = relTime(w.created_at || w.tanggal || w.time || '');
    const el = document.createElement('div');
    el.className = 'card-navy px-5 py-4';
    el.innerHTML = '<div class="flex items-baseline justify-between gap-3 mb-1"><p class="font-serif font-semibold text-[15px] tracking-wide text-gading"></p>' +
      (when ? '<span class="text-[10px] text-jbiru-100/45 whitespace-nowrap"></span>' : '') + '</div>' +
      '<p class="text-[13px] leading-relaxed text-jbiru-100/80"></p>';
    el.querySelector('p').textContent = name;
    const spans = el.querySelectorAll('span'); if (spans.length && when) spans[0].textContent = when;
    el.querySelectorAll('p')[1].textContent = msg;
    return el;
  }
  async function loadWishes() {
    const list = document.getElementById('wish-list');
    if (!list) return;
    list.innerHTML = '';
    try {
      const res = await fetch('/api/ucapan/' + INV_ID);
      const data = await res.json();
      const itemsW = Array.isArray(data) ? data : (data.data || data.ucapan || []);
      if (!itemsW.length) { list.innerHTML = '<p class="text-center text-[13px] text-jbiru-100/45 py-2">Jadilah yang pertama memberikan ucapan</p>'; return; }
      itemsW.forEach(w => list.appendChild(wishCard(w)));
    } catch (e) {
      list.innerHTML = '<p class="text-center text-[13px] text-jbiru-100/45 py-2">Ucapan belum dapat dimuat</p>';
    }
  }
  loadWishes();
});

// --- Salin teks + toast ---
let toastTimer = null;
function showToast(msg) { window.alert(msg); return;
  const t = document.getElementById('toast');
  if (!t) return;
  t.textContent = msg;
  t.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => t.classList.remove('show'), 2600);
}
function copyText(text) {
  if (navigator.clipboard && window.isSecureContext) {
    navigator.clipboard.writeText(text).then(() => showToast('Berhasil disalin')).catch(() => fallbackCopy(text));
  } else { fallbackCopy(text); }
}
function fallbackCopy(text) {
  const ta = document.createElement('textarea');
  ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
  document.body.appendChild(ta); ta.select();
  try { document.execCommand('copy'); showToast('Berhasil disalin'); }
  catch (e) { showToast('Gagal menyalin'); }
  document.body.removeChild(ta);
}

/* ---------- LIGHTBOX ---------- */
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
      var mus = document.getElementById('musicFloat');
      var musShown = mus && !mus.hidden && !mus.classList.contains('hidden');
      if(gateGone || musShown){ bar.classList.remove('hidden'); clearInterval(watch); }
      if(tries > 60) clearInterval(watch);
    }, 400);
  }
})();
</script>
</body>
</html>
