<?php

use App\Http\Controllers\HomeController;
//Route::get('', ['as' => 'admin.dashboard', function () {
	//$content = 'Define your dashboard here.';
	//return AdminSection::view($content, 'Dashboard');
//}]);

Route::get('/', [HomeController::class, 'adminHome'])->name('admin.dashboard');

ROute::post('import', [HomeController::class, 'import'])->name('admin.import');

Route::get('information', ['as' => 'admin.information', function () {
	$content = 'Define your information here.';
	return AdminSection::view($content, 'Information');
}]);
