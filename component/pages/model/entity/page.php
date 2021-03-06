<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Page Model Entity
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Pages
 */
class ModelEntityPage extends Library\ModelEntityRow
{
    /**
     * Saves the row to the database.
     *
     * This performs an intelligent insert/update and reloads the properties with fresh data from the table on success.
     *
     * @return boolean If successful return TRUE, otherwise FALSE
     */
    public function save()
    {
        if ($this->isModified('state'))
        {
            // Set link.
            parse_str($this->state, $query);

            if ($this->urlparams) {
                $query += $this->urlparams;
            }

            $this->state     = http_build_query($query);
            $this->component = $query['component'];
        }

        // Set default.
        if ($this->isModified('default') && $this->default == 1)
        {
            $page = $this->getObject('com:pages.database.table.pages')
                ->select(array('default' => 1), Library\Database::FETCH_ROW);

            $page->default = 0;
            $page->save();
        }

        // Update child pages if menu has been changed.
        if ($this->isModified('pages_menu_id'))
        {
            $descendants = $this->getDescendants();

            foreach ($descendants as $descendant) {
                $descendant->setProperties(array('pages_menu_id' => $this->pages_menu_id))->save();
            }
        }


        return parent::save();
    }

    public function getDescription()
    {
        $link        = $this->getLink();
        $query       = $link  ? $link->getQuery(true) : array();
        $description = ucfirst($this->component);
        $translator = $this->getObject('translator');

        if(isset($query['view'])) {
            $description .= ' &raquo; '. $translator(ucfirst($query['view']));
        }

        if(isset($query['layout'])) {
            $description .= ' / '. $translator(ucfirst($query['layout']));
        }

        return $description;
    }

    public function getLink()
    {
        $link = null;
        if($this->state)
        {
            $link = $this->getObject('lib:http.url', array('url' => '?' . $this->state));
            $link->query['Itemid']    = $this->id;
            $link->query['component'] = $this->component;
        }

        return $link;
    }

    public function getPropertyView()
    {
        $view = '';
        $link = $this->getLink();
        if($link && isset($link->query['view'])) {
            $view = $link->query['view'];
        }

        return $view;
    }

    public function getPropertyLayout()
    {
        $layout = '';
        $link   = $this->getLink();
        if($link && isset($link->query['layout'])) {
            $layout = $link->query['layout'];
        }

        return $layout;
    }

    public function getPropertyRoute()
    {
        $path = array();
        foreach(explode('/', $this->path) as $id) {
            $path[] = $this->getObject('pages')->find($id)->slug;
        }

        return implode('/', $path);
    }

    public function getParams($group)
    {
        $result = null;

        if($link = $this->getLink())
        {
            $query = $link->getQuery(true);

            $component = $query['component'];
            $view      = $query['view'];
            $layout    = isset($query['layout']) ? $query['layout'] : 'default';
            $site      = \Kodekit::getInstance()->getRootPath().'/application/site/component';

            $path = $site . '/' . $component . '/view/' . $view . '/templates/' . $layout . '.xml';

            if (file_exists($path))
            {
                $xml = simplexml_load_file($path);
                $result = $this->{'_getParams' . ucfirst($group)}($xml);
            }
        }

        return $result;
    }

    protected function _getParamsPage($xml)
    {
        $file = __DIR__ . '/page.xml';
        $xml   = simplexml_load_file($file);
        $params = new \JParameter($this->parameters, $file);

        if ($state = $xml->xpath('state/params')) {
            $params->setParams($state[0]);
        }

        return $params;
    }

    protected function _getParamsUrl($xml)
    {
        $params = new \JParameter();

        if ($state = $xml->xpath('state/url'))
        {
            $params->setParams($state[0]);

            if ($this->state) {
                $params->setData($this->getLink()->query);
            }
        }

        return $params;
    }

    protected function _getParamsLayout($xml)
    {
        $params = new \JParameter();

        if ($state = $xml->xpath('state/params'))
        {
            $params->setParams($state[0]);

            if ($this->state) {
                $params->setData($this->getLink()->query);
            }
        }

        return $params;
    }
}