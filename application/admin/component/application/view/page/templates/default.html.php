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

<body class="com_<?= $component ?>">
<div id="container">
    <div id="panel-header">
        <div id="menu">
        	<?= @helper('menubar.render')?>
        </div>
        <?= @helper('toolbar.render', array('toolbar' => $toolbar, 'attribs' => array('id' => 'statusmenu')))?>
	</div>

    <?= @helper('tabbar.render', array('tabbar' => $tabbar, 'attribs' => array('id' => 'menubar')))?>

    <ktml:modules position="toolbar">
    <div id="panel-toolbar">
        <ktml:modules:content />
    </div>
    </ktml:modules>

    <?= @template('page_message.html') ?>

    <div class="box-row">
        <ktml:modules position="sidebar">
        <div id="panel-sidebar">
            <ktml:modules:content />
        </div>
        </ktml:modules>

        <div id="panel-content" class="row-fluid">
            <ktml:content />
	    </div>

        <ktml:modules position="inspector">
            <div id="panel-inspector">
                <ktml:modules:content />
            </div>
        </ktml:modules>
    </div>
</div>

</body>

</html>