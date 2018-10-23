<?php
    /**
     * 設定檔
     */

    // 開啟除錯
    define('DEBUG_MODE', true);

    // 資料庫主機名稱
    define('DB_HOST', 'mysql');
    // 資料庫名稱
    define('DB_DATABASE', 'default');
    // 資料庫連線帳號
    define('DB_USER', 'default');
    // 資料庫連線密碼
    define('DB_PASSWORD', 'secret');
    // 資料庫 port
    define('DB_PORT', 3306);
    // 字元編碼
    define('DB_CHARSET', 'utf8');

    // 是否關閉模擬 prepare，確定資料庫有支援 prepare 才設為 false
    define('PDO_EMULATE_PREPARES', false);
