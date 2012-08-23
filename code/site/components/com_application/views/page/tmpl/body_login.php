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
<body>
<?= @template('message') ?>
<div id="frame" class="outline">
    <h1><?= @service('application')->getCfg('sitename'); ?></h1>
    <ktml:variable name="component" />
</div>
</body>
