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

<script>
var INV_ID = {{ (int)$inv_id }};
var scanner = null, scanning = false, lastScan = 0;

function esc(s){return String(s==null?'':s).replace(/[&<>"']/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];});}

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
        d.nama + (d.already ? ' (sudah tercatat)' : ' • ' + (d.checked_in_at||'')));
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
          return '<div class="recent-item"><span>' + esc(r.nama) + '</span><small>' + esc(r.checked_in_at||'') + '</small></div>';
        }).join('')
      : '<div class="empty">Belum ada check-in.</div>';
  }catch(e){}
}
refreshStats();
setInterval(refreshStats, 15000);
</script>
</body>
</html>
