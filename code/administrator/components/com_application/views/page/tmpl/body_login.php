<?
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<body class="<?= JRequest::getVar('option', 'cmd'); ?> login">
<div id="container">
    <div id="login-box" class="login">
		<img src="media://com_application/images/nooku-server_logo.png" alt="Nooku Server logo">
		<?= @template('default_message') ?>
		<div id="section-box">
			<ktml:variable name="component" />
		</div>
		<a class="return" href="<?= JURI::root(); ?>">
			<?= JText::_('Go to site home page.'); ?>
		</a>
	</div>
</div>

<script data-inline src="media://com_application/js/chosen.mootools.1.2.js" /></script>
<script data-inline> $$(".chzn-select").chosen(); </script>
</body>