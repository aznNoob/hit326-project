<?php
// Load Config
require_once 'config/config.php';

// Auto-load Libraries
spl_autoload_register(function ($className) {
    require_once 'lib/' . $className . '.php';
});
