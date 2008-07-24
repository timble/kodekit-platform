<?php
/**
 * @version     $Id$
 * @package     Koowa_Chart
 * @subpackage  Sparkline
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

Koowa::import('koowa.chart.renderer.sparkline.Sparkline_Bar');

/**
 * Sparkline Bar
 *
 * @author      Mathias Verraes <mathias@joomlatools.org>
 * @package     Koowa_Chart
 * @subpackage  Sparkline
 * @version     1.0
 */
class KChartSparklineBar extends KChartSparkline
{
    /**
     * Get an instance of a SparklineBar object, always creating it
     *
     * @param	array	Configuration array
     * @return	object	KChartSparklineBar proxy object
     */
    static public function getInstance($config = array())
    {
        if(!isset($config['catch_errors']))
        {
            $config['catch_errors'] = true;
        }

        $obj = new Sparkline_Bar($config['catch_errors']);
        $obj->SetDebugLevel(DEBUG_NONE);
        return new KChartSparklineBar($obj);
    }

    /**
     * Renders the sparkline. $width is ignored and calculated automatically
     *
     * @param   int width	(ignored)
     * @param   int height
     */
    public function render($width, $height)
    {
        $c = $this->getObject();
        $c->render($height);
        if($c->isError())
        {
        	JError::raiseError(500, array_pop($c->getError()));
            return false;
        }
        $c->output();
        KFactory::get('Application')->close();
    }
}