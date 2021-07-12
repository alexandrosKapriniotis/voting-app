<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowIdeasTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function list_of_ideas_shows_on_main_page()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create([
            'name'      => 'Open',
            'classes'   => 'bg-gray-200',
        ]);
        $statusConsidering = Status::factory()->create([
            'name'      => 'Considering',
            'classes'   => 'bg-purple text-white',
        ]);

        $ideaOne = Idea::factory()->create([
            'user_id'       => $user->id,
            'title'         => 'My First Idea',
            'category_id'   => $categoryOne->id,
            'status_id'     => $statusOpen->id,
            'description'   => 'Description of my first idea'
        ]);

        $ideaTwo = Idea::factory()->create([
            'user_id'       => $user->id,
            'title'         => 'My Second Title',
            'category_id'   => $categoryTwo->id,
            'status_id'     => $statusConsidering->id,
            'description'   => 'Description of my second idea'
        ]);

        $response = $this->get(route('idea.index'));

        $response->assertSuccessful();
        $response->assertSee($ideaOne->title);
        $response->assertSee($ideaOne->description);
        $response->assertSee($categoryOne->name);
        $response->assertSee('<div class="bg-gray-200 text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 py-2 px-4 ml-1">Open</div>', false);

        $response->assertSee($ideaTwo->title);
        $response->assertSee($ideaTwo->description);
        $response->assertSee($categoryTwo->name);
        $response->assertSee('<div class="bg-purple text-white text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 py-2 px-4 ml-1">Considering</div>', false);
    }

    /**
     * @test
     */
    public function single_idea_shows_correctly_on_show_page()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create([
            'name'      => 'Open',
            'classes'   => 'bg-gray-200',
        ]);

        $ideaOne = Idea::factory()->create([
            'user_id'       => $user->id,
            'title'         => 'My First Idea',
            'category_id'   => $categoryOne->id,
            'status_id'     => $statusOpen->id,
            'description'   => 'Description of my first idea'
        ]);

        $response = $this->get(route('idea.show',$ideaOne));

        $response->assertSuccessful();
        $response->assertSee($ideaOne->title);
        $response->assertSee($ideaOne->description);
        $response->assertSee($categoryOne->name);
    }

    /**
     * @test
     */
    public function ideas_pagination_works()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create([
            'name'      => 'Open',
            'classes'   => 'bg-gray-200',
        ]);

        Idea::factory(Idea::PAGINATION_COUNT + 1)->create(['user_id' => $user->id,'category_id' => $categoryOne->id,'status_id' => $statusOpen->id]);

        $ideaOne = Idea::find(1);
        $ideaOne->title = 'My first Idea';
        $ideaOne->save();

        $ideaEleven = Idea::find(11);
        $ideaEleven->title = 'My eleventh Idea';
        $ideaEleven->save();

        $response = $this->get('/');

        $response->assertSee($ideaEleven->title);
        $response->assertDontSee($ideaOne->title);

        $response = $this->get('/?page=2');

        $response->assertSee($ideaOne->title);
        $response->assertDontSee($ideaEleven->title);
    }

    /**
     * @test
     */
    public function same_idea_different_slugs()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create([
            'name'      => 'Open',
            'classes'   => 'bg-gray-200',
        ]);

        $ideaOne = Idea::factory()->create([
            'user_id'       => $user->id,
            'title'         => 'My First Idea',
            'category_id'   => $categoryOne->id,
            'status_id'     => $statusOpen->id,
            'description'   => 'Description of my first idea'
        ]);

        $ideaTwo = Idea::factory()->create([
            'user_id'       => $user->id,
            'title'         => 'My First Idea',
            'category_id'   => $categoryOne->id,
            'status_id'     => $statusOpen->id,
            'description'   => 'Another Description of my first idea'
        ]);

        $response = $this->get(route('idea.show',$ideaOne));

        $response->assertSuccessful();
        $this->assertTrue(request()->path() === 'ideas/my-first-idea');

        $response = $this->get(route('idea.show',$ideaTwo));


        $response->assertSuccessful();
        $this->assertTrue(request()->path() === 'ideas/my-first-idea-2');
    }
}
