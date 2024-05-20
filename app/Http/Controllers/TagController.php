<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class TagController extends Controller
{

    public function index(Request $request)
    {
        $tags = QueryBuilder::for(Tag::class)
            ->defaultSort('-id')
            ->allowedFilters(['title'])
            ->paginate($request->get('perPage', 10));;
        return response()->json($tags);
    }


    public function store(Request $request)
    {

        $validated = $request->validate(
            [
                'title' => 'required|string|unique:tags,title|max:32|regex:/^[а-яА-ЯёЁA-Za-z]+$/u',
            ],
            [
                'title.unique' => 'Тег с таким названием уже существует',
                'title.regex' => 'Название должно содержать только буквы',
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
            ],
            [
                'title.unique' => 'Тег с таким названием уже существует',
            ],
        );

        $tag = Tag::findOrFail($id);
        $tag->update(['title' => $validated['title']]);

        return response()->json(['message' => 'Тег успешно обновлен']);
    }

    public function destroy(Request $request, $id)
    {



        $tag = Tag::findOrFail($id);
        $tag->delete();

        return response()->json(['message' => 'Тег успешно удален']);
    }
}
