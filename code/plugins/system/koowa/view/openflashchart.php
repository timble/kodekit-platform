<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_View
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
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
class KViewOpenflashchart extends KViewTemplate
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
	 * @param 	object 	An optional KConfig object with configuration options
	 */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

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