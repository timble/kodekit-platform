<?php
/**
 * @version     $Id:openflashchart.php 137 2007-11-20 18:32:09Z mjaz $
 * @category	Koowa
 * @package     Koowa_Chart
 * @subpackage  OpenFlashChart
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

Koowa::import('lib.koowa.chart.renderer.open-flash-chart.open-flash-chart');

/**
 * Chart Data class with Open Flash Chart
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Chart
 * @subpackage  OpenFlashChart
 * @uses KObject
 */
class KChartOpenflashchart extends KObject
{
    /**
     * The Openflashchart data
     *
     * @var object Graph
     */
    protected $_data;

    public function __construct()
    {
    	$this->_data = new graph();

        // Set defaults
        $this->_data->set_y_min(0);
        $this->_data->title('<b>Untitled</b>', 'color:#0B55C4;font-size:22px;');
        $this->_data->set_tool_tip('#key#<br>#x_label#: #val# '.JText::_('Items'));
        $this->_data->bg_colour = '#FFFFFF';
        $this->_data->set_inner_background( '#FFFFFF', '#EEEEEE', 90 );
        $this->_data->x_axis_colour( '#ADB5C7', '#DFE8FA' );
        $this->_data->y_axis_colour( '#ADB5C7', '#BEC6D8' );

    }

    public function __call($function, $arguments)
    {
    	return call_user_func_array(array($this->_data, $function), $arguments);
    }

    /**
     * Renders the <object> tag for Open Flash Chart
     *
     * @param string    Data Url
     * @param string    Unique ID
     * @param string    SWF file url
     * @param string    Width (px, %)
     * @param string    Height (px, %)
     * @param string    Background color
     * @param string    Attributes for the surrounding <div>
     */
    public static function renderSwfobject( $dataUrl, $id, $swfUrl, $width = '100%', $height = '450px', $bgcolor = '#FFFFFF', $divAttr = '')
    {
        $option 	= KRequest::get('get.option', 'cmd');
        $dataUrl    = urlencode($dataUrl);
        $protocol   = KRequest::protocol();
        
        ob_start();
        ?>
        <div style="width:<?php echo $width?>;height:<?php echo $height?>;" <?php echo $divAttr?>>
        <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="<?php echo $protocol?>://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
            width="<?php echo $width?>" height="<?php echo $height?>"
            id="<?php echo "obj_$id"?>" align="middle">
            <param name="allowScriptAccess" value="sameDomain" />
            <param name="movie" value="<?php echo $swfUrl?>?width=<?php echo $width?>&amp;height=<?php echo $height?>&amp;data=<?php echo $dataUrl?>" />
            <param name="quality" value="high" />
            <param name="bgcolor" value="<?php echo $bgcolor?>" />
            <embed src="<?php echo $swfUrl?>?data=<?php echo $dataUrl?>" quality="high" bgcolor="<?php echo $bgcolor?>"
                width="<?php echo $width?>" height="<?php echo $height?>" name="<?php echo $id?>"
                align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash"
                pluginspage="<?php echo $protocol?>://www.macromedia.com/go/getflashplayer" id="<?php echo $id?>" />
        </object>
        </div>
        <?php
        $output = ob_get_clean();

        return $output;
    }

    /**
     * Alias for y_label_steps()
     */
    function set_y_label_steps($val)
     {
     	$this->y_label_steps($val);
     }

    function bar_3D( $alpha, $colour='', $text='', $size=-1 )
    {
        // Making sure this is set when needed
        if(!$this->_data->x_axis_3d)
        {
            $this->set_x_axis_3d( 4 );
        }

        $this->_data->bar_3D( $alpha, $colour, $text, $size );
    }
}