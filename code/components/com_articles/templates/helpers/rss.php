<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * RSS Template Helper Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesTemplateHelperRss extends KTemplateHelperDefault
{
    /**
     * @var array Maps data columns to feed item elements names.
     */
    protected $_column_map;

    /**
     * @var array Associative array of stylesheets containing the source (key) and their type (value).
     */
    protected $_stylesheets;

    public function __construct(KConfig $config) {
        parent::__construct($config);

        $this->_column_map = $config->column_map;

        $this->_stylesheets = $config->stylesheets;
    }

    protected function _initialize(KConfig $config) {
        $config->append(array(
            'stylesheets' => array(),
            'column_map'  => array(
                'author'      => 'created_by',
                'pubDate'     => 'created_on')));
        parent::_initialize($config);
    }

    /**
     * RSS feed link getter.
     *
     * @param array $config An optional configuration array.
     *
     * @return string The link HTML code for accessing the feed.
     */
    public function link($config = array()) {
        $config = new KConfig($config);

        $config->append(array('class' => array('articles-feed'), 'text' => 'Feed entries', 'format' => 'rss'));

        if (!$config->url) {
            $view        = $this->getTemplate()->getView();
            $name        = $view->getName();
            $config->url = 'index.php?option=com_articles&view=' . $name;
            if (KInflector::isSingular($name)) {
                // Append unique states.
                $state = $view->getModel()->getState()->getData(true);
                foreach ($state as $col => $val) {
                    $config->url .= "&{$col}={$val}";
                }
            }
        }

        $url = $this->getService('koowa:http.url', array('url' => $config->url));

        $query           = $url->getQuery(true);
        $query['format'] = $config->format;

        $url->setQuery($query);

        $html = '<div class="' . implode(' ', KConfig::unbox($config->class)) . '">';
        $html .= '<a title="' . JText::_($config->text) . '" href="' . JRoute::_($url) . '"></a>';
        $html .= '</div>';
        $html .= '<div class="clear_both"></div>';

        return $html;
    }

    /**
     * Channel image getter.
     *
     * @param array $config An optional configuration array.
     *
     * @return string The image portion of the feed.
     */
    protected function _getImage($config = array()) {
        $config = new KConfig($config);

        $output = '';

        if ($image = $config->channel->image) {
            $image = json_decode($image);

            $output .= "        <image>\n";
            $output .= "            <url>" . $image->url . "</url>\n";
            $output .= "            <title>" . $this->_escape($image->title) . "</title>\n";
            $output .= "            <link>" . $image->link . "</link>\n";
            $output .= $image->width ? "            <width>" . $image->width . "</width>\n" : '';
            $output .= $image->height ? "            <height>" . $image->height . "</height>\n" : '';
            $output .= $image->description ? "            <description><![CDATA[" . $image->description . "]]></description>\n" : '';
            $output .= "        </image>\n";
        }
        return $output;
    }

    /**
     * Item column mapper.
     *
     * Maps the provided data column values to the corresponding item feed elements.
     *
     * @param array $data The data.
     *
     * @return array The mapped data.
     */
    protected function _mapColumns($data) {

        // Keep a copy of the original data.
        $result = $data;

        foreach ($this->_column_map as $el => $col) {

            if (is_null($col)) {
                // A null column forces not to use the current element. This is needed if data contains
                // a column with the same name as the element, the column points a value that is not
                // related to element and we do not want the element on the feed output.
                $result[$el] = null;
                continue;
            }

            if (isset($data[$col])) {
                $result[$el] = $data[$col];
            }
        }
        return $result;
    }

    /**
     * Items getter.
     *
     * @param array $config An optional configuration array.
     *
     * @return string The feed items output.
     * @throws KTemplateHelperException If the provided data is not RSS 2.0 valid.
     */
    protected function _getItems($config = array()) {
        $config = new KConfig($config);

        $config->append(array('channel'=> array('items' => array())));

        $output = '';

        foreach ($config->channel->items as $item) {

            if (!$item instanceof KDatabaseRowInterface) {
                throw new KTemplateHelperException('Feed item is not a KDatabaseRowInterface object');
            }

            $data = $this->_mapColumns($item->getData());

            // At least title or description must be present
            // (http://www.rssboard.org/rss-specification#hrelementsOfLtitemgt)
            if (!isset($data['title']) || !isset($data['description'])) {
                throw new KTemplateHelperException('Feed item must either contain a title or description. None is present.');
            }

            $output .= "        <item>\n";
            $output .= isset($data['title']) ? "            <title>" . $this->_escape($data['title']) . "</title>\n" : '';
            $output .= isset($data['link']) ? "            <link>" . $data['link'] . "</link>\n" : '';
            $output .= isset($data['guid']) ? "            <guid>" . $data['guid'] . "</guid>\n" : '';
            $output .= isset($data['description']) ? "            <description><![CDATA[" . $this->_absolutize($data['description']) . "]]></description>\n" : '';

            if (isset($data['author'])) {
                $author = $data['author'];
                if (is_numeric($author)) {
                    $author = $this->_getAuthor($author);
                }
                $output .= isset($author) ? "            <author>" . $this->_escape($author) . "</author>\n" : '';
            }

            $output .= isset($data['source']) ? "            <source>" . $this->_escape($data['source']) . "</source>\n" : '';
            $output .= isset($data['category']) ? "            <category>" . $this->_escape($data['category']) . "</category>\n" : '';
            $output .= isset($data['comments']) ? "            <comments>" . $this->_escape($data['comments']) . "</comments>\n" : '';
            if (isset($data['pubDate'])) {
                $pub_date = new DateTime($data['pubDate']);
                $output .= "            <pubDate>" . $pub_date->format(DateTime::RFC822) . "</pubDate>\n";
            }

            if (isset($data['enclosure'])) {
                $enclosure = json_decode($data['enclosure']);
                // Enclosure elements have 3 mandatory attributes.
                if (!isset($enclosure->url) || !isset($enclosure->length) || isset($enclosure->type)) {
                    throw new KTemplateHelperException('Url, length and type attributes are mandatory on enclosure elements. At least one is missing.');
                }
                $output .= "            <enclosure url=\"";
                $output .= $enclosure->url;
                $output .= "\" length=\"";
                $output .= $enclosure->length;
                $output .= "\" type=\"";
                $output .= $enclosure->type;
                $output .= "\"/>\n";
            }

            $output .= "        </item>\n";
        }

        return $output;
    }

    /**
     * Author name getter.
     *
     * @param int $id The author user id.
     *
     * @return null|string Null if user with provided id isn't found. The user's full name otherwise.
     */
    protected function _getAuthor($id) {
        $author = null;
        $user   = JFactory::getUser($id);
        if ($user->id) {
            $author = ucfirst($user->name);
        }
        return $author;
    }

    /**
     * RSS feed renderer.
     *
     * @param array $config An optional configuration array.
     *
     * @return string The feed output string.
     */
    public function feed($config = array()) {

        $config = new KConfig($config);

        $document = JFactory::getDocument();

        $config->append(array('channel' => array('generator' => $document->getGenerator())));

        $config->append(array('stylesheets' => array()));

        $output = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $output .= "<!-- generator=\"" . $config->channel->generator . "\" -->\n";

        // Generate stylesheet links
        foreach ($this->_stylesheets as $src => $type) {
            $output .= "<?xml-stylesheet href=\"$src\" type=\"" . $type . "\"?>\n";
        }

        $output .= "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";

        $output .= $this->_getChannel($config);

        $output .= "</rss>\n";

        return $output;
    }

    protected function _getChannel($config = array()) {
        $config = new KConfig($config);

        $document = JFactory::getDocument();

        $config->append(array(
            'channel' => array(
                'title'       => $document->title,
                'description' => $document->description,
                'link'        => KRequest::url())));

        $channel = $config->channel;


        $output = "    <channel>\n";
        $output .= "        <title>" . $this->_escape($channel->title) . "</title>\n";
        $output .= "        <description>" . $this->_escape($channel->description) . "</description>\n";
        $output .= "        <link>" . $channel->link . "</link>\n";
        $current_date = new DateTime();
        $output .= "        <lastBuildDate>" . $current_date->format(DateTime::RFC822) . "</lastBuildDate>\n";
        $output .= "        <generator>" . $this->_escape($channel->generator) . "</generator>\n";

        $output .= $this->_getImage($config);

        $output .= $channel->language ? "        <language>" . $this->_escape($channel->language) . "</language>\n" : '';
        $output .= $channel->copyright ? "        <copyright>" . $this->_escape($channel->copyright) . "</copyright>\n" : '';
        $output .= $channel->editor_mail ? "        <managingEditor>" . $this->_escape($channel->editor_mail) . "</managingEditor>\n" : '';
        $output .= $channel->webmaster ? "        <webMaster>" . $this->_escape($config->webmaster) . "</webMaster>\n" : '';
        if ($channel->pub_date) {
            $pub_date = new DateTime($channel->pub_date);
            $output .= "        <pubDate>" . $pub_date->fomat(DateTime::RFC822) . "</pubDate>\n";
        }
        $output .= $channel->category ? "        <category>" . $this->_escape($channel->category) . "</category>\n" : '';
        $output .= $channel->docs ? "        <docs>" . $this->_escape($channel->docs) . "</docs>\n" : '';
        $output .= $channel->ttl ? "        <ttl>" . $this->_escape($channel->ttl) . "</ttl>\n" : '';
        $output .= $channel->rating ? "        <rating>" . $this->_escape($channel->rating) . "</rating>\n" : '';
        $output .= $channel->skip_hours ? "        <skipHours>" . $this->_escape($channel->skip_hours) . "</skipHours>\n" : '';
        $output .= $channel->skip_days ? "        <skipDays>" . $this->_escape($channel->skip_days) . "</skipDays>\n" : '';

        $output .= $this->_getItems($config);

        $output .= "    </channel>\n";

        return $output;
    }

    /**
     * Convert links in a text from relative to absolute.
     *
     * @param string The text.
     *
     * @return string The text with absolute links.
     */
    protected
    function _absolutize($text) {
        $root = KRequest::root();
        return preg_replace("/(href|src)=\"(?!http|ftp|https|mailto)([^\"]*)\"/", "$1=\"$root\$2\"", $text);
    }

    /**
     * Method for escaping text.
     *
     * @param string $text The text to escape.
     *
     * @return string Escaped text
     */
    protected
    function _escape($text) {
        return htmlspecialchars($text, ENT_QUOTES);
    }
}