<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

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
 */

class ListOfLists extends Model
{
    use SoftDeletes;

    protected $fillable //говорит для fill в Controller какие поля можно перезаписывать
        = [
            'name',
        ];

    public function todoLists()
    {
        return $this->hasMany(TodoList::class, 'list_id', 'id');
    }
}
