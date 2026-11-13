<?php
/* ============================================================
   TEMA: JAWA PENDOPO — taman Jawa dengan video opening (minimotion)
   Dipilih via settings: theme = jawa-pendopo
   Video opening: public/videos/jawa-pendopo-opening.mp4 (20 dtk, 606x1080)
   Background taman: public/images/jawa-pendopo/background.jpg (899x1599)
   Divider gunungan memakai aset public/images/jawa-puspa/gunungan-divider.webp.
   TANPA amplop — gerbangnya video opening (fullscreen, autoplay muted loop).
   ============================================================ */
$GLOBALS['jpn_settings'] = $settings ?? [];
if (!function_exists('jpnGet')) {
  function jpnGet($key, $default = '') {
    $s = $GLOBALS['jpn_settings'];
    if (!isset($s[$key]) || trim((string)$s[$key]) === '') return $default;
    return $s[$key];
  }
}
if (!function_exists('jpnDate')) {
  function jpnDate($dateStr) {
    if (!$dateStr) return '';
    $days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($dateStr);
    if (!$ts) return $dateStr;
    return $days[date('w',$ts)].', '.date('j',$ts).' '.$months[date('n',$ts)].' '.date('Y',$ts);
  }
}
if (!function_exists('jpnDayNum')) {
  function jpnDayNum($dateStr) { $ts = strtotime((string)$dateStr); return $ts ? date('j', $ts) : ''; }
}
if (!function_exists('jpnDayName')) {
  function jpnDayName($dateStr) {
    $days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $ts = strtotime((string)$dateStr); return $ts ? $days[date('w', $ts)] : '';
  }
}
if (!function_exists('jpnMonthName')) {
  function jpnMonthName($dateStr) {
    $months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime((string)$dateStr); return $ts ? $months[date('n', $ts)] : '';
  }
}
if (!function_exists('jpnYear')) {
  function jpnYear($dateStr) { $ts = strtotime((string)$dateStr); return $ts ? date('Y', $ts) : ''; }
}
if (!function_exists('jpnTime')) {
  function jpnTime($t) {
    $t = trim((string)$t); if ($t === '') return '08:00';
    $t = str_replace('.', ':', $t);
    return preg_match('/^\d{1,2}(:\d{2})?$/', $t) ? (strpos($t, ':') === false ? $t.':00' : $t) : '08:00';
  }
}
$groomNick = jpnGet('groom_nickname') ?: jpnGet('groom_name', 'Mempelai Pria');
$brideNick = jpnGet('bride_nickname') ?: jpnGet('bride_name', 'Mempelai Wanita');
$groomFull = jpnGet('groom_name', 'Mempelai Pria');
$brideFull = jpnGet('bride_name', 'Mempelai Wanita');
$toName = (!empty($guestName) && $guestName !== 'Bapak/Ibu/Saudara/i') ? $guestName : request('to', 'Tamu Undangan');
$wDate  = jpnGet('wedding_date', date('Y-m-d'));
$wTime  = jpnTime(jpnGet('wedding_time_start', '08:00'));
$wTimeE = jpnTime(jpnGet('wedding_time_end', '10:00'));
$rDate  = jpnGet('reception_date', $wDate);
$rTimeS = jpnTime(jpnGet('reception_time_start', '11:00'));
$rTimeE = jpnTime(jpnGet('reception_time_end', '13:00'));
$tzLabel = jpnGet('wedding_timezone', 'WIB');
$sameLoc = (jpnGet('event_location_mode', 'same') === 'same');
$mainMapLink = trim((string)jpnGet('wedding_map_link')) ?: trim((string)jpnGet('reception_map_link'));
$giftRecipient = jpnGet('gift_recipient_name', jpnGet('gift_owner', ''));
$countdownISO = $rDate.'T'.$rTimeS.':00';
$accounts = [];
$__gbanks = json_decode(jpnGet('gift_banks', ''), true) ?: [];
foreach ($__gbanks as $__b) {
  if (empty($__b['account'])) continue;
  $accounts[] = ['bank'=>($__b['name'] ?? 'Bank'),'no'=>$__b['account'],'an'=>($__b['owner'] ?? ''),'logo'=>($__b['logo'] ?? '')];
}
if (!$accounts) {
  if (jpnGet('gift_account')) $accounts[] = ['bank'=>jpnGet('gift_bank','Bank'),'no'=>jpnGet('gift_account'),'an'=>jpnGet('gift_owner',''),'logo'=>jpnGet('gift_bank_logo','')];
  if (jpnGet('gift_account2')) $accounts[] = ['bank'=>jpnGet('gift_bank2','Bank'),'no'=>jpnGet('gift_account2'),'an'=>jpnGet('gift_owner2',''),'logo'=>jpnGet('gift_bank_logo','')];
}
$qrisEnabled = jpnGet('gift_qris_enabled', '') === '1' && trim((string)jpnGet('gift_qris_image', '')) !== '';
$qrisImage = jpnGet('gift_qris_image', '');
$qrisName = jpnGet('gift_qris_name', '');
$musicUrl = null;
if (!empty($musicRecord) && !empty($musicRecord->file_path)) {
  $mp = $musicRecord->file_path;
  $musicUrl = (str_starts_with($mp,'http') || str_starts_with($mp,'/')) ? $mp : asset(ltrim($mp,'/'));
}
$musVol = (int) jpnGet('music_volume', 50);
$musAuto = jpnGet('music_autoplay', '1') !== '0';
$invId = $inv_id ?? 0;
$gcalDates = str_replace('-','',$rDate).'T'.str_replace(':','',$rTimeS).'00/'.str_replace('-','',$rDate).'T'.str_replace(':','',$rTimeE).'00';
$gcalUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text='.urlencode('Pernikahan '.$brideNick.' & '.$groomNick).'&dates='.$gcalDates.'&details='.urlencode(jpnGet('reception_location', jpnGet('wedding_location','')));
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
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:description" content="Tanpa mengurangi rasa hormat, kami mengundang Anda untuk menghadiri pernikahan {{ $brideFull }} & {{ $groomFull }}. Klik untuk melihat detail undangan.">
<meta property="og:image" content="{{ asset(jpnGet('og_preview_photo') ? jpnGet('og_preview_photo') : (jpnGet('bride_photo') ? jpnGet('bride_photo') : (jpnGet('groom_photo') ? jpnGet('groom_photo') : 'gunungan_utuh.png'))) }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        pendopo: { ink:'#2a2519', soft:'#57503e', gold:'#978045', deep:'#77603a', light:'#c2ab72', cream:'#fffdf7', paper:'#faf6ec' }
      },
      fontFamily: {
        script: ['"Pinyon Script"','cursive'],
        serif:  ['"Playfair Display"','serif'],
        sans:   ['"Poppins"','sans-serif']
      }
    }
  }
}
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pinyon+Script&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<style>
  html{scroll-behavior:smooth}
  body{background:#e9e2d0;font-family:'Poppins',sans-serif}
  .bg-garden{position:fixed;top:0;left:50%;transform:translateX(-50%);width:min(430px,100vw);height:100dvh;z-index:0;pointer-events:none;background-image:url('{{ asset('images/jawa-pendopo/background.jpg') }}');background-size:cover;background-position:center top;background-repeat:no-repeat}
  .bg-veil{position:fixed;top:0;left:50%;transform:translateX(-50%);width:min(430px,100vw);height:100dvh;z-index:0;pointer-events:none;background:rgba(255,253,247,.62)}
  main .scroll-section,main footer{position:relative;z-index:1}
  #hero-section{background:transparent}
  #hero-section .hero-veil{position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,253,247,.35) 0%,rgba(255,253,247,.10) 45%,rgba(255,253,247,.55) 100%)}
  #coverNames{position:absolute;left:0;right:0;top:40%;transform:translateY(-50%);z-index:5;text-align:center;padding:0 24px;opacity:0;pointer-events:none;transition:opacity 1.2s ease}
  #coverNames.show{opacity:1}
  #coverNames .cn-label{font-size:10px;letter-spacing:.4em;color:#e6c887;font-weight:700;text-shadow:0 2px 10px rgba(0,0,0,.6)}
  #coverNames .cn-names{font-family:'Pinyon Script',cursive;font-size:3rem;line-height:1.15;color:#fdf6dd;margin-top:6px;text-shadow:0 3px 16px rgba(0,0,0,.65)}
  #coverNames .cn-names span{color:#e6c887}
  #coverNames .cn-date{font-family:'Playfair Display',serif;font-size:13px;letter-spacing:.18em;color:#f4e8c8;margin-top:8px;text-shadow:0 2px 10px rgba(0,0,0,.6)}
  .invitation-frame{max-width:430px;margin:0 auto;position:relative;background:#fffdf7;
    box-shadow:0 0 60px rgba(90,75,40,.35);overflow:hidden;min-height:100vh}
  body.is-locked{overflow:hidden}
  .text-gold-grad{background:linear-gradient(105deg,#77603a 0%,#c2ab72 35%,#978045 55%,#d9c491 75%,#77603a 100%);
    -webkit-background-clip:text;background-clip:text;color:transparent}
  .card-putih{background:#ffffff;border:1px solid rgba(151,128,69,.45);border-radius:1.1rem;position:relative;
    box-shadow:0 22px 44px -20px rgba(119,96,58,.35)}
  .btn-gold{background:linear-gradient(105deg,#8a6f3c,#c2ab72 55%,#978045);color:#fffdf7;
    box-shadow:0 10px 24px -8px rgba(151,128,69,.55);transition:transform .25s,box-shadow .25s}
  .btn-gold:hover{transform:translateY(-2px)}
  .btn-outline{border:1px solid rgba(151,128,69,.75);color:#77603a;transition:all .25s;background:rgba(151,128,69,.05)}
  .btn-outline:hover{background:rgba(151,128,69,.14)}
  .hairline{height:1px;background:linear-gradient(90deg,transparent,rgba(151,128,69,.7),transparent)}
  .field-pendopo{width:100%;background:#fffdf7;border:1px solid rgba(151,128,69,.45);
    border-radius:.8rem;color:#2a2519;padding:.8rem 1rem;font-size:.9rem;outline:none;transition:border-color .25s}
  .field-pendopo::placeholder{color:rgba(87,80,62,.4)}
  .field-pendopo:focus{border-color:#978045}
  #musicFloat{position:fixed;bottom:96px;right:max(14px,calc(50% - 215px + 14px));z-index:45;
    width:52px;height:52px;border-radius:50%;border:1px solid rgba(151,128,69,.7);
    background:radial-gradient(circle at 32% 30%,#ffffff,#f3ecd9 70%);color:#77603a;
    display:flex;align-items:center;justify-content:center;box-shadow:0 12px 28px rgba(90,75,40,.35);cursor:pointer}
    #qrFloatBtn{ position:fixed; z-index:45; right:max(14px,calc(50% - 215px + 14px)); bottom:188px;
      width:48px; height:48px; border-radius:9999px; display:flex; align-items:center; justify-content:center;
      background:linear-gradient(160deg,rgba(43,33,24,.95),rgba(23,18,8,.97)); color:#e6c887; cursor:pointer;
      box-shadow:0 10px 24px rgba(90,70,20,.45); border:1px solid rgba(201,164,92,.8); }
    #qrFloatBtn[hidden]{ display:none !important; }
    #qrFloatBtn.hidden{ display:none !important; }
  #musicFloat .bars{display:none;gap:2.5px;align-items:flex-end;height:16px}
  #musicFloat .bars span{width:3px;background:#77603a;animation:eqB 0.9s ease-in-out infinite}
  #musicFloat .bars span:nth-child(2){animation-delay:.25s}
  #musicFloat .bars span:nth-child(3){animation-delay:.5s}
  #musicFloat.playing .bars{display:flex}
  #musicFloat.playing .note{display:none}
  @keyframes eqB{0%,100%{height:5px}50%{height:16px}}
  #floatMenu{position:fixed;right:max(14px,calc(50% - 215px + 14px));bottom:168px;z-index:45;
    display:flex;flex-direction:column;gap:9px}
  #floatMenu a{width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;
    background:rgba(255,253,247,.92);border:1px solid rgba(151,128,69,.6);color:#77603a;
    box-shadow:0 10px 22px rgba(90,75,40,.3);backdrop-filter:blur(4px)}
  #floatMenu.hidden, #musicFloat.hidden, #qrFloatBtn.hidden{display:none !important}
  #toast{position:fixed;left:50%;bottom:110px;transform:translate(-50%,20px);z-index:90;
    background:#2a2519;color:#fffdf7;border:1px solid #978045;border-radius:999px;
    padding:.65rem 1.2rem;font-size:.8rem;font-weight:600;opacity:0;pointer-events:none;transition:all .35s;
    box-shadow:0 14px 30px rgba(90,75,40,.4);max-width:88vw;text-align:center}
  #toast.show{opacity:1;transform:translate(-50%,0)}
  #petalCanvas{position:fixed;top:0;left:50%;transform:translateX(-50%);width:min(430px,100vw);
    height:100dvh;z-index:44;pointer-events:none}
  #wish-list > div{animation:jpnFadeUp .6s ease both}
  @keyframes jpnFadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
  .orn{will-change:transform}
  @media (prefers-reduced-motion: reduce){
    #musicFloat .bars span{animation:none}
  }

/* Standar menu klasik emas: bottom bar */
#floatMenu,#bottomNav{position:fixed;left:50%;transform:translateX(-50%);right:auto;bottom:10px;z-index:46;display:flex;flex-direction:row;gap:2px;background:rgba(255,253,247,.94);border:1px solid rgba(119,96,58,.45);border-radius:26px;padding:7px 8px;backdrop-filter:blur(10px);box-shadow:0 14px 34px rgba(90,70,20,.25);width:auto;max-width:96vw;flex-wrap:nowrap;justify-content:center;overflow:visible}
#floatMenu::-webkit-scrollbar,#bottomNav::-webkit-scrollbar{display:none}
#floatMenu a,#bottomNav a{display:flex;flex-direction:row;align-items:center;gap:0;flex:0 0 auto;width:auto;height:auto;min-width:0;max-width:none;padding:7px 9px;border-radius:13px;color:#77603a!important;background:transparent!important;border:none!important;box-shadow:none!important;text-decoration:none;font-size:7.5px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;transition:background .3s ease,box-shadow .3s ease,color .3s ease}
#floatMenu a svg,#bottomNav a svg{width:21px;height:21px;flex:none}
#floatMenu a span,#bottomNav a span{display:inline-block;flex:none;max-width:0;opacity:0;overflow:hidden;white-space:nowrap;transition:max-width .35s ease,opacity .3s ease,margin-left .35s ease}
#floatMenu a.active span,#bottomNav a.active span{max-width:90px;opacity:1;margin-left:6px}
#floatMenu a.active,#bottomNav a.active{background:rgba(119,96,58,.14)!important;box-shadow:inset 0 0 0 1.5px #c2ab72!important;color:#77603a!important}
#floatMenu.hidden,#bottomNav[hidden]{display:none!important}
#musicFloat{bottom:130px!important;background:linear-gradient(160deg,rgba(43,33,24,.95),rgba(23,18,8,.97))!important;border:1px solid rgba(201,164,92,.8)!important;color:#e6c887!important;box-shadow:0 10px 24px rgba(90,70,20,.45)!important}
@media (max-width:400px){
  #bottomNav a,#floatMenu a{padding:7px 6px}
  #bottomNav a svg,#floatMenu a svg{width:20px;height:20px}
  #musicFloat{bottom:140px!important}
  #qrFloatBtn{bottom:198px!important}
  #toast{bottom:150px!important}
}
#musicFloat .bars span{background:#e6c887!important}
#vinyl{bottom:96px!important}
@media (prefers-reduced-motion:reduce){}
</style>
</head>
<body class="is-locked antialiased">
<main class="invitation-frame min-h-screen">
  <div class="bg-garden" aria-hidden="true"></div>
  <div class="bg-veil" aria-hidden="true"></div>
  <canvas id="petalCanvas" aria-hidden="true"></canvas>

  <!-- ================= 1. PRELOADER ================= -->
  <section id="preloader" class="fixed inset-0 z-50 mx-auto max-w-[430px] flex flex-col items-center justify-center px-6 text-center bg-pendopo-cream">
    <img src="{{ asset('images/jawa-puspa/gunungan-divider.webp') }}" alt="" class="w-24 mb-4 relative" />
    <p class="relative text-[10px] uppercase tracking-[0.4em] text-pendopo-deep mb-2">Wedding Invitation</p>
    <h1 class="relative font-serif text-[1.7rem] tracking-[0.1em] text-pendopo-ink">{{ strtoupper($groomNick) }} <span class="text-gold-grad">&amp;</span> {{ strtoupper($brideNick) }}</h1>
    <div class="relative w-40 h-[3px] bg-pendopo-gold/15 rounded-full overflow-hidden mb-2 mt-6">
      <div id="loader-bar" class="h-full w-0" style="background:linear-gradient(90deg,#77603a,#c2ab72)"></div>
    </div>
    <span id="loader-percent" class="relative text-[11px] font-mono tracking-[0.25em] text-pendopo-deep">0%</span>
  </section>

  <!-- ================= 2. COVER (VIDEO OPENING) ================= -->
  <section id="cover-gate" class="fixed inset-0 z-40 mx-auto max-w-[430px] h-[100dvh] overflow-hidden bg-[#2a1f14] text-center">
    <video id="coverVideo" class="absolute inset-0 w-full h-full object-cover" playsinline preload="auto" poster="{{ asset('images/jawa-pendopo/background.jpg') }}" src="{{ asset('videos/jawa-pendopo-opening.mp4') }}"></video>
    <div class="absolute inset-0" style="background:linear-gradient(180deg,rgba(42,31,20,.28) 0%,rgba(42,31,20,0) 30%,rgba(42,31,20,0) 55%,rgba(42,31,20,.55) 100%)"></div>
    <div id="coverNames" aria-hidden="true">
      <p class="cn-label">THE WEDDING OF</p>
      <p class="cn-names">{{ $groomNick }} <span>&amp;</span> {{ $brideNick }}</p>
      <p class="cn-date">{{ jpnDate($rDate) }}</p>
    </div>
    <div id="gate-content" class="relative z-10 h-full flex flex-col justify-between px-6 py-8">
      <div id="gate-header">
        <p class="text-[11px] tracking-[0.35em] text-[#f4e8c8] font-semibold" style="text-shadow:0 2px 8px rgba(0,0,0,.5)">THE WEDDING OF</p>
        <p class="font-serif text-[1.35rem] tracking-[0.08em] text-[#fdf6dd] mt-1" style="text-shadow:0 2px 10px rgba(0,0,0,.55)">{{ strtoupper($groomNick) }} &amp; {{ strtoupper($brideNick) }}</p>
      </div>
      <div id="gate-center" class="flex flex-col items-center">
        <div class="px-6 py-5 rounded-2xl" style="background:rgba(253,246,221,.92);border:1px solid rgba(201,164,92,.7);box-shadow:0 18px 40px rgba(0,0,0,.35)">
          <p class="text-[10px] tracking-[0.3em] uppercase text-pendopo-deep mb-2">Kepada Yth<br>Bapak/Ibu/Saudara/i</p>
          <p class="font-serif text-[1.35rem] font-semibold text-pendopo-ink">{{ $toName }}</p>
        </div>
      </div>
      <div id="gate-footer">
        <button id="btn-open" class="btn-gold font-semibold text-[12px] tracking-[0.25em] px-10 py-3.5 rounded-full">
          BUKA UNDANGAN
        </button>
        <p class="text-[10px] text-[#f4e8c8]/80 mt-4 tracking-widest" style="text-shadow:0 2px 8px rgba(0,0,0,.5)">* Mohon maaf apabila ada kesalahan penulisan nama &amp; gelar</p>
      </div>
    </div>
  </section>

  <!-- ================= 3. HERO ================= -->
  <section id="hero-section" class="relative min-h-[100dvh] w-full flex flex-col items-center justify-center text-center px-6 py-14 overflow-hidden">
    <div class="hero-veil" aria-hidden="true"></div>
    <div class="hero-content relative z-10 flex flex-col items-center w-full pt-6">
      <svg class="hero-stagger opacity-0 w-72 h-16 mb-1" viewBox="0 0 300 60" aria-hidden="true">
        <defs><path id="arcPath" d="M 15 58 A 260 260 0 0 1 285 58" /></defs>
        <text style="font-family:'Playfair Display',serif;letter-spacing:0.42em" font-size="16.5" fill="#77603a">
          <textPath href="#arcPath" startOffset="50%" text-anchor="middle">THE WEDDING OF</textPath>
        </text>
      </svg>
      <div class="hero-stagger opacity-0 relative w-full max-w-[340px] flex justify-center my-1">
        <img src="{{ asset('images/jawa-puspa/sayap-gunungan.webp') }}" alt="" class="orn absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[340px] max-w-none pointer-events-none" data-speed="0.5" />
        <div class="relative w-44 h-60 mt-8" style="border-radius:999px 999px 14px 14px; overflow:hidden; border:2.5px solid #978045; box-shadow:0 18px 36px rgba(119,96,58,.35)">
          @if(!empty($gallery) && count($gallery))
          <img src="{{ $gallery[0] }}" class="w-full h-full object-cover" alt="Mempelai" />
          @elseif(jpnGet('cover_image'))
          <img src="{{ jpnGet('cover_image') }}" class="w-full h-full object-cover" alt="Mempelai" />
          @else
          <img src="{{ asset('images/jawa-pendopo/background.jpg') }}" class="w-full h-full object-cover" alt="Mempelai" />
          @endif
        </div>
      </div>
      <h2 class="hero-stagger opacity-0 font-script text-[3.2rem] leading-tight text-pendopo-ink mt-4">{{ $groomNick }} <span class="text-gold-grad">&amp;</span> {{ $brideNick }}</h2>
      <div class="hero-stagger opacity-0 mt-3 flex items-stretch justify-center gap-4 text-pendopo-ink">
        <div class="flex flex-col justify-center"><span class="font-serif text-lg">{{ jpnDayName($rDate) }}</span></div>
        <div class="flex flex-col justify-center border-x border-pendopo-gold/70 px-4"><span class="font-serif text-4xl font-semibold text-gold-grad leading-none">{{ jpnDayNum($rDate) }}</span><span class="text-[11px] tracking-[0.2em] text-pendopo-soft mt-1">{{ jpnYear($rDate) }}</span></div>
        <div class="flex flex-col justify-center"><span class="font-serif text-lg">{{ jpnMonthName($rDate) }}</span></div>
      </div>
    </div>
    <p class="hero-stagger absolute bottom-7 z-10 opacity-0 text-[9px] tracking-[0.4em] text-pendopo-soft/70 uppercase">Geser ke bawah</p>
  </section>

  <!-- ================= 4. QUOTES ================= -->
  <section id="sec-quotes" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden">
    <div class="anim-zoom relative z-10 w-full max-w-[340px]">
      <div class="mx-auto w-20 h-20 rounded-full flex items-center justify-center mb-5 bg-white"
        style="border:1.5px solid rgba(151,128,69,.75); box-shadow:0 10px 24px -10px rgba(119,96,58,.4)">
        <span class="font-serif text-2xl tracking-[0.12em] text-gold-grad font-semibold">{{ $monoBride }}{{ $monoGroom }}</span>
      </div>
      <p class="anim-fade font-serif text-[16.5px] leading-[1.9] text-pendopo-soft italic">
        &ldquo;Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang.&rdquo;
      </p>
      <div class="hairline w-40 mx-auto my-5"></div>
      <p class="anim-fade-down text-[11px] font-semibold tracking-[0.25em] uppercase text-pendopo-deep">QS. Ar-Rum : 21</p>
      @if(jpnGet('quotes'))
      <p class="anim-fade font-serif italic text-pendopo-soft/85 text-[15px] leading-relaxed mt-6">&ldquo;{{ jpnGet('quotes') }}&rdquo;</p>
      @endif
      <img src="{{ asset('images/jawa-puspa/gunungan-divider.webp') }}" alt="" class="anim-zoom w-24 mx-auto mt-8 opacity-90" />
    </div>
  </section>

  <!-- ================= 5. COUNTDOWN ================= -->
  <section class="scroll-section relative py-16 px-6 flex flex-col items-center text-center overflow-hidden">
    <p class="anim-fade-down text-[10px] uppercase tracking-[0.4em] text-pendopo-deep mb-2 relative z-10">Menghitung Hari Bahagia</p>
    <h2 class="anim-zoom font-script text-5xl text-gold-grad mb-8 relative z-10">Count The Date</h2>
    <div class="anim-zoom relative z-10 grid grid-cols-4 gap-2 w-full max-w-[340px]">
      <div class="py-4 rounded-xl bg-white" style="border:1px solid rgba(151,128,69,.5);box-shadow:0 12px 24px -12px rgba(119,96,58,.35)"><div class="font-serif text-[1.6rem] font-bold text-pendopo-ink" id="cd-d">00</div><div class="text-[8px] tracking-[0.22em] text-pendopo-soft mt-1 font-medium">HARI</div></div>
      <div class="py-4 rounded-xl bg-white" style="border:1px solid rgba(151,128,69,.5);box-shadow:0 12px 24px -12px rgba(119,96,58,.35)"><div class="font-serif text-[1.6rem] font-bold text-pendopo-ink" id="cd-h">00</div><div class="text-[8px] tracking-[0.22em] text-pendopo-soft mt-1 font-medium">JAM</div></div>
      <div class="py-4 rounded-xl bg-white" style="border:1px solid rgba(151,128,69,.5);box-shadow:0 12px 24px -12px rgba(119,96,58,.35)"><div class="font-serif text-[1.6rem] font-bold text-pendopo-ink" id="cd-m">00</div><div class="text-[8px] tracking-[0.22em] text-pendopo-soft mt-1 font-medium">MENIT</div></div>
      <div class="py-4 rounded-xl bg-white" style="border:1px solid rgba(151,128,69,.5);box-shadow:0 12px 24px -12px rgba(119,96,58,.35)"><div class="font-serif text-[1.6rem] font-bold text-pendopo-ink" id="cd-s">00</div><div class="text-[8px] tracking-[0.22em] text-pendopo-soft mt-1 font-medium">DETIK</div></div>
    </div>
    <a href="{{ $gcalUrl }}" target="_blank" rel="noopener" class="anim-fade btn-outline relative z-10 text-[10px] tracking-[0.25em] px-7 py-3 rounded-full mt-7 font-medium">+ SIMPAN KE GOOGLE CALENDAR</a>
  </section>

  <!-- ================= 6. MEMPELAI ================= -->
  <section id="sec-profil" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden">
    <p class="anim-fade-down text-[10px] uppercase tracking-[0.35em] text-pendopo-deep mb-2 relative z-10">Assalamu&rsquo;alaikum Warahmatullaahi Wabarakaatuh</p>
    <h2 class="anim-zoom font-script text-5xl text-gold-grad mb-3 relative z-10">Bride &amp; Groom</h2>
    <p class="anim-fade font-serif italic text-pendopo-soft text-[15px] leading-relaxed mb-10 max-w-[300px] relative z-10">Dengan memohon rahmat dan ridha Allah SWT, kami bermaksud menyelenggarakan pernikahan kami.</p>

    <!-- Mempelai wanita -->
    <div class="anim-zoom relative z-10 w-full max-w-[320px] card-putih px-6 pt-8 pb-10 mb-2">
      <div class="relative w-40 h-52 mx-auto mb-4" style="border-radius:999px 999px 14px 14px; overflow:hidden; border:2.5px solid #978045; box-shadow:0 14px 30px rgba(119,96,58,.3)">
        @if(jpnGet('bride_photo'))
        <img src="{{ jpnGet('bride_photo') }}" class="w-full h-full object-cover" alt="{{ $brideFull }}" />
        @else
        <img src="{{ asset('images/jawa-pendopo/background.jpg') }}" class="w-full h-full object-cover" alt="{{ $brideFull }}" />
        @endif
      </div>
      <h3 class="font-serif text-[1.5rem] font-semibold tracking-[0.04em] text-pendopo-ink leading-tight">{{ $brideFull }}</h3>
      <p class="text-[11px] text-pendopo-soft mt-2">{{ jpnGet('bride_child_of','Putri') }} dari</p>
      <p class="text-[13px] font-medium text-pendopo-deep mt-0.5 px-6">{{ jpnGet('bride_parents','') }}</p>
      @if(jpnGet('bride_instagram'))
      <a href="https://instagram.com/{{ ltrim(jpnGet('bride_instagram'),'@') }}" target="_blank" rel="noopener" class="btn-outline inline-block mt-4 text-[10px] tracking-[0.2em] px-5 py-2 rounded-full"><svg class="inline-block w-[13px] h-[13px] mr-1 -mt-[2px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="0.8" fill="currentColor" stroke="none"/></svg>&#64;{{ ltrim(jpnGet('bride_instagram'),'@') }}</a>
      @endif
    </div>

    <img src="{{ asset('images/jawa-puspa/gunungan-divider.webp') }}" alt="" class="anim-zoom relative z-10 w-28 my-5" />

    <!-- Mempelai pria -->
    <div class="anim-zoom relative z-10 w-full max-w-[320px] card-putih px-6 pt-8 pb-10 mt-2">
      <div class="relative w-40 h-52 mx-auto mb-4" style="border-radius:999px 999px 14px 14px; overflow:hidden; border:2.5px solid #978045; box-shadow:0 14px 30px rgba(119,96,58,.3)">
        @if(jpnGet('groom_photo'))
        <img src="{{ jpnGet('groom_photo') }}" class="w-full h-full object-cover" alt="{{ $groomFull }}" />
        @else
        <img src="{{ asset('images/jawa-pendopo/background.jpg') }}" class="w-full h-full object-cover" alt="{{ $groomFull }}" />
        @endif
      </div>
      <h3 class="font-serif text-[1.5rem] font-semibold tracking-[0.04em] text-pendopo-ink leading-tight">{{ $groomFull }}</h3>
      <p class="text-[11px] text-pendopo-soft mt-2">{{ jpnGet('groom_child_of','Putra') }} dari</p>
      <p class="text-[13px] font-medium text-pendopo-deep mt-0.5 px-6">{{ jpnGet('groom_parents','') }}</p>
      @if(jpnGet('groom_instagram'))
      <a href="https://instagram.com/{{ ltrim(jpnGet('groom_instagram'),'@') }}" target="_blank" rel="noopener" class="btn-outline inline-block mt-4 text-[10px] tracking-[0.2em] px-5 py-2 rounded-full"><svg class="inline-block w-[13px] h-[13px] mr-1 -mt-[2px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="0.8" fill="currentColor" stroke="none"/></svg>&#64;{{ ltrim(jpnGet('groom_instagram'),'@') }}</a>
      @endif
    </div>
  </section>

  <!-- ================= 7. ACARA ================= -->
  <section id="sec-acara" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden">
    <p class="anim-fade-down text-[10px] uppercase tracking-[0.4em] text-pendopo-deep mb-2 relative z-10">Wedding Event</p>
    <h2 class="anim-zoom font-script text-5xl text-gold-grad mb-10 relative z-10">Rangkaian Acara</h2>

    <!-- Akad -->
    <div class="anim-zoom relative z-10 w-full max-w-[340px] card-putih px-6 py-8 mt-6">
      <img src="{{ asset('images/jawa-puspa/gunungan-divider.webp') }}" alt="" class="w-20 mx-auto mb-3 block" />
      <h3 class="font-serif text-[1.6rem] font-semibold tracking-[0.12em] text-pendopo-ink">AKAD NIKAH</h3>
      <div class="hairline w-28 mx-auto my-3"></div>
      <p class="font-serif text-lg text-pendopo-soft mb-1">{{ jpnDate($wDate) }}</p>
      <p class="font-serif text-lg text-pendopo-deep font-semibold mb-4">{{ $wTime }} &ndash; {{ $wTimeE }} {{ $tzLabel }}</p>
      <p class="text-[10px] tracking-[0.25em] uppercase text-pendopo-soft/80 mb-1">Lokasi Acara</p>
      <p class="font-medium text-pendopo-ink px-2">{{ jpnGet('wedding_location','Kediaman Mempelai') }}</p>
      @if(!$sameLoc && jpnGet('wedding_map_link'))
      <a href="{{ jpnGet('wedding_map_link') }}" target="_blank" rel="noopener" class="btn-gold inline-flex items-center gap-2 mt-5 text-[11px] font-semibold tracking-[0.2em] px-8 py-3 rounded-full">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
        LIHAT LOKASI
      </a>
      @endif
    </div>

    <!-- Resepsi -->
    <div class="anim-zoom relative z-10 w-full max-w-[340px] card-putih px-6 py-8 mt-10">
      <img src="{{ asset('images/jawa-puspa/gunungan-divider.webp') }}" alt="" class="w-20 mx-auto mb-3 block" />
      <h3 class="font-serif text-[1.6rem] font-semibold tracking-[0.12em] text-pendopo-ink">RESEPSI</h3>
      <div class="hairline w-28 mx-auto my-3"></div>
      <p class="font-serif text-lg text-pendopo-soft mb-1">{{ jpnDate($rDate) }}</p>
      <p class="font-serif text-lg text-pendopo-deep font-semibold mb-4">{{ $rTimeS }} &ndash; {{ $rTimeE }} {{ $tzLabel }}</p>
      <p class="text-[10px] tracking-[0.25em] uppercase text-pendopo-soft/80 mb-1">Lokasi Acara</p>
      <p class="font-medium text-pendopo-ink px-2">{{ jpnGet('reception_location', jpnGet('wedding_location','Kediaman Mempelai')) }}</p>
      <div class="mt-5 flex flex-wrap justify-center gap-2">
        @if($sameLoc)
          @if($mainMapLink)
          <a href="{{ $mainMapLink }}" target="_blank" rel="noopener" class="btn-gold inline-flex items-center gap-2 text-[11px] font-semibold tracking-[0.2em] px-8 py-3 rounded-full">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
            LIHAT LOKASI
          </a>
          @endif
        @else
          @if(jpnGet('reception_map_link'))
          <a href="{{ jpnGet('reception_map_link') }}" target="_blank" rel="noopener" class="btn-gold inline-flex items-center gap-2 text-[11px] font-semibold tracking-[0.2em] px-8 py-3 rounded-full">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
            LIHAT LOKASI
          </a>
          @endif
        @endif
      </div>
    </div>
  </section>

  <!-- ================= 8. LOVE STORY ================= -->
  @if(!empty($stories) && count($stories))
  <section class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden" id="sec-kisah">
    <h2 class="anim-zoom font-script text-5xl text-gold-grad mb-2 relative z-10">Love Story</h2>
    <p class="anim-fade-down text-[11px] tracking-[0.3em] uppercase text-pendopo-deep mb-10 relative z-10">Kisah Perjalanan Kami</p>
    <div class="relative z-10 w-full max-w-[320px] pl-8 text-left">
      <div class="absolute left-[9px] top-2 bottom-2 w-0.5 rounded" style="background:linear-gradient(180deg,#c2ab72,#978045)"></div>
      @foreach($stories as $story)
      <div class="anim-fade relative mb-9">
        <div class="absolute -left-8 top-1.5 w-[19px] h-[19px] rounded-full bg-pendopo-cream flex items-center justify-center" style="border:2.5px solid #978045">
          <div class="w-1.5 h-1.5 rounded-full" style="background:#978045"></div>
        </div>
        @if(!empty($story->tahun))<p class="font-script text-[1.7rem] leading-none text-gold-grad mb-1">{{ $story->tahun }}</p>@endif
        <h4 class="font-serif font-semibold text-pendopo-ink tracking-wide mb-1">{{ $story->judul }}</h4>
        <p class="text-[13px] text-pendopo-soft leading-relaxed">{{ $story->isi }}</p>
      </div>
      @endforeach
    </div>
  </section>
  @endif

  <!-- ================= 9. GALERI ================= -->
  <section id="sec-galeri" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden">
    <h2 class="anim-zoom font-script text-5xl text-gold-grad mb-2 relative z-10">Wedding Gallery</h2>
    <p class="anim-fade-down text-[11px] tracking-[0.3em] uppercase text-pendopo-deep mb-10 relative z-10">Momen Bahagia Kami</p>
    <div class="relative z-10 grid grid-cols-2 gap-2.5 w-full max-w-[340px]">
          @if(jpnGet('prewedding_video_file') || jpnGet('prewedding_video_link'))
@php
  $prewedFile = jpnGet('prewedding_video_file');
  $prewedLink = jpnGet('prewedding_video_link');
  $prewedYtId = null;
  if (!$prewedFile && $prewedLink && preg_match('~(?:youtube\.com/(?:watch\?v=|live/|shorts/|embed/)|youtu\.be/)([\w-]{6,})~', (string)$prewedLink, $pm)) { $prewedYtId = $pm[1]; }
  $prewedThumb = $prewedYtId ? 'https://i.ytimg.com/vi/' . $prewedYtId . '/hqdefault.jpg' : '';
@endphp
    <div id="prewedBox" class="anim-zoom relative z-10 w-full max-w-[340px] col-span-2" style="border-radius:16px;overflow:hidden;border:1px solid rgba(201,164,92,.55);box-shadow:0 18px 40px rgba(0,0,0,.3)">
<div style="position:relative;padding-top:56.25%">
@if($prewedFile)
<video controls preload="metadata" playsinline src="{{ asset($prewedFile) }}" style="position:absolute;inset:0;width:100%;height:100%;background:#000" title="Video Prewedding"></video>
@elseif($prewedYtId)
<button type="button" onclick="playPrewed(this, '{{ $prewedYtId }}')" aria-label="Putar Video Prewedding" style="position:absolute;inset:0;width:100%;height:100%;border:0;padding:0;cursor:pointer;background:#000">
<img src="{{ $prewedThumb }}" alt="Video Prewedding" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.85">
<span style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:68px;height:48px;border-radius:12px;background:rgba(204,0,0,.93);display:flex;align-items:center;justify-content:center;box-shadow:0 10px 24px rgba(0,0,0,.45)"><span style="width:0;height:0;border-left:18px solid #fff;border-top:11px solid transparent;border-bottom:11px solid transparent;margin-left:4px"></span></span>
</button>
@elseif($prewedLink)
<a href="{{ $prewedLink }}" target="_blank" rel="noopener" style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;background:#000;color:#fdf6dd;text-decoration:none">
<span style="width:68px;height:48px;border-radius:12px;background:rgba(204,0,0,.93);display:flex;align-items:center;justify-content:center;box-shadow:0 10px 24px rgba(0,0,0,.45)"><span style="width:0;height:0;border-left:18px solid #fff;border-top:11px solid transparent;border-bottom:11px solid transparent;margin-left:4px"></span></span>
<span style="font-size:11px;font-weight:700;letter-spacing:.22em">TONTON VIDEO PREWEDDING</span>
</a>
@endif
</div>
</div>
    @if($prewedLink)<a href="{{ $prewedLink }}" target="_blank" rel="noopener" class="anim-fade relative z-10 inline-block mt-5 text-[11px] font-bold tracking-[0.22em] px-8 py-3 rounded-full col-span-2 justify-self-center" style="background:linear-gradient(105deg,#b8934a,#e6c887 55%,#c9a45c);color:#241a08;box-shadow:0 10px 24px rgba(90,70,20,.35)">BUKA VIDEO</a>@endif
    @endif
@if(!empty($gallery) && count($gallery))
        @foreach(array_slice($gallery, 0, 8) as $i => $src)
        <div class="gal-item anim-fade relative overflow-hidden rounded-xl cursor-pointer {{ $i === 0 ? 'col-span-2 aspect-[16/10]' : 'aspect-[4/5]' }}" data-full="{{ $src }}" style="border:1.5px solid rgba(151,128,69,.55); box-shadow:0 12px 26px -12px rgba(119,96,58,.4)">
          <img src="{{ $src }}" class="w-full h-full object-cover" alt="Galeri {{ $i+1 }}" loading="lazy" />
        </div>
        @endforeach
      @else
        @foreach(range(1,5) as $i)
        <div class="gal-item anim-fade relative overflow-hidden rounded-xl cursor-pointer {{ $i === 1 ? 'col-span-2 aspect-[16/10]' : 'aspect-[4/5]' }}" data-full="https://picsum.photos/seed/jpgal{{ $i }}/600/450" style="border:1.5px solid rgba(151,128,69,.55)">
          <img src="https://picsum.photos/seed/jpgal{{ $i }}/600/450" class="w-full h-full object-cover" alt="Galeri {{ $i }}" loading="lazy" />
        </div>
        @endforeach
      @endif
    </div>
  </section>

  <!-- ================= 10. GIFT ================= -->
  <section id="sec-gift" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden">
    <h2 class="anim-zoom font-script text-5xl text-gold-grad mb-3 relative z-10">Wedding Gift</h2>
    <p class="anim-fade font-serif italic text-pendopo-soft text-[15px] leading-relaxed mb-10 max-w-[320px] relative z-10">Doa restu Bapak/Ibu/Saudara/i merupakan karunia terindah bagi kami. Namun apabila berkenan memberikan tanda kasih, kami sediakan dengan penuh terima kasih.</p>

    <div class="relative z-10 w-full max-w-[340px] flex flex-col gap-5">
      @if($qrisEnabled)
      <div class="anim-zoom card-putih px-6 py-7">
        <p class="font-serif text-xl tracking-[0.2em] text-gold-grad font-semibold mb-1">QRIS</p>
        <p class="text-[11px] text-pendopo-soft mb-4">Scan untuk memberi tanda kasih{{ $qrisName ? ' — '.$qrisName : '' }}</p>
        <img src="{{ $qrisImage }}" alt="QRIS" class="w-48 h-48 object-contain mx-auto rounded-xl border border-pendopo-gold/30 p-2 bg-white">
      </div>
      @endif
      @foreach($accounts as $acc)
      <div class="anim-zoom card-putih px-6 py-6">
        <div class="flex items-center justify-center gap-2.5 mb-1">
          @if(!empty($acc['logo']))
          <img src="{{ $acc['logo'] }}" alt="Logo {{ $acc['bank'] }}" class="h-7 w-7 object-contain rounded-md border border-pendopo-gold/25 p-0.5" loading="lazy" onerror="this.style.display='none'">
          @endif
          <p class="font-serif text-[1.3rem] tracking-[0.18em] text-pendopo-ink font-semibold">{{ strtoupper($acc['bank']) }}</p>
        </div>
        <p class="text-[11px] text-pendopo-soft mb-3">a.n {{ !empty($acc['an']) ? $acc['an'] : $giftRecipient }}</p>
        <p class="font-mono text-xl tracking-[0.12em] text-pendopo-ink mb-4">{{ $acc['no'] }}</p>
        <button onclick="copyText('{{ $acc['no'] }}')" class="btn-outline text-[10px] tracking-[0.25em] px-6 py-2.5 rounded-full font-medium">SALIN NO REKENING</button>
      </div>
      @endforeach

      @if(jpnGet('gift_address'))
      <div class="anim-zoom card-putih px-6 py-6">
        <p class="font-serif text-[1.15rem] tracking-[0.14em] text-pendopo-ink font-semibold mb-2">KIRIM KADO FISIK</p>
        @if($giftRecipient)<p class="text-[12px] font-semibold text-pendopo-deep mb-1">{{ $giftRecipient }}</p>@endif
        <p class="text-[12.5px] leading-relaxed text-pendopo-soft">{{ jpnGet('gift_address') }}</p>
        <button onclick="copyText('{{ $giftRecipient }} — {{ jpnGet('gift_address') }}')" class="btn-outline text-[10px] tracking-[0.25em] px-6 py-2.5 rounded-full mt-4 font-medium">SALIN ALAMAT</button>
      </div>
      @endif
    </div>
  </section>

  <!-- ================= 11. RSVP & UCAPAN ================= -->
    @if(jpnGet('guest_qr_enabled') && !empty($guestQrCode))
  <button id="qrFloatBtn" type="button" onclick="openQrModal()" aria-label="QR Check-in" title="QR Check-in" class="hidden"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3.5" y="3.5" width="6.5" height="6.5" rx="1.5"/><rect x="14" y="3.5" width="6.5" height="6.5" rx="1.5"/><rect x="3.5" y="14" width="6.5" height="6.5" rx="1.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 14h3.5v3.5H14zM20.5 14v.5M14 20.5h.5M17.5 17.5h3v3h-3z"/></svg></button>
  <div id="qrModal" style="position:fixed;inset:0;z-index:60;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,.65);padding:20px" onclick="if(event.target===this)closeQrModal()">
    <div style="background:#fdf6dd;border:2px solid #c9a45c;border-radius:18px;padding:26px 22px;max-width:340px;width:100%;text-align:center;position:relative;box-shadow:0 24px 60px rgba(0,0,0,.4)">
      <button type="button" onclick="closeQrModal()" aria-label="Tutup" style="position:absolute;top:8px;right:12px;border:0;background:none;font-size:24px;line-height:1;cursor:pointer;color:#6d4a2c">&times;</button>
      <h2 style="font-family:serif;font-size:24px;color:#6d4a2c;margin:0 0 6px">QR Check-In</h2>
      <p style="opacity:.75;font-size:13px;line-height:1.6;margin:0 0 18px;color:#2b2118">Simpan dan tunjukkan QR ini kepada panitia saat check-in di lokasi acara.</p>
      <div style="background:#fff;padding:16px;border-radius:18px;display:inline-block;box-shadow:0 14px 34px rgba(0,0,0,.25)"><div id="qrModalBox"></div></div>
      <p style="margin:16px 0 0;font-weight:600;color:#2b2118">{{ $guestName }}</p>
      <p style="margin:6px 0 0;font-size:13px;color:#6d4a2c">Kode manual: <b style="letter-spacing:.12em">{{ $guestQrCode }}</b></p>
      <button id="qrModalDownloadBtn" type="button" style="display:block;width:100%;margin-top:14px;padding:12px;border:none;border-radius:999px;background:#775a19;color:#fff;font-weight:700;font-size:14px;cursor:pointer">Unduh QR</button>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script>
  function openQrModal(){var m=document.getElementById('qrModal');if(m)m.style.display='flex';}
  function closeQrModal(){var m=document.getElementById('qrModal');if(m)m.style.display='none';}
  if(window.QRCode){var qb=document.getElementById('qrModalBox');new QRCode(qb,{text:"{{ $guestQrCode }}",width:200,height:200,correctLevel:QRCode.CorrectLevel.M});document.getElementById('qrModalDownloadBtn').onclick=function(){var c=qb.querySelector('canvas');var im=qb.querySelector('img');var u=c?c.toDataURL('image/png'):(im?im.src:'');if(!u)return;var a=document.createElement('a');a.href=u;a.download='QR-Undangan.png';a.click();};}
  </script>
  @endif
<section id="sec-ucapan" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden">
    <h2 class="anim-zoom font-script text-5xl text-gold-grad mb-3 relative z-10">RSVP &amp; Ucapan</h2>
    <p class="anim-fade font-serif italic text-pendopo-soft text-[15px] leading-relaxed mb-8 max-w-[320px] relative z-10">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>

    <form id="rsvp-form" class="anim-zoom relative z-10 w-full max-w-[340px] card-putih px-6 py-7 text-left mb-12" onsubmit="submitRSVP(event)">
      <label class="block text-[10px] tracking-[0.25em] uppercase text-pendopo-deep mb-1.5 font-medium">Nama Lengkap</label>
      <input type="text" id="rsvp-name" required class="field-pendopo mb-4" placeholder="Nama Anda" value="{{ $toName !== 'Tamu Undangan' ? $toName : '' }}">
      <label class="block text-[10px] tracking-[0.25em] uppercase text-pendopo-deep mb-1.5 font-medium">Konfirmasi Kehadiran</label>
      <div class="grid grid-cols-2 gap-2 mb-4" id="rsvp-status-group">
        <button type="button" data-status="hadir" class="rsvp-status-btn py-3 rounded-lg text-[12px] font-semibold tracking-wider transition-all" style="border:1px solid rgba(151,128,69,.55);color:#77603a">Hadir</button>
        <button type="button" data-status="tidak" class="rsvp-status-btn py-3 rounded-lg text-[12px] font-semibold tracking-wider transition-all" style="border:1px solid rgba(151,128,69,.55);color:#77603a">Berhalangan</button>
      </div>
      <div id="rsvp-alasan-wrap" class="hidden">
        <label class="block text-[10px] tracking-[0.25em] uppercase text-pendopo-deep mb-1.5 font-medium">Alasan Berhalangan</label>
        <textarea id="rsvp-alasan" rows="2" placeholder="Tulis alasan berhalangan..." class="field-pendopo mb-4"></textarea>
      </div>
      <div id="rsvp-count-wrap" class="hidden">
        <label class="block text-[10px] tracking-[0.25em] uppercase text-pendopo-deep mb-1.5 font-medium">Jumlah Tamu</label>
        <select id="rsvp-count" class="field-pendopo mb-4">
          <option value="1">1 Orang</option>
          <option value="2">2 Orang</option>
          <option value="3">3 Orang</option>
          <option value="4">4 Orang</option>
          <option value="5">5 Orang</option>
        </select>
      </div>
      <button type="submit" id="rsvp-submit" class="btn-gold w-full py-3.5 rounded-full text-[12px] font-semibold tracking-[0.25em]">KONFIRMASI KEHADIRAN</button>
      <p id="rsvp-msg" class="hidden text-center text-[13px] text-pendopo-deep mt-4">Terima kasih, konfirmasi Anda telah kami terima.</p>
    </form>

    <form id="wish-form" class="anim-zoom relative z-10 w-full max-w-[340px] card-putih px-6 py-7 text-left" onsubmit="submitWish(event)">
      <label class="block text-[10px] tracking-[0.25em] uppercase text-pendopo-deep mb-1.5 font-medium">Nama</label>
      <input type="text" id="wish-name" required maxlength="60" class="field-pendopo mb-4" placeholder="Nama Anda" value="{{ $toName !== 'Tamu Undangan' ? $toName : '' }}">
      <label class="block text-[10px] tracking-[0.25em] uppercase text-pendopo-deep mb-1.5 font-medium">Ucapan &amp; Doa</label>
      <textarea id="wish-text" rows="3" required maxlength="500" class="field-pendopo mb-1.5 resize-none" placeholder="Tulis ucapan terbaik Anda..."></textarea>
      <p class="text-right text-[10px] text-pendopo-soft/60 mb-4"><span id="wish-count">0</span>/500</p>
      <button type="submit" id="wish-submit" class="btn-gold w-full py-3.5 rounded-full text-[12px] font-semibold tracking-[0.25em]">KIRIM UCAPAN</button>
    </form>

    <div id="wish-list" class="relative z-10 w-full max-w-[340px] mt-8 flex flex-col gap-3 text-left"></div>
  </section>

  <!-- ================= 12. FOOTER ================= -->

<script>
function playPrewed(btn, id){
  var wrap = btn.parentNode;
  wrap.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0&enablejsapi=1" style="position:absolute;inset:0;width:100%;height:100%;border:0" allow="accelerometer; autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen referrerpolicy="strict-origin-when-cross-origin" title="Video Prewedding"></iframe>';
if(window.prewedPauseMusic) window.prewedPauseMusic();
}
</script>
@if(jpnGet('live_stream_link'))
@php
  $liveUrl = jpnGet('live_stream_link');
  $liveEmbed = $liveUrl;
  if (preg_match('~(?:youtube\.com/(?:watch\?v=|live/|shorts/|embed/)|youtu\.be/)([\w-]{6,})~', (string)$liveUrl, $lm)) { $liveEmbed = 'https://www.youtube-nocookie.com/embed/' . $lm[1]; }
  $liveId = $lm[1] ?? null;
  $liveThumb = $liveId ? 'https://i.ytimg.com/vi/' . $liveId . '/hqdefault.jpg' : '';
@endphp
<section class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden" id="sec-live">
    <p class="anim-fade-down text-[10px] uppercase tracking-[0.4em] text-pendopo-deep mb-2 relative z-10">Live Streaming</p>
    <h2 class="anim-zoom font-script text-5xl text-gold-grad mb-3 relative z-10">Saksikan Langsung</h2>
    <p class="anim-fade font-serif italic text-pendopo-soft text-[15px] leading-relaxed mb-9 max-w-[320px] relative z-10">Bagi Bapak/Ibu/Saudara/i yang berhalangan hadir, silakan saksikan acara kami secara langsung melalui tayangan berikut.</p>
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
  <footer class="scroll-section relative pt-20 flex flex-col items-center text-center overflow-hidden">
    <div class="px-6 flex flex-col items-center">
      <img src="{{ asset('images/jawa-puspa/gunungan-divider.webp') }}" alt="" class="anim-zoom w-20 mb-5" />
      <p class="anim-fade font-serif italic text-pendopo-soft text-[15px] leading-[1.9] max-w-[320px] mb-8">Merupakan suatu kehormatan dan kebahagiaan bagi kami beserta keluarga besar apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu kepada kedua mempelai.</p>
      <p class="anim-fade-down text-[10px] tracking-[0.3em] uppercase text-pendopo-deep mb-2">Kami yang berbahagia,</p>
      <h3 class="anim-zoom font-script text-[2.9rem] leading-tight text-pendopo-ink mb-3">{{ $groomNick }} &amp; {{ $brideNick }}</h3>
      <p class="anim-fade-down text-[10px] tracking-[0.3em] uppercase text-pendopo-soft/70">Beserta Keluarga Besar</p>
    </div>
  </footer>

  <!-- Floating: musik + menu -->
  <button id="musicFloat" class="hidden" aria-label="Musik" onclick="toggleMusic()">
    <svg class="note w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18V6l10-2v11.5M9 18a3 3 0 1 1-2-2.83A3 3 0 0 1 9 18zm10-2.5a3 3 0 1 1-2-2.83A3 3 0 0 1 19 15.5z"/></svg>
    <span class="bars"><span></span><span></span><span></span></span>
  </button>
  <nav id="floatMenu" class="hidden" aria-label="Menu undangan">
        <a href="#sec-quotes" data-sec="sec-quotes"><svg class="w-[21px] h-[21px]" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 5.5C4.9 6.7 4 8.6 4 10.9V18h6.4v-6.4H7.2c0-1.9.8-3.2 2.3-4.2l-3.1-1.8zm9 0c-1.7 1.2-2.6 3.1-2.6 5.4V18h6.4v-6.4h-3.2c0-1.9.8-3.2 2.3-4.2l-3.1-1.8z"/></svg><span>Quotes</span></a>
        <a href="#sec-profil" data-sec="sec-profil"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7.5-4.7-9.5-9C1 8.5 3 5 6.5 5c2 0 3.5 1 4.5 2.5C12 6 13.5 5 15.5 5 19 5 21 8.5 20.5 12c-2 4.3-8.5 9-8.5 9z"/></svg><span>Mempelai</span></a>
        <a href="#sec-acara" data-sec="sec-acara"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path stroke-linecap="round" d="M8 3v4M16 3v4M3 10h18"/></svg><span>Acara</span></a>
        <a href="#sec-kisah" data-sec="sec-kisah"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.5C10 4.8 7 4.3 4.5 4.5v13c2.5-.2 5.5.3 7.5 2 2-1.7 5-2.2 7.5-2v-13C17 4.3 14 4.8 12 6.5z"/><path stroke-linecap="round" d="M12 6.5v13"/></svg><span>Kisah</span></a>
        <a href="#sec-galeri" data-sec="sec-galeri"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.6"/><path stroke-linecap="round" stroke-linejoin="round" d="m5 19 5.5-5.5 3 3L19 11l2 2"/></svg><span>Galeri</span></a>
        <a href="#sec-gift" data-sec="sec-gift"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8v13H3V8M1 3h22v5H1zM12 8v13M12 8H7.5a2.5 2.5 0 0 1 0-5C11 3 12 8 12 8zm0 0h4.5a2.5 2.5 0 0 0 0-5C13 3 12 8 12 8z"/></svg><span>Gift</span></a>
        <a href="#rsvp-form" data-sec="rsvp-form"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg><span>RSVP</span></a>
        <a href="#wish-form" data-sec="wish-form"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg><span>Ucapan</span></a>
        <a href="#sec-live" data-sec="sec-live"><svg class="w-[21px] h-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path fill="currentColor" stroke="none" d="M10 8.3v7.4c0 .6.7 1 1.2.7l6-3.7c.5-.3.5-1 0-1.3l-6-3.7c-.5-.3-1.2.1-1.2.6z"/></svg><span>Live</span></a>
      </nav>

  <!-- Lightbox -->
  <div id="lightbox" class="fixed inset-0 z-[80] hidden items-center justify-center p-4" style="background:rgba(30,25,12,.96)">
    <button id="lbClose" class="absolute top-4 right-4 z-10 text-white/80 hover:text-white text-4xl leading-none px-2" aria-label="Tutup">&times;</button>
    <button id="lbPrev" class="absolute left-2 z-10 w-11 h-11 rounded-full text-[#e8d9ae] text-3xl flex items-center justify-center" style="background:rgba(151,128,69,.18);border:1px solid rgba(194,171,114,.6)" aria-label="Sebelumnya">&#8249;</button>
    <img id="lbImg" class="max-w-full max-h-[82vh] rounded-xl object-contain select-none" style="border:1px solid rgba(194,171,114,.55)" alt="Galeri" draggable="false" />
    <button id="lbNext" class="absolute right-2 z-10 w-11 h-11 rounded-full text-[#e8d9ae] text-3xl flex items-center justify-center" style="background:rgba(151,128,69,.18);border:1px solid rgba(194,171,114,.6)" aria-label="Berikutnya">&#8250;</button>
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
  let gateOpened = false;
  async function initAudio() {
    try {
      const res = await fetch('/api/music?inv_id=' + (typeof INV_ID !== 'undefined' ? INV_ID : ''));
      const data = await res.json();
      const fp = data && data.music ? data.music.file_path : (data ? data.file_path : null);
      if (fp) {
        audioEl.src = (fp.startsWith('http') || fp.startsWith('/')) ? fp : '/' + fp.replace(/^\//, '');
        audioEl.load();
      }
    } catch (e) { /* abaikan */ }
    if (!audioEl.src && MUSIC_URL) { audioEl.src = MUSIC_URL; audioEl.load(); }
    if (audioEl.src && gateOpened) { musicFloat.classList.remove('hidden'); if (MUS_AUTO && !audioStarted) playMusic(); }
    /* QR ditampilkan di handler btn-open, bukan di sini (jangan muncul sebelum Buka) */
  }
  initAudio();

  function playMusic() {
    if (!audioEl || !audioEl.src) return;
    audioEl.volume = MUS_VOL;
    audioEl.play().then(() => { audioStarted = true; if (musicFloat) musicFloat.classList.add('playing'); }).catch(() => {});
  }
  window.toggleMusic = function () {
    if (!audioEl) return;
    if (audioEl.paused) { if(window.prewedPauseVideo) window.prewedPauseVideo(); prewedMusicWasOn=false; playMusic(); }
    else { audioEl.pause(); if (musicFloat) musicFloat.classList.remove('playing'); prewedMusicWasOn=false; }
  };

var prewedMusicWasOn=false;
function prewedPauseMusic(){ if(audioEl&&!audioEl.paused){ audioEl.pause(); if(musicFloat)musicFloat.classList.remove('playing'); prewedMusicWasOn=true; } }
function prewedResumeMusic(){ if(prewedMusicWasOn){ prewedMusicWasOn=false; playMusic(); } }

/* ---------- Video prewedding: autoplay saat terlihat + musik saling jeda ---------- */
var prewedBox=document.getElementById('prewedBox');
function prewedYtCmd(fn){ if(!prewedBox)return; var f=prewedBox.querySelector('iframe'); if(f&&f.contentWindow){ try{ f.contentWindow.postMessage('{"event":"command","func":"'+fn+'","args":""}','*'); }catch(e){} } }
function prewedPlayVideo(){
  if(!prewedBox)return;
  var v=prewedBox.querySelector('video');
  if(v){ var p=v.play(); if(p&&p.catch)p.catch(function(){}); return; }
  var b=prewedBox.querySelector('button[onclick*="playPrewed"]');
  if(b){ b.click(); return; }
  prewedYtCmd('playVideo'); prewedPauseMusic();
}
function prewedPauseVideo(){
  if(!prewedBox)return;
  var v=prewedBox.querySelector('video'); if(v&&!v.paused){ v.pause(); return; }
  prewedYtCmd('pauseVideo');
}
(function(){
  if(!prewedBox) return;
  var v=prewedBox.querySelector('video');
  if(v&&!v._prewedBound){ v._prewedBound=true;
    v.addEventListener('play',function(){ prewedPauseMusic(); });
    v.addEventListener('pause',function(){ prewedResumeMusic(); });
    v.addEventListener('ended',function(){ prewedResumeMusic(); });
  }
  if(!('IntersectionObserver' in window)) return;
  var io=new IntersectionObserver(function(es){
    es.forEach(function(en){
      if(en.isIntersecting){ prewedPlayVideo(); }
      else{ prewedPauseVideo(); prewedResumeMusic(); }
    });
  },{threshold:0.35});
  io.observe(prewedBox);
})();
window.prewedPauseVideo=prewedPauseVideo;
window.prewedPauseMusic=prewedPauseMusic;

  // --- Buka undangan: putar video opening, nama muncul di bingkai akhir, lalu buka ---
  function openInvitation() {
    const gate = document.getElementById('cover-gate');
    if (!gate || gate.style.display === 'none') return;
    var cv = document.getElementById('coverVideo'); if (cv) cv.pause();
    gsap.to(gate, {
      yPercent: -100, duration: 1.15, ease: 'power4.inOut',
      onStart: () => { if (MUS_AUTO) playMusic(); },
      onComplete: () => {
        gate.style.display = 'none';
        document.body.classList.remove('is-locked');
        const fm = document.getElementById('floatMenu');
        if (fm) fm.classList.remove('hidden');
        gateOpened = true;
        if (musicFloat) musicFloat.classList.remove('hidden');
        var qf2 = document.getElementById('qrFloatBtn'); if (qf2) qf2.classList.remove('hidden');
        animateHero();
        ScrollTrigger.refresh();
      }
    });
  }
  window.openInvitation = openInvitation;
  const btnOpen = document.getElementById('btn-open');
  if (btnOpen) {
    btnOpen.addEventListener('click', () => {
      const cv = document.getElementById('coverVideo');
      const reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (!cv || reduced || !cv.play) { openInvitation(); return; }
      ['gate-header','gate-center','gate-footer'].forEach(id => {
        const el = document.getElementById(id);
        if (el) { el.style.transition = 'opacity .5s ease'; el.style.opacity = '0'; el.style.pointerEvents = 'none'; }
      });
      const gc = document.getElementById('gate-content');
      if (gc) gc.style.pointerEvents = 'none';
      let opened = false;
      const finish = () => { if (!opened) { opened = true; openInvitation(); } };
      cv.addEventListener('ended', () => { setTimeout(finish, 1200); }, { once: true });
      cv.addEventListener('timeupdate', () => {
        const d = cv.duration || 0;
        if (d && cv.currentTime >= d - 3.5) {
          const cn = document.getElementById('coverNames');
          if (cn) cn.classList.add('show');
        }
      });
      const t0 = Date.now();
      const watchdog = setInterval(() => {
        if (opened) { clearInterval(watchdog); return; }
        if (!cv.paused && cv.currentTime > 0) { clearInterval(watchdog); return; }
        if (Date.now() - t0 > 2500) { clearInterval(watchdog); finish(); }
      }, 250);
      try { cv.currentTime = 0; const pr = cv.play(); if (pr && pr.catch) pr.catch(finish); }
      catch (e) { finish(); }
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
  document.querySelectorAll('.anim-fade-down').forEach(el =>
    jbReveal(el, { opacity: 0, y: -24 }, { opacity: 1, y: 0 }, 0.95));
  // Teks lain yang belum punya kelas animasi ikut fade-in halus
  document.querySelectorAll('.scroll-section h2, .scroll-section h3, .scroll-section h4, .scroll-section p, .scroll-section label, .scroll-section button, footer h2, footer h3, footer p').forEach(el => {
    if (el.classList.contains('anim-zoom') || el.classList.contains('anim-fade') || el.classList.contains('anim-fade-down') || el.classList.contains('hidden')) return;
    if (el.closest('.anim-zoom') || el.closest('.anim-fade') || el.closest('.anim-fade-down')) return; // ikut animasi kartu induknya
    jbReveal(el, { opacity: 0, y: 18 }, { opacity: 1, y: 0 }, 0.9);
  });

  // --- Kelopak bunga berjatuhan ---
  (function () {
    const cv = document.getElementById('petalCanvas');
    if (!cv || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    const ctx = cv.getContext('2d');
    let W = 0, H = 0, petals = [];
    const COLORS = ['rgba(194,171,114,', 'rgba(151,128,69,', 'rgba(217,196,145,'];
    function size() { const r = cv.getBoundingClientRect(); W = cv.width = Math.round(r.width); H = cv.height = Math.round(r.height); }
    function mk(init) {
      return { x: Math.random() * W, y: init ? Math.random() * H : -24, s: 5 + Math.random() * 7,
        vy: 0.35 + Math.random() * 0.7, ph: Math.random() * Math.PI * 2, sw: 0.4 + Math.random() * 0.9,
        rot: Math.random() * Math.PI * 2, vr: (Math.random() - 0.5) * 0.012,
        c: COLORS[Math.floor(Math.random() * COLORS.length)], a: 0.3 + Math.random() * 0.35 };
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
      b.style.background = on ? 'linear-gradient(105deg,#8a6f3c,#c2ab72 55%,#978045)' : 'transparent';
      b.style.color = on ? '#fffdf7' : '#77603a';
    });
    if (countWrap) countWrap.classList.toggle('hidden', rsvpStatus !== 'hadir');
    var aw = document.getElementById('rsvp-alasan-wrap');
    if (aw) aw.classList.toggle('hidden', rsvpStatus !== 'tidak');
    var ta = document.getElementById('rsvp-alasan');
    if (ta) ta.required = (rsvpStatus === 'tidak');
  }
  statusBtns.forEach(b => b.addEventListener('click', () => { rsvpStatus = b.dataset.status; paintStatus(); }));
  paintStatus();

  window.submitRSVP = async function (e) {
    e.preventDefault();
    const name = document.getElementById('rsvp-name').value.trim();
    if (!name) return showToast('Mohon isi nama Anda');
    const alasan = rsvpStatus === 'tidak' ? document.getElementById('rsvp-alasan').value.trim() : '';
    if (rsvpStatus === 'tidak' && !alasan) return showToast('Mohon isi alasan berhalangan.');
    const btn = document.getElementById('rsvp-submit');
    btn.disabled = true; btn.textContent = 'MENGIRIM...';
    const payload = { invitation_id: INV_ID, nama: name, status: rsvpStatus, konfirmasi: rsvpStatus, kehadiran: rsvpStatus, hadir: rsvpStatus === 'hadir', jumlah_tamu: rsvpStatus === 'hadir' ? parseInt(document.getElementById('rsvp-count').value || '1') : 1, alasan: alasan };
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
    el.className = 'card-putih px-5 py-4';
    el.innerHTML = '<div class="flex items-baseline justify-between gap-3 mb-1"><p class="font-serif font-semibold text-[15px] tracking-wide text-pendopo-ink"></p>' +
      (when ? '<span class="text-[10px] text-pendopo-soft/60 whitespace-nowrap"></span>' : '') + '</div>' +
      '<p class="text-[13px] leading-relaxed text-pendopo-soft"></p>';
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
      if (!itemsW.length) { list.innerHTML = '<p class="text-center text-[13px] text-pendopo-soft/60 py-2">Jadilah yang pertama memberikan ucapan</p>'; return; }
      itemsW.forEach(w => list.appendChild(wishCard(w)));
    } catch (e) {
      list.innerHTML = '<p class="text-center text-[13px] text-pendopo-soft/60 py-2">Ucapan belum dapat dimuat</p>';
    }
  }
  loadWishes();
});

// --- Salin teks + toast ---
let toastTimer = null;
function showToast(msg) {
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
    var y = window.innerHeight * 0.4, cur = links[0] || null;
    links.forEach(function(a){ if(a._sec.getBoundingClientRect().top <= y) cur = a; });
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
