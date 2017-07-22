<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
$apps = cmf_scan_dir(APP_PATH . '*', GLOB_ONLYDIR);

foreach ($apps as $app) {
    $routeFile = APP_PATH . $app . '/route.php';

    if (file_exists($routeFile)) {
        include_once $routeFile;
    }

}


return [
    '__rest__' => [
        'portal/categorys' => ['portal/categorys', ['only' => ['index', 'read']]],
        'portal/articles'  => ['portal/articles', ['only' => ['index', 'read']]],
        'portal/comments'  => ['portal/comments', ['only' => ['index']]],
        'portal/pages'     => ['portal/pages', ['only' => ['index', 'read']]]
    ]
];