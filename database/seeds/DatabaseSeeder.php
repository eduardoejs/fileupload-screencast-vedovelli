<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
    }
}

class UsersTableSeeder extends Seeder
{
	public function run()
	{
		$user = new \App\User();
		$user->name = 'Eduardo Silva';
		$user->email = 'edu@mail.com';
		$user->password = bcrypt('123456');
		$user->save();
	}
}