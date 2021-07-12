<div
    x-data
    @click="
        const target = $event.target.tagName.toLowerCase()
        const ignores = ['button','svg','path','a'];

        if(!ignores.includes(target))
        {
            $event.target.closest('.idea-container').querySelector('.idea-link').click()
        }

    "
    class="idea-container hover:shadow-card transition duration-150 ease-in bg-white rounded-xl flex">
    <div class="hidden md:block border-r border-gray-100 px-5 py-8">
        <div class="text-center">
            <div class="font-semibold text-2xl @if($hasVoted) text-blue @endif">{{ $votesCount }}</div>
            <div class="text-gray-500">Votes</div>
        </div>

        <div class="mt-8 px-2">
            @if($hasVoted)
                <button
                    wire:click.prevent="vote"
                    class="w-20 text-white bg-blue border border-blue font-bold text-xxs uppercase
                                        rounded-xl hover:bg-blue-hover transition duration-150 ease-in px-4 py-3 -mx-5">
                    Voted
                </button>
            @else
                <button
                    wire:click.prevent="vote"
                    class="w-20 bg-gray-200 border border-gray-200 font-bold text-xxs uppercase
                                        rounded-xl hover:bg-gray-200 transition duration-150 ease-in px-4 py-3 -mx-5">
                    Vote
                </button>
            @endif
        </div>
    </div>

    <div class="flex flex-col md:flex-row px-2 py-6">

        <div class="flex-none mx-2 md:mx-4 md:mx-0">
            <a href="#">
                <img src="{{ $idea->user->getAvatar() }}" alt="avatar" class="w-14 h-14 rounded-xl" />
            </a>
        </div>

        <div class="w-full flex-col justify-between mx-2 md:mx-0">
            <h4 class="text-xl font-semibold mt-2 md:mt-0">
                <a href="{{ route('idea.show',$idea) }}" class="idea-link hover:underline">
                    {{ $idea->title }}
                </a>
            </h4>

            <div class="text-gray-600 mt-3 line-clamp-3 cursor-pointer">
                {{ $idea->description }}
            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between mt-6">
                <div class="flex text-gray-400 md:items-center text-xs font-semibold md:space-x-2">
                    <div>{{ $idea->created_at->diffForHumans() }}</div>
                    <div>&bull;</div>
                    <div>{{ $idea->category->name }}</div>
                    <div>&bull;</div>
                    <div class="text-gray-900">3 Comments</div>
                </div>

                <div
                    class="flex items-center space-x-2 mt-4 md:mt-0"
                    x-data="{ isOpen: false}"
                >
                    <div class="{{ $idea->status->classes }} text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 py-2 px-4 ml-1">{{ $idea->status->name }}</div>
                    <button
                        @click="isOpen = !isOpen"
                        class="relative py-2 px-3 border transition duration-150 ease-in bg-gray-100 hover:bg-gray-200 rounded-full h-7">
                        <svg style="color:rgba(163,163,163,.5)" fill="currentColor" width="24" height="6">
                            <path d="M2.97.061A2.969 2.969 0 000 3.031 2.968 2.968 0 002.97 6a2.97 2.97 0 100-5.94zm9.184 0a2.97 2.97 0 100 5.939 2.97 2.97 0 100-5.939zm8.877 0a2.97 2.97 0 10-.003 5.94A2.97 2.97 0 0021.03.06z">
                            </path>
                        </svg>
                        <ul
                            x-cloak
                            x-show.transition.origin.top.left.duration.500ms="isOpen"
                            @click.away="isOpen = false"
                            @keydown.escape.window="isOpen = false"
                            class="ml-8 absolute w-44 text-left font-semibold bg-white shadow-dialog rounded-xl py-3
                                            md:ml-8 top-8 md:top-6 right-0 md:left-0">
                            <li>
                                <a href="#" class="block transition duration-150 ease-in hover:bg-gray-100 px-5 py-3">
                                    Mark as Spam
                                </a>
                            </li>

                            <li>
                                <a href="#" class="block transition duration-150 ease-in hover:bg-gray-100 px-5 py-3">
                                    Delete Post
                                </a>
                            </li>
                        </ul>
                    </button>
                </div>

                <div class="flex items-center md:hidden mt-4 md:mt-0">
                    <div class="bg-gray-100 text-center rounded-xl h-10 px-4 py-2 pr-8">
                        <div class="text-sm font-bold leading-none @if($hasVoted) text-blue @endif">{{ $votesCount }}</div>
                        <div class="text-xxs font-semibold leading-none text-gray-400">
                            Votes
                        </div>
                    </div>
                    @if($hasVoted)
                        <button
                            wire:click.prevent="vote"
                            class="w-20 text-white bg-blue border border-blue font-bold text-xxs uppercase
                                        rounded-xl hover:bg-blue-hover transition duration-150 ease-in px-4 py-3 -mx-5">
                            Voted
                        </button>
                    @else
                        <button
                            wire:click.prevent="vote"
                            class="w-20 bg-gray-200 border border-gray-200 font-bold text-xxs uppercase
                                        rounded-xl hover:bg-gray-200 transition duration-150 ease-in px-4 py-3 -mx-5">
                            Vote
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> <!-- end idea container -->
