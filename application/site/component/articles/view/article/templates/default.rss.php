<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/">

    <channel>
        <title><?= escape($article->title) ?> </title>
        <description><![CDATA[<?= $article->introtext . $article->fulltext ?>]]></description>
        <link><?= helper('route.article', array('row' => $article)) ?></link>
        <lastBuildDate><?= helper('date.format') ?></lastBuildDate>
        <generator>http://www.nooku.org?v=<?= \Nooku::VERSION ?></generator>
        <language><?= JFactory::getLanguage()->getTag() ?></language>

        <dc:language><?= JFactory::getLanguage()->getTag() ?></dc:language>
        <dc:rights>Copyright <?= helper('date.format', array('format' => '%Y')) ?></dc:rights>
        <dc:date><?= helper('date.format') ?></dc:date>

        <sy:updatePeriod><?= $update_period ?></sy:updatePeriod>
        <sy:updateFrequency><?= $update_frequency ?></sy:updateFrequency>

        <atom:link href="<?= helper('route.article', array('row' => $article, 'format' => 'rss')) ?>" rel="self" type="application/rss+xml"/>

        <? foreach($comments as $comment) : ?>
            <item>
                <title><?= translate('Comment on').' '.escape($article->title).' '.translate('by').' '.$comment->created_by_name ?></title>
                <link><?= helper('route.article', array('row' => $article)) ?></link>
                <dc:creator><?= $comment->created_by_name ?></dc:creator>
                <guid isPermaLink="false"><?= helper('route.article', array('row' => $article)) ?></guid>
                <description><![CDATA[<?= $comment->text ?>]]></description>

                <pubDate><?= helper('date.format', array('date' => $comment->created_on)) ?></pubDate>
                <dc:date><?= helper('date.format', array('date' => $comment->created_on)) ?></dc:date>
            </item>
        <? endforeach; ?>
    </channel>
</rss>