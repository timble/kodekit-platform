<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<ol class="search-results">
<?php
foreach( $this->results as $result ) : ?>
	<li><?php if ( $result->href ) :
				if ($result->browsernav == 1 ) : ?>
					<a href="<?php echo JRoute::_($result->href); ?>" target="_blank">
				<?php else : ?>
					<a href="<?php echo JRoute::_($result->href); ?>">
				<?php endif;
				echo $this->escape($result->title);
				if ( $result->href ) : ?>
					</a>
				<?php endif;
				if ( $result->section ) : ?>
					<span class="search-section">
						<?php echo $this->escape($result->section); ?>
					</span>
				<?php endif; ?>
			<?php endif; ?>
		<p class="search-description">
			<?php echo $result->text; ?>
		</p>
		<?php
			if ( $this->params->get( 'show_date' )) : ?>
		<div class="search-date">
			<?php echo $result->created; ?>
		</div>
		<?php endif; ?>
	</li>
<?php endforeach; ?>
</ol>
<div id="pagination-wrap" class="search-bottom">
	<div class="pagination-links">
		<?php echo $this->result; ?>
	</div>
	<?php echo $this->pagination->getPagesLinks( ); ?>
</div>