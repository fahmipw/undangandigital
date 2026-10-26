<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Check-in Panitia — {{ $title }}</title>
<style>
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:-apple-system,'Segoe UI',Roboto,sans-serif;background:#f6f4ee;color:#2b2118;min-height:100vh;padding-bottom:40px}
  .top{background:#1d1408;color:#f5ead0;padding:22px 18px;text-align:center}
  .top small{letter-spacing:.25em;font-size:10px;opacity:.7;text-transform:uppercase}
  .top h1{font-size:20px;margin-top:6px}
  .wrap{max-width:480px;margin:0 auto;padding:16px}
  .stats{display:flex;gap:10px;margin:16px 0}
  .stat{flex:1;background:#fff;border-radius:14px;padding:14px 8px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,.05)}
  .stat b{font-size:24px;display:block}
  .stat span{font-size:10px;text-transform:uppercase;letter-spacing:.12em;color:#8a7a5f}
  .card{background:#fff;border-radius:16px;padding:18px;margin-bottom:14px;box-shadow:0 2px 10px rgba(0,0,0,.05)}
  .card h3{font-size:12px;text-transform:uppercase;letter-spacing:.15em;color:#775a19;margin-bottom:12px}
  #reader{border-radius:12px;overflow:hidden;background:#000;min-height:240px}
  #reader video{object-fit:cover}
  .btn{display:block;width:100%;padding:14px;border:none;border-radius:12px;font-weight:700;font-size:14px;cursor:pointer;margin-top:10px}
  .btn-gold{background:linear-gradient(135deg,#775a19,#a8842c);color:#fff}
  .btn-ghost{background:#e9e2d2;color:#5b4a2e}
  .row{display:flex;gap:8px;margin-top:10px}
  .row input{flex:1;padding:13px;border:1.5px solid #ddd3bd;border-radius:12px;font-size:16px;text-transform:uppercase;letter-spacing:.1em;text-align:center}
  #result{display:none;border-radius:14px;padding:18px;text-align:center;margin-bottom:14px}
  #result.ok{display:block;background:#dcfce7;color:#166534}
  #result.err{display:block;background:#fee2e2;color:#991b1b}
  #result .big{font-size:20px;font-weight:800}
  #result .sub{font-size:13px;margin-top:4px}
  .recent-item{display:flex;justify-content:space-between;padding:10px 2px;border-bottom:1px solid #f0e9d8;font-size:14px}
  .recent-item:last-child{border-bottom:none}
  .recent-item small{color:#8a7a5f;font-size:11px}
  .empty{color:#aaa;font-style:italic;font-size:13px;text-align:center;padding:12px}
  /* Overlay animasi sukses (Lottie centang hijau) */
  #success-overlay{position:fixed;inset:0;background:rgba(29,20,8,.55);display:none;align-items:center;justify-content:center;z-index:50;padding:20px}
  #success-overlay.show{display:flex}
  .success-box{background:#fff;border-radius:20px;padding:24px 28px 22px;text-align:center;box-shadow:0 20px 50px rgba(0,0,0,.35);max-width:320px;width:100%;animation:pop .3s ease}
  @keyframes pop{from{transform:scale(.85);opacity:0}to{transform:scale(1);opacity:1}}
  #success-anim{width:200px;height:200px;margin:0 auto}
  #success-fallback{display:none;width:120px;height:120px;margin:16px auto;border-radius:50%;background:#dcfce7;color:#166534;font-size:72px;font-weight:800;align-items:center;justify-content:center}
  #success-fallback.show{display:flex}
  .success-name{font-size:20px;font-weight:800;color:#166534;margin-top:4px}
  .success-sub{font-size:13px;color:#8a7a5f;margin-top:4px}
</style>
</head>
<body>
<div class="top">
  <small>Check-in Panitia</small>
  <h1>{{ $title }}</h1>
</div>
<div class="wrap">
  <div class="stats">
    <div class="stat"><b id="st-total">–</b><span>Total Tamu</span></div>
    <div class="stat"><b id="st-in" style="color:#15803d">–</b><span>Hadir</span></div>
    <div class="stat"><b id="st-out" style="color:#b45309">–</b><span>Belum</span></div>
  </div>

  <div id="result"><div class="big" id="res-title"></div><div class="sub" id="res-sub"></div></div>

  <div class="card">
    <h3>Scan QR Tamu</h3>
    <div id="reader"></div>
    <button class="btn btn-gold" id="btn-scan" onclick="toggleScanner()">Mulai Kamera</button>
    <div class="row">
      <input id="manual" placeholder="KODE MANUAL" maxlength="16" autocomplete="off">
      <button class="btn btn-gold" style="width:auto;margin-top:0;padding:13px 20px" onclick="manualGo()">OK</button>
    </div>
  </div>

  <div class="card">
    <h3>Baru Saja Hadir</h3>
    <div id="recent"><div class="empty">Belum ada check-in.</div></div>
  </div>
</div>

<div id="success-overlay" onclick="if(event.target===this) hideSuccessAnim()">
  <div class="success-box">
    <div id="success-anim"></div>
    <div id="success-fallback">&#10003;</div>
    <div class="success-name" id="success-name"></div>
    <div class="success-sub" id="success-sub">Check-in berhasil</div>
  </div>
</div>

<script>
var INV_ID = {{ (int)$inv_id }};
// Zona waktu acara dari Data Utama (WIB/WITA/WIT, default WIB).
var EVENT_TZ_LABEL = "{{ $event_timezone ?? 'WIB' }}";
var EVENT_TZ_IANA = {WIB: 'Asia/Jakarta', WITA: 'Asia/Makassar', WIT: 'Asia/Jayapura'}[EVENT_TZ_LABEL] || 'Asia/Jakarta';
var scanner = null, scanning = false, lastScan = 0;

function esc(s){return String(s==null?'':s).replace(/[&<>"']/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];});}

// Server menyimpan waktu dalam UTC; string tanpa zona harus dibaca sebagai
// UTC (bukan waktu lokal) lalu ditampilkan dalam zona waktu acara
// (Data Utama: WIB/WITA/WIT) — bukan hardcode WIB lagi.
function parseServerTime(s){
  if(!s) return null;
  var t = String(s).trim().replace(' ', 'T');
  if(!/[+-]\d{2}:?\d{2}$/.test(t) && t.slice(-1) !== 'Z') t += 'Z';
  var d = new Date(t);
  return isNaN(d.getTime()) ? null : d;
}
function formatEventTime(s){
  var d = parseServerTime(s);
  if(!d) return s || '';
  return d.toLocaleString('id-ID', {timeZone:EVENT_TZ_IANA, day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit', hour12:false}).replace(',', '') + ' ' + EVENT_TZ_LABEL;
}

function showRes(ok, title, sub){
  var r = document.getElementById('result');
  r.className = ok ? 'ok' : 'err';
  document.getElementById('res-title').textContent = title;
  document.getElementById('res-sub').textContent = sub;
}

async function doCheckin(code){
  code = String(code||'').trim().toUpperCase();
  if(!code) return;
  // cegah double-scan beruntun dari kamera
  var now = Date.now();
  if(now - lastScan < 2500) return;
  lastScan = now;
  try{
    var res = await fetch('/api/checkin',{
      method:'POST',headers:{'Content-Type':'application/json'},
      body:JSON.stringify({invitation_id:INV_ID, code:code})
    });
    var d = await res.json();
    if(d.success){
      showRes(true, d.already ? 'SUDAH HADIR' : 'CHECK-IN BERHASIL',
        d.nama + (d.already ? ' (sudah tercatat)' : ' • ' + formatEventTime(d.checked_in_at)));
      // Animasi diputar untuk semua scan yang berhasil — check-in baru
      // maupun scan ulang tamu yang sudah hadir (teksnya dibedakan).
      showSuccessAnim(d.nama, d.already);
      refreshStats();
    }else{
      showRes(false, 'GAGAL', d.message || 'Kode tidak valid');
    }
  }catch(e){ showRes(false, 'GAGAL', 'Tidak dapat menghubungi server'); }
}

function manualGo(){
  var el = document.getElementById('manual');
  doCheckin(el.value); el.value='';
}

// --- Overlay animasi sukses (Lottie "Order Success" kiriman Fahmi) ---
var successAnim = null, successTimer = null;

function loadLottie(cb){
  if(window.lottie) return cb(true);
  var s = document.createElement('script');
  s.src = 'https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js';
  s.onload = function(){ cb(true); };
  s.onerror = function(){ cb(false); };
  document.head.appendChild(s);
}

function hideSuccessAnim(){
  document.getElementById('success-overlay').classList.remove('show');
  clearTimeout(successTimer);
}

function showFallbackAnim(){
  document.getElementById('success-anim').innerHTML = '';
  document.getElementById('success-anim').style.display = 'none';
  document.getElementById('success-fallback').classList.add('show');
}

function showSuccessAnim(nama, already){
  document.getElementById('success-name').textContent = nama || '';
  document.getElementById('success-sub').textContent = already ? 'Sudah tercatat hadir' : 'Check-in berhasil';
  document.getElementById('success-overlay').classList.add('show');
  document.getElementById('success-fallback').classList.remove('show');
  document.getElementById('success-anim').style.display = 'block';
  loadLottie(function(ok){
    if(!ok){
      // Player gagal dimuat: tampilkan centang hijau statis sebagai cadangan
      showFallbackAnim();
      return;
    }
    if(successAnim) successAnim.destroy();
    successAnim = lottie.loadAnimation({
      container: document.getElementById('success-anim'),
      renderer: 'svg',
      loop: false,
      autoplay: true,
      // Path relatif dari root (bukan asset() absolut) supaya tetap benar
      // apa pun host yang dipakai membuka halaman ini — asset() memakai
      // APP_URL, yang bisa menunjuk 127.0.0.1 sehingga gagal di HP panitia.
      path: '/lottie/order-success.json'
    });
    // JSON gagal diunduh (mis. file belum ter-deploy): jangan tampil kosong,
    // pakai centang hijau statis sebagai cadangan.
    successAnim.addEventListener('data_failed', showFallbackAnim);
  });
  clearTimeout(successTimer);
  successTimer = setTimeout(hideSuccessAnim, 3000);
}

function loadLib(cb){
  if(window.Html5Qrcode) return cb();
  var s = document.createElement('script');
  s.src = 'https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js';
  s.onload = cb;
  s.onerror = function(){ alert('Gagal memuat library scanner. Periksa koneksi internet.'); };
  document.head.appendChild(s);
}

function toggleScanner(){
  if(scanning){ stopScanner(); return; }
  loadLib(function(){
    scanner = new Html5Qrcode('reader');
    scanner.start({facingMode:'environment'}, {fps:10, qrbox:{width:230,height:230}},
      function(text){ doCheckin(text); }, function(){}
    ).then(function(){
      scanning = true;
      document.getElementById('btn-scan').textContent = 'Matikan Kamera';
      document.getElementById('btn-scan').className = 'btn btn-ghost';
    }).catch(function(){ alert('Tidak dapat mengakses kamera.'); });
  });
}
function stopScanner(){
  if(scanner){ scanner.stop().then(function(){ scanner.clear(); }).catch(function(){}); }
  scanning = false;
  document.getElementById('btn-scan').textContent = 'Mulai Kamera';
  document.getElementById('btn-scan').className = 'btn btn-gold';
}

async function refreshStats(){
  try{
    var res = await fetch('/api/checkin_stats?inv_id=' + INV_ID);
    var d = await res.json();
    if(!d.success) return;
    document.getElementById('st-total').textContent = d.total;
    document.getElementById('st-in').textContent = d.checked_in;
    document.getElementById('st-out').textContent = d.total - d.checked_in;
    document.getElementById('recent').innerHTML = d.recent.length
      ? d.recent.map(function(r){
          return '<div class="recent-item"><span>' + esc(r.nama) + '</span><small>' + esc(formatEventTime(r.checked_in_at)) + '</small></div>';
        }).join('')
      : '<div class="empty">Belum ada check-in.</div>';
  }catch(e){}
}
refreshStats();
setInterval(refreshStats, 15000);
</script>
</body>
</html>
