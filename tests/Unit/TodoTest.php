<?php

namespace Tests\Unit;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test retrieving all todos.
     *
     * @return void
     */
    public function test_can_retrieve_all_todos()
    {
        Todo::factory()->count(5)->create();

        $response = $this->getJson('/api/todos');

        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    /**
     * Test creating a new todo.
     *
     * @return void
     */
    public function test_can_create_todo()
    {
        $todoData = [
            'title' => 'Test Todo',
            'description' => 'Test Description',
            'status' => 'pending',
        ];

        $response = $this->postJson('/api/todos', $todoData);

        $response->assertStatus(201)
            ->assertJsonFragment($todoData);

        $this->assertDatabaseHas('todos', $todoData);
    }

    /**
     * Test retrieving a single todo.
     *
     * @return void
     */
    public function test_can_retrieve_single_todo()
    {
        $todo = Todo::factory()->create();

        $response = $this->getJson('/api/todos/' . $todo->id);

        $response->assertStatus(200)
            ->assertJson($todo->toArray());
    }

    /**
     * Test updating a todo.
     *
     * @return void
     */
    public function test_can_update_todo()
    {
        $todo = Todo::factory()->create();

        $updatedData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'status' => 'completed',
        ];

        $response = $this->putJson('/api/todos/' . $todo->id, $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment($updatedData);

        $this->assertDatabaseHas('todos', $updatedData);
    }

    /**
     * Test updating a todo.
     *
     * @return void
     */
    public function test_set_todo_invalid_status()
    {
        $todo = Todo::factory()->create();

        $updatedData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'status' => 'rejected',
        ];

        $response = $this->putJson('/api/todos/' . $todo->id, $updatedData);

        $response->assertStatus(400);
    }

    /**
     * Test deleting a todo.
     *
     * @return void
     */
    public function test_can_delete_todo()
    {
        $todo = Todo::factory()->create();

        $response = $this->deleteJson('/api/todos/' . $todo->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }
}
