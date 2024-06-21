<?php

namespace App\Services;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface TodoServiceInterface
{
    public function all(): Collection;
    public function store(Request $request): Todo;
    public function show(int $id): Todo;
    public function update(Request $request, int $id): Todo;
    public function destroy(int $id): Todo;
}
