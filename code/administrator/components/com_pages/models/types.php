<?php
/**
 * @version     $Id: types.php 3031 2011-10-09 14:21:07Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Types Model Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class ComPagesModelTypes extends KModelAbstract
{
    protected $_list;

    public function getList()
    {
        if(!isset($this->_list))
        {
            $table = $this->getService('com://admin/extensions.database.table.components');
            $query = $this->getService('koowa:database.query.select')
                ->where('link <> :link')
                ->where('parent = :parent')
                ->order('name')
                ->bind(array('link' => '', 'parent' => 0));

            $components = $table->select($query);

            // Iterate through the components.
            foreach($components as $component)
            {
                $path = JPATH_ROOT.'/site/components/'.$component->option.'/views';

                if(!is_dir($path)) {
                    continue;
                }

                // Iterator through the views.
                $views = array();
                foreach(new DirectoryIterator($path) as $view)
                {
                    $xml_path = $path.'/'.$view.'/metadata.xml';
                    if(!$view->isDir() || substr($view, 0, 1) == '.' || !file_exists($xml_path)) {
                        continue;
                    }

                    $xml_view = simplexml_load_file($xml_path);
                    if(strtolower($xml_view->view->attributes()->hidden) !== 'true')
                    {
                        // Iterate through the layouts.
                        $layouts = array();
                        foreach(new DirectoryIterator($path.'/'.$view.'/tmpl') as $layout)
                        {
                            if(!$layout->isFile() || substr($layout, 0, 1) == '.' || $layout->getExtension() != 'xml') {
                                continue;
                            }

                            $xml_layout = simplexml_load_file($path.'/'.$view.'/tmpl/'.$layout);
                            if(!$xml_layout->layout) {
                                continue;
                            }

                            if(strtolower($xml_layout->layout->attributes()->hidden) !== 'true')
                            {
                                $layouts[$layout->getBasename('.xml')] = (object) array(
                                    'name' => $layout->getBasename('.xml'),
                                    'title' => trim($xml_layout->layout->attributes()->title),
                                    'description' => trim($xml_layout->layout->message)
                                );
                            }
                        }

                        $views[$view->getFilename()] = (object) array(
                            'name' => $view->getFilename(),
                            'title' => trim($xml_view->view->attributes()->title),
                            'layouts' => $layouts
                        );
                    }
                }

                $component->views = $views;
            }

            $this->_list = $components;
        }

        return $this->_list;
    }
}
