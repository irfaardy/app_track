<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Tracker;
use App\Helpers\JSon;
use Illuminate\Support\Facades\DB;
use GeoIp2\Database\Reader;

class TrackingController extends Controller
{
  public function hit(Request $request)
  {
     // dd($this->geoIP($request->server_ip)->location);
    DB::beginTransaction();

    try {
        Tracker::create($this->params($request));
        DB::commit();
        return JSon::response(200,'tx',['status' => "OK"],[]);
    } catch (\Exception $e) {
      dd($e->getMessage());
        DB::rollback();

        return JSon::response(200,'tx',[],"failed");
    }

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
                'detected_at' => $request->detected_at
                        ];
      return $params;
  }
}
