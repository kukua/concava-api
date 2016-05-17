<?php

use Illuminate\Database\Seeder;

use App\Models\Database\User;
use App\Models\Database\UserToken;

class UserTableSeeder extends Seeder
{
	public function run ()
	{
		User::truncate();
		UserToken::truncate();

		$this->create([
			'name' => 'Chunky Monkey',
			'email' => 'chunky@monkey.com',
			'password' => 'banana'
		]);
		$this->create([
			'name' => 'Han Solo',
			'email' => 'han.solo@theforce.com',
			'password' => 'killedbykyloren'
		]);
	}

	private function create (array $data)
	{
		$user = User::create([
			'name' => & $data['name'],
			'email' => & $data['email'],
			'password' => & $data['password']
		]);
		$userToken = UserToken::create([
			'user_id' => $user->id,
			'token' => UserToken::randomToken()
		]);
	}
}
