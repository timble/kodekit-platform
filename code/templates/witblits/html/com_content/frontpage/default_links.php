<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<h3><?php echo JText::_( 'More Articles...' ); ?></h3>
<ul class="more-links">
<?php foreach ($this->links as $link) : ?>
	<li><a class="blogsection" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($link->slug, $link->catslug, $link->sectionid)); ?>"><?php echo $link->title; ?></a></li>
<?php endforeach; ?>
</ul>