<?php

namespace App\Services;

use App\Exceptions\TodoValidationException;
use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoService implements TodoServiceInterface
{
    /**
     * @return Collection<int, Todo>
     */
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Todo::all();
    }

    /**
     * @param Request $request
     * @return Todo
     * @throws TodoValidationException
     */
    public function store(Request $request): Todo
    {
        $this->validateStoreRequest($request);

        return Todo::create($request->only(['title', 'description', 'status']));
    }

    /**
     *
     * @param int $id
     * @return Todo
     * @throws ModelNotFoundException
     */
    public function show(int $id): Todo
    {
        return Todo::findOrFail($id);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Todo
     * @throws TodoValidationException
     * @throws ModelNotFoundException
     */
    public function update(Request $request, int $id): Todo
    {
        $this->validateUpdateRequest($request);

        $todo = Todo::findOrFail($id);
        $todo->update($request->all());

        return $todo;
    }

    /**
     * @param int $id
     * @return Todo
     */
    public function destroy(int $id): Todo
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return $todo;
    }

    /**
     * @throws TodoValidationException
     */
    private function validateStoreRequest(Request $request): void
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:pending,completed',
        ]);

        if ($validator->fails()) {
            throw new TodoValidationException($validator->errors());
        }
    }

    /**
     * @throws TodoValidationException
     */
    private function validateUpdateRequest(Request $request): void
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|string|in:pending,completed',
        ]);

        if ($validator->fails()) {
            throw new TodoValidationException($validator->errors());
        }
    }
}
