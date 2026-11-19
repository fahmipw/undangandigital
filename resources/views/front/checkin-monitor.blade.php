<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Monitor Check-in — {{ $title }}</title>
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
// Turunan kontras (WCAG >=4,5:1, dihitung dari luminansi — tanpa mengubah
// entri $genPalettes, jadi generator.blade.php tak ikut berubah):
// --gen-accent-text (judul kartu), --gen-top-bg (header), --gen-btn-*
// (teks + gradien tombol emas), --gen-amber (stat Belum).
$genRatio = function($a, $b) use ($genLum){
    $l1 = $genLum($a); $l2 = $genLum($b);
    return (max($l1, $l2) + 0.05) / (min($l1, $l2) + 0.05);
};
$genHex = function($r, $g, $b){
    return sprintf('#%02x%02x%02x', max(0, min(255, (int)round($r))), max(0, min(255, (int)round($g))), max(0, min(255, (int)round($b))));
};
$genMoveAway = function($fg, $bg) use ($genLum, $genRatio, $genHex){
    if($genRatio($fg, $bg) >= 4.5) return [$fg, 0];
    $r = hexdec(substr($fg, 1, 2)); $g = hexdec(substr($fg, 3, 2)); $b = hexdec(substr($fg, 5, 2));
    $toWhite = $genLum($fg) > $genLum($bg);
    $out = $fg;
    for($step = 1; $step <= 40; $step++){
        $t = $step * 0.03;
        if($toWhite){ $cr = $r + (255 - $r) * $t; $cg = $g + (255 - $g) * $t; $cb = $b + (255 - $b) * $t; }
        else { $cr = $r * (1 - $t); $cg = $g * (1 - $t); $cb = $b * (1 - $t); }
        $out = $genHex($cr, $cg, $cb);
        if($genRatio($out, $bg) >= 4.5) return [$out, $step];
    }
    return [$out, 40];
};
// Teks aksen: lawan latar terburuk (card & bg).
$genAccentText = $gen['accent'];
if($genRatio($genAccentText, $gen['card']) <= $genRatio($genAccentText, $gen['bg'])){
    list($genAccentText) = $genMoveAway($genAccentText, $gen['card']);
    list($genAccentText) = $genMoveAway($genAccentText, $gen['bg']);
} else {
    list($genAccentText) = $genMoveAway($genAccentText, $gen['bg']);
    list($genAccentText) = $genMoveAway($genAccentText, $gen['card']);
}
// Header: latar digeser sampai teks onAccent lolos (hanya bila perlu).
list($genTopBg) = $genMoveAway($gen['accent'], $gen['onAccent']);
// Tombol emas: pilih teks (onAccent / lawannya) dengan geseran minimal.
$genBtnPick = null;
foreach([$gen['onAccent'], $gen['onAccent'] === '#ffffff' ? '#1b1c1a' : '#ffffff'] as $txt){
    list($bs, $s1) = $genMoveAway($gen['accent'], $txt);
    list($be, $s2) = $genMoveAway($gen['accentDark'], $txt);
    if($genBtnPick === null || $s1 + $s2 < $genBtnPick[0] || ($s1 + $s2 === $genBtnPick[0] && $txt === $gen['onAccent'])){
        $genBtnPick = [$s1 + $s2, $txt, $bs, $be];
    }
}
$genBtnText = $genBtnPick[1]; $genBtnStart = $genBtnPick[2]; $genBtnEnd = $genBtnPick[3];
// Amber stat Belum di atas kartu (cermin skema hijau: terang/gelap).
$genAmber = $genLum($gen['card']) < 0.25 ? '#fbbf24' : '#92400e';
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
    --gen-accent-text:{{ $genAccentText }};
    --gen-top-bg:{{ $genTopBg }};
    --gen-btn-text:{{ $genBtnText }};
    --gen-btn-start:{{ $genBtnStart }};
    --gen-btn-end:{{ $genBtnEnd }};
    --gen-amber:{{ $genAmber }};
  }
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:-apple-system,'Segoe UI',Roboto,sans-serif;background:var(--gen-bg);color:var(--gen-ink);min-height:100vh;padding-bottom:40px}
  .top{background:var(--gen-top-bg);color:var(--gen-on-accent);padding:22px 18px;text-align:center}
  .top small{letter-spacing:.25em;font-size:10px;text-transform:uppercase}
  .top h1{font-size:20px;margin-top:6px}
  .topline{display:flex;justify-content:center;align-items:baseline;gap:6px;flex-wrap:wrap;font-size:13px;margin-top:6px;font-variant-numeric:tabular-nums}
  .topline .clock b{font-weight:700}
  .topline .sep{opacity:.6}
  .top-actions{margin-top:12px;display:flex;gap:8px;justify-content:center;flex-wrap:wrap}
  .top-pill{display:inline-block;background:transparent;color:inherit;border:1px solid currentColor;border-radius:999px;padding:6px 12px;font-size:12px;cursor:pointer;text-decoration:none}
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
  .recent-item{display:flex;justify-content:space-between;gap:8px;padding:10px 2px;border-bottom:1px solid var(--gen-border);font-size:14px}
  .recent-item:last-child{border-bottom:none}
  .recent-item small{color:var(--gen-inkSoft);font-size:11px;white-space:nowrap}
  .empty{color:var(--gen-inkSoft);font-style:italic;font-size:13px;text-align:center;padding:12px}
  .people-lbl{font-size:12px;color:var(--gen-inkSoft);text-align:center;margin-top:4px}
  .recent-meta{font-size:11px;color:var(--gen-inkSoft);margin-top:2px}
</style>
</head>
<body>
<div class="top">
  <small>Monitor Check-in</small>
  <h1>{{ $title }}</h1>
  <div class="topline"><span id="ev-date"></span><span class="sep">&bull;</span><span class="clock" id="clock">—</span></div>
  <div class="top-actions">
    <a class="top-pill" href="/checkin/{{ $slug }}/{{ $token }}">Kembali ke Check-in Panitia</a>
  </div>
</div>
<div class="wrap">
  <div class="stats">
    <div class="stat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--gen-accent)"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg><b id="st-total">–</b><span>Total Tamu</span></div>
    <div class="stat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--gen-pct)"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg><b id="st-in" style="color:var(--gen-pct)">–</b><span>Hadir</span></div>
    <div class="stat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--gen-amber)"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><b id="st-out" style="color:var(--gen-amber)">–</b><span>Belum</span></div>
  </div>
  <div class="progress">
    <div class="bar"><div class="fill" id="prog-fill"></div></div>
    <div class="lbl" id="prog-lbl">—</div>
    <div class="people-lbl" id="people-lbl"></div>
  </div>

  <div class="card">
    <h3>Baru Saja Hadir</h3>
    <div id="recent"><div class="empty">Belum ada check-in.</div></div>
  </div>
</div>

<script>
var INV_ID = {{ (int)$inv_id }};
// Token check-in dari URL halaman (sudah lolos checkinMonitorPage());
// dikirim di tiap request API dan dicocokkan server dengan
// settings.checkin_token.
var CHECKIN_TOKEN = "{{ $token }}";
// Zona waktu acara dari Data Utama (WIB/WITA/WIT, default WIB).
var EVENT_TZ_LABEL = "{{ $event_timezone ?? 'WIB' }}";
var EVENT_TZ_IANA = {WIB: 'Asia/Jakarta', WITA: 'Asia/Makassar', WIT: 'Asia/Jayapura'}[EVENT_TZ_LABEL] || 'Asia/Jakarta';
var EVENT_DATE = "{{ $event_date ?? '' }}";

function esc(s){return String(s==null?'':s).replace(/[&<>"']/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];});}

// Server menyimpan waktu dalam UTC; string tanpa zona harus dibaca sebagai
// UTC (bukan waktu lokal) lalu ditampilkan dalam zona waktu acara.
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
  var timeStr = now.toLocaleTimeString('id-ID', {timeZone:EVENT_TZ_IANA, hour:'2-digit', minute:'2-digit', second:'2-digit', hour12:false});
  document.getElementById('clock').innerHTML = '<b>' + esc(timeStr) + ' ' + esc(EVENT_TZ_LABEL) + '</b>';
}
tickClock();
setInterval(tickClock, 1000);

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
          return '<div class="recent-item"><span><b style="color:var(--gen-accent-text);margin-right:6px">' + (i+1) + '.</b>' + esc(r.nama) + (meta.length ? '<div class="recent-meta">' + esc(meta.join(' • ')) + '</div>' : '') + '</span><small>' + esc(formatEventTime(r.checked_in_at)) + '</small></div>';
        }).join('')
      : '<div class="empty">Belum ada check-in.</div>';
  }catch(e){}
}

// Polling tiap 5 detik saat halaman aktif (tab tersembunyi dijeda).
function tick(){
  if(document.hidden) return;
  refreshStats();
}
refreshStats();
setInterval(tick, 5000);
</script>
</body>
</html>
