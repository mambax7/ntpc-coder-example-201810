<?php
    /**
     * 處理 NTPC OpenID Login
     */

    require_once __DIR__ . '/../../bootstrap.php';

    // 開啟使用假資料
    if (FAKE_OPENID_USER) {
        setUserThenCheck(FAKE_OPENID_USER_DATA);
        exit();
    }

    /*******************************
     * OpenID 認證流程
     *******************************/

    $openid = new LightOpenID(DOMAIN_NAME);

    switch ($openid->mode) {
        // 同意
        case 'id_res':
            if ($openid->validate()) {
                // 驗證成功
                $user_data = getOpenidUserData($openid);
                setUserThenCheck($user_data);
            } else {
                // 驗證失敗
                echo 'OpenID login failed';
            }
            break;
        // 取消
        case 'cancel':
            echo '取消認證';
            break;
        // 啟動 OpenID 認證流程
        default:
            startOpenidAuth($openid);
    }


    /***************************************************
     * 函數區
     ***************************************************/

    /**
     * 啟動 OpenID 認證流程
     *
     * @param \LightOpenID $openid
     *
     * @throws \ErrorException
     */
    function startOpenidAuth(LightOpenID $openid) {
        $openid->identity = 'https://openid.ntpc.edu.tw/';
        $openid->required = OPENID_REQUIRED;
        headerTo($openid->authUrl());
    }


    /**
     * 將 user data 存入 session，轉向至登入規則檢查
     *
     * @param array $userData
     */
    function setUserThenCheck(array $userData) {
        $_SESSION['ntpc_user_data'] = $userData;
        headerTo('ntpcOpenidLoginCheck.php');
    }

    /**
     * 取得 OpenID user data
     *
     * @param \LightOpenID $openid
     *
     * @return mixed
     */
    function getOpenidUserData(LightOpenID $openid) {
        $user_data = [];

        $identity_array = array_values(explode('/', $openid->identity));
        $user_data['openid_username'] = end($identity_array);

        $attr = $openid->getAttributes();

        $user_data['id_code'] = isset($attr['contact/postalCode/home']) ? $attr['contact/postalCode/home'] : null;
        $user_data['real_name'] = isset($attr['namePerson']) ? $attr['namePerson'] : null;
        $user_data['nick_name'] = isset($attr['namePerson/friendly']) ? $attr['namePerson/friendly'] : null;
        $user_data['gender'] = isset($attr['person/gender']) ? (strtoupper($attr['person/gender']) === 'M' ? '男' : '女') : null;
        $user_data['birthday'] = isset($attr['birthDate']) ? $attr['birthDate'] : null;
        $user_data['email'] = isset($attr['contact/email']) ? $attr['contact/email'] : null;
        $user_data['schoolNameShort'] = isset($attr['contact/country/home']) ? $attr['contact/country/home'] : null;
        $user_data['grade'] = isset($attr['pref/language']) ? substr($attr['pref/language'], 0, 2) : null;
        $user_data['class'] = isset($attr['pref/language']) ? substr($attr['pref/language'], 2, 2) : null;
        $user_data['num'] = isset($attr['pref/language']) ? substr($attr['pref/language'], 4, 2) : null;
        $user_data['auth_info'] = json_decode($attr['pref/timezone'], true);

        return $user_data;
    }

    // 取得之資料如：
    //
    // $fakeUserData = [
    //     // OpenID 帳號
    //     "openid_username" => "myid",
    //     // 識別碼
    //     "id_code" => "5EE2EFCE20722348C2E27AA5E21F60FE69F811651068288F6F7F264BAF4638FB",
    //     // 姓名
    //     "real_name" => "林小明",
    //     // 暱稱
    //     "nick_name" => "林小明",
    //     // 性別
    //     "gender" => "男",
    //     // 生日
    //     "birthday" => "1983-06-25",
    //     // 公務信箱
    //     "email" => "myid@apps.ntpc.edu.tw",
    //     // 單位（簡稱）
    //     "schoolNameShort" => "xx國中",
    //     // 年級
    //     "grade" => "00",
    //     // 班級
    //     "class" => "00",
    //     // 座號
    //     "num" => "00",
    //     // 授權資訊(陣列)
    //     // 依序為：id 單位代碼、name 單位全銜、role 身份別、title 職務別、groups 職稱別(陣列)
    //     "auth_info" => [
    //         [
    //             "id" => "012345",
    //             "name" => "新北市立xx國民中學",
    //             "role" => "教師",
    //             "title" => "教師兼組長",
    //             "groups" => [
    //                 "資訊組長"
    //             ]
    //         ],
    //         [
    //             "id" => "023456",
    //             "name" => "新北市oooo中心",
    //             "role" => "教師",
    //             "title" => "跨校人員",
    //             "groups" => [
    //                 "資訊組長"
    //             ]
    //         ],
    //         [
    //             "id" => "012345",
    //             "name" => "新北市立xx國民中學",
    //             "role" => "家長",
    //             "title" => "其他",
    //             "groups" => [
    //                 "其他"
    //             ]
    //         ]
    //     ]
    // ];

