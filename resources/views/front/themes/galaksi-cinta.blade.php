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
<title>{{ $brideNick }} &amp; {{ $groomNick }} — Galaksi Cinta</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root{--bg0:#0b0b24;--bg1:#16163a;--ungu:#b18cff;--pink:#ff7ad9;--biru:#7ad9ff;
--tinta:#eef0ff;--tinta-dim:rgba(238,240,255,.62);--kaca:rgba(22,22,58,.72)}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{font-family:'Jost',sans-serif;background:radial-gradient(1400px 900px at 50% -10%,#1a1a44 0%,var(--bg0) 55%);color:var(--tinta);overflow-x:hidden;font-weight:300;min-height:100vh}
#petals{position:fixed;inset:0;z-index:2;pointer-events:none}
/* ---------- nebula aurora ---------- */
.nebula{position:fixed;border-radius:50%;filter:blur(70px);pointer-events:none;z-index:0;mix-blend-mode:screen}
.nebula.n1{width:60vw;height:60vw;max-width:420px;max-height:420px;top:-12%;left:-12%;background:radial-gradient(circle,rgba(122,60,220,.5),transparent 70%);animation:drift1 26s ease-in-out infinite alternate}
.nebula.n2{width:52vw;height:52vw;max-width:380px;max-height:380px;top:22%;right:-14%;background:radial-gradient(circle,rgba(255,122,217,.38),transparent 70%);animation:drift2 32s ease-in-out infinite alternate}
.nebula.n3{width:46vw;height:46vw;max-width:340px;max-height:340px;bottom:-10%;left:8%;background:radial-gradient(circle,rgba(80,140,255,.35),transparent 70%);animation:drift1 38s ease-in-out infinite alternate-reverse}
@keyframes drift1{from{transform:translate(0,0) scale(1)}to{transform:translate(46px,60px) scale(1.18)}}
@keyframes drift2{from{transform:translate(0,0) scale(1.1)}to{transform:translate(-54px,40px) scale(.94)}}
/* ---------- starfield 3 lapis ---------- */
.stars{position:fixed;left:0;right:0;top:-20%;height:140%;z-index:1;pointer-events:none}
.star{position:absolute;border-radius:50%;background:#fff;animation:tw var(--tw,3s) ease-in-out infinite}
.tw{animation:tw 2.6s ease-in-out infinite}
@keyframes tw{0%,100%{opacity:.15;transform:scale(.8)}50%{opacity:1;transform:scale(1.15)}}
.meteor{position:fixed;width:150px;height:2px;z-index:2;pointer-events:none;border-radius:2px;
background:linear-gradient(270deg,#fff,rgba(177,140,255,.7) 40%,transparent);
animation:meteorfall 1s ease-out forwards}
.meteor::before{content:'';position:absolute;right:-3px;top:-2px;width:6px;height:6px;border-radius:50%;background:#fff;box-shadow:0 0 12px 3px rgba(255,255,255,.8)}
@keyframes meteorfall{0%{transform:translate3d(0,0,0) rotate(-32deg);opacity:0}12%{opacity:1}100%{transform:translate3d(-48vw,36vh,0) rotate(-32deg);opacity:0}}
.wrap{position:relative;z-index:3;max-width:520px;margin:0 auto}
.dekor{font-family:'Cinzel Decorative',serif}
/* ---------- planet 3D bercincin ---------- */
.planet3d{perspective:1000px;width:230px;height:230px;margin:0 auto;position:relative;transition:transform .35s ease-out}
.planet{position:absolute;inset:48px;border-radius:50%;z-index:2;
background:radial-gradient(circle at 32% 28%,#ffe3f6 0%,var(--pink) 24%,var(--ungu) 58%,#3c2a78 100%);
box-shadow:0 0 55px rgba(177,140,255,.55),0 0 130px rgba(255,122,217,.22),inset -20px -24px 55px rgba(11,11,36,.8)}
.pdetail{position:absolute;inset:0;border-radius:50%;overflow:hidden;animation:pspin 55s linear infinite}
.pdetail i{position:absolute;border-radius:50%;filter:blur(7px)}
.pdetail i:nth-child(1){width:44px;height:30px;left:22%;top:30%;background:rgba(60,30,110,.4)}
.pdetail i:nth-child(2){width:30px;height:22px;left:58%;top:58%;background:rgba(255,255,255,.22)}
.pdetail i:nth-child(3){width:24px;height:18px;left:40%;top:68%;background:rgba(60,30,110,.35)}
@keyframes pspin{from{transform:rotate(0)}to{transform:rotate(360deg)}}
.orbit{position:absolute;inset:0;z-index:3;transform-style:preserve-3d;animation:orbitspin 22s linear infinite;pointer-events:none}
@keyframes orbitspin{from{transform:rotateX(72deg) rotateZ(0)}to{transform:rotateX(72deg) rotateZ(360deg)}}
.ring{position:absolute;left:50%;top:50%;border-radius:50%}
.ring.r1{width:300px;height:300px;margin:-150px 0 0 -150px;border:7px solid rgba(210,190,255,.5);border-top-color:rgba(255,255,255,.85)}
.ring.r2{width:238px;height:238px;margin:-119px 0 0 -119px;border:4px solid rgba(255,122,217,.4)}
.moon{position:absolute;left:50%;top:50%;width:14px;height:14px;margin:-157px 0 0 -7px;border-radius:50%;
background:radial-gradient(circle at 35% 35%,#fff,var(--biru) 70%);box-shadow:0 0 14px rgba(122,217,255,.9)}
.planet-float{animation:pfloat 7s ease-in-out infinite}
@keyframes pfloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-14px)}}
.planet3d.mini{width:170px;height:170px}
.planet3d.mini .planet{inset:36px}
.planet3d.mini .ring.r1{width:224px;height:224px;margin:-112px 0 0 -112px}
.planet3d.mini .ring.r2{width:178px;height:178px;margin:-89px 0 0 -89px}
.planet3d.mini .moon{margin:-119px 0 0 -7px}
/* ---------- cover ---------- */
#cover{position:fixed;inset:0;z-index:100;display:flex;align-items:center;justify-content:center;transition:opacity .9s,visibility .9s;overflow-y:auto;
background:radial-gradient(1000px 700px at 50% 0%,#1c1c48 0%,var(--bg0) 62%)}
#cover.open{opacity:0;visibility:hidden}
#coverTilt{transition:transform .3s ease-out;will-change:transform}
.cover-inner{text-align:center;padding:30px 22px 46px;width:100%;max-width:520px;position:relative}
.kicker{font-size:11px;letter-spacing:.5em;text-transform:uppercase;color:var(--ungu);margin:16px 0 4px;font-weight:500}
.cover-names{font-family:'Cinzel Decorative',serif;font-weight:700;font-size:clamp(38px,10.5vw,56px);line-height:1.3;margin:8px 0 4px;
background:linear-gradient(120deg,var(--ungu),var(--pink) 55%,#ffd7f2);-webkit-background-clip:text;background-clip:text;color:transparent;
filter:drop-shadow(0 0 22px rgba(255,122,217,.35))}
.cover-date{font-family:'Jost',sans-serif;font-size:14px;letter-spacing:.3em;color:var(--tinta-dim);text-transform:uppercase}
.kepada{margin-top:20px;font-size:17px;color:var(--tinta);line-height:1.6}
.guest-box{margin:10px auto 0;max-width:330px;background:rgba(22,22,58,.8);border:1px solid rgba(177,140,255,.55);border-radius:14px;padding:13px 18px;box-shadow:0 0 30px rgba(177,140,255,.22),inset 0 0 24px rgba(177,140,255,.07);backdrop-filter:blur(6px)}
.guest-box b{font-family:'Cinzel Decorative',serif;font-size:20px;color:#fff;font-weight:700}
.btn{display:inline-flex;align-items:center;gap:10px;margin-top:18px;padding:14px 42px;background:linear-gradient(135deg,var(--ungu),var(--pink));border:none;border-radius:999px;color:#0b0b24;font-size:12px;letter-spacing:.22em;text-transform:uppercase;cursor:pointer;font-family:'Jost';font-weight:500;transition:.35s;box-shadow:0 10px 30px rgba(255,122,217,.35)}
.btn:hover{transform:translateY(-2px);box-shadow:0 14px 36px rgba(255,122,217,.5)}
.btn-line{display:inline-block;padding:12px 32px;border:1px solid var(--ungu);color:var(--ungu);font-size:11px;letter-spacing:.26em;text-transform:uppercase;cursor:pointer;background:transparent;border-radius:999px;transition:.3s;text-decoration:none;font-family:'Jost'}
.btn-line:hover{background:rgba(177,140,255,.14);box-shadow:0 0 18px rgba(177,140,255,.3)}
/* ---------- section ---------- */
section{padding:66px 26px;text-align:center;position:relative}
.sec-kicker{font-size:10px;letter-spacing:.44em;text-transform:uppercase;color:var(--ungu);margin-bottom:10px;font-weight:500}
.sec-title{font-family:'Cinzel Decorative',serif;font-size:30px;font-weight:700;color:#fff;text-shadow:0 0 26px rgba(177,140,255,.5)}
.constel{display:block;width:200px;margin:14px auto}
.card{background:var(--kaca);border:1px solid rgba(177,140,255,.35);border-radius:16px;padding:36px 24px;margin:0 auto 18px;max-width:400px;box-shadow:0 14px 40px rgba(0,0,0,.45),inset 0 0 30px rgba(177,140,255,.05);backdrop-filter:blur(8px)}
#hero{min-height:96vh;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden}
.hero-hand{font-size:15px;letter-spacing:.34em;text-transform:uppercase;color:var(--ungu);margin-top:14px}
.hero-names{font-family:'Cinzel Decorative',serif;font-weight:700;font-size:clamp(38px,10.5vw,56px);line-height:1.32;margin:10px 0;
background:linear-gradient(120deg,var(--ungu),var(--pink) 55%,#ffd7f2);-webkit-background-clip:text;background-clip:text;color:transparent;
filter:drop-shadow(0 0 22px rgba(255,122,217,.3))}
.hero-date{letter-spacing:.3em;font-size:13px;color:var(--tinta-dim);text-transform:uppercase;margin-top:6px}
.cd-grid{display:flex;justify-content:center;gap:10px;margin-top:26px}
.cd{width:72px;padding:16px 0;background:var(--kaca);border:1px solid rgba(177,140,255,.4);border-radius:14px;box-shadow:0 8px 22px rgba(0,0,0,.4);backdrop-filter:blur(8px)}
.cd b{display:block;font-family:'Cinzel Decorative',serif;font-size:28px;color:var(--pink)}
.cd span{font-size:9px;letter-spacing:.26em;text-transform:uppercase;color:var(--tinta-dim)}
.photo{width:150px;height:150px;border-radius:50%;object-fit:cover;margin:0 auto 16px;border:3px solid var(--ungu);box-shadow:0 0 34px rgba(177,140,255,.5);display:block}
.photo-fallback{width:150px;height:150px;border-radius:50%;margin:0 auto 16px;background:radial-gradient(circle at 35% 30%,#3a2f6e,#141432);border:3px solid var(--ungu);box-shadow:0 0 34px rgba(177,140,255,.5);display:flex;align-items:center;justify-content:center;font-family:'Cinzel Decorative',serif;font-size:52px;color:#fff}
.couple-card h3{font-family:'Cinzel Decorative',serif;font-size:30px;color:#fff;font-weight:700}
.couple-card .full{font-size:16px;margin:6px 0;color:var(--tinta-dim)}
.couple-card p{font-size:14px;color:var(--tinta-dim);line-height:1.7}
.amp{font-family:'Cinzel Decorative',serif;font-size:40px;color:var(--pink);margin:2px 0;text-shadow:0 0 22px rgba(255,122,217,.55)}
.event h3{font-family:'Cinzel Decorative',serif;font-size:22px;letter-spacing:.08em;color:#fff}
.event .date{font-size:17px;margin:12px 0 4px}
.event .time{letter-spacing:.22em;font-size:12px;color:var(--pink);font-weight:500}
.event .loc{font-size:14px;color:var(--tinta-dim);margin:12px 0 18px;line-height:1.7}
.g-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:24px}
.g-grid img{width:100%;height:190px;object-fit:cover;border-radius:12px;border:2px solid rgba(177,140,255,.45);box-shadow:0 8px 22px rgba(0,0,0,.45);transition:.4s}
.g-grid img:hover{transform:scale(1.03)}
.tl{max-width:400px;margin:26px auto 0;text-align:left}
.tl-item{background:var(--kaca);border-left:3px solid var(--pink);border-radius:0 12px 12px 0;padding:18px 20px;margin-bottom:14px;box-shadow:0 8px 22px rgba(0,0,0,.4);backdrop-filter:blur(8px)}
.tl-item h4{font-family:'Cinzel Decorative',serif;font-size:18px;color:#fff}
.tl-item p{font-size:14px;color:var(--tinta-dim);line-height:1.7;margin-top:6px}
.field{margin:0 auto 14px;max-width:380px;text-align:left}
.field label{display:block;font-size:10px;letter-spacing:.32em;text-transform:uppercase;color:var(--ungu);margin-bottom:8px;font-weight:500}
.field input,.field select,.field textarea{width:100%;background:rgba(11,11,36,.85);border:1px solid rgba(177,140,255,.45);border-radius:10px;color:var(--tinta);padding:13px 16px;font-family:'Jost';font-size:15px;outline:none}
.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--pink);box-shadow:0 0 0 3px rgba(255,122,217,.15)}
.wish{background:var(--kaca);border:1px solid rgba(177,140,255,.3);border-radius:12px;padding:16px 18px;margin:0 auto 12px;max-width:400px;text-align:left;backdrop-filter:blur(8px)}
.wish-head{display:flex;justify-content:space-between;align-items:baseline;gap:10px;margin-bottom:6px}
.wtime{font-size:11px;opacity:.55;white-space:nowrap}
.wish b{font-family:'Jost';color:#fff;font-size:16px;font-weight:500}
.wish .st{font-size:10px;letter-spacing:.2em;color:var(--pink);text-transform:uppercase;margin-left:8px;font-weight:500}
.wish p{font-size:14px;color:var(--tinta-dim);margin-top:6px;line-height:1.6}
.bank{background:var(--kaca);border:1px dashed rgba(177,140,255,.55);border-radius:12px;padding:24px;margin:0 auto 14px;max-width:380px;backdrop-filter:blur(8px)}
.bank .bk{font-size:11px;letter-spacing:.34em;color:var(--ungu);text-transform:uppercase;font-weight:500}
.bank .no{font-family:'Cinzel Decorative',serif;font-size:26px;margin:10px 0 4px;letter-spacing:.05em;color:#fff}
.bank .an{font-size:14px;color:var(--tinta-dim)}
.copy{margin-top:12px}
footer{padding:56px 26px 110px;text-align:center}
footer .dekor{font-size:36px;color:#fff;text-shadow:0 0 24px rgba(177,140,255,.5)}
#musBtn{position:fixed;bottom:22px;right:22px;z-index:90;width:52px;height:52px;border-radius:50%;border:1px solid var(--ungu);background:linear-gradient(135deg,#23235a,#0b0b24);color:#fff;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 0 22px rgba(177,140,255,.4)}

/* === fitur bawaan default: lightbox, menu, progress, musik === */
.pbar{position:fixed;top:0;left:0;right:0;height:3px;z-index:96;background:transparent}
.pbar i{display:block;height:100%;width:0;background:linear-gradient(90deg,#b18cff,#ff7ad9)}
.lb{position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.92);display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:.35s;padding:20px;touch-action:pan-y}
#lbImg{max-width:100%;max-height:86vh;border-radius:8px;will-change:transform,opacity}
.lb.show{opacity:1;visibility:visible}
.lb img{max-width:100%;max-height:86vh;border:2px solid #b18cff}
.lb-x{position:absolute;top:16px;right:22px;font-size:40px;color:#fff;cursor:pointer;line-height:1;z-index:201}
.lb-prev,.lb-next{position:absolute;top:50%;transform:translateY(-50%);font-size:32px;color:#fff;cursor:pointer;padding:16px 12px;z-index:201;user-select:none;opacity:.85;line-height:1}
.lb-prev{left:6px}.lb-next{right:6px}.lb-prev:hover,.lb-next:hover{opacity:1}
.lb-count{position:absolute;bottom:16px;left:50%;transform:translateX(-50%);color:#fff;font-size:13px;letter-spacing:.15em;z-index:201;font-family:inherit}
.fmenu{position:fixed;bottom:14px;left:50%;transform:translateX(-50%);z-index:90;display:flex;gap:2px;background:#0b0b24f2;border:1px solid #b18cff55;border-radius:999px;padding:7px 9px;backdrop-filter:blur(8px);box-shadow:0 10px 30px rgba(0,0,0,.35);max-width:96vw;overflow-x:auto;scrollbar-width:none}
.fmenu::-webkit-scrollbar{display:none}
.fmenu a{display:flex;flex-direction:column;align-items:center;gap:3px;min-width:50px;padding:6px 7px;border-radius:12px;color:#eef0ff;opacity:.6;text-decoration:none;font-size:9px;letter-spacing:.06em;transition:.25s;font-family:'Jost',sans-serif}
.fmenu a i{font-style:normal;font-size:17px;line-height:1}
.fmenu a.active{opacity:1;background:#b18cff2e;color:#ff7ad9}
#musBtn.playing{outline:2px solid #ff7ad9;outline-offset:2px}
.att-toggle{display:flex;gap:10px}
.att-toggle button{flex:1;padding:13px 8px;border:1px solid #b18cff;background:transparent;color:#eef0ff;font-family:'Jost',sans-serif;font-size:14px;cursor:pointer;transition:.25s;border-radius:10px}
.att-toggle button.on{background:#b18cff;color:#0b0b24;font-weight:500}
.wish-empty{text-align:center;opacity:.6;font-size:14px;padding:12px}
body.locked #fmenu,body.locked #musBtn,body.locked .pbar{opacity:0 !important;visibility:hidden !important;pointer-events:none !important}

.rv{opacity:0;transform:translateY(36px);transition:opacity .9s,transform .9s}
.rv.on{opacity:1;transform:none}
</style>
</head>
<body>
<div class="pbar"><i id="pbarFill"></i></div>
<div class="nebula n1"></div><div class="nebula n2"></div><div class="nebula n3"></div>
<div class="stars" id="starsFar" data-speed="0.04"></div>
<div class="stars" id="starsMid" data-speed="0.1"></div>
<div class="stars" id="starsNear" data-speed="0.18"></div>
<canvas id="petals"></canvas>
<div class="wrap">

<!-- ================= COVER ================= -->
<div id="cover">
<div class="cover-inner">
<div id="coverTilt">
<div class="planet3d mini planet-float">
<div class="orbit"><div class="ring r1"></div><div class="ring r2"></div><div class="moon"></div></div>
<div class="planet"><div class="pdetail"><i></i><i></i><i></i></div></div>
</div>
</div>
<div class="kicker">The Wedding Of</div>
<div class="cover-names">{{ $brideNick }} &amp; {{ $groomNick }}</div>
<div class="cover-date">{{ mcDate($wDate) }}</div>
<div class="kepada">Kepada Yth.<br>Bapak/Ibu/Saudara/i</div>
<div class="guest-box"><b>{{ $guestName }}</b></div>
<div><button class="btn" onclick="openInv()">&#10022; &nbsp;Buka Undangan</button></div>
</div>
</div>

<!-- ================= HERO ================= -->
<section id="hero">
<div class="planet-wrap" data-speed="0.06">
<div class="planet3d planet-float">
<div class="orbit"><div class="ring r1"></div><div class="ring r2"></div><div class="moon"></div></div>
<div class="planet"><div class="pdetail"><i></i><i></i><i></i></div></div>
</div>
</div>
<div class="rv"><div class="hero-hand">The Wedding Of</div></div>
<div class="rv"><h1 class="hero-names">{{ $brideNick }} &amp; {{ $groomNick }}</h1></div>
<div class="rv"><div class="hero-date">{{ mcDate($wDate) }}</div>
@if(mcGet('quotes'))
<div class="rv" style="max-width:420px;margin:20px auto 0;padding:0 8px">
<p style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:19px;line-height:1.7;opacity:.85">“{{ mcGet('quotes') }}”</p>
</div>
@endif</div>
<svg class="constel rv" viewBox="0 0 200 180">
<polyline points="100,52 78,34 52,36 36,56 34,82 44,108 66,132 100,158 134,132 156,108 166,82 164,56 148,36 122,34 100,52" fill="none" stroke="rgba(177,140,255,.45)" stroke-width="1.2"/>
<g fill="#fff">
<circle class="tw" cx="100" cy="52" r="3.6"/><circle class="tw" cx="78" cy="34" r="3" style="animation-delay:-.4s"/><circle class="tw" cx="52" cy="36" r="3" style="animation-delay:-.9s"/><circle class="tw" cx="36" cy="56" r="3" style="animation-delay:-1.3s"/><circle class="tw" cx="34" cy="82" r="3" style="animation-delay:-.2s"/><circle class="tw" cx="44" cy="108" r="3" style="animation-delay:-1.7s"/><circle class="tw" cx="66" cy="132" r="3" style="animation-delay:-.7s"/><circle class="tw" cx="100" cy="158" r="4" style="animation-delay:-1.1s"/><circle class="tw" cx="134" cy="132" r="3" style="animation-delay:-.5s"/><circle class="tw" cx="156" cy="108" r="3" style="animation-delay:-1.5s"/><circle class="tw" cx="166" cy="82" r="3" style="animation-delay:-.1s"/><circle class="tw" cx="164" cy="56" r="3" style="animation-delay:-1.9s"/><circle class="tw" cx="148" cy="36" r="3" style="animation-delay:-.8s"/><circle class="tw" cx="122" cy="34" r="3" style="animation-delay:-1.2s"/>
</g>
</svg>
</section>

<!-- COUNTDOWN -->
<section>
<div class="rv"><div class="sec-kicker">Menuju Hari Bahagia</div><div class="sec-title">Hitung Mundur</div></div>
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
<div class="g-grid rv">
@foreach(array_slice($gallery, 0, 8) as $img)<img src="{{ $img }}" alt="Galeri {{ $brideNick }} & {{ $groomNick }}" loading="lazy">@endforeach
</div>
</section>
@endif

<!-- KISAH -->
@if(!empty($stories) && count($stories))
<section id="kisah">
<div class="rv"><div class="sec-kicker">Perjalanan Kami</div><div class="sec-title">Kisah Cinta</div></div>
<svg class="constel rv" viewBox="0 0 200 180">
<polyline points="100,52 78,34 52,36 36,56 34,82 44,108 66,132 100,158 134,132 156,108 166,82 164,56 148,36 122,34 100,52" fill="none" stroke="rgba(177,140,255,.45)" stroke-width="1.2"/>
<g fill="#fff">
<circle class="tw" cx="100" cy="52" r="3.6"/><circle class="tw" cx="78" cy="34" r="3" style="animation-delay:-.4s"/><circle class="tw" cx="52" cy="36" r="3" style="animation-delay:-.9s"/><circle class="tw" cx="36" cy="56" r="3" style="animation-delay:-1.3s"/><circle class="tw" cx="34" cy="82" r="3" style="animation-delay:-.2s"/><circle class="tw" cx="44" cy="108" r="3" style="animation-delay:-1.7s"/><circle class="tw" cx="66" cy="132" r="3" style="animation-delay:-.7s"/><circle class="tw" cx="100" cy="158" r="4" style="animation-delay:-1.1s"/><circle class="tw" cx="134" cy="132" r="3" style="animation-delay:-.5s"/><circle class="tw" cx="156" cy="108" r="3" style="animation-delay:-1.5s"/><circle class="tw" cx="166" cy="82" r="3" style="animation-delay:-1.1s"/><circle class="tw" cx="164" cy="56" r="3" style="animation-delay:-1.9s"/><circle class="tw" cx="148" cy="36" r="3" style="animation-delay:-.8s"/><circle class="tw" cx="122" cy="34" r="3" style="animation-delay:-1.2s"/>
</g>
</svg>
<div class="tl">
@foreach($stories as $st)
<div class="tl-item rv">@if(!empty($st->tahun))<div style="font-size:11px;letter-spacing:.25em;opacity:.55;margin-bottom:6px">{{ $st->tahun }}</div>@endif
<h4>{{ $st->judul ?? 'Cerita' }}</h4><p>{{ $st->isi ?? '' }}</p></div>
@endforeach
</div>
</section>
@endif

<!-- RSVP -->
<section id="rsvp">
<div class="rv"><div class="sec-kicker">Konfirmasi</div><div class="sec-title">RSVP</div></div>
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
<section id="ucapan">
<div class="rv"><div class="sec-kicker">Doa &amp; Harapan</div><div class="sec-title">Ucapan</div></div>
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
<svg class="rv" viewBox="0 0 120 70" style="width:130px;margin:12px auto;display:block">
<circle cx="45" cy="32" r="15" fill="#b18cff" opacity=".85"/>
<ellipse cx="45" cy="32" rx="30" ry="9" fill="none" stroke="#eef0ff" stroke-width="2.5" opacity=".8" transform="rotate(-18 45 32)"/>
<circle cx="78" cy="40" r="12" fill="#ff7ad9" opacity=".85"/>
<ellipse cx="78" cy="40" rx="25" ry="7.5" fill="none" stroke="#eef0ff" stroke-width="2.2" opacity=".8" transform="rotate(14 78 40)"/>
</svg>
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
<div class="rv"><div class="dekor">Terima Kasih</div>
<p style="color:var(--tinta-dim);font-size:14px;line-height:1.8;max-width:380px;margin:14px auto">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>
<div class="dekor" style="font-size:22px;color:var(--pink)">{{ $brideNick }} &amp; {{ $groomNick }}</div></div>
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
// debu bintang melayang
const cv=document.getElementById('petals'),cx=cv.getContext('2d');let P=[];
function rs(){cv.width=innerWidth;cv.height=innerHeight}rs();addEventListener('resize',rs);
const PCOLS=['#b18cff','#ff7ad9','#ffffff','#7ad9ff'];
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
// galaksi: starfield 3 lapis + meteor + parallax + tilt planet 3D
(function(){
  var layers=[
    {el:document.getElementById('starsFar'),n:130,sMin:.6,sMax:1.4,oMin:.25,oMax:.6},
    {el:document.getElementById('starsMid'),n:70,sMin:1.2,sMax:2.2,oMin:.4,oMax:.8},
    {el:document.getElementById('starsNear'),n:32,sMin:2,sMax:3.4,oMin:.6,oMax:1}
  ];
  var COLS=['#ffffff','#ffffff','#cdd6ff','#ffd7f2','#bfe9ff'];
  layers.forEach(function(L){
    if(!L.el)return;
    for(var i=0;i<L.n;i++){
      var s=document.createElement('span');s.className='star';
      var sz=(L.sMin+Math.random()*(L.sMax-L.sMin)).toFixed(1);
      s.style.width=s.style.height=sz+'px';
      s.style.left=(Math.random()*100)+'%';
      s.style.top=(Math.random()*100)+'%';
      s.style.opacity=(L.oMin+Math.random()*(L.oMax-L.oMin)).toFixed(2);
      s.style.background=COLS[(Math.random()*COLS.length)|0];
      s.style.setProperty('--tw',(2+Math.random()*3.5).toFixed(2)+'s');
      s.style.animationDelay=(-Math.random()*4).toFixed(2)+'s';
      if(Math.random()<.12)s.style.boxShadow='0 0 8px 1px rgba(255,255,255,.7)';
      L.el.appendChild(s);
    }
  });
  function meteor(){
    var m=document.createElement('div');m.className='meteor';
    m.style.left=(15+Math.random()*70)+'%';
    m.style.top=(2+Math.random()*30)+'%';
    document.body.appendChild(m);
    setTimeout(function(){m.remove()},1100);
    setTimeout(meteor,3500+Math.random()*6000);
  }
  setTimeout(meteor,2500);
  var plx=document.querySelectorAll('[data-speed]'),ticking=false;
  function upd(){var y=window.scrollY||window.pageYOffset;
    plx.forEach(function(el){el.style.transform='translate3d(0,'+(y*parseFloat(el.dataset.speed)).toFixed(1)+'px,0)'});
    ticking=false;}
  window.addEventListener('scroll',function(){if(!ticking){requestAnimationFrame(upd);ticking=true}},{passive:true});
  var tilt=document.getElementById('coverTilt');
  if(tilt&&window.matchMedia('(pointer:fine)').matches){
    document.getElementById('cover').addEventListener('mousemove',function(e){
      var r=tilt.getBoundingClientRect();
      var dx=(e.clientX-(r.left+r.width/2))/r.width,dy=(e.clientY-(r.top+r.height/2))/r.height;
      tilt.style.transform='rotateY('+(dx*24).toFixed(1)+'deg) rotateX('+(-dy*24).toFixed(1)+'deg)';
    });
  }
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
  var SECS = [['hero','⌂','Awal'],['mempelai','♥','Mempelai'],['galeri','▦','Galeri'],
              ['kisah','✎','Kisah'],['acara','◷','Acara'],['rsvp','✓','RSVP'],
              ['ucapan','✉','Ucapan'],['gift','✦','Gift']];
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
