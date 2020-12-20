<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->bigIncrements('id');
            // フォローする側のid
            $table->bigInteger('follower_id');
            $table->foreign('follower_id')
            // ユーザーのidと紐付ける事で、follower_ìdはユーザーのidが入る
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            // フォローされる側のid
            $table->bigInteger('followee_id');
            $table->foreign('followee_id')
            // ユーザーのidと紐付ける事で、followee_ìdはユーザーのidが入る
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            // どのユーザーが、どのユーザーをフォローしているかを判断している
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
        Schema::dropIfExists('follows');
    }
}
