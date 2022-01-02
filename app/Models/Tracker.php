<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tracker extends Model
{
    protected $table = "trackers";
    protected $fillable = ['app_id','sender_ip' ,'project_name','domain','server_ip','server_timezone','country','city','provinsi','lat','lng','type','operating_system','software_server','last_online','detected_at'];


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
