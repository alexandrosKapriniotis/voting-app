@props([
    'type' => 'success',
    'redirect' => false,
    'messageToDisplay' => ''
])
<div
    x-cloak
    x-data="{
        isOpen : false,
        isError: @if ($type === 'success') false @elseif ($type === 'error') true @endif,
        messageToDisplay: '{{ $messageToDisplay }}',
        showNotification(message) {
            this.isOpen = true
            this.messageToDisplay = message
            setTimeout(() => {
                this.isOpen = false
            }, 5000)
        }
    }"
    x-init="
    @if ($redirect)
        $nextTick(() => showNotification(messageToDisplay))
    @else
        Livewire.on('notificationSuccessOpen', message => {
            isError = false,
            showNotification(message)
        })
        Livewire.on('notificationErrorOpen', message => {
            isError = true,
            showNotification(message)
        })
    @endif
        "
    x-show="isOpen"
    @keydown.escape.window="isOpen = false"
    class="fixed flex justify-between max-w-xs sm:max-w-sm w-full z-20
        bottom-0 right-0 bg-white rounded-xl shadow-lg border px-6 py-5 mx-2 sm:mx-6 my-8">
    <div class="flex items-center">

        <svg x-show="!isError" class="text-green h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>

        <svg x-show="isError" class="text-red h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>

        <div
            x-text="messageToDisplay"
            class="ml-2 font-semibold text-gray-500 text-sm sm:text-base">
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
