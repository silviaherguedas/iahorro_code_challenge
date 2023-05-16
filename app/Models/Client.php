<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function leads()
    {
        return $this->hasOne(\App\Models\Lead::class);
    }
}
