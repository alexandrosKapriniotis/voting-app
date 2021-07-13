<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_count_of_each_status()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['id' => 1,'name' => 'Open']);
        $statusConsidering = Status::factory()->create(['id' => 2,'name' => 'Considering']);
        $statusInProgress = Status::factory()->create(['id' => 3,'name' => 'In Progress']);
        $statusImplemented = Status::factory()->create(['id' => 4,'name' => 'Implemented']);
        $statusClosed = Status::factory()->create(['id' => 5,'name' => 'Closed']);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusConsidering->id,
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusConsidering->id,
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusInProgress->id,
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusInProgress->id,
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusInProgress->id,
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusImplemented->id,
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusImplemented->id,
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusImplemented->id,
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusImplemented->id,
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusClosed->id,
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusClosed->id,
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusClosed->id,
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusClosed->id,
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusClosed->id,
        ]);

        $this->assertEquals(15, Status::getCount()['all_statuses']);
        $this->assertEquals(1, Status::getCount()['open']);
        $this->assertEquals(2, Status::getCount()['considering']);
        $this->assertEquals(3, Status::getCount()['in_progress']);
        $this->assertEquals(4, Status::getCount()['implemented']);
        $this->assertEquals(5, Status::getCount()['closed']);
    }
}
