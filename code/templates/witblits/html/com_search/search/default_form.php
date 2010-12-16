<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<form id="searchForm" action="<?php echo JRoute::_( 'index.php?option=com_search' );?>" method="post" name="searchForm">
<dl id="search-top">
	<dt><label for="search_searchword"><?php echo JText::_( 'Search Keyword' ); ?>:</label></dt>
	<dd><span class="input-wrap"><input type="text" name="searchword" id="search_searchword" size="30" maxlength="20" value="<?php echo $this->escape($this->searchword); ?>" class="search-input" /></span>
		<button name="Search" onclick="this.form.submit()" class="search-btn"><?php echo JText::_( 'Search' );?></button>
	</dd>
	<?php if ($this->params->get( 'search_areas', 1 )) : ?>
	<dt><?php echo JText::_( 'Search Only' );?>:</dt>
	<dd class="checkbox"><?php foreach ($this->searchareas['search'] as $val => $txt) :
			$checked = is_array( $this->searchareas['active'] ) && in_array( $val, $this->searchareas['active'] ) ? 'checked="checked"' : '';
		?>
		<input type="checkbox" name="areas[]" value="<?php echo $val;?>" id="area_<?php echo $val;?>" <?php echo $checked;?> />
			<label for="area_<?php echo $val;?>">
				<?php echo JText::_($txt); ?>
			</label>
			
		<?php endforeach; ?><span class="search-phrase"><?php echo $this->lists['searchphrase']; ?></span></dd>
	<?php endif; ?>
	<dt><label for="ordering"><?php echo JText::_( 'Ordering' );?>:	</label></dt>
	<dd class="radio"><?php echo $this->lists['ordering'];?></dd>
</dl>
<?php if($this->total > 0) : ?>
<ul class="search-info">
	<li class="search-counter"><span class="keyword"><?php echo $this->pagination->getPagesCounter(); ?>
	<?php echo JText::_( 'Search Keyword: ' ) .' <strong>'. $this->escape($this->searchword) .'</strong>'; ?></span>&nbsp;&nbsp;|&nbsp;&nbsp;<span class="results"><?php echo $this->result; ?></span></li>
    <li class="search-limit"><?php echo JText::_( 'Number of results' ); ?>&nbsp;<?php echo $this->pagination->getLimitBox( ); ?></li>
</ul>
<?php endif; ?>
<input type="hidden" name="task"   value="search" />
</form>
