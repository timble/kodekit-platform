<?php
/**
 * @version     $Id$
 * @package     Koowa_View
 * @subpackage  OpenFlashCahrt
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * View Open Flash Chart Class
 *
 * @author      Mathias Verraes <mathias@joomlatools.org>
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

    public function __construct($options = array())
    {
        parent::__construct($options);

        //Set the correct mime type
		$this->document->setMimeEncoding('text/plain');

        $this->chart = new KChartOpenflashchart();
    }

    public function display($tpl = null)
    {
        $result = $this->loadTemplate($tpl);
		if (JError::isError($result)) {
			return $result;
		}

		echo $this->chart->render();
    }
}