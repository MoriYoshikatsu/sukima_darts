<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parameter_id')->constrained()->onDelete('cascade');
            $table->string('title', 50);     // 旅行名
            $table->string('description', 200)->nullable();     // 詳細
            $table->double('first_latitude');     // 最初のピン緯度
            $table->double('first_longitude');     // 最初のピン経度
            $table->date('trip_date')->nullable();     // 旅行日
            $table->integer('status');     // 0:非公開，1:公開
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
        Schema::dropIfExists('trips');
    }
};
