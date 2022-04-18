<?php
/**
 * @author Erkin Khidirov
 * @author Erkin Khidirov <erkinofabrichi@gmail.com>
 */

if (!defined('PIDE')) { echo 'You dont have access'; die; }


$post = $_POST;

if(isset($post['morfin_ajax_action']) && !empty($post['morfin_ajax_action'])) {

    require_once MORF_SITE_DIR . "/libraries/Bootsrap.php";
    require_once MORF_SITE_DIR . "/inc/Morfine.php";

    $type = $post['morfin_ajax_action'];

    if ($type == 'dir_open') {

        $settings = array(

            'url' => '',
            'width' => '',
            'operation_system' => 'linux',
            'path_for_find_word' => ''

        );

        $file_manager = new FileManager($settings);
        $file_manager->dir_open($post);

        die();

    }

    if ($type == 'file_open') {

        $settings = array(

            'url' => '',
            'width' => '',
            'operation_system' => 'linux',
            'path_for_find_word' => ''

        );

        $file_manager = new FileManager($settings);
        $file_manager->file_open($post);

        die();

    }

    if ($type == 'file_save') {

        $settings = array(
            'url' => '',
            'width' => '',
            'operation_system' => 'linux',
            'path_for_find_word' => ''
        );

        $file_manager = new FileManager($settings);
        $file_manager->file_save($post);

        die();

    }

    if ($type == 'chmod_edit') {

        $settings = array(

            'url' => '',
            'width' => '',
            'operation_system' => 'linux',
            'path_for_find_word' => ''

        );

        $file_manager = new FileManager($settings);
        $file_manager->chmod_edit($post);

        die();

    }

    if ($type == 'owner_edit') {

        $settings = array(

            'url' => '',
            'width' => '',
            'operation_system' => 'linux',
            'path_for_find_word' => ''

        );

        $file_manager = new FileManager($settings);
        $file_manager->owner_edit($post);

        die();

    }

    if ($type == 'file_delete') {

        $settings = array(

            'url' => '',
            'width' => '',
            'operation_system' => 'linux',
            'path_for_find_word' => ''

        );

        $file_manager = new FileManager($settings);
        $file_manager->file_delete($post);

        die();

    }

    if ($type == 'create_dir') {

        $settings = array(
            'url' => '',
            'width' => '',
            'operation_system' => 'linux',
            'path_for_find_word' => ''
        );

        $file_manager = new FileManager($settings);
        $file_manager->dir_create($post);

        die();

    }

    if ($type == 'file_copy') {

        $settings = array(

            'url' => '',
            'width' => '',
            'operation_system' => 'linux',
            'path_for_find_word' => ''

        );

        $file_manager = new FileManager($settings);
        $file_manager->file_copy($post);

        die();

    }

    if ($type == 'unarchive') {

        $settings = array(

            'url' => '',
            'width' => '',
            'operation_system' => 'linux',
            'path_for_find_word' => ''

        );

        $file_manager = new FileManager($settings);
        $file_manager->unarchive($post);

        die();

    }

    if ($type == 'file_rename') {

        $settings = array(

            'url' => '',
            'width' => '',
            'operation_system' => 'linux',
            'path_for_find_word' => ''

        );

        $file_manager = new FileManager($settings);
        $file_manager->file_rename($post);

        die();

    }

    if ($type == 'ssh_command') {

        $settings = array(

            'url' => '',
            'width' => '',
            'operation_system' => 'linux',
            'path_for_find_word' => ''

        );

        $ssh = new ssh($settings);
        //$ssh->connect($post);
        //$ssh->cmd($post);

        die();

    }

}

if(isset($_FILES['file'])){

    require_once MORF_SITE_DIR . "/libraries/Bootsrap.php";
    require_once MORF_SITE_DIR . "/inc/Morfine.php";

    $settings = array(
        'url' => '',
        'width' => '',
        'operation_system' => 'linux',
        'path_for_find_word' => ''
    );

    $file_manager = new FileManager($settings);
    $file_manager->upload($_FILES, $_REQUEST);

    die();

}