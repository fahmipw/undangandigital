<?php
/* ============================================================
   TEMA PREMIUM: NEON METROPOLIS
   Dipilih via settings: theme = neon-metropolis
   Animasi 3D: kartu tilt mengikuti pointer, skyline 3 lapis
   parallax, kendaraan terbang, hujan neon, neon sign flicker
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
<title>{{ $brideNick }} &amp; {{ $groomNick }} — Neon Metropolis</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root{--bg0:#0a0a18;--bg1:#12122b;--cyan:#00e5ff;--mgnt:#ff2fb3;--tinta:#eaf6ff;--tinta-dim:rgba(234,246,255,.6);--kaca:rgba(16,16,42,.72)}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{font-family:'Jost',sans-serif;background:linear-gradient(180deg,var(--bg0) 0%,var(--bg1) 55%,#0a0a18 100%);color:var(--tinta);overflow-x:hidden;font-weight:300;min-height:100vh}
#petals{position:fixed;inset:0;z-index:3;pointer-events:none}
/* ---------- kota 3 lapis ---------- */
#citybg{position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden}
.city-layer{position:absolute;left:0;bottom:-2px;width:100%;height:44vh;will-change:transform}
.city-layer.l0{opacity:.5;filter:blur(2.5px)}
.city-layer.l1{opacity:.75;filter:blur(1px)}
.city-layer.l2{opacity:1}
.wflk{animation:wflk 3.2s infinite}
.wflk.d1{animation-delay:-1.1s}.wflk.d2{animation-delay:-2.2s}
@keyframes wflk{0%,100%{opacity:.9}45%{opacity:.9}50%{opacity:.15}55%{opacity:.9}}
.blink{animation:blink 1.6s steps(2,start) infinite}
@keyframes blink{50%{opacity:.1}}
.vig{position:fixed;inset:0;z-index:1;pointer-events:none;background:radial-gradient(120% 100% at 50% 30%,transparent 50%,rgba(4,4,12,.8) 100%)}
.wrap{position:relative;z-index:2;max-width:520px;margin:0 auto}
/* ---------- neon ---------- */
.neon{font-family:'Orbitron',sans-serif;color:#f4fdff;text-shadow:0 0 4px #fff,0 0 14px var(--cyan),0 0 38px var(--cyan),0 0 80px var(--cyan);animation:flick 4.5s infinite}
.neon-p{font-family:'Orbitron',sans-serif;color:#fff5fb;text-shadow:0 0 4px #fff,0 0 14px var(--mgnt),0 0 38px var(--mgnt),0 0 80px var(--mgnt);animation:flick 5.5s infinite}
@keyframes flick{0%,100%{opacity:1}3%{opacity:.55}5%{opacity:1}42%{opacity:1}44%{opacity:.35}46%{opacity:1}47.5%{opacity:.65}49%{opacity:1}}
.nline{height:2px;width:120px;margin:14px auto;background:linear-gradient(90deg,transparent,var(--cyan),var(--mgnt),transparent);box-shadow:0 0 12px var(--cyan)}
.nheart{width:34px;height:32px;filter:drop-shadow(0 0 6px var(--mgnt)) drop-shadow(0 0 16px var(--mgnt));animation:beat 1.6s ease-in-out infinite}
@keyframes beat{0%,100%{transform:scale(1)}12%{transform:scale(1.2)}24%{transform:scale(1)}36%{transform:scale(1.12)}48%{transform:scale(1)}}
/* ---------- kendaraan terbang ---------- */
.flyer{position:fixed;left:0;z-index:2;pointer-events:none;animation:flyby linear infinite}
.flyer svg{display:block;overflow:visible}
.f1{top:16%;animation-duration:15s}
.f1 svg{width:120px}
.f2{top:33%;animation-duration:24s;animation-delay:-10s;opacity:.65}
.f2 svg{width:66px}
.f3{top:7%;animation-duration:19s;animation-delay:-5s;opacity:.85}
.f3 svg{width:92px}
@keyframes flyby{from{transform:translateX(-18vw)}to{transform:translateX(118vw)}}
/* ---------- cover ---------- */
#cover{position:fixed;inset:0;z-index:100;display:flex;align-items:center;justify-content:center;transition:opacity .9s,visibility .9s;overflow-y:auto;background:linear-gradient(180deg,#0a0a18 0%,#151534 55%,#0a0a18 100%)}
#cover.open{opacity:0;visibility:hidden}
.cover-sky{position:absolute;left:0;bottom:0;width:100%;height:34vh;opacity:.9;pointer-events:none}
.cover-inner{text-align:center;padding:30px 22px 46px;width:100%;max-width:520px;position:relative}
.kicker{font-size:11px;letter-spacing:.5em;text-transform:uppercase;color:var(--cyan);margin-bottom:8px;font-weight:500;text-shadow:0 0 12px var(--cyan)}
.cover-names{font-family:'Orbitron',sans-serif;font-weight:900;font-size:clamp(30px,8.6vw,44px);line-height:1.5;margin:10px 0 4px;color:#f4fdff;text-shadow:0 0 4px #fff,0 0 16px var(--cyan),0 0 44px var(--cyan);animation:flick 6s infinite}
.cover-names .amp{color:#fff5fb;text-shadow:0 0 4px #fff,0 0 16px var(--mgnt),0 0 44px var(--mgnt);font-size:.7em}
.cover-date{font-size:15px;letter-spacing:.3em;color:var(--tinta-dim);text-transform:uppercase}
.kepada{margin-top:22px;font-size:17px;color:var(--tinta)}
.guest-box{margin:10px auto 0;max-width:330px;background:rgba(10,10,26,.8);border:1px solid var(--cyan);border-radius:10px;padding:13px 18px;box-shadow:0 0 24px rgba(0,229,255,.3),inset 0 0 18px rgba(0,229,255,.07)}
.guest-box b{font-family:'Orbitron',sans-serif;font-size:18px;font-weight:700;color:#fff;letter-spacing:.06em;text-shadow:0 0 12px var(--cyan)}
.btn{display:inline-flex;align-items:center;gap:10px;margin-top:18px;padding:14px 40px;background:linear-gradient(135deg,var(--cyan),#0090c8 55%,var(--mgnt));border:none;border-radius:999px;color:#06121a;font-size:12px;letter-spacing:.22em;text-transform:uppercase;cursor:pointer;font-family:'Jost';font-weight:500;transition:.35s;box-shadow:0 0 28px rgba(0,229,255,.45)}
.btn:hover{transform:translateY(-2px);box-shadow:0 0 36px rgba(255,47,179,.5)}
.btn-line{display:inline-block;padding:12px 32px;border:1px solid var(--mgnt);color:#ffd7ef;font-size:11px;letter-spacing:.26em;text-transform:uppercase;cursor:pointer;background:transparent;border-radius:999px;transition:.3s;text-decoration:none;font-family:'Jost';text-shadow:0 0 10px var(--mgnt)}
.btn-line:hover{background:rgba(255,47,179,.14);box-shadow:0 0 18px rgba(255,47,179,.35)}
/* ---------- section ---------- */
section{padding:64px 26px;text-align:center;position:relative;perspective:1200px}
.sec-kicker{font-size:10px;letter-spacing:.44em;text-transform:uppercase;color:var(--cyan);margin-bottom:12px;font-weight:500;text-shadow:0 0 10px var(--cyan)}
.sec-title{font-family:'Orbitron',sans-serif;font-size:27px;font-weight:700;color:#f4fdff;letter-spacing:.08em;text-shadow:0 0 12px var(--cyan),0 0 34px var(--cyan)}
.card{background:var(--kaca);border:1px solid rgba(0,229,255,.35);border-radius:14px;padding:34px 24px;margin:0 auto 18px;max-width:400px;box-shadow:0 14px 44px rgba(0,0,0,.55),0 0 26px rgba(0,229,255,.12),inset 0 0 30px rgba(0,229,255,.05);backdrop-filter:blur(6px)}
.t3d{transform-style:preserve-3d;will-change:transform}
.t3d .tz{transform:translateZ(42px)}
#hero{min-height:96vh;display:flex;flex-direction:column;justify-content:center;position:relative}
.hero-hand{font-size:13px;letter-spacing:.5em;text-transform:uppercase;color:var(--mgnt);text-shadow:0 0 12px var(--mgnt)}
.hero-names{font-family:'Orbitron',sans-serif;font-weight:900;font-size:clamp(32px,9.4vw,48px);line-height:1.55;margin:12px 0;color:#f4fdff;text-shadow:0 0 4px #fff,0 0 16px var(--cyan),0 0 46px var(--cyan);animation:flick 5s infinite}
.hero-names .amp{font-size:.66em;color:#fff5fb;text-shadow:0 0 4px #fff,0 0 16px var(--mgnt),0 0 44px var(--mgnt)}
.hero-date{letter-spacing:.3em;font-size:13px;color:var(--tinta-dim);text-transform:uppercase;margin-top:6px}
.cd-grid{display:flex;justify-content:center;gap:10px;margin-top:26px;perspective:800px}
.cd{width:72px;padding:16px 0;background:rgba(10,10,26,.85);border:1px solid rgba(255,47,179,.5);border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.5),0 0 18px rgba(255,47,179,.2)}
.cd b{display:block;font-family:'Orbitron',sans-serif;font-size:26px;color:#fff;text-shadow:0 0 12px var(--mgnt)}
.cd span{font-size:9px;letter-spacing:.26em;text-transform:uppercase;color:var(--tinta-dim)}
.photo{width:150px;height:150px;border-radius:50%;object-fit:cover;margin:0 auto 16px;border:3px solid var(--cyan);box-shadow:0 0 30px rgba(0,229,255,.45);display:block}
.photo-fallback{width:150px;height:150px;border-radius:50%;margin:0 auto 16px;background:radial-gradient(circle at 35% 30%,#1c2b52,#0a0a18);border:3px solid var(--cyan);box-shadow:0 0 30px rgba(0,229,255,.45);display:flex;align-items:center;justify-content:center;font-family:'Orbitron',sans-serif;font-size:48px;color:#fff;text-shadow:0 0 14px var(--cyan)}
.couple-card h3{font-family:'Orbitron',sans-serif;font-size:24px;font-weight:700;color:#fff;letter-spacing:.05em;text-shadow:0 0 14px var(--cyan)}
.couple-card .full{font-size:16px;margin:8px 0;color:var(--tinta)}
.couple-card p{font-size:14px;color:var(--tinta-dim);line-height:1.7}
.amp{font-size:38px;color:var(--mgnt);margin:2px 0;text-shadow:0 0 20px var(--mgnt)}
.event h3{font-family:'Orbitron',sans-serif;font-size:19px;letter-spacing:.1em;color:#fff;text-shadow:0 0 12px var(--cyan)}
.event .date{font-size:18px;margin:12px 0 4px}
.event .time{letter-spacing:.22em;font-size:12px;color:var(--cyan);text-shadow:0 0 10px var(--cyan)}
.event .loc{font-size:14px;color:var(--tinta-dim);margin:12px 0 18px;line-height:1.7}
.g-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:24px}
.g-grid img{width:100%;height:190px;object-fit:cover;border-radius:12px;border:2px solid rgba(0,229,255,.5);box-shadow:0 8px 22px rgba(0,0,0,.5);transition:.4s}
.g-grid img:hover{transform:scale(1.04);box-shadow:0 0 26px rgba(0,229,255,.4)}
.tl{max-width:400px;margin:26px auto 0;text-align:left}
.tl-item{background:var(--kaca);border-left:3px solid var(--mgnt);border-radius:0 12px 12px 0;padding:18px 20px;margin-bottom:14px;box-shadow:0 8px 22px rgba(0,0,0,.45),0 0 16px rgba(255,47,179,.12);display:flex;gap:14px;align-items:flex-start}
.tl-item .nheart{flex:0 0 auto;margin-top:2px}
.tl-item h4{font-family:'Orbitron',sans-serif;font-size:16px;color:#fff;letter-spacing:.04em;text-shadow:0 0 10px var(--mgnt)}
.tl-item p{font-size:14px;color:var(--tinta-dim);line-height:1.7;margin-top:6px}
.field{margin:0 auto 14px;max-width:380px;text-align:left}
.field label{display:block;font-size:10px;letter-spacing:.32em;text-transform:uppercase;color:var(--cyan);margin-bottom:8px;font-weight:500;text-shadow:0 0 8px var(--cyan)}
.field input,.field select,.field textarea{width:100%;background:rgba(8,8,22,.9);border:1px solid rgba(0,229,255,.45);border-radius:10px;color:var(--tinta);padding:13px 16px;font-family:'Jost';font-size:15px;outline:none}
.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--cyan);box-shadow:0 0 0 3px rgba(0,229,255,.15),0 0 16px rgba(0,229,255,.25)}
.field select option{background:#0d0d24}
.wish{background:var(--kaca);border:1px solid rgba(255,47,179,.35);border-radius:12px;padding:16px 18px;margin:0 auto 12px;max-width:400px;text-align:left;box-shadow:0 0 14px rgba(255,47,179,.1)}
.wish-head{display:flex;justify-content:space-between;align-items:baseline;gap:10px;margin-bottom:6px}
.wtime{font-size:11px;opacity:.55;white-space:nowrap}
.wish b{color:#fff;font-size:16px;text-shadow:0 0 8px var(--mgnt)}
.wish .st{font-size:10px;letter-spacing:.2em;color:var(--mgnt);text-transform:uppercase;margin-left:8px;font-weight:500}
.wish p{font-size:14px;color:var(--tinta-dim);margin-top:6px;line-height:1.6}
.bank{background:var(--kaca);border:1px dashed rgba(0,229,255,.55);border-radius:12px;padding:24px;margin:0 auto 14px;max-width:380px;box-shadow:0 0 18px rgba(0,229,255,.12)}
.bank .bk{font-size:11px;letter-spacing:.34em;color:var(--cyan);text-transform:uppercase;font-weight:500}
.bank .no{font-family:'Orbitron',sans-serif;font-size:24px;margin:10px 0 4px;letter-spacing:.06em;color:#fff;text-shadow:0 0 12px var(--cyan)}
.bank .an{font-size:14px;color:var(--tinta-dim)}
.copy{margin-top:12px}
footer{padding:56px 26px 110px;text-align:center}
footer .neon{font-size:34px;font-weight:700}
#musBtn{position:fixed;bottom:22px;right:22px;z-index:90;width:52px;height:52px;border-radius:50%;border:1px solid var(--cyan);background:rgba(8,8,22,.9);color:var(--cyan);font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 0 22px rgba(0,229,255,.4);text-shadow:0 0 10px var(--cyan)}

/* === fitur bawaan default: lightbox, menu, progress, musik === */
.pbar{position:fixed;top:0;left:0;right:0;height:3px;z-index:96;background:transparent}
.pbar i{display:block;height:100%;width:0;background:linear-gradient(90deg,#00e5ff,#ff2fb3)}
.lb{position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.92);display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:.35s;padding:20px;touch-action:pan-y}
#lbImg{max-width:100%;max-height:86vh;border-radius:8px;will-change:transform,opacity}
.lb.show{opacity:1;visibility:visible}
.lb img{max-width:100%;max-height:86vh;border:2px solid #00e5ff}
.lb-x{position:absolute;top:16px;right:22px;font-size:40px;color:#fff;cursor:pointer;line-height:1;z-index:201}
.lb-prev,.lb-next{position:absolute;top:50%;transform:translateY(-50%);font-size:32px;color:#fff;cursor:pointer;padding:16px 12px;z-index:201;user-select:none;opacity:.85;line-height:1}
.lb-prev{left:6px}.lb-next{right:6px}.lb-prev:hover,.lb-next:hover{opacity:1}
.lb-count{position:absolute;bottom:16px;left:50%;transform:translateX(-50%);color:#fff;font-size:13px;letter-spacing:.15em;z-index:201;font-family:inherit}
.fmenu{position:fixed;bottom:14px;left:50%;transform:translateX(-50%);z-index:90;display:flex;gap:2px;background:#0a0a18f2;border:1px solid #00e5ff55;border-radius:999px;padding:7px 9px;backdrop-filter:blur(8px);box-shadow:0 10px 30px rgba(0,0,0,.35);max-width:96vw;overflow-x:auto;scrollbar-width:none}
.fmenu::-webkit-scrollbar{display:none}
.fmenu a{display:flex;flex-direction:column;align-items:center;gap:3px;min-width:50px;padding:6px 7px;border-radius:12px;color:#eaf6ff;opacity:.6;text-decoration:none;font-size:9px;letter-spacing:.06em;transition:.25s;font-family:'Jost',sans-serif}
.fmenu a i{font-style:normal;font-size:17px;line-height:1}
.fmenu a.active{opacity:1;background:#00e5ff2e;color:#ff2fb3}
#musBtn.playing{outline:2px solid #ff2fb3;outline-offset:2px}
.att-toggle{display:flex;gap:10px}
.att-toggle button{flex:1;padding:13px 8px;border:1px solid #00e5ff;background:transparent;color:#eaf6ff;font-family:'Jost',sans-serif;font-size:14px;cursor:pointer;transition:.25s;border-radius:10px}
.att-toggle button.on{background:#00e5ff;color:#0a0a18;font-weight:500}
.wish-empty{text-align:center;opacity:.6;font-size:14px;padding:12px}
body.locked #fmenu,body.locked #musBtn,body.locked .pbar{opacity:0 !important;visibility:hidden !important;pointer-events:none !important}

.rv{opacity:0;transform:translateY(36px);transition:opacity .9s,transform .9s}
.rv.on{opacity:1;transform:none}
@media (prefers-reduced-motion:reduce){.flyer,.neon,.neon-p,.nheart{animation:none}}
</style>
</head>
<body>
<div class="pbar"><i id="pbarFill"></i></div>
<svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs>
<pattern id="winC" width="18" height="22" patternUnits="userSpaceOnUse"><rect x="5" y="6" width="6" height="8" fill="#00e5ff" opacity=".85"/></pattern>
<pattern id="winM" width="24" height="28" patternUnits="userSpaceOnUse"><rect x="6" y="7" width="7" height="9" fill="#ff2fb3" opacity=".8"/></pattern>
<pattern id="winY" width="30" height="26" patternUnits="userSpaceOnUse"><rect x="7" y="6" width="7" height="8" fill="#ffe66d" opacity=".75"/></pattern>
<linearGradient id="trail" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#00e5ff" stop-opacity="0"/><stop offset="1" stop-color="#00e5ff"/></linearGradient>
<linearGradient id="trailM" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#ff2fb3" stop-opacity="0"/><stop offset="1" stop-color="#ff2fb3"/></linearGradient>
</defs></svg>

<div id="citybg" aria-hidden="true">
<svg class="city-layer l0" data-depth="0.05" viewBox="0 0 1200 340" preserveAspectRatio="xMidYMax slice">
<g fill="#14142c">
<rect x="0" y="190" width="100" height="150"/><rect x="105" y="120" width="80" height="220"/><rect x="190" y="210" width="120" height="130"/><rect x="315" y="90" width="85" height="250"/><rect x="405" y="170" width="110" height="170"/><rect x="520" y="60" width="80" height="280"/><rect x="605" y="190" width="125" height="150"/><rect x="735" y="110" width="90" height="230"/><rect x="830" y="180" width="115" height="160"/><rect x="950" y="80" width="85" height="260"/><rect x="1040" y="150" width="75" height="190"/><rect x="1120" y="100" width="80" height="240"/>
</g>
<g fill="url(#winC)" opacity=".5">
<rect x="8" y="202" width="84" height="138"/><rect x="113" y="132" width="64" height="208"/><rect x="198" y="222" width="104" height="118"/><rect x="323" y="102" width="69" height="238"/><rect x="413" y="182" width="94" height="158"/><rect x="528" y="72" width="64" height="268"/><rect x="613" y="202" width="109" height="138"/><rect x="743" y="122" width="74" height="218"/><rect x="838" y="192" width="99" height="148"/><rect x="958" y="92" width="69" height="248"/><rect x="1048" y="162" width="59" height="178"/><rect x="1128" y="112" width="64" height="228"/>
</g>
<circle class="blink" cx="560" cy="52" r="4" fill="#ff3b3b"/>
</svg>
<svg class="city-layer l1" data-depth="0.11" viewBox="0 0 1200 340" preserveAspectRatio="xMidYMax slice">
<g fill="#0e0e22">
<rect x="30" y="150" width="90" height="190"/><rect x="140" y="230" width="130" height="110"/><rect x="290" y="120" width="75" height="220"/><rect x="385" y="200" width="120" height="140"/><rect x="525" y="90" width="95" height="250"/><rect x="640" y="170" width="110" height="170"/><rect x="770" y="60" width="80" height="280"/><rect x="870" y="190" width="125" height="150"/><rect x="1015" y="110" width="90" height="230"/><rect x="1125" y="180" width="75" height="160"/>
</g>
<g fill="url(#winM)" opacity=".55">
<rect x="38" y="162" width="74" height="178"/><rect x="298" y="132" width="59" height="208"/><rect x="533" y="102" width="79" height="238"/><rect x="778" y="72" width="64" height="268"/><rect x="1023" y="122" width="74" height="218"/>
</g>
<g fill="url(#winY)" opacity=".5">
<rect x="148" y="242" width="114" height="98"/><rect x="393" y="212" width="104" height="128"/><rect x="648" y="182" width="94" height="158"/><rect x="878" y="202" width="109" height="138"/>
</g>
<rect class="wflk" x="548" y="140" width="7" height="9" fill="#00e5ff"/><rect class="wflk d1" x="790" y="110" width="7" height="9" fill="#ff2fb3"/><rect class="wflk d2" x="305" y="170" width="7" height="9" fill="#ffe66d"/>
<circle class="blink" cx="810" cy="52" r="4" fill="#ff3b3b"/><circle class="blink" cx="572" cy="82" r="3.5" fill="#ff3b3b"/>
</svg>
<svg class="city-layer l2" data-depth="0.19" viewBox="0 0 1200 340" preserveAspectRatio="xMidYMax slice">
<g fill="#080814">
<rect x="0" y="220" width="110" height="120"/><rect x="130" y="140" width="85" height="200"/><rect x="235" y="250" width="140" height="90"/><rect x="395" y="110" width="90" height="230"/><rect x="505" y="190" width="130" height="150"/><rect x="655" y="70" width="85" height="270"/><rect x="760" y="160" width="120" height="180"/><rect x="900" y="230" width="110" height="110"/><rect x="1030" y="120" width="95" height="220"/><rect x="1145" y="200" width="55" height="140"/>
</g>
<g fill="url(#winC)" opacity=".7">
<rect x="8" y="232" width="94" height="108"/><rect x="138" y="152" width="69" height="188"/><rect x="403" y="122" width="74" height="218"/><rect x="663" y="82" width="69" height="258"/><rect x="1038" y="132" width="79" height="208"/>
</g>
<g fill="url(#winM)" opacity=".6">
<rect x="513" y="202" width="114" height="138"/><rect x="768" y="172" width="104" height="168"/>
</g>
<rect class="wflk" x="670" y="120" width="7" height="9" fill="#00e5ff"/><rect class="wflk d1" x="420" y="160" width="7" height="9" fill="#ff2fb3"/><rect class="wflk d2" x="1050" y="170" width="7" height="9" fill="#00e5ff"/>
<circle class="blink" cx="697" cy="62" r="4" fill="#ff3b3b"/>
<rect x="690" y="30" width="4" height="42" fill="#080814"/>
</svg>
</div>

<div class="flyer f1" aria-hidden="true"><svg viewBox="0 0 140 34"><rect x="0" y="14" width="66" height="6" rx="3" fill="url(#trail)"/><rect x="62" y="10" width="54" height="14" rx="7" fill="#14142e" stroke="#00e5ff" stroke-width="1.5"/><ellipse cx="102" cy="17" rx="8" ry="5" fill="#9df3ff" opacity=".9"/><circle cx="70" cy="29" r="2.5" fill="#ff2fb3"/></svg></div>
<div class="flyer f2" aria-hidden="true"><svg viewBox="0 0 140 34"><rect x="0" y="14" width="66" height="6" rx="3" fill="url(#trailM)"/><rect x="62" y="10" width="54" height="14" rx="7" fill="#1e0f24" stroke="#ff2fb3" stroke-width="1.5"/><ellipse cx="102" cy="17" rx="8" ry="5" fill="#ffc7e8" opacity=".9"/><circle cx="70" cy="29" r="2.5" fill="#00e5ff"/></svg></div>
<div class="flyer f3" aria-hidden="true"><svg viewBox="0 0 140 34"><rect x="0" y="14" width="66" height="6" rx="3" fill="url(#trail)"/><rect x="62" y="10" width="54" height="14" rx="7" fill="#14142e" stroke="#00e5ff" stroke-width="1.5"/><ellipse cx="102" cy="17" rx="8" ry="5" fill="#9df3ff" opacity=".9"/><circle cx="70" cy="29" r="2.5" fill="#ffe66d"/></svg></div>

<canvas id="petals"></canvas>
<div class="vig"></div>
<div class="wrap">

<!-- ================= COVER ================= -->
<div id="cover">
<svg class="cover-sky" viewBox="0 0 1200 340" preserveAspectRatio="xMidYMax slice">
<g fill="#0b0b1e"><rect x="0" y="220" width="110" height="120"/><rect x="130" y="140" width="85" height="200"/><rect x="235" y="250" width="140" height="90"/><rect x="395" y="110" width="90" height="230"/><rect x="505" y="190" width="130" height="150"/><rect x="655" y="70" width="85" height="270"/><rect x="760" y="160" width="120" height="180"/><rect x="900" y="230" width="110" height="110"/><rect x="1030" y="120" width="95" height="220"/><rect x="1145" y="200" width="55" height="140"/></g>
<g fill="url(#winC)" opacity=".7"><rect x="138" y="152" width="69" height="188"/><rect x="403" y="122" width="74" height="218"/><rect x="663" y="82" width="69" height="258"/><rect x="1038" y="132" width="79" height="208"/></g>
<g fill="url(#winM)" opacity=".6"><rect x="513" y="202" width="114" height="138"/><rect x="768" y="172" width="104" height="168"/></g>
</svg>
<div class="cover-inner">
<div class="kicker">The Wedding Of</div>
<div class="cover-names">{{ $brideNick }} <span class="amp">&amp;</span> {{ $groomNick }}</div>
<div class="nline"></div>
<div class="cover-date">{{ mcDate($wDate) }}</div>
<div class="kepada">Kepada Yth.<br>Bapak/Ibu/Saudara/i</div>
<div class="guest-box"><b>{{ $guestName }}</b></div>
<div><button class="btn" onclick="openInv()">&#9993; &nbsp;Buka Undangan</button></div>
</div>
</div>

<!-- ================= HERO ================= -->
<section id="hero">
<div class="rv"><div class="hero-hand">The Wedding Of</div></div>
<div class="rv"><h1 class="hero-names">{{ $brideNick }} <span class="amp">&amp;</span> {{ $groomNick }}</h1></div>
<div class="rv"><div class="nline"></div></div>
<div class="rv"><div class="hero-date">{{ mcDate($wDate) }}</div>
@if(mcGet('quotes'))
<div class="rv" style="max-width:420px;margin:20px auto 0;padding:0 8px">
<p style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:19px;line-height:1.7;opacity:.85">“{{ mcGet('quotes') }}”</p>
</div>
@endif</div>
</section>

<!-- COUNTDOWN -->
<section>
<div class="rv"><div class="sec-kicker">Menuju Hari Bahagia</div><div class="sec-title">Hitung Mundur</div></div>
<div class="rv"><div class="nline"></div></div>
<div class="cd-grid rv">
<div class="cd t3d"><b id="cdD">0</b><span>Hari</span></div>
<div class="cd t3d"><b id="cdH">0</b><span>Jam</span></div>
<div class="cd t3d"><b id="cdM">0</b><span>Menit</span></div>
<div class="cd t3d"><b id="cdS">0</b><span>Detik</span></div>
</div>
</section>

<!-- MEMPELAI -->
<section id="mempelai">
<div class="rv"><div class="sec-kicker">Assalamu'alaikum Wr. Wb.</div><div class="sec-title">Mempelai</div></div>
<div class="rv"><div class="nline"></div></div>
<div class="card t3d couple-card rv">
@if($bridePhoto)<img class="photo tz" src="{{ $bridePhoto }}" alt="{{ $brideFull }}">@else<div class="photo-fallback tz">{{ $brideInitial }}</div>@endif
<h3 class="tz">{{ $brideNick }}</h3><div class="full">{{ $brideFull }}</div>
<p>Putri dari<br>{{ mcGet('bride_parents', mcGet('bride_child_of', '')) }}</p>
</div>
<div class="amp rv">&#10084;</div>
<div class="card t3d couple-card rv">
@if($groomPhoto)<img class="photo tz" src="{{ $groomPhoto }}" alt="{{ $groomFull }}">@else<div class="photo-fallback tz">{{ $groomInitial }}</div>@endif
<h3 class="tz">{{ $groomNick }}</h3><div class="full">{{ $groomFull }}</div>
<p>Putra dari<br>{{ mcGet('groom_parents', mcGet('groom_child_of', '')) }}</p>
</div>
</section>

<!-- ACARA -->
<section id="acara">
<div class="rv"><div class="sec-kicker">Waktu &amp; Tempat</div><div class="sec-title">Rangkaian Acara</div></div>
<div class="rv"><div class="nline"></div></div>
<div class="card t3d event rv">
<h3 class="tz">Akad Nikah</h3>
<div class="date">{{ mcDate($wDate) }}</div>
<div class="time">{{ $wTime }} — {{ $wTimeE }} {{ mcGet('wedding_timezone','WIB') }}</div>
<div class="loc">{{ mcGet('wedding_location', 'Kediaman Mempelai') }}</div>
@if(mcGet('wedding_map_link'))<a class="btn-line" target="_blank" href="{{ mcGet('wedding_map_link') }}">Lihat Peta</a>@endif
</div>
<div class="card t3d event rv">
<h3 class="tz">Resepsi</h3>
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
<div class="rv"><div class="nline"></div></div>
<div class="g-grid rv">
@foreach(array_slice($gallery, 0, 8) as $img)<img src="{{ $img }}" alt="Galeri {{ $brideNick }} & {{ $groomNick }}" loading="lazy">@endforeach
</div>
</section>
@endif

<!-- KISAH -->
@if(!empty($stories) && count($stories))
<section id="kisah">
<div class="rv"><div class="sec-kicker">Perjalanan Kami</div><div class="sec-title">Kisah Cinta</div></div>
<div class="rv"><div class="nline"></div></div>
<div class="tl">
@foreach($stories as $st)
<div class="tl-item rv">
<svg class="nheart" viewBox="0 0 32 30"><path d="M16 28 C10 20 2 14 2 8 C2 3.5 5.5 1 9.5 1 C12.5 1 15 3 16 5.5 C17 3 19.5 1 22.5 1 C26.5 1 30 3.5 30 8 C30 14 22 20 16 28 Z" fill="none" stroke="#ff2fb3" stroke-width="2.5"/></svg>
<div>@if(!empty($st->tahun))<div style="font-size:11px;letter-spacing:.25em;opacity:.55;margin-bottom:6px">{{ $st->tahun }}</div>@endif
<h4>{{ $st->judul ?? 'Cerita' }}</h4><p>{{ $st->isi ?? '' }}</p></div>
</div>
@endforeach
</div>
</section>
@endif

<!-- RSVP -->
<section id="rsvp">
<div class="rv"><div class="sec-kicker">Konfirmasi</div><div class="sec-title">RSVP</div></div>
<div class="rv"><div class="nline"></div></div>
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
<section id="qrcheckin">
<div class="rv"><div class="sec-kicker">Kehadiran</div><div class="sec-title dekor">QR Check-in</div></div>
<div class="rv" style="max-width:340px;margin:0 auto;text-align:center">
<div id="qrBox" style="display:inline-block;padding:14px;background:#fff;border-radius:16px;box-shadow:0 8px 24px rgba(0,0,0,.12)"></div>
<p style="font-size:13px;opacity:.75;margin-top:14px;line-height:1.7">Tunjukkan QR code ini kepada panitia<br>saat kedatangan.<br>Nama: <b>{{ $guestName }}</b></p>
</div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>if(window.QRCode){new QRCode(document.getElementById('qrBox'),{text:"{{ $guestQrCode }}",width:200,height:200,correctLevel:QRCode.CorrectLevel.M});}</script>
@endif
<section id="ucapan">
<div class="rv"><div class="sec-kicker">Doa &amp; Harapan</div><div class="sec-title">Ucapan</div></div>
<div class="rv"><div class="nline"></div></div>
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
<div class="rv"><div class="nline"></div></div>
@foreach($accounts as $a)
<div class="card t3d bank rv">
<div class="bk">{{ $a['bank'] }}</div>
    @if(!empty($a['logo']))<img src="{{ $a['logo'] }}" alt="Logo bank" style="height:36px;max-width:150px;object-fit:contain;margin:10px auto 0;display:block">@endif
<div class="no tz">{{ $a['no'] }}</div>
<div class="an">a.n. {{ $a['an'] }}</div>
<button class="btn-line copy" onclick="copyNo('{{ $a['no'] }}')">Salin Nomor</button>
</div>
@endforeach
@if(mcGet('gift_address'))<div class="card t3d bank rv"><div class="bk">Kirim Hadiah Fisik</div><p style="font-size:14px;color:var(--tinta-dim);margin-top:10px;line-height:1.7">{{ mcGet('gift_address') }}</p>
    @if(mcGet('gift_maps_link'))<div style="margin-top:12px"><a href="{{ mcGet('gift_maps_link') }}" target="_blank" style="display:inline-block;padding:10px 26px;border:1px solid currentColor;border-radius:999px;font-size:11px;letter-spacing:.24em;text-transform:uppercase;text-decoration:none;opacity:.85">Lihat Peta</a></div>@endif</div>@endif
</section>
@endif

<footer>
<div class="rv">
<svg class="nheart" viewBox="0 0 32 30" style="width:44px;height:42px;margin-bottom:10px"><path d="M16 28 C10 20 2 14 2 8 C2 3.5 5.5 1 9.5 1 C12.5 1 15 3 16 5.5 C17 3 19.5 1 22.5 1 C26.5 1 30 3.5 30 8 C30 14 22 20 16 28 Z" fill="none" stroke="#ff2fb3" stroke-width="2.5"/></svg>
<div class="neon" style="font-size:30px;font-weight:700">Terima Kasih</div>
<p style="color:var(--tinta-dim);font-size:14px;line-height:1.8;max-width:380px;margin:14px auto">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>
<div class="neon-p" style="font-size:17px">{{ $brideNick }} &amp; {{ $groomNick }}</div>
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
// hujan neon
const cv=document.getElementById('petals'),cx=cv.getContext('2d');let P=[];
function rs(){cv.width=innerWidth;cv.height=innerHeight}rs();addEventListener('resize',rs);
const PCOLS=['#00e5ff','#ff2fb3','#7df9ff','#ff7ad9'];
for(let i=0;i<90;i++)P.push({x:Math.random()*innerWidth,y:Math.random()*innerHeight,l:Math.random()*18+10,s:Math.random()*14+9,c:PCOLS[i%PCOLS.length]});
(function loop(){cx.clearRect(0,0,cv.width,cv.height);cx.lineWidth=1.6;
P.forEach(p=>{p.y+=p.s;p.x-=p.s*0.25;
if(p.y>cv.height+20){p.y=-20;p.x=Math.random()*(cv.width+100)}
cx.strokeStyle=p.c;cx.globalAlpha=.55;cx.beginPath();cx.moveTo(p.x,p.y);cx.lineTo(p.x+p.l*0.25,p.y-p.l);cx.stroke();});
cx.globalAlpha=1;requestAnimationFrame(loop)})();
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
// motion 3D: kartu tilt mengikuti pointer + parallax kota 3 lapis
(function(){
  var cards=document.querySelectorAll('.t3d');
  function tilt(el,x,y){
    var r=el.getBoundingClientRect();
    var px=(x-r.left)/r.width-.5, py=(y-r.top)/r.height-.5;
    el.style.transform='perspective(900px) rotateY('+(px*14).toFixed(2)+'deg) rotateX('+(-py*14).toFixed(2)+'deg)';
  }
  cards.forEach(function(el){
    el.addEventListener('pointermove',function(e){tilt(el,e.clientX,e.clientY)});
    el.addEventListener('pointerleave',function(){el.style.transform=''});
  });
  var layers=document.querySelectorAll('.city-layer'),ticking=false;
  function par(){
    var y=window.scrollY||window.pageYOffset||0;
    layers.forEach(function(l){l.style.transform='translate3d(0,'+(y*parseFloat(l.dataset.depth)).toFixed(1)+'px,0)'});
    ticking=false;
  }
  window.addEventListener('scroll',function(){if(!ticking){requestAnimationFrame(par);ticking=true}},{passive:true});
  par();
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
