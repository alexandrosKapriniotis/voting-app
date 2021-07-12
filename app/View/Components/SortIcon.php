<?php

namespace App\View\Components;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SortIcon extends Component
{
    public $field;
    public $sortField;
    public $sortAsc;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($field,$sortField,$sortAsc)
    {
        $this->field = $field;
        $this->sortField = $sortField;
        $this->sortAsc = $sortAsc;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.sort-icon');
    }
}
