<!DOCTYPE html>
<html lang="en" class="bg-beige dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Crect x='3' y='5' width='18' height='14' rx='3' fill='%23fff1b6' stroke='%23704214' stroke-width='2'/%3E%3Ccircle cx='12' cy='12' r='3' fill='%23b5d8eb' stroke='%23704214' stroke-width='2'/%3E%3Crect x='16' y='7' width='2' height='2' rx='1' fill='%23704214'/%3E%3C/svg%3E">
    <title>Camagru Retro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        beige: '#f5f5dc',
                        sepia: '#704214',
                        pastel: {
                            pink: '#f7cac9',
                            blue: '#b5d8eb',
                            yellow: '#fff1b6'
                        },
                        accent: '#ffb347',
                        modern: '#22223b',
                        // New soft palette
                        softcream: '#f8f6f1',
                        softbrown: '#bfae9e',
                        softgray: '#eae7e1',
                        darksoft: '#23211f',
                        darkbrown: '#3a2e25',
                        // Light mode improvements
                        sand: '#f6ede3',
                        sand2: '#f3e9dc',
                        sand3: '#e9dcc9'
                    },
                    fontFamily: {
                        retro: ['"Courier New"', 'Courier', 'monospace'],
                        modern: ['"Montserrat"', '"Inter"', 'sans-serif']
                    },
                    boxShadow: {
                        polaroid: '0 6px 32px 0 rgba(112,66,20,0.18), 0 2px 0 0 #fff inset',
                        modern: '0 8px 32px 0 rgba(34,34,59,0.12)'
                    },
                    borderRadius: {
                        polaroid: '1.5rem',
                        modern: '1rem'
                    },
                    backgroundImage: {
                        'grain': "url('https://www.transparenttextures.com/patterns/grunge-wall.png')",
                        'modern': "linear-gradient(135deg, #f7cac9 0%, #b5d8eb 100%)"
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Montserrat', 'Arial', 'Helvetica Neue', Helvetica, 'Courier New', Courier, monospace; }
        .sidebar-glass {
            background: rgba(246,237,227,0.92);
            backdrop-filter: blur(8px);
            border-right: 2px solid #e9dcc9;
        }
        .modern-card {
            background: rgba(255,255,255,0.97);
            border-radius: 1.25rem;
            box-shadow: 0 8px 32px 0 rgba(34,34,59,0.10), 0 1.5px 0 0 #e9dcc9 inset;
            border: 1.5px solid #e9dcc9;
        }
        .toggle-btn {
            background: linear-gradient(90deg, #fff1b6 0%, #f7cac9 100%);
            color: #704214;
            border: 2px solid #e9dcc9;
            border-radius: 9999px;
            box-shadow: 0 2px 12px 0 rgba(255,179,71,0.10);
            transition: background 0.3s, color 0.3s, border 0.3s;
            font-weight: bold;
            letter-spacing: 0.05em;
            position: relative;
            overflow: hidden;
        }
        .toggle-btn:hover {
            background: linear-gradient(90deg, #f7cac9 0%, #fff1b6 100%);
            color: #22223b;
            border-color: #ffb347;
        }
        .toggle-btn .toggle-slider {
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #fffbe9;
            box-shadow: 0 1px 4px 0 rgba(112,66,20,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: left 0.3s, background 0.3s;
        }
        .dark .toggle-btn {
            background: linear-gradient(90deg, #3a2e25 0%, #23211f 100%);
            color: #fff1b6;
            border: 2px solid #3a2e25;
        }
        .dark .toggle-btn:hover {
            background: linear-gradient(90deg, #23211f 0%, #3a2e25 100%);
            color: #ffb347;
            border-color: #ffb347;
        }
        .dark .toggle-btn .toggle-slider {
            background: #3a2e25;
        }
        .sidebar-overlay {
            background: rgba(0,0,0,0.25);
            position: fixed;
            inset: 0;
            z-index: 40;
            display: none;
        }
        .sidebar-open .sidebar-overlay {
            display: block;
        }
        .sidebar-open .sidebar-mobile {
            transform: translateX(0);
        }
        @media (max-width: 768px) {
            .sidebar-desktop {
                display: none !important;
            }
            .sidebar-mobile {
                display: flex !important;
            }
            main {
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin-left: 0 !important;
                width: 100vw !important;
                max-width: 100vw !important;
            }
            .modern-card {
                border-radius: 0.75rem;
                box-shadow: 0 4px 16px 0 rgba(34,34,59,0.10), 0 1px 0 0 #e9dcc9 inset;
                border: 1px solid #e9dcc9;
            }
        }
        @media (min-width: 769px) {
            .sidebar-mobile {
                display: none !important;
            }
            .sidebar-desktop {
                display: flex !important;
            }
            main {
                margin-left: 18rem !important;
                width: calc(100vw - 18rem) !important;
                max-width: calc(100vw - 18rem) !important;
                background: transparent !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
        }
        @media (max-width: 768px) {
            nav.fixed.bottom-0 a {
                color: inherit !important;
            }
            .dark nav.fixed.bottom-0 a {
                color: inherit !important;
            }
        }
        /* --- Animation flash vintage sur le logo polaroid --- */
        .logo-polaroid-anim {
            animation: polaroid-flash 3.5s cubic-bezier(.8,.2,.2,1) infinite;
            cursor: pointer;
        }
        .logo-polaroid-flash {
            animation: polaroid-flash-click 0.5s cubic-bezier(.8,.2,.2,1) 1;
        }
        @keyframes polaroid-flash {
            0%, 100% { filter: brightness(1) drop-shadow(0 0 0 #fff1b6); }
            8% { filter: brightness(1.7) drop-shadow(0 0 16px #fff1b6); }
            12% { filter: brightness(1) drop-shadow(0 0 0 #fff1b6); }
            15% { filter: brightness(1.3) drop-shadow(0 0 8px #ffb347); }
            18% { filter: brightness(1) drop-shadow(0 0 0 #fff1b6); }
        }
        @keyframes polaroid-flash-click {
            0% { filter: brightness(1.7) drop-shadow(0 0 24px #fff1b6); }
            40% { filter: brightness(1.2) drop-shadow(0 0 8px #ffb347); }
            100% { filter: brightness(1) drop-shadow(0 0 0 #fff1b6); }
        }
    </style>
</head>
<body class="flex flex-col md:flex-row bg-gradient-to-br from-sand via-sand2 to-sand3 dark:bg-darksoft font-modern min-h-screen transition-colors duration-300">
    <!-- Mobile Top Bar -->
    <header class="md:hidden flex items-center justify-between px-4 py-2 bg-sand2/90 dark:bg-darksoft shadow z-30 fixed top-0 left-0 right-0">
        <!-- Logo/Title center, message icon right -->
        <span class="text-xl font-extrabold tracking-widest text-sepia dark:text-pastel-yellow">CAMAGRU RETRO</span>
        <span class="w-8 h-8"></span>
        <a href="#" class="p-2 rounded-full bg-pastel-yellow/80 dark:bg-darkbrown text-sepia dark:text-pastel-yellow shadow flex items-center justify-center">
            <i data-feather="message-circle"></i>
        </a>
    </header>
    <!-- Sidebar Desktop -->
    <aside class="sidebar-desktop w-20 md:w-72 min-h-screen sidebar-glass dark:bg-darkbrown bg-grain shadow-polaroid flex flex-col items-center py-10 border-r-8 border-softgray dark:border-darksoft fixed md:static z-40 top-0 left-0 h-full"
        style="background-image: url('https://www.transparenttextures.com/patterns/wood-pattern.png'); box-shadow: 0 12px 48px 0 rgba(112,66,20,0.10);">
        <!-- Logo Polaroid -->
        <div class="mb-12">
            <div class="rounded-polaroid shadow-polaroid bg-sand2/90 dark:bg-darksoft p-4 border-8 border-pastel-yellow dark:border-softbrown flex flex-col items-center">
                <span class="text-5xl drop-shadow-lg logo-polaroid-anim" id="logo-polaroid-desktop">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-sepia dark:text-pastel-yellow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="3" y="5" width="18" height="14" rx="3" fill="#fff1b6" stroke="#704214" stroke-width="2"/>
                        <circle cx="12" cy="12" r="3" fill="#b5d8eb" stroke="#704214" stroke-width="2"/>
                        <rect x="16" y="7" width="2" height="2" rx="1" fill="#704214"/>
                    </svg>
                </span>
            </div>
        </div>
        <!-- Navigation -->
        <nav class="flex flex-col gap-3 w-full items-center">
            <a href="#" class="flex items-center gap-4 px-5 py-3 rounded-xl bg-sand2/90 dark:bg-darksoft shadow-lg hover:bg-pastel-yellow/60 dark:hover:bg-darkbrown transition w-11/12 font-semibold text-modern dark:text-pastel-yellow group">
                <span class="text-xl text-accent group-hover:scale-110 transition"><i data-feather="home"></i></span>
                <span class="hidden md:inline">Accueil</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-3 rounded-xl bg-sand2/90 dark:bg-darksoft shadow-lg hover:bg-pastel-blue/50 dark:hover:bg-darkbrown transition w-11/12 font-semibold text-modern dark:text-pastel-yellow group">
                <span class="text-xl text-pastel-blue group-hover:scale-110 transition"><i data-feather="search"></i></span>
                <span class="hidden md:inline">Recherche</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-3 rounded-xl bg-sand2/90 dark:bg-darksoft shadow-lg hover:bg-pastel-pink/50 dark:hover:bg-darkbrown transition w-11/12 font-semibold text-modern dark:text-pastel-yellow group">
                <span class="text-xl text-pastel-pink group-hover:scale-110 transition"><i data-feather="compass"></i></span>
                <span class="hidden md:inline">Découvrir</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-3 rounded-xl bg-sand2/90 dark:bg-darksoft shadow-lg hover:bg-pastel-yellow/50 dark:hover:bg-darkbrown transition w-11/12 font-semibold text-modern dark:text-pastel-yellow group">
                <span class="text-xl text-pastel-yellow group-hover:scale-110 transition"><i data-feather="film"></i></span>
                <span class="hidden md:inline">Reels</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-3 rounded-xl bg-sand2/90 dark:bg-darksoft shadow-lg hover:bg-pastel-blue/50 dark:hover:bg-darkbrown transition w-11/12 font-semibold text-modern dark:text-pastel-yellow group">
                <span class="text-xl text-pastel-blue group-hover:scale-110 transition"><i data-feather="message-circle"></i></span>
                <span class="hidden md:inline">Messages</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-3 rounded-xl bg-sand2/90 dark:bg-darksoft shadow-lg hover:bg-pastel-pink/50 dark:hover:bg-darkbrown transition w-11/12 font-semibold text-modern dark:text-pastel-yellow group">
                <span class="text-xl text-pastel-pink group-hover:scale-110 transition"><i data-feather="bell"></i></span>
                <span class="hidden md:inline">Notifications</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-3 rounded-xl bg-sand2/90 dark:bg-darksoft shadow-lg hover:bg-pastel-yellow/50 dark:hover:bg-darkbrown transition w-11/12 font-semibold text-modern dark:text-pastel-yellow group">
                <span class="text-xl text-accent group-hover:scale-110 transition"><i data-feather="plus-square"></i></span>
                <span class="hidden md:inline">Créer</span>
            </a>
            <!-- Profil utilisateur -->
            <div class="flex flex-col items-center w-full mt-10">
                <div class="flex items-center gap-3 px-5 py-3 rounded-xl bg-gradient-to-r from-pastel-blue/30 to-pastel-pink/30 dark:from-darksoft dark:to-darkbrown shadow-xl border-2 border-softbrown dark:border-darksoft w-11/12">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="sami.60110" class="w-12 h-12 rounded-full border-2 border-sepia shadow-lg" />
                    <div class="hidden md:flex flex-col">
                        <span class="text-modern dark:text-pastel-yellow font-bold text-base">sami.60110</span>
                        <a href="#" class="text-xs text-accent dark:text-pastel-yellow hover:underline flex items-center gap-1">
                            <i data-feather="user"></i>
                            Profil
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Dark/Light Mode Toggle -->
        <button id="theme-toggle" class="toggle-btn mt-auto mb-6 flex items-center gap-2 px-7 py-3 w-48 justify-center relative group focus:outline-none">
            <span class="toggle-slider transition-all duration-300">
                <i id="theme-icon" data-feather="moon"></i>
            </span>
            <span class="ml-8 hidden md:inline transition-colors duration-300">Mode&nbsp;<span id="theme-label">Clair</span></span>
        </button>
    </aside>
    <!-- Sidebar Mobile (drawer) -->
    <div class="sidebar-overlay fixed inset-0 z-40"></div>
    <aside class="sidebar-mobile fixed top-0 left-0 h-full w-64 bg-sand2/95 dark:bg-darkbrown shadow-lg flex flex-col items-center py-10 border-r-8 border-softgray dark:border-darksoft z-50 transform -translate-x-full transition-transform duration-300"
        style="background-image: url('https://www.transparenttextures.com/patterns/wood-pattern.png');">
        <!-- Logo Polaroid (sidebar mobile) -->
        <div class="mb-12 mt-8">
            <div class="rounded-polaroid shadow-polaroid bg-sand2/90 dark:bg-darksoft p-4 border-8 border-pastel-yellow dark:border-softbrown flex flex-col items-center">
                <span class="text-5xl drop-shadow-lg logo-polaroid-anim" id="logo-polaroid-mobile">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-sepia dark:text-pastel-yellow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="3" y="5" width="18" height="14" rx="3" fill="#fff1b6" stroke="#704214" stroke-width="2"/>
                        <circle cx="12" cy="12" r="3" fill="#b5d8eb" stroke="#704214" stroke-width="2"/>
                        <rect x="16" y="7" width="2" height="2" rx="1" fill="#704214"/>
                    </svg>
                </span>
            </div>
        </div>
        <!-- Navigation -->
        <nav class="flex flex-col gap-3 w-full items-center">
            <a href="#" class="flex items-center gap-4 px-5 py-3 rounded-xl bg-sand2/90 dark:bg-darksoft shadow-lg hover:bg-pastel-yellow/60 dark:hover:bg-darkbrown transition w-11/12 font-semibold text-modern dark:text-pastel-yellow group">
                <span class="text-xl text-accent group-hover:scale-110 transition"><i data-feather="home"></i></span>
                <span class="hidden md:inline">Accueil</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-3 rounded-xl bg-sand2/90 dark:bg-darksoft shadow-lg hover:bg-pastel-blue/50 dark:hover:bg-darkbrown transition w-11/12 font-semibold text-modern dark:text-pastel-yellow group">
                <span class="text-xl text-pastel-blue group-hover:scale-110 transition"><i data-feather="search"></i></span>
                <span class="hidden md:inline">Recherche</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-3 rounded-xl bg-sand2/90 dark:bg-darksoft shadow-lg hover:bg-pastel-pink/50 dark:hover:bg-darkbrown transition w-11/12 font-semibold text-modern dark:text-pastel-yellow group">
                <span class="text-xl text-pastel-pink group-hover:scale-110 transition"><i data-feather="compass"></i></span>
                <span class="hidden md:inline">Découvrir</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-3 rounded-xl bg-sand2/90 dark:bg-darksoft shadow-lg hover:bg-pastel-yellow/50 dark:hover:bg-darkbrown transition w-11/12 font-semibold text-modern dark:text-pastel-yellow group">
                <span class="text-xl text-pastel-yellow group-hover:scale-110 transition"><i data-feather="film"></i></span>
                <span class="hidden md:inline">Reels</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-3 rounded-xl bg-sand2/90 dark:bg-darksoft shadow-lg hover:bg-pastel-blue/50 dark:hover:bg-darkbrown transition w-11/12 font-semibold text-modern dark:text-pastel-yellow group">
                <span class="text-xl text-pastel_blue group-hover:scale-110 transition"><i data-feather="message-circle"></i></span>
                <span class="hidden md:inline">Messages</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-3 rounded-xl bg-sand2/90 dark:bg-darksoft shadow-lg hover:bg-pastel-pink/50 dark:hover:bg-darkbrown transition w-11/12 font-semibold text-modern dark:text-pastel-yellow group">
                <span class="text-xl text-pastel-pink group-hover:scale-110 transition"><i data-feather="bell"></i></span>
                <span class="hidden md:inline">Notifications</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-3 rounded-xl bg-sand2/90 dark:bg-darksoft shadow-lg hover:bg-pastel-yellow/50 dark:hover:bg-darkbrown transition w-11/12 font-semibold text-modern dark:text-pastel-yellow group">
                <span class="text-xl text-accent group-hover:scale-110 transition"><i data-feather="plus-square"></i></span>
                <span class="hidden md:inline">Créer</span>
            </a>
            <!-- Profil utilisateur -->
            <div class="flex flex-col items-center w-full mt-10">
                <div class="flex items-center gap-3 px-5 py-3 rounded-xl bg-gradient-to-r from-pastel-blue/30 to-pastel-pink/30 dark:from-darksoft dark:to-darkbrown shadow-xl border-2 border-softbrown dark:border-darksoft w-11/12">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="sami.60110" class="w-12 h-12 rounded-full border-2 border-sepia shadow-lg" />
                    <div class="hidden md:flex flex-col">
                        <span class="text-modern dark:text-pastel-yellow font-bold text-base">sami.60110</span>
                        <a href="#" class="text-xs text-accent dark:text-pastel-yellow hover:underline flex items-center gap-1">
                            <i data-feather="user"></i>
                            Profil
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Dark/Light Mode Toggle -->
        <button id="theme-toggle-mobile" class="toggle-btn mt-auto mb-6 flex items-center gap-2 px-7 py-3 w-48 justify-center relative group focus:outline-none">
            <span class="toggle-slider transition-all duration-300">
                <i id="theme-icon-mobile" data-feather="moon"></i>
            </span>
            <span class="ml-8 transition-colors duration-300">Mode&nbsp;<span id="theme-label-mobile">Clair</span></span>
        </button>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 p-2 md:p-4 flex flex-col items-center min-h-screen transition-all duration-300 w-full max-w-4xl"
        style="justify-content: flex-start; margin-left: 0 !important;">
        <div class="h-12 md:hidden"></div>
        <!-- Stories Retro Style (placé ici, sous le titre, pour téléphone et laptop) -->
        <div class="flex gap-5 md:gap-8 px-2 md:px-4 mb-8 md:mb-12 w-full overflow-x-auto scrollbar-hide justify-start md:justify-center">
            <!-- Exemple de 5 stories, à remplacer dynamiquement plus tard -->
            <button class="group flex flex-col items-center focus:outline-none">
                <span class="relative flex items-center justify-center w-24 h-24 md:w-32 md:h-32 rounded-full border-[6px] border-double border-sepia bg-gradient-to-br from-pastel-yellow via-pastel-pink to-pastel-blue shadow-[0_6px_32px_rgba(112,66,20,0.13)] transition-transform group-hover:scale-105 retro-story-vintage">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="story1" class="w-20 h-20 md:w-28 md:h-28 rounded-full border-[3px] border-white object-cover sepia-[.25] contrast-125 brightness-110" />
                    <span class="absolute bottom-2 right-2 w-5 h-5 bg-accent border-2 border-white rounded-full shadow"></span>
                </span>
                <span class="mt-2 text-sm font-retro text-sepia dark:text-pastel-yellow tracking-widest drop-shadow-[0_1px_0_rgba(112,66,20,0.13)]">Sami</span>
            </button>
            <button class="group flex flex-col items-center focus:outline-none">
                <span class="relative flex items-center justify-center w-24 h-24 md:w-32 md:h-32 rounded-full border-[6px] border-dotted border-pastel-pink bg-gradient-to-br from-pastel-blue via-pastel-yellow to-pastel-pink shadow-[0_6px_32px_rgba(112,66,20,0.13)] transition-transform group-hover:scale-105 retro-story-vintage">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="story2" class="w-20 h-20 md:w-28 md:h-28 rounded-full border-[3px] border-white object-cover sepia-[.25] contrast-125 brightness-110" />
                </span>
                <span class="mt-2 text-sm font-retro text-sepia dark:text-pastel-yellow tracking-widest drop-shadow-[0_1px_0_rgba(112,66,20,0.13)]">Anna</span>
            </button>
            <button class="group flex flex-col items-center focus:outline-none">
                <span class="relative flex items-center justify-center w-24 h-24 md:w-32 md:h-32 rounded-full border-[6px] border-dashed border-pastel-blue bg-gradient-to-br from-pastel-yellow via-pastel-pink to-pastel-blue shadow-[0_6px_32px_rgba(112,66,20,0.13)] transition-transform group-hover:scale-105 retro-story-vintage">
                    <img src="https://randomuser.me/api/portraits/men/65.jpg" alt="story3" class="w-20 h-20 md:w-28 md:h-28 rounded-full border-[3px] border-white object-cover sepia-[.25] contrast-125 brightness-110" />
                </span>
                <span class="mt-2 text-sm font-retro text-sepia dark:text-pastel-yellow tracking-widest drop-shadow-[0_1px_0_rgba(112,66,20,0.13)]">Lucas</span>
            </button>
            <button class="group flex flex-col items-center focus:outline-none">
                <span class="relative flex items-center justify-center w-24 h-24 md:w-32 md:h-32 rounded-full border-[6px] border-double border-pastel-yellow bg-gradient-to-br from-pastel-pink via-pastel-blue to-pastel-yellow shadow-[0_6px_32px_rgba(112,66,20,0.13)] transition-transform group-hover:scale-105 retro-story-vintage">
                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="story4" class="w-20 h-20 md:w-28 md:h-28 rounded-full border-[3px] border-white object-cover sepia-[.25] contrast-125 brightness-110" />
                </span>
                <span class="mt-2 text-sm font-retro text-sepia dark:text-pastel-yellow tracking-widest drop-shadow-[0_1px_0_rgba(112,66,20,0.13)]">Zoé</span>
            </button>
            <button class="group flex flex-col items-center focus:outline-none">
                <span class="relative flex items-center justify-center w-24 h-24 md:w-32 md:h-32 rounded-full border-[6px] border-dotted border-accent bg-gradient-to-br from-pastel-blue via-pastel-pink to-pastel-yellow shadow-[0_6px_32px_rgba(112,66,20,0.13)] transition-transform group-hover:scale-105 retro-story-vintage">
                    <img src="https://randomuser.me/api/portraits/men/12.jpg" alt="story5" class="w-20 h-20 md:w-28 md:h-28 rounded-full border-[3px] border-white object-cover sepia-[.25] contrast-125 brightness-110" />
                </span>
                <span class="mt-2 text-sm font-retro text-sepia dark:text-pastel-yellow tracking-widest drop-shadow-[0_1px_0_rgba(112,66,20,0.13)]">Alex</span>
            </button>
        </div>
        <div class="w-full max-w-3xl flex flex-col gap-4 md:gap-6 mx-0">
            <!-- Example Polaroid Post -->
            <div class="modern-card border-0 p-0 flex flex-col items-center relative overflow-hidden shadow-2xl bg-white/95 dark:bg-darkbrown">
                <img src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=600&q=80" alt="Polaroid" class="rounded-polaroid border-4 border-sepia mb-6 shadow-xl transition-transform duration-300 hover:scale-105 bg-white w-full max-w-md" />
                <div class="w-full px-4 md:px-8 pb-6 md:pb-8 pt-2 flex flex-col items-center">
                    <p class="text-modern dark:text-pastel-yellow text-lg md:text-xl font-semibold font-modern text-center">Un instant capturé, une émotion retrouvée.</p>
                    <span class="text-xs text-accent dark:text-gray-400 mt-2 md:mt-3 font-mono">#nostalgie #polaroid #vintage</span>
                </div>
            </div>
            <!-- Add more posts here -->
        </div>
        <!-- Bottom navigation bar for mobile -->
        <nav class="fixed bottom-0 left-0 right-0 z-30 bg-sand2/95 dark:bg-darksoft border-t border-softgray dark:border-darkbrown flex md:hidden justify-around items-center py-2 shadow-lg">
            <a href="#" class="flex flex-col items-center text-green-600 dark:text-green-400">
                <i data-feather="home" class="text-green-600 dark:text-green-400"></i>
                <span class="text-xs font-semibold text-green-600 dark:text-green-400">Accueil</span>
            </a>
            <a href="#" class="flex flex-col items-center text-blue-600 dark:text-blue-400">
                <i data-feather="search" class="text-blue-600 dark:text-blue-400"></i>
                <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">Recherche</span>
            </a>
            <a href="#" class="flex flex-col items-center text-yellow-500 dark:text-yellow-300">
                <i data-feather="plus-square" class="text-yellow-500 dark:text-yellow-300"></i>
                <span class="text-xs font-semibold text-yellow-500 dark:text-yellow-300">Créer</span>
            </a>
            <a href="#" class="flex flex-col items-center text-pink-500 dark:text-pink-400">
                <i data-feather="film" class="text-pink-500 dark:text-pink-400"></i>
                <span class="text-xs font-semibold text-pink-500 dark:text-pink-400">Reels</span>
            </a>
            <a href="#" class="flex flex-col items-center text-purple-600 dark:text-purple-400">
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Profil" class="w-7 h-7 rounded-full border-2 border-sepia shadow mb-1" />
                <span class="text-xs font-semibold text-purple-600 dark:text-purple-400">Profil</span>
            </a>
        </nav>
    </main>
    <script>
        // Sidebar mobile drawer logic
        const openSidebar = document.getElementById('open-sidebar');
        const sidebarMobile = document.querySelector('.sidebar-mobile');
        const sidebarOverlay = document.querySelector('.sidebar-overlay');
        openSidebar && openSidebar.addEventListener('click', () => {
            document.body.classList.add('sidebar-open');
        });
        sidebarOverlay && sidebarOverlay.addEventListener('click', () => {
            document.body.classList.remove('sidebar-open');
        });

        // Dark/Light mode toggle (desktop)
        const btn = document.getElementById('theme-toggle');
        const icon = document.getElementById('theme-icon');
        const label = document.getElementById('theme-label');
        function setThemeBtn() {
            if (document.documentElement.classList.contains('dark')) {
                icon.outerHTML = feather.icons.sun.toSvg({width: 24, height: 24, 'stroke-width': 2, color: "#ffb347"});
                label.textContent = "Sombre";
            } else {
                icon.outerHTML = feather.icons.moon.toSvg({width: 24, height: 24, 'stroke-width': 2, color: "#704214"});
                label.textContent = "Clair";
            }
        }
        btn && btn.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            setThemeBtn();
        });

        // Dark/Light mode toggle (mobile)
        const btnMobile = document.getElementById('theme-toggle-mobile');
        const iconMobile = document.getElementById('theme-icon-mobile');
        const labelMobile = document.getElementById('theme-label-mobile');
        function setThemeBtnMobile() {
            if (document.documentElement.classList.contains('dark')) {
                iconMobile.outerHTML = feather.icons.sun.toSvg({width: 24, height: 24, 'stroke-width': 2, color: "#ffb347"});
                labelMobile.textContent = "Sombre";
            } else {
                iconMobile.outerHTML = feather.icons.moon.toSvg({width: 24, height: 24, 'stroke-width': 2, color: "#704214"});
                labelMobile.textContent = "Clair";
            }
        }
        btnMobile && btnMobile.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            setThemeBtnMobile();
        });

        // Set initial icon and label
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }
        setThemeBtn();
        setThemeBtnMobile();
        feather.replace();

        // Flash effect on polaroid logo click (desktop & mobile)
        function addPolaroidFlash(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('click', () => {
                el.classList.remove('logo-polaroid-flash'); // reset if needed
                void el.offsetWidth; // force reflow
                el.classList.add('logo-polaroid-flash');
                setTimeout(() => el.classList.remove('logo-polaroid-flash'), 500);
            });
        }
        addPolaroidFlash('logo-polaroid-desktop');
        addPolaroidFlash('logo-polaroid-mobile');
    </script>
</body>
</html>