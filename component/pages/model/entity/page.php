<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Page Model Entity
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Pages
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
        $description = $this->component ? ucfirst($this->component) : ucfirst($query['component']);

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
            $site      = $this->getObject('object.bootstrapper')->getApplicationPath('site');

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

        $xml = simplexml_load_file($file);

        $params = new \JParameter($this->parameters, $file);
        $params->setParams($xml->xpath('state/params'));

        return $params;
    }

    protected function _getParamsUrl($xml)
    {
        $state = $xml->xpath('state/url');
        $params = new \JParameter();

        if ($state instanceof \SimpleXMLElement)
        {
            $params->setParams($state);

            if ($this->state) {
                $params->setData($this->getLink()->query);
            }
        }

        return $params;
    }

    protected function _getParamsLayout($xml)
    {
        $state  = $xml->xpath('state\params');
        $params = new \JParameter();

        if ($state instanceof \SimpleXMLElement)
        {
            $params->setParams($state);

            if ($this->state) {
                $params->setData($this->getLink()->query);
            }
        }

        return $params;
    }
}