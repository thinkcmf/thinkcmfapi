<?php

use think\Route;

Route::resource('portal/categories', 'portal/Categories');
Route::resource('portal/articles', 'portal/Articles');
Route::resource('portal/pages', 'portal/Pages');
Route::resource('portal/tags', 'portal/Tags');
Route::get('portal/tags/hotTags', 'portal/Tags/hotTags');