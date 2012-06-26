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
 * Article Template Helper Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesTemplateHelperArticle extends KTemplateHelperDefault
{
    public function render($config = array())
    {
        $config = new KConfig($config);

        $config->append(array('parameters' => JComponentHelper::getParams('com_articles')));

        $parameters = $config->parameters;
        $html       = '';

        $html .= $this->title($config);
        $html .= $this->timestamp($config);
        $html .= $this->content($config);

        return $html;
    }

    public function title($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'parameters'    => JComponentHelper::getParams('com_articles'),
            'title_heading' => 2,
             'linkable'     => true,
        ));

        $article = $config->row;

        $html = "<h{$config->title_heading}>";
        $html .= $config->linkable ? $this->link(array_merge($config->toArray(), array('text' => $article->title))) : $article->title;
        $html .= "</h{$config->title_heading}>";

        return $html;
    }

    public function content($config = array())
    {
        $config = new KConfig($config);

        $config->append(array('parameters' => JComponentHelper::getParams('com_articles')))
               ->append(array('show_images' => true, 'show_more' => $config->parameters->get('show_readmore')));

        $article = $config->row;

        $html = '';

        // Only show more links if there is actually something else to show.
        if ($config->show_more && $article->fulltext)
        {
            $html .= $article->introtext;
            $html .= $this->link(array('row' => $article));
        }
        else $html .= $article->introtext . $article->fulltext;

        // Strip images from content.
        if (!$config->show_images) {
            $html = preg_replace('/<img[^>]*>/', '', $html);
        }

        return $html;
    }

    public function link($config = array())
    {
        $config = new KConfig($config);

        $config->append(array(
            'attribs'   => array(),
            'text'      => 'Read more',
            'translate' => true));

        $article = $config->row;
        $text    = $config->text;

        if ($config->tanslate) {
            $text = JText::_($text);
        }

        $attribs = KHelperArray::toString($config->attribs);

        $route = $this->getService('com://site/articles.helper.route')
                      ->getArticleRoute($article->id, $article->category_id,$article->section_id);

        $html = '';
        $html .= '<a ' . $attribs . ' href="' . JRoute::_($route) . '">';
        $html .= $text;
        $html .= '</a>';

        return $html;
    }

    public function edit($config = array())
    {
        $config = new KConfig($config);

        $article = $config->row;

        $html = '';

        if ($article->editable)
        {
            $route = $this->getService('com://site/articles.helper.route')
                           ->getArticleRoute($article->id, $article->category_id,$article->section_id);

            $html .= '<div class="edit-article">';
            $html .= '<a href="' . JRoute::_($route.'&layout=form') . '">';
            $html .= JText::_('Edit');
            $html .= '</a>';
            $html .= '</div>';
        }

        return $html;
    }

    public function timestamp($config = array())
    {
        $config = new KConfig($config);

        $config->append(array('parameters' => JComponentHelper::getParams('com_articles')))
               ->append(array(
                    'show_author'      => false,
                    'show_create_date' => $config->parameters->get('show_create_date'),
                    'show_modify_date' => $config->parameters->get('show_modify_date')
                ));

        $article = $config->row;

        $html = array();

        if ($config->show_author && ($author = $article->getAuthor())) {
            $html[] = JText::sprintf('Written by', $author);
        }

        if ($config->show_create_date)
        {
            $html[] = $this->getService('koowa:template.helper.date')->format(array(
                'date'   => $article->created_on,
                'format' => JText::_('DATE_FORMAT_LC2')));
        }

        if ($config->get('show_modify_date') && ($modified_on = $article->modified_on) && (intval($modified_on) != 0))
        {
            $html[] = JText::sprintf('LAST_UPDATED2',
                $this->getService('koowa:template.helper.date')->format(array(
                    'date'   => $article->modified_on,
                    'format' => JText::_('DATE_FORMAT_LC2'))));
        }

        if (!empty($html)) {
            $html = '<p class="timestamp">' . implode(' ', $html) . '</p>';
        } else {
            $html = '';
        }

        return $html;
    }
}