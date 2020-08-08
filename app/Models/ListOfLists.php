<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListOfLists extends Model
{
    use SoftDeletes;

    protected $fillable //говорит для fill в Controller какие поля можно перезаписывать
        = [
            'name',
        ];
}
