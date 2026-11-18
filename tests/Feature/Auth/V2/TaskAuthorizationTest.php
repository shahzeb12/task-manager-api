<?php

namespace Tests\Feature\Auth\V2;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;

class TaskAuthorizationTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_task_user_cannot_view_tasks_owned_by_others(): void
    {

        $owner = User::factory()->create();
        
        $otherUser = User::factory()->create();

        $task = Task::factory()->for($owner)->create();

        $this->actingAs($otherUser)
        ->getJson("/api/v2/tasks/{$task->id}")->
        assertForbidden();

    }


    public function test_task_user_cannot_update_tasks_owned_by_others(): void
    {

        $owner = User::factory()->create();
        
        $otherUser = User::factory()->create();

        $task = Task::factory()->for($owner)->create();

        $this->actingAs($otherUser)
        ->putJson("/api/v2/tasks/{$task->id}", [
            'name' => 'Unauthorized Update'
        ])->
        assertForbidden();

    }

    public function test_task_user_cannot_delete_tasks_owned_by_others(): void
    {

        $owner = User::factory()->create();
        
        $otherUser = User::factory()->create();

        $task = Task::factory()->for($owner)->create();

        $this->actingAs($otherUser)
        ->deleteJson("/api/v2/tasks/{$task->id}")->
        assertForbidden();

    }

    public function test_task_user_cannot_complated_tasks_owned_by_others(): void
    {

        $owner = User::factory()->create();
        
        $otherUser = User::factory()->create();

        $task = Task::factory()->for($owner)->create();

        $this->actingAs($otherUser)
        ->patchJson("/api/v2/tasks/{$task->id}/complete",[
            'is_completed' => true
        ])->
        assertForbidden();

    }


}
