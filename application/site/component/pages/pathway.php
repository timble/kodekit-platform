<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Pathway
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Pages
 */
class PagesPathway extends Library\ObjectArray implements Library\ObjectSingleton
{
    /**
     * Constructor
     *
     * @param Library\ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return Library\ObjectArray
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $pages = $this->getObject('application.pages');

        if($active = $pages->getActive())
        {
            $home = $pages->getHome();
            if($active->id != $home->id)
            {
                foreach(explode('/', $active->path) as $id)
                {
                    $page = $pages->getPage($id);
                    switch($page->type)
                    {
                        case 'pagelink':
                        case 'url' :
                            $url = $page->getLink();
                            break;

                        case 'separator':
                            $url = null;
                            break;

                        default:
                            $url = $page->getLink();
                            $url->query['Itemid'] = $page->id;
                            $this->getObject('application')->getRouter()->build($url);
                            break;
                    }

                    $this->addItem($page->title, $url);
                }
            }
        }
    }

    /**
     * Add item to pathway
     *
     * @param string $name
     * @param string $link
     * @return PagesPathway
     */
    public function addItem($name, $link = null)
    {
        $item = new \stdClass();
        $item->name = html_entity_decode($name, ENT_COMPAT, 'UTF-8');
        $item->link = $link;

        $this[] = $link;

        return $this;
    }
}