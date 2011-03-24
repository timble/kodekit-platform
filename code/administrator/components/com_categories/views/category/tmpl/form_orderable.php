<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die('Restricted access');

echo @helper('admin::com.categories.template.helper.listbox.categories', array(
                                    'filter' => array('section' => $state->section),
                                    'name' => 'ordering',
                                    'text' => 'ordering',
                                    'value'=> 'ordering',
                                    'column' => 'ordering',
                                    'listbox_title' => 'ordering',
                                    'deselect' => true
                                ));

 
