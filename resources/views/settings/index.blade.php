@extends('layouts.chat')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="overflow-y-auto h-full bg-gray-50 dark:bg-gray-950">


    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
        <div class="space-y-6">

                <!-- ── PROFILE ── -->
                <section id="profile">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="bg-white dark:bg-[#1a1d24] rounded-2xl border border-gray-200 dark:border-gray-800/60 shadow-sm overflow-hidden mb-5">
                            <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-800/60">
                                <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-500/10 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Profile Information</h3>
                                    <p class="text-xs text-gray-400">Update your account's profile information and email address</p>
                                </div>
                            </div>
                            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2 flex flex-col items-start gap-4 mb-2">
                                    <img id="avatarPreviewSettings"
                                         src="{{ auth()->user()->profile_photo_path ? Storage::url(auth()->user()->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=7c3aed&color=fff&size=128' }}"
                                         class="w-20 h-20 rounded-2xl object-cover shadow ring-2 ring-violet-500/30">
                                    <input type="file" name="profile_photo" id="profile_photo_settings" accept="image/*" class="hidden" onchange="previewSettingsAvatar(this)">
                                    <label for="profile_photo_settings"
                                           class="cursor-pointer inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700/60 hover:border-violet-400 dark:hover:border-violet-500/50 hover:bg-violet-50 dark:hover:bg-violet-500/10 rounded-xl transition-all">
                                        <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        Change Photo
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Full Name</label>
                                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 @error('name') border-red-400 @enderror">
                                    @error('name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 @error('email') border-red-400 @enderror">
                                    @error('email') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Phone Number</label>
                                    <input type="text" name="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Country</label>
                                    <input type="text" name="country" value="{{ old('country', auth()->user()->country) }}"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Bio</label>
                                    <textarea name="bio" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500">{{ old('bio', auth()->user()->bio) }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Timezone</label>
                                    <input type="text" name="timezone" value="{{ old('timezone', auth()->user()->timezone) }}"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500">
                                </div>
                            </div>
                            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800/60 flex justify-end">
                                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                                    Save Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </section>

                <!-- ── NOTIFICATIONS ── -->
                <section id="notifications">
                    <div class="bg-white dark:bg-[#1a1d24] rounded-2xl border border-gray-200 dark:border-gray-800/60 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-800/60">
                            <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-500/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Notifications</h3>
                                <p class="text-xs text-gray-400">Manage your alerts and in-app notifications</p>
                            </div>
                        </div>

                        <form action="{{ route('settings.update') }}" method="POST" class="divide-y divide-gray-100 dark:divide-gray-800/60">
                            @csrf @method('PUT')

                            <!-- Notifications -->
                            <div class="flex items-center justify-between px-5 py-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mt-0.5 flex-shrink-0">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Push Notifications</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Receive alerts and in-app notifications</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                    <input type="checkbox" name="notifications" value="1" {{ $setting->notifications ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                                </label>
                            </div>

                            <div class="px-5 py-4 flex justify-end">
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold rounded-xl shadow-sm shadow-violet-500/20 hover:shadow-md transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Save Preferences
                                </button>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- ── SECURITY / PASSWORD ── -->
                <section id="security">
                    <div class="bg-white dark:bg-[#1a1d24] rounded-2xl border border-gray-200 dark:border-gray-800/60 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-800/60">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
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
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Current Password</label>
                                    <input type="password" name="current_password" required placeholder="••••••••"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all @error('current_password') border-red-400 @enderror">
                                    @error('current_password') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">New Password</label>
                                    <input type="password" name="password" required placeholder="Min. 8 characters"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all @error('password') border-red-400 @enderror">
                                    @error('password') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Confirm Password</label>
                                    <input type="password" name="password_confirmation" required placeholder="Repeat password"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                </div>
                            </div>

                            <div class="flex justify-end pt-1">
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl shadow-sm shadow-blue-500/20 hover:shadow-md transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- ── DANGER ZONE ── -->
                <section id="danger">
                    <div class="bg-white dark:bg-[#1a1d24] rounded-2xl border border-red-200 dark:border-red-900/40 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-4 border-b border-red-100 dark:border-red-900/30 bg-red-50 dark:bg-red-500/5">
                            <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-500/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-red-700 dark:text-red-400">Danger Zone</h3>
                                <p class="text-xs text-red-500/70 dark:text-red-400/60">Irreversible and destructive actions</p>
                            </div>
                        </div>

                        <div class="p-5">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-red-50 dark:bg-red-500/5 border border-red-100 dark:border-red-900/30 rounded-xl p-4">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Delete Account</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm">
                                        Once deleted, your account and all data — chats, messages, settings — will be permanently removed.
                                    </p>
                                </div>
                                <button type="button"
                                        onclick="document.getElementById('deleteAccountModal').classList.remove('hidden')"
                                        class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/60 hover:bg-red-600 hover:border-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:border-red-600 dark:hover:text-white rounded-xl transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
        </div><!-- end space-y-6 -->
    </div><!-- end max-w-3xl -->
</div><!-- end scroll wrapper -->

<!-- ── DELETE ACCOUNT MODAL ── -->
<div id="deleteAccountModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-white dark:bg-[#1a1d24] rounded-2xl shadow-2xl w-full max-w-md border border-gray-200 dark:border-gray-800/60 overflow-hidden">
        <!-- Modal Header -->
        <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-800/60 bg-red-50 dark:bg-red-500/5">
            <div class="w-9 h-9 rounded-full bg-red-100 dark:bg-red-500/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Delete your account?</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">This action is permanent and cannot be reversed.</p>
            </div>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('settings.destroy-account') }}" method="POST" class="p-5 space-y-4">
            @csrf @method('DELETE')

            <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-xl px-4 py-3">
                <p class="text-xs text-amber-700 dark:text-amber-300 flex items-start gap-2">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    All your chats, messages, profile data, and settings will be permanently deleted.
                </p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">Confirm with your password</label>
                <input type="password" name="password" placeholder="Enter your password" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all @error('password') border-red-400 @enderror">
                @error('password') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-1">
                <button type="button"
                        onclick="document.getElementById('deleteAccountModal').classList.add('hidden')"
                        class="flex-1 py-2.5 text-sm font-medium border border-gray-200 dark:border-gray-700/60 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 text-sm font-semibold bg-red-600 hover:bg-red-500 text-white rounded-xl shadow-sm shadow-red-500/20 transition-all duration-200">
                    Yes, Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Close delete modal on backdrop click
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
                const isActive = l.getAttribute('href') === '#' + current;
                l.classList.toggle('bg-violet-50', isActive);
                l.classList.toggle('dark:bg-violet-500/10', isActive);
                l.classList.toggle('text-violet-700', isActive);
                l.classList.toggle('dark:text-violet-300', isActive);
                l.classList.toggle('font-semibold', isActive);
            });
        });
    }

    function previewSettingsAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('avatarPreviewSettings').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
