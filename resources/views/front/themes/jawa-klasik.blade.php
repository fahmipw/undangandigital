<?php
/* ============================================================
   TEMA: JAWA KLASIK — undangan Jawa cokelat-emas klasik mewah
   Terinspirasi tema Jawa-01 smstory.id (dibangun ulang mandiri)
   Dipilih via settings: theme = jawa-klasik
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
$musicUrl = null;
if (!empty($musicRecord) && !empty($musicRecord->file_path)) {
  $mp = $musicRecord->file_path;
  $musicUrl = (str_starts_with($mp,'http') || str_starts_with($mp,'/')) ? $mp : asset('storage/'.ltrim($mp,'/'));
}
$invId = $inv_id ?? 0;
$gcalDates = str_replace('-','',$rDate).'T'.str_replace(':','',$rTimeS).'00/'.str_replace('-','',$rDate).'T'.str_replace(':','',$rTimeE).'00';
$gcalUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text='.urlencode('Pernikahan '.$brideNick.' & '.$groomNick).'&dates='.$gcalDates.'&details='.urlencode(jklGet('reception_location', jklGet('wedding_location','')));
$ASSET = 'https://tibrainvitation.id/wp-content/uploads/2026/02/';
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
        colors: { klasik: {
          bg:'#2a1c11', deep:'#1e130b', card:'#38281a', gold:'#d4af37',
          lightgold:'#f0d98c', cream:'#f5ead9', brown:'#4a3018'
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
      overflow-x:hidden; background-color:#2a1c11; box-shadow:0 0 80px rgba(0,0,0,.95); }
    /* tekstur batik kawung */
    .batik-bg{ background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='96' height='96' viewBox='0 0 96 96'%3E%3Cg fill='none' stroke='%23d4af37' stroke-opacity='0.08'%3E%3Cellipse cx='48' cy='22' rx='17' ry='26'/%3E%3Cellipse cx='48' cy='74' rx='17' ry='26'/%3E%3Cellipse cx='22' cy='48' rx='26' ry='17'/%3E%3Cellipse cx='74' cy='48' rx='26' ry='17'/%3E%3Ccircle cx='48' cy='48' r='5'/%3E%3C/g%3E%3C/svg%3E"); }
    .text-gold-grad{ background:linear-gradient(135deg,#f6e27a 0%,#d4af37 50%,#a87f1f 100%);
      -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; color:transparent; }
    .card-klasik{ background:linear-gradient(160deg,#3a2a19 0%,#2c1f13 100%);
      border:1px solid rgba(212,175,55,.45); border-radius:1rem; position:relative;
      box-shadow:0 18px 45px -18px rgba(0,0,0,.75), inset 0 0 0 4px rgba(212,175,55,.08); }
    .card-klasik::before{ content:""; position:absolute; inset:6px; border:1px solid rgba(212,175,55,.25);
      border-radius:.7rem; pointer-events:none; }
    .btn-gold{ background:linear-gradient(135deg,#e0bb43 0%,#f7e3a1 50%,#b38618 100%);
      box-shadow:0 6px 24px rgba(212,175,55,.4); transition:transform .2s, box-shadow .2s; color:#241a0e; }
    .btn-gold:hover{ box-shadow:0 8px 30px rgba(212,175,55,.55); }
    .btn-gold:active{ transform:scale(.96); }
    .btn-outline{ border:1px solid rgba(212,175,55,.6); color:#f0d98c; transition:.25s; }
    .btn-outline:hover{ background:rgba(212,175,55,.12); }
    .hairline{ height:1px; background:linear-gradient(90deg,transparent,rgba(212,175,55,.7),transparent); }
    .field-klasik{ width:100%; background:rgba(0,0,0,.3); border:1px solid rgba(212,175,55,.35);
      border-radius:.8rem; padding:.8rem 1rem; color:#f5ead9; font-size:.9rem; outline:none; transition:.25s; }
    .field-klasik::placeholder{ color:#9a8a70; }
    .field-klasik:focus{ border-color:rgba(212,175,55,.85); box-shadow:0 0 0 3px rgba(212,175,55,.12); }
    select.field-klasik option{ background:#2c1f13; }
    /* musik melayang */
    #musicFloat{ position:fixed; z-index:45; right:max(14px,calc(50% - 215px + 14px)); bottom:86px;
      width:48px; height:48px; border-radius:9999px; display:flex; align-items:center; justify-content:center;
      background:linear-gradient(135deg,#e0bb43,#b38618); color:#241a0e; cursor:pointer;
      box-shadow:0 8px 22px rgba(212,175,55,.45); border:1px solid rgba(245,223,152,.6); }
    #musicFloat[hidden]{ display:none !important; }
    #musicFloat .bars{ display:none; align-items:flex-end; gap:2px; height:16px; }
    #musicFloat.playing .bars{ display:flex; }
    #musicFloat.playing .note{ display:none; }
    #musicFloat .bars span{ width:3px; background:#241a0e; border-radius:2px; animation:eq 0.9s ease-in-out infinite; }
    #musicFloat .bars span:nth-child(2){ animation-delay:.2s; } #musicFloat .bars span:nth-child(3){ animation-delay:.45s; }
    @keyframes eq{ 0%,100%{height:6px;} 50%{height:16px;} }
    /* navigasi bawah */
    #bottomNav{ position:fixed; z-index:45; left:50%; transform:translateX(-50%); bottom:0;
      width:100%; max-width:430px; background:rgba(30,19,11,.96); backdrop-filter:blur(10px);
      border-top:1px solid rgba(212,175,55,.4); display:flex; padding:8px 4px calc(8px + env(safe-area-inset-bottom)); }
    #bottomNav a{ flex:1; display:flex; flex-direction:column; align-items:center; gap:3px;
      color:#a68d64; font-size:9px; letter-spacing:.08em; text-transform:uppercase; padding:4px 0; transition:.25s; }
    #bottomNav a svg{ width:21px; height:21px; }
    #bottomNav a.active{ color:#f0d98c; }
    #bottomNav[hidden]{ display:none !important; }
    #toast{ position:fixed; left:50%; bottom:150px; transform:translate(-50%,16px); z-index:60;
      background:#2c1f13; color:#f5ead9; border:1px solid rgba(212,175,55,.6); border-radius:9999px;
      padding:.65rem 1.4rem; font-size:.8rem; letter-spacing:.05em; opacity:0; transition:.3s;
      pointer-events:none; max-width:86vw; text-align:center; white-space:nowrap;
      overflow:hidden; text-overflow:ellipsis; }
    #toast.show{ opacity:1; transform:translate(-50%,0); }
    ::-webkit-scrollbar{ width:0; background:transparent; }
    .orn{ will-change:transform; }
    .gold-tint{ filter:sepia(1) saturate(2.8) hue-rotate(-12deg) brightness(.92) drop-shadow(0 10px 25px rgba(212,175,55,.4)) !important; }
    @keyframes swaySlow{ 0%,100%{ transform:rotate(-2deg); } 50%{ transform:rotate(2deg); } }
    .sway-slow{ animation:swaySlow 9s ease-in-out infinite; transform-origin:50% 100%; }
    @media (prefers-reduced-motion:reduce){ .sway-slow{ animation:none !important; } }
  </style>
</head>
<body class="is-locked bg-[#120b06] text-klasik-cream font-sans antialiased">
<main class="invitation-frame min-h-screen border-x border-klasik-gold/20">

  <!-- ================= 1. PRELOADER ================= -->
  <section id="preloader" class="fixed inset-0 z-50 mx-auto max-w-[430px] flex flex-col items-center justify-center bg-klasik-deep px-6 text-center">
    <img src="{{ asset('images/gunungan-gold.png') }}" alt="Gunungan" class="w-[26%] max-w-[110px] h-auto mb-5 drop-shadow-[0_8px_20px_rgba(212,175,55,0.35)]" />
    <p class="text-[10px] uppercase tracking-[0.4em] text-klasik-gold mb-2">Wedding Invitation</p>
    <h1 class="font-script text-3xl text-gold-grad mb-6">{{ $groomNick }} &amp; {{ $brideNick }}</h1>
    <div class="w-40 h-[2px] bg-stone-800 rounded-full overflow-hidden mb-2">
      <div id="loader-bar" class="h-full w-0 bg-gradient-to-r from-klasik-gold to-klasik-lightgold"></div>
    </div>
    <span id="loader-percent" class="text-[11px] font-mono tracking-[0.25em] text-klasik-lightgold">0%</span>
  </section>

  <!-- ================= 2. COVER ================= -->
  <section id="cover-gate" class="fixed inset-0 z-40 mx-auto max-w-[430px] h-[100dvh] flex flex-col justify-between py-10 px-6 text-center overflow-hidden batik-bg"
    style="background-color:#241811">
    <div class="pointer-events-none absolute inset-0" style="background:radial-gradient(110% 60% at 50% 0%, rgba(212,175,55,.14), transparent 60%), linear-gradient(180deg, rgba(20,12,7,.55), transparent 35%, transparent 65%, rgba(20,12,7,.8))"></div>
    <img src="{{ $ASSET }}ASSET-JAWA-GUNUNGAN.png" class="gold-tint orn pointer-events-none absolute -left-8 top-6 w-36 opacity-70" data-speed="0.8" alt="" />
    <img src="{{ $ASSET }}ASSET-JAWA-GUNUNGAN.png" class="gold-tint orn pointer-events-none absolute -right-8 top-6 w-36 opacity-70 scale-x-[-1]" data-speed="0.8" alt="" />

    <div id="gate-header" class="relative z-10 pt-6">
      <p class="text-[10px] uppercase tracking-[0.42em] text-klasik-gold mb-3">The Wedding Of</p>
      <h2 class="font-script text-[2.6rem] leading-tight text-gold-grad">{{ $groomNick }} &amp; {{ $brideNick }}</h2>
    </div>

    <div id="gate-center" class="relative z-10">
      <img src="{{ asset('images/gunungan-gold.png') }}" alt="Gunungan" class="sway-slow w-[34%] max-w-[140px] h-auto mx-auto mb-5 drop-shadow-[0_10px_25px_rgba(212,175,55,0.4)]" />
      <div class="card-klasik mx-auto max-w-[300px] px-6 py-5">
        <p class="text-[10px] tracking-[0.3em] uppercase text-klasik-gold/80 mb-2">Kepada Yth<br>Bapak/Ibu/Saudara/i</p>
        <p class="font-serif text-xl font-semibold text-klasik-lightgold">{{ $toName }}</p>
      </div>
    </div>

    <div id="gate-footer" class="relative z-10 pb-4">
      <button id="btn-open" class="btn-gold font-semibold text-[12px] tracking-[0.25em] px-10 py-3.5 rounded-full">
        BUKA UNDANGAN
      </button>
      <p class="text-[10px] text-klasik-cream/50 mt-4 tracking-widest">* Mohon maaf apabila ada kesalahan penulisan nama &amp; gelar</p>
    </div>
  </section>

  <!-- ================= 3. HERO ================= -->
  <section id="hero-section" class="relative h-[100dvh] min-h-[600px] w-full flex flex-col items-center justify-center text-center px-6 overflow-hidden batik-bg" style="background-color:#2a1c11">
    <div class="pointer-events-none absolute inset-0" style="background:radial-gradient(120% 55% at 50% 0%, rgba(212,175,55,.12), transparent 62%), linear-gradient(180deg, rgba(20,12,7,.35), transparent 40%, rgba(20,12,7,.75))"></div>
    <!-- bingkai art-deco sudut -->
    <svg class="orn pointer-events-none absolute top-4 left-4 w-16 h-16 text-klasik-gold/70" data-speed="0.6" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 60 V16 Q4 4 16 4 H60 M12 60 V22 Q12 12 22 12 H60 M4 34 H20 M34 4 V20"/></svg>
    <svg class="orn pointer-events-none absolute top-4 right-4 w-16 h-16 text-klasik-gold/70 scale-x-[-1]" data-speed="0.6" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 60 V16 Q4 4 16 4 H60 M12 60 V22 Q12 12 22 12 H60 M4 34 H20 M34 4 V20"/></svg>
    <svg class="orn pointer-events-none absolute bottom-4 left-4 w-16 h-16 text-klasik-gold/70 scale-y-[-1]" data-speed="0.6" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 60 V16 Q4 4 16 4 H60 M12 60 V22 Q12 12 22 12 H60 M4 34 H20 M34 4 V20"/></svg>
    <svg class="orn pointer-events-none absolute bottom-4 right-4 w-16 h-16 text-klasik-gold/70 scale-[-1]" data-speed="0.6" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 60 V16 Q4 4 16 4 H60 M12 60 V22 Q12 12 22 12 H60 M4 34 H20 V20"/></svg>

    <div class="hero-content relative z-10 flex flex-col items-center px-4">
      <img src="{{ $ASSET }}ASSET-JAWA-GUNUNGAN.png" class="gold-tint orn hero-stagger pointer-events-none w-[46%] max-w-[190px] opacity-0 mb-2" data-speed="0.7" alt="Gunungan" />
      <p class="hero-stagger text-[10px] uppercase tracking-[0.42em] text-klasik-gold mb-3 opacity-0">The Wedding Of</p>
      <h2 class="hero-stagger font-script text-[clamp(2.6rem,11vw,3.6rem)] leading-tight text-gold-grad opacity-0">{{ $groomNick }} &amp; {{ $brideNick }}</h2>
      <div class="hero-stagger mt-6 opacity-0 flex items-stretch justify-center gap-4 text-klasik-cream">
        <div class="flex flex-col justify-center"><span class="font-serif text-lg">{{ jklDayName($rDate) }}</span></div>
        <div class="flex flex-col justify-center border-x border-klasik-gold/50 px-4"><span class="font-serif text-4xl font-semibold text-gold-grad leading-none">{{ jklDayNum($rDate) }}</span><span class="text-[11px] tracking-[0.2em] text-klasik-gold mt-1">{{ jklYear($rDate) }}</span></div>
        <div class="flex flex-col justify-center"><span class="font-serif text-lg">{{ jklMonthName($rDate) }}</span></div>
      </div>
    </div>

    <div class="hero-stagger absolute bottom-[12%] z-10 opacity-0">
      <p class="text-[9px] tracking-[0.4em] text-klasik-gold/70 uppercase">Geser ke bawah</p>
    </div>
  </section>

  <!-- ================= 4. KARTU JUDUL GUNUNGAN ================= -->
  <section id="sec-home" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden batik-bg" style="background-color:#31220f">
    <div class="pointer-events-none absolute left-[-7%] top-0 bottom-0 flex items-center z-20">
      <img src="{{ $ASSET }}1225-ASSET-JAWA-WAYANG.png" class="gold-tint orn w-[30vw] max-w-[128px] opacity-65 scale-x-[-1]" data-speed="1.3" alt="" />
    </div>
    <div class="pointer-events-none absolute right-[-7%] top-0 bottom-0 flex items-center z-20">
      <img src="{{ $ASSET }}1225-ASSET-JAWA-WAYANG.png" class="gold-tint orn w-[30vw] max-w-[128px] opacity-65" data-speed="1.3" alt="" />
    </div>

    <div class="anim-zoom relative z-10 card-klasik w-full max-w-[330px] px-6 pt-10 pb-8">
      <img src="{{ asset('images/gunungan-gold.png') }}" alt="Gunungan" class="anim-zoom w-[52%] max-w-[170px] h-auto mx-auto mb-4 drop-shadow-[0_10px_25px_rgba(212,175,55,0.4)]" />
      <h2 class="anim-zoom font-script text-[2.4rem] leading-tight text-gold-grad">{{ $groomNick }} &amp; {{ $brideNick }}</h2>
      <div class="hairline w-28 mx-auto my-4"></div>
      <p class="anim-fade font-serif text-lg text-klasik-cream">{{ jklDate($rDate) }}</p>
    </div>
  </section>

  <!-- ================= 5. QUOTES ================= -->
  <section class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden" style="background-color:#241811">
    <div class="pointer-events-none absolute left-[-7%] top-0 bottom-0 flex items-center z-20">
      <img src="{{ $ASSET }}1225-ASSET-JAWA-WAYANG.png" class="gold-tint orn w-[30vw] max-w-[128px] opacity-65 scale-x-[-1]" data-speed="1.3" alt="" />
    </div>
    <div class="pointer-events-none absolute right-[-7%] top-0 bottom-0 flex items-center z-20">
      <img src="{{ $ASSET }}1225-ASSET-JAWA-WAYANG.png" class="gold-tint orn w-[30vw] max-w-[128px] opacity-65" data-speed="1.3" alt="" />
    </div>

    <div class="anim-zoom relative z-10 w-full max-w-[340px] card-klasik px-7 py-9">
      <img src="{{ $ASSET }}1225-GUNUNGAN-JAWA-576x1024.png" class="gold-tint anim-zoom w-[22%] max-w-[76px] h-auto mx-auto mb-4 opacity-90" alt="Gunungan" />
      <p class="anim-fade font-serif text-[16px] leading-[1.9] text-klasik-cream/90 italic">
        &ldquo;Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang.&rdquo;
      </p>
      <div class="hairline w-24 mx-auto my-4"></div>
      <p class="anim-fade text-[11px] font-semibold tracking-[0.25em] uppercase text-klasik-gold">QS. Ar-R&umacr;m : 21</p>
    </div>
  </section>

  <!-- ================= 6. MEMPELAI ================= -->
  <section id="sec-profil" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden batik-bg" style="background-color:#2a1c11">

    <p class="anim-fade text-[10px] uppercase tracking-[0.42em] text-klasik-gold mb-2 relative z-10">Assalamu&rsquo;alaikum Warahmatullaahi Wabarakaatuh</p>
    <h2 class="anim-zoom font-script text-[2.2rem] text-gold-grad mb-2 relative z-10">Mempelai</h2>
    <p class="anim-fade font-serif italic text-klasik-cream/70 text-[15px] leading-relaxed mb-10 max-w-[300px] relative z-10">Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan. Ya Allah semoga ridho-Mu tercurah mengiringi pernikahan kami.</p>

    <!-- Mempelai wanita -->
    <div class="anim-zoom relative z-10 w-full max-w-[300px] card-klasik px-6 pt-8 pb-7 mb-8">
      <div class="relative w-40 h-52 mx-auto mb-5" style="border-radius:9999px 9999px 1rem 1rem; overflow:hidden; border:2px solid rgba(212,175,55,.6)">
        @if(jklGet('bride_photo'))
        <img src="{{ jklGet('bride_photo') }}" class="w-full h-full object-cover" alt="{{ $brideFull }}" />
        @else
        <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(160deg,#4a3018,#2c1f13)">
          <img src="{{ $ASSET }}1225-ASSET-JAWA-WAYANG.png" class="gold-tint w-20 h-auto opacity-70" alt="Mempelai Wanita" />
        </div>
        @endif
      </div>
      <img src="{{ $ASSET }}ASSET-JAWA-GUNUNGAN.png" class="gold-tint w-16 h-auto mx-auto -mt-9 mb-2 relative" alt="" />
      <h3 class="font-script text-[2rem] text-gold-grad leading-tight">{{ $brideNick }}</h3>
      <p class="text-[11px] text-klasik-cream/60 mt-2">{{ jklGet('bride_child_of','Putri') }} dari</p>
      <p class="text-[13px] font-medium text-klasik-lightgold mt-0.5">{{ jklGet('bride_parents','') }}</p>
      @if(jklGet('bride_instagram'))
      <a href="https://instagram.com/{{ ltrim(jklGet('bride_instagram'),'@') }}" target="_blank" rel="noopener" class="btn-outline inline-block mt-4 text-[10px] tracking-[0.2em] px-5 py-2 rounded-full">&#64;{{ ltrim(jklGet('bride_instagram'),'@') }}</a>
      @endif
    </div>

    <div class="anim-zoom font-script text-3xl text-gold-grad my-2 relative z-10">&amp;</div>

    <!-- Mempelai pria -->
    <div class="anim-zoom relative z-10 w-full max-w-[300px] card-klasik px-6 pt-8 pb-7 mt-8">
      <div class="relative w-40 h-52 mx-auto mb-5" style="border-radius:9999px 9999px 1rem 1rem; overflow:hidden; border:2px solid rgba(212,175,55,.6)">
        @if(jklGet('groom_photo'))
        <img src="{{ jklGet('groom_photo') }}" class="w-full h-full object-cover" alt="{{ $groomFull }}" />
        @else
        <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(160deg,#4a3018,#2c1f13)">
          <img src="{{ $ASSET }}1225-ASSET-JAWA-WAYANG-1.png" class="gold-tint w-20 h-auto opacity-70" alt="Mempelai Pria" />
        </div>
        @endif
      </div>
      <img src="{{ $ASSET }}ASSET-JAWA-GUNUNGAN.png" class="gold-tint w-16 h-auto mx-auto -mt-9 mb-2 relative" alt="" />
      <h3 class="font-script text-[2rem] text-gold-grad leading-tight">{{ $groomNick }}</h3>
      <p class="text-[11px] text-klasik-cream/60 mt-2">{{ jklGet('groom_child_of','Putra') }} dari</p>
      <p class="text-[13px] font-medium text-klasik-lightgold mt-0.5">{{ jklGet('groom_parents','') }}</p>
      @if(jklGet('groom_instagram'))
      <a href="https://instagram.com/{{ ltrim(jklGet('groom_instagram'),'@') }}" target="_blank" rel="noopener" class="btn-outline inline-block mt-4 text-[10px] tracking-[0.2em] px-5 py-2 rounded-full">&#64;{{ ltrim(jklGet('groom_instagram'),'@') }}</a>
      @endif
    </div>
  </section>

  <!-- ================= 7. COUNTDOWN ================= -->
  <section id="sec-acara" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden" style="background-color:#241811">

    <img src="{{ asset('images/gunungan-gold.png') }}" class="orn anim-zoom relative z-10 w-[30%] max-w-[120px] h-auto mb-4" data-speed="0.8" alt="Gunungan" />
    <h2 class="anim-zoom font-script text-[2.2rem] text-gold-grad mb-8 relative z-10">Counting The Days</h2>
    <div class="anim-zoom relative z-10 grid grid-cols-4 gap-2 w-full max-w-[340px] mb-8">
      <div class="card-klasik py-4"><div class="font-serif text-[1.6rem] font-semibold text-gold-grad" id="cd-d">00</div><div class="text-[9px] tracking-[0.25em] text-klasik-cream/60 mt-1">HARI</div></div>
      <div class="card-klasik py-4"><div class="font-serif text-[1.6rem] font-semibold text-gold-grad" id="cd-h">00</div><div class="text-[9px] tracking-[0.25em] text-klasik-cream/60 mt-1">JAM</div></div>
      <div class="card-klasik py-4"><div class="font-serif text-[1.6rem] font-semibold text-gold-grad" id="cd-m">00</div><div class="text-[9px] tracking-[0.25em] text-klasik-cream/60 mt-1">MENIT</div></div>
      <div class="card-klasik py-4"><div class="font-serif text-[1.6rem] font-semibold text-gold-grad" id="cd-s">00</div><div class="text-[9px] tracking-[0.25em] text-klasik-cream/60 mt-1">DETIK</div></div>
    </div>
    <a href="{{ $gcalUrl }}" target="_blank" rel="noopener" class="anim-fade btn-outline relative z-10 text-[10px] tracking-[0.25em] px-7 py-3 rounded-full mb-4">+ SIMPAN KE GOOGLE CALENDAR</a>

    <!-- Akad -->
    <div class="anim-zoom relative z-10 w-full max-w-[340px] card-klasik px-6 py-8 mt-8">
      <h3 class="font-script text-[1.9rem] text-gold-grad">Akad Nikah</h3>
      <div class="hairline w-24 mx-auto my-4"></div>
      <div class="flex items-stretch justify-center gap-3 text-klasik-cream mb-4">
        <div class="flex flex-col justify-center"><span class="font-serif">{{ jklDayName($wDate) }}</span></div>
        <div class="flex flex-col justify-center border-x border-klasik-gold/50 px-3"><span class="font-serif text-3xl font-semibold text-gold-grad leading-none">{{ jklDayNum($wDate) }}</span><span class="text-[10px] tracking-[0.2em] text-klasik-gold mt-1">{{ jklYear($wDate) }}</span></div>
        <div class="flex flex-col justify-center"><span class="font-serif">{{ jklMonthName($wDate) }}</span></div>
      </div>
      <p class="font-serif text-lg text-klasik-lightgold mb-4">{{ $wTime }} &ndash; {{ $wTimeE }} {{ $tzLabel }}</p>
      <div class="hairline w-24 mx-auto my-4"></div>
      <p class="text-[11px] tracking-[0.25em] uppercase text-klasik-gold/80 mb-1">Lokasi Acara</p>
      <p class="font-semibold text-klasik-cream">{{ jklGet('wedding_location','Kediaman Mempelai') }}</p>
      @if(!$sameLoc && jklGet('wedding_map_link'))
      <a href="{{ jklGet('wedding_map_link') }}" target="_blank" rel="noopener" class="btn-gold inline-flex items-center gap-2 mt-5 text-[11px] font-semibold tracking-[0.2em] px-8 py-3 rounded-full">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
        LIHAT LOKASI
      </a>
      @endif
    </div>

    <!-- Resepsi -->
    <div class="anim-zoom relative z-10 w-full max-w-[340px] card-klasik px-6 py-8 mt-6">
      <h3 class="font-script text-[1.9rem] text-gold-grad">Resepsi</h3>
      <div class="hairline w-24 mx-auto my-4"></div>
      <div class="flex items-stretch justify-center gap-3 text-klasik-cream mb-4">
        <div class="flex flex-col justify-center"><span class="font-serif">{{ jklDayName($rDate) }}</span></div>
        <div class="flex flex-col justify-center border-x border-klasik-gold/50 px-3"><span class="font-serif text-3xl font-semibold text-gold-grad leading-none">{{ jklDayNum($rDate) }}</span><span class="text-[10px] tracking-[0.2em] text-klasik-gold mt-1">{{ jklYear($rDate) }}</span></div>
        <div class="flex flex-col justify-center"><span class="font-serif">{{ jklMonthName($rDate) }}</span></div>
      </div>
      <p class="font-serif text-lg text-klasik-lightgold mb-4">{{ $rTimeS }} &ndash; {{ $rTimeE }} {{ $tzLabel }}</p>
      <div class="hairline w-24 mx-auto my-4"></div>
      <p class="text-[11px] tracking-[0.25em] uppercase text-klasik-gold/80 mb-1">Lokasi Acara</p>
      <p class="font-semibold text-klasik-cream">{{ jklGet('reception_location', jklGet('wedding_location','Kediaman Mempelai')) }}</p>
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
        @if(jklGet('live_stream_link'))
        <a href="{{ jklGet('live_stream_link') }}" target="_blank" rel="noopener" class="btn-outline text-[10px] tracking-[0.2em] px-7 py-3 rounded-full">LIVE STREAMING</a>
        @endif
      </div>
    </div>
  </section>

  <!-- ================= 8. GALERI ================= -->
  <section id="sec-galeri" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden batik-bg" style="background-color:#2a1c11">
    <div class="pointer-events-none absolute left-[-7%] top-0 bottom-0 flex items-center z-20">
      <img src="{{ $ASSET }}1225-ASSET-JAWA-WAYANG.png" class="gold-tint orn w-[30vw] max-w-[128px] opacity-65 scale-x-[-1]" data-speed="1.3" alt="" />
    </div>
    <div class="pointer-events-none absolute right-[-7%] top-0 bottom-0 flex items-center z-20">
      <img src="{{ $ASSET }}1225-ASSET-JAWA-WAYANG.png" class="gold-tint orn w-[30vw] max-w-[128px] opacity-65" data-speed="1.3" alt="" />
    </div>

    <img src="{{ asset('images/gunungan-gold.png') }}" class="orn anim-zoom relative z-10 w-[26%] max-w-[104px] h-auto mb-4" data-speed="0.8" alt="Gunungan" />
    <h2 class="anim-zoom font-script text-[2.2rem] text-gold-grad mb-2 relative z-10">Memorable Moment</h2>
    <p class="anim-fade text-[11px] tracking-[0.3em] uppercase text-klasik-gold/80 mb-8 relative z-10">Galeri kenangan kami</p>
    @if(!empty($gallery) && count($gallery))
    <div class="relative z-10 w-full max-w-[340px] grid grid-cols-2 gap-3">
      @foreach(array_slice($gallery, 0, 8) as $i => $img)
      <div class="anim-zoom gal-item relative overflow-hidden rounded-xl border border-klasik-gold/40 cursor-pointer {{ $i % 3 === 0 ? 'row-span-2' : '' }}" data-full="{{ $img }}" style="box-shadow:inset 0 0 0 3px rgba(212,175,55,.12)">
        <img src="{{ $img }}" alt="Galeri {{ $i+1 }}" loading="lazy" class="w-full {{ $i % 3 === 0 ? 'h-64' : 'h-[7.5rem]' }} object-cover transition-transform duration-700 hover:scale-110" />
      </div>
      @endforeach
    </div>
    @else
    <p class="anim-fade font-serif italic text-klasik-cream/60 relative z-10">Galeri kenangan akan segera hadir.</p>
    @endif
  </section>

  <!-- ================= 9. KISAH CINTA ================= -->
  <section class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden" style="background-color:#241811">

    <img src="{{ $ASSET }}ASSET-JAWA-GUNUNGAN.png" class="gold-tint orn anim-zoom relative z-10 w-[24%] max-w-[96px] h-auto mb-4" data-speed="0.8" alt="Gunungan" />
    <h2 class="anim-zoom font-script text-[2.2rem] text-gold-grad mb-2 relative z-10">Our Story</h2>
    <p class="anim-fade text-[11px] tracking-[0.3em] uppercase text-klasik-gold/80 mb-10 relative z-10">Perjalanan cinta kami</p>
    @if(!empty($stories) && count($stories))
    <div class="relative z-10 w-full max-w-[320px] pl-8 pb-10 text-left">
      <div class="absolute left-[9px] top-2 bottom-2 w-px bg-gradient-to-b from-klasik-gold/60 via-klasik-gold/30 to-transparent"></div>
      @foreach($stories as $st)
      <div class="anim-fade relative mb-8">
        <div class="absolute -left-8 top-1 w-[19px] h-[19px] rounded-full border-2 border-klasik-gold bg-klasik-deep flex items-center justify-center">
          <div class="w-1.5 h-1.5 rounded-full bg-klasik-gold"></div>
        </div>
        @if(!empty($st->tahun))
        <p class="font-script text-2xl text-gold-grad mb-1">{{ $st->tahun }}</p>
        @endif
        <h4 class="font-serif font-semibold text-klasik-lightgold tracking-wide mb-1">{{ $st->judul }}</h4>
        <p class="text-[13px] text-klasik-cream/70 leading-relaxed">{{ $st->isi }}</p>
      </div>
      @endforeach
    </div>
    @endif
  </section>

  <!-- ================= 10. WEDDING GIFT ================= -->
  <section class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden batik-bg" style="background-color:#31220f">
    <div class="pointer-events-none absolute left-[-7%] top-0 bottom-0 flex items-center z-20">
      <img src="{{ $ASSET }}1225-ASSET-JAWA-WAYANG.png" class="gold-tint orn w-[30vw] max-w-[128px] opacity-65 scale-x-[-1]" data-speed="1.3" alt="" />
    </div>
    <div class="pointer-events-none absolute right-[-7%] top-0 bottom-0 flex items-center z-20">
      <img src="{{ $ASSET }}1225-ASSET-JAWA-WAYANG.png" class="gold-tint orn w-[30vw] max-w-[128px] opacity-65" data-speed="1.3" alt="" />
    </div>

    <img src="{{ asset('images/gunungan-gold.png') }}" class="orn anim-zoom relative z-10 w-[26%] max-w-[104px] h-auto mb-4" data-speed="0.8" alt="Gunungan" />
    <h2 class="anim-zoom font-script text-[2.2rem] text-gold-grad mb-3 relative z-10">Wedding Gift</h2>
    <p class="anim-fade font-serif italic text-klasik-cream/70 text-[15px] leading-relaxed mb-8 max-w-[300px] relative z-10">Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Dan jika memberi adalah ungkapan tanda kasih Anda, Anda dapat memberi kado secara cashless.</p>
    @if(!empty($giftRecipient))
    <p class="anim-fade text-xs text-klasik-cream/60 mb-6 relative z-10">Penerima: <span class="font-semibold text-klasik-lightgold">{{ $giftRecipient }}</span></p>
    @endif
    @if(!empty($accounts))
    <div class="relative z-10 w-full max-w-[340px] space-y-4">
      @foreach($accounts as $i => $a)
      <div class="anim-zoom rounded-2xl p-[1.5px]" style="background:linear-gradient(135deg,#8a6a1f,#d4af37,#f6e27a,#d4af37,#8a6a1f)">
        <div class="rounded-2xl p-5 text-left" style="background:#241811">
          <div class="flex items-center justify-between mb-3">
            <p class="text-klasik-gold tracking-[0.25em] text-[11px] font-semibold">{{ $a['bank'] }}</p>
            @if(!empty($a['logo']))<img src="{{ $a['logo'] }}" alt="Logo bank" class="h-7 max-w-[100px] object-contain bg-klasik-cream rounded px-1" />@endif
          </div>
          <p class="font-serif text-klasik-lightgold text-xl tracking-[0.12em]" id="accNo{{ $i }}">{{ $a['no'] }}</p>
          <p class="text-klasik-cream/60 text-xs mt-1 mb-4">a.n. {{ $a['an'] }}</p>
          <button class="btn-gold copy-btn w-full text-[10px] font-semibold tracking-[0.25em] py-3 rounded-xl" data-i="{{ $i }}">SALIN NOMOR</button>
        </div>
      </div>
      @endforeach
    </div>
    @else
    <p class="anim-fade font-serif italic text-klasik-cream/60 relative z-10">Informasi amplop digital akan segera hadir.</p>
    @endif
    @if(jklGet('gift_address'))
    <div class="anim-zoom relative z-10 w-full max-w-[340px] card-klasik p-5 mt-5 text-left">
      <p class="text-klasik-gold tracking-[0.25em] text-[10px] font-semibold mb-2">KIRIM HADIAH FISIK</p>
      <p class="text-klasik-cream/80 text-sm leading-relaxed">{{ jklGet('gift_address') }}</p>
      @if(jklGet('gift_maps_link'))
      <a href="{{ jklGet('gift_maps_link') }}" target="_blank" rel="noopener" class="btn-outline inline-block mt-3 text-[10px] tracking-[0.25em] px-5 py-2.5 rounded-full">BUKA PETA</a>
      @endif
    </div>
    @endif
  </section>

  <!-- ================= 11. RSVP & UCAPAN ================= -->
  <section id="sec-ucapan" class="scroll-section relative py-20 px-6 flex flex-col items-center text-center overflow-hidden" style="background-color:#241811">

    <img src="{{ $ASSET }}ASSET-JAWA-GUNUNGAN.png" class="gold-tint orn anim-zoom relative z-10 w-[24%] max-w-[96px] h-auto mb-4" data-speed="0.8" alt="Gunungan" />
    <h2 class="anim-zoom font-script text-[2.2rem] text-gold-grad mb-3 relative z-10">RSVP</h2>
    <p class="anim-fade font-serif italic text-klasik-cream/70 text-[14px] leading-relaxed mb-8 max-w-[300px] relative z-10">Tanpa mengurangi rasa hormat kami, mohon konfirmasi kehadiran Anda terlebih dahulu.</p>
    <form id="rsvpForm" class="anim-zoom relative z-10 w-full max-w-[340px] card-klasik p-5 text-left space-y-3 mb-10">
      <input type="text" name="nama" required placeholder="Nama lengkap" class="field-klasik" />
      <select name="kehadiran" class="field-klasik" required>
        <option value="">Konfirmasi kehadiran</option>
        <option value="Hadir">Hadir, Insya Allah</option>
        <option value="Tidak Hadir">Berhalangan</option>
      </select>
      <input type="number" name="jumlah" min="1" max="10" value="1" class="field-klasik" />
      <button type="submit" class="btn-gold w-full text-[11px] font-semibold tracking-[0.25em] py-3.5 rounded-xl">KIRIM RSVP</button>
    </form>

    <h2 class="anim-zoom font-script text-[2.2rem] text-gold-grad mb-3 relative z-10">Best Wishes</h2>
    <p class="anim-fade font-serif italic text-klasik-cream/70 text-[14px] mb-8 relative z-10">Kirimkan ucapan &amp; do&rsquo;a restu.</p>
    <form id="wishForm" class="anim-zoom relative z-10 w-full max-w-[340px] card-klasik p-5 text-left space-y-3 mb-6">
      <input type="text" name="nama" required placeholder="Nama Anda" class="field-klasik" />
      <textarea name="pesan" required rows="3" placeholder="Tulis ucapan & doa restu..." class="field-klasik"></textarea>
      <button type="submit" class="btn-gold w-full text-[11px] font-semibold tracking-[0.25em] py-3.5 rounded-xl">KIRIM UCAPAN</button>
    </form>
    <div id="wishList" class="anim-fade relative z-10 w-full max-w-[340px] space-y-3 max-h-80 overflow-y-auto pr-1 text-left"></div>
  </section>

  <!-- ================= 12. FOOTER ================= -->
  <footer class="relative py-16 px-6 flex flex-col items-center text-center overflow-hidden batik-bg" style="background-color:#1e130b">
    <img src="{{ asset('images/gunungan-gold.png') }}" class="w-[24%] max-w-[96px] h-auto mb-4 opacity-90" alt="Gunungan" />
    <p class="font-serif italic text-klasik-cream/70 text-[15px] leading-relaxed max-w-[300px] mb-4">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan do&rsquo;a restu kepada kedua mempelai.</p>
    <h2 class="font-script text-[2rem] text-gold-grad mb-2">{{ $groomNick }} &amp; {{ $brideNick }}</h2>
    <p class="text-[10px] tracking-[0.3em] uppercase text-klasik-gold/60">Terima kasih atas do&rsquo;a &amp; kehadirannya</p>
    <div class="h-20"></div>
  </footer>
</main>

<!-- musik melayang -->
<button id="musicFloat" aria-label="Putar / jeda musik" hidden>
  <svg class="note w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55A4 4 0 1 0 14 17V7h4V3h-6z"/></svg>
  <span class="bars"><span></span><span></span><span></span></span>
</button>
<div id="toast"></div>

<!-- navigasi bawah -->
<nav id="bottomNav" hidden>
  <a href="#sec-home" data-sec="sec-home" class="active">
    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6h-6v6H4a1 1 0 0 1-1-1z"/></svg>
    Home
  </a>
  <a href="#sec-profil" data-sec="sec-profil">
    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7.5-4.7-9.5-9C1 8.5 3 5 6.5 5c2 0 3.5 1 4.5 2.5C12 6 13.5 5 15.5 5 19 5 21 8.5 20.5 12c-2 4.3-8.5 9-8.5 9z"/></svg>
    Profil
  </a>
  <a href="#sec-acara" data-sec="sec-acara">
    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path stroke-linecap="round" d="M8 3v4M16 3v4M3 10h18"/></svg>
    Acara
  </a>
  <a href="#sec-galeri" data-sec="sec-galeri">
    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.6"/><path stroke-linecap="round" stroke-linejoin="round" d="m5 19 5.5-5.5 3 3L19 11l2 2"/></svg>
    Galeri
  </a>
  <a href="#sec-ucapan" data-sec="sec-ucapan">
    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a8 8 0 0 1-8 8H4l2-3a8 8 0 1 1 15-5z"/></svg>
    Ucapan
  </a>
</nav>

<!-- lightbox -->
<div id="lightbox" class="fixed inset-0 z-[80] bg-black/95 hidden items-center justify-center p-4">
  <button id="lbClose" class="absolute top-4 right-4 text-klasik-cream text-3xl leading-none px-2" aria-label="Tutup">&times;</button>
  <button id="lbPrev" class="absolute left-2 text-klasik-cream text-4xl px-2" aria-label="Sebelumnya">&#8249;</button>
  <img id="lbImg" class="max-w-full max-h-[80vh] rounded-xl border border-klasik-gold/40 object-contain" alt="Galeri" />
  <button id="lbNext" class="absolute right-2 text-klasik-cream text-4xl px-2" aria-label="Berikutnya">&#8250;</button>
  <div id="lbCount" class="absolute bottom-5 text-klasik-cream/80 text-sm tracking-widest"></div>
</div>

<script>
"use strict";
/* ================= DATA ================= */
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
function timeAgo(s){ const d=new Date(String(s).replace(" ","T")+":00"); if(isNaN(d)) return "";
  const n=(Date.now()-d.getTime())/1000; if(n<60) return "Baru saja";
  if(n<3600) return Math.floor(n/60)+" menit yang lalu";
  if(n<86400) return Math.floor(n/3600)+" jam yang lalu";
  return Math.floor(n/86400)+" hari yang lalu"; }

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
    document.getElementById("bottomNav").hidden=false;
    initSectionScrollAnimations();
    initParallax();
    initNavSpy();
  }});
  tl.to(["#gate-header","#gate-center","#gate-footer"],{y:-14,opacity:0,duration:.5,stagger:.06,ease:"power2.in"},0)
    .to("#cover-gate",{opacity:0,duration:.6,ease:"power2.inOut"},.45)
    .to(".hero-stagger",{y:0,opacity:1,duration:1.1,ease:"expo.out",stagger:.12},.55);
  startMusic(true);
});

/* ---------- 5. NAVIGASI BAWAH (scroll-spy) ---------- */
function initNavSpy(){
  const links=[...document.querySelectorAll("#bottomNav a")];
  const secs=links.map(a=>document.getElementById(a.dataset.sec)).filter(Boolean);
  function spy(){
    let cur=secs[0];
    for(const s of secs){ if(s.getBoundingClientRect().top<=160) cur=s; }
    links.forEach(a=>a.classList.toggle("active", a.dataset.sec===(cur&&cur.id)));
  }
  addEventListener("scroll",spy,{passive:true}); spy();
}

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
async function startMusic(auto){
  const url=await resolveMusicUrl(); if(!url) return;
  if(!audioEl){ audioEl=new Audio(url); audioEl.loop=true; }
  try{ await audioEl.play(); musicOn=true;
    document.getElementById("musicFloat").classList.add("playing"); }
  catch(e){ if(!auto) toast("Musik tidak dapat diputar"); }
}
document.getElementById("musicFloat").addEventListener("click", async function(){
  if(musicOn && audioEl){ audioEl.pause(); musicOn=false; this.classList.remove("playing"); return; }
  startMusic(false);
});

/* ---------- 8. RSVP ---------- */
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

/* ---------- 9. UCAPAN ---------- */
async function loadWishes(){
  const box=document.getElementById("wishList");
  try{
    const r=await fetch("/api/ucapan?inv_id="+JWA.invId); const j=await r.json();
    const list=Array.isArray(j)?j:(j.data||[]);
    if(!list.length){ box.innerHTML='<p class="text-center text-klasik-cream/50 text-sm font-serif italic">Belum ada ucapan. Jadilah yang pertama!</p>'; return; }
    box.innerHTML=list.map(w=>
      '<div class="card-klasik p-4"><p class="font-semibold text-klasik-lightgold text-sm">'+esc(w.nama)+'</p>'+
      '<p class="text-klasik-cream/70 text-[13px] mt-1 leading-relaxed">'+esc(w.pesan)+'</p>'+
      '<p class="text-klasik-gold/60 text-[10px] mt-2 tracking-wider">'+esc(timeAgo(w.created_at))+'</p></div>'
    ).join("");
  }catch(e){ box.innerHTML='<p class="text-center text-klasik-cream/50 text-sm">Gagal memuat ucapan.</p>'; }
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

/* ---------- 10. SALIN REKENING ---------- */
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

/* ---------- 11. LIGHTBOX ---------- */
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
</body>
</html>
