<?php

namespace App\Http\Controllers\API;

use App\Models\ListOfLists;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $items = ListOfLists::where('user_id', '=', Auth::id())->get();

        return $this->sendResponse($items, 'Списки списков получены');
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
        $input['user_id'] = Auth::id();

        $validator = Validator::make($input, [
            'name' => 'required|min:5|max:255',
            'user_id' => 'required|integer|exists:users,id',
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
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(ListOfLists $list)
    {
        $this->authorize('view', $list);
        $lists = $list->todoLists;

        return $this->sendResponse(['list' => $lists->toArray()], 'Список списков получен');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ListOfLists $list
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, ListOfLists $list)
    {
        $this->authorize('update', $list);
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
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(ListOfLists $list)
    {
        $this->authorize('delete', $list);
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
