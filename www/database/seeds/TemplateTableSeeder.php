<?php

use Illuminate\Database\Seeder;

use App\Models\Template;
use App\Models\Attribute;
use App\Models\Converter;
use App\Models\Calibrator;
use App\Models\Validator;

class TemplateTableSeeder extends Seeder
{
	public function run ()
	{
		Template::truncate();
		Attribute::truncate();
		Converter::truncate();
		Calibrator::truncate();
		Validator::truncate();

		$this->create([
			'user_id' => 1,
			'name' => 'Template 1',
			'attr' => [
				[
					'name' => 'attr1',
					'converter' => 'uint32be',
					'validators' => [
						['min', 100],
						['max', 1300]
					]
				],
				[
					'name' => 'attr2',
					'converter' => 'int16le',
					'calibrator' => 'return val + 7'
				]
			]
		]);
		$this->create([
			'user_id' => 2,
			'name' => 'Template 2',
			'attr' => [
				[
					'name' => 'temp',
					'converter' => 'int16le'
				]
			]
		]);
	}

	private function create (array $data)
	{
		$template = Template::create([
			'user_id' => & $data['user_id'],
			'name' => & $data['name']
		]);

		foreach ($data['attr'] as $i => & $attr)
		{
			$attribute = Attribute::create([
				'template_id' => $template->id,
				'name' => $attr['name'],
				'order' => $i
			]);
			if ( ! empty($attr['converter']))
			{
				Converter::create([
					'attribute_id' => $attribute->id,
					'type' => $attr['converter']
				]);
			}
			if ( ! empty($attr['calibrator']))
			{
				Calibrator::create([
					'attribute_id' => $attribute->id,
					'fn' => $attr['calibrator']
				]);
			}
			if ( ! empty($attr['validators']))
			{
				foreach ($attr['validators'] as $j => & $validator)
				{
					Validator::create([
						'attribute_id' => $attribute->id,
						'type' => $validator[0],
						'value' => $validator[1],
						'order' => $j
					]);
				}
			}
		}
	}
}
