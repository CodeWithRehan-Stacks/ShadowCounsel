@extends('layouts.chat')

@section('title', 'My Profile')
@section('page-title', 'Profile')

@section('content')
<div class="overflow-y-auto h-full bg-gray-50 dark:bg-gray-950">

    <!-- Hero Banner -->
    <div class="relative h-36 bg-gradient-to-r from-violet-600 via-purple-600 to-indigo-600 overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <!-- Profile Card overlapping banner -->
    <div class="max-w-4xl mx-auto px-4">

        <!-- Avatar + Name Row -->
        <div class="relative -mt-16 mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div class="flex items-end gap-4">
                <div class="relative">
                    <img id="avatarPreview"
                         src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=7c3aed&color=fff&size=128' }}"
                         class="w-28 h-28 rounded-2xl object-cover ring-4 ring-white dark:ring-gray-950 shadow-xl">
                    <label for="profile_photo_trigger"
                           class="absolute -bottom-2 -right-2 w-8 h-8 bg-violet-600 hover:bg-violet-700 rounded-xl flex items-center justify-center cursor-pointer shadow-lg transition-all duration-200 hover:scale-110">
                        <i class="bi bi-camera-fill text-white text-sm"></i>
                    </label>
                </div>
                <div class="pb-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1.5 mt-0.5">
                        <i class="bi bi-envelope text-violet-500"></i>{{ $user->email }}
                    </p>
                    @if($user->bio)
                        <p class="text-xs text-gray-400 mt-1 italic max-w-xs truncate">"{{ $user->bio }}"</p>
                    @endif
                </div>
            </div>

            <!-- Stats Row -->
            <div class="flex gap-3 pb-1">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-center shadow-sm">
                    <p class="text-xl font-bold text-violet-600">{{ $totalChats }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Chats</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-center shadow-sm">
                    <p class="text-xl font-bold text-violet-600">{{ $totalMessages }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Messages</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-center shadow-sm">
                    <p class="text-sm font-semibold text-violet-600">{{ $user->created_at->format('M Y') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Joined</p>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
            @csrf @method('PUT')
            <input type="file" name="profile_photo" id="profile_photo_trigger" accept="image/*" class="hidden" onchange="previewAvatar(this)">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 pb-8">

                <!-- LEFT: Personal Info -->
                <div class="lg:col-span-2 space-y-5">

                    <!-- Basic Info Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80">
                            <i class="bi bi-person text-violet-500"></i>
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Basic Information</h3>
                        </div>
                        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition @error('name') border-red-400 @enderror">
                                @error('name') <p class="text-red-500 text-xs mt-1 flex items-center gap-1"><i class="bi bi-exclamation-circle"></i>{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition @error('email') border-red-400 @enderror">
                                @error('email') <p class="text-red-500 text-xs mt-1 flex items-center gap-1"><i class="bi bi-exclamation-circle"></i>{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Phone Number</label>
                                <div class="relative">
                                    <i class="bi bi-telephone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                           placeholder="+1 (555) 000-0000"
                                           class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Country</label>
                                <div class="relative">
                                    <i class="bi bi-globe2 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="country" value="{{ old('country', $user->country) }}"
                                           placeholder="Pakistan"
                                           class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition">
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Bio</label>
                                <textarea name="bio" rows="3" placeholder="Tell the world a little about yourself..."
                                          class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition resize-none">{{ old('bio', $user->bio) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white text-sm font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5">
                            <i class="bi bi-check2-circle"></i> Save Changes
                        </button>
                    </div>
                </div>

                <!-- RIGHT: Preferences + Avatar -->
                <div class="space-y-5">

                    <!-- Avatar Preview Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80">
                            <i class="bi bi-image text-violet-500"></i>
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Profile Photo</h3>
                        </div>
                        <div class="p-5 flex flex-col items-center text-center gap-3">
                            <img id="avatarPreviewSide"
                                 src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=7c3aed&color=fff&size=128' }}"
                                 class="w-24 h-24 rounded-2xl object-cover ring-4 ring-violet-500/20 shadow">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                            <label for="profile_photo_trigger"
                                   class="cursor-pointer inline-flex items-center gap-1.5 px-4 py-2 text-xs font-medium text-violet-600 border border-violet-300 dark:border-violet-700 hover:bg-violet-50 dark:hover:bg-violet-900/20 rounded-xl transition">
                                <i class="bi bi-upload"></i> Upload Photo
                            </label>
                            <p class="text-xs text-gray-400">JPG, PNG up to 2MB</p>
                        </div>
                    </div>

                    <!-- Locale Preferences Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80">
                            <i class="bi bi-translate text-violet-500"></i>
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Locale</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Timezone</label>
                                <div class="relative">
                                    <i class="bi bi-clock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="timezone" value="{{ old('timezone', $user->timezone) }}"
                                           placeholder="Asia/Karachi"
                                           class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-violet-500 transition">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Language</label>
                                <div class="relative">
                                    <i class="bi bi-flag absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <select name="language" class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-violet-500 transition appearance-none">
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
                    </div>

                    <!-- Account Details Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80">
                            <i class="bi bi-shield-check text-violet-500"></i>
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Account</h3>
                        </div>
                        <div class="p-5 space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400 text-xs">Member Since</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200 text-xs">{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm border-t border-gray-100 dark:border-gray-700 pt-3">
                                <span class="text-gray-500 dark:text-gray-400 text-xs">Email Status</span>
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-green-600 bg-green-100 dark:bg-green-900/30 px-2 py-0.5 rounded-full">
                                        <i class="bi bi-check-circle-fill"></i> Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-600 bg-amber-100 dark:bg-amber-900/30 px-2 py-0.5 rounded-full">
                                        <i class="bi bi-exclamation-circle-fill"></i> Unverified
                                    </span>
                                @endif
                            </div>
                            <div class="border-t border-gray-100 dark:border-gray-700 pt-3">
                                <a href="{{ route('settings.index') }}" class="inline-flex items-center gap-1.5 text-xs text-violet-600 hover:text-violet-700 font-medium transition">
                                    <i class="bi bi-gear"></i> Change Password
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
