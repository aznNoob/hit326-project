<?php
// Load Config
require_once 'config/config.php';

// Load Helpers
require_once 'helpers/session_helper.php';

// Auto-load Core Libraries
spl_autoload_register(function ($className) {
    require_once 'lib/' . $className . '.php';
});
