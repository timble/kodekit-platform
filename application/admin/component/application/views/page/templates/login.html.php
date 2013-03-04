<?
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<!DOCTYPE HTML>
<html lang="<?= $language; ?>" dir="<?= $direction; ?>">

<?= @template('page_head.html') ?>

<body class="com_<?= $component ?> login">
<div id="container">
    <div id="login-box" class="login">
		<img src="media://application/images/nooku-server_logo.png" alt="Nooku Server logo">
		<?= @template('page_message.html') ?>
		<div id="section-box">
            <ktml:content />
		</div>
		<a class="return" href="/">
			<?= @text('Go to site home page.'); ?>
		</a>
	</div>
</div>

<script data-inline src="media://application/js/chosen.mootools.1.2.js" /></script>
<script data-inline> $$(".chzn-select").chosen(); </script>
</body>

</html>