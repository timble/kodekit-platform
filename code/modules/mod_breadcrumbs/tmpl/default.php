<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<span class="breadcrumbs pathway" itemprop="breadcrumb">
<?php for ($i = 0; $i < $count; $i ++) :

    if ($params->get('showLast', 1) || $i < $count -1) {
		if(!empty($list[$i]->link)) {
			echo '<a href="'.$list[$i]->link.'" class="pathway">'.$list[$i]->name.'</a>';
		} else {
			echo $list[$i]->name;
		}
	}
	
	if ($i < $count -1) {
		echo ' '.$separator.' ';
	}
endfor; ?>
</span>
