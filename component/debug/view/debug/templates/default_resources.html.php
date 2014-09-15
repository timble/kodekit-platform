<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<table class="adminlist">
	<thead>
    	<tr>
    		<th class="-koowa-sortable"><?= translate('#') ?></th>
    		<th class="-koowa-sortable"><?= translate('Component') ?></th>
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
  	        	<td class="-koowa-sortable"><?= str_replace(APPLICATION_ROOT, '', $file); ?></td>
  	    	</tr>
  	        <? endforeach; ?>
  	        <? $i++; ?>
  	    <? endforeach; ?>
  	
  		<? foreach ( $includes as $file ) : ?>
  			<tr>  
  				<td class="-koowa-sortable" align="right" width="10"><?= $i; ?></td>
  				<td class="-koowa-sortable"><?= translate('PHP') ?></td>
				<td class="-koowa-sortable"><?= helper('com:files.filesize.humanize', array('size' => filesize($file))); ?></td>
            	<td class="-koowa-sortable"><?= str_replace(APPLICATION_ROOT, '', $file); ?></td>
        	</tr>
            <? $i++; ?>
        <? endforeach; ?>
  	</tbody>
</table>