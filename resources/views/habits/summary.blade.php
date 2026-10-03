<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Progress Summary') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-white min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- AI Coach Banner -->
            <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-4 mb-6 text-white">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <span class="font-medium">Need help improving your habits?</span>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('ai-chat') }}" class="px-3 py-1.5 text-sm bg-white/20 text-white rounded-full hover:bg-white/30 transition-colors">Discuss Progress</a>
                        <a href="{{ route('ai-chat') }}" class="px-3 py-1.5 text-sm bg-white/20 text-white rounded-full hover:bg-white/30 transition-colors">Get Suggestions</a>
                    </div>
                </div>
            </div>

            <!-- Month/Year Navigation -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                <div class="flex items-center gap-2">
                    <a href="{{ route('habits.summary', ['year' => $month == 1 ? $year - 1 : $year, 'month' => $month == 1 ? 12 : $month - 1]) }}"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium">
                        ← Prev
                    </a>

                    <div class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-bold text-lg min-w-fit shadow-md">
                        {{ $monthName }}
                    </div>

                    <a href="{{ route('habits.summary', ['year' => $month == 12 ? $year + 1 : $year, 'month' => $month == 12 ? 1 : $month + 1]) }}"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium">
                        Next →
                    </a>
                </div>

                <form action="{{ route('habits.summary') }}" method="GET" class="flex gap-2">
                    <select name="year" onchange="this.form.submit()" class="px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 font-medium">
                        @for ($y = now()->year - 2; $y <= now()->year + 2; $y++)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <select name="month" onchange="this.form.submit()" class="px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 font-medium">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromDate(2000, $m, 1)->format('F') }}</option>
                        @endfor
                    </select>
                </form>
            </div>

            <!-- Stats Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Overall Completion -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-8 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-4xl">📊</div>
                        <div class="text-sm font-medium opacity-90">Overall</div>
                    </div>
                    <div class="text-5xl font-bold mb-2">{{ $overallCompletion }}%</div>
                    <div class="text-sm opacity-90">{{ $totalCompletions }} completions</div>
                </div>

                <!-- Total Habits -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-8 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-4xl">🎯</div>
                        <div class="text-sm font-medium opacity-90">Habits</div>
                    </div>
                    <div class="text-5xl font-bold mb-2">{{ $totalHabits }}</div>
                    <div class="text-sm opacity-90">Being tracked</div>
                </div>

                <!-- Total Completions -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-8 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-4xl">✅</div>
                        <div class="text-sm font-medium opacity-90">Completed</div>
                    </div>
                    <div class="text-5xl font-bold mb-2">{{ $totalCompletions }}</div>
                    <div class="text-sm opacity-90">This month</div>
                </div>

                <!-- Average per Habit -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-8 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-4xl">📈</div>
                        <div class="text-sm font-medium opacity-90">Average</div>
                    </div>
                    <div class="text-5xl font-bold mb-2">{{ $totalHabits > 0 ? round($totalCompletions / $totalHabits, 1) : 0 }}</div>
                    <div class="text-sm opacity-90">Per habit</div>
                </div>
            </div>

            <!-- Share Overall Summary Button -->
            <div class="mb-6">
                <button onclick="shareOverall()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                    </svg>
                    Share Monthly Summary
                </button>
            </div>

            <!-- Habits Breakdown Section -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-8 py-6">
                    <h2 class="text-2xl font-bold text-white">Habits Breakdown</h2>
                </div>

                <div class="p-8">
                    @if(count($habitStats) == 0)
                        <div class="text-center py-16">
                            <div class="text-6xl mb-4">🎯</div>
                            <p class="text-gray-500 text-xl mb-6">No habits yet</p>
                            <a href="{{ route('habits.index') }}" class="inline-block px-8 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition font-bold text-lg">
                                Create First Habit
                            </a>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($habitStats as $index => $stat)
                                @php
                                    $habit = $stat['habit'];
                                    $type = $habit->type ?? 'good';
                                    $completion = $stat['completion_rate'];
                                    $isGood = $completion >= 75;
                                    $isWarning = $completion >= 50 && $completion < 75;
                                    $isBad = $completion < 50;
                                @endphp
                                <div class="border-2 {{ $isGood ? 'border-green-200 bg-green-50' : ($isWarning ? 'border-orange-200 bg-orange-50' : 'border-red-200 bg-red-50') }} rounded-xl p-6 hover:shadow-lg transition duration-200">
                                    <!-- Habit Header -->
                                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-r {{ $type === 'bad' ? 'from-red-500 to-red-600' : 'from-blue-500 to-blue-600' }} flex items-center justify-center text-white font-bold text-lg shadow-md">
                                                    {{ $index + 1 }}
                                                </div>
                                                <h3 class="text-3xl font-bold {{ $type === 'bad' ? 'text-red-700' : 'text-gray-800' }}">
                                                    {{ $habit->name }}
                                                </h3>
                                                @if($type === 'bad')
                                                    <span class="px-3 py-1 bg-red-300 text-red-800 text-xs font-bold rounded-full ml-2">Bad Habit</span>
                                                @endif
                                            </div>
                                            @if($habit->description)
                                                <p class="text-gray-700 mt-2 text-sm ml-13">{{ $habit->description }}</p>
                                            @endif
                                        </div>

                                        <!-- Completion Rate -->
                                        <div class="text-right">
                                            <div class="text-5xl font-bold {{ $isGood ? 'text-green-600' : ($isWarning ? 'text-orange-600' : 'text-red-600') }}">
                                                {{ $completion }}%
                                            </div>
                                            <div class="text-sm text-gray-600 font-medium">
                                                {{ $stat['completed_count'] }} of {{ $stat['days_in_month'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mb-4">
                                        <div class="w-full bg-gray-300 rounded-full h-4 overflow-hidden shadow-sm">
                                            <div class="h-4 rounded-full transition-all duration-500 {{ $isGood ? 'bg-gradient-to-r from-green-400 to-green-500' : ($isWarning ? 'bg-gradient-to-r from-orange-400 to-orange-500' : 'bg-gradient-to-r from-red-400 to-red-500') }}"
                                                style="width: {{ $completion }}%"></div>
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="mb-4">
                                        @if($isGood)
                                            <span class="inline-block px-4 py-2 bg-green-300 text-green-900 text-sm font-bold rounded-lg">✓ Excellent Progress!</span>
                                        @elseif($isWarning)
                                            <span class="inline-block px-4 py-2 bg-orange-300 text-orange-900 text-sm font-bold rounded-lg">⚠ Keep Going!</span>
                                        @else
                                            <span class="inline-block px-4 py-2 bg-red-300 text-red-900 text-sm font-bold rounded-lg">✕ Needs Attention</span>
                                        @endif
                                    </div>

                                    <!-- Stats Grid -->
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t-2 border-gray-300">
                                        <div class="text-center bg-white rounded-lg p-4 shadow-sm">
                                            <div class="text-3xl font-bold {{ $type === 'bad' ? 'text-red-600' : 'text-blue-600' }}">{{ $stat['current_streak'] }}</div>
                                            <div class="text-xs text-gray-600 font-bold mt-1">{{ $type === 'bad' ? 'Days Avoided' : 'Current Streak' }}</div>
                                        </div>
                                        <div class="text-center bg-white rounded-lg p-4 shadow-sm">
                                            <div class="text-3xl font-bold text-purple-600">{{ $stat['longest_streak'] }}</div>
                                            <div class="text-xs text-gray-600 font-bold mt-1">{{ $type === 'bad' ? 'Max Avoided' : 'Best Streak' }}</div>
                                        </div>
                                        <div class="text-center bg-white rounded-lg p-4 shadow-sm">
                                            <div class="text-3xl font-bold {{ $type === 'bad' ? 'text-red-600' : 'text-green-600' }}">{{ $stat['completed_count'] }}</div>
                                            <div class="text-xs text-gray-600 font-bold mt-1">{{ $type === 'bad' ? 'Times Done' : 'Completed' }}</div>
                                        </div>
                                        <div class="text-center bg-white rounded-lg p-4 shadow-sm">
                                            <div class="text-3xl font-bold {{ $type === 'bad' ? 'text-green-600' : 'text-gray-600' }}">{{ $stat['days_in_month'] - $stat['completed_count'] }}</div>
                                            <div class="text-xs text-gray-600 font-bold mt-1">{{ $type === 'bad' ? 'Days Resisted' : 'Skipped' }}</div>
                                        </div>
                                    </div>

                                    <!-- Share Button -->
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <button onclick="shareHabit({{ $habit->id }}, {{ $year }}, {{ $month }}, {{ $completion }}, {{ $stat['current_streak'] }}, {{ $stat['completed_count'] }})"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-colors text-sm font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                            </svg>
                                            Share Progress
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tips Section -->
            <div class="mt-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-8 text-white">
                <div class="flex items-start gap-4">
                    <div class="text-5xl flex-shrink-0">💡</div>
                    <div>
                        <h3 class="text-2xl font-bold mb-4">Tips for Better Performance</h3>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center gap-3">
                                <span class="text-xl">✓</span>
                                <span>Aim for 75% or higher completion rate for each habit</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="text-xl">✓</span>
                                <span>Focus on red-highlighted habits - they need more attention</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="text-xl">✓</span>
                                <span>Build consistent streaks by completing habits daily</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="text-xl">✓</span>
                                <span>Review your progress monthly to stay motivated</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function shareHabit(habitId, year, month, completionRate, currentStreak, totalCompletions) {
            try {
                const response = await fetch('share/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        habit_id: habitId,
                        year: year,
                        month: month,
                        completion_rate: completionRate,
                        current_streak: currentStreak,
                        total_completions: totalCompletions
                    })
                });

                const data = await response.json();
                if (data.success) {
                    window.open(data.url, '_blank');
                } else {
                    alert('Failed to create share link');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Something went wrong');
            }
        }

        async function shareOverall() {
            try {
                const response = await fetch('/share/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        year: {{ $year }},
                        month: {{ $month }},
                        completion_rate: {{ $overallCompletion }},
                        current_streak: 0,
                        total_completions: {{ $totalCompletions }}
                    })
                });

                const data = await response.json();
                if (data.success) {
                    window.open(data.url, '_blank');
                } else {
                    alert('Failed to create share link');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Something went wrong');
            }
        }
    </script>
</x-app-layout>
