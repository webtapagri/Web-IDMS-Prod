<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoadStatus extends Model
{
	use SoftDeletes;
    
	protected $table = 'TM_ROAD_STATUS';
	
    protected $dates =['deleted_at'];
	
	protected $fillable = [
		'status_name',
		'status_code',
		'updated_by',
	];

	public function setCategoryNameAttribute($value)
    {
        $this->attributes['status_name'] = strtoupper($value);
    }
	
}
