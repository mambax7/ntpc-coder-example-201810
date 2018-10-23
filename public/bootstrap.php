<?php
    /**
     * 進行初始化
     */

    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/config.php';

    // 啟用 session
    session_start();

    // 開關錯誤報告
    if (DEBUG_MODE) {
        error_reporting(E_ALL);
    } else {
        error_reporting(0);
    }

    // 設定預設時區
    date_default_timezone_set('Asia/Taipei');

    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE . ";charset=" . DB_CHARSET . ";port=" . DB_PORT;
        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, PDO_EMULATE_PREPARES);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        // 是否強制將整數以字串取回
        $pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
    } catch (PDOException $e) {
        echo "資料庫連線失敗<br>";
        echo "error code: ". $e->getCode() . "<br>";
        echo "error message: ". $e->getMessage() . "<br>";
    }
