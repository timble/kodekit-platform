<?php
class JConfig
{
	/* Site Settings */
	var $offline    = '0';
	var $sitename   = 'Nooku Server';
	var $list_limit = '20';
	var $feed_limit =  10;

	/* Database Settings */
	var $host = '127.0.0.1';
	var $user = 'root';
	var $password = 'root';
	var $db = 'nooku-server';
	var $dbprefix = 'ns_';

	/* Server Settings */
	var $secret          = 'FBVtggIk5lAzEU9H';
	var $gzip            = '0';
	var $debug_mode      = '0';
	var $timezone        = 'UTC';

	/* Session settings */
	var $lifetime          = '15';

	/* Mail Settings */
	var $mailer = 'mail';
	var $mailfrom = '';
	var $fromname = '';
	var $sendmail = '/usr/sbin/sendmail';
	var $smtpauth = '0';
	var $smtpuser = '';
	var $smtppass = '';
	var $smtphost = 'localhost';

	/* Cache Settings */
	var $caching       = '0';
	var $cachetime     = '15';
	var $cache_handler = 'apc';

	/* Debug Settings */
	var $debug      = '0';
	var $debug_db 	= '0';
	var $debug_lang = '0';

	/* SEO Settings */
	var $sef_suffix  = '';

	/* Template Settings */
	var $theme = 'bootstrap';
}