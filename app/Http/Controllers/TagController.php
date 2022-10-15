<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use  Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse | Collection
     */
    public function store(Request $request): JsonResponse|Collection
    {
        try {
            $request->validate(['name' => 'required']);
            return Tag::create(['name' => $request->name]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 409,);
        }
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Tag::all();
    }
}
