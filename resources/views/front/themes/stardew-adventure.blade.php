<?php
/* ============================================================
   TEMA: STARDEW ADVENTURE (kebun pixel, karakter bisa digerakkan)
   Dipilih via settings: theme = stardew-adventure
   ============================================================ */
$GLOBALS['sda_settings'] = $settings ?? [];
if (!function_exists('sdaGet')) {
  function sdaGet($key, $default = '') {
    $s = $GLOBALS['sda_settings'];
    if (!isset($s[$key]) || trim((string)$s[$key]) === '') return $default;
    return $s[$key];
  }
}
if (!function_exists('sdaDate')) {
  function sdaDate($dateStr) {
    if (!$dateStr) return '';
    $days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($dateStr);
    if (!$ts) return $dateStr;
    return $days[date('w',$ts)].', '.date('j',$ts).' '.$months[date('n',$ts)].' '.date('Y',$ts);
  }
}
if (!function_exists('sdaTime')) {
  function sdaTime($t) {
    $t = trim((string)$t); if ($t === '') return '08:00';
    $t = str_replace('.', ':', $t);
    return preg_match('/^\d{1,2}(:\d{2})?$/', $t) ? (strpos($t, ':') === false ? $t.':00' : $t) : '08:00';
  }
}
$groomNick = sdaGet('groom_nickname') ?: sdaGet('groom_name', 'Mempelai Pria');
$brideNick = sdaGet('bride_nickname') ?: sdaGet('bride_name', 'Mempelai Wanita');
$groomFull = sdaGet('groom_name', 'Mempelai Pria');
$brideFull = sdaGet('bride_name', 'Mempelai Wanita');
$wDate  = sdaGet('wedding_date', date('Y-m-d'));
$wTime  = sdaTime(sdaGet('wedding_time_start', '08:00'));
$wTimeE = sdaTime(sdaGet('wedding_time_end', '10:00'));
$rDate  = sdaGet('reception_date', $wDate);
$rTimeS = sdaTime(sdaGet('reception_time_start', '11:00'));
$rTimeE = sdaTime(sdaGet('reception_time_end', '13:00'));
$sameLoc = (sdaGet('event_location_mode', 'same') === 'same');
$mainMapLink = trim((string)sdaGet('wedding_map_link')) ?: trim((string)sdaGet('reception_map_link'));
$giftRecipient = sdaGet('gift_recipient_name', sdaGet('gift_owner', ''));
$countdownISO = $wDate.'T'.$wTime.':00+07:00';
$accounts = [];
$__gbanks = json_decode(sdaGet('gift_banks', ''), true) ?: [];
foreach ($__gbanks as $__b) {
  if (empty($__b['account'])) continue;
  $accounts[] = ['bank'=>($__b['name'] ?? 'Bank'),'no'=>$__b['account'],'an'=>($__b['owner'] ?? ''),'logo'=>($__b['logo'] ?? '')];
}
if (!$accounts) {
  if (sdaGet('gift_account')) $accounts[] = ['bank'=>sdaGet('gift_bank','Bank'),'no'=>sdaGet('gift_account'),'an'=>sdaGet('gift_owner',''),'logo'=>sdaGet('gift_bank_logo','')];
  if (sdaGet('gift_account2')) $accounts[] = ['bank'=>sdaGet('gift_bank2','Bank'),'no'=>sdaGet('gift_account2'),'an'=>sdaGet('gift_owner2',''),'logo'=>sdaGet('gift_bank_logo','')];
}
$qrisEnabled = sdaGet('gift_qris_enabled', '') === '1' && trim((string)sdaGet('gift_qris_image', '')) !== '';
$qrisImage = sdaGet('gift_qris_image', '');
$qrisName = sdaGet('gift_qris_name', '');
$musicUrl = null;
if (!empty($musicRecord) && !empty($musicRecord->file_path)) {
  $mp = $musicRecord->file_path;
  $musicUrl = (str_starts_with($mp,'http') || str_starts_with($mp,'/')) ? $mp : asset(ltrim($mp,'/'));
}
$musVol = (int) sdaGet('music_volume', 50);
$musAuto = sdaGet('music_autoplay', '1') !== '0';
$liveEmbed = sdaGet('live_stream_link','');
if (preg_match('~(?:youtube\.com/(?:watch\?v=|live/|shorts/|embed/)|youtu\.be/)([\w-]{6,})~', (string)$liveEmbed, $pqm)) { $liveEmbed = 'https://www.youtube-nocookie.com/embed/'.$pqm[1]; }
$invId = $inv_id ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Petualangan Kebun &mdash; {{ $brideNick }} &amp; {{ $groomNick }}</title>
<meta property="og:type" content="website">
<meta property="og:title" content="Undangan Pernikahan {{ $brideNick }} & {{ $groomNick }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
<style>
:root{--wood:#8a5f3c;--wood-d:#5d3a1f;--cream:#fff8e7;--leaf:#5eb83d;--gold:#ffd93b}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html,body{height:100%;overflow:hidden;background:#79c25f}
body{font-family:'VT323',monospace;font-size:20px;color:#3a2a18}
canvas{image-rendering:pixelated;image-rendering:crisp-edges}
.wood-panel{background:var(--cream);border:4px solid var(--wood-d);border-radius:6px;color:#3a2a18;
  box-shadow:0 0 0 2px var(--wood),0 8px 0 rgba(0,0,0,.25)}
.sd-btn{font-family:'Press Start 2P',monospace;font-size:12px;color:#fff;text-shadow:2px 2px 0 rgba(0,0,0,.4);
  background:linear-gradient(#a9764a,#8a5f3c);border:3px solid var(--wood-d);border-radius:8px;cursor:pointer;
  box-shadow:inset 2px 2px 0 rgba(255,255,255,.35),0 4px 0 var(--wood-d);
  padding:14px 22px;letter-spacing:1px}
.sd-btn:active{transform:translateY(3px);box-shadow:inset 2px 2px 0 rgba(255,255,255,.35),0 1px 0 var(--wood-d)}
.sd-btn.green{background:linear-gradient(#79d154,#4e9a26)}
.sd-btn.gold{background:linear-gradient(#ffe36e,#d9a821);text-shadow:2px 2px 0 #6e4d05}
.sd-input{width:100%;background:#fffdf4;color:#3a2a18;border:3px solid var(--wood);border-radius:6px;
  font-family:'VT323',monospace;font-size:21px;padding:11px 12px;outline:none}
.sd-input:focus{border-color:var(--leaf)}
select.sd-input{appearance:none}
.lbl{font-family:'Press Start 2P',monospace;font-size:10px;color:#6e4f2e;display:block;margin:14px 0 7px;letter-spacing:1px}
#start{position:fixed;inset:0;z-index:50;display:flex;align-items:center;justify-content:center;
  background:linear-gradient(#7ec8f2 0%,#bfe6c9 55%,#8fd07a 100%);padding:20px;overflow-y:auto}
.start-card{max-width:620px;width:100%;padding:28px 24px;text-align:center;max-height:94vh;overflow-y:auto}
.start-card h1{font-family:'Press Start 2P',monospace;font-size:clamp(15px,5vw,23px);color:#3a5a22;
  line-height:1.7;margin-bottom:6px}
.start-card .sub{font-size:22px;color:#5a7a3a;margin-bottom:14px}
.char-row{display:flex;gap:14px;justify-content:center;margin-bottom:8px;flex-wrap:wrap}
.char-opt{background:#a9764a;border:3px solid var(--wood-d);border-radius:8px;cursor:pointer;padding:12px 14px;text-align:center}
.char-opt.sel{background:#ffe9b0;outline:3px solid var(--gold)}
.char-opt canvas{width:52px;height:74px;background:linear-gradient(#9adc8a,#7cc46a);display:block;margin-bottom:8px;border-radius:4px}
.char-opt span{font-family:'Press Start 2P',monospace;font-size:9px;color:#3a2a18}
.controls-help{font-size:20px;color:#4a6a32;margin:14px 0 20px;line-height:1.6}
.controls-help b{color:#2b4a1c}
#game{position:fixed;inset:0}
#cv{position:absolute;inset:0;width:100%;height:100%;display:block}
#hud{position:absolute;top:0;left:0;right:0;display:flex;justify-content:space-between;align-items:flex-start;
  padding:10px 12px;pointer-events:none;z-index:5}
#hud>*{pointer-events:auto}
.progress{background:rgba(40,26,12,.85);border:3px solid #fff;border-radius:10px;
  padding:8px 12px;display:flex;align-items:center;gap:8px}
.progress .px{font-family:'Press Start 2P',monospace;font-size:10px;color:#fff}
.pips{display:flex;gap:5px}
.pip{width:26px;height:26px;border-radius:6px;background:#3a2a18;border:2px solid #6e4f2e;
  display:flex;align-items:center;justify-content:center;font-size:14px;filter:grayscale(1);opacity:.55}
.pip.on{filter:none;opacity:1;background:#fff8e7;border-color:var(--gold)}
#musicBtn{width:46px;height:46px;border-radius:10px;background:rgba(40,26,12,.85);border:3px solid #fff;
  color:#fff;font-size:20px;cursor:pointer}
#hint{position:absolute;left:50%;transform:translateX(-50%);bottom:118px;z-index:5;
  background:rgba(40,26,12,.88);color:#ffe9b0;border:3px solid var(--gold);border-radius:10px;
  padding:9px 16px;font-family:'Press Start 2P',monospace;font-size:10px;text-align:center;max-width:92vw}
#hint b{color:#fff}
#dpad{position:absolute;left:14px;bottom:14px;z-index:5;display:none;
  grid-template-columns:repeat(3,54px);grid-template-rows:repeat(3,54px);gap:4px}
#dpad.show{display:grid}
.dbtn{background:rgba(40,26,12,.6);border:3px solid rgba(255,255,255,.85);border-radius:12px;
  color:#fff;font-size:22px;touch-action:none;user-select:none;-webkit-user-select:none}
.dbtn:active{background:rgba(255,217,59,.55)}
#actBtn{position:absolute;right:16px;bottom:22px;z-index:5;display:none;
  width:86px;height:86px;border-radius:50%;background:rgba(40,26,12,.6);
  border:4px solid rgba(255,255,255,.85);color:#fff;
  font-family:'Press Start 2P',monospace;font-size:11px;touch-action:none;user-select:none;-webkit-user-select:none}
#actBtn.show{display:block}
#actBtn.ready{background:rgba(78,154,38,.85);border-color:var(--gold);animation:pulse 1s infinite}
@keyframes pulse{50%{transform:scale(1.08)}}
#toast{position:fixed;left:50%;top:64px;transform:translate(-50%,-16px);z-index:60;
  background:#2b4a1c;color:#fff;border:3px solid var(--gold);border-radius:10px;
  font-family:'Press Start 2P',monospace;font-size:11px;padding:12px 18px;opacity:0;transition:.25s;
  pointer-events:none;max-width:90vw;text-align:center}
#toast.show{opacity:1;transform:translate(-50%,0)}
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
.pcard{background:#fffdf4;border:3px solid var(--wood);border-radius:8px;padding:14px;text-align:center}
.pcard canvas{width:52px;height:52px;background:#dff3ff;border-radius:6px;margin-bottom:8px}
.pcard h3{font-size:23px;color:#3a2a18}
.ptag{font-family:'Press Start 2P',monospace;font-size:8px;color:#fff;background:var(--leaf);
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
.story{border-left:4px solid var(--leaf);padding:0 0 4px 16px;margin:0 0 18px 6px;position:relative}
.story::before{content:"";position:absolute;left:-11px;top:2px;width:18px;height:18px;border-radius:50%;
  background:var(--gold);border:3px solid var(--wood-d)}
.story .yr{font-family:'Press Start 2P',monospace;font-size:10px;color:#fff;background:#8a5f3c;
  display:inline-block;padding:5px 10px;border-radius:4px;margin-bottom:6px}
.story h4{font-size:23px;color:#3a5a22;margin-bottom:4px}
.story p{font-size:20px;color:#5a4a30}
.scoreboard{background:#fffdf4;border:3px solid var(--wood);border-radius:8px;margin-top:16px;overflow:hidden}
.scoreboard h4{font-family:'Press Start 2P',monospace;font-size:10px;color:#fff;background:#8a5f3c;padding:10px 14px}
.srow{display:flex;justify-content:space-between;align-items:center;padding:10px 14px;border-top:2px dashed #d8c9a0;font-size:20px}
.srow:first-of-type{border-top:none}
.msg-row{padding:10px 14px;border-top:2px dashed #d8c9a0}
.msg-row .nm{font-family:'Press Start 2P',monospace;font-size:9px;color:#3a5a22;margin-bottom:4px}
.msg-row .ps{font-size:20px;color:#3a2a18}
.msg-row .tm{font-size:16px;color:#a08a5a;margin-top:2px}
.chest-zone{text-align:center;padding:18px 0 6px}
.chest{width:110px;height:96px;margin:0 auto;position:relative;cursor:pointer}
.chest .base{position:absolute;bottom:0;left:0;right:0;height:62px;background:#8a5f3c;
  border:4px solid var(--wood-d);border-radius:0 0 8px 8px}
.chest .lid{position:absolute;top:0;left:0;right:0;height:44px;background:#a9764a;
  border:4px solid var(--wood-d);border-radius:8px 8px 0 0;transform-origin:50% 100%;transition:transform .45s}
.chest .glow{position:absolute;inset:-14px;background:radial-gradient(circle,rgba(255,217,59,.85),transparent 70%);
  opacity:0;transition:.4s}
.chest.open .lid{transform:rotateX(105deg)}
.chest.open .glow{opacity:1}
.gift-grid[hidden]{display:none}
.gift-grid{display:grid;gap:12px;margin-top:14px}
.acct{background:#2b4a1c;border:3px solid var(--wood-d);border-radius:8px;padding:16px;text-align:center;color:#ffe9b0}
.acct .bank{font-family:'Press Start 2P',monospace;font-size:10px;color:var(--gold);letter-spacing:1px}
.acct .no{font-family:'Press Start 2P',monospace;font-size:17px;margin:10px 0 4px;letter-spacing:1px}
.acct .an{font-size:20px;color:#cfe8b0;margin-bottom:12px}
.acct img{height:36px;max-width:150px;object-fit:contain;margin:10px auto 0;display:block;background:#fff;border-radius:6px;padding:4px}
.complete{text-align:center;padding:10px 4px}
.complete h2{font-family:'Press Start 2P',monospace;font-size:17px;color:#3a5a22;margin-bottom:14px;line-height:1.8}
.complete p{font-size:22px;color:#5a4a30;margin-bottom:10px}
.qr-card{background:#fffdf4;border:3px solid var(--wood);border-radius:8px;padding:16px;text-align:center;margin-top:14px}
.qr-card #qrBox img,.qr-card #qrBox canvas{margin:0 auto}
@media (prefers-reduced-motion:reduce){#actBtn.ready{animation:none}}
</style>
</head>
<body>
<div id="start">
  <div class="start-card wood-panel">
    <div style="font-size:44px;margin-bottom:4px">&#127793;</div>
    <h1>PETUALANGAN KEBUN</h1>
    <div class="sub">Undangan Pernikahan<br><b>{{ $brideNick }} &amp; {{ $groomNick }}</b></div>
    @if(!empty($guestName))
    <div class="sub" style="font-size:20px">Tamu spesial: <b>{{ $guestName }}</b></div>
    @endif
    <p style="font-size:20px;color:#5a7a3a;margin-bottom:14px">Pilih karakter petanimu:</p>
    <div class="char-row" id="charRow"></div>
    <div class="controls-help">
      <b>WASD / Panah</b> untuk jalan &mdash; <b>E / Spasi</b> untuk masuk lokasi<br>
      Di HP: pakai D-pad + tombol AKSI<br>
      Kunjungi <b>8 lokasi</b> di kebun untuk membuka semua undangan!
    </div>
    <button class="sd-btn green" id="startBtn" style="font-size:13px">MULAI BERMAIN</button>
  </div>
</div>

<div id="game" hidden>
  <canvas id="cv"></canvas>
  <div id="hud">
    <div class="progress">
      <span class="px">&#127793; <span id="progText">0/8</span></span>
      <div class="pips" id="pips"></div>
    </div>
    <button id="musicBtn" title="Musik">&#9834;</button>
  </div>
  <div id="hint" hidden></div>
  <div id="dpad">
    <span></span><button class="dbtn" data-dx="0" data-dy="-1">&#9650;</button><span></span>
    <button class="dbtn" data-dx="-1" data-dy="0">&#9664;</button><span></span><button class="dbtn" data-dx="1" data-dy="0">&#9654;</button>
    <span></span><button class="dbtn" data-dx="0" data-dy="1">&#9660;</button><span></span>
  </div>
  <button id="actBtn">AKSI</button>
</div>

<div id="panelOverlay" hidden>
  <div id="panel" class="wood-panel">
    <button id="panelClose">&#10005;</button>
    <div id="panelBody"></div>
  </div>
</div>
<div id="toast"></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
"use strict";
/* Data dari Blade */
const MC = {
  invId: {{ (int)$invId }},
  countdownISO: @json($countdownISO),
  musicUrl: @json($musicUrl),
  musVol: @json($musVol),
  musAuto: @json($musAuto),
  brideNick: @json($brideNick), groomNick: @json($groomNick),
  brideFull: @json($brideFull), groomFull: @json($groomFull),
  guestName: @json($guestName),
  dateText: @json(sdaDate($wDate)),
  quotes: @json(sdaGet('quotes', '')),
  guestQr: @json(!empty($guestQrCode) ? $guestQrCode : ''),
  guestQrOn: {{ sdaGet('guest_qr_enabled') ? 'true' : 'false' }},
  akad: { date: @json(sdaDate($wDate)), time: @json($wTime.' – '.$wTimeE.' '.sdaGet('wedding_timezone','WIB')),
    venue: @json(sdaGet('wedding_location','Kediaman Mempelai')), maps: @json($sameLoc ? $mainMapLink : sdaGet('wedding_map_link','')) },
  resepsi: { date: @json(sdaDate($rDate)), time: @json($rTimeS.' – '.$rTimeE.' '.sdaGet('wedding_timezone','WIB')),
    venue: @json(sdaGet('reception_location', sdaGet('wedding_location','Kediaman Mempelai'))), maps: @json($sameLoc ? $mainMapLink : sdaGet('reception_map_link','')) },
  liveStream: @json(sdaGet('live_stream_link', '')),
  liveEmbed: @json($liveEmbed),
  giftRecipient: @json($giftRecipient),
  giftAddress: @json(sdaGet('gift_address', '')),
  giftMaps: @json(sdaGet('gift_maps_link', ''))
};
const $=s=>document.querySelector(s), $$=s=>Array.from(document.querySelectorAll(s));
const reduced=matchMedia("(prefers-reduced-motion: reduce)").matches;
function toast(m){const t=$("#toast");t.textContent=m;t.classList.add("show");clearTimeout(t._h);t._h=setTimeout(()=>t.classList.remove("show"),2600);}
function esc(s){return String(s??"").replace(/[&<>"']/g,c=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"}[c]));}

/* ---------- sprites ---------- */
function drawSprite(cv,map,pal){
  const ctx=cv.getContext("2d"),s=cv.width/map[0].length;
  ctx.clearRect(0,0,cv.width,cv.height);
  map.forEach((row,y)=>{[...row].forEach((ch,x)=>{if(pal[ch]){ctx.fillStyle=pal[ch];
    ctx.fillRect(Math.floor(x*s),Math.floor(y*s),Math.ceil(s),Math.ceil(s));}});});
}
const PAL={R:"#e03131",G:"#5eb83d",g:"#4e9a26",K:"#141414",W:"#f2f2f2",Y:"#fcee5e",B:"#6b4a2a",
  O:"#141414",P:"#8a5f3c",L:"#b9834b",D:"#bfe9ff",E:"#3f8fd1",H:"#e8b93c"};
const ICONS={
  heart:["............","..RRR..RRR..",".RRRRRRRRRR.",".RRRRRRRRRR.","..RRRRRRRR..","...RRRRRR...","....RRRR....","............"],
  clock:[".....WW.....","...WWYYWW...","..WYYYYYYW..","..YYYKYYYY..","..YYYKYYYY..","..YYYKYYYY..","..YYYYYYYY..","...WWYYWW...",".....WW....."],
  book:["............",".BBBBBBBBBB.",".BWBBBBBBWB.",".BWBBBBBBWB.",".BWBBBBBBWB.",".BBBBBBBBBB.","............"],
  frame:["OOOOOOOOOOOO","OPPPPPPPPPPO","OP........PO","OP...YY...PO","OP..YYYY..PO","OP...YY...PO","OP........PO","OPPPPPPPPPPO","OOOOOOOOOOOO"],
  mail:["............",".WWWWWWWWWW.",".WKWWWWWWKW.",".WWKWWWWKWW.",".WWWKKKKWWW.",".WWWWWWWWWW.",".WWWWWWWWWW.","............"],
  chest:["............",".OOOOOOOOOO.",".OLLLLLLLLO.",".OLLLLLLLLO.",".OBBBBBBBBO.",".OBBBOOBBBO.",".OBBBBBBBBO.",".OOOOOOOOOO."],
  ring:[".....DDDD.....","....DDDDDD....","...DDWDDDWDD...","...DDDDDDDD...","....DDDDDD....",".....DDDD.....","......DD......",".....OOOO.....","....OYYYYO....","....OYGGYO....","....OYYYYO....",".....OOOO....."]
};
/* petani: seperti drawPlayer tapi bertopi jerami */
function drawFarmer(ctx,x,y,o,dir,frame,moving,scale){
  const s=scale;ctx.save();ctx.translate(Math.round(x),Math.round(y));
  ctx.fillStyle="rgba(0,0,0,.25)";ctx.beginPath();ctx.ellipse(0,13*s,9*s,3.5*s,0,0,6.29);ctx.fill();
  const legSwing=moving&&!reduced?(frame?1:-1)*3*s:0;
  ctx.fillStyle=o.pants;
  ctx.fillRect(-6*s,(6*s)+Math.max(0,legSwing),5*s,7*s-Math.max(0,legSwing));
  ctx.fillRect(1*s,(6*s)+Math.max(0,-legSwing),5*s,7*s-Math.max(0,-legSwing));
  ctx.fillStyle=o.shirt;ctx.fillRect(-8*s,-2*s,16*s,9*s);
  const armSwing=moving&&!reduced?(frame?-1:1)*2.5*s:0;
  ctx.fillStyle=o.shirt;
  ctx.fillRect(-11*s,-1*s,3.5*s,7*s+armSwing*0.4);ctx.fillRect(7.5*s,-1*s,3.5*s,7*s-armSwing*0.4);
  ctx.fillStyle=o.skin;
  ctx.fillRect(-11*s,(6*s)+armSwing*0.4,3.5*s,3*s);ctx.fillRect(7.5*s,(6*s)-armSwing*0.4,3.5*s,3*s);
  ctx.fillStyle=o.skin;ctx.fillRect(-7*s,-13*s,14*s,11*s);
  ctx.fillStyle=o.hair;
  if(dir==="up"){ctx.fillRect(-7*s,-13*s,14*s,11*s);}
  else{ctx.fillRect(-7*s,-13*s,14*s,3.5*s);
    if(dir==="down"){ctx.fillRect(-7*s,-13*s,3*s,8*s);ctx.fillRect(4*s,-13*s,3*s,8*s);}
    else if(dir==="left"){ctx.fillRect(-7*s,-13*s,8*s,3.5*s);}
    else{ctx.fillRect(-1*s,-13*s,8*s,3.5*s);}}
  ctx.fillStyle="#141414";
  if(dir==="down"){ctx.fillRect(-4*s,-6.5*s,2.4*s,2.8*s);ctx.fillRect(1.6*s,-6.5*s,2.4*s,2.8*s);}
  else if(dir==="left"){ctx.fillRect(-5.5*s,-6.5*s,2.4*s,2.8*s);}
  else if(dir==="right"){ctx.fillRect(3.1*s,-6.5*s,2.4*s,2.8*s);}
  /* topi jerami */
  ctx.fillStyle="#e8b93c";
  ctx.fillRect(-9*s,-17.5*s,18*s,4.5*s);
  ctx.fillRect(-16*s,-14*s,32*s,3.5*s);
  ctx.fillStyle="#c9922e";
  ctx.fillRect(-9*s,-14.6*s,18*s,1.6*s);
  ctx.fillStyle="rgba(255,255,255,.14)";ctx.fillRect(-8*s,-2*s,3*s,9*s);
  ctx.restore();
}
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
function drawHeartShape(c,x,y,s,col){
  c.fillStyle=col||"#e03131";const u=s/7;
  const p=[[-2,-1],[-1,-1],[0,-1],[1,-1],[2,-1],[-3,0],[-2,0],[-1,0],[0,0],[1,0],[2,0],[3,0],
    [-3,1],[-2,1],[-1,1],[0,1],[1,1],[2,1],[3,1],[-2,2],[-1,2],[0,2],[1,2],[2,2],[-1,3],[0,3],[1,3],[0,4]];
  p.forEach(pt=>c.fillRect(x+pt[0]*u-u/2,y+pt[1]*u-u/2,Math.ceil(u),Math.ceil(u)));
}
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
/* ---------- pilih karakter ---------- */
const CHARS=[
  {name:"TANI",   skin:"#c98d5f",hair:"#2b2b2b",shirt:"#00a8a8",pants:"#3b3bbf"},
  {name:"KEBUN",  skin:"#f0c49c",hair:"#e07b39",shirt:"#7ab648",pants:"#5a5a5a"},
  {name:"SAWAH",  skin:"#8a5a3a",hair:"#141414",shirt:"#a03a3a",pants:"#2b2b2b"}
];
let charIdx=0;
function renderChars(){
  const row=$("#charRow");row.innerHTML="";
  CHARS.forEach((c,i)=>{
    const d=document.createElement("div");d.className="char-opt"+(i===charIdx?" sel":"");
    const cv=document.createElement("canvas");cv.width=32;cv.height=44;
    drawFarmer(cv.getContext("2d"),16,30,c,"down",0,false,1.15);
    d.appendChild(cv);d.insertAdjacentHTML("beforeend",`<span>${c.name}</span>`);
    d.addEventListener("click",()=>{charIdx=i;blip(700,0.06,0.05);renderChars();});
    row.appendChild(d);});
}
renderChars();

/* ---------- dunia kebun ---------- */
const TILE=32,WT=52,HT=39,WW=WT*TILE,WH=HT*TILE;
const cv=$("#cv"),ctx=cv.getContext("2d");
let VW=0,VH=0,camX=0,camY=0;
function sizeCanvas(){VW=cv.width=innerWidth;VH=cv.height=innerHeight;}
const LOCS=[
  {id:"couple", name:"RUMAH MEMPELAI", short:"MEMPELAI", icon:"heart", tx:5, ty:4, w:9,h:6, wall:"#f5e6c8", roof:"#c0392b", kind:"house"},
  {id:"count",  name:"MENARA JAM",     short:"JAM",     icon:"clock", tx:39,ty:4, w:5,h:7, wall:"#d8b46a", roof:"#7a5a3a", kind:"silo"},
  {id:"story",  name:"RUMAH KACA",     short:"KISAH",   icon:"book",  tx:5, ty:15,w:9,h:6, wall:"#bfe9ff", roof:"#5eb83d", kind:"greenhouse"},
  {id:"event",  name:"LUMBUNG ACARA",  short:"ACARA",   icon:"ring",  tx:39,ty:15,w:9,h:6, wall:"#c0392b", roof:"#f5e6c8", kind:"barn"},
  {id:"gallery",name:"GALERI LUMBUNG", short:"GALERI",  icon:"frame", tx:5, ty:26,w:9,h:6, wall:"#d8b46a", roof:"#3f8fd1", kind:"barn"},
  {id:"rsvp",   name:"KANTOR POS",     short:"RSVP",    icon:"mail",  tx:39,ty:26,w:7,h:5, wall:"#f5e6c8", roof:"#5eb83d", kind:"house"},
  {id:"ucapan", name:"PAPAN UCAPAN",   short:"UCAPAN",  icon:"book",  tx:22,ty:30,w:8,h:3, wall:"#8a5f3c", roof:"#8a5f3c", kind:"board"},
  {id:"gift",   name:"GUDANG HARTA",   short:"HARTA",   icon:"chest", tx:22,ty:4, w:8,h:5, wall:"#8a5f3c", roof:"#d9a821", kind:"shed"}
];
const solids=[];
function solidRect(tx,ty,w,h){solids.push({x:tx*TILE,y:ty*TILE,w:w*TILE,h:h*TILE});}
const PATHS=[
  {x:24,y:0,w:4,h:39},{x:0,y:20,w:52,h:3},
  {x:7,y:7,w:4,h:9},{x:39,y:8,w:4,h:8},
  {x:7,y:23,w:4,h:4},{x:41,y:23,w:4,h:4},
  {x:24,y:23,w:4,h:11},{x:24,y:7,w:4,h:4}
];
function inRect(px,py,r){return px>=r.x&&px<r.x+r.w&&py>=r.y&&py<r.y+r.h;}
const FIELD={x:15,y:23,w:7,h:8};
const pond={x:33*TILE,y:33*TILE,rx:4*TILE,ry:2.6*TILE};
const flowers=[];
for(let i=0;i<80;i++)flowers.push({tx:Math.floor(Math.random()*WT),ty:Math.floor(Math.random()*HT),
  c:["#ff5b5b","#fcee5e","#ffffff","#ff9ff3","#ffb7c5"][i%5]});
const junimos=[{tx:18,ty:12,c:"#7ed957"},{tx:30,ty:18,c:"#9adcff"},{tx:12,ty:30,c:"#ffb7c5"}];
const hens=[{tx:28,ty:26},{tx:36,ty:22}];
let groundCv=null;
function shade(hex,amt){const n=parseInt(hex.slice(1),16);
  const r=Math.max(0,Math.min(255,((n>>16)&255)+amt)),g=Math.max(0,Math.min(255,((n>>8)&255)+amt)),
        b=Math.max(0,Math.min(255,(n&255)+amt));return `rgb(${r},${g},${b})`;}
function drawCrops(g,x0,y0){
  for(let r=0;r<FIELD.h;r++)for(let c=0;c<FIELD.w;c++){
    const X=x0+c*TILE+8,Y=y0+r*TILE+18;
    g.fillStyle="#4a8a38";g.fillRect(X-2,Y-14,4,14);
    const kind=(r*7+c)%3;
    if(kind===0){g.fillStyle="#7ed957";g.fillRect(X-7,Y-22,6,6);g.fillRect(X+1,Y-22,6,6);g.fillRect(X-3,Y-27,6,6);}
    else if(kind===1){g.fillStyle="#ffb7c5";g.fillRect(X-7,Y-20,14,8);}
    else{g.fillStyle="#e8b93c";g.fillRect(X-7,Y-20,14,8);g.fillStyle="#ffe27a";g.fillRect(X-4,Y-24,8,5);}
  }
}
function drawBuilding(g,L){
  const X=L.tx*TILE,Y=L.ty*TILE,Wd=L.w*TILE,Ht=L.h*TILE;
  g.fillStyle="rgba(0,0,0,.25)";g.fillRect(X+6,Y+Ht-4,Wd,Ht);
  if(L.kind==="board"){
    g.fillStyle="#6e4a2e";g.fillRect(X+10,Y+40,10,Ht-30);g.fillRect(X+Wd-20,Y+40,10,Ht-30);
    g.fillStyle="#8a5f3c";g.fillRect(X,Y,Wd,52);
    g.strokeStyle="#3a2410";g.lineWidth=4;g.strokeRect(X+4,Y+4,Wd-8,44);
    g.fillStyle="#fff8e7";g.font="9px 'Press Start 2P',monospace";g.textAlign="center";
    g.fillText("UCAPAN",X+Wd/2,Y+32);
    L.doorX=X+Wd/2;L.doorY=Y+Ht+18;return;
  }
  g.fillStyle=L.wall;g.fillRect(X,Y,Wd,Ht);
  g.fillStyle="rgba(0,0,0,.12)";for(let i=0;i<L.w;i++)g.fillRect(X+i*TILE,Y,2,Ht);
  if(L.kind==="greenhouse"){
    g.fillStyle="rgba(255,255,255,.35)";g.fillRect(X,Y,Wd,Ht);
    g.strokeStyle="#3f8fd1";g.lineWidth=3;
    for(let i=0;i<=L.w;i++){g.beginPath();g.moveTo(X+i*TILE,Y);g.lineTo(X+i*TILE,Y+Ht);g.stroke();}
    for(let i=0;i<=3;i++){g.beginPath();g.moveTo(X,Y+i*Ht/3);g.lineTo(X+Wd,Y+i*Ht/3);g.stroke();}
  }
  if(L.kind==="silo"){
    g.fillStyle=L.roof;g.fillRect(X-8,Y-30,Wd+16,30);
    g.fillStyle=shade(L.roof,-25);g.fillRect(X-8,Y-8,Wd+16,8);
    g.fillStyle="#fff8e7";g.beginPath();g.arc(X+Wd/2,Y+52,26,0,6.29);g.fill();
    g.strokeStyle="#3a2410";g.lineWidth=4;g.stroke();
    g.strokeStyle="#3a2410";g.lineWidth=3;
    g.beginPath();g.moveTo(X+Wd/2,Y+52);g.lineTo(X+Wd/2+10,Y+40);g.stroke();
    g.beginPath();g.moveTo(X+Wd/2,Y+52);g.lineTo(X+Wd/2,Y+34);g.stroke();
  }else{
    for(let r=0;r<3;r++){g.fillStyle=r%2?L.roof:shade(L.roof,-18);
      g.fillRect(X-TILE+r*10,Y-(r+1)*16,Wd+2*TILE-r*20,16);}
  }
  g.fillStyle="#fcee5e";g.fillRect(X+TILE,Y+2*TILE,TILE,TILE);
  g.fillRect(X+Wd-2*TILE,Y+2*TILE,TILE,TILE);
  g.fillStyle="#141414";g.fillRect(X+TILE,Y+2*TILE+TILE/2-2,TILE,4);
  g.fillRect(X+Wd-2*TILE,Y+2*TILE+TILE/2-2,TILE,4);
  const dx=X+Wd/2-TILE/2;
  g.fillStyle="#3a2410";g.fillRect(dx,Y+Ht-2*TILE,TILE,2*TILE);
  g.fillStyle="#f7c948";g.fillRect(dx+TILE-8,Y+Ht-TILE-16,5,5);
  const sy=Y-64;
  g.fillStyle="#6e4a2e";g.fillRect(X+Wd/2-3,sy+30,6,34);
  g.fillStyle="#8a5f3c";g.fillRect(X+Wd/2-56,sy,112,34);
  g.strokeStyle="#3a2410";g.lineWidth=3;g.strokeRect(X+Wd/2-56,sy,112,34);
  const imap=ICONS[L.icon],iw=imap[0].length;
  const isc=document.createElement("canvas");isc.width=iw;isc.height=imap.length;
  drawSprite(isc,imap,PAL);
  g.drawImage(isc,X+Wd/2-48,sy+5,24,24*imap.length/iw);
  g.fillStyle="#fff";g.font="8px 'Press Start 2P',monospace";g.textAlign="left";
  g.fillText(L.short,X+Wd/2-18,sy+21);
  L.doorX=X+Wd/2;L.doorY=Y+Ht+18;
  solidRect(L.tx,L.ty,L.w,L.h);
}
function buildGround(){
  groundCv=document.createElement("canvas");groundCv.width=WW;groundCv.height=WH;
  const g=groundCv.getContext("2d");
  g.fillStyle="#79c25f";g.fillRect(0,0,WW,WH);
  for(let i=0;i<9000;i++){g.fillStyle=Math.random()<0.5?"#6fb455":"#86cc6a";
    g.fillRect(Math.random()*WW,Math.random()*WH,3,3);}
  PATHS.forEach(p=>{g.fillStyle="#c9a86a";g.fillRect(p.x*TILE,p.y*TILE,p.w*TILE,p.h*TILE);
    for(let i=0;i<p.w*p.h*3;i++){g.fillStyle=Math.random()<0.5?"#b8945a":"#d8bc7e";
      g.fillRect((p.x+Math.random()*p.w)*TILE,(p.y+Math.random()*p.h)*TILE,4,4);}});
  /* ladang */
  const fx=FIELD.x*TILE,fy=FIELD.y*TILE,fw=FIELD.w*TILE,fh=FIELD.h*TILE;
  g.fillStyle="#8a5f3c";g.fillRect(fx-8,fy-8,fw+16,fh+16);
  g.fillStyle="#6e4a2e";
  for(let r=0;r<FIELD.h;r++)g.fillRect(fx,fy+r*TILE,fw,6);
  g.strokeStyle="#5d3a1f";g.lineWidth=6;g.strokeRect(fx-8,fy-8,fw+16,fh+16);
  for(let i=0;i<fw/16;i++){g.fillStyle="#5d3a1f";g.fillRect(fx+i*16,fy-8,5,fh+16);}
  drawCrops(g,fx,fy);
  solidRect(FIELD.x-0.25,FIELD.y-0.25,FIELD.w+0.5,FIELD.h+0.5);
  /* kolam */
  g.fillStyle="#c2b280";g.beginPath();g.ellipse(pond.x,pond.y,pond.rx+8,pond.ry+8,0,0,6.29);g.fill();
  g.fillStyle="#3f8fd1";g.beginPath();g.ellipse(pond.x,pond.y,pond.rx,pond.ry,0,0,6.29);g.fill();
  g.fillStyle="#5aa5e0";g.beginPath();g.ellipse(pond.x-pond.rx*0.25,pond.y-pond.ry*0.25,pond.rx*0.55,pond.ry*0.5,0,0,6.29);g.fill();
  /* bunga */
  flowers.forEach(f=>{const x=f.tx*TILE+8,y=f.ty*TILE+20;
    if(PATHS.some(p=>inRect(x,y,p)))return;
    if(x>fx-16&&x<fx+fw+16&&y>fy-16&&y<fy+fh+16)return;
    g.fillStyle="#3f8f23";g.fillRect(x,y,2,8);g.fillStyle=f.c;g.fillRect(x-3,y-4,8,6);});
  /* pagar kayu di sekeliling */
  g.fillStyle="#8a5f3c";
  for(let x=0;x<WT;x+=2){g.fillRect(x*TILE,0,8,26);g.fillRect(x*TILE,WH-26,8,26);}
  for(let y=0;y<HT;y+=2){g.fillRect(0,y*TILE,8,26);g.fillRect(WW-8,y*TILE,8,26);}
  g.fillRect(0,8,WW,8);g.fillRect(0,WH-16,WW,8);g.fillRect(8,0,8,WH);g.fillRect(WW-16,0,8,WH);
  solidRect(0,0,WT,0.8);solidRect(0,HT-0.8,WT,0.8);solidRect(0,0,0.5,HT);solidRect(WT-0.5,0,0.5,HT);
  LOCS.forEach(L=>drawBuilding(g,L));
}

/* ---------- pemain & kontrol ---------- */
const player={x:26*TILE,y:22*TILE,dir:"up",moving:false,frame:0,animT:0,pal:CHARS[0],speed:170,r:11};
const keys={};
addEventListener("keydown",e=>{
  if(["ArrowUp","ArrowDown","ArrowLeft","ArrowRight"," "].includes(e.key))e.preventDefault();
  keys[e.key.toLowerCase()]=true;
  if((e.key==="e"||e.key==="E"||e.key===" ")&&gameOn&&!panelOpen)tryInteract();
  if(e.key==="Escape"&&panelOpen)closePanel();
});
addEventListener("keyup",e=>{keys[e.key.toLowerCase()]=false;});
const held={};
$$(".dbtn").forEach(b=>{
  const k=b.dataset.dx+","+b.dataset.dy;
  b.addEventListener("pointerdown",e=>{e.preventDefault();held[k]=true;});
  ["pointerup","pointercancel","pointerleave"].forEach(ev=>b.addEventListener(ev,e=>{e.preventDefault();delete held[k];}));
});
$("#actBtn").addEventListener("click",()=>{if(gameOn&&!panelOpen)tryInteract();});
function inputVec(){
  let x=0,y=0;
  if(keys["a"]||keys["arrowleft"])x-=1;if(keys["d"]||keys["arrowright"])x+=1;
  if(keys["w"]||keys["arrowup"])y-=1;if(keys["s"]||keys["arrowdown"])y+=1;
  for(const k in held){const p=k.split(",");x+=+p[0];y+=+p[1];}
  if(x&&y){x*=0.7071;y*=0.7071;}
  return{x,y};
}
function collide(nx,ny){
  const r=player.r;
  if(nx<r||nx>WW-r||ny<r||ny>WH-r)return true;
  for(const s of solids)if(nx+r>s.x&&nx-r<s.x+s.w&&ny+r>s.y&&ny-r<s.y+s.h)return true;
  const dx=nx-pond.x,dy=ny-pond.y;
  if((dx*dx)/(pond.rx*pond.rx)+(dy*dy)/(pond.ry*pond.ry)<1)return true;
  return false;
}
function updatePlayer(dt){
  const v=inputVec();player.moving=!!(v.x||v.y);
  if(player.moving){
    if(Math.abs(v.x)>Math.abs(v.y))player.dir=v.x>0?"right":"left";else player.dir=v.y>0?"down":"up";
    player.animT+=dt;player.frame=Math.floor(player.animT*8)%2;
    const nx=player.x+v.x*player.speed*dt;if(!collide(nx,player.y))player.x=nx;
    const ny=player.y+v.y*player.speed*dt;if(!collide(player.x,ny))player.y=ny;
  }else player.animT=0;
  const tx=Math.max(0,Math.min(WW-VW,player.x-VW/2)),ty=Math.max(0,Math.min(WH-VH,player.y-VH/2));
  if(reduced){camX=tx;camY=ty;}else{camX+=(tx-camX)*Math.min(1,dt*6);camY+=(ty-camY)*Math.min(1,dt*6);}
}
/* ---------- interaksi ---------- */
let nearLoc=null,gameOn=false,panelOpen=false,completedShown=false;
const visited=new Set();
function checkNear(){
  nearLoc=null;let best=1e9;
  for(const L of LOCS){const d=Math.hypot(player.x-L.doorX,player.y-L.doorY);
    if(d<64&&d<best){best=d;nearLoc=L;}}
  const hint=$("#hint"),act=$("#actBtn");
  if(nearLoc&&!panelOpen){
    hint.hidden=false;
    hint.innerHTML=`&#9673; ${nearLoc.name} &mdash; tekan <b>E</b> / ketuk AKSI${visited.has(nearLoc.id)?" &#10003;":""}`;
    act.classList.add("ready");
  }else{hint.hidden=true;act.classList.remove("ready");}
}
function tryInteract(){if(!nearLoc)return;blip(880,0.08,0.05);openPanel(nearLoc.id);}
/* ---------- panel ---------- */
const PIP={couple:"\u2764",count:"\u23F1",story:"\uD83D\uDCD6",event:"\uD83D\uDC92",gallery:"\uD83D\uDDBC",rsvp:"\u2709",ucapan:"\uD83D\uDCAC",gift:"\uD83C\uDF81"};
function renderPips(){
  const box=$("#pips");box.innerHTML="";
  LOCS.forEach(L=>{const d=document.createElement("div");
    d.className="pip"+(visited.has(L.id)?" on":"");d.textContent=PIP[L.id];d.title=L.name;box.appendChild(d);});
  $("#progText").textContent=visited.size+"/"+LOCS.length;
}
function markVisited(id){
  if(visited.has(id))return;visited.add(id);renderPips();levelUp();
  heartFx(player.x-camX,player.y-camY-30,10);
  const L=LOCS.find(l=>l.id===id);
  toast(`LOKASI DITEMUKAN: ${L.name} (${visited.size}/${LOCS.length})`);
}
const TPL={
couple:()=>`<h2 class="ptitle">RUMAH MEMPELAI</h2>
@if(sdaGet('quotes'))<p class="psub" style="font-style:italic">&ldquo;{{ sdaGet('quotes') }}&rdquo;</p>@endif<p class="psub">Dua petani. Satu ladang. Selamanya.</p>
<div class="player-grid">
<div class="pcard"><canvas id="pfB" width="8" height="8"></canvas>
<h3>{{ $brideFull }}</h3><span class="ptag">MEMPELAI WANITA</span><p>{{ sdaGet('bride_parents', sdaGet('bride_child_of','')) }}</p>@if(sdaGet('bride_instagram'))<a href="https://instagram.com/{{ ltrim(sdaGet('bride_instagram'),'@') }}" target="_blank" rel="noopener" class="sd-btn" style="font-size:9px;margin-top:8px;display:inline-flex;align-items:center;gap:5px;text-decoration:none"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="flex:none"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="0.8" fill="currentColor" stroke="none"/></svg><span>&#64;{{ ltrim(sdaGet('bride_instagram'),'@') }}</span></a>@endif</div>
<div class="pcard"><canvas id="pfG" width="8" height="8"></canvas>
<h3>{{ $groomFull }}</h3><span class="ptag">MEMPELAI PRIA</span><p>{{ sdaGet('groom_parents', sdaGet('groom_child_of','')) }}</p>@if(sdaGet('groom_instagram'))<a href="https://instagram.com/{{ ltrim(sdaGet('groom_instagram'),'@') }}" target="_blank" rel="noopener" class="sd-btn" style="font-size:9px;margin-top:8px;display:inline-flex;align-items:center;gap:5px;text-decoration:none"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="flex:none"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="0.8" fill="currentColor" stroke="none"/></svg><span>&#64;{{ ltrim(sdaGet('groom_instagram'),'@') }}</span></a>@endif</div>
</div>
${MC.guestQrOn&&MC.guestQr?`<div class="qr-card"><h2 class="ptitle" style="font-size:12px">KARTU TAMU</h2>
<p class="psub" style="font-size:19px">Tunjukkan QR ini saat check-in di lokasi acara.</p>
<div id="qrBox" style="display:flex;justify-content:center"></div></div>`:""}`,
count:()=>`<h2 class="ptitle">MENARA JAM</h2><p class="psub">${MC.dateText} &mdash; musim panen dimulai dalam:</p>
<div class="hud2">
<div class="slot"><span class="num" id="cD">00</span><span class="unit">HARI</span></div>
<div class="slot"><span class="num" id="cH">00</span><span class="unit">JAM</span></div>
<div class="slot"><span class="num" id="cM">00</span><span class="unit">MENIT</span></div>
<div class="slot"><span class="num" id="cS">00</span><span class="unit">DETIK</span></div>
</div>
${MC.quotes?`<p class="psub" style="margin-top:14px;font-style:italic">&ldquo;${esc(MC.quotes)}&rdquo;</p>`:""}`,
story:()=>`<h2 class="ptitle">RUMAH KACA</h2><p class="psub">Jurnal cinta yang tumbuh seperti tanaman.</p>
@foreach($stories as $st)
<div class="story">
  @if(!empty($st->tahun))<div class="yr">{{ $st->tahun }}</div>@endif
  <h4>{{ $st->judul }}</h4><p>{{ $st->isi }}</p>
</div>
@endforeach
@if(empty($stories))<p class="psub">Kisah cinta akan segera tumbuh di sini.</p>@endif`,
event:()=>`<h2 class="ptitle">LUMBUNG ACARA</h2><p class="psub">Dua perayaan besar menantimu.</p>
<div class="adv"><div class="ic"><canvas class="ev-ic" data-ic="ring" width="16" height="16"></canvas></div>
<div><h3>AKAD NIKAH</h3><p>${MC.akad.date}</p><p class="tm">${MC.akad.time}</p><p>${esc(MC.akad.venue)}</p>
${MC.akad.maps?`<a class="sd-btn gold" style="font-size:10px;margin-top:10px;display:inline-block;text-decoration:none" href="${esc(MC.akad.maps)}" target="_blank" rel="noopener">&#9673; BUKA PETA</a>`:""}</div></div>
<div class="adv"><div class="ic"><canvas class="ev-ic" data-ic="heart" width="16" height="16"></canvas></div>
<div><h3>RESEPSI</h3><p>${MC.resepsi.date}</p><p class="tm">${MC.resepsi.time}</p><p>${esc(MC.resepsi.venue)}</p>
${MC.resepsi.maps?`<a class="sd-btn gold" style="font-size:10px;margin-top:10px;display:inline-block;text-decoration:none" href="${esc(MC.resepsi.maps)}" target="_blank" rel="noopener">&#9673; BUKA PETA</a>`:""}${MC.liveStream?`<div style="margin-top:10px;border:3px solid #7a4a21;border-radius:8px;overflow:hidden;max-width:320px;box-shadow:0 4px 10px rgba(0,0,0,.25)"><div style="position:relative;padding-top:56.25%"><iframe src="${esc(MC.liveEmbed)}" style="position:absolute;inset:0;width:100%;height:100%;border:0" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin" title="Live Streaming"></iframe></div></div><a class="sd-btn" style="font-size:10px;margin-top:10px;margin-left:8px;display:inline-block;text-decoration:none" href="${esc(MC.liveStream)}" target="_blank" rel="noopener">LIVE STREAMING</a>`:""}</div></div>`,
gallery:()=>`<h2 class="ptitle">GALERI LUMBUNG</h2><p class="psub">Panen kenangan terbaik.</p>
<div class="frames2">
@foreach(array_slice($gallery, 0, 6) as $i => $img)
<div class="frame2" data-full="{{ $img }}"><img src="{{ $img }}" alt="Galeri {{ $i+1 }}" loading="lazy"><p>Kenangan {{ $i+1 }}</p></div>
@endforeach
</div>
@if(empty($gallery))<p class="psub">Belum ada foto galeri.</p>@endif`,
rsvp:()=>`<h2 class="ptitle">KANTOR POS</h2><p class="psub">Kirim surat konfirmasimu lewat pos kebun.</p>
<form id="rf2">
<label class="lbl">NAMA LENGKAP</label><input class="sd-input" id="rn2" required maxlength="40" placeholder="Tulis namamu...">
<label class="lbl">KEHADIRAN</label><select class="sd-input" id="rh2"><option value="hadir">Hadir, siap panen!</option><option value="tidak">Tidak bisa ikut</option></select>
<label class="lbl">JUMLAH TAMU</label><select class="sd-input" id="rj2"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select>
<div style="margin-top:14px"><button class="sd-btn green" type="submit" style="font-size:11px">KIRIM RSVP</button></div>
</form>
<div class="scoreboard"><h4>&#9733; DAFTAR PETANI HADIR</h4><div id="rl2"><div class="srow"><span style="color:#777">Memuat...</span></div></div></div>`,
ucapan:()=>`<h2 class="ptitle">PAPAN UCAPAN</h2><p class="psub">Tempel pesanmu di papan pengumuman kebun.</p>
<form id="uf2">
<input class="sd-input" id="un2" required maxlength="40" placeholder="Namamu..." style="margin-bottom:10px">
<input class="sd-input" id="up2" required maxlength="160" placeholder="Tulis ucapan terbaikmu...">
<div style="margin-top:10px"><button class="sd-btn gold" type="submit" style="font-size:10px">KIRIM UCAPAN</button></div>
</form>
<div class="scoreboard" style="margin-top:14px"><h4>&#9733; PESAN TERBARU</h4><div id="ul2"><div class="msg-row"><div class="ps" style="color:#777">Memuat...</div></div></div></div>`,
gift:()=>`<h2 class="ptitle">GUDANG HARTA</h2><p class="psub">Klik petinya untuk membuka.</p>
<div class="chest-zone"><div class="chest" id="ch2"><div class="glow"></div><div class="lid"></div><div class="base"></div></div>
<div style="font-family:'Press Start 2P',monospace;font-size:9px;color:#5a5a5a" id="chL2">PETI TERKUNCI</div></div>
<div class="gift-grid" id="ga2" hidden>
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
<div class="acct"><div class="bank">{{ $a['bank'] }}</div>
    @if(!empty($a['logo']))<img src="{{ $a['logo'] }}" alt="Logo bank">@endif<div class="no" id="gno{{ $i }}">{{ $a['no'] }}</div>
<div class="an">{{ $a['an'] }}</div>
<button class="sd-btn gold" style="font-size:10px;padding:10px 14px" data-g="{{ $i }}">SALIN</button></div>
@endforeach
</div>
@if(!empty($giftRecipient))
<p class="psub" style="margin-top:12px">Penerima hadiah fisik: <b>{{ $giftRecipient }}</b></p>
@endif
@if(!empty(sdaGet('gift_address')))
<div class="adv" style="margin-top:12px"><div><h3>KIRIM HADIAH FISIK</h3><p>{{ sdaGet('gift_address') }}</p>
@if(sdaGet('gift_maps_link'))<a class="sd-btn gold" style="font-size:10px;margin-top:10px;display:inline-block;text-decoration:none" href="{{ sdaGet('gift_maps_link') }}" target="_blank" rel="noopener">&#9673; BUKA PETA</a>@endif</div></div>
@endif
@if(empty($accounts))<p class="psub">Belum ada info hadiah.</p>@endif`
};

function openPanel(id){
  markVisited(id);
  panelOpen=true;gameOn=false;
  $("#panelBody").innerHTML=TPL[id]();
  $("#panelOverlay").hidden=false;
  $("#panel").scrollTop=0;
  if(id==="couple"){
    drawFace($("#pfB"),{skin:"#f0c49c",hair:"#5a3a22",shirt:"#c2437f",style:"bride"});
    drawFace($("#pfG"),{skin:"#e8b98a",hair:"#2b2b2b",shirt:"#2f6db3",style:"groom"});
    if(MC.guestQrOn&&MC.guestQr&&window.QRCode){
      new QRCode($("#qrBox"),{text:MC.guestQr,width:180,height:180,correctLevel:QRCode.CorrectLevel.M});
      var qb=document.getElementById('qrBox');if(qb){if(!document.getElementById('qrDownloadBtn')){var db=document.createElement('button');db.id='qrDownloadBtn';db.type='button';db.textContent='Unduh QR';db.style.cssText='display:block;width:100%;margin-top:14px;padding:12px;border:none;border-radius:999px;background:#775a19;color:#fff;font-weight:700;font-size:14px;cursor:pointer';db.onclick=function(){var c=qb.querySelector('canvas');var im=qb.querySelector('img');var u=c?c.toDataURL('image/png'):(im?im.src:'');if(!u)return;var a=document.createElement('a');a.href=u;a.download='QR-Undangan.png';a.click();};qb.parentNode.insertBefore(db,qb.nextSibling);}}
    }
  }
  if(id==="count")startCd();
  if(id==="event")$$("#panelBody .ev-ic").forEach(c=>{
    const t=document.createElement("canvas");t.width=16;t.height=16;
    drawSprite(t,ICONS[c.dataset.ic],PAL);c.getContext("2d").drawImage(t,0,0,16,16);});
  if(id==="rsvp")wireRsvp();
  if(id==="ucapan")wireUcapan();
  if(id==="gift")wireChest();
  if(id==="gallery")$$("#panelBody .frame2").forEach(f=>f.addEventListener("click",()=>{
    window.open(f.dataset.full,"_blank");blip(700,0.06,0.05);}));
  blip(660,0.07,0.05);
}
function closePanel(){
  $("#panelOverlay").hidden=true;panelOpen=false;gameOn=true;
  stopCd();blip(440,0.07,0.05);
  if(visited.size>=LOCS.length&&!completedShown){completedShown=true;
    setTimeout(openComplete,400);}
}
$("#panelClose").addEventListener("click",closePanel);
$("#panelOverlay").addEventListener("click",e=>{if(e.target.id==="panelOverlay")closePanel();});
/* countdown panel */
let cdT=null;
function startCd(){
  const target=new Date(MC.countdownISO).getTime(),pad=n=>String(n).padStart(2,"0");
  const tick=()=>{const e=$("#cD");if(!e)return;let d=Math.max(0,target-Date.now());
    e.textContent=pad(Math.floor(d/864e5));$("#cH").textContent=pad(Math.floor(d/36e5)%24);
    $("#cM").textContent=pad(Math.floor(d/6e4)%60);$("#cS").textContent=pad(Math.floor(d/1e3)%60);};
  tick();cdT=setInterval(tick,1000);
}
function stopCd(){clearInterval(cdT);}
/* rsvp + ucapan */
async function apiGet(path){
  const r=await fetch(path);const j=await r.json();return j.data||[];
}
function wireRsvp(){
  const box=$("#rl2");
  const load=async()=>{try{const rows=(await apiGet("/api/rsvp?inv_id="+MC.invId)).slice(0,8);
    box.innerHTML=rows.length?rows.map(x=>
      `<div class="srow"><span>${esc(x.nama)} <small style="color:#888">x${x.jumlah_tamu}</small></span>
       <span style="font-family:'Press Start 2P',monospace;font-size:8px;color:${x.status==="hadir"?"#4e9a26":"#c0392b"}">${x.status.toUpperCase()}</span></div>`
    ).join(""):'<div class="srow"><span style="color:#777">Belum ada yang mendaftar.</span></div>';
  }catch(e){box.innerHTML='<div class="srow"><span style="color:#777">Gagal memuat.</span></div>';}};
  load();
  $("#rf2").onsubmit=async e=>{e.preventDefault();
    const nama=$("#rn2").value.trim();if(!nama){toast("Isi dulu namanya!");return;}
    const btn=e.target.querySelector("[type=submit]");btn.disabled=true;
    try{
      const r=await fetch("/api/rsvp",{method:"POST",headers:{"Content-Type":"application/json"},
        body:JSON.stringify({invitation_id:MC.invId,nama,
          jumlah_tamu:parseInt($("#rj2").value)||1,status:$("#rh2").value,alasan:""})});
      const j=await r.json();if(!j.success)throw new Error(j.message||"Gagal");
      e.target.reset();levelUp();toast("RSVP TERSIMPAN!");load();
    }catch(err){toast("Gagal: "+err.message);}
    btn.disabled=false;};
}
function timeAgo(s){const d=new Date(String(s).replace(" ","T")+"+07:00");if(isNaN(d))return "";
  const n=(Date.now()-d.getTime())/1000;if(n<60)return "Baru saja";
  if(n<3600)return Math.floor(n/60)+" menit yang lalu";if(n<86400)return Math.floor(n/3600)+" jam yang lalu";
  return Math.floor(n/86400)+" hari yang lalu";}
function wireUcapan(){
  const box=$("#ul2");
  const load=async()=>{try{const rows=(await apiGet("/api/ucapan?inv_id="+MC.invId)).slice(0,8);
    box.innerHTML=rows.length?rows.map(x=>
      `<div class="msg-row"><div class="nm">${esc(x.nama)}</div><div class="ps">${esc(x.pesan)}</div>
       <div class="tm">${timeAgo(x.created_at)}</div></div>`
    ).join(""):'<div class="msg-row"><div class="ps" style="color:#777">Belum ada ucapan.</div></div>';
  }catch(e){box.innerHTML='<div class="msg-row"><div class="ps" style="color:#777">Gagal memuat.</div></div>';}};
  load();
  $("#uf2").onsubmit=async e=>{e.preventDefault();
    const nama=$("#un2").value.trim(),pesan=$("#up2").value.trim();
    if(!nama||!pesan){toast("Lengkapi nama & ucapan!");return;}
    const btn=e.target.querySelector("[type=submit]");btn.disabled=true;
    try{
      const r=await fetch("/api/ucapan",{method:"POST",headers:{"Content-Type":"application/json"},
        body:JSON.stringify({invitation_id:MC.invId,nama,pesan})});
      const j=await r.json();if(!j.success)throw new Error(j.message||"Gagal");
      e.target.reset();blip(880,0.1,0.05);toast("UCAPAN TERKIRIM!");load();
    }catch(err){toast("Gagal: "+err.message);}
    btn.disabled=false;};
}
/* peti harta */
function wireChest(){
  const ch=$("#ch2");if(!ch)return;
  ch.addEventListener("click",e=>{e.stopPropagation();
    const open=!ch.classList.contains("open");
    ch.classList.toggle("open",open);
    $("#chL2").textContent=open?"PETI TERBUKA!":"PETI TERKUNCI";
    const ga=$("#ga2");if(ga)ga.hidden=!open;
    if(open){levelUp();toast("Harta karun ditemukan!");}else blip(300,0.08,0.05);});
  $$("#ga2 [data-g]").forEach(b=>b.addEventListener("click",async e=>{e.stopPropagation();
    const t=$("#gno"+b.dataset.g).textContent.replace(/\s/g,"");
    try{await navigator.clipboard.writeText(t);}catch(err){
      const ta=document.createElement("textarea");ta.value=t;document.body.appendChild(ta);
      ta.select();document.execCommand("copy");ta.remove();}
    blip(880,0.08,0.05);toast("NOMOR TERSALIN!");}));
}
function openComplete(){
  panelOpen=true;gameOn=false;
  $("#panelBody").innerHTML=`<div class="complete">
<h2>&#127793; PANEN RAYA SELESAI! &#127793;</h2>
<p>Terima kasih telah menyelesaikan petualangan kebun ini! Kehadiran dan doa restumu adalah panen terbaik bagi kami.</p>
<p>Kami yang berbahagia,</p>
<p style="font-family:'Press Start 2P',monospace;font-size:13px;color:#3a5a22">${esc(MC.brideNick)} &amp; ${esc(MC.groomNick)}</p>
<p style="font-family:'Press Start 2P',monospace;font-size:9px;color:#777;margin-top:14px">${esc(MC.dateText)}</p></div>`;
  $("#panelOverlay").hidden=false;
  levelUp();setTimeout(levelUp,400);
  for(let i=0;i<5;i++)setTimeout(()=>heartFx(Math.random()*VW,VH*0.3,8),i*220);
}
/* ---------- partikel, NPC, render, loop ---------- */
const parts=[];
function heartFx(x,y,n){n=n||8;
  for(let i=0;i<n;i++)parts.push({x:x+(Math.random()-0.5)*40,y,vx:(Math.random()-0.5)*50,
    vy:-(50+Math.random()*70),age:0,life:1.4+Math.random(),s:7+Math.random()*7});}
const npcState=junimos.map(()=>({x:Math.random()*WW,y:Math.random()*WH,tx:0,ty:0,t:0,hop:Math.random()*6}));
const henState=hens.map(()=>({t:Math.random()*6,peck:0}));
let tG=0;
function drawJunimo(c,x,y,col,hop){
  const b=Math.abs(Math.sin(hop))*6;
  c.fillStyle="rgba(0,0,0,.2)";c.beginPath();c.ellipse(x,y+2,14,5,0,0,6.29);c.fill();
  c.fillStyle=col;c.beginPath();c.arc(x,y-14-b,15,0,6.29);c.fill();
  c.fillStyle="rgba(255,255,255,.35)";c.beginPath();c.arc(x-5,y-20-b,5,0,6.29);c.fill();
  c.fillStyle="#141414";c.fillRect(x-8,y-18-b,5,7);c.fillRect(x+3,y-18-b,5,7);
  c.strokeStyle="#3f8f23";c.lineWidth=3;
  c.beginPath();c.moveTo(x,y-29-b);c.lineTo(x-4,y-38-b);c.stroke();
  c.fillStyle="#5eb83d";c.beginPath();c.arc(x-5,y-39-b,4,0,6.29);c.fill();
}
function drawHen(c,x,y,peck){
  const dip=peck>0?5:0;
  c.fillStyle="rgba(0,0,0,.2)";c.beginPath();c.ellipse(x,y+2,12,4,0,0,6.29);c.fill();
  c.fillStyle="#f2f2f2";c.beginPath();c.arc(x,y-12,11,0,6.29);c.fill();
  c.fillStyle="#fff";c.beginPath();c.arc(x+9,y-22+dip,8,0,6.29);c.fill();
  c.fillStyle="#e03131";c.fillRect(x+6,y-32+dip,6,6);
  c.fillStyle="#f7a821";c.fillRect(x+14,y-24+dip,7,5);
  c.fillStyle="#141414";c.fillRect(x+10,y-26+dip,3,3);
  c.fillStyle="#f7a821";c.fillRect(x-4,y-2,4,6);c.fillRect(x+3,y-2,4,6);
}
function render(dt){
  tG+=dt;
  ctx.fillStyle="#79c25f";ctx.fillRect(0,0,VW,VH);
  if(groundCv)ctx.drawImage(groundCv,Math.round(-camX),Math.round(-camY));
  /* junimo berkeliaran */
  npcState.forEach((n,i)=>{
    const j=junimos[i];
    n.t-=dt;
    if(n.t<=0){n.tx=j.tx*TILE+(Math.random()-0.5)*220;n.ty=j.ty*TILE+(Math.random()-0.5)*220;n.t=3+Math.random()*4;}
    const dx=n.tx-n.x,dy=n.ty-n.y,d=Math.hypot(dx,dy);
    if(d>8){n.x+=dx/d*40*dt;n.y+=dy/d*40*dt;}
    n.hop+=dt*6;
    const sx=n.x-camX,sy=n.y-camY;
    if(sx>-40&&sx<VW+40&&sy>-60&&sy<VH+40)drawJunimo(ctx,sx,sy,j.c,n.hop);
  });
  /* ayam */
  henState.forEach((h,i)=>{
    h.t-=dt;if(h.t<=0){h.peck=0.5;h.t=1.5+Math.random()*3;}
    if(h.peck>0)h.peck-=dt;
    const hx=hens[i].tx*TILE-camX,hy=hens[i].ty*TILE-camY;
    if(hx>-40&&hx<VW+40&&hy>-60&&hy<VH+40)drawHen(ctx,hx,hy,h.peck);
  });
  /* tanda ! di lokasi belum dikunjungi */
  LOCS.forEach(L=>{
    if(visited.has(L.id))return;
    const x=L.doorX-camX,y=L.doorY-camY-70+Math.sin(tG*3)*4;
    if(x<-30||x>VW+30||y<-30||y>VH+30)return;
    ctx.fillStyle="#fff";ctx.fillRect(x-11,y-22,22,22);
    ctx.fillStyle="#c0392b";ctx.fillRect(x-11,y-22,22,4);ctx.fillRect(x-11,y-4,22,4);
    ctx.fillStyle="#c0392b";ctx.font="bold 15px monospace";ctx.textAlign="center";ctx.fillText("!",x,y-6);});
  const px=player.x-camX,py=player.y-camY;
  drawFarmer(ctx,px,py,player.pal,player.dir,player.frame,player.moving,1.3);
  ctx.font="8px 'Press Start 2P',monospace";ctx.textAlign="center";
  const pname=(MC.guestName&&MC.guestName!=="Bapak/Ibu/Saudara/i")?MC.guestName:"PETANI";
  const pshort=pname.length>14?pname.slice(0,14)+"...":pname;
  const pw=ctx.measureText(pshort).width+14;
  ctx.fillStyle="rgba(0,0,0,.55)";ctx.fillRect(px-pw/2,py-40,pw,13);
  ctx.fillStyle="#fff";ctx.fillText(pshort,px,py-30);
  for(let i=parts.length-1;i>=0;i--){const p=parts[i];p.age+=dt;
    if(p.age>=p.life){parts.splice(i,1);continue;}
    p.x+=p.vx*dt;p.y+=p.vy*dt;
    ctx.globalAlpha=Math.min(1,(1-p.age/p.life)*1.6);
    drawHeartShape(ctx,p.x,p.y,p.s);}
  ctx.globalAlpha=1;
}
let last=performance.now(),started=false;
function loop(now){
  const dt=Math.min(0.05,(now-last)/1000);last=now;
  if(started){
    if(gameOn&&!panelOpen)updatePlayer(dt);
    if(!panelOpen)checkNear();else $("#hint").hidden=true;
    render(dt);
  }
  requestAnimationFrame(loop);
}
$("#startBtn").addEventListener("click",()=>{
  blip(520,0.08,0.05);setTimeout(()=>blip(780,0.1,0.05),110);
  player.pal=CHARS[charIdx];
  $("#start").style.display="none";$("#game").hidden=false;
  sizeCanvas();buildGround();renderPips();
  npcState.forEach((n,i)=>{n.x=junimos[i].tx*TILE;n.y=junimos[i].ty*TILE;});
  camX=Math.max(0,Math.min(WW-VW,player.x-VW/2));
  camY=Math.max(0,Math.min(WH-VH,player.y-VH/2));
  if("ontouchstart"in window||navigator.maxTouchPoints>0){
    $("#dpad").classList.add("show");$("#actBtn").classList.add("show");}
  started=true;gameOn=true;levelUp();
  toast("Selamat bertani! Kunjungi 8 lokasi!");
});
addEventListener("resize",()=>{if(started)sizeCanvas();});
addEventListener("contextmenu",e=>{if(e.target.closest&&e.target.closest("#game"))e.preventDefault();});
addEventListener("touchmove",e=>{if(started&&!(e.target.closest&&e.target.closest("#panelOverlay")))e.preventDefault();},{passive:false});
requestAnimationFrame(loop);
</script>
</body>
</html>
