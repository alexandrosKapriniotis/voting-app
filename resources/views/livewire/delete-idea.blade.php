<x-modal-confirm
    event-to-open-modal="open-delete-modal.window"
    event-to-close-modal="ideaWasDeleted"
    modal-title="Delete Idea"
    modal-description="Are you sure you want to delete this idea? This action cannot be undone."
    modal-confirm-button-text="Delete"
    wire-click="deleteIdea"
/>
