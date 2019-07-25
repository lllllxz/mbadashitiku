<?php
/**
 * Created by PhpStorm.
 * User: Liuxuezhi
 * Date: 2019/7/25
 * Time: 16:19
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function scopeLogic($query)
    {
        return $query->where('subject',1);
    }
}