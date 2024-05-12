<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class TagController extends Controller
{

    public function index()
    {
        $tags = QueryBuilder::for(Tag::class)->defaultSort('-id')->allowedFilters(['title'])->get();
        return response()->json($tags);
    }

    public function show(Request $request, $id)
    {
    }

    public function store(Request $request)
    {

        $validated = $request->validate(
            [
                'title' => 'required|string|unique:tags,title|max:32',
            ],
            [
                'title.unique' => 'Тег с таким названием уже существует',
            ],
        );

        $tag = Tag::create($validated);

        return response()->json(['message' => 'Тег успешно создан']);
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate(
            [
                'title' => 'required|string|unique:tags,title|max:32',
                'id' => 'required|exists:tags,id',
            ],
            [
                'title.unique' => 'Тег с таким названием уже существует',
            ],
        );

        $tag = Tag::find($validated['id']);
        $tag->update(['title' => $validated['title']]);

        return response()->json(['message' => 'Тег успешно обновлен']);
    }

    public function destroy(Request $request, $id)
    {

        $validated = $request->validate(
            [
                'id' => 'required|exists:tags,id',
            ],
        );

        $tag = Tag::find($validated['id']);
        $tag->delete();

        return response()->json(['message' => 'Тег успешно удален']);
    }
}
