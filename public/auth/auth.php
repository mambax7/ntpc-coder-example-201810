<?php
    /**
     * 處理登入 / 登出
     */

    require_once __DIR__ . '/../bootstrap.php';

    $op = $_REQUEST['op'] ?? 'invalid';

    switch ($op) {
        // NTPC OpenID 登入，已通過規則檢查
        case 'ntpcLogin':
            // echo 'login by ntpc openid';
            // dd($_SESSION['ntpc_user_data']);
            $data['username'] = 'ntpc_' . $_SESSION['ntpc_user_data']['openid_username'];
            $data['name'] = $_SESSION['ntpc_user_data']['real_name'];
            $data['email'] = $_SESSION['ntpc_user_data']['email'];
            
            unset($_SESSION['ntpc_user_data']);
            
            // create if not exist
            $user = getNTPCUserByUsername($data['username']);
            if (!$user) {
                $data['password'] = password_hash(rand(10000000, 99999999), PASSWORD_DEFAULT);
                $data['ntpc'] = '1';
                $user_id = createUser($data); // 取得新 id

                if (!$user_id) {
                    // 若建立 user 失敗
                    header('Location: ../login.php');
                    die();
                }

                $data['id'] = $user_id;
                $user = $data;
            }
            
            createSession($user);
            
            // dd($_SESSION['user']);
            
            header('Location: /');
            break;
        // 本地端帳號登入
        case 'localLogin':
            // echo 'login by local';
            // var_dump($_POST);
            localLogin($_POST['username'], $_POST['password']);
            break;
        // 登出
        case 'logout':
            // echo 'you have logouted';
            unset($_SESSION['user']);
            header('Location: ../login.php');
            break;

        default:
            echo 'other invalid action';
            header('Location: /');
    }

    // 本機帳號登入
    function localLogin($username, $password) {
        global $pdo;
        $sql = "SELECT id, username, name, password FROM users WHERE username = :username AND ntpc='0' LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();
        // var_dump($user);

        if (password_verify($password, $user['password'])) {
            createSession($user);
            header('Location: /');
            die();
        }
        
        header('Location: ../login.php');
    }

    // 以 username 取得 user
    function getNTPCUserByUsername($username) {
        global $pdo;
        $sql = "SELECT id, username, name FROM users WHERE username = :username AND ntpc='1' LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();
        
        return $user; // 回傳 user 或 false (user 不存在)
    }

    // 新增 user
    function createUser($data) {
        global $pdo;
        $sql = "INSERT INTO users (username, name, password, email, ntpc) VALUES (:username, :name, :password, :email, :ntpc)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $data['username']);
        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':password', $data['password']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':ntpc', $data['ntpc']);
        
        if (!$stmt->execute()) {
            return false;
        }

        return $pdo->lastInsertId();
    }

    // 建立 session
    function createSession($user) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'username' => $user['username'],
        ];
    }
