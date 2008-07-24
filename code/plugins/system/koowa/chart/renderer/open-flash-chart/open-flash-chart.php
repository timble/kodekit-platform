<?php

class graph
{
	function graph()
	{
		$this->data = array();
		$this->x_labels = '';
		$this->y_min = '';
		$this->y_max = '';
		$this->y_steps = '';
		$this->title = '';
		$this->title_style = '';
	
		$this->x_tick_size = -1;
	
		$this->y2_max = '';
		$this->y2_min = '';
			
		// GRID styles:
		$this->x_axis_colour = '';
		$this->x_axis_3d = '';
		$this->x_grid_colour = '';
		$this->x_axis_steps = '';
		$this->y_axis_colour = '';
		$this->y_grid_colour = '';
		$this->y2_axis_colour = '';
		
		// AXIS LABEL styles:         
		$this->x_label_style = '';
		$this->y_label_style = '';
		$this->y_label_style_right = '';
		
		// AXIS LEGEND styles:
		$this->x_legend = '';
		$this->x_legend_size = 20;
		$this->x_legend_colour = '#000000';
	
		$this->y_legend = '';
		$this->y_legend_right = '';
	
		$this->lines = array();
		$this->line_default = '&line=3,#87421F&'. "\r\n";
	
		$this->bg_colour = '';
		$this->bg_image = '';
	
		$this->inner_bg_colour = '';
		$this->inner_bg_colour_2 = '';
		$this->inner_bg_angle = '';
	
		// PIE chart ------------
		$this->pie = '';
		$this->pie_values = '';
		$this->pie_colours = '';
		$this->pie_labels = '';
		$this->pie_links = '';
	
		$this->tool_tip = '';
			
		// which data lines are attached to the
		// right Y axis?
		$this->y2_lines = array();

		//
		// set some default value incase the user forgets
		// to set them, so at least they see *something*
		// even is it is only the axis and some ticks
		//
		$this->set_y_min( 0 );
		$this->set_y_max( 20 );
		$this->set_x_axis_steps( 1 );
		$this->y_label_steps( 5 );
	}

	function set_data( $a )
	{
		if( count( $this->data ) == 0 )
			$this->data[] = '&values='.implode(',',$a).'&'."\r\n";
		else
			$this->data[] = '&values_'. (count( $this->data )+1) .'='.implode(',',$a).'&'."\r\n";
	}

	function set_tool_tip( $tip )
	{
		$this->tool_tip = $tip;
	}
	
	// an array of labels
	function set_x_labels( $a )
	{
		$this->x_labels = $this->_pack( 'x_labels', $a );
	}
	
	function set_x_label_style( $size, $colour='', $orientation=0, $step=-1, $grid_colour='' )
	{
		$tmp = array();
		$tmp[] = $size;
		
		if( strlen( $colour ) > 0 )
			$tmp[] = $colour;

		if( $orientation > -1 )
			$tmp[] = $orientation;

		if( $step > 0 )
			$tmp[] = $step;
		
		if( strlen( $grid_colour ) > 0 )
			$tmp[] = $grid_colour;
			
		$this->x_label_style = $this->_pack( 'x_label_style', $tmp );
	}

	function set_bg_colour( $colour )
	{
		$this->bg_colour = $colour;
	}

	function set_bg_image( $url, $x='center', $y='center' )
	{
		$this->bg_image = $url;
		$this->bg_image_x = $x;
		$this->bg_image_y = $y;
	}

	function attach_to_y_right_axis( $data_number )
	{
		$this->y2_lines[] = $data_number;
	}
	
	function set_inner_background( $col, $col2='', $angle=-1 )
	{
		$this->inner_bg_colour = $col;

		if( strlen($col2) > 0 )
			$this->inner_bg_colour_2 = $col2;

		if( $angle != -1 )
			$this->inner_bg_angle = $angle;
	}

	function _set_y_label_style( $name, $size, $colour )
	{
		$tmp = '&'. $name .'='. $size;
		
		if( strlen( $colour ) > 0 )
				$tmp .= ','. $colour;
				
		$tmp .= "&\r\n";
		
		return $tmp;
	}
	
	function set_y_label_style( $size, $colour='' )
	{
		$this->y_label_style = $this->_set_y_label_style( 'y_label_style', $size, $colour );
	}
	
	function set_y_right_label_style( $size, $colour='' )
	{
		$this->y_label_style_right = $this->_set_y_label_style( 'y2_label_style', $size, $colour );
	}
	
	function set_y_max( $max )
	{
		$this->y_max = $this->_pack( 'y_max', array($max) );
	}

	function set_y_min( $min )
	{
		$this->y_min = $this->_pack( 'y_min', array($min) );
	}
		
	function set_y_right_max( $max )
	{
		$this->y2_max = $this->_pack( 'y2_max', array($max) );
	}
	
	function set_y_right_min( $min )
	{
		$this->y2_min = $this->_pack( 'y2_min', array($min) );
	}
	
	function y_label_steps( $val )
	{
		//
		// TO DO!! add the tick major and minor size:
		//
		$this->y_steps = $this->_pack( 'y_ticks', array(5,10,$val) );
	}
	
	function title( $title, $style='' )
	{
		$this->title = $title;
		if( strlen( $style ) > 0 )
			$this->title_style = $style;
	}
	
	function set_x_legend( $text, $size=-1, $colour='' )
	{
		 $this->x_legend = $text;
		 if( $size > -1 )
			$this->x_legend_size = $size;
				
		 if( strlen( $colour )>0 )
			$this->x_legend_colour = $colour;
	}
	
	function set_x_tick_size( $size )
	{
		if( $size > 0 )
			$this->x_tick_size = $this->_pack( 'x_ticks', array($size) );
	}

	function set_x_axis_steps( $steps )
	{
		if ( $steps > 0 )
			$this->x_axis_steps = $this->_pack( 'x_axis_steps', array($steps) );
	}
	
	function set_x_axis_3d( $size )
	{
		if( $size > 0 )
			$this->x_axis_3d = $this->_pack( 'x_axis_3d', array($size) );
	}
	
	// PRIVATE METHOD
	function _set_y_legend( $name, $text, $size, $colour )
	{
		$tmp = array();
		$tmp[] = $text;

		if( $size > -1 )
		{
			$tmp[] = $size;

			if( strlen( $colour )>0 )
				$tmp[] = $colour;
		}

		return $this->_pack( $name, $tmp );
	}

	function set_y_legend( $text, $size=-1, $colour='' )
	{
		$this->y_legend = $this->_set_y_legend( 'y_legend', $text, $size, $colour );
	}
	
	function set_y_right_legend( $text, $size=-1, $colour='' )
	{
		$this->y_legend_right = $this->_set_y_legend( 'y2_legend', $text, $size, $colour );
	}
	
	function line( $width, $colour='', $text='', $size=-1, $circles=-1 )
	{
		$name = 'line';
		
		if( count( $this->lines ) > 0 )
			$name .= '_'. (count( $this->lines )+1);
			
		$tmp = array();
		
		if( $width > 0 )
		{
			$tmp[] = $width;
			$tmp[] = $colour;
		
			if( strlen( $text ) > 0 )
			{
				$tmp[] = $text;
				$tmp[] = $size;
					
				if( $circles > 0 )
					$tmp[] = $circles;
			}
		}
		
		$this->lines[] = $this->_pack( $name, $tmp );
	}

	function line_dot( $width, $dot_size, $colour, $text='', $font_size='' )
	{
		$name = 'line_dot';
		
		if( count( $this->lines ) > 0 )
			$name .= '_'. (count( $this->lines )+1);
			
		$tmp = array();
		$tmp[] = $width;
		$tmp[] = $colour;
		$tmp[] = $text;

		if( strlen( $font_size ) > 0 )
		{
			$tmp[] = $font_size;
			$tmp[] = $dot_size;
		}
		
		$this->lines[] = $this->_pack( $name, $tmp );
	}

	function line_hollow( $width, $dot_size, $colour, $text='', $font_size='' )
	{
		$name = 'line_hollow';
		
		if( count( $this->lines ) > 0 )
			$name .= '_'. (count( $this->lines )+1);
			
		$tmp = array();
		$tmp[] = $width;
		$tmp[] = $colour;
		$tmp[] = $text;

		if( strlen( $font_size ) > 0 )
		{
			$tmp[] = $font_size;
			$tmp[] = $dot_size;
		}
		
		$this->lines[] = $this->_pack( $name, $tmp );
	}

	function area_hollow( $width, $dot_size, $colour, $alpha, $text='', $font_size='', $fill_colour='' )
	{
		$name = 'area_hollow';
		
		if( count( $this->lines ) > 0 )
			$name .= '_'. (count( $this->lines )+1);
			
		$tmp = array();
		$tmp[] = $width;
		$tmp[] = $dot_size;
		$tmp[] = $colour;
		$tmp[] = $alpha;

		if( strlen( $text ) > 0 )
		{
			$tmp[] = $text;
			$tmp[] = $font_size;
			if( strlen( $fill_colour ) > 0 )
				$tmp[] = $fill_colour;
		}
			
		$this->lines[] = $this->_pack( $name, $tmp );
	}


	function bar( $alpha, $colour='', $text='', $size=-1 )
	{
		$name = 'bar';
		
		if( count( $this->lines ) > 0 )
			$name .= '_'. (count( $this->lines )+1);
		
		$this->lines[] = $this->_pack( $name, array( $alpha, $colour, $text, $size ) );
	}

	function bar_filled( $alpha, $colour, $colour_outline, $text='', $size=-1 )
	{
		$name = 'filled_bar';
		
		if( count( $this->lines ) > 0 )
			$name .= '_'. (count( $this->lines )+1);
		
		$this->lines[] = $this->_pack( $name, array( $alpha, $colour, $colour_outline, $text, $size ) );
	}
	
	function bar_3D( $alpha, $colour='', $text='', $size=-1 )
	{
		$name = 'bar_3d';
		
		if( count( $this->lines ) > 0 )
			$name .= '_'. (count( $this->lines )+1);
		
		$this->lines[] = $this->_pack( $name, array( $alpha, $colour, $text, $size) );
	}
	
	function bar_glass( $alpha, $colour, $outline_colour, $text='', $size=-1 )
	{
		$name = 'bar_glass';
	
		if( count( $this->lines ) > 0 )
			$name .= '_'. (count( $this->lines )+1);
	
		$this->lines[] = $this->_pack( $name, array( $alpha, $colour, $outline_colour, $text, $size) );
	}
	
	function bar_fade( $alpha, $colour='', $text='', $size=-1 )
	{
		$name = 'bar_fade';
	
		if( count( $this->lines ) > 0 )
			$name .= '_'. (count( $this->lines )+1);
	
		$this->lines[] = $this->_pack( $name, array( $alpha, $colour, $text, $size) );
	}
	
	function x_axis_colour( $axis, $grid='' )
	{
		$this->x_axis_colour = $axis;
		$this->x_grid_colour = $grid;
	}

	function y_axis_colour( $axis, $grid='' )
	{
		//$this->y_axis_colour = '&y_axis_colour='. $axis .'&'."\r\n";
		$this->y_axis_colour = $this->_pack( 'y_axis_colour', array($axis) );
		
		if( strlen( $grid ) > 0 )
			//$this->y_grid_colour = '&y_grid_colour='. $grid .'&';
			$this->y_grid_colour = $this->_pack( 'y_grid_colour', array($grid) );

		
	}
	
	function y_right_axis_colour( $colour )
	{
		$this->y2_axis_colour = $this->_pack( 'y2_axis_colour', array($colour) );
	}
	
/*
	function pie( $alpha, $line_colour, $label_colour )
	{
		$this->pie = $alpha.','.$line_colour.','.$label_colour;

	}
*/

	//
	// Patch by, Jeremy Miller (14th Nov, 2007)
	//
	function pie( $alpha, $line_colour, $label_colour, $gradient = true, $border_size = false )
	{
		$this->pie = $alpha.','.$line_colour.','.$label_colour;
		if (!$gradient) {
			$this->pie .= ','.!$gradient;
		}
		if ($border_size)
		{
			if ($gradient === false)
			{
				$this->pie .= ',';
			}
			$this->pie .= ','.$border_size;
		}
	}
	
	function pie_values( $values, $labels, $links )
	{
		$this->pie_values = implode(',',$values);
		$this->pie_labels = implode(',',$labels);
		$this->pie_links  = implode(",",$links);
	}


	function pie_slice_colours( $colours )
	{
		$this->pie_colours = implode(',',$colours);
	}
	
	//
	function _pack( $name, $data )
	{
		if (is_array($data))
			$data = implode(',',$data);
    
		return '&'. $name .'='. $data .'&';
	}

	function render()
	{
		$tmp = array();
		
		if( strlen( $this->title ) > 0 )
			$tmp[] = $this->_pack( 'title', array($this->title,$this->title_style) );
		
		if( strlen( $this->x_legend ) > 0 )
			$tmp[] = $this->_pack( 'x_legend', array($this->x_legend,$this->x_legend_size,$this->x_legend_colour) );

		if( strlen( $this->x_label_style ) > 0 )
			$tmp[] =  $this->x_label_style;
			
		if( $this->x_tick_size > 0 )
			$tmp[] = $this->x_tick_size;
				
		if( $this->x_axis_steps > 0 )
			$tmp[] = $this->x_axis_steps;

		if( strlen( $this->x_axis_3d ) > 0 )
			$tmp[] = $this->x_axis_3d;
		
		$tmp[] = $this->y_legend;	
		$tmp[] = $this->y_legend_right;

		if( strlen( $this->y_label_style ) > 0 )
			$tmp[] = $this->y_label_style;
		
		if( strlen( $this->y_label_style_right ) > 0 )
			$tmp[] = $this->y_label_style_right;   
		
		$tmp[] = $this->y_steps;
		
		if( count( $this->lines ) == 0 )
		{
			$tmp[] = $this->line_default;	
		}
		else
		{
			foreach( $this->lines as $line )
				$tmp[] = $line;	
		}

		foreach( $this->data as $data )
			$tmp[] = $data;
		
		if( count( $this->y2_lines ) > 0 )
		{
			$tmp[] = '&y2_lines='. implode( ',', $this->y2_lines ) .'&';
			//
			// Should this be an option? I think so...
			//
			$tmp[] = '&show_y2=true&';
		}	
		
		if( strlen( $this->x_labels ) > 0 )
			$tmp[] = $this->x_labels;
				
		$tmp[] = $this->y_min;
		$tmp[] = $this->y_max;
		
		$tmp[] = $this->y2_max;
		$tmp[] = $this->y2_min;
		
		if( strlen( $this->bg_colour ) > 0 )
			$tmp[] = '&bg_colour='. $this->bg_colour .'&';

		if( strlen( $this->bg_image ) > 0 )
		{
			$tmp[] = '&bg_image='. $this->bg_image .'&';
			$tmp[] = '&bg_image_x='. $this->bg_image_x .'&';
			$tmp[] = '&bg_image_y='. $this->bg_image_y .'&';
		}


		if( strlen( $this->x_axis_colour ) > 0 )
		{
			$tmp[] = '&x_axis_colour='. $this->x_axis_colour .'&';
			$tmp[] = '&x_grid_colour='. $this->x_grid_colour .'&';
		}

		if( strlen( $this->y_axis_colour ) > 0 )
			$tmp[] = $this->y_axis_colour;
		
		if( strlen( $this->y_grid_colour ) > 0 )
			$tmp[] = $this->y_grid_colour;
			
		if( strlen( $this->y2_axis_colour ) > 0 )    
			$tmp[] = $this->y2_axis_colour;

		if( strlen( $this->inner_bg_colour ) > 0 )
		{
			$t = '&inner_background='.$this->inner_bg_colour;
			if( strlen( $this->inner_bg_colour_2 ) > 0 )
			{
				$t .= ','. $this->inner_bg_colour_2;
				$t .= ','. $this->inner_bg_angle;
			}
			$t .= '&';
			$tmp [] = $t;
		}

		if( strlen( $this->pie ) > 0 )
		{
			$tmp[] = '&pie='.        $this->pie .'&';
			$tmp[] = '&values='.     $this->pie_values .'&';
			$tmp[] = '&pie_labels='. $this->pie_labels .'&';
			$tmp[] = '&colours='.    $this->pie_colours .'&';
			$tmp[] = '&links='.      $this->pie_links .'&';
		}

		if( strlen( $this->tool_tip ) > 0 )
		{
			$tmp[] = '&tool_tip='. $this->tool_tip .'&';
		}

		return implode("\r\n",$tmp);
	}
}