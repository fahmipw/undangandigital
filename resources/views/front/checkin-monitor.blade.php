<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Monitor Check-in — {{ $title }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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

// Monogram segel + foto slideshow (welcome screen monitor).
$monParts = array_map('trim', explode('&', $title));
$coupleA = $monParts[0] ?? '';
$coupleB = isset($monParts[1]) ? $monParts[1] : '';
$monogram = ($coupleB !== '') ? mb_substr($coupleA, 0, 1) . '&' . mb_substr($coupleB, 0, 1) : mb_substr($title, 0, 2);
$monPhotos = [];
foreach (($photos ?? []) as $mp) {
    $mp = trim((string) $mp);
    if ($mp === '') continue;
    $monPhotos[] = (str_starts_with($mp, 'http') || str_starts_with($mp, '/')) ? $mp : asset(ltrim($mp, '/'));
}
$monPhotosJson = json_encode(array_values(array_unique($monPhotos)));

// Monogram segel + foto slideshow (welcome screen monitor).
$monParts = array_map('trim', explode('&', $title));
$coupleA = $monParts[0] ?? '';
$coupleB = isset($monParts[1]) ? $monParts[1] : '';
$monogram = ($coupleB !== '') ? mb_substr($coupleA, 0, 1) . '&' . mb_substr($coupleB, 0, 1) : mb_substr($title, 0, 2);
$monPhotos = [];
foreach (($photos ?? []) as $mp) {
    $mp = trim((string) $mp);
    if ($mp === '') continue;
    $monPhotos[] = (str_starts_with($mp, 'http') || str_starts_with($mp, '/')) ? $mp : asset(ltrim($mp, '/'));
}
$monPhotosJson = json_encode(array_values(array_unique($monPhotos)), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
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
  html,body{height:100%}
  body{font-family:'Plus Jakarta Sans',-apple-system,'Segoe UI',Roboto,sans-serif;background:linear-gradient(160deg,var(--gen-bg),color-mix(in srgb,var(--gen-bg) 55%,#000));color:var(--gen-ink);height:100vh;height:100svh;width:100vw;overflow:hidden;position:relative;user-select:none}
  .hidden{display:none!important}
  /* --- latar slideshow foto galeri (bila ada) --- */
  #slideshow-layer{position:absolute;inset:0;z-index:0;overflow:hidden}
  .bg-slide{position:absolute;inset:0;background-size:cover;background-position:center;opacity:0;transform:scale(1.05);transition:opacity 1.8s ease-in-out,transform 10s ease-out}
  .bg-slide.on{opacity:1;transform:scale(1.08)}
  .bg-overlay{position:absolute;inset:0;background:linear-gradient(to bottom,rgba(18,12,7,.88),rgba(22,15,9,.8) 50%,rgba(14,9,5,.95))}
  /* --- bingkai emas tipis + ornamen sudut --- */
  .frame{position:absolute;inset:12px;border:1px solid color-mix(in srgb,var(--gen-accent) 38%,transparent);pointer-events:none;z-index:10;display:flex;flex-direction:column;justify-content:space-between;padding:14px 16px 64px}
  @media(min-width:640px){.frame{inset:24px;padding:24px 28px 68px}}
  @media(min-width:1024px){.frame{inset:36px;padding:32px 32px 76px}}
  .corner{position:absolute;width:clamp(14px,1.8vw,22px);height:clamp(14px,1.8vw,22px);border-color:color-mix(in srgb,var(--gen-accent) 55%,#fff)}
  .corner.tl{top:-1px;left:-1px;border-top:2px solid;border-left:2px solid}
  .corner.tr{top:-1px;right:-1px;border-top:2px solid;border-right:2px solid}
  .corner.bl{bottom:-1px;left:-1px;border-bottom:2px solid;border-left:2px solid}
  .corner.br{bottom:-1px;right:-1px;border-bottom:2px solid;border-right:2px solid}
  .frame-head{position:relative;display:flex;align-items:center;justify-content:center;min-height:28px;color:color-mix(in srgb,var(--gen-accent) 55%,#fff);font-size:10px;letter-spacing:.25em;text-transform:uppercase;font-weight:500}
  @media(min-width:640px){.frame-head{font-size:12px}}
  @media(min-width:1024px){.frame-head{font-size:14px}}
  .frame-head .live{display:flex;align-items:center;gap:8px}
  .frame-head .dot{width:8px;height:8px;border-radius:50%;background:#34d399;animation:pulse 2s infinite;flex-shrink:0}
  @keyframes pulse{0%,100%{opacity:1}50%{opacity:.35}}
  #live-clock{position:absolute;right:0;font-family:'Cormorant Garamond',Georgia,serif;letter-spacing:.18em;background:rgba(0,0,0,.3);padding:4px 12px;border-radius:999px;border:1px solid color-mix(in srgb,var(--gen-accent) 30%,transparent);color:#fff}
  @media(max-width:639px){#live-clock{display:none}}
  .frame-foot{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:8px;color:color-mix(in srgb,var(--gen-accent) 55%,#fff);font-size:10px;letter-spacing:.2em;text-transform:uppercase}
  @media(min-width:640px){.frame-foot{font-size:12px}}
  @media(min-width:1024px){.frame-foot{font-size:14px}}
  .frame-foot .couple{font-family:'Cormorant Garamond',Georgia,serif;font-style:italic;letter-spacing:.12em;text-transform:none;font-size:14px;color:color-mix(in srgb,var(--gen-accent) 45%,#fff)}
  @media(min-width:640px){.frame-foot .couple{font-size:16px}}
  .frame-foot .right{text-align:right;line-height:1.5}
  #ev-date{font-family:'Cormorant Garamond',Georgia,serif}
  #queue-pill{display:none;align-items:center;gap:8px;padding:4px 14px;border-radius:999px;background:var(--gen-accent);color:var(--gen-on-accent);font-size:10px;letter-spacing:.08em;font-weight:600;border:1px solid color-mix(in srgb,var(--gen-accent) 45%,#fff)}
  #queue-pill.show{display:inline-flex}
  #queue-pill .qdot{width:8px;height:8px;border-radius:50%;background:#fcd34d;animation:pulse 1s infinite}
  /* --- viewport utama --- */
  main{position:relative;z-index:20;height:100%;width:100%;display:flex;align-items:center;justify-content:center;padding:20px 20px 40px}
  @media(min-width:640px){main{padding:40px 40px 64px}}
  @media(min-width:1024px){main{padding:40px 64px}}
  /* --- STATE A: standby --- */
  #view-standby{text-align:center;max-width:900px;margin:0 auto}
  #view-standby .eyebrow{color:color-mix(in srgb,var(--gen-accent) 45%,#fff);letter-spacing:.28em;text-transform:uppercase;font-size:11px;font-weight:500;margin-bottom:12px}
  @media(min-width:640px){#view-standby .eyebrow{font-size:14px;letter-spacing:.38em;margin-bottom:16px}}
  @media(min-width:1024px){#view-standby .eyebrow{font-size:16px}}
  #view-standby h1{font-family:'Cormorant Garamond',Georgia,serif;font-weight:600;color:#fff;font-size:clamp(2.85rem,7.5vw,7.25rem);line-height:1;letter-spacing:.02em;text-shadow:0 2px 18px rgba(0,0,0,.45)}
  #view-standby h1 .amp{font-style:italic;font-weight:400;color:color-mix(in srgb,var(--gen-accent) 45%,#fff);margin:0 .1em}
  .divider{display:flex;align-items:center;justify-content:center;gap:16px;margin:20px 0}
  @media(min-width:640px){.divider{margin:28px 0}}
  .divider .line{width:56px;height:1px;background:linear-gradient(to right,transparent,color-mix(in srgb,var(--gen-accent) 60%,#fff))}
  .divider .line.r{background:linear-gradient(to left,transparent,color-mix(in srgb,var(--gen-accent) 60%,#fff))}
  .divider .dia{width:8px;height:8px;transform:rotate(45deg);border:1px solid color-mix(in srgb,var(--gen-accent) 60%,#fff)}
  #view-standby .welcome-text{color:rgba(255,255,255,.88);font-size:12px;font-weight:300;line-height:1.7;max-width:640px;margin:0 auto;padding:0 8px}
  @media(min-width:640px){#view-standby .welcome-text{font-size:16px}}
  @media(min-width:1024px){#view-standby .welcome-text{font-size:20px}}
  .scan-pill{margin-top:24px;display:inline-flex;align-items:center;gap:10px;padding:8px 16px;border-radius:999px;background:rgba(255,255,255,.05);border:1px solid color-mix(in srgb,var(--gen-accent) 40%,transparent);color:color-mix(in srgb,var(--gen-accent) 45%,#fff);font-size:10px;letter-spacing:.2em;text-transform:uppercase}
  @media(min-width:640px){.scan-pill{margin-top:40px;padding:10px 24px;font-size:12px}}
  @media(min-width:1024px){.scan-pill{font-size:14px}}
  .scan-pill svg{width:16px;height:16px;animation:pulse 2s infinite;flex-shrink:0}
  @media(min-width:640px){.scan-pill svg{width:20px;height:20px}}
  /* --- STATE B: kartu sambutan --- */
  #view-welcome{width:100%;max-width:900px;margin:0 auto;position:relative;padding-top:32px}
  @media(min-width:640px){#view-welcome{padding-top:40px}}
  .bokeh{pointer-events:none;position:absolute;inset:-48px;z-index:0;overflow:visible}
  .bokeh span{position:absolute;border-radius:50%;animation:floatBokeh 4.5s ease-in-out infinite}
  @keyframes floatBokeh{0%,100%{transform:translateY(0) scale(1);opacity:.35}50%{transform:translateY(-18px) scale(1.2);opacity:.85}}
  #welcome-card{position:relative;z-index:10;background:var(--gen-card);color:var(--gen-ink);border:1px solid color-mix(in srgb,var(--gen-accent) 55%,transparent);border-radius:44px 44px 28px 28px;box-shadow:0 32px 90px -10px rgba(0,0,0,.82),0 0 50px color-mix(in srgb,var(--gen-accent) 18%,transparent)}
  @media(min-width:640px){#welcome-card{border-radius:60px 60px 36px 36px}}
  .crest{position:absolute;top:-32px;left:50%;transform:translateX(-50%);z-index:30;display:flex;align-items:center;justify-content:center;gap:8px}
  @media(min-width:640px){.crest{top:-40px;gap:12px}}
  .crest svg{width:48px;height:32px;color:color-mix(in srgb,var(--gen-accent) 45%,#fff);filter:drop-shadow(0 2px 4px rgba(0,0,0,.4))}
  @media(min-width:640px){.crest svg{width:64px;height:40px}}
  .crest svg.flip{transform:scaleX(-1)}
  .wax-seal{width:64px;height:64px;border-radius:50%;display:flex;align-items:center;justify-content:center;padding:6px;border:1px solid rgba(248,229,190,.8);background:radial-gradient(circle at 30% 26%,color-mix(in srgb,var(--gen-accent) 55%,#fff) 0%,var(--gen-accent) 46%,var(--gen-accent-dark) 90%,color-mix(in srgb,var(--gen-accent-dark) 80%,#000) 100%);box-shadow:0 12px 28px -4px rgba(54,36,11,.5),0 0 0 4px color-mix(in srgb,var(--gen-card) 85%,transparent),inset 0 2px 5px rgba(255,249,234,.75),inset 0 -3px 7px rgba(52,34,9,.6);flex-shrink:0}
  @media(min-width:640px){.wax-seal{width:80px;height:80px}}
  .wax-seal .inner{width:100%;height:100%;border-radius:50%;border:1px solid rgba(78,53,16,.35);display:flex;align-items:center;justify-content:center}
  .wax-seal span{font-family:'Cinzel',Georgia,serif;font-weight:700;color:var(--gen-on-accent);font-size:16px;letter-spacing:-.02em;text-shadow:0 1px 2px rgba(58,36,8,.8)}
  @media(min-width:640px){.wax-seal span{font-size:20px}}
  @media(min-width:1024px){.wax-seal span{font-size:24px}}
  .card-clip{position:relative;overflow:hidden;border-radius:44px 44px 28px 28px;padding:40px 24px 56px;text-align:center;max-height:85vh;display:flex;flex-direction:column;justify-content:center}
  @media(min-width:640px){.card-clip{border-radius:60px 60px 36px 36px;padding:52px 48px 60px}}
  @media(min-width:1024px){.card-clip{padding:60px 64px}}
  .foil{pointer-events:none;position:absolute;inset:0;overflow:hidden;z-index:0}
  .foil div{width:33%;height:100%;background:linear-gradient(to right,transparent,rgba(255,255,255,.65),transparent);animation:foilSweep 4.2s cubic-bezier(.25,1,.5,1) .3s infinite}
  @keyframes foilSweep{0%{transform:translateX(-160%) skewX(-24deg)}50%,100%{transform:translateX(260%) skewX(-24deg)}}
  .watermark{pointer-events:none;position:absolute;inset:0;display:flex;align-items:center;justify-content:center;z-index:0;overflow:hidden}
  .watermark span{font-family:'Cinzel',Georgia,serif;font-weight:600;font-size:clamp(7rem,17vw,14rem);letter-spacing:.1em;line-height:1;color:color-mix(in srgb,var(--gen-accent) 6%,transparent)}
  .hairline{pointer-events:none;position:absolute;inset:10px;border:1px solid color-mix(in srgb,var(--gen-accent) 40%,transparent);border-radius:36px 36px 22px 22px;z-index:10}
  @media(min-width:640px){.hairline{inset:16px;border-radius:48px 48px 28px 28px}}
  .hairline div{position:absolute;inset:4px;border:1px solid color-mix(in srgb,var(--gen-accent) 22%,transparent);border-radius:32px 32px 18px 18px}
  @media(min-width:640px){.hairline div{border-radius:44px 44px 24px 24px}}
  .filigree{pointer-events:none;position:absolute;width:32px;height:32px;color:color-mix(in srgb,var(--gen-accent) 65%,transparent);z-index:10}
  @media(min-width:640px){.filigree{width:44px;height:44px}}
  .filigree.tl{top:16px;left:16px}
  .filigree.tr{top:16px;right:16px;transform:rotate(90deg)}
  .filigree.bl{bottom:16px;left:16px;transform:rotate(-90deg)}
  .filigree.br{bottom:16px;right:16px;transform:rotate(180deg)}
  @media(min-width:640px){.filigree.tl,.filigree.tr{top:24px}.filigree.tl,.filigree.bl{left:24px}.filigree.tr,.filigree.br{right:24px}.filigree.bl,.filigree.br{bottom:24px}}
  .card-body{position:relative;z-index:20;padding-top:12px}
  @media(min-width:640px){.card-body{padding-top:16px}}
  .card-eyebrow{display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:4px}
  @media(min-width:640px){.card-eyebrow{gap:16px;margin-bottom:8px}}
  .card-eyebrow .ln{width:32px;height:1px;background:color-mix(in srgb,var(--gen-accent) 50%,transparent)}
  @media(min-width:640px){.card-eyebrow .ln{width:48px}}
  .card-eyebrow p{font-family:'Cinzel',Georgia,serif;color:var(--gen-accent-text);text-transform:uppercase;letter-spacing:.2em;font-size:10px;font-weight:600}
  @media(min-width:640px){.card-eyebrow p{letter-spacing:.3em;font-size:12px}}
  @media(min-width:1024px){.card-eyebrow p{font-size:14px}}
  #guest-name{font-family:'Cormorant Garamond',Georgia,serif;font-weight:700;font-size:clamp(1.9rem,4.5vw,4.25rem);line-height:1.12;margin:6px 0 10px;padding:0 12px;word-break:break-word;background:linear-gradient(135deg,var(--gen-ink) 0%,var(--gen-accent-text) 52%,var(--gen-ink) 100%);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent}
  @media(min-width:640px){#guest-name{margin:10px 0}}
  .card-divider{display:flex;align-items:center;justify-content:center;gap:12px;margin:10px 0}
  @media(min-width:640px){.card-divider{margin:16px 0}}
  .card-divider .ln{width:56px;height:1px;background:linear-gradient(to right,transparent,color-mix(in srgb,var(--gen-accent) 85%,transparent))}
  @media(min-width:640px){.card-divider .ln{width:112px}}
  .card-divider .ln.r{background:linear-gradient(to left,transparent,color-mix(in srgb,var(--gen-accent) 85%,transparent))}
  .card-divider svg{width:16px;height:16px;color:var(--gen-accent-text)}
  @media(min-width:640px){.card-divider svg{width:20px;height:20px}}
  .guest-meta{display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:8px;color:var(--gen-ink-soft);font-size:14px}
  @media(min-width:640px){.guest-meta{gap:12px;font-size:18px}}
  @media(min-width:1024px){.guest-meta{font-size:20px}}
  #guest-note{font-family:'Cormorant Garamond',Georgia,serif;font-style:italic;font-size:16px;color:var(--gen-ink-soft)}
  @media(min-width:640px){#guest-note{font-size:20px}}
  @media(min-width:1024px){#guest-note{font-size:24px}}
  .guest-meta .sep{color:color-mix(in srgb,var(--gen-accent) 70%,transparent)}
  #guest-pax{display:inline-flex;align-items:center;gap:6px;font-weight:500;color:var(--gen-accent-text);background:color-mix(in srgb,var(--gen-accent) 8%,transparent);border:1px solid color-mix(in srgb,var(--gen-accent) 30%,transparent);padding:2px 12px;border-radius:999px;font-size:12px}
  @media(min-width:640px){#guest-pax{padding:4px 16px;font-size:14px}}
  @media(min-width:1024px){#guest-pax{font-size:16px}}
  #guest-pax svg{width:14px;height:14px;color:var(--gen-accent-text)}
  @media(min-width:640px){#guest-pax svg{width:16px;height:16px}}
  .card-quote{margin-top:12px;max-width:640px;margin-left:auto;margin-right:auto;padding:0 8px;font-family:'Cormorant Garamond',Georgia,serif;font-style:italic;color:var(--gen-ink-soft);font-size:14px;line-height:1.6}
  @media(min-width:640px){.card-quote{margin-top:16px;font-size:18px}}
  @media(min-width:1024px){.card-quote{font-size:19px}}
  .card-sign{margin-top:16px;padding-top:12px;border-top:1px solid color-mix(in srgb,var(--gen-accent) 20%,transparent);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:2px;color:var(--gen-ink-soft);font-size:11px}
  @media(min-width:640px){.card-sign{margin-top:24px;padding-top:14px;font-size:14px}}
  .card-sign .lbl{letter-spacing:.14em;text-transform:uppercase;font-size:10px}
  @media(min-width:640px){.card-sign .lbl{font-size:12px}}
  .card-sign .names{font-family:'Cormorant Garamond',Georgia,serif;font-style:italic;font-size:18px;color:var(--gen-ink);font-weight:600}
  @media(min-width:640px){.card-sign .names{font-size:24px}}
  #display-progress-bar{position:absolute;bottom:0;left:0;right:0;height:6px;background:linear-gradient(to right,var(--gen-accent),var(--gen-accent-dark),var(--gen-accent));z-index:20}
  @keyframes modalReveal{0%{opacity:0;transform:translateY(28px) scale(.94)}100%{opacity:1;transform:translateY(0) scale(1)}}
  .animate-modal-in{animation:modalReveal .85s cubic-bezier(.16,1,.3,1) forwards}
  @keyframes modalDismiss{0%{opacity:1;transform:translateY(0) scale(1)}100%{opacity:0;transform:translateY(-18px) scale(.96)}}
  .animate-modal-out{animation:modalDismiss .45s cubic-bezier(.4,0,1,1) forwards}
  @keyframes itemFadeUp{0%{opacity:0;transform:translateY(14px)}100%{opacity:1;transform:translateY(0)}}
  .stagger-1{opacity:0;animation:itemFadeUp .65s cubic-bezier(.16,1,.3,1) .12s forwards}
  .stagger-2{opacity:0;animation:itemFadeUp .65s cubic-bezier(.16,1,.3,1) .24s forwards}
  .stagger-3{opacity:0;animation:itemFadeUp .7s cubic-bezier(.16,1,.3,1) .34s forwards}
  .stagger-4{opacity:0;animation:itemFadeUp .65s cubic-bezier(.16,1,.3,1) .46s forwards}
  .stagger-5{opacity:0;animation:itemFadeUp .65s cubic-bezier(.16,1,.3,1) .56s forwards}
  /* --- ikon fullscreen (pojok kanan atas) --- */
  #btn-fs{position:fixed;top:12px;right:12px;z-index:60;width:38px;height:38px;border-radius:50%;background:rgba(0,0,0,.35);border:1px solid rgba(255,255,255,.25);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;opacity:.7;transition:opacity .3s,background .3s}
  #btn-fs:hover{opacity:1;background:rgba(0,0,0,.55)}
  @media (prefers-reduced-motion: reduce){
    .bg-slide,.foil div,.bokeh span,.frame-head .dot,.scan-pill svg,#queue-pill .qdot{animation:none!important;transition:none!important}
    .animate-modal-in,.animate-modal-out,.stagger-1,.stagger-2,.stagger-3,.stagger-4,.stagger-5{animation:none!important;opacity:1!important}
  }
</style>
</head>
<body>
<!-- Ikon fullscreen (pojok kanan atas) -->
<button type="button" id="btn-fs" onclick="toggleFullscreen()" title="Fullscreen" aria-label="Fullscreen"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9V4h5"/><path d="M20 9V4h-5"/><path d="M4 15v5h5"/><path d="M20 15v5h-5"/></svg></button>

<!-- 1. Latar slideshow foto galeri -->
<div id="slideshow-layer" class="hidden">
  <div class="bg-overlay"></div>
</div>

<!-- 2. Bingkai emas + jam live -->
<div class="frame">
  <div class="corner tl"></div><div class="corner tr"></div>
  <div class="corner bl"></div><div class="corner br"></div>
  <div class="frame-head">
    <div class="live"><span class="dot"></span><span>The Wedding Reception</span></div>
    <span id="live-clock">—</span>
  </div>
  <div class="frame-foot">
    <span class="couple">{{ $coupleA }} &amp; {{ $coupleB }}</span>
    <div id="queue-pill"><span class="qdot"></span><span id="queue-count-text">+1 Tamu dalam Antrean</span></div>
    <span class="right"><span id="ev-date"></span></span>
  </div>
</div>

<!-- 3. Viewport utama -->
<main>
  <!-- STATE A: standby -->
  <section id="view-standby">
    <p class="eyebrow">Selamat Datang di Resepsi Pernikahan</p>
    <h1>{{ $coupleA }} <span class="amp">&amp;</span> {{ $coupleB }}</h1>
    <div class="divider"><div class="line"></div><div class="dia"></div><div class="line r"></div></div>
    <p class="welcome-text">Merupakan suatu kehormatan dan kebahagiaan bagi kami atas kehadiran Bapak/Ibu/Saudara/i untuk memberikan doa restu kepada kedua mempelai.</p>
    <div class="scan-pill">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
      <span>Silakan Pindai QR Code Undangan Anda</span>
    </div>
  </section>

  <!-- STATE B: kartu sambutan tamu -->
  <section id="view-welcome" class="hidden">
    <div class="bokeh" aria-hidden="true">
      <span style="top:16px;left:32px;width:10px;height:10px;background:color-mix(in srgb,var(--gen-accent) 60%,#fff);animation-delay:0s"></span>
      <span style="top:48px;right:40px;width:12px;height:12px;background:color-mix(in srgb,var(--gen-accent) 50%,#fff);animation-delay:1.1s"></span>
      <span style="bottom:40px;left:48px;width:8px;height:8px;background:#fde68a;animation-delay:2.1s"></span>
      <span style="bottom:24px;right:56px;width:14px;height:14px;background:color-mix(in srgb,var(--gen-accent) 45%,#fff);animation-delay:.7s"></span>
      <span style="top:50%;left:-16px;width:8px;height:8px;background:color-mix(in srgb,var(--gen-accent) 65%,#fff);animation-delay:1.6s"></span>
      <span style="top:33%;right:-16px;width:8px;height:8px;background:color-mix(in srgb,var(--gen-accent) 65%,#fff);animation-delay:2.6s"></span>
    </div>
    <div id="welcome-card">
      <div class="crest">
        <svg class="flip" viewBox="0 0 64 40" fill="none"><path d="M58 32C44 30 30 22 18 10" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><path d="M46 28C43 22 38 19 33 20C36 25 41 28 46 28Z" fill="currentColor" fill-opacity="0.85"/><path d="M35 22C32 16 27 13 22 14C25 19 30 22 35 22Z" fill="currentColor" fill-opacity="0.85"/><path d="M24 15C21 9 16 6 11 7C14 12 19 15 24 15Z" fill="currentColor" fill-opacity="0.85"/><path d="M40 29C37 34 31 36 26 34C30 30 35 28 40 29Z" fill="currentColor" fill-opacity="0.65"/><path d="M29 22C26 27 20 29 15 27C19 23 24 21 29 22Z" fill="currentColor" fill-opacity="0.65"/></svg>
        <div class="wax-seal"><div class="inner"><span>{{ $monogram }}</span></div></div>
        <svg viewBox="0 0 64 40" fill="none"><path d="M58 32C44 30 30 22 18 10" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><path d="M46 28C43 22 38 19 33 20C36 25 41 28 46 28Z" fill="currentColor" fill-opacity="0.85"/><path d="M35 22C32 16 27 13 22 14C25 19 30 22 35 22Z" fill="currentColor" fill-opacity="0.85"/><path d="M24 15C21 9 16 6 11 7C14 12 19 15 24 15Z" fill="currentColor" fill-opacity="0.85"/><path d="M40 29C37 34 31 36 26 34C30 30 35 28 40 29Z" fill="currentColor" fill-opacity="0.65"/><path d="M29 22C26 27 20 29 15 27C19 23 24 21 29 22Z" fill="currentColor" fill-opacity="0.65"/></svg>
      </div>
      <div class="card-clip">
        <div class="foil"><div></div></div>
        <div class="watermark" aria-hidden="true"><span>{{ $monogram }}</span></div>
        <div class="hairline"><div></div></div>
        <svg class="filigree tl" viewBox="0 0 44 44" fill="none"><path d="M3 41V17C3 9.26801 9.26801 3 17 3H41" stroke="currentColor" stroke-width="1.2"/><path d="M8 35V19C8 12.9249 12.9249 8 19 8H35" stroke="currentColor" stroke-width="0.75" stroke-dasharray="2 2"/><path d="M15 15C19 15 21 12 21 9C18 9 15 11 15 15Z" fill="currentColor" fill-opacity="0.75"/><path d="M15 15C15 19 12 21 9 21C9 18 11 15 15 15Z" fill="currentColor" fill-opacity="0.75"/><circle cx="13.5" cy="13.5" r="1.8" fill="currentColor"/></svg>
        <svg class="filigree tr" viewBox="0 0 44 44" fill="none"><path d="M3 41V17C3 9.26801 9.26801 3 17 3H41" stroke="currentColor" stroke-width="1.2"/><path d="M8 35V19C8 12.9249 12.9249 8 19 8H35" stroke="currentColor" stroke-width="0.75" stroke-dasharray="2 2"/><path d="M15 15C19 15 21 12 21 9C18 9 15 11 15 15Z" fill="currentColor" fill-opacity="0.75"/><path d="M15 15C15 19 12 21 9 21C9 18 11 15 15 15Z" fill="currentColor" fill-opacity="0.75"/><circle cx="13.5" cy="13.5" r="1.8" fill="currentColor"/></svg>
        <svg class="filigree bl" viewBox="0 0 44 44" fill="none"><path d="M3 41V17C3 9.26801 9.26801 3 17 3H41" stroke="currentColor" stroke-width="1.2"/><path d="M8 35V19C8 12.9249 12.9249 8 19 8H35" stroke="currentColor" stroke-width="0.75" stroke-dasharray="2 2"/><path d="M15 15C19 15 21 12 21 9C18 9 15 11 15 15Z" fill="currentColor" fill-opacity="0.75"/><path d="M15 15C15 19 12 21 9 21C9 18 11 15 15 15Z" fill="currentColor" fill-opacity="0.75"/><circle cx="13.5" cy="13.5" r="1.8" fill="currentColor"/></svg>
        <svg class="filigree br" viewBox="0 0 44 44" fill="none"><path d="M3 41V17C3 9.26801 9.26801 3 17 3H41" stroke="currentColor" stroke-width="1.2"/><path d="M8 35V19C8 12.9249 12.9249 8 19 8H35" stroke="currentColor" stroke-width="0.75" stroke-dasharray="2 2"/><path d="M15 15C19 15 21 12 21 9C18 9 15 11 15 15Z" fill="currentColor" fill-opacity="0.75"/><path d="M15 15C15 19 12 21 9 21C9 18 11 15 15 15Z" fill="currentColor" fill-opacity="0.75"/><circle cx="13.5" cy="13.5" r="1.8" fill="currentColor"/></svg>
        <div class="card-body">
          <div class="stagger-1 card-eyebrow"><span class="ln"></span><p>Selamat Datang &amp; Kehormatan Bagi Kami</p><span class="ln"></span></div>
          <h2 id="guest-name" class="stagger-2">Tamu Undangan</h2>
          <div class="stagger-3 card-divider"><div class="ln"></div><svg viewBox="0 0 24 24" fill="none"><path d="M12 3L14.5 9.5L21 12L14.5 14.5L12 21L9.5 14.5L3 12L9.5 9.5L12 3Z" stroke="currentColor" stroke-width="1.2" fill="currentColor" fill-opacity="0.18"/></svg><div class="ln r"></div></div>
          <div class="stagger-4 guest-meta">
            <span id="guest-note">Tamu Kehormatan</span>
            <span class="sep" id="pax-sep">&bull;</span>
            <span id="guest-pax"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg><span>2 Orang Hadir</span></span>
          </div>
          <p class="stagger-4 card-quote">&ldquo;Terima kasih yang tulus atas kehadiran serta doa restu yang telah diberikan. Semoga langkah kami senantiasa diberkahi kebahagiaan, cinta, dan keberkahan.&rdquo;</p>
          <div class="stagger-5 card-sign"><span class="lbl">Dengan penuh rasa syukur &amp; cinta,</span><span class="names">{{ $coupleA }} &amp; {{ $coupleB }}</span></div>
        </div>
        <div id="display-progress-bar"></div>
      </div>
    </div>
  </section>
</main>

<script>
var INV_ID = {{ (int)$inv_id }};
// Token check-in dari URL halaman (sudah lolos checkinMonitorPage()).
var CHECKIN_TOKEN = "{{ $token }}";
// Zona waktu acara dari Data Utama (WIB/WITA/WIT, default WIB).
var EVENT_TZ_LABEL = "{{ $event_timezone ?? 'WIB' }}";
var EVENT_TZ_IANA = {WIB: 'Asia/Jakarta', WITA: 'Asia/Makassar', WIT: 'Asia/Jayapura'}[EVENT_TZ_LABEL] || 'Asia/Jakarta';
var EVENT_DATE = "{{ $event_date ?? '' }}";
// Foto slideshow latar (foto galeri; fallback preview + mempelai).
var MON_PHOTOS = {!! $monPhotosJson !!};

// Tanggal acara (YYYY-MM-DD dari Data Utama) dalam zona acara.
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
  document.getElementById('live-clock').textContent = timeStr + ' ' + EVENT_TZ_LABEL;
}
tickClock();
setInterval(tickClock, 1000);

// --- Slideshow latar: foto galeri, crossfade 7 detik + slow-zoom ---
var slides = [], activeSlideIndex = 0, slideTimer = null;
function initSlideshow(urls){
  var layer = document.getElementById('slideshow-layer');
  layer.querySelectorAll('.bg-slide').forEach(function(el){ el.remove(); });
  slides = []; activeSlideIndex = 0;
  if(slideTimer){ clearInterval(slideTimer); slideTimer = null; }
  if(!urls || !urls.length){ layer.classList.add('hidden'); return; }
  layer.classList.remove('hidden');
  urls.forEach(function(u, i){
    var d = document.createElement('div');
    d.className = 'bg-slide' + (i === 0 ? ' on' : '');
    d.style.backgroundImage = 'url("' + u + '")';
    layer.insertBefore(d, layer.firstChild);
    slides.push(d);
  });
  if(slides.length > 1){
    slideTimer = setInterval(function(){
      slides[activeSlideIndex].classList.remove('on');
      activeSlideIndex = (activeSlideIndex + 1) % slides.length;
      slides[activeSlideIndex].classList.add('on');
    }, 7000);
  }
}
initSlideshow(MON_PHOTOS);

// --- Chime sambutan (Web Audio, default mati; perlu ketukan tombol) ---
var soundEnabled = false, audioCtx = null;
function toggleSound(){
  soundEnabled = !soundEnabled;
  var icon = document.getElementById('sound-icon');
  var label = document.getElementById('sound-label');
  if(soundEnabled){
    try{
      if(!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      if(audioCtx.state === 'suspended') audioCtx.resume();
    }catch(e){}
    icon.textContent = '\uD83D\uDD14';
    label.textContent = 'Chime: On';
    playWelcomeChime();
  }else{
    icon.textContent = '\uD83D\uDD07';
    label.textContent = 'Chime: Off';
  }
}
function playWelcomeChime(){
  if(!soundEnabled || !audioCtx) return;
  try{
    var now = audioCtx.currentTime;
    var notes = [587.33, 880, 1174.66];
    notes.forEach(function(freq, idx){
      var osc = audioCtx.createOscillator();
      var gain = audioCtx.createGain();
      osc.type = 'sine';
      osc.frequency.setValueAtTime(freq, now + idx * 0.11);
      gain.gain.setValueAtTime(0.0001, now + idx * 0.11);
      gain.gain.exponentialRampToValueAtTime(0.08, now + idx * 0.11 + 0.04);
      gain.gain.exponentialRampToValueAtTime(0.0001, now + idx * 0.11 + 1.1);
      osc.connect(gain); gain.connect(audioCtx.destination);
      osc.start(now + idx * 0.11); osc.stop(now + idx * 0.11 + 1.15);
    });
  }catch(e){}
}

// --- Antrean sambutan: tiap tamu tampil 6 detik ---
var guestQueue = [];
var isDisplaying = false;
var DISPLAY_DURATION_MS = 6000;
var viewStandby = document.getElementById('view-standby');
var viewWelcome = document.getElementById('view-welcome');
var welcomeCard = document.getElementById('welcome-card');
var progressBar = document.getElementById('display-progress-bar');
var queuePill = document.getElementById('queue-pill');
var queueCountText = document.getElementById('queue-count-text');

function updateQueueIndicator(){
  if(guestQueue.length > 0){
    queueCountText.textContent = '+' + guestQueue.length + ' Tamu dalam Antrean';
    queuePill.classList.add('show');
  }else{
    queuePill.classList.remove('show');
  }
}
function restartStaggeredAnimations(){
  welcomeCard.querySelectorAll('.stagger-1, .stagger-2, .stagger-3, .stagger-4, .stagger-5').forEach(function(el){
    el.style.animation = 'none';
    void el.offsetWidth;
    el.style.animation = '';
  });
}
function enqueueGuest(guest){
  if(!guest || !guest.name) return;
  guestQueue.push(guest);
  updateQueueIndicator();
  processQueue();
}
function processQueue(){
  if(isDisplaying || guestQueue.length === 0) return;
  isDisplaying = true;
  var g = guestQueue.shift();
  updateQueueIndicator();
  playWelcomeChime();
  document.getElementById('guest-name').textContent = g.name;
  document.getElementById('guest-note').textContent = g.category || 'Tamu Kehormatan';
  var paxCount = 1 + (Number(g.companions) || 0);
  var paxPill = document.getElementById('guest-pax');
  var paxSep = document.getElementById('pax-sep');
  if(paxCount >= 3){
    paxPill.querySelector('span').textContent = 'Beserta Rombongan';
    paxPill.classList.remove('hidden'); paxSep.classList.remove('hidden');
  }else if(paxCount === 2){
    paxPill.querySelector('span').textContent = 'Beserta Pendamping';
    paxPill.classList.remove('hidden'); paxSep.classList.remove('hidden');
  }else{
    paxPill.classList.add('hidden'); paxSep.classList.add('hidden');
  }
  viewStandby.classList.add('hidden');
  viewWelcome.classList.remove('hidden');
  welcomeCard.classList.remove('animate-modal-out', 'animate-modal-in');
  void welcomeCard.offsetWidth;
  welcomeCard.classList.add('animate-modal-in');
  restartStaggeredAnimations();
  progressBar.style.transition = 'none';
  progressBar.style.width = '100%';
  void progressBar.offsetWidth;
  progressBar.style.transition = 'width ' + DISPLAY_DURATION_MS + 'ms linear';
  progressBar.style.width = '0%';
  setTimeout(function(){
    welcomeCard.classList.remove('animate-modal-in');
    welcomeCard.classList.add('animate-modal-out');
    setTimeout(function(){
      isDisplaying = false;
      if(guestQueue.length > 0){
        processQueue();
      }else{
        viewWelcome.classList.add('hidden');
        viewStandby.classList.remove('hidden');
      }
    }, 420);
  }, DISPLAY_DURATION_MS - 420);
}
// Pratinjau kartu tanpa scan sungguhan.
function testWelcome(){
  enqueueGuest({name: 'Tamu Undangan', companions: 0, category: null});
}

// --- Polling: kedatangan baru masuk antrean sambutan ---
var seenKeys = {};
var firstPoll = true;
function guestKey(r){ return (r.nama || '') + '|' + (r.checked_in_at || ''); }
function parseServerTime(s){
  if(!s) return null;
  var t = String(s).trim().replace(' ', 'T');
  if(!/[+-]\d{2}:?\d{2}$/.test(t) && t.slice(-1) !== 'Z') t += 'Z';
  var d = new Date(t);
  return isNaN(d.getTime()) ? null : d;
}
async function refreshStats(){
  try{
    var res = await fetch('/api/checkin_stats?inv_id=' + INV_ID + '&token=' + encodeURIComponent(CHECKIN_TOKEN));
    var d = await res.json();
    if(!d.success) return;
    var recent = d.recent || [];
    var fresh = [];
    recent.forEach(function(r){
      var k = guestKey(r);
      if(!seenKeys[k]){
        seenKeys[k] = true;
        fresh.push(r);
      }
    });
    // Muat pertama: jangan putar ulang riwayat; hanya tamu yang baru saja
    // scan (<=30 detik lalu) yang ikut ditampilkan sekali.
    if(firstPoll){
      firstPoll = false;
      var now = Date.now();
      var latest = null, latestTs = 0;
      fresh.forEach(function(r){
        var t = parseServerTime(r.checked_in_at);
        if(t && t.getTime() > latestTs){ latestTs = t.getTime(); latest = r; }
      });
      fresh = (latest && (now - latestTs) <= 30000) ? [latest] : [];
    }
    // recent dari API urut terbaru dulu; antrekan dari yang paling awal.
    fresh.reverse().forEach(function(r){
      enqueueGuest({name: r.nama, companions: r.companions || 0, category: r.category || null});
    });
  }catch(e){}
}
function tick(){
  if(document.hidden) return;
  refreshStats();
}
refreshStats();
setInterval(tick, 5000);

// --- Fullscreen + wake lock (ikon pojok kanan atas) ---
var wakeLock = null;
async function toggleFullscreen(){
  try{
    if(!document.fullscreenElement){
      await document.documentElement.requestFullscreen();
      if('wakeLock' in navigator) wakeLock = await navigator.wakeLock.request('screen');
    }else{
      await document.exitFullscreen();
      if(wakeLock){ await wakeLock.release(); wakeLock = null; }
    }
  }catch(err){}
}
</script>
</body>
</html>
