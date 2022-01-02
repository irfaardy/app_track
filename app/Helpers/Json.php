<?php
namespace App\Helpers;

use App\User;
use Auth;
use Session;

class Json
{
	public static function response($status, $key_data = 'data', $data=null, $errors=null)
    {
        return response()->json([
            'status'    => $status,
            $key_data   => $data,
            'errors'    => $errors
        ],$status);
    }

    public static function validateError($status, $key_data = 'data', $data=null)
    {
        return response()->json([
            $key_data   => $data,
        ],$status);
    }
}
