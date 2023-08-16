<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'column_id'
    ];

    public function column()
    {
        return $this->belongsTo(Column::class);
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }
}
