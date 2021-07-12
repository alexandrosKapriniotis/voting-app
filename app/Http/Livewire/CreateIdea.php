<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Idea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Livewire\Component;

class CreateIdea extends Component
{
    public $title;
    public $category = 1;
    public $description;

    protected $rules = [
        'title'         => 'required|min:4',
        'category'      => 'required|exists:categories,id',
        'description'   => 'required|min:4'
    ];

    /**
     * @return RedirectResponse
     */
    public function createIdea()
    {
        if (auth()->check()){

            $this->validate();

            Idea::create([
                'user_id'       => auth()->id(),
                'category_id'   => $this->category,
                'status_id'        => 1,
                'title'         => $this->title,
                'description'   => $this->description
            ]);
            session()->flash('success-message','Idea was added successfully.');

            $this->reset();

            return redirect()->route('idea.index');
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    public function render()
    {
        return view('livewire.create-idea',[
            'categories' => Category::all()
        ]);
    }
}
