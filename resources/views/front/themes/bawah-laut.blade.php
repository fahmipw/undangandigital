<?php
/* ============================================================
   TEMA PREMIUM: BAWAH LAUT (Underwater)
   Dipilih via settings: theme = bawah-laut
   Animasi: background berganti per scene saat scroll (menyelam
   makin dalam), berkas cahaya, gelembung naik, ikan berenang,
   ubur-ubur melayang, rumput laut bergoyang
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
<title>{{ $brideNick }} &amp; {{ $groomNick }} — Bawah Laut</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Pinyon+Script&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root{--tinta:#123a4a;--tinta-dim:rgba(18,58,74,.65);--kertas:rgba(255,255,255,.9);
--aksen:#16617f;--aksen-lt:#0e4a5f;--karang:#ff8f6b;--ombak:#7fc4dd}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{font-family:'Jost',sans-serif;background:#0e3a4a;color:var(--tinta);overflow-x:hidden;font-weight:300}
/* scene dalam memakai teks terang */
body[data-scene="4"],body[data-scene="5"],body[data-scene="6"],body[data-scene="7"],body[data-scene="8"],body[data-scene="9"],body[data-scene="10"]{
--tinta:#eaf7fb;--tinta-dim:rgba(234,247,251,.7);--kertas:rgba(8,20,38,.55);
--aksen:#9fe0f2;--aksen-lt:#d8f2fa}
/* ---------- background berganti per scene ---------- */
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
#bubbles{position:fixed;inset:0;z-index:1;pointer-events:none}
#fauna{position:fixed;inset:0;z-index:3;pointer-events:none;overflow:hidden}
.wrap{position:relative;z-index:2;max-width:520px;margin:0 auto}
.serif{font-family:'Cormorant Garamond',serif}.hand{font-family:'Pinyon Script',cursive}
/* ---------- berkas cahaya matahari ---------- */
.rays{position:absolute;inset:0;overflow:hidden;opacity:0;transition:opacity 1.2s;pointer-events:none}
body:not([data-scene]) .rays,body[data-scene="0"] .rays,body[data-scene="1"] .rays,body[data-scene="2"] .rays{opacity:1}
.ray{position:absolute;top:-25%;width:80px;height:150%;background:linear-gradient(180deg,rgba(255,255,255,.5),rgba(255,255,255,0));transform:rotate(16deg);filter:blur(8px);animation:raysway 7s ease-in-out infinite}
.ray.r1{left:10%}.ray.r2{left:32%;animation-delay:-2s;width:120px}.ray.r3{left:56%;animation-delay:-4s}.ray.r4{left:78%;animation-delay:-5.5s;width:60px}
@keyframes raysway{0%,100%{transform:rotate(16deg) translateX(0);opacity:.65}50%{transform:rotate(19deg) translateX(16px);opacity:1}}
/* ---------- ikan berenang ---------- */
.fish{position:absolute;animation:swim linear infinite;opacity:.85}
.fish.f1{top:9%;animation-duration:24s}
.fish.f2{top:23%;animation-duration:36s;animation-delay:-12s}
.fish.f3{top:40%;animation-duration:30s;animation-delay:-21s}
.fish.f4{top:62%;animation-duration:46s;animation-delay:-33s}
@keyframes swim{from{left:-90px}to{left:110%}}
.fish .tail{transform-box:fill-box;transform-origin:right center;animation:wag .7s ease-in-out infinite alternate}
@keyframes wag{from{transform:rotate(12deg)}to{transform:rotate(-12deg)}}
/* ---------- ubur-ubur ---------- */
.jelly{position:absolute;animation:jfloat 7s ease-in-out infinite}
.jelly.j1{top:13%;right:5%}
.jelly.j2{top:36%;left:3%;animation-delay:-3.5s}
.jelly .dome{transform-box:fill-box;transform-origin:50% 100%;animation:jpulse 2.4s ease-in-out infinite}
@keyframes jfloat{0%,100%{transform:translateY(-14px)}50%{transform:translateY(14px)}}
@keyframes jpulse{0%,100%{transform:scale(1,1)}50%{transform:scale(1.1,.88)}}
/* ---------- rumput laut ---------- */
.seaweed{position:absolute;bottom:-4px;left:0;width:100%;pointer-events:none}
.sw{transform-origin:50% 100%;animation:sway 5.5s ease-in-out infinite}
.sw.s2{animation-delay:-1.8s}.sw.s3{animation-delay:-3.6s}
@keyframes sway{0%,100%{transform:rotate(-3deg)}50%{transform:rotate(3deg)}}
/* ---------- cover ---------- */
#cover{position:fixed;inset:0;z-index:100;display:flex;align-items:center;justify-content:center;transition:opacity .9s,visibility .9s;overflow-y:auto;background:linear-gradient(180deg,#aee8e4 0%,#e0f7f0 100%)}
#cover.open{opacity:0;visibility:hidden}
.cover-inner{text-align:center;padding:26px 20px 40px;width:100%;max-width:520px;position:relative}
.shell-frame{display:block;width:100%;max-width:320px;margin:0 auto}
.kicker{font-size:12px;letter-spacing:.5em;text-transform:uppercase;color:var(--aksen);margin-top:6px;font-weight:500}
.cover-names{font-family:'Pinyon Script',cursive;font-size:clamp(52px,14vw,76px);color:var(--tinta);margin:10px 0 2px;line-height:1.15}
.cover-date{font-family:'Cormorant Garamond',serif;font-size:21px;letter-spacing:.3em;color:var(--aksen-lt)}
.wave-div{display:block;margin:12px auto}
.kepada{margin-top:14px;font-family:'Cormorant Garamond',serif;font-size:21px;color:var(--tinta)}
.guest-box{margin:10px auto 0;max-width:330px;background:var(--kertas);border:1px solid var(--karang);border-radius:14px;padding:13px 18px;box-shadow:0 8px 24px rgba(10,40,60,.18)}
.guest-box b{font-family:'Cormorant Garamond',serif;font-size:21px;color:var(--tinta);font-weight:600}
.btn{display:inline-flex;align-items:center;gap:10px;margin-top:16px;padding:14px 40px;background:linear-gradient(135deg,#2a8ab0,#16617f);border:none;border-radius:999px;color:#fff;font-size:13px;letter-spacing:.22em;text-transform:uppercase;cursor:pointer;font-family:'Jost';font-weight:500;transition:.35s;box-shadow:0 10px 26px rgba(22,97,127,.35)}
.btn:hover{transform:translateY(-2px);box-shadow:0 14px 32px rgba(22,97,127,.45)}
.btn-line{display:inline-block;padding:12px 32px;border:1px solid var(--aksen);color:var(--aksen-lt);font-size:12px;letter-spacing:.26em;text-transform:uppercase;cursor:pointer;background:transparent;border-radius:999px;transition:.3s;text-decoration:none;font-family:'Jost'}
.btn-line:hover{background:rgba(127,196,221,.18)}
/* ---------- section ---------- */
section{padding:64px 26px;text-align:center;position:relative}
.sec-kicker{font-size:11px;letter-spacing:.44em;text-transform:uppercase;color:var(--aksen);margin-bottom:10px;font-weight:500}
.sec-title{font-family:'Cormorant Garamond',serif;font-size:38px;font-weight:600;color:var(--tinta)}
.card{background:var(--kertas);border:1px solid rgba(127,196,221,.4);border-radius:18px;padding:34px 24px;margin:0 auto 18px;max-width:400px;box-shadow:0 14px 36px rgba(10,40,60,.18);backdrop-filter:blur(4px)}
#hero{min-height:94vh;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden}
.hero-hand{font-family:'Pinyon Script',cursive;font-size:34px;color:var(--aksen-lt)}
.hero-names{font-family:'Pinyon Script',cursive;font-size:clamp(54px,15vw,80px);line-height:1.15;margin:12px 0;color:var(--tinta)}
.hero-names em{font-style:normal;color:var(--karang);font-size:.72em}
.hero-date{letter-spacing:.3em;font-size:14px;color:var(--tinta-dim);text-transform:uppercase}
.cd-grid{display:flex;justify-content:center;gap:10px;margin-top:26px}
.cd{width:72px;padding:16px 0;background:var(--kertas);border:1px solid rgba(127,196,221,.45);border-radius:14px;box-shadow:0 8px 20px rgba(10,40,60,.15)}
.cd b{display:block;font-family:'Cormorant Garamond',serif;font-size:32px;color:var(--aksen-lt)}
.cd span{font-size:10px;letter-spacing:.26em;text-transform:uppercase;color:var(--tinta-dim)}
.photo{width:150px;height:150px;border-radius:50%;object-fit:cover;margin:0 auto 16px;border:4px solid #fff;box-shadow:0 12px 30px rgba(10,40,60,.3);display:block}
.photo-fallback{width:150px;height:150px;border-radius:50%;margin:0 auto 16px;background:linear-gradient(135deg,#57b8d4,#16617f);border:4px solid #fff;box-shadow:0 12px 30px rgba(10,40,60,.3);display:flex;align-items:center;justify-content:center;font-family:'Pinyon Script',cursive;font-size:60px;color:#fff}
.couple-card h3{font-family:'Pinyon Script',cursive;font-size:40px;color:var(--aksen-lt);font-weight:400}
.couple-card .full{font-family:'Cormorant Garamond',serif;font-size:19px;margin:6px 0}
.couple-card p{font-size:14px;color:var(--tinta-dim);line-height:1.7}
.amp{font-family:'Pinyon Script',cursive;font-size:48px;color:var(--karang);margin:2px 0}
.event h3{font-family:'Cormorant Garamond',serif;font-size:27px;letter-spacing:.08em;color:var(--aksen-lt);text-transform:uppercase}
.event .date{font-family:'Cormorant Garamond',serif;font-size:20px;margin:12px 0 4px}
.event .time{letter-spacing:.22em;font-size:13px;color:var(--aksen)}
.event .loc{font-size:14px;color:var(--tinta-dim);margin:12px 0 18px;line-height:1.7}
.g-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:24px}
.g-grid img{width:100%;height:190px;object-fit:cover;border-radius:14px;border:4px solid #fff;box-shadow:0 8px 22px rgba(10,40,60,.25);transition:.4s}
.g-grid img:hover{transform:scale(1.03) rotate(.5deg)}
.tl{max-width:400px;margin:26px auto 0;text-align:left}
.tl-item{background:var(--kertas);border-left:3px solid var(--karang);border-radius:0 14px 14px 0;padding:18px 20px;margin-bottom:14px;box-shadow:0 8px 22px rgba(10,40,60,.15)}
.tl-item h4{font-family:'Cormorant Garamond',serif;font-size:22px;color:var(--aksen-lt)}
.tl-item p{font-size:14px;color:var(--tinta-dim);line-height:1.7;margin-top:6px}
.field{margin:0 auto 14px;max-width:380px;text-align:left}
.field label{display:block;font-size:10px;letter-spacing:.32em;text-transform:uppercase;color:var(--aksen);margin-bottom:8px;font-weight:500}
.field input,.field select,.field textarea{width:100%;background:var(--kertas);border:1px solid rgba(127,196,221,.5);border-radius:12px;color:var(--tinta);padding:13px 16px;font-family:'Jost';font-size:15px;outline:none}
.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--aksen);box-shadow:0 0 0 3px rgba(127,196,221,.2)}
.field input::placeholder,.field textarea::placeholder{color:var(--tinta-dim)}
.wish{background:var(--kertas);border:1px solid rgba(127,196,221,.4);border-radius:14px;padding:16px 18px;margin:0 auto 12px;max-width:400px;text-align:left;box-shadow:0 6px 18px rgba(10,40,60,.12)}
.wish b{font-family:'Cormorant Garamond',serif;color:var(--aksen-lt);font-size:17px}
.wish .st{font-size:10px;letter-spacing:.2em;color:var(--karang);text-transform:uppercase;margin-left:8px;font-weight:500}
.wish p{font-size:14px;color:var(--tinta-dim);margin-top:6px;line-height:1.6}
.bank{background:var(--kertas);border:1px dashed rgba(127,196,221,.6);border-radius:14px;padding:24px;margin:0 auto 14px;max-width:380px}
.bank .bk{font-size:11px;letter-spacing:.34em;color:var(--aksen);text-transform:uppercase;font-weight:500}
.bank .no{font-family:'Cormorant Garamond',serif;font-size:30px;margin:10px 0 4px;letter-spacing:.06em}
.bank .an{font-size:14px;color:var(--tinta-dim)}
.copy{margin-top:12px}
footer{padding:56px 26px 100px;text-align:center;position:relative}
footer .hand{font-size:42px;color:var(--aksen-lt)}
#musBtn{position:fixed;bottom:22px;right:22px;z-index:90;width:52px;height:52px;border-radius:50%;border:none;background:linear-gradient(135deg,#2a8ab0,#16617f);color:#fff;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 26px rgba(22,97,127,.4)}

/* === fitur bawaan default: lightbox, menu, progress, musik === */
.pbar{position:fixed;top:0;left:0;right:0;height:3px;z-index:96;background:transparent}
.pbar i{display:block;height:100%;width:0;background:linear-gradient(90deg,#2e9db8,#1b6e85)}
.lb{position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.92);display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:.35s;padding:20px}
.lb.show{opacity:1;visibility:visible}
.lb img{max-width:100%;max-height:86vh;border:2px solid #2e9db8}
.lb-x{position:absolute;top:16px;right:22px;font-size:40px;color:#fff;cursor:pointer;line-height:1;z-index:201}
.fmenu{position:fixed;bottom:14px;left:50%;transform:translateX(-50%);z-index:90;display:flex;gap:2px;background:#06283af2;border:1px solid #2e9db855;border-radius:999px;padding:7px 9px;backdrop-filter:blur(8px);box-shadow:0 10px 30px rgba(0,0,0,.35);max-width:96vw;overflow-x:auto;scrollbar-width:none}
.fmenu::-webkit-scrollbar{display:none}
.fmenu a{display:flex;flex-direction:column;align-items:center;gap:3px;min-width:50px;padding:6px 7px;border-radius:12px;color:#eaf7fb;opacity:.6;text-decoration:none;font-size:9px;letter-spacing:.06em;transition:.25s;font-family:'Jost',sans-serif}
.fmenu a i{font-style:normal;font-size:17px;line-height:1}
.fmenu a.active{opacity:1;background:#2e9db82e;color:#1b6e85}
#musBtn.playing{outline:2px solid #1b6e85;outline-offset:2px}
.att-toggle{display:flex;gap:10px}
.att-toggle button{flex:1;padding:13px 8px;border:1px solid #2e9db8;background:transparent;color:#eaf7fb;font-family:'Jost',sans-serif;font-size:14px;cursor:pointer;transition:.25s;border-radius:10px}
.att-toggle button.on{background:#2e9db8;color:#06283a;font-weight:500}
.wish-empty{text-align:center;opacity:.6;font-size:14px;padding:12px}
body.locked #fmenu,body.locked #musBtn,body.locked .pbar{opacity:0 !important;visibility:hidden !important;pointer-events:none !important}

.rv{opacity:0;transform:translateY(36px);transition:opacity .9s,transform .9s}
.rv.on{opacity:1;transform:none}
</style>
</head>
<body>
<div class="pbar"><i id="pbarFill"></i></div>
<div id="scene">
  <div class="sbg" style="background:linear-gradient(180deg,#aee8e4,#e0f7f0)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#8fd8dd,#c8f0ea)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#5fb8cc,#a0e0e8)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#3a8fb8,#7ac8d8)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#2a6ea0,#5aa8c8)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#1e4e80,#3a7eb0)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#163a66,#2a5e96)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#102a4e,#1e4a7a)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#0c1e3a,#163a5e)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#081426,#102a48)"></div>
  <div class="sbg" style="background:linear-gradient(180deg,#060e1c,#0c2038)"></div>
</div>
<canvas id="bubbles"></canvas>
<div id="fauna">
  <div class="fish f1"><svg width="64" height="30" viewBox="0 0 70 32"><path class="tail" d="M24 16 L4 5 L4 27 Z" fill="#1e6f95"/><ellipse cx="42" cy="16" rx="20" ry="11" fill="#1e6f95"/><path d="M40 6 Q44 1 48 6" stroke="#155a78" stroke-width="3" fill="none" stroke-linecap="round"/><circle cx="52" cy="12" r="2.8" fill="#fff"/></svg></div>
  <div class="fish f2"><svg width="48" height="23" viewBox="0 0 70 32"><path class="tail" d="M24 16 L4 5 L4 27 Z" fill="#4fa3c4"/><ellipse cx="42" cy="16" rx="20" ry="11" fill="#4fa3c4"/><path d="M40 6 Q44 1 48 6" stroke="#3a86a6" stroke-width="3" fill="none" stroke-linecap="round"/><circle cx="52" cy="12" r="2.8" fill="#fff"/></svg></div>
  <div class="fish f3"><svg width="56" height="26" viewBox="0 0 70 32"><path class="tail" d="M24 16 L4 5 L4 27 Z" fill="#1e6f95"/><ellipse cx="42" cy="16" rx="20" ry="11" fill="#1e6f95"/><path d="M40 6 Q44 1 48 6" stroke="#155a78" stroke-width="3" fill="none" stroke-linecap="round"/><circle cx="52" cy="12" r="2.8" fill="#fff"/></svg></div>
  <div class="fish f4"><svg width="40" height="19" viewBox="0 0 70 32"><path class="tail" d="M24 16 L4 5 L4 27 Z" fill="#4fa3c4"/><ellipse cx="42" cy="16" rx="20" ry="11" fill="#4fa3c4"/><path d="M40 6 Q44 1 48 6" stroke="#3a86a6" stroke-width="3" fill="none" stroke-linecap="round"/><circle cx="52" cy="12" r="2.8" fill="#fff"/></svg></div>
</div>
<div class="wrap">

<!-- ================= COVER ================= -->
<div id="cover" data-scene="0">
  <div class="cover-inner">
    <svg class="shell-frame" viewBox="0 0 300 76">
      <g transform="translate(52 40) scale(.9)">
        <path d="M0,-20 L5.9,-6.5 L20,-6.2 L8.2,3.2 L12.4,17 L0,8.5 L-12.4,17 L-8.2,3.2 L-20,-6.2 L-5.9,-6.5 Z" fill="#ff8f6b" stroke="#e06a45" stroke-width="1.5"/>
        <circle r="3.2" fill="#e06a45"/>
      </g>
      <g transform="translate(150 46)">
        <path d="M-30 12 A33 33 0 0 1 30 12 Z" fill="#f7dcc3" stroke="#d9a066" stroke-width="2"/>
        <g stroke="#d9a066" stroke-width="1.6">
          <line x1="0" y1="12" x2="-24" y2="-10"/><line x1="0" y1="12" x2="-12" y2="-16"/>
          <line x1="0" y1="12" x2="0" y2="-18"/><line x1="0" y1="12" x2="12" y2="-16"/>
          <line x1="0" y1="12" x2="24" y2="-10"/>
        </g>
        <circle cx="0" cy="12" r="4" fill="#e8b04b"/>
      </g>
      <g transform="translate(248 40) scale(.9)">
        <path d="M0,-20 L5.9,-6.5 L20,-6.2 L8.2,3.2 L12.4,17 L0,8.5 L-12.4,17 L-8.2,3.2 L-20,-6.2 L-5.9,-6.5 Z" fill="#ff8f6b" stroke="#e06a45" stroke-width="1.5"/>
        <circle r="3.2" fill="#e06a45"/>
      </g>
      <g fill="none" stroke="#7fc4dd" stroke-width="1.6">
        <circle cx="105" cy="18" r="5"/><circle cx="196" cy="14" r="4"/><circle cx="150" cy="8" r="3"/>
      </g>
    </svg>

    <div class="kicker">The Wedding Of</div>
    <div class="cover-names">{{ $brideNick }} &amp; {{ $groomNick }}</div>
    <div class="cover-date">{{ mcDate($wDate) }}</div>
    <svg class="wave-div" viewBox="0 0 300 24" style="width:200px">
      <path d="M0 12 Q25 2 50 12 T100 12 T150 12 T200 12 T250 12 T300 12" fill="none" stroke="#7fc4dd" stroke-width="3" stroke-linecap="round"/>
    </svg>

    <div class="kepada">Kepada Yth.<br>Bapak/Ibu/Saudara/i</div>
    <div class="guest-box"><b>{{ $guestName }}</b></div>
    <div><button class="btn" onclick="openInv()">&#9993; &nbsp;Buka Undangan</button></div>
  </div>
</div>

<!-- ================= HERO ================= -->
<section id="hero" data-scene="1">
  <div class="rays"><div class="ray r1"></div><div class="ray r2"></div><div class="ray r3"></div><div class="ray r4"></div></div>
  <div class="jelly j1"><svg width="86" viewBox="0 0 100 150">
    <path class="dome" d="M12 70 A38 38 0 0 1 88 70 Q70 58 50 62 Q30 58 12 70 Z" fill="rgba(255,182,213,.55)" stroke="#ffd9e8" stroke-width="2"/>
    <g stroke="rgba(255,217,232,.85)" stroke-width="2.5" fill="none" stroke-linecap="round">
      <path d="M30 68 q-6 22 2 40 q5 14 -2 30"/><path d="M45 70 q4 22 -2 42 q-4 14 3 30"/>
      <path d="M60 70 q-4 22 3 40 q5 14 -3 32"/><path d="M72 66 q6 20 -1 38"/>
    </g>
    <circle cx="38" cy="45" r="3" fill="#fff" opacity=".8"/><circle cx="62" cy="45" r="3" fill="#fff" opacity=".8"/>
  </svg></div>
  <div class="jelly j2"><svg width="64" viewBox="0 0 100 150">
    <path class="dome" d="M12 70 A38 38 0 0 1 88 70 Q70 58 50 62 Q30 58 12 70 Z" fill="rgba(159,224,242,.5)" stroke="#d8f2fa" stroke-width="2"/>
    <g stroke="rgba(216,242,250,.85)" stroke-width="2.5" fill="none" stroke-linecap="round">
      <path d="M30 68 q-6 22 2 40 q5 14 -2 30"/><path d="M45 70 q4 22 -2 42 q-4 14 3 30"/>
      <path d="M60 70 q-4 22 3 40 q5 14 -3 32"/><path d="M72 66 q6 20 -1 38"/>
    </g>
    <circle cx="38" cy="45" r="3" fill="#fff" opacity=".8"/><circle cx="62" cy="45" r="3" fill="#fff" opacity=".8"/>
  </svg></div>
  <div class="rv"><div class="hero-hand">The Wedding Of</div></div>
  <div class="rv"><h1 class="hero-names">{{ $brideNick }} <em>&amp;</em> {{ $groomNick }}</h1></div>
  <div class="rv">
    <svg class="wave-div" viewBox="0 0 300 24" style="width:180px">
      <path d="M0 12 Q25 2 50 12 T100 12 T150 12 T200 12 T250 12 T300 12" fill="none" stroke="#7fc4dd" stroke-width="3" stroke-linecap="round"/>
      <circle cx="150" cy="12" r="5" fill="#ff8f6b"/>
    </svg>
  </div>
  <div class="rv"><div class="hero-date">{{ mcDate($wDate) }}</div>
@if(mcGet('quotes'))
<div class="rv" style="max-width:420px;margin:20px auto 0;padding:0 8px">
<p style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:19px;line-height:1.7;opacity:.85">“{{ mcGet('quotes') }}”</p>
</div>
@endif</div>
  <svg class="seaweed" viewBox="0 0 300 130" preserveAspectRatio="xMidYMax meet">
    <g class="sw s1"><path d="M60 132 C50 102 70 82 58 52 C50 32 58 20 54 8" stroke="#3fa7c4" stroke-width="9" fill="none" stroke-linecap="round"/>
      <ellipse cx="50" cy="72" rx="13" ry="6" fill="#3fa7c4" transform="rotate(-30 50 72)"/></g>
    <g class="sw s2"><path d="M150 132 C160 97 140 77 152 47 C160 27 152 16 156 6" stroke="#57b8d4" stroke-width="11" fill="none" stroke-linecap="round"/>
      <ellipse cx="142" cy="80" rx="14" ry="6" fill="#57b8d4" transform="rotate(24 142 80)"/></g>
    <g class="sw s3"><path d="M240 132 C230 102 250 84 238 54 C230 34 238 22 234 10" stroke="#2a8ab0" stroke-width="9" fill="none" stroke-linecap="round"/></g>
    <ellipse cx="150" cy="128" rx="140" ry="8" fill="#1e6f95" opacity=".3"/>
  </svg>
</section>

<!-- COUNTDOWN -->
<section id="countdown" data-scene="2">
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
  </div>
  <div class="amp rv">&amp;</div>
  <div class="card couple-card rv">
    @if($groomPhoto)<img class="photo" src="{{ $groomPhoto }}" alt="{{ $groomFull }}">@else<div class="photo-fallback">{{ $groomInitial }}</div>@endif
    <h3>{{ $groomNick }}</h3><div class="full">{{ $groomFull }}</div>
    <p>Putra dari<br>{{ mcGet('groom_parents', mcGet('groom_child_of', '')) }}</p>
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
    <div class="tl-item rv"><h4>{{ $st->title ?? 'Cerita' }}</h4><p>{{ $st->description ?? $st->content ?? '' }}</p></div>
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

<footer data-scene="10">
  <div class="rv"><div class="hand">Terima Kasih</div>
  <p style="color:var(--tinta-dim);font-size:14px;line-height:1.8;max-width:380px;margin:14px auto">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>
  <div class="serif" style="font-size:22px;color:var(--aksen-lt)">{{ $brideNick }} &amp; {{ $groomNick }}</div>
  <svg viewBox="0 0 300 60" style="width:220px;margin:18px auto 0;display:block">
    <g transform="translate(70 30) scale(.8)">
      <path d="M0,-20 L5.9,-6.5 L20,-6.2 L8.2,3.2 L12.4,17 L0,8.5 L-12.4,17 L-8.2,3.2 L-20,-6.2 L-5.9,-6.5 Z" fill="#ff8f6b" stroke="#e06a45" stroke-width="1.5"/>
    </g>
    <g transform="translate(150 34)">
      <path d="M-26 10 A29 29 0 0 1 26 10 Z" fill="#f7dcc3" stroke="#d9a066" stroke-width="2"/>
      <g stroke="#d9a066" stroke-width="1.5">
        <line x1="0" y1="10" x2="-20" y2="-8"/><line x1="0" y1="10" x2="0" y2="-14"/><line x1="0" y1="10" x2="20" y2="-8"/>
      </g>
    </g>
    <g transform="translate(230 30) scale(.8)">
      <path d="M0,-20 L5.9,-6.5 L20,-6.2 L8.2,3.2 L12.4,17 L0,8.5 L-12.4,17 L-8.2,3.2 L-20,-6.2 L-5.9,-6.5 Z" fill="#ff8f6b" stroke="#e06a45" stroke-width="1.5"/>
    </g>
  </svg></div>
  <svg class="seaweed" viewBox="0 0 300 130" preserveAspectRatio="xMidYMax meet">
    <g class="sw s1"><path d="M80 132 C70 102 90 82 78 52 C70 32 78 20 74 8" stroke="#4fb3d1" stroke-width="9" fill="none" stroke-linecap="round"/></g>
    <g class="sw s2"><path d="M220 132 C230 102 210 82 222 52 C230 32 222 20 226 8" stroke="#4fb3d1" stroke-width="9" fill="none" stroke-linecap="round"/></g>
    <ellipse cx="150" cy="128" rx="130" ry="8" fill="#1e6f95" opacity=".3"/>
  </svg>
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
// gelembung naik
const cv=document.getElementById('bubbles'),cx=cv.getContext('2d');let P=[];
function rs(){cv.width=innerWidth;cv.height=innerHeight}rs();addEventListener('resize',rs);
for(let i=0;i<30;i++)P.push({x:Math.random()*innerWidth,y:Math.random()*innerHeight,r:Math.random()*7+2,s:Math.random()*.8+.3,ph:Math.random()*6.28,sw:Math.random()*1+.4});
(function loop(t){cx.clearRect(0,0,cv.width,cv.height);P.forEach(p=>{p.y-=p.s;p.x+=Math.sin(t/1600+p.ph)*p.sw;
  if(p.y<-12){p.y=cv.height+12;p.x=Math.random()*cv.width}
  cx.beginPath();cx.arc(p.x,p.y,p.r,0,6.29);cx.strokeStyle='rgba(255,255,255,.5)';cx.lineWidth=1.4;cx.stroke();
  cx.beginPath();cx.arc(p.x-p.r*.3,p.y-p.r*.3,Math.max(p.r*.28,1),0,6.29);cx.fillStyle='rgba(255,255,255,.55)';cx.fill();});requestAnimationFrame(loop)})(0);
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
// === ganti background per scene saat scroll (efek menyelam makin dalam) ===
(function(){
  var io2=new IntersectionObserver(function(es){
    es.forEach(function(e){
      if(!e.isIntersecting) return;
      if(e.target.id==='cover' && e.target.classList.contains('open')) return;
      document.body.dataset.scene=e.target.getAttribute('data-scene');
    });
  },{rootMargin:'-45% 0px -45% 0px'});
  document.querySelectorAll('[data-scene]').forEach(function(el){io2.observe(el);});
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
