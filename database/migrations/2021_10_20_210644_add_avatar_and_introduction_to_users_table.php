<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvatarAndIntroductionToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //我们会将头像的图片以文件形式放置于服务器上，然后将路径字符串存储于数据库中，所以我们需要用到 string 类型，用户注册并未提供头像上传功能，因此我们还需要将字段设置为 nullable，意为允许空字符串。
            $table->string('avatar')->nullable();
            $table->string('introduction')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('avatar');
            $table->dropColumn('introduction');
        });
    }
}
