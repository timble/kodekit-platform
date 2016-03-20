<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

use Kodekit\Library; ?>


<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/">

    <channel>

        <title><?= escape($category->title) ?></title>
        <description><![CDATA[<?= escape($category->description) ?>]]></description>
        <link><?= route() ?></link>
        <lastBuildDate><?= helper('date.format', array('format' => \DateTime::RSS)) ?></lastBuildDate>
        <generator>Kodekit?v=<?= \Kodekit::VERSION ?></generator>
        <language><?= @object('translator')->getLanguage(); ?></language>

        <dc:language><?= @object('translator')->getLanguage(); ?></dc:language>
        <dc:rights>Copyright <?= helper('date.format', array('format' => '%Y')) ?></dc:rights>
        <dc:date><?= helper('date.format', array('format' => \DateTime::RSS)) ?></dc:date>

        <sy:updatePeriod><?= $update_period ?></sy:updatePeriod>
        <sy:updateFrequency><?= $update_frequency ?></sy:updateFrequency>

        <atom:link href="<?= route() ?>" rel="self" type="application/rss+xml"/>

        <? if($category->image) : ?>
        <image>
            <url><?= object('request')->getUrl()->setPath(str_replace(APPLICATION_ROOT.DS, '', $category->image->path))->toString(Library\HttpUrl::BASE) ?></url>
            <title><?= escape($category->title) ?></title>
            <link><?= route() ?></link>
            <width><?= $category->image->width ?></width>
            <height><?= $category->image->height ?></height>
        </image>
        <? endif; ?>

        <? foreach($articles as $article) : ?>
        <item>
            <title><?= escape($article->title) ?></title>
            <link><?= helper('route.article', array('entity' => $article)) ?></link>
            <dc:creator><?= $article->getAuthor()->getName() ?></dc:creator>
            <guid isPermaLink="false"><?= helper('route.article', array('entity' => $article)) ?> ?></guid>
            <description><![CDATA[<?= $article->introtext . $article->fulltext ?>]]></description>
            <category domain="<?= helper('route.category', array('entity' => $category)) ?>">
                <![CDATA[<? $article->category_title ?>]]>
            </category>
            <pubDate><?= helper('date.format', array('date' => $article->published_on, 'format' => \DateTime::RSS)) ?></pubDate>
            <dc:date><?= helper('date.format', array('date' => $article->published_on, 'format' => \DateTime::RSS)) ?></dc:date>
        </item>
        <? endforeach; ?>

    </channel>

</rss>
