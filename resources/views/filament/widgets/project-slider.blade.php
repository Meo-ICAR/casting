// resources/views/filament/widgets/project-slider.blade.php

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
    <div class="p-6">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Ultimi Progetti</h3>

        <!-- Include Alpine.js directly in the component -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <div x-data="{
            currentIndex: 0,
            projects: @js($this->getProjects()),
            next() {
                this.currentIndex = (this.currentIndex + 1) % this.projects.length;
            },
            prev() {
                this.currentIndex = (this.currentIndex - 1 + this.projects.length) % this.projects.length;
            }
        }" class="relative group">
            <!-- Slider container -->
            <div class="relative overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-700 aspect-video">
                <template x-for="(project, index) in projects" :key="project.id">
                    <div x-show="currentIndex === index"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition-leave="opacity-100 scale-100"
                         x-transition-leave-to="opacity-0 scale-95"
                         class="absolute inset-0 w-full h-full flex flex-col">
                        <!-- Project Image -->
                        <div class="flex-1 relative">
                            <img :src="project.media[0]?.original_url"
                                 :alt="project.title"
                                 class="w-full h-full object-cover">

                            <!-- Project Info Overlay -->
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 to-transparent p-6">
                                <h4 class="text-xl font-bold text-white" x-text="project.title"></h4>
                                <p class="text-gray-200 text-sm mt-1" x-text="project.description?.substring(0, 100) + '...'"></p>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Navigation buttons -->
                <button @click="prev()"
                        class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button @click="next()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Indicators -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                    <template x-for="(project, index) in projects" :key="'indicator-'+project.id">
                        <button @click="currentIndex = index"
                                :class="{
                                    'w-8 bg-primary-500': currentIndex === index,
                                    'w-3 bg-white/50': currentIndex !== index
                                }"
                                class="h-1.5 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
            </div>

            <!-- Project Count -->
            <div class="mt-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                <span x-text="currentIndex + 1"></span> / <span x-text="projects.length"></span> Progetti
            </div>
        </div>
    </div>
</div>
