<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\TodoList
 *
 * @property int $id
 * @property string $name
 * @property int $list_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property ListOfLists $list
 * @property TodoItem $items
 *
 * @mixin Eloquent
 */
class TodoList extends Model
{
    use SoftDeletes;

    protected $fillable //говорит для fill в Controller какие поля можно перезаписывать
        = [
            'name',
            'list_id'
        ];

    public function list()
    {
        return $this->belongsTo(ListOfLists::class, 'list_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(TodoItem::class, 'list_id', 'id');
    }

    public function isOwn($user_id)
    {
        return ($this->list->user_id === $user_id);
    }

    public static function getListOfListsIds($user_id)
    {
        $ids = array();

        $lists = ListOfLists::where('user_id', '=', $user_id)->get();
        foreach ($lists as $list) {
            $ids[] = $list->id;
        }

        return $ids;
    }

}
