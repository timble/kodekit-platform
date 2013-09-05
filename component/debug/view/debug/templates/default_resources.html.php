<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<table class="adminlist">
	<thead>
    	<tr>
    		<th class="-koowa-sortable"><?= translate('#') ?></th>
    		<th class="-koowa-sortable"><?= translate('Extension') ?></th>
    		<th class="-koowa-sortable"><?= translate('Size') ?></th>
    		<th class="-koowa-sortable"><?= translate('File'); ?></th>
    	</tr>
  	</thead>
  	<tbody>
  	    <? $i = 1; ?>
  	    	<? foreach ( $languages as $extension => $files) : ?>
  	    	    <? foreach ( $files as $file => $status ) : ?>
  	    		<tr>  
  	    			<td class="-koowa-sortable" align="right"><?= $i; ?></td>
  	    			<td class="-koowa-sortable"><?= translate('language') ?></td>
  	    		<td class="-koowa-sortable"><?= helper('com:files.filesize.humanize', array('size' => filesize($file))); ?></td>
  	        	<td class="-koowa-sortable"><?= str_replace(JPATH_ROOT, '', $file); ?></td>
  	    	</tr>
  	        <? endforeach; ?>
  	        <? $i++; ?>
  	    <? endforeach; ?>
  	
  		<? foreach ( $includes as $file ) : ?>
  			<tr>  
  				<td class="-koowa-sortable" align="right" width="10"><?= $i; ?></td>
  				<td class="-koowa-sortable"><?= translate('PHP') ?></td>
				<td class="-koowa-sortable"><?= helper('com:files.filesize.humanize', array('size' => filesize($file))); ?></td>
            	<td class="-koowa-sortable"><?= str_replace(JPATH_ROOT, '', $file); ?></td>
        	</tr>
            <? $i++; ?>
        <? endforeach; ?>
  	</tbody>
</table>