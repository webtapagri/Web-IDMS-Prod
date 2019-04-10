<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "tbm_menu";
    public $timestamps = false;

    protected $fillable = [
        "id",
        "module_id",
        "name",
        "url",
        "sort",
        "deleted",
        "created_by",
        "updated_by"
    ];
}
