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

<body id="tmpl-modal" class="com_<?= $component ?>">
    <?= @template('page_message.html') ?>
    <ktml:content />
</body>

</html>