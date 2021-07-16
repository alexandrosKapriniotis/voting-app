<x-app-layout>
    <div class="mb-2">
        <a href="{{ $backUrl }}" class="flex items-center font-semibold hover:underline">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span class="ml-2">All Ideas (or back to chosen category with filters)</span>
        </a>
    </div>

    <livewire:idea-show
        :idea="$idea"
        :votesCount="$votesCount"
    />

    <div class="comments-container relative space-y-6 md:ml-22 my-8 mt-1 pt-4">
        <div class="comment-container relative bg-white rounded-xl flex">
            <div class="flex flex-col md:flex-row px-4 py-6">
                <div class="flex-none mx-4 md:mx-0">
                    <a href="#">
                        <img src="https://source.unsplash.com/200x200/?face&crop=face&v=2" alt="avatar" class="w-14 h-14 rounded-xl" />
                    </a>
                </div>

                <div class="w-full mx-4">

                    <div class="text-gray-600 mt-0">
                        {{ $idea->description }}
                    </div>

                    <div class="flex md:items-center justify-between mt-6">
                        <div class="flex items-center text-xs text-gray-400 font-semibold space-x-2">
                            <div class="hidden md:block font-bold text-gray-900">{{ $idea->user->name }}</div>
                            <div class="hidden md:block">&bull;</div>
                            <div>{{ $idea->created_at->diffForHumans() }}</div>
                        </div>

                        <div
                            x-data="{ isOpen: false}"
                            class="flex items-center space-x-2 mt-4 md:mt-0"
                        >
                            <button
                                @click="isOpen = !isOpen"
                                class="relative bg-gray-100 hover:bg-gray-200 border rounded-full h-7 transition duration-150 ease-in py-2 px-3"
                            >
                                <svg style="color:rgba(163,163,163,.5)" fill="currentColor" width="24" height="6">
                                    <path d="M2.97.061A2.969 2.969 0 000 3.031 2.968 2.968 0 002.97 6a2.97 2.97 0 100-5.94zm9.184 0a2.97 2.97 0 100 5.939 2.97 2.97 0 100-5.939zm8.877 0a2.97 2.97 0 10-.003 5.94A2.97 2.97 0 0021.03.06z">
                                    </path>
                                </svg>
                                <ul
                                    x-cloak
                                    x-show.transition.origin.top.left.duration.500ms="isOpen"
                                    @click.away="isOpen = false"
                                    @keydown.escape.window="isOpen = false"
                                    class="ml-8 absolute w-44 text-left font-semibold bg-white shadow-dialog rounded-xl py-3 md:ml-8 top-8 md:top-6 right-0 md:left-0 z-10">
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
                    </div>
                </div>
            </div>
        </div> <!-- end comment-container -->

        <div class="comment-container flex is-admin relative bg-white rounded-xl">
            <div class="flex flex-col md:flex-row px-4 py-6">
                <div class="flex-none mx-2 md:mx-4 md:mx-0">
                    <a href="#">
                        <img src="https://source.unsplash.com/200x200/?face&crop=face&v=3" alt="avatar" class="w-14 h-14 rounded-xl" />
                    </a>
                    <div class="md:text-center uppercase text-blue font-bold text-xxs mt-1">Admin</div>
                </div>

                <div class="w-full mx-2 md:mx-4">
                    <h4 class="text-xl font-semibold">
                        Status changed to "Under Consideration"
                    </h4>

                    <div class="text-gray-600 mt-3">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vitae enim non tortor tincidunt congue nec vel magna. Quisque a placerat risus. Etiam vestibulum elit dolor, ac posuere leo tristique sed. Mauris ligula enim, congue sit amet hendrerit ac, luctus quis velit. Morbi eleifend enim at viverra hendrerit. Donec molestie elit eu mi posuere pellentesque vel sed lacus. Etiam mollis nisl in est bibendum scelerisque. Donec faucibus ultrices augue, ut congue purus. Donec vulputate nibh ut ex cursus, sit amet tincidunt leo congue. Praesent auctor aliquet tincidunt.
                    </div>

                    <div class="flex md:items-center justify-between mt-6">
                        <div class="flex text-gray-400 md:items-center text-xs font-semibold md:space-x-2">
                            <div class="hidden md:block font-bold text-blue">Andrea</div>
                            <div class="hidden md:block">&bull;</div>
                            <div>10 hours ago</div>
                        </div>

                        <div
                            class="flex items-center space-x-2"
                            x-data="{ isOpen: false}"
                        >
                            <button
                                class="relative py-2 px-3 border transition duration-150 ease-in bg-gray-100 hover:bg-gray-200 rounded-full h-7"
                                @click="isOpen = !isOpen"
                            >
                                <svg style="color:rgba(163,163,163,.5)" fill="currentColor" width="24" height="6">
                                    <path d="M2.97.061A2.969 2.969 0 000 3.031 2.968 2.968 0 002.97 6a2.97 2.97 0 100-5.94zm9.184 0a2.97 2.97 0 100 5.939 2.97 2.97 0 100-5.939zm8.877 0a2.97 2.97 0 10-.003 5.94A2.97 2.97 0 0021.03.06z">
                                    </path>
                                </svg>
                                <ul
                                    x-cloak
                                    x-show.transition.origin.top.left.duration.500ms="isOpen"
                                    @click.away="isOpen = false"
                                    @keydown.escape.window="isOpen = false"
                                    class="ml-8 absolute w-44 text-left font-semibold bg-white shadow-dialog rounded-xl md:ml-8 top-8 md:top-6 right-0 md:left-0 z-10">
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
                    </div>
                </div>
            </div>
        </div> <!-- end admin comment-container -->
    </div> <!-- end comments-section -->
</x-app-layout>
