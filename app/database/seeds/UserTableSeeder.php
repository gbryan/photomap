<?php

class UserTableSeeder extends Seeder {

    public function run()
    {
        User::create(['email' => 'foo@bar.com', 'password' => Hash::make('test')]);
    }

}
