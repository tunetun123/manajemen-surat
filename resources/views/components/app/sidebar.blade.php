<div class="min-w-fit">
    <!-- Sidebar backdrop (mobile only) -->
    <div
        class="fixed inset-0 bg-gray-900/30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'"
        aria-hidden="true"
        x-cloak
    ></div>

    <!-- Sidebar -->
    <div
        id="sidebar"
        class="flex lg:flex! flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-[100dvh] overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:w-64! shrink-0 bg-white dark:bg-gray-800 p-4 transition-all duration-200 ease-in-out border-r border-gray-200 dark:border-gray-700/60"
        :class="sidebarOpen ? 'max-lg:translate-x-0' : 'max-lg:-translate-x-64'"
        @click.outside="sidebarOpen = false"
        @keydown.escape.window="sidebarOpen = false"
    >

        <!-- Sidebar header -->
        <div class="flex justify-between mb-10 pr-3 sm:px-2">
            <!-- Close button -->
            <button class="lg:hidden text-gray-500 hover:text-gray-400" @click.stop="sidebarOpen = !sidebarOpen" aria-controls="sidebar" :aria-expanded="sidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                </svg>
            </button>
            <!-- Logo -->
            <a class="block" href="{{ route('dashboard') }}">
                <svg class="fill-violet-500" xmlns="http://www.w3.org/2000/svg" width="32" height="32">
                    <path d="M31.956 14.8C31.372 6.92 25.08.628 17.2.044V5.76a9.04 9.04 0 0 0 9.04 9.04h5.716ZM14.8 26.24v5.716C6.92 31.372.63 25.08.044 17.2H5.76a9.04 9.04 0 0 1 9.04 9.04Zm11.44-9.04h5.716c-.584 7.88-6.876 14.172-14.756 14.756V26.24a9.04 9.04 0 0 1 9.04-9.04ZM.044 14.8C.63 6.92 6.92.628 14.8.044V5.76a9.04 9.04 0 0 1-9.04 9.04H.044Z" />
                </svg>                
            </a>
        </div>

        <!-- Links -->
        <div class="space-y-8">
            <!-- Pages group -->
            <div>
                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6" aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Menu</span>
                </h3>
                <ul class="mt-3">
                    <!-- Dashboard -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('dashboard')){{ 'bg-violet-500/[0.12] dark:bg-violet-500/[0.24]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('dashboard')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('dashboard') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('dashboard')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M5.936.278A7.983 7.983 0 0 1 8 0a8 8 0 1 1-8 8c0-.722.104-1.413.278-2.064a1 1 0 1 1 1.932.516A5.99 5.99 0 0 0 2 8a6 6 0 1 0 6-6c-.53 0-1.045.076-1.548.21A1 1 0 1 1 5.936.278Z" />
                                    <path d="M6.068 7.482A2.003 2.003 0 0 0 8 10a2 2 0 1 0-.518-3.932L3.707 2.293a1 1 0 0 0-1.414 1.414l3.775 3.775Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Dashboard</span>
                            </div>
                        </a>
                    </li>
                    
                    <!-- Kategori -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('categories')){{ 'bg-violet-500/[0.12] dark:bg-violet-500/[0.24]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('categories')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('categories') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('categories')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M9 6.855A3.502 3.502 0 0 0 8 0a3.5 3.5 0 0 0-1 6.855v1.656L5.534 9.65a3.5 3.5 0 1 0 1.229 1.578L8 10.267l1.238.962a3.5 3.5 0 1 0 1.229-1.578L9 8.511V6.855ZM6.5 3.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm4.803 8.095c.005-.005.01-.01.013-.016l.012-.016a1.5 1.5 0 1 1-.025.032ZM3.5 11c.474 0 .897.22 1.171.563l.013.016.013.017A1.5 1.5 0 1 1 3.5 11Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Kategori</span>
                            </div>
                        </a>
                    </li>

                    <!-- Jenis Dokumen -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('document-types')){{ 'bg-violet-500/[0.12] dark:bg-violet-500/[0.24]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('document-types')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('document-types') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('document-types')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M11.44 2.12l-8.32 8.32C2.12 11.44.8 13.04.8 15.2c0 2.16 1.32 3.76 2.32 4.76l8.32-8.32c1.04-1.04 2.64-1.04 3.68 0 1.04 1.04 1.04 2.64 0 3.68l-8.32 8.32C5.64 24.8 3.6 24.8 2.32 23.52c-1.28-1.28-1.28-3.32 0-4.6l8.32-8.32c2.08-2.08 5.28-2.08 7.36 0 2.08 2.08 2.08 5.28 0 7.36l-8.32 8.32c-3.12 3.12-7.92 3.12-11.04 0-3.12-3.12-3.12-7.92 0-11.04l8.32-8.32c4.16-4.16 10.56-4.16 14.72 0 4.16 4.16 4.16 10.56 0 14.72l-8.32 8.32 1.44 1.44 8.32-8.32c4.96-4.96 4.96-12.64 0-17.6-4.96-4.96-12.64-4.96-17.6 0z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Jenis Dokumen</span>
                            </div>
                        </a>
                    </li>

                    <!-- Dokumen -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('documents')){{ 'bg-violet-500/[0.12] dark:bg-violet-500/[0.24]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('documents')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('documents') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('documents')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M14 0H2c-.6 0-1 .4-1 1v14c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V1c0-.6-.4-1-1-1zM3 2h10v2H3V2zm10 12H3v-2h10v2zm0-4H3V8h10v2z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Dokumen</span>
                            </div>
                        </a>
                    </li>

                    <!-- Pengguna -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('users')){{ 'bg-violet-500/[0.12] dark:bg-violet-500/[0.24]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('users')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('users') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('users')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8Zm0 12c-2.2 0-4-1.8-4-4s1.8-4 4-4 4 1.8 4 4-1.8 4-4 4Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Pengguna</span>
                            </div>
                        </a>
                    </li>

                    <!-- Pengaturan -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('settings')){{ 'bg-violet-500/[0.12] dark:bg-violet-500/[0.24]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('settings')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('settings') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('settings')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M14.4 7.2h-1.3c-.2-1-.6-2-1.2-2.8l.9-.9c.4-.4.4-1 0-1.4l-1.4-1.4c-.4-.4-1-.4-1.4 0l-.9.9c-.8-.6-1.8-1-2.8-1.2V.8c0-.4-.4-.8-.8-.8h-2c-.4 0-.8.4-.8.8v1.3c-1 .2-2 .6-2.8 1.2l-.9-.9c-.4-.4-1-.4-1.4 0l-1.4 1.4c-.4.4-.4 1 0 1.4l.9.9c-.6.8-1 1.8-1.2 2.8H.8c-.4 0-.8.4-.8.8v2c0 .4.4.8.8.8h1.3c.2 1 .6 2 1.2 2.8l-.9.9c-.4.4-.4 1 0 1.4l1.4 1.4c.4.4 1 .4 1.4 0l.9-.9c.8.6 1.8 1 2.8 1.2v1.3c0 .4.4.8.8.8h2c.4 0 .8-.4.8-.8v-1.3c1-.2 2-.6 2.8-1.2l.9.9c.4.4 1 .4 1.4 0l1.4-1.4c.4-.4.4-1 0-1.4l-.9-.9c.6-.8 1-1.8 1.2-2.8h1.3c.4 0 .8-.4.8-.8v-2c0-.4-.4-.8-.8-.8zM8 10.4c-1.3 0-2.4-1.1-2.4-2.4s1.1-2.4 2.4-2.4 2.4 1.1 2.4 2.4-1.1 2.4-2.4 2.4z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Pengaturan</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Expand / collapse button -->
        <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
            <div class="px-3 py-2">
                <button @click="sidebarExpanded = !sidebarExpanded">
                    <span class="sr-only">Expand / collapse sidebar</span>
                    <svg class="w-6 h-6 fill-current sidebar-expanded:rotate-180" viewBox="0 0 24 24">
                        <path class="text-gray-400" d="M19.586 11l-5-5L16 4.586 23.414 12 16 19.414 14.586 18l5-5H7v-2z" />
                        <path class="text-gray-600" d="M3 23H1V1h2z" />
                    </svg>
                </button>
            </div>
        </div>

    </div>
</div>