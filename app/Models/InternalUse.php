<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalUse extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];
}
