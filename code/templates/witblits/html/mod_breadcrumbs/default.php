<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<p class="breadcrumbs"><strong><?php echo JText::_('You are here:');?></strong>&nbsp;
<?php for ($i = 0; $i < $count; $i ++) :
	$linktext = explode(' # ',$list[$i]->name);
	
	if ($i < $count -1) {
		if(!empty($list[$i]->link)) {
			echo '<a href="'.$list[$i]->link.'">'.$linktext[0].'</a>';
		} else {
			echo '<span>'.$linktext[0].'</span>';
		}
	}  else if ($params->get('showLast', 1)) { // when $i == $count -1 and 'showLast' is true
	    echo '<span class="pathway-current">'.$linktext[0].'</span>';
	}
endfor; ?>
</p>