<?php
/* ============================================================
   TEMA PREMIUM: JAWA KLASIK
   Dipilih via settings: theme = jawa-premium
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
/* Preset warna admin (theme_primary dkk; fallback Classic Gold). */
$presetPrimary = mcGet('theme_primary', '#361f1a');
$presetSecondary = mcGet('theme_secondary', '#775a19');
$presetBackground = mcGet('theme_background', '#fbf9f5');
$presetPrimaryContainer = mcGet('theme_primary_container', '#4e342e');
$presetSecondaryContainer = mcGet('theme_secondary_container', '#fed488');
$presetSurfaceLow = mcGet('theme_surface_container_low', '#f5f3ef');
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
$sameLoc = (mcGet('event_location_mode', 'same') === 'same');
$mainMapLink = trim((string)mcGet('wedding_map_link')) ?: trim((string)mcGet('reception_map_link'));
$giftRecipient = mcGet('gift_recipient_name', mcGet('gift_owner', ''));
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
$qrisEnabled = mcGet('gift_qris_enabled', '') === '1' && trim((string)mcGet('gift_qris_image', '')) !== '';
$qrisImage = mcGet('gift_qris_image', '');
$qrisName = mcGet('gift_qris_name', '');
$musicUrl = null;
if (!empty($musicRecord) && !empty($musicRecord->file_path)) {
  $mp = $musicRecord->file_path;
  $musicUrl = (str_starts_with($mp,'http') || str_starts_with($mp,'/')) ? $mp : asset(ltrim($mp,'/'));
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
<title>{{ $brideNick }} &amp; {{ $groomNick }} — Undangan Jawa Klasik</title>
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="Undangan Pernikahan {{ $brideNick }} & {{ $groomNick }}">
<meta property="og:description" content="Tanpa mengurangi rasa hormat, kami mengundang Anda untuk menghadiri pernikahan {{ $brideFull }} & {{ $groomFull }}. Klik untuk melihat detail undangan.">
<meta property="og:image" content="{{ asset(mcGet('og_preview_photo') ? mcGet('og_preview_photo') : ($bridePhoto ? $bridePhoto : ($groomPhoto ? $groomPhoto : 'gunungan_utuh.png'))) }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Pinyon+Script&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<style>/* Preset warna admin — 6 warna theme_preset (dibaca di preamble). */:root{--preset-primary:{{ $presetPrimary }};--preset-secondary:{{ $presetSecondary }};--preset-background:{{ $presetBackground }};--preset-primary-container:{{ $presetPrimaryContainer }};--preset-secondary-container:{{ $presetSecondaryContainer }};--preset-surface-low:{{ $presetSurfaceLow }}}</style>
<style>
:root{--soga:var(--preset-primary-container);--soga2:var(--preset-primary-container);--prada:var(--preset-secondary);--prada-lt:var(--preset-secondary-container);--gading:var(--preset-surface-low);--gading-dim:rgba(245,234,214,.7)}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{font-family:'Jost',sans-serif;background:var(--soga);color:var(--gading);overflow-x:hidden;font-weight:300}
/* pola kawung */
body::before{content:'';position:fixed;inset:0;z-index:0;pointer-events:none;opacity:.5;
  background-image:radial-gradient(circle at 50% 50%,transparent 34px,rgba(212,175,55,.10) 35px,rgba(212,175,55,.10) 37px,transparent 38px);
  background-size:96px 96px}
#petal{position:fixed;inset:0;z-index:1;pointer-events:none}
.wrap{position:relative;z-index:2;max-width:520px;margin:0 auto}
.serif{font-family:'Cormorant Garamond',serif}.aksara{font-family:'Pinyon Script',cursive}
#cover{position:fixed;inset:0;z-index:100;background:radial-gradient(ellipse at 50% 25%,#4a2410 0%,var(--preset-primary-container) 70%);display:flex;align-items:center;justify-content:center;transition:opacity .9s,visibility .9s}
#cover.open{opacity:0;visibility:hidden}
.cover-card{text-align:center;padding:40px 30px;margin:24px;max-width:420px;border:2px solid var(--prada);position:relative;background:rgba(36,16,9,.6)}
.cover-card::before{content:'';position:absolute;inset:8px;border:1px solid rgba(212,175,55,.45);pointer-events:none}
.gunungan{width:120px;margin:0 auto 10px;display:block;filter:drop-shadow(0 0 12px rgba(212,175,55,.5))}
.kicker{font-size:11px;letter-spacing:.4em;text-transform:uppercase;color:var(--prada)}
.cover-names{font-family:'Pinyon Script',cursive;font-size:54px;color:var(--gading);margin:12px 0;line-height:1.15}
.btn{display:inline-block;margin-top:22px;padding:14px 40px;background:linear-gradient(135deg,var(--prada-lt),var(--prada));color:#2a1408;font-size:12px;letter-spacing:.3em;text-transform:uppercase;cursor:pointer;border:none;transition:.35s;font-family:'Jost';font-weight:500}
.btn:hover{box-shadow:0 0 28px rgba(212,175,55,.6);transform:translateY(-2px)}
.btn-line{display:inline-block;padding:12px 32px;border:1px solid var(--prada);color:var(--prada-lt);font-size:12px;letter-spacing:.26em;text-transform:uppercase;cursor:pointer;background:transparent;transition:.3s;text-decoration:none;font-family:'Jost'}
.btn-line:hover{background:rgba(212,175,55,.14)}
.guest{margin-top:18px;font-size:13px;color:var(--gading-dim)}
.guest b{display:block;font-family:'Cormorant Garamond',serif;font-size:23px;color:var(--gading);margin-top:6px}
section{padding:70px 26px;text-align:center;position:relative}
.sec-kicker{font-size:11px;letter-spacing:.4em;text-transform:uppercase;color:var(--prada);margin-bottom:10px}
.sec-title{font-family:'Cormorant Garamond',serif;font-size:38px;font-weight:600}
.ukel{display:flex;align-items:center;justify-content:center;gap:10px;margin:16px auto;max-width:300px;color:var(--prada)}
.ukel::before,.ukel::after{content:'';height:1px;flex:1;background:linear-gradient(90deg,transparent,var(--prada))}
.ukel::after{background:linear-gradient(90deg,var(--prada),transparent)}
#hero{min-height:94vh;display:flex;flex-direction:column;justify-content:center;background:radial-gradient(ellipse at 50% 0%,#4a2410 0%,transparent 62%)}
.hero-script{font-family:'Pinyon Script',cursive;font-size:32px;color:var(--prada-lt)}
.hero-names{font-family:'Cormorant Garamond',serif;font-size:54px;line-height:1.08;margin:14px 0;font-weight:700}
.hero-names em{font-family:'Pinyon Script',cursive;font-weight:400;color:var(--prada-lt);font-size:42px}
.hero-date{letter-spacing:.32em;font-size:13px;color:var(--gading-dim);text-transform:uppercase}
.cd-grid{display:flex;justify-content:center;gap:10px;margin-top:26px}
.cd{width:72px;padding:16px 0;background:rgba(212,175,55,.07);border:1px solid rgba(212,175,55,.45)}
.cd b{display:block;font-family:'Cormorant Garamond',serif;font-size:34px;color:var(--prada-lt)}
.cd span{font-size:10px;letter-spacing:.28em;text-transform:uppercase;color:var(--gading-dim)}
.couple-card{border:1px solid rgba(212,175,55,.4);background:linear-gradient(180deg,rgba(212,175,55,.08),transparent);padding:34px 22px;margin:0 auto 20px;max-width:380px}
.photo{width:148px;height:148px;border-radius:50%;object-fit:cover;margin:0 auto 16px;border:2px solid var(--prada);padding:5px;display:block}
.photo-fallback{width:148px;height:148px;border-radius:50%;margin:0 auto 16px;border:2px solid var(--prada);display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:62px;color:var(--prada-lt);background:radial-gradient(circle,var(--preset-primary-container),var(--preset-primary-container))}
.couple-card h3{font-family:'Pinyon Script',cursive;font-size:40px;color:var(--prada-lt);font-weight:400}
.couple-card .full{font-family:'Cormorant Garamond',serif;font-size:19px;margin:6px 0}
.couple-card p{font-size:14px;color:var(--gading-dim);line-height:1.7}
.amp{font-family:'Pinyon Script',cursive;font-size:52px;color:var(--prada);margin:4px 0}
.event{border:1px solid rgba(212,175,55,.4);padding:36px 24px;margin:0 auto 20px;max-width:400px;background:rgba(36,16,9,.55);position:relative}
.event::before{content:'';position:absolute;inset:8px;border:1px solid rgba(212,175,55,.22);pointer-events:none}
.event h3{font-family:'Cormorant Garamond',serif;font-size:28px;letter-spacing:.14em;text-transform:uppercase;color:var(--prada-lt)}
.event .date{font-family:'Cormorant Garamond',serif;font-size:20px;margin:12px 0 4px}
.event .time{letter-spacing:.24em;font-size:13px;color:var(--prada)}
.event .loc{font-size:14px;color:var(--gading-dim);margin:12px 0 18px;line-height:1.7}
.g-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:24px}
.g-grid img{width:100%;height:190px;object-fit:cover;border:1px solid rgba(212,175,55,.4);transition:.4s}
.g-grid img:hover{transform:scale(1.03)}
.tl{max-width:400px;margin:26px auto 0;text-align:left;padding-left:26px;position:relative}
.tl::before{content:'';position:absolute;left:8px;top:6px;bottom:6px;width:1px;background:linear-gradient(var(--prada),transparent)}
.tl-item{position:relative;padding:0 0 28px 18px}
.tl-item::before{content:'❋';position:absolute;left:-26px;top:-2px;color:var(--prada)}
.tl-item h4{font-family:'Cormorant Garamond',serif;font-size:22px;color:var(--prada-lt)}
.tl-item p{font-size:14px;color:var(--gading-dim);line-height:1.7;margin-top:6px}
.field{margin:0 auto 14px;max-width:380px;text-align:left}
.field label{display:block;font-size:10px;letter-spacing:.32em;text-transform:uppercase;color:var(--prada);margin-bottom:8px}
.field input,.field select,.field textarea{width:100%;background:rgba(245,234,214,.05);border:1px solid rgba(212,175,55,.45);color:var(--gading);padding:13px 14px;font-family:'Jost';font-size:15px;outline:none}
.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--prada)}
.field select option{color:#222}
.wish{border-left:2px solid var(--prada);background:rgba(212,175,55,.06);padding:14px 16px;margin:0 auto 12px;max-width:400px;text-align:left}
.wish-head{display:flex;justify-content:space-between;align-items:baseline;gap:10px;margin-bottom:6px}
.wtime{font-size:11px;opacity:.55;white-space:nowrap}
.wish b{font-family:'Cormorant Garamond',serif;color:var(--prada-lt);font-size:17px}
.wish .st{font-size:10px;letter-spacing:.2em;color:var(--prada);text-transform:uppercase;margin-left:8px}
.wish p{font-size:14px;color:var(--gading-dim);margin-top:6px;line-height:1.6}
.bank{border:1px solid rgba(212,175,55,.45);padding:22px;margin:0 auto 14px;max-width:380px;background:rgba(212,175,55,.05)}
.bank .bk{font-size:11px;letter-spacing:.34em;color:var(--prada);text-transform:uppercase}
.bank .no{font-family:'Cormorant Garamond',serif;font-size:30px;letter-spacing:.06em;margin:10px 0 4px}
.bank .an{font-size:14px;color:var(--gading-dim)}
.copy{margin-top:12px}
footer{padding:60px 26px 90px;text-align:center}
footer .aksara{font-size:42px;color:var(--prada-lt)}
#musBtn{position:fixed;bottom:22px;right:22px;z-index:90;width:52px;height:52px;border-radius:50%;border:1px solid var(--prada);background:rgba(36,16,9,.9);color:var(--prada-lt);font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center}
#qrFloatBtn{position:fixed;bottom:192px;right:22px;z-index:90;width:52px;height:52px;border-radius:50%;border:1px solid var(--preset-secondary);background:linear-gradient(160deg,var(--preset-primary-container),var(--preset-primary));color:var(--preset-secondary-container);font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 26px rgba(90,70,20,.5)}

/* === fitur bawaan default: lightbox, menu, progress, musik === */
.pbar{position:fixed;top:0;left:0;right:0;height:3px;z-index:96;background:transparent}
.pbar i{display:block;height:100%;width:0;background:linear-gradient(90deg,var(--preset-secondary),var(--preset-secondary-container))}
.lb{position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.92);display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:.35s;padding:20px;touch-action:pan-y}
#lbImg{max-width:100%;max-height:86vh;border-radius:8px;will-change:transform,opacity}
.lb.show{opacity:1;visibility:visible}
.lb img{max-width:100%;max-height:86vh;border:2px solid var(--preset-secondary)}
.lb-x{position:absolute;top:16px;right:22px;font-size:40px;color:#fff;cursor:pointer;line-height:1;z-index:201}
.lb-prev,.lb-next{position:absolute;top:50%;transform:translateY(-50%);font-size:32px;color:#fff;cursor:pointer;padding:16px 12px;z-index:201;user-select:none;opacity:.85;line-height:1}
.lb-prev{left:6px}.lb-next{right:6px}.lb-prev:hover,.lb-next:hover{opacity:1}
.lb-count{position:absolute;bottom:16px;left:50%;transform:translateX(-50%);color:#fff;font-size:13px;letter-spacing:.15em;z-index:201;font-family:inherit}
.fmenu{position:fixed;bottom:10px;left:50%;transform:translateX(-50%);z-index:90;display:flex;gap:2px 0;background:var(--preset-background);border:1px solid var(--preset-secondary);border-radius:26px;padding:7px 8px;backdrop-filter:blur(10px);box-shadow:0 14px 34px rgba(90,70,20,.25);width:min(96vw,500px);max-width:96vw;flex-wrap:wrap;justify-content:center;overflow:visible;row-gap:2px}
.fmenu::-webkit-scrollbar{display:none}
.fmenu a{display:flex;flex-direction:column;align-items:center;gap:3px;flex:1 1 0;min-width:36px;max-width:54px;padding:7px 0;border-radius:13px;color:var(--preset-secondary);text-decoration:none;font-size:8px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;transition:.25s}
.fmenu a i{font-style:normal;line-height:0}
.fmenu a svg{width:21px;height:21px}
@media (max-width:400px){
  .fmenu a{flex:1 1 18%;min-width:0;max-width:64px}
  #musBtn{bottom:140px!important}
  #qrFloatBtn{bottom:202px!important}
}
.fmenu a.active{background:var(--preset-secondary-container);box-shadow:inset 0 0 0 1.5px var(--preset-secondary);color:var(--preset-secondary)}
body::after{content:"";display:block;height:150px}
#musBtn.playing{outline:2px solid var(--preset-secondary-container);outline-offset:2px}
.att-toggle{display:flex;gap:10px}
.att-toggle button{flex:1;padding:13px 8px;border:1px solid var(--preset-secondary);background:transparent;color:var(--preset-surface-low);font-family:'Jost',sans-serif;font-size:14px;cursor:pointer;transition:.25s;border-radius:10px}
.att-toggle button.on{background:var(--preset-secondary);color:var(--preset-primary-container);font-weight:500}
.wish-empty{text-align:center;opacity:.6;font-size:14px;padding:12px}
body.locked #fmenu,body.locked #musBtn,body.locked #qrFloatBtn,body.locked .pbar{opacity:0 !important;visibility:hidden !important;pointer-events:none !important}

.rv{opacity:0;transform:translateY(36px);transition:opacity .9s,transform .9s}
.rv.on{opacity:1;transform:none}
.rvd{opacity:0;transform:translateY(-36px);transition:opacity .9s,transform .9s}
.rvd.on{opacity:1;transform:none}
@media (prefers-reduced-motion:reduce){.rv,.rvd{opacity:1!important;transform:none!important;transition:none}}
@media(min-width:640px){.hero-names{font-size:66px}}

/* Standar menu klasik emas */
#musBtn{background:linear-gradient(160deg,var(--preset-primary-container),var(--preset-primary))!important;border:1px solid var(--preset-secondary)!important;color:var(--preset-secondary-container)!important;bottom:130px!important;box-shadow:0 10px 26px rgba(90,70,20,.5)!important}
</style>
</head>
<body>
<!-- Amplop pembuka Jawa: depan tertutup rapat (sisi alamat), dibuka dari belakang -->
<div id="envOverlay" aria-hidden="true">
  <div class="env-scene">
    <div class="env-head">
      <div class="env-head-label">THE WEDDING OF</div>
      <div class="env-head-names">{{ $brideNick }} &amp; {{ $groomNick }}</div>
    </div>
    <div class="env-row">
      <img class="env-wayang left" src="{{ asset('images/amplop/wayang-wanita-emas.png') }}" alt="">
      <div class="env-wrap">
      <div class="env-face env-face-front">
        <div class="env-front-paper">
          <div class="env-frame"></div>
          <div class="env-sender">Dari:<br><b>{{ $brideNick }} &amp; {{ $groomNick }}</b></div>
          <div class="env-rings"><div class="env-ring r1"></div><div class="env-ring r2"></div></div>
          <div class="env-recipient">Kepada Yth.<br><b>{{ $guestName }}</b><br><span class="env-to-apology">Mohon maaf apabila ada kesalahan penulisan nama dan gelar</span></div>
        </div>
      </div>
      <div class="env-face env-face-back">
<div class="env-card">
          <div class="env-card-label">THE WEDDING OF</div>
          <div class="env-card-names">{{ $groomNick }} &amp; {{ $brideNick }}</div>
          <div class="env-card-date" data-env-date="{{ $rDate }}"><span class="env-card-day"></span><span class="env-card-num"><b></b><i></i></span><span class="env-card-month"></span></div>
          <div class="env-card-div"></div>
          <div class="env-card-mark">&#10022;</div>
        </div>
        <div class="env-back"></div>
        <div class="env-front"></div>
        <div class="env-fold-l"></div><div class="env-fold-r"></div>
        <div class="env-flap"></div>
      </div>
    </div>
      <img class="env-wayang right" src="{{ asset('images/amplop/wayang-pria-emas.png') }}" alt="">
    </div>
    <button id="envOpenBtn" type="button" class="env-open-btn">BUKA UNDANGAN</button>
    <div class="env-hint">Ketuk untuk membuka undangan</div>
  </div>
</div>
<style>
#envOverlay{position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;overflow:hidden;background:radial-gradient(ellipse at 50% 28%,var(--preset-primary-container) 0%,var(--preset-primary-container) 72%),repeating-linear-gradient(45deg,rgba(212,163,31,.05) 0 2px,transparent 2px 14px);transition:opacity .5s ease,visibility 0s linear .5s}
#envOverlay.gone{opacity:0;visibility:hidden}
#envOverlay .env-scene{display:flex;flex-direction:column;align-items:center}
#envOverlay .env-head{text-align:center;margin-bottom:22px;transition:opacity .4s ease}
#envOverlay .env-head-label{font-size:11px;letter-spacing:.35em;color:var(--preset-secondary);font-weight:700}
#envOverlay .env-head-names{font-family:Georgia,serif;font-size:30px;font-weight:700;color:var(--preset-background);margin-top:8px}
#envOverlay .env-wrap{position:relative;width:min(320px,62vw);height:280px;perspective:900px;transform-style:preserve-3d;transition:transform .9s ease-in-out}
#envOverlay .env-row{display:flex;align-items:flex-end;justify-content:center;width:100%}
#envOverlay .env-wayang{height:min(260px,62vw);width:auto;flex:none;pointer-events:none;z-index:1}
#envOverlay .env-wayang.left{transform:scaleX(-1);margin-right:-8px}
#envOverlay .env-wayang.right{transform:scaleX(-1);margin-left:-8px}
#envOverlay .env-wrap{z-index:2;flex:none}
#envOverlay.flip .env-wrap{transform:rotateY(180deg)}
#envOverlay.zoom .env-wrap{transform:scale(2.2)}
#envOverlay .env-face{position:absolute;inset:0;backface-visibility:hidden;-webkit-backface-visibility:hidden}
#envOverlay .env-face-front{z-index:2}
#envOverlay .env-face-back{z-index:1;transform:rotateY(180deg)}
#envOverlay .env-front-paper{position:absolute;inset:0;background:linear-gradient(180deg,#fffdf4,#f6eeda);border:1px solid #ddd2b8;border-radius:10px;box-shadow:0 24px 60px rgba(0,0,0,.55);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:20px;text-align:center}
#envOverlay .env-frame{position:absolute;inset:10px;border:1px solid rgba(180,160,120,.55);border-radius:6px;pointer-events:none}
#envOverlay .env-sender{position:absolute;top:18px;left:18px;font-size:10px;letter-spacing:.12em;color:var(--preset-primary);text-align:left;line-height:1.6}
#envOverlay .env-sender b{font-size:11px;color:#5a4632;letter-spacing:.02em}
#envOverlay .env-rings{position:absolute;top:18px;right:14px;width:72px;height:60px}
#envOverlay .env-ring{position:absolute;top:0;width:44px;height:44px;border-radius:50%;border:5px solid var(--preset-secondary);box-shadow:0 0 0 1px #b8912a,inset 0 0 0 1px rgba(255,255,255,.5),0 4px 10px rgba(0,0,0,.25)}
#envOverlay .env-ring.r1{left:0}
#envOverlay .env-ring.r2{right:0;top:8px}
#envOverlay .env-recipient{position:relative;font-size:13px;color:#6b5a41;line-height:1.5}
#envOverlay .env-recipient b{font-size:20px;color:#3a2a1a}
#envOverlay .env-to-apology{display:block;margin-top:6px;font-size:11px;font-style:italic;line-height:1.45;color:var(--preset-primary)}
#envOverlay .env-back{position:absolute;inset:0;z-index:1;background:linear-gradient(180deg,#fffdf4,#f6eeda);border:1px solid #ddd2b8;border-radius:10px;box-shadow:0 24px 60px rgba(0,0,0,.55)}
#envOverlay .env-card{position:absolute;left:50%;top:10px;z-index:2;width:min(210px,66vw);height:260px;transform:translateX(-50%);background:linear-gradient(180deg,#fff8e7,#f3e4c2);border-radius:8px;padding:18px 14px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;text-align:center;box-shadow:0 8px 24px rgba(0,0,0,.3);transition:transform .85s cubic-bezier(.22,1,.36,1)}
#envOverlay.open .env-card,#envOverlay.zoom .env-card{transform:translateX(-50%) translateY(-200px);transition-delay:.25s}
#envOverlay.zoomcard .env-card{transform:translateX(-50%) translateY(-200px) scale(5);transition-delay:0s;transition-duration:1.05s;z-index:10}
#envOverlay .env-card-label{font-size:9px;letter-spacing:.35em;color:var(--preset-primary);font-weight:700}
#envOverlay .env-card-date{display:flex;align-items:stretch;justify-content:center;gap:8px;color:#5a4632}
#envOverlay .env-card-day,#envOverlay .env-card-month{display:flex;align-items:center;font-family:Georgia,serif;font-size:11px}
#envOverlay .env-card-num{display:flex;flex-direction:column;align-items:center;border-left:1px solid rgba(180,160,120,.6);border-right:1px solid rgba(180,160,120,.6);padding:0 8px}
#envOverlay .env-card-num b{font-size:22px;line-height:1;color:#3a2a1a}
#envOverlay .env-card-num i{font-style:normal;font-size:8px;letter-spacing:.2em;color:var(--preset-primary);margin-top:2px}
#envOverlay .env-card-names{font-family:Georgia,serif;font-size:24px;font-weight:700;color:#6d421e;line-height:1.3}
#envOverlay .env-card-div{width:44px;height:1px;background:var(--preset-secondary)}
#envOverlay .env-card-mark{font-size:16px;color:var(--preset-secondary)}
#envOverlay .env-front{position:absolute;left:2px;right:2px;bottom:2px;z-index:3;height:165px;background:linear-gradient(180deg,#fbf6e9,#efe5cb);clip-path:polygon(0 0,50% 52%,100% 0,100% 100%,0 100%);border-radius:0 0 8px 8px}
#envOverlay .env-fold-l{position:absolute;left:2px;top:2px;bottom:2px;width:50%;z-index:3;background:linear-gradient(180deg,#f5eeda,#e9dfc6);clip-path:polygon(0 0,0 100%,100% 52%)}
#envOverlay .env-fold-r{position:absolute;right:2px;top:2px;bottom:2px;width:50%;z-index:3;background:linear-gradient(180deg,#f5eeda,#e9dfc6);clip-path:polygon(100% 0,100% 100%,0 52%)}
#envOverlay .env-flap{position:absolute;top:2px;left:2px;right:2px;z-index:4;height:126px;background:linear-gradient(180deg,#f6eeda,#e8dcc0);clip-path:polygon(0 0,100% 0,50% 100%);transform-origin:top center;transition:transform .7s ease .15s}
#envOverlay.open .env-flap,#envOverlay.zoom .env-flap{transform:rotateX(180deg);z-index:1}
#envOverlay.zoomcard .env-head,#envOverlay.zoomcard .env-open-btn,#envOverlay.zoomcard .env-hint{opacity:0}
#envOverlay.zoomcard .env-back,#envOverlay.zoomcard .env-front,#envOverlay.zoomcard .env-fold-l,#envOverlay.zoomcard .env-fold-r,#envOverlay.zoomcard .env-flap,#envOverlay.zoomcard .env-face-front{opacity:0;transition:opacity .8s ease}
@keyframes envFloat{0%,100%{top:10px}50%{top:-14px}}
#envOverlay.floating .env-card{animation:envFloat 1.2s ease-in-out infinite}
#envOverlay.open .env-open-btn,#envOverlay.open .env-hint,#envOverlay.flip .env-open-btn,#envOverlay.flip .env-hint{opacity:0}
#envOverlay .env-open-btn{margin-top:26px;background:#8e1b12;color:#fff;border:2px solid var(--preset-secondary);border-radius:999px;padding:13px 34px;font-size:14px;font-weight:700;letter-spacing:.15em;cursor:pointer;box-shadow:0 10px 30px rgba(0,0,0,.4);transition:opacity .4s ease,transform .15s ease}
#envOverlay .env-open-btn:active{transform:scale(.96)}
#envOverlay .env-hint{margin-top:14px;font-size:12px;letter-spacing:.2em;color:#f4d58a;opacity:.75;transition:opacity .4s ease}
#envOverlay .env-burst{position:absolute;left:0;top:0;width:0;height:0;pointer-events:none;z-index:5}
#envOverlay .env-bit{position:absolute;left:0;top:0;margin:-8px 0 0 -8px;line-height:1;pointer-events:none}
</style>
<script>
function envSurprise(){
  var ov = document.getElementById('envOverlay');
  if (!ov) return;
  var wrap = ov.querySelector('.env-wrap');
  var r = wrap ? wrap.getBoundingClientRect() : {left:window.innerWidth/2-120, top:window.innerHeight/2-140, width:240};
  var layer = document.createElement('div');
  layer.className = 'env-burst';
  layer.style.left = (r.left + r.width/2) + 'px';
  layer.style.top = (r.top + 12) + 'px';
  var shapes = ['\u2726','\u2727','\u25CF','\u2766','\u2665','\u2605'];
  var colors = ['#d9a31f','#ffedb0','#fffdf4','#8e1b12','#e8a0a0','#f4d58a'];
  for (var i = 0; i < 28; i++) {
    var s = document.createElement('span');
    s.className = 'env-bit';
    s.textContent = shapes[Math.floor(Math.random()*shapes.length)];
    s.style.color = colors[Math.floor(Math.random()*colors.length)];
    s.style.fontSize = (11 + Math.floor(Math.random()*10)) + 'px';
    layer.appendChild(s);
    var dx = Math.floor(Math.random()*340) - 170;
    var dy = -Math.floor(60 + Math.random()*190);
    var rot = Math.floor(Math.random()*520) - 260;
    if (s.animate) {
      s.animate([
        {transform:'translate(0,0) rotate(0deg) scale(.6)', opacity:0},
        {transform:'translate('+(dx*0.18)+'px,'+(dy*0.18)+'px) rotate('+(rot*0.2)+'deg) scale(1.15)', opacity:1, offset:0.12},
        {transform:'translate('+(dx*0.92)+'px,'+(dy*0.92)+'px) rotate('+(rot*0.8)+'deg) scale(1)', opacity:1, offset:0.6},
        {transform:'translate('+dx+'px,'+(dy+46)+'px) rotate('+rot+'deg) scale(.7)', opacity:0}
      ], {duration:1700, easing:'cubic-bezier(.17,.67,.35,1)', delay:i*28, fill:'forwards'});
    }
  }
  ov.appendChild(layer);
  setTimeout(function(){ if (layer.parentNode) layer.parentNode.removeChild(layer); }, 2600);
}
function withEnvelope(fn){
  return function(){
    var self = this, args = arguments;
    function run(){ fn.apply(self, args); }
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) { run(); return; }
    var ov = document.getElementById('envOverlay');
    if (!ov || ov.dataset.done) { run(); return; }
    ov.dataset.done = '1';
    ov.classList.add('flip');
    setTimeout(function(){ ov.classList.add('open'); envSurprise(); }, 900);
    setTimeout(function(){ ov.classList.add('gone'); }, 2900);
    setTimeout(function(){ ov.remove(); run(); }, 3400);
  };
}
(function(){
  var dd = document.querySelector('#envOverlay .env-card-date');
  if (dd) {
    var raw = dd.getAttribute('data-env-date') || '';
    var mt = raw.match(/(\d{4})-(\d{2})-(\d{2})/);
    if (mt) {
      var dt = new Date(+mt[1], +mt[2]-1, +mt[3]);
      var days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
      var months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
      var set = function(sel, txt){ var el = dd.querySelector(sel); if (el) el.textContent = txt; };
      set('.env-card-day', days[dt.getDay()]);
      set('.env-card-num b', String(dt.getDate()));
      set('.env-card-num i', String(dt.getFullYear()));
      set('.env-card-month', months[dt.getMonth()+1]);
    }
  }
  var ov = document.getElementById('envOverlay');
  var btn = document.getElementById('envOpenBtn');
  if (!ov || !btn) return;
  btn.addEventListener('click', function(){
    if (ov.dataset.done) return;
    ov.dataset.done = '1';
    function finish(){
      var g = document.getElementById('cover-gate') || document.getElementById('cover');
      if (g) g.style.display = 'none';
      document.body.classList.remove('is-locked', 'locked');
      document.body.style.overflow = '';
      ov.remove();
      var b = document.getElementById('btn-open') || document.getElementById('openBtn');
      if (b) { b.click(); return; }
      if (typeof window.openInv === 'function') { window.openInv(); }
    }
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) { finish(); return; }
    ov.classList.add('flip');
    setTimeout(function(){ ov.classList.add('open'); envSurprise(); }, 850);
    setTimeout(function(){ ov.classList.add('floating'); }, 1950);
    setTimeout(function(){ ov.classList.remove('floating'); ov.classList.add('zoomcard'); }, 3150);
    setTimeout(function(){ ov.classList.add('gone'); }, 4300);
    setTimeout(finish, 4700);
  });
})();
</script>



<div class="pbar"><i id="pbarFill"></i></div>
<canvas id="petal"></canvas>
<div class="wrap">

<div id="cover">
  <div class="cover-card">
    <img class="gunungan" src="{{ asset('images/jawa-puspa/gunungan-divider.webp') }}" alt="Gunungan" />
    <div class="kicker">Sugeng Rawuh</div>
    <div class="cover-names">{{ $brideNick }} &amp; {{ $groomNick }}</div>
    <div class="guest">Dhumateng Panjenengan<br>Bapak/Ibu/Sedherek<b>{{ $guestName }}</b></div>
    <button class="btn" onclick="openInv()">Buka Undangan</button>
  </div>
</div>

<section id="hero">
  <div class="rv"><div class="hero-script">Pawiwahan</div></div>
  <div class="rv"><h1 class="hero-names">{{ $brideNick }} <em>&amp;</em> {{ $groomNick }}</h1></div>
  <div class="rv"><div class="ukel">❋</div></div>
  <div class="rv"><div class="hero-date">{{ mcDate($wDate) }}</div>
@if(mcGet('quotes'))
<div id=\"quotes\" class="rv" style="max-width:420px;margin:20px auto 0;padding:0 8px">
<p style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:19px;line-height:1.7;opacity:.85">“{{ mcGet('quotes') }}”</p>
</div>
@endif</div>
</section>

<section>
  <div class="rvd"><div class="sec-kicker">Dinten Bahagia</div><div class="sec-title serif">Hitung Mundur</div><div class="ukel">❋</div></div>
  <div class="cd-grid rv">
    <div class="cd"><b id="cdD">0</b><span>Dinten</span></div>
    <div class="cd"><b id="cdH">0</b><span>Jam</span></div>
    <div class="cd"><b id="cdM">0</b><span>Menit</span></div>
    <div class="cd"><b id="cdS">0</b><span>Detik</span></div>
  </div>
</section>

<section id="mempelai">
  <div class="rvd"><div class="sec-kicker">Sugeng Rawuh</div><div class="sec-title serif">Mempelai</div><div class="ukel">❋</div></div>
  <div class="couple-card rv">
    @if($bridePhoto)<img class="photo" src="{{ $bridePhoto }}" alt="{{ $brideFull }}">@else<div class="photo-fallback">{{ $brideInitial }}</div>@endif
    <h3>{{ $brideNick }}</h3><div class="full">{{ $brideFull }}</div>
    <p>Putri saking<br>{{ mcGet('bride_parents', mcGet('bride_child_of', '')) }}</p>
    @if(mcGet('bride_instagram'))<a href="https://instagram.com/{{ ltrim(mcGet('bride_instagram'),'@') }}" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:6px;margin-top:10px;padding:7px 16px;border:1px solid currentColor;border-radius:999px;font-size:11px;letter-spacing:.12em;text-decoration:none;opacity:.85"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="flex:none"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="0.8" fill="currentColor" stroke="none"/></svg>&#64;{{ ltrim(mcGet('bride_instagram'),'@') }}</a>@endif
  </div>
  <div class="amp rv">&amp;</div>
  <div class="couple-card rv">
    @if($groomPhoto)<img class="photo" src="{{ $groomPhoto }}" alt="{{ $groomFull }}">@else<div class="photo-fallback">{{ $groomInitial }}</div>@endif
    <h3>{{ $groomNick }}</h3><div class="full">{{ $groomFull }}</div>
    <p>Putra saking<br>{{ mcGet('groom_parents', mcGet('groom_child_of', '')) }}</p>
    @if(mcGet('groom_instagram'))<a href="https://instagram.com/{{ ltrim(mcGet('groom_instagram'),'@') }}" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:6px;margin-top:10px;padding:7px 16px;border:1px solid currentColor;border-radius:999px;font-size:11px;letter-spacing:.12em;text-decoration:none;opacity:.85"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="flex:none"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="0.8" fill="currentColor" stroke="none"/></svg>&#64;{{ ltrim(mcGet('groom_instagram'),'@') }}</a>@endif
  </div>
</section>

<section id="acara">
  <div class="rvd"><div class="sec-kicker">Wanci &amp; Papan</div><div class="sec-title serif">Acara</div><div class="ukel">❋</div></div>
  <div class="event rv">
    <h3>Akad Nikah</h3>
    <div class="date">{{ mcDate($wDate) }}</div><div class="time">{{ $wTime }} — {{ $wTimeE }} {{ mcGet('wedding_timezone','WIB') }}</div>
    <div class="loc">{{ mcGet('wedding_location', 'Kediaman Mempelai') }}</div>
    @if(!$sameLoc && mcGet('wedding_map_link'))<a class="btn-line" target="_blank" href="{{ mcGet('wedding_map_link') }}">Lihat Peta</a>@endif
  </div>
  <div class="event rv">
    <h3>Resepsi</h3>
    <div class="date">{{ mcDate($rDate) }}</div><div class="time">{{ $rTimeS }} — {{ $rTimeE }} {{ mcGet('wedding_timezone','WIB') }}</div>
    <div class="loc">{{ mcGet('reception_location', mcGet('wedding_location', 'Kediaman Mempelai')) }}</div>
    @if(!$sameLoc && mcGet('reception_map_link'))<a class="btn-line" target="_blank" href="{{ mcGet('reception_map_link') }}">Lihat Peta</a>@endif
@if($sameLoc && $mainMapLink)<div style="text-align:center;margin-top:12px"><a class="btn-line" target="_blank" rel="noopener" href="{{ $mainMapLink }}">Lihat Peta Lokasi</a></div>@endif
  </div>
</section>

@if(!empty($gallery) || mcGet('prewedding_video_file') || mcGet('prewedding_video_link'))
<section id="galeri">
  <div class="rvd"><div class="sec-kicker">Momen</div><div class="sec-title serif">Galeri</div><div class="ukel">❋</div></div>
    @if(mcGet('prewedding_video_file') || mcGet('prewedding_video_link'))
@php
  $prewedFile = mcGet('prewedding_video_file');
  $prewedLink = mcGet('prewedding_video_link');
  $prewedYtId = null;
  if (!$prewedFile && $prewedLink && preg_match('~(?:youtube\.com/(?:watch\?v=|live/|shorts/|embed/)|youtu\.be/)([\w-]{6,})~', (string)$prewedLink, $pm)) { $prewedYtId = $pm[1]; }
  $prewedThumb = $prewedYtId ? 'https://i.ytimg.com/vi/' . $prewedYtId . '/hqdefault.jpg' : '';
@endphp
<div id="prewedBox" class="rv" style="max-width:430px;margin:16px auto 0;border-radius:16px;overflow:hidden;border:1px solid var(--preset-secondary);box-shadow:0 18px 40px rgba(0,0,0,.25)">
<div style="position:relative;padding-top:56.25%">
@if($prewedFile)
<video controls preload="metadata" playsinline src="{{ asset($prewedFile) }}" style="position:absolute;inset:0;width:100%;height:100%;background:#000" title="Video Prewedding"></video>
@elseif($prewedYtId)
<button type="button" onclick="playPrewed(this, '{{ $prewedYtId }}')" aria-label="Putar Video Prewedding" style="position:absolute;inset:0;width:100%;height:100%;border:0;padding:0;cursor:pointer;background:#000">
<img src="{{ $prewedThumb }}" alt="Video Prewedding" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.85">
<span style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:68px;height:48px;border-radius:12px;background:rgba(204,0,0,.93);display:flex;align-items:center;justify-content:center;box-shadow:0 10px 24px rgba(0,0,0,.45)"><span style="width:0;height:0;border-left:18px solid #fff;border-top:11px solid transparent;border-bottom:11px solid transparent;margin-left:4px"></span></span>
</button>
@elseif($prewedLink)
<a href="{{ $prewedLink }}" target="_blank" rel="noopener" style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;background:#000;color:var(--preset-background);text-decoration:none">
<span style="width:68px;height:48px;border-radius:12px;background:rgba(204,0,0,.93);display:flex;align-items:center;justify-content:center;box-shadow:0 10px 24px rgba(0,0,0,.45)"><span style="width:0;height:0;border-left:18px solid #fff;border-top:11px solid transparent;border-bottom:11px solid transparent;margin-left:4px"></span></span>
<span style="font-size:11px;font-weight:700;letter-spacing:.22em">TONTON VIDEO PREWEDDING</span>
</a>
@endif
</div>
</div>
<div class="rv" style="margin-top:16px">@if($prewedLink)<a href="{{ $prewedLink }}" target="_blank" rel="noopener" style="display:inline-block;padding:11px 28px;border-radius:999px;background:linear-gradient(105deg,var(--preset-secondary),var(--preset-secondary-container) 55%,var(--preset-secondary));color:var(--preset-primary);font-size:11px;font-weight:700;letter-spacing:.22em;text-decoration:none">BUKA VIDEO</a>@endif</div>
  @endif
@if(!empty($gallery))
  <div class="g-grid rv">
    @foreach(array_slice($gallery, 0, 8) as $img)<img src="{{ $img }}" alt="Galeri" loading="lazy">@endforeach
  </div>
  @endif
</section>
@endif

@if(!empty($stories) && count($stories))
<section id="kisah">
  <div class="rvd"><div class="sec-kicker">Lampah Katresnan</div><div class="sec-title serif">Kisah Kami</div><div class="ukel">❋</div></div>
  <div class="tl">
    @foreach($stories as $st)
    <div class="tl-item rv">@if(!empty($st->tahun))<div style="font-size:11px;letter-spacing:.25em;opacity:.55;margin-bottom:6px">{{ $st->tahun }}</div>@endif
<h4>{{ $st->judul ?? 'Cerita' }}</h4><p>{{ $st->isi ?? '' }}</p></div>
    @endforeach
  </div>
</section>
@endif

<section id="rsvp">
  <div class="rvd"><div class="sec-kicker">Konfirmasi</div><div class="sec-title serif">RSVP</div><div class="ukel">❋</div></div>
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

@if(mcGet('guest_qr_enabled') && !empty($guestQrCode))
<button id="qrFloatBtn" type="button" onclick="openQrModal()" aria-label="QR Check-in" title="QR Check-in"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3.5" y="3.5" width="6.5" height="6.5" rx="1.5"/><rect x="14" y="3.5" width="6.5" height="6.5" rx="1.5"/><rect x="3.5" y="14" width="6.5" height="6.5" rx="1.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 14h3.5v3.5H14zM20.5 14v.5M14 20.5h.5M17.5 17.5h3v3h-3z"/></svg></button>
<div id="qrModal" style="position:fixed;inset:0;z-index:96;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,.65);padding:20px" onclick="if(event.target===this)closeQrModal()">
  <div style="background:var(--preset-background);border:2px solid var(--preset-secondary);border-radius:18px;padding:26px 22px;max-width:340px;width:100%;text-align:center;position:relative;box-shadow:0 24px 60px rgba(0,0,0,.4)">
    <button type="button" onclick="closeQrModal()" aria-label="Tutup" style="position:absolute;top:8px;right:12px;border:0;background:none;font-size:24px;line-height:1;cursor:pointer;color:var(--preset-primary)">&times;</button>
    <h2 style="font-family:serif;font-size:24px;color:var(--preset-primary);margin:0 0 6px">QR Check-In</h2>
    <p style="opacity:.75;font-size:13px;line-height:1.6;margin:0 0 18px;color:var(--preset-primary)">Simpan dan tunjukkan QR ini kepada panitia saat check-in di lokasi acara.</p>
    <div style="background:#fff;padding:16px;border-radius:18px;display:inline-block;box-shadow:0 14px 34px rgba(0,0,0,.25)"><div id="qrModalBox"></div></div>
    <p style="margin:16px 0 0;font-weight:600;color:var(--preset-primary)">{{ $guestName }}</p>
    <p style="margin:6px 0 0;font-size:13px;color:var(--preset-primary)">Kode manual: <b style="letter-spacing:.12em">{{ $guestQrCode }}</b></p>
    <button id="qrModalDownloadBtn" type="button" style="display:block;width:100%;margin-top:14px;padding:12px;border:none;border-radius:999px;background:var(--preset-secondary);color:#fff;font-weight:700;font-size:14px;cursor:pointer">Unduh QR</button>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
function openQrModal(){var m=document.getElementById('qrModal');if(m)m.style.display='flex';}
function closeQrModal(){var m=document.getElementById('qrModal');if(m)m.style.display='none';}
if(window.QRCode){var qb=document.getElementById('qrModalBox');new QRCode(qb,{text:"{{ $guestQrCode }}",width:200,height:200,correctLevel:QRCode.CorrectLevel.M});document.getElementById('qrModalDownloadBtn').onclick=function(){var c=qb.querySelector('canvas');var im=qb.querySelector('img');var u=c?c.toDataURL('image/png'):(im?im.src:'');if(!u)return;var a=document.createElement('a');a.href=u;a.download='QR-Undangan.png';a.click();};}
</script>
@endif
<section id="ucapan">
  <div class="rvd"><div class="sec-kicker">Donga Pangestu</div><div class="sec-title serif">Ucapan</div><div class="ukel">❋</div></div>
  <div id="wishList" class="rv"></div>
  <form id="wishForm" class="rv" onsubmit="return sendWish(event)" style="margin-top:22px">
    <div class="field"><label>Nama</label><input required name="name" placeholder="Nama Anda"></div>
    <div class="field"><label>Ucapan</label><textarea required name="message" rows="3" placeholder="Tulis doa terbaik..."></textarea></div>
    <button class="btn-line" type="submit">Kirim Ucapan</button>
  </form>
</section>

@if(!empty($accounts) || mcGet('gift_address'))
<section id="gift">
  <div class="rvd"><div class="sec-kicker">Tanda Asih</div><div class="sec-title serif">Hadiah</div><div class="ukel">❋</div></div>
@if($qrisEnabled)
<div style="max-width:340px;margin:0 auto 18px;background:var(--preset-background);border-radius:16px;padding:20px;text-align:center;box-shadow:0 10px 30px rgba(0,0,0,.18);color:var(--preset-primary);grid-column:1/-1">
<div style="font-weight:800;letter-spacing:.18em;font-size:13px;color:#c0392b">QRIS</div>
<p style="font-size:12px;opacity:.65;margin:4px 0 12px">Scan untuk kirim hadiah &mdash; semua e-wallet &amp; m-banking</p>
<img src="{{ $qrisImage }}" alt="QRIS" style="width:210px;height:210px;object-fit:contain;margin:0 auto;display:block">
@if($qrisName)<p style="font-size:13px;font-weight:600;margin-top:10px">{{ $qrisName }}</p>@endif
<p style="font-size:11px;opacity:.55;margin-top:4px">atau pilih transfer bank di bawah ini</p>
</div>
@endif
  @foreach($accounts as $a)
  <div class="bank rv"><div class="bk">{{ $a['bank'] }}</div>
    @if(!empty($a['logo']))<img src="{{ $a['logo'] }}" alt="Logo bank" style="height:36px;max-width:150px;object-fit:contain;margin:10px auto 0;display:block">@endif<div class="no">{{ $a['no'] }}</div><div class="an">a.n. {{ $a['an'] }}</div>
  <button class="btn-line copy" onclick="copyNo('{{ $a['no'] }}')">Salin Nomor</button></div>
  @endforeach
  @if(mcGet('gift_address'))<div class="bank rv"><div class="bk">Hadiah Fisik</div><p style="font-size:14px;color:var(--gading-dim);margin-top:10px;line-height:1.7">{{ mcGet('gift_address') }}</p>@if($giftRecipient)<p style="font-size:13px;opacity:.75;margin-top:8px">Penerima: {{ $giftRecipient }}</p>@endif
    @if(mcGet('gift_maps_link'))<div style="margin-top:12px"><a href="{{ mcGet('gift_maps_link') }}" target="_blank" style="display:inline-block;padding:10px 26px;border:1px solid currentColor;color:inherit;border-radius:999px;font-size:11px;letter-spacing:.24em;text-transform:uppercase;text-decoration:none;opacity:.85">Lihat Peta</a></div>@endif</div>@endif
</section>
@endif


<script>
function playPrewed(btn, id){
  var wrap = btn.parentNode;
  wrap.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0&enablejsapi=1" style="position:absolute;inset:0;width:100%;height:100%;border:0" allow="accelerometer; autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen referrerpolicy="strict-origin-when-cross-origin" title="Video Prewedding"></iframe>';
if(window.prewedPauseMusic) window.prewedPauseMusic();
}
</script>
@if(mcGet('live_stream_link'))
@php
  $liveUrl = mcGet('live_stream_link');
  $liveEmbed = $liveUrl;
  if (preg_match('~(?:youtube\.com/(?:watch\?v=|live/|shorts/|embed/)|youtu\.be/)([\w-]{6,})~', (string)$liveUrl, $lm)) { $liveEmbed = 'https://www.youtube-nocookie.com/embed/' . $lm[1]; }
  $liveId = $lm[1] ?? null;
  $liveThumb = $liveId ? 'https://i.ytimg.com/vi/' . $liveId . '/hqdefault.jpg' : '';
@endphp
<section id="live">
<div class="rvd"><div class="sec-kicker">Live Streaming</div><div class="sec-title">Saksikan Langsung</div></div>
<div class="rv" style="max-width:430px;margin:16px auto 0;border-radius:16px;overflow:hidden;border:1px solid var(--preset-secondary);box-shadow:0 18px 40px rgba(0,0,0,.25)">
<div style="position:relative;padding-top:56.25%">
@if($liveId)
<button type="button" onclick="playLive(this, '{{ $liveId }}')" aria-label="Putar Live Streaming" style="position:absolute;inset:0;width:100%;height:100%;border:0;padding:0;cursor:pointer;background:#000">
<img src="{{ $liveThumb }}" alt="Live Streaming" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.85">
<span style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:68px;height:48px;border-radius:12px;background:rgba(204,0,0,.93);display:flex;align-items:center;justify-content:center;box-shadow:0 10px 24px rgba(0,0,0,.45)"><span style="width:0;height:0;border-left:18px solid #fff;border-top:11px solid transparent;border-bottom:11px solid transparent;margin-left:4px"></span></span>
</button>
@else
<iframe src="{{ $liveEmbed }}" style="position:absolute;inset:0;width:100%;height:100%;border:0" allow="accelerometer; autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin" title="Live Streaming"></iframe>
@endif
</div>
</div>
<div class="rv" style="margin-top:16px"><a href="{{ $liveUrl }}" target="_blank" rel="noopener" style="display:inline-block;padding:11px 28px;border-radius:999px;background:linear-gradient(105deg,var(--preset-secondary),var(--preset-secondary-container) 55%,var(--preset-secondary));color:var(--preset-primary);font-size:11px;font-weight:700;letter-spacing:.22em;text-decoration:none">BUKA LIVE STREAMING</a></div>
</section>
@endif
<script>
function playLive(btn, id){
  var wrap = btn.parentNode;
  wrap.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0" style="position:absolute;inset:0;width:100%;height:100%;border:0" allow="accelerometer; autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen referrerpolicy="strict-origin-when-cross-origin" title="Live Streaming"></iframe>';
}
</script>
<footer>
  <div class="rv"><div class="aksara">Matur Nuwun</div>
  <p style="color:var(--gading-dim);font-size:14px;line-height:1.8;max-width:380px;margin:14px auto">Matur nuwun sanget awit rawuh saha donga pangestu panjenengan. Mugi dados berkah tumrap kita sedaya.</p>
  <div class="ukel">❋</div>
  <div class="serif" style="font-size:22px;color:var(--prada-lt)">{{ $brideNick }} &amp; {{ $groomNick }}</div></div>
  <img src="{{ asset('images/jawa-puspa/ornamen-bawah.webp') }}" alt="" style="width:100%;display:block;margin-top:24px" />
</footer>
</div>

<button id="musBtn" onclick="toggleMus()" style="display:none" aria-label="Putar musik">&#9834;</button>


<div class="lb" id="lb"><span class="lb-x" onclick="closeLb()">&times;</span><span class="lb-prev" onclick="stepLb(-1)">&#10094;</span><img id="lbImg" src="" alt="Foto"><span class="lb-next" onclick="stepLb(1)">&#10095;</span><span class="lb-count" id="lbCount"></span></div>
<nav class="fmenu" id="fmenu" aria-label="Menu undangan"></nav>

<script>
const INV_ID = {{ (int)$invId }};
function openInv(){document.getElementById('cover').classList.add('open');document.body.style.overflow='';const m=document.getElementById('mus');if(m)m.play().catch(()=>{});}
document.body.style.overflow='hidden';
/* countdown diganti modul bawaan (resepsi+timezone) */
const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('on');io.unobserve(e.target)}}),{threshold:.12});
document.querySelectorAll('.rv,.rvd').forEach(el=>io.observe(el));
// kelopak melati
const cv=document.getElementById('petal'),cx=cv.getContext('2d');let P=[];
function rs(){cv.width=innerWidth;cv.height=innerHeight}rs();addEventListener('resize',rs);
for(let i=0;i<36;i++)P.push({x:Math.random()*innerWidth,y:Math.random()*-innerHeight,r:Math.random()*4+2,s:Math.random()*.8+.3,ph:Math.random()*6.28,sw:Math.random()*1.4+.4});
(function loop(t){cx.clearRect(0,0,cv.width,cv.height);P.forEach(p=>{p.y+=p.s;p.x+=Math.sin(t/1400+p.ph)*p.sw*.4;
  if(p.y>cv.height+8){p.y=-8;p.x=Math.random()*cv.width}
  cx.save();cx.translate(p.x,p.y);cx.rotate(Math.sin(t/900+p.ph)*.6);cx.globalAlpha=.5;cx.fillStyle='#f5ead6';
  cx.beginPath();cx.ellipse(0,0,p.r,p.r*.55,0,0,6.29);cx.fill();cx.restore()});requestAnimationFrame(loop)})(0);
async function sendRSVP(e){e.preventDefault();const f=e.target;
  const r=await fetch('/api/rsvp?inv_id='+INV_ID,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name:f.name.value,status:f.status.value,guest_count:f.guest_count.value})});
  alert(r.ok?'Matur nuwun konfirmasinipun!':'Gagal, coba lagi.');f.reset();return false;}
function wishTime(ts){if(!ts)return '';var s=String(ts).replace(' ','T');if(s.indexOf('Z')<0&&s.indexOf('+')<0)s+='+07:00';var d=new Date(s),diff=Math.floor((Date.now()-d.getTime())/1000);if(isNaN(diff)||diff<0||diff<60)return 'Baru saja';if(diff<3600)return Math.floor(diff/60)+' menit yang lalu';if(diff<86400)return Math.floor(diff/3600)+' jam yang lalu';return Math.floor(diff/86400)+' hari yang lalu';}
async function loadWishes(){try{const r=await fetch('/api/ucapan?inv_id='+INV_ID);const d=await r.json();
  wishList.innerHTML=(d.data||d||[]).map(w=>`<div class="wish"><b>${esc(w.nama||w.name)}</b><span class="st">${esc(w.status||'')}</span><span class="wtime">${esc(wishTime(w.created_at||w.tanggal||''))}</span><p>${esc(w.pesan||w.message||w.ucapan||'')}</p></div>`).join('')||'<p style="color:var(--gading-dim)">Dereng wonten ucapan.</p>';}catch(e){}}
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
async function sendWish(e){e.preventDefault();const f=e.target;
  const r=await fetch('/api/ucapan?inv_id='+INV_ID,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name:f.name.value,message:f.message.value})});
  if(r.ok){f.reset();loadWishes();}else alert('Gagal mengirim.');return false;}
loadWishes();
function copyNo(t){navigator.clipboard.writeText(t).then(()=>alert('Nomor tersalin!'));}
function toggleMus(){const m=document.getElementById('mus');if(m.paused){m.play();musBtn.style.opacity=1}else{m.pause();musBtn.style.opacity=.5}}
</script>

<script>
/* === Modul fitur bawaan default: musik API, lightbox, menu, countdown resepsi, RSVP & ucapan === */
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
  if(!musPlaying){ if(window.prewedPauseVideo) window.prewedPauseVideo(); prewedMusicWasOn=false; startMus(); }
  else { bgMus.pause(); musPlaying = false; window.musicPlaying = false; if(b) b.classList.remove('playing'); prewedMusicWasOn=false; }
}

var prewedMusicWasOn=false;
function prewedPauseMusic(){ if(musPlaying&&bgMus){ bgMus.pause(); musPlaying=false; window.musicPlaying=false; var b=document.getElementById('musBtn'); if(b)b.classList.remove('playing'); prewedMusicWasOn=true; } }
function prewedResumeMusic(){ if(prewedMusicWasOn){ prewedMusicWasOn=false; startMus(); } }

/* ---------- Video prewedding: autoplay saat terlihat + musik saling jeda ---------- */
var prewedBox=document.getElementById('prewedBox');
function prewedYtCmd(fn){ if(!prewedBox)return; var f=prewedBox.querySelector('iframe'); if(f&&f.contentWindow){ try{ f.contentWindow.postMessage('{"event":"command","func":"'+fn+'","args":""}','*'); }catch(e){} } }
function prewedPlayVideo(){
  if(!prewedBox)return;
  var v=prewedBox.querySelector('video');
  if(v){ var p=v.play(); if(p&&p.catch)p.catch(function(){}); return; }
  var b=prewedBox.querySelector('button[onclick*="playPrewed"]');
  if(b){ b.click(); return; }
  prewedYtCmd('playVideo'); prewedPauseMusic();
}
function prewedPauseVideo(){
  if(!prewedBox)return;
  var v=prewedBox.querySelector('video'); if(v&&!v.paused){ v.pause(); return; }
  prewedYtCmd('pauseVideo');
}
(function(){
  if(!prewedBox) return;
  var v=prewedBox.querySelector('video');
  if(v&&!v._prewedBound){ v._prewedBound=true;
    v.addEventListener('play',function(){ prewedPauseMusic(); });
    v.addEventListener('pause',function(){ prewedResumeMusic(); });
    v.addEventListener('ended',function(){ prewedResumeMusic(); });
  }
  if(!('IntersectionObserver' in window)) return;
  var io=new IntersectionObserver(function(es){
    es.forEach(function(en){
      if(en.isIntersecting){ prewedPlayVideo(); }
      else{ prewedPauseVideo(); prewedResumeMusic(); }
    });
  },{threshold:0.35});
  io.observe(prewedBox);
})();
window.prewedPauseVideo=prewedPauseVideo;
window.prewedPauseMusic=prewedPauseMusic;
(function(){
  document.body.classList.add('locked');
  var prevOpen = window.openInv;
  window.openInv = withEnvelope(function(){
    document.body.classList.remove('locked');
    document.getElementById('cover').classList.add('open');
    document.body.style.overflow = '';
    if(musCfg.autoplay && bgMus) startMus();
  });
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
  document.getElementById('rsvpAlasan').required = (v === 'tidak');
}
async function sendRSVP(e){
  e.preventDefault();
  var nama = document.getElementById('rsvpNama').value.trim();
  var jumlah = parseInt(document.getElementById('rsvpCount').value, 10) || 1;
  var alasan = (__att === 'tidak') ? document.getElementById('rsvpAlasan').value.trim() : '';
  if(!nama){ alert('Mohon isi nama terlebih dahulu.'); return false; }
  if(!__att){ alert('Mohon pilih status kehadiran.'); return false; }
  if(__att === 'tidak' && !alasan){ alert('Mohon isi alasan berhalangan.'); return false; }
  var btn = document.getElementById('rsvpBtn'); btn.disabled = true;
  try{
    var r = await fetch('/api/rsvp', { method:'POST', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ invitation_id: INV_ID, nama: nama, jumlah_tamu: jumlah, status: __att, alasan: alasan }) });
    var j = await r.json();
    if(!j.success) throw new Error(j.message || 'Gagal menyimpan');
    var msg = 'Halo, saya *' + nama + '* ingin mengkonfirmasi kehadiran.\n\n'
      + 'Status : ' + (__att === 'hadir' ? 'HADIR \u2705' : 'TIDAK HADIR \u274C')
      + (__att === 'hadir' ? '\nJumlah Tamu : ' + jumlah + ' orang' : '')
      + (alasan ? '\nAlasan : ' + alasan : '')
      + '\n\nTerima kasih atas undangannya \uD83D\uDE4F';
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
  var SECS = [
    ['mempelai','<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7.5-4.7-9.5-9C1 8.5 3 5 6.5 5c2 0 3.5 1 4.5 2.5C12 6 13.5 5 15.5 5 19 5 21 8.5 20.5 12c-2 4.3-8.5 9-8.5 9z"/></svg>','Mempelai'],
    ['quotes','<svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 5.5C4.9 6.7 4 8.6 4 10.9V18h6.4v-6.4H7.2c0-1.9.8-3.2 2.3-4.2l-3.1-1.8zm9 0c-1.7 1.2-2.6 3.1-2.6 5.4V18h6.4v-6.4h-3.2c0-1.9.8-3.2 2.3-4.2l-3.1-1.8z"/></svg>','Quotes'],
    ['acara','<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path stroke-linecap="round" d="M8 3v4M16 3v4M3 10h18"/></svg>','Acara'],
    ['galeri','<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.6"/><path stroke-linecap="round" stroke-linejoin="round" d="m5 19 5.5-5.5 3 3L19 11l2 2"/></svg>','Galeri'],
    ['kisah','<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.5C10 4.8 7 4.3 4.5 4.5v13c2.5-.2 5.5.3 7.5 2 2-1.7 5-2.2 7.5-2v-13C17 4.3 14 4.8 12 6.5z"/><path stroke-linecap="round" d="M12 6.5v13"/></svg>','Kisah'],
    ['rsvp','<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>','RSVP'],
    ['ucapan','<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>','Ucapan'],
    ['gift','<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8v13H3V8M1 3h22v5H1zM12 8v13M12 8H7.5a2.5 2.5 0 0 1 0-5C11 3 12 8 12 8zm0 0h4.5a2.5 2.5 0 0 0 0-5C13 3 12 8 12 8z"/></svg>','Gift'],
    ['live','<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path fill="currentColor" stroke="none" d="M10 8.3v7.4c0 .6.7 1 1.2.7l6-3.7c.5-.3.5-1 0-1.3l-6-3.7c-.5-.3-1.2.1-1.2.6z"/></svg>','Live']
  ];
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
