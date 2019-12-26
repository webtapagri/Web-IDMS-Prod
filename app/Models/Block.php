<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model
{
    use SoftDeletes;
    
	protected $table = 'TM_BLOCK';
	
    protected $dates =['deleted_at'];
	
	protected $fillable = [
		'block_dode',
		'block_name',
		'region_code',
		'company_code',
		'estate_code',
		'weks',
		'werks_afd_block_code',
		'latitude_block',
		'longitude_block',
	];
}
