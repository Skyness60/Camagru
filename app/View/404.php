<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>404 - Page non trouvée | Camagru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: radial-gradient(ellipse at center, #23211e 0%, #181716 100%);
        }
        .grain {
            pointer-events: none;
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: 10;
            opacity: 0.18;
            background: url('https://grainy-gradients.vercel.app/noise.svg');
            mix-blend-mode: soft-light;
            animation: grainmove 2s infinite linear alternate;
        }
        @keyframes grainmove {
            0% { background-position: 0 0; }
            100% { background-position: 20px 20px; }
        }
        .flicker {
            animation: flicker 2.5s infinite alternate;
        }
        @keyframes flicker {
            0%, 100% { opacity: 1; }
            45% { opacity: 0.85; }
            50% { opacity: 0.7; }
            55% { opacity: 0.9; }
            60% { opacity: 0.8; }
        }
        .polaroid {
            box-shadow: 0 8px 32px 0 rgba(30, 24, 18, 0.45), 0 1.5px 0 0 #b9a074;
            border-radius: 1.5rem;
            border: 4px solid #e7d7b1;
            background: linear-gradient(180deg, #f5e8d2 80%, #b9a074 100%);
            filter: sepia(0.25) contrast(1.05);
        }
        .photo {
            filter: sepia(0.6) contrast(1.1) brightness(0.95);
            border-radius: 0.75rem;
            border: 2px solid #b9a074;
            box-shadow: 0 4px 16px 0 rgba(30, 24, 18, 0.25);
        }
        .btn-vintage {
            background: #23211e;
            color: #e7d7b1;
            border: 2px solid #b9a074;
            border-radius: 9999px;
            box-shadow: 0 2px 8px 0 rgba(30, 24, 18, 0.25);
            transition: background 0.2s, color 0.2s;
        }
        .btn-vintage:hover {
            background: #b9a074;
            color: #23211e;
        }
        /* Custom slow spin for lens */
        .animate-spin-slow {
            animation: spin 6s linear infinite;
        }
        @keyframes spin {
            100% { transform: rotate(360deg);}
        }
    </style>
</head>
<body class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="grain"></div>
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none z-0">
        <!-- Animated film strip (left) -->
        <div class="absolute left-0 top-1/4 flex flex-col gap-4 z-0">
            <div class="w-6 sm:w-8 h-16 sm:h-24 bg-[#23211e] border-4 border-[#b9a074] rounded-lg opacity-60 animate-bounce"></div>
            <div class="w-6 sm:w-8 h-16 sm:h-24 bg-[#23211e] border-4 border-[#b9a074] rounded-lg opacity-40 animate-pulse"></div>
        </div>
        <!-- Animated film strip (right) -->
        <div class="absolute right-0 bottom-1/4 flex flex-col gap-4 z-0">
            <div class="w-6 sm:w-8 h-16 sm:h-24 bg-[#23211e] border-4 border-[#b9a074] rounded-lg opacity-60 animate-bounce"></div>
            <div class="w-6 sm:w-8 h-16 sm:h-24 bg-[#23211e] border-4 border-[#b9a074] rounded-lg opacity-40 animate-pulse"></div>
        </div>
    </div>
    <main class="relative z-20 flex flex-col items-center w-full px-2 sm:px-0">
        <div class="polaroid p-4 sm:p-6 md:p-10 flex flex-col items-center shadow-2xl flicker w-full max-w-xs sm:max-w-md md:max-w-lg">
            <div class="w-40 h-40 sm:w-64 sm:h-64 bg-[#23211e] flex items-center justify-center mb-4 rounded-xl overflow-hidden photo relative">
                <!-- Camera lens animation -->
                <div class="absolute w-16 h-16 sm:w-24 sm:h-24 rounded-full border-8 border-[#b9a074] left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 animate-spin-slow"></div>
                <svg class="w-12 h-12 sm:w-20 sm:h-20 text-[#b9a074] opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 48 48">
                    <rect x="8" y="14" width="32" height="20" rx="6" fill="#23211e" stroke="#b9a074" stroke-width="2"/>
                    <circle cx="24" cy="24" r="7" fill="#b9a074" stroke="#e7d7b1" stroke-width="2"/>
                    <circle cx="24" cy="24" r="3" fill="#23211e"/>
                    <rect x="18" y="10" width="12" height="6" rx="2" fill="#b9a074" stroke="#e7d7b1" stroke-width="2"/>
                </svg>
            </div>
            <h1 class="text-4xl sm:text-6xl md:text-7xl font-extrabold text-[#23211e] drop-shadow-lg tracking-widest mb-2 flicker">404</h1>
            <p class="text-lg sm:text-xl md:text-2xl text-[#b9a074] font-semibold mb-4 text-center">Oups ! Cette page n’existe pas…</p>
            <p class="text-sm sm:text-base md:text-lg text-[#23211e] opacity-80 mb-6 text-center max-w-xs">
                Comme une photo manquante dans un album,<br>
                cette page s’est égarée dans le temps.
            </p>
            <a href="/" class="btn-vintage px-6 sm:px-8 py-2 sm:py-3 text-base sm:text-lg font-bold uppercase tracking-wider mt-2 shadow-lg hover:scale-105 transition-transform">Retour à l’accueil</a>
        </div>
        <div class="mt-8 flex flex-row gap-2 sm:gap-4 opacity-80">
            <div class="w-16 h-20 sm:w-24 sm:h-32 bg-[#f5e8d2] border-4 border-[#b9a074] rounded-xl shadow-lg rotate-[-8deg] flex items-center justify-center">
                <svg class="w-8 h-8 sm:w-12 sm:h-12 text-[#b9a074]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 48 48">
                    <rect x="8" y="14" width="32" height="20" rx="6" fill="#23211e" stroke="#b9a074" stroke-width="2"/>
                    <circle cx="24" cy="24" r="7" fill="#b9a074" stroke="#e7d7b1" stroke-width="2"/>
                </svg>
            </div>
            <div class="w-16 h-20 sm:w-24 sm:h-32 bg-[#f5e8d2] border-4 border-[#b9a074] rounded-xl shadow-lg rotate-[6deg] flex items-center justify-center">
                <svg class="w-8 h-8 sm:w-12 sm:h-12 text-[#b9a074]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 48 48">
                    <rect x="8" y="14" width="32" height="20" rx="6" fill="#23211e" stroke="#b9a074" stroke-width="2"/>
                    <circle cx="24" cy="24" r="7" fill="#b9a074" stroke="#e7d7b1" stroke-width="2"/>
                </svg>
            </div>
        </div>
    </main>
</body>
</html>
