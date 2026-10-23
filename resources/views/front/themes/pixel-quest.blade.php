<?php
/* ============================================================
   TEMA: PIXEL QUEST — undangan game JRPG pixel-art
   Dipilih via settings: theme = pixel-quest
   Grafis digambar prosedural di canvas (orisinil, tanpa aset game lain).
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
$musVol = (int) jklGet('music_volume', 50);
$musAuto = jklGet('music_autoplay', '1') !== '0';
$liveEmbed = jklGet('live_stream_link','');
if (preg_match('~(?:youtube\.com/(?:watch\?v=|live/|shorts/|embed/)|youtu\.be/)([\w-]{6,})~', (string)$liveEmbed, $pqm)) { $liveEmbed = 'https://www.youtube-nocookie.com/embed/'.$pqm[1]; }
$invId = $inv_id ?? 0;
$gcalDates = str_replace('-','',$rDate).'T'.str_replace(':','',$rTimeS).'00/'.str_replace('-','',$rDate).'T'.str_replace(':','',$rTimeE).'00';
$gcalUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text='.urlencode('Pernikahan '.$brideNick.' & '.$groomNick).'&dates='.$gcalDates.'&details='.urlencode(jklGet('reception_location', jklGet('wedding_location','')));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Pixel Quest &mdash; {{ $brideNick }} &amp; {{ $groomNick }}</title>
<meta property="og:type" content="website">
<meta property="og:title" content="Undangan Pernikahan {{ $brideNick }} & {{ $groomNick }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
<style>
:root{--wood:#8a5f3c;--wood-d:#5d3a1f;--cream:#fff8e7;--gold:#ffd93b;--ink:#2a1a0c}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html,body{height:100%;overflow:hidden;background:#3a7a2e}
body{font-family:'VT323',monospace;font-size:21px;color:var(--ink)}
canvas{image-rendering:pixelated;image-rendering:crisp-edges}
.px{font-family:'Press Start 2P',monospace}
.wood-panel{background:var(--cream);border:4px solid var(--wood-d);border-radius:6px;color:var(--ink);
  box-shadow:0 0 0 2px var(--wood),0 8px 0 rgba(0,0,0,.25)}
.pq-btn{font-family:'Press Start 2P',monospace;font-size:12px;color:#fff;text-shadow:2px 2px 0 rgba(0,0,0,.4);
  background:linear-gradient(#a9764a,#8a5f3c);border:3px solid var(--wood-d);border-radius:8px;cursor:pointer;
  box-shadow:inset 2px 2px 0 rgba(255,255,255,.35),0 4px 0 var(--wood-d);padding:14px 22px;letter-spacing:1px}
.pq-btn:active{transform:translateY(3px);box-shadow:inset 2px 2px 0 rgba(255,255,255,.35),0 1px 0 var(--wood-d)}
.pq-btn.green{background:linear-gradient(#79d154,#4e9a26)}
.pq-btn.gold{background:linear-gradient(#ffe36e,#d9a821);text-shadow:2px 2px 0 #6e4d05}
.pq-input{width:100%;background:#fffdf4;color:var(--ink);border:3px solid var(--wood);border-radius:6px;
  font-family:'VT323',monospace;font-size:21px;padding:11px 12px;outline:none}
.pq-input:focus{border-color:#3f8fd1}
select.pq-input{appearance:none}
.lbl{font-family:'Press Start 2P',monospace;font-size:10px;color:#6e4f2e;display:block;margin:14px 0 7px;letter-spacing:1px}
/* ---------- layar judul ---------- */
#start{position:fixed;inset:0;z-index:50;display:flex;align-items:center;justify-content:center;
  background:linear-gradient(#7ec8f2 0%,#bfe6c9 55%,#8fd07a 100%);padding:20px;overflow-y:auto}
.start-card{max-width:620px;width:100%;padding:28px 24px;text-align:center;max-height:94vh;overflow-y:auto}
.logo{font-family:'Press Start 2P',monospace;font-size:clamp(20px,7vw,34px);line-height:1.5;
  color:#ffd93b;text-shadow:3px 3px 0 #5d3a1f, -1px -1px 0 #5d3a1f, 1px -1px 0 #5d3a1f, -1px 1px 0 #5d3a1f;}
.logo small{display:block;font-size:clamp(10px,3vw,13px);color:#fff;text-shadow:2px 2px 0 #5d3a1f;margin-top:4px}
.start-card .sub{font-size:22px;color:#5a7a3a;margin:14px 0}
.char-row{display:flex;gap:14px;justify-content:center;margin-bottom:8px;flex-wrap:wrap}
.char-opt{background:#a9764a;border:3px solid var(--wood-d);border-radius:8px;cursor:pointer;padding:12px 14px;text-align:center}
.char-opt.sel{background:#ffe9b0;outline:3px solid var(--gold)}
.char-opt canvas{width:52px;height:74px;background:linear-gradient(#9adc8a,#7cc46a);display:block;margin-bottom:8px;border-radius:4px}
.char-opt span{font-family:'Press Start 2P',monospace;font-size:9px;color:#3a2a18}
.controls-help{font-size:20px;color:#4a6a32;margin:14px 0 20px;line-height:1.6}
.controls-help b{color:#2b4a1c}
/* ---------- game ---------- */
#game{position:fixed;inset:0}
#cv{position:absolute;inset:0;width:100%;height:100%;display:block}
#hud{position:absolute;top:0;left:0;right:0;display:flex;justify-content:space-between;align-items:flex-start;
  padding:10px 12px;pointer-events:none;z-index:5}
#hud>*{pointer-events:auto}
#questLog{background:rgba(20,14,6,.82);border:3px solid #fff;border-radius:8px;color:#fff;
  padding:10px 12px;max-width:250px}
#questLog h3{font-family:'Press Start 2P',monospace;font-size:11px;color:var(--gold);margin-bottom:8px;
  display:flex;align-items:center;gap:8px}
#questLog h3 .qb{width:18px;height:18px;background:#5d3a1f;border:2px solid var(--gold);
  display:inline-flex;align-items:center;justify-content:center;font-size:12px}
#questLog .qnext{font-size:19px;line-height:1.35;color:#fff}
#questLog .qnext b{color:var(--gold)}
#questLog .qitem{display:flex;gap:8px;align-items:flex-start;font-size:18px;color:#cfe8b0;margin-top:7px;line-height:1.25}
#questLog .qitem .box{flex:0 0 15px;height:15px;border:2px solid #fff;margin-top:2px;text-align:center;
  font-size:12px;line-height:11px;color:#fff}
#questLog .qitem.done{color:#8fd07a;text-decoration:line-through}
#questLog .qitem.done .box{background:#4e9a26}
#logoMini{font-family:'Press Start 2P',monospace;font-size:13px;color:var(--gold);
  text-shadow:2px 2px 0 #5d3a1f, -1px -1px 0 #5d3a1f, 1px -1px 0 #5d3a1f, -1px 1px 0 #5d3a1f;
  text-align:right;line-height:1.5}
#logoMini span{display:block;font-size:8px;color:#fff;text-shadow:1px 1px 0 #5d3a1f}
#musicBtn{width:46px;height:46px;border-radius:10px;background:rgba(20,14,6,.82);border:3px solid #fff;
  color:#fff;font-size:20px;cursor:pointer;margin-top:6px}
.pips{display:flex;gap:4px}
.pip{width:22px;height:22px;border-radius:5px;background:#3a2a18;border:2px solid #6e4f2e;
  display:flex;align-items:center;justify-content:center;font-size:12px;filter:grayscale(1);opacity:.55}
.pip.on{filter:none;opacity:1;background:#fff8e7;border-color:var(--gold)}
#hint{position:absolute;left:50%;transform:translateX(-50%);bottom:178px;z-index:5;
  background:rgba(20,14,6,.88);color:#ffe9b0;border:3px solid var(--gold);border-radius:10px;
  padding:9px 16px;font-family:'Press Start 2P',monospace;font-size:10px;text-align:center;max-width:92vw}
#hint b{color:#fff}
#dpad{position:absolute;left:14px;bottom:14px;z-index:5;display:none;
  grid-template-columns:repeat(3,54px);grid-template-rows:repeat(3,54px);gap:4px}
#dpad.show{display:grid}
.dbtn{background:rgba(20,14,6,.6);border:3px solid rgba(255,255,255,.85);border-radius:12px;
  color:#fff;font-size:22px;touch-action:none;user-select:none;-webkit-user-select:none}
.dbtn:active{background:rgba(255,217,59,.55)}
#actBtn{position:absolute;right:16px;bottom:22px;z-index:5;display:none;
  width:86px;height:86px;border-radius:50%;background:rgba(20,14,6,.6);
  border:4px solid rgba(255,255,255,.85);color:#fff;
  font-family:'Press Start 2P',monospace;font-size:11px;touch-action:none;user-select:none;-webkit-user-select:none}
#actBtn.show{display:block}
#actBtn.ready{background:rgba(78,154,38,.85);border-color:var(--gold);animation:pulse 1s infinite}
@keyframes pulse{50%{transform:scale(1.08)}}
/* ---------- dialog bawah ---------- */
#dialog{position:absolute;left:12px;right:12px;bottom:12px;z-index:6;display:none;
  background:#fff8e7;border:4px solid var(--wood-d);border-radius:10px;
  box-shadow:0 0 0 2px var(--wood),0 8px 0 rgba(0,0,0,.25);padding:14px 16px 14px 92px;min-height:96px;cursor:pointer}
#dialog.show{display:block}
#dlgFace{position:absolute;left:10px;top:50%;transform:translateY(-50%);width:66px;height:66px;
  border:3px solid var(--wood-d);border-radius:8px;background:#dff3ff;overflow:hidden}
#dlgFace canvas{width:100%;height:100%}
#dlgName{font-family:'Press Start 2P',monospace;font-size:10px;color:#8a5f3c;margin-bottom:6px}
#dlgText{font-size:23px;line-height:1.35;color:var(--ink);min-height:52px}
#dlgNext{position:absolute;right:12px;bottom:8px;font-size:16px;color:#8a5f3c;animation:blink 1s infinite}
@keyframes blink{50%{opacity:0}}
#toast{position:fixed;left:50%;top:64px;transform:translate(-50%,-16px);z-index:60;
  background:#2b4a1c;color:#fff;border:3px solid var(--gold);border-radius:10px;
  font-family:'Press Start 2P',monospace;font-size:11px;padding:12px 18px;opacity:0;transition:.25s;
  pointer-events:none;max-width:90vw;text-align:center}
#toast.show{opacity:1;transform:translate(-50%,0)}
/* ---------- panel ---------- */
#panelOverlay{position:fixed;inset:0;z-index:40;background:rgba(20,12,4,.72);
  display:flex;align-items:center;justify-content:center;padding:16px}
#panelOverlay[hidden]{display:none}
#panel{width:100%;max-width:560px;max-height:88vh;overflow-y:auto;padding:26px 22px;position:relative;
  background:var(--cream)}
#panelClose{position:sticky;top:0;float:right;width:40px;height:40px;border-radius:8px;
  background:#c0392b;color:#fff;border:3px solid #7d241a;font-family:'Press Start 2P',monospace;
  font-size:13px;cursor:pointer;z-index:2}
.ptitle{font-family:'Press Start 2P',monospace;font-size:16px;color:#3a5a22;margin-bottom:6px;letter-spacing:1px}
.psub{font-size:21px;color:#7a6a4a;margin-bottom:16px}
.player-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:6px}
.qr-card{margin-top:12px;text-align:center}
.qr-card #qrBox img,.qr-card #qrBox canvas{margin:0 auto}
.pcard{background:#fffdf4;border:3px solid var(--wood);border-radius:8px;padding:14px;text-align:center}
.pcard canvas{width:52px;height:52px;background:#dff3ff;border-radius:6px;margin-bottom:8px}
.pcard h3{font-size:23px;color:#3a2a18}
.ptag{font-family:'Press Start 2P',monospace;font-size:8px;color:#fff;background:#4e9a26;
  border-radius:4px;padding:4px 8px;display:inline-block;margin:6px 0}
.pcard p{font-size:19px;color:#7a6a4a}
.hud2{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin:6px 0 4px}
.slot{background:#2b4a1c;border:3px solid var(--wood-d);border-radius:8px;padding:12px 4px;text-align:center}
.slot .num{font-family:'Press Start 2P',monospace;font-size:19px;color:var(--gold);display:block}
.slot .unit{font-size:16px;color:#cfe8b0}
.adv{background:#fffdf4;border:3px solid var(--wood);border-radius:8px;padding:14px;display:flex;gap:12px;margin-bottom:12px}
.adv .ic{flex:0 0 44px}
.adv .ic canvas{width:44px;height:44px}
.adv h3{font-family:'Press Start 2P',monospace;font-size:11px;color:#3a5a22;margin-bottom:6px}
.adv p{font-size:20px;color:#5a4a30}
.adv .tm{font-family:'Press Start 2P',monospace;font-size:13px;color:#8a5f3c;margin:4px 0}
.frames2{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.frame2{background:#a9764a;border:3px solid var(--wood-d);border-radius:6px;padding:8px;text-align:center;cursor:pointer}
.frame2 img{width:100%;height:150px;object-fit:cover;border-radius:4px;display:block;background:#dff3ff}
.frame2 p{font-size:18px;color:#ffe9b0;margin-top:6px}
.story{border-left:4px solid #4e9a26;padding:0 0 4px 16px;margin:0 0 18px 6px;position:relative}
.story::before{content:"";position:absolute;left:-11px;top:2px;width:18px;height:18px;border-radius:50%;
  background:var(--gold);border:3px solid var(--wood-d)}
.story .yr{font-family:'Press Start 2P',monospace;font-size:10px;color:#fff;background:#8a5f3c;
  display:inline-block;padding:5px 10px;border-radius:4px;margin-bottom:6px}
.story h4{font-size:23px;color:#3a5a22;margin-bottom:4px}
.story p{font-size:20px;color:#5a4a30}
.msg-row{padding:10px 14px;border-top:2px dashed #d8c9a0}
.msg-row .nm{font-family:'Press Start 2P',monospace;font-size:9px;color:#3a5a22;margin-bottom:4px}
.msg-row .ps{font-size:20px;color:#3a2a18}
.msg-row .tm{font-size:16px;color:#a08a5a;margin-top:2px}
/* ---------- toko senjata ---------- */
.shop-tabs{display:flex;gap:8px;margin-bottom:14px}
.shop-tab{flex:1;font-family:'Press Start 2P',monospace;font-size:10px;padding:12px 4px;text-align:center;
  background:#8a5f3c;color:#ffe9b0;border:3px solid var(--wood-d);border-radius:8px;cursor:pointer}
.shop-tab.sel{background:var(--gold);color:#5d3a1f}
.shop-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.shop-card{background:#fffdf4;border:3px solid var(--wood);border-radius:8px;padding:12px;text-align:center;position:relative}
.shop-card.hot{border-color:var(--gold);box-shadow:0 0 0 2px var(--gold)}
.shop-card .badge{position:absolute;top:-10px;right:8px;font-family:'Press Start 2P',monospace;
  font-size:8px;background:#e03131;color:#fff;padding:4px 7px;border-radius:4px}
.shop-card canvas{width:56px;height:56px;background:#efe6c8;border-radius:6px;margin:4px auto 8px}
.shop-card h4{font-size:20px;color:#3a2a18;margin-bottom:4px}
.shop-card .price{font-family:'Press Start 2P',monospace;font-size:10px;color:#8a5f3c;margin-bottom:4px}
.shop-card .stat{font-size:18px;color:#7a6a4a;margin-bottom:10px}
.shop-card .copybtn{width:100%}
.complete{text-align:center;padding:10px 4px}
.complete h2{font-family:'Press Start 2P',monospace;font-size:17px;color:#3a5a22;margin-bottom:14px;line-height:1.8}
.complete p{font-size:22px;color:#5a4a30;margin-bottom:10px}
@media (prefers-reduced-motion:reduce){#actBtn.ready,#dlgNext{animation:none}}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>
<div id="start">
  <div class="start-card wood-panel">
    <div class="logo">PIXEL QUEST<small>&#9733; UNDANGAN PERNIKAHAN &#9733;</small></div>
    <div class="sub"><b>{{ $brideNick }} &amp; {{ $groomNick }}</b><br>
      <span style="font-size:19px">{{ jklDate($rDate) }}</span></div>
    <div class="sub" style="font-size:20px">Pahlawan: <b>{{ $toName }}</b></div>
    <p style="font-size:20px;color:#5a7a3a;margin-bottom:14px">Pilih pahlawanmu:</p>
    <div class="char-row" id="charRow"></div>
    <div class="controls-help">
      <b>WASD / Panah</b> untuk jalan &mdash; <b>E / Spasi</b> untuk berinteraksi<br>
      Di HP: pakai D-pad + tombol AKSI<br>
      Jelajahi desa, bicara dengan warga, dan kunjungi <b>7 lokasi</b> untuk membuka semua undangan!
    </div>
    <button class="pq-btn green" id="startBtn" style="font-size:13px">MULAI BERMAIN</button>
  </div>
</div>

<div id="game" hidden>
  <canvas id="cv"></canvas>
  <div id="hud">
    <div id="questLog">
      <h3><span class="qb">&#9998;</span> QUESTS</h3>
      <div class="qnext" id="questNext">Next: <b>Jelajahi desa</b></div>
      <div class="qitem" id="qVisit"><span class="box"></span><span>Kunjungi 7 lokasi (<span id="qVisitN">0</span>/7)</span></div>
      <div class="qitem" id="qRsvp"><span class="box"></span><span>Konfirmasi kehadiran</span></div>
      <div class="qitem" id="qWish"><span class="box"></span><span>Kirim doa restu</span></div>
      <div class="qitem" id="qGift"><span class="box"></span><span>Kunjungi Toko Senjata</span></div>
      <div class="pips" id="pips" style="margin-top:9px"></div>
    </div>
    <div>
      <div id="logoMini">PIXEL QUEST<span>WEDDING ADVENTURE</span></div>
      <button id="musicBtn" title="Musik">&#9834;</button>
    </div>
  </div>
  <div id="hint" hidden></div>
  <div id="dpad">
    <span></span><button class="dbtn" data-dx="0" data-dy="-1">&#9650;</button><span></span>
    <button class="dbtn" data-dx="-1" data-dy="0">&#9664;</button><span></span><button class="dbtn" data-dx="1" data-dy="0">&#9654;</button>
    <span></span><button class="dbtn" data-dx="0" data-dy="1">&#9660;</button><span></span>
  </div>
  <button id="actBtn">AKSI</button>
  <div id="dialog">
    <div id="dlgFace"><canvas id="dlgFaceCv" width="66" height="66"></canvas></div>
    <div id="dlgName"></div>
    <div id="dlgText"></div>
    <div id="dlgNext">KETUK &#9660;</div>
  </div>
</div>

<div id="panelOverlay" hidden>
  <div id="panel" class="wood-panel">
    <button id="panelClose">&#10005;</button>
    <div id="panelBody"></div>
  </div>
</div>
<div id="toast"></div>
<script>
"use strict";
/* ================= DATA ================= */
const MC = {
  invId: {{ (int)$invId }},
  countdownISO: @json($countdownISO),
  musicUrl: @json($musicUrl),
  guestQr: @json(!empty($guestQrCode) ? $guestQrCode : ''),
  guestQrOn: {{ jklGet('guest_qr_enabled') ? 'true' : 'false' }},
  musVol: @json($musVol),
  musAuto: @json($musAuto),
  brideNick: @json($brideNick), groomNick: @json($groomNick),
  brideFull: @json($brideFull), groomFull: @json($groomFull),
  guestName: @json($toName),
  dateText: @json(jklDate($rDate)),
  akadDate: @json(jklDate($wDate)),
  akadTime: @json($wTime.' – '.$wTimeE.' '.$tzLabel),
  resepsiDate: @json(jklDate($rDate)),
  resepsiTime: @json($rTimeS.' – '.$rTimeE.' '.$tzLabel),
  akadVenue: @json(jklGet('wedding_location','Kediaman Mempelai')),
  resepsiVenue: @json(jklGet('reception_location', jklGet('wedding_location','Kediaman Mempelai'))),
  akadMaps: @json($sameLoc ? $mainMapLink : jklGet('wedding_map_link','')),
  resepsiMaps: @json($sameLoc ? $mainMapLink : jklGet('reception_map_link','')),
  liveStream: @json(jklGet('live_stream_link', '')),
  liveEmbed: @json($liveEmbed),
  giftRecipient: @json($giftRecipient),
  qrisEnabled: @json(jklGet('gift_qris_enabled', '') === '1' && jklGet('gift_qris_image', '') !== ''),
  qrisImage: @json(jklGet('gift_qris_image', '')),
  qrisName: @json(jklGet('gift_qris_name', '')),
  giftAddress: @json(jklGet('gift_address', '')),
  giftMaps: @json(jklGet('gift_maps_link', '')),
  brideParents: @json(jklGet('bride_parents', '')),
  brideIg: @json(jklGet('bride_instagram', '')),
  groomIg: @json(jklGet('groom_instagram', '')),
  quotes: @json(jklGet('quotes', '')),
  groomParents: @json(jklGet('groom_parents', '')),
  brideChild: @json(jklGet('bride_child_of', 'Putri')),
  groomChild: @json(jklGet('groom_child_of', 'Putra')),
  quotes: @json(jklGet('quotes', '')),
  accounts: @json($accounts),
  gallery: @json($gallery),
  stories: @json($stories),
  gcalUrl: @json($gcalUrl)
};
const $=s=>document.querySelector(s), $$=s=>Array.from(document.querySelectorAll(s));
const reduced=matchMedia("(prefers-reduced-motion: reduce)").matches;
function toast(m){const t=$("#toast");t.textContent=m;t.classList.add("show");clearTimeout(t._h);t._h=setTimeout(()=>t.classList.remove("show"),2600);}
function esc(s){return String(s??"").replace(/[&<>"']/g,c=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"}[c]));}
function timeAgo(s){const d=new Date(String(s).replace(" ","T")+":00");if(isNaN(d))return "";
  const n=(Date.now()-d.getTime())/1000;if(n<60)return"Baru saja";
  if(n<3600)return Math.floor(n/60)+" menit yang lalu";
  if(n<86400)return Math.floor(n/3600)+" jam yang lalu";
  return Math.floor(n/86400)+" hari yang lalu";}

/* ================= SPRITE PIXEL ================= */
function px(c,x,y,w,h,col){c.fillStyle=col;c.fillRect(Math.round(x),Math.round(y),Math.ceil(w),Math.ceil(h));}
/* Pahlawan: rambut biru, jubah merah, pedang */
function drawHero(c,x,y,dir,frame,moving,o,s){
  c.save();c.translate(Math.round(x),Math.round(y));
  px(c,-9*s,13*s,18*s,3.5*s,"rgba(0,0,0,.25)");
  const legSwing=moving&&!reduced?(frame?1:-1)*3*s:0;
  /* sepatu bot */
  px(c,-6*s,11*s+Math.max(0,legSwing),5*s,2.5*s,"#2b1a0c");
  px(c,1*s,11*s+Math.max(0,-legSwing),5*s,2.5*s,"#2b1a0c");
  px(c,-6*s,6*s+Math.max(0,legSwing),5*s,7*s-Math.max(0,legSwing),o.pants);
  px(c,1*s,6*s+Math.max(0,-legSwing),5*s,7*s-Math.max(0,-legSwing),o.pants);
  /* jubah merah + lipatan */
  px(c,-9*s,-3*s,18*s,11*s,o.cape);
  px(c,-9*s,6*s,18*s,3*s,o.capeDark);
  px(c,-6*s,-3*s,2*s,11*s,o.capeDark);
  px(c,4*s,-3*s,2*s,11*s,"rgba(255,255,255,.14)");
  /* badan + sabuk */
  px(c,-8*s,-2*s,16*s,9*s,o.shirt);
  px(c,-8*s,-2*s,3*s,9*s,"rgba(255,255,255,.18)");
  px(c,-8*s,4*s,16*s,2.5*s,"#5d3a1f");
  px(c,-1.5*s,4*s,3*s,2.5*s,"#ffd93b");
  /* tangan + pedang */
  const armSwing=moving&&!reduced?(frame?-1:1)*2*s:0;
  px(c,-11*s,-1*s,3.5*s,7*s+armSwing*.4,o.shirt);
  px(c,-11*s,6*s+armSwing*.4,3.5*s,3*s,o.skin);
  px(c,7.5*s,-1*s,3.5*s,7*s-armSwing*.4,o.shirt);
  px(c,7.5*s,6*s-armSwing*.4,3.5*s,3*s,o.skin);
  if(dir!=="up"){
    px(c,11*s,-6*s,2.5*s,14*s,"#d9d9d9");
    px(c,11*s,-6*s,1*s,14*s,"#ffffff");          /* kilau bilah */
    px(c,10*s,-7*s,4.5*s,2.5*s,"#8a5f3c");
    px(c,9*s,-2*s,7*s,2*s,"#ffd93b");
    px(c,12*s,7*s,2*s,2*s,"#8a5f3c");
  }
  /* kepala + rambut biru + highlight */
  px(c,-7*s,-13*s,14*s,11*s,o.skin);
  px(c,-7*s,-13*s,14*s,4*s,o.hair);
  px(c,-5*s,-13*s,6*s,1.5*s,"rgba(255,255,255,.35)");   /* highlight rambut */
  if(dir==="down"){px(c,-7*s,-13*s,3*s,9*s,o.hair);px(c,4*s,-13*s,3*s,9*s,o.hair);}
  else if(dir==="left"){px(c,-7*s,-13*s,9*s,4*s,o.hair);}
  else if(dir==="right"){px(c,-1*s,-13*s,8*s,4*s,o.hair);}
  else{px(c,-7*s,-13*s,14*s,11*s,o.hair);px(c,-5*s,-13*s,6*s,1.5*s,"rgba(255,255,255,.35)");}
  /* pipi merona */
  px(c,-6*s,-3.5*s,2*s,1.5*s,"rgba(255,120,120,.5)");
  px(c,4*s,-3.5*s,2*s,1.5*s,"rgba(255,120,120,.5)");
  /* mata + kilau */
  px(c,-4*s,-6.5*s,2.4*s,2.8*s,"#141414");
  px(c,-3.6*s,-6.5*s,1*s,1*s,"#ffffff");
  if(dir==="down"){px(c,1.6*s,-6.5*s,2.4*s,2.8*s,"#141414");px(c,2*s,-6.5*s,1*s,1*s,"#ffffff");}
  else if(dir==="left"){px(c,-5.5*s,-6.5*s,2.4*s,2.8*s,"#141414");px(c,-5.1*s,-6.5*s,1*s,1*s,"#ffffff");}
  else if(dir==="right"){px(c,3.1*s,-6.5*s,2.4*s,2.8*s,"#141414");px(c,3.5*s,-6.5*s,1*s,1*s,"#ffffff");}
  c.restore();
}
/* Wajah pixel untuk dialog */
function drawFace(cv,o,cape){
  const s=cv.width/8,c=cv.getContext("2d");
  for(let y=0;y<8;y++)for(let x=0;x<8;x++){px(c,x*s,y*s,s,s,o.skin);}
  const hair=(x0,x1,y)=>{for(let x=x0;x<=x1;x++)px(c,x*s,y*s,s,s,o.hair);};
  if(o.style==="bride"){for(let y=0;y<8;y++){px(c,0,y*s,s,s,"#f5f5f5");px(c,7*s,y*s,s,s,"#f5f5f5");}
    for(let x=1;x<7;x++)px(c,x*s,0,s,s,"#f5f5f5");
    hair(1,6,1);hair(1,1,2);hair(6,6,2);hair(1,1,3);hair(6,6,3);}
  else if(o.style==="wizard"){for(let x=1;x<7;x++)px(c,x*s,0,s,s,"#6a3fb5");
    px(c,2*s,1*s,s,s,"#6a3fb5");px(c,5*s,1*s,s,s,"#6a3fb5");
    px(c,3*s,1*s,s,s,"#ffd93b");px(c,4*s,1*s,s,s,"#ffd93b");
    hair(0,7,2);hair(0,0,3);hair(7,7,3);}
  else if(o.style==="shop"){hair(0,7,0);hair(0,7,1);hair(0,1,2);hair(6,7,2);
    px(c,0,2*s,s,s,o.hair);px(c,7*s,2*s,s,s,o.hair);
    for(let x=0;x<8;x++)px(c,x*s,3*s,s,s,o.hair);}
  else{hair(0,7,0);hair(0,7,1);hair(0,1,2);hair(6,7,2);hair(0,0,3);hair(7,7,3);}
  px(c,2*s,3*s,s,s,"#141414");px(c,5*s,3*s,s,s,"#141414");
  px(c,3*s,5*s,2*s,s,"#7d2d2d");
  if(cape)for(let x=0;x<8;x++)px(c,x*s,7*s,s,s,cape);
  else for(let x=0;x<8;x++)px(c,x*s,7*s,s,s,o.shirt);
  px(c,3*s,6*s,2*s,s,o.skin);
}
/* Penjaga toko: peri rambut merah, apron hijau */
function drawShopkeeper(c,x,y,o,s,blink){
  c.save();c.translate(Math.round(x),Math.round(y));
  px(c,-8*s,12*s,16*s,3*s,"rgba(0,0,0,.25)");
  px(c,-6*s,11*s,12*s,2.5*s,"#2b1a0c");
  px(c,-6*s,6*s,12*s,7*s,"#7a6a4a");
  px(c,-7*s,-2*s,14*s,9*s,"#4e9a26");
  px(c,-7*s,-2*s,14*s,3*s,"#2b6e1e");
  px(c,1*s,3*s,5*s,4*s,"#2b6e1e");               /* kantong apron */
  px(c,2*s,4*s,3*s,1.5*s,"#ffd93b");
  px(c,-6*s,-13*s,12*s,11*s,"#f0c49c");
  px(c,-7*s,-13*s,14*s,4*s,"#e03131");
  px(c,-4*s,-13*s,5*s,1.5*s,"rgba(255,255,255,.35)"); /* highlight rambut */
  px(c,-7*s,-13*s,2*s,9*s,"#e03131");px(c,5*s,-13*s,2*s,9*s,"#e03131");
  px(c,-8*s,-15*s,3*s,5*s,"#e03131");px(c,5*s,-15*s,3*s,5*s,"#e03131");
  px(c,-7.5*s,-14*s,2*s,2*s,"#f0c49c");px(c,5.5*s,-14*s,2*s,2*s,"#f0c49c"); /* telinga dalam */
  px(c,-3*s,-7*s,2*s,2.5*s,"#141414");px(c,1*s,-7*s,2*s,2.5*s,"#141414");
  px(c,-2.6*s,-7*s,1*s,1*s,"#ffffff");px(c,1.4*s,-7*s,1*s,1*s,"#ffffff");
  px(c,-2*s,-4*s,4*s,1.5*s,"#7d2d2d");
  px(c,-6*s,-3.5*s,2*s,1.5*s,"rgba(255,120,120,.5)");
  px(c,4*s,-3.5*s,2*s,1.5*s,"rgba(255,120,120,.5)");
  c.restore();
}
/* Penyihir pendamping: topi ungu berbintang, sayap biru */
function drawWizard(c,x,y,t,s){
  const bob=Math.sin(t*3)*2*s;
  c.save();c.translate(Math.round(x),Math.round(y+bob));
  /* sayap */
  const flap=Math.sin(t*10)*3*s;
  px(c,-20*s,-8*s,12*s,10*s-flap*.3,"#7cc4ff");
  px(c,-18*s,-5*s,8*s,6*s,"#bfe6ff");
  px(c,8*s,-8*s,12*s,10*s-flap*.3,"#7cc4ff");
  px(c,10*s,-5*s,8*s,6*s,"#bfe6ff");
  px(c,-7*s,12*s,14*s,3*s,"rgba(0,0,0,.25)");
  /* jubah + lipatan */
  px(c,-7*s,0,14*s,13*s,"#3b2d7a");
  px(c,-7*s,0,14*s,4*s,"#6a3fb5");
  px(c,-4*s,2,2*s,11*s,"#2b1f5e");
  px(c,2*s,2,2*s,11*s,"rgba(255,255,255,.12)");
  /* kepala */
  px(c,-6*s,-10*s,12*s,10*s,"#f0c49c");
  px(c,-6*s,-10*s,12*s,3*s,"#f5f5f5");
  px(c,-3*s,-4*s,2*s,2.5*s,"#141414");px(c,1*s,-4*s,2*s,2.5*s,"#141414");
  /* topi penyihir */
  px(c,-8*s,-14*s,16*s,2*s,"#6a3fb5");
  px(c,-5*s,-26*s,10*s,13*s,"#6a3fb5");
  px(c,-3*s,-22*s,2*s,2*s,"#ffd93b");px(c,2*s,-18*s,2*s,2*s,"#ffd93b");
  px(c,0,-24*s,2*s,2*s,"#ffd93b");px(c,-1*s,-16*s,1.5*s,1.5*s,"#ffd93b");
  /* tongkat */
  px(c,9*s,-14*s,2*s,24*s,"#8a5f3c");
  px(c,8*s,-17*s,4*s,4*s,"#ffd93b");
  c.restore();
}
/* Slime hijau melompat */
function drawSlime(c,x,y,t,s,col){
  const hop=Math.abs(Math.sin(t*2.4))*4*s;
  c.save();c.translate(Math.round(x),Math.round(y-hop));
  px(c,-8*s,8*s,16*s,3*s,"rgba(0,0,0,.2)");
  px(c,-9*s,-2*s,18*s,10*s,col);
  px(c,-9*s,0,18*s,3*s,"rgba(255,255,255,.35)");
  px(c,-4*s,1*s,3*s,4*s,"#141414");px(c,1*s,1*s,3*s,4*s,"#141414");
  px(c,-2*s,6*s,4*s,1.5*s,"#141414");
  c.restore();
}
/* Ikon senjata pixel untuk kartu toko */
const WEAPON_ICONS={
  sword:["....KK....","....KK....","....KK....","....KK....","....KK....","....KK....","....KK....",
         "..KKKKKK..","....KK....","....KK....","..K.KK.K..","...KKKK...","....KK....","....KK....","...KKKK...","....KK...."],
  axe:["..KKKKKKKK..",".KKKKKKKKKK.",".KKK.KKKKKK.","..KKKKKKKK..","....KKKK....","....KK......",
       "....KK......","....KK......","....KK......","....KK......","....KK......","....KK......",
       "....KK......","....KK......","...KKKKK....","....KKK....."],
  staff:["...YYYYY....","..YYYYYYY...","...YYYYY....","....KK......","....KK......","....KK......",
         "....KK......","....KK......","....KK......","....KK......","....KK......","....KK......",
         "....KK......","....KK......","...KKKKK....","....KKK....."],
  bow:[".KKKKKKKKK..","KK..KK...KK.","K....KK....K","K.....KK...K.","K......KK.K..","K.......KK..","K......KK.K..","K.....KK...K.",
       "K....KK....K","KK..KK...KK.",".KKKKKKKKK..",".....KK.....",".....KK.....",".....KK.....",".....KK.....","....KKKK...."]
};
function drawWeaponIcon(cv,kind){
  const map=WEAPON_ICONS[kind]||WEAPON_ICONS.sword;
  const s=cv.width/map[0].length, c=cv.getContext("2d");
  const pal={K:"#8a8a8a",Y:"#ffd93b"};
  c.clearRect(0,0,cv.width,cv.height);
  map.forEach((row,y)=>{[...row].forEach((ch,x)=>{if(pal[ch]){c.fillStyle=pal[ch];
    c.fillRect(Math.floor(x*s),Math.floor(y*s),Math.ceil(s),Math.ceil(s));}});});
}

/* ================= MUSIK ================= */
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
function toggleMusic(){
  musicOn=!musicOn;$("#musicBtn").innerHTML=musicOn?"&#9835;":"&#9834;";
  if(musicOn){
    if(MC.musicUrl&&!audioEl){audioEl=new Audio(MC.musicUrl);audioEl.loop=true;audioEl.volume=(MC.musVol||50)/100;
      audioEl.addEventListener("error",()=>{useFile=false;audioEl=null;mstep=0;playStep();});}
    if(audioEl&&MC.musicUrl){useFile=true;audioEl.play().catch(()=>{useFile=false;mstep=0;playStep();});}
    else{try{ac().resume();}catch(e){}mstep=0;playStep();}
  }else{clearTimeout(musicTimer);if(audioEl)audioEl.pause();}
  blip(musicOn?880:440,0.08,0.05);
}
$("#musicBtn").addEventListener("click",e=>{e.stopPropagation();toggleMusic();toast(musicOn?"MUSIK: ON":"MUSIK: OFF");});

/* ================= PILIH KARAKTER ================= */
const CHARS=[
  {name:"BIRU",  skin:"#f0c49c",hair:"#3b5bff",shirt:"#3f8fd1",pants:"#2b2b6e",cape:"#e03131",capeDark:"#a02121"},
  {name:"MERAH", skin:"#c98d5f",hair:"#e03131",shirt:"#e8a33d",pants:"#5a3a1e",cape:"#3f8fd1",capeDark:"#2b6cb0"},
  {name:"HIJAU", skin:"#8a5a3a",hair:"#1e7a1e",shirt:"#4e9a26",pants:"#2b2b2b",cape:"#a78bda",capeDark:"#7a5cc9"}
];
let charIdx=0;
function renderChars(){
  const row=$("#charRow");row.innerHTML="";
  CHARS.forEach((c,i)=>{
    const d=document.createElement("div");d.className="char-opt"+(i===charIdx?" sel":"");
    const cv=document.createElement("canvas");cv.width=32;cv.height=44;
    drawHero(cv.getContext("2d"),16,32,"down",0,false,c,1.15);
    d.appendChild(cv);d.insertAdjacentHTML("beforeend",`<span>${c.name}</span>`);
    d.addEventListener("click",()=>{charIdx=i;blip(700,0.06,0.05);renderChars();});
    row.appendChild(d);});
}
renderChars();

/* ================= DUNIA ================= */
const TILE=32,WT=52,HT=39,WW=WT*TILE,WH=HT*TILE;
const cv=$("#cv"),ctx=cv.getContext("2d");
let VW=0,VH=0,camX=0,camY=0,frame=0,lastT=0;
let DPR=1;
function sizeCanvas(){DPR=Math.min(2,window.devicePixelRatio||1);VW=innerWidth;VH=innerHeight;
  cv.width=Math.round(VW*DPR);cv.height=Math.round(VH*DPR);}
const LOCS=[
  {id:"couple",  name:"RUMAH MEMPELAI", short:"MEMPELAI", tx:5,  ty:4,  w:9,h:6, wall:"#f5e6c8", roof:"#c0392b", kind:"house",  door:{x:9,y:9}},
  {id:"count",   name:"MENARA JAM",     short:"JAM",     tx:39, ty:4,  w:5,h:7, wall:"#d8c9a0", roof:"#5d3a1f", kind:"tower",  door:{x:41,y:10}},
  {id:"acara",   name:"KAPEL",          short:"ACARA",   tx:39, ty:15, w:9,h:7, wall:"#fff8e7", roof:"#3f8fd1", kind:"chapel", door:{x:43,y:21}},
  {id:"gift",    name:"TOKO SENJATA",   short:"TOKO",    tx:5,  ty:15, w:10,h:6, wall:"#c9a86a", roof:"#a02121", kind:"shop",   door:{x:10,y:20}},
  {id:"gallery", name:"GALERI KENANGAN",short:"GALERI",  tx:5,  ty:26, w:9,h:6, wall:"#d8b46a", roof:"#4e9a26", kind:"barn",   door:{x:9,y:31}},
  {id:"rsvp",    name:"PENGINAPAN",     short:"RSVP",    tx:39, ty:26, w:8,h:6, wall:"#f5e6c8", roof:"#e8a33d", kind:"house",  door:{x:43,y:31}},
  {id:"ucapan",  name:"PAPAN UCAPAN",   short:"UCAPAN",  tx:22, ty:30, w:8,h:3, wall:"#8a5f3c", roof:"#8a5f3c", kind:"board",  door:{x:26,y:32}}
];
const solids=[];
function solidRect(tx,ty,w,h){solids.push({x:tx*TILE,y:ty*TILE,w:w*TILE,h:h*TILE});}
const PATHS=[
  {x:24,y:0,w:4,h:39},{x:0,y:20,w:52,h:3},
  {x:7,y:7,w:4,h:9},{x:39,y:8,w:4,h:8},
  {x:7,y:23,w:4,h:4},{x:41,y:23,w:4,h:4},
  {x:24,y:23,w:4,h:11},{x:24,y:7,w:4,h:4},
  {x:10,y:31,w:16,h:3},{x:26,y:31,w:16,h:3}
];
function inRect(px,py,r){return px>=r.x&&px<r.x+r.w&&py>=r.y&&py<r.y+r.h;}
const pond={x:33*TILE,y:33*TILE,rx:4*TILE,ry:2.4*TILE};
const trees=[{tx:2,ty:2},{tx:48,ty:2},{tx:2,ty:36},{tx:48,ty:36},{tx:16,ty:2},{tx:34,ty:2},
  {tx:16,ty:36},{tx:30,ty:36},{tx:2,ty:12},{tx:2,ty:24},{tx:48,ty:12},{tx:48,ty:24},{tx:18,ty:12},{tx:32,ty:12}];
const flowers=[];
for(let i=0;i<70;i++)flowers.push({tx:Math.floor(Math.random()*WT),ty:Math.floor(Math.random()*HT),
  c:["#ff5b5b","#fcee5e","#ffffff","#ff9ff3","#ffb7c5"][i%5]});
const slimes=[{tx:15,ty:24,col:"#7ed957"},{tx:34,ty:9,col:"#9adcff"},{tx:45,ty:33,col:"#ffb7c5"}];
let groundCv=null;
function shade(hex,amt){const n=parseInt(hex.slice(1),16);
  const r=Math.max(0,Math.min(255,((n>>16)&255)+amt)),g=Math.max(0,Math.min(255,((n>>8)&255)+amt)),
        b=Math.max(0,Math.min(255,(n&255)+amt));return `rgb(${r},${g},${b})`;}
function drawTree(g,x,y){
  px(g,x-2,y+18,8,12,"#5d3a1f");px(g,x+2,y+18,4,12,"#7a4a2a");
  px(g,x-24,y-14,48,26,"#1e5a18");               /* bayangan kanopi */
  px(g,x-22,y-22,44,34,"#2b6e1e");
  px(g,x-16,y-32,32,24,"#3f9a2a");
  px(g,x-12,y-38,14,10,"#5eb83d");
  px(g,x+6,y-30,10,8,"#5eb83d");
  px(g,x-8,y-36,10,6,"rgba(255,255,255,.22)");    /* highlight daun */
  px(g,x-14,y-18,4,4,"#ff5b5b");px(g,x+8,y-22,4,4,"#ff5b5b");   /* buah */
  px(g,x+2,y-12,4,4,"#ffd93b");
}
function drawBuilding(g,L){
  const X=L.tx*TILE,Y=L.ty*TILE,Wd=L.w*TILE,Ht=L.h*TILE;
  px(g,X+6,Y+Ht-4,Wd,8,"rgba(0,0,0,.22)");
  if(L.kind==="board"){
    px(g,X,Y,Wd,Ht,"#8a5f3c");px(g,X+4,Y+4,Wd-8,Ht-8,"#fff8e7");
    px(g,X+14,Y+14,10,8,"#ffd93b");px(g,X+30,Y+14,10,8,"#ff9ff3");px(g,X+46,Y+14,10,8,"#7cc4ff");
    px(g,X+14,Y+30,10,8,"#7ed957");px(g,X+30,Y+30,10,8,"#ff5b5b");px(g,X+46,Y+30,10,8,"#fcee5e");
    px(g,X+Wd/2-4,Y-16,8,16,"#5d3a1f");
    px(g,X+Wd/2-20,Y-24,40,10,"#5d3a1f");
    return;
  }
  /* dinding */
  px(g,X,Y,Wd,Ht,L.wall);
  px(g,X,Y,Wd,6,shade(L.wall,18));
  px(g,X,Y+Ht-8,Wd,8,shade(L.wall,-14));
  /* atap */
  if(L.kind==="tower"){
    px(g,X-6,Y-10,Wd+12,16,L.roof);
    px(g,X-2,Y-26,12,16,"#8a8a8a");px(g,X+2,Y-22,4,4,"#ffd93b");
    /* jam */
    px(g,X+Wd/2-16,Y+Ht/2-30,32,32,"#fff8e7");
    px(g,X+Wd/2-16,Y+Ht/2-30,32,3,"#5d3a1f");px(g,X+Wd/2-16,Y+Ht/2-30,3,32,"#5d3a1f");
    px(g,X+Wd/2-16,Y+Ht/2-3,32,3,"#5d3a1f");px(g,X+Wd/2+13,Y+Ht/2-30,3,32,"#5d3a1f");
    px(g,X+Wd/2-1,Y+Ht/2-14,2,14,"#e03131");
    px(g,X+Wd/2-1,Y+Ht/2-1,10,2,"#e03131");
    px(g,X+Wd/2-3,Y+Ht/2-3,6,6,"#5d3a1f");
    px(g,X+Wd/2-12,Y+Ht/2+8,24,10,"#5d3a1f");
    px(g,X+Wd/2-8,Y+Ht/2+10,16,3,"#ffd93b");
  } else if(L.kind==="chapel"){
    px(g,X-8,Y-6,Wd+16,14,L.roof);
    px(g,X+Wd/2-4,Y-34,8,34,"#8a5f3c");
    px(g,X+Wd/2-14,Y-30,28,6,"#8a5f3c");
    px(g,X+Wd/2-16,Y+Ht/2-34,32,44,"#3f8fd1");
    px(g,X+Wd/2-10,Y+Ht/2-26,20,28,"#bfe6ff");
    px(g,X+Wd/2-2,Y+Ht/2-26,4,28,"#3f8fd1");
    px(g,X+Wd/2-10,Y+Ht/2-14,20,4,"#3f8fd1");
  } else {
    px(g,X-8,Y-6,Wd+16,16,L.roof);
    px(g,X-4,Y+8,Wd+8,3,shade(L.roof,22));
    for(let r=0;r<2;r++)px(g,X-8,Y+2+r*6,Wd+16,1.5,shade(L.roof,-18)); /* garis genteng */
  }
  px(g,X,Y+Ht-2,Wd,2,"#8a8a8a"); /* fondasi batu */
  /* pintu */
  const dx=L.door.x*TILE,dy=L.door.y*TILE;
  px(g,dx-14,dy-8,28,10+((L.ty+L.h)*TILE-dy),"#5d3a1f");
  px(g,dx-10,dy-4,20,10+((L.ty+L.h)*TILE-dy)-2,"#7a4a2a");
  px(g,dx+6,dy+6,3,3,"#ffd93b");
  /* jendela */
  px(g,X+16,Y+20,26,22,"#bfe6ff");px(g,X+16,Y+20,26,3,"#5d3a1f");px(g,X+16,Y+39,26,3,"#5d3a1f");
  px(g,X+Wd-42,Y+20,26,22,"#bfe6ff");px(g,X+Wd-42,Y+20,26,3,"#5d3a1f");px(g,X+Wd-42,Y+39,26,3,"#5d3a1f");
  /* detail per jenis */
  if(L.kind==="shop"){
    px(g,X+Wd/2-44,Y-26,88,18,"#5d3a1f");
    px(g,X+Wd/2-40,Y-23,80,12,"#ffd93b");
    px(g,X+Wd/2-16,Y-22,10,10,"#c0c0c0");px(g,X+Wd/2+2,Y-22,10,10,"#c0c0c0");
    /* pedang pajangan */
    px(g,X+20,Y+56,3,26,"#c0c0c0");px(g,X+34,Y+56,3,26,"#c0c0c0");
    px(g,X+Wd-38,Y+56,3,26,"#c0c0c0");px(g,X+Wd-24,Y+56,3,26,"#c0c0c0");
    px(g,X+18,Y+80,8,3,"#ffd93b");px(g,X+Wd-28,Y+80,8,3,"#ffd93b");
    /* kotak bunga */
    px(g,X+14,Y+Ht-22,44,10,"#8a5f3c");
    px(g,X+20,Y+Ht-30,8,8,"#ff5b5b");px(g,X+32,Y+Ht-30,8,8,"#fcee5e");px(g,X+44,Y+Ht-30,8,8,"#ffffff");
    px(g,X+Wd-58,Y+Ht-22,44,10,"#8a5f3c");
    px(g,X+Wd-52,Y+Ht-30,8,8,"#ff9ff3");px(g,X+Wd-40,Y+Ht-30,8,8,"#ff5b5b");px(g,X+Wd-28,Y+Ht-30,8,8,"#fcee5e");
  }
  if(L.kind==="house"){
    px(g,X+Wd/2-14,Y+Ht-40,28,14,"#e03131");
    px(g,X+Wd/2-9,Y+Ht-34,18,6,"#ffd93b");
  }
  if(L.kind==="barn"){
    px(g,X+Wd/2-18,Y+20,36,30,"#fff8e7");
    px(g,X+Wd/2-12,Y+26,24,18,"#8a5f3c");
  }
}
const SS=2; /* supersample ground agar tajam di layar HD */
function buildGround(){
  groundCv=document.createElement("canvas");groundCv.width=WW*SS;groundCv.height=WH*SS;
  const g=groundCv.getContext("2d");g.scale(SS,SS);
  for(let ty=0;ty<HT;ty++)for(let tx=0;tx<WT;tx++){
    g.fillStyle=(tx+ty)%2?"#79c25f":"#6db854";
    g.fillRect(tx*TILE,ty*TILE,TILE,TILE);
    if((tx*7+ty*13)%29===0){g.fillStyle="#6db854";g.fillRect(tx*TILE+8,ty*TILE+10,16,6);}
  }
  for(const r of PATHS){g.fillStyle="#c9a86a";g.fillRect(r.x*TILE,r.y*TILE,r.w*TILE,r.h*TILE);
    for(let i=0;i<r.w*r.h*2;i++){g.fillStyle="#b8945a";
      g.fillRect((r.x+Math.random()*r.w)*TILE,(r.y+Math.random()*r.h)*TILE,3,3);}}
  /* kolam */
  g.fillStyle="#3f8fd1";
  g.beginPath();g.ellipse(pond.x,pond.y,pond.rx,pond.ry,0,0,6.29);g.fill();
  g.fillStyle="#7cc4ff";
  g.beginPath();g.ellipse(pond.x-20,pond.y-10,pond.rx*.6,pond.ry*.5,0,0,6.29);g.fill();
  /* bunga liar */
  for(const f of flowers){g.fillStyle=f.c;g.fillRect(f.tx*TILE+12,f.ty*TILE+12,5,5);
    g.fillStyle="#4e9a26";g.fillRect(f.tx*TILE+14,f.ty*TILE+16,2,6);}
  for(const t of trees)drawTree(g,t.tx*TILE+16,t.ty*TILE+28);
  for(const L of LOCS)drawBuilding(g,L);
  for(const L of LOCS)solidRect(L.tx,L.ty,L.w,L.h);
}
/* ---------- pemain ---------- */
const player={x:26*TILE,y:34*TILE,dir:"up",moving:false,anim:0,speed:2.4};
const keys={};
let joyX=0,joyY=0;
function collides(nx,ny){
  const r=18;
  if(nx<8||ny<8||nx>WW-8||ny>WH-8)return true;
  for(const s of solids){if(nx+r>s.x&&nx-r<s.x+s.w&&ny+r>s.y&&ny-r<s.y+s.h)return true;}
  for(const t of trees){const tx=t.tx*TILE+16,ty=t.ty*TILE+28;
    if(Math.abs(nx-tx)<16&&Math.abs(ny-ty)<14)return true;}
  const dx=nx-pond.x,dy=ny-pond.y;
  if((dx*dx)/(pond.rx*pond.rx)+(dy*dy)/(pond.ry*pond.ry)<1)return true;
  return false;
}
function nearestInteract(){
  let best=null,bd=1e9;
  for(const L of LOCS){
    const dx=(L.door.x*TILE+16)-player.x,dy=(L.door.y*TILE+16)-player.y,d=Math.hypot(dx,dy);
    if(d<96&&d<bd){bd=d;best={type:"loc",loc:L};}
  }
  const sx=10*TILE+16,sy=22*TILE+16;
  const sd=Math.hypot(sx-player.x,sy-player.y);
  if(sd<96&&sd<bd){bd=sd;best={type:"shopkeeper"};}
  return best;
}
/* ---------- kamera + render ---------- */
function drawWorld(t){
  const g=ctx;
  g.setTransform(DPR,0,0,DPR,0,0);
  g.drawImage(groundCv,camX*SS,camY*SS,VW*SS,VH*SS,0,0,VW,VH);
  g.save();g.translate(-camX,-camY);
  /* slime */
  for(const s of slimes)drawSlime(g,s.tx*TILE+16,s.ty*TILE+16,t/1000,1,s.col);
  /* penjaga toko di depan toko */
  drawShopkeeper(g,10*TILE+16,22*TILE+16,CHARS[charIdx],1.5,false);
  /* penyihir pendamping mengikuti pemain */
  const wx=player.x+26+Math.sin(t/500)*6,wy=player.y-34+Math.cos(t/600)*4;
  drawWizard(g,wx,wy,t/1000,1.35);
  /* bubble bicara penjaga toko */
  if(!dialogOpen){
    const bx=10*TILE+16,by=22*TILE-34+Math.sin(t/400)*3;
    g.fillStyle="#fff";g.fillRect(bx-34,by-22,68,26);
    g.fillStyle="#fff";g.beginPath();g.moveTo(bx-6,by+4);g.lineTo(bx+6,by+4);g.lineTo(bx,by+12);g.fill();
    g.fillStyle="#2a1a0c";g.font="bold 13px monospace";g.textAlign="center";
    g.fillText("Senjata baru!",bx,by-4);
  }
  drawHero(g,player.x,player.y,player.dir,player.anim,player.moving,CHARS[charIdx],1.7);
  g.restore();
  /* nama lokasi melayang */
  g.save();g.translate(-camX,-camY);
  for(const L of LOCS){
    const cx=(L.tx+L.w/2)*TILE,cy=L.ty*TILE-14;
    g.fillStyle="rgba(20,14,6,.8)";
    const w=L.name.length*7+16;
    g.fillRect(cx-w/2,cy-8,w,18);
    g.fillStyle="#ffd93b";g.font="bold 11px monospace";g.textAlign="center";
    g.fillText(L.name,cx,cy+5);
  }
  g.restore();
}

/* ================= DIALOG ================= */
let dialogOpen=false, dialogLines=[], dialogIdx=0, dialogCb=null, dialogChar=0, dialogTimer=null;
function showDialog(name, lines, faceObj, cb){
  dialogOpen=true; dialogLines=lines; dialogIdx=0; dialogCb=cb||null; dialogChar=0;
  $("#dlgName").textContent=name;
  $("#dialog").classList.add("show");
  drawFace($("#dlgFaceCv"), faceObj||CHARS[charIdx], (faceObj||CHARS[charIdx]).cape);
  typeLine();
}
function typeLine(){
  clearTimeout(dialogTimer);
  const line=dialogLines[dialogIdx]||"", el=$("#dlgText");
  dialogChar=0; el.textContent="";
  (function step(){
    dialogChar++;
    el.textContent=line.slice(0,dialogChar);
    if(dialogChar<line.length && !reduced) dialogTimer=setTimeout(step,18);
    else if(dialogChar<line.length) el.textContent=line;
  })();
}
function advanceDialog(){
  if(!dialogOpen) return;
  const line=dialogLines[dialogIdx]||"";
  if(dialogChar<line.length){ dialogChar=line.length; $("#dlgText").textContent=line; return; }
  dialogIdx++;
  if(dialogIdx>=dialogLines.length){
    dialogOpen=false; $("#dialog").classList.remove("show");
    const cb=dialogCb; dialogCb=null; if(cb)cb();
  } else typeLine();
}
$("#dialog").addEventListener("click",advanceDialog);

/* ================= QUEST ================= */
const visited=new Set();
let rsvpDone=false, wishDone=false, giftVisited=false;
const LOC_DIALOGS={
  couple:[
    "Selamat datang di Rumah Mempelai!",
    "Di sini kamu bisa melihat siapa saja yang akan menikah.",
    "Ayo, kenalan dulu dengan kedua mempelai!"
  ],
  acara:[
    "KAPEL ini adalah tempat sakral pernikahan.",
    "Akad dan resepsi akan digelar di sini. Catat tanggalnya!"
  ],
  count:[
    "MENARA JAM ini menghitung mundur menuju Hari-H.",
    "Setiap detiknya mendekatkan kita ke hari bahagia!"
  ],
  gallery:[
    "GALERI KENANGAN menyimpan momen-momen indah.",
    "Lihatlah foto-foto perjalanan cinta mereka."
  ],
  rsvp:[
    "Selamat datang di PENGINAPAN!",
    "Silakan konfirmasi kehadiranmu di sini ya."
  ],
  ucapan:[
    "PAPAN UCAPAN tempat semua orang menuliskan doa.",
    "Tinggalkan pesan terbaikmu untuk kedua mempelai!"
  ],
  gift:[
    "Selamat datang di TOKO SENJATA!",
    "Pahlawan sepertimu pasti ingin memberi hadiah terbaik."
  ]
};
function updateQuestUI(){
  
  $("#qVisitN").textContent=visited.size;
  $("#qVisit").classList.toggle("done", visited.size>=7);
  $("#qRsvp").classList.toggle("done", rsvpDone);
  $("#qWish").classList.toggle("done", wishDone);
  $("#qGift").classList.toggle("done", giftVisited);
  const pips=$("#pips"); pips.innerHTML="";
  const icons=["&#127968;","&#9200;","&#9962;","&#9876;","&#128444;","&#127968;","&#128172;"];
  LOCS.forEach((L,i)=>{
    const d=document.createElement("div");
    d.className="pip"+(visited.has(L.id)?" on":"");
    d.innerHTML=visited.has(L.id)?"&#10003;":icons[i];
    pips.appendChild(d);
  });
  const next = visited.size<7 ? "Jelajahi desa" : !rsvpDone ? "Konfirmasi kehadiran" : !wishDone ? "Kirim doa restu" : "Quest selesai!";
  $("#questNext").innerHTML="Next: <b>"+next+"</b>";
}
function checkQuestComplete(){
  if(visited.size>=7){
    levelUp();
    setTimeout(()=>openPanel("complete"),600);
  }
}

/* ================= PANEL ================= */
function openPanel(id){
  const body=$("#panelBody");
  if(id==="couple"){
    body.innerHTML=`<div class="ptitle">RUMAH MEMPELAI</div><div class="psub">Kenalan dengan kedua mempelai</div>
    <div class="player-grid">
      <div class="pcard"><canvas id="faceBride" width="52" height="52"></canvas>
        <h3>${esc(MC.brideNick)}</h3><span class="ptag">MEMPELAI WANITA</span>
        <p>${esc(MC.brideChild)} dari<br>${esc(MC.brideParents)}</p>${MC.brideIg?`<a href="https://instagram.com/${esc(MC.brideIg.replace(/^@/,''))}" target="_blank" rel="noopener" class="pq-btn" style="display:inline-block;margin-top:8px;font-size:8px;padding:8px 10px">&#64;${esc(MC.brideIg.replace(/^@/,''))}</a>`:""}</div>
      <div class="pcard"><canvas id="faceGroom" width="52" height="52"></canvas>
        <h3>${esc(MC.groomNick)}</h3><span class="ptag">MEMPELAI PRIA</span>
        <p>${esc(MC.groomChild)} dari<br>${esc(MC.groomParents)}</p>${MC.groomIg?`<a href="https://instagram.com/${esc(MC.groomIg.replace(/^@/,''))}" target="_blank" rel="noopener" class="pq-btn" style="display:inline-block;margin-top:8px;font-size:8px;padding:8px 10px">&#64;${esc(MC.groomIg.replace(/^@/,''))}</a>`:""}</div>
    </div>
    <p style="font-size:20px;color:#5a4a30;text-align:center;margin-top:6px">${esc(MC.quotes||"")}</p>
    ${MC.guestQrOn&&MC.guestQr?`<div class="qr-card"><h2 class="ptitle" style="font-size:12px">KARTU TAMU</h2>
<p class="psub">Tunjukkan QR ini saat check-in di lokasi acara.</p>
<div id="qrBox" style="display:flex;justify-content:center"></div></div>`:""}`;
    drawFace($("#faceBride"),{skin:"#f0c49c",hair:"#2b2b2b",shirt:"#e8a2b0",style:"bride"});
    drawFace($("#faceGroom"),{skin:"#c98d5f",hair:"#2b2b2b",shirt:"#3f8fd1"});
    if(MC.guestQrOn&&MC.guestQr&&window.QRCode){
      new QRCode($("#qrBox"),{text:MC.guestQr,width:180,height:180,correctLevel:QRCode.CorrectLevel.M});
    }
  }
  else if(id==="acara"){
    body.innerHTML=`<div class="ptitle">KAPEL PERNIKAHAN</div><div class="psub">Akad &amp; Resepsi</div>
    ${MC.quotes?`<p style="font-style:italic;text-align:center;margin:0 0 12px;font-size:11px;line-height:1.7">&ldquo;${esc(MC.quotes)}&rdquo;</p>`:""}
    <div class="adv"><div class="ic"><canvas id="icA1" width="44" height="44"></canvas></div><div>
      <h3>AKAD NIKAH</h3><p>${esc(MC.akadDate)}</p><p class="tm">${esc(MC.akadTime)}</p>
      <p>${esc(MC.akadVenue)}</p>
      ${MC.akadMaps?`<a href="${esc(MC.akadMaps)}" target="_blank" rel="noopener" class="pq-btn gold" style="display:inline-block;margin-top:8px;font-size:9px;padding:10px 14px">LIHAT LOKASI</a>`:""}
    </div></div>
    <div class="adv"><div class="ic"><canvas id="icA2" width="44" height="44"></canvas></div><div>
      <h3>RESEPSI</h3><p>${esc(MC.resepsiDate)}</p><p class="tm">${esc(MC.resepsiTime)}</p>
      <p>${esc(MC.resepsiVenue)}</p>
      ${MC.resepsiMaps?`<a href="${esc(MC.resepsiMaps)}" target="_blank" rel="noopener" class="pq-btn gold" style="display:inline-block;margin-top:8px;font-size:9px;padding:10px 14px">LIHAT LOKASI</a>`:""}
      ${MC.liveStream?`<div style="margin-top:10px;border:3px solid #0f380f;box-shadow:0 0 0 2px #9bbc0f;border-radius:4px;overflow:hidden;max-width:320px"><div style="position:relative;padding-top:56.25%"><iframe src="${esc(MC.liveEmbed)}" style="position:absolute;inset:0;width:100%;height:100%;border:0" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin" title="Live Streaming"></iframe></div></div><a href="${esc(MC.liveStream)}" target="_blank" rel="noopener" class="pq-btn" style="display:inline-block;margin-top:8px;margin-left:6px;font-size:9px;padding:10px 14px">LIVE STREAMING</a>`:""}
    </div></div>
    <a href="${MC.gcalUrl}" target="_blank" rel="noopener" class="pq-btn" style="display:block;text-align:center;margin-top:4px;font-size:10px">+ SIMPAN KE GOOGLE CALENDAR</a>`;
  }
  else if(id==="count"){
    body.innerHTML=`<div class="ptitle">MENARA JAM</div><div class="psub">Menghitung mundur menuju Hari-H</div>
    <div class="hud2">
      <div class="slot"><span class="num" id="cdD2">00</span><span class="unit">HARI</span></div>
      <div class="slot"><span class="num" id="cdH2">00</span><span class="unit">JAM</span></div>
      <div class="slot"><span class="num" id="cdM2">00</span><span class="unit">MENIT</span></div>
      <div class="slot"><span class="num" id="cdS2">00</span><span class="unit">DETIK</span></div>
    </div>
    <p style="font-size:21px;color:#5a4a30;text-align:center;margin-top:10px">Pernikahan: <b>${esc(MC.dateText)}</b></p>`;
    tickCountdown("cdD2","cdH2","cdM2","cdS2");
  }
  else if(id==="gallery"){
    const g=MC.gallery||[];
    body.innerHTML=`<div class="ptitle">GALERI KENANGAN</div><div class="psub">Momen-momen indah</div>`+
    (g.length?`<div class="frames2">${g.map((im,i)=>`<div class="frame2" data-full="${esc(im)}"><img src="${esc(im)}" alt="Galeri ${i+1}" loading="lazy"><p>FOTO ${i+1}</p></div>`).join("")}</div>`
    :`<p style="font-size:20px;color:#7a6a4a">Galeri kenangan akan segera hadir.</p>`);
    $$("#panelBody .frame2").forEach(f=>f.addEventListener("click",()=>openLightbox(f.dataset.full)));
  }
  else if(id==="rsvp"){
    body.innerHTML=`<div class="ptitle">PENGINAPAN</div><div class="psub">Konfirmasi kehadiranmu</div>
    <form id="rsvpForm">
      <label class="lbl">NAMA LENGKAP</label><input class="pq-input" name="nama" required placeholder="Nama lengkap">
      <label class="lbl">KONFIRMASI</label><select class="pq-input" name="kehadiran" required>
        <option value="">Konfirmasi kehadiran</option>
        <option value="Hadir">Hadir, Insya Allah</option>
        <option value="Tidak Hadir">Berhalangan</option></select>
      <label class="lbl">JUMLAH TAMU</label><input class="pq-input" type="number" name="jumlah" min="1" max="10" value="1">
      <button type="submit" class="pq-btn green" style="width:100%;margin-top:18px">KIRIM RSVP</button>
    </form>`;
    $("#rsvpForm").addEventListener("submit",submitRsvp);
  }
  else if(id==="ucapan"){
    body.innerHTML=`<div class="ptitle">PAPAN UCAPAN</div><div class="psub">Tuliskan doa restu</div>
    <form id="wishForm">
      <label class="lbl">NAMA</label><input class="pq-input" name="nama" required placeholder="Nama Anda">
      <label class="lbl">UCAPAN &amp; DOA</label><textarea class="pq-input" name="pesan" required rows="3" placeholder="Tulis ucapan & doa restu..."></textarea>
      <button type="submit" class="pq-btn green" style="width:100%;margin-top:14px">KIRIM UCAPAN</button>
    </form>
    <div id="wishList" style="margin-top:16px"></div>`;
    $("#wishForm").addEventListener("submit",submitWish);
    loadWishes();
  }
  else if(id==="gift"){
    giftVisited=true; updateQuestUI();
    body.innerHTML=`<div class="ptitle">TOKO SENJATA</div><div class="psub">Beri hadiah terbaik untuk kedua mempelai</div>
    <p style="font-size:20px;color:#5a4a30;margin-bottom:14px">Penjaga toko: <i>"Pilih senjatamu, pahlawan! Setiap hadiah memperkuat ikatan mereka."</i></p>
    <div class="shop-tabs">
      <div class="shop-tab sel" data-tab="weapons">SENJATA</div>
      <div class="shop-tab" data-tab="armor">PERISAI</div>
      <div class="shop-tab" data-tab="acc">AKSESORI</div>
    </div>
    <div id="shopBody"></div>`;
    renderShop("weapons");
    $$("#panelBody .shop-tab").forEach(t=>t.addEventListener("click",()=>{
      $$("#panelBody .shop-tab").forEach(x=>x.classList.remove("sel"));
      t.classList.add("sel"); renderShop(t.dataset.tab);
    }));
  }
  else if(id==="complete"){
    body.innerHTML=`<div class="complete">
      <div style="font-size:52px">&#127881;</div>
      <h2>QUEST COMPLETE!</h2>
      <p>Kamu telah mengunjungi semua lokasi di desa.</p>
      <p>Terima kasih, pahlawan <b>${esc(MC.guestName)}</b>!</p>
      <p style="font-size:19px;color:#7a6a4a">Kehadiran dan doa restumu adalah hadiah terindah bagi ${esc(MC.brideNick)} &amp; ${esc(MC.groomNick)}.</p>
      <button class="pq-btn gold" onclick="closePanel()">LANJUTKAN PETUALANGAN</button>
    </div>`;
  }
  $("#panelOverlay").hidden=false;
}
function closePanel(){ $("#panelOverlay").hidden=true; }
$("#panelClose").addEventListener("click",closePanel);
$("#panelOverlay").addEventListener("click",e=>{ if(e.target.id==="panelOverlay") closePanel(); });

/* ---------- toko senjata ---------- */
const WEAPON_KINDS=["sword","axe","staff","bow"];
function renderShop(tab){
  const box=$("#shopBody"); if(!box) return;
  if(tab==="weapons"){
    const accs=MC.accounts||[];
    const qrisHtml = MC.qrisEnabled ? `<div class="shop-card" style="grid-column:1/-1;background:#fff">
      <div style="font-family:'Press Start 2P',monospace;font-size:11px;color:#c0392b;letter-spacing:2px">QRIS</div>
      <p style="font-size:18px;color:#7a6a4a;margin:4px 0 8px">Scan untuk kirim hadiah</p>
      <img src="${esc(MC.qrisImage)}" alt="QRIS" style="width:170px;height:170px;object-fit:contain;margin:0 auto;display:block">
      ${MC.qrisName?`<p style="font-size:19px;font-weight:bold;color:#3a2a18;margin-top:6px">${esc(MC.qrisName)}</p>`:""}
      <p style="font-size:17px;color:#7a6a4a">atau pilih senjata (rekening) di bawah</p></div>` : "";
    box.innerHTML = qrisHtml + (accs.length ? `<div class="shop-grid">${accs.map((a,i)=>{
      const kind=WEAPON_KINDS[i%4];
      return `<div class="shop-card ${i===accs.length-1?'hot':''}">${i===accs.length-1?'<span class="badge">TERBAIK!</span>':''}
      <canvas class="wicon" data-kind="${kind}" width="56" height="56"></canvas>
      <h4>${esc(a.bank)}</h4>
      <div class="price">REKENING: ${esc(a.no)}</div>
      <div class="stat">a.n. ${esc(a.an)}</div>
      <button class="pq-btn gold copybtn" data-no="${esc(a.no)}" style="font-size:9px;padding:10px 6px">SALIN NOMOR</button>
      </div>`;}).join("")}</div>`
    : `<p style="font-size:20px;color:#7a6a4a">Informasi hadiah akan segera hadir.</p>`);
    $$("#shopBody .wicon").forEach(c=>drawWeaponIcon(c,c.dataset.kind));
    $$("#shopBody .copybtn").forEach(b=>b.addEventListener("click",()=>{
      copyText(b.dataset.no); toast("NOMOR REKENING TERSALIN!");
    }));
  }
  else if(tab==="armor"){
    box.innerHTML = MC.giftAddress ? `<div class="shop-card" style="text-align:left">
      <h4 style="text-align:center">HADIAH FISIK</h4>
      <p style="font-size:20px;color:#5a4a30;margin:8px 0">${esc(MC.giftAddress)}</p>
      ${MC.giftRecipient?`<p style="font-size:19px;color:#7a6a4a">Penerima: <b>${esc(MC.giftRecipient)}</b></p>`:""}
      ${MC.giftMaps?`<a href="${esc(MC.giftMaps)}" target="_blank" rel="noopener" class="pq-btn" style="display:inline-block;margin-top:10px;font-size:9px;padding:10px 14px">BUKA PETA</a>`:""}
    </div>` : `<p style="font-size:20px;color:#7a6a4a">Alamat pengiriman hadiah akan segera hadir.</p>`;
  }
  else{
    box.innerHTML = `<div class="shop-card" style="text-align:left">
      <h4 style="text-align:center">TENTANG HADIAH</h4>
      <p style="font-size:20px;color:#5a4a30;margin:8px 0">Doa restu Anda adalah karunia yang sangat berarti bagi kedua mempelai.</p>
      ${MC.giftRecipient?`<p style="font-size:19px;color:#7a6a4a">Penerima hadiah: <b>${esc(MC.giftRecipient)}</b></p>`:""}
    </div>`;
  }
}
function copyText(t){
  if(navigator.clipboard){ navigator.clipboard.writeText(t).catch(()=>{}); }
  else{ const ta=document.createElement("textarea"); ta.value=t; document.body.appendChild(ta);
    ta.select(); try{document.execCommand("copy");}catch(e){} ta.remove(); }
}

/* ---------- lightbox galeri ---------- */
function openLightbox(src){
  const lb=document.createElement("div");
  lb.style.cssText="position:fixed;inset:0;z-index:70;background:rgba(0,0,0,.9);display:flex;align-items:center;justify-content:center;padding:16px;cursor:pointer";
  lb.innerHTML=`<img src="${esc(src)}" style="max-width:100%;max-height:85vh;border:4px solid #fff;border-radius:8px">`;
  lb.addEventListener("click",()=>lb.remove());
  document.body.appendChild(lb);
}

/* ---------- countdown ---------- */
function tickCountdown(dId,hId,mId,sId){
  const target=new Date(MC.countdownISO).getTime(), pad=n=>String(n).padStart(2,"0");
  const el={d:document.getElementById(dId),h:document.getElementById(hId),
    m:document.getElementById(mId),s:document.getElementById(sId)};
  if(!el.d) return;
  (function tick(){ const d=Math.max(0,target-Date.now());
    el.d.textContent=pad(Math.floor(d/864e5)); el.h.textContent=pad(Math.floor(d/36e5)%24);
    el.m.textContent=pad(Math.floor(d/6e4)%60); el.s.textContent=pad(Math.floor(d/1e3)%60); })();
  setInterval(()=>{ const d=Math.max(0,target-Date.now());
    el.d.textContent=pad(Math.floor(d/864e5)); el.h.textContent=pad(Math.floor(d/36e5)%24);
    el.m.textContent=pad(Math.floor(d/6e4)%60); el.s.textContent=pad(Math.floor(d/1e3)%60); },1000);
}

/* ---------- form ---------- */
async function submitRsvp(e){
  e.preventDefault();
  const fd=new FormData(e.target);
  try{
    const r=await fetch("/api/rsvp",{method:"POST",headers:{"Content-Type":"application/json"},
      body:JSON.stringify({nama:fd.get("nama"),kehadiran:fd.get("kehadiran"),jumlah:fd.get("jumlah"),inv_id:MC.invId})});
    const j=await r.json();
    toast(j&&j.message?j.message:"RSVP TERKIRIM!");
    rsvpDone=true; updateQuestUI(); e.target.reset();
  }catch(err){ toast("GAGAL MENGIRIM. COBA LAGI."); }
}
async function loadWishes(){
  const box=$("#wishList"); if(!box) return;
  try{
    const r=await fetch("/api/ucapan?inv_id="+MC.invId); const j=await r.json();
    const list=Array.isArray(j)?j:(j.data||[]);
    if(!list.length){ box.innerHTML='<p style="text-align:center;color:#7a6a4a;font-size:19px">Belum ada ucapan. Jadilah yang pertama!</p>'; return; }
    box.innerHTML=list.map(w=>`<div class="msg-row"><div class="nm">${esc(w.nama)}</div><div class="ps">${esc(w.pesan)}</div><div class="tm">${esc(timeAgo(w.created_at))}</div></div>`).join("");
  }catch(e){ box.innerHTML='<p style="text-align:center;color:#7a6a4a">Gagal memuat ucapan.</p>'; }
}
async function submitWish(e){
  e.preventDefault();
  const fd=new FormData(e.target);
  try{
    const r=await fetch("/api/ucapan",{method:"POST",headers:{"Content-Type":"application/json"},
      body:JSON.stringify({nama:fd.get("nama"),pesan:fd.get("pesan"),inv_id:MC.invId})});
    const j=await r.json();
    toast(j&&j.message?j.message:"UCAPAN TERKIRIM!");
    wishDone=true; updateQuestUI(); e.target.reset(); loadWishes();
  }catch(err){ toast("GAGAL MENGIRIM. COBA LAGI."); }
}

/* ================= INTERAKSI & LOOP ================= */
let near=null;
function updateHint(){
  near=nearestInteract();
  const hint=$("#hint"), act=$("#actBtn");
  if(near && !dialogOpen){
    hint.hidden=false;
    hint.innerHTML = near.type==="loc"
      ? `Tekan <b>E / AKSI</b> &mdash; ${near.loc.name}`
      : `Tekan <b>E / AKSI</b> &mdash; Bicara Penjaga Toko`;
    act.classList.add("ready");
  } else {
    hint.hidden=true; act.classList.remove("ready");
  }
}
function interact(){
  if(dialogOpen){ advanceDialog(); return; }
  const n=nearestInteract(); if(!n) return;
  blip(700,0.06,0.05);
  if(n.type==="shopkeeper"){
    visited.add("gift"); updateQuestUI();
    showDialog("PENJAGA TOKO", [
      "Selamat datang, pahlawan! Aku mendengar monster hutan makin kuat.",
      "Tapi tenang... yang lebih penting: pernikahan besar akan segera tiba!",
      "Senjata terbaik bukanlah pedang... melainkan hadiah tulus untuk kedua mempelai.",
      "Masuklah ke TOKO SENJATA dan pilih hadiahmu!"
    ], {skin:"#f0c49c",hair:"#e03131",shirt:"#4e9a26",style:"shop"}, ()=>{
      openPanel("gift"); checkQuestComplete();
    });
    return;
  }
  const L=n.loc;
  const firstTime=!visited.has(L.id);
  visited.add(L.id); updateQuestUI();
  const lines=LOC_DIALOGS[L.id]||["Selamat datang!"];
  showDialog(L.name, lines, null, ()=>{
    openPanel(L.id);
    if(firstTime && L.id!=="gift") checkQuestComplete();
    if(L.id==="gift") checkQuestComplete();
  });
}
addEventListener("keydown",e=>{
  if(["ArrowUp","ArrowDown","ArrowLeft","ArrowRight"," "].includes(e.key)) e.preventDefault();
  if(e.key==="Escape"){ closePanel(); return; }
  if(e.key==="Enter"||e.key===" "||e.key.toLowerCase()==="e"){ interact(); return; }
  keys[e.key.toLowerCase()]=true;
});
addEventListener("keyup",e=>{ keys[e.key.toLowerCase()]=false; });
function bindDpad(){
  $$(".dbtn").forEach(b=>{
    const sx=parseInt(b.dataset.dx), sy=parseInt(b.dataset.dy);
    const on=e=>{e.preventDefault(); try{b.setPointerCapture(e.pointerId);}catch(err){} joyX=sx; joyY=sy;};
    const off=e=>{e.preventDefault(); if(joyX===sx)joyX=0; if(joyY===sy)joyY=0;};
    b.addEventListener("pointerdown",on);
    b.addEventListener("pointerup",off);
    b.addEventListener("pointercancel",off);
    b.addEventListener("pointerleave",off);
  });
}
bindDpad();
$("#actBtn").addEventListener("click",e=>{e.preventDefault(); interact();});
function gameLoop(t){
  requestAnimationFrame(gameLoop);
  const dt=Math.min(32,t-lastT||16); lastT=t;
  let dx=0,dy=0;
  if(keys["w"]||keys["arrowup"])dy-=1;
  if(keys["s"]||keys["arrowdown"])dy+=1;
  if(keys["a"]||keys["arrowleft"])dx-=1;
  if(keys["d"]||keys["arrowright"])dx+=1;
  dx+=joyX; dy+=joyY;
  player.moving=false;
  if((dx||dy)&&!dialogOpen&&$("#panelOverlay").hidden){
    const len=Math.hypot(dx,dy); dx/=len; dy/=len;
    if(Math.abs(dx)>Math.abs(dy)) player.dir=dx>0?"right":"left";
    else player.dir=dy>0?"down":"up";
    const nx=player.x+dx*player.speed, ny=player.y+dy*player.speed;
    if(!collides(nx,player.y)) player.x=nx;
    if(!collides(player.x,ny)) player.y=ny;
    player.moving=true;
    if(!reduced && frame%10===0) player.anim=player.anim?0:1;
  }
  frame++;
  camX=Math.max(0,Math.min(WW-VW, player.x-VW/2));
  camY=Math.max(0,Math.min(WH-VH, player.y-VH/2));
  drawWorld(t||0);
  if(frame%12===0) updateHint();
}

/* ================= MULAI ================= */
$("#startBtn").addEventListener("click",()=>{
  blip(880,0.12,0.06);
  $("#start").style.display="none";
  $("#game").hidden=false;
  $("#dpad").classList.add("show"); $("#actBtn").classList.add("show");
  sizeCanvas(); buildGround(); updateQuestUI();
  /* dialog pembuka */
  setTimeout(()=>{
    showDialog("PENYIHIR", [
      "Halo, pahlawan "+MC.guestName+"! Aku adalah penyihir penjaga desa ini.",
      "Sebuah pernikahan besar akan digelar di desa ini: "+MC.brideNick+" & "+MC.groomNick+".",
      "Jelajahi desa, kunjungi 7 lokasi, dan selesaikan quest-mu!",
      "Gunakan WASD / panah untuk berjalan, E / AKSI untuk berinteraksi."
    ], {skin:"#f0c49c",hair:"#f5f5f5",shirt:"#6a3fb5",style:"wizard"}, null);
  }, 400);
  requestAnimationFrame(gameLoop);
  /* musik otomatis setelah klik */
  if(!musicOn && MC.musAuto) toggleMusic();
});
addEventListener("resize",()=>{ if(!$("#game").hidden) sizeCanvas(); });
</script>
</body>
</html>
