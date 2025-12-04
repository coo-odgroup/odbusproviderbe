<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPushNotificationTableAddColumns extends Migration
{
    public function up()
    {
        Schema::connection('mysql_scheduler')->table('push_notification', function (Blueprint $table) {

            if (!Schema::connection('mysql_scheduler')->hasColumn('push_notification', 'description')) {
                $table->text('description')->nullable()->after('message');
            }

            if (!Schema::connection('mysql_scheduler')->hasColumn('push_notification', 'status')) {
                $table->tinyInteger('status')->default(1)->after('description');
            }

            if (!Schema::connection('mysql_scheduler')->hasColumn('push_notification', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('status');
            }

            if (!Schema::connection('mysql_scheduler')->hasColumn('push_notification', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }

            if (!Schema::connection('mysql_scheduler')->hasColumn('push_notification', 'deleted_by')) {
                $table->unsignedBigInteger('deleted_by')->nullable()->after('updated_by');
            }

            // Created at column
            if (!Schema::connection('mysql_scheduler')->hasColumn('push_notification', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('deleted_by');
            }

            // Updated at column
            if (!Schema::connection('mysql_scheduler')->hasColumn('push_notification', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }

            // Soft delete column
            if (!Schema::connection('mysql_scheduler')->hasColumn('push_notification', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql_scheduler')->table('push_notification', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'status',
                'created_by',
                'updated_by',
                'deleted_by',
                'created_at',
                'updated_at',
                'deleted_at'
            ]);
        });
    }
}
