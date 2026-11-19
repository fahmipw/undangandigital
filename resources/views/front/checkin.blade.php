<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Check-in Panitia — {{ $title }}</title>
@php
// Palet Link Generator per tema (diturunkan dari palet tema undangan).
// Kunci: $theme dari FrontController::generator; tak dikenal -> 'default'.
$genPalettes = [
    'default' => ['accent' => '#775a19', 'accentDark' => '#5d4201', 'bg' => '#f5f3ef', 'ink' => '#1b1c1a', 'inkSoft' => '#4b5563', 'muted' => '#6b7280', 'card' => '#ffffff', 'soft' => '#f9fafb', 'border' => '#e5e7eb', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'arabian-nights' => ['accent' => '#d4af37', 'accentDark' => '#997e28', 'bg' => '#120a20', 'ink' => '#f3eefc', 'inkSoft' => '#b0aaba', 'muted' => '#777183', 'card' => '#1a0f2e', 'soft' => '#160c27', 'border' => '#2d253a', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'bawah-laut' => ['accent' => '#16617f', 'accentDark' => '#10465b', 'bg' => '#dff2f7', 'ink' => '#0e2f3d', 'inkSoft' => '#4d6a75', 'muted' => '#819aa3', 'card' => '#f2fbfe', 'soft' => '#e8f6fa', 'border' => '#c6dbe1', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'betawi' => ['accent' => '#1E4D33', 'accentDark' => '#163725', 'bg' => '#E4EDE1', 'ink' => '#1F2A24', 'inkSoft' => '#5a645d', 'muted' => '#8b958c', 'card' => '#FAF9F4', 'soft' => '#eff3ea', 'border' => '#ccd6ca', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'empat-musim' => ['accent' => '#3e7d44', 'accentDark' => '#2d5a31', 'bg' => '#f0f4ea', 'ink' => '#1e2b1e', 'inkSoft' => '#5d675b', 'muted' => '#929a8e', 'card' => '#fffdf7', 'soft' => '#f8f8f0', 'border' => '#d7dcd2', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'galaksi-cinta' => ['accent' => '#b18cff', 'accentDark' => '#7f65b8', 'bg' => '#0f0f28', 'ink' => '#efecff', 'inkSoft' => '#acaabe', 'muted' => '#747289', 'card' => '#16163a', 'soft' => '#121231', 'border' => '#2a2a42', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'gunung-berkabut' => ['accent' => '#5b7a8a', 'accentDark' => '#425863', 'bg' => '#eef2f4', 'ink' => '#1f2a30', 'inkSoft' => '#5d666b', 'muted' => '#91989c', 'card' => '#ffffff', 'soft' => '#f6f8fa', 'border' => '#d5dadc', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'islamic-elegance' => ['accent' => '#c9a24b', 'accentDark' => '#917536', 'bg' => '#072a25', 'ink' => '#eef7f2', 'inkSoft' => '#a9b9b4', 'muted' => '#6f8681', 'card' => '#0a3a32', 'soft' => '#08322c', 'border' => '#23433e', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'jawa-arunika' => ['accent' => '#7c4a18', 'accentDark' => '#593511', 'bg' => '#f3e7d6', 'ink' => '#2b2118', 'inkSoft' => '#675c51', 'muted' => '#998e80', 'card' => '#f7efe6', 'soft' => '#f5ebde', 'border' => '#dbcfbf', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'jawa-biru' => ['accent' => '#e6c887', 'accentDark' => '#a69061', 'bg' => '#071224', 'ink' => '#eef2f7', 'inkSoft' => '#a9afb8', 'muted' => '#6f7783', 'card' => '#0a1b30', 'soft' => '#08162a', 'border' => '#232d3d', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'jawa-cinematic' => ['accent' => '#D4AF37', 'accentDark' => '#997e28', 'bg' => '#071223', 'ink' => '#f2ead8', 'inkSoft' => '#aba9a2', 'muted' => '#717374', 'card' => '#0A192F', 'soft' => '#081629', 'border' => '#232c39', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'jawa-gerbang' => ['accent' => '#77603a', 'accentDark' => '#56452a', 'bg' => '#f5f0e1', 'ink' => '#2b2118', 'inkSoft' => '#685f54', 'muted' => '#9a9387', 'card' => '#fffdf7', 'soft' => '#faf6ec', 'border' => '#ddd7c9', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'jawa-gunungan' => ['accent' => '#5A3620', 'accentDark' => '#412717', 'bg' => '#EAD9C8', 'ink' => '#3E2618', 'inkSoft' => '#725c4d', 'muted' => '#9d8879', 'card' => '#F5EAD9', 'soft' => '#f0e2d0', 'border' => '#d5c4b3', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'jawa-klasik' => ['accent' => '#d4af37', 'accentDark' => '#997e28', 'bg' => '#1e150c', 'ink' => '#f5ead9', 'inkSoft' => '#b4aa9b', 'muted' => '#7f7568', 'card' => '#2a1c11', 'soft' => '#24180e', 'border' => '#382f25', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'jawa-pendopo' => ['accent' => '#77603a', 'accentDark' => '#56452a', 'bg' => '#f5f0e1', 'ink' => '#2b2118', 'inkSoft' => '#685f54', 'muted' => '#9a9387', 'card' => '#fffdf7', 'soft' => '#faf6ec', 'border' => '#ddd7c9', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'jawa-premium' => ['accent' => '#d4af37', 'accentDark' => '#997e28', 'bg' => '#2a140a', 'ink' => '#f5ead9', 'inkSoft' => '#b8aa9b', 'muted' => '#857467', 'card' => '#3a1c0e', 'soft' => '#32180c', 'border' => '#422e23', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'jawa-puspa' => ['accent' => '#77603a', 'accentDark' => '#56452a', 'bg' => '#f5f0e1', 'ink' => '#2b2118', 'inkSoft' => '#685f54', 'muted' => '#9a9387', 'card' => '#fffdf7', 'soft' => '#faf6ec', 'border' => '#ddd7c9', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'midnight-galaxy' => ['accent' => '#a78bfa', 'accentDark' => '#7864b4', 'bg' => '#0d0920', 'ink' => '#efecff', 'inkSoft' => '#aba8bc', 'muted' => '#736f84', 'card' => '#140e2c', 'soft' => '#100c26', 'border' => '#28243b', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'minang-gadang' => ['accent' => '#d4af37', 'accentDark' => '#997e28', 'bg' => '#0c3a22', 'ink' => '#f2f7ee', 'inkSoft' => '#adbeb1', 'muted' => '#748f7e', 'card' => '#12502f', 'soft' => '#0f4528', 'border' => '#28513a', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'minecraft' => ['accent' => '#7bff5e', 'accentDark' => '#59b844', 'bg' => '#0b0f1a', 'ink' => '#e8f5e9', 'inkSoft' => '#a6b0ab', 'muted' => '#6e7677', 'card' => '#101010', 'soft' => '#0e1015', 'border' => '#262b33', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'minecraft-adventure' => ['accent' => '#fcee5e', 'accentDark' => '#b5ab44', 'bg' => '#0d1420', 'ink' => '#f5f5f5', 'inkSoft' => '#afb2b5', 'muted' => '#757980', 'card' => '#1a2333', 'soft' => '#141c2a', 'border' => '#292f3a', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'neon-metropolis' => ['accent' => '#00e5ff', 'accentDark' => '#00a5b8', 'bg' => '#0a0a1e', 'ink' => '#e8fbff', 'inkSoft' => '#a5b3bc', 'muted' => '#6e7683', 'card' => '#12122b', 'soft' => '#0e0e24', 'border' => '#252739', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'origami-dreams' => ['accent' => '#d15f7c', 'accentDark' => '#964459', 'bg' => '#fbeef2', 'ink' => '#2b1e22', 'inkSoft' => '#695c60', 'muted' => '#9d9094', 'card' => '#fdfaf2', 'soft' => '#fcf4f2', 'border' => '#e2d5d9', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#1b1c1a'],
    'pixel-quest' => ['accent' => '#ffd93b', 'accentDark' => '#b89c2a', 'bg' => '#101010', 'ink' => '#f5f5f5', 'inkSoft' => '#b0b0b0', 'muted' => '#777777', 'card' => '#1e1e1e', 'soft' => '#171717', 'border' => '#2b2b2b', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'rimba-watercolor' => ['accent' => '#2f4a3a', 'accentDark' => '#22352a', 'bg' => '#eef0e6', 'ink' => '#1e2a22', 'inkSoft' => '#5c655d', 'muted' => '#90978e', 'card' => '#fbf9f2', 'soft' => '#f4f4ec', 'border' => '#d5d8ce', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'royal-emerald' => ['accent' => '#c9a24b', 'accentDark' => '#917536', 'bg' => '#082a20', 'ink' => '#eef7f2', 'inkSoft' => '#a9b9b3', 'muted' => '#70867e', 'card' => '#0b3a2c', 'soft' => '#0a3226', 'border' => '#244339', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'ruang-jawa' => ['accent' => '#8e1b12', 'accentDark' => '#66130d', 'bg' => '#efe6d0', 'ink' => '#2b2118', 'inkSoft' => '#665c4f', 'muted' => '#978d7d', 'card' => '#fdf6dd', 'soft' => '#f6eed6', 'border' => '#d7ceba', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'rustic-boho' => ['accent' => '#b4653a', 'accentDark' => '#82492a', 'bg' => '#f5ecdc', 'ink' => '#2b2118', 'inkSoft' => '#685e53', 'muted' => '#9a9184', 'card' => '#efe4d0', 'soft' => '#f2e8d6', 'border' => '#ddd4c4', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'sakura-dream' => ['accent' => '#bc7182', 'accentDark' => '#87515e', 'bg' => '#fdf0f3', 'ink' => '#2b1e22', 'inkSoft' => '#6a5d61', 'muted' => '#9e9295', 'card' => '#ffffff', 'soft' => '#fef8f9', 'border' => '#e4d7da', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#1b1c1a'],
    'senja-pesisir' => ['accent' => '#d16843', 'accentDark' => '#964b30', 'bg' => '#fdf0e8', 'ink' => '#2b2118', 'inkSoft' => '#6a5f56', 'muted' => '#9e938a', 'card' => '#fffdf7', 'soft' => '#fef6f0', 'border' => '#e4d7cf', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#1b1c1a'],
    'sogan-parallax' => ['accent' => '#d4af37', 'accentDark' => '#997e28', 'bg' => '#1e1008', 'ink' => '#f5ead9', 'inkSoft' => '#b4a99a', 'muted' => '#7f7266', 'card' => '#2b180d', 'soft' => '#24140a', 'border' => '#382a21', 'hover' => 'rgba(255,255,255,.08)', 'onAccent' => '#1b1c1a'],
    'stardew-adventure' => ['accent' => '#4a9a32', 'accentDark' => '#356f24', 'bg' => '#dfe8d2', 'ink' => '#1e2a1e', 'inkSoft' => '#586354', 'muted' => '#889281', 'card' => '#fffdf4', 'soft' => '#eff2e3', 'border' => '#c8d1bc', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#1b1c1a'],
    'stardew-valley' => ['accent' => '#8b5a2b', 'accentDark' => '#64411f', 'bg' => '#f5ecd7', 'ink' => '#2b2118', 'inkSoft' => '#685e51', 'muted' => '#9a9181', 'card' => '#fffdf4', 'soft' => '#faf4e6', 'border' => '#ddd4c0', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'taman-janur' => ['accent' => '#3e7d44', 'accentDark' => '#2d5a31', 'bg' => '#f8f6e8', 'ink' => '#1e2b1e', 'inkSoft' => '#5f685b', 'muted' => '#969b8d', 'card' => '#fffdf7', 'soft' => '#fcfaf0', 'border' => '#deded0', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    'taman-surga' => ['accent' => '#7a9b76', 'accentDark' => '#587055', 'bg' => '#f0f4ec', 'ink' => '#1e2b1e', 'inkSoft' => '#5d675c', 'muted' => '#929a8f', 'card' => '#fffdf8', 'soft' => '#f8f8f2', 'border' => '#d7dcd3', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#1b1c1a'],
    'winter-aurora' => ['accent' => '#3a6ea5', 'accentDark' => '#2a4f77', 'bg' => '#eef4f9', 'ink' => '#1e2a33', 'inkSoft' => '#5c676e', 'muted' => '#9099a0', 'card' => '#ffffff', 'soft' => '#f6fafc', 'border' => '#d5dce1', 'hover' => 'rgba(0,0,0,.06)', 'onAccent' => '#ffffff'],
    ];
$gen = $genPalettes[$theme ?? 'default'] ?? $genPalettes['default'];
// Hijau progres (instruksi Fahmi): % + isi bar harus terbaca di latar
// tema terang maupun gelap — pilih hijau menurut kecerahan background.
$genLum = function($hex){ $hex = ltrim($hex, '#'); $r = hexdec(substr($hex, 0, 2)) / 255; $g = hexdec(substr($hex, 2, 2)) / 255; $b = hexdec(substr($hex, 4, 2)) / 255; $f = function($c){ return $c <= 0.03928 ? $c / 12.92 : pow(($c + 0.055) / 1.055, 2.4); }; return 0.2126 * $f($r) + 0.7152 * $f($g) + 0.0722 * $f($b); };
$genDarkBg = $genLum($gen['bg']) < 0.25;
$genPct = $genDarkBg ? '#4ade80' : '#166534';
$genPctFill = $genDarkBg ? '#22c55e' : '#15803d';
@endphp
<style>
  :root{
    --gen-accent:{{ $gen['accent'] }};
    --gen-accent-dark:{{ $gen['accentDark'] }};
    --gen-bg:{{ $gen['bg'] }};
    --gen-ink:{{ $gen['ink'] }};
    --gen-ink-soft:{{ $gen['inkSoft'] }};
    --gen-muted:{{ $gen['muted'] }};
    --gen-card:{{ $gen['card'] }};
    --gen-soft:{{ $gen['soft'] }};
    --gen-border:{{ $gen['border'] }};
    --gen-hover:{{ $gen['hover'] }};
    --gen-on-accent:{{ $gen['onAccent'] }};
    --gen-pct:{{ $genPct }};
    --gen-pct-fill:{{ $genPctFill }};
  }
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:-apple-system,'Segoe UI',Roboto,sans-serif;background:var(--gen-bg);color:var(--gen-ink);min-height:100vh;padding-bottom:40px}
  .top{background:var(--gen-accent);color:var(--gen-on-accent);padding:22px 18px;text-align:center}
  .top small{letter-spacing:.25em;font-size:10px;opacity:.75;text-transform:uppercase}
  .top h1{font-size:20px;margin-top:6px}
  .top .evdate{font-size:13px;margin-top:4px;opacity:.9}
  .top .clock{font-size:13px;margin-top:6px;font-variant-numeric:tabular-nums}
  .top .clock b{font-weight:700}
  .top-actions{margin-top:12px;display:flex;gap:8px;justify-content:center;flex-wrap:wrap}
  .top-pill{display:inline-block;background:transparent;color:inherit;border:1px solid currentColor;border-radius:999px;padding:6px 12px;font-size:12px;cursor:pointer;text-decoration:none;opacity:.9}
  .top-pill:hover{opacity:1}
  .wrap{max-width:480px;margin:0 auto;padding:16px}
  .stats{display:flex;gap:10px;margin:16px 0 10px}
  .stat{flex:1;background:var(--gen-card);border:1px solid var(--gen-border);border-radius:14px;padding:14px 8px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,.05)}
  .stat b{font-size:24px;display:block}
  .stat svg{display:block;margin:0 auto 6px;width:26px;height:26px}
  .stat span{font-size:10px;text-transform:uppercase;letter-spacing:.12em;color:var(--gen-inkSoft)}
  .progress{margin:0 0 16px}
  .progress .bar{height:10px;border-radius:999px;background:var(--gen-soft);border:1px solid var(--gen-border);overflow:hidden}
  .progress .fill{height:100%;width:0%;background:var(--gen-pct-fill);transition:width .4s ease}
  .progress .lbl{font-size:12px;color:var(--gen-inkSoft);text-align:center;margin-top:6px}
  .progress .lbl .pct{color:var(--gen-pct);font-weight:700}
  .card{background:var(--gen-card);border:1px solid var(--gen-border);border-radius:16px;padding:18px;margin-bottom:14px;box-shadow:0 2px 10px rgba(0,0,0,.05)}
  .card h3{font-size:12px;text-transform:uppercase;letter-spacing:.15em;color:var(--gen-accent);margin-bottom:12px}
  .card h3 .pill{float:right;background:var(--gen-soft);border:1px solid var(--gen-border);color:var(--gen-inkSoft);border-radius:999px;padding:1px 8px;font-size:11px;letter-spacing:0;text-transform:none}
  #reader{border-radius:12px;overflow:hidden;background:#000;min-height:240px}
  #reader video{object-fit:cover}
  .btn{display:block;width:100%;padding:14px;border:none;border-radius:12px;font-weight:700;font-size:14px;cursor:pointer;margin-top:10px}
  .btn-gold{background:linear-gradient(135deg,var(--gen-accent),var(--gen-accentDark));color:var(--gen-on-accent)}
  .btn-ghost{background:var(--gen-soft);color:var(--gen-inkSoft);border:1px solid var(--gen-border)}
  .btn .ic{vertical-align:-3px;margin-right:6px}
  .row{display:flex;gap:8px;margin-top:10px;flex-wrap:wrap}
  .row input{flex:1;padding:13px;border:1.5px solid var(--gen-border);border-radius:12px;font-size:16px;text-transform:uppercase;letter-spacing:.1em;text-align:center;background:var(--gen-card);color:var(--gen-ink)}
  .row input::placeholder{color:var(--gen-inkSoft)}
  .search{width:100%;padding:12px 14px;border:1.5px solid var(--gen-border);border-radius:12px;font-size:15px;background:var(--gen-card);color:var(--gen-ink);margin-bottom:10px}
  .search::placeholder{color:var(--gen-inkSoft)}
  #result{display:none;border-radius:14px;padding:18px;text-align:center;margin-bottom:14px}
  #result.ok{display:block;background:#dcfce7;color:#166534}
  #result.err{display:block;background:#fee2e2;color:#991b1b}
  #result .big{font-size:20px;font-weight:800}
  #result .sub{font-size:13px;margin-top:4px}
  .recent-item{display:flex;justify-content:space-between;gap:8px;padding:10px 2px;border-bottom:1px solid var(--gen-border);font-size:14px}
  .recent-item:last-child{border-bottom:none}
  .recent-item small{color:var(--gen-inkSoft);font-size:11px;white-space:nowrap}
  .guest-item{display:flex;align-items:center;gap:8px;padding:9px 2px;border-bottom:1px solid var(--gen-border);font-size:14px}
  .guest-item:last-child{border-bottom:none}
  .guest-item .gname{flex:1;min-width:0;overflow-wrap:anywhere}
  .guest-item .gtime{color:var(--gen-inkSoft);font-size:11px;white-space:nowrap}
  .badge{flex-shrink:0;font-size:11px;font-weight:700;border-radius:999px;padding:3px 9px;white-space:nowrap}
  .badge.in{background:#dcfce7;color:#166534}
  .badge.out{background:#fef3c7;color:#92400e}
  .mini{flex-shrink:0;border:none;border-radius:999px;padding:7px 12px;font-size:12px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,var(--gen-accent),var(--gen-accentDark));color:var(--gen-on-accent)}
  .count{font-size:12px;color:var(--gen-inkSoft);text-align:center;margin-top:8px}
  .empty{color:var(--gen-inkSoft);font-style:italic;font-size:13px;text-align:center;padding:12px}
  /* Bar nama petugas + stepper pendamping (gelombang 2) */
  .opbar{display:flex;gap:8px;align-items:center}
  .opbar input{flex:1;min-width:0;padding:11px 12px;border:1.5px solid var(--gen-border);border-radius:12px;font-size:15px;background:var(--gen-card);color:var(--gen-ink)}
  .opbar input::placeholder{color:var(--gen-inkSoft)}
  .opbar .opname{flex:1;min-width:0;font-size:14px}
  .opbar .opname b{font-weight:700}
  .opbar button{flex-shrink:0;border:none;border-radius:12px;padding:11px 16px;font-size:13px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,var(--gen-accent),var(--gen-accentDark));color:var(--gen-on-accent)}
  .opbar button.alt{background:var(--gen-soft);color:var(--gen-inkSoft);border:1px solid var(--gen-border)}
  .stepper{display:flex;align-items:center;justify-content:center;gap:14px;margin-top:14px}
  .stepper button{width:44px;height:44px;border-radius:50%;border:1px solid var(--gen-border);background:var(--gen-soft);color:var(--gen-ink);font-size:24px;font-weight:700;cursor:pointer}
  .stepper b{font-size:26px;min-width:36px;text-align:center;font-variant-numeric:tabular-nums}
  .people-lbl{font-size:12px;color:var(--gen-inkSoft);text-align:center;margin-top:4px}
  .recent-meta{font-size:11px;color:var(--gen-inkSoft);margin-top:2px}
  /* Overlay animasi sukses (Lottie centang hijau) */
  #success-overlay{position:fixed;inset:0;background:rgba(0,0,0,.55);display:none;align-items:center;justify-content:center;z-index:50;padding:20px}
  #success-overlay.show{display:flex}
  .success-box{background:var(--gen-card);border:1px solid var(--gen-border);border-radius:20px;padding:24px 28px 22px;text-align:center;box-shadow:0 20px 50px rgba(0,0,0,.35);max-width:320px;width:100%;animation:pop .3s ease}
  @keyframes pop{from{transform:scale(.85);opacity:0}to{transform:scale(1);opacity:1}}
  #success-anim{width:200px;height:200px;margin:0 auto}
  #success-fallback{display:none;width:120px;height:120px;margin:16px auto;border-radius:50%;background:#dcfce7;color:#166534;font-size:72px;font-weight:800;align-items:center;justify-content:center}
  #success-fallback.show{display:flex}
  .success-name{font-size:20px;font-weight:800;color:#166534;margin-top:4px}
  .success-sub{font-size:13px;color:var(--gen-inkSoft);margin-top:4px}
</style>
</head>
<body>
<div class="top">
  <small>Check-in Panitia</small>
  <h1>{{ $title }}</h1>
  <div class="evdate" id="ev-date"></div>
  <div class="clock" id="clock">—</div>
  <div class="top-actions">
    <a class="top-pill" href="/checkin/{{ $slug }}/{{ $token }}/monitor">Mode Monitor</a>
    <button class="top-pill" id="btn-sound" type="button" onclick="toggleSound()">Suara: Nyala</button>
  </div>
</div>
<div class="wrap">
  <div class="card" id="op-card" style="padding:14px 18px">
    <div id="op-form">
      <h3 style="margin-bottom:8px">Nama Petugas</h3>
      <div class="opbar">
        <input id="op-name" placeholder="Nama kamu (petugas)" autocomplete="off" maxlength="100">
        <button type="button" onclick="saveOperator()">Simpan</button>
      </div>
      <div style="font-size:12px;color:var(--gen-inkSoft);margin-top:8px">Nama ini dicatat di setiap check-in dari HP ini.</div>
    </div>
    <div id="op-saved" style="display:none">
      <div class="opbar"><span class="opname">Petugas: <b id="op-name-show"></b></span><button type="button" class="alt" onclick="editOperator()">Ganti</button></div>
    </div>
  </div>
  <div class="stats">
    <div class="stat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--gen-accent)"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg><b id="st-total">–</b><span>Total Tamu</span></div>
    <div class="stat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#15803d"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg><b id="st-in" style="color:#15803d">–</b><span>Hadir</span></div>
    <div class="stat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#b45309"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><b id="st-out" style="color:#b45309">–</b><span>Belum</span></div>
  </div>
  <div class="progress">
    <div class="bar"><div class="fill" id="prog-fill"></div></div>
    <div class="lbl" id="prog-lbl">—</div>
    <div class="people-lbl" id="people-lbl"></div>
  </div>

  <div id="result"><div class="big" id="res-title"></div><div class="sub" id="res-sub"></div></div>

  <div class="card">
    <h3>Scan QR Tamu</h3>
    <div id="reader"></div>
    <button class="btn btn-gold" id="btn-scan" onclick="toggleScanner()"><svg class="ic" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM21 14v3M14 21h3M21 18v3h-3"/></svg><span id="btn-scan-label">Mulai Scan QR</span></button>
    <div class="row">
      <input id="manual" placeholder="KODE UNDANGAN" maxlength="16" autocomplete="off">
      <button class="btn btn-gold" style="width:auto;margin-top:0;padding:13px 20px;white-space:nowrap" onclick="manualGo()">KODE UNDANGAN</button>
    </div>
  </div>

  <div class="card">
    <h3>Cari Tamu</h3>
    <input class="search" id="guest-search" type="search" placeholder="Cari nama tamu..." autocomplete="off" oninput="renderGuestSearch()">
    <div id="guest-list"><div class="empty">Memuat daftar tamu...</div></div>
    <div class="count" id="guest-count"></div>
  </div>

  <div class="card">
    <h3>Belum Hadir <span class="pill" id="pending-count"></span></h3>
    <div id="pending-list"><div class="empty">Memuat daftar tamu...</div></div>
    <button class="btn btn-ghost" id="btn-pending-more" style="display:none" onclick="togglePending()">Tampilkan semua</button>
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
    <div id="comp-box" style="display:none;margin-top:16px;border-top:1px solid var(--gen-border);padding-top:14px">
      <div style="font-size:13px;color:var(--gen-inkSoft)">Jumlah pendamping (di luar tamu)</div>
      <div class="stepper">
        <button type="button" onclick="compStep(-1)">-</button>
        <b id="comp-val">0</b>
        <button type="button" onclick="compStep(1)">+</button>
      </div>
      <button class="btn btn-gold" type="button" onclick="saveCompanions()">Simpan Pendamping</button>
      <button class="btn btn-ghost" type="button" onclick="hideSuccessAnim()">Lewati</button>
    </div>
  </div>
</div>

<script>
var INV_ID = {{ (int)$inv_id }};
// Token check-in dari URL halaman (sudah lolos checkinPage()); dikirim di
// tiap request API dan dicocokkan server dengan settings.checkin_token.
var CHECKIN_TOKEN = "{{ $token }}";
// Zona waktu acara dari Data Utama (WIB/WITA/WIT, default WIB).
var EVENT_TZ_LABEL = "{{ $event_timezone ?? 'WIB' }}";
var EVENT_TZ_IANA = {WIB: 'Asia/Jakarta', WITA: 'Asia/Makassar', WIT: 'Asia/Jayapura'}[EVENT_TZ_LABEL] || 'Asia/Jakarta';
var EVENT_DATE = "{{ $event_date ?? '' }}";
var scanner = null, scanning = false, lastScan = 0;

// --- Nama petugas (opsi 1): ditanya sekali, disimpan per HP + undangan ---
var OP_KEY = 'checkin_operator_' + INV_ID;
var operatorName = '';
try{ operatorName = localStorage.getItem(OP_KEY) || ''; }catch(e){}
function renderOperator(){
  var has = !!operatorName;
  document.getElementById('op-form').style.display = has ? 'none' : 'block';
  document.getElementById('op-saved').style.display = has ? 'block' : 'none';
  if(has) document.getElementById('op-name-show').textContent = operatorName;
}
function saveOperator(){
  var v = (document.getElementById('op-name').value || '').trim();
  if(!v){ document.getElementById('op-name').focus(); return; }
  operatorName = v.slice(0, 100);
  try{ localStorage.setItem(OP_KEY, operatorName); }catch(e){}
  renderOperator();
}
function editOperator(){
  document.getElementById('op-name').value = operatorName;
  operatorName = '';
  try{ localStorage.removeItem(OP_KEY); }catch(e){}
  renderOperator();
  document.getElementById('op-name').focus();
}
renderOperator();

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
// Tanggal acara (YYYY-MM-DD dari Data Utama) ditampilkan dalam zona acara.
function formatEventDate(s){
  if(!s) return '';
  var p = String(s).split('-');
  if(p.length !== 3) return s;
  var d = new Date(Date.UTC(+p[0], +p[1] - 1, +p[2]));
  if(isNaN(d.getTime())) return s;
  return d.toLocaleDateString('id-ID', {timeZone:EVENT_TZ_IANA, weekday:'long', day:'numeric', month:'long', year:'numeric'});
}
document.getElementById('ev-date').textContent = formatEventDate(EVENT_DATE);

// Jam realtime dalam zona waktu acara, diperbarui tiap detik.
function tickClock(){
  var now = new Date();
  var dateStr = now.toLocaleDateString('id-ID', {timeZone:EVENT_TZ_IANA, weekday:'long', day:'numeric', month:'long', year:'numeric'});
  var timeStr = now.toLocaleTimeString('id-ID', {timeZone:EVENT_TZ_IANA, hour:'2-digit', minute:'2-digit', second:'2-digit', hour12:false});
  document.getElementById('clock').innerHTML = '<b>' + esc(timeStr) + ' ' + esc(EVENT_TZ_LABEL) + '</b> &bull; ' + esc(dateStr);
}
tickClock();
setInterval(tickClock, 1000);

// --- Suara notifikasi (WebAudio, tanpa file) ---
// AudioContext hanya boleh dibuat/di-resume setelah interaksi pengguna,
// jadi dibuat malas (lazy) pada sentuhan/klik pertama.
var audioCtx = null, soundOn = true;
try{ soundOn = localStorage.getItem('checkin_sound') !== 'off'; }catch(e){}
function ensureAudio(){
  if(!audioCtx){ try{ audioCtx = new (window.AudioContext || window.webkitAudioContext)(); }catch(e){} }
  if(audioCtx && audioCtx.state === 'suspended') audioCtx.resume();
}
document.addEventListener('pointerdown', ensureAudio);
document.addEventListener('keydown', ensureAudio);
function beep(freq, start, dur, type, vol){
  if(!audioCtx) return;
  var o = audioCtx.createOscillator(), g = audioCtx.createGain();
  o.type = type || 'sine'; o.frequency.setValueAtTime(freq, start);
  g.gain.setValueAtTime(0.0001, start);
  g.gain.exponentialRampToValueAtTime(vol || 0.15, start + 0.02);
  g.gain.exponentialRampToValueAtTime(0.0001, start + dur);
  o.connect(g); g.connect(audioCtx.destination);
  o.start(start); o.stop(start + dur + 0.05);
}
function playSound(kind){
  if(!soundOn || !audioCtx) return;
  var t = audioCtx.currentTime + 0.01;
  if(kind === 'ok'){ beep(660, t, 0.12); beep(880, t + 0.13, 0.2); }
  else if(kind === 'dup'){ beep(520, t, 0.12); beep(520, t + 0.16, 0.12); }
  else { beep(160, t, 0.3, 'sawtooth', 0.12); }
}
function toggleSound(){
  soundOn = !soundOn;
  try{ localStorage.setItem('checkin_sound', soundOn ? 'on' : 'off'); }catch(e){}
  document.getElementById('btn-sound').textContent = soundOn ? 'Suara: Nyala' : 'Suara: Mati';
  if(soundOn){ ensureAudio(); playSound('ok'); }
}
document.getElementById('btn-sound').textContent = soundOn ? 'Suara: Nyala' : 'Suara: Mati';

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
      body:JSON.stringify({invitation_id:INV_ID, code:code, token:CHECKIN_TOKEN, operator:operatorName})
    });
    var d = await res.json();
    if(d.success){
      lastCode = code;
      var compTxt = (d.companions > 0) ? ' • +' + d.companions + ' pendamping' : '';
      showRes(true, d.already ? 'SUDAH HADIR' : 'CHECK-IN BERHASIL',
        d.nama + (d.already ? ' (sudah tercatat)' : ' • ' + formatEventTime(d.checked_in_at)) + compTxt);
      // Animasi diputar untuk semua scan yang berhasil — check-in baru
      // maupun scan ulang tamu yang sudah hadir (teksnya dibedakan).
      showSuccessAnim(d.nama, d.already, d.companions || 0);
      playSound(d.already ? 'dup' : 'ok');
      refreshStats();
      loadGuests();
    }else{
      showRes(false, 'GAGAL', d.message || 'Kode tidak valid');
      playSound('err');
    }
  }catch(e){ showRes(false, 'GAGAL', 'Tidak dapat menghubungi server'); playSound('err'); }
}

function manualGo(){
  var el = document.getElementById('manual');
  doCheckin(el.value); el.value='';
}

// --- Overlay animasi sukses (Lottie "Order Success" kiriman Fahmi) ---
var successAnim = null, successTimer = null;
var lastCode = '', compVal = 0;

// --- Stepper pendamping di overlay sukses ---
function compStep(d){
  compVal = Math.max(0, Math.min(20, compVal + d));
  document.getElementById('comp-val').textContent = compVal;
}
async function saveCompanions(){
  if(!lastCode) return;
  try{
    var res = await fetch('/api/checkin_companions',{
      method:'POST',headers:{'Content-Type':'application/json'},
      body:JSON.stringify({invitation_id:INV_ID, code:lastCode, companions:compVal, token:CHECKIN_TOKEN, operator:operatorName})
    });
    var d = await res.json();
    if(d.success){
      hideSuccessAnim();
      refreshStats();
      loadGuests();
    }else{
      alert(d.message || 'Gagal menyimpan pendamping.');
    }
  }catch(e){ alert('Tidak dapat menghubungi server.'); }
}

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

function showSuccessAnim(nama, already, companions){
  document.getElementById('success-name').textContent = nama || '';
  document.getElementById('success-sub').textContent = already ? 'Sudah tercatat hadir' : 'Check-in berhasil';
  // Stepper pendamping: tampil di setiap scan sukses; overlay tidak
  // menutup otomatis supaya petugas sempat mengisi lalu Simpan/Lewati.
  compVal = companions || 0;
  document.getElementById('comp-val').textContent = compVal;
  document.getElementById('comp-box').style.display = 'block';
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
}

function loadLib(cb){
  if(window.Html5Qrcode) return cb();
  var s = document.createElement('script');
  s.src = 'https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js';
  s.onload = cb;
  s.onerror = function(){ alert('Gagal memuat library scanner. Periksa koneksi internet.'); };
  document.head.appendChild(s);
}

function setScanBtn(on){
  document.getElementById('btn-scan-label').textContent = on ? 'Berhenti Scan' : 'Mulai Scan QR';
  document.getElementById('btn-scan').className = 'btn ' + (on ? 'btn-ghost' : 'btn-gold');
}

function toggleScanner(){
  if(scanning){ stopScanner(); return; }
  loadLib(function(){
    scanner = new Html5Qrcode('reader');
    scanner.start({facingMode:'environment'}, {fps:10, qrbox:{width:230,height:230}},
      function(text){ doCheckin(text); }, function(){}
    ).then(function(){
      scanning = true;
      setScanBtn(true);
    }).catch(function(){ alert('Tidak dapat mengakses kamera.'); });
  });
}
function stopScanner(){
  if(scanner){ scanner.stop().then(function(){ scanner.clear(); }).catch(function(){}); }
  scanning = false;
  setScanBtn(false);
}

// --- Daftar tamu: dimuat sekali, difilter client-side ---
var guestsCache = [], guestsSig = '', pendingExpanded = false;

async function loadGuests(){
  try{
    var res = await fetch('/api/checkin_guests?inv_id=' + INV_ID + '&token=' + encodeURIComponent(CHECKIN_TOKEN));
    var d = await res.json();
    if(!d.success || !d.guests) return;
    var sig = JSON.stringify(d.guests);
    if(sig === guestsSig) return; // tidak ada perubahan: jangan render ulang
    guestsSig = sig;
    guestsCache = d.guests;
    renderGuestSearch();
    renderPending();
  }catch(e){}
}

function guestRow(g){
  var done = !!g.checked_in_at;
  var badge = done
    ? '<span class="badge in">Hadir</span><span class="gtime">' + esc(formatEventTime(g.checked_in_at)) + (g.companions > 0 ? ' • +' + g.companions : '') + '</span>'
    : '<span class="badge out">Belum</span><button class="mini" type="button" onclick="doCheckin(\'' + esc(g.qr_code || '') + '\')">Check-in</button>';
  return '<div class="guest-item"><span class="gname">' + esc(g.nama) + '</span>' + badge + '</div>';
}

function renderGuestSearch(){
  var q = (document.getElementById('guest-search').value || '').trim().toLowerCase();
  var list = guestsCache.filter(function(g){ return !q || String(g.nama).toLowerCase().indexOf(q) !== -1; });
  var box = document.getElementById('guest-list');
  if(!guestsCache.length){
    box.innerHTML = '<div class="empty">Belum ada tamu.</div>';
  }else if(!list.length){
    box.innerHTML = '<div class="empty">Tidak ada tamu bernama "' + esc(q) + '".</div>';
  }else{
    box.innerHTML = list.slice(0, 50).map(guestRow).join('');
  }
  document.getElementById('guest-count').textContent = guestsCache.length
    ? 'Menampilkan ' + Math.min(list.length, 50) + ' dari ' + list.length + ' hasil • ' + guestsCache.length + ' tamu'
    : '';
}

function renderPending(){
  var pending = guestsCache.filter(function(g){ return !g.checked_in_at; });
  document.getElementById('pending-count').textContent = pending.length + ' tamu';
  var box = document.getElementById('pending-list');
  if(!pending.length){
    box.innerHTML = '<div class="empty">Semua tamu sudah hadir.</div>';
  }else{
    var shown = pendingExpanded ? pending : pending.slice(0, 20);
    box.innerHTML = shown.map(function(g){
      return '<div class="guest-item"><span class="gname">' + esc(g.nama) + '</span></div>';
    }).join('');
  }
  var more = document.getElementById('btn-pending-more');
  if(pending.length > 20){
    more.style.display = 'block';
    more.textContent = pendingExpanded ? 'Tampilkan lebih sedikit' : 'Tampilkan semua (' + pending.length + ' tamu)';
  }else{
    more.style.display = 'none';
  }
}

function togglePending(){
  pendingExpanded = !pendingExpanded;
  renderPending();
}

async function refreshStats(){
  try{
    var res = await fetch('/api/checkin_stats?inv_id=' + INV_ID + '&token=' + encodeURIComponent(CHECKIN_TOKEN));
    var d = await res.json();
    if(!d.success) return;
    document.getElementById('st-total').textContent = d.total;
    document.getElementById('st-in').textContent = d.checked_in;
    document.getElementById('st-out').textContent = d.total - d.checked_in;
    var pct = d.total ? Math.round(d.checked_in / d.total * 100) : 0;
    document.getElementById('prog-fill').style.width = pct + '%';
    document.getElementById('prog-lbl').innerHTML = '<span class="pct">' + pct + '%</span> (' + d.checked_in + ' dari ' + d.total + ' tamu sudah hadir)';
    var tp = (typeof d.total_people === 'number') ? d.total_people : d.checked_in;
    document.getElementById('people-lbl').textContent = 'Total orang hadir: ' + tp + ' orang' + (tp > d.checked_in ? ' (termasuk ' + (tp - d.checked_in) + ' pendamping)' : '');
    document.getElementById('recent').innerHTML = d.recent.length
      ? d.recent.map(function(r,i){
          var meta = [];
          if(r.companions > 0) meta.push('+' + r.companions + ' pendamping');
          if(r.checked_in_by) meta.push('Petugas: ' + r.checked_in_by);
          return '<div class="recent-item"><span><b style="color:var(--gen-accent);margin-right:6px">' + (i+1) + '.</b>' + esc(r.nama) + (meta.length ? '<div class="recent-meta">' + esc(meta.join(' • ')) + '</div>' : '') + '</span><small>' + esc(formatEventTime(r.checked_in_at)) + '</small></div>';
        }).join('')
      : '<div class="empty">Belum ada check-in.</div>';
  }catch(e){}
}

// Polling tiap 5 detik saat halaman aktif (tab tersembunyi dijeda).
function tick(){
  if(document.hidden) return;
  refreshStats();
  loadGuests();
}
refreshStats();
loadGuests();
setInterval(tick, 5000);
</script>
</body>
</html>
