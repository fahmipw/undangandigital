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
<title>{{ $brideNick }} &amp; {{ $groomNick }} — Rimba Watercolor</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Cormorant+Garamond:ital,wght@0,400;0,500;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root{--krim:#f7f4ea;--krim2:#fbf9f2;--pinus:#2f4a3a;--pinus-dk:#22392c;--sage:#8ba888;
--emas:#c9a24b;--emas-dk:#a8823a;--perunggu:#7a5f28;
--tinta:#3d4a3a;--tinta-dim:rgba(61,74,58,.66)}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{font-family:'Jost',sans-serif;background:var(--krim);color:var(--tinta);overflow-x:hidden;font-weight:300}
#petals{position:fixed;inset:0;z-index:3;pointer-events:none}
.wrap{position:relative;z-index:2;max-width:520px;margin:0 auto}
.serif{font-family:'Playfair Display',serif}
/* ---------- cover ala referensi ---------- */
#cover{position:fixed;inset:0;z-index:100;display:flex;align-items:center;justify-content:center;transition:opacity .9s,visibility .9s;overflow-y:auto;background:var(--krim2)}
#cover.open{opacity:0;visibility:hidden}
.cover-scene{position:absolute;inset:0;width:100%;height:100%}
.cover-inner{text-align:center;padding:34px 30px 46px;width:100%;max-width:480px;position:relative}
.hexa{position:absolute;left:0;right:0;top:0;bottom:0;margin:auto;height:104%;width:auto;max-width:96vw;pointer-events:none}
.kicker{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:24px;color:var(--tinta);margin-bottom:2px}
.cover-names{font-family:'Playfair Display',serif;font-weight:700;font-size:clamp(44px,12.5vw,64px);line-height:1.12;color:var(--perunggu);letter-spacing:.04em;margin:6px 0}
.cover-names .amp{display:block;font-size:.62em;color:var(--emas-dk);margin:2px 0}
.laurel{display:block;width:120px;margin:10px auto}
.kepada{margin-top:16px;font-family:'Cormorant Garamond',serif;font-size:21px;color:var(--tinta);line-height:1.5}
.guest-box{margin:12px auto 0;max-width:300px;background:rgba(255,255,255,.92);border:1.5px solid var(--emas);border-radius:2px;padding:14px 18px;box-shadow:0 6px 22px rgba(122,95,40,.16)}
.guest-box b{font-family:'Cormorant Garamond',serif;font-size:24px;color:var(--tinta);font-weight:500}
.btn-pill{display:inline-block;margin-top:18px;padding:12px 44px;background:rgba(251,249,242,.95);border:1.5px solid var(--emas);border-radius:999px;color:var(--perunggu);font-family:'Cormorant Garamond',serif;font-size:20px;cursor:pointer;transition:.35s;box-shadow:0 8px 24px rgba(122,95,40,.2)}
.btn-pill:hover{transform:translateY(-2px);box-shadow:0 12px 30px rgba(122,95,40,.3)}
/* ---------- tombol & section umum ---------- */
.btn{display:inline-flex;align-items:center;gap:10px;margin-top:16px;padding:13px 38px;background:linear-gradient(135deg,#d8b45e,#c9a24b 55%,#a8823a);border:none;border-radius:999px;color:#fffdf6;font-size:12px;letter-spacing:.22em;text-transform:uppercase;cursor:pointer;font-family:'Jost';font-weight:500;transition:.35s;box-shadow:0 8px 24px rgba(122,95,40,.3)}
.btn:hover{transform:translateY(-2px)}
.btn-line{display:inline-block;padding:11px 30px;border:1px solid var(--emas);color:var(--perunggu);font-size:11px;letter-spacing:.26em;text-transform:uppercase;cursor:pointer;background:transparent;border-radius:999px;transition:.3s;text-decoration:none;font-family:'Jost'}
.btn-line:hover{background:rgba(201,162,75,.12)}
section{padding:64px 26px;text-align:center;position:relative}
.sec-kicker{font-size:10px;letter-spacing:.44em;text-transform:uppercase;color:var(--sage);margin-bottom:10px;font-weight:500}
.sec-title{font-family:'Playfair Display',serif;font-size:34px;font-weight:600;color:var(--perunggu)}
.daun-div{display:block;width:170px;margin:14px auto}
.card{background:#fffdf8;border:1px solid rgba(139,168,136,.5);border-radius:10px;padding:34px 24px;margin:0 auto 18px;max-width:400px;box-shadow:0 12px 32px rgba(61,74,58,.1)}
/* ---------- hero ---------- */
#hero{min-height:96vh;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden;background:var(--krim2)}
.hero-scene{position:absolute;inset:0;width:100%;height:100%}
.hero-content{position:relative;text-align:center;padding:40px 26px}
.hero-hand{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:26px;color:var(--tinta)}
.hero-names{font-family:'Playfair Display',serif;font-weight:700;font-size:clamp(42px,11.5vw,60px);line-height:1.15;color:var(--perunggu);margin:8px 0}
.hero-names .amp{display:block;font-size:.6em;color:var(--emas-dk)}
.hero-date{letter-spacing:.3em;font-size:13px;color:var(--tinta-dim);text-transform:uppercase;margin-top:8px}
.cd-grid{display:flex;justify-content:center;gap:10px;margin-top:26px}
.cd{width:72px;padding:16px 0;background:#fffdf8;border:1px solid rgba(201,162,75,.55);border-radius:10px;box-shadow:0 8px 20px rgba(61,74,58,.1)}
.cd b{display:block;font-family:'Playfair Display',serif;font-size:30px;color:var(--perunggu)}
.cd span{font-size:9px;letter-spacing:.26em;text-transform:uppercase;color:var(--tinta-dim)}
.photo{width:150px;height:150px;border-radius:50%;object-fit:cover;margin:0 auto 16px;border:3px solid var(--emas);box-shadow:0 10px 28px rgba(122,95,40,.25);display:block}
.photo-fallback{width:150px;height:150px;border-radius:50%;margin:0 auto 16px;background:linear-gradient(135deg,#a9c2a4,#5a7a5c);border:3px solid var(--emas);box-shadow:0 10px 28px rgba(122,95,40,.25);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:54px;color:#fffdf6}
.couple-card h3{font-family:'Playfair Display',serif;font-size:32px;color:var(--perunggu);font-weight:600}
.couple-card .full{font-family:'Cormorant Garamond',serif;font-size:19px;margin:6px 0}
.couple-card p{font-size:14px;color:var(--tinta-dim);line-height:1.7}
.amp{font-family:'Playfair Display',serif;font-size:44px;color:var(--emas);margin:2px 0}
.event h3{font-family:'Playfair Display',serif;font-size:24px;color:var(--perunggu)}
.event .date{font-family:'Cormorant Garamond',serif;font-size:20px;margin:12px 0 4px}
.event .time{letter-spacing:.22em;font-size:12px;color:var(--emas-dk);font-weight:500}
.event .loc{font-size:14px;color:var(--tinta-dim);margin:12px 0 18px;line-height:1.7}
.g-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:24px}
.g-grid img{width:100%;height:190px;object-fit:cover;border-radius:10px;border:3px solid #fff;box-shadow:0 8px 22px rgba(61,74,58,.16);transition:.4s}
.g-grid img:hover{transform:scale(1.03)}
.tl{max-width:400px;margin:26px auto 0;text-align:left}
.tl-item{background:#fffdf8;border-left:3px solid var(--emas);border-radius:0 10px 10px 0;padding:18px 20px;margin-bottom:14px;box-shadow:0 8px 22px rgba(61,74,58,.1)}
.tl-item h4{font-family:'Playfair Display',serif;font-size:20px;color:var(--perunggu)}
.tl-item p{font-size:14px;color:var(--tinta-dim);line-height:1.7;margin-top:6px}
/* ---------- section dalam hutan (rsvp & gift) ---------- */
section.deep{background:linear-gradient(180deg,#2a4433,#1d3126);color:#f5ead0;overflow:hidden}
section.deep .sec-kicker{color:var(--sage)}
section.deep .sec-title{color:#f2d67c}
section.deep .field label{color:var(--sage)}
section.deep .field input,section.deep .field select,section.deep .field textarea{background:rgba(247,244,234,.07);border:1px solid rgba(201,162,75,.5);color:#f5ead0}
section.deep .field input::placeholder,section.deep .field textarea::placeholder{color:rgba(245,234,208,.4)}
section.deep .wish{background:rgba(247,244,234,.06);border:1px solid rgba(201,162,75,.35)}
section.deep .wish b{color:#f2d67c}
section.deep .wish p{color:rgba(245,234,208,.75)}
section.deep .bank{background:rgba(247,244,234,.06);border:1px dashed rgba(201,162,75,.55)}
section.deep .bank .no{color:#f2d67c}
section.deep .bank .an,section.deep .bank p{color:rgba(245,234,208,.7)}
.deep-pines{position:absolute;bottom:-6px;left:0;width:100%;pointer-events:none;opacity:.5}
.field{margin:0 auto 14px;max-width:380px;text-align:left}
.field label{display:block;font-size:10px;letter-spacing:.32em;text-transform:uppercase;color:var(--sage);margin-bottom:8px;font-weight:500}
.field input,.field select,.field textarea{width:100%;background:#fffdf8;border:1px solid rgba(139,168,136,.55);border-radius:10px;color:var(--tinta);padding:13px 16px;font-family:'Jost';font-size:15px;outline:none}
.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--emas);box-shadow:0 0 0 3px rgba(201,162,75,.15)}
.wish{background:#fffdf8;border:1px solid rgba(139,168,136,.4);border-radius:10px;padding:16px 18px;margin:0 auto 12px;max-width:400px;text-align:left;box-shadow:0 6px 18px rgba(61,74,58,.08)}
.wish b{font-family:'Playfair Display',serif;color:var(--perunggu);font-size:17px}
.wish .st{font-size:10px;letter-spacing:.2em;color:var(--emas-dk);text-transform:uppercase;margin-left:8px;font-weight:500}
.wish p{font-size:14px;color:var(--tinta-dim);margin-top:6px;line-height:1.6}
.bank{background:#fffdf8;border:1px dashed rgba(201,162,75,.6);border-radius:10px;padding:24px;margin:0 auto 14px;max-width:380px}
.bank .bk{font-size:11px;letter-spacing:.34em;color:var(--sage);text-transform:uppercase;font-weight:500}
.bank .no{font-family:'Playfair Display',serif;font-size:28px;margin:10px 0 4px;letter-spacing:.04em;color:var(--perunggu)}
.bank .an{font-size:14px;color:var(--tinta-dim)}
.copy{margin-top:12px}
footer{padding:60px 26px 110px;text-align:center;background:var(--krim2)}
footer .serif{font-size:38px;color:var(--perunggu)}
#musBtn{position:fixed;bottom:22px;right:22px;z-index:90;width:52px;height:52px;border-radius:50%;border:1px solid var(--emas);background:linear-gradient(135deg,#fffdf8,#f0e8d2);color:var(--perunggu);font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 22px rgba(122,95,40,.3)}

/* === fitur bawaan default: lightbox, menu, progress, musik === */
.pbar{position:fixed;top:0;left:0;right:0;height:3px;z-index:96;background:transparent}
.pbar i{display:block;height:100%;width:0;background:linear-gradient(90deg,#c9a24b,#a8823a)}
.lb{position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.92);display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:.35s;padding:20px}
.lb.show{opacity:1;visibility:visible}
.lb img{max-width:100%;max-height:86vh;border:2px solid #c9a24b}
.lb-x{position:absolute;top:16px;right:22px;font-size:40px;color:#fff;cursor:pointer;line-height:1;z-index:201}
.lb-prev,.lb-next{position:absolute;top:50%;transform:translateY(-50%);font-size:32px;color:#fff;cursor:pointer;padding:16px 12px;z-index:201;user-select:none;opacity:.85;line-height:1}
.lb-prev{left:6px}.lb-next{right:6px}.lb-prev:hover,.lb-next:hover{opacity:1}
.lb-count{position:absolute;bottom:16px;left:50%;transform:translateX(-50%);color:#fff;font-size:13px;letter-spacing:.15em;z-index:201;font-family:inherit}
.fmenu{position:fixed;bottom:14px;left:50%;transform:translateX(-50%);z-index:90;display:flex;gap:2px;background:#22392cf2;border:1px solid #c9a24b55;border-radius:999px;padding:7px 9px;backdrop-filter:blur(8px);box-shadow:0 10px 30px rgba(0,0,0,.35);max-width:96vw;overflow-x:auto;scrollbar-width:none}
.fmenu::-webkit-scrollbar{display:none}
.fmenu a{display:flex;flex-direction:column;align-items:center;gap:3px;min-width:50px;padding:6px 7px;border-radius:12px;color:#f5ead0;opacity:.6;text-decoration:none;font-size:9px;letter-spacing:.06em;transition:.25s;font-family:'Jost',sans-serif}
.fmenu a i{font-style:normal;font-size:17px;line-height:1}
.fmenu a.active{opacity:1;background:#c9a24b2e;color:#a8823a}
#musBtn.playing{outline:2px solid #a8823a;outline-offset:2px}
.att-toggle{display:flex;gap:10px}
.att-toggle button{flex:1;padding:13px 8px;border:1px solid #c9a24b;background:transparent;color:#f5ead0;font-family:'Jost',sans-serif;font-size:14px;cursor:pointer;transition:.25s;border-radius:10px}
.att-toggle button.on{background:#c9a24b;color:#22392c;font-weight:500}
.wish-empty{text-align:center;opacity:.6;font-size:14px;padding:12px}
body.locked #fmenu,body.locked #musBtn,body.locked .pbar{opacity:0 !important;visibility:hidden !important;pointer-events:none !important}

.rv{opacity:0;transform:translateY(36px);transition:opacity .9s,transform .9s}
.rv.on{opacity:1;transform:none}
</style>
</head>
<body>
<div class="pbar"><i id="pbarFill"></i></div>
<!-- ======== DEFS: filter cat air, pinus, pakis, eucalyptus, bingkai heksagon ======== -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
<defs>
<filter id="wc" x="-20%" y="-20%" width="140%" height="140%">
<feTurbulence type="fractalNoise" baseFrequency="0.016" numOctaves="3" seed="7" result="n"/>
<feDisplacementMap in="SourceGraphic" in2="n" scale="22"/>
</filter>
<filter id="wcSoft" x="-20%" y="-20%" width="140%" height="140%">
<feTurbulence type="fractalNoise" baseFrequency="0.02" numOctaves="3" seed="11" result="n"/>
<feDisplacementMap in="SourceGraphic" in2="n" scale="14" result="d"/>
<feGaussianBlur in="d" stdDeviation="1.6"/>
</filter>
<filter id="blurMist"><feGaussianBlur stdDeviation="16"/></filter>
<linearGradient id="skyG" x1="0" y1="0" x2="0" y2="1">
<stop offset="0" stop-color="#fbf9f2"/><stop offset="1" stop-color="#edf0e2"/>
</linearGradient>
<linearGradient id="mtnFar" x1="0" y1="0" x2="0" y2="1">
<stop offset="0" stop-color="#c3cec4"/><stop offset="1" stop-color="#a9b8ae"/>
</linearGradient>
<linearGradient id="mtnMid" x1="0" y1="0" x2="0" y2="1">
<stop offset="0" stop-color="#93a89b"/><stop offset="1" stop-color="#7d938a"/>
</linearGradient>
<linearGradient id="mtnNear" x1="0" y1="0" x2="0" y2="1">
<stop offset="0" stop-color="#6b7f76"/><stop offset="1" stop-color="#54665e"/>
</linearGradient>
<g id="pine">
<rect x="46" y="150" width="8" height="50" fill="#3a2e1f"/>
<path d="M50 0 L82 60 L62 60 L88 105 L66 105 L94 150 L6 150 L34 105 L12 105 L38 60 Z" fill="currentColor"/>
<path d="M50 20 L68 58 L56 58 L74 96 L60 96 L78 132 L22 132 L40 96 L26 96 L44 58 Z" fill="#ffffff" opacity=".14"/>
</g>
<g id="fern">
<path d="M30 200 C28 140 32 80 30 8" fill="none" stroke="currentColor" stroke-width="2.5"/>
<g fill="currentColor">
<ellipse cx="15" cy="172" rx="15" ry="4.6" transform="rotate(-24 15 172)"/><ellipse cx="45" cy="172" rx="15" ry="4.6" transform="rotate(24 45 172)"/>
<ellipse cx="16" cy="148" rx="13.5" ry="4.4" transform="rotate(-24 16 148)"/><ellipse cx="44" cy="148" rx="13.5" ry="4.4" transform="rotate(24 44 148)"/>
<ellipse cx="17" cy="124" rx="12" ry="4.2" transform="rotate(-24 17 124)"/><ellipse cx="43" cy="124" rx="12" ry="4.2" transform="rotate(24 43 124)"/>
<ellipse cx="18" cy="100" rx="10.5" ry="4" transform="rotate(-24 18 100)"/><ellipse cx="42" cy="100" rx="10.5" ry="4" transform="rotate(24 42 100)"/>
<ellipse cx="19" cy="78" rx="9" ry="3.8" transform="rotate(-24 19 78)"/><ellipse cx="41" cy="78" rx="9" ry="3.8" transform="rotate(24 41 78)"/>
<ellipse cx="21" cy="58" rx="7.5" ry="3.5" transform="rotate(-24 21 58)"/><ellipse cx="39" cy="58" rx="7.5" ry="3.5" transform="rotate(24 39 58)"/>
<ellipse cx="23" cy="40" rx="6" ry="3.2" transform="rotate(-24 23 40)"/><ellipse cx="37" cy="40" rx="6" ry="3.2" transform="rotate(24 37 40)"/>
<ellipse cx="30" cy="18" rx="5" ry="9"/>
</g>
</g>
<g id="euca">
<path d="M40 200 C36 140 44 80 40 12" fill="none" stroke="currentColor" stroke-width="2.5"/>
<g fill="currentColor" opacity=".92">
<circle cx="30" cy="170" r="11"/><circle cx="50" cy="150" r="12"/><circle cx="31" cy="128" r="10"/>
<circle cx="49" cy="106" r="11"/><circle cx="32" cy="86" r="9"/><circle cx="48" cy="64" r="10"/>
<circle cx="36" cy="44" r="8"/><circle cx="44" cy="26" r="7"/>
</g>
</g>
<g id="spray">
<g fill="currentColor">
<path d="M40 200 C36 150 30 110 12 70 C34 84 44 120 46 170 Z"/>
<path d="M42 200 C44 150 52 108 72 66 C50 82 42 122 40 172 Z"/>
<path d="M41 200 C41 140 41 90 41 30 C47 90 47 140 45 200 Z"/>
</g>
</g>
<g id="laurel" fill="none" stroke="#c9a24b" stroke-width="2">
<path d="M4 20 C 30 18, 55 10, 76 2"/>
<g fill="#c9a24b" stroke="none">
<ellipse cx="18" cy="17" rx="7" ry="3" transform="rotate(-18 18 17)"/><ellipse cx="34" cy="13" rx="7" ry="3" transform="rotate(-14 34 13)"/>
<ellipse cx="50" cy="9" rx="7" ry="3" transform="rotate(-10 50 9)"/><ellipse cx="64" cy="5" rx="6" ry="2.8" transform="rotate(-8 64 5)"/>
</g>
<path d="M156 20 C 130 18, 105 10, 84 2"/>
<g fill="#c9a24b" stroke="none">
<ellipse cx="142" cy="17" rx="7" ry="3" transform="rotate(18 142 17)"/><ellipse cx="126" cy="13" rx="7" ry="3" transform="rotate(14 126 13)"/>
<ellipse cx="110" cy="9" rx="7" ry="3" transform="rotate(10 110 9)"/><ellipse cx="96" cy="5" rx="6" ry="2.8" transform="rotate(8 96 5)"/>
</g>
</g>
</defs>
</svg>

<!-- ================= COVER (replika referensi) ================= -->
<div id="cover">
<svg class="cover-scene" viewBox="0 0 480 900" preserveAspectRatio="xMidYMid slice">
<rect width="480" height="900" fill="url(#skyG)"/>
<g filter="url(#wc)">
<path d="M0 330 L120 180 L200 260 L300 150 L420 280 L480 220 L480 480 L0 480 Z" fill="url(#mtnFar)" opacity=".8"/>
<path d="M0 390 L100 260 L190 340 L290 240 L390 340 L480 280 L480 540 L0 540 Z" fill="url(#mtnMid)" opacity=".85"/>
<path d="M0 440 L130 300 L230 390 L340 310 L480 400 L480 580 L0 580 Z" fill="url(#mtnNear)" opacity=".9"/>
</g>
<ellipse cx="240" cy="330" rx="260" ry="42" fill="#ffffff" opacity=".65" filter="url(#blurMist)"/>
<ellipse cx="240" cy="430" rx="280" ry="46" fill="#ffffff" opacity=".55" filter="url(#blurMist)"/>
<g filter="url(#wcSoft)" opacity=".85">
<use href="#pine" x="0" y="0" width="100" height="200" transform="translate(18 470) scale(1.15)" color="#2f4a3a"/>
<use href="#pine" transform="translate(88 500) scale(.95)" color="#3a5a44"/>
<use href="#pine" transform="translate(150 520) scale(.8)" color="#2f4a3a"/>
<use href="#pine" transform="translate(330 515) scale(.85)" color="#3a5a44"/>
<use href="#pine" transform="translate(395 490) scale(1.0)" color="#2f4a3a"/>
</g>
<g filter="url(#wcSoft)">
<ellipse cx="420" cy="150" rx="90" ry="70" fill="#6b8f6e" opacity=".9"/><ellipse cx="360" cy="200" rx="70" ry="55" fill="#8ba888" opacity=".85"/>
<ellipse cx="460" cy="220" rx="75" ry="60" fill="#55755a" opacity=".9"/><ellipse cx="400" cy="120" rx="55" ry="45" fill="#9db89a" opacity=".8"/>
</g>
<g filter="url(#wcSoft)">
<use href="#fern" transform="translate(20 640) scale(1.3)" color="#2f4a3a"/>
<use href="#fern" transform="translate(90 680) scale(1.0)" color="#3a5a44"/>
<use href="#euca" transform="translate(330 650) scale(1.25)" color="#55755a"/>
<use href="#fern" transform="translate(400 690) scale(1.1)" color="#2f4a3a"/>
<use href="#spray" transform="translate(180 700) scale(1.4)" color="#1f3529"/>
<use href="#spray" transform="translate(260 720) scale(1.2)" color="#2f4a3a"/>
<use href="#euca" transform="translate(140 720) scale(.9)" color="#6b8f6e"/>
<ellipse cx="240" cy="880" rx="280" ry="90" fill="#1f3529" opacity=".95"/>
</g>
</svg>
<div class="cover-inner">
<svg class="hexa" viewBox="0 0 400 620" preserveAspectRatio="xMidYMid meet">
<polygon points="200,15 343,163 343,458 200,605 57,458 57,163" fill="none" stroke="#c9a24b" stroke-width="2.5" opacity=".95"/>
<polygon points="200,32 330,170 330,450 200,588 70,450 70,170" fill="none" stroke="#c9a24b" stroke-width="1.4" opacity=".7" transform="rotate(5 200 310)"/>
</svg>
<div class="kicker">The Wedding of</div>
<div class="cover-names">{{ $brideNick }}<span class="amp">&amp;</span>{{ $groomNick }}</div>
<svg class="laurel" viewBox="0 0 160 24"><use href="#laurel"/></svg>
<div class="kepada">Kepada Yth.<br>Bapak/Ibu/Saudara/i:</div>
<div class="guest-box"><b>{{ $guestName }}</b></div>
<div><button class="btn-pill" onclick="openInv()">Buka Undangan</button></div>
</div>
</div>

<!-- ================= HERO ================= -->
<section id="hero">
<svg class="hero-scene" viewBox="0 0 480 900" preserveAspectRatio="xMidYMid slice">
<rect width="480" height="900" fill="url(#skyG)"/>
<g filter="url(#wc)" data-speed="0.04">
<path d="M0 330 L120 180 L200 260 L300 150 L420 280 L480 220 L480 480 L0 480 Z" fill="url(#mtnFar)" opacity=".8"/>
<path d="M0 390 L100 260 L190 340 L290 240 L390 340 L480 280 L480 540 L0 540 Z" fill="url(#mtnMid)" opacity=".85"/>
</g>
<ellipse cx="240" cy="340" rx="260" ry="42" fill="#ffffff" opacity=".6" filter="url(#blurMist)"/>
<g filter="url(#wcSoft)" opacity=".8" data-speed="0.09">
<use href="#pine" transform="translate(30 500) scale(1.0)" color="#3a5a44"/>
<use href="#pine" transform="translate(390 510) scale(.9)" color="#3a5a44"/>
<use href="#pine" transform="translate(200 530) scale(.7)" color="#55755a"/>
</g>
<g filter="url(#wcSoft)" data-speed="0.14">
<use href="#fern" transform="translate(30 700) scale(1.2)" color="#3a5a44"/>
<use href="#euca" transform="translate(370 700) scale(1.1)" color="#55755a"/>
<use href="#spray" transform="translate(200 730) scale(1.3)" color="#2f4a3a"/>
<ellipse cx="240" cy="900" rx="300" ry="80" fill="#2f4a3a" opacity=".9"/>
</g>
</svg>
<div class="hero-content">
<div class="rv"><div class="hero-hand">The Wedding of</div></div>
<div class="rv"><h1 class="hero-names">{{ $brideNick }}<span class="amp">&amp;</span>{{ $groomNick }}</h1></div>
<svg class="laurel rv" viewBox="0 0 160 24" style="width:120px;margin:10px auto"><use href="#laurel"/></svg>
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
<div class="rv"><div class="sec-kicker">Menuju Hari Bahagia</div><div class="sec-title serif">Hitung Mundur</div></div>
<svg class="daun-div rv" viewBox="0 0 160 24"><use href="#laurel"/></svg>
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
<svg class="daun-div rv" viewBox="0 0 160 24"><use href="#laurel"/></svg>
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
<svg class="daun-div rv" viewBox="0 0 160 24"><use href="#laurel"/></svg>
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
<div class="rv"><div class="sec-kicker">Momen Indah</div><div class="sec-title serif">Galeri</div></div>
<svg class="daun-div rv" viewBox="0 0 160 24"><use href="#laurel"/></svg>
<div class="g-grid rv">
@foreach(array_slice($gallery, 0, 8) as $img)<img src="{{ $img }}" alt="Galeri {{ $brideNick }} & {{ $groomNick }}" loading="lazy">@endforeach
</div>
</section>
@endif

<!-- KISAH -->
@if(!empty($stories) && count($stories))
<section id="kisah">
<div class="rv"><div class="sec-kicker">Perjalanan Kami</div><div class="sec-title serif">Kisah Cinta</div></div>
<svg class="daun-div rv" viewBox="0 0 160 24"><use href="#laurel"/></svg>
<div class="tl">
@foreach($stories as $st)
<div class="tl-item rv">@if(!empty($st->tahun))<div style="font-size:11px;letter-spacing:.25em;opacity:.55;margin-bottom:6px">{{ $st->tahun }}</div>@endif
<h4>{{ $st->judul ?? 'Cerita' }}</h4><p>{{ $st->isi ?? '' }}</p></div>
@endforeach
</div>
</section>
@endif

<!-- RSVP -->
<section class="deep" id="rsvp">
<svg class="deep-pines" viewBox="0 0 480 120" preserveAspectRatio="xMidYMax slice"><g fill="#16241c" filter="url(#wcSoft)"><path d="M20 120 L60 40 L100 120 Z"/><path d="M90 120 L140 20 L190 120 Z"/><path d="M300 120 L350 30 L400 120 Z"/><path d="M390 120 L440 50 L490 120 Z"/><path d="M200 120 L240 60 L280 120 Z"/></g></svg>
<div class="rv"><div class="sec-kicker">Konfirmasi</div><div class="sec-title serif">RSVP</div></div>
<svg class="daun-div rv" viewBox="0 0 160 24"><use href="#laurel"/></svg>
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
<svg class="daun-div rv" viewBox="0 0 160 24"><use href="#laurel"/></svg>
<div id="wishList" class="rv"></div>
<form id="wishForm" class="rv" onsubmit="return sendWish(event)" style="margin-top:22px">
<div class="field"><label>Nama</label><input required name="name" placeholder="Nama Anda"></div>
<div class="field"><label>Ucapan</label><textarea required name="message" rows="3" placeholder="Tulis doa terbaik Anda..."></textarea></div>
<button class="btn-line" type="submit">Kirim Ucapan</button>
</form>
</section>

<!-- GIFT -->
@if(!empty($accounts) || mcGet('gift_address'))
<section class="deep" id="gift">
<svg class="deep-pines" viewBox="0 0 480 120" preserveAspectRatio="xMidYMax slice"><g fill="#16241c" filter="url(#wcSoft)"><path d="M20 120 L60 40 L100 120 Z"/><path d="M90 120 L140 20 L190 120 Z"/><path d="M300 120 L350 30 L400 120 Z"/><path d="M390 120 L440 50 L490 120 Z"/><path d="M200 120 L240 60 L280 120 Z"/></g></svg>
<div class="rv"><div class="sec-kicker">Tanda Kasih</div><div class="sec-title serif">Hadiah</div></div>
<svg class="daun-div rv" viewBox="0 0 160 24"><use href="#laurel"/></svg>
@foreach($accounts as $a)
<div class="card bank rv" style="background:rgba(247,244,234,.06);border:1px dashed rgba(201,162,75,.55)">
<div class="bk" style="color:var(--sage)">{{ $a['bank'] }}</div>
    @if(!empty($a['logo']))<img src="{{ $a['logo'] }}" alt="Logo bank" style="height:36px;max-width:150px;object-fit:contain;margin:10px auto 0;display:block">@endif
<div class="no" style="color:#f2d67c">{{ $a['no'] }}</div>
<div class="an" style="color:rgba(245,234,208,.7)">a.n. {{ $a['an'] }}</div>
<button class="btn-line copy" onclick="copyNo('{{ $a['no'] }}')" style="color:#f2d67c;border-color:var(--emas)">Salin Nomor</button>
</div>
@endforeach
@if(mcGet('gift_address'))<div class="card bank rv" style="background:rgba(247,244,234,.06);border:1px dashed rgba(201,162,75,.55)"><div class="bk" style="color:var(--sage)">Kirim Hadiah Fisik</div><p style="font-size:14px;color:rgba(245,234,208,.7);margin-top:10px;line-height:1.7">{{ mcGet('gift_address') }}</p>
    @if(mcGet('gift_maps_link'))<div style="margin-top:12px"><a href="{{ mcGet('gift_maps_link') }}" target="_blank" style="display:inline-block;padding:10px 26px;border:1px solid currentColor;border-radius:999px;font-size:11px;letter-spacing:.24em;text-transform:uppercase;text-decoration:none;opacity:.85">Lihat Peta</a></div>@endif</div>@endif
</section>
@endif

<footer>
<div class="rv">
<svg viewBox="0 0 160 24" style="width:120px;margin:0 auto 10px"><use href="#laurel"/></svg>
<div class="serif">Terima Kasih</div>
<p style="color:var(--tinta-dim);font-size:14px;line-height:1.8;max-width:380px;margin:14px auto">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>
<div class="serif" style="font-size:22px;color:var(--perunggu)">{{ $brideNick }} &amp; {{ $groomNick }}</div>
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
// daun & kabut melayang
const cv=document.getElementById('petals'),cx=cv.getContext('2d');let P=[];
function rs(){cv.width=innerWidth;cv.height=innerHeight}rs();addEventListener('resize',rs);
const PCOLS=['#8ba888','#a9c2a4','#c9a24b','#6b8f6e'];
for(let i=0;i<26;i++)P.push({k:'l',x:Math.random()*innerWidth,y:Math.random()*-innerHeight,r:Math.random()*5+3.5,s:Math.random()*.7+.3,ph:Math.random()*6.28,sw:Math.random()*1.2+.4,rot:Math.random()*6.28,vr:(Math.random()-.5)*.04,c:PCOLS[i%PCOLS.length]});
for(let i=0;i<10;i++)P.push({k:'m',x:Math.random()*innerWidth,y:Math.random()*innerHeight,r:Math.random()*60+40,s:Math.random()*.22+.08,ph:Math.random()*6.28});
(function loop(t){cx.clearRect(0,0,cv.width,cv.height);P.forEach(p=>{
  if(p.k==='l'){p.y+=p.s;p.x+=Math.sin(t/1600+p.ph)*p.sw;p.rot+=p.vr;
    if(p.y>cv.height+12){p.y=-12;p.x=Math.random()*cv.width}
    cx.save();cx.translate(p.x,p.y);cx.rotate(p.rot);cx.globalAlpha=.75;cx.fillStyle=p.c;
    cx.beginPath();cx.ellipse(0,0,p.r,p.r*.5,0,0,6.29);cx.fill();cx.restore();}
  else{p.x+=p.s;if(p.x-p.r>cv.width){p.x=-p.r;p.y=Math.random()*cv.height}
    const g=cx.createRadialGradient(p.x,p.y,0,p.x,p.y,p.r);
    g.addColorStop(0,'rgba(255,255,255,.14)');g.addColorStop(1,'rgba(255,255,255,0)');
    cx.fillStyle=g;cx.beginPath();cx.arc(p.x,p.y,p.r,0,6.29);cx.fill();}});
  requestAnimationFrame(loop)})(0);
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
// parallax berlapis: gunung, pinus, dedaunan bergerak beda kecepatan
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
