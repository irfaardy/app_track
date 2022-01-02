<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_projects', function (Blueprint $table) {
            $table->id();
            $table->string("app_id",64);
            $table->string("client_name",128);
            $table->string("app_name",128);
            $table->date("registered_date");
            $table->string("keterangan",500)->nullable();
            $table->string("updated_by",20)->nullable();
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
        Schema::dropIfExists('app_list');
    }
}
