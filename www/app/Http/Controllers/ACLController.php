<?php

namespace App\Http\Controllers;

use Model;
use Auth;
use HttpException;

class ACLController {
	function onCreating (Model $model) {
		$user = Auth::user();

		if ($user && $user->is_admin) return;
		if ($model->guardCreate === false) return;

		$ex = new HttpException(401, 'Not allowed to create entity.');

		if ( ! $user->id) throw $ex;
		if ( ! in_array($user->id, $model->user_ids, true)) throw $ex;
	}

	function onUpdating (Model $model) {
		$user = Auth::user();

		if ($user && $user->is_admin) return;
		if ($model->guardUpdate === false) return;

		$ex = new HttpException(401, 'Not allowed to modify entity.');
		$original = $model->findOrFail($model->getOriginal()['id']);

		if ( ! $user->id) throw $ex;
		if ( ! in_array($user->id, $original->user_ids, true)) throw $ex;
		if ( ! in_array($user->id, $model->user_ids, true)) throw $ex;
	}

	function onDeleting (Model $model) {
		$user = Auth::user();

		if ($user && $user->is_admin) return;
		if ($model->guardDelete === false) return;

		$ex = new HttpException(401, 'Not allowed to delete entity.');

		if ( ! $user->id) throw $ex;
		if ( ! in_array($user->id, $model->user_ids, true)) throw $ex;
	}
}
