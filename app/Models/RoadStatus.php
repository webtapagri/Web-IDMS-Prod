<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoadStatus extends Model
{
	use SoftDeletes;
    
	protected $table = 'tm_road_status';
	
    protected $dates =['deleted_at'];
	
	protected $fillable = [
		'status_name',
		'status_code',
		'updated_by',
	];
}
