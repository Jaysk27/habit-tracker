<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Coach') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(!$hasApiKey)
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-amber-800">API Key Not Configured</h3>
                            <p class="text-sm text-amber-700 mt-1">Add your OpenRouter API key to the <code class="bg-amber-100 px-1 rounded">OPENROUTER_API_KEY</code> environment variable to enable AI chat.</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-indigo-500 to-purple-600">
                    <h3 class="font-semibold text-white">Habit Coach AI</h3>
                    <p class="text-sm text-indigo-100">Ask me anything about your habits</p>
                </div>

                <div id="chat-messages" class="h-[500px] min-h-[400px] overflow-y-auto p-4 space-y-4">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="bg-gray-100 rounded-lg px-4 py-3 max-w-[80%]">
                            <p class="text-sm text-gray-800">Hi! I'm your habit coach. Ask me about your progress, get motivation, or receive suggestions to improve your habits. Use the buttons below to get started!</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex flex-wrap gap-2 mb-3">
                        <button onclick="sendWithContext('today')" class="px-3 py-1.5 text-sm bg-green-100 text-green-700 rounded-full hover:bg-green-200 transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Today's Check-in
                        </button>
                        <button onclick="sendWithContext('progress')" class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Discuss Progress
                        </button>
                        <button onclick="sendWithContext('struggling')" class="px-3 py-1.5 text-sm bg-red-100 text-red-700 rounded-full hover:bg-red-200 transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Need Motivation
                        </button>
                        <button onclick="sendWithContext('suggestions')" class="px-3 py-1.5 text-sm bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            Get Suggestions
                        </button>
                    </div>

                    <form id="chat-form" class="flex gap-2">
                        @csrf
                        <input type="text" id="message-input" placeholder="Type your message..."
                            class="flex-1 h-11 px-4 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            {{ !$hasApiKey ? 'disabled' : '' }}>
                        <button type="submit" id="send-button"
                            class="px-6 h-11 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ !$hasApiKey ? 'disabled' : '' }}>
                            Send
                        </button>
                    </form>
                </div>
            </div>

            @if($habits->count() > 0)
                <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <h4 class="font-medium text-gray-800 mb-3">Quick context for specific habit:</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($habits as $habit)
                            <button onclick="sendWithContext('progress', {{ $habit->id }})" class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                {{ $habit->name }}
                            </button>
                            <button onclick="sendWithContext('struggling', {{ $habit->id }})" class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                Struggling with {{ $habit->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        const chatMessages = document.getElementById('chat-messages');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const sendButton = document.getElementById('send-button');
        let currentContext = '';

        function addMessage(content, isUser = false) {
            const div = document.createElement('div');
            div.className = 'flex gap-3';

            if (isUser) {
                div.innerHTML = `
                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="bg-indigo-100 rounded-lg px-4 py-3 max-w-[80%]">
                        <p class="text-sm text-gray-800">${escapeHtml(content)}</p>
                    </div>
                `;
            } else {
                div.innerHTML = `
                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="bg-gray-100 rounded-lg px-4 py-3 max-w-[80%]">
                        <p class="text-sm text-gray-800 whitespace-pre-wrap">${escapeHtml(content)}</p>
                    </div>
                `;
            }

            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function setLoading(loading) {
            sendButton.disabled = loading;
            messageInput.disabled = loading;
            sendButton.textContent = loading ? '...' : 'Send';
        }

        async function sendWithContext(type, habitId = null) {
            setLoading(true);

            try {
                const contextResponse = await fetch('/ai-chat/context', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ type: type, habit_id: habitId })
                });

                const contextData = await contextResponse.json();
                currentContext = contextData.context;

                let prompt = '';
                switch(type) {
                    case 'today': prompt = "Help me with today's habit check-in"; break;
                    case 'progress': prompt = "Let's discuss my habit progress"; break;
                    case 'struggling': prompt = "I need motivation and help with my habits"; break;
                    case 'suggestions': prompt = "Give me suggestions to improve my habits"; break;
                }

                await sendMessage(prompt);
            } catch (error) {
                console.error('Error:', error);
                addMessage('Sorry, something went wrong. Please try again.', false);
            }

            setLoading(false);
        }

        async function sendMessage(message) {
            addMessage(message, true);

            try {
                const response = await fetch('/ai-chat/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: message,
                        context: currentContext
                    })
                });

                const data = await response.json();

                if (data.error) {
                    addMessage(data.error, false);
                } else {
                    addMessage(data.response, false);
                }
            } catch (error) {
                console.error('Error:', error);
                addMessage('Sorry, something went wrong. Please try again.', false);
            }
        }

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;

            messageInput.value = '';
            setLoading(true);

            await sendMessage(message);
            currentContext = '';
            setLoading(false);
        });
    </script>
</x-app-layout>
