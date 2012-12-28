<script src="media://com_pages/js/pages-list.js" />

<nav class="scrollable">
<ul class="nav nav-list">
<? foreach($this->getService('com://admin/pages.model.menus')->sort('title')->getRowset() as $menu) : ?>
    <? $menu_pages = $this->getService('com://admin/pages.model.pages')->getRowset()->find(array('pages_menu_id' => $menu->id)) ?>
    <? if(count($menu_pages)) : ?>
        <li class="nav-header"><?= $menu->title ?></li>
		<? $first = true; $last_depth = 0; ?>
		
		<? foreach($menu_pages as $page) : ?>
		    <? $depth = substr_count($page->path, '/') ?>
		    		    
		    <? if(substr($page->path, -1) != '/') : ?>
		        <? $depth++ ?>
		    <? endif ?>
		
		    <? if($depth > $last_depth && !$first) : ?>
		        <ul>
		        <? $last_depth = $depth; $first = false; ?>
		    <? endif ?>
		
		    <? if($depth < $last_depth && !$first) : ?>
		        <?= str_repeat('</li></ul>', $last_depth - $depth) ?>
		        <? $last_depth = $depth ?>
		    <? endif ?>
		    
		    <? if($depth == $last_depth) : ?>
		        </li>
		    <? endif ?>
		    <li>
		        <? switch($page->type) : 
		              case 'component': ?>
						<a href="<?= @route(preg_replace('%layout=table%', 'layout=default', $page->link->getQuery()).'&Itemid='.$page->id) ?>">
		                    <span><?= $page->title ?></span>
		                </a>
						<? break ?>
						
				    <? case 'menulink': ?>
				        <? $page_linked = @service('application.pages')->getPage($page->link->query['Itemid']); ?>
				        <a href="<?= $page_linked->link ?>">
		                    <span><?= $page->title ?></span>
		                </a>
						<? break ?>
						
		            <? case 'separator': ?>
						<span class="separator"><span><?= $page->title ?></span></span>
						<? break ?>
		
					<? case 'url': ?>
						<a href="<?= $page->link ?>">
		                    <span><?= $page->title ?></span>
		                </a>
						<? break ?>
						
			        <? case 'redirect': ?>
			            <a href="<?= $page->route ?>">
			                <span><?= $page->title ?></span>
			            </a>
				<? endswitch ?>
			</li>
		<? endforeach ?>		
	<? endif; ?>
<? endforeach ?>
</ul>
</nav>