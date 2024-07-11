<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCondominios extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','condominio_id'];

}
