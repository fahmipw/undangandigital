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
<title>{{ $brideNick }} &amp; {{ $groomNick }} — Taman Janur</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Pinyon+Script&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root{--sky1:#eaf5e4;--sky2:#fbf8ec;--daun:#3e7d44;--daun-dk:#2c5f33;--janur:#f2c230;--janur-dk:#c9962a;
--bunga:#e88ca0;--tinta:#3a4a2f;--tinta-dim:rgba(58,74,47,.62);--kertas:#fffdf7}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{font-family:'Jost',sans-serif;background:linear-gradient(180deg,var(--sky1) 0%,var(--sky2) 45%,#f3ecd9 100%);color:var(--tinta);overflow-x:hidden;font-weight:300}
#petals{position:fixed;inset:0;z-index:1;pointer-events:none}
#fauna{position:fixed;inset:0;z-index:3;pointer-events:none;overflow:hidden}
.wrap{position:relative;z-index:2;max-width:520px;margin:0 auto}
.serif{font-family:'Cormorant Garamond',serif}.hand{font-family:'Pinyon Script',cursive}
/* ---------- langit: matahari, awan, burung ---------- */
.sun{position:absolute;top:46px;right:8%;width:90px;height:90px;border-radius:50%;
background:radial-gradient(circle,#fff8d6 0%,#fbe89b 45%,rgba(251,232,155,0) 72%);filter:blur(1px);animation:sunpulse 6s ease-in-out infinite}
@keyframes sunpulse{0%,100%{transform:scale(1);opacity:.9}50%{transform:scale(1.12);opacity:1}}
.cloud{position:absolute;background:#fff;border-radius:999px;opacity:.85;filter:blur(1px);animation:drift linear infinite}
.cloud::before,.cloud::after{content:'';position:absolute;background:#fff;border-radius:50%}
.cloud.c1{top:70px;left:-140px;width:110px;height:34px;animation-duration:65s}
.cloud.c1::before{width:52px;height:52px;top:-24px;left:16px}.cloud.c1::after{width:36px;height:36px;top:-16px;left:58px}
.cloud.c2{top:150px;left:-180px;width:140px;height:40px;animation-duration:95s;animation-delay:-40s;opacity:.7}
.cloud.c2::before{width:62px;height:62px;top:-30px;left:24px}.cloud.c2::after{width:44px;height:44px;top:-20px;left:76px}
.cloud.c3{top:26px;left:-120px;width:90px;height:28px;animation-duration:80s;animation-delay:-60s;opacity:.6}
.cloud.c3::before{width:44px;height:44px;top:-20px;left:14px}.cloud.c3::after{width:30px;height:30px;top:-13px;left:50px}
@keyframes drift{from{transform:translateX(0)}to{transform:translateX(calc(100vw + 320px))}}
.bird{position:absolute;animation:fly linear infinite}
.bird svg{display:block;overflow:visible}
.bird.b1{top:9%;animation-duration:30s}
.bird.b2{top:15%;animation-duration:42s;animation-delay:-18s;transform:scale(.75)}
.bird.b3{top:22%;animation-duration:55s;animation-delay:-35s;transform:scale(.6)}
@keyframes fly{from{left:-70px}to{left:110%}}
.bird .wings{transform-origin:center;animation:flap .55s ease-in-out infinite alternate}
@keyframes flap{from{transform:scaleY(1)}to{transform:scaleY(.45)}}
/* ---------- janur ---------- */
.janur{transform-origin:50% 100%;animation:sway 5.5s ease-in-out infinite}
.janur.j2{animation-delay:-2.7s;animation-duration:6.3s}
@keyframes sway{0%,100%{transform:rotate(-2.6deg)}50%{transform:rotate(2.6deg)}}
.fl{transform-origin:50% 100%;animation:sway 4.8s ease-in-out infinite}
.fl.f2{animation-delay:-1.6s}.fl.f3{animation-delay:-3.2s}.fl.f4{animation-delay:-2.3s}.fl.f5{animation-delay:-4s}
/* ---------- kupu-kupu ---------- */
.bfly{position:absolute;width:34px;height:26px}
.bfly .wing{position:absolute;top:0;width:16px;height:26px;border-radius:80% 20% 80% 20%;transform-origin:right center}
.bfly .wing.l{left:1px;animation:flapL .32s ease-in-out infinite alternate}
.bfly .wing.r{right:1px;transform-origin:left center;animation:flapR .32s ease-in-out infinite alternate}
@keyframes flapL{from{transform:scaleX(1) rotate(-8deg)}to{transform:scaleX(.25) rotate(-8deg)}}
@keyframes flapR{from{transform:scaleX(1) rotate(8deg)}to{transform:scaleX(.25) rotate(8deg)}}
.bfly .bd{position:absolute;left:50%;top:4px;width:4px;height:18px;margin-left:-2px;background:#4a3525;border-radius:4px}
/* ---------- cover ---------- */
#cover{position:fixed;inset:0;z-index:100;display:flex;align-items:center;justify-content:center;transition:opacity .9s,visibility .9s;overflow-y:auto;
background:linear-gradient(180deg,#dcefd8 0%,#f2f7e4 40%,#fbf6e6 100%)}
#cover.open{opacity:0;visibility:hidden}
.cover-inner{text-align:center;padding:26px 20px 40px;width:100%;max-width:520px;position:relative}
.janur-arch{display:flex;justify-content:space-between;align-items:flex-start;margin:0 -6px}
.janur-arch .janur{width:44%}
.kicker{font-size:12px;letter-spacing:.5em;text-transform:uppercase;color:var(--daun-dk);margin-top:6px;font-weight:500}
.cover-names{font-family:'Pinyon Script',cursive;font-size:clamp(52px,14vw,76px);color:var(--tinta);margin:10px 0 2px;line-height:1.15}
.cover-date{font-family:'Cormorant Garamond',serif;font-size:21px;letter-spacing:.3em;color:var(--daun-dk)}
.flower-bed{display:block;width:100%;max-width:420px;margin:14px auto 0}
.kepada{margin-top:14px;font-family:'Cormorant Garamond',serif;font-size:21px;color:var(--tinta)}
.guest-box{margin:10px auto 0;max-width:330px;background:rgba(255,253,247,.95);border:1px solid var(--janur-dk);border-radius:14px;padding:13px 18px;box-shadow:0 8px 24px rgba(120,90,20,.18)}
.guest-box b{font-family:'Cormorant Garamond',serif;font-size:21px;color:#5a4a1f;font-weight:600}
.btn{display:inline-flex;align-items:center;gap:10px;margin-top:16px;padding:14px 40px;background:linear-gradient(135deg,#4c9455,#2c5f33);border:none;border-radius:999px;color:#fff;font-size:13px;letter-spacing:.22em;text-transform:uppercase;cursor:pointer;font-family:'Jost';font-weight:500;transition:.35s;box-shadow:0 10px 26px rgba(44,95,51,.35)}
.btn:hover{transform:translateY(-2px);box-shadow:0 14px 32px rgba(44,95,51,.45)}
.btn-line{display:inline-block;padding:12px 32px;border:1px solid var(--daun);color:var(--daun-dk);font-size:12px;letter-spacing:.26em;text-transform:uppercase;cursor:pointer;background:transparent;border-radius:999px;transition:.3s;text-decoration:none;font-family:'Jost'}
.btn-line:hover{background:rgba(62,125,68,.12)}
/* ---------- section ---------- */
section{padding:64px 26px;text-align:center;position:relative}
.sec-kicker{font-size:11px;letter-spacing:.44em;text-transform:uppercase;color:var(--daun);margin-bottom:10px;font-weight:500}
.sec-title{font-family:'Cormorant Garamond',serif;font-size:38px;font-weight:600;color:var(--tinta)}
.janur-div{display:flex;justify-content:center;margin:14px auto;max-width:230px}
.card{background:var(--kertas);border:1px solid rgba(62,125,68,.25);border-radius:18px;padding:34px 24px;margin:0 auto 18px;max-width:400px;box-shadow:0 14px 36px rgba(90,110,60,.12)}
#hero{min-height:94vh;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden}
.hero-hand{font-family:'Pinyon Script',cursive;font-size:34px;color:var(--daun-dk)}
.hero-names{font-family:'Pinyon Script',cursive;font-size:clamp(54px,15vw,80px);line-height:1.15;margin:12px 0;color:var(--tinta)}
.hero-names em{font-style:normal;color:var(--janur-dk);font-size:.72em}
.hero-date{letter-spacing:.3em;font-size:14px;color:var(--tinta-dim);text-transform:uppercase}
.cd-grid{display:flex;justify-content:center;gap:10px;margin-top:26px}
.cd{width:72px;padding:16px 0;background:var(--kertas);border:1px solid rgba(62,125,68,.3);border-radius:14px;box-shadow:0 8px 20px rgba(90,110,60,.1)}
.cd b{display:block;font-family:'Cormorant Garamond',serif;font-size:32px;color:var(--daun-dk)}
.cd span{font-size:10px;letter-spacing:.26em;text-transform:uppercase;color:var(--tinta-dim)}
.photo{width:150px;height:150px;border-radius:50%;object-fit:cover;margin:0 auto 16px;border:4px solid #fff;box-shadow:0 12px 30px rgba(90,110,60,.25);display:block}
.photo-fallback{width:150px;height:150px;border-radius:50%;margin:0 auto 16px;background:linear-gradient(135deg,#8fce93,#3e7d44);border:4px solid #fff;box-shadow:0 12px 30px rgba(90,110,60,.25);display:flex;align-items:center;justify-content:center;font-family:'Pinyon Script',cursive;font-size:60px;color:#fff}
.couple-card h3{font-family:'Pinyon Script',cursive;font-size:40px;color:var(--daun-dk);font-weight:400}
.couple-card .full{font-family:'Cormorant Garamond',serif;font-size:19px;margin:6px 0}
.couple-card p{font-size:14px;color:var(--tinta-dim);line-height:1.7}
.amp{font-family:'Pinyon Script',cursive;font-size:48px;color:var(--janur-dk);margin:2px 0}
.event h3{font-family:'Cormorant Garamond',serif;font-size:27px;letter-spacing:.08em;color:var(--daun-dk);text-transform:uppercase}
.event .date{font-family:'Cormorant Garamond',serif;font-size:20px;margin:12px 0 4px}
.event .time{letter-spacing:.22em;font-size:13px;color:var(--daun)}
.event .loc{font-size:14px;color:var(--tinta-dim);margin:12px 0 18px;line-height:1.7}
.g-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:24px}
.g-grid img{width:100%;height:190px;object-fit:cover;border-radius:14px;border:4px solid #fff;box-shadow:0 8px 22px rgba(90,110,60,.16);transition:.4s}
.g-grid img:hover{transform:scale(1.03) rotate(.5deg)}
.tl{max-width:400px;margin:26px auto 0;text-align:left}
.tl-item{background:var(--kertas);border-left:3px solid var(--janur);border-radius:0 14px 14px 0;padding:18px 20px;margin-bottom:14px;box-shadow:0 8px 22px rgba(90,110,60,.1)}
.tl-item h4{font-family:'Cormorant Garamond',serif;font-size:22px;color:var(--daun-dk)}
.tl-item p{font-size:14px;color:var(--tinta-dim);line-height:1.7;margin-top:6px}
.field{margin:0 auto 14px;max-width:380px;text-align:left}
.field label{display:block;font-size:10px;letter-spacing:.32em;text-transform:uppercase;color:var(--daun);margin-bottom:8px;font-weight:500}
.field input,.field select,.field textarea{width:100%;background:var(--kertas);border:1px solid rgba(62,125,68,.4);border-radius:12px;color:var(--tinta);padding:13px 16px;font-family:'Jost';font-size:15px;outline:none}
.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--daun);box-shadow:0 0 0 3px rgba(62,125,68,.12)}
.wish{background:var(--kertas);border:1px solid rgba(62,125,68,.25);border-radius:14px;padding:16px 18px;margin:0 auto 12px;max-width:400px;text-align:left;box-shadow:0 6px 18px rgba(90,110,60,.08)}
.wish b{font-family:'Cormorant Garamond',serif;color:var(--daun-dk);font-size:17px}
.wish .st{font-size:10px;letter-spacing:.2em;color:var(--janur-dk);text-transform:uppercase;margin-left:8px;font-weight:500}
.wish p{font-size:14px;color:var(--tinta-dim);margin-top:6px;line-height:1.6}
.bank{background:var(--kertas);border:1px dashed rgba(62,125,68,.5);border-radius:14px;padding:24px;margin:0 auto 14px;max-width:380px}
.bank .bk{font-size:11px;letter-spacing:.34em;color:var(--daun);text-transform:uppercase;font-weight:500}
.bank .no{font-family:'Cormorant Garamond',serif;font-size:30px;margin:10px 0 4px;letter-spacing:.06em}
.bank .an{font-size:14px;color:var(--tinta-dim)}
.copy{margin-top:12px}
footer{padding:56px 26px 100px;text-align:center}
footer .hand{font-size:42px;color:var(--daun-dk)}
#musBtn{position:fixed;bottom:22px;right:22px;z-index:90;width:52px;height:52px;border-radius:50%;border:none;background:linear-gradient(135deg,#4c9455,#2c5f33);color:#fff;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 26px rgba(44,95,51,.4)}

/* === fitur bawaan default: lightbox, menu, progress, musik === */
.pbar{position:fixed;top:0;left:0;right:0;height:3px;z-index:96;background:transparent}
.pbar i{display:block;height:100%;width:0;background:linear-gradient(90deg,#3e7d44,#2c5f33)}
.lb{position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.92);display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:.35s;padding:20px}
.lb.show{opacity:1;visibility:visible}
.lb img{max-width:100%;max-height:86vh;border:2px solid #3e7d44}
.lb-x{position:absolute;top:16px;right:22px;font-size:40px;color:#fff;cursor:pointer;line-height:1;z-index:201}
.fmenu{position:fixed;bottom:14px;left:50%;transform:translateX(-50%);z-index:90;display:flex;gap:2px;background:#fffffff2;border:1px solid #3e7d4455;border-radius:999px;padding:7px 9px;backdrop-filter:blur(8px);box-shadow:0 10px 30px rgba(0,0,0,.35);max-width:96vw;overflow-x:auto;scrollbar-width:none}
.fmenu::-webkit-scrollbar{display:none}
.fmenu a{display:flex;flex-direction:column;align-items:center;gap:3px;min-width:50px;padding:6px 7px;border-radius:12px;color:#3a4a2f;opacity:.6;text-decoration:none;font-size:9px;letter-spacing:.06em;transition:.25s;font-family:'Jost',sans-serif}
.fmenu a i{font-style:normal;font-size:17px;line-height:1}
.fmenu a.active{opacity:1;background:#3e7d442e;color:#2c5f33}
#musBtn.playing{outline:2px solid #2c5f33;outline-offset:2px}
.att-toggle{display:flex;gap:10px}
.att-toggle button{flex:1;padding:13px 8px;border:1px solid #3e7d44;background:transparent;color:#3a4a2f;font-family:'Jost',sans-serif;font-size:14px;cursor:pointer;transition:.25s;border-radius:10px}
.att-toggle button.on{background:#3e7d44;color:#ffffff;font-weight:500}
.wish-empty{text-align:center;opacity:.6;font-size:14px;padding:12px}
body.locked #fmenu,body.locked #musBtn,body.locked .pbar{opacity:0 !important;visibility:hidden !important;pointer-events:none !important}

.rv{opacity:0;transform:translateY(36px);transition:opacity .9s,transform .9s}
.rv.on{opacity:1;transform:none}
</style>
</head>
<body>
<div class="pbar"><i id="pbarFill"></i></div>
<canvas id="petals"></canvas>
<div id="fauna"></div>
<div class="wrap">

<!-- ================= COVER ================= -->
<div id="cover">
  <div class="cover-inner">
    <div class="janur-arch">
      <svg class="janur" viewBox="0 0 140 320">
        <path d="M70 312 C64 230 70 140 108 44" fill="none" stroke="#c9962a" stroke-width="6" stroke-linecap="round"/>
        <g fill="#f2c230" stroke="#c9962a" stroke-width="1.4">
          <path d="M68 282 C42 274 24 256 15 234 C39 238 60 252 70 268 Z"/>
          <path d="M72 282 C98 274 116 256 125 234 C101 238 80 252 70 268 Z"/>
          <path d="M67 244 C43 236 27 220 19 200 C41 204 60 216 69 230 Z"/>
          <path d="M71 244 C95 236 111 220 119 200 C97 204 78 216 69 230 Z"/>
          <path d="M67 206 C46 198 32 184 25 166 C45 170 62 182 69 194 Z"/>
          <path d="M71 206 C92 198 106 184 113 166 C93 170 76 182 69 194 Z"/>
          <path d="M68 168 C50 160 38 148 32 132 C50 136 64 146 70 158 Z"/>
          <path d="M72 168 C90 160 102 148 108 132 C90 136 76 146 70 158 Z"/>
          <path d="M71 130 C56 122 47 112 43 98 C57 102 68 110 73 120 Z"/>
          <path d="M75 130 C90 122 99 112 103 98 C89 102 78 110 73 120 Z"/>
          <path d="M78 94 C68 86 62 78 60 66 C70 70 78 78 82 88 Z"/>
          <path d="M84 94 C94 86 100 78 102 66 C92 70 84 78 80 88 Z"/>
        </g>
        <path d="M108 44 q6 -18 -4 -30 q14 4 12 22" fill="none" stroke="#c9962a" stroke-width="3" stroke-linecap="round"/>
      </svg>
      <svg class="janur j2" viewBox="0 0 140 320" style="transform:scaleX(-1)">
        <path d="M70 312 C64 230 70 140 108 44" fill="none" stroke="#c9962a" stroke-width="6" stroke-linecap="round"/>
        <g fill="#f2c230" stroke="#c9962a" stroke-width="1.4">
          <path d="M68 282 C42 274 24 256 15 234 C39 238 60 252 70 268 Z"/>
          <path d="M72 282 C98 274 116 256 125 234 C101 238 80 252 70 268 Z"/>
          <path d="M67 244 C43 236 27 220 19 200 C41 204 60 216 69 230 Z"/>
          <path d="M71 244 C95 236 111 220 119 200 C97 204 78 216 69 230 Z"/>
          <path d="M67 206 C46 198 32 184 25 166 C45 170 62 182 69 194 Z"/>
          <path d="M71 206 C92 198 106 184 113 166 C93 170 76 182 69 194 Z"/>
          <path d="M68 168 C50 160 38 148 32 132 C50 136 64 146 70 158 Z"/>
          <path d="M72 168 C90 160 102 148 108 132 C90 136 76 146 70 158 Z"/>
          <path d="M71 130 C56 122 47 112 43 98 C57 102 68 110 73 120 Z"/>
          <path d="M75 130 C90 122 99 112 103 98 C89 102 78 110 73 120 Z"/>
          <path d="M78 94 C68 86 62 78 60 66 C70 70 78 78 82 88 Z"/>
          <path d="M84 94 C94 86 100 78 102 66 C92 70 84 78 80 88 Z"/>
        </g>
        <path d="M108 44 q6 -18 -4 -30 q14 4 12 22" fill="none" stroke="#c9962a" stroke-width="3" stroke-linecap="round"/>
      </svg>
    </div>

    <div class="kicker">The Wedding Of</div>
    <div class="cover-names">{{ $brideNick }} &amp; {{ $groomNick }}</div>
    <div class="cover-date">{{ mcDate($wDate) }}</div>

    <svg class="flower-bed" viewBox="0 0 420 120">
      <g stroke="#3e7d44" stroke-width="4" stroke-linecap="round">
        <line x1="60" y1="118" x2="60" y2="52"/><line x1="140" y1="118" x2="140" y2="38"/>
        <line x1="220" y1="118" x2="220" y2="46"/><line x1="300" y1="118" x2="300" y2="38"/>
        <line x1="372" y1="118" x2="372" y2="54"/>
      </g>
      <g fill="#3e7d44">
        <ellipse cx="48" cy="88" rx="14" ry="7" transform="rotate(-28 48 88)"/><ellipse cx="72" cy="88" rx="14" ry="7" transform="rotate(28 72 88)"/>
        <ellipse cx="128" cy="76" rx="14" ry="7" transform="rotate(-28 128 76)"/><ellipse cx="152" cy="76" rx="14" ry="7" transform="rotate(28 152 76)"/>
        <ellipse cx="208" cy="84" rx="14" ry="7" transform="rotate(-28 208 84)"/><ellipse cx="232" cy="84" rx="14" ry="7" transform="rotate(28 232 84)"/>
        <ellipse cx="288" cy="76" rx="14" ry="7" transform="rotate(-28 288 76)"/><ellipse cx="312" cy="76" rx="14" ry="7" transform="rotate(28 312 76)"/>
        <ellipse cx="360" cy="90" rx="14" ry="7" transform="rotate(-28 360 90)"/><ellipse cx="384" cy="90" rx="14" ry="7" transform="rotate(28 384 90)"/>
      </g>
      <g class="fl"><g transform="translate(60 40)">
        <ellipse cx="0" cy="-13" rx="9" ry="13" fill="#e88ca0"/><ellipse cx="12" cy="-4" rx="9" ry="13" fill="#e88ca0" transform="rotate(72 12 -4)"/><ellipse cx="7" cy="11" rx="9" ry="13" fill="#ef9fb2" transform="rotate(144 7 11)"/><ellipse cx="-7" cy="11" rx="9" ry="13" fill="#ef9fb2" transform="rotate(216 -7 11)"/><ellipse cx="-12" cy="-4" rx="9" ry="13" fill="#e88ca0" transform="rotate(288 -12 -4)"/><circle r="6.5" fill="#f2c230"/>
      </g></g>
      <g class="fl f2"><g transform="translate(140 26) scale(.9)">
        <ellipse cx="0" cy="-13" rx="9" ry="13" fill="#fdfbf5" stroke="#e3d9c2" stroke-width="1"/><ellipse cx="12" cy="-4" rx="9" ry="13" fill="#fdfbf5" stroke="#e3d9c2" transform="rotate(72 12 -4)"/><ellipse cx="7" cy="11" rx="9" ry="13" fill="#fdfbf5" stroke="#e3d9c2" transform="rotate(144 7 11)"/><ellipse cx="-7" cy="11" rx="9" ry="13" fill="#fdfbf5" stroke="#e3d9c2" transform="rotate(216 -7 11)"/><ellipse cx="-12" cy="-4" rx="9" ry="13" fill="#fdfbf5" stroke="#e3d9c2" transform="rotate(288 -12 -4)"/><circle r="6.5" fill="#f2c230"/>
      </g></g>
      <g class="fl f3"><g transform="translate(220 34)">
        <ellipse cx="0" cy="-13" rx="9" ry="13" fill="#b79ce0"/><ellipse cx="12" cy="-4" rx="9" ry="13" fill="#b79ce0" transform="rotate(72 12 -4)"/><ellipse cx="7" cy="11" rx="9" ry="13" fill="#c4abe6" transform="rotate(144 7 11)"/><ellipse cx="-7" cy="11" rx="9" ry="13" fill="#c4abe6" transform="rotate(216 -7 11)"/><ellipse cx="-12" cy="-4" rx="9" ry="13" fill="#b79ce0" transform="rotate(288 -12 -4)"/><circle r="6.5" fill="#f2c230"/>
      </g></g>
      <g class="fl f4"><g transform="translate(300 26) scale(.9)">
        <ellipse cx="0" cy="-13" rx="9" ry="13" fill="#e88ca0"/><ellipse cx="12" cy="-4" rx="9" ry="13" fill="#e88ca0" transform="rotate(72 12 -4)"/><ellipse cx="7" cy="11" rx="9" ry="13" fill="#ef9fb2" transform="rotate(144 7 11)"/><ellipse cx="-7" cy="11" rx="9" ry="13" fill="#ef9fb2" transform="rotate(216 -7 11)"/><ellipse cx="-12" cy="-4" rx="9" ry="13" fill="#e88ca0" transform="rotate(288 -12 -4)"/><circle r="6.5" fill="#f2c230"/>
      </g></g>
      <g class="fl f5"><g transform="translate(372 42)">
        <ellipse cx="0" cy="-13" rx="9" ry="13" fill="#fdfbf5" stroke="#e3d9c2" stroke-width="1"/><ellipse cx="12" cy="-4" rx="9" ry="13" fill="#fdfbf5" stroke="#e3d9c2" transform="rotate(72 12 -4)"/><ellipse cx="7" cy="11" rx="9" ry="13" fill="#fdfbf5" stroke="#e3d9c2" transform="rotate(144 7 11)"/><ellipse cx="-7" cy="11" rx="9" ry="13" fill="#fdfbf5" stroke="#e3d9c2" transform="rotate(216 -7 11)"/><ellipse cx="-12" cy="-4" rx="9" ry="13" fill="#fdfbf5" stroke="#e3d9c2" transform="rotate(288 -12 -4)"/><circle r="6.5" fill="#f2c230"/>
      </g></g>
      <ellipse cx="210" cy="116" rx="200" ry="10" fill="#3e7d44" opacity=".35"/>
    </svg>

    <div class="kepada">Kepada Yth.<br>Bapak/Ibu/Saudara/i</div>
    <div class="guest-box"><b>{{ $guestName }}</b></div>
    <div><button class="btn" onclick="openInv()">&#9993; &nbsp;Buka Undangan</button></div>
  </div>
</div>

<!-- ================= HERO ================= -->
<section id="hero">
  <div class="sun"></div>
  <div class="cloud c1"></div><div class="cloud c2"></div><div class="cloud c3"></div>
  <div class="bird b1"><svg width="52" height="20" viewBox="0 0 52 20"><g class="wings" stroke="#2c5f33" stroke-width="3" fill="none" stroke-linecap="round"><path d="M2 14 Q14 2 26 12 Q38 2 50 14"/></g></svg></div>
  <div class="bird b2"><svg width="52" height="20" viewBox="0 0 52 20"><g class="wings" stroke="#2c5f33" stroke-width="3" fill="none" stroke-linecap="round"><path d="M2 14 Q14 2 26 12 Q38 2 50 14"/></g></svg></div>
  <div class="bird b3"><svg width="52" height="20" viewBox="0 0 52 20"><g class="wings" stroke="#3e7d44" stroke-width="3" fill="none" stroke-linecap="round"><path d="M2 14 Q14 2 26 12 Q38 2 50 14"/></g></svg></div>
  <div class="rv"><div class="hero-hand">The Wedding Of</div></div>
  <div class="rv"><h1 class="hero-names">{{ $brideNick }} <em>&amp;</em> {{ $groomNick }}</h1></div>
  <div class="rv">
    <svg class="janur-div" viewBox="0 0 230 44" style="width:180px">
      <path d="M8 34 Q60 30 115 22 Q170 30 222 34" fill="none" stroke="#c9962a" stroke-width="3" stroke-linecap="round"/>
      <g fill="#f2c230" stroke="#c9962a" stroke-width="1">
        <path d="M40 32 l-9 -14 12 3 Z"/><path d="M70 29 l-9 -14 12 3 Z"/><path d="M100 26 l-9 -14 12 3 Z"/>
        <path d="M130 26 l9 -14 -12 3 Z"/><path d="M160 29 l9 -14 -12 3 Z"/><path d="M190 32 l9 -14 -12 3 Z"/>
      </g>
      <circle cx="115" cy="16" r="6" fill="#e88ca0"/><circle cx="115" cy="16" r="2.6" fill="#f2c230"/>
    </svg>
  </div>
  <div class="rv"><div class="hero-date">{{ mcDate($wDate) }}</div>
@if(mcGet('quotes'))
<div class="rv" style="max-width:420px;margin:20px auto 0;padding:0 8px">
<p style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:19px;line-height:1.7;opacity:.85">“{{ mcGet('quotes') }}”</p>
</div>
@endif</div>
</section>

<!-- COUNTDOWN -->
<section>
  <div class="rv"><div class="sec-kicker">Menuju Hari Bahagia</div><div class="sec-title serif">Hitung Mundur</div></div>
  <div class="cd-grid rv">
    <div class="cd"><b id="cdD">0</b><span>Hari</span></div>
    <div class="cd"><b id="cdH">0</b><span>Jam</span></div>
    <div class="cd"><b id="cdM">0</b><span>Menit</span></div>
    <div class="cd"><b id="cdS">0</b><span>Detik</span></div>
  </div>
</section>

<!-- MEMPELAI -->
<section id="mempelai">
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
<section id="acara">
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
    @if(mcGet('reception_map_link'))<a class="btn-line" target="_blank" href="{{ mcGet('reception_map_link') }}">Lihat Peta</a>@endif@if(mcGet('live_stream_link'))<a class="btn-line" target="_blank" href="{{ mcGet('live_stream_link') }}" style="margin-left:8px">Live Streaming</a>@endif
  </div>
</section>

<!-- GALERI -->
@if(!empty($gallery))
<section id="galeri">
  <div class="rv"><div class="sec-kicker">Momen Indah</div><div class="sec-title serif">Galeri</div></div>
  <div class="g-grid rv">
    @foreach(array_slice($gallery, 0, 8) as $img)<img src="{{ $img }}" alt="Galeri {{ $brideNick }} & {{ $groomNick }}" loading="lazy">@endforeach
  </div>
</section>
@endif

<!-- KISAH -->
@if(!empty($stories) && count($stories))
<section id="kisah">
  <div class="rv"><div class="sec-kicker">Perjalanan Kami</div><div class="sec-title serif">Kisah Cinta</div></div>
  <div class="tl">
    @foreach($stories as $st)
    <div class="tl-item rv"><h4>{{ $st->title ?? 'Cerita' }}</h4><p>{{ $st->description ?? $st->content ?? '' }}</p></div>
    @endforeach
  </div>
</section>
@endif

<!-- RSVP -->
<section id="rsvp">
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
<section id="ucapan">
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
<section id="gift">
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

<footer>
  <div class="rv"><div class="hand">Terima Kasih</div>
  <p style="color:var(--tinta-dim);font-size:14px;line-height:1.8;max-width:380px;margin:14px auto">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>
  <div class="serif" style="font-size:22px;color:var(--daun-dk)">{{ $brideNick }} &amp; {{ $groomNick }}</div></div>
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
