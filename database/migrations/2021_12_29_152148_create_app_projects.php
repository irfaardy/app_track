<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trackers', function (Blueprint $table) {
            $table->id();
            $table->string('app_id',64)->nullable();
            $table->string('project_name',200);
            $table->string('domain',250); //$_SERVER['HTTP_HOST']
            $table->ipAddress('server_ip');//getHostByName(getHostName())
            // $table->string('server_timezone',120)->nullable();//getHostByName(getHostName())
            $table->string('country',80)->nullable();//getHostByName(getHostName())
            $table->string('city',80)->nullable();//getHostByName(getHostName())
            $table->string('provinsi',80)->nullable();//getHostByName(getHostName())
            $table->double('lat',12,8)->nullable();//getHostByName(getHostName())
            $table->double('lng',12,8)->nullable();//getHostByName(getHostName())
            $table->string('type',60)->nullable();
            $table->string('operating_system',60);
            $table->text('software_server')->nullable(); // $_SERVER['SERVER_SOFTWARE']
            $table->dateTime('last_online')->nullable();
            $table->dateTime('detected_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_projects');
    }
}
