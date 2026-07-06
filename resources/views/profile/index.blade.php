@extends('layouts.chat')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="overflow-y-auto h-full bg-gray-50 dark:bg-gray-950">

    <!-- Hero Banner -->
    <div class="relative h-40 overflow-hidden bg-[#0f1117]">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-violet-900/40 via-[#0f1117] to-[#0f1117]"></div>
        <div class="absolute bottom-0 right-0 translate-y-1/3 translate-x-1/4 w-72 h-72 bg-purple-600/10 rounded-full blur-3xl"></div>
        <div class="absolute top-0 left-0 -translate-y-1/2 w-64 h-64 bg-violet-600/10 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6">

        <!-- Avatar + Name Row -->
        <div class="relative -mt-14 mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div class="flex items-end gap-4">
                <div class="relative flex-shrink-0">
                    <img id="avatarPreview"
                         src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=7c3aed&color=fff&size=128' }}"
                         class="w-28 h-28 rounded-2xl object-cover ring-4 ring-white dark:ring-gray-950 shadow-xl">
                    <label for="profile_photo_trigger"
                           class="absolute -bottom-2 -right-2 w-8 h-8 bg-violet-600 hover:bg-violet-500 rounded-xl flex items-center justify-center cursor-pointer shadow-lg transition-all duration-200 hover:scale-110">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </label>
                </div>
                <div class="pb-2">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $user->name }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1.5 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        {{ $user->email }}
                    </p>
                    @if($user->bio)
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 italic max-w-sm truncate">"{{ $user->bio }}"</p>
                    @endif
                </div>
            </div>

            <!-- Stats Row -->
            <div class="flex gap-3 pb-2">
                <div class="bg-white dark:bg-[#1a1d24] border border-gray-200 dark:border-gray-800/60 rounded-2xl px-5 py-3 text-center shadow-sm">
                    <p class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $totalChats }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Chats</p>
                </div>
                <div class="bg-white dark:bg-[#1a1d24] border border-gray-200 dark:border-gray-800/60 rounded-2xl px-5 py-3 text-center shadow-sm">
                    <p class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $totalMessages }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Messages</p>
                </div>
                <div class="bg-white dark:bg-[#1a1d24] border border-gray-200 dark:border-gray-800/60 rounded-2xl px-5 py-3 text-center shadow-sm">
                    <p class="text-sm font-bold text-violet-600 dark:text-violet-400">{{ $user->created_at->format('M Y') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Joined</p>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
            @csrf @method('PUT')
            <input type="file" name="profile_photo" id="profile_photo_trigger" accept="image/*" class="hidden" onchange="previewAvatar(this)">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 pb-10">

                <!-- LEFT: Personal Info -->
                <div class="lg:col-span-2 space-y-5">

                    <!-- Basic Info Card -->
                    <div class="bg-white dark:bg-[#1a1d24] rounded-2xl border border-gray-200 dark:border-gray-800/60 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-800/60">
                            <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-500/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Basic Information</h3>
                                <p class="text-xs text-gray-400">Update your personal details</p>
                            </div>
                        </div>
                        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required placeholder="John Doe"
                                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all @error('name') border-red-400 @enderror">
                                @error('name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="you@example.com"
                                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all @error('email') border-red-400 @enderror">
                                @error('email') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Phone Number</label>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                           placeholder="+1 (555) 000-0000"
                                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Country</label>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <input type="text" name="country" value="{{ old('country', $user->country) }}"
                                           placeholder="Pakistan"
                                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Bio</label>
                                <textarea name="bio" rows="3" placeholder="Tell the world a little about yourself..."
                                          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all resize-none">{{ old('bio', $user->bio) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-violet-600 hover:bg-violet-500 active:bg-violet-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-violet-500/20 hover:shadow-md transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Save Changes
                        </button>
                    </div>
                </div>

                <!-- RIGHT: Sidebar Cards -->
                <div class="space-y-5">

                    <!-- Avatar Card -->
                    <div class="bg-white dark:bg-[#1a1d24] rounded-2xl border border-gray-200 dark:border-gray-800/60 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-800/60">
                            <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-500/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Profile Photo</h3>
                        </div>
                        <div class="p-5 flex flex-col items-center text-center gap-4">
                            <img id="avatarPreviewSide"
                                 src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=7c3aed&color=fff&size=128' }}"
                                 class="w-20 h-20 rounded-2xl object-cover shadow ring-2 ring-violet-500/30">
                            <div>
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400 truncate max-w-[14rem]">{{ $user->email }}</p>
                            </div>
                            <label for="profile_photo_trigger"
                                   class="cursor-pointer w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700/60 hover:border-violet-400 dark:hover:border-violet-500/50 hover:bg-violet-50 dark:hover:bg-violet-500/10 rounded-xl transition-all">
                                <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                Upload Photo
                            </label>
                            <p class="text-xs text-gray-400">JPG, PNG up to 2MB</p>
                        </div>
                    </div>

                    <!-- Locale Card -->
                    <div class="bg-white dark:bg-[#1a1d24] rounded-2xl border border-gray-200 dark:border-gray-800/60 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-800/60">
                            <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-500/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Locale</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Timezone</label>
                                <input type="text" name="timezone" value="{{ old('timezone', $user->timezone) }}"
                                       placeholder="Asia/Karachi"
                                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Language</label>
                                <select name="language" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all appearance-none">
                                    <option value="en" {{ ($user->language ?? 'en') === 'en' ? 'selected' : '' }}>🇺🇸 English</option>
                                    <option value="ar" {{ ($user->language ?? '') === 'ar' ? 'selected' : '' }}>🇸🇦 Arabic</option>
                                    <option value="fr" {{ ($user->language ?? '') === 'fr' ? 'selected' : '' }}>🇫🇷 French</option>
                                    <option value="es" {{ ($user->language ?? '') === 'es' ? 'selected' : '' }}>🇪🇸 Spanish</option>
                                    <option value="de" {{ ($user->language ?? '') === 'de' ? 'selected' : '' }}>🇩🇪 German</option>
                                    <option value="ur" {{ ($user->language ?? '') === 'ur' ? 'selected' : '' }}>🇵🇰 Urdu</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Account Card -->
                    <div class="bg-white dark:bg-[#1a1d24] rounded-2xl border border-gray-200 dark:border-gray-800/60 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-800/60">
                            <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-500/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Account</h3>
                        </div>
                        <div class="p-5 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Member Since</span>
                                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center border-t border-gray-100 dark:border-gray-800/60 pt-3">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Email Status</span>
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-500/10 px-2 py-0.5 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-500/10 px-2 py-0.5 rounded-full">
                                        Unverified
                                    </span>
                                @endif
                            </div>
                            <div class="border-t border-gray-100 dark:border-gray-800/60 pt-3">
                                <a href="{{ route('settings.index') }}" class="inline-flex items-center gap-1.5 text-xs text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 font-medium transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                    Change Password
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('avatarPreview').src    = e.target.result;
            document.getElementById('avatarPreviewSide').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
