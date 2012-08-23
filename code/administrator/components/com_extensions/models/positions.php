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
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('application', 'cmd', 'site');        
    }
    
    public function getList() 
    {
        if (!$this->_list) 
        {
            $state = $this->getState();
            
            $template   = $this->getService('com://admin/extensions.model.templates')->application($state->application)->default(1)->getItem();
          
            $positions = array();
            
            $templateDetails = $template->path . '/templateDetails.xml';
            
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
            
            
            asort($positions);
            
            $this->_list = $this->getService('com://admin/extensions.database.rowset.positions')
                    ->addData($positions);

        }

        return $this->_list;
    }

}