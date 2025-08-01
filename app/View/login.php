<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Camagru Retro</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Crect x='3' y='5' width='18' height='14' rx='3' fill='%23fff1b6' stroke='%23704214' stroke-width='2'/%3E%3Ccircle cx='12' cy='12' r='3' fill='%23b5d8eb' stroke='%23704214' stroke-width='2'/%3E%3Crect x='16' y='7' width='2' height='2' rx='1' fill='%23704214'/%3E%3C/svg%3E">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/camagru-retro.css">
</head>
<body class="bg-gradient-to-br from-beige-100 via-yellow-50 to-gray-200 flex items-center justify-center min-h-screen font-mono relative overflow-hidden">
    <div class="grain-bg"></div>
    <div class="sepia-overlay"></div>
    <!-- Camera flash overlay -->
    <div class="flash-overlay" id="flashOverlay"></div>
    <!-- Vignette -->
    <div class="vignette"></div>
    <!-- Floating polaroids -->
    <div class="floating-polaroid" style="top:12%; left:6%; animation-delay:0s;" data-id="1">
        <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=200&q=80" alt="Polaroid 1">
    </div>
    <div class="floating-polaroid" style="top:70%; left:80%; animation-delay:3s;" data-id="2">
        <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=200&q=80" alt="Polaroid 2">
    </div>
    <div class="floating-polaroid" style="top:40%; left:85%; animation-delay:6s;" data-id="3">
        <img src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=200&q=80" alt="Polaroid 3">
    </div>
    <div class="floating-polaroid" style="top:85%; left:2%; animation-delay:9s; display:flex; align-items:center; justify-content:center;" data-id="4">
        <img src="https://demo-source.imgix.net/puppy.jpg" alt="Polaroid 4">
    </div>
    <div class="relative w-full max-w-md z-10">
        <div class="film-border"></div>
        <!-- Camera Body -->
            <?php if (!empty($errors['login'][0])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                    <?= htmlspecialchars($errors['login'][0] ?? '', ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($errors['csrf'][0])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                    <?= htmlspecialchars($errors['csrf'][0] ?? '', ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
        <div class="bg-gradient-to-br from-yellow-100 via-beige-200 to-gray-300 rounded-2xl shadow-2xl border-4 border-dashed border-gray-400 pb-10 pt-6 px-8 relative"
             style="box-shadow: 0 12px 32px rgba(60,40,20,0.18), 0 2px 0 #d6bfa7; border-image: url('https://www.transparenttextures.com/patterns/grain.png') 30 repeat;">
            <!-- Camera Lens -->
            <div class="absolute -top-12 left-1/2 transform -translate-x-1/2">
                <div class="bg-gradient-to-br from-gray-700 via-gray-400 to-gray-200 border-4 border-gray-900 rounded-full w-20 h-20 flex items-center justify-center shadow-lg"
                     style="box-shadow: 0 0 0 8px #d6bfa7;">
                    <span class="text-4xl font-bold text-gray-900 flicker">🎞️</span>
                </div>
            </div>
            <h2 class="text-2xl font-bold mb-8 text-center text-gray-800 tracking-widest font-mono mt-12"
                style="letter-spacing: 0.18em; text-shadow: 1px 1px 0 #e5d5c2;">Retro Polaroid Studio</h2>
            <form action="/login" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Service\Csrf::generateToken(), ENT_QUOTES, 'UTF-8'); ?>">
                <div>
                    <label for="username" class="block text-gray-700 mb-2 font-semibold font-mono">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" required
                        class="w-full px-4 py-2 border-2 border-dashed border-gray-400 rounded-full focus:outline-none focus:ring-2 focus:ring-yellow-300 bg-beige-50 font-mono shadow-inner"
                        style="box-shadow: 0 1px 4px #d6bfa7;">
                </div>
                <div>
                    <label for="password" class="block text-gray-700 mb-2 font-semibold font-mono">Mot de passe</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2 border-2 border-dashed border-gray-400 rounded-full focus:outline-none focus:ring-2 focus:ring-yellow-300 bg-beige-50 font-mono shadow-inner"
                        style="box-shadow: 0 1px 4px #d6bfa7;">
                </div>
                <div class="flex justify-center my-4">
                    <!-- Google reCAPTCHA v2 -->
                    <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars($_ENV['RECAPTCHA_SITE_KEY'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-callback="onRecaptchaSuccess" data-expired-callback="onRecaptchaExpired"></div>
                </div>
                <div class="relative">
                    <button id="login-btn" type="submit"
                        class="w-full bg-gradient-to-br from-yellow-400 via-pink-200 to-gray-300 text-gray-900 py-3 rounded-full shadow-lg font-bold font-mono border-2 border-gray-500 hover:bg-yellow-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
                        style="letter-spacing: 0.12em; box-shadow: 0 2px 8px #d6bfa7;" disabled>Connexion</button>
                    <div id="lock-overlay" class="absolute inset-0 flex flex-col items-center justify-center rounded-full transition-opacity duration-300 select-none fog-overlay" style="pointer-events: none; opacity:1;">
                        <span style="font-size:2rem;">🔒</span>
                    </div>
    <style>
    .fog-overlay {
        background: repeating-linear-gradient(120deg, #23211fbb 0 12px, #23211f99 12px 24px) repeat, radial-gradient(circle at 60% 40%, #23211fcc 0 60%, #000c 100%);
        box-shadow: 0 0 32px 12px #23211fcc inset;
        animation: fogMove 7s linear infinite alternate;
    }
    @keyframes fogMove {
        0% { background-position: 0 0, 0 0; }
        100% { background-position: 60px 30px, 30px 60px; }
    }
    </style>
                </div>
                <div id="captcha-help" class="text-xs text-gray-500 text-center mt-2 select-none">
                    <span id="captcha-msg">Veuillez valider le captcha pour activer la connexion.</span>
                </div>
            </form>
            <p class="mt-8 text-center text-gray-600 font-mono">
                Pas de compte ?
                <a href="/register" class="text-yellow-700 hover:underline font-bold">Inscription</a>
            </p>
        </div>
        <div class="text-center mt-4 text-xs text-gray-500 font-mono tracking-widest"
         style="text-shadow: 1px 1px 0 #e5d5c2;">© Camagru Retro by sperron</div>
    </div>
    <script>
        // Camera flash effect on page load
        window.addEventListener('DOMContentLoaded', function() {
            const flash = document.getElementById('flashOverlay');
            flash.style.opacity = '1';
            setTimeout(() => {
                flash.style.opacity = '0';
            }, 400);
        });
    </script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
    // Désactive le bouton tant que le captcha n'est pas validé, affiche un message et un overlay cadenas
    function setLockOverlay(locked) {
        const overlay = document.getElementById('lock-overlay');
        if (overlay) overlay.style.opacity = locked ? '1' : '0';
    }
    function onRecaptchaSuccess() {
        document.getElementById('login-btn').disabled = false;
        document.getElementById('captcha-msg').textContent = '';
        setLockOverlay(false);
    }
    function onRecaptchaExpired() {
        document.getElementById('login-btn').disabled = true;
        document.getElementById('captcha-msg').textContent = 'Veuillez valider le captcha pour activer la connexion.';
        setLockOverlay(true);
    }
    window.onload = function() {
        document.getElementById('login-btn').disabled = true;
        document.getElementById('captcha-msg').textContent = 'Veuillez valider le captcha pour activer la connexion.';
        setLockOverlay(true);
    }
    </script>
    <script src="/js/polaroid.js"></script>
</body>
</html>