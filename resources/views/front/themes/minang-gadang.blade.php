<?php
/* ============================================================
   TEMA PREMIUM: MINANG GADANG (Rumah Gadang)
   Dipilih via settings: theme = minang-gadang
   Referensi: hijau tua + emas, rumah gadang, payung hias
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
$wDateNum = date('d.m.Y', strtotime($wDate) ?: time());
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
<title>{{ $brideNick }} &amp; {{ $groomNick }} — Minang Gadang</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root{--hijau:#0c3b26;--hijau2:#12502f;--hijau-dk:#082a1b;--emas:#d4af37;--emas-lt:#f2d67c;--gading:#faf6ec;--gading-dim:rgba(250,246,236,.7)}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{font-family:'Jost',sans-serif;background:var(--hijau-dk);color:var(--gading);overflow-x:hidden;font-weight:300}
#dust{position:fixed;inset:0;z-index:1;pointer-events:none}
.wrap{position:relative;z-index:2;max-width:520px;margin:0 auto;background:linear-gradient(180deg,var(--hijau2) 0%,var(--hijau) 30%,var(--hijau-dk) 100%)}
.serif{font-family:'Cormorant Garamond',serif}.cinzel{font-family:'Cinzel Decorative',serif}
/* ---------- cover ---------- */
#cover{position:fixed;inset:0;z-index:100;background:linear-gradient(180deg,#12502f 0%,#0c3b26 55%,#082a1b 100%);display:flex;align-items:center;justify-content:center;transition:opacity .9s,visibility .9s;overflow-y:auto}
#cover.open{opacity:0;visibility:hidden}
.cover-inner{text-align:center;padding:0 0 34px;width:100%;max-width:520px;position:relative}
.tumpal{display:block;width:100%;height:64px}
.mono{width:104px;margin:6px auto 4px;display:block;filter:drop-shadow(0 4px 14px rgba(0,0,0,.4))}
.kicker{font-size:12px;letter-spacing:.5em;text-transform:uppercase;color:var(--gading);margin-top:10px;font-weight:400}
.cover-names{font-family:'Cinzel Decorative',serif;font-weight:700;font-size:clamp(34px,9vw,52px);color:#fff;margin:12px 8px 4px;line-height:1.2;text-shadow:0 3px 12px rgba(0,0,0,.45)}
.cover-date{font-family:'Cormorant Garamond',serif;font-size:22px;letter-spacing:.28em;color:var(--emas-lt)}
.gadang{width:92%;max-width:440px;margin:8px auto 0;display:block}
.kepada{margin-top:2px;font-family:'Cormorant Garamond',serif;font-size:21px;color:var(--gading)}
.guest-box{margin:10px auto 0;max-width:330px;background:rgba(250,246,236,.94);border:1px solid var(--emas);border-radius:14px;padding:13px 18px;box-shadow:0 8px 24px rgba(0,0,0,.3)}
.guest-box b{font-family:'Cormorant Garamond',serif;font-size:21px;color:#3a2c12;font-weight:600}
.btn{display:inline-flex;align-items:center;gap:10px;margin-top:16px;padding:14px 40px;background:linear-gradient(135deg,#1d6a3c,#0f4a27);border:1px solid var(--emas);border-radius:999px;color:#fff;font-size:13px;letter-spacing:.22em;text-transform:uppercase;cursor:pointer;font-family:'Jost';font-weight:500;transition:.35s;box-shadow:0 10px 26px rgba(0,0,0,.4)}
.btn:hover{transform:translateY(-2px);box-shadow:0 14px 32px rgba(0,0,0,.5),0 0 22px rgba(212,175,55,.35)}
.btn-line{display:inline-block;padding:12px 32px;border:1px solid var(--emas);color:var(--emas-lt);font-size:12px;letter-spacing:.26em;text-transform:uppercase;cursor:pointer;background:transparent;border-radius:999px;transition:.3s;text-decoration:none;font-family:'Jost'}
.btn-line:hover{background:rgba(212,175,55,.14)}
.mawar-row{display:block;width:100%;margin-top:14px}
/* ---------- section ---------- */
section{padding:70px 26px;text-align:center;position:relative}
.sec-kicker{font-size:11px;letter-spacing:.44em;text-transform:uppercase;color:var(--emas);margin-bottom:10px}
.sec-title{font-family:'Cinzel Decorative',serif;font-size:30px;font-weight:700;color:#fff}
.ukir{display:flex;align-items:center;justify-content:center;gap:10px;margin:16px auto;max-width:300px;color:var(--emas)}
.ukir::before,.ukir::after{content:'';height:1px;flex:1;background:linear-gradient(90deg,transparent,var(--emas))}
.ukir::after{background:linear-gradient(90deg,var(--emas),transparent)}
#hero{min-height:92vh;display:flex;flex-direction:column;justify-content:center}
.hero-names{font-family:'Cinzel Decorative',serif;font-weight:700;font-size:clamp(36px,10vw,58px);line-height:1.2;margin:14px 6px;color:#fff;text-shadow:0 3px 14px rgba(0,0,0,.5)}
.hero-names em{font-style:normal;color:var(--emas-lt)}
.hero-date{letter-spacing:.3em;font-size:14px;color:var(--gading-dim);text-transform:uppercase;font-family:'Cormorant Garamond',serif;font-size:19px}
.cd-grid{display:flex;justify-content:center;gap:10px;margin-top:26px}
.cd{width:72px;padding:16px 0;background:rgba(212,175,55,.08);border:1px solid rgba(212,175,55,.5);border-radius:10px}
.cd b{display:block;font-family:'Cinzel Decorative',serif;font-size:26px;color:var(--emas-lt)}
.cd span{font-size:10px;letter-spacing:.28em;text-transform:uppercase;color:var(--gading-dim)}
.couple-card{border:1px solid rgba(212,175,55,.45);border-radius:14px;background:linear-gradient(180deg,rgba(212,175,55,.09),transparent);padding:34px 22px;margin:0 auto 20px;max-width:380px}
.photo{width:150px;height:150px;border-radius:50%;object-fit:cover;margin:0 auto 16px;border:3px solid var(--emas);padding:4px;display:block;background:var(--hijau-dk)}
.photo-fallback{width:150px;height:150px;border-radius:50%;margin:0 auto 16px;border:3px solid var(--emas);display:flex;align-items:center;justify-content:center;font-family:'Cinzel Decorative',serif;font-size:56px;color:var(--emas-lt);background:radial-gradient(circle,#12502f,#082a1b)}
.couple-card h3{font-family:'Cinzel Decorative',serif;font-size:26px;color:var(--emas-lt)}
.couple-card .full{font-family:'Cormorant Garamond',serif;font-size:19px;margin:8px 0 4px}
.couple-card p{font-size:14px;color:var(--gading-dim);line-height:1.7}
.amp{font-family:'Cinzel Decorative',serif;font-size:40px;color:var(--emas);margin:4px 0}
.event{border:1px solid rgba(212,175,55,.45);border-radius:14px;padding:36px 24px;margin:0 auto 20px;max-width:400px;background:rgba(8,42,27,.55);position:relative}
.event::before{content:'';position:absolute;inset:8px;border:1px solid rgba(212,175,55,.25);border-radius:8px;pointer-events:none}
.event h3{font-family:'Cinzel Decorative',serif;font-size:22px;color:var(--emas-lt)}
.event .date{font-family:'Cormorant Garamond',serif;font-size:20px;margin:12px 0 4px}
.event .time{letter-spacing:.22em;font-size:13px;color:var(--emas)}
.event .loc{font-size:14px;color:var(--gading-dim);margin:12px 0 18px;line-height:1.7}
.g-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:24px}
.g-grid img{width:100%;height:190px;object-fit:cover;border:1px solid rgba(212,175,55,.5);border-radius:8px;transition:.4s}
.g-grid img:hover{transform:scale(1.03)}
.tl{max-width:400px;margin:26px auto 0;text-align:left;padding-left:26px;position:relative}
.tl::before{content:'';position:absolute;left:8px;top:6px;bottom:6px;width:1px;background:linear-gradient(var(--emas),transparent)}
.tl-item{position:relative;padding:0 0 28px 18px}
.tl-item::before{content:'◆';position:absolute;left:-25px;top:0;color:var(--emas);font-size:12px}
.tl-item h4{font-family:'Cormorant Garamond',serif;font-size:22px;color:var(--emas-lt)}
.tl-item p{font-size:14px;color:var(--gading-dim);line-height:1.7;margin-top:6px}
.field{margin:0 auto 14px;max-width:380px;text-align:left}
.field label{display:block;font-size:10px;letter-spacing:.32em;text-transform:uppercase;color:var(--emas);margin-bottom:8px}
.field input,.field select,.field textarea{width:100%;background:rgba(250,246,236,.06);border:1px solid rgba(212,175,55,.5);border-radius:10px;color:var(--gading);padding:13px 14px;font-family:'Jost';font-size:15px;outline:none}
.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--emas)}
.field select option{color:#222}
.wish{border:1px solid rgba(212,175,55,.35);background:rgba(212,175,55,.07);border-radius:10px;padding:14px 16px;margin:0 auto 12px;max-width:400px;text-align:left}
.wish-head{display:flex;justify-content:space-between;align-items:baseline;gap:10px;margin-bottom:6px}
.wtime{font-size:11px;opacity:.55;white-space:nowrap}
.wish b{font-family:'Cormorant Garamond',serif;color:var(--emas-lt);font-size:17px}
.wish .st{font-size:10px;letter-spacing:.2em;color:var(--emas);text-transform:uppercase;margin-left:8px}
.wish p{font-size:14px;color:var(--gading-dim);margin-top:6px;line-height:1.6}
.bank{border:1px solid rgba(212,175,55,.5);border-radius:12px;padding:22px;margin:0 auto 14px;max-width:380px;background:rgba(212,175,55,.06)}
.bank .bk{font-size:11px;letter-spacing:.34em;color:var(--emas);text-transform:uppercase}
.bank .no{font-family:'Cormorant Garamond',serif;font-size:30px;letter-spacing:.06em;margin:10px 0 4px}
.bank .an{font-size:14px;color:var(--gading-dim)}
.copy{margin-top:12px}
footer{padding:60px 26px 100px;text-align:center}
footer .cinzel{font-size:30px;color:var(--emas-lt)}
#musBtn{position:fixed;bottom:22px;right:22px;z-index:90;width:52px;height:52px;border-radius:50%;border:1px solid var(--emas);background:rgba(8,42,27,.9);color:var(--emas-lt);font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center}

/* === fitur bawaan default: lightbox, menu, progress, musik === */
.pbar{position:fixed;top:0;left:0;right:0;height:3px;z-index:96;background:transparent}
.pbar i{display:block;height:100%;width:0;background:linear-gradient(90deg,#d4af37,#f2d67c)}
.lb{position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.92);display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:.35s;padding:20px}
.lb.show{opacity:1;visibility:visible}
.lb img{max-width:100%;max-height:86vh;border:2px solid #d4af37}
.lb-x{position:absolute;top:16px;right:22px;font-size:40px;color:#fff;cursor:pointer;line-height:1;z-index:201}
.lb-prev,.lb-next{position:absolute;top:50%;transform:translateY(-50%);font-size:32px;color:#fff;cursor:pointer;padding:16px 12px;z-index:201;user-select:none;opacity:.85;line-height:1}
.lb-prev{left:6px}.lb-next{right:6px}.lb-prev:hover,.lb-next:hover{opacity:1}
.lb-count{position:absolute;bottom:16px;left:50%;transform:translateX(-50%);color:#fff;font-size:13px;letter-spacing:.15em;z-index:201;font-family:inherit}
.fmenu{position:fixed;bottom:14px;left:50%;transform:translateX(-50%);z-index:90;display:flex;gap:2px;background:#0c3b26f2;border:1px solid #d4af3755;border-radius:999px;padding:7px 9px;backdrop-filter:blur(8px);box-shadow:0 10px 30px rgba(0,0,0,.35);max-width:96vw;overflow-x:auto;scrollbar-width:none}
.fmenu::-webkit-scrollbar{display:none}
.fmenu a{display:flex;flex-direction:column;align-items:center;gap:3px;min-width:50px;padding:6px 7px;border-radius:12px;color:#faf6ec;opacity:.6;text-decoration:none;font-size:9px;letter-spacing:.06em;transition:.25s;font-family:'Jost',sans-serif}
.fmenu a i{font-style:normal;font-size:17px;line-height:1}
.fmenu a.active{opacity:1;background:#d4af372e;color:#f2d67c}
#musBtn.playing{outline:2px solid #f2d67c;outline-offset:2px}
.att-toggle{display:flex;gap:10px}
.att-toggle button{flex:1;padding:13px 8px;border:1px solid #d4af37;background:transparent;color:#faf6ec;font-family:'Jost',sans-serif;font-size:14px;cursor:pointer;transition:.25s;border-radius:10px}
.att-toggle button.on{background:#d4af37;color:#0c3b26;font-weight:500}
.wish-empty{text-align:center;opacity:.6;font-size:14px;padding:12px}
body.locked #fmenu,body.locked #musBtn,body.locked .pbar{opacity:0 !important;visibility:hidden !important;pointer-events:none !important}

.rv{opacity:0;transform:translateY(36px);transition:opacity .9s,transform .9s}
.rv.on{opacity:1;transform:none}
</style>
</head>
<body>
<div class="pbar"><i id="pbarFill"></i></div>
<canvas id="dust"></canvas>
<div class="wrap">

<!-- ================= COVER ================= -->
<div id="cover">
  <div class="cover-inner">
    <svg class="tumpal" viewBox="0 0 520 64" preserveAspectRatio="xMidYMin meet">
      <defs><pattern id="tump" width="52" height="64" patternUnits="userSpaceOnUse">
        <path d="M26 6 L48 56 L4 56 Z" fill="none" stroke="#d4af37" stroke-width="2.4"/>
        <path d="M26 20 L40 50 L12 50 Z" fill="#d4af37" opacity=".9"/>
        <circle cx="26" cy="42" r="4.5" fill="#0c3b26"/>
      </pattern></defs>
      <rect width="520" height="64" fill="url(#tump)"/>
    </svg>

    <svg class="mono" viewBox="0 0 120 140">
      <path d="M60 4 C86 4 102 20 102 44 L102 92 C102 116 82 132 60 136 C38 132 18 116 18 92 L18 44 C18 20 34 4 60 4 Z" fill="#faf6ec" stroke="#d4af37" stroke-width="3"/>
      <path d="M60 12 C80 12 94 25 94 45 L94 90 C94 110 78 124 60 128 C42 124 26 110 26 90 L26 45 C26 25 40 12 60 12 Z" fill="none" stroke="#d4af37" stroke-width="1.2" opacity=".7"/>
      <text x="60" y="92" text-anchor="middle" font-family="Cinzel Decorative, serif" font-weight="700" font-size="52" fill="#0c3b26">{{ $brideInitial }}{{ $groomInitial }}</text>
      <path d="M38 108 q22 10 44 0" fill="none" stroke="#d4af37" stroke-width="1.6"/>
      <circle cx="60" cy="114" r="3" fill="#d4af37"/>
    </svg>

    <div class="kicker">The Wedding Of</div>
    <div class="cover-names">{{ $brideNick }} &amp; {{ $groomNick }}</div>
    <div class="cover-date">{{ $wDateNum }}</div>

    <!-- Rumah Gadang -->
    <svg class="gadang" viewBox="0 0 440 340">
      <defs>
        <radialGradient id="glow" cx="50%" cy="42%" r="55%">
          <stop offset="0%" stop-color="#1d6a3c" stop-opacity=".55"/>
          <stop offset="100%" stop-color="#1d6a3c" stop-opacity="0"/>
        </radialGradient>
        <g id="payung">
          <path d="M-58 0 A58 44 0 0 1 58 0 L52 0 A52 38 0 0 0 -52 0 Z" fill="#d4af37"/>
          <path d="M-58 0 A58 44 0 0 1 58 0" fill="none" stroke="#f2d67c" stroke-width="2"/>
          <g stroke="#8f6b1d" stroke-width="1.4">
            <line x1="0" y1="-42" x2="-44" y2="-6"/><line x1="0" y1="-42" x2="-24" y2="-16"/>
            <line x1="0" y1="-42" x2="0" y2="-2"/><line x1="0" y1="-42" x2="24" y2="-16"/>
            <line x1="0" y1="-42" x2="44" y2="-6"/>
          </g>
          <g fill="none" stroke="#f2d67c" stroke-width="1.6">
            <path d="M-50 -8 a8 8 0 0 0 -16 0"/><path d="M-34 -14 a8 8 0 0 0 -16 0"/>
            <path d="M-18 -18 a8 8 0 0 0 -16 0"/><path d="M-2 -20 a8 8 0 0 0 -16 0"/>
            <path d="M14 -18 a8 8 0 0 0 -16 0"/><path d="M30 -14 a8 8 0 0 0 -16 0"/>
            <path d="M46 -8 a8 8 0 0 0 -16 0"/>
          </g>
          <circle cx="0" cy="-46" r="5" fill="#f2d67c" stroke="#8f6b1d" stroke-width="1.5"/>
          <line x1="0" y1="-52" x2="0" y2="-60" stroke="#f2d67c" stroke-width="2.5"/>
          <line x1="0" y1="0" x2="0" y2="64" stroke="#8f6b1d" stroke-width="4"/>
          <path d="M0 64 q0 14 -14 14" fill="none" stroke="#8f6b1d" stroke-width="4" stroke-linecap="round"/>
        </g>
        <g id="mawar">
          <ellipse cx="-16" cy="10" rx="13" ry="9" fill="#1d6a3c" transform="rotate(-30 -16 10)"/>
          <ellipse cx="16" cy="10" rx="13" ry="9" fill="#1d6a3c" transform="rotate(30 16 10)"/>
          <g>
            <ellipse cx="0" cy="-6" rx="11" ry="13" fill="#fdfbf5"/>
            <ellipse cx="-8" cy="0" rx="9" ry="11" fill="#f7ecec" transform="rotate(-24)"/>
            <ellipse cx="8" cy="0" rx="9" ry="11" fill="#f7ecec" transform="rotate(24)"/>
            <ellipse cx="0" cy="4" rx="8" ry="9" fill="#fdfbf5"/>
            <path d="M-5 -2 q5 -6 10 0 q-2 6 -5 6 q-4 0 -5 -6" fill="#e9c9c9"/>
            <circle cx="0" cy="0" r="3.4" fill="#d4a0a0"/>
          </g>
        </g>
      </defs>

      <ellipse cx="220" cy="150" rx="200" ry="130" fill="url(#glow)"/>

      <!-- atap utama gonjong -->
      <path d="M28 196 C100 182 152 130 220 44 C288 130 340 182 412 196 L396 214 C340 200 292 152 220 80 C148 152 100 200 44 214 Z" fill="#0a2e1d" stroke="#d4af37" stroke-width="3.5"/>
      <path d="M62 192 C122 180 168 136 220 72 C272 136 318 180 378 192" fill="none" stroke="#d4af37" stroke-width="1.6" opacity=".8"/>
      <path d="M96 188 C142 178 178 142 220 92 C262 142 298 178 344 188" fill="none" stroke="#d4af37" stroke-width="1.2" opacity=".55"/>
      <!-- mustaka -->
      <line x1="220" y1="44" x2="220" y2="18" stroke="#d4af37" stroke-width="4"/>
      <path d="M220 8 l10 10 -10 10 -10 -10 Z" fill="#f2d67c" stroke="#d4af37" stroke-width="1.5"/>
      <!-- ukiran atap -->
      <g fill="#d4af37">
        <circle cx="120" cy="176" r="3.4"/><circle cx="150" cy="166" r="3.4"/><circle cx="180" cy="150" r="3.4"/>
        <circle cx="320" cy="176" r="3.4"/><circle cx="290" cy="166" r="3.4"/><circle cx="260" cy="150" r="3.4"/>
      </g>
      <path d="M196 120 q24 -26 48 0 q-12 10 -24 10 q-12 0 -24 -10" fill="none" stroke="#f2d67c" stroke-width="2"/>
      <path d="M186 138 q34 -30 68 0" fill="none" stroke="#d4af37" stroke-width="1.6" opacity=".8"/>

      <!-- sayap kiri -->
      <path d="M18 200 C48 193 68 165 94 132 C120 165 140 193 170 200 L160 211 C136 204 118 180 94 152 C70 180 52 204 28 211 Z" fill="#0a2e1d" stroke="#d4af37" stroke-width="2.6"/>
      <!-- sayap kanan -->
      <path d="M270 200 C300 193 320 165 346 132 C372 165 392 193 422 200 L412 211 C388 204 370 180 346 152 C322 180 304 204 280 211 Z" fill="#0a2e1d" stroke="#d4af37" stroke-width="2.6"/>

      <!-- badan -->
      <rect x="96" y="208" width="248" height="56" fill="#0e4028" stroke="#d4af37" stroke-width="2.5"/>
      <g stroke="#d4af37" stroke-width="1" opacity=".5">
        <line x1="140" y1="208" x2="140" y2="264"/><line x1="180" y1="208" x2="180" y2="264"/>
        <line x1="260" y1="208" x2="260" y2="264"/><line x1="300" y1="208" x2="300" y2="264"/>
      </g>
      <!-- tiang -->
      <g fill="#d4af37">
        <rect x="104" y="200" width="11" height="64"/><rect x="150" y="200" width="11" height="64"/>
        <rect x="196" y="200" width="11" height="64"/><rect x="233" y="200" width="11" height="64"/>
        <rect x="279" y="200" width="11" height="64"/><rect x="325" y="200" width="11" height="64"/>
      </g>
      <!-- pintu -->
      <rect x="207" y="222" width="26" height="42" fill="#081f14" stroke="#f2d67c" stroke-width="2"/>
      <path d="M207 222 q13 -12 26 0" fill="none" stroke="#f2d67c" stroke-width="1.6"/>
      <!-- tangga -->
      <g fill="#d4af37" stroke="#8f6b1d" stroke-width="1.4">
        <rect x="150" y="264" width="140" height="12"/><rect x="138" y="276" width="164" height="12"/>
        <rect x="126" y="288" width="188" height="12"/><rect x="114" y="300" width="212" height="12"/>
      </g>

      <!-- payung kiri kanan -->
      <use href="#payung" transform="translate(52 236) rotate(-14) scale(.82)"/>
      <use href="#payung" transform="translate(388 236) rotate(14) scale(.82)"/>

      <!-- pohon siluet -->
      <g opacity=".5" stroke="#06231425" fill="#062314">
        <path d="M8 90 q26 -34 12 -70 q22 8 30 34 q14 -20 34 -26 q-6 26 -24 38 q20 2 30 16 q-24 6 -44 -2 q-4 22 -18 34 Z" opacity=".55"/>
        <path d="M432 90 q-26 -34 -12 -70 q-22 8 -30 34 q-14 -20 -34 -26 q6 26 24 38 q-20 2 -30 16 q24 6 44 -2 q4 22 18 34 Z" opacity=".55"/>
      </g>
    </svg>

    <div class="kepada">Kepada :</div>
    <div class="guest-box"><b>{{ $guestName }}</b></div>
    <div><button class="btn" onclick="openInv()">&#9993; &nbsp;Buka Undangan</button></div>

    <svg class="mawar-row" viewBox="0 0 520 90">
      <use href="#mawar" transform="translate(60 48)"/>
      <use href="#mawar" transform="translate(150 56) scale(.85)"/>
      <use href="#mawar" transform="translate(250 44) scale(1.1)"/>
      <use href="#mawar" transform="translate(350 56) scale(.85)"/>
      <use href="#mawar" transform="translate(445 48)"/>
      <g stroke="#1d6a3c" stroke-width="3" fill="none" opacity=".8">
        <path d="M0 84 Q130 66 260 78 T520 74"/>
      </g>
    </svg>
  </div>
</div>

<!-- ================= HERO ================= -->
<section id="hero">
  <div class="rv"><div class="sec-kicker">The Wedding Of</div></div>
  <div class="rv">
    <svg viewBox="0 0 200 120" style="width:150px;margin:4px auto;display:block" aria-hidden="true">
      <path d="M14 88 C48 80 70 58 100 20 C130 58 152 80 186 88 L176 98 C152 91 132 72 100 40 C68 72 48 91 24 98 Z" fill="#0a2e1d" stroke="#d4af37" stroke-width="3"/>
      <line x1="100" y1="20" x2="100" y2="8" stroke="#d4af37" stroke-width="3"/>
      <path d="M100 2 l7 7 -7 7 -7 -7 Z" fill="#f2d67c"/>
      <rect x="52" y="96" width="96" height="8" fill="#d4af37"/>
      <g fill="#d4af37"><rect x="60" y="88" width="7" height="10"/><rect x="96" y="88" width="7" height="10"/><rect x="132" y="88" width="7" height="10"/></g>
    </svg>
  </div>
  <div class="rv"><h1 class="hero-names">{{ $brideNick }} <em>&amp;</em> {{ $groomNick }}</h1></div>
  <div class="rv"><div class="ukir">◆</div></div>
  <div class="rv"><div class="hero-date">{{ mcDate($wDate) }}</div>
@if(mcGet('quotes'))
<div class="rv" style="max-width:420px;margin:20px auto 0;padding:0 8px">
<p style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:19px;line-height:1.7;opacity:.85">“{{ mcGet('quotes') }}”</p>
</div>
@endif</div>
</section>

<!-- COUNTDOWN -->
<section>
  <div class="rv"><div class="sec-kicker">Menuju Hari Bahagia</div><div class="sec-title">Hitung Mundur</div><div class="ukir">◆</div></div>
  <div class="cd-grid rv">
    <div class="cd"><b id="cdD">0</b><span>Hari</span></div>
    <div class="cd"><b id="cdH">0</b><span>Jam</span></div>
    <div class="cd"><b id="cdM">0</b><span>Menit</span></div>
    <div class="cd"><b id="cdS">0</b><span>Detik</span></div>
  </div>
</section>

<!-- MEMPELAI -->
<section id="mempelai">
  <div class="rv"><div class="sec-kicker">Assalamu'alaikum Wr. Wb.</div><div class="sec-title">Mempelai</div><div class="ukir">◆</div></div>
  <div class="couple-card rv">
    @if($bridePhoto)<img class="photo" src="{{ $bridePhoto }}" alt="{{ $brideFull }}">@else<div class="photo-fallback">{{ $brideInitial }}</div>@endif
    <h3>{{ $brideNick }}</h3><div class="full">{{ $brideFull }}</div>
    <p>Putri dari<br>{{ mcGet('bride_parents', mcGet('bride_child_of', '')) }}</p>
  </div>
  <div class="amp rv">&amp;</div>
  <div class="couple-card rv">
    @if($groomPhoto)<img class="photo" src="{{ $groomPhoto }}" alt="{{ $groomFull }}">@else<div class="photo-fallback">{{ $groomInitial }}</div>@endif
    <h3>{{ $groomNick }}</h3><div class="full">{{ $groomFull }}</div>
    <p>Putra dari<br>{{ mcGet('groom_parents', mcGet('groom_child_of', '')) }}</p>
  </div>
</section>

<!-- ACARA -->
<section id="acara">
  <div class="rv"><div class="sec-kicker">Waktu &amp; Tempat</div><div class="sec-title">Rangkaian Acara</div><div class="ukir">◆</div></div>
  <div class="event rv">
    <h3>Akad Nikah</h3>
    <div class="date">{{ mcDate($wDate) }}</div>
    <div class="time">{{ $wTime }} — {{ $wTimeE }} {{ mcGet('wedding_timezone','WIB') }}</div>
    <div class="loc">{{ mcGet('wedding_location', 'Kediaman Mempelai') }}</div>
    @if(mcGet('wedding_map_link'))<a class="btn-line" target="_blank" href="{{ mcGet('wedding_map_link') }}">Lihat Peta</a>@endif
  </div>
  <div class="event rv">
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
  <div class="rv"><div class="sec-kicker">Momen Indah</div><div class="sec-title">Galeri</div><div class="ukir">◆</div></div>
  <div class="g-grid rv">
    @foreach(array_slice($gallery, 0, 8) as $img)<img src="{{ $img }}" alt="Galeri {{ $brideNick }} & {{ $groomNick }}" loading="lazy">@endforeach
  </div>
</section>
@endif

<!-- KISAH -->
@if(!empty($stories) && count($stories))
<section id="kisah">
  <div class="rv"><div class="sec-kicker">Perjalanan Kami</div><div class="sec-title">Kisah Cinta</div><div class="ukir">◆</div></div>
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
  <div class="rv"><div class="sec-kicker">Konfirmasi</div><div class="sec-title">RSVP</div><div class="ukir">◆</div></div>
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
  <div class="rv"><div class="sec-kicker">Doa &amp; Harapan</div><div class="sec-title">Ucapan</div><div class="ukir">◆</div></div>
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
  <div class="rv"><div class="sec-kicker">Tanda Kasih</div><div class="sec-title">Hadiah</div><div class="ukir">◆</div></div>
  @foreach($accounts as $a)
  <div class="bank rv">
    <div class="bk">{{ $a['bank'] }}</div>
    @if(!empty($a['logo']))<img src="{{ $a['logo'] }}" alt="Logo bank" style="height:36px;max-width:150px;object-fit:contain;margin:10px auto 0;display:block">@endif
    <div class="no">{{ $a['no'] }}</div>
    <div class="an">a.n. {{ $a['an'] }}</div>
    <button class="btn-line copy" onclick="copyNo('{{ $a['no'] }}')">Salin Nomor</button>
  </div>
  @endforeach
  @if(mcGet('gift_address'))<div class="bank rv"><div class="bk">Kirim Hadiah Fisik</div><p style="font-size:14px;color:var(--gading-dim);margin-top:10px;line-height:1.7">{{ mcGet('gift_address') }}</p>
    @if(mcGet('gift_maps_link'))<div style="margin-top:12px"><a href="{{ mcGet('gift_maps_link') }}" target="_blank" style="display:inline-block;padding:10px 26px;border:1px solid currentColor;border-radius:999px;font-size:11px;letter-spacing:.24em;text-transform:uppercase;text-decoration:none;opacity:.85">Lihat Peta</a></div>@endif</div>@endif
</section>
@endif

<footer>
  <div class="rv"><div class="cinzel">Terima Kasih</div>
  <p style="color:var(--gading-dim);font-size:14px;line-height:1.8;max-width:380px;margin:14px auto">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>
  <div class="ukir">◆</div>
  <div class="serif" style="font-size:22px;color:var(--emas-lt)">{{ $brideNick }} &amp; {{ $groomNick }}</div></div>
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
// gold dust
const cv=document.getElementById('dust'),cx=cv.getContext('2d');let P=[];
function rs(){cv.width=innerWidth;cv.height=innerHeight}rs();addEventListener('resize',rs);
for(let i=0;i<70;i++)P.push({x:Math.random()*innerWidth,y:Math.random()*innerHeight,r:Math.random()*2+.4,s:Math.random()*.5+.15,o:Math.random()*.7+.15,ph:Math.random()*6.28});
(function loop(t){cx.clearRect(0,0,cv.width,cv.height);P.forEach(p=>{p.y-=p.s;if(p.y<-5){p.y=cv.height+5;p.x=Math.random()*cv.width}
  cx.globalAlpha=p.o*(0.6+0.4*Math.sin(t/900+p.ph));cx.fillStyle='#e8c86a';cx.beginPath();cx.arc(p.x,p.y,p.r,0,6.29);cx.fill()});
  cx.globalAlpha=1;requestAnimationFrame(loop)})(0);
// rsvp
async function sendRSVP(e){e.preventDefault();const f=e.target;
  const r=await fetch('/api/rsvp?inv_id='+INV_ID,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name:f.name.value,status:f.status.value,guest_count:f.guest_count.value})});
  alert(r.ok?'Terima kasih atas konfirmasinya!':'Gagal mengirim, coba lagi.');f.reset();return false;}
// wishes
async function loadWishes(){try{const r=await fetch('/api/ucapan?inv_id='+INV_ID);const d=await r.json();
  wishList.innerHTML=(d.data||d||[]).map(w=>`<div class="wish"><b>${esc(w.name)}</b><span class="st">${esc(w.status||'')}</span><p>${esc(w.message||w.ucapan||'')}</p></div>`).join('')||'<p style="color:var(--gading-dim)">Belum ada ucapan.</p>';}catch(e){}}
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
