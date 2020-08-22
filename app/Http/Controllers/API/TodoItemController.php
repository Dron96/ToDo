<?php

namespace App\Http\Controllers\API;

use App\Models\TodoItem;
use Auth;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoItemController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $TodoLists = TodoItem::getListIds(Auth::id());
        $items = TodoItem::whereIn('list_id', $TodoLists)->get();

        return $this->sendResponse($items->toArray(), 'Задачи получены');
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
            'complete' => 'integer|max:0',
            'urgency' => 'required|integer|max:5',
            'list_id' => 'required|integer|exists:todo_lists,id',
            'description' => 'string|max:3000|min:10',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Ошибка валидации', $validator->errors());
        }
        $item = new TodoItem();
        $item->fill($input);
        $this->authorize('create', $item);
        $item->save();

        return $this->sendResponse($item->toArray(), 'Задача создана');
    }

    /**
     * Display the specified resource.
     *
     * @param TodoItem $item
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(TodoItem $item)
    {
        $this->authorize('view', $item);

        return $this->sendResponse($item->toArray(), 'Задача получена');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param TodoItem $item
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, TodoItem $item)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|min:5|max:255',
            'complete' => 'required|integer|max:1',
            'urgency' => 'required|integer|max:5',
            'list_id' => 'required|integer|exists:todo_lists,id',
            'description' => 'string|max:3000|min:10',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Ошибка валидации', $validator->errors());
        }

        $item->fill([
            'name' => $input['name'],
            'complete' => $input['complete'],
            'urgency' => $input['urgency'],
            'list_id' => $input['list_id'],
            'description' => $input['description'],
        ]);
        $this->authorize('update', $item);
        $item->save();

        return $this->sendResponse($item->toArray(), 'Задача успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     * @param TodoItem $item
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(TodoItem $item)
    {
        $this->authorize('delete', $item);
        $item->delete();

        return $this->sendResponse($item->toArray(), 'Список успешно удален');
    }
}
