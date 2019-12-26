<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'TM_TOKEN';
	
    protected $dates =['valid_until'];
	
	protected $fillable = [
		'token',
		'valid_until',
	];
}
