<?php

namespace App\Policies;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TodoListPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param TodoList $todoList
     * @return mixed
     */
    public function view(User $user, TodoList $todoList)
    {
        return $todoList->isOwn($user->id)
            ? Response::allow()
            : Response::deny('Список не принадлежит данному пользователю');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param TodoList $list
     * @return Response
     */
    public function create(User $user, TodoList $list)
    {
        $ids = TodoList::getListOfListsIds($user->id);
        $list_id = $list->list_id;

        return in_array($list_id, $ids)
            ? Response::allow()
            : Response::deny('Список не принадлежит пользователю');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param TodoList $todoList
     * @return mixed
     */
    public function update(User $user, TodoList $todoList)
    {
        $ids = TodoList::getListOfListsIds($user->id);
        $list_id = $todoList->list_id;

        if ($todoList->isOwn($user->id)) {
            if (in_array($list_id, $ids)) {
                return Response::allow();
            }
        }

        return Response::deny('Список не принадлежит данному пользователю');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param TodoList $todoList
     * @return mixed
     */
    public function delete(User $user, TodoList $todoList)
    {
        return $todoList->isOwn($user->id)
            ? Response::allow()
            : Response::deny('Список не принадлежит данному пользователю');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param TodoList $todoList
     * @return mixed
     */
    public function restore(User $user, TodoList $todoList)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param TodoList $todoList
     * @return mixed
     */
    public function forceDelete(User $user, TodoList $todoList)
    {
        //
    }
}
