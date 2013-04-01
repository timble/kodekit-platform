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

<body class="login">
<div id="container">
    <div class="login-box">
		<?= @template('page_message.html') ?>
        <ktml:content />
	</div>
</div>

<script data-inline src="media://application/js/chosen.mootools.1.2.js" /></script>
<script data-inline> $$(".chzn-select").chosen(); </script>
</body>

</html>