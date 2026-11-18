<?php

namespace Tests\Feature\Api\V2;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_task(): void
    {
        $response = $this->postJson('/api/v2/tasks',[
            'name' => 'New Task'
            ]
        );

        $response->assertCreated();

        $response->assertJsonStructure([
            'data' => 
                ['id', 'name', 'is_completed']
            
        ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'New Task'
        ]);
    }

    public function test_user_cannot_create_invalid_task(): void
    {
        $response = $this->postJson('/api/v2/tasks',[
            'name' => ''
            ]
        );

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['name']);


    }

    public function test_user_can_get_list_of_tasks(): void
    {
        $tasks  = Task::factory(2)->count(2)->create();

        // Http get request expects json

        $response = $this->getJson('/api/v2/tasks');

        $response->assertOk();

        $response->assertJsonCount(2, 'data');

        $response->assertJsonStructure([
            'data' => [
                ['id', 'name', 'is_completed']
            ]
        ]);
    }

    public function test_user_can_update_task(): void
    {
        $task  = Task::factory()->create();
        
        $response = $this->putJson('/api/v2/tasks/' . $task->id, [
            'name' => 'Updated task'
        ]);

        $response->assertOk();

        $response->assertJsonFragment([
            'name' => 'Updated task'
            
        ]);

    }

    public function test_user_cannot_update_task_with_invalid_data(): void
    {

        $task  = Task::factory()->create();

        $response = $this->putJson('/api/v2/tasks/' . $task->id, [
            'name' => ''
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['name']);

    }

    public function test_user_can_toggle_task_completion(): void
    {
        $task = Task::factory()->create([
            'is_completed' => false,
        ]);

        $response = $this->patchJson('/api/v2/tasks/' . $task->id . '/complete', [
            'is_completed' => true,
        ]);

        $response->assertOk();

        $response->assertJsonFragment([
            'is_completed' => true
            
        ]);
    }

    public function test_user_cannot_toggle_completed_with_invalid_data(): void
    {

        $task = Task::factory()->create([
            'is_completed' => false,
        ]);

        $response = $this->patchJson('/api/v2/tasks/' . $task->id . '/complete', [
            'is_completed' => "Yes",
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['is_completed']);
    }

    public function test_user_can_get_single_task(): void
    {
         $task  = Task::factory()->create();

         $response = $this->getJson('/api/v2/tasks/' . $task->id);

         $response->assertOk();

        $response->assertJsonStructure([
            'data' => 
                ['id', 'name', 'is_completed']
            
        ]);

        $response->assertJson([
              'data' => [
                'id' => $task->id,
                'name' => $task->name,
                'is_completed' => $task->is_completed
              ]  
        ]);

    }

    public function test_user_can_delete_task(): void
    {

        $task  = Task::factory()->create();

        $response = $this->deleteJson('/api/v2/tasks/' . $task->id);

        $response->assertNoContent();

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id
        ]);
    }
}
