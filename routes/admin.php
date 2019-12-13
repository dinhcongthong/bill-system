<?php

use Iluminate\Http\Request;


Route::Resource('users','UserController');
Route::Resource('roles', 'RoleController');
Route::Resource('permissions', 'PermissionController');
Route::Resource('activities', 'ActivityController');