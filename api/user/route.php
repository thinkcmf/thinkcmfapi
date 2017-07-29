<?php

use think\Route;

Route::resource('user/favorites', 'user/Favorites');
Route::resource('user/articles', 'user/Articles');
Route::resource('user/favorites', 'user/Favorites');
Route::post('user/articles/deletes','user/Articles/deletes');