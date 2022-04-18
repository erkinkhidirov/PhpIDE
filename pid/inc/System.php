<?php
/**
 * @author Erkin Khidirov
 * @author Erkin Khidirov <erkinofabrichi@gmail.com>
 */

if(defined(DEBUG) && DEBUG){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$errors = false;
if (version_compare(PHP_VERSION, '7.2.0') < 1) {
    $errors .= '<div class="sys_info_elem">Php version must be at least 7.2.0. Current version is ' . PHP_VERSION . ' </div>';
}

if(!function_exists('exec')) {
    $errors .= '<div class="sys_info_elem">exec() function is disabled in php.ini. Please enable this function. <a href="http://phpide.io/links?type=exec" target="_blank" >Learn more</a></div>';
}

$link_sys_req_shown = false;

if(!empty($errors)){
    $errors .= '<a href="'.ABOUT_SYSTEM_REQUIREMENTS.'" target="_blank">Read this article about system requiremens</a>';
    $link_sys_req_shown = true;
    echo $errors;
    die();
}

if (PHP_OS_FAMILY !== "Linux") {
    $errors .= '<div class="sys_info_elem">Your OS is not Linux (Ubuntu, Debian, CentOS...) PhpIDE work just in Linux based OS.</div>';
}

if(!empty($errors)){
    if(!$link_sys_req_shown){
        $errors .= '<a href="'.ABOUT_SYSTEM_REQUIREMENTS.'" target="_blank">Read this article about system requiremens</a>';
    }
    echo $errors;
    die();
}