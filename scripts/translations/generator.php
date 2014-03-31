<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Script;

use Nooku\Library;

class TranslationsGenerator
{
    public $strings = array();

    public $errors = array();

    public $current_file;

    public $format;

    protected $_location;

    protected $_component;

    protected $_language;

    public function __construct($config = array())
    {
        // Only one supported for now ... forcing.
        $this->format = 'yaml';

        $this->_component = isset($config[1]) ? $config[1] : 'application';

        $this->_language = isset($config[2]) ? $config[2] : 'en-GB';

        $this->parser = new \PHPParser_Parser(new \PHPParser_Lexer);

        $this->traverser = new \PHPParser_NodeTraverser;

        $this->traverser->addVisitor(new TranslationsNodeVisitor($this));
    }

    public function logNode($node)
    {
        if ($value = $node->value) {

            if (!isset($this->strings[$this->_location][$value])) {
                $this->strings[$this->_location][$value] = array();
            }

            $this->strings[$this->_location][$value][] = array(
                'file' => $this->current_file,
                'line' => $node->getAttribute('startLine')
            );
        } elseif ($node instanceof PHPParser_Node_Expr_Array) {
        	foreach ($node->items as $item) {
        		$this->logNode($item->value);
        	}
        } elseif ($node instanceof \PHPParser_Node_Expr_Ternary) {
            $outcomes = array('if', 'else');
            foreach ($outcomes as $outcome)
            {
                if ($node->{$outcome} instanceof \PHPParser_Node_Scalar)
                {
                    $this->logNode($node->{$outcome});
                }
            }
        } elseif ($node instanceof PHPParser_Node_Expr_Variable) {
            $this->errors[] = array(
                'file' => $this->current_file,
                'line' => $node->getAttribute('startLine'),
                'name' => '$'.$node->name,
                'type' => get_class($node)
            );
        } else {
            $this->errors[] = array(
                'file' => $this->current_file,
                'line' => $node->getAttribute('startLine'),
                'type' => get_class($node)
            );
        }
    }

    protected function _parse($directories)
    {
        foreach ($directories as $directory)
        {
            if (file_exists($directory))
            {
                foreach (new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($directory),
                    \RecursiveIteratorIterator::LEAVES_ONLY)
                    as $file)
                {

                    if ($file->getExtension() !== 'php')
                    {
                        continue;
                    }

                    try
                    {
                        $code               = file_get_contents($file);
                        $this->current_file = $file->getRealPath();

                        $stmts = $this->parser->parse($code);

                        /*$nodeDumper = new PHPParser_NodeDumper;
                        echo $nodeDumper->dump($stmts);*/

                        $stmts = $this->traverser->traverse($stmts);
                    } catch (PHPParser_Error $e)
                    {
                        echo 'Parse Error: ', $e->getMessage();
                    }
                }
            }
        }
    }

    public function getTranslations()
    {
        $files     = array();
        $strategy  = $this->getStrategy();
        $component = $this->_component;

        foreach ($this->_getLocations($component) as $location => $directories)
        {
            $this->_setLocation($location);
            $this->_parse($directories);

            if ($file = $this->_getLanguageFile($directories)) {
                $files[$location] = $strategy->parse($file);
            }
        }

        $translations = array();

        foreach ($this->strings as $location => $strings)
        {
            $translations[$location] = array();

            foreach ($strings as $key => $occurrences)
            {
                $value = $key;

                // Use current value instead of key if any.
                if (isset($files[$location]) && isset($files[$location][$key])) {
                    $value = $files[$location][$key];
                }

                $data = array('value' => $value, 'occurrences' => $occurrences);

                $translations[$location][$key] = $data;
            }
        }

        $output = '';

        if (count($translations))
        {
            $output .= "\nTranslations for *** {$component} *** component:\n";
        }
        else
        {
            $output .= "\nNo translations where found for  *** {$component} *** component ...\n\n";
        }

        // Load current application translations if generating translations for any other component.
        if ($this->_component != 'application')
        {
            $application = array('site' => array(), 'admin' => array(), 'component' => array());

            foreach ($this->_getLocations('application') as $location => $directories)
            {
                if ($file = $this->_getLanguageFile($directories))
                {
                    $application[$location] = $strategy->parse($file);
                }
            }

            // Merge component layer with application layer translations.
            $application['site'] = array_merge($application['component'], $application['site']);
            $application['admin'] = array_merge($application['component'], $application['admin']);
        }

        foreach ($translations as $location => $data)
        {
            // Do not list translations that are already included in the application translations files unless they are
            // overrides.
            if (isset($application) && !empty($application[$location]))
            {
                $app_overrides = array();
                $app_ignored   = array();

                foreach (array_keys(array_intersect_key($data, $application[$location])) as $key)
                {
                    if (isset($files[$location][$key]))
                    {
                        // Report the override.
                        $app_overrides[$key] = $data[$key];
                    }
                    else
                    {
                        // Remove it from translations list and notify.
                        $app_ignored[$key] = $data[$key];
                        unset($data[$key]);
                    }
                }
            }

            if ($location != 'component') {
                // Do not list translations that are already included in the component's layer translation unless they
                // are overrides.
                foreach (array_keys(array_intersect_key($data, $translations['component'])) as $key)
                {
                    // Remove the common string if there's no override present in the current translation file.
                    if (!isset($files[$location][$key]))
                    {
                        unset($data[$key]);
                    }
                }
            }

            $output .= "\n\n### Location: {$location} ... ###\n\n";
            $output .= $strategy->dump($data);
            $output .= "\n";

            // Check for component overridden translations.
            if ($location != 'component') {

                $component_overrides = array_intersect_key($data, $translations['component']);

                if (!empty($component_overrides))
                {
                    $output .= '!!! ' . ucfirst($this->_component) . " {$location} overrides: !!!\n\n";
                    $output .= $strategy->dump($component_overrides);
                    $output .= "\n";
                }
            }

            $unfound = array_diff_key($files[$location], $translations[$location]);

            // Report non-referenced (not found) translations.
            if (!empty($unfound)) {
                $output .= "!!! Non-referenced translations (present in translations file, but not used on {$location} layer code): !!!\n\n";
                $output .= $strategy->dump($unfound);
                $output .= "\n";
            }

            if (!empty($app_ignored))
            {
                $output .= "!!! Ignored translations (already available on Application component translations): !!!\n\n";
                $output .= $strategy->dump($app_ignored);
                $output .= "\n";
            }

            if (!empty($app_overrides)) {
                $output .= "!!! Application component overrides: !!!\n\n";
                $output .= $strategy->dump($app_overrides);
                $output .= "\n";
            }
        }

        return $output;
    }

    protected function _setLocation($location)
    {
        $this->_location = $location;
        // Initialize the strings array for current location if not done already.
        if (isset($this->strings[$location])) $this->strings[$location] = array();
    }

    protected function _getLocations($component) {
        $locations              = array();
        $locations['site']      = array(NOOKU_PATH . "/application/site/component/{$component}");
        $locations['admin']     = array(NOOKU_PATH . "/application/admin/component/{$component}");
        $locations['component'] = array(NOOKU_PATH . "/component/{$component}");

        // Particular case.
        if ($component == 'application')
        {
            $locations['component'][] = NOOKU_PATH . '/library';
        } else {

        }

        return $locations;
    }

    protected function _getLanguageFile($directories)
    {
        $result = null;

        $strategy = $this->getStrategy();

        foreach ($directories as $directory)
        {
            $candidate = $directory . "/resources/language/{$this->_language}." . $strategy->getFileExtension();
            if (!file_exists($candidate)) continue;
            $result = $candidate;
            break;
        }

        return $result;
    }

    public function getStrategy()
    {
        $classname = 'Nooku\Script\TranslationsStrategy' . ucfirst($this->format);
        return new $classname();
    }

    public function getErrorMessage()
    {
        if (!count($this->errors)) {
            return '';
        }

        $output = "\n\nThe following calls with non-string arguments were also found:\n";
        $output .= "--------------------------------------------------------------\n";
        foreach ($this->errors as $error) {
            $output .= 'File: '.$error['file']."\n";
            $output .= 'Line: '.$error['line']."\n";
            if (!empty($error['name'])) {
                $output .= "Type: Variable \n";
                $output .= 'Variable name: '.$error['name']."\n";
            } else {
                $output .= 'Type: '.$error['type']."\n";
            }
            $output .= "--------------------------------------------------------------\n";
        }

        return $output;
    }
}



