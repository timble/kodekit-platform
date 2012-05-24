<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php foreach ($this->items as $item) : ?>
<h2>
	<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug)); ?>"><?php echo $this->escape($item->title); ?></a>
</h2>

<p>
	<?php if ($this->params->get('show_create_date')) : ?>
		<?php echo JText::_('Created') .': '.  KService::get('koowa:template.helper.date')->format(array('date' => $item->created, 'format' => JText::_('DATE_FORMAT_LC2'))) ?>
	<?php endif; ?>
	<?php if ($this->params->get('show_author')) : ?>
		<?php echo JText::_('Author').': '; echo $this->escape($item->created_by_alias) ? $this->escape($item->created_by_alias) : $this->escape($item->author); ?>
	<?php endif; ?>
</p>

<p>
	<?php echo substr(strip_tags($item->introtext), 0, 255);  ?>...
</p>
<?php endforeach; ?>

<div id="navigation">
	<span><?php echo $this->pagination->getPagesLinks(); ?></span>
	<span><?php echo $this->pagination->getPagesCounter(); ?></span>
</div>
