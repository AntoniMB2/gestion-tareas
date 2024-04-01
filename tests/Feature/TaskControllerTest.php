<?php

/* namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
    }

    public function testIndex()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/tasks');

        $response->assertStatus(200);
    }

    public function testShow()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $task = Task::factory()->create();

        $response = $this->get('/tasks/' . $task->id);

        $response->assertStatus(200);
    }

    public function testStore()
    {
        $user = User::factory()->create(['role' => 'superadmin']);
        $this->actingAs($user);

        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'Pendiente',
            'assigned_to' => $user->id
        ];

        $response = $this->post('/tasks', $taskData);

        $response->assertStatus(201);
    }

    public function testUpdate()
    {
        $user = User::factory()->create(['role' => 'superadmin']);
        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $updatedTaskData = [
            'title' => 'Updated Test Task',
            'description' => 'Updated Test Description',
            'status' => 'En_progreso',
            'assigned_to' => $user->id
        ];

        $response = $this->put('/tasks/' . $task->id, $updatedTaskData);

        $response->assertStatus(200);
    }

    public function testDestroy()
    {
        $user = User::factory()->create(['role' => 'superadmin']);
        $this->actingAs($user);

        $task = Task::factory()->create();

        $response = $this->delete('/tasks/' . $task->id);

        $response->assertStatus(204);
    } */
/* } */

