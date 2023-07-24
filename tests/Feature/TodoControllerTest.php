<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TodoControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing todos for authenticated user.
     *
     * @return void
     */
    public function testListTodos()
    {
        // Create a user
        $user = User::factory()->create();

        // Create some todos for the user
        Todo::factory(3)->create(['user_id' => $user->id]);

        // Simulate user authentication
        $this->actingAs($user);

        // Send the GET request to the index method of TodoController
        $response = $this->get('/api/todos');

        // Assert that the response is successful and contains the todos
        $response->assertStatus(200)
            ->assertJsonStructure([
                'todos' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'completed',
                        'user_id',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    /**
     * Test creating a new todo for authenticated user.
     *
     * @return void
     */
    public function testCreateTodo()
    {
        // Create a user
        $user = User::factory()->create();

        // Simulate user authentication
        $this->actingAs($user);

        // Todo data to be sent in the request
        $todoData = [
            'title' => 'New Todo',
            'description' => 'Todo description',
        ];

        // Send the POST request to the store method of TodoController
        $response = $this->post('/api/todos', $todoData);

        // Assert that the response is successful and contains the created todo
        $response->assertStatus(201)
            ->assertJsonStructure([
                'todo' => [
                    'id',
                    'title',
                    'description',
                    'completed',
                    'user_id',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    /**
     * Test updating a todo for authenticated user.
     *
     * @return void
     */
    public function testUpdateTodo()
    {
        // Create a user
        $user = User::factory()->create();

        // Simulate user authentication
        $this->actingAs($user);

        // Create a todo for the user
        $todo = Todo::factory()->create(['user_id' => $user->id]);

        // Updated todo data to be sent in the request
        $updatedTodoData = [
            'title' => 'Updated Todo',
            'description' => 'Updated description',
            'completed' => true,
        ];

        // Send the PUT request to the update method of TodoController
        $response = $this->put('/api/todos/' . $todo->id, $updatedTodoData);

        // Assert that the response is successful and contains the updated todo
        $response->assertStatus(200)
            ->assertJsonStructure([
                'todo' => [
                    'id',
                    'title',
                    'description',
                    'completed',
                    'user_id',
                    'created_at',
                    'updated_at'
                ]
            ]);

        // Assert that the todo in the database has been updated with the new data
        $this->assertDatabaseHas('todos', $updatedTodoData);
    }

    /**
     * Test deleting a todo for authenticated user.
     *
     * @return void
     */
    public function testDeleteTodo()
    {
        // Create a user
        $user = User::factory()->create();

        // Simulate user authentication
        $this->actingAs($user);

        // Create a todo for the user
        $todo = Todo::factory()->create(['user_id' => $user->id]);

        // Send the DELETE request to the destroy method of TodoController
        $response = $this->delete('/api/todos/' . $todo->id);

        // Assert that the response is successful and contains the success message
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Todo deleted successfully'
            ]);

        // Assert that the todo has been deleted from the database
        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }
}
