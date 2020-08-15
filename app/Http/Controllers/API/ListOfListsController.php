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
     * @param ListOfLists $list
     * @return JsonResponse
     */
    public function show(ListOfLists $list)
    {
        $lists = $list->todoLists()->get();
        return $this->sendResponse($lists->toArray(), 'Список списков получен');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ListOfLists $list
     * @return JsonResponse
     */
    public function update(Request $request, ListOfLists $list)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|min:5|max:255',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Ошибка валидации', $validator->errors());
        }
        $list->name = $input['name'];
        $list->save();
        return $this->sendResponse($list->toArray(), 'Список списков успешно обновлен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ListOfLists $list
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(ListOfLists $list)
    {
        $todoLists = $list->todoLists()->get();
        foreach ($todoLists as $todoList) {
            $todoItems = $todoList->items()->get();
            foreach ($todoItems as $todoItem) {
                $todoItem->delete();
            }
            $todoList->delete();
        }
        $list->delete();

        return $this->sendResponse($list->toArray(), 'Список списков успешно удален');
    }
}
