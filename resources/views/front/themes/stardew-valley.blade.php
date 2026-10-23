<?php
/* ============================================================
   TEMA PREMIUM: STARDEW VALLEY (Pixel Farm Game)
   Dipilih via settings: theme = stardew-valley
   Pixel-art farm: rumah tani, ladang bergoyang, ayam, Junimo,
   panen interaktif. Full fitur bawaan (musik API, lightbox, menu,
   RSVP/ucapan, countdown resepsi, gift, QR check-in opsional).
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
  function mcTime($t) {
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
$countdownISO = $rDate.'T'.$rTimeS.':00';
$tz = mcGet('reception_timezone', mcGet('wedding_timezone', 'Asia/Jakarta'));
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
$qrisEnabled = mcGet('gift_qris_enabled', '') === '1' && trim((string)mcGet('gift_qris_image', '')) !== '';
$qrisImage = mcGet('gift_qris_image', '');
$qrisName = mcGet('gift_qris_name', '');
$musicUrl = null;
if (!empty($musicRecord)) { $musicUrl = $musicRecord->file_path ?? $musicRecord->url ?? null; }
if (!$musicUrl) { $musicUrl = mcGet('music_url', ''); }
$guestName = $guestName ?? 'Bapak/Ibu/Saudara/i';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $groomNick }} &amp; {{ $brideNick }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
:root{
  --sky1:#6ec3f5; --sky2:#dff3ff;
  --grass:#79c25f; --grass-d:#5da244; --grass-dd:#4a8a38;
  --soil:#8a5a33; --soil-d:#6e4426;
  --wood:#8b5a2b; --wood-d:#5d3a1a; --wood-l:#a9743d;
  --cream:#fff8e7; --paper:#fdf6e3;
  --ink:#3a2a18; --ink-soft:#6b543a;
  --gold:#e8b93c; --gold-d:#b8892a;
  --pink:#ffb7c5; --red:#d94f3d;
}
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{font-family:'Nunito',sans-serif;background:var(--sky2);color:var(--ink);overflow-x:hidden}
body.locked{overflow:hidden}
.px{font-family:'Press Start 2P',monospace}
img{image-rendering:pixelated}
.wrap{max-width:480px;margin:0 auto;position:relative;overflow:hidden;background:linear-gradient(180deg,var(--sky1) 0%,var(--sky2) 62%,#bfe8c9 62.5%,#bfe8c9 100%)}
section{position:relative;padding:56px 20px}
/* ---------- reveal ---------- */
.rv{opacity:0;transform:translateY(26px);transition:opacity .7s ease,transform .7s ease}
.rv.on{opacity:1;transform:none}
/* ---------- progress & menu ---------- */
.pbar{position:fixed;top:0;left:0;height:4px;width:0;background:linear-gradient(90deg,var(--gold),#ffdf80);z-index:120;box-shadow:0 0 8px #e8b93c}
body.locked .pbar,body.locked #fmenu,body.locked #musBtn{display:none!important}
#musBtn{position:fixed;top:14px;right:14px;z-index:110;width:46px;height:46px;border-radius:12px;border:3px solid var(--wood-d);background:var(--wood);color:#fff;font-size:20px;cursor:pointer;box-shadow:0 4px 0 var(--wood-d)}
#musBtn:active{transform:translateY(3px);box-shadow:none}
#musBtn.off{opacity:.55}
#fmenu{position:fixed;bottom:12px;left:50%;transform:translateX(-50%);z-index:110;display:flex;gap:2px;background:#2b1d0ef2;border:3px solid #120b04;border-radius:14px;padding:6px 8px;max-width:96vw;overflow-x:auto;scrollbar-width:none;box-shadow:0 6px 18px rgba(0,0,0,.4)}
#fmenu::-webkit-scrollbar{display:none}
#fmenu a{display:flex;flex-direction:column;align-items:center;gap:4px;min-width:52px;padding:7px 6px;border-radius:8px;color:#f5ead0;opacity:.55;text-decoration:none;font-size:8px;font-family:'Press Start 2P',monospace;transition:.2s}
#fmenu a.on{opacity:1;background:#4a3420;box-shadow:inset 0 0 0 2px var(--gold)}
#fmenu a .ic{font-size:16px;font-family:'Nunito',sans-serif}
/* ---------- cover ---------- */
#cover{position:fixed;inset:0;z-index:200;overflow:hidden;transition:transform .9s ease,opacity .9s ease}
#cover.open{transform:translateY(-100%);opacity:.4;pointer-events:none}
.farmsky{position:absolute;inset:0}
.pxc{shape-rendering:crispEdges}
.cloud{position:absolute;animation:drift linear infinite;opacity:.95}
@keyframes drift{from{transform:translateX(-30vw)}to{transform:translateX(115vw)}}
.sunrays{transform-origin:center;transform-box:fill-box;animation:spin 24s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
.swayer{transform-origin:bottom center;transform-box:fill-box;animation:sway 3.2s ease-in-out infinite}
@keyframes sway{0%,100%{transform:rotate(-4deg)}50%{transform:rotate(4deg)}}
.bob{animation:bob 2.6s ease-in-out infinite}
@keyframes bob{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
.smoke{animation:smokeUp 3s ease-out infinite}
@keyframes smokeUp{0%{transform:translateY(0) scale(1);opacity:.7}100%{transform:translateY(-46px) scale(1.8);opacity:0}}
.petal{position:absolute;top:-20px;animation:fall linear infinite;pointer-events:none}
@keyframes fall{to{transform:translateY(110vh) rotate(340deg)}}
.sign{position:absolute;left:50%;transform:translateX(-50%);background:var(--wood);border:4px solid var(--wood-d);border-radius:6px;box-shadow:0 6px 0 rgba(0,0,0,.25);text-align:center;color:#ffe9b8}
.sign .rope{position:absolute;top:-34px;left:50%;transform:translateX(-50%);width:4px;height:34px;background:var(--wood-d)}
.sign .rope:before,.sign .rope:after{content:'';position:absolute;top:0;width:4px;height:34px;background:var(--wood-d)}
.sign .rope:before{left:-46px;transform:rotate(14deg);transform-origin:top}
.sign .rope:after{right:-46px;transform:rotate(-14deg);transform-origin:top}
.btn-wood{display:inline-block;font-family:'Press Start 2P',monospace;font-size:13px;color:#fff;background:linear-gradient(180deg,var(--wood-l),var(--wood));border:4px solid var(--wood-d);border-radius:10px;padding:16px 26px;cursor:pointer;box-shadow:0 6px 0 var(--wood-d),0 10px 20px rgba(0,0,0,.3);text-decoration:none;transition:transform .12s}
.btn-wood:active{transform:translateY(4px);box-shadow:0 2px 0 var(--wood-d)}
.plaque{background:var(--paper);border:3px solid var(--wood-d);border-radius:8px;box-shadow:0 4px 0 rgba(0,0,0,.2)}
/* ---------- section decor ---------- */
.sec-head{text-align:center;margin-bottom:30px}
.sec-kicker{font-family:'Press Start 2P',monospace;font-size:9px;letter-spacing:.2em;color:var(--ink-soft);margin-bottom:12px}
.sec-title{font-family:'Press Start 2P',monospace;font-size:20px;color:var(--ink);text-shadow:2px 2px 0 rgba(232,185,60,.55)}
.fence{height:26px;margin:18px auto 0;max-width:340px;background:
  repeating-linear-gradient(90deg,var(--wood) 0 10px,transparent 10px 26px),
  linear-gradient(var(--wood-l),var(--wood-l));
  background-size:auto,100% 8px;background-position:0 0,0 4px;background-repeat:repeat-x,no-repeat;
  border-radius:3px;position:relative}
.fence:before{content:'';position:absolute;inset:14px 0 0;background:linear-gradient(var(--wood-l),var(--wood-l));background-size:100% 6px;background-repeat:no-repeat}
/* ---------- cards ---------- */
.npc{background:var(--paper);border:4px solid var(--wood-d);border-radius:10px;box-shadow:0 8px 0 rgba(93,58,26,.25);overflow:hidden}
.npc .pframe{border-bottom:4px solid var(--wood-d);background:linear-gradient(180deg,#aee3f5,#dff3ff);position:relative}
.npc .pframe img{width:100%;height:300px;object-fit:cover;object-position:top;display:block;filter:saturate(1.05) contrast(1.03)}
.npc .pbody{padding:22px 18px;text-align:center}
.npc h3{font-family:'Press Start 2P',monospace;font-size:16px;margin-bottom:8px}
.npc .role{font-size:11px;letter-spacing:.2em;text-transform:uppercase;color:var(--ink-soft);margin-bottom:10px}
.npc p{font-size:14px;color:var(--ink-soft);line-height:1.7}
/* galeri */
.g-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}
.g-frame{background:var(--wood);border:4px solid var(--wood-d);border-radius:8px;padding:8px;box-shadow:0 6px 0 rgba(93,58,26,.3);cursor:zoom-in;transition:transform .2s}
.g-frame:hover{transform:translateY(-4px) rotate(-1deg)}
.g-frame img{width:100%;height:170px;object-fit:cover;display:block;border-radius:3px;background:#cfe8d8}
/* kisah journal */
.journal{background:var(--paper);border:4px solid var(--wood-d);border-radius:10px;box-shadow:0 8px 0 rgba(93,58,26,.25);padding:6px}
.jpage{border:2px dashed rgba(139,90,43,.4);border-radius:6px;padding:20px 16px;margin:10px}
.jpage .yr{display:inline-block;font-family:'Press Start 2P',monospace;font-size:10px;background:var(--gold);color:#3a2a18;padding:7px 10px;border-radius:6px;border:2px solid var(--gold-d);margin-bottom:10px}
.jpage h4{font-family:'Press Start 2P',monospace;font-size:12px;margin-bottom:8px;line-height:1.6}
.jpage p{font-size:14px;line-height:1.8;color:var(--ink-soft)}
/* acara signpost */
.post{background:linear-gradient(180deg,var(--wood-l),var(--wood));border:4px solid var(--wood-d);border-radius:10px;color:#ffe9b8;box-shadow:0 8px 0 rgba(93,58,26,.3);overflow:hidden}
.post .phead{background:rgba(0,0,0,.18);padding:16px;text-align:center;border-bottom:3px solid var(--wood-d)}
.post .phead h3{font-family:'Press Start 2P',monospace;font-size:14px}
.post .pbody{padding:22px 18px;text-align:center}
.post .pdate{font-family:'Press Start 2P',monospace;font-size:11px;line-height:2;color:#ffe9b8}
.post .ptime{font-size:22px;font-weight:800;margin:10px 0}
.post .pplace{font-size:14px;opacity:.9;line-height:1.7}
.btn-gold{display:inline-block;font-family:'Press Start 2P',monospace;font-size:10px;color:#3a2a18;background:linear-gradient(180deg,#ffe27a,var(--gold));border:3px solid var(--gold-d);border-radius:8px;padding:12px 18px;margin:6px 4px 0;text-decoration:none;box-shadow:0 4px 0 var(--gold-d);transition:transform .12s}
.btn-gold:active{transform:translateY(3px);box-shadow:none}
/* forms */
.field{margin-bottom:14px;text-align:left}
.field label{display:block;font-family:'Press Start 2P',monospace;font-size:9px;letter-spacing:.1em;margin-bottom:8px;color:var(--ink-soft)}
.field input,.field textarea,.field select{width:100%;font-family:'Nunito',sans-serif;font-size:15px;font-weight:600;color:var(--ink);background:#fff;border:3px solid var(--wood-d);border-radius:8px;padding:13px 14px;outline:none}
.field input:focus,.field textarea:focus{border-color:var(--gold-d);box-shadow:0 0 0 3px rgba(232,185,60,.35)}
.att-row{display:flex;gap:10px}
.att{flex:1;font-family:'Press Start 2P',monospace;font-size:10px;padding:14px 6px;border-radius:8px;border:3px solid var(--wood-d);background:#fff;color:var(--ink-soft);cursor:pointer;text-align:center}
.att.sel-hadir{background:#d9f2c7;border-color:#4a8a38;color:#2c5c22}
.att.sel-tidak{background:#fbd9d2;border-color:#b3402e;color:#8c2f22}
.formbox{background:var(--paper);border:4px solid var(--wood-d);border-radius:12px;box-shadow:0 8px 0 rgba(93,58,26,.25);padding:24px 18px}
/* wishes */
.wish{background:#fff;border:3px solid rgba(139,90,43,.35);border-radius:10px;padding:14px 16px;margin:0 auto 12px;max-width:400px;box-shadow:3px 3px 0 rgba(139,90,43,.15)}
.wish-head{display:flex;justify-content:space-between;align-items:baseline;gap:10px;margin-bottom:6px}
.wish-head b{font-size:14px}
.wtime{font-size:11px;opacity:.55;white-space:nowrap}
.wish p{font-size:14px;line-height:1.7;color:var(--ink-soft)}
.wish-empty{text-align:center;opacity:.6;font-size:14px;padding:14px}
/* gift */
.bank{background:linear-gradient(135deg,#2f4a2e,#1e3320);border:4px solid #101d11;border-radius:12px;color:#ffe9b8;padding:20px 18px;margin:0 auto 14px;max-width:380px;box-shadow:0 8px 0 rgba(0,0,0,.25)}
.bank .bname{font-family:'Press Start 2P',monospace;font-size:11px;color:var(--gold);margin-bottom:10px}
.bank .bno{font-size:22px;font-weight:800;letter-spacing:.06em;margin-bottom:6px}
.bank .ban{font-size:13px;opacity:.8}
.bank img{height:34px;max-width:150px;object-fit:contain;margin:10px auto 0;display:block;background:#fff;border-radius:6px;padding:4px}
.copybtn{font-family:'Press Start 2P',monospace;font-size:9px;background:var(--gold);color:#3a2a18;border:3px solid var(--gold-d);border-radius:8px;padding:10px 16px;margin-top:12px;cursor:pointer;box-shadow:0 4px 0 var(--gold-d)}
.copybtn:active{transform:translateY(3px);box-shadow:none}
/* lightbox */
.lb{position:fixed;inset:0;z-index:200;background:rgba(10,8,4,.94);display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:.35s;padding:20px;touch-action:pan-y}
.lb.show{opacity:1;visibility:visible}
#lbImg{max-width:100%;max-height:86vh;border-radius:8px;will-change:transform,opacity;border:4px solid var(--wood)}
.lb-x{position:absolute;top:14px;right:20px;font-size:42px;color:#fff;cursor:pointer;line-height:1;z-index:201;font-family:'Nunito',sans-serif}
.lb-prev,.lb-next{position:absolute;top:50%;transform:translateY(-50%);font-size:32px;color:#fff;cursor:pointer;padding:16px 12px;z-index:201;user-select:none;opacity:.85;line-height:1;font-family:'Nunito',sans-serif}
.lb-prev{left:6px}.lb-next{right:6px}
.lb-count{position:absolute;bottom:16px;left:50%;transform:translateX(-50%);color:#fff;font-size:13px;letter-spacing:.15em;z-index:201;font-family:'Press Start 2P',monospace}
/* countdown */
.cd{display:flex;gap:10px;justify-content:center;margin-top:22px}
.cd .box{background:#2b1d0e;border:3px solid #120b04;border-radius:10px;color:#ffe9b8;min-width:68px;padding:12px 6px;box-shadow:0 5px 0 rgba(0,0,0,.3)}
.cd .num{font-family:'Press Start 2P',monospace;font-size:18px;color:var(--gold)}
.cd .lbl{font-size:9px;letter-spacing:.15em;text-transform:uppercase;opacity:.7;margin-top:6px}
/* hud panen */
#hud{position:fixed;top:14px;left:14px;z-index:110;background:#2b1d0ef2;border:3px solid #120b04;border-radius:12px;color:#ffe9b8;font-family:'Press Start 2P',monospace;font-size:10px;padding:10px 12px;display:flex;align-items:center;gap:8px}
body.locked #hud{display:none!important}
#hud .star{color:var(--gold);font-size:14px}
.spark{position:absolute;font-size:18px;pointer-events:none;animation:sparkUp 1s ease-out forwards;z-index:50}
@keyframes sparkUp{0%{transform:translateY(0) scale(1);opacity:1}100%{transform:translateY(-60px) scale(1.6);opacity:0}}
.crop{cursor:pointer;transition:transform .15s}
.crop:hover{transform:scale(1.15)}
.crop.picked{opacity:.25;pointer-events:none}
#chicken{cursor:pointer}
#cluck{position:absolute;background:#fff;border:3px solid var(--wood-d);border-radius:10px;padding:8px 12px;font-family:'Press Start 2P',monospace;font-size:10px;opacity:0;transition:.3s;pointer-events:none;white-space:nowrap}
#cluck.show{opacity:1}
footer{padding:50px 20px 110px;text-align:center}
.qr-card{background:var(--paper);border:4px solid var(--wood-d);border-radius:12px;box-shadow:0 8px 0 rgba(93,58,26,.25);padding:26px 18px;max-width:360px;margin:0 auto;text-align:center}
@media(max-width:380px){.sec-title{font-size:17px}}
</style>
</head>
<body class="locked">
<div class="pbar" id="pbar"></div>
<button id="musBtn" aria-label="Musik">♪</button>
<div id="hud"><span class="star">★</span><span>Panen: <span id="panenCount">0</span></span></div>
<div class="wrap">
<!-- ================= COVER ================= -->
<div id="cover">
  <svg class="farmsky pxc" viewBox="0 0 480 800" preserveAspectRatio="xMidYMid slice" style="width:100%;height:100%;display:block">
    <defs>
      <linearGradient id="skyG" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0" stop-color="#6ec3f5"/><stop offset=".62" stop-color="#dff3ff"/><stop offset=".625" stop-color="#bfe8c9"/><stop offset="1" stop-color="#a5dcae"/>
      </linearGradient>
      <linearGradient id="soilG" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0" stop-color="#8a5a33"/><stop offset="1" stop-color="#6e4426"/>
      </linearGradient>
    </defs>
    <rect width="480" height="800" fill="url(#skyG)"/>
    <!-- matahari pixel -->
    <g transform="translate(392,86)">
      <g class="sunrays" fill="#ffd93b">
        <rect x="-8" y="-44" width="16" height="16"/><rect x="-8" y="28" width="16" height="16"/>
        <rect x="-44" y="-8" width="16" height="16"/><rect x="28" y="-8" width="16" height="16"/>
        <rect x="-34" y="-34" width="14" height="14"/><rect x="20" y="-34" width="14" height="14"/>
        <rect x="-34" y="20" width="14" height="14"/><rect x="20" y="20" width="14" height="14"/>
      </g>
      <rect x="-26" y="-26" width="52" height="52" fill="#ffdf5e"/>
      <rect x="-18" y="-18" width="36" height="36" fill="#fff3b0"/>
    </g>
    <!-- awan pixel -->
    <g class="cloud" style="animation-duration:75s" transform="translate(0,110)">
      <rect x="0" y="10" width="120" height="26" fill="#ffffff"/><rect x="22" y="0" width="76" height="36" fill="#ffffff"/><rect x="96" y="12" width="40" height="22" fill="#f2f9ff"/>
    </g>
    <g class="cloud" style="animation-duration:105s;animation-delay:-40s" transform="translate(0,190)">
      <rect x="0" y="8" width="90" height="20" fill="#ffffff"/><rect x="18" y="0" width="54" height="28" fill="#ffffff"/>
    </g>
    <!-- bukit -->
    <polygon points="0,500 90,440 180,500 300,430 420,500 480,470 480,520 0,520" fill="#8fd07a"/>
    <polygon points="0,520 120,470 260,520 400,465 480,505 480,540 0,540" fill="#79c25f"/>
    <!-- ladang -->
    <rect x="0" y="540" width="480" height="260" fill="url(#soilG)"/>
    <g fill="#5d3a1f" opacity=".55">
      <rect x="0" y="580" width="480" height="10"/><rect x="0" y="640" width="480" height="10"/><rect x="0" y="700" width="480" height="10"/><rect x="0" y="760" width="480" height="10"/>
    </g>
    <!-- barisan tanaman -->
    <g id="cropField">
      <g class="crop" transform="translate(60,600)"><g class="swayer"><rect x="-4" y="-26" width="8" height="26" fill="#4a8a38"/><rect x="-14" y="-38" width="12" height="12" fill="#7ed957"/><rect x="2" y="-38" width="12" height="12" fill="#7ed957"/><rect x="-6" y="-48" width="12" height="12" fill="#a4e86a"/></g><ellipse cx="0" cy="2" rx="16" ry="5" fill="#5d3a1f" opacity=".5"/></g>
      <g class="crop" transform="translate(140,600)"><g class="swayer" style="animation-delay:-1s"><rect x="-4" y="-26" width="8" height="26" fill="#4a8a38"/><rect x="-14" y="-36" width="28" height="14" fill="#ffb7c5"/><rect x="-8" y="-44" width="16" height="10" fill="#ffd3dc"/></g><ellipse cx="0" cy="2" rx="16" ry="5" fill="#5d3a1f" opacity=".5"/></g>
      <g class="crop" transform="translate(220,600)"><g class="swayer" style="animation-delay:-2s"><rect x="-4" y="-26" width="8" height="26" fill="#4a8a38"/><rect x="-14" y="-38" width="12" height="12" fill="#7ed957"/><rect x="2" y="-38" width="12" height="12" fill="#7ed957"/><rect x="-6" y="-48" width="12" height="12" fill="#a4e86a"/></g><ellipse cx="0" cy="2" rx="16" ry="5" fill="#5d3a1f" opacity=".5"/></g>
      <g class="crop" transform="translate(300,600)"><g class="swayer" style="animation-delay:-.5s"><rect x="-4" y="-26" width="8" height="26" fill="#4a8a38"/><rect x="-14" y="-36" width="28" height="14" fill="#e8b93c"/><rect x="-8" y="-44" width="16" height="10" fill="#ffe27a"/></g><ellipse cx="0" cy="2" rx="16" ry="5" fill="#5d3a1f" opacity=".5"/></g>
      <g class="crop" transform="translate(380,600)"><g class="swayer" style="animation-delay:-1.6s"><rect x="-4" y="-26" width="8" height="26" fill="#4a8a38"/><rect x="-14" y="-38" width="12" height="12" fill="#7ed957"/><rect x="2" y="-38" width="12" height="12" fill="#7ed957"/><rect x="-6" y="-48" width="12" height="12" fill="#a4e86a"/></g><ellipse cx="0" cy="2" rx="16" ry="5" fill="#5d3a1f" opacity=".5"/></g>
      <g class="crop" transform="translate(100,660)"><g class="swayer" style="animation-delay:-2.4s"><rect x="-4" y="-26" width="8" height="26" fill="#4a8a38"/><rect x="-14" y="-36" width="28" height="14" fill="#e8b93c"/><rect x="-8" y="-44" width="16" height="10" fill="#ffe27a"/></g><ellipse cx="0" cy="2" rx="16" ry="5" fill="#5d3a1f" opacity=".5"/></g>
      <g class="crop" transform="translate(200,660)"><g class="swayer" style="animation-delay:-.8s"><rect x="-4" y="-26" width="8" height="26" fill="#4a8a38"/><rect x="-14" y="-38" width="12" height="12" fill="#7ed957"/><rect x="2" y="-38" width="12" height="12" fill="#7ed957"/><rect x="-6" y="-48" width="12" height="12" fill="#a4e86a"/></g><ellipse cx="0" cy="2" rx="16" ry="5" fill="#5d3a1f" opacity=".5"/></g>
      <g class="crop" transform="translate(300,660)"><g class="swayer" style="animation-delay:-1.2s"><rect x="-4" y="-26" width="8" height="26" fill="#4a8a38"/><rect x="-14" y="-36" width="28" height="14" fill="#ffb7c5"/><rect x="-8" y="-44" width="16" height="10" fill="#ffd3dc"/></g><ellipse cx="0" cy="2" rx="16" ry="5" fill="#5d3a1f" opacity=".5"/></g>
      <g class="crop" transform="translate(400,660)"><g class="swayer" style="animation-delay:-2.9s"><rect x="-4" y="-26" width="8" height="26" fill="#4a8a38"/><rect x="-14" y="-38" width="12" height="12" fill="#7ed957"/><rect x="2" y="-38" width="12" height="12" fill="#7ed957"/><rect x="-6" y="-48" width="12" height="12" fill="#a4e86a"/></g><ellipse cx="0" cy="2" rx="16" ry="5" fill="#5d3a1f" opacity=".5"/></g>
    </g>
    <!-- rumah tani -->
    <g transform="translate(150,330)">
      <rect x="-14" y="-120" width="28" height="70" fill="#8b5a2b"/>
      <g class="smoke"><rect x="-8" y="-150" width="16" height="16" fill="#ffffff" opacity=".8"/></g>
      <g class="smoke" style="animation-delay:-1.5s"><rect x="2" y="-160" width="12" height="12" fill="#ffffff" opacity=".6"/></g>
      <rect x="-90" y="-50" width="180" height="110" fill="#f5e6c8"/>
      <rect x="-90" y="-50" width="180" height="14" fill="#e8d3a8"/>
      <polygon points="-105,-50 0,-125 105,-50" fill="#c0392b"/>
      <polygon points="-105,-50 0,-125 105,-50" fill="none" stroke="#8e2a1f" stroke-width="6"/>
      <rect x="-92" y="-44" width="184" height="10" fill="#8e2a1f"/>
      <rect x="-24" y="6" width="48" height="54" fill="#8b5a2b"/>
      <rect x="-24" y="6" width="48" height="54" fill="none" stroke="#5d3a1a" stroke-width="5"/>
      <circle cx="14" cy="34" r="4" fill="#e8b93c"/>
      <rect x="-74" y="-24" width="40" height="36" fill="#bfe3f2"/>
      <rect x="-74" y="-24" width="40" height="36" fill="none" stroke="#5d3a1a" stroke-width="5"/>
      <rect x="-57" y="-24" width="6" height="36" fill="#5d3a1a"/><rect x="-74" y="-9" width="40" height="6" fill="#5d3a1a"/>
      <rect x="34" y="-24" width="40" height="36" fill="#bfe3f2"/>
      <rect x="34" y="-24" width="40" height="36" fill="none" stroke="#5d3a1a" stroke-width="5"/>
      <rect x="51" y="-24" width="6" height="36" fill="#5d3a1a"/><rect x="34" y="-9" width="40" height="6" fill="#5d3a1a"/>
    </g>
    <!-- pagar -->
    <g fill="#8b5a2b" stroke="#5d3a1a" stroke-width="3">
      <rect x="330" y="520" width="14" height="60"/><rect x="380" y="520" width="14" height="60"/><rect x="430" y="520" width="14" height="60"/>
      <rect x="320" y="532" width="140" height="10"/><rect x="320" y="556" width="140" height="10"/>
    </g>
    <!-- kolam -->
    <g transform="translate(60,470)">
      <ellipse cx="0" cy="0" rx="70" ry="30" fill="#5db9e8"/>
      <ellipse cx="0" cy="0" rx="58" ry="22" fill="#8fd4f5"/>
      <ellipse cx="-20" cy="-4" rx="24" ry="8" fill="#bfe9fb"/>
    </g>
    <!-- ayam -->
    <g id="chicken" transform="translate(330,700)">
      <ellipse cx="0" cy="4" rx="26" ry="6" fill="#000" opacity=".18"/>
      <g id="chickBody">
        <rect x="-20" y="-34" width="40" height="34" fill="#ffffff"/>
        <rect x="-20" y="-34" width="40" height="8" fill="#f2f2f2"/>
        <rect x="12" y="-28" width="14" height="12" fill="#ff9f2e"/>
        <rect x="-30" y="-40" width="10" height="10" fill="#e8b93c"/>
        <rect x="-26" y="-50" width="8" height="10" fill="#d94f3d"/>
        <rect x="-18" y="-52" width="8" height="10" fill="#d94f3d"/>
        <rect x="2" y="-26" width="6" height="6" fill="#222"/>
        <rect x="-12" y="0" width="8" height="12" fill="#ff9f2e"/><rect x="4" y="0" width="8" height="12" fill="#ff9f2e"/>
      </g>
    </g>
    <g id="cluck" transform="translate(330,640)"><rect x="-46" y="-24" width="92" height="30" rx="4" fill="#fff" stroke="#5d3a1a" stroke-width="3"/><text x="0" y="-4" text-anchor="middle" font-family="monospace" font-size="13" fill="#3a2a18">Cluck!</text></g>
    <!-- junimo -->
    <g class="bob" transform="translate(110,540)">
      <ellipse cx="0" cy="26" rx="20" ry="5" fill="#000" opacity=".15"/>
      <circle cx="0" cy="0" r="24" fill="#7ed957"/><circle cx="0" cy="0" r="24" fill="none" stroke="#3f7a2e" stroke-width="4"/>
      <circle cx="-8" cy="-4" r="4" fill="#1d2b16"/><circle cx="8" cy="-4" r="4" fill="#1d2b16"/>
      <rect x="-10" y="6" width="20" height="4" fill="#1d2b16"/>
      <rect x="-4" y="-30" width="8" height="10" fill="#3f7a2e"/>
    </g>
    <g class="bob" style="animation-delay:-1.3s" transform="translate(420,560)">
      <ellipse cx="0" cy="22" rx="17" ry="4" fill="#000" opacity=".15"/>
      <circle cx="0" cy="0" r="20" fill="#c99df5"/><circle cx="0" cy="0" r="20" fill="none" stroke="#7a4fa3" stroke-width="4"/>
      <circle cx="-7" cy="-3" r="3.5" fill="#2b1d3a"/><circle cx="7" cy="-3" r="3.5" fill="#2b1d3a"/>
      <rect x="-8" y="5" width="16" height="4" fill="#2b1d3a"/>
      <rect x="-3" y="-26" width="7" height="9" fill="#7a4fa3"/>
    </g>
  </svg>
  <div class="petal" style="left:12%;animation-duration:9s;font-size:18px">🌸</div>
  <div class="petal" style="left:38%;animation-duration:12s;animation-delay:-4s;font-size:14px">🌸</div>
  <div class="petal" style="left:64%;animation-duration:10s;animation-delay:-7s;font-size:16px">🌸</div>
  <div class="petal" style="left:85%;animation-duration:13s;animation-delay:-2s;font-size:13px">🌸</div>

  <div class="sign" style="top:120px;width:min(78%,330px);padding:22px 14px">
    <span class="rope"></span>
    <div class="px" style="font-size:9px;letter-spacing:.25em;color:#d9a94e;margin-bottom:10px">★ THE WEDDING OF ★</div>
    <div class="px" style="font-size:17px;line-height:1.7;color:#ffe9b8">{{ $groomNick }}<br>&amp;<br>{{ $brideNick }}</div>
    <div class="px" style="font-size:9px;margin-top:12px;color:#e8c87a">{{ mcDate($wDate) }}</div>
  </div>

  <div style="position:absolute;bottom:36px;left:0;right:0;text-align:center;padding:0 24px">
    <div class="plaque" style="display:inline-block;padding:12px 22px;margin-bottom:16px">
      <div style="font-size:11px;color:var(--ink-soft);letter-spacing:.15em">KEPADA YTH</div>
      <div class="px" style="font-size:12px;margin-top:6px">{{ $guestName }}</div>
    </div>
    <br>
    <button class="btn-wood" onclick="openInv()">▶ BUKA UNDANGAN</button>
    <div class="px" style="font-size:8px;color:#fff;margin-top:14px;text-shadow:1px 1px 0 #3a2a18;letter-spacing:.15em">KLIK AYAM &amp; PANEN TANAMAN YA!</div>
  </div>
</div>
<!-- ================= HERO ================= -->
<section id="hero" style="text-align:center;padding-top:70px">
  <div class="rv">
    <div class="sec-kicker">— SELAMAT DATANG DI KEBUN KAMI —</div>
    <div class="px" style="font-size:11px;color:var(--ink-soft);letter-spacing:.2em;margin-bottom:14px">THE WEDDING OF</div>
    <h1 class="px sec-title" style="font-size:30px;line-height:1.7">{{ $groomNick }}<br><span style="color:var(--red)">&amp;</span><br>{{ $brideNick }}</h1>
    <div class="fence rv"></div>
    <div class="rv" style="margin-top:20px">
      <div class="px" style="font-size:12px">{{ mcDate($wDate) }}</div>
      @if(mcGet('quotes'))
      <div class="rv" style="max-width:420px;margin:20px auto 0;padding:0 8px">
        <p style="font-family:'Nunito',sans-serif;font-style:italic;font-size:16px;line-height:1.8;color:var(--ink-soft)">“{{ mcGet('quotes') }}”</p>
      </div>
      @endif
    </div>
    <div class="cd rv" id="cd">
      <div class="box"><div class="num" id="cd-d">0</div><div class="lbl">Hari</div></div>
      <div class="box"><div class="num" id="cd-h">0</div><div class="lbl">Jam</div></div>
      <div class="box"><div class="num" id="cd-m">0</div><div class="lbl">Menit</div></div>
      <div class="box"><div class="num" id="cd-s">0</div><div class="lbl">Detik</div></div>
    </div>
  </div>
</section>

<!-- ================= MEMPELAI ================= -->
<section id="mempelai">
  <div class="sec-head rv">
    <div class="sec-kicker">★ KARAKTER UTAMA ★</div>
    <div class="sec-title">Mempelai</div>
    <div class="fence"></div>
  </div>
  <div class="rv npc" style="margin-bottom:22px">
    <div class="pframe">
      @if(mcGet('groom_photo'))
      <img src="{{ mcGet('groom_photo') }}" alt="{{ $groomNick }}">
      @else
      <svg viewBox="0 0 400 300" class="pxc" style="width:100%;height:300px;display:block">
        <rect width="400" height="300" fill="#aee3f5"/>
        <rect x="150" y="90" width="100" height="110" fill="#f2c89b"/>
        <rect x="150" y="90" width="100" height="26" fill="#5d3a1a"/>
        <rect x="170" y="130" width="16" height="16" fill="#222"/><rect x="214" y="130" width="16" height="16" fill="#222"/>
        <rect x="186" y="165" width="28" height="8" fill="#8c4a2f"/>
        <rect x="140" y="200" width="120" height="100" fill="#4a7ec2"/>
      </svg>
      @endif
      <div class="px" style="position:absolute;bottom:10px;left:10px;background:#2b1d0e;color:#ffe9b8;font-size:9px;padding:8px 10px;border-radius:6px">LVL ♥ PETANI</div>
    </div>
    <div class="pbody">
      <h3>{{ $groomFull }}</h3>
      <div class="role">{{ $groomNick }}</div>
      <p>{{ mcGet('groom_child_of', 'Putra') }} dari pasangan<br><b>{{ mcGet('groom_parents', 'Bapak & Ibu Mempelai Pria') }}</b></p>
    </div>
  </div>
  <div class="rv npc">
    <div class="pframe">
      @if(mcGet('bride_photo'))
      <img src="{{ mcGet('bride_photo') }}" alt="{{ $brideNick }}">
      @else
      <svg viewBox="0 0 400 300" class="pxc" style="width:100%;height:300px;display:block">
        <rect width="400" height="300" fill="#ffd9e8"/>
        <rect x="150" y="90" width="100" height="110" fill="#f7d3a8"/>
        <rect x="138" y="70" width="124" height="60" fill="#7a4a21"/>
        <rect x="138" y="70" width="124" height="22" fill="#5d3a1a"/>
        <rect x="170" y="130" width="16" height="16" fill="#222"/><rect x="214" y="130" width="16" height="16" fill="#222"/>
        <rect x="186" y="162" width="28" height="10" fill="#c25e7a"/>
        <rect x="140" y="200" width="120" height="100" fill="#d94f8a"/>
      </svg>
      @endif
      <div class="px" style="position:absolute;bottom:10px;left:10px;background:#2b1d0e;color:#ffe9b8;font-size:9px;padding:8px 10px;border-radius:6px">LVL ♥ PETANI</div>
    </div>
    <div class="pbody">
      <h3>{{ $brideFull }}</h3>
      <div class="role">{{ $brideNick }}</div>
      <p>{{ mcGet('bride_child_of', 'Putri') }} dari pasangan<br><b>{{ mcGet('bride_parents', 'Bapak & Ibu Mempelai Wanita') }}</b></p>
    </div>
  </div>
</section>

<!-- ================= GALERI ================= -->
@if(!empty($gallery) && count($gallery))
<section id="galeri">
  <div class="sec-head rv">
    <div class="sec-kicker">▦ PAPAN PENGUMUMAN ▦</div>
    <div class="sec-title">Galeri</div>
    <div class="fence"></div>
  </div>
  <div class="g-grid rv">
    @foreach(array_slice($gallery, 0, 8) as $img)
    <div class="g-frame"><img src="{{ $img }}" alt="Galeri" loading="lazy"></div>
    @endforeach
  </div>
  <p class="rv" style="text-align:center;font-size:12px;color:var(--ink-soft);margin-top:14px">Klik foto untuk memperbesar • geser untuk lanjut</p>
</section>
@endif

<!-- ================= KISAH ================= -->
@if(!empty($stories) && count($stories))
<section id="kisah">
  <div class="sec-head rv">
    <div class="sec-kicker">✎ JURNAL PETUALANGAN ✎</div>
    <div class="sec-title">Kisah Cinta</div>
    <div class="fence"></div>
  </div>
  <div class="journal rv">
    @foreach($stories as $st)
    <div class="jpage">
      @if(!empty($st->tahun))<div class="yr">{{ $st->tahun }}</div>@endif
      <h4>{{ $st->judul ?? 'Cerita' }}</h4>
      <p>{{ $st->isi ?? '' }}</p>
    </div>
    @endforeach
  </div>
</section>
@endif
<!-- ================= ACARA ================= -->
<section id="acara">
  <div class="sec-head rv">
    <div class="sec-kicker">◷ PAPAN PETUNJUK ◷</div>
    <div class="sec-title">Waktu &amp; Tempat</div>
    <div class="fence"></div>
  </div>
  <div class="rv post" style="margin-bottom:22px">
    <div class="phead"><h3>⚒ AKAD NIKAH</h3></div>
    <div class="pbody">
      <div class="pdate">{{ mcDate($wDate) }}</div>
      <div class="ptime">{{ $wTime }} – {{ $wTimeE }} {{ mcGet('wedding_timezone', 'WIB') }}</div>
      <div class="pplace">{{ mcGet('wedding_location', 'Kediaman Mempelai') }}</div>
      @if(mcGet('wedding_map_link'))
      <a class="btn-gold" target="_blank" href="{{ mcGet('wedding_map_link') }}">BUKA PETA</a>
      @endif
    </div>
  </div>
  <div class="rv post">
    <div class="phead"><h3>🎉 RESEPSI</h3></div>
    <div class="pbody">
      <div class="pdate">{{ mcDate($rDate) }}</div>
      <div class="ptime">{{ $rTimeS }} – {{ $rTimeE }} {{ mcGet('reception_timezone', mcGet('wedding_timezone', 'WIB')) }}</div>
      <div class="pplace">{{ mcGet('reception_location', mcGet('wedding_location', 'Kediaman Mempelai')) }}</div>
      @if(mcGet('reception_map_link'))
      <a class="btn-gold" target="_blank" href="{{ mcGet('reception_map_link') }}">BUKA PETA</a>
      @endif
      @if(mcGet('live_stream_link'))
      @php $lvE = mcGet('live_stream_link'); if (preg_match('~(?:youtube\.com/(?:watch\?v=|live/|shorts/|embed/)|youtu\.be/)([\w-]{6,})~', (string)$lvE, $lm2)) { $lvE = 'https://www.youtube-nocookie.com/embed/'.$lm2[1]; } @endphp
      <div style="margin:12px auto 6px;max-width:330px;border:3px solid #7a4a21;border-radius:10px;overflow:hidden;box-shadow:0 6px 16px rgba(0,0,0,.25)"><div style="position:relative;padding-top:56.25%"><iframe src="{{ $lvE }}" style="position:absolute;inset:0;width:100%;height:100%;border:0" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin" title="Live Streaming"></iframe></div></div>
      @endif
      @if(mcGet('live_stream_link'))
      <a class="btn-gold" target="_blank" href="{{ mcGet('live_stream_link') }}">LIVE STREAMING</a>
      @endif
    </div>
  </div>
</section>

@if(mcGet('guest_qr_enabled') && !empty($guestQrCode))
<!-- QR CHECK-IN -->
<section id="qrcheckin">
  <div class="sec-head rv">
    <div class="sec-kicker">✓ KEHADIRAN ✓</div>
    <div class="sec-title">QR Check-in</div>
    <div class="fence"></div>
  </div>
  <div class="qr-card rv">
    <div id="qrBox" style="display:inline-block;padding:14px;background:#fff;border-radius:12px"></div>
    <p style="font-size:13px;color:var(--ink-soft);margin-top:14px;line-height:1.7">Tunjukkan QR code ini kepada panitia<br>saat kedatangan.<br>Nama: <b>{{ $guestName }}</b></p>
  </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>if(window.QRCode){new QRCode(document.getElementById('qrBox'),{text:"{{ $guestQrCode }}",width:200,height:200,correctLevel:QRCode.CorrectLevel.M});}</script>
@endif

<!-- ================= RSVP ================= -->
<section id="rsvp">
  <div class="sec-head rv">
    <div class="sec-kicker">✓ KONFIRMASI ✓</div>
    <div class="sec-title">RSVP</div>
    <div class="fence"></div>
  </div>
  <form id="rsvpForm" class="formbox rv" onsubmit="return sendRSVP(event)">
    <div class="field"><label>Nama Lengkap</label><input required id="rsvpNama" placeholder="Nama Anda" autocomplete="name"></div>
    <div class="field"><label>Jumlah Tamu</label>
      <select id="rsvpJumlah"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select>
    </div>
    <div class="field"><label>Kehadiran</label>
      <div class="att-row">
        <div class="att sel-hadir" data-v="hadir" onclick="setAtt(this)">HADIR ✓</div>
        <div class="att" data-v="tidak" onclick="setAtt(this)">TIDAK ✗</div>
      </div>
    </div>
    <div class="field" id="alasanBox" style="display:none"><label>Alasan / Ucapan</label><textarea id="rsvpAlasan" rows="2" placeholder="Tulis alasan..."></textarea></div>
    <button class="btn-wood" type="submit" style="width:100%;font-size:11px">KIRIM RSVP</button>
  </form>
</section>

<!-- ================= UCAPAN ================= -->
<section id="ucapan">
  <div class="sec-head rv">
    <div class="sec-kicker">✉ PAPAN SURAT ✉</div>
    <div class="sec-title">Ucapan</div>
    <div class="fence"></div>
  </div>
  <div id="wishList" class="rv"></div>
  <form id="wishForm" class="formbox rv" onsubmit="return sendWish(event)" style="margin-top:22px">
    <div class="field"><label>Nama</label><input required name="name" maxlength="40" placeholder="Nama Anda"></div>
    <div class="field"><label>Ucapan</label><textarea required name="message" rows="3" placeholder="Tulis doa terbaik Anda..."></textarea></div>
    <button class="btn-wood" type="submit" style="width:100%;font-size:11px">KIRIM UCAPAN</button>
  </form>
</section>

<!-- ================= GIFT ================= -->
<section id="gift">
  <div class="sec-head rv">
    <div class="sec-kicker">✦ GUDANG HADIAH ✦</div>
    <div class="sec-title">Gift</div>
    <div class="fence"></div>
  </div>
  <p class="rv" style="text-align:center;font-size:14px;color:var(--ink-soft);margin-bottom:22px;line-height:1.8">Doa restu Anda adalah hadiah terindah.<br>Namun jika ingin memberi lebih:</p>
  @if(!empty($accounts))
@if($qrisEnabled)
<div style="max-width:340px;margin:0 auto 18px;background:#ffffff;border-radius:16px;padding:20px;text-align:center;box-shadow:0 10px 30px rgba(0,0,0,.18);color:#222222;grid-column:1/-1">
<div style="font-weight:800;letter-spacing:.18em;font-size:13px;color:#c0392b">QRIS</div>
<p style="font-size:12px;opacity:.65;margin:4px 0 12px">Scan untuk kirim hadiah &mdash; semua e-wallet &amp; m-banking</p>
<img src="{{ $qrisImage }}" alt="QRIS" style="width:210px;height:210px;object-fit:contain;margin:0 auto;display:block">
@if($qrisName)<p style="font-size:13px;font-weight:600;margin-top:10px">{{ $qrisName }}</p>@endif
<p style="font-size:11px;opacity:.55;margin-top:4px">atau pilih transfer bank di bawah ini</p>
</div>
@endif
  @foreach($accounts as $a)
  <div class="bank rv">
    <div class="bname">{{ $a['bank'] }}</div>
    <div class="bno">{{ $a['no'] }}</div>
    <div class="ban">a.n. {{ $a['an'] }}</div>
    @if(!empty($a['logo']))<img src="{{ $a['logo'] }}" alt="Logo bank">@endif
    <button class="copybtn" onclick="copyText('{{ $a['no'] }}', this)">SALIN NOMOR</button>
  </div>
  @endforeach
  @endif
  @if(mcGet('gift_address'))
  <div class="qr-card rv" style="margin-top:8px">
    <div class="px" style="font-size:10px;color:var(--ink-soft);letter-spacing:.15em;margin-bottom:10px">KIRIM HADIAH FISIK</div>
    <p style="font-size:14px;line-height:1.8">{{ mcGet('gift_address') }}</p>
    @if(mcGet('gift_maps_link'))
    <a class="btn-gold" target="_blank" href="{{ mcGet('gift_maps_link') }}">BUKA PETA</a>
    @endif
  </div>
  @endif
</section>

<footer>
  <div class="rv">
    <div class="px" style="font-size:10px;color:var(--ink-soft);letter-spacing:.2em;margin-bottom:12px">TERIMA KASIH</div>
    <div class="px" style="font-size:15px;line-height:2">{{ $groomNick }} &amp; {{ $brideNick }}</div>
    <div style="font-size:32px;margin-top:14px">🌻</div>
  </div>
</footer>
</div><!-- /wrap -->

<nav id="fmenu" aria-label="Menu undangan"></nav>

<div class="lb" id="lb">
  <span class="lb-x" onclick="closeLb()">&times;</span>
  <span class="lb-prev" onclick="stepLb(-1)">&#10094;</span>
  <img id="lbImg" src="" alt="Foto">
  <span class="lb-next" onclick="stepLb(1)">&#10095;</span>
  <span class="lb-count" id="lbCount"></span>
</div>

<audio id="mus" loop preload="none"></audio>
<script>
/* === Modul fitur bawaan: musik API, lightbox, menu, countdown resepsi, RSVP & ucapan === */
const INV_ID = {{ (int)$inv_id }};
const CD_ISO = "{{ $countdownISO }}";
const CD_TZ = "{{ $tz }}";

function esc(s){return String(s==null?'':s).replace(/[&<>"']/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];});}
function $(s){return document.querySelector(s);}
function $$(s){return Array.prototype.slice.call(document.querySelectorAll(s));}

/* ---------- buka undangan ---------- */
function openInv(){
  document.getElementById('cover').classList.add('open');
  document.body.classList.remove('locked');
  document.body.style.overflow='';
  startMusic();
  setTimeout(function(){ var m=$('#hero'); if(m) m.scrollIntoView(); }, 950);
}

/* ---------- musik ---------- */
var musEl = document.getElementById('mus'), musOn = true, musVol = 50;
async function startMusic(){
  try{
    var r = await fetch('/api/music?inv_id=' + INV_ID);
    var d = await r.json();
    var url = (d.data && (d.data.file_path || d.data.url)) || "{{ $musicUrl }}";
    var autoplay = true;
    try{
      var s = await fetch('/api/get_settings?inv_id=' + INV_ID); var sd = await s.json();
      if(sd.data){ musVol = parseInt(sd.data.music_volume || '50', 10); if(sd.data.music_autoplay === '0') autoplay = false; }
    }catch(e){}
    if(!url) { document.getElementById('musBtn').style.display='none'; return; }
    musEl.src = url; musEl.volume = 0;
    var btn = document.getElementById('musBtn');
    btn.onclick = function(){
      if(musEl.paused){ musEl.play().catch(function(){}); btn.classList.remove('off'); }
      else{ musEl.pause(); btn.classList.add('off'); }
    };
    if(autoplay){
      musEl.play().then(function(){
        var v = 0, target = musVol/100, iv = setInterval(function(){
          v += 0.05; if(v >= target){ v = target; clearInterval(iv); }
          musEl.volume = v;
        }, 120);
      }).catch(function(){});
    }
  }catch(e){ document.getElementById('musBtn').style.display='none'; }
}

/* ---------- countdown ---------- */
function tickCd(){
  try{
    var target = new Date(CD_ISO);
    // terapkan offset timezone sederhana via Intl
    var now = new Date();
    var diff = Math.max(0, target.getTime() - now.getTime());
    var dd = Math.floor(diff/864e5), hh = Math.floor(diff/36e5)%24, mm = Math.floor(diff/6e4)%60, ss = Math.floor(diff/1e3)%60;
    document.getElementById('cd-d').textContent = dd;
    document.getElementById('cd-h').textContent = String(hh).padStart(2,'0');
    document.getElementById('cd-m').textContent = String(mm).padStart(2,'0');
    document.getElementById('cd-s').textContent = String(ss).padStart(2,'0');
  }catch(e){}
}
setInterval(tickCd, 1000); tickCd();

/* ---------- reveal ---------- */
var io = new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){e.target.classList.add('on');io.unobserve(e.target);}});},{threshold:.12});
$$('.rv').forEach(function(el){io.observe(el);});

/* ---------- progress ---------- */
window.addEventListener('scroll', function(){
  var h = document.documentElement;
  var p = h.scrollTop / (h.scrollHeight - h.clientHeight) * 100;
  document.getElementById('pbar').style.width = p + '%';
}, {passive:true});

/* ---------- floating menu ---------- */
(function(){
  var SECS = [['hero','⌂','Awal'],['mempelai','♥','Mempelai'],['galeri','▦','Galeri'],
              ['kisah','✎','Kisah'],['acara','◷','Acara'],['rsvp','✓','RSVP'],
              ['ucapan','✉','Ucapan'],['gift','✦','Gift']];
  var nav = document.getElementById('fmenu');
  var items = [];
  SECS.forEach(function(s){
    if(!document.getElementById(s[0])) return;
    var a = document.createElement('a');
    a.href = '#' + s[0];
    a.innerHTML = '<span class="ic">' + s[1] + '</span><span>' + s[2] + '</span>';
    a.addEventListener('click', function(e){
      e.preventDefault();
      document.getElementById(s[0]).scrollIntoView({behavior:'smooth'});
    });
    nav.appendChild(a); items.push([s[0], a]);
  });
  var spy = new IntersectionObserver(function(es){
    es.forEach(function(e){
      if(e.isIntersecting){
        items.forEach(function(it){ it[1].classList.toggle('on', it[0] === e.target.id); });
      }
    });
  }, {rootMargin:'-40% 0px -55% 0px'});
  items.forEach(function(it){ var el=document.getElementById(it[0]); if(el) spy.observe(el); });
})();

/* ---------- lightbox ---------- */
var lbList=[], lbIdx=0, lbDir=1;
function lbCollect(){
  lbList = $$('.g-frame img, .pframe img').filter(function(im){return im.getAttribute('src');});
}
function openLb(src){
  if(!src) return;
  lbCollect();
  var i = lbList.findIndex(function(im){ return im.src === src; });
  lbIdx = i < 0 ? 0 : i;
  showLb();
}
function showLb(){
  var im = lbList[lbIdx];
  if(!im) return;
  var el = document.getElementById('lbImg');
  el.style.transition = 'none';
  el.style.transform = 'translateX(' + (46 * lbDir) + 'px)';
  el.style.opacity = '0';
  el.src = im.src;
  document.getElementById('lbCount').textContent = (lbIdx+1) + ' / ' + lbList.length;
  document.getElementById('lb').classList.add('show');
  document.body.style.overflow = 'hidden';
  requestAnimationFrame(function(){ requestAnimationFrame(function(){
    el.style.transition = 'transform .28s ease, opacity .28s ease';
    el.style.transform = 'translateX(0)';
    el.style.opacity = '1';
  }); });
  [1,-1].forEach(function(d){
    var n = lbList[(lbIdx + d + lbList.length) % lbList.length];
    if(n){ var pre = new Image(); pre.src = n.src; }
  });
}
function stepLb(d){
  if(!lbList.length) return;
  lbDir = d >= 0 ? 1 : -1;
  lbIdx = (lbIdx + d + lbList.length) % lbList.length;
  showLb();
}
function closeLb(){
  document.getElementById('lb').classList.remove('show');
  document.body.style.overflow = '';
}
$$('.g-frame img, .pframe img').forEach(function(im){
  if(!im.getAttribute('src')) return;
  im.style.cursor = 'zoom-in';
  im.addEventListener('click', function(){ openLb(im.src); });
});
document.getElementById('lb').addEventListener('click', function(e){ if(e.target === this) closeLb(); });
document.addEventListener('keydown', function(e){
  if(!document.getElementById('lb').classList.contains('show')) return;
  if(e.key === 'ArrowLeft') stepLb(-1);
  else if(e.key === 'ArrowRight') stepLb(1);
  else if(e.key === 'Escape') closeLb();
});
(function(){
  var lb = document.getElementById('lb'), sx = 0, sy = 0, swiped = false;
  lb.addEventListener('touchstart', function(e){
    sx = e.changedTouches[0].clientX; sy = e.changedTouches[0].clientY; swiped = false;
  }, {passive:true});
  lb.addEventListener('touchmove', function(e){
    if(swiped) return;
    var dx = e.changedTouches[0].clientX - sx, dy = e.changedTouches[0].clientY - sy;
    if(Math.abs(dx) > 50 && Math.abs(dx) > Math.abs(dy) * 1.4){
      swiped = true; stepLb(dx < 0 ? 1 : -1);
    }
  }, {passive:true});
})();

/* ---------- RSVP ---------- */
var __att = 'hadir';
function setAtt(el){
  __att = el.getAttribute('data-v');
  $$('.att').forEach(function(b){ b.classList.remove('sel-hadir','sel-tidak'); });
  el.classList.add(__att === 'hadir' ? 'sel-hadir' : 'sel-tidak');
  document.getElementById('alasanBox').style.display = __att === 'hadir' ? 'none' : 'block';
}
async function sendRSVP(e){
  e.preventDefault();
  var nama = document.getElementById('rsvpNama').value.trim();
  var jumlah = document.getElementById('rsvpJumlah').value;
  var alasan = document.getElementById('rsvpAlasan').value.trim();
  if(!nama){ alert('Mohon isi nama.'); return false; }
  try{
    var r = await fetch('/api/rsvp', { method:'POST', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ invitation_id: INV_ID, nama: nama, jumlah_tamu: jumlah, status: __att, alasan: alasan }) });
    var j = await r.json();
    if(!j.success) throw new Error(j.message || 'Gagal mengirim');
    var waNumber = "{{ mcGet('rsvp_whatsapp','6288210841990') }}".replace(/[^0-9]/g, '');
    var msg = 'Halo! Saya ' + nama + ' (' + jumlah + ' orang) — ' +
      (__att === 'hadir' ? 'INSYA ALLAH HADIR' : 'BERHALANGAN') +
      (alasan ? '. Catatan: ' + alasan : '');
    window.open('https://wa.me/' + waNumber + '?text=' + encodeURIComponent(msg), '_blank');
    document.getElementById('rsvpForm').reset();
    alert('RSVP terkirim! 🌱');
  }catch(err){ alert('Gagal: ' + err.message); }
  return false;
}

/* ---------- ucapan ---------- */
function timeAgo(ts){
  if(!ts) return 'Baru saja';
  var s = String(ts).replace(' ', 'T');
  if(s.indexOf('Z') < 0 && s.indexOf('+') < 0) s += '+07:00';
  var d = new Date(s), diff = Math.floor((Date.now() - d.getTime()) / 1000);
  if(isNaN(diff) || diff < 0 || diff < 60) return 'Baru saja';
  if(diff < 3600) return Math.floor(diff / 60) + ' menit yang lalu';
  if(diff < 86400) return Math.floor(diff / 3600) + ' jam yang lalu';
  return Math.floor(diff / 86400) + ' hari yang lalu';
}
async function loadWishes(){
  try{
    var r = await fetch('/api/ucapan?inv_id=' + INV_ID + '&limit=20');
    var d = await r.json();
    var rows = d.data || [];
    document.getElementById('wishList').innerHTML = rows.map(function(w){
      return '<div class="wish"><div class="wish-head"><b>' + esc(w.nama) + '</b><span class="wtime">' + esc(timeAgo(w.created_at)) + '</span></div><p>' + esc(w.pesan) + '</p></div>';
    }).join('') || '<p class="wish-empty">Belum ada ucapan. Jadilah yang pertama! 🌻</p>';
  }catch(e){}
}
async function sendWish(e){
  e.preventDefault();
  var f = e.target;
  var nama = f.name.value.trim(), pesan = f.message.value.trim();
  if(!nama || !pesan){ alert('Mohon isi nama dan ucapan.'); return false; }
  try{
    var r = await fetch('/api/ucapan', { method:'POST', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ invitation_id: INV_ID, nama: nama, pesan: pesan }) });
    var j = await r.json();
    if(!j.success) throw new Error(j.message || 'Gagal mengirim');
    f.reset(); loadWishes();
  }catch(err){ alert('Gagal: ' + err.message); }
  return false;
}
loadWishes();

/* ---------- gift copy ---------- */
function copyText(t, btn){
  function done(){ var o = btn.textContent; btn.textContent = 'TERSALIN ✓'; setTimeout(function(){ btn.textContent = o; }, 1600); }
  if(navigator.clipboard && navigator.clipboard.writeText){
    navigator.clipboard.writeText(t).then(done).catch(function(){ fallback(); });
  } else fallback();
  function fallback(){
    var ta = document.createElement('textarea'); ta.value = t; document.body.appendChild(ta);
    ta.select(); try{ document.execCommand('copy'); done(); }catch(e){} document.body.removeChild(ta);
  }
}

/* ---------- interaksi game: panen & ayam ---------- */
var panen = 0;
$$('#cropField .crop').forEach(function(c){
  c.addEventListener('click', function(){
    if(c.classList.contains('picked')) return;
    c.classList.add('picked');
    panen++;
    document.getElementById('panenCount').textContent = panen;
    var r = c.getBoundingClientRect(), w = document.querySelector('.wrap').getBoundingClientRect();
    var sp = document.createElement('div');
    sp.className = 'spark';
    sp.textContent = ['✦','🌟','💰'][Math.floor(Math.random()*3)];
    sp.style.left = (r.left - w.left + r.width/2) + 'px';
    sp.style.top = (r.top - w.top) + 'px';
    document.querySelector('.wrap').appendChild(sp);
    setTimeout(function(){ sp.remove(); }, 1000);
  });
});
var chick = document.getElementById('chicken');
if(chick){
  chick.addEventListener('click', function(){
    var b = document.getElementById('chickBody');
    b.style.transition = 'transform .3s';
    b.style.transform = 'translateY(-24px)';
    setTimeout(function(){ b.style.transform = 'translateY(0)'; }, 320);
    var cl = document.getElementById('cluck');
    cl.classList.add('show');
    setTimeout(function(){ cl.classList.remove('show'); }, 1200);
  });
}
</script>
</body>
</html>
