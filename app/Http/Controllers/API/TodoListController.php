<?php

namespace App\Http\Controllers\API;

use App\Models\TodoList;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoListController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $items = TodoList::all();

        return $this->sendResponse($items->toArray(), 'Списки получены');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|min:5|max:255',
            'list_id' => 'required|integer|exists:list_of_lists,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Ошибка валидации', $validator->errors());
        }
        $item = TodoList::create($input);
        return $this->sendResponse($item->toArray(), 'Список успешно создан');
    }

    /**
     * Display the specified resource.
     *
     * @param TodoList $list
     * @return JsonResponse
     */
    public function show(TodoList $list)
    {
        $items = $list->items()->get();
        return $this->sendResponse($items, 'Список получен');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param TodoList $list
     * @return JsonResponse
     */
    public function update(Request $request, TodoList $list)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|min:5|max:255',
            'list_id' => 'required|integer|exists:list_of_lists,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Ошибка валидации', $validator->errors());
        }
        $list->name = $input['name'];
        $list->list_id = $input['list_id'];
        $list->save();
        return $this->sendResponse($list->toArray(), 'Список успешно обновлен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TodoList $list
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(TodoList $list)
    {
        $todoItems = $list->items()->get();
        foreach ($todoItems as $todoItem) {
            $todoItem->delete();
        }

        $list->delete();

        return $this->sendResponse($list->toArray(), 'Список успешно удален');
    }
}
