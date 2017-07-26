<?php

use think\Route;

Route::resource('user/articles', 'user/Articles');
Route::resource('user/favorites', 'user/Favorites');
Route::resource('user/comments', 'user/Comments');