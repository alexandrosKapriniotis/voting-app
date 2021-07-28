<x-modal-confirm
    livewire-event-to-open-modal="markAsNotSpamCommentWasSet"
    event-to-close-modal="commentWasMarkedAsNotSpam"
    modal-title="Reset Spam Counter"
    modal-description="Are you sure you want to reset the spam counter?"
    modal-confirm-button-text="Reset Spam Counter"
    wire-click="markAsNotSpam"
/>
