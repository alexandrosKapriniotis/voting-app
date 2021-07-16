<?php

namespace Tests\Feature;

use App\Http\Livewire\SetStatus;
use App\Jobs\NotifyAllVoters;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class AdminSetStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function show_page_contains_set_status_livewire_component_when_user_is_admin()
    {
        $user = User::factory()->create([
            'name'  => 'Alexander',
            'email' => 'admin@admin.com'
        ]);

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

        $this->actingAs($user)
              ->get(route('idea.show',$ideaOne))
              ->assertSeeLivewire('set-status');
    }

    /** @test */
    public function show_page_contains_set_status_livewire_component_when_user_is_not_admin()
    {
        $user = User::factory()->create([
            'name'  => 'Alexander',
            'email' => 'user@user.com'
        ]);

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

        $this->actingAs($user)
            ->get(route('idea.show',$ideaOne))
            ->assertDontSeeLivewire('set-status');
    }

    /** @test */
    public function initial_status_is_set_correctly()
    {
        $user = User::factory()->create([
            'name'  => 'Alexander',
            'email' => 'admin@admin.com'
        ]);

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusConsidering = Status::factory()->create(['name' => 'Considering']);

        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusConsidering->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        Livewire::actingAs($user)
            ->test(SetStatus::class,['idea' => $ideaOne])
            ->assertSet('status', $statusConsidering->id);
    }

    /** @test */
    public function can_set_status_correctly()
    {
        $user = User::factory()->create([
            'name'  => 'Alexander',
            'email' => 'admin@admin.com'
        ]);

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);
        $statusConsidering = Status::factory()->create(['name' => 'Considering']);

        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusConsidering->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        Livewire::actingAs($user)
            ->test(SetStatus::class,['idea' => $ideaOne])
            ->set('status',$statusOpen->id)
            ->call('setStatus')
            ->assertEmitted('statusWasUpdated');

        $this->assertDatabaseHas('ideas',[
            'id'        => $ideaOne->id,
            'status_id' => $statusOpen->id
        ]);
    }

    /** @test */
    public function can_set_status_correctly_while_notifying_all_voters()
    {
        $user = User::factory()->create([
            'name'  => 'Alexander',
            'email' => 'admin@admin.com'
        ]);

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);
        $statusConsidering = Status::factory()->create(['name' => 'Considering']);

        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusConsidering->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        Queue::fake();

        Queue::assertNothingPushed();

        Livewire::actingAs($user)
            ->test(SetStatus::class,['idea' => $ideaOne])
            ->set('status',$statusOpen->id)
            ->set('notifyAllVoters',true)
            ->call('setStatus')
            ->assertEmitted('statusWasUpdated');

        Queue::assertPushed(NotifyAllVoters::class);
    }
}
