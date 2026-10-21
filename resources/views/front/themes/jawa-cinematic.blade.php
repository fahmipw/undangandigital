<?php
/* ============================================================
   TEMA: JAWA CINEMATIC — mewah & sinematik
   Tailwind + GSAP ScrollTrigger + Three.js gold dust
   Dipilih via settings: theme = jawa-cinematic
   ============================================================ */
$GLOBALS['jwc_settings'] = $settings ?? [];
if (!function_exists('jwcGet')) {
  function jwcGet($key, $default = '') {
    $s = $GLOBALS['jwc_settings'];
    if (!isset($s[$key]) || trim((string)$s[$key]) === '') return $default;
    return $s[$key];
  }
}
if (!function_exists('jwcDate')) {
  function jwcDate($dateStr) {
    if (!$dateStr) return '';
    $days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($dateStr);
    if (!$ts) return $dateStr;
    return $days[date('w',$ts)].', '.date('j',$ts).' '.$months[date('n',$ts)].' '.date('Y',$ts);
  }
}
if (!function_exists('jwcTime')) {
  function jwcTime($t) {
    $t = trim((string)$t); if ($t === '') return '08:00';
    $t = str_replace('.', ':', $t);
    return preg_match('/^\d{1,2}(:\d{2})?$/', $t) ? (strpos($t, ':') === false ? $t.':00' : $t) : '08:00';
  }
}
$groomNick = jwcGet('groom_nickname') ?: jwcGet('groom_name', 'Mempelai Pria');
$brideNick = jwcGet('bride_nickname') ?: jwcGet('bride_name', 'Mempelai Wanita');
$groomFull = jwcGet('groom_name', $invitation->groom_name ?? 'Mempelai Pria');
$brideFull = jwcGet('bride_name', $invitation->bride_name ?? 'Mempelai Wanita');
$toName = (!empty($guestName) && $guestName !== 'Bapak/Ibu/Saudara/i') ? $guestName : request('to', 'Tamu Undangan');
$wDate  = jwcGet('wedding_date', date('Y-m-d'));
$wTime  = jwcTime(jwcGet('wedding_time_start', '08:00'));
$wTimeE = jwcTime(jwcGet('wedding_time_end', '10:00'));
$rDate  = jwcGet('reception_date', $wDate);
$rTimeS = jwcTime(jwcGet('reception_time_start', '11:00'));
$rTimeE = jwcTime(jwcGet('reception_time_end', '13:00'));
$tzLabel = jwcGet('wedding_timezone', 'WIB');
$sameLoc = (jwcGet('event_location_mode', 'same') === 'same');
$mainMapLink = trim((string)jwcGet('wedding_map_link')) ?: trim((string)jwcGet('reception_map_link'));
$giftRecipient = jwcGet('gift_recipient_name', jwcGet('gift_owner', ''));
$countdownISO = $rDate.'T'.$rTimeS.':00';
$accounts = [];
$__gbanks = json_decode(jwcGet('gift_banks', ''), true) ?: [];
foreach ($__gbanks as $__b) {
  if (empty($__b['account'])) continue;
  $accounts[] = ['bank'=>($__b['name'] ?? 'Bank'),'no'=>$__b['account'],'an'=>($__b['owner'] ?? ''),'logo'=>($__b['logo'] ?? '')];
}
if (!$accounts) {
  if (jwcGet('gift_account')) $accounts[] = ['bank'=>jwcGet('gift_bank','Bank'),'no'=>jwcGet('gift_account'),'an'=>jwcGet('gift_owner',''),'logo'=>jwcGet('gift_bank_logo','')];
  if (jwcGet('gift_account2')) $accounts[] = ['bank'=>jwcGet('gift_bank2','Bank'),'no'=>jwcGet('gift_account2'),'an'=>jwcGet('gift_owner2',''),'logo'=>jwcGet('gift_bank_logo','')];
}
$qrisEnabled = jwcGet('gift_qris_enabled', '') === '1' && trim((string)jwcGet('gift_qris_image', '')) !== '';
$qrisImage = jwcGet('gift_qris_image', '');
$qrisName = jwcGet('gift_qris_name', '');
$musicUrl = null;
if (!empty($musicRecord) && !empty($musicRecord->file_path)) {
  $mp = $musicRecord->file_path;
  $musicUrl = (str_starts_with($mp,'http') || str_starts_with($mp,'/')) ? $mp : asset('storage/'.ltrim($mp,'/'));
}
$invId = $inv_id ?? 0;
$gcalDates = str_replace('-','',$rDate).'T'.str_replace(':','',$rTimeS).'00/'.str_replace('-','',$rDate).'T'.str_replace(':','',$rTimeE).'00';
$gcalUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text='.urlencode('Pernikahan '.$brideNick.' & '.$groomNick).'&dates='.$gcalDates.'&details='.urlencode(jwcGet('reception_location', jwcGet('wedding_location','')));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $brideNick }} &amp; {{ $groomNick }} — Undangan Pernikahan</title>
<meta property="og:type" content="website">
<meta property="og:title" content="Undangan Pernikahan {{ $brideNick }} & {{ $groomNick }}">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: { extend: {
    colors: { navy:'#0A192F', ivory:'#F8F5EE', gold:'#D4AF37', golddim:'#A8842A',
      terracotta:'#C67B5C', mocha:'#4A3728', mochadark:'#2E2119', sand:'#EDE6D6' },
    fontFamily: {
      serif:['"Cormorant Garamond"','serif'],
      display:['"Playfair Display"','serif'],
      arabic:['Amiri','serif'],
      sans:['"Jost"','sans-serif']
    }
  }}
}
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Playfair+Display:wght@500;600;700&family=Amiri:wght@400;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/0.160.0/three.min.js"></script>
<style>
  html{scroll-behavior:auto}
  body{background:#0A192F;overflow-x:hidden}
  body.locked{overflow:hidden;height:100vh}
  #webgl{position:fixed;inset:0;z-index:1;pointer-events:none}
  .content-z{position:relative;z-index:10}
  .gold-text{background:linear-gradient(105deg,#8a6a1f 0%,#D4AF37 25%,#f6e27a 50%,#D4AF37 75%,#8a6a1f 100%);
    background-size:200% auto;-webkit-background-clip:text;background-clip:text;color:transparent;
    animation:sheen 6s linear infinite}
  @keyframes sheen{to{background-position:200% center}}
  .arch{border-radius:999px 999px 18px 18px}
  .arch-frame{border:1px solid rgba(212,175,55,.65);outline:1px solid rgba(212,175,55,.3);outline-offset:7px}
  .hairline{height:1px;background:linear-gradient(90deg,transparent,#D4AF37,transparent)}
  .truntum{display:flex;justify-content:center;gap:10px;color:#D4AF37;font-size:15px;letter-spacing:2px}
  .card-ivory{background:rgba(248,245,238,.94);backdrop-filter:blur(6px)}
  .btn-gold{background:linear-gradient(135deg,#b8942a,#D4AF37 55%,#f0d878);color:#0A192F;
    box-shadow:0 8px 24px -8px rgba(212,175,55,.7);transition:transform .25s,box-shadow .25s}
  .btn-gold:hover{transform:translateY(-2px);box-shadow:0 14px 30px -8px rgba(212,175,55,.8)}
  .btn-ghost{border:1px solid rgba(212,175,55,.7);color:#D4AF37;transition:.25s}
  .btn-ghost:hover{background:rgba(212,175,55,.12)}
  /* vinyl */
  #vinyl{position:fixed;right:14px;bottom:14px;z-index:60;width:62px;height:62px;border-radius:50%;
    background:radial-gradient(circle at 50% 50%,#D4AF37 0 9px,#111 10px,#1c1c1c 11px 30px);
    border:2px solid rgba(212,175,55,.8);box-shadow:0 6px 20px rgba(0,0,0,.5);cursor:pointer;
    display:flex;align-items:center;justify-content:center}
  #vinyl::before{content:"";position:absolute;inset:8px;border-radius:50%;
    border:1px dashed rgba(212,175,55,.4)}
  #vinyl.playing{animation:spin 3.2s linear infinite}
  @keyframes spin{to{transform:rotate(360deg)}}
  #vinyl .eq{position:absolute;right:-4px;top:-4px;width:18px;height:18px;border-radius:50%;
    background:#D4AF37;color:#0A192F;font-size:11px;display:none;align-items:center;justify-content:center}
  #vinyl.playing .eq{display:flex}
  /* toast */
  #toast{position:fixed;left:50%;bottom:92px;transform:translate(-50%,20px);z-index:70;
    background:#0A192F;color:#F8F5EE;border:1px solid #D4AF37;border-radius:999px;
    padding:10px 22px;font-size:14px;letter-spacing:.06em;opacity:0;transition:.3s;pointer-events:none;
    max-width:88vw;text-align:center}
  #toast.show{opacity:1;transform:translate(-50%,0)}
  .cd-cell{background:rgba(10,25,47,.55);border:1px solid rgba(212,175,55,.5);backdrop-filter:blur(4px)}
  input,select,textarea{color:#0A192F}
  input::placeholder,textarea::placeholder{color:#8a8578}
  .timeline-dot{box-shadow:0 0 0 5px rgba(212,175,55,.18)}
  #cover{transition:none}
  .float-slow{animation:floaty 7s ease-in-out infinite}
  @keyframes floaty{50%{transform:translateY(-14px)}}
  @media (prefers-reduced-motion:reduce){
    .gold-text,.float-slow,#vinyl.playing{animation:none}
  }
</style>
</head>
<body class="locked font-sans antialiased">
<canvas id="webgl"></canvas>

<!-- ============ COVER ============ -->
<div id="cover" class="fixed inset-0 z-50 flex items-center justify-center" style="background:radial-gradient(ellipse at 50% 35%,#12294d 0%,#0A192F 68%)">
  <div class="absolute top-0 left-0 w-40 h-40 opacity-70 pointer-events-none" data-cover-float>
    <svg viewBox="0 0 100 100" fill="none"><path d="M0 0 Q50 8 100 0 Q92 50 100 100 Q50 92 0 100 Q8 50 0 0Z" stroke="#D4AF37" stroke-width="1.4" opacity=".8"/><circle cx="50" cy="50" r="30" stroke="#D4AF37" stroke-width="1" opacity=".5"/><circle cx="50" cy="50" r="6" fill="#D4AF37" opacity=".8"/></svg>
  </div>
  <div class="absolute bottom-0 right-0 w-40 h-40 opacity-70 rotate-180 pointer-events-none" data-cover-float>
    <svg viewBox="0 0 100 100" fill="none"><path d="M0 0 Q50 8 100 0 Q92 50 100 100 Q50 92 0 100 Q8 50 0 0Z" stroke="#D4AF37" stroke-width="1.4" opacity=".8"/><circle cx="50" cy="50" r="30" stroke="#D4AF37" stroke-width="1" opacity=".5"/><circle cx="50" cy="50" r="6" fill="#D4AF37" opacity=".8"/></svg>
  </div>
  <div class="relative text-center px-8 max-w-md" id="coverInner">
    <p class="truntum mb-5" aria-hidden="true">✦ ✦ ✦</p>
    <p class="text-ivory/70 tracking-[.35em] text-xs mb-4">UNDANGAN PERNIKAHAN</p>
    <h1 class="font-display text-ivory text-4xl leading-tight mb-2">{{ $brideNick }} <span class="gold-text">&amp;</span> {{ $groomNick }}</h1>
    <div class="hairline w-40 mx-auto my-6"></div>
    <p class="text-ivory/60 text-sm tracking-widest mb-1">KEPADA YTH.</p>
    <p class="text-ivory/60 text-sm tracking-widest mb-1">BAPAK / IBU / SAUDARA/I</p>
    <p class="font-serif text-gold text-2xl italic mb-8">{{ $toName }}</p>
    <button id="openBtn" class="btn-gold font-semibold tracking-[.2em] text-sm px-10 py-4 rounded-full">BUKA UNDANGAN</button>
    <p class="text-ivory/40 text-xs mt-6 tracking-widest">Mohon maaf apabila ada kesalahan penulisan nama &amp; gelar</p>
  </div>
</div>

<div class="content-z max-w-md mx-auto" id="smooth-wrapper">

  <!-- ============ HERO ============ -->
  <section data-bg="#0A192F" class="relative min-h-[100svh] flex flex-col items-center justify-center text-center px-6 overflow-hidden">
    <div class="absolute -top-6 -left-8 w-56 opacity-60 pointer-events-none" data-speed="0.55" aria-hidden="true">
      <svg viewBox="0 0 120 160" fill="none"><g stroke="#D4AF37" stroke-width="1.2" opacity=".75"><path d="M10 150 Q60 120 30 60 Q20 30 45 10"/><path d="M30 130 Q70 110 50 70"/><circle cx="45" cy="10" r="4" fill="#D4AF37" opacity=".6"/><circle cx="60" cy="45" r="3" fill="#D4AF37" opacity=".5"/><circle cx="28" cy="90" r="3.5" fill="#D4AF37" opacity=".55"/></g></svg>
    </div>
    <div class="absolute -bottom-8 -right-8 w-56 opacity-60 pointer-events-none rotate-180" data-speed="0.7" aria-hidden="true">
      <svg viewBox="0 0 120 160" fill="none"><g stroke="#D4AF37" stroke-width="1.2" opacity=".75"><path d="M10 150 Q60 120 30 60 Q20 30 45 10"/><path d="M30 130 Q70 110 50 70"/><circle cx="45" cy="10" r="4" fill="#D4AF37" opacity=".6"/><circle cx="60" cy="45" r="3" fill="#D4AF37" opacity=".5"/><circle cx="28" cy="90" r="3.5" fill="#D4AF37" opacity=".55"/></g></svg>
    </div>
    <p class="reveal text-gold/80 tracking-[.4em] text-[11px] mb-5">ASSALAMU'ALAIKUM WR. WB.</p>
    <p class="reveal text-ivory/70 font-serif italic text-lg mb-3">Dengan penuh syukur, kami mengundang Bapak/Ibu/Saudara/i pada pernikahan</p>
    <h2 class="reveal font-display text-5xl leading-[1.15] mb-2"><span class="gold-text">{{ $brideNick }}</span></h2>
    <p class="reveal font-serif italic text-gold text-3xl my-2">&amp;</p>
    <h2 class="reveal font-display text-5xl leading-[1.15] mb-6"><span class="gold-text">{{ $groomNick }}</span></h2>
    <div class="reveal hairline w-48 my-2"></div>
    <p class="reveal text-ivory/80 tracking-[.25em] text-sm mt-4">{{ jwcDate($rDate) }}</p>
    <div class="reveal mt-10 text-gold/70 text-xs tracking-[.3em] float-slow">SCROLL<br><span class="text-xl">↓</span></div>
  </section>

  <!-- ============ AYAT SUCI ============ -->
  <section data-bg="#F8F5EE" class="relative px-6 py-24">
    <div class="absolute top-10 right-2 w-44 opacity-50 pointer-events-none" data-speed="1.35" aria-hidden="true">
      <svg viewBox="0 0 100 120" fill="none"><g fill="#C67B5C" opacity=".5"><ellipse cx="50" cy="30" rx="10" ry="22"/><ellipse cx="50" cy="90" rx="10" ry="22"/><ellipse cx="28" cy="60" rx="22" ry="10"/><ellipse cx="72" cy="60" rx="22" ry="10"/><circle cx="50" cy="60" r="9"/></g></svg>
    </div>
    <div class="reveal card-ivory rounded-2xl border border-gold/50 shadow-[0_20px_60px_-20px_rgba(10,25,47,.35)] px-7 py-10 text-center relative overflow-hidden">
      <div class="absolute inset-x-0 top-0 h-1.5" style="background:repeating-linear-gradient(90deg,#D4AF37 0 14px,transparent 14px 22px)"></div>
      <p class="truntum mb-4" aria-hidden="true">✦ ✦ ✦</p>
      <p class="reveal font-arabic text-[26px] leading-[2.1] text-mochadark mb-6" dir="rtl">وَمِنْ آيَاتِهِ أَنْ خَلَقَ لَكُم مِّنْ أَنفُسِكُمْ أَزْوَاجًا لِّتَسْكُنُوا إِلَيْهَا وَجَعَلَ بَيْنَكُم مَّوَدَّةً وَرَحْمَةً</p>
      <p class="reveal font-serif italic text-mochadark/90 text-lg leading-relaxed mb-4">"Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang."</p>
      <p class="reveal text-golddim tracking-[.3em] text-xs font-semibold">QS. AR-RŪM : 21</p>
      <div class="absolute inset-x-0 bottom-0 h-1.5" style="background:repeating-linear-gradient(90deg,#D4AF37 0 14px,transparent 14px 22px)"></div>
    </div>
  </section>

  <!-- ============ MEMPELAI ============ -->
  <section data-bg="#2E2119" class="relative px-6 py-24">
    <p class="reveal text-center text-gold/80 tracking-[.4em] text-[11px] mb-3">MEMPELAI</p>
    <h3 class="reveal text-center font-display text-ivory text-4xl mb-2">Dua Hati, <span class="gold-text">Satu Tujuan</span></h3>
    <p class="reveal text-center truntum my-5" aria-hidden="true">✦ ✦ ✦</p>

    <div class="reveal mt-8 text-center">
      <div class="arch arch-frame overflow-hidden w-56 h-72 mx-auto bg-navy/40">
        @if(jwcGet('bride_photo'))
        <img src="{{ jwcGet('bride_photo') }}" alt="{{ $brideFull }}" class="w-full h-full object-cover">
        @else
        <div class="w-full h-full flex items-center justify-center"><span class="font-display text-gold text-6xl">{{ mb_substr($brideNick,0,1) }}</span></div>
        @endif
      </div>
      <h4 class="font-display text-3xl text-ivory mt-6">{{ $brideFull }}</h4>
      <p class="text-gold tracking-[.25em] text-xs mt-2 mb-3">MEMPELAI WANITA</p>
      <p class="text-ivory/70 font-serif italic">{{ jwcGet('bride_parents', jwcGet('bride_child_of','')) }}</p>
      @if(jwcGet('bride_instagram'))
      <a href="https://instagram.com/{{ ltrim(jwcGet('bride_instagram'),'@') }}" target="_blank" rel="noopener" class="btn-ghost inline-block mt-4 text-xs tracking-[.2em] px-6 py-2.5 rounded-full">⌁ {{ '@'.ltrim(jwcGet('bride_instagram'),'@') }}</a>
      @endif
    </div>

    <div class="reveal text-center font-serif italic text-gold text-4xl my-10">&amp;</div>

    <div class="reveal text-center">
      <div class="arch arch-frame overflow-hidden w-56 h-72 mx-auto bg-navy/40">
        @if(jwcGet('groom_photo'))
        <img src="{{ jwcGet('groom_photo') }}" alt="{{ $groomFull }}" class="w-full h-full object-cover">
        @else
        <div class="w-full h-full flex items-center justify-center"><span class="font-display text-gold text-6xl">{{ mb_substr($groomNick,0,1) }}</span></div>
        @endif
      </div>
      <h4 class="font-display text-3xl text-ivory mt-6">{{ $groomFull }}</h4>
      <p class="text-gold tracking-[.25em] text-xs mt-2 mb-3">MEMPELAI PRIA</p>
      <p class="text-ivory/70 font-serif italic">{{ jwcGet('groom_parents', jwcGet('groom_child_of','')) }}</p>
      @if(jwcGet('groom_instagram'))
      <a href="https://instagram.com/{{ ltrim(jwcGet('groom_instagram'),'@') }}" target="_blank" rel="noopener" class="btn-ghost inline-block mt-4 text-xs tracking-[.2em] px-6 py-2.5 rounded-full">⌁ {{ '@'.ltrim(jwcGet('groom_instagram'),'@') }}</a>
      @endif
    </div>
  </section>

  <!-- ============ ACARA & COUNTDOWN ============ -->
  <section data-bg="#EDE6D6" class="relative px-6 py-24">
    <p class="reveal text-center text-golddim tracking-[.4em] text-[11px] mb-3">WAKTU &amp; TEMPAT</p>
    <h3 class="reveal text-center font-display text-mochadark text-4xl mb-8">Menuju <span class="gold-text">Hari Bahagia</span></h3>

    <div class="reveal grid grid-cols-4 gap-2.5 max-w-sm mx-auto mb-12" id="countdown">
      <div class="cd-cell rounded-xl py-4 text-center"><div class="font-display text-3xl text-gold" id="cd-d">00</div><div class="text-[10px] tracking-[.25em] text-ivory/70 mt-1">HARI</div></div>
      <div class="cd-cell rounded-xl py-4 text-center"><div class="font-display text-3xl text-gold" id="cd-h">00</div><div class="text-[10px] tracking-[.25em] text-ivory/70 mt-1">JAM</div></div>
      <div class="cd-cell rounded-xl py-4 text-center"><div class="font-display text-3xl text-gold" id="cd-m">00</div><div class="text-[10px] tracking-[.25em] text-ivory/70 mt-1">MENIT</div></div>
      <div class="cd-cell rounded-xl py-4 text-center"><div class="font-display text-3xl text-gold" id="cd-s">00</div><div class="text-[10px] tracking-[.25em] text-ivory/70 mt-1">DETIK</div></div>
    </div>

    <div class="reveal card-ivory rounded-2xl border border-gold/50 p-7 text-center mb-5 shadow-lg">
      <p class="text-golddim tracking-[.3em] text-[11px] font-semibold mb-2">AKAD NIKAH</p>
      <p class="font-display text-2xl text-mochadark">{{ jwcDate($wDate) }}</p>
      <p class="font-serif italic text-lg text-mochadark/80 mt-1">{{ $wTime }} – {{ $wTimeE }} {{ $tzLabel }}</p>
      <div class="hairline w-32 mx-auto my-4"></div>
      <p class="text-mochadark/80">{{ jwcGet('wedding_location','Kediaman Mempelai') }}</p>
      @if(!$sameLoc && jwcGet('wedding_map_link'))
      <a href="{{ jwcGet('wedding_map_link') }}" target="_blank" rel="noopener" class="btn-gold inline-block mt-5 text-xs tracking-[.2em] font-semibold px-8 py-3 rounded-full">LIHAT PETA</a>
      @endif
    </div>

    <div class="reveal card-ivory rounded-2xl border border-gold/50 p-7 text-center mb-5 shadow-lg">
      <p class="text-golddim tracking-[.3em] text-[11px] font-semibold mb-2">RESEPSI</p>
      <p class="font-display text-2xl text-mochadark">{{ jwcDate($rDate) }}</p>
      <p class="font-serif italic text-lg text-mochadark/80 mt-1">{{ $rTimeS }} – {{ $rTimeE }} {{ $tzLabel }}</p>
      <div class="hairline w-32 mx-auto my-4"></div>
      <p class="text-mochadark/80">{{ jwcGet('reception_location', jwcGet('wedding_location','Kediaman Mempelai')) }}</p>
      @if($sameLoc)
        @if($mainMapLink)
        <a href="{{ $mainMapLink }}" target="_blank" rel="noopener" class="btn-gold inline-block mt-5 text-xs tracking-[.2em] font-semibold px-8 py-3 rounded-full">LIHAT PETA LOKASI</a>
        @endif
      @else
        @if(jwcGet('reception_map_link'))
        <a href="{{ jwcGet('reception_map_link') }}" target="_blank" rel="noopener" class="btn-gold inline-block mt-5 text-xs tracking-[.2em] font-semibold px-8 py-3 rounded-full">LIHAT PETA</a>
        @endif
      @endif
      @if(jwcGet('live_stream_link'))
      <a href="{{ jwcGet('live_stream_link') }}" target="_blank" rel="noopener" class="btn-ghost inline-block mt-3 ml-2 text-xs tracking-[.2em] px-8 py-3 rounded-full !text-mochadark !border-mochadark/50">LIVE STREAMING</a>
      @endif
    </div>

    <div class="reveal text-center mt-6">
      <a href="{{ $gcalUrl }}" target="_blank" rel="noopener" class="btn-ghost inline-block text-xs tracking-[.2em] px-8 py-3.5 rounded-full !text-mochadark !border-mochadark/60">+ SIMPAN KE GOOGLE CALENDAR</a>
    </div>
  </section>

  <!-- ============ DRESS CODE ============ -->
  <section data-bg="#F8F5EE" class="relative px-6 py-24 text-center">
    <p class="reveal text-golddim tracking-[.4em] text-[11px] mb-3">DRESS CODE</p>
    <h3 class="reveal font-display text-mochadark text-4xl mb-6">Berbusana <span class="gold-text">Terbaik</span></h3>
    <div class="reveal card-ivory rounded-2xl border border-gold/50 p-8 shadow-lg">
      <p class="font-serif italic text-xl text-mochadark/90 leading-relaxed">{{ jwcGet('dress_code','Kami sangat menghargai kehadiran Bapak/Ibu/Saudara/i dengan busana batik atau busana formal bernuansa earth tone.') }}</p>
      <div class="flex justify-center gap-3 mt-6" aria-hidden="true">
        <span class="w-10 h-10 rounded-full border border-gold/60" style="background:#0A192F"></span>
        <span class="w-10 h-10 rounded-full border border-gold/60" style="background:#D4AF37"></span>
        <span class="w-10 h-10 rounded-full border border-gold/60" style="background:#C67B5C"></span>
        <span class="w-10 h-10 rounded-full border border-gold/60" style="background:#4A3728"></span>
        <span class="w-10 h-10 rounded-full border border-gold/60" style="background:#F8F5EE"></span>
      </div>
    </div>
  </section>

  <!-- ============ GALERI ============ -->
  <section data-bg="#0A192F" class="relative px-6 py-24">
    <p class="reveal text-center text-gold/80 tracking-[.4em] text-[11px] mb-3">GALERI</p>
    <h3 class="reveal text-center font-display text-ivory text-4xl mb-8">Kenangan <span class="gold-text">Terukir</span></h3>
    @if(!empty($gallery) && count($gallery))
    <div class="grid grid-cols-2 gap-3">
      @foreach(array_slice($gallery, 0, 8) as $i => $img)
      <div class="reveal gal-item relative overflow-hidden rounded-xl border border-gold/30 cursor-pointer {{ $i % 3 === 0 ? 'row-span-2' : '' }}" data-full="{{ $img }}">
        <img src="{{ $img }}" alt="Galeri {{ $i+1 }}" loading="lazy" class="w-full {{ $i % 3 === 0 ? 'h-64' : 'h-40' }} object-cover transition-transform duration-700 hover:scale-110">
        <div class="absolute inset-0 bg-gradient-to-t from-navy/60 to-transparent opacity-0 hover:opacity-100 transition"></div>
      </div>
      @endforeach
    </div>
    @else
    <p class="reveal text-center text-ivory/60 font-serif italic">Galeri kenangan akan segera hadir.</p>
    @endif
  </section>

  <!-- ============ KISAH CINTA ============ -->
  <section data-bg="#2E2119" class="relative px-6 py-24">
    <p class="reveal text-center text-gold/80 tracking-[.4em] text-[11px] mb-3">KISAH CINTA</p>
    <h3 class="reveal text-center font-display text-ivory text-4xl mb-10">Perjalanan <span class="gold-text">Kami</span></h3>
    @if(!empty($stories) && count($stories))
    <div class="relative pl-8 max-w-sm mx-auto">
      <div class="absolute left-[9px] top-2 bottom-2 w-px bg-gold/40"></div>
      @foreach($stories as $st)
      <div class="reveal relative mb-10">
        <div class="timeline-dot absolute -left-8 top-1 w-[18px] h-[18px] rounded-full bg-gold border-2 border-ivory"></div>
        @if(!empty($st->tahun))
        <p class="text-gold tracking-[.3em] text-[11px] font-semibold mb-1">{{ $st->tahun }}</p>
        @endif
        <h4 class="font-display text-2xl text-ivory mb-1">{{ $st->judul }}</h4>
        <p class="text-ivory/70 font-serif italic leading-relaxed">{{ $st->isi }}</p>
      </div>
      @endforeach
    </div>
    @else
    <p class="reveal text-center text-ivory/60 font-serif italic">Kisah cinta kami akan segera ditulis di sini.</p>
    @endif
  </section>

  <!-- ============ RSVP & BUKU TAMU ============ -->
  <section data-bg="#EDE6D6" class="relative px-6 py-24">
    <p class="reveal text-center text-golddim tracking-[.4em] text-[11px] mb-3">KONFIRMASI</p>
    <h3 class="reveal text-center font-display text-mochadark text-4xl mb-8">RSVP &amp; <span class="gold-text">Buku Tamu</span></h3>

    <form id="rsvpForm" class="reveal card-ivory rounded-2xl border border-gold/50 p-7 shadow-lg mb-8">
      <label class="block text-[11px] tracking-[.25em] text-golddim font-semibold mb-2">NAMA LENGKAP</label>
      <input id="rsvpNama" required maxlength="60" placeholder="Tulis nama Anda..." class="w-full bg-white/70 border border-mocha/25 rounded-xl px-4 py-3 mb-4 outline-none focus:border-gold transition">
      <label class="block text-[11px] tracking-[.25em] text-golddim font-semibold mb-2">KEHADIRAN</label>
      <div class="grid grid-cols-2 gap-3 mb-4" id="attendWrap">
        <button type="button" data-val="hadir" class="attend-btn border rounded-xl py-3 text-sm tracking-widest font-semibold transition border-gold bg-gold/15 text-mochadark">HADIR</button>
        <button type="button" data-val="tidak" class="attend-btn border rounded-xl py-3 text-sm tracking-widest font-semibold transition border-mocha/25 text-mochadark/60">BERHALANGAN</button>
      </div>
      <label class="block text-[11px] tracking-[.25em] text-golddim font-semibold mb-2">JUMLAH TAMU</label>
      <select id="rsvpJumlah" class="w-full bg-white/70 border border-mocha/25 rounded-xl px-4 py-3 mb-6 outline-none focus:border-gold">
        <option>1</option><option>2</option><option>3</option><option>4</option><option>5</option>
      </select>
      <button type="submit" id="rsvpBtn" class="btn-gold w-full font-semibold tracking-[.2em] text-sm py-4 rounded-xl">KIRIM KONFIRMASI</button>
    </form>

    <div class="reveal card-ivory rounded-2xl border border-gold/50 p-7 shadow-lg">
      <p class="text-golddim tracking-[.3em] text-[11px] font-semibold mb-5 text-center">BUKU TAMU</p>
      <form id="wishForm" class="mb-6">
        <input id="wishNama" required maxlength="40" placeholder="Nama Anda..." class="w-full bg-white/70 border border-mocha/25 rounded-xl px-4 py-3 mb-3 outline-none focus:border-gold transition">
        <textarea id="wishPesan" required maxlength="300" rows="3" placeholder="Tulis ucapan & doa terbaik..." class="w-full bg-white/70 border border-mocha/25 rounded-xl px-4 py-3 mb-3 outline-none focus:border-gold transition resize-none"></textarea>
        <button type="submit" id="wishBtn" class="btn-ghost w-full text-sm tracking-[.2em] font-semibold py-3.5 rounded-xl !text-mochadark !border-mochadark/50">KIRIM UCAPAN</button>
      </form>
      <div id="wishList" class="space-y-4 max-h-96 overflow-y-auto pr-1">
        <p class="text-center text-mochadark/50 font-serif italic" id="wishLoading">Memuat ucapan...</p>
      </div>
    </div>
  </section>

  <!-- ============ AMPLOP DIGITAL ============ -->
  <section data-bg="#F8F5EE" class="relative px-6 py-24 text-center">
    <p class="reveal text-golddim tracking-[.4em] text-[11px] mb-3">TANDA KASIH</p>
    <h3 class="reveal font-display text-mochadark text-4xl mb-4">Amplop <span class="gold-text">Digital</span></h3>
    <p class="reveal font-serif italic text-mochadark/70 text-lg mb-8 max-w-xs mx-auto">Doa restu Anda adalah hadiah terindah. Namun jika berkenan memberi tanda kasih, kami sediakan dengan penuh syukur.</p>
    @if(!empty($giftRecipient))
    <p class="reveal text-sm text-mochadark/70 mb-6">Penerima: <span class="font-semibold text-mochadark">{{ $giftRecipient }}</span></p>
    @endif
    @if(!empty($accounts))
    <div class="space-y-4 max-w-sm mx-auto">
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
      <div class="reveal rounded-2xl p-[1.5px]" style="background:linear-gradient(135deg,#8a6a1f,#D4AF37,#f6e27a,#D4AF37,#8a6a1f)">
        <div class="bg-navy rounded-2xl p-6 text-left">
          <div class="flex items-center justify-between mb-4">
            <p class="text-gold tracking-[.25em] text-xs font-semibold">{{ $a['bank'] }}</p>
            @if(!empty($a['logo']))<img src="{{ $a['logo'] }}" alt="Logo bank" class="h-8 max-w-[110px] object-contain bg-ivory rounded px-1">@endif
          </div>
          <p class="font-display text-ivory text-2xl tracking-[.12em]" id="accNo{{ $i }}">{{ $a['no'] }}</p>
          <p class="text-ivory/60 text-sm mt-1 mb-5">a.n. {{ $a['an'] }}</p>
          <button class="btn-gold copy-btn w-full text-xs tracking-[.2em] font-semibold py-3 rounded-xl" data-i="{{ $i }}">SALIN NOMOR</button>
        </div>
      </div>
      @endforeach
    </div>
    @else
    <p class="reveal text-mochadark/60 font-serif italic">Informasi amplop digital akan segera hadir.</p>
    @endif
    @if(jwcGet('gift_address'))
    <div class="reveal card-ivory rounded-2xl border border-gold/50 p-6 mt-6 max-w-sm mx-auto text-left shadow">
      <p class="text-golddim tracking-[.25em] text-[11px] font-semibold mb-2">KIRIM HADIAH FISIK</p>
      <p class="text-mochadark/85">{{ jwcGet('gift_address') }}</p>
      @if(jwcGet('gift_maps_link'))
      <a href="{{ jwcGet('gift_maps_link') }}" target="_blank" rel="noopener" class="btn-ghost inline-block mt-4 text-xs tracking-[.2em] px-6 py-2.5 rounded-full !text-mochadark !border-mochadark/50">BUKA PETA</a>
      @endif
    </div>
    @endif
  </section>

  <!-- ============ FOOTER ============ -->
  <footer data-bg="#0A192F" class="relative px-6 pt-20 pb-28 text-center overflow-hidden">
    <p class="reveal truntum mb-6" aria-hidden="true">✦ ✦ ✦</p>
    <p class="reveal text-ivory/70 font-serif italic text-lg max-w-xs mx-auto leading-relaxed mb-6">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>
    <p class="reveal text-ivory/50 text-sm mb-2">Kami yang berbahagia,</p>
    <h4 class="reveal font-display text-4xl mb-8"><span class="gold-text">{{ $brideNick }} &amp; {{ $groomNick }}</span></h4>
    <div class="reveal hairline w-40 mx-auto mb-6"></div>
    <p class="reveal text-ivory/40 text-xs tracking-[.3em]">MATUR NUWUN • {{ jwcDate($rDate) }}</p>
  </footer>
</div>

<!-- vinyl player -->
<button id="vinyl" aria-label="Putar / jeda musik" hidden><span class="eq">♪</span></button>
<div id="toast"></div>

<!-- lightbox -->
<div id="lightbox" class="fixed inset-0 z-[80] bg-navy/95 hidden items-center justify-center p-4">
  <button id="lbClose" class="absolute top-5 right-5 text-ivory text-3xl leading-none px-2" aria-label="Tutup">&times;</button>
  <button id="lbPrev" class="absolute left-2 text-ivory text-4xl px-3" aria-label="Sebelumnya">‹</button>
  <img id="lbImg" src="" alt="Galeri" class="max-h-[85vh] max-w-full rounded-lg border border-gold/40 object-contain">
  <button id="lbNext" class="absolute right-2 text-ivory text-4xl px-3" aria-label="Berikutnya">›</button>
  <p id="lbCount" class="absolute bottom-6 text-ivory/70 tracking-[.3em] text-xs"></p>
</div>
<script>
"use strict";
const JC = {
  invId: {{ (int)$invId }},
  countdownISO: @json($countdownISO),
  musicUrl: @json($musicUrl),
  guestName: @json($toName)
};
const reduced = matchMedia("(prefers-reduced-motion: reduce)").matches;
function toast(m){const t=document.getElementById("toast");t.textContent=m;t.classList.add("show");
  clearTimeout(t._h);t._h=setTimeout(()=>t.classList.remove("show"),2600);}
function esc(s){return String(s??"").replace(/[&<>"']/g,c=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"}[c]));}
function timeAgo(s){const d=new Date(String(s).replace(" ","T")+"+07:00");if(isNaN(d))return "";
  const n=(Date.now()-d.getTime())/1000;if(n<60)return "Baru saja";
  if(n<3600)return Math.floor(n/60)+" menit yang lalu";if(n<86400)return Math.floor(n/3600)+" jam yang lalu";
  return Math.floor(n/86400)+" hari yang lalu";}

/* ---------- musik + vinyl ---------- */
let audioEl=null, musicOn=false, acCtx=null, pluckTimer=null, stepIdx=0;
const PENTA=[523.25,587.33,659.25,783.99,880.0,1046.5];
function ac(){if(!acCtx)acCtx=new (window.AudioContext||window.webkitAudioContext)();return acCtx;}
function pluck(f,when,dur){try{const a=ac(),o=a.createOscillator(),g=a.createGain(),t=a.currentTime+when;
  o.type="triangle";o.frequency.value=f;
  g.gain.setValueAtTime(0.0001,t);g.gain.exponentialRampToValueAtTime(0.09,t+0.02);
  g.gain.exponentialRampToValueAtTime(0.0001,t+dur);
  o.connect(g);g.connect(a.destination);o.start(t);o.stop(t+dur+0.05);}catch(e){}}
function pluckStep(){ /* pola saron lembut */
  const seq=[0,2,4,2, 3,4,2,1, 0,2,4,5, 4,2,1,0];
  const n=seq[stepIdx%seq.length];
  pluck(PENTA[n],0,1.4);
  if(stepIdx%4===0)pluck(PENTA[0]/2,0,2.2);
  stepIdx++; pluckTimer=setTimeout(pluckStep,520);
}
async function resolveMusicUrl(){
  if(JC.musicUrl) return JC.musicUrl;
  try{const r=await fetch("/api/music?inv_id="+JC.invId);const j=await r.json();
    if(j&&j.url) return j.url;}catch(e){}
  return null;
}
async function toggleMusic(){
  const vinyl=document.getElementById("vinyl");
  musicOn=!musicOn;
  vinyl.classList.toggle("playing",musicOn);
  if(musicOn){
    const url=await resolveMusicUrl();
    if(url){audioEl=new Audio(url);audioEl.loop=true;audioEl.volume=0.75;
      try{await audioEl.play();}catch(e){musicOn=false;vinyl.classList.remove("playing");toast("Musik tidak dapat diputar");}
    }else{try{ac().resume();}catch(e){} stepIdx=0; pluckStep();}
    if(musicOn) toast("Musik diputar untuk "+JC.guestName);
  }else{
    if(audioEl){audioEl.pause();audioEl=null;}
    clearTimeout(pluckTimer);
    toast("Musik dijeda");
  }
}
document.getElementById("vinyl").addEventListener("click",toggleMusic);

/* ---------- buka undangan ---------- */
document.getElementById("openBtn").addEventListener("click",()=>{
  const cover=document.getElementById("cover");
  gsap.to("#coverInner",{y:-30,opacity:0,duration:.6,ease:"power2.in"});
  gsap.to(cover,{opacity:0,duration:.9,delay:.25,ease:"power2.inOut",onComplete:()=>{
    cover.style.display="none";
    document.body.classList.remove("locked");
    document.getElementById("vinyl").hidden=false;
    if(window.ScrollTrigger) ScrollTrigger.refresh();
  }});
  if(!musicOn) toggleMusic();
});

/* ---------- GSAP: reveal, parallax, bg shift ---------- */
gsap.registerPlugin(ScrollTrigger);
gsap.utils.toArray(".reveal").forEach(el=>{
  gsap.fromTo(el,{y:48,opacity:0,scale:.965},
    {y:0,opacity:1,scale:1,duration:1.05,ease:"power3.out",
     scrollTrigger:{trigger:el,start:"top 88%",toggleActions:"play none none reverse"}});
});
gsap.utils.toArray("[data-speed]").forEach(el=>{
  const sp=parseFloat(el.dataset.speed)||1;
  gsap.to(el,{y:()=>(sp-1)*260,ease:"none",
    scrollTrigger:{trigger:el.closest("section")||el,start:"top bottom",end:"bottom top",scrub:1.2}});
});
/* transisi warna background antar seksi */
gsap.utils.toArray("[data-bg]").forEach(sec=>{
  ScrollTrigger.create({trigger:sec,start:"top 55%",end:"bottom 55%",
    onEnter:()=>gsap.to("body",{backgroundColor:sec.dataset.bg,duration:1.1,overwrite:"auto"}),
    onEnterBack:()=>gsap.to("body",{backgroundColor:sec.dataset.bg,duration:1.1,overwrite:"auto"})});
});
/* ornamen cover melayang */
if(!reduced){
  gsap.utils.toArray("[data-cover-float]").forEach((el,i)=>{
    gsap.to(el,{y:i?16:-16,rotation:i?6:-6,duration:5+i*1.4,yoyo:true,repeat:-1,ease:"sine.inOut"});
  });
}

/* ---------- Three.js: gold dust + kelopak melati ---------- */
(function(){
  if(reduced || !window.THREE) return;
  const canvas=document.getElementById("webgl");
  const renderer=new THREE.WebGLRenderer({canvas,alpha:true,antialias:false});
  renderer.setPixelRatio(Math.min(devicePixelRatio,1.5));
  const scene=new THREE.Scene();
  const camera=new THREE.PerspectiveCamera(60,innerWidth/innerHeight,0.1,100);
  camera.position.z=14;
  /* tekstur titik emas */
  function dotTexture(){
    const c=document.createElement("canvas");c.width=c.height=64;
    const g=c.getContext("2d"),gr=g.createRadialGradient(32,32,0,32,32,32);
    gr.addColorStop(0,"rgba(255,240,190,1)");gr.addColorStop(.4,"rgba(212,175,55,.9)");
    gr.addColorStop(1,"rgba(212,175,55,0)");g.fillStyle=gr;g.fillRect(0,0,64,64);
    return new THREE.CanvasTexture(c);
  }
  const N=innerWidth<480?220:340;
  const pos=new Float32Array(N*3), spd=new Float32Array(N);
  for(let i=0;i<N;i++){pos[i*3]=(Math.random()-0.5)*30;pos[i*3+1]=(Math.random()-0.5)*20;pos[i*3+2]=(Math.random()-0.5)*10;spd[i]=0.2+Math.random()*0.8;}
  const geo=new THREE.BufferGeometry();
  geo.setAttribute("position",new THREE.BufferAttribute(pos,3));
  const dust=new THREE.Points(geo,new THREE.PointsMaterial({size:.35,map:dotTexture(),
    transparent:true,opacity:.85,depthWrite:false,blending:THREE.AdditiveBlending,color:0xf3d97b}));
  scene.add(dust);
  /* kelopak melati */
  function petalTexture(){
    const c=document.createElement("canvas");c.width=64;c.height=64;
    const g=c.getContext("2d");g.fillStyle="rgba(250,246,236,.95)";
    g.beginPath();g.ellipse(32,32,14,24,0.5,0,6.29);g.fill();
    g.fillStyle="rgba(212,175,55,.35)";g.beginPath();g.ellipse(32,32,6,18,0.5,0,6.29);g.fill();
    return new THREE.CanvasTexture(c);
  }
  const petals=[];
  const petalGeo=new THREE.PlaneGeometry(0.55,0.85);
  const petalMat=new THREE.MeshBasicMaterial({map:petalTexture(),transparent:true,opacity:.9,side:THREE.DoubleSide,depthWrite:false});
  const PN=innerWidth<480?22:36;
  for(let i=0;i<PN;i++){
    const m=new THREE.Mesh(petalGeo,petalMat);
    m.position.set((Math.random()-0.5)*26,(Math.random()-0.5)*18,(Math.random()-0.5)*8);
    m.rotation.set(Math.random()*3,Math.random()*3,Math.random()*3);
    m.userData={rs:(Math.random()-0.5)*1.4,fs:0.25+Math.random()*0.5,ph:Math.random()*6.28};
    petals.push(m);scene.add(m);
  }
  let lastY=scrollY,vel=0,swirl=0,t=0,running=true;
  addEventListener("scroll",()=>{const y=scrollY;vel=y-lastY;lastY=y;},{passive:true});
  function size(){renderer.setSize(innerWidth,innerHeight);camera.aspect=innerWidth/innerHeight;camera.updateProjectionMatrix();}
  size();addEventListener("resize",size);
  document.addEventListener("visibilitychange",()=>{running=!document.hidden;if(running)requestAnimationFrame(tick);});
  function tick(){
    if(!running)return;
    t+=0.016;
    swirl+=((vel*0.004)-swirl)*0.06;vel*=0.92;
    const p=geo.attributes.position.array;
    for(let i=0;i<N;i++){
      p[i*3+1]+=spd[i]*0.012+swirl*0.05;
      p[i*3]+=Math.sin(t*0.7+i)*0.006+swirl*0.03;
      if(p[i*3+1]>11){p[i*3+1]=-11;p[i*3]=(Math.random()-0.5)*30;}
      if(p[i*3+1]<-11)p[i*3+1]=11;
    }
    geo.attributes.position.needsUpdate=true;
    dust.rotation.z+=0.0006+swirl*0.02;
    dust.rotation.y=Math.sin(t*0.2)*0.08;
    petals.forEach(m=>{
      const u=m.userData;
      m.position.y-=u.fs*0.03+Math.abs(swirl)*0.06;
      m.position.x+=Math.sin(t*0.9+u.ph)*0.008+swirl*0.05;
      m.rotation.x+=u.rs*0.02;m.rotation.y+=u.rs*0.015;m.rotation.z+=u.rs*0.01;
      if(m.position.y<-10){m.position.y=10;m.position.x=(Math.random()-0.5)*26;}
    });
    renderer.render(scene,camera);
    requestAnimationFrame(tick);
  }
  tick();
})();

/* ---------- countdown ---------- */
(function(){
  const target=new Date(JC.countdownISO).getTime();
  const pad=n=>String(n).padStart(2,"0");
  const el={d:document.getElementById("cd-d"),h:document.getElementById("cd-h"),m:document.getElementById("cd-m"),s:document.getElementById("cd-s")};
  function tick(){
    let d=Math.max(0,target-Date.now());
    el.d.textContent=pad(Math.floor(d/864e5));el.h.textContent=pad(Math.floor(d/36e5)%24);
    el.m.textContent=pad(Math.floor(d/6e4)%60);el.s.textContent=pad(Math.floor(d/1e3)%60);
  }
  tick();setInterval(tick,1000);
})();

/* ---------- RSVP ---------- */
let attendVal="hadir";
document.querySelectorAll(".attend-btn").forEach(b=>b.addEventListener("click",()=>{
  attendVal=b.dataset.val;
  document.querySelectorAll(".attend-btn").forEach(x=>{
    const on=x===b;
    x.classList.toggle("border-gold",on);x.classList.toggle("bg-gold/15",on);
    x.classList.toggle("text-mochadark",on);x.classList.toggle("border-mocha/25",!on);
    x.classList.toggle("text-mochadark/60",!on);
  });
}));
document.getElementById("rsvpForm").addEventListener("submit",async e=>{
  e.preventDefault();
  const nama=document.getElementById("rsvpNama").value.trim();
  if(!nama){toast("Mohon isi nama Anda");return;}
  const btn=document.getElementById("rsvpBtn");btn.disabled=true;btn.textContent="MENGIRIM...";
  try{
    const r=await fetch("/api/rsvp",{method:"POST",headers:{"Content-Type":"application/json"},
      body:JSON.stringify({invitation_id:JC.invId,nama,jumlah_tamu:parseInt(document.getElementById("rsvpJumlah").value)||1,status:attendVal,alasan:""})});
    const j=await r.json();if(!j.success)throw new Error(j.message||"Gagal");
    e.target.reset();toast("Matur nuwun! Konfirmasi terkirim.");
  }catch(err){toast("Gagal: "+err.message);}
  btn.disabled=false;btn.textContent="KIRIM KONFIRMASI";
});

/* ---------- ucapan ---------- */
async function loadWishes(){
  const box=document.getElementById("wishList");
  try{
    const r=await fetch("/api/ucapan?inv_id="+JC.invId+"&limit=20");const j=await r.json();
    const rows=j.data||[];
    box.innerHTML=rows.length?rows.map(x=>
      `<div class="bg-white/70 border border-mocha/15 rounded-xl p-4">
        <p class="text-golddim text-[11px] tracking-[.2em] font-semibold mb-1">${esc(x.nama)}</p>
        <p class="text-mochadark/90 font-serif italic leading-relaxed">${esc(x.pesan)}</p>
        <p class="text-mochadark/40 text-xs mt-2">${timeAgo(x.created_at)}</p>
      </div>`).join("")
      :'<p class="text-center text-mochadark/50 font-serif italic">Belum ada ucapan. Jadilah yang pertama!</p>';
  }catch(e){box.innerHTML='<p class="text-center text-mochadark/50 font-serif italic">Gagal memuat ucapan.</p>';}
}
loadWishes();
document.getElementById("wishForm").addEventListener("submit",async e=>{
  e.preventDefault();
  const nama=document.getElementById("wishNama").value.trim(),pesan=document.getElementById("wishPesan").value.trim();
  if(!nama||!pesan){toast("Lengkapi nama & ucapan");return;}
  const btn=document.getElementById("wishBtn");btn.disabled=true;
  try{
    const r=await fetch("/api/ucapan",{method:"POST",headers:{"Content-Type":"application/json"},
      body:JSON.stringify({invitation_id:JC.invId,nama,pesan})});
    const j=await r.json();if(!j.success)throw new Error(j.message||"Gagal");
    e.target.reset();toast("Ucapan terkirim. Matur nuwun!");loadWishes();
  }catch(err){toast("Gagal: "+err.message);}
  btn.disabled=false;
});

/* ---------- copy rekening ---------- */
document.querySelectorAll(".copy-btn").forEach(b=>b.addEventListener("click",async ()=>{
  const t=document.getElementById("accNo"+b.dataset.i).textContent.replace(/\s/g,"");
  try{await navigator.clipboard.writeText(t);}catch(e){
    const ta=document.createElement("textarea");ta.value=t;document.body.appendChild(ta);
    ta.select();document.execCommand("copy");ta.remove();}
  toast("Nomor rekening tersalin");
}));

/* ---------- lightbox ---------- */
(function(){
  const items=Array.from(document.querySelectorAll(".gal-item"));
  const lb=document.getElementById("lightbox"),img=document.getElementById("lbImg"),
        count=document.getElementById("lbCount");
  let idx=0;
  function show(i){
    idx=(i+items.length)%items.length;
    img.src=items[idx].dataset.full;
    count.textContent=(idx+1)+" / "+items.length;
    if(window.gsap)gsap.fromTo(img,{opacity:0,scale:.96},{opacity:1,scale:1,duration:.35,ease:"power2.out"});
  }
  items.forEach((it,i)=>it.addEventListener("click",()=>{
    lb.classList.remove("hidden");lb.classList.add("flex");show(i);}));
  function close(){lb.classList.add("hidden");lb.classList.remove("flex");}
  document.getElementById("lbClose").addEventListener("click",close);
  document.getElementById("lbPrev").addEventListener("click",e=>{e.stopPropagation();show(idx-1);});
  document.getElementById("lbNext").addEventListener("click",e=>{e.stopPropagation();show(idx+1);});
  lb.addEventListener("click",e=>{if(e.target===lb)close();});
  addEventListener("keydown",e=>{if(lb.classList.contains("hidden"))return;
    if(e.key==="Escape")close();if(e.key==="ArrowLeft")show(idx-1);if(e.key==="ArrowRight")show(idx+1);});
  let tx=0;
  lb.addEventListener("touchstart",e=>{tx=e.touches[0].clientX;},{passive:true});
  lb.addEventListener("touchmove",e=>{
    const dx=e.touches[0].clientX-tx;
    if(Math.abs(dx)>48){show(idx+(dx<0?1:-1));tx=e.touches[0].clientX;}
  },{passive:true});
})();
</script>
</body>
</html>
