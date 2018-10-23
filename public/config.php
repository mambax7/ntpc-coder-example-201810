<?php
    /**
     * 設定檔
     */

    // require_once __DIR__ . '/config/system.php';
    // require_once __DIR__ . '/config/ntpcOpenidConfig.php';

    // 載入 config 目錄下所有設定檔
    foreach (scandir(__DIR__ . '/config') as $filename) {
        $path = __DIR__ . '/config/' . $filename;
        if (is_file($path) && ends_with($filename, '.php')) {
            require_once $path;
        }
    }
