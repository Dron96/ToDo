<?php

namespace App\Http\Controllers\API;

use App\Models\ListOfLists;
use App\Models\TodoItem;
use App\Models\TodoList;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Validator;

class ListOfListsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $items = ListOfLists::all();

        return $this->sendResponse($items->toArray(), 'Списки списков получены');
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
        ]);

        if ($validator->fails()) {
            return $this->sendError('Ошибка валидации', $validator->errors());
        }
        $item = ListOfLists::create($input);
        return $this->sendResponse($item->toArray(), 'Список списков успешно создан');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $item = TodoList::all()->where('list_id', $id);
        if (is_null($item)) {
            return $this->sendError('Список списков не найден', 404);
        }
        return $this->sendResponse($item->toArray(), 'Список списков получен');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $item = ListOfLists::find($id);

        if (empty($item)) {
            return $this->sendError("Запись id=[{$id}] не найдена");
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|min:5|max:255',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Ошибка валидации', $validator->errors());
        }
        $item->name = $input['name'];
        $item->save();
        return $this->sendResponse($item->toArray(), 'Список списков успешно обновлен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($id)
    {
        $item = ListOfLists::find($id);

        if (empty($item)) {
            return $this->sendError("Запись id=[{$id}] не найдена");
        }

        $todoLists = TodoList::all()->where('list_id', '=', "{$item->id}");

        foreach ($todoLists as $todoList) {
            $todoItems = TodoItem::all()->where('list_id', '=', "{$todoList->id}");

            foreach ($todoItems as $todoItem) {
                $todoItem->delete();
            }
            $todoList->delete();
        }
        $item->delete();

        return $this->sendResponse($item->toArray(), 'Список списков успешно удален');
    }
}
