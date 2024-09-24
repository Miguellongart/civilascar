<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Children extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'name', 'age', 'uniform_size', 'child_document_path', 'document', 'birthdate'];

    public function parent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con los guardianes
    public function guardians()
    {
        return $this->hasMany(Guardian::class);
    }
}
