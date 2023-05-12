<?php
// Load Config
require_once 'config/config.php';

// Load Libraries
// require_once 'lib/Core.php';
// require_once 'lib/Controller.php';
// require_once 'lib/Database.php';

// Auto-load Core Libraries
spl_autoload_register(function ($className) {
    require_once 'lib/' . $className . '.php';
});
