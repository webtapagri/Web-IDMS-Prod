<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estate extends Model
{
    use SoftDeletes;
    
	protected $table = 'TM_ESTATE';
	
    protected $dates =['deleted_at'];
	
	protected $fillable = [
		'estate_code',
		'estate_name',
		'company_id',
		'werks',
		'city',
	];
}
