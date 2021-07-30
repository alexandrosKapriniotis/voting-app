<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Traits\WithAuthRedirects;
use App\Models\Idea;
use Livewire\Component;

class IdeaShow extends Component
{
    use WithAuthRedirects;

    public $idea;
    public $votesCount;
    public $hasVoted;

    protected $listeners = [
        'statusWasUpdated' => '$refresh',
        'ideaWasUpdated' => '$refresh',
        'ideaWasMarkedAsSpam' => '$refresh',
        'ideaWasMarkedAsNotSpam' => '$refresh',
        'commentWasAdded' => '$refresh',
        'commentWasDeleted' => '$refresh',
        'statusWasUpdatedError' => '$refresh',
    ];

    public function mount(Idea $idea, $votesCount)
    {
        $this->idea = $idea;
        $this->votesCount = $votesCount;
        $this->hasVoted = $idea->isVotedByUser(auth()->user());
    }

    public function statusWasUpdated()
    {
        $this->idea->refresh();
    }

    public function vote()
    {
        if (auth()->guest())
        {
            return $this->redirectToLogin();
        }

        if($this->hasVoted)
        {
            $this->idea->removeVote(auth()->user());
            $this->votesCount--;
            $this->hasVoted = false;
        } else
        {
            $this->idea->vote(auth()->user());
            $this->votesCount++;
            $this->hasVoted = true;
        }
    }

    public function render()
    {
        return view('livewire.idea-show');
    }
}
