<?php
/* ============================================================
   TEMA PREMIUM: TAMAN JANUR (Garden)
   Dipilih via settings: theme = taman-janur
   Animasi: janur bergoyang, kupu-kupu, burung terbang, bunga
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
$groomPhoto = mcGet('groom_photo'); $bridePhoto = mcGet('bride_photo');
$groomInitial = mb_substr($groomNick, 0, 1); $brideInitial = mb_substr($brideNick, 0, 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $brideNick }} &amp; {{ $groomNick }} — Origami Dreams</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--kertas:#f7f2e8;--kertas2:#fdfaf2;--coral:#e86a8a;--coral-dk:#d14f74;--oren:#f2a03d;--oren-dk:#e07f2e;
--tinta:#4a3f35;--tinta-dim:rgba(74,63,53,.62);--panel:#2b2b3a}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{font-family:'Jost',sans-serif;font-weight:300;color:var(--tinta);overflow-x:hidden;min-height:100vh;
background-color:var(--kertas);
background-image:repeating-linear-gradient(45deg,transparent 0 46px,rgba(190,150,100,.07) 46px 47px),repeating-linear-gradient(-45deg,transparent 0 46px,rgba(190,150,100,.07) 46px 47px)}
#petals{position:fixed;inset:0;z-index:3;pointer-events:none}
#sky3d{position:fixed;inset:0;z-index:1;pointer-events:none;perspective:700px;overflow:hidden}
.wrap{position:relative;z-index:2;max-width:520px;margin:0 auto}
/* ---------- 3D origami crane ---------- */
.cfly{position:absolute;left:0;animation:flyAcross linear infinite;will-change:transform}
@keyframes flyAcross{from{transform:translateX(-24vw)}to{transform:translateX(122vw)}}
.crane{transform-style:preserve-3d}
.cbob{position:relative;width:96px;height:52px;transform-style:preserve-3d;animation:bob 3.4s ease-in-out infinite}
@keyframes bob{0%,100%{transform:translateY(7px) rotateZ(-3deg) rotateX(10deg)}50%{transform:translateY(-11px) rotateZ(3deg) rotateX(10deg)}}
.wing{position:absolute;top:6px;width:46px;height:40px}
.wl{left:2px;background:linear-gradient(135deg,var(--c1),var(--c2));clip-path:polygon(100% 50%,0 0,0 100%);transform-origin:100% 50%;animation:flapL .85s ease-in-out infinite}
.wr{right:2px;background:linear-gradient(225deg,var(--c1),var(--c2));clip-path:polygon(0 50%,100% 0,100% 100%);transform-origin:0% 50%;animation:flapR .85s ease-in-out infinite}
@keyframes flapL{0%,100%{transform:rotateX(58deg)}50%{transform:rotateX(-58deg)}}
@keyframes flapR{0%,100%{transform:rotateX(-58deg)}50%{transform:rotateX(58deg)}}
.cbody{position:absolute;left:50%;top:50%;width:12px;height:40px;transform:translate(-50%,-50%);background:linear-gradient(180deg,var(--c2),var(--c1));clip-path:polygon(50% 0,100% 50%,50% 100%,0 50%)}
/* ---------- paper plane ---------- */
.plane{position:absolute;width:74px;height:30px;background:linear-gradient(135deg,#ffffff,#f2e3c8);clip-path:polygon(0 50%,100% 0,100% 16%,58% 50%,100% 84%,100% 100%);filter:drop-shadow(3px 5px 3px rgba(190,150,100,.35));animation:planeFly linear infinite}
@keyframes planeFly{0%{transform:translate(-12vw,24vh) rotateY(-14deg)}50%{transform:translate(50vw,10vh) rotateY(16deg)}100%{transform:translate(118vw,28vh) rotateY(-14deg)}}
/* ---------- origami heart (love story icon) ---------- */
.oheart{position:relative;width:56px;height:50px;transform-style:preserve-3d;transform:rotateX(14deg)}
.oh-l,.oh-r{position:absolute;top:0;width:28px;height:50px}
.oh-l{left:0;background:linear-gradient(160deg,var(--coral),var(--coral-dk));clip-path:polygon(100% 0,100% 100%,0 38%)}
.oh-r{right:0;background:linear-gradient(200deg,#f490a8,var(--coral));clip-path:polygon(0 0,0 100%,100% 38%)}
.oheart.float{animation:heartFloat 4.5s ease-in-out infinite}
@keyframes heartFloat{0%,100%{transform:rotateX(14deg) translateY(0)}50%{transform:rotateX(24deg) translateY(-12px)}}
/* ---------- layered paper mountains ---------- */
.mts{position:relative;height:230px;perspective:600px;overflow:visible}
.mt{position:absolute;bottom:0}
.m1{left:-4%;width:52%;height:210px;background:linear-gradient(180deg,#f3e2c2,#ecd3ac);clip-path:polygon(50% 0,100% 100%,0 100%);filter:drop-shadow(7px 9px 0 rgba(190,150,100,.30))}
.m2{left:30%;width:56%;height:160px;background:linear-gradient(180deg,#f8ecd4,#f1ddba);clip-path:polygon(50% 0,100% 100%,0 100%);filter:drop-shadow(7px 9px 0 rgba(190,150,100,.26))}
.m3{right:-4%;width:46%;height:185px;background:linear-gradient(180deg,#f6e8cf,#eed7b0);clip-path:polygon(50% 0,100% 100%,0 100%);filter:drop-shadow(-7px 9px 0 rgba(190,150,100,.26))}
.mt::after{content:'';position:absolute;inset:0;background:linear-gradient(105deg,transparent 49.4%,rgba(190,150,100,.25) 49.4% 50.6%,transparent 50.6%)}
/* ---------- envelope cover ---------- */
#cover{position:fixed;inset:0;z-index:100;display:flex;align-items:center;justify-content:center;transition:opacity .9s,visibility .9s;overflow-y:auto;background-color:var(--kertas);
background-image:repeating-linear-gradient(45deg,transparent 0 46px,rgba(190,150,100,.07) 46px 47px),repeating-linear-gradient(-45deg,transparent 0 46px,rgba(190,150,100,.07) 46px 47px)}
#cover.open{opacity:0;visibility:hidden}
.cover-inner{text-align:center;padding:34px 24px 48px;width:100%;max-width:520px;position:relative}
.envelope{position:relative;width:min(78vw,330px);height:220px;margin:0 auto 8px;perspective:800px}
.env-body{position:absolute;inset:0;background:linear-gradient(180deg,#fdfaf2,#f5ecd9);border:1px solid rgba(190,150,100,.4);border-radius:10px;box-shadow:0 24px 50px rgba(190,150,100,.35)}
.env-body::before{content:'';position:absolute;inset:0;border-radius:10px;background:repeating-linear-gradient(45deg,transparent 0 30px,rgba(190,150,100,.06) 30px 31px)}
.env-flap{position:absolute;left:0;right:0;top:0;height:118px;background:linear-gradient(180deg,#f8f0dd,#f0e2c4);clip-path:polygon(0 0,100% 0,50% 100%);border-radius:10px 10px 0 0;transform-origin:top center;transform:rotateX(18deg);box-shadow:0 8px 14px rgba(190,150,100,.25)}
.env-seal{position:absolute;left:50%;top:96px;transform:translateX(-50%);filter:drop-shadow(0 6px 8px rgba(209,79,116,.35))}
.kicker{font-size:11px;letter-spacing:.5em;text-transform:uppercase;color:var(--oren-dk);margin:16px 0 4px;font-weight:600}
.cover-names{font-family:'Cormorant Garamond',serif;font-weight:600;font-style:italic;font-size:clamp(44px,12vw,64px);line-height:1.15;color:var(--tinta);margin:6px 0}
.cover-names em{font-style:normal;color:var(--coral)}
.cover-date{font-family:'Cormorant Garamond',serif;font-size:20px;letter-spacing:.24em;color:var(--tinta-dim)}
.kepada{margin-top:18px;font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--tinta)}
.guest-box{margin:10px auto 0;max-width:330px;background:#fffdf8;border:1px solid rgba(232,106,138,.5);border-radius:12px;padding:13px 18px;box-shadow:0 10px 26px rgba(190,150,100,.25)}
.guest-box b{font-family:'Cormorant Garamond',serif;font-size:21px;color:var(--coral-dk);font-weight:600}
.btn{display:inline-flex;align-items:center;gap:10px;margin-top:18px;padding:14px 42px;background:linear-gradient(135deg,var(--coral),var(--oren));border:none;border-radius:999px;color:#fff;font-size:12px;letter-spacing:.22em;text-transform:uppercase;cursor:pointer;font-family:'Jost';font-weight:600;transition:.35s;box-shadow:0 10px 26px rgba(232,106,138,.4)}
.btn:hover{transform:translateY(-2px) scale(1.02)}
.btn-line{display:inline-block;padding:12px 32px;border:1px solid var(--coral);color:var(--coral-dk);font-size:11px;letter-spacing:.26em;text-transform:uppercase;cursor:pointer;background:transparent;border-radius:999px;transition:.3s;text-decoration:none;font-family:'Jost';font-weight:500}
.btn-line:hover{background:rgba(232,106,138,.1)}
/* ---------- sections ---------- */
section{padding:64px 26px;text-align:center;position:relative}
.sec-kicker{font-size:10px;letter-spacing:.44em;text-transform:uppercase;color:var(--oren-dk);margin-bottom:10px;font-weight:600}
.sec-title{font-family:'Cormorant Garamond',serif;font-size:36px;font-weight:600;font-style:italic;color:var(--tinta)}
.fold-div{display:flex;align-items:center;justify-content:center;gap:10px;margin:14px auto;max-width:260px}
.fold-div::before,.fold-div::after{content:'';height:1px;flex:1;background:linear-gradient(90deg,transparent,rgba(190,150,100,.6))}
.fold-div::after{background:linear-gradient(90deg,rgba(190,150,100,.6),transparent)}
.fold-div i{font-style:normal;color:var(--coral);font-size:16px}
.card{background:var(--kertas2);border:1px solid rgba(190,150,100,.35);border-radius:16px;padding:34px 24px;margin:0 auto 18px;max-width:400px;box-shadow:0 14px 34px rgba(190,150,100,.22)}
#hero{min-height:96vh;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden}
.hero-hand{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:26px;color:var(--oren-dk)}
.hero-names{font-family:'Cormorant Garamond',serif;font-weight:600;font-style:italic;font-size:clamp(46px,13vw,66px);line-height:1.15;margin:10px 0;color:var(--tinta)}
.hero-names em{font-style:normal;color:var(--coral)}
.hero-date{letter-spacing:.3em;font-size:13px;color:var(--tinta-dim);text-transform:uppercase}
.hero-heart{display:flex;justify-content:center;margin:18px 0 4px}
.cd-grid{display:flex;justify-content:center;gap:10px;margin-top:26px}
.cd{width:72px;padding:16px 0;background:var(--kertas2);border:1px solid rgba(190,150,100,.4);border-radius:14px;box-shadow:0 8px 20px rgba(190,150,100,.2)}
.cd b{display:block;font-family:'Cormorant Garamond',serif;font-size:32px;color:var(--coral-dk)}
.cd span{font-size:9px;letter-spacing:.26em;text-transform:uppercase;color:var(--tinta-dim)}
.photo{width:150px;height:150px;border-radius:50%;object-fit:cover;margin:0 auto 16px;border:4px solid #fff;box-shadow:0 12px 30px rgba(190,150,100,.35);display:block}
.photo-fallback{width:150px;height:150px;border-radius:50%;margin:0 auto 16px;background:linear-gradient(135deg,#f6b8c8,#e86a8a);border:4px solid #fff;box-shadow:0 12px 30px rgba(190,150,100,.35);display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-style:italic;font-size:58px;color:#fff}
.couple-card h3{font-family:'Cormorant Garamond',serif;font-size:34px;font-style:italic;color:var(--coral-dk);font-weight:600}
.couple-card .full{font-family:'Cormorant Garamond',serif;font-size:19px;margin:6px 0}
.couple-card p{font-size:14px;color:var(--tinta-dim);line-height:1.7}
.amp{font-family:'Cormorant Garamond',serif;font-size:46px;color:var(--oren);margin:2px 0;font-style:italic}
.event h3{font-family:'Cormorant Garamond',serif;font-size:26px;font-style:italic;color:var(--coral-dk)}
.event .date{font-family:'Cormorant Garamond',serif;font-size:20px;margin:12px 0 4px}
.event .time{letter-spacing:.22em;font-size:12px;color:var(--oren-dk);font-weight:600}
.event .loc{font-size:14px;color:var(--tinta-dim);margin:12px 0 18px;line-height:1.7}
.g-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:24px}
.g-grid img{width:100%;height:190px;object-fit:cover;border-radius:12px;border:4px solid #fff;box-shadow:0 8px 22px rgba(190,150,100,.28);transition:.4s}
.g-grid img:hover{transform:scale(1.03) rotate(.5deg)}
/* ---------- love story timeline dengan ikon hati origami ---------- */
.tl{max-width:400px;margin:26px auto 0;text-align:left;position:relative}
.tl::before{content:'';position:absolute;left:27px;top:8px;bottom:8px;width:2px;background:repeating-linear-gradient(180deg,rgba(190,150,100,.55) 0 8px,transparent 8px 14px)}
.tl-item{position:relative;background:var(--kertas2);border:1px solid rgba(190,150,100,.35);border-radius:14px;padding:18px 18px 18px 76px;margin-bottom:16px;box-shadow:0 8px 22px rgba(190,150,100,.2)}
.tl-item .oheart{position:absolute;left:12px;top:16px;transform:rotateX(14deg) scale(.62);transform-origin:top left}
.tl-item h4{font-family:'Cormorant Garamond',serif;font-size:22px;font-style:italic;color:var(--coral-dk)}
.tl-item p{font-size:14px;color:var(--tinta-dim);line-height:1.7;margin-top:6px}
/* ---------- dark panel untuk RSVP (teks terang modul) ---------- */
.dark-panel{background:var(--panel);border-radius:18px;padding:30px 22px;max-width:430px;margin:0 auto;box-shadow:0 18px 44px rgba(43,43,58,.35)}
.dark-panel .field label{color:#f6b8c8}
.dark-panel .field input,.dark-panel .field select,.dark-panel .field textarea{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.22);color:#fff}
.dark-panel .field input::placeholder{color:rgba(255,255,255,.4)}
.field{margin:0 auto 14px;max-width:380px;text-align:left}
.field label{display:block;font-size:10px;letter-spacing:.32em;text-transform:uppercase;color:var(--oren-dk);margin-bottom:8px;font-weight:600}
.field input,.field select,.field textarea{width:100%;background:var(--kertas2);border:1px solid rgba(190,150,100,.45);border-radius:12px;color:var(--tinta);padding:13px 16px;font-family:'Jost';font-size:15px;outline:none}
.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--coral);box-shadow:0 0 0 3px rgba(232,106,138,.14)}
.wish{background:var(--kertas2);border:1px solid rgba(190,150,100,.35);border-radius:14px;padding:16px 18px;margin:0 auto 12px;max-width:400px;text-align:left;box-shadow:0 6px 18px rgba(190,150,100,.16)}
.wish b{font-family:'Cormorant Garamond',serif;font-style:italic;color:var(--coral-dk);font-size:17px}
.wish .st{font-size:10px;letter-spacing:.2em;color:var(--oren-dk);text-transform:uppercase;margin-left:8px;font-weight:600}
.wish p{font-size:14px;color:var(--tinta-dim);margin-top:6px;line-height:1.6}
.bank{background:var(--kertas2);border:1px dashed rgba(232,106,138,.55);border-radius:14px;padding:24px;margin:0 auto 14px;max-width:380px}
.bank .bk{font-size:11px;letter-spacing:.34em;color:var(--oren-dk);text-transform:uppercase;font-weight:600}
.bank .no{font-family:'Cormorant Garamond',serif;font-size:30px;margin:10px 0 4px;letter-spacing:.04em;color:var(--tinta)}
.bank .an{font-size:14px;color:var(--tinta-dim)}
.copy{margin-top:12px}
footer{padding:56px 26px 110px;text-align:center}
footer .hand{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:40px;color:var(--coral-dk)}
#musBtn{position:fixed;bottom:22px;right:22px;z-index:90;width:52px;height:52px;border-radius:50%;border:none;background:linear-gradient(135deg,var(--coral),var(--oren));color:#fff;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 26px rgba(232,106,138,.45)}

/* === fitur bawaan default: lightbox, menu, progress, musik === */
.pbar{position:fixed;top:0;left:0;right:0;height:3px;z-index:96;background:transparent}
.pbar i{display:block;height:100%;width:0;background:linear-gradient(90deg,#e86a8a,#f2a03d)}
.lb{position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.92);display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:.35s;padding:20px}
.lb.show{opacity:1;visibility:visible}
.lb img{max-width:100%;max-height:86vh;border:2px solid #e86a8a}
.lb-x{position:absolute;top:16px;right:22px;font-size:40px;color:#fff;cursor:pointer;line-height:1;z-index:201}
.fmenu{position:fixed;bottom:14px;left:50%;transform:translateX(-50%);z-index:90;display:flex;gap:2px;background:#2b2b3af2;border:1px solid #e86a8a55;border-radius:999px;padding:7px 9px;backdrop-filter:blur(8px);box-shadow:0 10px 30px rgba(0,0,0,.35);max-width:96vw;overflow-x:auto;scrollbar-width:none}
.fmenu::-webkit-scrollbar{display:none}
.fmenu a{display:flex;flex-direction:column;align-items:center;gap:3px;min-width:50px;padding:6px 7px;border-radius:12px;color:#fff8f0;opacity:.6;text-decoration:none;font-size:9px;letter-spacing:.06em;transition:.25s;font-family:'Jost',sans-serif}
.fmenu a i{font-style:normal;font-size:17px;line-height:1}
.fmenu a.active{opacity:1;background:#e86a8a2e;color:#f2a03d}
#musBtn.playing{outline:2px solid #f2a03d;outline-offset:2px}
.att-toggle{display:flex;gap:10px}
.att-toggle button{flex:1;padding:13px 8px;border:1px solid #e86a8a;background:transparent;color:#fff8f0;font-family:'Jost',sans-serif;font-size:14px;cursor:pointer;transition:.25s;border-radius:10px}
.att-toggle button.on{background:#e86a8a;color:#2b2b3a;font-weight:500}
.wish-empty{text-align:center;opacity:.6;font-size:14px;padding:12px}
body.locked #fmenu,body.locked #musBtn,body.locked .pbar{opacity:0 !important;visibility:hidden !important;pointer-events:none !important}

.rv{opacity:0;transform:translateY(36px);transition:opacity .9s,transform .9s}
.rv.on{opacity:1;transform:none}
</style>
</head>
<body>
<div class="pbar"><i id="pbarFill"></i></div>
<canvas id="petals"></canvas>
<div id="sky3d" aria-hidden="true"></div>
<div class="wrap">

<!-- ================= COVER ================= -->
<div id="cover">
  <div class="cover-inner">
    <div class="envelope rv" style="opacity:1;transform:none">
      <div class="env-body"></div>
      <div class="env-flap"></div>
      <div class="env-seal"><div class="oheart"><div class="oh-l"></div><div class="oh-r"></div></div></div>
    </div>
    <div class="kicker">The Wedding Of</div>
    <div class="cover-names">{{ $brideNick }} <em>&amp;</em> {{ $groomNick }}</div>
    <div class="cover-date">{{ mcDate($wDate) }}</div>
    <div class="kepada">Kepada Yth.<br>Bapak/Ibu/Saudara/i</div>
    <div class="guest-box"><b>{{ $guestName }}</b></div>
    <div><button class="btn" onclick="openInv()">&#9993; &nbsp;Buka Undangan</button></div>
  </div>
</div>

<!-- ================= HERO ================= -->
<section id="hero">
  <div class="rv"><div class="hero-hand">The Wedding Of</div></div>
  <div class="rv"><h1 class="hero-names">{{ $brideNick }} <em>&amp;</em> {{ $groomNick }}</h1></div>
  <div class="rv hero-heart"><div class="oheart float"><div class="oh-l"></div><div class="oh-r"></div></div></div>
  <div class="rv"><div class="hero-date">{{ mcDate($wDate) }}</div>
@if(mcGet('quotes'))
<div class="rv" style="max-width:420px;margin:20px auto 0;padding:0 8px">
<p style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:19px;line-height:1.7;opacity:.85">“{{ mcGet('quotes') }}”</p>
</div>
@endif</div>
  <div class="rv"><div class="mts" style="margin-top:26px"><div class="mt m1"></div><div class="mt m2"></div><div class="mt m3"></div></div></div>
</section>

<!-- COUNTDOWN -->
<section>
  <div class="rv"><div class="sec-kicker">Menuju Hari Bahagia</div><div class="sec-title">Hitung Mundur</div></div>
  <div class="rv"><div class="fold-div"><i>&#10022;</i></div></div>
  <div class="cd-grid rv">
    <div class="cd"><b id="cdD">0</b><span>Hari</span></div>
    <div class="cd"><b id="cdH">0</b><span>Jam</span></div>
    <div class="cd"><b id="cdM">0</b><span>Menit</span></div>
    <div class="cd"><b id="cdS">0</b><span>Detik</span></div>
  </div>
</section>

<!-- MEMPELAI -->
<section id="mempelai">
  <div class="rv"><div class="sec-kicker">Assalamu'alaikum Wr. Wb.</div><div class="sec-title">Mempelai</div></div>
  <div class="rv"><div class="fold-div"><i>&#10022;</i></div></div>
  <div class="card couple-card rv">
    @if($bridePhoto)<img class="photo" src="{{ $bridePhoto }}" alt="{{ $brideFull }}">@else<div class="photo-fallback">{{ $brideInitial }}</div>@endif
    <h3>{{ $brideNick }}</h3><div class="full">{{ $brideFull }}</div>
    <p>Putri dari<br>{{ mcGet('bride_parents', mcGet('bride_child_of', '')) }}</p>
  </div>
  <div class="amp rv">&amp;</div>
  <div class="card couple-card rv">
    @if($groomPhoto)<img class="photo" src="{{ $groomPhoto }}" alt="{{ $groomFull }}">@else<div class="photo-fallback">{{ $groomInitial }}</div>@endif
    <h3>{{ $groomNick }}</h3><div class="full">{{ $groomFull }}</div>
    <p>Putra dari<br>{{ mcGet('groom_parents', mcGet('groom_child_of', '')) }}</p>
  </div>
</section>

<!-- ACARA -->
<section id="acara">
  <div class="rv"><div class="sec-kicker">Waktu &amp; Tempat</div><div class="sec-title">Rangkaian Acara</div></div>
  <div class="rv"><div class="fold-div"><i>&#10022;</i></div></div>
  <div class="card event rv">
    <h3>Akad Nikah</h3>
    <div class="date">{{ mcDate($wDate) }}</div>
    <div class="time">{{ $wTime }} — {{ $wTimeE }} {{ mcGet('wedding_timezone','WIB') }}</div>
    <div class="loc">{{ mcGet('wedding_location', 'Kediaman Mempelai') }}</div>
    @if(mcGet('wedding_map_link'))<a class="btn-line" target="_blank" href="{{ mcGet('wedding_map_link') }}">Lihat Peta</a>@endif
  </div>
  <div class="card event rv">
    <h3>Resepsi</h3>
    <div class="date">{{ mcDate($rDate) }}</div>
    <div class="time">{{ $rTimeS }} — {{ $rTimeE }} {{ mcGet('wedding_timezone','WIB') }}</div>
    <div class="loc">{{ mcGet('reception_location', mcGet('wedding_location', 'Kediaman Mempelai')) }}</div>
    @if(mcGet('reception_map_link'))<a class="btn-line" target="_blank" href="{{ mcGet('reception_map_link') }}">Lihat Peta</a>@endif @if(mcGet('live_stream_link'))<a class="btn-line" target="_blank" href="{{ mcGet('live_stream_link') }}" style="margin-left:8px">Live Streaming</a>@endif
  </div>
</section>

<!-- GALERI -->
@if(!empty($gallery))
<section id="galeri">
  <div class="rv"><div class="sec-kicker">Momen Indah</div><div class="sec-title">Galeri</div></div>
  <div class="rv"><div class="fold-div"><i>&#10022;</i></div></div>
  <div class="g-grid rv">
    @foreach(array_slice($gallery, 0, 8) as $img)<img src="{{ $img }}" alt="Galeri {{ $brideNick }} & {{ $groomNick }}" loading="lazy">@endforeach
  </div>
</section>
@endif

<!-- KISAH -->
@if(!empty($stories) && count($stories))
<section id="kisah">
  <div class="rv"><div class="sec-kicker">Perjalanan Kami</div><div class="sec-title">Kisah Cinta</div></div>
  <div class="rv"><div class="fold-div"><i>&#10022;</i></div></div>
  <div class="tl">
    @foreach($stories as $st)
    <div class="tl-item rv"><div class="oheart"><div class="oh-l"></div><div class="oh-r"></div></div><h4>{{ $st->title ?? 'Cerita' }}</h4><p>{{ $st->description ?? $st->content ?? '' }}</p></div>
    @endforeach
  </div>
</section>
@endif

<!-- RSVP -->
<section id="rsvp">
  <div class="rv"><div class="sec-kicker">Konfirmasi</div><div class="sec-title">RSVP</div></div>
  <div class="rv"><div class="fold-div"><i>&#10022;</i></div></div>
  <div class="dark-panel rv">
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
  </div>
</section>

<!-- UCAPAN -->
<section id="ucapan">
  <div class="rv"><div class="sec-kicker">Doa &amp; Harapan</div><div class="sec-title">Ucapan</div></div>
  <div class="rv"><div class="fold-div"><i>&#10022;</i></div></div>
  <div id="wishList" class="rv"></div>
  <form id="wishForm" class="rv" onsubmit="return sendWish(event)" style="margin-top:22px">
    <div class="field"><label>Nama</label><input required name="name" placeholder="Nama Anda"></div>
    <div class="field"><label>Ucapan</label><textarea required name="message" rows="3" placeholder="Tulis doa terbaik Anda..."></textarea></div>
    <button class="btn-line" type="submit">Kirim Ucapan</button>
  </form>
</section>

<!-- GIFT -->
@if(!empty($accounts) || mcGet('gift_address'))
<section id="gift">
  <div class="rv"><div class="sec-kicker">Tanda Kasih</div><div class="sec-title">Hadiah</div></div>
  <div class="rv"><div class="fold-div"><i>&#10022;</i></div></div>
  @foreach($accounts as $a)
  <div class="card bank rv">
    <div class="bk">{{ $a['bank'] }}</div>
    @if(!empty($a['logo']))<img src="{{ $a['logo'] }}" alt="Logo bank" style="height:36px;max-width:150px;object-fit:contain;margin:10px auto 0;display:block">@endif
    <div class="no">{{ $a['no'] }}</div>
    <div class="an">a.n. {{ $a['an'] }}</div>
    <button class="btn-line copy" onclick="copyNo('{{ $a['no'] }}')">Salin Nomor</button>
  </div>
  @endforeach
  @if(mcGet('gift_address'))<div class="card bank rv"><div class="bk">Kirim Hadiah Fisik</div><p style="font-size:14px;color:var(--tinta-dim);margin-top:10px;line-height:1.7">{{ mcGet('gift_address') }}</p>
    @if(mcGet('gift_maps_link'))<div style="margin-top:12px"><a href="{{ mcGet('gift_maps_link') }}" target="_blank" style="display:inline-block;padding:10px 26px;border:1px solid currentColor;border-radius:999px;font-size:11px;letter-spacing:.24em;text-transform:uppercase;text-decoration:none;opacity:.85">Lihat Peta</a></div>@endif</div>@endif
</section>
@endif

<footer>
  <div class="rv"><div class="hand">Terima Kasih</div>
  <p style="color:var(--tinta-dim);font-size:14px;line-height:1.8;max-width:380px;margin:14px auto">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>
  <div class="rv hero-heart"><div class="oheart float"><div class="oh-l"></div><div class="oh-r"></div></div></div>
  <div style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:24px;color:var(--coral-dk)">{{ $brideNick }} &amp; {{ $groomNick }}</div></div>
</footer>
</div>

<button id="musBtn" onclick="toggleMus()" style="display:none" aria-label="Putar musik">&#9834;</button>


<div class="lb" id="lb"><span class="lb-x" onclick="closeLb()">&times;</span><img id="lbImg" src="" alt="Foto"></div>
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
// debu emas melayang
const cv=document.getElementById('petals'),cx=cv.getContext('2d');let P=[];
function rs(){cv.width=innerWidth;cv.height=innerHeight}rs();addEventListener('resize',rs);
const PCOLS=['#e86a8a','#f2a03d','#ffffff','#f6d9a8','#f6b8c8'];
for(let i=0;i<50;i++)P.push({x:Math.random()*innerWidth,y:Math.random()*innerHeight,r:Math.random()*2.2+.6,s:Math.random()*.25+.08,ph:Math.random()*6.28,tw:Math.random()*.05+.01,c:PCOLS[i%PCOLS.length]});
(function loop(t){cx.clearRect(0,0,cv.width,cv.height);P.forEach(p=>{p.y-=p.s;p.x+=Math.sin(t/2000+p.ph)*.3;
  if(p.y<-10){p.y=cv.height+10;p.x=Math.random()*cv.width}
  cx.globalAlpha=.3+Math.abs(Math.sin(t*p.tw+p.ph))*.6;cx.fillStyle=p.c;
  cx.beginPath();cx.arc(p.x,p.y,p.r,0,6.29);cx.fill();});cx.globalAlpha=1;requestAnimationFrame(loop)})(0);
// rsvp
async function sendRSVP(e){e.preventDefault();const f=e.target;
  const r=await fetch('/api/rsvp?inv_id='+INV_ID,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name:f.name.value,status:f.status.value,guest_count:f.guest_count.value})});
  alert(r.ok?'Terima kasih atas konfirmasinya!':'Gagal mengirim, coba lagi.');f.reset();return false;}
// wishes
async function loadWishes(){try{const r=await fetch('/api/ucapan?inv_id='+INV_ID);const d=await r.json();
  wishList.innerHTML=(d.data||d||[]).map(w=>`<div class="wish"><b>${esc(w.name)}</b><span class="st">${esc(w.status||'')}</span><p>${esc(w.message||w.ucapan||'')}</p></div>`).join('')||'<p style="color:var(--tinta-dim)">Belum ada ucapan.</p>';}catch(e){}}
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
async function sendWish(e){e.preventDefault();const f=e.target;
  const r=await fetch('/api/ucapan?inv_id='+INV_ID,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name:f.name.value,message:f.message.value})});
  if(r.ok){f.reset();loadWishes();}else alert('Gagal mengirim ucapan.');return false;}
loadWishes();
function copyNo(t){navigator.clipboard.writeText(t).then(()=>alert('Nomor tersalin!'));}
function toggleMus(){const m=document.getElementById('mus');if(m.paused){m.play();musBtn.style.opacity=1}else{m.pause();musBtn.style.opacity=.5}}
</script>
<script>
// === Animasi origami 3D: kawanan bangau & pesawat kertas ===
(function(){
  var sky = document.getElementById('sky3d');
  var cranes = [
    {top:'10%', dur:'26s', delay:'-6s',  sc:.55, blur:1.6, op:.62, c1:'#f2a03d', c2:'#e07f2e', flap:.7},
    {top:'20%', dur:'34s', delay:'-19s', sc:.8,  blur:.7, op:.85, c1:'#e86a8a', c2:'#d14f74', flap:.9},
    {top:'30%', dur:'30s', delay:'-2s',  sc:1,   blur:0,   op:1,   c1:'#e86a8a', c2:'#f2a03d', flap:.85},
    {top:'40%', dur:'38s', delay:'-25s', sc:.65, blur:1.1, op:.72, c1:'#f6b8c8', c2:'#e86a8a', flap:1}
  ];
  cranes.forEach(function(c){
    var fly = document.createElement('div');
    fly.className = 'cfly';
    fly.style.top = c.top;
    fly.style.animationDuration = c.dur;
    fly.style.animationDelay = c.delay;
    fly.innerHTML =
      '<div class="crane" style="transform:scale(' + c.sc + ');filter:blur(' + c.blur + 'px);opacity:' + c.op + '">' +
      '<div class="cbob">' +
      '<div class="wing wl" style="--c1:' + c.c1 + ';--c2:' + c.c2 + ';animation-duration:' + c.flap + 's"></div>' +
      '<div class="wing wr" style="--c1:' + c.c1 + ';--c2:' + c.c2 + ';animation-duration:' + c.flap + 's"></div>' +
      '<div class="cbody" style="--c1:' + c.c1 + ';--c2:' + c.c2 + '"></div>' +
      '</div></div>';
    sky.appendChild(fly);
  });
  var planes = [
    {top:'0', dur:'21s', delay:'-4s'},
    {top:'0', dur:'27s', delay:'-16s'}
  ];
  planes.forEach(function(p){
    var el = document.createElement('div');
    el.className = 'plane';
    el.style.top = p.top;
    el.style.animationDuration = p.dur;
    el.style.animationDelay = p.delay;
    sky.appendChild(el);
  });
})();
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

function openLb(src){
  if(!src) return;
  document.getElementById('lbImg').src = src;
  document.getElementById('lb').classList.add('show');
  document.body.style.overflow = 'hidden';
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
      + 'Status : ' + (__att === 'hadir' ? 'HADIR' : 'TIDAK HADIR')
      + (__att === 'hadir' ? NL + 'Jumlah Tamu : ' + jumlah + ' orang' : '')
      + (alasan ? NL + 'Alasan : ' + alasan : '')
      + NL + NL + 'Terima kasih atas undangannya';
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

async function loadWishes(){
  try{
    var r = await fetch('/api/ucapan?inv_id=' + INV_ID + '&limit=20');
    var d = await r.json();
    var rows = d.data || [];
    document.getElementById('wishList').innerHTML = rows.map(function(w){
      return '<div class="wish"><b>' + esc(w.nama) + '</b><p>' + esc(w.pesan) + '</p></div>';
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
  var SECS = [['hero','⌂','Awal'],['mempelai','♥','Mempelai'],['galeri','▦','Galeri'],
              ['kisah','✎','Kisah'],['acara','◷','Acara'],['rsvp','✓','RSVP'],
              ['gift','✦','Gift'],['ucapan','✉','Ucapan']];
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
</script>

</body>
</html>
