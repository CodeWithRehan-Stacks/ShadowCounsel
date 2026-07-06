@extends('layouts.chat')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="overflow-y-auto h-full bg-gray-50 dark:bg-gray-950">

    <!-- Hero Banner -->
    <div class="h-28 bg-gradient-to-r from-slate-700 via-gray-800 to-gray-900 relative overflow-hidden flex items-end px-6 pb-4">
        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.6\' fill-rule=\'evenodd\'%3E%3Cpath d=\'M0 40L40 0H20L0 20M40 40V20L20 40\'/%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="relative">
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="bi bi-gear-wide-connected text-gray-300"></i> Settings
            </h1>
            <p class="text-xs text-gray-400 mt-0.5">Manage your account preferences and security</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-5">

            <!-- LEFT: Settings Navigation -->
            <nav class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden sticky top-4">
                    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Navigation</p>
                    </div>
                    <div class="p-2 space-y-0.5">
                        <a href="#appearance" class="settings-nav-link flex items-center gap-2.5 px-3 py-2.5 text-sm rounded-xl text-gray-600 dark:text-gray-400 hover:bg-violet-50 dark:hover:bg-violet-900/20 hover:text-violet-700 dark:hover:text-violet-300 transition group">
                            <i class="bi bi-palette2 text-base group-hover:text-violet-500"></i> Appearance
                        </a>
                        <a href="#security" class="settings-nav-link flex items-center gap-2.5 px-3 py-2.5 text-sm rounded-xl text-gray-600 dark:text-gray-400 hover:bg-violet-50 dark:hover:bg-violet-900/20 hover:text-violet-700 dark:hover:text-violet-300 transition group">
                            <i class="bi bi-shield-lock text-base group-hover:text-violet-500"></i> Security
                        </a>
                        <a href="#notifications" class="settings-nav-link flex items-center gap-2.5 px-3 py-2.5 text-sm rounded-xl text-gray-600 dark:text-gray-400 hover:bg-violet-50 dark:hover:bg-violet-900/20 hover:text-violet-700 dark:hover:text-violet-300 transition group">
                            <i class="bi bi-bell text-base group-hover:text-violet-500"></i> Notifications
                        </a>
                        <a href="#danger" class="settings-nav-link flex items-center gap-2.5 px-3 py-2.5 text-sm rounded-xl text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition group">
                            <i class="bi bi-exclamation-triangle text-base"></i> Danger Zone
                        </a>
                    </div>
                    <div class="p-3 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-2 text-xs text-violet-600 dark:text-violet-400 hover:underline">
                            <i class="bi bi-person-circle"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </nav>

            <!-- RIGHT: Settings Panels -->
            <div class="lg:col-span-3 space-y-5">

                <!-- ── APPEARANCE ── -->
                <section id="appearance">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80">
                            <div class="w-7 h-7 rounded-lg bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center">
                                <i class="bi bi-palette2 text-violet-600 text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Appearance & Language</h3>
                                <p class="text-xs text-gray-400">Customize how the app looks and feels</p>
                            </div>
                        </div>

                        <form action="{{ route('settings.update') }}" method="POST" class="divide-y divide-gray-100 dark:divide-gray-700">
                            @csrf @method('PUT')

                            <!-- Dark Mode -->
                            <div class="flex items-center justify-between px-5 py-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center mt-0.5">
                                        <i class="bi bi-moon-stars text-gray-500 dark:text-gray-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200">Dark Mode</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Reduce eye strain with a darker interface</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                    <input type="checkbox" name="dark_mode" value="1" {{ $setting->dark_mode ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                                </label>
                            </div>

                            <!-- Notifications -->
                            <div class="flex items-center justify-between px-5 py-4" id="notifications">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center mt-0.5">
                                        <i class="bi bi-bell text-gray-500 dark:text-gray-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200">Push Notifications</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Receive alerts and in-app notifications</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                    <input type="checkbox" name="notifications" value="1" {{ $setting->notifications ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                                </label>
                            </div>

                            <!-- Language -->
                            <div class="flex items-center justify-between px-5 py-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center mt-0.5">
                                        <i class="bi bi-translate text-gray-500 dark:text-gray-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200">Language</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Choose your preferred interface language</p>
                                    </div>
                                </div>
                                <select name="language" class="text-sm px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-violet-500 transition">
                                    <option value="en" {{ $setting->language === 'en' ? 'selected' : '' }}>🇺🇸 English</option>
                                    <option value="ar" {{ $setting->language === 'ar' ? 'selected' : '' }}>🇸🇦 Arabic</option>
                                    <option value="fr" {{ $setting->language === 'fr' ? 'selected' : '' }}>🇫🇷 French</option>
                                    <option value="es" {{ $setting->language === 'es' ? 'selected' : '' }}>🇪🇸 Spanish</option>
                                    <option value="de" {{ $setting->language === 'de' ? 'selected' : '' }}>🇩🇪 German</option>
                                    <option value="ur" {{ $setting->language === 'ur' ? 'selected' : '' }}>🇵🇰 Urdu</option>
                                </select>
                            </div>

                            <div class="px-5 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end">
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                                    <i class="bi bi-check2"></i> Save Preferences
                                </button>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- ── SECURITY / PASSWORD ── -->
                <section id="security">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80">
                            <div class="w-7 h-7 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <i class="bi bi-shield-lock text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Security</h3>
                                <p class="text-xs text-gray-400">Manage your password and account security</p>
                            </div>
                        </div>

                        <form action="{{ route('settings.password') }}" method="POST" class="p-5 space-y-4">
                            @csrf @method('PUT')

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">
                                        Current Password
                                    </label>
                                    <div class="relative">
                                        <i class="bi bi-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                        <input type="password" name="current_password" required
                                               class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('current_password') border-red-400 @enderror"
                                               placeholder="••••••••">
                                    </div>
                                    @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">
                                        New Password
                                    </label>
                                    <div class="relative">
                                        <i class="bi bi-key absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                        <input type="password" name="password" required
                                               class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('password') border-red-400 @enderror"
                                               placeholder="Min. 8 characters">
                                    </div>
                                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">
                                        Confirm Password
                                    </label>
                                    <div class="relative">
                                        <i class="bi bi-key-fill absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                        <input type="password" name="password_confirmation" required
                                               class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                               placeholder="Repeat password">
                                    </div>
                                </div>
                            </div>

                            <div class="pt-1 flex justify-end">
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                                    <i class="bi bi-shield-check"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- ── DANGER ZONE ── -->
                <section id="danger">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-red-200 dark:border-red-900/60 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 px-5 py-3.5 border-b border-red-100 dark:border-red-900/50 bg-red-50 dark:bg-red-900/10">
                            <div class="w-7 h-7 rounded-lg bg-red-100 dark:bg-red-900/40 flex items-center justify-center">
                                <i class="bi bi-exclamation-triangle text-red-600 text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-red-700 dark:text-red-400">Danger Zone</h3>
                                <p class="text-xs text-red-400/80 dark:text-red-400/60">Irreversible and destructive actions</p>
                            </div>
                        </div>

                        <div class="p-5">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800/50 rounded-xl p-4">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Delete Account</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        Once deleted, your account and all data — chats, messages, settings — will be permanently removed. This cannot be undone.
                                    </p>
                                </div>
                                <button type="button"
                                        onclick="document.getElementById('deleteAccountModal').classList.remove('hidden')"
                                        class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-red-600 border border-red-300 dark:border-red-700 hover:bg-red-600 hover:text-white rounded-xl transition-all duration-200">
                                    <i class="bi bi-trash3"></i> Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

            </div><!-- end right col -->
        </div><!-- end grid -->
    </div><!-- end container -->
</div><!-- end scroll wrapper -->

<!-- ── DELETE ACCOUNT MODAL ── -->
<div id="deleteAccountModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md border border-gray-200 dark:border-gray-700 overflow-hidden"
         onclick.stop="">
        <!-- Modal Header -->
        <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-700 bg-red-50 dark:bg-red-900/20">
            <div class="w-9 h-9 rounded-full bg-red-100 dark:bg-red-900/40 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-exclamation-triangle-fill text-red-600 text-base"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Delete your account?</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">This action is permanent and cannot be reversed.</p>
            </div>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('settings.destroy-account') }}" method="POST" class="p-5 space-y-4">
            @csrf @method('DELETE')

            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/50 rounded-xl px-4 py-3">
                <p class="text-xs text-amber-700 dark:text-amber-400 flex items-start gap-2">
                    <i class="bi bi-info-circle-fill flex-shrink-0 mt-0.5"></i>
                    All your chats, messages, profile data, and settings will be permanently deleted.
                </p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">
                    Confirm with your password
                </label>
                <div class="relative">
                    <i class="bi bi-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="password" name="password" placeholder="Enter your password" required
                           class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition @error('password') border-red-400 @enderror">
                </div>
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-1">
                <button type="button"
                        onclick="document.getElementById('deleteAccountModal').classList.add('hidden')"
                        class="flex-1 py-2.5 text-sm font-medium border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 text-sm font-semibold bg-red-600 hover:bg-red-700 text-white rounded-xl shadow-sm transition-all duration-200">
                    <i class="bi bi-trash3 mr-1.5"></i>Yes, Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Close modal on backdrop click
    document.getElementById('deleteAccountModal').addEventListener('click', function (e) {
        if (e.target === this) this.classList.add('hidden');
    });

    // Highlight active nav link on scroll
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.settings-nav-link');
    const scrollArea = document.querySelector('.overflow-y-auto');

    if (scrollArea) {
        scrollArea.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(s => {
                if (scrollArea.scrollTop + 80 >= s.offsetTop) current = s.id;
            });
            navLinks.forEach(l => {
                l.classList.toggle('bg-violet-50', l.getAttribute('href') === '#' + current);
                l.classList.toggle('dark:bg-violet-900/20', l.getAttribute('href') === '#' + current);
                l.classList.toggle('text-violet-700', l.getAttribute('href') === '#' + current);
                l.classList.toggle('dark:text-violet-300', l.getAttribute('href') === '#' + current);
                l.classList.toggle('font-semibold', l.getAttribute('href') === '#' + current);
            });
        });
    }
</script>
@endsection
