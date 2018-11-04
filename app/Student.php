<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];

    protected $appends = ['courses_count'];

    protected $visible = ['id', 'name', 'courses_count'];

    public function courses(){
        return $this->belongsToMany('\App\Course');
    }

    public function grades(){
        return $this->hasMany('\App\Grade');
    }

    public function getCoursesCountAttribute(){
        return $this->courses->count();
    }
}
