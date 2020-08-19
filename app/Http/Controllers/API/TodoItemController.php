<?php

namespace App\Http\Controllers\API;

use App\Models\TodoItem;
use Exception;
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

        $items = TodoItem::whereIn('list_id', (new TodoItem())->getListIds())->get();

        return $this->sendResponse($items->toArray(), 'Задачи получены');
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

        if (!in_array($input['list_id'], (new TodoItem())->getListIds())) {
            return $this->sendError('Список с id не принадлежит пользователю');
        }

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
        $item = TodoItem::create($input);

        return $this->sendResponse($item->toArray(), 'Задача создана');
    }

    /**
     * Display the specified resource.
     *
     * @param TodoItem $item
     * @return JsonResponse
     */
    public function show(TodoItem $item)
    {
        if (!$item->isOwn()) {
            return $this->sendError('Задача не принадлежит данному пользователю');
        }

        return $this->sendResponse($item->toArray(), 'Задача получена');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param TodoItem $item
     * @return JsonResponse
     */
    public function update(Request $request, TodoItem $item)
    {
        if (!$item->isOwn()) {
            return $this->sendError('Задача не принадлежит данному пользователю');
        }

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

        if (!in_array($input['list_id'], (new TodoItem())->getListIds())) {
            return $this->sendError('Список с id не принадлежит пользователю');
        }

        $item->name = $input['name'];
        $item->complete = $input['complete'];
        $item->urgency = $input['urgency'];
        $item->list_id = $input['list_id'];
        $item->description = $input['description'];
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
        if (!$item->isOwn()) {
            return $this->sendError('Список не принадлежит данному пользователю');
        }

        $item->delete();

        return $this->sendResponse($item->toArray(), 'Список успешно удален');
    }
}
