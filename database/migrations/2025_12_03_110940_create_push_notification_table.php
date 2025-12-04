<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushNotificationTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql_scheduler')->create('push_notification', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mysql_scheduler')->dropIfExists('push_notification');
    }
}
