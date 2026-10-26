<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&family=Playfair+Display:wght@700&family=Instrument+Serif&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4E342E',
                        secondary: '#775a19',
                        'on-primary': '#ffffff',
                        'primary-container': '#FFDBCF',
                        'on-primary-container': '#380D00',
                        'secondary-container': '#F6E0BB',
                        'on-secondary-container': '#261A00',
                        'surface': '#FCF8F8',
                        'on-surface': '#1F1B1B',
                        'surface-container-low': '#F7F2F1',
                        'surface-container': '#F1ECEB',
                        'surface-container-high': '#EBE6E5',
                        'outline': '#85736E',
                        'outline-variant': '#D8C2BC'
                    }
                }
            }
        }
    </script>
    <style>
        body {
            /* Latar: gradasi cokelat tua ke emas + semburat cahaya, tanpa gambar luar */
            background:
                radial-gradient(600px 420px at 85% 8%, rgba(246, 224, 187, 0.35), transparent 60%),
                radial-gradient(520px 520px at 8% 92%, rgba(255, 219, 207, 0.28), transparent 60%),
                linear-gradient(135deg, #2a1a12 0%, #4E342E 48%, #775a19 100%);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }
        .login-card {
            background: rgba(255, 253, 247, 0.94);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(201, 162, 75, 0.45);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.35);
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 1;
        }
        /* Ornamen latar: cincin emas, semburat blur, dan titik-titik halus */
        .bg-decor {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }
        .bg-decor .ring {
            position: absolute;
            border: 1px solid rgba(246, 224, 187, 0.35);
            border-radius: 9999px;
        }
        .bg-decor .ring-1 {
            width: 440px;
            height: 440px;
            top: -170px;
            right: -130px;
        }
        .bg-decor .ring-2 {
            width: 280px;
            height: 280px;
            bottom: -110px;
            left: -90px;
            border-color: rgba(246, 224, 187, 0.25);
        }
        .bg-decor .ring-3 {
            width: 180px;
            height: 180px;
            top: 62%;
            right: 12%;
            border-color: rgba(246, 224, 187, 0.18);
        }
        .bg-decor .glow {
            position: absolute;
            border-radius: 9999px;
            filter: blur(70px);
        }
        .bg-decor .glow-1 {
            width: 280px;
            height: 280px;
            background: rgba(201, 162, 75, 0.35);
            top: 12%;
            left: 8%;
        }
        .bg-decor .glow-2 {
            width: 240px;
            height: 240px;
            background: rgba(255, 219, 207, 0.25);
            bottom: 8%;
            right: 10%;
        }
        .bg-decor .dot-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(246, 224, 187, 0.20) 1px, transparent 1px);
            background-size: 28px 28px;
            opacity: 0.5;
        }
    </style>
</head>
<body class="p-6">
    <div class="bg-decor" aria-hidden="true">
        <span class="ring ring-1"></span>
        <span class="ring ring-2"></span>
        <span class="ring ring-3"></span>
        <span class="glow glow-1"></span>
        <span class="glow glow-2"></span>
        <span class="dot-grid"></span>
    </div>
    <div class="login-card p-10 rounded-3xl shadow-2xl">
        <div class="flex items-center justify-center gap-2 mb-6" aria-hidden="true">
            <span class="h-px w-12 bg-[#c9a24b]/60"></span>
            <span class="block w-2 h-2 rotate-45 bg-[#c9a24b]"></span>
            <span class="h-px w-12 bg-[#c9a24b]/60"></span>
        </div>
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-secondary/10 rounded-full mb-4">
                <span class="material-symbols-outlined text-secondary text-3xl">lock_person</span>
            </div>
            <h1 class="font-display-lg text-2xl text-primary">Admin Panel</h1>
            <p class="text-on-surface-variant text-sm mt-2 font-body-md opacity-70">Masuk untuk mengelola undangan</p>
        </div>

        @error('username')
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-base">error</span>
                {{ $message }}
            </div>
        @enderror

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div class="mb-4 text-left">
                <label class="block text-primary font-bold mb-2 text-sm tracking-wider">USERNAME</label>
                <input type="text" name="username" placeholder="Masukkan username" required autofocus class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-white focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all text-on-surface">
            </div>

            <div class="mb-6 text-left">
                <label class="block text-primary font-bold mb-2 text-sm tracking-wider">PASSWORD</label>
                <input type="password" name="password" placeholder="Masukkan password" required class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-white focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all text-on-surface">
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-secondary to-primary text-white py-4 rounded-xl font-label-caps tracking-widest shadow-lg hover:opacity-90 transition-all active:scale-95 btn-3d">
                MASUK SEKARANG
            </button>
        </form>
    </div>
</body>
</html>
