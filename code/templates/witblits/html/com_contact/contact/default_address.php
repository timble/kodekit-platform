<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php
 
function splitname($n)
{	
	$split = explode(' ', $n);
	$count = count($split);
	
	switch($count){
		case 2:
		$html = '<span class="fn n">'."\n\t".
					'<span class="given-name">'.$split[0].'</span>'."\n\t".
					'<span class="family-name">'.$split[1].'</span>'."\n".
					'</span>'."\n";
		break;
		case 3:
		$html = '<span class="fn n">'."\n\t".
				'<span class="given-name">'.$split[0].'</span>'."\n\t".
				'<span class="additional-name">'.$split[1].'</span>'."\n\t".
				'<span class="family-name">'.$split[2].'</span>'."\n".
				'</span>'."\n";
		break;
	}
	
	return $html;
}
?>
<?php if ( ( $this->contact->address || $this->contact->suburb  || $this->contact->state || $this->contact->country || $this->contact->postcode ) ) : ?>

<div id="hcard-<?php echo str_replace(" ","-",ucwords($this->escape($this->contact->name))); ?>" class="vcard">

    <div class="contact">

        <?php if ( $this->contact->name && $this->contact->params->get( 'show_name' ) ) : ?>
        <h2 class="contact-name">
            <?php echo splitname($this->escape($this->contact->name)); ?>
        </h2>
        <?php endif; ?>
        <?php if ( $this->contact->con_position && $this->contact->params->get( 'show_position' ) ) : ?>
        <h3 class="contact-position"><span class="role"><?php echo $this->escape($this->contact->con_position); ?></span></h3>
        <?php endif; ?>
     
    </div>

    <?php if ( $this->contact->address && $this->contact->params->get( 'show_street_address' ) ) : ?>
    <div class="adr">
        
        <?php if ( $this->contact->address && $this->contact->params->get( 'show_street_address' ) ) : ?>
        <span class="street-address"><?php echo nl2br($this->escape($this->contact->address)); ?></span>
        <?php endif; ?>
      
        <?php if ( $this->contact->suburb && $this->contact->params->get( 'show_suburb' ) ) : ?>
        <span class="locality"><?php echo $this->escape($this->contact->suburb); ?></span>
        <?php endif; ?>
        
        <?php if ( $this->contact->state && $this->contact->params->get( 'show_state' ) ) : ?>
        <span class="region"><?php echo $this->escape($this->contact->state); ?></span>
        <?php endif; ?>
        
        <?php if ( $this->contact->postcode && $this->contact->params->get( 'show_postcode' ) ) : ?>
        <span class="postal-code"><?php echo $this->escape($this->contact->postcode); ?></span>
        <?php endif; ?>
        
        <?php if ( $this->contact->country && $this->contact->params->get( 'show_country' ) ) : ?>
        <span class="country-name"><?php echo $this->escape($this->contact->country); ?></span>
        <?php endif; ?>
    
    </div>
    <?php endif; ?>
 
    <?php if ( $this->contact->telephone && $this->contact->params->get('show_telephone') || $this->contact->fax && $this->contact->params->get('show_fax')
      || $this->contact->mobile && $this->contact->params->get('show_mobile')) : ?>
      
        <ul>   
            <?php if ( $this->contact->telephone && $this->contact->params->get( 'show_telephone' ) ) : ?>
            <li class="tel contact-tel">
                  <span class="type">work</span>
                  <strong><?php echo JText::_('Telephone'); ?>:</strong> <span class="value"><?php echo nl2br($this->escape($this->contact->telephone)); ?></span>
            </li>
            <?php endif; ?>
            
            <?php if ( $this->contact->fax && $this->contact->params->get( 'show_fax' ) ) : ?>
            <li class="tel contact-fax">
                  <span class="type">work</span>
                  <span class="type">fax</span>
                  <strong><?php echo JText::_('Fax'); ?>:</strong> <span class="value"><?php echo nl2br($this->escape($this->contact->fax)); ?></span>
            </li>
            <?php endif; ?>
            
            <?php if ( $this->contact->mobile && $this->contact->params->get( 'show_mobile' ) ) :?>
            <li class="tel contact-mob">
                  <span class="type">cell</span>
                  <strong><?php echo JText::_('Mobile'); ?>:</strong> <span class="value"><?php echo nl2br($this->escape($this->contact->mobile)); ?></span>
            </li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>


    <?php if ( $this->contact->webpage && $this->contact->params->get( 'show_webpage' )) : ?>
	<p class="website"><strong><?php echo JText::_('Web'); ?>:</strong> <a href="<?php echo $this->escape($this->contact->webpage); ?>" target="_blank"><span class="org url">
	<?php echo $this->escape($this->contact->webpage); ?></span></a></p>
    <?php endif; ?>  
        
    <?php if ( $this->contact->params->get( 'allow_vcard' ) ) : ?>
	    <p class="vcard"><?php echo JText::_( 'Download information as a' );?>
		<a href="<?php echo JURI::base(); ?>index.php?option=com_contact&amp;task=vcard&amp;contact_id=<?php echo $this->contact->id; ?>&amp;format=raw&amp;tmpl=component">
			<?php echo JText::_( 'VCard' );?></a>.</p>
	<?php endif; ?>	
	
</div>
<?php endif; ?>