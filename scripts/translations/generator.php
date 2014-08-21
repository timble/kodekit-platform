<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://github.com/nooku/nooku-platform for the canonical source repository
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

    protected $_source;

    protected $_language;

    public function __construct($config = array())
    {
        // Only one supported for now ... forcing.
        $this->format = 'yaml';

        $this->_source = isset($config[1]) ? $config[1] : 'lib';

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

    protected function _parse($directory)
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

    protected function _loadLanguageFiles($source)
    {
        $files = array();

        $strategy = $this->getStrategy();

        foreach ($this->_getLocations($source) as $location => $directory)
        {
            if ($file = $this->_getLanguageFile($directory))
            {
                $files[$location] = $strategy->parse($file);
            }
        }

        return $files;
    }

    protected function _getBaseTranslations($source)
    {
        $translations = array();

        $parts = explode(':', $source);

        if ($parts[0] == 'com')
        {
            // Load Framework translations.
            $library = $this->_loadLanguageFiles('lib');

            if ($parts[1] != 'application')
            {
                // Load Application translations.
                $application = $this->_loadLanguageFiles('com:application');

                // Calculate base by merging library and application translations.
                $translations['component'] = array_merge($library['library'], $application['component']);
                $translations['admin']     = array_merge($library['library'], $application['component'],
                    $application['admin']);
                $translations['site']      = array_merge($library['library'], $application['component'],
                    $application['site']);
            }
            else
            {
                // Use library translations as base.
                $translations['component'] = $library['library'];
                $translations['admin']     = $library['library'];
                $translations['site']      = $library['library'];
            }
        }

        return $translations;
    }

    protected function _isComponent($source)
    {
        $parts = explode(':', $source);
        return $parts[0] == 'com';
    }

    public function getTranslations()
    {
        $strategy  = $this->getStrategy();
        $source = $this->_source;

        foreach ($this->_getLocations($source) as $location => $directory)
        {
            $this->_setLocation($location);
            $this->_parse($directory);
        }

        $files = $this->_loadLanguageFiles($source);

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

                $translations[$location][$key] = array('value' => $value, 'occurrences' => $occurrences);;
            }
        }

        $output = '';

        if (count($translations))
        {
            $output .= "\nTranslations for *** {$source} ***:\n";
        }
        else
        {
            $output .= "\nNo translations where found for  *** {$source} *** ...\n\n";
        }

        $base = $this->_getBaseTranslations($source);

        foreach ($translations as $location => $data)
        {
            $overrides = array();
            $ignored = array();

            // Do not list translations that are already included on base translations (application component
            // and/or framework).
            if ($this->_isComponent($source))
            {
                foreach (array_keys(array_intersect_key($data, $base[$location])) as $key)
                {
                    if (isset($files[$location][$key]))
                    {
                        // Report the override.
                        $overrides[$key] = $data[$key];
                    }
                    else
                    {
                        // Remove it from translations list and notify.
                        $ignored[$key] = $data[$key];
                        unset($data[$key]);
                    }
                }

                if ($location != 'component')
                {
                    // Do not list translations that are already included in the component's layer file unless they
                    // are overrides.
                    foreach (array_keys(array_intersect_key($data, $translations['component'])) as $key)
                    {
                        if (isset($files[$location][$key]))
                        {
                            // Report the override.
                            $overrides[$key] = $data[$key];
                        }
                        else
                        {
                            // Remove it from translations list and notify.
                            unset($data[$key]);
                        }
                    }
                }
            }

            $output .= "\n\n### Location: {$location} ... ###\n\n";
            $output .= $strategy->dump($data);
            $output .= "\n";

            $unreferenced = array_diff_key($files[$location], $translations[$location]);

            // Report non-referenced (not found) translations.
            if (!empty($unreferenced))
            {
                $output .= "!!! Non-referenced translations (present on current location file, but not referenced/present on location): !!!\n\n";
                $output .= $strategy->dump($unreferenced);
                $output .= "\n";
            }

            if (!empty($ignored))
            {
                $output .= "!!! Ignored translations (already present on a base translations file): !!!\n\n";
                $output .= $strategy->dump($ignored);
                $output .= "\n";
            }

            if (!empty($overrides)) {
                $output .= "!!! Overridden translations (present on any base translations file but overridden on current location): !!!\n\n";
                $output .= $strategy->dump($overrides);
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

    protected function _getLocations($source) {

        $parts = explode(':', $source);

        switch ($parts[0])
        {
            case 'lib':
                $locations = array('library' => NOOKU_PATH . '/library');
                break;
            case 'com':
                $component = $parts[1];
                $locations = array(
                    'site'      => NOOKU_PATH . "/application/site/component/{$component}",
                    "admin"     => NOOKU_PATH . "/application/admin/component/{$component}",
                    'component' => NOOKU_PATH . "/component/{$component}");
                break;
            default:
                $locations = array();
                break;
        }

        return $locations;
    }

    protected function _getLanguageFile($directory)
    {
        $strategy = $this->getStrategy();

        $file = $directory . "/resources/language/{$this->_language}." . $strategy->getFileExtension();

        if (!file_exists($file)) $file = null;

        return $file;
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



