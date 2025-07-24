<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Camagru Retro</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Crect x='3' y='5' width='18' height='14' rx='3' fill='%23fff1b6' stroke='%23704214' stroke-width='2'/%3E%3Ccircle cx='12' cy='12' r='3' fill='%23b5d8eb' stroke='%23704214' stroke-width='2'/%3E%3Crect x='16' y='7' width='2' height='2' rx='1' fill='%23704214'/%3E%3C/svg%3E">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/camagru-retro.css">
</head>
<body class="bg-gradient-to-br from-beige-100 via-yellow-50 to-gray-200 flex items-center justify-center min-h-screen font-mono relative overflow-hidden">
    <div class="grain-bg"></div>
    <div class="sepia-overlay"></div>
    <div class="flash-overlay" id="flashOverlay"></div>
    <div class="vignette"></div>
    <!-- Floating polaroids (same as login, you can copy/paste or change images) -->
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
        <div class="bg-gradient-to-br from-yellow-100 via-beige-200 to-gray-300 rounded-2xl shadow-2xl border-4 border-dashed border-gray-400 pb-10 pt-6 px-8 relative"
             style="box-shadow: 0 12px 32px rgba(60,40,20,0.18), 0 2px 0 #d6bfa7; border-image: url('https://www.transparenttextures.com/patterns/grain.png') 30 repeat;">
            <div class="absolute -top-12 left-1/2 transform -translate-x-1/2">
                <div class="bg-gradient-to-br from-gray-700 via-gray-400 to-gray-200 border-4 border-gray-900 rounded-full w-20 h-20 flex items-center justify-center shadow-lg"
                     style="box-shadow: 0 0 0 8px #d6bfa7;">
                    <span class="text-4xl font-bold text-gray-900 flicker">🎞️</span>
                </div>
            </div>
            <h2 class="text-2xl font-bold mb-8 text-center text-gray-800 tracking-widest font-mono mt-12"
                style="letter-spacing: 0.18em; text-shadow: 1px 1px 0 #e5d5c2;">Inscription Camagru Retro</h2>
            <form action="/register" method="POST" class="space-y-6">
                <div>
                    <label for="firstName" class="block text-gray-700 mb-2 font-semibold font-mono">Prénom</label>
                    <input type="text" id="firstName" name="firstName" required
                        class="w-full px-4 py-2 border-2 border-dashed border-gray-400 rounded-full focus:outline-none focus:ring-2 focus:ring-yellow-300 bg-beige-50 font-mono shadow-inner"
                        style="box-shadow: 0 1px 4px #d6bfa7;">
                </div>
                <div>
                    <label for="lastName" class="block text-gray-700 mb-2 font-semibold font-mono">Nom</label>
                    <input type="text" id="lastName" name="lastName" required
                        class="w-full px-4 py-2 border-2 border-dashed border-gray-400 rounded-full focus:outline-none focus:ring-2 focus:ring-yellow-300 bg-beige-50 font-mono shadow-inner"
                        style="box-shadow: 0 1px 4px #d6bfa7;">
                </div>
                <div>
                    <label for="email" class="block text-gray-700 mb-2 font-semibold font-mono">Email</label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-4 py-2 border-2 border-dashed border-gray-400 rounded-full focus:outline-none focus:ring-2 focus:ring-yellow-300 bg-beige-50 font-mono shadow-inner"
                        style="box-shadow: 0 1px 4px #d6bfa7;">
                </div>
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
                <div>
                    <label for="confirmPassword" class="block text-gray-700 mb-2 font-semibold font-mono">Confirmer le mot de passe</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required
                        class="w-full px-4 py-2 border-2 border-dashed border-gray-400 rounded-full focus:outline-none focus:ring-2 focus:ring-yellow-300 bg-beige-50 font-mono shadow-inner"
                        style="box-shadow: 0 1px 4px #d6bfa7;">
                </div>
                <button type="submit"
                    class="w-full bg-gradient-to-br from-yellow-400 via-pink-200 to-gray-300 text-gray-900 py-3 rounded-full shadow-lg font-bold font-mono border-2 border-gray-500 hover:bg-yellow-500 transition"
                    style="letter-spacing: 0.12em; box-shadow: 0 2px 8px #d6bfa7;">S'inscrire</button>
            </form>
            <p class="mt-8 text-center text-gray-600 font-mono">
                Déjà inscrit ?
                <a href="/login" class="text-yellow-700 hover:underline font-bold">Connexion</a>
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
    <script src="/js/polaroid.js"></script>
</body>
</html>