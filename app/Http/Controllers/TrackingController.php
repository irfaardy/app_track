<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Tracker;
use App\Models\AccessKey;
use App\Helpers\Json;
use Illuminate\Support\Facades\DB;
use GeoIp2\Database\Reader;
use Yajra\DataTables\Facades\DataTables;

class TrackingController extends Controller
{
  public function hit(Request $request)
  {
    $cek = Tracker::where(['app_id'=>$request->app_id,'domain'=>$request->domain])->count();
     // dd($this->geoIP($request->server_ip)->location);
    if($cek > 0)
    {
      DB::beginTransaction();

      try {
            Tracker::where(['app_id'=>$request->app_id,'domain'=>$request->domain])->update(['last_online' => $request->last_online,'sender_ip' => $request->ip()]);
          DB::commit();
          return Json::response(200,'tx',[],"TRX-UPDATED");
      } catch (\Exception $e) {

          DB::rollback();

          return Json::response(200,'tx',[],"Update-failed");
      }


    }
    DB::beginTransaction();

    try {
        Tracker::create($this->params($request));
        DB::commit();
        return Json::response(200,'tx',['status' => "OK"],[]);
    } catch (\Exception $e) {

        DB::rollback();

        return Json::response(200,'tx',[],"failed");
    }

  }
  public function getData(Request $request)
  {
    if(!empty($_SERVER['HTTP_ORIGIN']))
        {
        header("Access-Control-Allow-Origin: ".$_SERVER['HTTP_ORIGIN']);

        }
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
        header('Content-Type: application/json');
    if(!$this->authenticate($request->api_key))
    {
      return Json::response(401,'tx',[],"Unauthorized");
    }
    DB::beginTransaction();

    try {
        $track = Tracker::orderBy('last_online','DESC')->get();
        DB::commit();
        return DataTables::of($track)
            ->rawColumns(['aksi','status','progress_task'])
            ->make(true);
    } catch (\Exception $e) {
        echo $e->getMessage();
        DB::rollback();

        return Json::response(400,'tx',[],"Internal Server Error");
    }
  }
  private function authenticate($token)
  {
      $cek = AccessKey::where('api_Key', $token)->count();
      if($cek == 1)
      {
        return true;
      }
      return false;
  }
  private function geoIP($ip)
  {
    // City DB
    $path = storage_path('geoip/GeoLite2-City.mmdb');
      $reader = new Reader($path);
      $record = $reader->city($ip);

      return $record;
      // or for Country DB
      // $reader = new Reader('/path/to/GeoLite2-Country.mmdb');
      // $record = $reader->country($_SERVER['REMOTE_ADDR']);
      // print($record->country->isoCode . "\n");
      // print($record->country->name . "\n");
      // print($record->country->names['zh-CN'] . "\n");
      // print($record->mostSpecificSubdivision->name . "\n");
      // print($record->mostSpecificSubdivision->isoCode . "\n");
      // print($record->city->name . "\n");
      // print($record->postal->code . "\n");
      // print($record->location->latitude . "\n");
      // print($record->location->longitude . "\n");
  }
  private function params($request)
  {
     $getIPAttr = $this->geoIP($request->server_ip);
     $params = ['app_id' => $request->app_id,
                'project_name' => $request->project_name,
                'domain' => $request->domain,
                'server_ip' => $request->server_ip,
                'country' => $getIPAttr->country->name,
                'city' => $getIPAttr->city->name,
                'provinsi' => $getIPAttr->mostSpecificSubdivision->name ,
                'lat' => $getIPAttr->location->latitude,
                'lng' => $getIPAttr->location->longitude,
                'type' => $request->type,
                'operating_system' => $request->operating_system,
                'software_server' => $request->software_server,
                'last_online' => $request->last_online,
                'detected_at' => $request->detected_at,
                'sender_ip' => $request->ip()
                        ];
      return $params;
  }
}
