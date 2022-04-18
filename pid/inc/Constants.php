<?php
/**
 * @author Erkin Khidirov
 * @author Erkin Khidirov <erkinofabrichi@gmail.com>
 */

$parent_category = dirname( dirname(__FILE__) );
$path_info = pathinfo($parent_category);
$proj_name = $path_info['filename'];
$proj_dir_name=$proj_name;

$root_url = '//' . $_SERVER['HTTP_HOST'] . '/';

define('MORF_SITE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/' . $proj_dir_name);
define('MORF_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/'  );
define('MORF_ROOT_NO_SLASH', $_SERVER['DOCUMENT_ROOT'] . ''  );
define('MORF_URL_ROOT', $root_url  );
define('MORF_URL_PROJECT', $root_url . '/' .  $proj_name );

// Help Links
define('HOW_UNBLOCK', 'https://phpide.io/links/?link=how_to_unblock' );
define('HOW_TO_AUTHORIZE', 'https://phpide.io/links/?link=how_to_auth' );
define('ABOUT_SYSTEM_REQUIREMENTS', 'https://phpide.io/links/?link=sys_req' );

