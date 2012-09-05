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

<script src="media://com_application/js/application.js" />

<? /*if($params->get('flexBox', '1')) :*/ if(true) : ?>
<script src="media://com_application/js/chromatable.js" />
<? else : ?>
<style src="media://com_application/css/legacy.css" />
<? endif; ?>

<body class="<?= $option ?>">
<div id="container" class="box-column">
    <div id="panel-header">
        <?= @helper('menubar.render', array('menubar' => $menubar, 'attribs' => array('id' => 'menu')))?>
        <?= @helper('toolbar.render', array('toolbar' => $toolbar, 'attribs' => array('id' => 'statusmenu')))?>
	</div>

    <div id="panel-navigation">
		<ktml:modules position="menubar" />
	</div>

    <ktml:modules position="toolbar">
    <div id="panel-toolbar">
        <ktml:content />
    </div>
    </ktml:modules>

    <?= @template('default_message') ?>

    <div class="box-row">
        <ktml:modules position="sidebar">
        <div id="panel-sidebar">
            <ktml:content />
        </div>
        </ktml:modules>

        <div id="panel-content" class="<?= @service('component')->getController()->getView()->getLayout() ?> row-fluid">
            <ktml:variable name="content" />
	    </div>

        <ktml:modules position="inspector">
            <div id="panel-inspector">
                <ktml:content />
            </div>
        </ktml:modules>
    </div>
</div>
<? if(KDEBUG) : ?>
	<?= @service('com://admin/debug.controller.debug')->display(); ?>
<? endif; ?>
	
<script data-inline src="media://com_application/js/chosen.mootools.1.2.js" /></script>
<script data-inline> $$(".chzn-select").chosen(); </script>
</body>