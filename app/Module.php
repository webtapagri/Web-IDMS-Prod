<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'tbm_module';
    public $timestamps = false;

    protected $fillable = [
        "name",
        "description",
        "sort",
        "icon",
        "created_by",
        "updated_by",
        "deleted"
    ]; 

}
