<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowCommentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function idea_comments_livewire_component_renders()
    {
        $idea = Idea::factory()->create();

        $this->get(route('idea.show',$idea))
            ->assertSeeLivewire('idea-comments');
    }

    /** @test */
    public function idea_comment_livewire_component_renders()
    {
        $idea = Idea::factory()->create();
        $comment = Comment::factory()->create([
            'idea_id'   => $idea->id,
            'body'      => 'This is my first comment'
        ]);

        $this->get(route('idea.show',$idea))
            ->assertSeeLivewire('idea-comment');
    }

    /** @test */
    public function no_comments_shows_appropriate_message()
    {
        $idea = Idea::factory()->create();

        $this->get(route('idea.show',$idea))
            ->assertSee('No comments yet');
    }

    /** @test */
    public function list_of_comments_shows_on_idea_page()
    {
        $idea = Idea::factory()->create();
        $comment = Comment::factory()->create([
            'idea_id'   => $idea->id,
            'body'      => 'This is my first comment'
        ]);

        $commentTwo = Comment::factory()->create([
            'idea_id'   => $idea->id,
            'body'      => 'This is my second comment'
        ]);

        $this->get(route('idea.show',$idea))
            ->assertSee('This is my first comment')
            ->assertSee('This is my second comment')
            ->assertSee('2 Comments');
    }

    /** @test */
    public function comments_count_shows_correctly_on_index_page()
    {
        $idea = Idea::factory()->create();
        $comment = Comment::factory()->create([
            'idea_id'   => $idea->id,
            'body'      => 'This is my first comment'
        ]);

        $commentTwo = Comment::factory()->create([
            'idea_id'   => $idea->id,
            'body'      => 'This is my second comment'
        ]);

        $this->get(route('idea.index',$idea))
            ->assertSee('2 Comments');
    }

    /** @test */
    public function op_badge_shows_if_author_with_idea_comments_on_idea()
    {
        $user = User::factory()->create();

        $idea = Idea::factory()->create(['user_id' => $user->id]);

        $comment = Comment::factory()->create([
            'idea_id'   => $idea->id,
            'body'      => 'This is my first comment'
        ]);

        $commentTwo = Comment::factory()->create([
            'user_id'   => $user->id,
            'idea_id'   => $idea->id,
            'body'      => 'This is my second comment'
        ]);

        $response = $this->get(route('idea.show',$idea));

        $response->assertSee('OP');
    }

    /**
     * @test
     */
    public function comments_pagination_works()
    {
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id'   => $idea->id,
            'body'      => 'This is my first comment'
        ]);

        Comment::factory($comment->getPerPage())->create([
            'idea_id'   => $idea->id,
        ]);

        $response = $this->get(route('idea.show',$idea));

        $response->assertSee($comment->body);

        $response->assertDontSee(Comment::find(Comment::count())->body);

        $response = $this->get(route('idea.show',[
            'idea'  => $idea,
            'page'  => 2,
        ]));

        $response->assertDontSee($comment->body);
        $response->assertSee(Comment::find(Comment::count())->body);
    }
}
