<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Afdeling extends Model
{
    use SoftDeletes;
    
	protected $table = 'TM_AFDELING';
	
    protected $dates =['deleted_at'];
	
	protected $fillable = [
		'afdeling_code',
		'afdeling_name',
		'region_code',
		'company_code',
		'weks',
		'werks_afd_code',
	];
}
