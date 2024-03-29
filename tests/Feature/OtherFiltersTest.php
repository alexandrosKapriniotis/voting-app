<?php

namespace Tests\Feature;

use App\Http\Livewire\IdeasIndex;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OtherFiltersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function top_voted_filter_works()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();
        $userC = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);

        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        $ideaTwo = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        Vote::factory()->create([
            'idea_id'  => $ideaOne->id,
            'user_id'  => $user->id
        ]);

        Vote::factory()->create([
            'idea_id'  => $ideaOne->id,
            'user_id'  => $userB->id
        ]);

        Vote::factory()->create([
            'idea_id'  => $ideaTwo->id,
            'user_id'  => $userC->id
        ]);

        Livewire::test(IdeasIndex::class)
            ->set('ideas_filter','top_voted')
            ->assertViewHas('ideas',function($ideas){
                return $ideas->count() === 2
                    && $ideas->first()->votes()->count() === 2
                    && $ideas->get(1)->votes()->count() === 1;
            });
    }

    /** @test */
    public function my_ideas_filter_works_correctly_when_user_logged_in()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);

        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        $ideaTwo = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My Second Idea',
            'description' => 'Description for my first idea',
        ]);

        $ideaThree = Idea::factory()->create([
            'user_id' => $userB->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        Vote::factory()->create([
            'idea_id'  => $ideaOne->id,
            'user_id'  => $user->id
        ]);

        Vote::factory()->create([
            'idea_id'  => $ideaOne->id,
            'user_id'  => $userB->id
        ]);

        Vote::factory()->create([
            'idea_id'  => $ideaTwo->id,
            'user_id'  => $userB->id
        ]);

        Livewire::actingAs($user)
            ->test(IdeasIndex::class)
            ->set('ideas_filter','my_ideas')
            ->assertViewHas('ideas',function($ideas){
                return $ideas->count() === 2
                    && $ideas->first()->title === 'My Second Idea'
                    && $ideas->get(1)->title === 'My First Idea';
            });
    }

    /** @test */
    public function my_ideas_filter_works_correctly_when_user_is_not_logged_in()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);

        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        $ideaTwo = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My Second Idea',
            'description' => 'Description for my first idea',
        ]);

        $ideaThree = Idea::factory()->create([
            'user_id' => $userB->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        Vote::factory()->create([
            'idea_id'  => $ideaOne->id,
            'user_id'  => $user->id
        ]);

        Vote::factory()->create([
            'idea_id'  => $ideaOne->id,
            'user_id'  => $userB->id
        ]);

        Vote::factory()->create([
            'idea_id'  => $ideaTwo->id,
            'user_id'  => $userB->id
        ]);

        Livewire::test(IdeasIndex::class)
            ->set('ideas_filter','my_ideas')
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function my_ideas_filter_works_correctly_with_categories_filter()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);

        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        $ideaTwo = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My Second Idea',
            'description' => 'Description for my first idea',
        ]);

        $ideaThree = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryTwo->id,
            'status_id' => $statusOpen->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        Livewire::actingAs($user)
            ->test(IdeasIndex::class)
            ->set('category','Category 1')
            ->set('ideas_filter','my_ideas')
            ->assertViewHas('ideas',function($ideas){
                return $ideas->count() === 2
                    && $ideas->first()->title === 'My Second Idea'
                    && $ideas->get(1)->title === 'My First Idea';
            });
    }

    /** @test */
    public function no_filters_works_correctly()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);

        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        $ideaTwo = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My Second Idea',
            'description' => 'Description for my first idea',
        ]);

        $ideaThree = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My Third Idea',
            'description' => 'Description for my third idea',
        ]);

        Livewire::test(IdeasIndex::class)
            ->set('ideas_filter','no_filter')
            ->assertViewHas('ideas',function($ideas){
                return $ideas->count() === 3
                    && $ideas->first()->title === 'My Third Idea'
                    && $ideas->get(1)->title ===  'My Second Idea';
            });
    }

    /** @test */
    public function spam_ideas_filter_works()
    {
        $user = User::factory()->admin()->create();

        Idea::factory()->create([
            'title' => 'My First Idea',
            'spam_reports' => 1,
        ]);

        Idea::factory()->create([
            'title' => 'My Second Idea',
            'spam_reports' => 0,
        ]);

        Idea::factory()->create([
            'title' => 'My Third Idea',
            'spam_reports' => 5,
        ]);

        Livewire::actingAs($user)
            ->test(IdeasIndex::class)
            ->set('ideas_filter','spam_ideas')
            ->assertViewHas('ideas', function($ideas){
                return $ideas->count() === 2
                    && $ideas->first()->title === 'My Third Idea'
                    && $ideas->get(1)->title === 'My First Idea';
            });
    }

    /** @test */
    public function spam_comments_filter_works()
    {
        $user = User::factory()->admin()->create();

        $idea = Idea::factory()->create([
            'title' => 'My First Idea',
        ]);

        $ideaTwo = Idea::factory()->create([
            'title' => 'My Second Idea',
        ]);

        $ideaThree = Idea::factory()->create([
            'title' => 'My Third Idea',
        ]);

        $comment = Comment::factory()->create([
            'idea_id'   => $idea->id,
            'spam_reports'  => 1
        ]);

        $commentTwo = Comment::factory()->create([
            'idea_id'   => $ideaTwo->id,
            'spam_reports'  => 0
        ]);

        Livewire::actingAs($user)
            ->test(IdeasIndex::class)
            ->set('ideas_filter','spam_comments')
            ->assertViewHas('ideas', function($ideas){
                return $ideas->count() === 1
                    && $ideas->first()->title === 'My First Idea';
            });
    }
}
