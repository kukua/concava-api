<?php

use Illuminate\Database\Seeder;

use App\Models\Database\Device;
use App\Models\Database\User;

class DeviceTableSeeder extends Seeder
{
	public function run ()
	{
		Device::truncate();

		$this->create([
			'udid' => '0000000000000001',
			'name' => 'TEST_NL_0001',
			'user_id' => 1,
			'template_id' => 1
		]);
		$this->create([
			'udid' => 'abdef0123456789',
			'name' => 'TEST_NL_0002',
			'user_id' => 2,
			'template_id' => 2
		]);
	}

	private function create (array $data)
	{
		$device = Device::create([
			'template_id' => & $data['template_id'],
			'udid' => & $data['udid'],
			'name' => & $data['name']
		]);

		$user = User::find($data['user_id']);

		$device->users()->save($user);
	}
}
