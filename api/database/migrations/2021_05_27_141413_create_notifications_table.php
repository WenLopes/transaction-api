<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $defaultNotificationStatus = config('constants.notification.status.WAITING');

        Schema::create('notifications', function (Blueprint $table) use ($defaultNotificationStatus) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index('fk_notifications_user_id_idx');
            $table->integer('notification_status_id')->default($defaultNotificationStatus)->index('fk_notifications_notification_status_id_idx');
            $table->string('subject');
            $table->longText('content');
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
        Schema::dropIfExists('notifications');
    }
}
