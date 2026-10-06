<?php
/* ============================================================
   TEMA: MINECRAFT (klasik)
   Dipilih via settings: theme = minecraft
   Data diambil dari $settings (getSet), $gallery, $musicRecord,
   $guestName, $inv_id — sama seperti tema default.
   ============================================================ */
$GLOBALS['mc_settings'] = $settings ?? [];
if (!function_exists('mcGet')) {
  function mcGet($key, $default = '') {
    $s = $GLOBALS['mc_settings'];
    if (!isset($s[$key]) || trim((string)$s[$key]) === '') return $default;
    return $s[$key];
  }
}
if (!function_exists('mcDate')) {
  function mcDate($dateStr) {
    if (!$dateStr) return '';
    $days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($dateStr);
    if (!$ts) return $dateStr;
    return $days[date('w',$ts)].', '.date('j',$ts).' '.$months[date('n',$ts)].' '.date('Y',$ts);
  }
}
if (!function_exists('mcTime')) {
  function mcTime($t) { // "08.00" / "08:00" -> "08:00"
    $t = trim((string)$t); if ($t === '') return '08:00';
    $t = str_replace('.', ':', $t);
    return preg_match('/^\d{1,2}(:\d{2})?$/', $t) ? (strpos($t, ':') === false ? $t.':00' : $t) : '08:00';
  }
}
$groomNick = mcGet('groom_nickname') ?: mcGet('groom_name', 'Mempelai Pria');
$brideNick = mcGet('bride_nickname') ?: mcGet('bride_name', 'Mempelai Wanita');
$groomFull = mcGet('groom_name', 'Mempelai Pria');
$brideFull = mcGet('bride_name', 'Mempelai Wanita');
$wDate  = mcGet('wedding_date', date('Y-m-d'));
$wTime  = mcTime(mcGet('wedding_time_start', '08:00'));
$wTimeE = mcTime(mcGet('wedding_time_end', '10:00'));
$rDate  = mcGet('reception_date', $wDate);
$rTimeS = mcTime(mcGet('reception_time_start', '11:00'));
$rTimeE = mcTime(mcGet('reception_time_end', '13:00'));
$countdownISO = $wDate.'T'.$wTime.':00+07:00';
$accounts = [];
$__gbanks = json_decode(mcGet('gift_banks', ''), true) ?: [];
foreach ($__gbanks as $__b) {
  if (empty($__b['account'])) continue;
  $accounts[] = ['bank'=>($__b['name'] ?? 'Bank'),'no'=>$__b['account'],'an'=>($__b['owner'] ?? ''),'logo'=>($__b['logo'] ?? '')];
}
if (!$accounts) {
  if (mcGet('gift_account')) $accounts[] = ['bank'=>mcGet('gift_bank','Bank'),'no'=>mcGet('gift_account'),'an'=>mcGet('gift_owner',''),'logo'=>mcGet('gift_bank_logo','')];
  if (mcGet('gift_account2')) $accounts[] = ['bank'=>mcGet('gift_bank2','Bank'),'no'=>mcGet('gift_account2'),'an'=>mcGet('gift_owner2',''),'logo'=>mcGet('gift_bank_logo','')];
}
$musicUrl = null;
if (!empty($musicRecord) && !empty($musicRecord->file_path)) {
  $mp = $musicRecord->file_path;
  $musicUrl = (str_starts_with($mp,'http') || str_starts_with($mp,'/')) ? $mp : asset('storage/'.ltrim($mp,'/'));
}
$invId = $inv_id ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Undangan Pernikahan &mdash; {{ $brideNick }} &amp; {{ $groomNick }}</title>
<meta property="og:type" content="website">
<meta property="og:title" content="Undangan Pernikahan {{ $brideNick }} & {{ $groomNick }}">
<meta property="og:description" content="Tanpa mengurangi rasa hormat, kami mengundang Anda untuk menghadiri pernikahan {{ $brideFull }} & {{ $groomFull }}.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
<style>
:root{--grass:#6abe30;--grass-dark:#4e9a26;--dirt:#8a5f3c;--stone:#7d7d7d;--panel:#c6c6c6;
  --ink:#1c1c1c;--gold:#fcee5e;--red:#e03131}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{font-family:'VT323',monospace;font-size:20px;line-height:1.45;background:#0d1420;color:#f2f2f2;overflow-x:hidden}
canvas{image-rendering:pixelated;image-rendering:crisp-edges}
#loader{position:fixed;inset:0;z-index:200;background:#0d1420;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:22px;transition:opacity .6s}
#loader.done{opacity:0;pointer-events:none}
#loader .t{font-family:'Press Start 2P',monospace;font-size:14px;color:#fff;letter-spacing:1px}
#loadbar{width:min(420px,80vw);height:26px;background:#111;border:3px solid #000;box-shadow:inset 2px 2px 0 rgba(0,0,0,.6),0 0 0 2px #3a3a3a}
#loadfill{height:100%;width:0%;background:linear-gradient(#7bd94f,#4e9a26);transition:width .25s}
#loadpct{font-family:'Press Start 2P',monospace;font-size:11px;color:var(--gold)}
#cover{position:relative;height:100svh;min-height:620px;overflow:hidden}
#world{position:absolute;inset:0;width:100%;height:100%}
.cover-ui{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:18px;padding:24px;text-align:center;z-index:2}
.cover-card{max-width:min(560px,92vw);padding:30px 28px 26px;animation:floaty 4s ease-in-out infinite}
@keyframes floaty{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
.kicker{font-family:'Press Start 2P',monospace;font-size:11px;letter-spacing:3px;color:#3f5b23;margin-bottom:16px}
.names{font-family:'Press Start 2P',monospace;font-size:clamp(26px,7vw,46px);line-height:1.35;color:#2b2b2b;text-shadow:3px 3px 0 rgba(255,255,255,.5);margin-bottom:10px}
.names .amp{color:#c22f2f;font-size:.7em;vertical-align:middle}
.date-line{font-size:26px;color:#3a3a3a;margin-bottom:20px}
.to-label{font-size:22px;color:#555;margin-bottom:6px}
.guest-name{font-family:'Press Start 2P',monospace;font-size:13px;color:#2b2b2b;margin-bottom:20px;line-height:1.7}
.mc-panel{background:var(--panel);border:3px solid #141414;color:var(--ink);
  box-shadow:inset 3px 3px 0 rgba(255,255,255,.6),inset -3px -3px 0 rgba(0,0,0,.35)}
.mc-btn{font-family:'Press Start 2P',monospace;font-size:13px;color:#fff;text-shadow:2px 2px 0 #3a3a3a;
  background:linear-gradient(#a3a3a3,#7f7f7f);border:2px solid #0a0a0a;cursor:pointer;
  box-shadow:inset 2px 2px 0 rgba(255,255,255,.5),inset -2px -2px 0 rgba(0,0,0,.5);
  padding:15px 22px;letter-spacing:1px;transition:transform .06s}
.mc-btn:hover{filter:brightness(1.08)}
.mc-btn:active{transform:translateY(2px);box-shadow:inset -2px -2px 0 rgba(255,255,255,.25),inset 2px 2px 0 rgba(0,0,0,.5)}
.mc-btn.green{background:linear-gradient(#79d154,#4e9a26)}
.mc-btn.gold{background:linear-gradient(#ffe36e,#d9a821);text-shadow:2px 2px 0 #6e4d05}
.mc-input{width:100%;background:#0f0f0f;color:#fff;border:2px solid #9a9a9a;font-family:'VT323',monospace;
  font-size:21px;padding:11px 12px;box-shadow:inset 2px 2px 0 rgba(0,0,0,.7);outline:none}
.mc-input:focus{border-color:#fff}
select.mc-input{appearance:none}
label.lbl{font-family:'Press Start 2P',monospace;font-size:10px;color:#3a3a3a;display:block;margin:14px 0 7px;letter-spacing:1px}
#main{background:linear-gradient(rgba(10,14,22,.92),rgba(10,14,22,.94)),
  repeating-conic-gradient(#141b29 0% 25%, #101623 0% 50%) 0 0/48px 48px;padding-bottom:60px}
section.block{max-width:860px;margin:0 auto;padding:56px 20px 8px}
.sec-head{display:flex;align-items:center;justify-content:center;gap:14px;margin-bottom:26px}
.sec-head canvas{width:34px;height:34px;flex:none}
.sec-title{font-family:'Press Start 2P',monospace;font-size:clamp(15px,4vw,22px);color:#fff;
  text-shadow:3px 3px 0 #000;letter-spacing:1px;text-align:center}
.sec-sub{text-align:center;color:#9fb3c8;font-size:22px;margin:-14px 0 26px}
.mc-dialog{background:rgba(8,8,8,.82);border:3px solid #f2f2f2;color:#fff;padding:20px 22px;
  min-height:132px;cursor:pointer;box-shadow:0 0 0 3px #141414,8px 8px 0 rgba(0,0,0,.45);
  font-size:23px;position:relative}
.mc-dialog .speaker{font-family:'Press Start 2P',monospace;font-size:10px;color:var(--gold);display:block;margin-bottom:10px;letter-spacing:1px}
.mc-dialog .next{position:absolute;right:14px;bottom:10px;color:var(--gold);animation:blink 1s steps(2) infinite;font-size:20px}
@keyframes blink{50%{opacity:0}}
.couple-grid{display:grid;grid-template-columns:1fr;gap:22px}
@media(min-width:680px){.couple-grid{grid-template-columns:1fr auto 1fr;align-items:center}}
.player-card{padding:26px 22px;text-align:center}
.player-card canvas.face{width:112px;height:112px;margin-bottom:14px;background:#8ab6e0;border:3px solid #141414;box-shadow:inset -4px -4px 0 rgba(0,0,0,.25)}
.player-card h3{font-family:'Press Start 2P',monospace;font-size:16px;color:#2b2b2b;margin-bottom:8px;line-height:1.5}
.player-tag{display:inline-block;font-family:'Press Start 2P',monospace;font-size:9px;background:#2b2b2b;color:var(--gold);
  padding:7px 10px;margin-bottom:12px;letter-spacing:1px}
.player-card p{font-size:21px;color:#3d3d3d}
.heart-sep{display:flex;justify-content:center}
.heart-sep canvas{width:54px;height:54px;animation:beat 1.1s ease-in-out infinite}
@keyframes beat{0%,100%{transform:scale(1)}25%{transform:scale(1.18)}45%{transform:scale(1)}}
.hud{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;max-width:640px;margin:0 auto}
.hud-slot{background:#101010;border:3px solid #000;box-shadow:inset 2px 2px 0 rgba(255,255,255,.12),0 0 0 2px #3a3a3a;
  padding:16px 6px;text-align:center}
.hud-slot .num{font-family:'Press Start 2P',monospace;font-size:clamp(18px,6vw,30px);color:#7bff5e;text-shadow:2px 2px 0 #063;display:block;margin-bottom:8px}
.hud-slot .unit{font-family:'Press Start 2P',monospace;font-size:9px;color:#9fb3c8;letter-spacing:1px}
.adv{background:#1b1b1b;border:2px solid #000;box-shadow:inset 2px 2px 0 rgba(255,255,255,.08);
  display:flex;gap:16px;padding:18px;margin-bottom:16px;align-items:flex-start}
.adv .icon{flex:none;width:58px;height:58px;background:var(--panel);border:3px solid #000;
  box-shadow:inset 2px 2px 0 rgba(255,255,255,.5),inset -2px -2px 0 rgba(0,0,0,.4);
  display:flex;align-items:center;justify-content:center}
.adv .icon canvas{width:38px;height:38px}
.adv h3{font-family:'Press Start 2P',monospace;font-size:12px;color:var(--gold);margin-bottom:8px;letter-spacing:1px;line-height:1.6}
.adv p{color:#cfcfcf;font-size:21px}
.adv .time{color:#7bff5e;font-size:22px}
.map-btn{margin-top:14px;display:inline-block;text-decoration:none}
.frames{display:grid;grid-template-columns:repeat(2,1fr);gap:18px}
@media(min-width:680px){.frames{grid-template-columns:repeat(4,1fr)}}
.item-frame{background:#5d4126;border:3px solid #141414;box-shadow:inset 3px 3px 0 rgba(255,255,255,.25),inset -3px -3px 0 rgba(0,0,0,.5);padding:10px}
.item-frame img{width:100%;display:block;aspect-ratio:1;object-fit:cover;background:#000;border:2px solid #2a1d0e}
.item-frame p{text-align:center;color:#ffe9b8;font-size:19px;margin-top:8px}
.rsvp-panel{padding:26px 24px}
.scoreboard{margin-top:22px;background:#101010;border:3px solid #000;box-shadow:0 0 0 2px #3a3a3a;padding:6px 0}
.scoreboard h4{font-family:'Press Start 2P',monospace;font-size:10px;color:var(--gold);padding:12px 16px;letter-spacing:1px;border-bottom:2px solid #2a2a2a}
.score-row{display:flex;justify-content:space-between;gap:10px;padding:9px 16px;border-bottom:1px solid #222;font-size:20px;color:#e8e8e8;align-items:center}
.score-row:last-child{border-bottom:none}
.score-row .st-ok{color:#7bff5e}.score-row .st-no{color:#ff7b7b}
.score-row small{color:#888;font-size:17px}
.score-empty{padding:14px 16px;color:#777;font-size:20px}
.msg-row{padding:12px 16px;border-bottom:1px solid #222}
.msg-row:last-child{border-bottom:none}
.msg-row .nm{font-family:'Press Start 2P',monospace;font-size:9px;color:var(--gold);margin-bottom:6px}
.msg-row .ps{font-size:21px;color:#e8e8e8}
.msg-row .tm{font-size:17px;color:#777;margin-top:4px}
.chest-zone{display:flex;flex-direction:column;align-items:center;gap:6px;padding:10px 0 4px}
.chest{width:132px;height:104px;position:relative;cursor:pointer;perspective:400px}
.chest .base{position:absolute;left:0;right:0;bottom:0;height:66px;background:linear-gradient(#a9743f,#7d5327);
  border:3px solid #141414;box-shadow:inset 0 6px 0 rgba(255,255,255,.18)}
.chest .base::after{content:"";position:absolute;left:50%;top:8px;transform:translateX(-50%);width:16px;height:22px;
  background:linear-gradient(#d8d8d8,#8a8a8a);border:2px solid #141414}
.chest .lid{position:absolute;left:0;right:0;top:0;height:44px;background:linear-gradient(#b9834b,#8a5e2e);
  border:3px solid #141414;transform-origin:top center;transition:transform .45s cubic-bezier(.3,1.4,.5,1);z-index:2}
.chest.open .lid{transform:rotateX(-105deg)}
.chest .glow{position:absolute;left:8%;right:8%;top:34px;height:44px;background:radial-gradient(ellipse at center,#ffe9a3 0%,rgba(255,220,120,.55) 45%,transparent 75%);
  opacity:0;transition:opacity .5s;z-index:1}
.chest.open .glow{opacity:1;animation:glowpulse 1.6s ease-in-out infinite}
@keyframes glowpulse{50%{opacity:.55}}
.chest-label{font-family:'Press Start 2P',monospace;font-size:10px;color:#ffe9b8;letter-spacing:1px}
.gift-accounts{display:grid;gap:14px;margin-top:18px;grid-template-columns:1fr}
@media(min-width:640px){.gift-accounts{grid-template-columns:1fr 1fr}}
.acct{padding:18px}
.acct .bank{font-family:'Press Start 2P',monospace;font-size:12px;color:#2b2b2b;margin-bottom:8px}
.acct .no{font-family:'Press Start 2P',monospace;font-size:15px;color:#123f12;letter-spacing:1px;margin-bottom:4px;word-break:break-all}
.acct .an{font-size:20px;color:#444;margin-bottom:12px}
.copy-btn{font-size:11px;padding:10px 14px}
.thanks-card{text-align:center;padding:38px 26px}
.thanks-card h2{font-family:'Press Start 2P',monospace;font-size:clamp(18px,5vw,28px);color:#2b6b1c;margin-bottom:16px;line-height:1.6}
.thanks-card p{font-size:23px;color:#3d3d3d;max-width:560px;margin:0 auto 12px}
.fam{font-family:'Press Start 2P',monospace;font-size:11px;color:#6b6b6b;line-height:2;margin-top:18px}
.footer-note{text-align:center;color:#5b6b80;font-size:19px;padding:34px 20px 10px}
.float-btns{position:fixed;right:14px;bottom:14px;z-index:90;display:flex;flex-direction:column;gap:10px}
.float-btns button{width:52px;height:52px;font-size:20px;padding:0;display:flex;align-items:center;justify-content:center}
#toast{position:fixed;left:50%;bottom:84px;transform:translateX(-50%) translateY(20px);z-index:120;
  background:#101010;color:#fff;border:2px solid #fff;box-shadow:0 0 0 2px #000,6px 6px 0 rgba(0,0,0,.4);
  font-family:'Press Start 2P',monospace;font-size:11px;padding:14px 18px;opacity:0;pointer-events:none;
  transition:opacity .3s,transform .3s;max-width:88vw;text-align:center;line-height:1.7}
#toast.show{opacity:1;transform:translateX(-50%) translateY(0)}
#fx{position:fixed;inset:0;z-index:80;pointer-events:none}
#mineWrap{display:flex;align-items:center;gap:12px}
#mineBlock{width:64px;height:64px;cursor:pointer;transition:transform .1s}
#mineBlock:active{transform:scale(.92)}
#mineBlock.crack{animation:shake .3s}
@keyframes shake{25%{transform:translateX(-3px)}75%{transform:translateX(3px)}}
.mine-hint{font-size:20px;color:#fff;text-shadow:2px 2px 0 #000}
.creeper-peek{position:absolute;z-index:2;cursor:pointer;transition:transform .3s}
.reveal{opacity:0;transform:translateY(36px);transition:opacity .7s ease,transform .7s ease}
.reveal.in{opacity:1;transform:none}
@media (prefers-reduced-motion:reduce){
  .cover-card,.heart-sep canvas,.mc-dialog .next{animation:none}
  .reveal{transition:none;opacity:1;transform:none}
  html{scroll-behavior:auto}
}
</style>
</head>
<body>

<div id="loader">
  <div class="t">GENERATING WORLD...</div>
  <div id="loadbar"><div id="loadfill"></div></div>
  <div id="loadpct">0%</div>
</div>

<header id="cover">
  <canvas id="world"></canvas>
  <img class="creeper-peek" id="creeper" alt="" style="display:none">
  <div class="cover-ui">
    <div class="mc-panel cover-card">
      <p class="kicker">UNDANGAN PERNIKAHAN</p>
      <h1 class="names">{{ $brideNick }} <span class="amp">&amp;</span> {{ $groomNick }}</h1>
      <p class="date-line">{{ mcDate($wDate) }}</p>
      <p class="to-label">Kepada Yth. Bapak/Ibu/Saudara/i</p>
      <p class="guest-name">{{ $guestName }}</p>
      <button class="mc-btn green" id="openBtn">&#9971; BUKA UNDANGAN</button>
    </div>
    <div id="mineWrap">
      <canvas id="mineBlock" width="16" height="16" title="Klik untuk menambang"></canvas>
      <span class="mine-hint">&larr; klik baloknya!</span>
    </div>
  </div>
</header>

<main id="main" hidden>
  <section class="block reveal">
    <div class="mc-dialog" id="dialog" title="Klik untuk lanjut">
      <span class="speaker" id="dlgSpeaker">???</span>
      <span id="dlgText"></span>
      <span class="next">&#9654;</span>
    </div>
  </section>

  <section class="block reveal" id="couple">
    <div class="sec-head"><canvas class="sec-ico" data-icon="heart" width="16" height="16"></canvas><h2 class="sec-title">MEMILIH PARTY</h2><canvas class="sec-ico" data-icon="heart" width="16" height="16"></canvas></div>
    <p class="sec-sub">Dua pemain. Satu tim. Selamanya.</p>
    <div class="couple-grid">
      <div class="mc-panel player-card">
        <canvas class="face" id="faceBride" width="8" height="8"></canvas>
        <h3>{{ $brideFull }}</h3>
        <span class="player-tag">PLAYER 1 &mdash; MEMPELAI WANITA</span>
        <p>{{ mcGet('bride_parents', mcGet('bride_child_of', '')) }}</p>
      </div>
      <div class="heart-sep"><canvas id="bigHeart" width="14" height="12"></canvas></div>
      <div class="mc-panel player-card">
        <canvas class="face" id="faceGroom" width="8" height="8"></canvas>
        <h3>{{ $groomFull }}</h3>
        <span class="player-tag">PLAYER 2 &mdash; MEMPELAI PRIA</span>
        <p>{{ mcGet('groom_parents', mcGet('groom_child_of', '')) }}</p>
      </div>
    </div>
  </section>

  <section class="block reveal" id="countdownSec">
    <div class="sec-head"><canvas class="sec-ico" data-icon="clock" width="16" height="16"></canvas><h2 class="sec-title">HITUNG MUNDUR</h2><canvas class="sec-ico" data-icon="clock" width="16" height="16"></canvas></div>
    <p class="sec-sub">Quest dimulai dalam...</p>
    <div class="hud">
      <div class="hud-slot"><span class="num" id="cdD">00</span><span class="unit">HARI</span></div>
      <div class="hud-slot"><span class="num" id="cdH">00</span><span class="unit">JAM</span></div>
      <div class="hud-slot"><span class="num" id="cdM">00</span><span class="unit">MENIT</span></div>
      <div class="hud-slot"><span class="num" id="cdS">00</span><span class="unit">DETIK</span></div>
    </div>
  </section>

  <section class="block reveal" id="eventSec">
    <div class="sec-head"><canvas class="sec-ico" data-icon="book" width="16" height="16"></canvas><h2 class="sec-title">QUEST LOG</h2><canvas class="sec-ico" data-icon="book" width="16" height="16"></canvas></div>
    <p class="sec-sub">Dua misi utama menantimu.</p>
    <div class="adv">
      <div class="icon"><canvas id="icoAkad" width="16" height="16"></canvas></div>
      <div>
        <h3>AKAD NIKAH</h3>
        <p>{{ mcDate($wDate) }}</p>
        <p class="time">{{ $wTime }} &ndash; {{ $wTimeE }} {{ mcGet('wedding_timezone','WIB') }}</p>
        <p>{{ mcGet('wedding_location', 'Kediaman Mempelai') }}</p>
        @if(mcGet('wedding_map_link'))<a class="mc-btn map-btn" href="{{ mcGet('wedding_map_link') }}" target="_blank" rel="noopener" style="font-size:10px">&#9673; BUKA PETA</a>@endif
      </div>
    </div>
    <div class="adv">
      <div class="icon"><canvas id="icoResepsi" width="16" height="16"></canvas></div>
      <div>
        <h3>RESEPSI</h3>
        <p>{{ mcDate($rDate) }}</p>
        <p class="time">{{ $rTimeS }} &ndash; {{ $rTimeE }} {{ mcGet('wedding_timezone','WIB') }}</p>
        <p>{{ mcGet('reception_location', mcGet('wedding_location', 'Kediaman Mempelai')) }}</p>
        @if(mcGet('reception_map_link'))<a class="mc-btn map-btn" href="{{ mcGet('reception_map_link') }}" target="_blank" rel="noopener" style="font-size:10px">&#9673; BUKA PETA</a>@endif@if(mcGet('live_stream_link'))<a class="mc-btn map-btn" target="_blank" href="{{ mcGet('live_stream_link') }}" style="margin-left:8px">Live Streaming</a>@endif
      </div>
    </div>
  </section>

  @if(!empty($gallery))
  <section class="block reveal" id="gallerySec">
    <div class="sec-head"><canvas class="sec-ico" data-icon="frame" width="16" height="16"></canvas><h2 class="sec-title">GALERI PETUALANGAN</h2><canvas class="sec-ico" data-icon="frame" width="16" height="16"></canvas></div>
    <p class="sec-sub">Momen yang diabadikan dalam balok kenangan.</p>
    <div class="frames">
      @foreach(array_slice($gallery, 0, 8) as $i => $img)
      <div class="item-frame"><img src="{{ $img }}" alt="Galeri {{ $i + 1 }}" loading="lazy"><p>Kenangan {{ $i + 1 }}</p></div>
      @endforeach
    </div>
  </section>
  @endif

  @if(!empty($stories) && count($stories))
  <section class="block reveal" id="storySec">
    <div class="sec-head"><canvas class="sec-ico" data-icon="book" width="16" height="16"></canvas><h2 class="sec-title">CATATAN PETUALANGAN</h2><canvas class="sec-ico" data-icon="book" width="16" height="16"></canvas></div>
    <p class="sec-sub">Jejak langkah kami hingga ke sini.</p>
    @foreach($stories as $st)
    <div class="adv">
      <div class="icon"><canvas class="story-ico" data-icon="heart" width="16" height="16"></canvas></div>
      <div><h3>{{ $st->title ?? 'Cerita' }}</h3><p>{{ $st->description ?? $st->content ?? '' }}</p></div>
    </div>
    @endforeach
  </section>
  @endif

  <section class="block reveal" id="rsvpSec">
    <div class="sec-head"><canvas class="sec-ico" data-icon="book" width="16" height="16"></canvas><h2 class="sec-title">KONFIRMASI MISI</h2><canvas class="sec-ico" data-icon="book" width="16" height="16"></canvas></div>
    <p class="sec-sub">Apakah kamu akan bergabung dalam party?</p>
    <div class="mc-panel rsvp-panel">
      <form id="rsvpForm">
        <label class="lbl" for="fNama">NAMA PEMAIN</label>
        <input class="mc-input" id="fNama" required maxlength="40" placeholder="Tulis namamu..." autocomplete="name">
        <label class="lbl" for="fHadir">STATUS KEHADIRAN</label>
        <select class="mc-input" id="fHadir">
          <option value="hadir">Hadir, siap berpetualang!</option>
          <option value="tidak">Tidak bisa ikut quest</option>
        </select>
        <label class="lbl" for="fJumlah">JUMLAH ROMBONGAN</label>
        <select class="mc-input" id="fJumlah">
          <option>1</option><option>2</option><option>3</option><option>4</option><option>5</option>
        </select>
        <div style="margin-top:20px;display:flex;gap:12px;flex-wrap:wrap">
          <button type="submit" class="mc-btn green" style="font-size:11px">KIRIM RSVP</button>
        </div>
      </form>
      <div class="scoreboard">
        <h4>&#9733; DAFTAR PETUALANG</h4>
        <div id="rsvpList"><div class="score-empty">Memuat daftar...</div></div>
      </div>
    </div>
  </section>

  <section class="block reveal" id="ucapanSec">
    <div class="sec-head"><canvas class="sec-ico" data-icon="mail" width="16" height="16"></canvas><h2 class="sec-title">PAPAN UCAPAN</h2><canvas class="sec-ico" data-icon="mail" width="16" height="16"></canvas></div>
    <p class="sec-sub">Tinggalkan pesan di papan desa.</p>
    <div class="mc-panel rsvp-panel">
      <form id="ucapanForm">
        <label class="lbl" for="uNama">NAMA</label>
        <input class="mc-input" id="uNama" required maxlength="40" placeholder="Namamu...">
        <label class="lbl" for="uPesan">UCAPAN</label>
        <input class="mc-input" id="uPesan" required maxlength="160" placeholder="Tulis ucapan terbaikmu...">
        <div style="margin-top:16px"><button type="submit" class="mc-btn gold" style="font-size:11px">KIRIM UCAPAN</button></div>
      </form>
      <div class="scoreboard">
        <h4>&#9733; UCAPAN MASUK</h4>
        <div id="ucapanList"><div class="score-empty">Memuat ucapan...</div></div>
      </div>
    </div>
  </section>

  @if(!empty($accounts))
  <section class="block reveal" id="giftSec">
    <div class="sec-head"><canvas class="sec-ico" data-icon="chest" width="16" height="16"></canvas><h2 class="sec-title">PETI HADIAH</h2><canvas class="sec-ico" data-icon="chest" width="16" height="16"></canvas></div>
    <p class="sec-sub">Klik petinya untuk membuka.</p>
    <div class="chest-zone">
      <div class="chest" id="chest" role="button" tabindex="0" aria-label="Buka peti hadiah">
        <div class="glow"></div><div class="lid"></div><div class="base"></div>
      </div>
      <div class="chest-label" id="chestLabel">PETI TERKUNCI</div>
    </div>
    <div class="gift-accounts" id="giftAccounts" hidden>
      @foreach($accounts as $i => $a)
      <div class="mc-panel acct">
        <div class="bank">{{ $a['bank'] }}</div>
    @if(!empty($a['logo']))<img src="{{ $a['logo'] }}" alt="Logo bank" style="height:36px;max-width:150px;object-fit:contain;margin:10px auto 0;display:block">@endif
        <div class="no" id="accNo{{ $i }}">{{ $a['no'] }}</div>
        <div class="an">{{ $a['an'] }}</div>
        <button class="mc-btn gold copy-btn" data-acc="accNo{{ $i }}">SALIN NOMOR</button>
      </div>
      @endforeach
      @if(mcGet('gift_address'))<div class="mc-panel acct" style="grid-column:1/-1"><div class="bank">KIRIM HADIAH FISIK</div><div class="an">{{ mcGet('gift_address') }}</div>
      @if(mcGet('gift_maps_link'))<div style="margin-top:12px"><a href="{{ mcGet('gift_maps_link') }}" target="_blank" style="display:inline-block;padding:10px 26px;border:1px solid currentColor;border-radius:999px;font-size:11px;letter-spacing:.24em;text-transform:uppercase;text-decoration:none;opacity:.85">Lihat Peta</a></div>@endif</div>@endif
    </div>
  </section>
  @endif

  <section class="block reveal" id="thanksSec">
    <div class="mc-panel thanks-card">
      <h2>&#9733; QUEST COMPLETE! &#9733;</h2>
      <p>Terima kasih telah menjadi bagian dari petualangan kami. Kehadiran dan doa restumu adalah hadiah terbaik &mdash; tanpamu, quest ini takkan lengkap.</p>
      <p>Kami yang berbahagia,</p>
      <p class="px-font" style="font-family:'Press Start 2P',monospace;font-size:13px;color:#2b6b1c">{{ $brideNick }} &amp; {{ $groomNick }}</p>
      <div class="fam">{{ mcDate($wDate) }}</div>
    </div>
    <p class="footer-note">Dibuat dengan &#10084; dan balok-balok cinta &mdash; edisi video game</p>
  </section>
</main>

<canvas id="fx"></canvas>
<div class="float-btns">
  <button class="mc-btn" id="dayNightBtn" title="Siang / Malam">&#9728;</button>
  <button class="mc-btn" id="musicBtn" title="Musik">&#9834;</button>
</div>
<div id="toast"></div>
<script>
"use strict";
/* Data dari Blade */
const MC = {
  invId: {{ (int)$invId }},
  countdownISO: @json($countdownISO),
  musicUrl: @json($musicUrl),
  bride: @json($brideNick),
  groom: @json($groomNick),
  dialog: [
    { sp:"NARATOR", tx:"Halo, Petualang! Selamat datang di dunia kami." },
    { sp:"NARATOR", tx:"Dua pemain telah memutuskan untuk memulai quest terbesar dalam hidup: PERNIKAHAN." },
    { sp:"SYSTEM", tx:"Quest baru dibuka! Scroll ke bawah untuk menjelajahi setiap level. Klik balok, hati-hati Creeper... dan selamat bersenang-senang!" }
  ]
};
const $=s=>document.querySelector(s), $$=s=>Array.from(document.querySelectorAll(s));
const reduced=matchMedia("(prefers-reduced-motion: reduce)").matches;
function toast(m){const t=$("#toast");t.textContent=m;t.classList.add("show");clearTimeout(t._h);t._h=setTimeout(()=>t.classList.remove("show"),2600);}
(function(){const g=new URLSearchParams(location.search).get("to");
  if(g&&!document.querySelector(".guest-name").textContent.trim())document.querySelector(".guest-name").textContent=g;})();

/* ---------- sprites ---------- */
function drawSprite(cv,map,pal){
  const ctx=cv.getContext("2d"),s=cv.width/map[0].length;
  ctx.clearRect(0,0,cv.width,cv.height);
  map.forEach((row,y)=>{[...row].forEach((ch,x)=>{if(pal[ch]){ctx.fillStyle=pal[ch];
    ctx.fillRect(Math.floor(x*s),Math.floor(y*s),Math.ceil(s),Math.ceil(s));}});});
}
const PAL={R:"#e03131",G:"#5eb83d",g:"#4e9a26",K:"#141414",W:"#f2f2f2",Y:"#fcee5e",B:"#6b4a2a",
  O:"#141414",P:"#8a5f3c",L:"#b9834b",D:"#bfe9ff",E:"#3f8fd1"};
const ICONS={
  heart:["............","..RRR..RRR..",".RRRRRRRRRR.",".RRRRRRRRRR.","..RRRRRRRR..","...RRRRRR...","....RRRR....","............"],
  clock:[".....WW.....","...WWYYWW...","..WYYYYYYW..","..YYYKYYYY..","..YYYKYYYY..","..YYYKYYYY..","..YYYYYYYY..","...WWYYWW...",".....WW....."],
  book:["............",".BBBBBBBBBB.",".BWBBBBBBWB.",".BWBBBBBBWB.",".BWBBBBBBWB.",".BBBBBBBBBB.","............"],
  frame:["OOOOOOOOOOOO","OPPPPPPPPPPO","OP........PO","OP...YY...PO","OP..YYYY..PO","OP...YY...PO","OP........PO","OPPPPPPPPPPO","OOOOOOOOOOOO"],
  mail:["............",".WWWWWWWWWW.",".WKWWWWWWKW.",".WWKWWWWKWW.",".WWWKKKKWWW.",".WWWWWWWWWW.",".WWWWWWWWWW.","............"],
  chest:["............",".OOOOOOOOOO.",".OLLLLLLLLO.",".OLLLLLLLLO.",".OBBBBBBBBO.",".OBBBOOBBBO.",".OBBBBBBBBO.",".OOOOOOOOOO."],
  ring:[".....DDDD.....","....DDDDDD....","...DDWDDDWDD...","...DDDDDDDD...","....DDDDDD....",".....DDDD.....","......DD......",".....OOOO.....","....OYYYYO....","....OYGGYO....","....OYYYYO....",".....OOOO....."]
};
function drawFace(cv,o){
  const s=cv.width/8,ctx=cv.getContext("2d"),F=c=>ctx.fillStyle=c;
  for(let y=0;y<8;y++)for(let x=0;x<8;x++){F(o.skin);ctx.fillRect(x*s,y*s,s,s);}
  const hair=(x0,x1,y)=>{F(o.hair);for(let x=x0;x<=x1;x++)ctx.fillRect(x*s,y*s,s,s);};
  if(o.style==="bride"){F("#f5f5f5");for(let y=0;y<8;y++){ctx.fillRect(0,y*s,s,s);ctx.fillRect(7*s,y*s,s,s);}
    for(let x=1;x<7;x++)ctx.fillRect(x*s,0,s,s);
    hair(1,6,1);hair(1,1,2);hair(6,6,2);hair(1,1,3);hair(6,6,3);}
  else{hair(0,7,0);hair(0,7,1);hair(0,1,2);hair(6,7,2);hair(0,0,3);hair(7,7,3);}
  F("#141414");ctx.fillRect(2*s,3*s,s,s);ctx.fillRect(5*s,3*s,s,s);
  F("#7d2d2d");ctx.fillRect(3*s,5*s,2*s,s);
  F(o.shirt);for(let x=0;x<8;x++)ctx.fillRect(x*s,7*s,s,s);
  F(o.skin);ctx.fillRect(3*s,6*s,2*s,s);
}
function paintIcons(){
  $$(".sec-ico").forEach(cv=>drawSprite(cv,ICONS[cv.dataset.icon]||ICONS.heart,PAL));
  $$(".story-ico").forEach(cv=>drawSprite(cv,ICONS.heart,PAL));
  const ia=$("#icoAkad"),ir=$("#icoResepsi");
  if(ia)drawSprite(ia,ICONS.ring,PAL); if(ir)drawSprite(ir,ICONS.heart,PAL);
  const bh=$("#bigHeart"); if(bh)drawSprite(bh,ICONS.heart,PAL);
  const fg=$("#faceGroom"),fb=$("#faceBride");
  if(fg)drawFace(fg,{skin:"#e8b98a",hair:"#2b2b2b",shirt:"#2f6db3",style:"groom"});
  if(fb)drawFace(fb,{skin:"#f0c49c",hair:"#5a3a22",shirt:"#c2437f",style:"bride"});
}
function drawGrassBlock(cv){
  const ctx=cv.getContext("2d"),s=cv.width/16;
  for(let y=0;y<16;y++)for(let x=0;x<16;x++){
    ctx.fillStyle=y<4?(((x*7+y*13)%5===0)?"#4e9a26":"#6abe30"):(((x*5+y*11)%4===0)?"#6e4a2e":"#8a5f3c");
    ctx.fillRect(x*s,y*s,s,s);}
  ctx.fillStyle="rgba(255,255,255,.25)";ctx.fillRect(0,0,cv.width,s);
  ctx.fillStyle="rgba(0,0,0,.3)";ctx.fillRect(0,cv.height-s,cv.width,s);
}

/* ---------- dunia voxel ---------- */
const world={t:0,dayT:1,dayTarget:1,clouds:[],stars:[],terrain:null,W:0,H:0,B:26};
const SKY={day:["#79b8f2","#cfe9ff"],night:["#060a18","#101a33"]};
function mix(c1,c2,t){const a=parseInt(c1.slice(1),16),b=parseInt(c2.slice(1),16);
  const r=Math.round(((a>>16)&255)*(1-t)+((b>>16)&255)*t),g=Math.round(((a>>8)&255)*(1-t)+((b>>8)&255)*t),
        bl=Math.round((a&255)*(1-t)+(b&255)*t);return `rgb(${r},${g},${bl})`;}
function skyCol(i){return mix(SKY.night[i],SKY.day[i],world.dayT);}
function groundH(x){return 9+Math.round(2.2*Math.sin(x*0.16)+1.4*Math.sin(x*0.41+2));}
function buildWorld(){
  const cv=$("#world");world.W=cv.width=cv.offsetWidth;world.H=cv.height=cv.offsetHeight;
  const B=world.B,cols=Math.ceil(world.W/B)+2;
  const off=document.createElement("canvas");off.width=world.W;off.height=world.H;
  const c=off.getContext("2d");
  for(let i=0;i<cols;i++){
    const gh=groundH(i),topY=world.H-gh*B;
    for(let yb=0;yb<gh+3;yb++){const y=topY+yb*B;let col;
      if(yb===0)col=((i*7+yb*3)%6===0)?"#57a82c":"#6abe30";
      else if(yb<4)col=((i*5+yb*11)%5===0)?"#6e4a2e":"#8a5f3c";
      else col=((i*3+yb*7)%5===0)?"#6a6a6a":"#7d7d7d";
      c.fillStyle=col;c.fillRect(i*B,y,B,B);
      c.fillStyle="rgba(0,0,0,.12)";c.fillRect(i*B,y+B-2,B,2);}
    if(i%7===3&&gh>0){const fx=i*B+B/2,fy=topY-6;
      c.fillStyle="#3f8f23";c.fillRect(fx-1,fy,2,6);
      c.fillStyle=["#ff5b5b","#fcee5e","#ffffff","#ff9ff3"][(i*3)%4];c.fillRect(fx-3,fy-4,6,4);}
  }
  const tree=(bx,sc)=>{const gh=groundH(bx),topY=world.H-gh*B,X=bx*B,S=B*sc;
    c.fillStyle="#6e4a2e";c.fillRect(X+S*0.4,topY-S*1.6,S*0.2,S*1.6);
    c.fillStyle="#3f8f23";c.fillRect(X,topY-S*2.6,S,S*1.4);
    c.fillStyle="#4e9a26";c.fillRect(X+S*0.1,topY-S*2.4,S*0.8,S);
    c.fillStyle="#3f8f23";c.fillRect(X-S*0.25,topY-S*2.2,S*1.5,S*0.7);};
  tree(6,1);tree(30,1.25);tree(52,0.9);
  const hx=40,gh=groundH(hx),hy=world.H-gh*B,X=hx*B,S=B;
  c.fillStyle="#9c7a4d";c.fillRect(X,hy-3*S,7*S,3*S);
  c.fillStyle="rgba(0,0,0,.15)";for(let i=0;i<7;i++)c.fillRect(X+i*S,hy-3*S,2,3*S);
  c.fillStyle="#8a3b2e";for(let i=0;i<4;i++)c.fillRect(X-S+i*S,hy-3*S-(i+1)*S*0.55,9*S-2*i*S,S*0.55);
  c.fillStyle="#4a2f18";c.fillRect(X+3*S,hy-2*S,S,2*S);
  c.fillStyle="#fcee5e";c.fillRect(X+S,hy-2.4*S,S,S);
  world.clouds=Array.from({length:7},()=>({x:Math.random()*world.W,y:20+Math.random()*world.H*0.28,
    w:70+Math.random()*90,sp:4+Math.random()*8}));
  world.stars=Array.from({length:90},()=>({x:Math.random()*world.W,y:Math.random()*world.H*0.55,tw:Math.random()*6.28}));
  world.terrain=off;
}
function drawWorld(dt){
  const cv=$("#world"),ctx=cv.getContext("2d"),W=world.W,H=world.H;
  world.t+=dt;world.dayT+=(world.dayTarget-world.dayT)*Math.min(1,dt*2.5);
  const g=ctx.createLinearGradient(0,0,0,H);
  g.addColorStop(0,skyCol(0));g.addColorStop(1,skyCol(1));
  ctx.fillStyle=g;ctx.fillRect(0,0,W,H);
  if(world.dayT<0.6){const a=(0.6-world.dayT)/0.6;
    world.stars.forEach(s=>{ctx.globalAlpha=a*(0.4+0.6*Math.abs(Math.sin(world.t*1.5+s.tw)));
      ctx.fillStyle="#fff";ctx.fillRect(s.x,s.y,2,2);});ctx.globalAlpha=1;}
  const sx=W-110,sy=90;
  if(world.dayT>0.5){ctx.fillStyle="#ffdf3d";ctx.fillRect(sx,sy,54,54);
    ctx.fillStyle="rgba(255,223,61,.25)";ctx.fillRect(sx-12,sy-12,78,78);}
  else{ctx.fillStyle="#e8ecf5";ctx.fillRect(sx,sy,44,44);
    ctx.fillStyle=skyCol(0);ctx.fillRect(sx+14,sy-6,26,26);}
  ctx.fillStyle=`rgba(255,255,255,${0.35+0.55*world.dayT})`;
  world.clouds.forEach(cl=>{cl.x+=cl.sp*dt*(reduced?0:1);if(cl.x-cl.w>W)cl.x=-cl.w;
    ctx.fillRect(cl.x,cl.y,cl.w,18);ctx.fillRect(cl.x+14,cl.y-12,cl.w-36,12);ctx.fillRect(cl.x+8,cl.y+18,cl.w-16,10);});
  ctx.drawImage(world.terrain,0,0);
  if(!reduced&&Math.random()<dt*1.2)
    parts.push({k:"h",x:Math.random()*W,y:H+20,vx:(Math.random()-0.5)*20,vy:-(30+Math.random()*40),age:0,life:6+Math.random()*3,s:10+Math.random()*8});
}

/* ---------- partikel ---------- */
const parts=[],fx=$("#fx"),fctx=fx.getContext("2d");
function sizeFx(){fx.width=innerWidth;fx.height=innerHeight;}
function burst(x,y,colors,n,pow){n=n||14;pow=pow||260;if(reduced)n=Math.min(n,6);
  for(let i=0;i<n;i++){const a=Math.random()*6.283,sp=pow*(0.3+Math.random()*0.7);
    parts.push({k:"sq",x,y,vx:Math.cos(a)*sp,vy:Math.sin(a)*sp-120,age:0,life:0.7+Math.random()*0.5,
      s:4+Math.random()*6,col:colors[i%colors.length],rot:Math.random()*6.28,vr:(Math.random()-0.5)*10});}}
function heartBurst(x,y,n){n=n||8;
  for(let i=0;i<n;i++)parts.push({k:"h",x:x+(Math.random()-0.5)*30,y,vx:(Math.random()-0.5)*60,
    vy:-(60+Math.random()*80),age:0,life:1.6+Math.random(),s:8+Math.random()*8});}
function drawHeartShape(c,x,y,s,col){c.fillStyle=col||"#e03131";const u=s/7;
  const p=[[-2,-1],[-1,-1],[0,-1],[1,-1],[2,-1],[-3,0],[-2,0],[-1,0],[0,0],[1,0],[2,0],[3,0],
    [-3,1],[-2,1],[-1,1],[0,1],[1,1],[2,1],[3,1],[-2,2],[-1,2],[0,2],[1,2],[2,2],[-1,3],[0,3],[1,3],[0,4]];
  p.forEach(pt=>c.fillRect(x+pt[0]*u-u/2,y+pt[1]*u-u/2,Math.ceil(u),Math.ceil(u)));}
function tickFx(dt){
  fctx.clearRect(0,0,fx.width,fx.height);
  for(let i=parts.length-1;i>=0;i--){const p=parts[i];p.age+=dt;
    if(p.age>=p.life){parts.splice(i,1);continue;}
    const t=1-p.age/p.life;
    if(p.k==="sq"){p.vy+=620*dt;p.x+=p.vx*dt;p.y+=p.vy*dt;p.rot+=p.vr*dt;
      fctx.save();fctx.globalAlpha=t;fctx.translate(p.x,p.y);fctx.rotate(p.rot);
      fctx.fillStyle=p.col;fctx.fillRect(-p.s/2,-p.s/2,p.s,p.s);fctx.restore();}
    else{p.x+=(p.vx||0)*dt;p.y+=p.vy*dt;fctx.globalAlpha=Math.min(1,t*1.6);
      drawHeartShape(fctx,p.x,p.y,p.s*(0.6+0.4*t));}}
  fctx.globalAlpha=1;
}
addEventListener("pointerdown",e=>{
  if(e.target.closest("button,a,input,select,.chest,#dialog"))return;
  heartBurst(e.clientX,e.clientY,5);blip(700,0.05,0.03);
},{passive:true});

/* ---------- audio ---------- */
let AC=null,musicOn=false,musicTimer=null,mstep=0,audioEl=null,useFile=false;
function ac(){if(!AC)AC=new (window.AudioContext||window.webkitAudioContext)();return AC;}
function tone(f,dur,type,vol,when){try{const a=ac(),o=a.createOscillator(),g=a.createGain(),t=a.currentTime+(when||0);
  o.type=type||"square";o.frequency.value=f;g.gain.setValueAtTime(vol||0.05,t);
  g.gain.exponentialRampToValueAtTime(0.0001,t+dur);o.connect(g);g.connect(a.destination);
  o.start(t);o.stop(t+dur+0.02);}catch(e){}}
function blip(f,d,v){tone(f||600,d||0.07,"square",v||0.04);}
function levelUp(){[523,659,784,1047].forEach((f,i)=>tone(f,0.12,"square",0.05,i*0.09));}
const MEL=[523,587,659,784,880,784,659,587,523,587,659,587,523,0,392,0],BAS=[131,98,110,98];
function playStep(){if(!musicOn||useFile)return;
  const m=MEL[mstep%MEL.length],b=BAS[Math.floor(mstep/4)%BAS.length];
  if(m)tone(m,0.22,"triangle",0.05);if(mstep%2===0)tone(b,0.3,"sine",0.045);mstep++;
  musicTimer=setTimeout(playStep,240);}
$("#musicBtn").addEventListener("click",e=>{
  e.stopPropagation();musicOn=!musicOn;
  $("#musicBtn").innerHTML=musicOn?"&#9835;":"&#9834;";
  if(musicOn){
    if(MC.musicUrl&&!audioEl){
      audioEl=new Audio(MC.musicUrl);audioEl.loop=true;audioEl.volume=0.7;
      audioEl.addEventListener("error",()=>{useFile=false;audioEl=null;mstep=0;playStep();});
    }
    if(audioEl&&MC.musicUrl){useFile=true;audioEl.play().catch(()=>{useFile=false;mstep=0;playStep();});}
    else{try{ac().resume();}catch(err){} mstep=0;playStep();}
    toast("MUSIK: ON");
  }else{
    clearTimeout(musicTimer);if(audioEl)audioEl.pause();toast("MUSIK: OFF");
  }
  blip(musicOn?880:440,0.08,0.05);
});

/* ---------- dialog ---------- */
const dlg={i:0,typing:false,timer:null};
function typeDialog(){
  const d=MC.dialog[dlg.i];$("#dlgSpeaker").textContent=d.sp;
  const el=$("#dlgText");el.textContent="";
  if(reduced){el.textContent=d.tx;return;}
  dlg.typing=true;let c=0;clearInterval(dlg.timer);
  dlg.timer=setInterval(()=>{el.textContent=d.tx.slice(0,++c);
    if(c%3===0)tone(300+Math.random()*80,0.02,"square",0.012);
    if(c>=d.tx.length){clearInterval(dlg.timer);dlg.typing=false;}},26);
}
$("#dialog").addEventListener("click",()=>{
  if(dlg.typing){clearInterval(dlg.timer);dlg.typing=false;$("#dlgText").textContent=MC.dialog[dlg.i].tx;return;}
  blip(500,0.06,0.04);dlg.i=(dlg.i+1)%MC.dialog.length;typeDialog();
});

/* ---------- countdown ---------- */
const target=new Date(MC.countdownISO).getTime();
function tickCd(){let d=Math.max(0,target-Date.now());const pad=n=>String(n).padStart(2,"0");
  const e1=$("#cdD");if(!e1)return;
  e1.textContent=pad(Math.floor(d/864e5));$("#cdH").textContent=pad(Math.floor(d/36e5)%24);
  $("#cdM").textContent=pad(Math.floor(d/6e4)%60);$("#cdS").textContent=pad(Math.floor(d/1e3)%60);}
setInterval(tickCd,1000);

/* ---------- interaksi dunia ---------- */
const mineCv=$("#mineBlock");drawGrassBlock(mineCv);let mineCD=false;
mineCv.addEventListener("click",e=>{e.stopPropagation();if(mineCD)return;mineCD=true;
  mineCv.classList.add("crack");
  const r=mineCv.getBoundingClientRect();
  burst(r.left+r.width/2,r.top+r.height/2,["#6abe30","#8a5f3c","#4e9a26"],18,300);
  tone(180,0.12,"sawtooth",0.05);setTimeout(()=>tone(120,0.15,"sawtooth",0.05),90);
  if(Math.random()<0.35){heartBurst(r.left+r.width/2,r.top,6);toast("Kamu menemukan HATI!");}
  else toast("Balok ditambang! +1 XP");
  setTimeout(()=>{mineCv.classList.remove("crack");drawGrassBlock(mineCv);mineCD=false;},700);});
function placeCreeper(){
  const c0=document.createElement("canvas");c0.width=8;c0.height=8;
  const x0=c0.getContext("2d"),pal={G:"#5eb83d",g:"#4e9a26",K:"#141414"};
  const map=["GGGGGGGG","GgGGGGgG","GgGGGGgG","GGKKKKGG","GKKKKKKG","GKKKKKKG","GKKKKKKG","GGKGGKGG"];
  map.forEach((row,ry)=>{[...row].forEach((ch,rx)=>{x0.fillStyle=pal[ch];x0.fillRect(rx,ry,1,1);});});
  const el=$("#creeper");el.src=c0.toDataURL();
  el.style.width="54px";el.style.left="8%";el.style.bottom="26%";el.style.display="block";
  el.addEventListener("click",e=>{e.stopPropagation();
    const r=el.getBoundingClientRect();heartBurst(r.left+27,r.top,12);
    [0,1,2,3].forEach(i=>tone(90,0.1,"sawtooth",0.05,i*0.08));
    toast("Creeper pun ikut berbahagia!");
    el.style.transform="translateY(40px)";setTimeout(()=>el.style.transform="",2500);});
}

/* ---------- RSVP via API ---------- */
function esc(s){return String(s??"").replace(/[&<>"']/g,c=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"}[c]));}
async function loadRsvp(){
  const box=$("#rsvpList");if(!box)return;
  try{
    const r=await fetch("/api/rsvp?inv_id="+MC.invId);const j=await r.json();
    const rows=(j.data||[]).slice(0,8);
    box.innerHTML=rows.length?rows.map(x=>
      `<div class="score-row"><span>${esc(x.nama)} <small>x${x.jumlah_tamu}</small></span>
       <span class="px-font" style="font-family:'Press Start 2P',monospace;font-size:9px;color:${x.status==="hadir"?"#7bff5e":"#ff7b7b"}">${x.status.toUpperCase()}</span></div>`
    ).join(""):'<div class="score-empty">Belum ada yang mendaftar. Jadilah yang pertama!</div>';
  }catch(e){box.innerHTML='<div class="score-empty">Gagal memuat daftar.</div>';}
}
$("#rsvpForm").addEventListener("submit",async e=>{
  e.preventDefault();
  const nama=$("#fNama").value.trim();
  if(!nama){toast("Isi dulu nama pemainnya!");return;}
  const btn=e.target.querySelector("[type=submit]");btn.disabled=true;
  try{
    const r=await fetch("/api/rsvp",{method:"POST",headers:{"Content-Type":"application/json"},
      body:JSON.stringify({invitation_id:MC.invId,nama,jumlah_tamu:parseInt($("#fJumlah").value)||1,
        status:$("#fHadir").value,alasan:""})});
    const j=await r.json();
    if(!j.success)throw new Error(j.message||"Gagal");
    e.target.reset();levelUp();toast("RSVP TERSIMPAN!");loadRsvp();
    const rc=$("#rsvpSec").getBoundingClientRect();
    burst(rc.left+rc.width/2,rc.top+120,["#7bff5e","#fcee5e","#e03131"],22,340);
  }catch(err){toast("Gagal: "+err.message);}
  btn.disabled=false;
});
/* ---------- ucapan via API ---------- */
function timeAgo(s){const d=new Date(String(s).replace(" ","T")+"+07:00");if(isNaN(d))return "";
  const n=(Date.now()-d.getTime())/1000;if(n<60)return "baru saja";
  if(n<3600)return Math.floor(n/60)+" mnt lalu";if(n<86400)return Math.floor(n/3600)+" jam lalu";
  return Math.floor(n/86400)+" hari lalu";}
async function loadUcapan(){
  const box=$("#ucapanList");if(!box)return;
  try{
    const r=await fetch("/api/ucapan?inv_id="+MC.invId);const j=await r.json();
    const rows=(j.data||[]).slice(0,10);
    box.innerHTML=rows.length?rows.map(x=>
      `<div class="msg-row"><div class="nm">${esc(x.nama)}</div><div class="ps">${esc(x.pesan)}</div>
       <div class="tm">${timeAgo(x.created_at)}</div></div>`
    ).join(""):'<div class="score-empty">Belum ada ucapan. Jadilah yang pertama!</div>';
  }catch(e){box.innerHTML='<div class="score-empty">Gagal memuat ucapan.</div>';}
}
$("#ucapanForm").addEventListener("submit",async e=>{
  e.preventDefault();
  const nama=$("#uNama").value.trim(),pesan=$("#uPesan").value.trim();
  if(!nama||!pesan){toast("Lengkapi nama & ucapan!");return;}
  const btn=e.target.querySelector("[type=submit]");btn.disabled=true;
  try{
    const r=await fetch("/api/ucapan",{method:"POST",headers:{"Content-Type":"application/json"},
      body:JSON.stringify({invitation_id:MC.invId,nama,pesan})});
    const j=await r.json();if(!j.success)throw new Error(j.message||"Gagal");
    e.target.reset();blip(880,0.1,0.05);toast("UCAPAN TERKIRIM!");loadUcapan();
  }catch(err){toast("Gagal: "+err.message);}
  btn.disabled=false;
});

/* ---------- peti ---------- */
const chest=$("#chest");
if(chest){
  const toggle=open=>{
    const isOpen=open!==undefined?open:!chest.classList.contains("open");
    chest.classList.toggle("open",isOpen);
    const lb=$("#chestLabel");if(lb)lb.textContent=isOpen?"PETI TERBUKA!":"PETI TERKUNCI";
    const ga=$("#giftAccounts");if(ga)ga.hidden=!isOpen;
    if(isOpen){levelUp();toast("Harta karun ditemukan!");
      const r=chest.getBoundingClientRect();
      burst(r.left+r.width/2,r.top,["#f7c948","#ffe9a3","#fcee5e"],20,280);
    }else blip(300,0.08,0.05);};
  chest.addEventListener("click",e=>{e.stopPropagation();toggle();});
  chest.addEventListener("keydown",e=>{if(e.key==="Enter"||e.key===" "){e.preventDefault();toggle();}});
}
$$(".copy-btn").forEach(b=>b.addEventListener("click",async e=>{
  e.stopPropagation();
  const t=$("#"+b.dataset.acc).textContent.replace(/\s/g,"");
  try{await navigator.clipboard.writeText(t);}catch(err){
    const ta=document.createElement("textarea");ta.value=t;document.body.appendChild(ta);
    ta.select();document.execCommand("copy");ta.remove();}
  blip(880,0.08,0.05);toast("NOMOR TERSALIN!");
}));

/* ---------- lain-lain ---------- */
$("#dayNightBtn").addEventListener("click",e=>{
  e.stopPropagation();world.dayTarget=world.dayTarget>0.5?0:1;
  const night=world.dayTarget<0.5;
  $("#dayNightBtn").innerHTML=night?"&#9790;":"&#9728;";
  toast(night?"MODE MALAM":"MODE SIANG");blip(night?330:660,0.1,0.05);
});
const io=new IntersectionObserver(es=>es.forEach(en=>{if(en.isIntersecting){en.target.classList.add("in");io.unobserve(en.target);}}),{threshold:0.12});
let opened=false;
$("#openBtn").addEventListener("click",()=>{
  if(opened)return;opened=true;
  blip(520,0.08,0.05);setTimeout(()=>blip(780,0.1,0.05),100);
  const cover=$("#cover");
  cover.style.transition="transform .9s cubic-bezier(.6,0,.3,1), opacity .9s";
  cover.style.transform="translateY(-100%)";cover.style.opacity="0.2";
  setTimeout(()=>{cover.style.display="none";
    const main=$("#main");main.hidden=false;
    $$(".reveal").forEach(el=>io.observe(el));
    typeDialog();tickCd();loadRsvp();loadUcapan();levelUp();main.scrollIntoView();},850);
});
(function(){let p=0;const iv=setInterval(()=>{p=Math.min(100,p+8+Math.random()*14);
  $("#loadfill").style.width=p+"%";$("#loadpct").textContent=Math.floor(p)+"%";
  if(p>=100){clearInterval(iv);setTimeout(()=>$("#loader").classList.add("done"),350);}},140);})();
let last=performance.now();
function loop(now){const dt=Math.min(0.05,(now-last)/1000);last=now;
  const cov=$("#cover");
  if(!cov.style.display||cov.style.display!=="none")drawWorld(dt);
  tickFx(dt);requestAnimationFrame(loop);}
paintIcons();sizeFx();buildWorld();placeCreeper();
addEventListener("resize",()=>{sizeFx();buildWorld();});
requestAnimationFrame(loop);
</script>
</body>
</html>
