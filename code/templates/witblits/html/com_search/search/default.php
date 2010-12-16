<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
<h1 class="componentheading">
	<?php echo $this->params->get( 'page_title' ); ?>
</h1>
<?php endif; ?>

<?php echo $this->loadTemplate('form'); ?>
<?php if(!$this->error && count($this->results) > 0) :
	echo $this->loadTemplate('results');
else :
	echo $this->loadTemplate('error');
endif; ?>
