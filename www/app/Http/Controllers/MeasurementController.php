<?php

namespace App\Http\Controllers;

// NOTE(mauvm): By allowing specifying select and sort, hidden columns can be guessed.

use Request;
use Model;
use Auth;
use DB;
use InputValidator;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Measurement;
use App\Models\Device;

class MeasurementController extends BaseController {
	protected $class = Measurement::class;

	public $restful = true;

	protected static $aggregates = ['avg', 'min', 'max'];
	protected static $queryRules = [
		'from'      => 'required|carbon_date',
		'to'        => 'required|carbon_date',
		'interval'  => 'required|integer|min:300',
		'select'    => 'required|regex:/^[a-zA-Z0-9\_\:\,]+$/',
		'sort'      => 'required|regex:/^[a-zA-Z0-9\_\-\,]+$/',
	];

	function __construct ($registerMiddleware = true) {
		$this->registerMiddleware();
	}

	protected function registerMiddleware () {
		$this->middleware('auth.token');
	}

	function index () {
		$devices = new Collection();

		if (Request::has('ids')) {
			$ids = explode(',', Request::input('ids'));

			foreach ($ids as & $id) {
				$devices->push(Device::findOrFail($id));
			}
		} else {
			$devices = Device::all();
		}

		list($errResponse, $options) = $this->getOptions();

		if ($errResponse) return $errResponse;

		$devices->filter(function ($device) {
			return in_array(Auth::id(), $device->user_ids, true);
		})->each(function ($device) use ( & $options) {
			$this->fetchMeasurements($device, $options);
		});

		return $devices;
	}

	function storeByDeviceId ($id) {
		$device = Device::findOrFail($id);

		$measurement = $this->instance();
		$measurement->udid = $device->udid;
		$measurement->fill(Request::input());
		$measurement->save();

		return response(null, 200);
	}

	function showByDeviceId ($id) {
		$device = Device::findOrFail($id);
		list($errResponse, $options) = $this->getOptions();

		if ($errResponse) return $errResponse;

		$this->fetchMeasurements($device, $options);

		return $device;
	}

	protected function getOptions () {
		$options = array_only(Request::input(), array_keys(static::$queryRules));

		// Defaults
		if ( ! empty($options['from']) && is_numeric($options['from'])) {
			$timestamp = $options['from'];
			$options['from'] = new Carbon();
			$options['from']->setTimestamp((int) $timestamp);
		}
		if ( ! empty($options['to']) && is_numeric($options['to'])) {
			$timestamp = $options['to'];
			$options['to'] = new Carbon();
			$options['to']->setTimestamp((int) $timestamp);
		}
		if (empty($options['to'])) {
			$options['to'] = new Carbon();
		}
		if (empty($options['interval'])) {
			$options['interval'] = 300;
		}
		if (empty($options['sort'])) {
			$options['sort'] = '-timestamp';
		}

		// Validate
		$validator = InputValidator::make($options, static::$queryRules);

		if ($validator->fails()) {
			return [
				response()->json([
					'messages' => $validator->messages()
				], 400),
				null
			];
		}

		// Parse
		$options['from'] = new Carbon($options['from']);
		$options['to']   = new Carbon($options['to']);

		$select = [];
		$columns = ['timestamp'];
		$sort = [];

		foreach (explode(',', $options['select']) as $column) {
			$parts = explode(':', $column);
			$column = $parts[0];
			$aggregate = (isset($parts[1]) ? $parts[1] : null);

			if ( ! in_array($aggregate, static::$aggregates)) {
				$aggregate = static::$aggregates[0];
			}

			$select[$column] = $aggregate;
			$columns[] = $column;
		}

		foreach (explode(',', $options['sort']) as $column) {
			$order = 'asc';

			if (starts_with($column, '-')) {
				$order = 'desc';
				$column = substr($column, 1);
			}

			$sort[$column] = $order;
		}

		$options['select']  = & $select;
		$options['columns'] = & $columns;
		$options['sort']    = & $sort;

		return [null, $options];
	}

	protected function fetchMeasurements (Device $device, array $options) {
		$query = $device->measurements()
			->where('timestamp', '>=', $options['from'])
			->where('timestamp', '<=', $options['to'])
			->interval($options['interval'])
			;

		foreach ($options['select'] as $column => & $aggregate) {
			$query = $query->addSelect(DB::raw("$aggregate(`$column`) as `$column`"));
		}

		foreach ($options['sort'] as $column => & $order) {
			$query = $query->orderBy($column, $order);
		}

		$options['from'] = $options['from']->toIso8601String();
		$options['to']   = $options['to']->toIso8601String();

		$columns = new Collection($options['columns']);

		$device->measurements = $options + [
			'values' => $query->get()->map(function ($row) use ( & $columns) {
				return $columns->map(function ($column) use ($row) {
					if ($column === 'timestamp') {
						return $row->timestamp->timestamp;
					}

					return $row->$column;
				});
			}),
		];
	}

	protected function instance () {
		return (new $this->class);
	}
}
