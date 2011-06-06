<?
    $article_slug  = $article->id.($article->slug ? ':'.$article->slug : '');
    $category_slug = $article->category_id.($article->category_slug ? ':'.$article->category_slug : '');

    if($article->access <= $user->get('aid', 0)) {
        $readmore_link = @route(ContentHelperRoute::getArticleRoute($article_slug, $category_slug, $article->section_id));
    } else {
        $readmore_link = @route('option=com_users&view=login');
    }
?>

<? if($article->state == 0) : ?>
    <div class="system-unpublished">
<? endif ?>

<? if($parameters->get('show_title')) : ?>
    <table class="contentpaneopen<?= @escape($parameters->get('pageclass_sfx')) ?>">
        <tr>
        <? if($parameters->get('show_title')) : ?>
        	<td class="contentheading<?= @escape($parameters->get('pageclass_sfx')) ?>" width="100%">
            <? if($parameters->get('link_titles') && $article->readmore_link != '') : ?>
                <a href="<?= $readmore_link ?>" class="contentpagetitle<?= @escape($parameters->get('pageclass_sfx')) ?>">
                    <?= @escape($article->title) ?>
                </a>
            <? else : ?>
                <?= @escape($article->title) ?>
            <? endif ?>
        	</td>
        <? endif ?>
        </tr>
    </table>
<? endif ?>

<table class="contentpaneopen<?= @escape($parameters->get('pageclass_sfx')) ?>">
<? if(($parameters->get('show_section') && $article->section_id) || ($parameters->get('show_category') && $article->category_id)) : ?>
    <tr>
    	<td>
    	<? if($parameters->get('show_section') && $article->section_id) : ?>
    		<span>
            <? if($parameters->get('link_section')) : ?>
                <a href="<?= @route(ContentHelperRoute::getSectionRoute($article->section_id)) ?>">
                    <?= @escape($article->section_name) ?>
                </a>
            <? else : ?>
                <?= @escape($article->section_name) ?>
            <? endif ?>
            <? if($parameters->get('show_category')) : ?>
                <?= ' - ' ?>
            <? endif ?>
    		</span>
    	<? endif ?>
    	<? if($parameters->get('show_category') && $article->category_id) : ?>
    		<span>
            <? if($parameters->get('link_category')) : ?>
                <a href="<?= @route(ContentHelperRoute::getCategoryRoute($category_slug, $article->section_id)) ?>">
                    <?= @escape($article->category) ?>
                </a>
            <? else : ?>
                <?= @escape($article->category) ?>
            <? endif ?>
    		</span>
    	<? endif ?>
    	</td>
    </tr>
<? endif ?>

<? if(($parameters->get('show_author')) && ($article->author != '')) : ?>
    <tr>
    	<td width="70%" valign="top" colspan="2">
    		<span class="small">
                <?= sprintf(@text('Written by'), @escape($article->author)) ?>
    		</span>
    	</td>
    </tr>
<? endif ?>

<? if($parameters->get('show_create_date')) : ?>
    <tr>
        <td valign="top" colspan="2" class="createdate">
            <?= @helper('date.format', array('date' => $article->created_on, 'format' => @text('DATE_FORMAT_LC2'))) ?>
        </td>
    </tr>
<? endif ?>

<tr>
    <td valign="top" colspan="2">
        <?= $article->introtext ?>
    </td>
</tr>

<? if($article->modified_by && $parameters->get('show_modify_date')) : ?>
    <tr>
    	<td colspan="2" class="modifydate">
            <?= sprintf(@text('LAST_UPDATED2'), @helper('date.format', array('date' => $article->modified_on, 'format' => @text('DATE_FORMAT_LC2')))) ?>
    	</td>
    </tr>
<? endif ?>

<? if($article->fulltext && $parameters->get('show_readmore')) : ?>
    <tr>
    	<td colspan="2">
            <a href="<?= $readmore_link ?>" class="readon<?= @escape($parameters->get('pageclass_sfx')) ?>">
            <? if($article->access <= $user->get('aid', 0)) : ?>
                <?= $parameters->get('readmore') ? $parameters->get('readmore') : @text('Read more...') ?>
            <? else : ?>
                <?= @text('Register to read more...') ?>
            <? endif ?>
            </a>
    	</td>
    </tr>
<? endif ?>
</table>

<? if($article->state == 0) : ?>
    </div>
<? endif ?>

<span class="article_separator"></span>