<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TR_REG_ASSET_DETAIL_PO extends Model
{
    protected $table = 'TR_REG_ASSET_DETAIL_PO';
     protected $guarded = ['id'];
    public $timestamps = false;

    protected $fillable = [
        "NO_REG",
        "NO_PO",
        "ITEM_PO",
        "KODE_MATERIAL",
        "NAMA_MATERIAL",
        "QUANTITY_PO",
        "QUANTITY_SUBMIT",
        "CREATED_BY",
        "CREATED_AT",
        "UPDATED_BY",
        "UPDATED_AT"
    ]; 
}
