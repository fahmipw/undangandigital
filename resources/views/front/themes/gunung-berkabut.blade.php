<?php
/* ============================================================
   TEMA PREMIUM: GUNUNG BERKABUT (Misty Mountain)
   Dipilih via settings: theme = gunung-berkabut
   Background berganti saat scroll: pagi berkabut -> siang cerah
   -> sore keemasan -> senja gelap. Gunung parallax, kabut, pinus.
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
$sameLoc = (mcGet('event_location_mode', 'same') === 'same');
$mainMapLink = trim((string)mcGet('wedding_map_link')) ?: trim((string)mcGet('reception_map_link'));
$giftRecipient = mcGet('gift_recipient_name', mcGet('gift_owner', ''));
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
$qrisEnabled = mcGet('gift_qris_enabled', '') === '1' && trim((string)mcGet('gift_qris_image', '')) !== '';
$qrisImage = mcGet('gift_qris_image', '');
$qrisName = mcGet('gift_qris_name', '');
$musicUrl = null;
if (!empty($musicRecord) && !empty($musicRecord->file_path)) {
  $mp = $musicRecord->file_path;
  $musicUrl = (str_starts_with($mp,'http') || str_starts_with($mp,'/')) ? $mp : asset(ltrim($mp,'/'));
}
$invId = $inv_id ?? 0;
$groomPhoto = mcGet('groom_photo'); $bridePhoto = mcGet('bride_photo');
$groomInitial = mb_substr($groomNick, 0, 1); $brideInitial = mb_substr($brideNick, 0, 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $brideNick }} &amp; {{ $groomNick }} — Gunung Berkabut</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Pinyon+Script&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root{--tinta:#33424a;--tinta-dim:rgba(51,66,74,.65);--kertas:rgba(255,255,255,.92);
--aksen:#5b7a8a;--aksen-dk:#3d5a6a;--emas:#c99b4a}
body[data-scene="6"],body[data-scene="7"],body[data-scene="8"],body[data-scene="9"],body[data-scene="10"]{
--tinta:#f2ede4;--tinta-dim:rgba(242,237,228,.7);--kertas:rgba(30,22,16,.5);
--aksen:#a8c6d4;--aksen-dk:#d4e6ee}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{font-family:'Jost',sans-serif;background:#dfe9ec;color:var(--tinta);overflow-x:hidden;font-weight:300;transition:color 1s}
/* ---------- background berganti saat scroll ---------- */
#scene{position:fixed;inset:0;z-index:0;pointer-events:none}
.sbg{position:absolute;inset:0;opacity:0;transition:opacity 1.4s ease}
body:not([data-scene]) .sbg:first-child{opacity:1}
body[data-scene="0"] .sbg:nth-child(1){opacity:1}
body[data-scene="1"] .sbg:nth-child(2){opacity:1}
body[data-scene="2"] .sbg:nth-child(3){opacity:1}
body[data-scene="3"] .sbg:nth-child(4){opacity:1}
body[data-scene="4"] .sbg:nth-child(5){opacity:1}
body[data-scene="5"] .sbg:nth-child(6){opacity:1}
body[data-scene="6"] .sbg:nth-child(7){opacity:1}
body[data-scene="7"] .sbg:nth-child(8){opacity:1}
body[data-scene="8"] .sbg:nth-child(9){opacity:1}
body[data-scene="9"] .sbg:nth-child(10){opacity:1}
body[data-scene="10"] .sbg:nth-child(11){opacity:1}
.wrap{position:relative;z-index:2;max-width:520px;margin:0 auto}
.serif{font-family:'Cormorant Garamond',serif}.hand{font-family:'Pinyon Script',cursive}
/* ---------- gunung parallax ---------- */
.ridge{position:fixed;left:0;right:0;bottom:-2px;z-index:1;pointer-events:none}
.ridge svg{display:block;width:100%;preserveAspectRatio:xMidYMax slice}
#ridge1{opacity:.55}#ridge2{opacity:.7}#ridge3{opacity:.9}
#ridge1 svg{height:120px}#ridge2 svg{height:165px}#ridge3 svg{height:210px}
/* ---------- pita kabut ---------- */
.fog{position:fixed;left:-20%;width:140%;height:90px;z-index:1;pointer-events:none;opacity:.45;
background:radial-gradient(ellipse at center,rgba(255,255,255,.6),rgba(255,255,255,0) 70%);
filter:blur(8px);animation:fogdrift linear infinite}
.fog.f1{top:20%;animation-duration:55s}
.fog.f2{top:46%;animation-duration:82s;animation-delay:-30s}
.fog.f3{top:68%;animation-duration:66s;animation-delay:-48s}
@keyframes fogdrift{from{transform:translateX(-10%)}to{transform:translateX(10%)}}
body:not([data-scene]) .fog,
body[data-scene="0"] .fog,body[data-scene="1"] .fog,body[data-scene="2"] .fog,
body[data-scene="8"] .fog,body[data-scene="9"] .fog,body[data-scene="10"] .fog{opacity:.85}
#mist{position:fixed;inset:0;z-index:1;pointer-events:none}
#petals,#fauna{display:none}
/* ---------- langit: matahari, burung ---------- */
.sun{position:absolute;top:46px;right:8%;width:90px;height:90px;border-radius:50%;
background:radial-gradient(circle,#fff8d6 0%,#fbe89b 45%,rgba(251,232,155,0) 72%);filter:blur(1px);animation:sunpulse 6s ease-in-out infinite}
@keyframes sunpulse{0%,100%{transform:scale(1);opacity:.9}50%{transform:scale(1.12);opacity:1}}
.bird{position:absolute;animation:fly linear infinite}
.bird svg{display:block;overflow:visible}
.bird.b1{top:9%;animation-duration:30s}
.bird.b2{top:15%;animation-duration:42s;animation-delay:-18s;transform:scale(.75)}
.bird.b3{top:22%;animation-duration:55s;animation-delay:-35s;transform:scale(.6)}
@keyframes fly{from{left:-70px}to{left:110%}}
.bird .wings{transform-origin:center;animation:flap .55s ease-in-out infinite alternate}
@keyframes flap{from{transform:scaleY(1)}to{transform:scaleY(.45)}}
/* ---------- cover ---------- */
#cover{position:fixed;inset:0;z-index:100;display:flex;align-items:center;justify-content:center;transition:opacity .9s,visibility .9s;overflow-y:auto;background:linear-gradient(180deg,#e8eef0 0%,#f5f7f6 100%)}
#cover.open{opacity:0;visibility:hidden}
.cover-inner{text-align:center;padding:26px 20px 40px;width:100%;max-width:520px;position:relative}
.cover-mtn{display:block;width:100%;max-width:420px;margin:0 auto 6px}
.kicker{font-size:12px;letter-spacing:.5em;text-transform:uppercase;color:var(--aksen-dk);margin-top:6px;font-weight:500}
.cover-names{font-family:'Pinyon Script',cursive;font-size:clamp(52px,14vw,76px);color:var(--tinta);margin:10px 0 2px;line-height:1.15}
.cover-date{font-family:'Cormorant Garamond',serif;font-size:21px;letter-spacing:.3em;color:var(--tinta-dim)}
.kepada{margin-top:14px;font-family:'Cormorant Garamond',serif;font-size:21px;color:var(--tinta)}
.guest-box{margin:10px auto 0;max-width:330px;background:var(--kertas);border:1px solid var(--aksen);border-radius:14px;padding:13px 18px;box-shadow:0 8px 24px rgba(60,80,95,.18)}
.guest-box b{font-family:'Cormorant Garamond',serif;font-size:21px;color:var(--tinta);font-weight:600}
.btn{display:inline-flex;align-items:center;gap:10px;margin-top:16px;padding:14px 40px;background:linear-gradient(135deg,var(--aksen),var(--aksen-dk));border:none;border-radius:999px;color:#fff;font-size:13px;letter-spacing:.22em;text-transform:uppercase;cursor:pointer;font-family:'Jost';font-weight:500;transition:.35s;box-shadow:0 10px 26px rgba(61,90,106,.35)}
.btn:hover{transform:translateY(-2px);box-shadow:0 14px 32px rgba(61,90,106,.45)}
.btn-line{display:inline-block;padding:12px 32px;border:1px solid var(--aksen);color:var(--aksen-dk);font-size:12px;letter-spacing:.26em;text-transform:uppercase;cursor:pointer;background:transparent;border-radius:999px;transition:.3s;text-decoration:none;font-family:'Jost'}
.btn-line:hover{background:rgba(91,122,138,.14)}
/* ---------- section ---------- */
section{padding:64px 26px;text-align:center;position:relative}
.sec-kicker{font-size:11px;letter-spacing:.44em;text-transform:uppercase;color:var(--aksen);margin-bottom:10px;font-weight:500}
.sec-title{font-family:'Cormorant Garamond',serif;font-size:38px;font-weight:600;color:var(--tinta)}
.mtn-div{display:flex;justify-content:center;margin:14px auto;max-width:230px}
.card{background:var(--kertas);border:1px solid rgba(140,170,185,.4);border-radius:18px;padding:34px 24px;margin:0 auto 18px;max-width:400px;box-shadow:0 14px 36px rgba(60,80,95,.14)}
#hero{min-height:94vh;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden}
.hero-hand{font-family:'Pinyon Script',cursive;font-size:34px;color:var(--aksen-dk)}
.hero-names{font-family:'Pinyon Script',cursive;font-size:clamp(54px,15vw,80px);line-height:1.15;margin:12px 0;color:var(--tinta)}
.hero-names em{font-style:normal;color:var(--emas);font-size:.72em}
.hero-date{letter-spacing:.3em;font-size:14px;color:var(--tinta-dim);text-transform:uppercase}
.cd-grid{display:flex;justify-content:center;gap:10px;margin-top:26px}
.cd{width:72px;padding:16px 0;background:var(--kertas);border:1px solid rgba(140,170,185,.4);border-radius:14px;box-shadow:0 8px 20px rgba(60,80,95,.12)}
.cd b{display:block;font-family:'Cormorant Garamond',serif;font-size:32px;color:var(--aksen-dk)}
.cd span{font-size:10px;letter-spacing:.26em;text-transform:uppercase;color:var(--tinta-dim)}
.photo{width:150px;height:150px;border-radius:50%;object-fit:cover;margin:0 auto 16px;border:4px solid #fff;box-shadow:0 12px 30px rgba(60,80,95,.25);display:block}
.photo-fallback{width:150px;height:150px;border-radius:50%;margin:0 auto 16px;background:linear-gradient(135deg,#8ba3b3,#3d5a6a);border:4px solid #fff;box-shadow:0 12px 30px rgba(60,80,95,.25);display:flex;align-items:center;justify-content:center;font-family:'Pinyon Script',cursive;font-size:60px;color:#fff}
.couple-card h3{font-family:'Pinyon Script',cursive;font-size:40px;color:var(--aksen-dk);font-weight:400}
.couple-card .full{font-family:'Cormorant Garamond',serif;font-size:19px;margin:6px 0}
.couple-card p{font-size:14px;color:var(--tinta-dim);line-height:1.7}
.amp{font-family:'Pinyon Script',cursive;font-size:48px;color:var(--emas);margin:2px 0}
.event h3{font-family:'Cormorant Garamond',serif;font-size:27px;letter-spacing:.08em;color:var(--aksen-dk);text-transform:uppercase}
.event .date{font-family:'Cormorant Garamond',serif;font-size:20px;margin:12px 0 4px}
.event .time{letter-spacing:.22em;font-size:13px;color:var(--aksen)}
.event .loc{font-size:14px;color:var(--tinta-dim);margin:12px 0 18px;line-height:1.7}
.g-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:24px}
.g-grid img{width:100%;height:190px;object-fit:cover;border-radius:14px;border:4px solid #fff;box-shadow:0 8px 22px rgba(60,80,95,.18);transition:.4s}
.g-grid img:hover{transform:scale(1.03) rotate(.5deg)}
.tl{max-width:400px;margin:26px auto 0;text-align:left}
.tl-item{background:var(--kertas);border-left:3px solid var(--emas);border-radius:0 14px 14px 0;padding:18px 20px;margin-bottom:14px;box-shadow:0 8px 22px rgba(60,80,95,.12)}
.tl-item h4{font-family:'Cormorant Garamond',serif;font-size:22px;color:var(--aksen-dk)}
.tl-item p{font-size:14px;color:var(--tinta-dim);line-height:1.7;margin-top:6px}
.field{margin:0 auto 14px;max-width:380px;text-align:left}
.field label{display:block;font-size:10px;letter-spacing:.32em;text-transform:uppercase;color:var(--aksen);margin-bottom:8px;font-weight:500}
.field input,.field select,.field textarea{width:100%;background:var(--kertas);border:1px solid rgba(140,170,185,.55);border-radius:12px;color:var(--tinta);padding:13px 16px;font-family:'Jost';font-size:15px;outline:none}
.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--aksen);box-shadow:0 0 0 3px rgba(91,122,138,.15)}
.wish{background:var(--kertas);border:1px solid rgba(140,170,185,.4);border-radius:14px;padding:16px 18px;margin:0 auto 12px;max-width:400px;text-align:left;box-shadow:0 6px 18px rgba(60,80,95,.1)}
.wish-head{display:flex;justify-content:space-between;align-items:baseline;gap:10px;margin-bottom:6px}
.wtime{font-size:11px;opacity:.55;white-space:nowrap}
.wish b{font-family:'Cormorant Garamond',serif;color:var(--aksen-dk);font-size:17px}
.wish .st{font-size:10px;letter-spacing:.2em;color:var(--emas);text-transform:uppercase;margin-left:8px;font-weight:500}
.wish p{font-size:14px;color:var(--tinta-dim);margin-top:6px;line-height:1.6}
.bank{background:var(--kertas);border:1px dashed rgba(140,170,185,.6);border-radius:14px;padding:24px;margin:0 auto 14px;max-width:380px}
.bank .bk{font-size:11px;letter-spacing:.34em;color:var(--aksen);text-transform:uppercase;font-weight:500}
.bank .no{font-family:'Cormorant Garamond',serif;font-size:30px;margin:10px 0 4px;letter-spacing:.06em}
.bank .an{font-size:14px;color:var(--tinta-dim)}
.copy{margin-top:12px}
footer{padding:56px 26px 100px;text-align:center}
footer .hand{font-size:42px;color:var(--aksen-dk)}
#musBtn{position:fixed;bottom:22px;right:22px;z-index:90;width:52px;height:52px;border-radius:50%;border:none;background:linear-gradient(135deg,var(--aksen),var(--aksen-dk));color:#fff;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;bottom:130px!important;box-shadow:0 10px 26px rgba(61,90,106,.4)}

/* === fitur bawaan default: lightbox, menu, progress, musik === */
.pbar{position:fixed;top:0;left:0;right:0;height:3px;z-index:96;background:transparent}
.pbar i{display:block;height:100%;width:0;background:linear-gradient(90deg,var(--aksen),var(--aksen-dk))}
.lb{position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.92);display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:.35s;padding:20px;touch-action:pan-y}
#lbImg{max-width:100%;max-height:86vh;border-radius:8px;will-change:transform,opacity}
.lb.show{opacity:1;visibility:visible}
.lb img{max-width:100%;max-height:86vh;border:2px solid var(--aksen)}
.lb-x{position:absolute;top:16px;right:22px;font-size:40px;color:#fff;cursor:pointer;line-height:1;z-index:201}
.lb-prev,.lb-next{position:absolute;top:50%;transform:translateY(-50%);font-size:32px;color:#fff;cursor:pointer;padding:16px 12px;z-index:201;user-select:none;opacity:.85;line-height:1}
.lb-prev{left:6px}.lb-next{right:6px}.lb-prev:hover,.lb-next:hover{opacity:1}
.lb-count{position:absolute;bottom:16px;left:50%;transform:translateX(-50%);color:#fff;font-size:13px;letter-spacing:.15em;z-index:201;font-family:inherit}
.fmenu{position:fixed;bottom:10px;left:50%;transform:translateX(-50%);z-index:90;display:flex;gap:2px 0;background:rgba(255,253,247,.94);border:1px solid rgba(151,128,69,.4);border-radius:26px;padding:7px 8px;backdrop-filter:blur(10px);box-shadow:0 14px 34px rgba(90,70,20,.25);width:min(96vw,500px);max-width:96vw;flex-wrap:wrap;justify-content:center;overflow:visible;row-gap:2px}
.fmenu::-webkit-scrollbar{display:none}
.fmenu a{display:flex;flex-direction:column;align-items:center;gap:3px;flex:1 1 0;min-width:36px;max-width:54px;padding:7px 0;border-radius:13px;color:#978045;text-decoration:none;font-size:8px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;transition:.25s}
.fmenu a i{font-style:normal;line-height:0}
.fmenu a svg{width:21px;height:21px}
.fmenu a.active{background:rgba(151,128,69,.14);box-shadow:inset 0 0 0 1.5px #b8934a;color:#7c652f}
body::after{content:"";display:block;height:150px}
#musBtn.playing{outline:2px solid var(--aksen-dk);outline-offset:2px}
.att-toggle{display:flex;gap:10px}
.att-toggle button{flex:1;padding:13px 8px;border:1px solid var(--aksen);background:transparent;color:var(--tinta);font-family:'Jost',sans-serif;font-size:14px;cursor:pointer;transition:.25s;border-radius:10px}
.att-toggle button.on{background:var(--aksen);color:#22303a;font-weight:500}
.wish-empty{text-align:center;opacity:.6;font-size:14px;padding:12px}
body.locked #fmenu,body.locked #musBtn,body.locked .pbar{opacity:0 !important;visibility:hidden !important;pointer-events:none !important}

.rv{opacity:0;transform:translateY(36px);transition:opacity .9s,transform .9s}
.rv.on{opacity:1;transform:none}

/* Standar menu klasik emas */
#musBtn{background:linear-gradient(160deg,#3a2c17,#171106)!important;border:1px solid rgba(201,164,92,.85)!important;color:#e6c887!important;box-shadow:0 10px 26px rgba(90,70,20,.5)!important}
</style>
</head>
<body>
<div class="pbar"><i id="pbarFill"></i></div>

<!-- ====== background 11 scene: pagi berkabut -> siang -> sore -> senja ====== -->
<div id="scene">
  <div class="sbg" style="background:linear-gradient(180deg,#e8eef0,#f5f7f6)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#dfe9ec,#f0f4f3)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#d8e4e8,#eef2f1)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#cfe0e8,#f0f4f2)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#c2d8e2,#e8f0ee)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#bcd4de,#e2ecea)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#7a4a2a,#3a2418)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#6a3f24,#33200f)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#4a3f5e,#2a2438)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#3d3450,#221d30)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#332c44,#1d1828)"></div>
</div>

<!-- ====== 3 lapis gunung parallax ====== -->
<div class="ridge" id="ridge1"><svg viewBox="0 0 1440 160" preserveAspectRatio="xMidYMax slice">
<path d="M0,120 L100,60 L180,95 L300,30 L420,90 L540,55 L660,110 L780,40 L900,100 L1020,65 L1140,115 L1260,75 L1360,110 L1440,90 L1440,160 L0,160 Z" fill="#bcccd8"/>
</svg></div>
<div class="ridge" id="ridge2"><svg viewBox="0 0 1440 200" preserveAspectRatio="xMidYMax slice">
<path d="M0,150 L140,70 L260,130 L400,50 L540,140 L700,80 L860,150 L1000,60 L1140,140 L1280,90 L1440,150 L1440,200 L0,200 Z" fill="#93a9b8"/>
</svg></div>
<div class="ridge" id="ridge3"><svg viewBox="0 0 1440 240" preserveAspectRatio="xMidYMax slice">
<path d="M0,190 L180,100 L340,170 L520,80 L700,180 L880,110 L1060,190 L1240,120 L1440,180 L1440,240 L0,240 Z" fill="#748b9b"/>
<g fill="#5d7683">
<rect x="87" y="221" width="6" height="14"/><path d="M72,221 L90,183 L108,221 Z"/><path d="M76,203 L90,170 L104,203 Z"/>
<rect x="317" y="221" width="6" height="14"/><path d="M302,221 L320,183 L338,221 Z"/><path d="M306,203 L320,170 L334,203 Z"/>
<rect x="697" y="221" width="6" height="14"/><path d="M682,221 L700,183 L718,221 Z"/><path d="M686,203 L700,170 L714,203 Z"/>
<rect x="1077" y="221" width="6" height="14"/><path d="M1062,221 L1080,183 L1098,221 Z"/><path d="M1066,203 L1080,170 L1094,203 Z"/>
<rect x="1327" y="221" width="6" height="14"/><path d="M1312,221 L1330,183 L1348,221 Z"/><path d="M1316,203 L1330,170 L1344,203 Z"/>
</g>
</svg></div>

<!-- ====== pita kabut melayang ====== -->
<div class="fog f1"></div><div class="fog f2"></div><div class="fog f3"></div>
<canvas id="mist"></canvas>
<canvas id="petals"></canvas>
<div id="fauna"></div>

<div class="wrap">

<!-- ================= COVER ================= -->
<div id="cover" data-scene="0">
  <div class="cover-inner">
    <svg class="cover-mtn" viewBox="0 0 400 190">
      <path d="M0,140 L60,90 L120,120 L180,60 L240,110 L300,80 L360,120 L400,100 L400,190 L0,190 Z" fill="#c3d2dc"/>
      <path d="M0,160 L80,110 L160,150 L240,100 L320,150 L400,120 L400,190 L0,190 Z" fill="#9fb4c2"/>
      <ellipse cx="200" cy="120" rx="170" ry="18" fill="#ffffff" opacity=".55"/>
      <path d="M0,190 L0,170 L100,140 L200,170 L300,145 L400,170 L400,190 Z" fill="#7d96a6"/>
      <g fill="#5d7683">
        <rect x="57" y="158" width="5" height="12"/><path d="M44,158 L59,128 L74,158 Z"/><path d="M47,143 L59,118 L71,143 Z"/>
        <rect x="337" y="158" width="5" height="12"/><path d="M324,158 L339,128 L354,158 Z"/><path d="M327,143 L339,118 L351,143 Z"/>
      </g>
      <g stroke="#3d5a6a" stroke-width="2.4" fill="none" stroke-linecap="round">
        <path d="M120,52 Q128,44 136,50 Q144,44 152,52"/>
        <path d="M250,40 Q257,33 264,39 Q271,33 278,39"/>
      </g>
    </svg>

    <div class="kicker">The Wedding Of</div>
    <div class="cover-names">{{ $brideNick }} &amp; {{ $groomNick }}</div>
    <div class="cover-date">{{ mcDate($wDate) }}</div>

    <div class="kepada">Kepada Yth.<br>Bapak/Ibu/Saudara/i</div>
    <div class="guest-box"><b>{{ $guestName }}</b></div>
    <div><button class="btn" onclick="openInv()">&#9993; &nbsp;Buka Undangan</button></div>
  </div>
</div>

<!-- ================= HERO ================= -->
<section id="hero" data-scene="1">
  <div class="sun"></div>
  <div class="bird b1"><svg width="52" height="20" viewBox="0 0 52 20"><g class="wings" stroke="#3d5a6a" stroke-width="3" fill="none" stroke-linecap="round"><path d="M2 14 Q14 2 26 12 Q38 2 50 14"/></g></svg></div>
  <div class="bird b2"><svg width="52" height="20" viewBox="0 0 52 20"><g class="wings" stroke="#3d5a6a" stroke-width="3" fill="none" stroke-linecap="round"><path d="M2 14 Q14 2 26 12 Q38 2 50 14"/></g></svg></div>
  <div class="bird b3"><svg width="52" height="20" viewBox="0 0 52 20"><g class="wings" stroke="#5b7a8a" stroke-width="3" fill="none" stroke-linecap="round"><path d="M2 14 Q14 2 26 12 Q38 2 50 14"/></g></svg></div>
  <div class="rv"><div class="hero-hand">The Wedding Of</div></div>
  <div class="rv"><h1 class="hero-names">{{ $brideNick }} <em>&amp;</em> {{ $groomNick }}</h1></div>
  <div class="rv">
    <svg class="mtn-div" viewBox="0 0 230 44" style="width:180px">
      <path d="M10,36 L60,12 L100,30 L140,8 L180,30 L220,16 L220,44 L10,44 Z" fill="none" stroke="var(--aksen)" stroke-width="2.5" stroke-linejoin="round"/>
      <circle cx="140" cy="8" r="5" fill="var(--emas)"/>
    </svg>
  </div>
  <div class="rv"><div class="hero-date">{{ mcDate($wDate) }}</div>
@if(mcGet('quotes'))
<div id=\"quotes\" class="rv" style="max-width:420px;margin:20px auto 0;padding:0 8px">
<p style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:19px;line-height:1.7;opacity:.85">“{{ mcGet('quotes') }}”</p>
</div>
@endif</div>
</section>

<!-- COUNTDOWN -->
<section data-scene="2">
  <div class="rv"><div class="sec-kicker">Menuju Hari Bahagia</div><div class="sec-title serif">Hitung Mundur</div></div>
  <div class="cd-grid rv">
    <div class="cd"><b id="cdD">0</b><span>Hari</span></div>
    <div class="cd"><b id="cdH">0</b><span>Jam</span></div>
    <div class="cd"><b id="cdM">0</b><span>Menit</span></div>
    <div class="cd"><b id="cdS">0</b><span>Detik</span></div>
  </div>
</section>

<!-- MEMPELAI -->
<section id="mempelai" data-scene="3">
  <div class="rv"><div class="sec-kicker">Assalamu'alaikum Wr. Wb.</div><div class="sec-title serif">Mempelai</div></div>
  <div class="card couple-card rv">
    @if($bridePhoto)<img class="photo" src="{{ $bridePhoto }}" alt="{{ $brideFull }}">@else<div class="photo-fallback">{{ $brideInitial }}</div>@endif
    <h3>{{ $brideNick }}</h3><div class="full">{{ $brideFull }}</div>
    <p>Putri dari<br>{{ mcGet('bride_parents', mcGet('bride_child_of', '')) }}</p>
    @if(mcGet('bride_instagram'))<a href="https://instagram.com/{{ ltrim(mcGet('bride_instagram'),'@') }}" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:6px;margin-top:10px;padding:7px 16px;border:1px solid currentColor;border-radius:999px;font-size:11px;letter-spacing:.12em;text-decoration:none;opacity:.85"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="flex:none"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="0.8" fill="currentColor" stroke="none"/></svg>&#64;{{ ltrim(mcGet('bride_instagram'),'@') }}</a>@endif
  </div>
  <div class="amp rv">&amp;</div>
  <div class="card couple-card rv">
    @if($groomPhoto)<img class="photo" src="{{ $groomPhoto }}" alt="{{ $groomFull }}">@else<div class="photo-fallback">{{ $groomInitial }}</div>@endif
    <h3>{{ $groomNick }}</h3><div class="full">{{ $groomFull }}</div>
    <p>Putra dari<br>{{ mcGet('groom_parents', mcGet('groom_child_of', '')) }}</p>
    @if(mcGet('groom_instagram'))<a href="https://instagram.com/{{ ltrim(mcGet('groom_instagram'),'@') }}" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:6px;margin-top:10px;padding:7px 16px;border:1px solid currentColor;border-radius:999px;font-size:11px;letter-spacing:.12em;text-decoration:none;opacity:.85"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="flex:none"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="0.8" fill="currentColor" stroke="none"/></svg>&#64;{{ ltrim(mcGet('groom_instagram'),'@') }}</a>@endif
  </div>
</section>

<!-- ACARA -->
<section id="acara" data-scene="4">
  <div class="rv"><div class="sec-kicker">Waktu &amp; Tempat</div><div class="sec-title serif">Rangkaian Acara</div></div>
  <div class="card event rv">
    <h3>Akad Nikah</h3>
    <div class="date">{{ mcDate($wDate) }}</div>
    <div class="time">{{ $wTime }} — {{ $wTimeE }} {{ mcGet('wedding_timezone','WIB') }}</div>
    <div class="loc">{{ mcGet('wedding_location', 'Kediaman Mempelai') }}</div>
    @if(!$sameLoc && mcGet('wedding_map_link'))<a class="btn-line" target="_blank" href="{{ mcGet('wedding_map_link') }}">Lihat Peta</a>@endif
  </div>
  <div class="card event rv">
    <h3>Resepsi</h3>
    <div class="date">{{ mcDate($rDate) }}</div>
    <div class="time">{{ $rTimeS }} — {{ $rTimeE }} {{ mcGet('wedding_timezone','WIB') }}</div>
    <div class="loc">{{ mcGet('reception_location', mcGet('wedding_location', 'Kediaman Mempelai')) }}</div>
    @if(!$sameLoc && mcGet('reception_map_link'))<a class="btn-line" target="_blank" href="{{ mcGet('reception_map_link') }}">Lihat Peta</a>@endif
@if($sameLoc && $mainMapLink)<div style="text-align:center;margin-top:12px"><a class="btn-line" target="_blank" rel="noopener" href="{{ $mainMapLink }}">Lihat Peta Lokasi</a></div>@endif
  </div>
</section>

<!-- GALERI -->
@if(!empty($gallery))
<section id="galeri" data-scene="5">
  <div class="rv"><div class="sec-kicker">Momen Indah</div><div class="sec-title serif">Galeri</div></div>
  <div class="g-grid rv">
    @foreach(array_slice($gallery, 0, 8) as $img)<img src="{{ $img }}" alt="Galeri {{ $brideNick }} & {{ $groomNick }}" loading="lazy">@endforeach
  </div>
</section>
@endif

<!-- KISAH -->
@if(!empty($stories) && count($stories))
<section id="kisah" data-scene="6">
  <div class="rv"><div class="sec-kicker">Perjalanan Kami</div><div class="sec-title serif">Kisah Cinta</div></div>
  <div class="tl">
    @foreach($stories as $st)
    <div class="tl-item rv">@if(!empty($st->tahun))<div style="font-size:11px;letter-spacing:.25em;opacity:.55;margin-bottom:6px">{{ $st->tahun }}</div>@endif
<h4>{{ $st->judul ?? 'Cerita' }}</h4><p>{{ $st->isi ?? '' }}</p></div>
    @endforeach
  </div>
</section>
@endif

<!-- RSVP -->
<section id="rsvp" data-scene="7">
  <div class="rv"><div class="sec-kicker">Konfirmasi</div><div class="sec-title serif">RSVP</div></div>
  <form id="rsvpForm" onsubmit="return sendRSVP(event)">
    <div class="field"><label>Nama Lengkap</label><input required id="rsvpNama" placeholder="Nama Anda" autocomplete="name"></div>
    <div class="field"><label>Konfirmasi Kehadiran</label>
      <div class="att-toggle">
        <button type="button" data-att="hadir" onclick="setAtt('hadir')">Hadir</button>
        <button type="button" data-att="tidak" onclick="setAtt('tidak')">Berhalangan</button>
      </div>
    </div>
    <div class="field" id="alasanWrap" style="display:none"><label>Alasan</label><textarea id="rsvpAlasan" rows="2" placeholder="Alasan berhalangan..."></textarea></div>
    <div class="field"><label>Jumlah Tamu</label><input type="number" id="rsvpCount" min="1" max="20" value="1"></div>
    <button class="btn" type="submit" id="rsvpBtn">Kirim Konfirmasi</button>
  </form>
</section>

<!-- UCAPAN -->
@if(mcGet('guest_qr_enabled') && !empty($guestQrCode))
<!-- QR CHECK-IN (opsional, tampil bila diaktifkan di admin) -->
<section id="qrcheckin" data-scene="8">
<div class="rv"><div class="sec-kicker">Kehadiran</div><div class="sec-title dekor">QR Check-in</div></div>
<div class="rv" style="max-width:340px;margin:0 auto;text-align:center">
<div id="qrBox" style="display:inline-block;padding:14px;background:#fff;border-radius:16px;box-shadow:0 8px 24px rgba(0,0,0,.12)"></div>
<p style="font-size:13px;opacity:.75;margin-top:14px;line-height:1.7">Tunjukkan QR code ini kepada panitia<br>saat kedatangan.<br>Nama: <b>{{ $guestName }}</b></p>
</div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>if(window.QRCode){var qb=document.getElementById('qrBox');new QRCode(qb,{text:"{{ $guestQrCode }}",width:200,height:200,correctLevel:QRCode.CorrectLevel.M});if(!document.getElementById('qrDownloadBtn')){var db=document.createElement('button');db.id='qrDownloadBtn';db.type='button';db.textContent='Unduh QR';db.style.cssText='display:block;width:100%;margin-top:14px;padding:12px;border:none;border-radius:999px;background:#775a19;color:#fff;font-weight:700;font-size:14px;cursor:pointer';db.onclick=function(){var c=qb.querySelector('canvas');var im=qb.querySelector('img');var u=c?c.toDataURL('image/png'):(im?im.src:'');if(!u)return;var a=document.createElement('a');a.href=u;a.download='QR-Undangan.png';a.click();};qb.parentNode.insertBefore(db,qb.nextSibling);}}</script>
@endif
<section id="ucapan" data-scene="8">
  <div class="rv"><div class="sec-kicker">Doa &amp; Harapan</div><div class="sec-title serif">Ucapan</div></div>
  <div id="wishList" class="rv"></div>
  <form id="wishForm" class="rv" onsubmit="return sendWish(event)" style="margin-top:22px">
    <div class="field"><label>Nama</label><input required name="name" placeholder="Nama Anda"></div>
    <div class="field"><label>Ucapan</label><textarea required name="message" rows="3" placeholder="Tulis doa terbaik Anda..."></textarea></div>
    <button class="btn-line" type="submit">Kirim Ucapan</button>
  </form>
</section>

<!-- GIFT -->
@if(!empty($accounts) || mcGet('gift_address'))
<section id="gift" data-scene="9">
  <div class="rv"><div class="sec-kicker">Tanda Kasih</div><div class="sec-title serif">Hadiah</div></div>
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
  <div class="card bank rv">
    <div class="bk">{{ $a['bank'] }}</div>
    @if(!empty($a['logo']))<img src="{{ $a['logo'] }}" alt="Logo bank" style="height:36px;max-width:150px;object-fit:contain;margin:10px auto 0;display:block">@endif
    <div class="no">{{ $a['no'] }}</div>
    <div class="an">a.n. {{ $a['an'] }}</div>
    <button class="btn-line copy" onclick="copyNo('{{ $a['no'] }}')">Salin Nomor</button>
  </div>
  @endforeach
  @if(mcGet('gift_address'))<div class="card bank rv"><div class="bk">Kirim Hadiah Fisik</div><p style="font-size:14px;color:var(--tinta-dim);margin-top:10px;line-height:1.7">{{ mcGet('gift_address') }}</p>@if($giftRecipient)<p style="font-size:13px;opacity:.75;margin-top:8px">Penerima: {{ $giftRecipient }}</p>@endif
    @if(mcGet('gift_maps_link'))<div style="margin-top:12px"><a href="{{ mcGet('gift_maps_link') }}" target="_blank" style="display:inline-block;padding:10px 26px;border:1px solid currentColor;color:inherit;border-radius:999px;font-size:11px;letter-spacing:.24em;text-transform:uppercase;text-decoration:none;opacity:.85">Lihat Peta</a></div>@endif</div>@endif
</section>
@endif

@if(mcGet('live_stream_link'))
@php
  $liveUrl = mcGet('live_stream_link');
  $liveEmbed = $liveUrl;
  if (preg_match('~(?:youtube\.com/(?:watch\?v=|live/|shorts/|embed/)|youtu\.be/)([\w-]{6,})~', (string)$liveUrl, $lm)) { $liveEmbed = 'https://www.youtube-nocookie.com/embed/' . $lm[1]; }
  $liveId = $lm[1] ?? null;
  $liveThumb = $liveId ? 'https://i.ytimg.com/vi/' . $liveId . '/hqdefault.jpg' : '';
@endphp
<section id="live">
<div class="rv"><div class="sec-kicker">Live Streaming</div><div class="sec-title">Saksikan Langsung</div></div>
<div class="rv" style="max-width:430px;margin:16px auto 0;border-radius:16px;overflow:hidden;border:1px solid rgba(201,164,92,.55);box-shadow:0 18px 40px rgba(0,0,0,.25)">
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
<div class="rv" style="margin-top:16px"><a href="{{ $liveUrl }}" target="_blank" rel="noopener" style="display:inline-block;padding:11px 28px;border-radius:999px;background:linear-gradient(105deg,#b8934a,#e6c887 55%,#c9a45c);color:#241a08;font-size:11px;font-weight:700;letter-spacing:.22em;text-decoration:none">BUKA LIVE STREAMING</a></div>
</section>
@endif
<script>
function playLive(btn, id){
  var wrap = btn.parentNode;
  wrap.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0" style="position:absolute;inset:0;width:100%;height:100%;border:0" allow="accelerometer; autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen referrerpolicy="strict-origin-when-cross-origin" title="Live Streaming"></iframe>';
}
</script>
<footer data-scene="10">
  <div class="rv"><div class="hand">Terima Kasih</div>
  <p style="color:var(--tinta-dim);font-size:14px;line-height:1.8;max-width:380px;margin:14px auto">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>
  <div class="serif" style="font-size:22px;color:var(--aksen-dk)">{{ $brideNick }} &amp; {{ $groomNick }}</div></div>
</footer>
</div>

<button id="musBtn" onclick="toggleMus()" style="display:none" aria-label="Putar musik">&#9834;</button>


<div class="lb" id="lb"><span class="lb-x" onclick="closeLb()">&times;</span><span class="lb-prev" onclick="stepLb(-1)">&#10094;</span><img id="lbImg" src="" alt="Foto"><span class="lb-next" onclick="stepLb(1)">&#10095;</span><span class="lb-count" id="lbCount"></span></div>
<nav class="fmenu" id="fmenu" aria-label="Menu undangan"></nav>

<script>
const INV_ID = {{ (int)$invId }};
function openInv(){document.getElementById('cover').classList.add('open');document.body.style.overflow='';const m=document.getElementById('mus');if(m)m.play().catch(()=>{});}
document.body.style.overflow='hidden';
// countdown
/* countdown diganti modul bawaan (resepsi+timezone) */
// reveal
const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('on');io.unobserve(e.target)}}),{threshold:.12});
document.querySelectorAll('.rv').forEach(el=>io.observe(el));
// kelopak bunga berjatuhan
const cv=document.getElementById('petals'),cx=cv.getContext('2d');let P=[];
function rs(){cv.width=innerWidth;cv.height=innerHeight}rs();addEventListener('resize',rs);
const PCOLS=['#e88ca0','#f4b8c6','#fdfbf5','#f2c230','#c4abe6'];
for(let i=0;i<44;i++)P.push({x:Math.random()*innerWidth,y:Math.random()*-innerHeight,r:Math.random()*5+3,s:Math.random()*.9+.35,ph:Math.random()*6.28,sw:Math.random()*1.4+.5,rot:Math.random()*6.28,vr:(Math.random()-.5)*.03,c:PCOLS[i%PCOLS.length]});
(function loop(t){cx.clearRect(0,0,cv.width,cv.height);P.forEach(p=>{p.y+=p.s;p.x+=Math.sin(t/1500+p.ph)*p.sw;p.rot+=p.vr;
  if(p.y>cv.height+10){p.y=-10;p.x=Math.random()*cv.width}
  cx.save();cx.translate(p.x,p.y);cx.rotate(p.rot);cx.globalAlpha=.8;cx.fillStyle=p.c;
  cx.beginPath();cx.ellipse(0,0,p.r,p.r*.55,0,0,6.29);cx.fill();cx.restore()});requestAnimationFrame(loop)})(0);
// kupu-kupu
const fauna=document.getElementById('fauna');
const BCOLS=[['#f2a03d','#f7c873'],['#b79ce0','#d9c8f2'],['#e88ca0','#f4b8c6'],['#7fb8e8','#b8d8f2']];
const flies=[];
for(let i=0;i<7;i++){
  const c=BCOLS[i%BCOLS.length];
  const el=document.createElement('div');el.className='bfly';
  el.innerHTML=`<span class="wing l" style="background:linear-gradient(135deg,${c[0]},${c[1]})"></span><span class="wing r" style="background:linear-gradient(225deg,${c[0]},${c[1]})"></span><span class="bd"></span>`;
  fauna.appendChild(el);
  flies.push({el,x:Math.random()*innerWidth,base:innerHeight*(0.08+Math.random()*0.5),t:Math.random()*1000,vx:.5+Math.random()*.9,amp:20+Math.random()*36,sp:.008+Math.random()*.01,s:.7+Math.random()*.7});
  el.style.transform=`scale(${flies[i].s})`;
}
(function flyloop(){
  flies.forEach(f=>{f.t+=1;f.x+=f.vx;if(f.x>innerWidth+50){f.x=-50;f.base=innerHeight*(0.08+Math.random()*0.5)}
    const y=f.base+Math.sin(f.t*f.sp*6)*f.amp;
    f.el.style.left=f.x+'px';f.el.style.top=y+'px';});
  requestAnimationFrame(flyloop);
})();
// rsvp
async function sendRSVP(e){e.preventDefault();const f=e.target;
  const r=await fetch('/api/rsvp?inv_id='+INV_ID,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name:f.name.value,status:f.status.value,guest_count:f.guest_count.value})});
  alert(r.ok?'Terima kasih atas konfirmasinya!':'Gagal mengirim, coba lagi.');f.reset();return false;}
// wishes
function wishTime(ts){if(!ts)return '';var s=String(ts).replace(' ','T');if(s.indexOf('Z')<0&&s.indexOf('+')<0)s+='+07:00';var d=new Date(s),diff=Math.floor((Date.now()-d.getTime())/1000);if(isNaN(diff)||diff<0||diff<60)return 'Baru saja';if(diff<3600)return Math.floor(diff/60)+' menit yang lalu';if(diff<86400)return Math.floor(diff/3600)+' jam yang lalu';return Math.floor(diff/86400)+' hari yang lalu';}
async function loadWishes(){try{const r=await fetch('/api/ucapan?inv_id='+INV_ID);const d=await r.json();
  wishList.innerHTML=(d.data||d||[]).map(w=>`<div class="wish"><b>${esc(w.nama||w.name)}</b><span class="st">${esc(w.status||'')}</span><span class="wtime">${esc(wishTime(w.created_at||w.tanggal||''))}</span><p>${esc(w.pesan||w.message||w.ucapan||'')}</p></div>`).join('')||'<p style="color:var(--tinta-dim)">Belum ada ucapan.</p>';}catch(e){}}
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
async function sendWish(e){e.preventDefault();const f=e.target;
  const r=await fetch('/api/ucapan?inv_id='+INV_ID,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name:f.name.value,message:f.message.value})});
  if(r.ok){f.reset();loadWishes();}else alert('Gagal mengirim ucapan.');return false;}
loadWishes();
function copyNo(t){navigator.clipboard.writeText(t).then(()=>alert('Nomor tersalin!'));}
function toggleMus(){const m=document.getElementById('mus');if(m.paused){m.play();musBtn.style.opacity=1}else{m.pause();musBtn.style.opacity=.5}}
</script>

<script>
/* === Modul fitur bawaan default: musik API, lightbox, menu, countdown resepsi, RSVP & ucapan === */
var NL = String.fromCharCode(10);
(function(){
  var tz = "{{ mcGet('reception_timezone','WIB') }}";
  var off = tz === 'WIT' ? '+09:00' : (tz === 'WITA' ? '+08:00' : '+07:00');
  var target = new Date("{{ $rDate }}T{{ $rTimeS }}:00" + off).getTime();
  function pad(n){ return String(n).padStart(2,'0'); }
  function tick(){
    var d = target - Date.now(); if (d < 0) d = 0;
    var cdD=document.getElementById('cdD'),cdH=document.getElementById('cdH'),
        cdM=document.getElementById('cdM'),cdS=document.getElementById('cdS');
    if(!cdD) return;
    cdD.textContent = Math.floor(d/864e5);
    cdH.textContent = pad(Math.floor(d/36e5)%24);
    cdM.textContent = pad(Math.floor(d/6e4)%60);
    cdS.textContent = pad(Math.floor(d/1e3)%60);
  }
  tick(); setInterval(tick, 1000);
})();

var bgMus = null, musPlaying = false, musCfg = { file_path:'', volume:.5, autoplay:true };
async function initMus(){
  var btn = document.getElementById('musBtn');
  try{
    var r = await fetch('/api/music?inv_id=' + INV_ID);
    var d = await r.json();
    if (d.success && d.music && d.music.file_path) { musCfg = d.music; }
    else { if(btn) btn.style.display='none'; return; }
  }catch(e){ if(btn) btn.style.display='none'; return; }
  bgMus = new Audio(musCfg.file_path); bgMus.loop = true; bgMus.volume = 0;
  window.bgMusic = bgMus;
  if(btn) btn.style.display = 'flex';
}
function fadeMus(a,t){ a.volume = 0; var st = setInterval(function(){
  a.volume = Math.min(t, a.volume + t/20); if(a.volume >= t) clearInterval(st); }, 25); }
function startMus(){
  if(!bgMus) return;
  bgMus.play().then(function(){
    musPlaying = true; window.musicPlaying = true;
    var b = document.getElementById('musBtn'); if(b) b.classList.add('playing');
    fadeMus(bgMus, parseFloat(musCfg.volume) || .5);
  }).catch(function(){});
}
function toggleMus(){
  var b = document.getElementById('musBtn');
  if(!bgMus) return;
  if(!musPlaying){ startMus(); }
  else { bgMus.pause(); musPlaying = false; window.musicPlaying = false; if(b) b.classList.remove('playing'); }
}
(function(){
  document.body.classList.add('locked');
  window.openInv = function(){
    document.body.classList.remove('locked');
    document.getElementById('cover').classList.add('open');
    document.body.style.overflow = '';
    if(musCfg.autoplay && bgMus) startMus();
  };
  initMus();
})();

var lbList=[], lbIdx=0;
function lbCollect(){
  lbList = Array.prototype.slice.call(document.querySelectorAll('.g-grid img, .photo, .planet, .frame img, .arch img'))
    .filter(function(im){ return im.tagName==='IMG' && im.getAttribute('src'); });
}
function openLb(src){
  if(!src) return;
  lbCollect();
  var i = lbList.findIndex(function(im){ return im.src === src; });
  lbIdx = i < 0 ? 0 : i;
  showLb();
}
var lbDir = 1;
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
  [1, -1].forEach(function(d){
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
document.querySelectorAll('.g-grid img, .photo, .planet, .frame img, .arch img').forEach(function(im){
  if(im.tagName !== 'IMG' || !im.getAttribute('src')) return;
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
      swiped = true;
      stepLb(dx < 0 ? 1 : -1);
    }
  }, {passive:true});
})();

var __att = '';
function setAtt(v){
  __att = v;
  document.querySelectorAll('.att-toggle button').forEach(function(b){
    b.classList.toggle('on', b.dataset.att === v);
  });
  document.getElementById('alasanWrap').style.display = (v === 'tidak') ? 'block' : 'none';
}
async function sendRSVP(e){
  e.preventDefault();
  var nama = document.getElementById('rsvpNama').value.trim();
  var jumlah = parseInt(document.getElementById('rsvpCount').value, 10) || 1;
  var alasan = (__att === 'tidak') ? document.getElementById('rsvpAlasan').value.trim() : '';
  if(!nama){ alert('Mohon isi nama terlebih dahulu.'); return false; }
  if(!__att){ alert('Mohon pilih status kehadiran.'); return false; }
  var btn = document.getElementById('rsvpBtn'); btn.disabled = true;
  try{
    var r = await fetch('/api/rsvp', { method:'POST', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ invitation_id: INV_ID, nama: nama, jumlah_tamu: jumlah, status: __att, alasan: alasan }) });
    var j = await r.json();
    if(!j.success) throw new Error(j.message || 'Gagal menyimpan');
    var msg = 'Halo, saya *' + nama + '* ingin mengkonfirmasi kehadiran.' + NL + NL
      + 'Status : ' + (__att === 'hadir' ? 'HADIR ✅' : 'TIDAK HADIR ❌')
      + (__att === 'hadir' ? NL + 'Jumlah Tamu : ' + jumlah + ' orang' : '')
      + (alasan ? NL + 'Alasan : ' + alasan : '')
      + NL + NL + 'Terima kasih atas undangannya 🙏';
    var waNumber = "{{ mcGet('rsvp_whatsapp','6288210841990') }}".replace(/[^0-9]/g, '');
    window.open('https://wa.me/' + waNumber + '?text=' + encodeURIComponent(msg), '_blank');
    document.getElementById('rsvpNama').value = '';
    document.getElementById('rsvpAlasan').value = '';
    document.getElementById('rsvpCount').value = '1';
    setAtt('');
  }catch(err){ alert('Gagal: ' + err.message); }
  btn.disabled = false;
  return false;
}

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
    }).join('') || '<p class="wish-empty">Belum ada ucapan.</p>';
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

(function(){
  var SECS = [
    ['mempelai','<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7.5-4.7-9.5-9C1 8.5 3 5 6.5 5c2 0 3.5 1 4.5 2.5C12 6 13.5 5 15.5 5 19 5 21 8.5 20.5 12c-2 4.3-8.5 9-8.5 9z"/></svg>','Mempelai'],
    ['quotes','<svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 5.5C4.9 6.7 4 8.6 4 10.9V18h6.4v-6.4H7.2c0-1.9.8-3.2 2.3-4.2l-3.1-1.8zm9 0c-1.7 1.2-2.6 3.1-2.6 5.4V18h6.4v-6.4h-3.2c0-1.9.8-3.2 2.3-4.2l-3.1-1.8z"/></svg>','Quotes'],
    ['acara','<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path stroke-linecap="round" d="M8 3v4M16 3v4M3 10h18"/></svg>','Acara'],
    ['galeri','<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.6"/><path stroke-linecap="round" stroke-linejoin="round" d="m5 19 5.5-5.5 3 3L19 11l2 2"/></svg>','Galeri'],
    ['kisah','<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.5C10 4.8 7 4.3 4.5 4.5v13c2.5-.2 5.5.3 7.5 2 2-1.7 5-2.2 7.5-2v-13C17 4.3 14 4.8 12 6.5z"/><path stroke-linecap="round" d="M12 6.5v13"/></svg>','Kisah'],
    ['rsvp','<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>','RSVP'],
    ['qrcheckin','<svg fill=\"none\" stroke=\"currentColor\" stroke-width=\"1.8\" viewBox=\"0 0 24 24\"><rect x=\"3.5\" y=\"3.5\" width=\"6.5\" height=\"6.5\" rx=\"1.5\"/><rect x=\"14\" y=\"3.5\" width=\"6.5\" height=\"6.5\" rx=\"1.5\"/><rect x=\"3.5\" y=\"14\" width=\"6.5\" height=\"6.5\" rx=\"1.5\"/><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M14 14h3.5v3.5H14zM20.5 14v.5M14 20.5h.5M17.5 17.5h3v3h-3z\"/></svg>','QR'],
    ['ucapan','<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>','Ucapan'],
    ['gift','<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8v13H3V8M1 3h22v5H1zM12 8v13M12 8H7.5a2.5 2.5 0 0 1 0-5C11 3 12 8 12 8zm0 0h4.5a2.5 2.5 0 0 0 0-5C13 3 12 8 12 8z"/></svg>','Gift'],
    ['live','<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path fill="currentColor" stroke="none" d="M10 8.3v7.4c0 .6.7 1 1.2.7l6-3.7c.5-.3.5-1 0-1.3l-6-3.7c-.5-.3-1.2.1-1.2.6z"/></svg>','Live']
  ];
  var nav = document.getElementById('fmenu');
  var items = [];
  SECS.forEach(function(s){
    var el = document.getElementById(s[0]); if(!el) return;
    var a = document.createElement('a'); a.href = '#' + s[0];
    a.innerHTML = '<i>' + s[1] + '</i><span>' + s[2] + '</span>';
    a.addEventListener('click', function(e){ e.preventDefault(); el.scrollIntoView({behavior:'smooth'}); });
    nav.appendChild(a); items.push([a, el]);
  });
  if(!items.length){ nav.style.display = 'none'; return; }
  var io = new IntersectionObserver(function(es){
    es.forEach(function(en){
      if(en.isIntersecting){
        items.forEach(function(it){ it[0].classList.toggle('active', it[1] === en.target); });
      }
    });
  }, { rootMargin:'-40% 0px -50% 0px' });
  items.forEach(function(it){ io.observe(it[1]); });
  window.addEventListener('scroll', function(){
    var h = document.documentElement;
    var max = h.scrollHeight - h.clientHeight;
    var p = max > 0 ? (h.scrollTop / max * 100) : 0;
    document.getElementById('pbarFill').style.width = p + '%';
  }, { passive:true });
})();

/* === Gunung Berkabut: scene berganti saat scroll, gunung parallax, kabut === */
(function(){
  // 1) background berganti mengikuti section yang sedang dibaca
  var sceneIO = new IntersectionObserver(function(es){
    es.forEach(function(en){
      if(en.isIntersecting && en.target.id !== 'cover'){
        document.body.dataset.scene = en.target.dataset.scene;
      }
    });
  }, { rootMargin:'-45% 0px -45% 0px' });
  document.querySelectorAll('[data-scene]').forEach(function(el){ sceneIO.observe(el); });

  // 2) 3 lapis gunung bergerak dengan kecepatan beda saat scroll
  var ridges = [document.getElementById('ridge1'), document.getElementById('ridge2'), document.getElementById('ridge3')];
  var speeds = [0.12, 0.28, 0.48];
  var ticking = false;
  function par(){
    var y = window.scrollY || window.pageYOffset || 0;
    ridges.forEach(function(r, i){
      if(r) r.style.transform = 'translateY(' + (y * speeds[i]) + 'px)';
    });
    ticking = false;
  }
  window.addEventListener('scroll', function(){
    if(!ticking){ ticking = true; requestAnimationFrame(par); }
  }, { passive:true });
  par();

  // 3) partikel kabut: titik putih lembut melayang horizontal
  var mc = document.getElementById('mist'), mctx = mc.getContext('2d'), MP = [];
  function mrs(){ mc.width = window.innerWidth; mc.height = window.innerHeight; }
  mrs(); window.addEventListener('resize', mrs);
  for(var i=0;i<26;i++) MP.push({
    x: Math.random()*window.innerWidth, y: Math.random()*window.innerHeight,
    r: Math.random()*3+1.5, s: Math.random()*.5+.15,
    a: Math.random()*.25+.08, ph: Math.random()*6.28
  });
  (function mloop(t){
    mctx.clearRect(0,0,mc.width,mc.height);
    MP.forEach(function(p){
      p.x += p.s;
      if(p.x > mc.width + 10){ p.x = -10; p.y = Math.random()*mc.height; }
      var yy = p.y + Math.sin(t/2200 + p.ph)*8;
      mctx.globalAlpha = p.a; mctx.fillStyle = '#ffffff';
      mctx.beginPath(); mctx.arc(p.x, yy, p.r, 0, 6.29); mctx.fill();
    });
    mctx.globalAlpha = 1;
    requestAnimationFrame(mloop);
  })(0);
})();
</script>

</body>
</html>
