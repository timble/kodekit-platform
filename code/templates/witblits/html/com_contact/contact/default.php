<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php 
$cparams = JComponentHelper::getParams ('com_media');
?>
<div id="component-contact">
    <?php if ($this->params->get('show_page_title',1) && $this->params->get('page_title') != $this->contact->name) : ?>
        <h1 class="componentheading clearer">
    		<?php echo $this->params->get( 'page_title' ); ?>
    	</h1>
    <?php endif; ?>
    
    <?php if ( $this->contact->misc && $this->contact->params->get( 'show_misc' ) ) : ?>
    <p class="desc">
    	<?php echo nl2br($this->contact->misc); ?>
    </p>
    <?php endif; ?>

    <?php if ( $this->params->get( 'show_contact_list' ) && count( $this->contacts ) > 1) : ?>
    <div id="select-contact">
    	<form action="<?php echo JRoute::_('index.php') ?>" method="post" name="selectForm" id="selectForm">
    	<strong><?php echo JText::_( 'Select Contact' ); ?>:</strong>
    		<?php echo JHTML::_('select.genericlist',  $this->contacts, 'contact_id', 'class="inputbox" onchange="this.form.submit()"', 'id', 'name', $this->contact->id);?>
    		<input type="hidden" name="option" value="com_contact" />
    	</form>
    </div>
    <?php endif; ?>

	<?php if ($this->params->get('show_street_address') || $this->params->get('show_state') || $this->params->get('show_suburb') || $this->params->get('show_postcode') || $this->params->get('show_country') || $this->params->get('show_image')) : ?>
    <div id="contact-top">
		<?php if ($this->params->get('show_street_address') || $this->params->get('show_state') || $this->params->get('show_suburb') || $this->params->get('show_postcode') || $this->params->get('show_country')) : ?>
	    <div class="contact-info">
        	<?php echo $this->loadTemplate('address'); ?>
        </div>
		<?php endif; ?>
		<?php if ( $this->contact->image && $this->contact->params->get( 'show_image' ) ) : ?>
		<div class="contact-photo">
			<?php echo JHTML::_('image', 'images/stories' . '/'.$this->contact->image, JText::_( 'Contact' ), array('align' => 'middle', 'class' => 'photo')); ?>
		</div>
		<?php endif; ?>
    </div>           
	<?php endif; ?>
	
    <?php if ( $this->contact->params->get('show_email_form') && ($this->contact->email_to || $this->contact->user_id))
	    echo $this->loadTemplate('form');
    ?>
</div>