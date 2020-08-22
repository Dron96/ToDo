<?php

namespace App\Http\Controllers\API;

use App\Models\TodoList;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $todoListIds = TodoList::getListOfListsIds(Auth::id());
        $items = TodoList::whereIn('list_id', $todoListIds)->get();

        return $this->sendResponse($items->toArray(), 'Списки получены');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
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
        $item = new TodoList();
        $item->fill([
            'name' => $input['name'],
            'list_id' => $input['list_id'],
        ]);
        $this->authorize('create', $item);
        $item->save();

        return $this->sendResponse($item->toArray(), 'Список успешно создан');
    }

    /**
     * Display the specified resource.
     *
     * @param TodoList $list
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(TodoList $list)
    {
        $this->authorize('view', $list);
        $items = $list->items;

        return $this->sendResponse($items, 'Список получен');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param TodoList $list
     * @return JsonResponse
     * @throws AuthorizationException
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
        $list->fill([
            'name' => $input['name'],
            'list_id' => $input['list_id'],
        ]);
        $this->authorize('update', $list);
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
        $this->authorize('delete', $list);
        $todoItems = $list->items()->get();
        foreach ($todoItems as $todoItem) {
            $todoItem->delete();
        }
        $list->delete();

        return $this->sendResponse($list->toArray(), 'Список успешно удален');
    }
}
