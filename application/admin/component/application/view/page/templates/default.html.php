<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<!DOCTYPE HTML>
<html lang="<?= $language; ?>" dir="<?= $direction; ?>">

<?= import('page_head.html') ?>

<body class="com_<?= $extension ?>">
<?
/*
?>
<div id="panel-pages">
    <?= import('com:pages.view.pages.list.html', array('state' => $state)); ?>
</div>
*/
?>
<div id="panel-wrapper">
    <div id="panel-header">
        <div id="menu">
            <ktml:toolbar type="menubar">
        </div>
        <ktml:toolbar type="actionbar" id="statusmenu">
	</div>

    <ktml:toolbar type="tabbar">

    <ktml:modules position="actionbar">
    <div id="panel-toolbar">
        <ktml:modules:content>
        <?= import('page_message.html') ?>
    </div>
    </ktml:modules>

    <div id="panel-component">
        <ktml:modules position="sidebar">
        <div id="panel-sidebar">
            <ktml:modules:content>
        </div>
        </ktml:modules>

        <div id="panel-content">
            <ktml:content>
	    </div>

        <ktml:modules position="inspector">
            <div id="panel-inspector">
                <ktml:modules:content>
            </div>
        </ktml:modules>
    </div>
</div>

</body>

</html>