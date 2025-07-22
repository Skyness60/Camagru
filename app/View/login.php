<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Camagru Retro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Animation grain */
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
        /* Sépia overlay */
        .sepia-overlay {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 1;
            background: linear-gradient(120deg, #f5ecd7 60%, #e7d3b1 100%);
            mix-blend-mode: multiply;
            opacity: 0.55;
        }
        /* Pellicule border */
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
        /* Flicker animation for lens emoji */
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

        /* Floating polaroid photos */
        .floating-polaroid {
            position: fixed;
            z-index: 10; /* Increased for interaction */
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

        /* Camera flash overlay */
        .flash-overlay {
            position: fixed;
            inset: 0;
            background: radial-gradient(circle, #fff 60%, transparent 100%);
            opacity: 0;
            z-index: 100;
            pointer-events: none;
            transition: opacity 0.7s;
        }

        /* Vignette fade-in */
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
                <button type="submit"
                    class="w-full bg-gradient-to-br from-yellow-400 via-pink-200 to-gray-300 text-gray-900 py-3 rounded-full shadow-lg font-bold font-mono border-2 border-gray-500 hover:bg-yellow-500 transition"
                    style="letter-spacing: 0.12em; box-shadow: 0 2px 8px #d6bfa7;">Connexion</button>
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

        // Drag, realistic throw, collision with login and between polaroids (no overlap on drag)
        (function() {
            let draggingPolaroid = null;
            let startX = 0, startY = 0, origX = 0, origY = 0, mouseX = 0, mouseY = 0;
            let velocityX = 0, velocityY = 0;
            let dragHistory = [];
            const minX = 0, maxX = 92;
            const minY = 0, maxY = 88;
            const polaroids = Array.from(document.querySelectorAll('.floating-polaroid'));
            const polaroidStates = new Map();
            const polaroidAnimFrames = new Map();
            const restitution = 0.8;
            const friction = 0.995;
            const minSpeed = 0.03;
            const POLAROID_W = 6.5, POLAROID_H = 9.5;

            function getLoginRect() {
                const login = document.querySelector('.relative.w-full.max-w-md.z-10');
                const rect = login.getBoundingClientRect();
                return {
                    left: rect.left * 100 / window.innerWidth,
                    top: rect.top * 100 / window.innerHeight,
                    right: rect.right * 100 / window.innerWidth,
                    bottom: rect.bottom * 100 / window.innerHeight,
                    width: rect.width * 100 / window.innerWidth,
                    height: rect.height * 100 / window.innerHeight
                };
            }

            function getPolaroidRect(state) {
                return {
                    left: state.x,
                    top: state.y,
                    right: state.x + POLAROID_W,
                    bottom: state.y + POLAROID_H,
                    width: POLAROID_W,
                    height: POLAROID_H
                };
            }

            function isColliding(a, b) {
                return !(a.right <= b.left || a.left >= b.right || a.bottom <= b.top || a.top >= b.bottom);
            }

            // Set initial state for each polaroid
            polaroids.forEach((p) => {
                let x = parseFloat(p.style.left) || 10;
                let y = parseFloat(p.style.top) || 20;
                polaroidStates.set(p, { x, y, vx: 0, vy: 0 });
                polaroidAnimFrames.set(p, null);
            });

            polaroids.forEach(function(polaroid) {
                polaroid.addEventListener('mousedown', function(e) {
                    let animFrame = polaroidAnimFrames.get(polaroid);
                    if (animFrame) cancelAnimationFrame(animFrame);
                    polaroidAnimFrames.set(polaroid, null);

                    draggingPolaroid = polaroid;
                    polaroid.classList.add('dragging');
                    startX = e.clientX;
                    startY = e.clientY;
                    origX = parseFloat(polaroid.style.left);
                    origY = parseFloat(polaroid.style.top);
                    mouseX = origX;
                    mouseY = origY;
                    dragHistory = [{x: startX, y: startY, t: performance.now()}];
                    polaroid.style.animation = 'none';
                    document.body.style.userSelect = 'none';
                    e.preventDefault();
                });
            });

            document.addEventListener('mousemove', function(e) {
                if (!draggingPolaroid) return;
                let dx = e.clientX - startX;
                let dy = e.clientY - startY;
                let newX = origX + dx * 100 / window.innerWidth;
                let newY = origY + dy * 100 / window.innerHeight;
                newX = Math.max(minX, Math.min(maxX, newX));
                newY = Math.max(minY, Math.min(maxY, newY));

                // Prevent overlap with login box
                const loginRect = getLoginRect();
                let tempRect = {
                    left: newX,
                    top: newY,
                    right: newX + 8,
                    bottom: newY + 12
                };
                if (isColliding(tempRect, loginRect)) {
                    // Snap to closest edge outside login
                    if (dx > 0 && tempRect.right > loginRect.left && tempRect.left < loginRect.left) newX = loginRect.left - 8;
                    if (dx < 0 && tempRect.left < loginRect.right && tempRect.right > loginRect.right) newX = loginRect.right;
                    if (dy > 0 && tempRect.bottom > loginRect.top && tempRect.top < loginRect.top) newY = loginRect.top - 12;
                    if (dy < 0 && tempRect.top < loginRect.bottom && tempRect.bottom > loginRect.bottom) newY = loginRect.bottom;
                }

                // Prevent overlap with other polaroids
                for (let other of polaroids) {
                    if (other === draggingPolaroid) continue;
                    let otherState = polaroidStates.get(other);
                    let otherRect = getPolaroidRect(otherState);
                    let thisRect = {
                        left: newX,
                        top: newY,
                        right: newX + 8,
                        bottom: newY + 12
                    };
                    if (isColliding(thisRect, otherRect)) {
                        // Snap away from the other polaroid
                        if (dx > 0 && thisRect.right > otherRect.left && thisRect.left < otherRect.left) newX = otherRect.left - 8;
                        if (dx < 0 && thisRect.left < otherRect.right && thisRect.right > otherRect.right) newX = otherRect.right;
                        if (dy > 0 && thisRect.bottom > otherRect.top && thisRect.top < otherRect.top) newY = otherRect.top - 12;
                        if (dy < 0 && thisRect.top < otherRect.bottom && thisRect.bottom > otherRect.bottom) newY = otherRect.bottom;
                    }
                }

                mouseX = Math.max(minX, Math.min(maxX, newX));
                mouseY = Math.max(minY, Math.min(maxY, newY));
                draggingPolaroid.style.left = mouseX + '%';
                draggingPolaroid.style.top = mouseY + '%';

                // Ajoute à l'historique pour calculer la vélocité sur les 100 derniers ms
                const now = performance.now();
                dragHistory.push({x: e.clientX, y: e.clientY, t: now});
                // Garde seulement les points des 100 derniers ms
                while (dragHistory.length > 2 && now - dragHistory[0].t > 100) {
                    dragHistory.shift();
                }
            });

            document.addEventListener('mouseup', function(e) {
                if (!draggingPolaroid) return;
                draggingPolaroid.classList.remove('dragging');
                document.body.style.userSelect = '';
                draggingPolaroid.style.animation = '';

                // Calcul de la vélocité sur les 100 derniers ms du drag
                let vdx = 0, vdy = 0, vdt = 1;
                if (dragHistory.length >= 2) {
                    let first = dragHistory[0];
                    let last = dragHistory[dragHistory.length - 1];
                    vdx = last.x - first.x;
                    vdy = last.y - first.y;
                    vdt = Math.max(1, last.t - first.t);
                }
                velocityX = (vdx * 100 / window.innerWidth) / vdt * 10;
                velocityY = (vdy * 100 / window.innerHeight) / vdt * 10;
                velocityX = Math.max(-2, Math.min(2, velocityX));
                velocityY = Math.max(-2, Math.min(2, velocityY));
                let state = polaroidStates.get(draggingPolaroid);
                state.x = mouseX;
                state.y = mouseY;
                state.vx = velocityX;
                state.vy = velocityY;
                animatePolaroid(draggingPolaroid);
                draggingPolaroid = null;
            });

            function axisOfMaxOverlap(r1, r2) {
                const overlapX = Math.min(r1.right, r2.right) - Math.max(r1.left, r2.left);
                const overlapY = Math.min(r1.bottom, r2.bottom) - Math.max(r1.top, r2.top);
                if (overlapX === overlapY) {
                    return Math.abs(r1.left - r2.left) > Math.abs(r1.top - r2.top) ? 'x' : 'y';
                }
                return overlapX < overlapY ? 'x' : 'y';
            }
            function sign(x) { return x >= 0 ? 1 : -1; }

            function animatePolaroid(polaroid) {
                let state = polaroidStates.get(polaroid);
                function step() {
                    state.x += state.vx;
                    state.y += state.vy;

                    state.vx *= friction;
                    state.vy *= friction;

                    // Collision with viewport borders
                    let bounced = false;
                    if (state.x < minX) {
                        state.x = minX;
                        state.vx = Math.abs(state.vx) * restitution;
                        bounced = true;
                    }
                    if (state.x > maxX) {
                        state.x = maxX;
                        state.vx = -Math.abs(state.vx) * restitution;
                        bounced = true;
                    }
                    if (state.y < minY) {
                        state.y = minY;
                        state.vy = Math.abs(state.vy) * restitution;
                        bounced = true;
                    }
                    if (state.y > maxY) {
                        state.y = maxY;
                        state.vy = -Math.abs(state.vy) * restitution;
                        bounced = true;
                    }

                    // Collision with login box (rebond sur axe principal, gère bien les coins)
                    const loginRect = getLoginRect();
                    const pRect = getPolaroidRect(state);
                    if (isColliding(pRect, loginRect)) {
                        const overlapX = Math.min(pRect.right, loginRect.right) - Math.max(pRect.left, loginRect.left);
                        const overlapY = Math.min(pRect.bottom, loginRect.bottom) - Math.max(pRect.top, loginRect.top);
                        // Si coin, rebond sur les deux axes
                        if (overlapX > 0 && overlapY > 0 && Math.abs(overlapX - overlapY) < 1.5) {
                            // Coin : rebond sur x ET y
                            if (state.x + POLAROID_W / 2 < (loginRect.left + loginRect.right) / 2) {
                                state.x = loginRect.left - POLAROID_W;
                                state.vx = -Math.abs(state.vx) * restitution;
                            } else {
                                state.x = loginRect.right;
                                state.vx = Math.abs(state.vx) * restitution;
                            }
                            if (state.y + POLAROID_H / 2 < (loginRect.top + loginRect.bottom) / 2) {
                                state.y = loginRect.top - POLAROID_H;
                                state.vy = -Math.abs(state.vy) * restitution;
                            } else {
                                state.y = loginRect.bottom;
                                state.vy = Math.abs(state.vy) * restitution;
                            }
                        } else {
                            // Sinon, rebond sur l'axe principal
                            const axis = axisOfMaxOverlap(pRect, loginRect);
                            if (axis === 'x') {
                                if (state.x + POLAROID_W / 2 < (loginRect.left + loginRect.right) / 2) {
                                    state.x = loginRect.left - POLAROID_W;
                                    state.vx = -Math.abs(state.vx) * restitution;
                                } else {
                                    state.x = loginRect.right;
                                    state.vx = Math.abs(state.vx) * restitution;
                                }
                            } else {
                                if (state.y + POLAROID_H / 2 < (loginRect.top + loginRect.bottom) / 2) {
                                    state.y = loginRect.top - POLAROID_H;
                                    state.vy = -Math.abs(state.vy) * restitution;
                                } else {
                                    state.y = loginRect.bottom;
                                    state.vy = Math.abs(state.vy) * restitution;
                                }
                            }
                        }
                    }

                    // Collision with other polaroids (rebond sur axe principal dans tous les cas)
                    polaroids.forEach(function(other) {
                        if (other === polaroid) return;
                        let otherState = polaroidStates.get(other);
                        let r1 = getPolaroidRect(state);
                        let r2 = getPolaroidRect(otherState);
                        if (isColliding(r1, r2)) {
                            const axis = axisOfMaxOverlap(r1, r2);
                            if (axis === 'x') {
                                // Échange de vitesse sur x
                                let tmp = state.vx;
                                state.vx = otherState.vx * restitution;
                                otherState.vx = tmp * restitution;
                                // Séparation douce sur x
                                let mid = (r1.left + r1.right) / 2;
                                let omid = (r2.left + r2.right) / 2;
                                let overlap = (POLAROID_W - Math.abs(mid - omid));
                                if (overlap > 0) {
                                    let sep = overlap / 2 * sign(mid - omid);
                                    state.x += sep;
                                    otherState.x -= sep;
                                }
                            } else if (axis === 'y') {
                                // Échange de vitesse sur y
                                let tmp = state.vy;
                                state.vy = otherState.vy * restitution;
                                otherState.vy = tmp * restitution;
                                // Séparation douce sur y
                                let mid = (r1.top + r1.bottom) / 2;
                                let omid = (r2.top + r2.bottom) / 2;
                                let overlap = (POLAROID_H - Math.abs(mid - omid));
                                if (overlap > 0) {
                                    let sep = overlap / 2 * sign(mid - omid);
                                    state.y += sep;
                                    otherState.y -= sep;
                                }
                            }
                        }
                    });

                    polaroid.style.left = state.x + '%';
                    polaroid.style.top = state.y + '%';

                    if (Math.abs(state.vx) < minSpeed && Math.abs(state.vy) < minSpeed) {
                        state.vx = 0;
                        state.vy = 0;
                        polaroidAnimFrames.set(polaroid, null);
                        return;
                    }

                    let animFrame = requestAnimationFrame(step);
                    polaroidAnimFrames.set(polaroid, animFrame);
                }
                let prev = polaroidAnimFrames.get(polaroid);
                if (prev) cancelAnimationFrame(prev);
                polaroidAnimFrames.set(polaroid, requestAnimationFrame(step));
            }

            // On page load, start all polaroids with a random direction except polaroid 4
            polaroids.forEach(function(polaroid) {
                let state = polaroidStates.get(polaroid);
                if (polaroid.querySelector('img') === null) {
                    state.vx = 0;
                    state.vy = 0;
                    polaroidAnimFrames.set(polaroid, null);
                } else {
                    state.vx = (Math.random() > 0.5 ? 0.5 : -0.5) * (0.5 + Math.random());
                    state.vy = (Math.random() > 0.5 ? 0.5 : -0.5) * (0.5 + Math.random());
                    animatePolaroid(polaroid);
                }
            });
        })();
    </script>
</body>
</html>