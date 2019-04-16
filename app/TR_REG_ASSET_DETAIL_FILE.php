<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TR_REG_ASSET_DETAIL_FILE extends Model
{
    protected $table = 'TR_REG_ASSET_DETAIL_FILE';
     protected $guarded = ['id'];
    public $timestamps = false;

    protected $fillable = [
        "NO_REG_ITEM_FILE",
        "NO_REG",
        "JENIS_FOTO",
        "FILENAME",
        "DOC_SIZE",
        "FILE_CATEGORY",
        "FILE_UPLOAD",
        "CREATED_BY",
        "CREATED_AT",
        "UPDATED_BY",
        "UPDATED_AT"
    ]; 

}
