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
<title>{{ $brideNick }} &amp; {{ $groomNick }} — Arabian Nights</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Cormorant+Garamond:ital,wght@0,400;0,500;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root{--ungu:#1a0f2e;--ungu2:#2b1a4a;--emas:#d4af37;--emas-lt:#f2d67c;--emas-dk:#a87e1f;
--krem:#f7ecd4;--tinta:#f7ecd4;--tinta-dim:rgba(247,236,212,.62)}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{font-family:'Jost',sans-serif;background:linear-gradient(180deg,#120a24 0%,#1a0f2e 40%,#241640 100%);color:var(--tinta);overflow-x:hidden;font-weight:300;min-height:100vh}
#petals{position:fixed;inset:0;z-index:3;pointer-events:none}
.arab-layer{position:fixed;inset:0;z-index:0;pointer-events:none;opacity:.14}
.arab-layer svg{width:100%;height:100%}
.stars{position:absolute;inset:0;overflow:hidden;pointer-events:none}
.stars i{position:absolute;background:#fffdf4;border-radius:50%;animation:tw 3s ease-in-out infinite}
@keyframes tw{0%,100%{opacity:.15;transform:scale(.7)}50%{opacity:1;transform:scale(1.25)}}
.wrap{position:relative;z-index:2;max-width:520px;margin:0 auto}
.dekor{font-family:'Cinzel Decorative',serif}
/* ---------- lentera 3D ---------- */
.lant-scene{position:absolute;inset:0;perspective:1100px;pointer-events:none;overflow:hidden}
.l-drift{position:absolute;top:0;animation:rise linear infinite}
.l-swing{transform-origin:50% 0;animation:swing3d ease-in-out infinite alternate}
.l-swing svg{display:block;width:100%;height:auto}
.d-near{width:96px;filter:drop-shadow(0 0 26px rgba(245,185,66,.85))}
.d-mid{width:62px;filter:drop-shadow(0 0 14px rgba(245,185,66,.55))}
.d-far{width:36px;filter:blur(1.6px) drop-shadow(0 0 10px rgba(245,185,66,.45))}
@keyframes swing3d{from{transform:rotateY(-24deg) rotateX(9deg)}to{transform:rotateY(24deg) rotateX(-9deg)}}
@keyframes rise{from{transform:translateY(105vh)}to{transform:translateY(-30vh)}}
/* ---------- bulan sabit ---------- */
.moon{position:absolute;filter:drop-shadow(0 0 34px rgba(242,214,124,.55));animation:moonfloat 9s ease-in-out infinite}
@keyframes moonfloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
/* ---------- karpet terbang ---------- */
.carpet{position:absolute;top:20%;left:0;animation:carpetfly 26s linear infinite;pointer-events:none}
.carpet-in{animation:carpetbob 3.2s ease-in-out infinite;filter:drop-shadow(0 18px 14px rgba(0,0,0,.45))}
@keyframes carpetfly{from{transform:translateX(-280px)}to{transform:translateX(115vw)}}
@keyframes carpetbob{0%,100%{transform:translateY(0) rotateY(-10deg) rotateX(5deg)}50%{transform:translateY(-18px) rotateY(10deg) rotateX(-5deg)}}
/* ---------- istana ---------- */
.palace{position:absolute;bottom:-4px;left:0;width:100%;display:block;filter:drop-shadow(0 -6px 24px rgba(212,175,55,.25))}
/* ---------- cover ---------- */
#cover{position:fixed;inset:0;z-index:100;display:flex;align-items:center;justify-content:center;transition:opacity .9s,visibility .9s;overflow-y:auto;background:linear-gradient(180deg,#100823 0%,#1a0f2e 55%,#2b1a4a 100%)}
#cover.open{opacity:0;visibility:hidden}
.cover-inner{text-align:center;padding:60px 24px 150px;width:100%;max-width:520px;position:relative}
.kicker{font-size:11px;letter-spacing:.5em;text-transform:uppercase;color:var(--emas);margin-top:8px;font-weight:500}
.cover-names{font-family:'Cinzel Decorative',serif;font-weight:700;font-size:clamp(36px,10vw,52px);line-height:1.4;margin:10px 0 4px;
background:linear-gradient(180deg,#f7e08a 0%,#d4af37 45%,#a87e1f 80%,#f2d67c 100%);-webkit-background-clip:text;background-clip:text;color:transparent;
filter:drop-shadow(0 2px 14px rgba(212,175,55,.4))}
.cover-date{font-family:'Cormorant Garamond',serif;font-size:20px;letter-spacing:.28em;color:var(--tinta-dim)}
.kepada{margin-top:22px;font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--tinta)}
.guest-box{margin:10px auto 0;max-width:330px;background:rgba(26,15,46,.85);border:1px solid var(--emas);border-radius:8px;padding:13px 18px;box-shadow:0 0 26px rgba(212,175,55,.28)}
.guest-box b{font-family:'Cinzel Decorative',serif;font-size:19px;color:var(--emas-lt);font-weight:700}
.btn{display:inline-flex;align-items:center;gap:10px;margin-top:18px;padding:14px 40px;background:linear-gradient(135deg,#f2d67c,#d4af37 55%,#a87e1f);border:none;border-radius:999px;color:#1a0f2e;font-size:12px;letter-spacing:.22em;text-transform:uppercase;cursor:pointer;font-family:'Jost';font-weight:500;transition:.35s;box-shadow:0 8px 28px rgba(212,175,55,.4)}
.btn:hover{transform:translateY(-2px)}
.btn-line{display:inline-block;padding:12px 32px;border:1px solid var(--emas);color:var(--emas-lt);font-size:11px;letter-spacing:.26em;text-transform:uppercase;cursor:pointer;background:transparent;border-radius:999px;transition:.3s;text-decoration:none;font-family:'Jost'}
.btn-line:hover{background:rgba(212,175,55,.14);box-shadow:0 0 18px rgba(212,175,55,.3)}
/* ---------- section ---------- */
section{padding:66px 26px;text-align:center;position:relative}
.sec-kicker{font-size:10px;letter-spacing:.44em;text-transform:uppercase;color:var(--emas);margin-bottom:10px;font-weight:500}
.sec-title{font-family:'Cinzel Decorative',serif;font-size:30px;font-weight:700;color:var(--emas-lt);text-shadow:0 0 24px rgba(212,175,55,.45)}
.star-div{display:flex;align-items:center;justify-content:center;gap:10px;margin:16px auto;max-width:260px}
.star-div::before,.star-div::after{content:'';height:1px;width:90px;background:linear-gradient(90deg,transparent,var(--emas-dk))}
.star-div::after{background:linear-gradient(90deg,var(--emas-dk),transparent)}
.star-div svg{width:26px;flex:none}
.card{background:linear-gradient(180deg,rgba(52,32,86,.92),rgba(30,18,54,.96));border:1px solid rgba(212,175,55,.5);border-radius:12px;padding:36px 24px;margin:0 auto 18px;max-width:400px;box-shadow:0 14px 40px rgba(0,0,0,.55),inset 0 0 30px rgba(212,175,55,.06);position:relative}
.card::before{content:'';position:absolute;inset:7px;border:1px solid rgba(212,175,55,.22);border-radius:8px;pointer-events:none}
#hero{min-height:98vh;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden}
.hero-content{position:relative;text-align:center;padding:70px 26px 190px}
.hero-hand{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:24px;color:var(--emas)}
.hero-names{font-family:'Cinzel Decorative',serif;font-weight:700;font-size:clamp(38px,10.5vw,56px);line-height:1.4;margin:10px 0;
background:linear-gradient(180deg,#f7e08a,#d4af37 50%,#a87e1f 85%,#f2d67c);-webkit-background-clip:text;background-clip:text;color:transparent;
filter:drop-shadow(0 2px 14px rgba(212,175,55,.4))}
.hero-names em{font-style:normal;-webkit-text-fill-color:var(--emas-dk)}
.hero-date{letter-spacing:.3em;font-size:13px;color:var(--tinta-dim);text-transform:uppercase}
.cd-grid{display:flex;justify-content:center;gap:10px;margin-top:26px}
.cd{width:72px;padding:16px 0;background:rgba(30,18,54,.9);border:1px solid rgba(212,175,55,.55);border-radius:12px;box-shadow:0 8px 22px rgba(0,0,0,.5)}
.cd b{display:block;font-family:'Cinzel Decorative',serif;font-size:28px;color:var(--emas-lt)}
.cd span{font-size:9px;letter-spacing:.26em;text-transform:uppercase;color:var(--tinta-dim)}
.photo{width:150px;height:150px;border-radius:50%;object-fit:cover;margin:0 auto 16px;border:3px solid var(--emas);box-shadow:0 0 30px rgba(212,175,55,.45);display:block}
.photo-fallback{width:150px;height:150px;border-radius:50%;margin:0 auto 16px;background:radial-gradient(circle at 35% 30%,#3d2a63,#1a0f2e);border:3px solid var(--emas);box-shadow:0 0 30px rgba(212,175,55,.45);display:flex;align-items:center;justify-content:center;font-family:'Cinzel Decorative',serif;font-size:52px;color:var(--emas-lt)}
.couple-card h3{font-family:'Cinzel Decorative',serif;font-size:30px;color:var(--emas-lt);font-weight:700}
.couple-card .full{font-family:'Cormorant Garamond',serif;font-size:19px;margin:6px 0;color:var(--tinta)}
.couple-card p{font-size:14px;color:var(--tinta-dim);line-height:1.7}
.amp{font-family:'Cinzel Decorative',serif;font-size:40px;color:var(--emas);margin:2px 0;text-shadow:0 0 20px rgba(212,175,55,.5)}
.event h3{font-family:'Cinzel Decorative',serif;font-size:22px;letter-spacing:.08em;color:var(--emas-lt)}
.event .date{font-family:'Cormorant Garamond',serif;font-size:20px;margin:12px 0 4px}
.event .time{letter-spacing:.22em;font-size:12px;color:var(--emas)}
.event .loc{font-size:14px;color:var(--tinta-dim);margin:12px 0 18px;line-height:1.7}
.g-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:24px}
.g-grid img{width:100%;height:190px;object-fit:cover;border-radius:10px;border:2px solid rgba(212,175,55,.6);box-shadow:0 8px 22px rgba(0,0,0,.5);transition:.4s}
.g-grid img:hover{transform:scale(1.03)}
.tl{max-width:400px;margin:26px auto 0;text-align:left}
.tl-item{background:rgba(43,26,74,.9);border-left:3px solid var(--emas);border-radius:0 10px 10px 0;padding:18px 20px;margin-bottom:14px;box-shadow:0 8px 22px rgba(0,0,0,.45)}
.tl-item h4{font-family:'Cinzel Decorative',serif;font-size:18px;color:var(--emas-lt)}
.tl-item p{font-size:14px;color:var(--tinta-dim);line-height:1.7;margin-top:6px}
.story-icon{display:block;width:130px;margin:6px auto 2px;filter:drop-shadow(0 0 16px rgba(212,175,55,.4))}
.field{margin:0 auto 14px;max-width:380px;text-align:left}
.field label{display:block;font-size:10px;letter-spacing:.32em;text-transform:uppercase;color:var(--emas);margin-bottom:8px;font-weight:500}
.field input,.field select,.field textarea{width:100%;background:rgba(26,15,46,.9);border:1px solid rgba(212,175,55,.5);border-radius:10px;color:var(--tinta);padding:13px 16px;font-family:'Jost';font-size:15px;outline:none}
.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--emas-lt);box-shadow:0 0 0 3px rgba(212,175,55,.15)}
.wish{background:rgba(43,26,74,.9);border:1px solid rgba(212,175,55,.35);border-radius:10px;padding:16px 18px;margin:0 auto 12px;max-width:400px;text-align:left}
.wish b{font-family:'Cormorant Garamond',serif;color:var(--emas-lt);font-size:17px}
.wish .st{font-size:10px;letter-spacing:.2em;color:var(--emas);text-transform:uppercase;margin-left:8px;font-weight:500}
.wish p{font-size:14px;color:var(--tinta-dim);margin-top:6px;line-height:1.6}
.bank{background:rgba(43,26,74,.9);border:1px dashed rgba(212,175,55,.6);border-radius:10px;padding:24px;margin:0 auto 14px;max-width:380px}
.bank .bk{font-size:11px;letter-spacing:.34em;color:var(--emas);text-transform:uppercase;font-weight:500}
.bank .no{font-family:'Cinzel Decorative',serif;font-size:24px;margin:10px 0 4px;letter-spacing:.05em;color:var(--emas-lt)}
.bank .an{font-size:14px;color:var(--tinta-dim)}
.copy{margin-top:12px}
footer{padding:60px 26px 110px;text-align:center;position:relative}
footer .dekor{font-size:34px;color:var(--emas-lt);text-shadow:0 0 22px rgba(212,175,55,.45)}
#musBtn{position:fixed;bottom:22px;right:22px;z-index:90;width:52px;height:52px;border-radius:50%;border:1px solid var(--emas);background:linear-gradient(135deg,#3d2a63,#1a0f2e);color:var(--emas-lt);font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 0 22px rgba(212,175,55,.4)}

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
.fmenu{position:fixed;bottom:14px;left:50%;transform:translateX(-50%);z-index:90;display:flex;gap:2px;background:#1a0f2ef2;border:1px solid #d4af3755;border-radius:999px;padding:7px 9px;backdrop-filter:blur(8px);box-shadow:0 10px 30px rgba(0,0,0,.35);max-width:96vw;overflow-x:auto;scrollbar-width:none}
.fmenu::-webkit-scrollbar{display:none}
.fmenu a{display:flex;flex-direction:column;align-items:center;gap:3px;min-width:50px;padding:6px 7px;border-radius:12px;color:#f7ecd4;opacity:.6;text-decoration:none;font-size:9px;letter-spacing:.06em;transition:.25s;font-family:'Jost',sans-serif}
.fmenu a i{font-style:normal;font-size:17px;line-height:1}
.fmenu a.active{opacity:1;background:#d4af372e;color:#f2d67c}
#musBtn.playing{outline:2px solid #f2d67c;outline-offset:2px}
.att-toggle{display:flex;gap:10px}
.att-toggle button{flex:1;padding:13px 8px;border:1px solid #d4af37;background:transparent;color:#f7ecd4;font-family:'Jost',sans-serif;font-size:14px;cursor:pointer;transition:.25s;border-radius:10px}
.att-toggle button.on{background:#d4af37;color:#1a0f2e;font-weight:500}
.wish-empty{text-align:center;opacity:.6;font-size:14px;padding:12px}
body.locked #fmenu,body.locked #musBtn,body.locked .pbar{opacity:0 !important;visibility:hidden !important;pointer-events:none !important}

.rv{opacity:0;transform:translateY(36px);transition:opacity .9s,transform .9s}
.rv.on{opacity:1;transform:none}
</style>
</head>
<body>
<div class="pbar"><i id="pbarFill"></i></div>
<!-- ======== DEFS: gradien emas, pola arabesque, lentera, istana, bulan, karpet, lampu, cincin ======== -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
<defs>
<linearGradient id="goldG" x1="0" y1="0" x2="1" y2="1">
<stop offset="0" stop-color="#f7e08a"/><stop offset=".45" stop-color="#d4af37"/><stop offset=".8" stop-color="#a87e1f"/><stop offset="1" stop-color="#f2d67c"/>
</linearGradient>
<radialGradient id="lglow" cx=".5" cy=".45" r=".65">
<stop offset="0" stop-color="#ffe9a8"/><stop offset=".55" stop-color="#f5b942"/><stop offset="1" stop-color="#c97b1e"/>
</radialGradient>
<radialGradient id="moonG" cx=".4" cy=".4" r=".8">
<stop offset="0" stop-color="#fff6d8"/><stop offset=".7" stop-color="#f2d67c"/><stop offset="1" stop-color="#e0b84e"/>
</radialGradient>
<linearGradient id="palG" x1="0" y1="0" x2="0" y2="1">
<stop offset="0" stop-color="#e8c860"/><stop offset="1" stop-color="#9a7420"/>
</linearGradient>
<pattern id="arab" width="96" height="96" patternUnits="userSpaceOnUse">
<g fill="none" stroke="#d4af37" stroke-width="1.1" opacity=".55">
<path d="M48 10 L57 39 L86 48 L57 57 L48 86 L39 57 L10 48 L39 39 Z"/>
<circle cx="48" cy="48" r="7"/>
<path d="M0 0 L14 14 M96 0 L82 14 M0 96 L14 82 M96 96 L82 82" opacity=".6"/>
</g>
</pattern>
<symbol id="fanous" viewBox="0 0 100 170">
<circle cx="50" cy="10" r="5" fill="none" stroke="#d4af37" stroke-width="3"/>
<path d="M38 22 L62 22 L56 34 L44 34 Z" fill="#d4af37"/>
<path d="M50 34 C30 50 26 78 34 104 L66 104 C74 78 70 50 50 34 Z" fill="url(#lglow)" opacity=".95"/>
<path d="M50 34 C42 52 40 78 43 104 M50 34 C58 52 60 78 57 104" stroke="#a87e1f" stroke-width="2" fill="none" opacity=".7"/>
<path d="M34 104 L66 104 L60 116 L40 116 Z" fill="#d4af37"/>
<circle cx="50" cy="124" r="4" fill="#f2d67c"/>
<g stroke="#f2d67c" stroke-width="2"><line x1="50" y1="128" x2="50" y2="152"/><line x1="44" y1="130" x2="42" y2="150"/><line x1="56" y1="130" x2="58" y2="150"/></g>
</symbol>
<symbol id="palace" viewBox="0 0 520 200">
<rect x="0" y="150" width="520" height="50" fill="url(#palG)"/>
<g fill="#ffe9a8" opacity=".9">
<path d="M40 150 v-22 a11 11 0 0 1 22 0 v22 Z"/><path d="M100 150 v-22 a11 11 0 0 1 22 0 v22 Z"/>
<path d="M160 150 v-22 a11 11 0 0 1 22 0 v22 Z"/><path d="M338 150 v-22 a11 11 0 0 1 22 0 v22 Z"/>
<path d="M398 150 v-22 a11 11 0 0 1 22 0 v22 Z"/><path d="M458 150 v-22 a11 11 0 0 1 22 0 v22 Z"/>
<path d="M249 150 v-26 a11 11 0 0 1 22 0 v26 Z"/>
</g>
<rect x="228" y="100" width="64" height="50" fill="url(#palG)"/>
<path d="M260 18 C288 52 302 74 302 100 C302 122 282 134 260 134 C238 134 218 122 218 100 C218 74 232 52 260 18 Z" fill="url(#palG)"/>
<line x1="260" y1="18" x2="260" y2="2" stroke="#f2d67c" stroke-width="3"/>
<path d="M266 -2 A10 10 0 1 0 266 18 A7.5 7.5 0 1 1 266 -2 Z" fill="#f2d67c"/>
<rect x="128" y="118" width="44" height="32" fill="url(#palG)"/>
<path d="M150 62 C168 84 176 98 176 114 C176 128 164 136 150 136 C136 136 124 128 124 114 C124 98 132 84 150 62 Z" fill="url(#palG)"/>
<rect x="348" y="118" width="44" height="32" fill="url(#palG)"/>
<path d="M370 62 C388 84 396 98 396 114 C396 128 384 136 370 136 C356 136 344 128 344 114 C344 98 352 84 370 62 Z" fill="url(#palG)"/>
<rect x="52" y="60" width="16" height="90" fill="url(#palG)"/>
<rect x="44" y="88" width="32" height="8" fill="url(#palG)"/>
<path d="M60 26 C70 38 74 46 74 56 C74 64 67 68 60 68 C53 68 46 64 46 56 C46 46 50 38 60 26 Z" fill="url(#palG)"/>
<rect x="452" y="60" width="16" height="90" fill="url(#palG)"/>
<rect x="444" y="88" width="32" height="8" fill="url(#palG)"/>
<path d="M460 26 C470 38 474 46 474 56 C474 64 467 68 460 68 C453 68 446 64 446 56 C446 46 450 38 460 26 Z" fill="url(#palG)"/>
</symbol>
<symbol id="bulan" viewBox="0 0 120 120">
<mask id="mcut"><rect width="120" height="120" fill="#fff"/><circle cx="82" cy="38" r="46" fill="#000"/></mask>
<circle cx="58" cy="62" r="44" fill="url(#moonG)" mask="url(#mcut)"/>
</symbol>
<symbol id="karpet" viewBox="0 0 230 112">
<rect x="8" y="14" width="214" height="84" rx="10" fill="#7a1f3d"/>
<rect x="8" y="14" width="214" height="84" rx="10" fill="none" stroke="#d4af37" stroke-width="4"/>
<rect x="22" y="26" width="186" height="60" rx="6" fill="none" stroke="#d4af37" stroke-width="2" opacity=".8"/>
<path d="M115 34 L155 56 L115 78 L75 56 Z" fill="none" stroke="#f2d67c" stroke-width="2.5"/>
<circle cx="115" cy="56" r="8" fill="#f2d67c" opacity=".9"/>
<g stroke="#f2d67c" stroke-width="2.5">
<line x1="2" y1="30" x2="2" y2="44"/><line x1="2" y1="52" x2="2" y2="66"/><line x1="2" y1="74" x2="2" y2="88"/>
<line x1="228" y1="30" x2="228" y2="44"/><line x1="228" y1="52" x2="228" y2="66"/><line x1="228" y1="74" x2="228" y2="88"/>
</g>
</symbol>
<symbol id="lampu" viewBox="0 0 230 130">
<ellipse cx="112" cy="114" rx="54" ry="9" fill="#8a6a1f" opacity=".8"/>
<path d="M45 78 C80 62 150 62 185 82 C150 100 80 100 45 78 Z" fill="url(#goldG)"/>
<path d="M185 78 C205 72 218 58 224 40 L212 34 C204 50 192 60 180 66 Z" fill="url(#goldG)"/>
<path d="M48 74 C28 70 20 54 30 42 C36 52 44 60 56 64" fill="none" stroke="#d4af37" stroke-width="6" stroke-linecap="round"/>
<path d="M95 62 L135 62 L128 44 L102 44 Z" fill="url(#goldG)"/>
<circle cx="115" cy="36" r="6" fill="#f2d67c"/>
<path d="M115 18 C118 28 111 32 115 44 C118 32 125 30 122 18 C120 28 116 26 115 18 Z" fill="#ffe9a8"/>
<g fill="#f2d67c"><circle cx="58" cy="28" r="2.5"/><circle cx="172" cy="22" r="2"/><circle cx="198" cy="48" r="2.5"/><circle cx="36" cy="52" r="2"/></g>
</symbol>
<symbol id="cincin" viewBox="0 0 200 110">
<circle cx="80" cy="62" r="32" fill="none" stroke="url(#goldG)" stroke-width="9"/>
<circle cx="122" cy="62" r="32" fill="none" stroke="url(#goldG)" stroke-width="9"/>
<g transform="translate(122 18)"><path d="M0 -11 L9 0 L0 11 L-9 0 Z" fill="#e4f2ff" stroke="#9db8d8" stroke-width="1.5"/><path d="M-9 0 H9 M0 -11 V11" stroke="#9db8d8" stroke-width="1"/></g>
</symbol>
<symbol id="bintang8" viewBox="0 0 40 40">
<path d="M20 2 L24 16 L38 20 L24 24 L20 38 L16 24 L2 20 L16 16 Z" fill="#d4af37"/>
</symbol>
</defs>
</svg>

<div class="arab-layer"><svg><rect width="100%" height="100%" fill="url(#arab)"/></svg></div>
<canvas id="petals"></canvas>
<div class="wrap">

<!-- ================= COVER ================= -->
<div id="cover">
<div class="stars"></div>
<div class="lant-scene">
<div class="l-drift" style="left:8%;animation-duration:24s;animation-delay:-6s"><div class="l-swing d-far" style="animation-duration:4.2s"><svg viewBox="0 0 100 170"><use href="#fanous"/></svg></div></div>
<div class="l-drift" style="left:70%;animation-duration:30s;animation-delay:-18s"><div class="l-swing d-mid" style="animation-duration:5.4s;animation-delay:-2s"><svg viewBox="0 0 100 170"><use href="#fanous"/></svg></div></div>
<div class="l-drift" style="left:38%;animation-duration:27s;animation-delay:-12s"><div class="l-swing d-near" style="animation-duration:4.8s;animation-delay:-1s"><svg viewBox="0 0 100 170"><use href="#fanous"/></svg></div></div>
</div>
<svg class="moon" viewBox="0 0 120 120" style="width:92px;top:56px;right:8%"><use href="#bulan"/></svg>
<div class="cover-inner">
<div class="kicker">The Wedding Of</div>
<div class="cover-names">{{ $brideNick }} &amp; {{ $groomNick }}</div>
<div class="cover-date">{{ mcDate($wDate) }}</div>
<div class="star-div rv"><svg viewBox="0 0 40 40"><use href="#bintang8"/></svg></div>
<div class="kepada">Kepada Yth.<br>Bapak/Ibu/Saudara/i</div>
<div class="guest-box"><b>{{ $guestName }}</b></div>
<div><button class="btn" onclick="openInv()">&#9993; &nbsp;Buka Undangan</button></div>
</div>
<svg class="palace" viewBox="0 0 520 200" preserveAspectRatio="xMidYMax slice" style="height:120px"><use href="#palace"/></svg>
</div>

<!-- ================= HERO ================= -->
<section id="hero">
<div class="stars"></div>
<div class="lant-scene">
<div class="l-drift" style="left:5%;animation-duration:26s;animation-delay:-3s"><div class="l-swing d-mid" style="animation-duration:5s"><svg viewBox="0 0 100 170"><use href="#fanous"/></svg></div></div>
<div class="l-drift" style="left:22%;animation-duration:32s;animation-delay:-20s"><div class="l-swing d-far" style="animation-duration:3.8s;animation-delay:-1.5s"><svg viewBox="0 0 100 170"><use href="#fanous"/></svg></div></div>
<div class="l-drift" style="left:48%;animation-duration:24s;animation-delay:-11s"><div class="l-swing d-near" style="animation-duration:4.4s;animation-delay:-.8s"><svg viewBox="0 0 100 170"><use href="#fanous"/></svg></div></div>
<div class="l-drift" style="left:66%;animation-duration:29s;animation-delay:-24s"><div class="l-swing d-far" style="animation-duration:4.9s"><svg viewBox="0 0 100 170"><use href="#fanous"/></svg></div></div>
<div class="l-drift" style="left:84%;animation-duration:25s;animation-delay:-15s"><div class="l-swing d-mid" style="animation-duration:5.8s;animation-delay:-3s"><svg viewBox="0 0 100 170"><use href="#fanous"/></svg></div></div>
</div>
<svg class="moon" viewBox="0 0 120 120" style="width:130px;top:44px;right:6%"><use href="#bulan"/></svg>
<div class="carpet" style="animation-delay:-9s"><div class="carpet-in"><svg viewBox="0 0 230 112" style="width:190px"><use href="#karpet"/></svg></div></div>
<div class="hero-content">
<div class="rv"><div class="hero-hand">The Wedding Of</div></div>
<div class="rv"><h1 class="hero-names">{{ $brideNick }} <em>&amp;</em> {{ $groomNick }}</h1></div>
<div class="star-div rv"><svg viewBox="0 0 40 40"><use href="#bintang8"/></svg></div>
<div class="rv"><div class="hero-date">{{ mcDate($wDate) }}</div>
@if(mcGet('quotes'))
<div class="rv" style="max-width:420px;margin:20px auto 0;padding:0 8px">
<p style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:19px;line-height:1.7;opacity:.85">“{{ mcGet('quotes') }}”</p>
</div>
@endif</div>
</div>
<svg class="palace" viewBox="0 0 520 200" preserveAspectRatio="xMidYMax slice" style="height:150px"><use href="#palace"/></svg>
</section>

<!-- COUNTDOWN -->
<section>
<div class="rv"><div class="sec-kicker">Menuju Hari Bahagia</div><div class="sec-title">Hitung Mundur</div></div>
<div class="star-div rv"><svg viewBox="0 0 40 40"><use href="#bintang8"/></svg></div>
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
<div class="star-div rv"><svg viewBox="0 0 40 40"><use href="#bintang8"/></svg></div>
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
<div class="star-div rv"><svg viewBox="0 0 40 40"><use href="#bintang8"/></svg></div>
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
<div class="star-div rv"><svg viewBox="0 0 40 40"><use href="#bintang8"/></svg></div>
<div class="g-grid rv">
@foreach(array_slice($gallery, 0, 8) as $img)<img src="{{ $img }}" alt="Galeri {{ $brideNick }} & {{ $groomNick }}" loading="lazy">@endforeach
</div>
</section>
@endif

<!-- KISAH -->
@if(!empty($stories) && count($stories))
<section id="kisah">
<div class="rv"><div class="sec-kicker">Perjalanan Kami</div><div class="sec-title">Kisah Cinta</div></div>
<svg class="story-icon rv" viewBox="0 0 230 130"><use href="#lampu"/></svg>
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
<div class="star-div rv"><svg viewBox="0 0 40 40"><use href="#bintang8"/></svg></div>
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
<div class="star-div rv"><svg viewBox="0 0 40 40"><use href="#bintang8"/></svg></div>
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
<div class="star-div rv"><svg viewBox="0 0 40 40"><use href="#bintang8"/></svg></div>
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
<svg class="story-icon" viewBox="0 0 200 110" style="width:150px"><use href="#cincin"/></svg>
<div class="dekor">Terima Kasih</div>
<p style="color:var(--tinta-dim);font-size:14px;line-height:1.8;max-width:380px;margin:14px auto">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>
<div class="dekor" style="font-size:20px;color:var(--emas-lt)">{{ $brideNick }} &amp; {{ $groomNick }}</div>
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
// bintang berkelip di langit malam
(function(){
  document.querySelectorAll('.stars').forEach(function(box){
    for(let i=0;i<70;i++){
      const s=document.createElement('i');
      const sz=(Math.random()*2+1).toFixed(1);
      s.style.cssText='left:'+(Math.random()*100)+'%;top:'+(Math.random()*100)+'%;width:'+sz+'px;height:'+sz+'px;animation-duration:'+(2+Math.random()*3).toFixed(2)+'s;animation-delay:-'+(Math.random()*4).toFixed(2)+'s';
      box.appendChild(s);
    }
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
