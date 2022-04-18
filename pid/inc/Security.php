<?php
/**
 * @author Erkin Khidirov
 * @author Erkin Khidirov <erkinofabrichi@gmail.com>
 */

$current_client_ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$curr_url=$_SERVER['REQUEST_URI'];

// Generate hash password
if (isset($_POST['morfin_ajax_action']) && $_POST['morfin_ajax_action'] == 'generate_pass') {

    if(isset($_POST['pass']) && !empty($_POST['pass'])) {

        $password_generator = password_hash($_POST['pass'], PASSWORD_DEFAULT);

        $message = array(
            'status'=> '1',
            'message' =>  $password_generator
        );
        echo json_encode($message);

    }
    die();
}

// Start user session
session_start();

if(defined('YOUR_PASSWORD') && empty(YOUR_PASSWORD)) {
    require_once MORF_SITE_DIR . "/tpl/generatepass.php";
    die();
}

if(PASS_TRY === true ){
    if(isset($_SESSION['pass_try'])){
        unset($_SESSION['pass_try']);
    }
    if(isset($_SESSION['banned_ip'])){
        unset($_SESSION['banned_ip']);
    }
} else {
    if(
        isset($_SESSION['pass_try']) &&
        $_SESSION['pass_try'] < 0 or
        isset($_SESSION['banned_ip']) &&
        $_SESSION['banned_ip'] == $current_client_ip
    ){
        $_SESSION['banned_ip'] = $current_client_ip;
        echo "You have been blocked. <a href='". HOW_UNBLOCK ."' target='_blank'>Read this article</a> to unblock yourself";
        die();
    }
}

//unset($_SESSION['morfin_user']);

$parsed_url = parse_url($curr_url);

if( isset($parsed_url['query']) && !empty($parsed_url['query']) ){

    $query = $parsed_url['query'];
    $query_val_array = explode('=',$query);

    if(isset($query_val_array[0]) && $query_val_array[0] == 'q'){
        if(isset($query_val_array[1]) && $query_val_array[1] == 'generate-password'){

            require_once MORF_SITE_DIR . "/tpl/generatepass.php";

            die();
        }
    }

}

if(!isset($_SESSION['morfin_user'])){

    require_once MORF_SITE_DIR . "/tpl/security.php";

    if(isset($_POST)) {

        $post = $_POST;

        if (isset($post['form']) && !empty($post['form'])) {

            if ($post['form'] == 'morfin_security') {

                if(isset($post['password']) && !empty($post['password'])){

                    if(defined('YOUR_PASSWORD') && !empty(YOUR_PASSWORD)) {

                        $password = $post['password'];
                        $password_check = password_verify($password, YOUR_PASSWORD);

                        if ($password_check) {

                            $user = array(
                                'ip' => base64_encode($current_client_ip),
                                'user_agent' => base64_encode($user_agent),
                                'password' => base64_encode($post['password'])
                            );

                            $encode = json_encode($user);
                            $base_64 = base64_encode($encode);

                            $_SESSION['morfin_user'] = $base_64;

                            define('PIDE',true);

                            echo "<script>document.location.reload();</script>";

                        } else {

                            echo "<div class='password_incorrect'><i class='fa-solid fa-triangle-exclamation'></i> Incorrect password</div>";

                            $pass_try = 5;
                            if(isset($_SESSION['pass_try'])){
                                $pass_try = $_SESSION['pass_try'];
                                $pass_try = $pass_try - 1;
                            }
                            $_SESSION['pass_try'] = $pass_try;

                        }
                    } else {

                        echo "<div class='password_incorrect'>Password is not set in index.php. Please set first password in index.php and then continue</div>";

                    }

                }

            }

        }

    }

    die();

} else {

    if(isset($_SESSION['morfin_user']) && !empty($_SESSION['morfin_user'])){

        $user =  $_SESSION['morfin_user'];
        $user = base64_decode($user);
        $user = json_decode($user, true);

        $user['ip'] = base64_decode($user['ip']);
        $user['user_agent'] = base64_decode($user['user_agent']);
        $user['password'] = base64_decode($user['password']);

        if(!empty($user)){

            $current_client_ip = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];

            if($user['ip'] != $current_client_ip or $user['user_agent'] != $user_agent){

                require_once MORF_SITE_DIR . "/tpl/security.php";
                session_unset();
                die();

            } else {
                define('PIDE',true);
            }

        } else {

            echo "Что то пошло не так";

        }

    } else {

        require_once MORF_SITE_DIR . "/tpl/security.php";
        die();

    }

}