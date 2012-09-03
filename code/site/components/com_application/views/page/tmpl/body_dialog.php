<?
/**
 * @version     $Id: default.php 4558 2012-08-11 21:12:47Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<body id="tmpl-modal" class="<?= $option ?> contentpane">
    <?= @template('default_message') ?>
    <ktml:variable name="content" />
</body>