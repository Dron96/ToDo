<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\TodoItem
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property bool $complete
 * @property int $urgency
 * @property int $list_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property TodoList $list
 *
 * @mixin Eloquent
 */
class TodoItem extends Model
{
    use SoftDeletes;

    protected $fillable //говорит для fill в Controller какие поля можно перезаписывать
        = [
            'name',
            'complete',
            'list_id',
            'description',
            'urgency'
        ];

    public function list()
    {
        return $this->belongsTo(TodoList::class, 'list_id', 'id');
    }

    public function isOwn($user_id)
    {
        return ($this->list->list->user_id === $user_id);
    }

    public static function getListIds($user_id)
    {
        $ids = array();

        $lists = ListOfLists::where('user_id', '=', $user_id)->get();
        foreach ($lists as $list) {
            $items = $list->todoLists;
            foreach ($items as $item) {
                $ids[] = $item->id;
            }
        }

        return $ids;
    }
}
