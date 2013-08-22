<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
?>

<?='<?xml version="1.0" encoding="utf-8" ?>' ?>

<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/">

    <channel>

        <title><?= escape($category->title) ?></title>
        <description><![CDATA[<?= escape($category->description) ?>]]></description>
        <link><?= route() ?></link>
        <lastBuildDate><?= helper('date.format') ?></lastBuildDate>
        <generator>http://www.nooku.org?v=<?= \Nooku::VERSION ?></generator>
        <language><?= JFactory::getLanguage()->getTag() ?></language>

        <dc:language><?= JFactory::getLanguage()->getTag() ?></dc:language>
        <dc:rights>Copyright <?= helper('date.format', array('format' => '%Y')) ?></dc:rights>
        <dc:date><?= helper('date.format') ?></dc:date>

        <sy:updatePeriod><?= $update_period ?></sy:updatePeriod>
        <sy:updateFrequency><?= $update_frequency ?></sy:updateFrequency>

        <atom:link href="<?= route() ?>" rel="self" type="application/rss+xml"/>

        <? if($category->image) : ?>
        <image>
            <url><?= object('request')->getUrl()->setPath(str_replace(JPATH_ROOT.DS, '', $category->image->path))->toString(Library\HttpUrl::BASE) ?></url>
            <title><?= escape($category->title) ?></title>
            <link><?= route() ?></link>
            <width><?= $category->image->width ?></width>
            <height><?= $category->image->height ?></height>
        </image>
        <? endif; ?>

        <? foreach($articles as $article) : ?>
        <item>
            <title><?= escape($article->title) ?></title>
            <link><?= helper('route.article', array('row' => $article)) ?> ?></link>
            <dc:creator><?= $article->created_by_name ?></dc:creator>
            <guid isPermaLink="false"><?= helper('route.article', array('row' => $article)) ?> ?></guid>
            <description><![CDATA[<?= $article->introtext . $article->fulltext ?>]]></description>
            <category domain="<?= helper('route.category', array('row' => $category)) ?>">
                <![CDATA[<? $article->category_title ?>]]>
            </category>
            <pubDate><?= helper('date.format', array('date' => $article->published_on)) ?></pubDate>
            <dc:date><?= helper('date.format', array('date' => $article->published_on)) ?></dc:date>
        </item>
        <? endforeach; ?>

    </channel>

</rss>
