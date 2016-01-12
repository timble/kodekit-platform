<?php
/**
 * @version		$Id: parameter.php 14401 2010-01-26 14:10:00Z louis $
 * @package		Joomla.Framework
 * @subpackage	Parameter
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

//Register the element class with the loade
JLoader::register('JElement', dirname(__FILE__).DS.'parameter'.DS.'element.php');

/**
 * Parameter handler
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 */
class JParameter extends JObject
{
    /**
     * The parameter data
     *
     * @var array
     */
    protected $_data;

    /**
	 * The parameters
	 *
     * @var SimpleXmlElement
	 */
	protected $_params;

	/**
 	 * The elements
	 *
     * @var array
	 */
	protected $_elements;

	/**
	* Paths to look for elements
	*
	* @var array
	*/
	protected $_paths;

	/**
	 * Constructor
	 *
	 * @param	string $data The parameters
	 * @param	string $path Path to the xml setup file
	 */
	public function __construct($data = array(), $path = '')
	{
		$this->_data     = array();
        $this->_paths    = array();
        $this->_elements = array();

		// Set base path
		$this->addElementPath(dirname( __FILE__ ).DS.'parameter'.DS.'element');

		// Set the data
        if(is_array($data)) {
            $this->setData($data);
        }

        // Load the parameters
        if(is_file($path)) {
            $this->loadParams($path);
        }
	}

	/**
	 * Set a value
	 *
	 * @param	string $key   The name of the param
	 * @param	string $value The value of the parameter
     * @param	string	$group
	 * @return	JParameter
	 */
	public function set($key, $value = '', $group = '_default')
	{
        if(!isset($this->_data[$group])) {
            $this->_data[$group] = array();
        }

        $this->_data[$group][$key] = $value;

        return $this;
	}

	/**
	 * Get a value
	 *
	 * @param	string $key  The name of the param
	 * @param	mixed $value The default value if not found
	 * @return	string
	 */
	public function get($key, $default = '', $group = '_default')
	{
        $result = $default;

        if(isset($this->_data[$group]))
        {
            if(isset($this->_data[$group][$key])) {
                $result = $this->_data[$group][$key];
            }
        }

		return $result;
	}

	/**
	 * Sets a default value if not alreay assigned
	 *
	 * @param	string	$key   The name of the param
	 * @param	string	$value The value of the parameter
	 * @param	string	$group The parameter group to modify
	 * @return	string	The set value
	 */
	public function def($key, $default = '', $group = '_default')
    {
		$value = $this->get($key, $default, $group);
		return $this->set($key, $value);
	}

    /**
     * Set the parameters data
     *
     * @param	array	$data
     * @return	boolean True on success
     */
    public function setData($data, $group = '_default')
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value, $group);
        }

        return true;
    }

    /**
     * Get the parameters data
     *
     * @return	array	An associative array holding data
     */
    public function getData($group = '_default')
    {
        $result = array();

        if(isset($this->_data[$group])) {
            $result = $this->_data[$group];
        }

        return $result;
    }

    /**
     * Get the number of params in each group
     *
     * @return	array	Array of all group names as key and param count as value
     */
    public function getGroups()
    {
        if (is_array($this->_params))
        {
            $results = array();
            foreach ($this->_params as $name => $group)  {
                $results[$name] = $this->countParams($name);
            }

            return $results;
        }


        return false;
    }

	/**
	 * Render the parameters
	 *
	 * @param	string	$name The name of the control, or the default text area if a setup file is not found
     * @param	string	$group
	 * @return	string	HTML
	 */
	public function render($name = 'params', $group = '_default')
	{
        $html = array();

        if (isset($this->_params[$group]))
        {
            if ($description = $this->_params[$group]->attributes()->description) {
                $html[]	= $description;
            }

            foreach ($this->_params[$group]->children() as $param)
            {
                $param = $this->renderParam($param, $name);

                $html[] = '<div>';
                $html[] = $param[0];
                $html[] = '<div>';
                $html[] = $param[1];
                $html[] = isset($param[2]) ? '<p class="help-block">'.$param[2].'</p>' : '';
                $html[] = '</div>';
                $html[] = '</div>';
            }
		}

        return implode("\n", $html);
	}

    /**
     * Render a parameter type
     *
     * @param	SimpleXMLElement	$param  A param
     * @param	string	$string The control name
     * @return	array	Any array of the label, the form element and the tooltip
     */
    public function renderParam(SimpleXMLElement $param, $name = 'params', $group = '_default')
    {
        $type    = $param->attributes()->type;
        $element = $this->loadElement($type);

        if ($element === false)
        {
            $result = array();
            $result[0] = $param->attributes()->name;
            $result[1] = 'Element not defined for type = '.$type;
            $result[5] = $result[0];

            return $result;
        }

        $value = $this->get((string) $param->attributes()->name, (string) $param->attributes()->default, $group);

        return $element->render($param, $value, $name);
    }

    /**
     * Return number of params
     *
     *  @param	string	$group
     * @return	SimpleXMLElement|null
     */
    public function getParams($group = '_default')
    {
        if (isset($this->_params[$group])) {
            return $this->_params[$group];
        }

        return false;
    }

    /**
     * Return number of params
     *
     * @param   array  $params
     * @param	string $group
     * @return	JParameter
     */
    public function setParams($params, $group = '_default')
    {
        $group = $params->attributes()->group ?: $group;
        $this->_params[$group] = $params;

        if ($dir = (string)$params->attributes()->path) {
            $this->addElementPath(APPLICATION_ROOT . str_replace('/', DS, $dir));
        }

        return $this;
    }

    /**
     * Return number of params
     *
     *  @param	string	$group
     * @return	mixed	Boolean false if no params exist or integer number of params that exist
     */
    public function countParams($group = '_default')
    {
        if (isset($this->_params[$group])) {
            return count($this->_params[$group]->children());
        }

        return false;
    }

	/**
	 * Loads an xml setup file and parses it
	 *
	 * @param	string	$path path to xml setup file
     * @return bool     Return true if the params have been successfully loaded.
	 */
	public function loadParams($path)
	{
        if ($xml = simplexml_load_file($path))
        {
            if ($params = $xml->params)
            {
                $this->setParams($params);
                return true;
            }
        }

		return false;
	}

	/**
	 * Loads a element type
	 *
	 * @param	string	$type elementType
	 * @return	object|null
	 */
	public function loadElement( $type )
	{
		$result = null;
        $class  = 'JElement'.strtoupper($type);

        if( (!isset( $this->_elements[$class] )))
        {
            if( !class_exists( $class ) )
            {
                $file = str_replace('_', '/', $type).'.php';

                foreach ($this->_paths as $path)
                {
                    if($result = $this->realPath($path.'/'.$file))
                    {
                        include_once $result;
                        break;
                    }
                }
            }

            if( class_exists( $class ) ) {
                $this->_elements[$class] = new $class($this);
            }
        }

        if(isset($this->_elements[$class])) {
            $result = $this->_elements[$class];
        }

		return $result;
	}

	/**
	 * Add a directory to search for elements
	 *
	 * @param	string|array $path	Directory or directories to search.
     * @return JParameter
	 */
	public function addElementPath( $path )
	{
		settype( $path, 'array' );

		foreach ( $path as $dir )
		{
			$dir = trim( $dir );

			if ( substr( $dir, -1 ) != '/' ) {
				$dir .= '/';
			}

			array_unshift( $this->_paths, $dir );
		}

        return $this;
	}

    /**
     * Get a path from an file
     *
     * Function will check if the path is an alias and return the real file path
     *
     * @param  string $file The file path
     * @return string The real file path
     */
    final public function realPath($file)
    {
        $result = false;
        $path   = dirname($file);

        // Is the path based on a stream?
        if (strpos($path, '://') === false)
        {
            // Not a stream, so do a realpath() to avoid directory traversal attempts on the local file system.
            $path = realpath($path); // needed for substr() later
            $file = realpath($file);
        }

        // The substr() check added to make sure that the realpath() results in a directory registered so that
        // non-registered directories are not accessible via directory traversal attempts.
        if (file_exists($file) && substr($file, 0, strlen($path)) == $path) {
            $result = $file;
        }

        return $result;
    }


}