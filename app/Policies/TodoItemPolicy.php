<?php

namespace App\Policies;

use App\Models\TodoItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TodoItemPolicy
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
     * @param TodoItem $todoItem
     * @return mixed
     */
    public function view(User $user, TodoItem $todoItem)
    {
        return $todoItem->isOwn($user->id)
            ? Response::allow()
            : Response::deny('Задача не принадлежит данному пользователю');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param TodoItem $item
     * @return Response
     */
    public function create(User $user, TodoItem $item)
    {
        $ids = TodoItem::getListIds($user->id);
        $list_id = $item->list_id;

        return in_array($list_id, $ids)
            ? Response::allow()
            : Response::deny('Список не принадлежит пользователю');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param TodoItem $todoItem
     * @return mixed
     */
    public function update(User $user, TodoItem $todoItem)
    {
        $ids = TodoItem::getListIds($user->id);
        $list_id = $todoItem->list_id;

        if ($todoItem->isOwn($user->id)) {
            if (in_array($list_id, $ids)) {
                return Response::allow();
            }
        }

        return Response::deny('Список или задача не принадлежит данному пользователю');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param TodoItem $todoItem
     * @return mixed
     */
    public function delete(User $user, TodoItem $todoItem)
    {
        return $todoItem->isOwn($user->id)
            ? Response::allow()
            : Response::deny('Задача не принадлежит данному пользователю');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param TodoItem $todoItem
     * @return mixed
     */
    public function restore(User $user, TodoItem $todoItem)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param TodoItem $todoItem
     * @return mixed
     */
    public function forceDelete(User $user, TodoItem $todoItem)
    {
        //
    }
}
