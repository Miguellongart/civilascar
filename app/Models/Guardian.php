<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'relationship', 'document'];

    public function parent()
    {
        return $this->belongsTo(User::class);
    }
}
