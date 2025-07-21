<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Camagru Retro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* ...copy all CSS from login.php... */
        .grain-bg {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background: url('https://www.transparenttextures.com/patterns/grain.png');
            opacity: 0.22;
            animation: grainMove 2s infinite linear alternate;
        }
        @keyframes grainMove {
            0% { background-position: 0 0; }
            100% { background-position: 40px 60px; }
        }
        .sepia-overlay {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 1;
            background: linear-gradient(120deg, #f5ecd7 60%, #e7d3b1 100%);
            mix-blend-mode: multiply;
            opacity: 0.55;
        }
        .film-border {
            position: absolute;
            left: 50%;
            top: 0;
            transform: translateX(-50%);
            width: 110%;
            height: 100%;
            pointer-events: none;
            z-index: 2;
            border: 12px dashed #b8a07a;
            border-radius: 32px;
            opacity: 0.18;
            box-shadow: 0 0 32px #b8a07a inset;
        }
        .flicker {
            animation: flicker 1.2s infinite alternate, lensRotate 8s infinite linear;
        }
        @keyframes flicker {
            0% { filter: brightness(1.1) drop-shadow(0 0 2px #fff); }
            100% { filter: brightness(0.8) drop-shadow(0 0 8px #e7d3b1); }
        }
        @keyframes lensRotate {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
        .floating-polaroid {
            position: fixed;
            z-index: 10;
            width: 90px;
            height: 110px;
            opacity: 0.85;
            border: 6px solid #f5ecd7;
            border-bottom: 18px solid #e7d3b1;
            border-radius: 12px;
            box-shadow: 0 6px 24px #b8a07a55;
            background: #fff;
            animation: floatPolaroid 12s infinite linear;
            cursor: grab;
            transition: box-shadow 0.2s;
        }
        .floating-polaroid.dragging {
            z-index: 101;
            box-shadow: 0 12px 32px #b8a07a99, 0 2px 8px #e7d3b1;
            opacity: 1;
            cursor: grabbing;
            animation: none !important;
        }
        .floating-polaroid img {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        @keyframes floatPolaroid {
            0% { transform: translateY(0) rotate(-8deg); opacity: 0.7;}
            10% { opacity: 1;}
            50% { transform: translateY(-40px) rotate(8deg);}
            90% { opacity: 1;}
            100% { transform: translateY(0) rotate(-8deg); opacity: 0.7;}
        }
        .flash-overlay {
            position: fixed;
            inset: 0;
            background: radial-gradient(circle, #fff 60%, transparent 100%);
            opacity: 0;
            z-index: 100;
            pointer-events: none;
            transition: opacity 0.7s;
        }
        .vignette {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 4;
            background: radial-gradient(ellipse at center, transparent 60%, #2d2d2d33 100%);
            opacity: 0;
            animation: vignetteFade 2.5s forwards;
        }
        @keyframes vignetteFade {
            0% { opacity: 0;}
            100% { opacity: 1;}
        }
    </style>
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
        <span style="font-size:1.1em; color:#b8a07a; font-family:monospace; text-align:center; padding:8px;">Polaroid 4<br><small style="color:#e7d3b1;">(vide)</small></span>
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

        // Drag for polaroids (drag container even if clicking child)
        (function() {
            let draggingPolaroid = null;
            let startX = 0, startY = 0, origX = 0, origY = 0, mouseX = 0, mouseY = 0;

            document.querySelectorAll('.floating-polaroid').forEach(function(polaroid) {
                polaroid.addEventListener('mousedown', function(e) {
                    draggingPolaroid = polaroid;
                    draggingPolaroid.classList.add('dragging');
                    startX = e.clientX;
                    startY = e.clientY;
                    origX = parseFloat(draggingPolaroid.style.left);
                    origY = parseFloat(draggingPolaroid.style.top);
                    mouseX = origX;
                    mouseY = origY;
                    draggingPolaroid.style.animation = 'none';
                    document.body.style.userSelect = 'none';
                    e.preventDefault();
                });
            });

            document.addEventListener('mousemove', function(e) {
                if (!draggingPolaroid) return;
                let dx = e.clientX - startX;
                let dy = e.clientY - startY;
                mouseX = origX + dx * 100 / window.innerWidth;
                mouseY = origY + dy * 100 / window.innerHeight;
                mouseX = Math.max(0, Math.min(92, mouseX));
                mouseY = Math.max(0, Math.min(88, mouseY));
                draggingPolaroid.style.left = mouseX + '%';
                draggingPolaroid.style.top = mouseY + '%';
            });

            document.addEventListener('mouseup', function(e) {
                if (!draggingPolaroid) return;
                draggingPolaroid.classList.remove('dragging');
                document.body.style.userSelect = '';
                draggingPolaroid.style.animation = '';
                draggingPolaroid = null;
            });
        })();
    </script>
</body>
</html>
