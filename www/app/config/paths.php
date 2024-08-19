<?php

//SERVER REQUEST PROTOCOL USED
if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	define('PROTOCOL', 'https');
} else {
	define('PROTOCOL', 'http');
}

//BASE URL
define('URL', PROTOCOL.'://'.$_SERVER['HTTP_HOST'].'/');

?>