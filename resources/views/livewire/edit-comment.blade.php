<div
    x-cloak
    x-data="{ isOpen : false }"
    x-show="isOpen"
    @keydown.escape.window="isOpen = false"
    x-init="
        Livewire.on('commentWasUpdated',() => {
            isOpen = false
        })

        Livewire.on('editCommentWasSet',() => {
            isOpen = true
            $nextTick(() => $refs.editComment.focus())
        })
    "
    class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true"
>
    <div
        x-show.transition.opacity="isOpen"
        class="flex items-end justify-center min-h-screen">
        <div
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div
            x-show.transition.origin.bottom.duration.300ms="isOpen"
            class="modal inline-block align-bottom bg-white rounded-tl-xl rounded-tr-xl overflow-hidden
                transform transition-all sm:max-w-lg sm:w-full py-2 px-4"
        >

            <div class="absolute top-0 right-0 pt-2 pr-4">
                <button
                    @click="isOpen = false"
                    class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <h3 class="text-center text-lg font-medium text-gray-900">
                Edit Comment
            </h3>

            <form wire:submit.prevent="updateComment" method="POST" class="space-y-4 px-4 py-6">

                <div>
                    <textarea x-ref="editComment" wire:model.defer="body" name="comment" id="comment" cols="30" rows="4" required
                              class="resize-none border-none w-full bg-gray-100 rounded-xl placeholder-gray-900 text-sm px-4 py-2"
                              placeholder="Your comment here">
                    </textarea>
                    @error('body')
                    <p class="text-red text-xs mt-1">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="flex items-center justify-between space-x-3">
                    <button type="button" class="flex items-center justify-center w-1/2 h-11 text-xs bg-gray-200
                                font-semibold rounded-xl border border-gray-200 hover:border-gray-400 transition duration-150 ease-in px-6 py-3">
                        <svg class="text-gray-600 w-4 transform -rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        <span class="ml-2">
                            Attach
                        </span>
                    </button>

                    <button type="submit" class="flex items-center justify-center w-1/2 h-11 text-xs bg-blue
                        font-semibold rounded-xl border border-blue hover:bg-blue-hover transition duration-150 ease-in px-6 py-3 text-white">
                        <span class="ml-2">
                            Update
                        </span>
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
