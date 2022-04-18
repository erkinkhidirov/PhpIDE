<?php
/**
 * @author Erkin Khidirov
 * @author Erkin Khidirov <erkinofabrichi@gmail.com>
 */

define('DEBUG', true);

//If you want to unblock yourself set true and dont forget after login set false
define('PASS_TRY', false);

// Change password here
define('YOUR_PASSWORD', '$2y$10$z2G06fKzN0n.P515Jgg70OG3UiYUClpUeoo1G617/Tyh12Sn6F6e2');

// Load Main Class
require_once "inc/Bootsrap.php";

// System settings. Don't change
$settings = array(
    'operation_system' => 'linux',
    'path_for_find_word' => ''
);

$_morfine = new Morfine($settings);
$_morfine->fm_init();