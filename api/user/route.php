<?php

use think\Route;

Route::resource('user/articles', 'user/Articles');
Route::post('user/articles/deletes','user/Articles/deletes');