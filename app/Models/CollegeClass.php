<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollegeClass extends Model
{
    /**
     * The DB table used by this model
     *
     * @var string
     */
    protected $table = 'classes';

    protected $guarded = ['id'];
}
