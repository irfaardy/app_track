<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AccessKey extends Model
{
    protected $table = "access_key";
    protected $fillable = ['name','api_key','updated_by'];


	public function getCreatedAtAttribute($date)
	{
		$time = strtotime($date);
		return date('Y-m-d H:i:s',$time);
	}

	public function getUpdatedAtAttribute($date)
	{
		$time = strtotime($date);
		return date('Y-m-d H:i:s',$time);
	}
}
