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
<title>{{ $brideNick }} &amp; {{ $groomNick }} — Sogan Parallax</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root{--sogan:#1b0f07;--sogan2:#2b180d;--emas:#d4af37;--emas-lt:#f2d67c;--emas-dk:#a87e1f;
--tinta:#f5ead0;--tinta-dim:rgba(245,234,208,.62);--kertas:rgba(43,24,13,.9)}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{font-family:'Jost',sans-serif;background:radial-gradient(1200px 800px at 50% -10%,#2b180d 0%,#1b0f07 60%);color:var(--tinta);overflow-x:hidden;font-weight:300;min-height:100vh}
#petals{position:fixed;inset:0;z-index:3;pointer-events:none}
.batik-layer{position:fixed;inset:-12% 0;z-index:0;pointer-events:none;opacity:.5}
.batik-layer svg{width:100%;height:100%}
.vignette{position:fixed;inset:0;z-index:1;pointer-events:none;background:radial-gradient(120% 90% at 50% 40%,transparent 55%,rgba(10,6,3,.75) 100%)}
.wrap{position:relative;z-index:2;max-width:520px;margin:0 auto}
.dekor{font-family:'Cinzel Decorative',serif}
/* ---------- gunungan ---------- */
.gunungan{display:block;filter:drop-shadow(0 0 26px rgba(212,175,55,.35))}
.gun-float{animation:gunfloat 7s ease-in-out infinite}
@keyframes gunfloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
.gun-cover{width:min(72vw,300px);margin:0 auto;animation:gunfloat 8s ease-in-out infinite}
.gun-wrap{width:min(64vw,260px);margin:6px auto 4px}
/* ---------- cover ---------- */
#cover{position:fixed;inset:0;z-index:100;display:flex;align-items:center;justify-content:center;transition:opacity .9s,visibility .9s;overflow-y:auto;background:linear-gradient(180deg,#241408 0%,#1b0f07 70%)}
#cover.open{opacity:0;visibility:hidden}
.cover-batik{position:absolute;inset:0;width:100%;height:100%;opacity:.4;pointer-events:none}
.cover-inner{text-align:center;padding:30px 22px 44px;width:100%;max-width:520px;position:relative}
.kicker{font-size:11px;letter-spacing:.5em;text-transform:uppercase;color:var(--emas);margin:14px 0 4px;font-weight:500}
.cover-names{font-family:'Cinzel Decorative',serif;font-weight:700;font-size:clamp(34px,9.5vw,50px);line-height:1.35;margin:8px 0 4px;
background:linear-gradient(180deg,#f7e08a 0%,#d4af37 45%,#a87e1f 80%,#f2d67c 100%);-webkit-background-clip:text;background-clip:text;color:transparent;
filter:drop-shadow(0 2px 12px rgba(212,175,55,.35))}
.cover-date{font-family:'Cormorant Garamond',serif;font-size:20px;letter-spacing:.28em;color:var(--tinta-dim)}
.kepada{margin-top:20px;font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--tinta)}
.guest-box{margin:10px auto 0;max-width:330px;background:rgba(27,15,7,.85);border:1px solid var(--emas);border-radius:4px;padding:13px 18px;box-shadow:0 0 24px rgba(212,175,55,.25),inset 0 0 18px rgba(212,175,55,.08)}
.guest-box b{font-family:'Cinzel Decorative',serif;font-size:19px;color:var(--emas-lt);font-weight:700}
.btn{display:inline-flex;align-items:center;gap:10px;margin-top:18px;padding:14px 40px;background:linear-gradient(135deg,#f2d67c,#d4af37 55%,#a87e1f);border:none;border-radius:4px;color:#1b0f07;font-size:12px;letter-spacing:.22em;text-transform:uppercase;cursor:pointer;font-family:'Jost';font-weight:500;transition:.35s;box-shadow:0 8px 28px rgba(212,175,55,.35)}
.btn:hover{transform:translateY(-2px);box-shadow:0 12px 34px rgba(212,175,55,.5)}
.btn-line{display:inline-block;padding:12px 32px;border:1px solid var(--emas);color:var(--emas-lt);font-size:11px;letter-spacing:.26em;text-transform:uppercase;cursor:pointer;background:transparent;border-radius:4px;transition:.3s;text-decoration:none;font-family:'Jost'}
.btn-line:hover{background:rgba(212,175,55,.14);box-shadow:0 0 18px rgba(212,175,55,.25)}
/* ---------- section ---------- */
section{padding:66px 26px;text-align:center;position:relative}
.sec-kicker{font-size:10px;letter-spacing:.44em;text-transform:uppercase;color:var(--emas);margin-bottom:10px;font-weight:500}
.sec-title{font-family:'Cinzel Decorative',serif;font-size:32px;font-weight:700;color:var(--emas-lt);text-shadow:0 0 24px rgba(212,175,55,.4)}
.ukiran{display:block;width:220px;margin:16px auto}
.card{background:linear-gradient(180deg,rgba(52,30,15,.94),rgba(30,17,8,.96));border:1px solid rgba(212,175,55,.5);border-radius:6px;padding:36px 24px;margin:0 auto 18px;max-width:400px;box-shadow:0 14px 40px rgba(0,0,0,.5),inset 0 0 30px rgba(212,175,55,.06);position:relative}
.card::before{content:'';position:absolute;inset:6px;border:1px solid rgba(212,175,55,.22);border-radius:3px;pointer-events:none}
#hero{min-height:96vh;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden}
.hero-hand{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:24px;color:var(--emas)}
.hero-names{font-family:'Cinzel Decorative',serif;font-weight:700;font-size:clamp(36px,10vw,54px);line-height:1.4;margin:10px 0;
background:linear-gradient(180deg,#f7e08a,#d4af37 50%,#a87e1f 85%,#f2d67c);-webkit-background-clip:text;background-clip:text;color:transparent;
filter:drop-shadow(0 2px 14px rgba(212,175,55,.35))}
.hero-names em{font-style:normal;-webkit-text-fill-color:var(--emas-dk)}
.hero-date{letter-spacing:.3em;font-size:13px;color:var(--tinta-dim);text-transform:uppercase}
.cd-grid{display:flex;justify-content:center;gap:10px;margin-top:26px}
.cd{width:72px;padding:16px 0;background:rgba(30,17,8,.9);border:1px solid rgba(212,175,55,.55);border-radius:6px;box-shadow:0 8px 22px rgba(0,0,0,.5)}
.cd b{display:block;font-family:'Cinzel Decorative',serif;font-size:28px;color:var(--emas-lt)}
.cd span{font-size:9px;letter-spacing:.26em;text-transform:uppercase;color:var(--tinta-dim)}
.photo{width:150px;height:150px;border-radius:50%;object-fit:cover;margin:0 auto 16px;border:3px solid var(--emas);box-shadow:0 0 30px rgba(212,175,55,.4);display:block}
.photo-fallback{width:150px;height:150px;border-radius:50%;margin:0 auto 16px;background:radial-gradient(circle at 35% 30%,#3a2412,#1b0f07);border:3px solid var(--emas);box-shadow:0 0 30px rgba(212,175,55,.4);display:flex;align-items:center;justify-content:center;font-family:'Cinzel Decorative',serif;font-size:52px;color:var(--emas-lt)}
.couple-card h3{font-family:'Cinzel Decorative',serif;font-size:30px;color:var(--emas-lt);font-weight:700}
.couple-card .full{font-family:'Cormorant Garamond',serif;font-size:19px;margin:6px 0;color:var(--tinta)}
.couple-card p{font-size:14px;color:var(--tinta-dim);line-height:1.7}
.amp{font-family:'Cinzel Decorative',serif;font-size:40px;color:var(--emas);margin:2px 0;text-shadow:0 0 20px rgba(212,175,55,.5)}
.event h3{font-family:'Cinzel Decorative',serif;font-size:22px;letter-spacing:.1em;color:var(--emas-lt)}
.event .date{font-family:'Cormorant Garamond',serif;font-size:20px;margin:12px 0 4px}
.event .time{letter-spacing:.22em;font-size:12px;color:var(--emas)}
.event .loc{font-size:14px;color:var(--tinta-dim);margin:12px 0 18px;line-height:1.7}
.g-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:24px}
.g-grid img{width:100%;height:190px;object-fit:cover;border-radius:4px;border:2px solid rgba(212,175,55,.6);box-shadow:0 8px 22px rgba(0,0,0,.5);transition:.4s}
.g-grid img:hover{transform:scale(1.03)}
.tl{max-width:400px;margin:26px auto 0;text-align:left}
.tl-item{background:rgba(36,20,8,.9);border-left:3px solid var(--emas);border-radius:0 6px 6px 0;padding:18px 20px;margin-bottom:14px;box-shadow:0 8px 22px rgba(0,0,0,.45)}
.tl-item h4{font-family:'Cinzel Decorative',serif;font-size:18px;color:var(--emas-lt)}
.tl-item p{font-size:14px;color:var(--tinta-dim);line-height:1.7;margin-top:6px}
.field{margin:0 auto 14px;max-width:380px;text-align:left}
.field label{display:block;font-size:10px;letter-spacing:.32em;text-transform:uppercase;color:var(--emas);margin-bottom:8px;font-weight:500}
.field input,.field select,.field textarea{width:100%;background:rgba(27,15,7,.9);border:1px solid rgba(212,175,55,.5);border-radius:4px;color:var(--tinta);padding:13px 16px;font-family:'Jost';font-size:15px;outline:none}
.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--emas-lt);box-shadow:0 0 0 3px rgba(212,175,55,.15)}
.wish{background:rgba(36,20,8,.9);border:1px solid rgba(212,175,55,.35);border-radius:6px;padding:16px 18px;margin:0 auto 12px;max-width:400px;text-align:left}
.wish b{font-family:'Cormorant Garamond',serif;color:var(--emas-lt);font-size:17px}
.wish .st{font-size:10px;letter-spacing:.2em;color:var(--emas);text-transform:uppercase;margin-left:8px;font-weight:500}
.wish p{font-size:14px;color:var(--tinta-dim);margin-top:6px;line-height:1.6}
.bank{background:rgba(36,20,8,.9);border:1px dashed rgba(212,175,55,.6);border-radius:6px;padding:24px;margin:0 auto 14px;max-width:380px}
.bank .bk{font-size:11px;letter-spacing:.34em;color:var(--emas);text-transform:uppercase;font-weight:500}
.bank .no{font-family:'Cinzel Decorative',serif;font-size:26px;margin:10px 0 4px;letter-spacing:.06em;color:var(--emas-lt)}
.bank .an{font-size:14px;color:var(--tinta-dim)}
.copy{margin-top:12px}
footer{padding:56px 26px 110px;text-align:center}
footer .dekor{font-size:36px;color:var(--emas-lt);text-shadow:0 0 22px rgba(212,175,55,.4)}
#musBtn{position:fixed;bottom:22px;right:22px;z-index:90;width:52px;height:52px;border-radius:50%;border:1px solid var(--emas);background:linear-gradient(135deg,#3a2412,#1b0f07);color:var(--emas-lt);font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 0 22px rgba(212,175,55,.35)}

/* === fitur bawaan default: lightbox, menu, progress, musik === */
.pbar{position:fixed;top:0;left:0;right:0;height:3px;z-index:96;background:transparent}
.pbar i{display:block;height:100%;width:0;background:linear-gradient(90deg,#d4af37,#f2d67c)}
.lb{position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.92);display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:.35s;padding:20px}
.lb.show{opacity:1;visibility:visible}
.lb img{max-width:100%;max-height:86vh;border:2px solid #d4af37}
.lb-x{position:absolute;top:16px;right:22px;font-size:40px;color:#fff;cursor:pointer;line-height:1;z-index:201}
.fmenu{position:fixed;bottom:14px;left:50%;transform:translateX(-50%);z-index:90;display:flex;gap:2px;background:#1b0f07f2;border:1px solid #d4af3755;border-radius:999px;padding:7px 9px;backdrop-filter:blur(8px);box-shadow:0 10px 30px rgba(0,0,0,.35);max-width:96vw;overflow-x:auto;scrollbar-width:none}
.fmenu::-webkit-scrollbar{display:none}
.fmenu a{display:flex;flex-direction:column;align-items:center;gap:3px;min-width:50px;padding:6px 7px;border-radius:12px;color:#f5ead0;opacity:.6;text-decoration:none;font-size:9px;letter-spacing:.06em;transition:.25s;font-family:'Jost',sans-serif}
.fmenu a i{font-style:normal;font-size:17px;line-height:1}
.fmenu a.active{opacity:1;background:#d4af372e;color:#f2d67c}
#musBtn.playing{outline:2px solid #f2d67c;outline-offset:2px}
.att-toggle{display:flex;gap:10px}
.att-toggle button{flex:1;padding:13px 8px;border:1px solid #d4af37;background:transparent;color:#f5ead0;font-family:'Jost',sans-serif;font-size:14px;cursor:pointer;transition:.25s;border-radius:10px}
.att-toggle button.on{background:#d4af37;color:#1b0f07;font-weight:500}
.wish-empty{text-align:center;opacity:.6;font-size:14px;padding:12px}
body.locked #fmenu,body.locked #musBtn,body.locked .pbar{opacity:0 !important;visibility:hidden !important;pointer-events:none !important}

.rv{opacity:0;transform:translateY(36px);transition:opacity .9s,transform .9s}
.rv.on{opacity:1;transform:none}
</style>
</head>
<body>
<div class="pbar"><i id="pbarFill"></i></div>
<!-- ======== DEFS: gradient emas, motif batik kawung & parang, gunungan wayang ======== -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
<defs>
<linearGradient id="emasG" x1="0" y1="0" x2="1" y2="1">
<stop offset="0" stop-color="#f7e08a"/><stop offset=".45" stop-color="#d4af37"/><stop offset=".8" stop-color="#a87e1f"/><stop offset="1" stop-color="#f2d67c"/>
</linearGradient>
<radialGradient id="glowG" cx=".5" cy=".5" r=".5">
<stop offset="0" stop-color="#f2d67c" stop-opacity=".55"/><stop offset="1" stop-color="#f2d67c" stop-opacity="0"/>
</radialGradient>
<filter id="emasGlow" x="-40%" y="-40%" width="180%" height="180%">
<feGaussianBlur stdDeviation="6" result="b"/><feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
</filter>
<pattern id="kawung" width="120" height="120" patternUnits="userSpaceOnUse">
<g fill="none" stroke="#d4af37" stroke-width="1.4" opacity=".55">
<ellipse cx="60" cy="60" rx="52" ry="34"/><ellipse cx="60" cy="60" rx="52" ry="34" transform="rotate(90 60 60)"/>
<ellipse cx="60" cy="60" rx="37" ry="23"/><ellipse cx="60" cy="60" rx="37" ry="23" transform="rotate(90 60 60)"/>
</g>
<circle cx="60" cy="60" r="5.5" fill="#d4af37" opacity=".55"/>
<circle cx="0" cy="0" r="4" fill="#d4af37" opacity=".4"/><circle cx="120" cy="0" r="4" fill="#d4af37" opacity=".4"/>
<circle cx="0" cy="120" r="4" fill="#d4af37" opacity=".4"/><circle cx="120" cy="120" r="4" fill="#d4af37" opacity=".4"/>
</pattern>
<pattern id="parang" width="180" height="64" patternUnits="userSpaceOnUse">
<g fill="none" stroke="#d4af37" stroke-width="1.6" opacity=".6">
<path d="M-20 74 C 30 50, 60 30, 110 6"/><path d="M-20 88 C 30 64, 60 44, 110 20" opacity=".5"/>
<path d="M70 74 C 120 50, 150 30, 200 6"/><path d="M70 88 C 120 64, 150 44, 200 20" opacity=".5"/>
</g>
</pattern>
<clipPath id="gunClip"><path d="M200 86 C247 148 281 234 281 344 C281 430 241 484 200 504 C159 484 119 430 119 344 C119 234 153 148 200 86 Z"/></clipPath>
<symbol id="gunungan" viewBox="0 0 400 620">
<ellipse cx="200" cy="310" rx="175" ry="250" fill="url(#glowG)" opacity=".55"/>
<path d="M200 4 c15 22 13 42 0 62 c-13 -20 -15 -40 0 -62 Z" fill="url(#emasG)" filter="url(#emasGlow)"/>
<circle cx="200" cy="80" r="7" fill="#f2d67c" filter="url(#emasGlow)"/>
<path d="M200 30 C265 110 315 220 315 350 C315 470 260 540 200 565 C140 540 85 470 85 350 C85 220 135 110 200 30 Z"
fill="#241408" stroke="url(#emasG)" stroke-width="9" filter="url(#emasGlow)"/>
<path d="M200 54 C257 124 295 224 295 348 C295 450 250 510 200 532 C150 510 105 450 105 348 C105 224 143 124 200 54 Z"
fill="none" stroke="#f2d67c" stroke-width="7" stroke-linecap="round" stroke-dasharray="0.1 17" opacity=".9"/>
<path d="M200 86 C247 148 281 234 281 344 C281 430 241 484 200 504 C159 484 119 430 119 344 C119 234 153 148 200 86 Z"
fill="#160c05" stroke="url(#emasG)" stroke-width="3"/>
<g clip-path="url(#gunClip)">
<circle cx="200" cy="175" r="24" fill="url(#emasG)" filter="url(#emasGlow)"/>
<g stroke="#f2d67c" stroke-width="4" stroke-linecap="round">
<line x1="200" y1="133" x2="200" y2="117"/><line x1="200" y1="133" x2="200" y2="117" transform="rotate(30 200 175)"/>
<line x1="200" y1="133" x2="200" y2="117" transform="rotate(60 200 175)"/><line x1="200" y1="133" x2="200" y2="117" transform="rotate(90 200 175)"/>
<line x1="200" y1="133" x2="200" y2="117" transform="rotate(120 200 175)"/><line x1="200" y1="133" x2="200" y2="117" transform="rotate(150 200 175)"/>
<line x1="200" y1="133" x2="200" y2="117" transform="rotate(180 200 175)"/><line x1="200" y1="133" x2="200" y2="117" transform="rotate(210 200 175)"/>
<line x1="200" y1="133" x2="200" y2="117" transform="rotate(240 200 175)"/><line x1="200" y1="133" x2="200" y2="117" transform="rotate(270 200 175)"/>
<line x1="200" y1="133" x2="200" y2="117" transform="rotate(300 200 175)"/><line x1="200" y1="133" x2="200" y2="117" transform="rotate(330 200 175)"/>
</g>
<path d="M138 238 q11 -11 22 0 q11 -11 22 0" fill="none" stroke="#d4af37" stroke-width="3.5" stroke-linecap="round"/>
<path d="M218 238 q11 -11 22 0 q11 -11 22 0" fill="none" stroke="#d4af37" stroke-width="3.5" stroke-linecap="round"/>
<g stroke="#d4af37" stroke-width="3" fill="none" opacity=".85">
<path d="M132 446 q14 -11 28 0 t28 0 t28 0 t28 0 t28 0"/>
<path d="M132 462 q14 -11 28 0 t28 0 t28 0 t28 0 t28 0" opacity=".6"/>
<path d="M132 478 q14 -11 28 0 t28 0 t28 0 t28 0 t28 0" opacity=".35"/>
</g>
<path d="M200 478 C196 420 204 360 200 300" fill="none" stroke="#d4af37" stroke-width="7" stroke-linecap="round"/>
<g fill="none" stroke="#d4af37" stroke-linecap="round">
<path d="M200 430 C172 412 152 394 142 366" stroke-width="5"/><path d="M200 380 C176 366 160 352 152 330" stroke-width="4"/>
<path d="M200 430 C228 412 248 394 258 366" stroke-width="5"/><path d="M200 380 C224 366 240 352 248 330" stroke-width="4"/>
<path d="M200 478 C186 492 172 496 158 498" stroke-width="5"/><path d="M200 478 C214 492 228 496 242 498" stroke-width="5"/>
</g>
<g fill="url(#emasG)">
<ellipse cx="142" cy="360" rx="13" ry="7" transform="rotate(-30 142 360)"/><ellipse cx="152" cy="324" rx="12" ry="6.5" transform="rotate(-40 152 324)"/>
<ellipse cx="258" cy="360" rx="13" ry="7" transform="rotate(30 258 360)"/><ellipse cx="248" cy="324" rx="12" ry="6.5" transform="rotate(40 248 324)"/>
<ellipse cx="200" cy="292" rx="12" ry="7"/><ellipse cx="182" cy="306" rx="10" ry="6" transform="rotate(-25 182 306)"/>
<ellipse cx="218" cy="306" rx="10" ry="6" transform="rotate(25 218 306)"/>
</g>
</g>
</symbol>
</defs>
</svg>

<div class="batik-layer" data-speed="0.05"><svg><rect width="100%" height="100%" fill="url(#kawung)"/></svg></div>
<canvas id="petals"></canvas>
<div class="vignette"></div>
<div class="wrap">

<!-- ================= COVER ================= -->
<div id="cover">
<svg class="cover-batik"><rect width="100%" height="100%" fill="url(#kawung)"/></svg>
<div class="cover-inner">
<svg class="gunungan gun-cover" viewBox="0 0 400 620"><use href="#gunungan"/></svg>
<div class="kicker">The Wedding Of</div>
<div class="cover-names">{{ $brideNick }} &amp; {{ $groomNick }}</div>
<div class="cover-date">{{ mcDate($wDate) }}</div>
<div class="kepada">Kepada Yth.<br>Bapak/Ibu/Saudara/i</div>
<div class="guest-box"><b>{{ $guestName }}</b></div>
<div><button class="btn" onclick="openInv()">&#9993; &nbsp;Buka Undangan</button></div>
</div>
</div>

<!-- ================= HERO ================= -->
<section id="hero">
<div class="gun-wrap" data-speed="0.1"><svg class="gunungan gun-float" viewBox="0 0 400 620" style="width:100%"><use href="#gunungan"/></svg></div>
<div class="rv"><div class="hero-hand">The Wedding Of</div></div>
<div class="rv"><h1 class="hero-names">{{ $brideNick }} <em>&amp;</em> {{ $groomNick }}</h1></div>
<div class="rv"><div class="hero-date">{{ mcDate($wDate) }}</div>
@if(mcGet('quotes'))
<div class="rv" style="max-width:420px;margin:20px auto 0;padding:0 8px">
<p style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:19px;line-height:1.7;opacity:.85">“{{ mcGet('quotes') }}”</p>
</div>
@endif</div>
<svg class="ukiran rv" viewBox="0 0 320 30"><rect width="100%" height="100%" fill="url(#parang)" opacity=".5"/><path d="M10 15 H138 M182 15 H310" stroke="#a87e1f" stroke-width="1.5"/><path d="M160 4 l11 11 -11 11 -11 -11 Z" fill="url(#emasG)"/><circle cx="146" cy="15" r="3" fill="#d4af37"/><circle cx="174" cy="15" r="3" fill="#d4af37"/></svg>
</section>

<!-- COUNTDOWN -->
<section>
<div class="rv"><div class="sec-kicker">Menuju Hari Bahagia</div><div class="sec-title dekor">Hitung Mundur</div></div>
<svg class="ukiran rv" viewBox="0 0 320 30"><path d="M10 15 H138 M182 15 H310" stroke="#a87e1f" stroke-width="1.5"/><path d="M160 4 l11 11 -11 11 -11 -11 Z" fill="url(#emasG)"/><circle cx="146" cy="15" r="3" fill="#d4af37"/><circle cx="174" cy="15" r="3" fill="#d4af37"/></svg>
<div class="cd-grid rv">
<div class="cd"><b id="cdD">0</b><span>Hari</span></div>
<div class="cd"><b id="cdH">0</b><span>Jam</span></div>
<div class="cd"><b id="cdM">0</b><span>Menit</span></div>
<div class="cd"><b id="cdS">0</b><span>Detik</span></div>
</div>
</section>

<!-- MEMPELAI -->
<section id="mempelai">
<div class="rv"><div class="sec-kicker">Assalamu'alaikum Wr. Wb.</div><div class="sec-title dekor">Mempelai</div></div>
<svg class="ukiran rv" viewBox="0 0 320 30"><path d="M10 15 H138 M182 15 H310" stroke="#a87e1f" stroke-width="1.5"/><path d="M160 4 l11 11 -11 11 -11 -11 Z" fill="url(#emasG)"/><circle cx="146" cy="15" r="3" fill="#d4af37"/><circle cx="174" cy="15" r="3" fill="#d4af37"/></svg>
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
<div class="rv"><div class="sec-kicker">Waktu &amp; Tempat</div><div class="sec-title dekor">Rangkaian Acara</div></div>
<svg class="ukiran rv" viewBox="0 0 320 30"><path d="M10 15 H138 M182 15 H310" stroke="#a87e1f" stroke-width="1.5"/><path d="M160 4 l11 11 -11 11 -11 -11 Z" fill="url(#emasG)"/><circle cx="146" cy="15" r="3" fill="#d4af37"/><circle cx="174" cy="15" r="3" fill="#d4af37"/></svg>
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
<div class="rv"><div class="sec-kicker">Momen Indah</div><div class="sec-title dekor">Galeri</div></div>
<svg class="ukiran rv" viewBox="0 0 320 30"><path d="M10 15 H138 M182 15 H310" stroke="#a87e1f" stroke-width="1.5"/><path d="M160 4 l11 11 -11 11 -11 -11 Z" fill="url(#emasG)"/><circle cx="146" cy="15" r="3" fill="#d4af37"/><circle cx="174" cy="15" r="3" fill="#d4af37"/></svg>
<div class="g-grid rv">
@foreach(array_slice($gallery, 0, 8) as $img)<img src="{{ $img }}" alt="Galeri {{ $brideNick }} & {{ $groomNick }}" loading="lazy">@endforeach
</div>
</section>
@endif

<!-- KISAH -->
@if(!empty($stories) && count($stories))
<section id="kisah">
<div class="rv"><div class="sec-kicker">Perjalanan Kami</div><div class="sec-title dekor">Kisah Cinta</div></div>
<svg class="ukiran rv" viewBox="0 0 320 30"><path d="M10 15 H138 M182 15 H310" stroke="#a87e1f" stroke-width="1.5"/><path d="M160 4 l11 11 -11 11 -11 -11 Z" fill="url(#emasG)"/><circle cx="146" cy="15" r="3" fill="#d4af37"/><circle cx="174" cy="15" r="3" fill="#d4af37"/></svg>
<div class="tl">
@foreach($stories as $st)
<div class="tl-item rv"><h4>{{ $st->title ?? 'Cerita' }}</h4><p>{{ $st->description ?? $st->content ?? '' }}</p></div>
@endforeach
</div>
</section>
@endif

<!-- RSVP -->
<section id="rsvp">
<div class="rv"><div class="sec-kicker">Konfirmasi</div><div class="sec-title dekor">RSVP</div></div>
<svg class="ukiran rv" viewBox="0 0 320 30"><path d="M10 15 H138 M182 15 H310" stroke="#a87e1f" stroke-width="1.5"/><path d="M160 4 l11 11 -11 11 -11 -11 Z" fill="url(#emasG)"/><circle cx="146" cy="15" r="3" fill="#d4af37"/><circle cx="174" cy="15" r="3" fill="#d4af37"/></svg>
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
<div class="rv"><div class="sec-kicker">Doa &amp; Harapan</div><div class="sec-title dekor">Ucapan</div></div>
<svg class="ukiran rv" viewBox="0 0 320 30"><path d="M10 15 H138 M182 15 H310" stroke="#a87e1f" stroke-width="1.5"/><path d="M160 4 l11 11 -11 11 -11 -11 Z" fill="url(#emasG)"/><circle cx="146" cy="15" r="3" fill="#d4af37"/><circle cx="174" cy="15" r="3" fill="#d4af37"/></svg>
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
<div class="rv"><div class="sec-kicker">Tanda Kasih</div><div class="sec-title dekor">Hadiah</div></div>
<svg class="ukiran rv" viewBox="0 0 320 30"><path d="M10 15 H138 M182 15 H310" stroke="#a87e1f" stroke-width="1.5"/><path d="M160 4 l11 11 -11 11 -11 -11 Z" fill="url(#emasG)"/><circle cx="146" cy="15" r="3" fill="#d4af37"/><circle cx="174" cy="15" r="3" fill="#d4af37"/></svg>
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
<svg class="gunungan" viewBox="0 0 400 620" style="width:120px;margin:0 auto 10px"><use href="#gunungan"/></svg>
<div class="dekor">Terima Kasih</div>
<p style="color:var(--tinta-dim);font-size:14px;line-height:1.8;max-width:380px;margin:14px auto">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>
<div class="dekor" style="font-size:20px;color:var(--emas-lt)">{{ $brideNick }} &amp; {{ $groomNick }}</div>
</div>
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
const PCOLS=['#f2d67c','#d4af37','#f7e08a','#fff3c4'];
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
// parallax berlapis: motif batik & gunungan bergerak beda kecepatan
(function(){
  var els=document.querySelectorAll('[data-speed]'),ticking=false;
  function upd(){var y=window.scrollY||window.pageYOffset;
    els.forEach(function(el){el.style.transform='translate3d(0,'+(y*parseFloat(el.dataset.speed)).toFixed(1)+'px,0)'});
    ticking=false;}
  window.addEventListener('scroll',function(){if(!ticking){requestAnimationFrame(upd);ticking=true}},{passive:true});
  upd();
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
