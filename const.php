<?php 

define('DS', DIRECTORY_SEPARATOR);

define('_LIBS', __DIR__ .DS."libs".DS);
define('_LIBS_CONTROLLERS', _LIBS."controllers".DS);
define('_TEMPLATES', __DIR__.DS."templates".DS);
define('_TEMPLATES_CACHE', _TEMPLATES."cache".DS);

define('BASE_LINK', $config->base_link);
define('ADMIN_LINK', BASE_LINK."/admin.php");
