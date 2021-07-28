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
            'name'      => 'Open'
        ]);
        $statusConsidering = Status::factory()->create([
            'name'      => 'Considering'
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
//        $response->assertSee('<div class="bg-gray-200 text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 py-2 px-4 ml-1">Open</div>', false);

        $response->assertSee($ideaTwo->title);
        $response->assertSee($ideaTwo->description);
        $response->assertSee($categoryTwo->name);
//        $response->assertSee('<div class="bg-purple text-white text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 py-2 px-4 ml-1">Considering</div>', false);
    }

    /**
     * @test
     */
    public function single_idea_shows_correctly_on_show_page()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create([
            'name'      => 'Open'
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
        $ideaOne = Idea::factory()->create();

        Idea::factory($ideaOne->getPerPage())->create();

        $response = $this->get('/');

        $response->assertSee(Idea::find(Idea::count())->title);
        $response->assertDontSee($ideaOne->title);

        $response = $this->get('/?page=2');

        $response->assertDontSee(Idea::find(Idea::count())->title);
        $response->assertSee($ideaOne->title);
    }

    /**
     * @test
     */
    public function same_idea_different_slugs()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create([
            'name'      => 'Open'
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

    /**
     *@test
     */
    public function in_app_back_button_works_when_index_page_visited_first()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create([
            'name'      => 'Open'
        ]);

        $ideaOne = Idea::factory()->create([
            'user_id'       => $user->id,
            'title'         => 'My First Idea',
            'category_id'   => $categoryOne->id,
            'status_id'     => $statusOpen->id,
            'description'   => 'Description of my first idea'
        ]);

        $response = $this->get('/?category=Category%202&status=Considering');
        $response = $this->get(route('idea.show',$ideaOne));

        $this->assertStringContainsString('/?category=Category%202&status=Considering',$response['backUrl']);
    }

    /**
     *@test
     */
    public function in_app_back_button_works_when_show_page_only_page_visited()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create([
            'name'      => 'Open'
        ]);

        $ideaOne = Idea::factory()->create([
            'user_id'       => $user->id,
            'title'         => 'My First Idea',
            'category_id'   => $categoryOne->id,
            'status_id'     => $statusOpen->id,
            'description'   => 'Description of my first idea'
        ]);

        $response = $this->get(route('idea.show',$ideaOne));

        $this->assertEquals(route('idea.index'),$response['backUrl']);
    }
}
