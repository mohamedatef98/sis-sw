<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = [];

    protected $appends = ['students_count'];

    protected $visible = ['id', 'name', 'max_grade', 'students_count'];

    public function students(){
        return $this->belongsToMany('\App\Student');
    }

    public function grades(){
        return $this->hasMany('\App\Grade');
    }

    public function getStudentsCountAttribute(){
        return $this->students->count();
    }

}
