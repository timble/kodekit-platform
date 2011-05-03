<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>
		
<? if (version_compare(JVERSION,'1.6.0','ge')) : ?>
<div class="toolbar-list" id="toolbar-<?= $toolbar->getName(); ?>">
<? else : ?>
<div class="toolbar" id="toolbar-<?= $toolbar->getName(); ?>">
<? endif; ?>

<table class="toolbar">
	<tr>
	<? foreach ($toolbar->getButtons() as $button) : ?>
		
		<?
            $link   = $button->getLink();
            $href   = !empty($link) ? 'href="'.JRoute::_($link).'"' : '';

            $onclick =  $button->getOnClick();
            $onclick = !empty($onclick) ? 'onclick="'. $onclick.'"' : '';
        ?>
        
        <? if($button->getName() == 'divider') : ?>
        	</tr></table><table class="toolbar"><tr><td class="divider"></td></tr></table><table class="toolbar"><tr>
    	<? else : ?>
			<td class="button" id="<?= $button->getId() ?>">
       			<a <?= $href.' '.$onclick ?> class="toolbar">
        			<span class="<?= $button->getClass() ?>" title="<?= @text($button->getText()); ?>"></span>
       	            <?= @text($button->getText()); ?>
       		 	</a>
        	</td>
       	<? endif; ?>
	<? endforeach; ?>
	</tr>
</table>
</div>