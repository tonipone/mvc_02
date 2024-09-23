<?php
require("core/Config.php");
session_start();

use \Core\{Config, Router,H};

define('PROOT',__DIR__);
define('DS',DIRECTORY_SEPARATOR);

echo Config::get('version');