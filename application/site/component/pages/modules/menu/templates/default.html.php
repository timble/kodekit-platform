<?
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<? $title = $show_title ? $module->title : null; ?>

<?= @helper('com://site/pages.template.helper.list.pages', array('pages' => $pages, 'active' => $active, 'title' => $title, 'attribs' => array('class' => $class))) ?>