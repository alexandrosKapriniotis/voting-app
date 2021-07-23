<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\Vote;
use App\QueryFilters\ideasFilter;
use Illuminate\Pipeline\Pipeline;
use Livewire\Component;
use Livewire\WithPagination;

class IdeasIndex extends Component
{
    use WithPagination;

    public $status = 'All';
    public $category;
    public $ideas_filter;
    public $search;

    protected $queryString = [
        'status'    => ['except' => ''],
        'category',
        'ideas_filter'
    ];

    protected $listeners = [
        'queryStringUpdatedStatus'
    ];

    public function mount()
    {
        $this->status = request()->status ?? 'All';
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingIdeasFilter()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedIdeasFilter()
    {
        if ($this->ideas_filter === 'my_ideas') {
            if (!auth()->check())
            {
                return redirect(route('login'));
            }
        }
    }

    public function queryStringUpdatedStatus($newStatus)
    {
        $this->status = $newStatus;
        $this->resetPage();
    }

    public function render()
    {
        $statuses   = Status::all()->pluck('id','name');
        $categories = Category::all();

        return view('livewire.ideas-index',[
            'ideas' => Idea::with('user','category','status')
                ->when($this->status && $this->status != 'All',function ($query) use ($statuses){
                    return $query->where('status_id', $statuses->get($this->status));
                })
                ->when($this->category && $this->category != 'all_categories',function ($query) use ($categories){
                    return $query->where('category_id', $categories->pluck('id','name')->get($this->category));
                })
                ->when($this->ideas_filter && $this->ideas_filter === 'top_voted', function ($query) {
                    return $query->orderByDesc('votes_count');
                })->when($this->ideas_filter && $this->ideas_filter === 'my_ideas', function ($query) {
                    return $query->where('user_id', auth()->id());
                })
                ->when($this->ideas_filter && $this->ideas_filter === 'spam_ideas',function ($query) {
                    return $query->where('spam_reports','>',0)->orderByDesc('spam_reports');
                })
                ->when(strlen($this->search) >= 3, function ($query) {
                    return $query->where('title','like', '%'.$this->search.'%');
                })
                ->addSelect(['voted_by_user' => Vote::select('id')
                    ->where('user_id',auth()->id())
                    ->whereColumn('idea_id','ideas.id')
                ])
                ->withCount('votes')
                ->withCount('comments')
                ->orderBy('id','desc')
                ->simplePaginate(Idea::PAGINATION_COUNT),
            'categories' => $categories
        ]);
    }
}
