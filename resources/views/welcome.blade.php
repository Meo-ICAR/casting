<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'CastingPro') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @filamentStyles
            @filamentScripts
        @else
            <style>
                /* Base styles */
                *, ::before, ::after {
                    box-sizing: border-box;
                    border-width: 0;
                    border-style: solid;
                    border-color: #e5e7eb;
                }

                html {
                    line-height: 1.5;
                    -webkit-text-size-adjust: 100%;
                    -moz-tab-size: 4;
                    tab-size: 4;
                    font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                }

                body {
                    margin: 0;
                    line-height: inherit;
                }

                /* Utility classes */
                .flex { display: flex; }
                .hidden { display: none; }
                .items-center { align-items: center; }
                .justify-between { justify-content: space-between; }
                .space-x-2 > :not([hidden]) ~ :not([hidden]) { margin-left: 0.5rem; }
                .space-x-4 > :not([hidden]) ~ :not([hidden]) { margin-left: 1rem; }
                .w-10 { width: 2.5rem; }
                .h-10 { height: 2.5rem; }
                .rounded-xl { border-radius: 0.75rem; }
                .shadow-lg { box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1); }
                .text-white { color: white; }
                .text-2xl { font-size: 1.5rem; line-height: 2rem; }
                .font-bold { font-weight: 700; }
                .bg-gradient-to-r { background-image: linear-gradient(to right, var(--tw-gradient-stops)); }
                .from-red-500 { --tw-gradient-from: #ef4444; --tw-gradient-to: rgb(239 68 68 / 0); --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to); }
                .to-orange-500 { --tw-gradient-to: #f97316; }
                .flex { display: flex; }
                .items-center { align-items: center; }
                .justify-center { justify-content: center; }
                .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
                .py-6 { padding-top: 1.5rem; padding-bottom: 1.5rem; }
                .lg\:px-8 { @media (min-width: 1024px) { padding-left: 2rem; padding-right: 2rem; }}
                .max-w-7xl { max-width: 80rem; }
                .mx-auto { margin-left: auto; margin-right: auto; }
                /* Add more utility classes as needed */
            </style>
        @endif
    </head>
    <body class="bg-gradient-to-br from-slate-50 to-white dark:from-gray-900 dark:to-gray-800 text-gray-900 dark:text-gray-100 antialiased min-h-screen">

        <!-- Navigation -->
        <nav class="max-w-7xl mx-auto px-6 py-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-r from-black-500 to-gray-500 rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-lg">🎬</span>
                    </div>
                    <span class="text-2xl font-bold bg-gradient-to-r from-black-600 to-gray-500 bg-clip-text text-black">
                        CastingPro
                    </span>
                </div>


                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/admin') }}" class="px-6 py-2.5 bg-white/80 backdrop-blur-sm hover:bg-white/100 border border-gray-200/50 hover:border-gray-300 rounded-xl text-sm font-medium shadow-sm hover:shadow-md transition-all duration-200">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ url('/admin/login') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                Accedi
                            </a>

                                <a href="{{url('/admin/register') }}" class="px-6 py-2.5 bg-gradient-to-r from-red-500 to-orange-500 hover:from-red-600 hover:to-orange-600 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                                    Registrati Gratis
                                </a>

                        @endauth
                    </div>

            </div>
        </nav>

        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-6 lg:px-8 py-20 lg:py-32">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">

                <!-- Content -->
                <div class="lg:order-1 space-y-8">
                    <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-100/80 to-orange-100/80 dark:from-red-900/30 dark:to-orange-900/30 rounded-full text-sm font-semibold text-red-700 dark:text-red-300 backdrop-blur-sm border border-red-200/50 dark:border-red-800/50">
                        🎥 La Tua Prossima Audizione Ti Aspetta
                    </div>

                    <h1 class="text-5xl lg:text-7xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-200 bg-clip-text text-transparent leading-tight">
                        CastingPro:<br>
                        <span class="bg-gradient-to-r from-gray-600 via-orange-500 to-yellow-500 bg-clip-text text-transparent">Il Tuo</span><br>
                        Palcoscenico Digitale
                    </h1>

                    <p class="text-xl text-gray-600 dark:text-gray-300 leading-relaxed max-w-lg">
                        Connettiamo talenti con registi, casting director e produzioni. Il tuo profilo raggiunge chi conta nel cinema e TV, aumentando le tue possibilità di essere scelto per le prossime produzioni.
                    </p>

                    <div class="grid md:grid-cols-2 gap-4 max-w-lg">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="group bg-gradient-to-r from-red-500 to-orange-500 hover:from-red-600 hover:to-orange-600 text-white font-semibold py-4 px-8 rounded-2xl shadow-2xl hover:shadow-3xl hover:-translate-y-1 transition-all duration-300 text-lg flex items-center justify-center space-x-3">
                                <span>Inizia Ora</span>
                                <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        @endif

                        <div class="flex items-center space-x-6 text-sm text-gray-500 dark:text-gray-400 py-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                <span>1.745 Attori Registrati</span>
                            </div>
                            <div class="w-px h-6 bg-gray-200 dark:bg-gray-700"></div>
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                                <span>123+ Produzioni</span>
                            </div>
                        </div>
                    </div>
                </div>

               <!-- Hero Carousel -->
<div class="lg:order-2 relative group">
    <div class="relative w-full aspect-[4/3] lg:aspect-square rounded-3xl overflow-hidden shadow-2xl hover:shadow-3xl transition-all duration-500">

        <!-- Carousel Container -->
        <div class="carousel-container h-full w-full relative" x-data="carousel()" x-init="$nextTick(() => init())">

            <!-- Slides -->
            <div class="carousel-slides h-full w-full overflow-hidden">
                <div class="carousel-track flex transition-transform duration-700 ease-in-out" :style="`transform: translateX(-${currentIndex * 100}%)`">

                    <!-- Slide 1: Attore protagonista -->
                    <div class="carousel-slide flex-shrink-0 w-full h-full relative">
                        <img src="{{ asset('attore.png') }}"
                             alt="Attore protagonista in audizione"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                        <div class="absolute bottom-8 left-8 text-white">
                            <h3 class="text-2xl font-bold mb-2">Protagonista</h3>
                            <p class="text-lg opacity-90">Il tuo ruolo principale</p>
                        </div>
                    </div>

                    <!-- Slide 2: Casting Director -->
                    <div class="carousel-slide flex-shrink-0 w-full h-full relative">
                          <img src="{{ asset('casting.png') }}"
                              alt="Casting director al lavoro"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                        <div class="absolute bottom-8 left-8 text-white">
                            <h3 class="text-2xl font-bold mb-2">Casting Director</h3>
                            <p class="text-lg opacity-90">Trova il talento perfetto</p>
                        </div>
                    </div>

                    <!-- Slide 3: Set cinematografico -->
                    <div class="carousel-slide flex-shrink-0 w-full h-full relative">
                          <img src="{{ asset('location.png') }}"
                             alt="Set cinematografico professionale"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                        <div class="absolute bottom-8 left-8 text-white">
                            <h3 class="text-2xl font-bold mb-2">Location</h3>
                            <p class="text-lg opacity-90">Fai girare film da te</p>
                        </div>
                    </div>

                      <!-- Slide 4: Servizi -->
                    <div class="carousel-slide flex-shrink-0 w-full h-full relative">
                        <img src="{{ asset('service.png') }}"
                              alt="Set cinematografico professionale"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                        <div class="absolute bottom-8 left-8 text-white">
                            <h3 class="text-2xl font-bold mb-2">Service</h3>
                            <p class="text-lg opacity-90">Proponi i tuoi servizi al cinema</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Indicators -->
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="goToSlide(index)"
                            :class="`w-3 h-3 rounded-full transition-all duration-300 ${currentIndex === index ? 'bg-white scale-125 shadow-lg' : 'bg-white/50 hover:bg-white'}`">
                    </button>
                </template>
            </div>

            <!-- Navigation Arrows -->
            <button class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 backdrop-blur-sm p-3 rounded-full text-white hover:text-white transition-all duration-300"
                    @click="prevSlide()" x-show="currentIndex > 0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 backdrop-blur-sm p-3 rounded-full text-white hover:text-white transition-all duration-300"
                    @click="nextSlide()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

        </div>



    </div>
</div>


            </div>
        </section>

        <!-- Features Section -->
        <section class="max-w-7xl mx-auto px-6 lg:px-8 py-32">
            <div class="text-center mb-20">
                <h2 class="text-4xl lg:text-5xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-200 bg-clip-text text-transparent mb-6">
                    Perché CastingPro?
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    La piattaforma di casting in Italia. Tecnologia avanzata, matching intelligente, risultati garantiti.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="group p-8 rounded-3xl bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border border-white/30 hover:border-white/50 hover:bg-white/70 dark:hover:bg-gray-800/70 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition-transform">
                        <span class="text-2xl">🎯</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Matching Perfetto</h3>
                    <p class="text-gray-600 dark:text-gray-300">Ricevi solo i casting perfetti per età, esperienza e tipo di ruolo.</p>
                </div>

                <div class="group p-8 rounded-3xl bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border border-white/30 hover:border-white/50 hover:bg-white/70 dark:hover:bg-gray-800/70 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition-transform">
                        <span class="text-2xl">⚡</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Visibilità Immediata</h3>
                    <p class="text-gray-600 dark:text-gray-300">Il tuo profilo è online subito e visibile a tutti i casting director.</p>
                </div>

                <div class="group p-8 rounded-3xl bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border border-white/30 hover:border-white/50 hover:bg-white/70 dark:hover:bg-gray-800/70 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-purple-400 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition-transform">
                        <span class="text-2xl">🛡️</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">100% Sicuro</h3>
                    <p class="text-gray-600 dark:text-gray-300">Protezione dati GDPR, verifiche identità, pagamenti sicuri.</p>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="max-w-4xl mx-auto px-6 lg:px-8 py-20 text-center">
            <div class="bg-gradient-to-r from-black-500 via-gray-500 to-black-500 rounded-3xl p-12 lg:p-20 shadow-2xl">
                <h2 class="text-4xl lg:text-5xl font-bold text-black mb-6 leading-tight">
                    Pronto per il Tuo<br>
                    <span class="text-6xl">Ruolo?</span>
                </h2>
                <p class="text-xl text-black/90 mb-10 max-w-2xl mx-auto">
                    Unisciti ai tanti attori che hanno trovato il loro ruolo perfetto con CastingPro.
                </p>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center px-10 py-5 bg-white text-red-600 font-bold text-xl rounded-2xl shadow-2xl hover:shadow-3xl hover:-translate-y-1 transition-all duration-300 hover:bg-gray-50">
                        Crea Profilo Gratis <span class="ml-3">→</span>
                    </a>
                @endif
            </div>
        </section>

        <!-- Admin Access -->
        @if (Route::has('filament.admin.auth.login'))
        <div class="max-w-md mx-auto px-6 pb-12 text-center">
            <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-200/50 dark:border-gray-700/50 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Professionisti del Settore?</h3>
                <div class="space-y-2">
                    <a href="{{ route('filament.admin.auth.login') }}"
                       class="block w-full text-red-600 hover:text-red-700 font-medium py-2 px-4 border border-red-200 hover:border-red-300 rounded-xl hover:bg-red-50 transition-all">
                        Iscriviti gratuitamente come professionista
                    </a>
                </div>
            </div>
        </div>
        @endif

    </body>
    <!-- Script del Carousel (alla fine del body) -->
<script>
    function carousel() {
        return {
            currentIndex: 0,
            slides: 4,
            interval: null,
            badges: [
            {
                title: '📸 Portfolio Illimitato',
                subtitle: 'Mostra il meglio di te'
            },

            {
                title: '📸 Ruoli su misura',
                subtitle: 'Casting perfetti per te'
            },
             {
                title: '🎬 Il set da te',
                subtitle: 'Ulteriori guadagni'
            },
              {
                title: '🎭 Opportunità',
                subtitle: 'Valorizza i tuoi servizi'
            },
        ],

            init() {
                this.startAutoPlay();
            },

            nextSlide() {
                this.currentIndex = (this.currentIndex + 1) % this.slides;
                this.pauseAutoPlay();
                this.startAutoPlay();
            },

            prevSlide() {
                this.currentIndex = this.currentIndex === 0 ? this.slides - 1 : this.currentIndex - 1;
                this.pauseAutoPlay();
                this.startAutoPlay();
            },

            goToSlide(index) {
                this.currentIndex = index;
                this.pauseAutoPlay();
                this.startAutoPlay();
            },

            startAutoPlay() {
                this.pauseAutoPlay();
                this.interval = setInterval(() => {
                    this.nextSlide();
                }, 5000);
            },

            pauseAutoPlay() {
                if (this.interval) {
                    clearInterval(this.interval);
                    this.interval = null;
                }
            }
        }
    }
</script>
</html>
