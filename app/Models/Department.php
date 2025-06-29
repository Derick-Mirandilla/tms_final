<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $primaryKey = 'department_id';

    public function categories()
    {
        return $this->hasMany(Category::class, 'department_id', 'department_id');
    }
}
