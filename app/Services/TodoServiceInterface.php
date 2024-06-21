<?php

namespace App\Services;

use App\Exceptions\TodoValidationException;
use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

interface TodoServiceInterface
{
    /**
     * @return Collection<int, Todo>
     */
    public function all(): Collection;

    /**
     * @param Request $request
     * @return Todo
     * @throws TodoValidationException
     */
    public function store(Request $request): Todo;

    /**
     *
     * @param int $id
     * @return Todo
     * @throws ModelNotFoundException
     */
    public function show(int $id): Todo;

    /**
     * @param Request $request
     * @param int $id
     * @return Todo
     * @throws TodoValidationException
     * @throws ModelNotFoundException
     */
    public function update(Request $request, int $id): Todo;

    /**
     * @param int $id
     * @return Todo
     */
    public function destroy(int $id): Todo;
}
