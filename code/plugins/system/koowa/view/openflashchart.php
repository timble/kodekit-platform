<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_View
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * View Open Flash Chart Class
 *
 * @author      Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_View
 */
class KViewOpenflashchart extends KViewAbstract
{
    /**
     * KChartOpenflashchart object
     *
     * @var object
     */
    public $chart;

    /**
	 * Constructor
	 *
	 * @param	array An optional associative array of configuration settings.
	 */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        //Set the correct mime type
		$this->_document->setMimeEncoding('text/plain');

        $this->chart = new KChartOpenflashchart();
    }

    /**
	 * Renders and echo's the views output
 	 *
	 * @return KViewOpenflashchart
	 */
    public function display()
    {
    	$this->loadTemplate();
		echo $this->chart->render();
		
		return $this;
    }
}