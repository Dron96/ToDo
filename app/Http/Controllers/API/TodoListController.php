<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\TodoListCreateRequest;
use App\Http\Requests\TodoListUpdateRequest;
use App\Models\ListOfLists;
use App\Models\TodoItem;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoListController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $items = TodoList::all();

        return $this->sendResponse($items->toArray(), 'Списки получены');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|min:5|max:255',
            'list_id' => 'required|integer|exists:list_of_lists,id',
        ]);
        if ($validator->fails()){
            return $this->sendError('Ошибка валидации', $validator->errors());
        }
        $item = TodoList::create($input);
        return $this->sendResponse($item->toArray(), 'Список успешно создан');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $item = TodoItem::all()->where('list_id', $id);
        if (is_null($item)) {
            return $this->sendError('Список не найден', 404);
        }
        return $this->sendResponse($item->toArray(), 'Список получен');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $item = TodoList::find($id);

        if (empty($item)) {
            return $this->sendError("Запись id=[{$id}] не найдена");
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|min:5|max:255',
            'list_id' => 'required|integer|exists:list_of_lists,id',
        ]);
        if ($validator->fails()){
            return $this->sendError('Ошибка валидации', $validator->errors());
        }
        $item->name = $input['name'];
        $item->list_id = $input['list_id'];
        $item->save();
        return $this->sendResponse($item->toArray(), 'Список успешно обновлен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $item = TodoList::find($id);

        if (empty($item)) {
            return $this->sendError("Запись id=[{$id}] не найдена");
        }

        $todoItems = TodoItem::all()->where('list_id', '=', "{$item->id}");
        foreach ($todoItems as $todoItem) {
            $todoItem->delete();
        }

        $item->delete();

        return $this->sendResponse($item->toArray(), 'Список успешно удален');
    }
}
