<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */
?>

<table class="adminlist">
	<thead>
    	<tr>
    		<th class="-koowa-sortable"><?= @text('#') ?></th>
    		<th class="-koowa-sortable"><?= @text('Extension') ?></th>
    		<th class="-koowa-sortable"><?= @text('Size') ?></th>
    		<th class="-koowa-sortable"><?= @text('File'); ?></th>
    	</tr>
  	</thead>
  	<tbody>
  	    <? $i = 1; ?>
  	    	<? foreach ( $languages as $extension => $files) : ?>
  	    	    <? foreach ( $files as $file => $status ) : ?>
  	    		<tr>  
  	    			<td class="-koowa-sortable" align="right"><?= $i; ?></td>
  	    			<td class="-koowa-sortable"><?= @text('language') ?></td>
  	    		<td class="-koowa-sortable"><?= @helper('com:files.filesize.humanize', array('size' => @filesize($file))); ?></td>
  	        	<td class="-koowa-sortable"><?= str_replace(JPATH_ROOT, '', $file); ?></td>
  	    	</tr>
  	        <? endforeach; ?>
  	        <? $i++; ?>
  	    <? endforeach; ?>
  	
  		<? foreach ( $includes as $file ) : ?>
  			<tr>  
  				<td class="-koowa-sortable" align="right" width="10"><?= $i; ?></td>
  				<td class="-koowa-sortable"><?= @text('PHP') ?></td>
				<td class="-koowa-sortable"><?= @helper('com:files.filesize.humanize', array('size' => @filesize($file))); ?></td>
            	<td class="-koowa-sortable"><?= str_replace(JPATH_ROOT, '', $file); ?></td>
        	</tr>
            <? $i++; ?>
        <? endforeach; ?>
  	</tbody>
</table>