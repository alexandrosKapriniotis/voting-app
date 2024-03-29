<?php

namespace Tests\Feature;

use App\Http\Livewire\IdeaIndex;
use App\Http\Livewire\IdeaShow;
use App\Http\Livewire\IdeasIndex;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class VoteIndexPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function index_page_contains_idea_index_livewire_component()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create([
            'name'      => 'Open'
        ]);

        $idea = Idea::factory()->create([
            'user_id'       => $user->id,
            'category_id'   => $categoryOne->id,
            'status_id'     => $statusOpen->id,
            'title'         => 'My First Idea',
            'description'   => 'Description for my first idea'
        ]);

        $this->get(route('idea.index', $idea))
            ->assertSeeLivewire('idea-index');
    }

    /**
     *@test
     */
    public function ideas_index_livewire_component_correctly_receives_votes_count()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create([
            'name'      => 'Open'
        ]);

        $idea = Idea::factory()->create([
            'user_id'       => $user->id,
            'category_id'   => $categoryOne->id,
            'status_id'     => $statusOpen->id,
            'title'         => 'My First Idea',
            'description'   => 'Description for my first idea'
        ]);

        Vote::factory()->create([
            'idea_id'  => $idea->id,
            'user_id'  => $user->id
        ]);

        Vote::factory()->create([
            'idea_id'  => $idea->id,
            'user_id'  => $userB->id
        ]);

        Livewire::test(IdeasIndex::class)->assertViewHas('ideas',function ($ideas) {
                return (int)$ideas->first()->votes_count === 2;
        });

//        $this->get(route('idea.index', $idea))
//            ->assertViewHas('ideas',function ($ideas) {
//                return (int)$ideas->first()->votes_count === 2;
//            });
    }

    /**
     *@test
     */
    public function votes_count_shows_correctly_on_show_page_livewire_component()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create([
            'name'      => 'Open'
        ]);

        $idea = Idea::factory()->create([
            'user_id'       => $user->id,
            'category_id'   => $categoryOne->id,
            'status_id'     => $statusOpen->id,
            'title'         => 'My First Idea',
            'description'   => 'Description for my first idea'
        ]);

        Livewire::test(IdeaIndex::class, [
            'idea'          => $idea,
            'votesCount'    => 5
        ])
            ->assertSet('votesCount',5)
            ->assertSeeHtml('<div class="font-semibold text-2xl ">5</div>')
            ->assertSeeHtml('<div class="text-sm font-bold leading-none ">5</div>');
    }

    /** @test */
    public function user_who_is_logged_in_shows_voted_if_idea_already_voted_for()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        Vote::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
        ]);

        $idea->votes_count = 1;
        $idea->voted_by_user = 1;

        Livewire::actingAs($user)
            ->test(IdeaIndex::class, [
                'idea' => $idea,
                'votesCount' => 5,
            ])
            ->assertSet('hasVoted', true)
            ->assertSee('Voted');
    }

    /**
     *@test
     */
    public function user_who_is_logged_in_can_vote_for_idea()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create([
            'name'      => 'Open',
        ]);

        $idea = Idea::factory()->create([
            'user_id'       => $user->id,
            'category_id'   => $categoryOne->id,
            'status_id'     => $statusOpen->id,
            'title'         => 'My First Idea',
            'description'   => 'Description for my first idea'
        ]);

        $this->assertDatabaseMissing('votes',[
            'user_id'   => $user->id,
            'idea_id'   => $idea->id
        ]);

        Livewire::actingAs($user)->test(IdeaShow::class, [
            'idea'          => $idea,
            'votesCount'    => 5
        ])
            ->call('vote')
            ->assertSet('votesCount',6)
            ->assertSet('hasVoted',true)
            ->assertSee('Voted');

        $this->assertDatabaseHas('votes',[
            'user_id'   => $user->id,
            'idea_id'   => $idea->id
        ]);
    }
}
