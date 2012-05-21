<?php

/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Positions Model Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 */
class ComExtensionsModelPositions extends KModelAbstract
{
    public function getList()
    {
        if (!$this->_list)
        {
            $templates = $this->getService('com://admin/extensions.model.templates')->getList();
          
            $positions = array();
            foreach ($templates as $template)
            {
                $path = $template->path;
                $templateDetails = $path . '/templateDetails.xml';
                
                if (file_exists($templateDetails))
                {
                    $xml = simplexml_load_file($templateDetails);
                    if (isset($xml->positions))
                    {
                        foreach ($xml->positions->children() as $position)
                        {
                            $position = (string)$position;
                            $positions[$position] = array(
                                'position' => $position
                            );
                        }
                    }
                } 
            }
            asort($positions);
            
            $this->_list = $this->getService('com://admin/extensions.database.rowset.positions')
                                ->addData($positions);

        }

        return $this->_list;
    }

}