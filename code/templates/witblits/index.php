<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Templates
 * @subpackage	Witblits
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once('templates/'.$this->template.'/lib/functions.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" class="no-js">
<head>
	<jdoc:include type="head" />
</head>
<body class="<?php echo bodyClasses($menu, $view); ?>" id="witblits">
	<?php if($wb_gridbg == 1) : ?>
		<div id="grid-overlay"></div>
	<?php endif; ?>
	<div id="skipto"<?php if($wb_skipto == 0) echo ' class="hidden"'; ?>>
		<ul class="list-reset">
			<?php if (JDocumentHTML::countModules( 'user3' )) : ?><li><a title="Skip to menu" href="#navigation"><?php echo JText::_('skip to menu'); ?></a></li><?php endif; ?>
			<li><a title="Skip to content" href="#content"><?php echo JText::_('skip to content'); ?></a></li>
			<li><a title="Skip to bottom" href="#footer"><?php echo JText::_('skip to bottom'); ?></a></li>
		</ul>
	</div>
	
	<?php if($this->countModules('toolbar')) : ?>
		<?php if($wb_blockwraps == 1) : ?><div id="toolbar-wrap"><?php endif; ?>
			<div id="toolbar" class="container_<?php echo $wb_grid_columns; ?> clearfix">
				<jdoc:include type="modules" name="toolbar" style="basic" />	
			</div>
		<?php if($wb_blockwraps == 1) : ?></div><?php endif; ?>
	<?php endif; ?>
	
	<?php if($wb_menu_position == 0) include_once($apath . '/includes/menu.php'); ?>
	<?php if($wb_breadcrumbs_position == 0) include_once($apath . '/includes/breadcrumbs.php'); ?>
	
	<?php if($wb_blockwraps == 1) : ?><div id="branding-wrap"><?php endif; ?>
		<div id="branding" class="container_<?php echo $wb_grid_columns; ?> clearfix">
			<?php if($wb_logo_type == 'image') : ?>
				<a href="<?php echo $this->baseurl ?>" title="<?php if ($wb_tagline != ""){ echo $this->params->get('tagline'); } else { echo $mainframe->getCfg('sitename'); } ?>" class="logo-image tooltip clearfix">
					<?php if($wb_logo_image == '-1') : ?>
					<img src="<?php echo $ipath; ?>/logos/placeholder.png" width="160" height="50" alt="logo placeholder image" title="Your logo type is currently set to image, but there is no image selected. Upload your logo to your templates/witblits/images/logos folder, then select the logo image in the templates parameters in Joomla's admin" border="0" class="tooltip" />
					<?php else : ?>
					<img src="<?php echo $tpath; ?>/images/logos/<?php echo $wb_logo_image; ?>" width="<?php echo $wb_logo_width; ?>" height="<?php echo $wb_logo_height; ?>" alt="<?php if ( $wb_logo_text != ""){ echo $wb_logo_text; } else { echo $mainframe->getCfg('sitename'); } ?>" border="0" class="clearfix" />
					<?php endif; ?>
				</a>
			<?php endif; ?>
			<?php if($wb_logo_type == 'text') : ?>
				<h1 class="logo reset clearfix">
					<a href="<?php echo $this->baseurl ?>" title="<?php echo $wb_logo_link_title; ?>" class="tooltip">						
						<?php if ($wb_logo_text != ""){ echo $wb_logo_text; } else { echo $mainframe->getCfg('sitename'); } ?>
					</a>
				</h1>
			<?php endif; ?>
			<?php if($wb_logo_type == 'module') : ?>
				<jdoc:include type="modules" name="branding" style="raw" />
			<?php endif; ?>
			
			<?php if($this->countModules('banner') && $wb_tagline !== '')  : ?>
				<p class="tagline tagline-btm reset"><?php echo $wb_tagline; ?></p>
			<?php endif; ?>
			
			<?php if($this->countModules('banner'))  : ?>
				<div class="banner">
					<jdoc:include type="modules" name="banner" style="raw" />
				</div>
			<?php else : ?>
				<?php if($wb_tagline !== '') : ?>
					<p class="tagline tagline-right reset"><?php echo $wb_tagline; ?></p>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	<?php if($wb_blockwraps == 1) : ?></div><?php endif; ?>
	
	<?php if($wb_menu_position == 1) include_once($apath . '/includes/menu.php'); ?>
	<?php if($wb_breadcrumbs_position == 1) include_once($apath . '/includes/breadcrumbs.php'); ?>
	
	<?php if($this->countModules('header') || $wb_header !== '')  : ?>
		<?php if($wb_blockwraps == 1) : ?><div id="header-wrap"><?php endif; ?>
			<div id="header" class="container_<?php echo $wb_grid_columns; ?> clearfix">
				<div class="inner">
					<jdoc:include type="modules" name="header" style="basic" />
				</div>
			</div>
		<?php if($wb_blockwraps == 1) : ?></div><?php endif; ?>
	<?php endif; ?>
	
	<?php if($wb_menu_position == 2) include_once($apath . '/includes/menu.php'); ?>
	<?php if($wb_breadcrumbs_position == 2) include_once($apath . '/includes/breadcrumbs.php'); ?>

	<?php if($this->countModules('user1 or user5 or user6')) : ?>
		<?php if($wb_blockwraps == 1) : ?><div id="topshelf-wrap"><?php endif; ?>
			<?php if($this->countModules('user1 or user5 or user6')) : ?><div id="topshelf" class="container_<?php echo $wb_grid_columns; ?> clearfix"><?php endif; ?>
				<?php if($this->countModules('user1')) : ?>
				<div id="topshelf1" class="<?php echo 'count'.$wb_user1_grid; ?> shelf clearfix">
					<jdoc:include type="modules" name="user1" style="grid" />
				</div>
				<?php endif; ?>
				<?php if($this->countModules('user5')) : ?>
				<div id="topshelf2" class="<?php echo 'count'.$wb_user5_grid; ?> shelf top clearfix">
					<jdoc:include type="modules" name="user5" style="grid" />
				</div>
				<?php endif; ?>
				<?php if($this->countModules('user6')) : ?>
				<div id="topshelf3" class="<?php echo 'count'.$wb_user6_grid; ?> shelf top clearfix">
					<jdoc:include type="modules" name="user6" style="grid" />
				</div>
				<?php endif; ?>
			<?php if($this->countModules('user1 or user5 or user6')) : ?></div><?php endif; ?>
		<?php if($wb_blockwraps == 1) : ?></div><?php endif; ?>
	<?php endif; ?>
	
	<?php if($wb_breadcrumbs_position == 3) include_once($apath . '/includes/breadcrumbs.php'); ?>
	
	<?php if($wb_blockwraps == 1) : ?><div id="main-wrap"><?php endif; ?>
		<div id="main" class="container_<?php echo $wb_grid_columns . ' sidebar-' . $wb_sidebar_position; ?>  clearfix<?php echo $wb_article_form; ?>">
			<div id="content" class="<?php if(!$this->countModules('left')) : echo 'grid_' . $wb_grid_columns;	else : echo 'grid_' . $wb_content_columns;	endif; if($wb_sidebar_position == 'left' && $this->countModules('left')) : echo ' push_' . $wb_sidebar_columns; endif; if($wb_equalize == 1) echo ' equalize' ?>">
				<div class="inner">
				
					<?php if ($this->getBuffer('message')) : ?>
					<div class="error"><jdoc:include type="message" /></div>
					<?php endif; ?>
					
					<?php if($this->countModules('inset1')) : ?>
					<div id="inset1" class="inset clearfix">
						<jdoc:include type="modules" name="inset1" style="basic" />
					</div>
					<?php endif; ?>
					
					<?php if($this->countModules('inset2')) : ?>
					<div id="inset2" class="inset clearfix">
						<jdoc:include type="modules" name="inset2" style="grid" />
					</div>
					<?php endif; ?>
					
					<jdoc:include type="component" />
					
					<?php if($this->countModules('inset3')) : ?>
					<div id="inset3" class="inset clearfix">
						<jdoc:include type="modules" name="inset3" style="grid" />
					</div>
					<?php endif; ?>
					
					<?php if($this->countModules('inset4')) : ?>
					<div id="inset4" class="inset clearfix">
						<jdoc:include type="modules" name="inset4" style="basic" />
					</div>
					<?php endif; ?>
				</div>
			</div>
			<?php if($this->countModules('left')) : ?>
			<div id="sidebar" class="<?php echo 'grid_' . $wb_sidebar_columns; if($wb_sidebar_position == 'left') : echo ' pull_' . $wb_content_columns; endif; if($wb_equalize == 1) echo ' equalize' ?>">
				<div class="inner">
					<jdoc:include type="modules" name="left" style="basic" />
				</div>
			</div>
			<?php endif; ?>
		</div>
	<?php if($wb_blockwraps == 1) : ?></div><?php endif; ?>
	
	<?php if($wb_breadcrumbs_position == 4) include_once($apath . '/includes/breadcrumbs.php'); ?>
	
	<?php if($this->countModules('user2 or user7 or user8')) : ?>
		<?php if($wb_blockwraps == 1) : ?><div id="bottomshelf-wrap"><?php endif; ?>
			<?php if($this->countModules('user2 or user7 or user8')) : ?><div id="bottomshelf" class="container_<?php echo $wb_grid_columns; ?> clearfix"><?php endif; ?>
				<?php if($this->countModules('user2')) : ?>
				<div id="bottomshelf1" class="<?php echo 'count'.$wb_user2_grid; ?> shelf btm clearfix">
					<jdoc:include type="modules" name="user2" style="grid" />
				</div>
				<?php endif; ?>
				<?php if($this->countModules('user7')) : ?>
				<div id="bottomshelf2" class="<?php echo 'count'.$wb_user7_grid; ?> shelf btm clearfix">
					<jdoc:include type="modules" name="user7" style="grid" />
				</div>
				<?php endif; ?>
				<?php if($this->countModules('user8')) : ?>
				<div id="bottomshelf3" class="<?php echo 'count'.$wb_user8_grid; ?> shelf btm clearfix">
					<jdoc:include type="modules" name="user8" style="grid" />
				</div>
			<?php endif; ?>
			<?php if($this->countModules('user2 or user7 or user8')) : ?></div><?php endif; ?>
		<?php if($wb_blockwraps == 1) : ?></div><?php endif; ?>
	<?php endif; ?>
			
	<?php if($wb_breadcrumbs_position == 5) include_once($apath . '/includes/breadcrumbs.php'); ?>
	
	<?php if($wb_blockwraps == 1) : ?><div id="footer-wrap"><?php endif; ?>
		<div id="footer" class="container_<?php echo $wb_grid_columns; ?> clearfix">
			<div class="copyright grid_<?php echo $wb_footer_grid1; ?>">
				Copyright &copy;  <?php echo date('Y'), ' '.$mainframe->getCfg('sitename'); ?>. All Rights Reserved.
			</div>
			<div class="back-to-top grid_<?php echo $wb_footer_grid2; ?>">
				<a href="#top" class="">Back to Top</a>
			</div>
			<div class="divider clearfix"></div>
			<div class="footer-links grid_<?php echo $wb_footer_grid1; ?>">
				<jdoc:include type="modules" name="footerlinks" style="none" />
			</div>
			<div class="credits grid_<?php echo $wb_footer_grid2; ?>">
				Powered by <a href="http://www.joomla.org" target="_blank" title="Powered by Joomla Content Management System">Joomla!</a> &amp; <a href="http://www.prothemer.com/witblits">Witblits</a>. <a href="http://validator.w3.org/check/referer" target="_blank" title="<?php echo JText::_("XHTML Validity");?>" style="text-decoration: none;">XHTML</a> &amp; <a href="http://jigsaw.w3.org/css-validator/check/referer" target="_blank" title="<?php echo JText::_("Validate this sites css");?>" style="text-decoration: none;">CSS</a> Valid.
			</div>
		</div>
	<?php if($wb_blockwraps == 1) : ?></div><?php endif; ?>
	
	<?php if($this->countModules('debug')) : ?>
	<div class="debug">
		<jdoc:include type="modules" name="debug" />
	</div>
	<?php endif; ?>	
	<script type="text/javascript" > 
		window.addEvent('domready', function() {			
			var myMenu = new MenuMatic({ 
				effect: 'slide',
				opacity: 100 
			});			
		});		
	</script> 
</body>
</html>