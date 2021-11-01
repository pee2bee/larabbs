<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::factory() -> count(10) -> create();

        //
        $user = User::find(1);
        $user -> name = '张三';
        $user -> email = '1234@qq.com';
        $user -> avatar = 'https://cdn.learnku.com/uploads/images/201710/14/1/ZqM7iaP4CR.png';
        $user -> save();

        // 初始化用户角色，将 1 号用户指派为『站长』
        $user -> assignRole('Founder');

        $user = User::find(2);
        $user -> name = '李四';
        $user -> email = '12345@qq.com';
        $user -> avatar = 'https://cdn.learnku.com/uploads/images/201710/14/1/ZqM7iaP4CR.png';
        $user -> save();

        //将 2 号用户指派为『管理员』
        $user -> assignRole('Maintainer');

    }
}
