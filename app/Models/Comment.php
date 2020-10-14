<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['name','content'];

    public function newCollection(array $models = [])
    {
        return new CommentCollection($models);
    }
}
