<?php
/**
 * @version     $Id: form_orderable.php 837 2011-04-06 00:58:44Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die('Restricted access');

echo @helper('admin::com.categories.template.helper.listbox.banners', array(
                                    'filter' => array('category' => $state->category),
                                    'name' => 'ordering',
                                    'text' => 'ordering',
                                    'value'=> 'ordering',
                                    'column' => 'ordering',
                                    'listbox_title' => 'ordering',
                                    'deselect' => true,
                                    'package' => 'banners'
                                ));

 
