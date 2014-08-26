<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Url Template Filter
 *
 * Filter allows to define asset url schemes that are replaced on compile and render. A default assets:// scheme is
 * added that is rewritten to '/assets/'.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateFilterAsset extends TemplateFilterAbstract
{
    /**
     * The schemes
     *
     * @var array
     */
    protected $_schemes;

    /**
     * Constructor.
     *
     * @param   ObjectConfig $config Configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        foreach($config->schemes as $scheme => $path) {
            $this->addScheme($scheme, $path);
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config Configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'schemes' => array('assets://' => '/assets/'),
        ));

        parent::_initialize($config);
    }

    /**
     * Add an asset url scheme
     *
     * @param string $scheme Scheme to be replaced
     * @param mixed  $path   The path to replace the scheme with
     * @return TemplateFilterAsset
     */
    public function addScheme($scheme, $path)
    {
        $this->_schemes[$scheme] = $path;
        return $this;
    }

    /**
     * Convert the schemes to their real paths
     *
     * @param string $text  The text to parse
     * @return void
     */
    public function filter(&$text)
    {
        $text = str_replace(
            array_keys($this->_schemes),
            array_values($this->_schemes),
            $text);
    }
}