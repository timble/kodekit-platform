<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Provides an easy interface to parse and display a vcard
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package		Joomla.Framework
 * @subpackage	Document
 * @since		Nooku Server 0.7
 */
class JDocumentVcard extends JDocument
{
	/**
	 * The VCard properties
	 *
	 * @var		array
	 */
	var $_properties;
	
	/**
	 * The filename
	 *
	 * @var string
	 */
	var $_filename;
	
	/**
	 * Class constructore
	 *
	 * @param	array	$options Associative array of options
	 */
	public function __construct($options = array())
	{
		parent::__construct($options);
		
		//set document type
		$this->_type = 'vcard';

		//set mime type
		$this->_mime = 'text/x-vcard';
	}
	
	/**
	 * Get the document head data
	 *
	 * @return	array	The document head data in array form
	 */
	public function getHeadData() { }

	/**
	 * Set the document head data
	 *
	 * @param	array	$data	The document head data in array form
	 * @return	this
	 */
	public function setHeadData(array $data) { }

	/**
	 * Render the document.
	 *
	 * @param boolean 	$cache		If true, cache the output
	 * @param array		$params		Associative array of attributes
	 * @return 	The rendered data
	 */
	public function render( $cache = false, array $params = array())
	{
		$data 	= 'BEGIN:VCARD';
		$data	.= "\r\n";
		$data 	.= 'VERSION:2.1';
		$data	.= "\r\n";

		foreach( $this->_properties as $key => $value ) {
			$data	.= "$key:$value";
			$data	.= "\r\n";
		}
		$data	.= 'REV:'. date( 'Y-m-d' ) .'T'. date( 'H:i:s' ). 'Z';
		$data	.= "\r\n";
		$data	.= 'MAILER: Joomla! vCard for '. $this->getBase();
		$data	.= "\r\n";
		$data	.= 'END:VCARD';
		$data	.= "\r\n";
		
		// Set document type headers
		parent::render();
		
		// Send vCard file headers
		JResponse::setHeader('Content-Length', strlen($data), true);
		JResponse::setHeader('Content-disposition', 'attachment; filename="'.$this->_filename.'"', true);
		
		return $data;
	}

	/**
	 * Set a phone number
	 *
	 * @param	string	Phone number
	 * @param 	string	Type [PREF|WORK|HOME|VOICE|FAX|MSG|CELL|PAGER|BBS|CAR|MODEM|ISDN|VIDEO] or a combination, e.g. "PREF;WORK;VOICE"
	 * @return 	this
	 */
	public function setPhoneNumber($number, $type='') 
	{
		$key = 'TEL';
		if ($type!='') {
			$key .= ';'. $type;
		}
		$key.= ';ENCODING=QUOTED-PRINTABLE';

		$this->_properties[$key] = $this->quoted_printable_encode($number);
		return $this;
	}

	/**
	 * Set a photo
	 *
	 * @param	string 	Type [GIF|JPEG]
	 * @param 	string	Photo
	 * @return 	this
	 */
	public function setPhoto($type, $photo) 
	{ 
		$this->_properties["PHOTO;TYPE=$type;ENCODING=BASE64"] = base64_encode($photo);
		return $this;
	}

	/**
	 * Set formatted name
	 *
	 * @param	string	Name
	 * @return 	this
	 */
	public function setFormattedName($name) 
	{
		$this->_properties['FN'] = $this->quoted_printable_encode($name);
		return $this;
	}
	
	/**
	 * Set name
	 *
	 * @param 	string	Family name
	 * @param 	string	First name
	 * @param 	string	Additional name
	 * @param 	string	Prefix
	 * @param 	string 	Suffix
	 * @return 	this
	 */
	public function setName( $family='', $first='', $additional='', $prefix='', $suffix='' ) 
	{
		$this->_properties["N"] 	= "$family;$first;$additional;$prefix;$suffix";
		$this->setFormattedName( trim( "$prefix $first $additional $family $suffix" ) );
		return $this;
	}

	/**
	 * Set birthday
	 *
	 * @param 	string	Date YYYY-MM-DD
	 * @return 	this
	 */
	public function setBirthday($date) 
	{ 
		$this->_properties['BDAY'] = $date;
		return $this;
	}

	/**
	 * Set Address
	 *
	 * @param 	string Postoffice
	 * @param	string Extended
	 * @param 	string Street
	 * @param 	string City
	 * @param 	string Region
	 * @param 	string Zip
	 * @param 	string Country
	 * @param 	string Type [DOM|INTL|POSTAL|PARCEL|HOME|WORK] or a combination e.g. "WORK;PARCEL;POSTAL"
	 * @return 	this
	 */
	public function setAddress( $postoffice='', $extended='', $street='', $city='', $region='', $zip='', $country='', $type='HOME;POSTAL' ) 
	{
		$separator = ';';

		$key 		= 'ADR';
		if ( $type != '' ) {
			$key	.= $separator . $type;
		}
		$key.= ';ENCODING=QUOTED-PRINTABLE';

		$return = $this->encode( $postoffice );
		$return .= $separator . $this->encode( $extended );
		$return .= $separator . $this->encode( $street );
		$return .= $separator . $this->encode( $city );
		$return .= $separator . $this->encode( $region);
		$return .= $separator . $this->encode( $zip );
		$return .= $separator . $this->encode( $country );

		$this->_properties[$key] = $return;
		return $this;
	}

	/**
	 * Set label
	 *
	 * @param 	string Postoffice
	 * @param	string Extended
	 * @param 	string Street
	 * @param 	string City
	 * @param 	string Region
	 * @param 	string Zip
	 * @param 	string Country
	 * @param 	string Type [DOM|INTL|POSTAL|PARCEL|HOME|WORK] or a combination e.g. "WORK;PARCEL;POSTAL"
	 * @return 	this
	 */
	public function setLabel($postoffice='', $extended='', $street='', $city='', $region='', $zip='', $country='', $type='HOME;POSTAL') 
	{
		$label = '';
		if ($postoffice!='') {
			$label.= $postoffice;
			$label.= "\r\n";
		}

		if ($extended!='') {
			$label.= $extended;
			$label.= "\r\n";
		}

		if ($street!='') {
			$label.= $street;
			$label.= "\r\n";
		}

		if ($zip!='') {
			$label.= $zip .' ';
		}

		if ($city!='') {
			$label.= $city;
			$label.= "\r\n";
		}

		if ($region!='') {
			$label.= $region;
			$label.= "\r\n";
		}

		if ($country!='') {
			$country.= $country;
			$label.= "\r\n";
		}

		$this->_properties["LABEL;$type;ENCODING=QUOTED-PRINTABLE"] = $this->quoted_printable_encode($label);
		return $this;
	}

	/**
	 * Set Email
	 *
	 * @param 	string Email
	 * @return 	this
	 */
	public function setEmail($address) 
	{
		$this->_properties['EMAIL;INTERNET'] = $address;
		return $this;
	}

	/**
	 * Set note
	 *	
	 * @param 	string	Note
	 * @return 	this
	 */
	public function setNote($note) 
	{
		$this->_properties['NOTE;ENCODING=QUOTED-PRINTABLE'] = $this->quoted_printable_encode($note);
		return $this;
	}

	/**
	 * Set URL
	 *
	 * @param 	string	Url
	 * @param 	string	Type [WORK|HOME]
	 * @return 	this
	 */
	public function setURL($url, $type='') 
	{
		$key = 'URL';
		if ($type!='') {
			$key.= ";$type";
		}

		$this->_properties[$key] = $url;
		return $this;
	}
	
	/**
	 * Set filename
	 *
	 * @param 	string	Filename
	 * @return 	this
	 */
	public function setFilename( $filename ) 
	{
		$this->_filename = $filename .'.vcf';
		return $this;
	}
	
	/**
	 * Set title
	 *
	 * @param 	string	Title
	 * @return 	this
	 */
	public function setTitle( $title ) 
	{
		$title 	= trim( $title );
		$this->_properties['TITLE'] 	= $title;
		return $this;
	}
	
	
	/**
	 * Set organisation
	 *
	 * @param 	string	Organisation
	 * @return 	this
	 */
	public function setOrg( $org ) 
	{
		$org 	= trim( $org );
		$this->_properties['ORG'] = $org;
		return $this;
	}


	/**
	 * Encode
	 *
	 * @param 	string	String to encode
	 * @return 	string	Encoded string
	 */
	public function encode($string) 
	{
		return $this->escape($this->quoted_printable_encode($string));
	}

	/**
	 * Escape
	 *
	 * @param 	string	String to escape
	 * @return 	string	Escaped string
	 */
	public function escape($string) 
	{
		return str_replace(';',"\;",$string);
	}

	/**
	 * Quote for printable output
	 *
	 * @param 	string 	Input
	 * @param 	int		Max line length
	 * @return 	string
	 */
	public function quoted_printable_encode($input, $line_max = 76) 
	{
		$hex 		= array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
		$lines 		= preg_split("/(?:\r\n|\r|\n)/", $input);
		$eol 		= "\r\n";
		$linebreak 	= '=0D=0A';
		$escape 	= '=';
		$output 	= '';

		for ($j=0;$j<count($lines);$j++) 
		{
			$line 		= $lines[$j];
			$linlen 	= strlen($line);
			$newline 	= '';

			for($i = 0; $i < $linlen; $i++) 
			{
				$c 		= substr($line, $i, 1);
				$dec 	= ord($c);

				if ( ($dec == 32) && ($i == ($linlen - 1)) ) { // convert space at eol only
					$c = '=20';
				} elseif ( ($dec == 61) || ($dec < 32 ) || ($dec > 126) ) { // always encode "\t", which is *not* required
					$h2 = floor($dec/16);
					$h1 = floor($dec%16);
					$c 	= $escape.$hex["$h2"] . $hex["$h1"];
				}
				if ( (strlen($newline) + strlen($c)) >= $line_max ) { // CRLF is not counted
					$output .= $newline.$escape.$eol; // soft line break; " =\r\n" is okay
					$newline = "    ";
				}
				$newline .= $c;
			} // end of for
			$output .= $newline;
			if ($j<count($lines)-1) {
				$output .= $linebreak;
			}
		}

		return trim($output);
	}
}