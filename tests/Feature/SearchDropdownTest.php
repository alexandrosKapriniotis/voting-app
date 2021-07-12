<?php

namespace Tests\Feature;

use App\Http\Livewire\SearchDropdown;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class SearchDropdownTest extends TestCase
{
    /**
     * @test
     **/
    public function search_dropdown_searches_correctly()
    {
        Livewire::test(SearchDropdown::class)
            ->assertDontSee('John Lennon')
            ->set('search','Imagine')
            ->assertSee('John Lennon');
    }

    /**
     * @test
     **/
    public function search_dropdown_shows_message_if_no_show_exists()
    {
        Livewire::test(SearchDropdown::class)
            ->set('search','asddasadsads')
            ->assertSee('No results found for');
    }
}
