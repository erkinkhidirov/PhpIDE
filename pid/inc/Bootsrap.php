<?php
/**
 * @author Erkin Khidirov
 * @author Erkin Khidirov <erkinofabrichi@gmail.com>
 */

// All Constants
require_once "Constants.php";

// Check System requirements
require_once "inc/System.php";

// All template functions
require_once MORF_SITE_DIR . "/inc/functions.php";

// Include Security
require_once MORF_SITE_DIR . "/inc/Security.php";

// Include Ajax Handler
require_once MORF_SITE_DIR . "/inc/Ajax.php";

// Router
//require_once MORF_SITE_DIR . "/inc/Router.php";

// Includer Bootsrap of Libraries
require_once MORF_SITE_DIR . "/libraries/Bootsrap.php";

require_once MORF_SITE_DIR . "/inc/Morfine.php";

require_once MORF_SITE_DIR . "/tpl/main.php";
