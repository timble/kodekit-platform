<? $iterator = $articles->getIterator() ?>
<? $count    = count($articles) ?>

<? if($parameters->get('show_page_title', 1)) : ?>
<div class="componentheading<?= @escape($parameters->get('pageclass_sfx')) ?>">
	<?= @escape($parameters->get('page_title')) ?>
</div>
<? endif ?>

<table class="blog<?= @escape($parameters->get('pageclass_sfx')) ?>" cellpadding="0" cellspacing="0">
	<? if($parameters->def('num_leading_articles', 1)) : ?>
	<tr>
		<td valign="top">
		<? for($i = 1; $i <= $parameters->get('num_leading_articles'); $i++) : ?>
		    <? if($i > $count) break ?>
			<div>
			    <?= @template('featured_item', array('article' => $iterator->current(), 'parameters' => $parameters, 'user' => $user)) ?>
			</div>
			<? $iterator->next() ?>
	    <? endfor ?>
		</td>
	</tr>
	<? endif ?>

	<? if($parameters->get('num_intro_articles') && $parameters->get('num_leading_articles') < $total) : ?>
    	<? for($i = 0; $i < min($parameters->get('num_intro_articles'), $count - $parameters->get('num_leading_articles')); $i++) : ?>
    		<? $items[$i] = (string) @template('featured_item', array('article' => $iterator->current(), 'parameters' => $parameters, 'user' => $user)) ?>
    		<? $iterator->next() ?>
    	<? endfor ?>
    	<tr>
    		<td valign="top">
    			<table width="100%" cellpadding="0" cellspacing="0">
    				<tr>
    				<? $rows = ceil($parameters->get('num_intro_articles') / $parameters->get('num_columns')) ?>
					<? $cols = $parameters->get('num_columns') ?>

                    <? for($i = 0; $i < $cols; $i++) : ?>
    					<? $divider = $i > 0 ? ' column_separator' : '' ?>

    					<td valign="top" width="<?= intval(100 / $cols) ?>%" class="article_column<?= $divider ?>">
    					<? for($j = 0; $j < $rows; $j++) : ?>
    						<? if(($i * $rows + $j) >= $parameters->get('num_intro_articles')) break ?>

            				<? if($parameters->get('multi_column_order')) : ?>
    							<?= isset($items[$j * $cols + $i]) ? $items[$j * $cols + $i] : '' ?>
            				<? else : ?>
            					<?= isset($items[$i * $rows + $j]) ? $items[$i * $rows + $j] : '' ?>
            				<? endif ?>
        				<? endfor ?>
        				</td>
                    <? endfor ?>
    				</tr>
    			</table>
    		</td>
    	</tr>
	<? endif ?>

	<? if($parameters->get('num_links') && count($links)) : ?>
    	<tr>
    		<td valign="top">
    			<div class="blog_more<?= @escape($parameters->get('pageclass_sfx')) ?>">
    				<div>
                    	<strong><?= @text('More Articles...') ?></strong>
                    </div>

    				<? foreach($links as $link) : ?>
                		<?= (string) @template('featured_link', array('article' => $link, 'parameters' => $parameters, 'user' => $user)) ?>
                	<? endforeach ?>
    			</div>
    		</td>
    	</tr>
	<? endif ?>

	<? if($parameters->get('show_pagination') == 1 || ($parameters->get('show_pagination') == 2 && $total > $count)) : ?>
	<tr>
		<td valign="top" align="center">
			<?= @helper('paginator.pagination', array('total' => $total)) ?>
		</td>
	</tr>
    	<?// if($parameters->def('show_pagination_results', 1)) : ?>
    	<tr>
    		<td valign="top" align="center"><?//= $this->pagination->getPagesCounter() ?>
    		</td>
    	</tr>
    	<?// endif ?>
	<? endif ?>
</table>