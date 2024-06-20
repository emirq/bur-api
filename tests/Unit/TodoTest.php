<?php

namespace Tests\Unit;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_all_todos()
    {
        // Arrange
        \App\Models\Todo::factory()->count(3)->create();

        // Act
        $response = $this->get('/api/todos');

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => null,
            ])
            ->assertJsonCount(3);
    }

    public function test_it_creates_a_new_todo_successfully()
    {
        $data = [
            'title' => 'New Todo',
            'description' => 'This is a new todo item',
            'status' => 'pending',
        ];

        $response = $this->postJson('/api/todos', $data);

        $response->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJson([
                'success' => true,
                'message' => null,
                'data' => $data,
            ]);

        $this->assertDatabaseHas('todos', $data);
    }

    public function test_it_validates_required_fields_on_store()
    {
        $data = [
            // 'title' is intentionally missing
            'description' => 'This is a new todo item',
            'status' => 'pending',
        ];

        $response = $this->postJson('/api/todos', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('title');
    }

    public function test_it_shows_existing_todo()
    {
        $todo = \App\Models\Todo::factory()->create();

        $response = $this->get("/api/todos/$todo->id");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'success' => true,
                'message' => null,
                'data' => [
                    'id' => $todo->id,
                    'title' => $todo->title,
                    'description' => $todo->description,
                    'status' => $todo->status,
                ],
            ]);
    }

    public function test_it_returns_not_found_if_todo_not_exists()
    {
        $id = 123;

        $response = $this->get("/api/todos/$id");

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND)
            ->assertJson([
                'success' => false,
                'message' => 'Todo not found',
            ]);
    }

    public function test_it_updates_existing_todo_successfully()
    {
        $todo = \App\Models\Todo::factory()->create();

        $updateData = [
            'title' => 'Updated Todo',
            'description' => 'This is an updated todo item',
            'status' => 'completed',
        ];

        $response = $this->putJson("/api/todos/$todo->id", $updateData);

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'success' => true,
                'message' => null,
                'data' => [
                    'id' => $todo->id,
                    'title' => 'Updated Todo',
                    'description' => 'This is an updated todo item',
                    'status' => 'completed',
                ],
            ]);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => 'Updated Todo',
            'description' => 'This is an updated todo item',
            'status' => 'completed',
        ]);
    }

    public function test_it_returns_not_found_during_update_if_todo_does_not_exist()
    {
        $nonExistingId = 123;

        $updateData = [
            'title' => 'Updated Todo',
            'description' => 'This is an updated todo item',
            'status' => 'completed',
        ];

        // Act: Make a PUT request to the update method with the non-existing ID
        $response = $this->putJson("/api/todos/$nonExistingId", $updateData);

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND)
            ->assertJson([
                'success' => false,
                'message' => 'Todo not found',
            ]);
    }

    public function test_it_deletes_existing_todo_successfully()
    {
        $todo = \App\Models\Todo::factory()->create();

        $response = $this->delete("/api/todos/$todo->id");

        $response->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }

    public function test_it_returns_not_found_during_deletion_if_todo_does_not_exist()
    {
        $id = 123;

        $response = $this->delete("/api/todos/$id");

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND)
            ->assertJson([
                'success' => false,
                'message' => 'Todo not found',
            ]);
    }
}
