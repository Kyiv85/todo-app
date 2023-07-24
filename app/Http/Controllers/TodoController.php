<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

use Illuminate\Support\Facades\Auth;

use Exception;

class TodoController extends Controller
{
    // Method for listing todos
    public function index()
    {
        $user = Auth::user();
        $todos = Todo::where('user_id', $user->id)->get();

        return response()->json(['todos' => $todos], 200);
    }

    // Method for create a todo
    public function store(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        try {
            $todo = Todo::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'completed' => $request->input('completed', false),
                'user_id' => $user->id,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error creating the information'], 401);
            error_log($e->getMessage());
        }

        return response()->json(['todo' => $todo], 201);
    }

    // Method for update a todo
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $todo = Todo::findOrFail($id);

        //Verify user belongs todo
        if ($user->id !== $todo->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => $request->input('description'),
            'completed' => 'boolean',
        ]);

        try {
            $todo->update($request->all());
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating the information'], 401);
            error_log($e->getMessage());
        }

        return response()->json(['todo' => $todo], 200);
    }

    // Method for deleting a todo
    public function destroy($id)
    {
        $user = Auth::user();
        $todo = Todo::findOrFail($id);

        if ($user->id !== $todo->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $todo->delete();
        } catch (Exception $e) {
            return response()->json(['message' => 'Error deleting the information'], 401);
            error_log($e->getMessage());
        }

        return response()->json(['message' => 'Todo deleted successfully'], 200);
    }
}
