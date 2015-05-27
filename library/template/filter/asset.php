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
        $schemes    = $this->_resolveAliases();
        $keys       = $schemes['keys'];
        $values     = $schemes['values'];

        $text = str_replace(
            $keys,
            $values,
            $text);
    }

    /**
     * Resolves any asset aliases, e.g.
     *
     * /assets/application/ => /theme/bootstrap/
     * asset:// => /assets/
     *
     * resolves to
     *
     * /assets/application/ => /theme/bootstrap/
     * asset://application/ => /theme/bootstrap/
     * asset:// => /assets/
     *
     * @return array
     */
    protected function _resolveAliases()
    {
        $schemes = array('keys' => array(), 'values' => array());

        //Iterate existing schemes and resolve the alias
        foreach($this->_schemes as $key => $value){
            $resolved = $this->_resolveAlias($key, $value, $schemes);
            $schemes = array_merge_recursive($schemes, $resolved);
        }

        return $schemes;
    }

    /**
     * Resolves a single key => value alias against existing sources
     *
     * @param $key
     * @param $value
     * @param array $sources
     * @return array
     */
    protected function _resolveAlias($key, $value, array $sources)
    {
        $schemes = array('keys' => array(), 'values' => array());

        //Iterate current replacements, see if any resolve to the current replacement. Check both key and value
        foreach($sources['keys'] as $i => $key_target){

            $value_target = $sources['values'][$i];

            //Look for a value match
            if(strpos($key_target, $value) !== false){
                $schemes['keys'][] = str_replace($value, $key, $key_target);
                $schemes['values'][] = $value_target;
            }

            //Look for a key match
            if(strpos($key, $key_target) !== false){
                $schemes['keys'][] = str_replace($key_target, $value_target, $key);
                $schemes['values'][] = $value;
            }
        }

        //Add the original replacement
        $schemes['keys'][] = $key;
        $schemes['values'][] = $value;

        return $schemes;
    }
}