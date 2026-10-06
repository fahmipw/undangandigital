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
<title>{{ $brideNick }} &amp; {{ $groomNick }} — Undangan Jawa Klasik</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Pinyon+Script&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root{--soga:#241009;--soga2:#3a1c0e;--prada:#d4af37;--prada-lt:#f2d67c;--gading:#f5ead6;--gading-dim:rgba(245,234,214,.7)}
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
#cover{position:fixed;inset:0;z-index:100;background:radial-gradient(ellipse at 50% 25%,#4a2410 0%,#241009 70%);display:flex;align-items:center;justify-content:center;transition:opacity .9s,visibility .9s}
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
.photo-fallback{width:148px;height:148px;border-radius:50%;margin:0 auto 16px;border:2px solid var(--prada);display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:62px;color:var(--prada-lt);background:radial-gradient(circle,#3a1c0e,#241009)}
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
.fmenu{position:fixed;bottom:14px;left:50%;transform:translateX(-50%);z-index:90;display:flex;gap:2px;background:#241009f2;border:1px solid #d4af3755;border-radius:999px;padding:7px 9px;backdrop-filter:blur(8px);box-shadow:0 10px 30px rgba(0,0,0,.35);max-width:96vw;overflow-x:auto;scrollbar-width:none}
.fmenu::-webkit-scrollbar{display:none}
.fmenu a{display:flex;flex-direction:column;align-items:center;gap:3px;min-width:50px;padding:6px 7px;border-radius:12px;color:#f5ead6;opacity:.6;text-decoration:none;font-size:9px;letter-spacing:.06em;transition:.25s;font-family:'Jost',sans-serif}
.fmenu a i{font-style:normal;font-size:17px;line-height:1}
.fmenu a.active{opacity:1;background:#d4af372e;color:#f2d67c}
#musBtn.playing{outline:2px solid #f2d67c;outline-offset:2px}
.att-toggle{display:flex;gap:10px}
.att-toggle button{flex:1;padding:13px 8px;border:1px solid #d4af37;background:transparent;color:#f5ead6;font-family:'Jost',sans-serif;font-size:14px;cursor:pointer;transition:.25s;border-radius:10px}
.att-toggle button.on{background:#d4af37;color:#241009;font-weight:500}
.wish-empty{text-align:center;opacity:.6;font-size:14px;padding:12px}
body.locked #fmenu,body.locked #musBtn,body.locked .pbar{opacity:0 !important;visibility:hidden !important;pointer-events:none !important}

.rv{opacity:0;transform:translateY(36px);transition:opacity .9s,transform .9s}
.rv.on{opacity:1;transform:none}
@media(min-width:640px){.hero-names{font-size:66px}}
</style>
</head>
<body>
<div class="pbar"><i id="pbarFill"></i></div>
<canvas id="petal"></canvas>
<div class="wrap">

<div id="cover">
  <div class="cover-card">
    <svg class="gunungan" viewBox="0 0 100 130"><path d="M50 4 C78 30 92 62 92 96 L92 126 L8 126 L8 96 C8 62 22 30 50 4 Z" fill="none" stroke="#d4af37" stroke-width="2.5"/><path d="M50 22 C68 42 78 64 78 92 L78 118 L22 118 L22 92 C22 64 32 42 50 22 Z" fill="none" stroke="#d4af37" stroke-width="1.2" opacity=".7"/><circle cx="50" cy="70" r="7" fill="none" stroke="#d4af37" stroke-width="1.5"/><path d="M50 40 L50 100 M32 60 L68 60 M28 82 L72 82" stroke="#d4af37" stroke-width="1" opacity=".6"/></svg>
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
<div class="rv" style="max-width:420px;margin:20px auto 0;padding:0 8px">
<p style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:19px;line-height:1.7;opacity:.85">“{{ mcGet('quotes') }}”</p>
</div>
@endif</div>
</section>

<section>
  <div class="rv"><div class="sec-kicker">Dinten Bahagia</div><div class="sec-title serif">Hitung Mundur</div><div class="ukel">❋</div></div>
  <div class="cd-grid rv">
    <div class="cd"><b id="cdD">0</b><span>Dinten</span></div>
    <div class="cd"><b id="cdH">0</b><span>Jam</span></div>
    <div class="cd"><b id="cdM">0</b><span>Menit</span></div>
    <div class="cd"><b id="cdS">0</b><span>Detik</span></div>
  </div>
</section>

<section id="mempelai">
  <div class="rv"><div class="sec-kicker">Sugeng Rawuh</div><div class="sec-title serif">Mempelai</div><div class="ukel">❋</div></div>
  <div class="couple-card rv">
    @if($bridePhoto)<img class="photo" src="{{ $bridePhoto }}" alt="{{ $brideFull }}">@else<div class="photo-fallback">{{ $brideInitial }}</div>@endif
    <h3>{{ $brideNick }}</h3><div class="full">{{ $brideFull }}</div>
    <p>Putri saking<br>{{ mcGet('bride_parents', mcGet('bride_child_of', '')) }}</p>
  </div>
  <div class="amp rv">&amp;</div>
  <div class="couple-card rv">
    @if($groomPhoto)<img class="photo" src="{{ $groomPhoto }}" alt="{{ $groomFull }}">@else<div class="photo-fallback">{{ $groomInitial }}</div>@endif
    <h3>{{ $groomNick }}</h3><div class="full">{{ $groomFull }}</div>
    <p>Putra saking<br>{{ mcGet('groom_parents', mcGet('groom_child_of', '')) }}</p>
  </div>
</section>

<section id="acara">
  <div class="rv"><div class="sec-kicker">Wanci &amp; Papan</div><div class="sec-title serif">Acara</div><div class="ukel">❋</div></div>
  <div class="event rv">
    <h3>Akad Nikah</h3>
    <div class="date">{{ mcDate($wDate) }}</div><div class="time">{{ $wTime }} — {{ $wTimeE }} {{ mcGet('wedding_timezone','WIB') }}</div>
    <div class="loc">{{ mcGet('wedding_location', 'Kediaman Mempelai') }}</div>
    @if(mcGet('wedding_map_link'))<a class="btn-line" target="_blank" href="{{ mcGet('wedding_map_link') }}">Lihat Peta</a>@endif
  </div>
  <div class="event rv">
    <h3>Resepsi</h3>
    <div class="date">{{ mcDate($rDate) }}</div><div class="time">{{ $rTimeS }} — {{ $rTimeE }} {{ mcGet('wedding_timezone','WIB') }}</div>
    <div class="loc">{{ mcGet('reception_location', mcGet('wedding_location', 'Kediaman Mempelai')) }}</div>
    @if(mcGet('reception_map_link'))<a class="btn-line" target="_blank" href="{{ mcGet('reception_map_link') }}">Lihat Peta</a>@endif @if(mcGet('live_stream_link'))<a class="btn-line" target="_blank" href="{{ mcGet('live_stream_link') }}" style="margin-left:8px">Live Streaming</a>@endif
  </div>
</section>

@if(!empty($gallery))
<section id="galeri">
  <div class="rv"><div class="sec-kicker">Momen</div><div class="sec-title serif">Galeri</div><div class="ukel">❋</div></div>
  <div class="g-grid rv">
    @foreach(array_slice($gallery, 0, 8) as $img)<img src="{{ $img }}" alt="Galeri" loading="lazy">@endforeach
  </div>
</section>
@endif

@if(!empty($stories) && count($stories))
<section id="kisah">
  <div class="rv"><div class="sec-kicker">Lampah Katresnan</div><div class="sec-title serif">Kisah Kami</div><div class="ukel">❋</div></div>
  <div class="tl">
    @foreach($stories as $st)
    <div class="tl-item rv">@if(!empty($st->tahun))<div style="font-size:11px;letter-spacing:.25em;opacity:.55;margin-bottom:6px">{{ $st->tahun }}</div>@endif
<h4>{{ $st->judul ?? 'Cerita' }}</h4><p>{{ $st->isi ?? '' }}</p></div>
    @endforeach
  </div>
</section>
@endif

<section id="rsvp">
  <div class="rv"><div class="sec-kicker">Konfirmasi</div><div class="sec-title serif">RSVP</div><div class="ukel">❋</div></div>
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

<section id="ucapan">
  <div class="rv"><div class="sec-kicker">Donga Pangestu</div><div class="sec-title serif">Ucapan</div><div class="ukel">❋</div></div>
  <div id="wishList" class="rv"></div>
  <form id="wishForm" class="rv" onsubmit="return sendWish(event)" style="margin-top:22px">
    <div class="field"><label>Nama</label><input required name="name" placeholder="Nama Anda"></div>
    <div class="field"><label>Ucapan</label><textarea required name="message" rows="3" placeholder="Tulis doa terbaik..."></textarea></div>
    <button class="btn-line" type="submit">Kirim Ucapan</button>
  </form>
</section>

@if(!empty($accounts) || mcGet('gift_address'))
<section id="gift">
  <div class="rv"><div class="sec-kicker">Tanda Asih</div><div class="sec-title serif">Hadiah</div><div class="ukel">❋</div></div>
  @foreach($accounts as $a)
  <div class="bank rv"><div class="bk">{{ $a['bank'] }}</div>
    @if(!empty($a['logo']))<img src="{{ $a['logo'] }}" alt="Logo bank" style="height:36px;max-width:150px;object-fit:contain;margin:10px auto 0;display:block">@endif<div class="no">{{ $a['no'] }}</div><div class="an">a.n. {{ $a['an'] }}</div>
  <button class="btn-line copy" onclick="copyNo('{{ $a['no'] }}')">Salin Nomor</button></div>
  @endforeach
  @if(mcGet('gift_address'))<div class="bank rv"><div class="bk">Hadiah Fisik</div><p style="font-size:14px;color:var(--gading-dim);margin-top:10px;line-height:1.7">{{ mcGet('gift_address') }}</p>
    @if(mcGet('gift_maps_link'))<div style="margin-top:12px"><a href="{{ mcGet('gift_maps_link') }}" target="_blank" style="display:inline-block;padding:10px 26px;border:1px solid currentColor;border-radius:999px;font-size:11px;letter-spacing:.24em;text-transform:uppercase;text-decoration:none;opacity:.85">Lihat Peta</a></div>@endif</div>@endif
</section>
@endif

<footer>
  <div class="rv"><div class="aksara">Matur Nuwun</div>
  <p style="color:var(--gading-dim);font-size:14px;line-height:1.8;max-width:380px;margin:14px auto">Matur nuwun sanget awit rawuh saha donga pangestu panjenengan. Mugi dados berkah tumrap kita sedaya.</p>
  <div class="ukel">❋</div>
  <div class="serif" style="font-size:22px;color:var(--prada-lt)">{{ $brideNick }} &amp; {{ $groomNick }}</div></div>
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
document.querySelectorAll('.rv').forEach(el=>io.observe(el));
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
async function loadWishes(){try{const r=await fetch('/api/ucapan?inv_id='+INV_ID);const d=await r.json();
  wishList.innerHTML=(d.data||d||[]).map(w=>`<div class="wish"><b>${esc(w.name)}</b><span class="st">${esc(w.status||'')}</span><p>${esc(w.message||w.ucapan||'')}</p></div>`).join('')||'<p style="color:var(--gading-dim)">Dereng wonten ucapan.</p>';}catch(e){}}
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
  if(!musPlaying){ startMus(); }
  else { bgMus.pause(); musPlaying = false; window.musicPlaying = false; if(b) b.classList.remove('playing'); }
}
(function(){
  document.body.classList.add('locked');
  var prevOpen = window.openInv;
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
  var SECS = [['hero','\u2302','Awal'],['mempelai','\u2665','Mempelai'],['galeri','\u25A6','Galeri'],
              ['kisah','\u270E','Kisah'],['acara','\u25F7','Acara'],['rsvp','\u2713','RSVP'],
              ['ucapan','\u2709','Ucapan'],['gift','\u2726','Gift']];
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
