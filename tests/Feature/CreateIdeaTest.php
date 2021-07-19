<?php

namespace Tests\Feature;

use App\Http\Livewire\CreateIdea;
use App\Models\Category;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateIdeaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_idea_form_does_not_show_when_logged_out()
    {
        $response = $this->get(route('idea.index'));

        $response->assertSuccessful();
        $response->assertSee('Please login to create an idea.');
        $response->assertDontSee('Let us know what you would like and we\'ll take a look',false);
    }

    /**
     * @test
     */
    public function create_idea_form_does_show_when_logged_in()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('idea.index'));

        $response->assertSuccessful();
        $response->assertDontSee('Please login to create an idea.');
        $response->assertSee('Let us know what you would like and we\'ll take a look',false);
    }

    /**
     * @test
     */
    public function main_page_contains_create_idea_livewire_component()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('idea.index'))->assertSeeLivewire('create-idea');
    }

    /** @test */
    public function create_idea_form_validation_works()
    {
        Livewire::actingAs(User::factory()->create())
            ->test(CreateIdea::class)
            ->set('title','')
            ->set('category','')
            ->set('description','')
            ->call('createIdea')
            ->assertHasErrors(['title','category','description'])
            ->assertSee('The title field is required');
    }

    /** @test */
    public function creating_an_idea_works_correctly()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create([
            'name'      => 'Open'
        ]);

        Livewire::actingAs($user)
            ->test(CreateIdea::class)
            ->set('title','my first idea')
            ->set('category',1)
            ->set('description','this is my first idea')
            ->call('createIdea')
            ->assertRedirect('/');

        $response = $this->actingAs($user)->get(route('idea.index'));
        $response->assertSuccessful();
        $response->assertSee('my first idea');
        $response->assertSee('this is my first idea');

        $this->assertDatabaseHas('ideas',[
            'title' => 'my first idea'
        ]);
    }

    /** @test */
    public function creating_two_ideas_with_same_title_still_works_but_has_different_slugs_correctly()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create([
            'name'      => 'Open',
            'classes'   => 'bg-gray-200',
        ]);

        Livewire::actingAs($user)
            ->test(CreateIdea::class)
            ->set('title','my first idea')
            ->set('category',1)
            ->set('description','this is my first idea')
            ->call('createIdea')
            ->assertRedirect('/');

        $response = $this->actingAs($user)->get(route('idea.index'));
        $response->assertSuccessful();
        $response->assertSee('my first idea');
        $response->assertSee('this is my first idea');

        $this->assertDatabaseHas('ideas',[
            'title' => 'my first idea',
            'slug'  => 'my-first-idea'
        ]);

        Livewire::actingAs($user)
            ->test(CreateIdea::class)
            ->set('title','my first idea')
            ->set('category',1)
            ->set('description','this is my first idea')
            ->call('createIdea')
            ->assertRedirect('/');

        $response = $this->actingAs($user)->get(route('idea.index'));
        $response->assertSuccessful();
        $response->assertSee('my first idea');
        $response->assertSee('this is my first idea');

        $this->assertDatabaseHas('ideas',[
            'id'    => 2,
            'title' => 'my first idea',
            'slug'  => 'my-first-idea-2'
        ]);
    }
}
