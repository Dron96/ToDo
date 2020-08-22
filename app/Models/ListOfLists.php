<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Ramsey\Collection\Collection;

/**
 * Class ListOfLists
 *
 * @package App\Models
 * @mixin Eloquent
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property int $user_id
 * @property array|Collection|TodoList $todoLists
 */
class ListOfLists extends Model
{
    use SoftDeletes;

    //говорит для fill в Controller какие поля можно перезаписывать
    protected $fillable
        = [
            'name',
            'user_id',
        ];

    public function todoLists()
    {
        return $this->hasMany(TodoList::class, 'list_id', 'id');
    }

    public function isOwn($user_id)
    {
        return ($this->user_id === $user_id);
    }
}
