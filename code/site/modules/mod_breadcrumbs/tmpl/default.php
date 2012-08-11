<ul class="breadcrumb">

	<?php for ($i = 0; $i < $count; $i ++) : ?>
		<? // If not the last item in the breadcrumbs add the separator ?>
		<? if ($i < $count - 1) : ?>
			<? if(!empty($list[$i]->link)) : ?>
				<li><?= '<a href="'.$list[$i]->link.'" class="pathway">'.$list[$i]->name.'</a>'; ?></li>
			<? else : ?>
				<li><?= $list[$i]->name; ?></li>
			<? endif; ?>
			<span class="divider">/</span>
		<? elseif ($params->get('showLast', 1)) : // when $i == $count -1 and 'showLast' is true ?>
		    <li><?=  $list[$i]->name; ?></li>
		<? endif; ?>
	<? endfor; ?>
</ul>