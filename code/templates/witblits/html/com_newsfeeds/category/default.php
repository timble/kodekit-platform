<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
<h1 class="componentheading clearer"><?php echo $this->escape($this->params->get('page_title')); ?></h1>
<?php endif; ?>
<?php if ( @$this->image || @$this->category->description ) : ?>
<div class="desc"><?php if ( isset($this->image) ) :  echo $this->image; endif; echo $this->category->description; ?></div>
<?php endif; ?>
<?php echo $this->loadTemplate('items'); ?>