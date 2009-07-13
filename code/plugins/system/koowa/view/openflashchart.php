<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_View
 * @subpackage  OpenFlashCahrt
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.koowa.org
 */

/**
 * View Open Flash Chart Class
 *
 * @author      Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_View
 * @subpackage  OpenFlashCahrt
 */

class KViewOpenflashchart extends KViewAbstract
{
    /**
     * KChartOpenflashchart object
     *
     * @var object
     */
    public $chart;

    public function __construct(array $options = array())
    {
        parent::__construct($options);

        //Set the correct mime type
		$this->_document->setMimeEncoding('text/plain');

        $this->chart = new KChartOpenflashchart();
    }

    public function display()
    {
        $this->loadTemplate();
		echo $this->chart->render();
    }
}