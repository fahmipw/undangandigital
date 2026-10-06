<?php
/* ============================================================
   TEMA: MINECRAFT ADVENTURE (karakter bisa digerakkan)
   Dipilih via settings: theme = minecraft-adventure
   ============================================================ */
$GLOBALS['mca_settings'] = $settings ?? [];
if (!function_exists('mcaGet')) {
  function mcaGet($key, $default = '') {
    $s = $GLOBALS['mca_settings'];
    if (!isset($s[$key]) || trim((string)$s[$key]) === '') return $default;
    return $s[$key];
  }
}
if (!function_exists('mcaDate')) {
  function mcaDate($dateStr) {
    if (!$dateStr) return '';
    $days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($dateStr);
    if (!$ts) return $dateStr;
    return $days[date('w',$ts)].', '.date('j',$ts).' '.$months[date('n',$ts)].' '.date('Y',$ts);
  }
}
if (!function_exists('mcaTime')) {
  function mcaTime($t) {
    $t = trim((string)$t); if ($t === '') return '08:00';
    $t = str_replace('.', ':', $t);
    return preg_match('/^\d{1,2}(:\d{2})?$/', $t) ? (strpos($t, ':') === false ? $t.':00' : $t) : '08:00';
  }
}
$groomNick = mcaGet('groom_nickname') ?: mcaGet('groom_name', 'Mempelai Pria');
$brideNick = mcaGet('bride_nickname') ?: mcaGet('bride_name', 'Mempelai Wanita');
$groomFull = mcaGet('groom_name', 'Mempelai Pria');
$brideFull = mcaGet('bride_name', 'Mempelai Wanita');
$wDate  = mcaGet('wedding_date', date('Y-m-d'));
$wTime  = mcaTime(mcaGet('wedding_time_start', '08:00'));
$wTimeE = mcaTime(mcaGet('wedding_time_end', '10:00'));
$rDate  = mcaGet('reception_date', $wDate);
$rTimeS = mcaTime(mcaGet('reception_time_start', '11:00'));
$rTimeE = mcaTime(mcaGet('reception_time_end', '13:00'));
$countdownISO = $wDate.'T'.$wTime.':00+07:00';
$accounts = [];
$__gbanks = json_decode(mcaGet('gift_banks', ''), true) ?: [];
foreach ($__gbanks as $__b) {
  if (empty($__b['account'])) continue;
  $accounts[] = ['bank'=>($__b['name'] ?? 'Bank'),'no'=>$__b['account'],'an'=>($__b['owner'] ?? ''),'logo'=>($__b['logo'] ?? '')];
}
if (!$accounts) {
  if (mcaGet('gift_account')) $accounts[] = ['bank'=>mcaGet('gift_bank','Bank'),'no'=>mcaGet('gift_account'),'an'=>mcaGet('gift_owner',''),'logo'=>mcaGet('gift_bank_logo','')];
  if (mcaGet('gift_account2')) $accounts[] = ['bank'=>mcaGet('gift_bank2','Bank'),'no'=>mcaGet('gift_account2'),'an'=>mcaGet('gift_owner2',''),'logo'=>mcaGet('gift_bank_logo','')];
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
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Petualangan Undangan &mdash; {{ $brideNick }} &amp; {{ $groomNick }}</title>
<meta property="og:type" content="website">
<meta property="og:title" content="Undangan Pernikahan {{ $brideNick }} & {{ $groomNick }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
<style>
:root{--panel:#c6c6c6;--gold:#fcee5e;--green:#7bff5e}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html,body{height:100%;overflow:hidden;background:#0d1420}
body{font-family:'VT323',monospace;font-size:20px;color:#f2f2f2}
canvas{image-rendering:pixelated;image-rendering:crisp-edges}
.mc-panel{background:var(--panel);border:3px solid #141414;color:#1c1c1c;
  box-shadow:inset 3px 3px 0 rgba(255,255,255,.6),inset -3px -3px 0 rgba(0,0,0,.35)}
.mc-btn{font-family:'Press Start 2P',monospace;font-size:12px;color:#fff;text-shadow:2px 2px 0 #3a3a3a;
  background:linear-gradient(#a3a3a3,#7f7f7f);border:2px solid #0a0a0a;cursor:pointer;
  box-shadow:inset 2px 2px 0 rgba(255,255,255,.5),inset -2px -2px 0 rgba(0,0,0,.5);
  padding:14px 20px;letter-spacing:1px}
.mc-btn:active{transform:translateY(2px)}
.mc-btn.green{background:linear-gradient(#79d154,#4e9a26)}
.mc-btn.gold{background:linear-gradient(#ffe36e,#d9a821);text-shadow:2px 2px 0 #6e4d05}
.mc-input{width:100%;background:#0f0f0f;color:#fff;border:2px solid #9a9a9a;font-family:'VT323',monospace;
  font-size:21px;padding:11px 12px;box-shadow:inset 2px 2px 0 rgba(0,0,0,.7);outline:none}
.mc-input:focus{border-color:#fff}
select.mc-input{appearance:none}
.lbl{font-family:'Press Start 2P',monospace;font-size:10px;color:#3a3a3a;display:block;margin:14px 0 7px;letter-spacing:1px}
#start{position:fixed;inset:0;z-index:50;display:flex;align-items:center;justify-content:center;
  background:radial-gradient(ellipse at 50% 30%,#1b2f4a 0%,#0d1420 70%);padding:20px;overflow-y:auto}
.start-card{max-width:600px;width:100%;padding:30px 26px;text-align:center;max-height:94vh;overflow-y:auto}
.start-card h1{font-family:'Press Start 2P',monospace;font-size:clamp(16px,5vw,24px);color:#2b2b2b;
  line-height:1.6;margin-bottom:6px;text-shadow:2px 2px 0 rgba(255,255,255,.5)}
.start-card .sub{font-size:22px;color:#4a4a4a;margin-bottom:18px}
.char-row{display:flex;gap:14px;justify-content:center;margin-bottom:8px;flex-wrap:wrap}
.char-opt{background:#8b8b8b;border:3px solid #141414;cursor:pointer;padding:12px 14px;text-align:center;
  box-shadow:inset 2px 2px 0 rgba(255,255,255,.4)}
.char-opt.sel{background:#e8e8e8;outline:3px solid var(--gold)}
.char-opt canvas{width:58px;height:80px;background:#79b8f2;display:block;margin-bottom:8px}
.char-opt span{font-family:'Press Start 2P',monospace;font-size:9px;color:#1c1c1c}
.controls-help{font-size:20px;color:#4a4a4a;margin:14px 0 20px;line-height:1.6}
.controls-help b{color:#1c1c1c}
#game{position:fixed;inset:0}
#cv{position:absolute;inset:0;width:100%;height:100%;display:block}
#hud{position:absolute;top:0;left:0;right:0;display:flex;justify-content:space-between;align-items:flex-start;
  padding:10px 12px;pointer-events:none;z-index:5}
#hud>*{pointer-events:auto}
.progress{background:rgba(10,10,10,.78);border:2px solid #fff;box-shadow:0 0 0 2px #000;
  padding:8px 12px;display:flex;align-items:center;gap:8px}
.progress .px{font-family:'Press Start 2P',monospace;font-size:10px;color:#fff}
.pips{display:flex;gap:6px}
.pip{width:22px;height:22px;background:#2a2a2a;border:2px solid #555;display:flex;align-items:center;justify-content:center;
  font-size:13px;filter:grayscale(1);opacity:.5}
.pip.on{filter:none;opacity:1;border-color:var(--gold);box-shadow:0 0 6px var(--gold)}
.hud-btns{display:flex;gap:8px}
.hud-btns .mc-btn{font-size:14px;padding:10px 12px}
#hint{position:absolute;left:50%;bottom:118px;transform:translateX(-50%);z-index:5;
  background:rgba(10,10,10,.85);border:2px solid var(--gold);box-shadow:0 0 0 2px #000;
  color:#fff;font-family:'Press Start 2P',monospace;font-size:10px;padding:12px 16px;text-align:center;
  line-height:1.8;max-width:92vw;animation:hintpulse 1.2s ease-in-out infinite}
@keyframes hintpulse{50%{transform:translateX(-50%) scale(1.04)}}
#dpad{position:absolute;left:14px;bottom:14px;z-index:5;display:none;
  grid-template-columns:repeat(3,58px);grid-template-rows:repeat(3,58px);gap:4px;opacity:.92}
#dpad.show{display:grid}
.dbtn{background:rgba(20,20,20,.72);border:2px solid #fff;color:#fff;font-size:20px;
  display:flex;align-items:center;justify-content:center;touch-action:none;user-select:none}
.dbtn:active{background:rgba(120,120,120,.8)}
#actBtn{position:absolute;right:16px;bottom:22px;z-index:5;display:none;width:84px;height:84px;border-radius:50%;
  font-size:11px;padding:0}
#actBtn.show{display:block}
#actBtn.ready{animation:actpulse .8s ease-in-out infinite;border-color:var(--gold)}
@keyframes actpulse{50%{transform:scale(1.1);box-shadow:0 0 14px var(--gold)}}
#panelOverlay{position:fixed;inset:0;z-index:40;background:rgba(5,8,14,.78);
  display:flex;align-items:center;justify-content:center;padding:16px}
#panelOverlay[hidden]{display:none}
.sheet{width:min(680px,96vw);max-height:92vh;overflow-y:auto;padding:26px 24px;position:relative;
  animation:sheetin .25s ease-out}
@keyframes sheetin{from{transform:scale(.92) translateY(16px);opacity:0}}
#panelClose{position:absolute;top:10px;right:10px;font-size:11px;padding:10px 12px;z-index:2}
.sheet h2.ptitle{font-family:'Press Start 2P',monospace;font-size:15px;color:#2b2b2b;margin:6px 0 4px;
  text-align:center;line-height:1.7}
.sheet .psub{text-align:center;color:#5a5a5a;font-size:21px;margin-bottom:18px}
.player-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
@media(max-width:520px){.player-grid{grid-template-columns:1fr}}
.pcard{background:#a8a8a8;border:3px solid #141414;padding:18px 14px;text-align:center;
  box-shadow:inset 2px 2px 0 rgba(255,255,255,.4)}
.pcard canvas{width:96px;height:96px;background:#8ab6e0;border:3px solid #141414;margin-bottom:10px}
.pcard h3{font-family:'Press Start 2P',monospace;font-size:12px;line-height:1.6;margin-bottom:8px}
.ptag{display:inline-block;font-family:'Press Start 2P',monospace;font-size:8px;background:#2b2b2b;color:var(--gold);
  padding:6px 8px;margin-bottom:10px}
.pcard p{font-size:20px;color:#2e2e2e}
.hud2{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin:6px 0}
.hud2 .slot{background:#101010;border:3px solid #000;box-shadow:0 0 0 2px #3a3a3a;padding:14px 4px;text-align:center}
.hud2 .num{font-family:'Press Start 2P',monospace;font-size:20px;color:var(--green);display:block;margin-bottom:6px}
.hud2 .unit{font-family:'Press Start 2P',monospace;font-size:8px;color:#9fb3c8}
.adv{background:#1b1b1b;border:2px solid #000;display:flex;gap:14px;padding:16px;margin-bottom:12px;color:#e8e8e8}
.adv .ic{flex:none;width:52px;height:52px;background:var(--panel);border:3px solid #000;display:flex;align-items:center;justify-content:center}
.adv .ic canvas{width:34px;height:34px}
.adv h3{font-family:'Press Start 2P',monospace;font-size:11px;color:var(--gold);margin-bottom:6px}
.adv p{font-size:20px;color:#cfcfcf}
.adv .tm{color:var(--green);font-size:21px}
.frames2{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
.frame2{background:#5d4126;border:3px solid #141414;padding:8px;box-shadow:inset 2px 2px 0 rgba(255,255,255,.25)}
.frame2 img{width:100%;display:block;aspect-ratio:1;object-fit:cover;background:#000}
.frame2 p{text-align:center;color:#ffe9b8;font-size:19px;margin-top:6px}
.scoreboard{margin-top:16px;background:#101010;border:3px solid #000;padding:4px 0}
.scoreboard h4{font-family:'Press Start 2P',monospace;font-size:9px;color:var(--gold);padding:10px 14px;border-bottom:2px solid #2a2a2a}
.srow{display:flex;justify-content:space-between;align-items:center;padding:8px 14px;border-bottom:1px solid #222;font-size:20px;color:#e8e8e8}
.srow:last-child{border:none}
.msg-row{padding:10px 14px;border-bottom:1px solid #222}
.msg-row:last-child{border:none}
.msg-row .nm{font-family:'Press Start 2P',monospace;font-size:8px;color:var(--gold);margin-bottom:5px}
.msg-row .ps{font-size:20px;color:#e8e8e8}
.msg-row .tm{font-size:16px;color:#777;margin-top:3px}
.chest-zone{display:flex;flex-direction:column;align-items:center;gap:8px;padding:8px 0}
.chest{width:120px;height:96px;position:relative;cursor:pointer;perspective:400px}
.chest .base{position:absolute;left:0;right:0;bottom:0;height:60px;background:linear-gradient(#a9743f,#7d5327);border:3px solid #141414}
.chest .base::after{content:"";position:absolute;left:50%;top:6px;transform:translateX(-50%);width:14px;height:20px;
  background:linear-gradient(#d8d8d8,#8a8a8a);border:2px solid #141414}
.chest .lid{position:absolute;left:0;right:0;top:0;height:40px;background:linear-gradient(#b9834b,#8a5e2e);
  border:3px solid #141414;transform-origin:top center;transition:transform .45s;z-index:2}
.chest.open .lid{transform:rotateX(-105deg)}
.chest .glow{position:absolute;left:8%;right:8%;top:30px;height:40px;z-index:1;opacity:0;transition:opacity .5s;
  background:radial-gradient(ellipse at center,#ffe9a3 0%,rgba(255,220,120,.55) 45%,transparent 75%)}
.chest.open .glow{opacity:1}
.gift-grid{display:grid;gap:12px;grid-template-columns:1fr 1fr;margin-top:14px}
@media(max-width:520px){.gift-grid{grid-template-columns:1fr}}
.acct{background:#a8a8a8;border:3px solid #141414;padding:14px}
.acct .bank{font-family:'Press Start 2P',monospace;font-size:11px;margin-bottom:6px}
.acct .no{font-family:'Press Start 2P',monospace;font-size:13px;color:#123f12;word-break:break-all;margin-bottom:4px}
.acct .an{font-size:19px;color:#333;margin-bottom:10px}
.complete{text-align:center;padding:10px 4px}
.complete h2{font-family:'Press Start 2P',monospace;font-size:19px;color:#2b6b1c;line-height:1.7;margin-bottom:14px}
.complete p{font-size:22px;color:#3d3d3d;margin-bottom:10px}
#toast{position:fixed;left:50%;bottom:110px;transform:translateX(-50%) translateY(16px);z-index:60;
  background:#101010;color:#fff;border:2px solid #fff;box-shadow:0 0 0 2px #000;
  font-family:'Press Start 2P',monospace;font-size:10px;padding:13px 18px;opacity:0;pointer-events:none;
  transition:opacity .3s,transform .3s;max-width:90vw;text-align:center;line-height:1.8}
#toast.show{opacity:1;transform:translateX(-50%) translateY(0)}
@media (prefers-reduced-motion:reduce){
  #hint,.sheet,#actBtn.ready{animation:none}
}
</style>
</head>
<body>

<div id="start">
  <div class="mc-panel start-card">
    <h1>MISI:<br>UNDANGAN PERNIKAHAN</h1>
    <p class="sub">{{ $brideNick }} &amp; {{ $groomNick }} &mdash; {{ mcaDate($wDate) }}</p>
    <p class="px" style="font-family:'Press Start 2P',monospace;font-size:10px;color:#2b2b2b;margin-bottom:12px">PILIH KARAKTERMU</p>
    <div class="char-row" id="charRow"></div>
    <p class="controls-help" id="controlsHelp">
      <b>WASD / Panah</b> untuk jalan &mdash; <b>E / Spasi</b> untuk masuk lokasi<br>
      Di HP: pakai D-pad + tombol AKSI<br>
      Kunjungi <b>6 lokasi</b> untuk membuka semua menu undangan!
    </p>
    <button class="mc-btn green" id="startBtn" style="font-size:13px">&#9654; MULAI BERPETUALANG</button>
  </div>
</div>

<div id="game" hidden>
  <canvas id="cv"></canvas>
  <div id="hud">
    <div class="progress"><span class="px" id="progText">0/6</span><div class="pips" id="pips"></div></div>
    <div class="hud-btns">
      <button class="mc-btn" id="musicBtn" title="Musik">&#9834;</button>
      <button class="mc-btn" id="helpBtn" title="Bantuan">?</button>
    </div>
  </div>
  <div id="hint" hidden></div>
  <div id="dpad">
    <span></span><button class="dbtn" data-dx="0" data-dy="-1">&#9650;</button><span></span>
    <button class="dbtn" data-dx="-1" data-dy="0">&#9664;</button><span></span><button class="dbtn" data-dx="1" data-dy="0">&#9654;</button>
    <span></span><button class="dbtn" data-dx="0" data-dy="1">&#9660;</button><span></span>
  </div>
  <button class="mc-btn gold" id="actBtn">AKSI</button>
</div>

<div id="panelOverlay" hidden>
  <div class="mc-panel sheet">
    <button class="mc-btn" id="panelClose">&#10005;</button>
    <div id="panelBody"></div>
  </div>
</div>

<div id="toast"></div>
<script>
"use strict";
/* Data dari Blade */
const MC = {
  invId: {{ (int)$invId }},
  countdownISO: @json($countdownISO),
  musicUrl: @json($musicUrl),
  brideNick: @json($brideNick), groomNick: @json($groomNick),
  brideFull: @json($brideFull), groomFull: @json($groomFull),
  dateText: @json(mcaDate($wDate)),
  akad: { date: @json(mcaDate($wDate)), time: @json($wTime.' – '.$wTimeE.' '.mcaGet('wedding_timezone','WIB')),
    venue: @json(mcaGet('wedding_location','Kediaman Mempelai')), maps: @json(mcaGet('wedding_map_link','')) },
  resepsi: { date: @json(mcaDate($rDate)), time: @json($rTimeS.' – '.$rTimeE.' '.mcaGet('wedding_timezone','WIB')),
    venue: @json(mcaGet('reception_location', mcaGet('wedding_location','Kediaman Mempelai'))), maps: @json(mcaGet('reception_map_link','')) }
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
function drawPlayer(ctx,x,y,o,dir,frame,moving,scale){
  const s=scale;ctx.save();ctx.translate(Math.round(x),Math.round(y));
  ctx.fillStyle="rgba(0,0,0,.28)";ctx.beginPath();ctx.ellipse(0,13*s,9*s,3.5*s,0,0,6.29);ctx.fill();
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
  ctx.fillStyle=o.skin;ctx.fillRect(-7*s,-14*s,14*s,12*s);
  ctx.fillStyle=o.hair;
  if(dir==="up"){ctx.fillRect(-7*s,-14*s,14*s,12*s);}
  else{ctx.fillRect(-7*s,-14*s,14*s,4*s);
    if(dir==="down"){ctx.fillRect(-7*s,-14*s,3*s,9*s);ctx.fillRect(4*s,-14*s,3*s,9*s);}
    else if(dir==="left"){ctx.fillRect(-7*s,-14*s,8*s,4*s);}
    else{ctx.fillRect(-1*s,-14*s,8*s,4*s);}}
  ctx.fillStyle="#141414";
  if(dir==="down"){ctx.fillRect(-4*s,-7*s,2.4*s,2.8*s);ctx.fillRect(1.6*s,-7*s,2.4*s,2.8*s);}
  else if(dir==="left"){ctx.fillRect(-5.5*s,-7*s,2.4*s,2.8*s);}
  else if(dir==="right"){ctx.fillRect(3.1*s,-7*s,2.4*s,2.8*s);}
  ctx.fillStyle="rgba(255,255,255,.14)";ctx.fillRect(-8*s,-2*s,3*s,9*s);
  ctx.restore();
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
    if(MC.musicUrl&&!audioEl){audioEl=new Audio(MC.musicUrl);audioEl.loop=true;audioEl.volume=0.7;
      audioEl.addEventListener("error",()=>{useFile=false;audioEl=null;mstep=0;playStep();});}
    if(audioEl&&MC.musicUrl){useFile=true;audioEl.play().catch(()=>{useFile=false;mstep=0;playStep();});}
    else{try{ac().resume();}catch(e){}mstep=0;playStep();}
  }else{clearTimeout(musicTimer);if(audioEl)audioEl.pause();}
  blip(musicOn?880:440,0.08,0.05);
}
$("#musicBtn").addEventListener("click",e=>{e.stopPropagation();toggleMusic();toast(musicOn?"MUSIK: ON":"MUSIK: OFF");});
/* ---------- pilih karakter ---------- */
const CHARS=[
  {name:"STEVE", skin:"#c98d5f",hair:"#2b2b2b",shirt:"#00a8a8",pants:"#3b3bbf"},
  {name:"ALEX",  skin:"#f0c49c",hair:"#e07b39",shirt:"#7ab648",pants:"#5a5a5a"},
  {name:"SATRIA",skin:"#8a5a3a",hair:"#141414",shirt:"#a03a3a",pants:"#2b2b2b"}
];
let charIdx=0;
function renderChars(){
  const row=$("#charRow");row.innerHTML="";
  CHARS.forEach((c,i)=>{
    const d=document.createElement("div");d.className="char-opt"+(i===charIdx?" sel":"");
    const cv=document.createElement("canvas");cv.width=32;cv.height=44;
    drawPlayer(cv.getContext("2d"),16,28,c,"down",0,false,1.15);
    d.appendChild(cv);d.insertAdjacentHTML("beforeend",`<span>${c.name}</span>`);
    d.addEventListener("click",()=>{charIdx=i;blip(700,0.06,0.05);renderChars();});
    row.appendChild(d);});
}
renderChars();

/* ---------- dunia ---------- */
const TILE=32,WT=52,HT=39,WW=WT*TILE,WH=HT*TILE;
const cv=$("#cv"),ctx=cv.getContext("2d");
let VW=0,VH=0,camX=0,camY=0;
function sizeCanvas(){VW=cv.width=innerWidth;VH=cv.height=innerHeight;}
const LOCS=[
  {id:"couple", name:"RUMAH MEMPELAI",short:"MEMPELAI",icon:"heart",tx:6, ty:5, w:8,h:6,wall:"#c9a86a",roof:"#8a3b2e"},
  {id:"count",  name:"MENARA JAM",    short:"JAM",    icon:"clock",tx:38,ty:5, w:5,h:7,wall:"#9a9a9a",roof:"#3f6db3"},
  {id:"event",  name:"BALAI QUEST",   short:"QUEST",  icon:"book", tx:4, ty:17,w:8,h:6,wall:"#b08d5a",roof:"#5a7a3a"},
  {id:"gallery",name:"GALERI",        short:"GALERI", icon:"frame",tx:40,ty:17,w:8,h:6,wall:"#d8cfc0",roof:"#7a4a8a"},
  {id:"rsvp",   name:"POS RSVP",      short:"RSVP",   icon:"mail", tx:6, ty:28,w:7,h:5,wall:"#a8c8e8",roof:"#c23b3b"},
  {id:"gift",   name:"PETI HARTA",    short:"HARTA",  icon:"chest",tx:40,ty:28,w:7,h:5,wall:"#8a5f3c",roof:"#d9a821"}
];
const solids=[],torches=[];
function solidRect(tx,ty,w,h){solids.push({x:tx*TILE,y:ty*TILE,w:w*TILE,h:h*TILE});}
const PATHS=[
  {x:24,y:0,w:4,h:39},{x:0,y:18,w:52,h:3},
  {x:8,y:8,w:4,h:11},{x:38,y:8,w:4,h:11},
  {x:6,y:21,w:4,h:8},{x:42,y:21,w:4,h:8},
  {x:6,y:31,w:4,h:5},{x:40,y:31,w:4,h:5}
];
function inRect(px,py,r){return px>=r.x&&px<r.x+r.w&&py>=r.y&&py<r.y+r.h;}
const trees=[{tx:14,ty:4},{tx:34,ty:3},{tx:47,ty:10},{tx:2,ty:13},{tx:17,ty:15},{tx:33,ty:14},
  {tx:49,ty:22},{tx:15,ty:24},{tx:29,ty:25},{tx:3,ty:33},{tx:20,ty:33},{tx:33,ty:33},{tx:48,ty:35},
  {tx:15,ty:11},{tx:44,ty:13},{tx:25,ty:8}];
const flowers=[];for(let i=0;i<70;i++)flowers.push({tx:Math.floor(Math.random()*WT),ty:Math.floor(Math.random()*HT),
  c:["#ff5b5b","#fcee5e","#ffffff","#ff9ff3"][i%4]});
const pond={x:26*TILE,y:4*TILE,rx:5*TILE,ry:3*TILE};
let groundCv=null;
function shade(hex,amt){const n=parseInt(hex.slice(1),16);
  const r=Math.max(0,Math.min(255,((n>>16)&255)+amt)),g=Math.max(0,Math.min(255,((n>>8)&255)+amt)),
        b=Math.max(0,Math.min(255,(n&255)+amt));return `rgb(${r},${g},${b})`;}
function buildGround(){
  groundCv=document.createElement("canvas");groundCv.width=WW;groundCv.height=WH;
  const g=groundCv.getContext("2d");
  g.fillStyle="#5da832";g.fillRect(0,0,WW,WH);
  for(let i=0;i<9000;i++){g.fillStyle=Math.random()<0.5?"#549a2c":"#67b83a";
    g.fillRect(Math.random()*WW,Math.random()*WH,3,3);}
  PATHS.forEach(p=>{g.fillStyle="#8a5f3c";g.fillRect(p.x*TILE,p.y*TILE,p.w*TILE,p.h*TILE);
    for(let i=0;i<p.w*p.h*3;i++){g.fillStyle=Math.random()<0.5?"#7d5327":"#96703f";
      g.fillRect((p.x+Math.random()*p.w)*TILE,(p.y+Math.random()*p.h)*TILE,4,4);}});
  g.fillStyle="#7d7d7d";g.beginPath();g.ellipse(26*TILE,35*TILE,4*TILE,3*TILE,0,0,6.29);g.fill();
  g.fillStyle="#6a6a6a";for(let i=0;i<120;i++){const a=Math.random()*6.28,r=Math.random()*3.4*TILE;
    g.fillRect(26*TILE+Math.cos(a)*r*1.3,35*TILE+Math.sin(a)*r,4,4);}
  g.fillStyle="#c2b280";g.beginPath();g.ellipse(pond.x,pond.y,pond.rx+8,pond.ry+8,0,0,6.29);g.fill();
  g.fillStyle="#3f8fd1";g.beginPath();g.ellipse(pond.x,pond.y,pond.rx,pond.ry,0,0,6.29);g.fill();
  g.fillStyle="#5aa5e0";g.beginPath();g.ellipse(pond.x-pond.rx*0.25,pond.y-pond.ry*0.25,pond.rx*0.55,pond.ry*0.5,0,0,6.29);g.fill();
  flowers.forEach(f=>{const x=f.tx*TILE+8,y=f.ty*TILE+20;
    if(PATHS.some(p=>inRect(x,y,p)))return;
    g.fillStyle="#3f8f23";g.fillRect(x,y,2,8);g.fillStyle=f.c;g.fillRect(x-3,y-4,8,6);});
  trees.forEach(t=>{const X=t.tx*TILE,Y=t.ty*TILE;
    g.fillStyle="rgba(0,0,0,.25)";g.beginPath();g.ellipse(X+16,Y+52,20,7,0,0,6.29);g.fill();
    g.fillStyle="#6e4a2e";g.fillRect(X+12,Y+20,8,32);
    g.fillStyle="#3f8f23";g.fillRect(X-8,Y-12,48,36);
    g.fillStyle="#4e9a26";g.fillRect(X-2,Y-6,36,24);g.fillRect(X-8,Y+2,48,14);
    g.fillStyle="#67b83a";for(let i=0;i<14;i++)g.fillRect(X-8+Math.random()*44,Y-12+Math.random()*30,4,4);
    solidRect(t.tx+0.25,t.ty+0.6,0.5,1);});
  LOCS.forEach(L=>{
    const X=L.tx*TILE,Y=L.ty*TILE,Wd=L.w*TILE,Ht=L.h*TILE;
    g.fillStyle="rgba(0,0,0,.3)";g.fillRect(X+6,Y+Ht-4,Wd,Ht);
    g.fillStyle=L.wall;g.fillRect(X,Y,Wd,Ht);
    g.fillStyle="rgba(0,0,0,.14)";for(let i=0;i<L.w;i++)g.fillRect(X+i*TILE,Y,2,Ht);
    g.fillStyle="rgba(255,255,255,.2)";g.fillRect(X,Y,Wd,4);
    for(let r=0;r<3;r++){g.fillStyle=r%2?L.roof:shade(L.roof,-18);
      g.fillRect(X-TILE+r*10,Y-(r+1)*16,Wd+2*TILE-r*20,16);}
    g.fillStyle="#fcee5e";g.fillRect(X+TILE,Y+2*TILE,TILE,TILE);
    g.fillRect(X+Wd-2*TILE,Y+2*TILE,TILE,TILE);
    g.fillStyle="#141414";g.fillRect(X+TILE,Y+2*TILE+TILE/2-2,TILE,4);
    g.fillRect(X+Wd-2*TILE,Y+2*TILE+TILE/2-2,TILE,4);
    const dx=X+Wd/2-TILE/2;
    g.fillStyle="#3a2410";g.fillRect(dx,Y+Ht-2*TILE,TILE,2*TILE);
    g.fillStyle="#f7c948";g.fillRect(dx+TILE-8,Y+Ht-TILE-16,5,5);
    const sy=Y-64;
    g.fillStyle="#6e4a2e";g.fillRect(X+Wd/2-3,sy+30,6,34);
    g.fillStyle="#8a5f3c";g.fillRect(X+Wd/2-52,sy,104,34);
    g.strokeStyle="#3a2410";g.lineWidth=3;g.strokeRect(X+Wd/2-52,sy,104,34);
    const imap=ICONS[L.icon],iw=imap[0].length;
    const isc=document.createElement("canvas");isc.width=iw;isc.height=imap.length;
    drawSprite(isc,imap,PAL);
    g.drawImage(isc,X+Wd/2-44,sy+5,24,24*imap.length/iw);
    g.fillStyle="#fff";g.font="8px 'Press Start 2P',monospace";g.textAlign="left";
    g.fillText(L.short,X+Wd/2-14,sy+21);
    L.doorX=X+Wd/2;L.doorY=Y+Ht+18;
    solidRect(L.tx,L.ty,L.w,L.h);
    torches.push({x:X+8,y:Y+Ht-8},{x:X+Wd-8,y:Y+Ht-8});
  });
  [[22,32],[30,32],[22,38],[30,38]].forEach(pt=>torches.push({x:pt[0]*TILE,y:pt[1]*TILE}));
}

/* ---------- pemain & kontrol ---------- */
const player={x:26*TILE,y:35*TILE,dir:"up",moving:false,frame:0,animT:0,pal:CHARS[0],speed:165,r:11};
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
    if(d<62&&d<best){best=d;nearLoc=L;}}
  const hint=$("#hint"),act=$("#actBtn");
  if(nearLoc&&!panelOpen){
    hint.hidden=false;
    hint.innerHTML=`&#9673; ${nearLoc.name} &mdash; tekan <b>E</b> / ketuk AKSI${visited.has(nearLoc.id)?" &#10003;":""}`;
    act.classList.add("ready");
  }else{hint.hidden=true;act.classList.remove("ready");}
}
function tryInteract(){if(!nearLoc)return;blip(880,0.08,0.05);openPanel(nearLoc.id);}
/* ---------- panel ---------- */
const PIP={couple:"\u2764",count:"\u23F1",event:"\uD83D\uDCD3",gallery:"\uD83D\uDDBC",rsvp:"\u2709",gift:"\uD83C\uDF81"};
function renderPips(){
  const box=$("#pips");box.innerHTML="";
  LOCS.forEach(L=>{const d=document.createElement("div");
    d.className="pip"+(visited.has(L.id)?" on":"");d.textContent=PIP[L.id];d.title=L.name;box.appendChild(d);});
  $("#progText").textContent=visited.size+"/6";
}
function markVisited(id){
  if(visited.has(id))return;visited.add(id);renderPips();levelUp();
  heartFx(player.x-camX,player.y-camY-30,10);
  const L=LOCS.find(l=>l.id===id);
  toast(`LOKASI DITEMUKAN: ${L.name} (${visited.size}/6)`);
}
const TPL={
couple:()=>`<h2 class="ptitle">RUMAH MEMPELAI</h2><p class="psub">Dua pemain. Satu tim. Selamanya.</p>
<div class="player-grid">
<div class="pcard"><canvas id="pfB" width="8" height="8"></canvas>
<h3>{{ $brideFull }}</h3><span class="ptag">MEMPELAI WANITA</span><p>{{ mcaGet('bride_parents', mcaGet('bride_child_of','')) }}</p></div>
<div class="pcard"><canvas id="pfG" width="8" height="8"></canvas>
<h3>{{ $groomFull }}</h3><span class="ptag">MEMPELAI PRIA</span><p>{{ mcaGet('groom_parents', mcaGet('groom_child_of','')) }}</p></div>
</div>`,
count:()=>`<h2 class="ptitle">MENARA JAM</h2><p class="psub">${MC.dateText} &mdash; quest dimulai dalam:</p>
<div class="hud2">
<div class="slot"><span class="num" id="cD">00</span><span class="unit">HARI</span></div>
<div class="slot"><span class="num" id="cH">00</span><span class="unit">JAM</span></div>
<div class="slot"><span class="num" id="cM">00</span><span class="unit">MENIT</span></div>
<div class="slot"><span class="num" id="cS">00</span><span class="unit">DETIK</span></div>
</div>`,
event:()=>`<h2 class="ptitle">BALAI QUEST</h2><p class="psub">Dua misi utama menantimu.</p>
<div class="adv"><div class="ic"><canvas class="ev-ic" data-ic="ring" width="16" height="16"></canvas></div>
<div><h3>AKAD NIKAH</h3><p>${MC.akad.date}</p><p class="tm">${MC.akad.time}</p><p>${esc(MC.akad.venue)}</p>
${MC.akad.maps?`<a class="mc-btn" style="font-size:10px;margin-top:10px;display:inline-block;text-decoration:none" href="${esc(MC.akad.maps)}" target="_blank" rel="noopener">&#9673; BUKA PETA</a>`:""}</div></div>
<div class="adv"><div class="ic"><canvas class="ev-ic" data-ic="heart" width="16" height="16"></canvas></div>
<div><h3>RESEPSI</h3><p>${MC.resepsi.date}</p><p class="tm">${MC.resepsi.time}</p><p>${esc(MC.resepsi.venue)}</p>
${MC.resepsi.maps?`<a class="mc-btn" style="font-size:10px;margin-top:10px;display:inline-block;text-decoration:none" href="${esc(MC.resepsi.maps)}" target="_blank" rel="noopener">&#9673; BUKA PETA</a>`:""}</div></div>`,
gallery:()=>`<h2 class="ptitle">GALERI</h2><p class="psub">Balok-balok kenangan.</p>
<div class="frames2">
@foreach(array_slice($gallery, 0, 6) as $i => $img)
<div class="frame2"><img src="{{ $img }}" alt="Galeri {{ $i+1 }}" loading="lazy"><p>Kenangan {{ $i+1 }}</p></div>
@endforeach
</div>
@if(empty($gallery))<p class="psub">Belum ada foto galeri.</p>@endif`,
rsvp:()=>`<h2 class="ptitle">POS RSVP</h2><p class="psub">Apakah kamu bergabung dalam party?</p>
<form id="rf2">
<label class="lbl">NAMA PEMAIN</label><input class="mc-input" id="rn2" required maxlength="40" placeholder="Tulis namamu...">
<label class="lbl">STATUS</label><select class="mc-input" id="rh2"><option value="hadir">Hadir, siap!</option><option value="tidak">Tidak bisa ikut</option></select>
<label class="lbl">JUMLAH</label><select class="mc-input" id="rj2"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select>
<div style="margin-top:14px"><button class="mc-btn green" type="submit" style="font-size:11px">KIRIM RSVP</button></div>
</form>
<div class="scoreboard"><h4>&#9733; DAFTAR PETUALANG</h4><div id="rl2"><div class="srow"><span style="color:#777">Memuat...</span></div></div></div>
<div class="scoreboard"><h4>&#9733; PAPAN UCAPAN</h4>
<form id="uf2" style="padding:12px 14px">
<input class="mc-input" id="un2" required maxlength="40" placeholder="Namamu..." style="margin-bottom:10px">
<input class="mc-input" id="up2" required maxlength="160" placeholder="Tulis ucapan...">
<div style="margin-top:10px"><button class="mc-btn gold" type="submit" style="font-size:10px">KIRIM UCAPAN</button></div>
</form>
<div id="ul2"></div></div>`,
gift:()=>`<h2 class="ptitle">PETI HARTA</h2><p class="psub">Klik petinya untuk membuka.</p>
<div class="chest-zone"><div class="chest" id="ch2"><div class="glow"></div><div class="lid"></div><div class="base"></div></div>
<div class="px" style="font-family:'Press Start 2P',monospace;font-size:9px;color:#5a5a5a" id="chL2">PETI TERKUNCI</div></div>
<div class="gift-grid" id="ga2" hidden>
@foreach($accounts as $i => $a)
<div class="acct"><div class="bank">{{ $a['bank'] }}</div>
    @if(!empty($a['logo']))<img src="{{ $a['logo'] }}" alt="Logo bank" style="height:36px;max-width:150px;object-fit:contain;margin:10px auto 0;display:block">@endif<div class="no" id="gno{{ $i }}">{{ $a['no'] }}</div>
<div class="an">{{ $a['an'] }}</div>
<button class="mc-btn gold" style="font-size:10px;padding:10px 14px" data-g="{{ $i }}">SALIN</button></div>
@endforeach
</div>
@if(empty($accounts))<p class="psub">Belum ada info hadiah.</p>@endif`
};
function openPanel(id){
  markVisited(id);
  panelOpen=true;gameOn=false;
  $("#panelBody").innerHTML=TPL[id]();
  $("#panelOverlay").hidden=false;
  if(id==="couple"){
    drawFace($("#pfB"),{skin:"#f0c49c",hair:"#5a3a22",shirt:"#c2437f",style:"bride"});
    drawFace($("#pfG"),{skin:"#e8b98a",hair:"#2b2b2b",shirt:"#2f6db3",style:"groom"});
  }
  if(id==="count")startCd();
  if(id==="event")$$("#panelBody .ev-ic").forEach(c=>{
    const t=document.createElement("canvas");t.width=16;t.height=16;
    drawSprite(t,ICONS[c.dataset.ic],PAL);c.getContext("2d").drawImage(t,0,0,16,16);});
  if(id==="rsvp"){wireRsvp();wireUcapan();}
  if(id==="gift")wireChest();
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
       <span style="font-family:'Press Start 2P',monospace;font-size:8px;color:${x.status==="hadir"?"#7bff5e":"#ff7b7b"}">${x.status.toUpperCase()}</span></div>`
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
  const n=(Date.now()-d.getTime())/1000;if(n<60)return "baru saja";
  if(n<3600)return Math.floor(n/60)+" mnt lalu";if(n<86400)return Math.floor(n/3600)+" jam lalu";
  return Math.floor(n/86400)+" hari lalu";}
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
/* peti */
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
<h2>&#9733; QUEST COMPLETE! &#9733;</h2>
<p>Terima kasih telah menyelesaikan petualangan ini! Kehadiran dan doa restumu adalah hadiah terbaik bagi kami.</p>
<p>Kami yang berbahagia,</p>
<p style="font-family:'Press Start 2P',monospace;font-size:13px;color:#2b6b1c">${esc(MC.brideNick)} &amp; ${esc(MC.groomNick)}</p>
<p style="font-family:'Press Start 2P',monospace;font-size:9px;color:#777;margin-top:14px">${esc(MC.dateText)}</p></div>`;
  $("#panelOverlay").hidden=false;
  levelUp();setTimeout(levelUp,400);
  for(let i=0;i<5;i++)setTimeout(()=>heartFx(Math.random()*VW,VH*0.3,8),i*220);
}
/* ---------- partikel, render, loop ---------- */
const parts=[];
function heartFx(x,y,n){n=n||8;
  for(let i=0;i<n;i++)parts.push({x:x+(Math.random()-0.5)*40,y,vx:(Math.random()-0.5)*50,
    vy:-(50+Math.random()*70),age:0,life:1.4+Math.random(),s:7+Math.random()*7});}
let tG=0;
function render(dt){
  tG+=dt;
  ctx.fillStyle="#0d1420";ctx.fillRect(0,0,VW,VH);
  if(groundCv)ctx.drawImage(groundCv,Math.round(-camX),Math.round(-camY));
  torches.forEach((t,i)=>{
    const x=t.x-camX,y=t.y-camY;
    if(x<-40||x>VW+40||y<-60||y>VH+40)return;
    ctx.fillStyle="#5a3a22";ctx.fillRect(x-2,y-26,4,26);
    const fl=3+Math.sin(tG*11+i*2.3)*1.2;
    ctx.fillStyle="rgba(255,150,30,.25)";ctx.beginPath();ctx.arc(x,y-32,14+fl,0,6.29);ctx.fill();
    ctx.fillStyle="#ff961e";ctx.fillRect(x-4,y-38,8,10+fl);
    ctx.fillStyle="#fcee5e";ctx.fillRect(x-2,y-36,4,6+fl);});
  LOCS.forEach(L=>{
    if(visited.has(L.id))return;
    const x=L.doorX-camX,y=L.doorY-camY-64+Math.sin(tG*3)*4;
    if(x<-30||x>VW+30||y<-30||y>VH+30)return;
    ctx.fillStyle="#fff";ctx.fillRect(x-11,y-22,22,22);
    ctx.fillStyle="#141414";ctx.fillRect(x-11,y-22,22,3);ctx.fillRect(x-11,y-3,22,3);
    ctx.fillStyle="#c22f2f";ctx.font="bold 15px monospace";ctx.textAlign="center";ctx.fillText("!",x,y-6);});
  const px=player.x-camX,py=player.y-camY;
  drawPlayer(ctx,px,py,player.pal,player.dir,player.frame,player.moving,1.3);
  ctx.font="8px 'Press Start 2P',monospace";ctx.textAlign="center";
  ctx.fillStyle="rgba(0,0,0,.55)";ctx.fillRect(px-34,py-38,68,13);
  ctx.fillStyle="#fff";ctx.fillText("PETUALANG",px,py-28);
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
  camX=Math.max(0,Math.min(WW-VW,player.x-VW/2));
  camY=Math.max(0,Math.min(WH-VH,player.y-VH/2));
  if("ontouchstart"in window||navigator.maxTouchPoints>0){
    $("#dpad").classList.add("show");$("#actBtn").classList.add("show");}
  started=true;gameOn=true;levelUp();
  toast("Selamat berpetualang! Kunjungi 6 lokasi!");
});
$("#helpBtn").addEventListener("click",e=>{e.stopPropagation();
  toast("WASD/Panah jalan — E/Spasi masuk lokasi");blip(600,0.06,0.04);});
addEventListener("resize",()=>{if(started)sizeCanvas();});
addEventListener("contextmenu",e=>{if(e.target.closest&&e.target.closest("#game"))e.preventDefault();});
addEventListener("touchmove",e=>{if(started&&!(e.target.closest&&e.target.closest("#panelOverlay")))e.preventDefault();},{passive:false});
requestAnimationFrame(loop);
</script>
</body>
</html>
