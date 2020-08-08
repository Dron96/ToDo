<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TodoItemCreateRequest;
use App\Http\Requests\TodoItemUpdateRequest;
use App\Models\TodoItem;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function GuzzleHttp\Promise\all;

class TodoItemController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $items = TodoItem::all();

        return $this->sendResponse($items->toArray(), 'Задачи получены');
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
            'complete' => 'integer|max:0',
            'urgency' => 'required|integer|max:5',
            'list_id' => 'required|integer|exists:todo_lists,id',
            'description' => 'string|max:3000|min:10',

        ]);

        if ($validator->fails()){
            return $this->sendError('Ошибка валидации', $validator->errors());
        }
        $item = TodoItem::create($input);
        return $this->sendResponse($item->toArray(), 'Задача создана');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $item = TodoItem::find($id);
        if (is_null($item)) {
            return $this->sendError('Список не найден', 404);
        }
        return $this->sendResponse($item->toArray(), 'Задача получена');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $item = TodoItem::find($id);

        if (empty($item)) {
            return $this->sendError("Запись id=[{$id}] не найдена");
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|min:5|max:255',
            'complete' => 'required|integer|max:1',
            'urgency' => 'required|integer|max:5',
            'list_id' => 'required|integer|exists:todo_lists,id',
            'description' => 'string|max:3000|min:10',
        ]);
        if ($validator->fails()){
            return $this->sendError('Ошибка валидации', $validator->errors());
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $item = TodoItem::find($id);

        if (empty($item)) {
            return $this->sendError("Запись id=[{$id}] не найдена");
        }

        $item->delete();

        return $this->sendResponse($item->toArray(), 'Список успешно удален');
    }
}
