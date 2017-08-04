<?php

use think\Route;

Route::resource('portal/categories', 'portal/Categories');
Route::resource('portal/articles', 'portal/Articles');
Route::resource('portal/pages', 'portal/Pages');

Route::get('portal/articles/my', 'portal/Articles/my');
Route::get('portal/tags', 'portal/Tags/index');
Route::get('portal/tag/:id/articles', 'portal/Tags/articles');