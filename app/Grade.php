<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $guarded = [];

    protected $visible = ['grade'];

    public function student(){
        return $this->belongsTo('\App\Student');
    }

    public function course(){
        return $this->belongsTo('\App\Course');
    }
}
