<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('shop.index') }}"
                        class="flex items-center space-x-2 text-gray-800 hover:text-gray-900 transition duration-150 ease-in-out">
                        {{-- Ganti dengan logo Anda jika ada, contoh: --}}
                        <img src="{{ asset('images/toko.png') }}" alt="Logo" class="h-8 w-auto">
                        <span class="font-bold text-xl">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                </div>

                <div class="hidden space-x-6 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.index') && !request()->query('category')">
                        {{ __('Semua Produk') }}
                    </x-nav-link>

                    <div class="relative flex items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-700 hover:text-gray-900 hover:border-gray-300 focus:outline-none focus:text-gray-900 focus:border-gray-300 transition duration-150 ease-in-out border-b-2 border-transparent">
                                    <div>Kategori</div>
                                    <div class="ml-1">
                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @foreach ($navCategories as $category)
                                    <x-dropdown-link :href="route('shop.index', ['category' => $category->slug])">
                                        {{ $category->name }}
                                    </x-dropdown-link>
                                @endforeach
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <a href="{{ route('cart.index') }}"
                    class="relative p-2 text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c.51 0 .962-.343 1.087-.835l1.823-6.841A1.125 1.125 0 0018.102 6H4.227l-1.14-4.275A1.125 1.125 0 002.25 1H1.5M16.5 18.75h.008v.008h-.008v-.008z" />
                    </svg>
                    @if ($cartCount > 0)
                        <span
                            class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-xs font-semibold text-white bg-red-500 rounded-full ring-2 ring-white">{{ $cartCount }}</span>
                    @endif
                </a>

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-900 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ml-1">
                                    <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if (Auth::user()->role === 'admin')
                                <x-dropdown-link :href="route('admin.dashboard')">
                                    {{ __('Admin Dashboard') }}
                                </x-dropdown-link>
                            @else
                                <x-dropdown-link :href="route('customer.dashboard')">
                                    {{ __('My Account') }}
                                </x-dropdown-link>
                            @endif
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profil') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm text-gray-700 hover:text-gray-900 font-semibold py-2 px-3 rounded-md transition duration-150 ease-in-out">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="ml-4 text-sm text-white bg-indigo-600 hover:bg-indigo-700 font-semibold py-2 px-3 rounded-md transition duration-150 ease-in-out">Daftar</a>
                @endauth
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.index') && !request()->query('category')">
                {{ __('Semua Produk') }}
            </x-responsive-nav-link>

            <div class="border-t border-gray-200">
                <button @click="openDropdown = ! openDropdown"
                    class="flex w-full items-center pl-3 pr-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                    <span>Kategori</span>
                    <div class="ml-auto">
                        <svg class="w-4 h-4 fill-current" :class="{ 'rotate-180': openDropdown, '': !openDropdown }"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
                <div x-show="openDropdown" class="pl-8 pr-4 py-2 space-y-1">
                    @foreach ($navCategories as $category)
                        <x-responsive-nav-link :href="route('shop.index', ['category' => $category->slug])">
                            {{ $category->name }}
                        </x-responsive-nav-link>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    @if (Auth::user()->role === 'admin')
                        <x-responsive-nav-link :href="route('admin.dashboard')">
                            {{ __('Admin Dashboard') }}
                        </x-responsive-nav-link>
                    @else
                        <x-responsive-nav-link :href="route('customer.dashboard')">
                            {{ __('My Account') }}
                        </x-responsive-nav-link>
                    @endif
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profil') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4 py-2">
                    <a href="{{ route('login') }}"
                        class="block text-sm text-gray-700 hover:text-gray-900 font-semibold py-2">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="block text-sm text-white bg-indigo-600 hover:bg-indigo-700 font-semibold py-2 px-3 rounded-md mt-2 text-center">Daftar</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
