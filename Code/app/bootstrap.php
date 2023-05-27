<?php
// Load Config
require_once 'config/config.php';

// Load Helpers
require_once 'helpers/URL_helper.php';
require_once 'helpers/session_helper.php';
require_once 'helpers/format_helper.php';

// Auto-load Core Libraries
spl_autoload_register(function ($className) {
    require_once 'lib/' . $className . '.php';
});
