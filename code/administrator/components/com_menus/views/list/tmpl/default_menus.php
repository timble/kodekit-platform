<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php if (count($this->menus)) : ?>
<ul>
<?php foreach ($this->menus as $menu) : ?> 
<?php $class = ($menu->menutype == $this->menutype) ? 'class="active"' : ''; ?>
<li <?php echo $class?>>
	<a href="<?php echo JRoute::_('index.php?option=com_menus&task=view&menutype='.$menu->menutype); ?>">
		<?php echo $menu->title . ($menu->home ? ' *' : '') ?>
	</a>
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
						
			