<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Modules
 * @subpackage  Widget
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @overlay(array('url' => @route($url), 'options' => array('selector' => $module->params->get('selector', 'body')))); ?>