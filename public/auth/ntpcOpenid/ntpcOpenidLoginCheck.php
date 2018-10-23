<?php
    /**
     * 檢查登入規則 for NTPC OpenID
     */

    require_once __DIR__ . '/../../bootstrap.php';

    if (! isset($_SESSION['ntpc_user_data'])) {
        headerTo('../index.php');
    }

    // 多重身份，只保留選擇之登入身份
    if (isset($_GET['idx'])) {
        pickAuthInfo((int) $_GET['idx']);
    }

    if (count($_SESSION['ntpc_user_data']['auth_info']) > 1) {
        // 多重身份時，顯示身份選擇畫面
        echo showSelector($_SESSION['ntpc_user_data']['real_name'], $_SESSION['ntpc_user_data']['auth_info']);
    } else {
        // 單一身份 or 選擇登入身份之後
        // 檢查授權資訊之登入規則
        $canLogin = checkWhiteList($_SESSION['ntpc_user_data']['openid_username']) ? true : checkAuthInfoRules($_SESSION['ntpc_user_data']['auth_info'][0]);
        if ($canLogin) {
            // 通過規則，可以登入
            headerTo(OPENID_REDIRECT_TO_AFTER_CHECK_PASS);
        } else {
            // 未通過，拒絕登入
            echo '不符合登入規則，無法登入';
        }
    }

    /***************************************************
     * 函數區
     ***************************************************/

    /**
     * 顯示身份選擇畫面
     *
     * @param string $name          姓名
     * @param array  $auth_infos    授權資訊
     *
     * @return string
     */
    function showSelector(string $name, array $auth_infos) {
        $list = '<ul class="list">';
        foreach ($auth_infos as $idx => $info) {
            $list .= '<li><a href="' . $_SERVER['PHP_SELF'] .'?idx=' . $idx . '"><div>';
            $list .= '' . $info['name'] . '';
            $list .= '<div class="groups">' . implode(',', $info['groups']) . '</div><div class="clear"></div>';
            $list .= '</div></a></li>';
        }
        $list .= '</ul>';

        $page = <<<SELECT
            <style>
              a {
                text-decoration: none;
                color: #313131;
                font-size: 18px;
              }
              .container {
                text-align: center;
                max-width: 600px;
                min-width: 400px;
                margin: 100px auto 0; 
                border: 2px solid #2950ff; 
                border-radius: 5px;
                box-sizing: border-box;
              }
              .greeting {
                background-color: #2950ff; 
                color: #fff; 
                padding: 10px; 
                margin: 0;
              }
              .title {
                background-color: #404040; 
                color: #fff; 
                padding: 10px; 
                margin: 0;
              }
              ul.list {
                text-align: left;
                margin: 10px; 
                padding: 0;
              }
              ul.list li {
                padding: 10px; 
                list-style: none;
              }
              ul.list li:hover {
                background-color: #ff7f50;
              }
              ul.list li:hover > a {
                color: #fff;
              }
              .groups {
                float: right;
                display: inline-block;
                width: 30%;
              }
              .clear {
                clear: both;
              }
            </style>
            
            <div class="container">
                <h2 class="greeting">$name 你好</h2>
                <h3 class="title">請選擇登入身份</h3>
                <div>$list</div>
            </div>
SELECT;

        return $page;
    }


    /**
     * 選擇登入身份
     *
     * @param int $idx
     */
    function pickAuthInfo(int $idx) {
        $info = $_SESSION['ntpc_user_data']['auth_info'][$idx];
        if (is_null($info)) {
            unset($_SESSION['ntpc_user_data']);
            headerTo('/');
        }

        // 只保留選擇的身份
        $_SESSION['ntpc_user_data']['auth_info'] = [];
        $_SESSION['ntpc_user_data']['auth_info'][] = $info;
        $info = null;
    }

    /**
     * 檢查授權資訊之登入規則
     *
     * @param array $info 授權資訊
     *
     * @return bool
     */
    function checkAuthInfoRules(array $info) {
        // 空陣列代表不設限
        if (empty(OPENID_RULES)) {
            return true;
        }

        // 逐條檢查，first match
        foreach (OPENID_RULES as $rule) {
            $result = checkSingleAuthInfoRule($info, $rule);
            if ($result) {
                return true;
            }
        }

        // 所有規則均不通過，拒絕登入
        return false;
    }


    /**
     * 檢查單一條規則，規則為陣列，所有欄位檢查都要通過才算通過
     *
     * @param array $info   授權資訊
     * @param array $rule
     *
     * @return bool
     */
    function checkSingleAuthInfoRule(array $info, array $rule = []) {
        // 空陣列時回傳 false
        if (empty($rule)) {
            return false;
        }

        $result = false;
        foreach ($rule as $field => $value) {
            // 沒有要求的欄位則回傳 false
            if (is_null($info[$field])) {
                return false;
            }

            $to_check = $info[$field];
            /**
             * A                B
             * $info[$field]   $value
             * array            array   =>
             * string           array   => in_array(A, B)  A是否在B之中
             * array            string  =>
             * string           string  => A === B  A是否等於B
             */
            switch ($field) {
                /**
                 * A                B
                 * $info[$field]   $value
                 * string           array   => in_array(A, B)  A是否在B之中
                 * string           string  => A === B  A是否等於B
                 */
                case 'id': // 單位代碼
                case 'name': // 單位全銜
                case 'role': // 身份別
                case 'title': // 職務別
                    $result = is_array($value) ? in_array($to_check, $value) : $to_check === $value;
                    break;

                /**
                 * A                B
                 * $info['groups']   $value
                 * array            array   => AB交集 => 陣列長度 > 0 or not empty array
                 * array            string  => in_array(B, A)  B是否在A之中
                 */
                case 'groups': // 職稱別(陣列)
                    dd($to_check);
                    dd($value);
                    $result = is_array($value) ? !empty(array_intersect($to_check, $value)) : in_array($value, $to_check);
                    dd($result);
                    break;
            }
            // 只要一個欄位檢查不通過，則拒絕
            if ($result === false) {
                return false;
            }
        }

        // 至此 $result = true，通過各欄位之檢查
        return true;
    }
    /*****************************************
     * 授權資訊範例
     *****************************************/
    // [
    //     "id" => "012345",
    //     "name" => "新北市立xx國民中學",
    //     "role" => "教師",
    //     "title" => "教師兼組長",
    //     "groups" => [
    //         "資訊組長"
    //     ]
    // ]


    /**
     * 檢查帳號白名單
     *
     * @param string $openid_account
     *
     * @return bool
     */
    function checkWhiteList(string $openid_account) {
        // 未設定白名單則回傳 false
        if (empty(OPENID_ACCOUNT_WHITE_LIST)) {
            return false;
        }

        return in_array($openid_account, OPENID_ACCOUNT_WHITE_LIST);
    }
