<?php


namespace Tests\Feature\Comments;

use App\Http\Livewire\IdeaComment;
use App\Http\Livewire\IdeaComments;
use App\Http\Livewire\IdeaIndex;
use App\Http\Livewire\IdeaShow;
use App\Http\Livewire\MarkCommentAsNotSpam;
use App\Http\Livewire\MarkIdeaAsNotSpam;
use App\Http\Livewire\MarkIdeaAsSpam;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

class SpamManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_mark_comment_as_spam_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();
        $idea = Idea::factory()->create();

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSeeLivewire('mark-comment-as-not-spam');
    }

    /** @test */
    public function does_not_show_mark_idea_as_not_spam_livewire_component_when_user_does_not_have_authorization()
    {
        $idea = Idea::factory()->create();

        $this
            ->get(route('idea.show', $idea))
            ->assertDontSeeLivewire('mark-idea-as-not-spam');
    }

    /** @test */
    public function marking_an_idea_as_not_spam_works_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();

        $idea = Idea::factory()->create();
        $comment = Comment::factory()->create([
            'idea_id' => $idea->id
        ]);

        Livewire::actingAs($user)
            ->test(MarkCommentAsNotSpam::class)
            ->call('setMarkAsNotSpamComment',$comment->id)
            ->assertEmitted('markAsNotSpamCommentWasSet')
            ->call('markAsNotSpam')
            ->assertEmitted('commentWasMarkedAsNotSpam');

        $this->assertEquals(0, Idea::first()->spam_reports);
    }

    /** @test */
    public function marking_an_idea_as_not_spam_does_not_work_when_user_does_not_have_authorization()
    {
        Idea::factory()->create();

        Livewire::
        test(MarkCommentAsNotSpam::class)
            ->call('markAsNotSpam')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function marking_a_comment_as_not_spam_shows_on_menu_when_user_has_authorization()
    {
        $user    = User::factory()->create();
        $idea    = Idea::factory()->create();
        $comment = Comment::factory()->create([
            'idea_id' => $idea->id
        ]);

        Livewire::actingAs($user)
            ->test(IdeaComment::class,[
                'comment'    => $comment,
                'ideaUserId' => $idea->user_id
            ])
            ->assertSee('Mark as Spam');
    }

    /** @test */
    public function marking_a_comment_as_spam_does_not_show_on_menu_when_user_does_not_have_authorization()
    {
        $idea    = Idea::factory()->create();
        $comment = Comment::factory()->create([
            'idea_id' => $idea->id
        ]);

        Livewire::
            test(IdeaComment::class,[
                'comment'    => $comment,
                'ideaUserId' => $idea->user_id
            ])
            ->assertDontSee('Mark as Spam');
    }

    /** @test */
    public function marking_a_comment_as_not_spam_does_not_show_on_menu_when_user_does_not_have_authorization()
    {
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id
        ]);

        Livewire::test(IdeaComment::class, [
            'comment'    => $comment,
            'ideaUserId' => $idea->user_id
        ])->assertDontSee('Not Spam');
    }

    /** @test */
    public function spam_reports_count_shows_on_idea_comments_page_if_logged_in_as_admin()
    {
        $user = User::factory()->admin()->create();
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'spam_reports' => 3
        ]);

        Livewire::actingAs($user)
            ->test(IdeaComments::class, [
                'idea' => $idea
            ])
            ->assertSee('Spam Reports: 3');
    }

    /** @test */
    public function spam_reports_count_shows_on_ideas_show_page_if_logged_in_as_admin()
    {
        $user = User::factory()->admin()->create();
        $idea = Idea::factory()->create([
            'spam_reports' => 3
        ]);

        Livewire::actingAs($user)
            ->test(IdeaShow::class, [
                'idea' => $idea,
                'votesCount' => 4,
            ])
            ->assertSee('Spam Reports: 3');
    }
}
