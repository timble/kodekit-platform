<?
/**
 * @package        Nooku_Server
 * @subpackage     Search
 * @copyright      Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
?>

<? if ($item_id): ?>
<form action="<?=@route('option=com_articles&view=articles&Itemid=' . $item_id)?>" method="get" class="<?=$form_class?>">
    <div class="input-append">
        <input id="searchword" name="searchword" class="<?=$input_class?>" type="text" value=""
               placeholder="<?=@text($placeholder)?>"/>
        <button type="submit" class="<?=$button_class?>"><i class="icon-search"></i></button>
    </div>
</form>
<? endif; ?>