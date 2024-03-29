<div wire:poll="getNotificationCount" x-data="{ isOpen: false }" class="relative">
    <button
        @click="
            isOpen = !isOpen

            if (isOpen) {
                Livewire.emit('getNotifications');
            }
        ">
        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($notificationCount)
            <div class="absolute rounded-full bg-red text-white text-xxs w-6 h-6 flex
             justify-center items-center border-2 -top-1 -right-1">
                {{ $notificationCount }}
            </div>
        @endif
    </button>

    <ul
        x-cloak
        x-show.transition.origin.top.duration.500ms="isOpen"
        @click.away="isOpen = false"
        @keydown.escape.window="isOpen = false"
        class="ml-8 absolute w-76 md:w-96 text-sm text-gray-700 text-left bg-white shadow-dialog rounded-xl
                                            max-h-128 overflow-y-auto z-10 -right-28 md:-right-12"
    >
        @if($notifications->isNotEmpty() && ! $isLoading)
            @foreach($notifications as $notification)
                <li>
                    <a
                        href="{{ route('idea.show',$notification->data['idea_slug']) }}"
                        @click.prevent="
                            isOpen = false
                        "
                        wire:click.prevent="markAsRead('{{ $notification->id }}')"
                        class="flex hover:bg-gray-100 px-5 py-3 transition duration-150 ease-in">
                        <img src="{{ $notification->data['user_avatar'] }}" class="rounded-xl w-10 h-10" alt="avatar" />
                        <div class="ml-4">
                            <div class="line-clamp-6">
                                <span class="font-semibold">{{ $notification->data['user_name'] }}</span>
                                commented on
                                <span class="font-semibold">{{ $notification->data['idea_title'] }}: </span>
                                <span>"{{ $notification->data['comment_body'] }}"</span>
                            </div>
                            <div>content</div>
                            <div class="text-xs text-gray-500 mt-2">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                    </a>
                </li>
            @endforeach
        <li class="flex border-t border-gray-300 text-center">
            <button
                wire:click="markAllAsRead"
                @click.prevent="isOpen=false"
                href="#" class="block w-full font-semibold hover:bg-gray-100 transition
                             duration-150 ease-in px-5 py-3">
                Mark all as read
            </button>
        </li>
        @elseif ($isLoading)
            @foreach (range(1,3) as $item)
                <li class="animate-pulse flex items-center transition duration-150 ease-in px-5 py-3">
                    <div class="bg-gray-200 rounded-xl w-10 h-10"></div>
                    <div class="flex-1 ml-4 space-y-2">
                        <div class="bg-gray-200 w-full rounded h-4"></div>
                        <div class="bg-gray-200 w-full rounded h-4"></div>
                        <div class="bg-gray-200 w-1/2 rounded h-4"></div>
                    </div>
                </li>
            @endforeach
        @else
        <li class="mx-auto w-40 py-6">
            <img src="{{ asset('img/no-ideas.svg') }}" alt="No Ideas" class="mx-auto" style="mix-blend-mode: luminosity">
            <div class="text-gray-400 text-center font-bold mt-6">
                No new notifications
            </div>
        </li>
        @endif
    </ul>
</div>
