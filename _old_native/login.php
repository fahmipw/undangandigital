<?php
session_start();
require_once 'api/db.php';

// Redirect jika sudah login
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    if (!empty($user) && !empty($pass)) {
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
            $stmt->execute([$user]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($pass, $admin['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header('Location: admin.php');
                exit;
            } else {
                $error = 'Username atau password salah.';
            }
        } catch (PDOException $e) {
            $error = 'Terjadi kesalahan sistem.';
        }
    } else {
        $error = 'Silakan isi semua field.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Elvy & Rokim</title>
    <link rel="stylesheet" href="styles.css">
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
            background-color: #fbf9f5;
            background-image: url('https://i.pinimg.com/474x/80/f7/69/80f769b3f507e84769bb365efc33f7a3.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(251, 249, 245, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(119, 90, 25, 0.2);
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body class="p-6">
    <div class="login-card p-10 rounded-3xl shadow-2xl">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-secondary/10 rounded-full mb-4">
                <span class="material-symbols-outlined text-secondary text-3xl">lock_person</span>
            </div>
            <h1 class="font-display-lg text-2xl text-primary">Admin Panel</h1>
            <p class="text-on-surface-variant text-sm mt-2 font-body-md opacity-70">Masuk untuk mengelola undangan</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-base">error</span>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div class="rsvp-field">
                <label>USERNAME</label>
                <input type="text" name="username" placeholder="Masukkan username" required autofocus>
            </div>

            <div class="rsvp-field">
                <label>PASSWORD</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="w-full bg-secondary text-white py-4 rounded-xl font-label-caps tracking-widest shadow-lg hover:bg-primary transition-all active:scale-95 btn-3d">
                MASUK SEKARANG
            </button>
        </form>

        <div class="mt-8 pt-8 border-t border-outline-variant/30 text-center">
            <a href="index.php" class="text-secondary text-sm font-label-caps tracking-wider hover:underline flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                KEMBALI KE UNDANGAN
            </a>
        </div>
    </div>
</body>
</html>
