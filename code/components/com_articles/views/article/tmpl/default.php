<? if($parameters->get('show_page_title') && $parameters->get('page_title') != $article->title) : ?>
	<div class="componentheading<?= @escape($parameters->get('pageclass_sfx')) ?>">
		<?= @escape($parameters->get('page_title')) ?>
	</div>
<? endif ?>

<? if($parameters->get('show_title')) : ?>
    <table class="contentpaneopen<?= @escape($parameters->get('pageclass_sfx')) ?>">
        <tr>
        <? if($parameters->get('show_title')) : ?>
            <td class="contentheading<?= @escape($parameters->get('pageclass_sfx')) ?>" width="100%">
                <? if($parameters->get('link_titles') && $article->readmore_link != '') : ?>
                    <a href="<?= $article->readmore_link ?>" class="contentpagetitle<?= @escape($parameters->get('pageclass_sfx')) ?>">
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
<? if(($parameters->get('show_section') && $article->sectionid) || ($parameters->get('show_category') && $article->catid)) : ?>
    <tr>
        <td>
        <? if($parameters->get('show_section') && $article->section_id) : ?>
            <span>
            <? if($parameters->get('link_section')) : ?>
                <a href="<?= @route(ContentHelperRoute::getSectionRoute($article->section_id)) ?>">
                    <?= @escape($article->section) ?>
                </a>
            <? else : ?>
                <?= @escape($article->section) ?>
            <? endif ?>
            <? if($parameters->get('show_category')) : ?>
                <?= ' - ' ?>
            <? endif ?>
            </span>
        <? endif ?>
        <? if($parameters->get('show_category') && $article->category_id) : ?>
            <span>
            <? if($parameters->get('link_category')) : ?>
                <a href="<?= @route(ContentHelperRoute::getCategoryRoute($article->catslug, $article->sectionid)) ?>">
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

<? if(($parameters->get('show_author')) && $article->author) : ?>
    <tr>
        <td valign="top">
            <span class="small">
                <?= sprintf(@text('Written by'), $article->author) ?>
            </span>
        </td>
    </tr>
<? endif ?>

<? if($parameters->get('show_create_date')) : ?>
    <tr>
        <td valign="top" class="createdate">
            <?= @helper('date.format', array('date' => $article->created_on, 'format' => @text('DATE_FORMAT_LC2'))) ?>
        </td>
    </tr>
<? endif ?>

    <tr>
        <td valign="top">
            <?= $article->introtext.$article->fulltext ?>
        </td>
    </tr>

<? if($article->modified_by && $parameters->get('show_modify_date')) : ?>
    <tr>
        <td class="modifydate">
            <?= sprintf(@text('LAST_UPDATED2'), @helper('date.format', array('date' => $article->modified_on, 'format' => @text('DATE_FORMAT_LC2')))) ?>
        </td>
    </tr>
<? endif ?>
</table>
<span class="article_separator"></span>