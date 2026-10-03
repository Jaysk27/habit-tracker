<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habit Progress - {{ $monthName }}</title>
    <meta property="og:title" content="Habit Progress - {{ $shareLink->habit?->name ?? 'All Habits' }}">
    <meta property="og:description" content="Check out my progress: {{ $shareLink->completion_rate ?? 0 }}% completion rate with a {{ $shareLink->current_streak ?? 0 }} day streak!">
    <meta property="og:url" content="{{ $shareLink->share_url }}">
    <meta name="twitter:card" content="summary_large_image">
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <span class="text-2xl font-bold">H</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">Habit Tracker</h1>
                        <p class="text-indigo-100 text-sm">Progress Summary</p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">{{ $shareLink->habit?->name ?? 'All Habits' }}</h2>
                    <p class="text-gray-500 mt-1">{{ $monthName }}</p>
                    @if($shareLink->habit)
                        <span class="inline-block mt-2 px-3 py-1 text-sm bg-indigo-100 text-indigo-700 rounded-full">
                            {{ $shareLink->habit->type === 'bad' ? 'Avoid' : 'Build' }}
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="text-center p-4 bg-indigo-50 rounded-xl">
                        <div class="text-3xl font-bold text-indigo-600">{{ $shareLink->completion_rate ?? 0 }}%</div>
                        <div class="text-sm text-gray-600 mt-1">Completion</div>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded-xl">
                        <div class="text-3xl font-bold text-orange-600">{{ $shareLink->current_streak ?? 0 }}</div>
                        <div class="text-sm text-gray-600 mt-1">Streak</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-xl">
                        <div class="text-3xl font-bold text-green-600">{{ $shareLink->total_completions ?? 0 }}</div>
                        <div class="text-sm text-gray-600 mt-1">Total</div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Share this progress</h3>
                    
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            <span>Share link</span>
                        </div>
                        <div class="flex gap-2">
                            <input type="text" id="share-url" value="{{ $shareLink->share_url }}" readonly 
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                            <button onclick="copyLink()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                                Copy
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <a href="https://twitter.com/intent/tweet?text=Check+out+my+habit+progress!+{{ $shareLink->completion_rate }}%25+completion+rate+with+a+{{ $shareLink->current_streak }}+day+streak!&url={{ urlencode($shareLink->share_url) }}" 
                           target="_blank"
                           class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-black text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            Twitter
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareLink->share_url) }}" 
                           target="_blank"
                           class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            Facebook
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($shareLink->share_url) }}&title=My+Habit+Progress&summary={{ $shareLink->completion_rate }}%25+completion+rate+with+a+{{ $shareLink->current_streak }}+day+streak!" 
                           target="_blank"
                           class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-700 text-white rounded-lg text-sm font-medium hover:bg-blue-800 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            LinkedIn
                        </a>
                    </div>
                </div>

                <div class="mt-6 text-center text-sm text-gray-500">
                    Shared {{ $shareLink->views }} time{{ $shareLink->views !== 1 ? 's' : '' }}
                </div>
            </div>
        </div>

        <p class="text-center text-sm text-gray-400 mt-6">
            Created with <span class="font-semibold text-indigo-600">Habit Tracker</span>
        </p>
    </div>

    <script>
        function copyLink() {
            const input = document.getElementById('share-url');
            input.select();
            document.execCommand('copy');
            
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Copied!';
            button.classList.add('bg-green-600');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-600');
            }, 2000);
        }
    </script>
</body>
</html>