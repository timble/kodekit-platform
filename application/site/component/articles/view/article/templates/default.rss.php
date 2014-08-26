<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/">

    <channel>
        <title><?= escape($article->title) ?> </title>
        <description><![CDATA[<?= $article->introtext . $article->fulltext ?>]]></description>
        <link><?= helper('route.article', array('entity' => $article)) ?></link>
        <lastBuildDate><?= helper('date.format', array('format' => \DateTime::RSS)) ?></lastBuildDate>
        <generator>http://www.nooku.org?v=<?= \Nooku::VERSION ?></generator>
        <language><?= @object('translator')->getLocale(); ?></language>

        <dc:language><?= @object('translator')->getLocale(); ?></dc:language>
        <dc:rights>Copyright <?= helper('date.format', array('format' => '%Y')) ?></dc:rights>
        <dc:date><?= helper('date.format', array('format' => \DateTime::RSS)) ?></dc:date>

        <sy:updatePeriod><?= $update_period ?></sy:updatePeriod>
        <sy:updateFrequency><?= $update_frequency ?></sy:updateFrequency>

        <atom:link href="<?= helper('route.article', array('entity' => $article, 'format' => 'rss')) ?>" rel="self" type="application/rss+xml"/>

        <? foreach($comments as $comment) : ?>
            <item>
                <title><?= translate('Comment on').' '.escape($article->title).' '.translate('by').' '.$comment->getAuthor()->getName() ?></title>
                <link><?= helper('route.article', array('entity' => $article)) ?></link>
                <dc:creator><?= $comment->getAuthor()->getName() ?></dc:creator>
                <guid isPermaLink="false"><?= helper('route.article', array('entity' => $article)) ?></guid>
                <description><![CDATA[<?= $comment->text ?>]]></description>

                <pubDate><?= helper('date.format', array('date' => $comment->created_on, 'format' => \DateTime::RSS)) ?></pubDate>
                <dc:date><?= helper('date.format', array('date' => $comment->created_on, 'format' => \DateTime::RSS)) ?></dc:date>
            </item>
        <? endforeach; ?>
    </channel>
</rss>