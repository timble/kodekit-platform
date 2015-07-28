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
 * Debug Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateHelperDebug extends TemplateHelperAbstract
{
    /**
     * Returns an HTML string of information about a single variable.
     *
     * @param array $config
     * @internal param mixed $value variable to dump
     * @internal param int $length maximum length of strings
     * @internal param int $level_recursion recursion limit
     * @return  string
     */
    public function dump($config = array())
    {
        // Have to do this to avoid array to KObjectConfig conversion
        $value = $config['value'];
        unset($config['value']);

        $config = new ObjectConfigJson($config);
        $config->append(array(
            'length'          => 128,
            'level_recursion' => 0
        ));

        return $this->_dump($value, $config->length, $config->level_recursion);
    }

    /**
     * Removes Joomla root from a filename replacing them with the plain text equivalents.
     *
     * Useful for debugging when you want to display a shorter path.
     *
     * @param 	array 	$config An optional array with configuration options
     * @return	string	Html
     */
    public function path($config = array())
    {
        $config = new ObjectConfigJson($config);
        $config->append(array(
            'file'  => '',
            'root'  => \Nooku::getInstance()->getRootPath()
        ));

        $html = $config->file;
        if (strpos($config->file, $config->root) === 0) {
            $html = 'ROOT/'.trim(substr($config->file, strlen($config->root)), DIRECTORY_SEPARATOR);
        }

        return $html;
    }

    /**
     * Returns an HTML string, highlighting a specific line of a file, with some number of lines padded above and below.
     *
     *  @param 	array 	$config An optional array with configuration options
     * @return	string	Html
     */
    public function source($config = array())
    {
        $config = new ObjectConfigJson($config);
        $config->append(array(
            'padding' => 5,
            'file'    => '',
            'line'    => ''
        ));

        $file        = $config->file;
        $line_number = $config->line;
        $padding     = $config->padding;

        $html = '';

        // Continuing will cause errors
        if ($file && is_readable($file))
        {
            // Open the file and set the line position
            $file = fopen($file, 'r');
            $line = 0;

            // Set the reading range
            $range = array('start' => $line_number - $padding, 'end' => $line_number + $padding);

            // Set the zero-padding amount for line numbers
            $format = '% '.strlen($range['end']).'d';

            while (($row = fgets($file)) !== FALSE)
            {
                // Increment the line number
                if (++$line > $range['end']) {
                    break;
                }

                if ($line >= $range['start'])
                {
                    // Make the row safe for output
                    $row = htmlspecialchars($row, ENT_NOQUOTES, 'utf-8');

                    // Trim whitespace and sanitize the row
                    $row = '<span class="number">'.sprintf($format, $line).'</span> '.$row;

                    // Apply highlighting to this row
                    if ($line === $line_number) {
                        $row = '<span class="line highlight">'.$row.'</span>';
                    } else {
                        $row = '<span class="line">'.$row.'</span>';
                    }

                    // Add to the captured source
                    $html .= $row;
                }
            }

            // Close the file
            fclose($file);

            $html = '<pre class="source"><code>'.$html.'</code></pre>';
        }

        return $html;
    }

    /**
     * Returns an array of HTML strings that represent each step in the backtrace.
     *
     * @param 	array 	$config An optional array with configuration options
     * @return	string	Html
     */
    public function trace($config = array())
    {
        $config = new ObjectConfigJson($config);
        $config->append(array(
            'trace' => null
        ));

        $trace = $config->trace;

        // Start a new trace
        if ($trace === NULL) {
            $trace = debug_backtrace();
        }

        // Non-standard function calls
        $statements = array('include', 'include_once', 'require', 'require_once');

        $output = array();
        foreach ($trace as $step)
        {
            // Invalid trace step
            if (!isset($step['function'])) {
                continue;
            }

            // Include the source of this step
            if (isset($step['file']) AND isset($step['line'])) {
                $source = $this->source(array('file' => $step['file'], 'line' => $step['line']));
            }

            if (isset($step['file']))
            {
                $file = $step['file'];

                if (isset($step['line'])) {
                    $line = $step['line'];
                }
            }

            // function()
            $function = $step['function'];

            if (in_array($step['function'], $statements))
            {
                // No arguments
                if (empty($step['args'])) {
                    $args = array();
                } else {
                    $args = array($step['args'][0]);
                }
            }
            elseif (isset($step['args']))
            {
                $params = NULL;

                // Introspection on closures or language constructs in a stack trace is impossible
                if (function_exists($step['function']) || strpos($step['function'], '{closure}') === FALSE)
                {
                    if (isset($step['class']))
                    {
                        if (method_exists($step['class'], $step['function'])) {
                            $reflection = new \ReflectionMethod($step['class'], $step['function']);
                        } else {
                            $reflection = new \ReflectionMethod($step['class'], '__call');
                        }
                    }
                    else $reflection = new \ReflectionFunction($step['function']);

                    // Get the function parameters
                    $params = $reflection->getParameters();
                }

                $args = array();
                foreach ($step['args'] as $i => $arg)
                {
                    if (isset($params[$i])) {
                        $args[$params[$i]->name] = $arg;  // Assign the argument by the parameter name
                    } else {
                        $args[$i] = $arg; // Assign the argument by number
                    }
                }
            }

            // Class->method() or Class::method()
            if (isset($step['class']))
            {
                $type = $step['type'];

                //Support for xdebug
                if($step['type'] == "dynamic") {
                    $type = '->';
                }

                //Support for xdebug
                if($step['type'] == "static") {
                    $type = '::';
                }

                $function = $step['class'].$type.$step['function'];
            }

            $output[] = array(
                'function' => $function,
                'args'     => isset($args)   ? $args : NULL,
                'file'     => isset($file)   ? $file : NULL,
                'line'     => isset($line)   ? $line : NULL,
                'source'   => isset($source) ? $source : NULL,
            );

            unset($function, $args, $file, $line, $source);
        }

        return $output;
    }

    /**
     * Helper for dump(), handles recursion in arrays and objects.
     *
     * @param   mixed   $var    variable to dump
     * @param   integer $length maximum length of strings
     * @param   integer $limit  recursion limit
     * @param   integer $level  current recursion level (internal usage only!)
     * @return  string
     */
    protected function _dump(&$var, $length = 128, $limit = 10, $level = 0)
    {
        $output = array();

        if ($var === NULL) {
            return '<small>NULL</small>';
        }

        if (is_bool($var)) {
            return '<small>bool</small> '.($var ? 'TRUE' : 'FALSE');
        }

        if (is_float($var)) {
            return '<small>float</small> '.$var;
        }

        if (is_resource($var))
        {
            if (($type = get_resource_type($var)) === 'stream' AND $meta = stream_get_meta_data($var))
            {
                $meta = stream_get_meta_data($var);

                if (isset($meta['uri']))
                {
                    $file = $meta['uri'];

                    if (stream_is_local($file)) {
                        $file = $this->path($file);
                    }

                    return '<small>resource</small><span>('.$type.')</span> '.htmlspecialchars($file, ENT_NOQUOTES, 'utf-8');
                }
            }
            else return '<small>resource</small><span>('.$type.')</span>';
        }

        if (is_string($var))
        {
            if (mb_strlen($var) > $length) {
                $str = htmlspecialchars(mb_substr($var, 0, $length), ENT_NOQUOTES, 'utf-8').'&nbsp;&hellip;';
            } else {
                $str = htmlspecialchars($var, ENT_NOQUOTES, 'utf-8');
            }

            return '<small>string</small><span>('.strlen($var).')</span> "'.$str.'"';
        }

        if (is_array($var))
        {
            static $marker;

            // Indentation for this variable
            $space = str_repeat($s = '    ', $level);

            // Make a unique marker
            if ($marker === NULL) {
                $marker = uniqid("\x00");
            }

            if (!empty($var))
            {
                if (!isset($var[$marker]))
                {
                    if ($level < $limit)
                    {
                        $output[] = "<span>(";

                        $var[$marker] = TRUE;
                        foreach ($var as $key => & $val)
                        {
                            if ($key === $marker) {
                                continue;
                            }

                            if ( ! is_int($key)) {
                                $key = '"'.htmlspecialchars($key, ENT_NOQUOTES, 'utf-8').'"';
                            }

                            $output[] = "$space$s$key => ".$this->_dump($val, $length, $limit, $level + 1);
                        }

                        unset($var[$marker]);

                        $output[] = "$space)</span>";
                    }
                    else $output[] = "(\n$space$s...\n$space)";
                }
                else  $output[] = "(\n$space$s*RECURSION*\n$space)";
            }

            return '<small>array</small><span>('.count($var).')</span> '.implode("\n", $output);
        }

        if (is_object($var))
        {
            // Objects that are being dumped
            static $objects = array();

            // Copy the object as an array
            $array = (array) $var;

            // Indentation for this variable
            $space = str_repeat($s = '    ', $level);
            $hash  = spl_object_hash($var);

            if (!empty($var))
            {
                if (!isset($objects[$hash]))
                {
                    if ($level < $limit)
                    {
                        $output[] = "<code>{";

                        $objects[$hash] = TRUE;
                        foreach ($array as $key => & $val)
                        {
                            if ($key[0] === "\x00")
                            {
                                // Determine if the access is protected or protected
                                $access = '<small>'.(($key[1] === '*') ? 'protected' : 'private').'</small>';

                                // Remove the access level from the variable name
                                $key = substr($key, strrpos($key, "\x00") + 1);
                            }
                            else $access = '<small>public</small>';

                            $output[] = "$space$s$access $key => ".$this->_dump($val, $length, $limit, $level + 1);
                        }
                        unset($objects[$hash]);

                        $output[] = "$space}</code>";
                    }
                    else $output[] = "{\n$space$s...\n$space}";
                }
                else $output[] = "{\n$space$s*RECURSION*\n$space}";

            }

            return '<small>object</small> <span>'.get_class($var).'('.count($array).')</span> '.implode("\n", $output);
        }
        else return '<small>'.gettype($var).'</small> '.htmlspecialchars(print_r($var, TRUE), ENT_NOQUOTES, 'utf-8');
    }
}