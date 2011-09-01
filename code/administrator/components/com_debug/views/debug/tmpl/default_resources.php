<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<table>
	<thead>
    	<tr>
    		<th><?= @text('#') ?></th>
    		<th><?= @text('Extension') ?></th>
    		<th><?= @text('Size') ?></th>
    		<th><?= @text('File'); ?></th>
    	</tr>
  	</thead>
  	<tbody>
  		<? $i = 1; ?>
  		<? foreach ( $languages as $extension => $files) : ?>
  		    <? foreach ( $files as $file => $status ) : ?>
  			<tr>  
  				<td><?= $i; ?></td>
  				<td><?= $extension; ?></td>
				<td><?= @helper('com://admin/files.template.helper.filesize.humanize', array('size' => filesize($file))); ?></td>
            	<td><?= str_replace(JPATH_ROOT, '', $file); ?></td>
            	<td></td>
        	</tr>
            <? endforeach; ?>
            <? $i++; ?>
        <? endforeach; ?>
  	</tbody>
</table>

<table>
	<thead>
    	<tr>
    		<th><?= @text('#') ?></th>
    		<th><?= @text('Extension') ?></th>
    		<th><?= @text('Size') ?></th>
    		<th><?= @text('File'); ?></th>
    	</tr>
  	</thead>
  	<tbody>
  		<? $i = 1; ?>
  		<? foreach ( $includes as $file ) : ?>
  			<tr>  
  				<td><?= $i; ?></td>
  				<td><?= @text('PHP') ?></td>
				<td><?= @helper('com://admin/files.template.helper.filesize.humanize', array('size' => filesize($file))); ?></td>
            	<td><?= str_replace(JPATH_ROOT, '', $file); ?></td>
            	<td></td>
        	</tr>
            <? $i++; ?>
        <? endforeach; ?>
  	</tbody>
</table>