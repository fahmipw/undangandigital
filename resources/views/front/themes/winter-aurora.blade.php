<?php
/* ============================================================
   TEMA PREMIUM: WINTER AURORA PALACE
   Dipilih via settings: theme = winter-aurora
   Animasi: salju 3D, aurora, istana es shimmer, glassmorphism
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
<title>{{ $brideNick }} &amp; {{ $groomNick }} — Winter Aurora Palace</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--bg:#eef4f8;--ice:#a8c8ff;--toska:#7fe3d0;--deep:#3a6ea5;--ink:#2a3a4a;
--night1:#0e1c2e;--night2:#1a2f47;--snow:#ffffff;--tinta:#2a3a4a;--tinta-dim:rgba(42,58,74,.62)}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{font-family:'Jost',sans-serif;color:var(--ink);font-weight:300;overflow-x:hidden;
background:linear-gradient(180deg,#e8f1f8 0%,#eef4f8 30%,#f4f9fc 70%,#e9f2f9 100%)}
#petals{position:fixed;inset:0;z-index:1;pointer-events:none}
.wrap{position:relative;z-index:2;max-width:520px;margin:0 auto}
.serif{font-family:'Cormorant Garamond',serif}
/* ---------- aurora ---------- */
.aurora{position:absolute;border-radius:50%;filter:blur(55px);pointer-events:none;opacity:.75}
.aurora.a1{width:420px;height:170px;top:-60px;left:-90px;
background:linear-gradient(100deg,rgba(127,227,208,.85),rgba(168,200,255,.55) 55%,rgba(180,140,255,.45));
animation:aur1 14s ease-in-out infinite alternate}
.aurora.a2{width:360px;height:150px;top:30px;right:-110px;
background:linear-gradient(260deg,rgba(127,227,208,.7),rgba(140,170,255,.5) 60%,rgba(190,150,255,.35));
animation:aur2 18s ease-in-out infinite alternate}
.aurora.a3{width:300px;height:120px;bottom:-40px;left:10%;
background:linear-gradient(120deg,rgba(127,227,208,.5),rgba(168,200,255,.4));
animation:aur1 20s ease-in-out infinite alternate-reverse}
@keyframes aur1{from{transform:translateX(-24px) skewX(-8deg)}to{transform:translateX(40px) skewX(10deg)}}
@keyframes aur2{from{transform:translateX(30px) skewX(9deg)}to{transform:translateX(-36px) skewX(-7deg)}}
/* ---------- salju 3D ---------- */
.sky3d{position:fixed;inset:0;z-index:120;pointer-events:none;overflow:hidden;perspective:1000px}
.f3d{position:absolute;top:-12%;animation-name:fall;animation-timing-function:linear;animation-iteration-count:infinite;transform-style:preserve-3d;will-change:transform}
@keyframes fall{to{transform:translateY(130vh)}}
.spin{display:block;transform-style:preserve-3d;animation-name:spin3d;animation-timing-function:linear;animation-iteration-count:infinite}
@keyframes spin3d{from{transform:rotateY(0deg)}to{transform:rotateY(360deg)}}
/* ---------- istana es ---------- */
.palace{width:100%;display:block}
.shimmer{animation:shim 5.5s ease-in-out infinite}
@keyframes shim{0%{transform:translateX(-180px) skewX(-18deg);opacity:0}15%{opacity:.85}80%{opacity:.85}100%{transform:translateX(560px) skewX(-18deg);opacity:0}}
/* ---------- cover ---------- */
#cover{position:fixed;inset:0;z-index:100;display:flex;align-items:center;justify-content:center;
transition:opacity .9s,visibility .9s;overflow:hidden;
background:linear-gradient(180deg,#cfe2f4 0%,#e6f0f9 42%,#f2f8fc 70%,#e9f2f9 100%)}
#cover.open{opacity:0;visibility:hidden}
.cover-inner{position:relative;text-align:center;padding:30px 26px;width:100%;max-width:520px;z-index:2}
.kicker{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:22px;color:#4a6a8a;letter-spacing:.12em}
.cover-names{font-family:'Cormorant Garamond',serif;font-weight:700;font-size:clamp(46px,13vw,68px);line-height:1.08;color:#23405e;
text-shadow:0 0 26px rgba(168,200,255,.9),0 2px 0 rgba(255,255,255,.7);margin:8px 0 2px}
.cover-names .amp{font-style:italic;font-weight:500;color:#3a6ea5;font-size:.62em}
.cover-date{font-size:13px;letter-spacing:.34em;text-transform:uppercase;color:#5a7a9a;margin-top:10px}
.kepada{margin-top:22px;font-family:'Cormorant Garamond',serif;font-size:20px;color:#3a5468}
.guest-box{margin:12px auto 0;max-width:320px;background:rgba(255,255,255,.55);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);
border:1px solid rgba(255,255,255,.85);border-radius:16px;padding:14px 20px;box-shadow:0 12px 32px rgba(58,110,165,.14)}
.guest-box b{font-family:'Cormorant Garamond',serif;font-size:22px;color:#23405e;font-weight:600}
.btn{display:inline-flex;align-items:center;gap:10px;margin-top:20px;padding:14px 42px;border:none;border-radius:999px;cursor:pointer;
font-family:'Jost';font-weight:500;font-size:13px;letter-spacing:.24em;text-transform:uppercase;color:#fff;
background:linear-gradient(120deg,#3a6ea5,#5aa8c8 55%,#7fe3d0);box-shadow:0 12px 30px rgba(58,110,165,.35);transition:.35s}
.btn:hover{transform:translateY(-2px);box-shadow:0 16px 36px rgba(58,110,165,.45)}
.btn-line{display:inline-block;padding:12px 34px;border:1.5px solid #8fb8dd;color:#2f5a86;font-size:11px;letter-spacing:.26em;
text-transform:uppercase;cursor:pointer;background:rgba(255,255,255,.4);border-radius:999px;transition:.3s;text-decoration:none;font-family:'Jost';font-weight:500}
.btn-line:hover{background:rgba(168,200,255,.25)}
/* ---------- section ---------- */
section{padding:64px 26px;text-align:center;position:relative}
.sec-kicker{font-size:10px;letter-spacing:.44em;text-transform:uppercase;color:#6a9ac0;margin-bottom:10px;font-weight:500}
.sec-title{font-family:'Cormorant Garamond',serif;font-size:36px;font-weight:600;color:#23405e}
.card{background:rgba(255,255,255,.55);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);
border:1px solid rgba(255,255,255,.85);border-radius:20px;padding:34px 24px;margin:0 auto 18px;max-width:400px;
box-shadow:0 14px 36px rgba(58,110,165,.12)}
/* ---------- hero ---------- */
#hero{min-height:96vh;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden;padding-top:90px}
.hero-inner{position:relative;z-index:2;text-align:center;padding:20px 26px}
.hero-hand{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:24px;color:#4a6a8a}
.hero-names{font-family:'Cormorant Garamond',serif;font-weight:700;font-size:clamp(48px,13.5vw,70px);line-height:1.1;color:#23405e;
text-shadow:0 0 30px rgba(168,200,255,.8);margin:6px 0}
.hero-names .amp{font-style:italic;font-weight:500;color:#3a6ea5;font-size:.6em}
.hero-date{letter-spacing:.3em;font-size:13px;color:#5a7a9a;text-transform:uppercase;margin-top:12px}
.cd-grid{display:flex;justify-content:center;gap:10px;margin-top:26px;flex-wrap:wrap}
.cd{width:72px;padding:15px 0;background:rgba(255,255,255,.6);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);
border:1px solid rgba(168,200,255,.6);border-radius:16px;box-shadow:0 10px 24px rgba(58,110,165,.12)}
.cd b{display:block;font-family:'Cormorant Garamond',serif;font-size:30px;color:#23405e;font-weight:700}
.cd span{font-size:9px;letter-spacing:.26em;text-transform:uppercase;color:#6a8aa8}
/* ---------- mempelai ---------- */
.photo{width:150px;height:150px;border-radius:50%;object-fit:cover;margin:0 auto 16px;display:block;
border:4px solid rgba(255,255,255,.9);box-shadow:0 0 0 3px #a8c8ff,0 14px 30px rgba(58,110,165,.2)}
.photo-fallback{width:150px;height:150px;border-radius:50%;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;
font-family:'Cormorant Garamond',serif;font-size:58px;color:#fff;background:linear-gradient(135deg,#8fb8dd,#5aa8c8);
border:4px solid rgba(255,255,255,.9);box-shadow:0 0 0 3px #a8c8ff,0 14px 30px rgba(58,110,165,.2)}
.couple-card h3{font-family:'Cormorant Garamond',serif;font-size:36px;color:#23405e;font-weight:600}
.couple-card .full{font-family:'Cormorant Garamond',serif;font-size:19px;margin:6px 0;color:#3a5468}
.couple-card p{font-size:14px;color:var(--tinta-dim);line-height:1.7}
.heart-div{display:flex;justify-content:center;margin:4px 0}
.heart-div svg{width:64px;filter:drop-shadow(0 6px 14px rgba(127,227,208,.5));animation:heartbeat 2.6s ease-in-out infinite}
@keyframes heartbeat{0%,100%{transform:scale(1)}12%{transform:scale(1.12)}24%{transform:scale(1)}}
/* ---------- acara ---------- */
.event h3{font-family:'Cormorant Garamond',serif;font-size:26px;letter-spacing:.06em;color:#23405e;font-weight:600}
.event .date{font-family:'Cormorant Garamond',serif;font-size:20px;margin:12px 0 4px;color:#3a5468}
.event .time{letter-spacing:.2em;font-size:13px;color:#3a6ea5;font-weight:500}
.event .loc{font-size:14px;color:var(--tinta-dim);margin:12px 0 18px;line-height:1.7}
/* ---------- galeri ---------- */
.g-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:24px}
.g-grid img{width:100%;height:190px;object-fit:cover;border-radius:16px;border:3px solid rgba(255,255,255,.9);
box-shadow:0 10px 24px rgba(58,110,165,.14);transition:.4s}
.g-grid img:hover{transform:scale(1.03)}
/* ---------- kisah ---------- */
.tl{max-width:400px;margin:26px auto 0;text-align:left;position:relative}
.tl::before{content:'';position:absolute;left:27px;top:8px;bottom:8px;width:2px;
background:linear-gradient(180deg,#7fe3d0,#a8c8ff,#7fe3d0);border-radius:2px}
.tl-item{position:relative;background:rgba(255,255,255,.6);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);
border:1px solid rgba(255,255,255,.85);border-radius:16px;padding:18px 20px 18px 70px;margin-bottom:16px;
box-shadow:0 10px 26px rgba(58,110,165,.1)}
.tl-item .tic{position:absolute;left:8px;top:14px;width:40px;height:40px}
.tl-item h4{font-family:'Cormorant Garamond',serif;font-size:22px;color:#23405e;font-weight:600}
.tl-item p{font-size:14px;color:var(--tinta-dim);line-height:1.7;margin-top:6px}
/* ---------- rsvp malam ---------- */
.night{margin:30px -26px -64px;padding:44px 26px 72px;position:relative;overflow:hidden;
background:linear-gradient(180deg,#0e1c2e 0%,#16283f 55%,#0e1c2e 100%)}
.night .sec-kicker{color:#8fd8c8}
.night .sec-title{color:#eaf4fb}
.night .star{position:absolute;border-radius:50%;background:#fff;animation:twinkle 3s ease-in-out infinite}
@keyframes twinkle{0%,100%{opacity:.25}50%{opacity:.95}}
/* ---------- form ---------- */
.field{margin:0 auto 14px;max-width:380px;text-align:left;position:relative;z-index:2}
.field label{display:block;font-size:10px;letter-spacing:.32em;text-transform:uppercase;color:#5a8ab0;margin-bottom:8px;font-weight:500}
.field input,.field select,.field textarea{width:100%;background:rgba(255,255,255,.7);border:1px solid rgba(143,184,221,.7);
border-radius:14px;color:var(--ink);padding:13px 16px;font-family:'Jost';font-size:15px;outline:none}
.field input:focus,.field select:focus,.field textarea:focus{border-color:#5aa8c8;box-shadow:0 0 0 3px rgba(127,227,208,.25)}
.night .field label{color:#8fd8c8}
.night .field input,.night .field select,.night .field textarea{background:rgba(255,255,255,.08);
border:1px solid rgba(143,184,221,.5);color:#f2f8ff}
.night .field input::placeholder,.night .field textarea::placeholder{color:rgba(242,248,255,.45)}
.night .field select option{color:#1a2a3a}
/* ---------- ucapan ---------- */
.wish{background:rgba(255,255,255,.6);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);
.wish-head{display:flex;justify-content:space-between;align-items:baseline;gap:10px;margin-bottom:6px}
.wtime{font-size:11px;opacity:.55;white-space:nowrap}
border:1px solid rgba(255,255,255,.85);border-radius:16px;padding:16px 18px;margin:0 auto 12px;max-width:400px;
text-align:left;box-shadow:0 8px 22px rgba(58,110,165,.1)}
.wish b{font-family:'Cormorant Garamond',serif;color:#23405e;font-size:18px;font-weight:600}
.wish .st{font-size:10px;letter-spacing:.2em;color:#3a9a86;text-transform:uppercase;margin-left:8px;font-weight:500}
.wish p{font-size:14px;color:var(--tinta-dim);margin-top:6px;line-height:1.6}
/* ---------- gift ---------- */
.bank{background:rgba(255,255,255,.6);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);
border:1.5px dashed rgba(143,184,221,.8);border-radius:16px;padding:24px;margin:0 auto 14px;max-width:380px}
.bank .bk{font-size:11px;letter-spacing:.34em;color:#5a8ab0;text-transform:uppercase;font-weight:500}
.bank .no{font-family:'Cormorant Garamond',serif;font-size:30px;margin:10px 0 4px;letter-spacing:.05em;color:#23405e;font-weight:700}
.bank .an{font-size:14px;color:var(--tinta-dim)}
.copy{margin-top:12px}
footer{padding:60px 26px 110px;text-align:center;position:relative;overflow:hidden}
footer .serif{font-size:40px;color:#23405e;font-weight:600}
#musBtn{position:fixed;bottom:22px;right:22px;z-index:90;width:52px;height:52px;border-radius:50%;border:none;cursor:pointer;
display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;
background:linear-gradient(135deg,#3a6ea5,#5aa8c8);box-shadow:0 10px 26px rgba(58,110,165,.4)}

/* === fitur bawaan default: lightbox, menu, progress, musik === */
.pbar{position:fixed;top:0;left:0;right:0;height:3px;z-index:96;background:transparent}
.pbar i{display:block;height:100%;width:0;background:linear-gradient(90deg,#7fe3d0,#a8c8ff)}
.lb{position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.92);display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:.35s;padding:20px}
.lb.show{opacity:1;visibility:visible}
.lb img{max-width:100%;max-height:86vh;border:2px solid #7fe3d0}
.lb-x{position:absolute;top:16px;right:22px;font-size:40px;color:#fff;cursor:pointer;line-height:1;z-index:201}
.lb-prev,.lb-next{position:absolute;top:50%;transform:translateY(-50%);font-size:32px;color:#fff;cursor:pointer;padding:16px 12px;z-index:201;user-select:none;opacity:.85;line-height:1}
.lb-prev{left:6px}.lb-next{right:6px}.lb-prev:hover,.lb-next:hover{opacity:1}
.lb-count{position:absolute;bottom:16px;left:50%;transform:translateX(-50%);color:#fff;font-size:13px;letter-spacing:.15em;z-index:201;font-family:inherit}
.fmenu{position:fixed;bottom:14px;left:50%;transform:translateX(-50%);z-index:90;display:flex;gap:2px;background:#0e1c2ef2;border:1px solid #7fe3d055;border-radius:999px;padding:7px 9px;backdrop-filter:blur(8px);box-shadow:0 10px 30px rgba(0,0,0,.35);max-width:96vw;overflow-x:auto;scrollbar-width:none}
.fmenu::-webkit-scrollbar{display:none}
.fmenu a{display:flex;flex-direction:column;align-items:center;gap:3px;min-width:50px;padding:6px 7px;border-radius:12px;color:#f2f8ff;opacity:.6;text-decoration:none;font-size:9px;letter-spacing:.06em;transition:.25s;font-family:'Jost',sans-serif}
.fmenu a i{font-style:normal;font-size:17px;line-height:1}
.fmenu a.active{opacity:1;background:#7fe3d02e;color:#a8c8ff}
#musBtn.playing{outline:2px solid #a8c8ff;outline-offset:2px}
.att-toggle{display:flex;gap:10px}
.att-toggle button{flex:1;padding:13px 8px;border:1px solid #7fe3d0;background:transparent;color:#f2f8ff;font-family:'Jost',sans-serif;font-size:14px;cursor:pointer;transition:.25s;border-radius:10px}
.att-toggle button.on{background:#7fe3d0;color:#0e1c2e;font-weight:500}
.wish-empty{text-align:center;opacity:.6;font-size:14px;padding:12px}
body.locked #fmenu,body.locked #musBtn,body.locked .pbar{opacity:0 !important;visibility:hidden !important;pointer-events:none !important}

.rv{opacity:0;transform:translateY(36px);transition:opacity .9s,transform .9s}
.rv.on{opacity:1;transform:none}
</style>
</head>
<body>
<div class="pbar"><i id="pbarFill"></i></div>

<canvas id="petals"></canvas>
<div class="sky3d" aria-hidden="true">
<div class="f3d" style="left:4%;animation-duration:13s;animation-delay:-2s"><svg class="spin" style="width:34px;animation-duration:7s" viewBox="0 0 100 100"><use href="#flake"/></svg></div>
<div class="f3d" style="left:14%;animation-duration:19s;animation-delay:-11s"><svg class="spin" style="width:20px;animation-duration:5s;filter:blur(1px);opacity:.8" viewBox="0 0 100 100"><use href="#flake"/></svg></div>
<div class="f3d" style="left:24%;animation-duration:11s;animation-delay:-6s"><svg class="spin" style="width:44px;animation-duration:9s" viewBox="0 0 100 100"><use href="#flake"/></svg></div>
<div class="f3d" style="left:36%;animation-duration:22s;animation-delay:-15s"><svg class="spin" style="width:16px;animation-duration:4s;filter:blur(1.5px);opacity:.7" viewBox="0 0 100 100"><use href="#flake"/></svg></div>
<div class="f3d" style="left:47%;animation-duration:14s;animation-delay:-4s"><svg class="spin" style="width:38px;animation-duration:8s" viewBox="0 0 100 100"><use href="#flake"/></svg></div>
<div class="f3d" style="left:58%;animation-duration:17s;animation-delay:-9s"><svg class="spin" style="width:24px;animation-duration:6s;filter:blur(.8px);opacity:.85" viewBox="0 0 100 100"><use href="#flake"/></svg></div>
<div class="f3d" style="left:68%;animation-duration:12s;animation-delay:-1s"><svg class="spin" style="width:48px;animation-duration:10s" viewBox="0 0 100 100"><use href="#flake"/></svg></div>
<div class="f3d" style="left:78%;animation-duration:20s;animation-delay:-13s"><svg class="spin" style="width:18px;animation-duration:5s;filter:blur(1.2px);opacity:.75" viewBox="0 0 100 100"><use href="#flake"/></svg></div>
<div class="f3d" style="left:88%;animation-duration:15s;animation-delay:-7s"><svg class="spin" style="width:32px;animation-duration:7.5s" viewBox="0 0 100 100"><use href="#flake"/></svg></div>
<div class="f3d" style="left:95%;animation-duration:24s;animation-delay:-18s"><svg class="spin" style="width:15px;animation-duration:4.5s;filter:blur(1.5px);opacity:.7" viewBox="0 0 100 100"><use href="#flake"/></svg></div>
</div>
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
<defs>
<symbol id="flake" viewBox="0 0 100 100">
<g fill="none" stroke="#eaf4ff" stroke-linecap="round">
<path d="M50 6 V94" stroke-width="5"/><path d="M50 6 V94" stroke-width="5" transform="rotate(60 50 50)"/><path d="M50 6 V94" stroke-width="5" transform="rotate(120 50 50)"/>
<g stroke-width="3.4">
<path d="M50 16 l-10 10 M50 16 l10 10 M50 84 l-10 -10 M50 84 l10 -10"/>
<path d="M50 16 l-10 10 M50 16 l10 10 M50 84 l-10 -10 M50 84 l10 -10" transform="rotate(60 50 50)"/>
<path d="M50 16 l-10 10 M50 16 l10 10 M50 84 l-10 -10 M50 84 l10 -10" transform="rotate(120 50 50)"/>
</g>
</g>
<polygon points="50,40 58.7,45 58.7,55 50,60 41.3,55 41.3,45" fill="rgba(234,244,255,.4)"/>
</symbol>
<symbol id="iceheart" viewBox="0 0 100 100">
<defs><linearGradient id="iceHg" x1="0" y1="0" x2="1" y2="1">
<stop offset="0" stop-color="#dcf7f1"/><stop offset=".5" stop-color="#7fe3d0"/><stop offset="1" stop-color="#5aa8c8"/>
</linearGradient></defs>
<path d="M50 88 C22 62 10 44 10 30 C10 17 19 10 29 10 C39 10 47 16 50 24 C53 16 61 10 71 10 C81 10 90 17 90 30 C90 44 78 62 50 88Z" fill="url(#iceHg)"/>
<g stroke="#ffffff" stroke-width="1.6" opacity=".65" fill="none">
<path d="M50 26 V78 M50 26 L30 15 M50 26 L70 15 M50 52 L18 34 M50 52 L82 34 M50 70 L32 64 M50 70 L68 64"/>
</g>
<ellipse cx="33" cy="25" rx="10" ry="5.5" fill="#fff" opacity=".6" transform="rotate(-24 33 25)"/>
</symbol>
<linearGradient id="iceG" x1="0" y1="0" x2="0" y2="1">
<stop offset="0" stop-color="#f2f9ff"/><stop offset=".55" stop-color="#bcd6f2"/><stop offset="1" stop-color="#9dc3ea"/>
</linearGradient>
<linearGradient id="shimG" x1="0" y1="0" x2="1" y2="0">
<stop offset="0" stop-color="#fff" stop-opacity="0"/><stop offset=".5" stop-color="#fff" stop-opacity=".85"/><stop offset="1" stop-color="#fff" stop-opacity="0"/>
</linearGradient>
<clipPath id="palClip"><path d="M40 190 V120 L70 120 V70 L95 120 H110 V60 L140 60 V20 L160 20 V0 L180 20 V60 H210 V30 L240 30 V0 L270 0 V30 H300 V60 H330 V20 L350 20 V0 L370 20 V60 H400 V120 H415 L440 70 V120 H470 V190 Z"/></clipPath>
<symbol id="palaceBody" viewBox="0 0 480 200">
<path d="M40 190 V120 L70 120 V70 L95 120 H110 V60 L140 60 V20 L160 20 V0 L180 20 V60 H210 V30 L240 30 V0 L270 0 V30 H300 V60 H330 V20 L350 20 V0 L370 20 V60 H400 V120 H415 L440 70 V120 H470 V190 Z" fill="url(#iceG)" stroke="#ffffff" stroke-width="2"/>
<g fill="#ffffff" opacity=".9">
<polygon points="150,18 160,0 170,18"/><polygon points="250,28 260,2 270,28"/><polygon points="340,18 350,0 360,18"/>
<polygon points="80,68 95,42 110,68"/>
</g>
<g fill="#fff7d6" opacity=".95">
<rect x="228" y="80" width="14" height="22" rx="7"/><rect x="248" y="80" width="14" height="22" rx="7"/>
<rect x="132" y="100" width="10" height="16" rx="5"/><rect x="358" y="100" width="10" height="16" rx="5"/>
</g>
<g clip-path="url(#palClip)"><rect class="shimmer" x="0" y="-20" width="130" height="240" fill="url(#shimG)"/></g>
<rect x="20" y="190" width="440" height="10" rx="5" fill="#dcebf7"/>
</symbol>
</defs>
</svg>
<div class="wrap">

<!-- ================= COVER ================= -->
<div id="cover">
<div class="aurora a1"></div><div class="aurora a2"></div>
<div class="cover-inner">
<div class="kicker">The Wedding Of</div>
<div class="cover-names">{{ $brideNick }} <span class="amp">&amp;</span> {{ $groomNick }}</div>
<div class="cover-date">{{ mcDate($wDate) }}</div>
<svg class="palace" viewBox="0 0 480 200" style="max-width:400px;margin:14px auto 0" aria-hidden="true"><use href="#palaceBody"/></svg>
<div class="kepada">Kepada Yth.<br>Bapak/Ibu/Saudara/i</div>
<div class="guest-box"><b>{{ $guestName }}</b></div>
<div><button class="btn" onclick="openInv()">&#10022; &nbsp;Buka Undangan</button></div>
</div>
</div>

<!-- ================= HERO ================= -->
<section id="hero">
<div class="aurora a1"></div><div class="aurora a2"></div><div class="aurora a3"></div>
<div class="hero-inner">
<div class="rv"><div class="hero-hand">The Wedding Of</div></div>
<div class="rv"><h1 class="hero-names">{{ $brideNick }} <span class="amp">&amp;</span> {{ $groomNick }}</h1></div>
<div class="rv heart-div"><svg viewBox="0 0 100 100"><use href="#iceheart"/></svg></div>
<div class="rv"><div class="hero-date">{{ mcDate($wDate) }}</div>
@if(mcGet('quotes'))
<div class="rv" style="max-width:420px;margin:20px auto 0;padding:0 8px">
<p style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:19px;line-height:1.7;opacity:.85">“{{ mcGet('quotes') }}”</p>
</div>
@endif</div>
</div>
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
<div class="rv heart-div"><svg viewBox="0 0 100 100"><use href="#iceheart"/></svg></div>
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
<div class="tl">
@foreach($stories as $st)
<div class="tl-item rv"><svg class="tic" viewBox="0 0 100 100"><use href="#iceheart"/></svg>@if(!empty($st->tahun))<div style="font-size:11px;letter-spacing:.25em;opacity:.55;margin-bottom:6px">{{ $st->tahun }}</div>@endif
<h4>{{ $st->judul ?? 'Cerita' }}</h4><p>{{ $st->isi ?? '' }}</p></div>
@endforeach
</div>
</section>
@endif

<!-- RSVP -->
<section id="rsvp">
<div class="rv"><div class="sec-kicker">Konfirmasi</div><div class="sec-title">RSVP</div></div>
<div class="night">
<div class="aurora a1" style="opacity:.35"></div><div class="aurora a2" style="opacity:.3"></div>
<span class="star" style="top:12%;left:18%;width:3px;height:3px"></span>
<span class="star" style="top:22%;left:82%;width:2px;height:2px;animation-delay:-1s"></span>
<span class="star" style="top:8%;left:60%;width:2px;height:2px;animation-delay:-2s"></span>
<span class="star" style="top:30%;left:40%;width:3px;height:3px;animation-delay:-.5s"></span>
<span class="star" style="top:16%;left:32%;width:2px;height:2px;animation-delay:-1.6s"></span>
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
<div class="rv">
<svg class="palace" viewBox="0 0 480 200" style="max-width:340px;margin:0 auto 8px" aria-hidden="true">
<use href="#palaceBody"/>
</svg>
<div class="serif">Terima Kasih</div>
<p style="color:var(--tinta-dim);font-size:14px;line-height:1.8;max-width:380px;margin:14px auto">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>
<div class="serif" style="font-size:24px;color:#23405e">{{ $brideNick }} &amp; {{ $groomNick }}</div>
<div class="heart-div" style="margin-top:14px"><svg viewBox="0 0 100 100"><use href="#iceheart"/></svg></div>
</div>
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
// salju turun
const cv=document.getElementById('petals'),cx=cv.getContext('2d');let P=[];
function rs(){cv.width=innerWidth;cv.height=innerHeight}rs();addEventListener('resize',rs);
const PCOLS=['#ffffff','#eaf4ff','#d8e9fb','#cfe4f7'];
for(let i=0;i<70;i++)P.push({x:Math.random()*innerWidth,y:Math.random()*-innerHeight,r:Math.random()*2.6+1,s:Math.random()*.9+.35,ph:Math.random()*6.28,sw:Math.random()*1.1+.4,c:PCOLS[i%PCOLS.length],o:Math.random()*.5+.35});
(function loop(t){cx.clearRect(0,0,cv.width,cv.height);P.forEach(p=>{p.y+=p.s;p.x+=Math.sin(t/1700+p.ph)*p.sw;
if(p.y>cv.height+8){p.y=-8;p.x=Math.random()*cv.width}
cx.globalAlpha=p.o;cx.fillStyle=p.c;cx.beginPath();cx.arc(p.x,p.y,p.r,0,6.29);cx.fill();});cx.globalAlpha=1;requestAnimationFrame(loop)})(0);
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
// parallax aurora berlapis saat scroll
(function(){
var layers=document.querySelectorAll('.aurora'),ticking=false;
function upd(){var y=window.scrollY||0;
layers.forEach(function(el,i){el.style.translate='0 '+(-y*((i%3+1)*0.05)).toFixed(1)+'px'});
ticking=false;}
window.addEventListener('scroll',function(){if(!ticking){ticking=true;requestAnimationFrame(upd)}},{passive:true});
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
function showLb(){
  var im = lbList[lbIdx];
  if(!im) return;
  document.getElementById('lbImg').src = im.src;
  document.getElementById('lbCount').textContent = (lbIdx+1) + ' / ' + lbList.length;
  document.getElementById('lb').classList.add('show');
  document.body.style.overflow = 'hidden';
}
function stepLb(d){
  if(!lbList.length) return;
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
  var lb = document.getElementById('lb'), sx = 0;
  lb.addEventListener('touchstart', function(e){ sx = e.changedTouches[0].clientX; }, {passive:true});
  lb.addEventListener('touchend', function(e){
    var dx = e.changedTouches[0].clientX - sx;
    if(Math.abs(dx) > 40) stepLb(dx < 0 ? 1 : -1);
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
