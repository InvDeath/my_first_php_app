<?php
/*
 * conf/config.php
 *
 */



date_default_timezone_set('Europe/Kiev');

/*
 * System settings
 */
define('SYS_SEPARATOR','/'); //fs separator in your os.
define('SYS_DATABASE','mysql'); //database. 'mysql' if its MySQL.  
define('DB_HOST','localhost');
define('DB_USER','dbuser');
define('DB_PASS','dbpass');
define('DB_BASE','booker');
define('DIR_TEMPLATES','templates');
define('DIR_LANGUAGES','data');



/*
 * User settings
 */
define('USR_TIME', 24); // '12' or '24' hours format.
define('USR_MINUTES_STEP','30'); // step in minutes.
define('USR_FIRSTDAY','1'); // '0' Sunday or '1' Monday is the first day of week.
define('USR_ROOMS', 3); // Number of bording rooms.
define('DEF_LANGUAGE','en');

/*
 * messages  
 */
define('ERR_NO_LNG','Lng file not exists!');


?>
