<?php
/**
 * @author Erkin Khidirov
 * @author Erkin Khidirov <erkinofabrichi@gmail.com>
 */

function get_styles(){
    $styles = array(
      'jquery-ui.theme',
      'jquery-ui.structure',
      'jquery-ui',
      'fontawesome/css/all',
        'fonts',
        'animate.min',
        'jquery.dm-uploader.min',
        'morfine',
    );
    return $styles;
}

function get_scripts(){
    $scripts = array(
        'jquery-3.6.0.min',
        'ui/jquery-ui',
        'jquery.dm-uploader.min',
        'require',
        'ace',
        'morfine',
        'custom',

    );
    return $scripts;
}

function get_head(){
    $styles = get_styles();
    $scripts = get_scripts();
    $tpl = false;

    if(!empty($styles)){
        foreach ($styles as $style){
            $tpl .= '<link rel="stylesheet" href="'.MORF_URL_PROJECT.'/assets/css/'. $style .'.css" />';
        }
    }

    if(!empty($scripts)){
        foreach ($scripts as $script){
            if($script == 'require'){
                // Include after authorized
                if (defined('PIDE')) {
                    $tpl .= '<script src="'.MORF_URL_PROJECT.'/libraries/filemanager/ace/lib/ace/require.js"></script>';
                }
            } else if($script == 'ace'){
                // Include after authorized
                if (defined('PIDE')) {
                    $tpl .= '<script src="'.MORF_URL_PROJECT.'/assets/js/'. $script .'.js"></script>';
                }
            }
            else {
                $tpl .= '<script src="'.MORF_URL_PROJECT.'/assets/js/'. $script .'.js"></script>';
            }
        }
    }
    $tpl .= '<link rel="icon" type="image/x-icon" href="'.MORF_URL_PROJECT.'/favicon.ico" />';
    $tpl .= '<title>PhpIDE</title>';
    echo $tpl;

}

function get_dark_light_version(){
    if (isset($_COOKIE["darklight"]) && !empty($_COOKIE["darklight"])) {
        $dark_light = htmlspecialchars($_COOKIE["darklight"]);
        if ($dark_light == 'dark') {
            return 'dark';
        } else {
            return 'light';
        }
    } else {
        return 'light';
    }
}