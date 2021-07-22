<div x-data="{ isOpen : false }">
    <button
        @click=" isOpen = !isOpen
        setTimeout(() => {
           isOpen = false
        },2000);"
    >Toggle</button>
    <div
        x-cloak
        x-show.transition.opacity.duration.300ms="isOpen"
        @keydown.escape.window="isOpen = false"
        x-init="
            window.livewire.on('ideaWasUpdated',() => {
                isOpen = false
            })"

        class="fixed flex justify-between max-w-xs sm:max-w-sm w-full z-20
            bottom-0 right-0 bg-white rounded-xl shadow-lg border px-6 py-5 mx-2 sm:mx-6 my-8">
        <div class="flex items-center">
            <svg class="text-green h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="ml-2 font-semibold text-gray-500 text-sm sm:text-base">
                Idea was updated successfully!
            </div>
        </div>
        <button
            @click="isOpen = false"
            class="text-gray-400 hover:text-gray-500">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>