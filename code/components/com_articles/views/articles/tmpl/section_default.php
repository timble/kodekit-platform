<? if($parameters->get('show_page_title')) : ?>
    <div class="componentheading<?= @escape($parameters->get('pageclass_sfx')) ?>">
    	<?= @escape($parameters->get('page_title')) ?>
    </div>
<? endif ?>

<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="contentpane<?= @escape($parameters->get('pageclass_sfx')) ?>">
    <tr>
    	<td valign="top" class="contentdescription<?= @escape($parameters->get('pageclass_sfx')) ?>" colspan="2">
    	<? if($parameters->get('show_description_image') && $section->image) : ?>
    		<img src="<?= KRequest::base().'/'.str_replace(JPATH_ROOT.DS, '', JPATH_IMAGES.'/stories/'.$category->image) ?>" align="<?= $section->image_position ?>" hspace="6" alt="<?= $section->image ?>" />
    	<? endif ?>
    	<? if($parameters->get('show_description') && $section->description) : ?>
    		<?= $section->description ?>
    	<? endif ?>
    	</td>
    </tr>
    <tr>
    	<td colspan="2">
    	<? if($parameters->get('show_categories')) : ?>
        	<ul>
        	<? foreach($categories as $category) : ?>
        		<? if(!$parameters->get('show_empty_categories') && !$category->activecount) continue ?>
        		<li>
        			<? $category_slug = $category->id.($category->slug ? ':'.$category->slug : '') ?>
        			<a href="<?= @route(ContentHelperRoute::getCategoryRoute($category_slug, $category->section_id).'&layout=default') ?>" class="category">
        				<?= @escape($category->title);?></a>
        			<? if($parameters->get('show_cat_num_articles')) : ?>
            			<span class="small">
            				( <?= $category->activecount.' '.@text($category->activecount == 1 ? 'item' : 'items') ?> )
            			</span>
        			<? endif ?>
        			<? if($parameters->get('show_category_description') && $category->description) : ?>
            			<br />
            			<?= $category->description ?>
        			<? endif ?>
        		</li>
        	<? endforeach ?>
        	</ul>
    	<? endif ?>
    	</td>
    </tr>
</table>