<?php @ini_set('session.cookie_httponly', True); ?>
<?php @ini_set('session.use_only_cookies', True); ?>
<?php @ini_set('session.cookie_secure', True); ?>
<?php header_remove('X-Powered-By'); ?>
<?php
	$limit = ini_get('memory_limit');
	@ini_set('memory_limit', -1);
	@ini_set('display_errors', 1);
?>