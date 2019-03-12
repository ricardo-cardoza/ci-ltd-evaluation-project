<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
      // $this->call(UsersTableSeeder::class);
    App\User::create([
      'name' => 'User #1',
      'email' => 'testuser.0001@example.com',
      'password' => bcrypt('secret1234'),
    ]);    
  }
}
