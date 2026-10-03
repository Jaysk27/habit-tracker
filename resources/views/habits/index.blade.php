<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Habits') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium">Today's Progress</p>
                        <p class="text-4xl font-bold mt-1">{{ $progressSummary->completion_rate }}%</p>
                    </div>
                    <div class="text-right">
                        <p class="text-indigo-100 text-sm">{{ $progressSummary->completed_today }} of {{ $progressSummary->total_habits }} completed</p>
                    </div>
                </div>
                <div class="mt-4 h-2 bg-indigo-400/30 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full transition-all duration-500" style="width: {{ $progressSummary->completion_rate }}%"></div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <p class="text-sm text-gray-600">Need help with your habits?</p>
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('ai-chat') }}" class="px-3 py-1.5 text-sm bg-green-100 text-green-700 rounded-full hover:bg-green-200 transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Today's Check-in
                        </a>
                        <a href="{{ route('ai-chat') }}" class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Discuss Progress
                        </a>
                        <a href="{{ route('ai-chat') }}" class="px-3 py-1.5 text-sm bg-red-100 text-red-700 rounded-full hover:bg-red-200 transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Need Motivation
                        </a>
                        <a href="{{ route('ai-chat') }}" class="px-3 py-1.5 text-sm bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            Get Suggestions
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <form action="{{ route('habits.store') }}" method="POST" class="flex gap-3">
                    @csrf
                    <input type="text" name="name" placeholder="What habit do you want to build?" required 
                        class="flex-1 h-11 px-4 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    <select name="type" required class="h-11 px-4 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm bg-white">
                        <option value="good">Good Habit</option>
                        <option value="bad">Bad Habit</option>
                    </select>
                    <button type="submit" class="px-6 h-11 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        Add
                    </button>
                </form>

                <div class="mt-4 flex gap-3 flex-col">
                    <form action="{{ route('habits.index') }}" method="GET" class="flex gap-2">
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search habits..." 
                            class="flex-1 h-10 px-4 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                        <input type="hidden" name="year" value="{{ $year }}">
                        <input type="hidden" name="month" value="{{ $month }}">
                        <button type="submit" class="px-4 h-10 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">Search</button>
                    </form>
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('habits.index', ['year' => $year, 'month' => $month, 'search' => $search ?? '', 'type' => 'all']) }}" 
                            class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors {{ ($filterType ?? 'all') === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            All Habits
                        </a>
                        <a href="{{ route('habits.index', ['year' => $year, 'month' => $month, 'search' => $search ?? '', 'type' => 'good']) }}" 
                            class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors {{ ($filterType ?? 'all') === 'good' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            ✓ Good Habits
                        </a>
                        <a href="{{ route('habits.index', ['year' => $year, 'month' => $month, 'search' => $search ?? '', 'type' => 'bad']) }}" 
                            class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors {{ ($filterType ?? 'all') === 'bad' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            ✗ Bad Habits
                        </a>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <a href="{{ route('habits.index', ['year' => $year, 'month' => $month - 1]) }}" 
                        class="px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                        &larr; Previous
                    </a>
                    <span class="px-4 py-1.5 bg-gray-100 rounded-lg font-semibold text-gray-800">{{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</span>
                    <a href="{{ route('habits.index', ['year' => $year, 'month' => $month + 1]) }}" 
                        class="px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                        Next &rarr;
                    </a>
                </div>
            </div>

            @if($habits->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">No habits yet</h3>
                    <p class="text-gray-500">Start building better habits by adding your first one above!</p>
                </div>
            @else
                @foreach($habits as $habit)
                    @php $habitType = $habit->type ?? 'good'; @endphp
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-5 border-b border-gray-100 {{ $habitType === 'bad' ? 'bg-red-50' : 'bg-white' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-xl font-bold text-gray-900">{{ $habit->name }}</h3>
                                        @if($habitType === 'bad')
                                            <span class="px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700 rounded-full">Avoid</span>
                                        @else
                                            <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded-full">Build</span>
                                        @endif
                                    </div>
                                    @if($habit->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $habit->description }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-6 ml-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold {{ $habitType === 'bad' ? 'text-red-500' : 'text-orange-500' }}">{{ $habit->streak?->current_count ?? 0 }}</div>
                                        <div class="text-xs text-gray-500">{{ $habitType === 'bad' ? 'Days Avoided' : 'Streak' }}</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-purple-500">{{ $habit->streak?->longest_count ?? 0 }}</div>
                                        <div class="text-xs text-gray-500">Best</div>
                                    </div>
                                    <form action="{{ route('habits.destroy', $habit) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this habit? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50">
                            <div class="grid grid-cols-7 gap-1 text-center text-xs font-medium text-gray-500 mb-2">
                                @foreach(['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day)
                                    <div class="w-8">{{ $day }}</div>
                                @endforeach
                            </div>
                            <div class="grid grid-cols-7 gap-1">
                                @php
                                    $firstDay = \Carbon\Carbon::createFromDate($year, $month, 1)->dayOfWeek;
                                    $daysInMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->daysInMonth;
                                    $completedDates = $calendarData[$habit->id] ?? [];
                                @endphp
                                @for($i = 0; $i < $firstDay; $i++)
                                    <div class="w-8 h-8"></div>
                                @endfor
                                @for($day = 1; $day <= $daysInMonth; $day++)
                                    @php
                                        $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                                        $isCompleted = in_array($date, $completedDates);
                                        $isToday = $date === now()->toDateString();
                                    @endphp
                                    <form action="{{ route('habits.toggle', $habit) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="date" value="{{ $date }}">
                                        <input type="hidden" name="year" value="{{ $year }}">
                                        <input type="hidden" name="month" value="{{ $month }}">
                                        <button type="submit" 
                                            class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-medium transition-all
                                            {{ $isCompleted 
                                                ? ($habitType === 'bad' ? 'bg-red-500 text-white' : 'bg-green-500 text-white') 
                                                : 'bg-white text-gray-700 hover:bg-gray-200' }}
                                            {{ $isToday ? 'ring-2 ring-indigo-500 ring-offset-1' : 'border border-gray-200' }}">
                                            {{ $day }}
                                        </button>
                                    </form>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>