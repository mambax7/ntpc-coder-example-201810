<?php
    /**
     * NTPC OpenID Config
     */

    // 用於 OpenID
    define('DOMAIN_NAME', 'localhost');
    // 成功通過規則檢查後重導到哪裡
    define('OPENID_REDIRECT_TO_AFTER_CHECK_PASS', '/auth/auth.php?op=ntpcLogin');
    // OpenID 取得欄位
    define('OPENID_REQUIRED', [
        // 'namePerson/friendly', // 暱稱
        'contact/email', // email
        'namePerson', // 姓名
        // 'birthDate', // 生日，1985-06-12
        // 'person/gender', // 性別，M 男
        // 'contact/postalCode/home', // 識別碼
        // 'contact/country/home', // 單位簡稱，xx國中
        'pref/language', // 年級班級座號，6 碼
        'pref/timezone' // 授權資訊，含單位代碼、單位全銜、職務別、職稱別、身份別等資料，陣列
    ]);
    // 授權資訊登入規則，空陣列代表不設限，陣列元素必須為陣列
    define('OPENID_RULES', []);
    // OpenID 帳號白名單，陣列
    define('OPENID_ACCOUNT_WHITE_LIST', []);
    // 是否使用假資料，開發階段有需要才設為 true
    define('FAKE_OPENID_USER', false);
    // 啟用假資料時才設定假資料常數
    if (FAKE_OPENID_USER) {
        define('FAKE_OPENID_USER_DATA', [
            // OpenID 帳號
            "openid_username" => "myid",
            // 識別碼
            "id_code" => "5EE2EFCE20722348C2E27AA5E21F60FE69F811651068288F6F7F264BAF4638FB",
            // 姓名
            "real_name" => "林小明",
            // 暱稱
            "nick_name" => "林小明",
            // 性別
            "gender" => "男",
            // 生日
            "birthday" => "1983-06-25",
            // 公務信箱
            "email" => "myid@apps.ntpc.edu.tw",
            // 單位（簡稱）
            "schoolNameShort" => "xx國中",
            // 年級
            "grade" => "00",
            // 班級
            "class" => "00",
            // 座號
            "num" => "00",
            // 授權資訊(陣列)
            // 依序為：id 單位代碼、name 單位全銜、role 身份別、title 職務別、groups 職稱別(陣列)
            "auth_info" => [
                [
                    "id" => "012345",
                    "name" => "新北市立xx國民中學",
                    "role" => "教師",
                    "title" => "教師兼組長",
                    "groups" => [
                        "資訊組長"
                    ]
                ],
                [
                    "id" => "023456",
                    "name" => "新北市oooo中心",
                    "role" => "教師",
                    "title" => "跨校人員",
                    "groups" => [
                        "資訊組長"
                    ]
                ],
                [
                    "id" => "012345",
                    "name" => "新北市立xx國民中學",
                    "role" => "家長",
                    "title" => "其他",
                    "groups" => [
                        "其他"
                    ]
                ]
            ]
        ]);
    }
