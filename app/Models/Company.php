<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    
	protected $table = 'TM_COMPANY';
	
    protected $dates =['deleted_at'];
	
	protected $fillable = [
		'company_code',
		'company_name',
		'valid_from',
		'valid_to',
		'region_code',
		'address',
		'national',
	];
}
