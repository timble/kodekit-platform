<?php

ini_set('xdebug.max_nesting_level', 2000);

$old = error_reporting();
error_reporting($old & ~E_STRICT);

define('JVERSION', '2.5');
define('_JEXEC', 1);
define('JPATH_BASE', '/Users/ercan/www/docman/administrator');

require_once '/Users/ercan/www/docman/libraries/import.php';

$language = JFactory::getLanguage();
$language->load('lib_joomla');

function import_koowa()
{
    $base = '/Users/ercan/Projects/joomlatools/koowa/code';
    require_once $base.'/libraries/koowa/koowa.php';
    
    Koowa::getInstance(array(
        'cache_enabled' => false
    ));
    
    KClassLoader::getInstance()->registerLocator(new KClassLocatorComponent(array('basepath' => $base)));
    
    KObjectIdentifier::addLocator(KObjectManager::getInstance()->getObject('koowa:object.locator.component'));
    
    KObjectIdentifier::setApplication('admin', $base.'/administrator');
    
    //Setup the request
    KRequest::root(str_replace('/administrator', '', KRequest::base()));
}
import_koowa();

class KTranslatorCallVisitor extends PHPParser_NodeVisitorAbstract
{
    public $logger;
    
    public static $_prev_node = false;
    
    public function __construct($logger) {
        $this->logger = $logger;    
    }
    
    public function enterNode(PHPParser_Node $node) 
    {
        if ($node instanceof PHPParser_Node_Expr_MethodCall
            && is_string($node->name)
            && $node->name === 'translate') 
        {
            $this->logNode($node->args[0]->value);
            
            // Check for $this->translate('string')
            // (for template helpers)
            /*if ((string)$node->var->name === 'this') {
                $this->logNode($node->args[0]->value);
            }
            
            // Check for $this->getObject('translator')->getTranslator('identifier')->translate('string')
            if ((string)$node->var->name === 'getTranslator') {
                $this->logNode($node->args[0]->value);
            }
            
            // Check for $this->getObject('translator')->translate('string')
            if ((string)$node->var->name === 'getObject'
                && $node->var->args[0]->value instanceof PHPParser_Node_Scalar_String
                && ($node->var->args[0]->value->value === 'translator'
                    || strpos($node->var->args[0]->value->value, '.translator'))
             ) {
                $this->logNode($node->args[0]->value);
            }*/
        }
        
        if ($node instanceof PHPParser_Node_Expr_MethodCall
        		&& is_string($node->name)
        		&& $node->name === 'choose')
        {
        	$this->logNode($node->args[0]->value);
        }

        // Check for @translate('string');
        // (for layouts)
        if ($node instanceof PHPParser_Node_Expr_ErrorSuppress) {
            if ((string)$node->expr->name === 'text') {
                $this->logNode($node->expr->args[0]->value);
            }
        }
    }
    
    public function logNode($node)
    {
        $this->logger->logNode($node);
    }
}

class LanguageFileGenerator 
{
    public $strings = array();
    public $errors = array();
    
    public $current_file;
    
    public $directory;
    
    public function __construct($directory)
    {
        global $language;
        
        $this->directory = $directory;
        
        $this->language = JFactory::getLanguage(); 
        
        $this->translator = $this->getTranslator();

        $this->parser = new PHPParser_Parser(new PHPParser_Lexer);
        $this->traverser = new PHPParser_NodeTraverser;
        
        $this->traverser->addVisitor(new KTranslatorCallVisitor($this));
        
        $this->parseDir($this->directory);
    }
    
    public function logNode($node)
    {
        if ($value = $node->value) {
            $existing = array_map(function ($a) { return $a['name']; }, $this->strings);
            if (in_array($value, $existing)) {
                return;
            }
            
            $this->strings[] = array(
                'file' => $this->current_file,
                'line' => $node->getAttribute('startLine'),
                'name' => $value
            );
        } elseif ($node instanceof PHPParser_Node_Expr_Array) {
        	foreach ($node->items as $item) {
        		$this->logNode($item->value);
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
    
    public function parseDir() {
        foreach (new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->directory),
            RecursiveIteratorIterator::LEAVES_ONLY)
            as $file) {

            if ($file->getExtension() !== 'php') {
                continue;
            }
    
            try {
                $code = file_get_contents($file);
                $this->current_file = $file->getRealPath();
    
                $stmts = $this->parser->parse($code);
                
                /*$nodeDumper = new PHPParser_NodeDumper;
                echo $nodeDumper->dump($stmts);*/
                $stmts = $this->traverser->traverse($stmts);
            } catch (PHPParser_Error $e) {
                echo 'Parse Error: ', $e->getMessage();
            }
        }
    }
    
    public function getTranslator()
    {
        $translator = KObjectManager::getInstance()->getObject('com://admin/koowa.translator');
        
        return $translator;
    }
    
    public function getTranslationFile()
    {
        $output = '';
        $previous_file = null;

        $alias_catalogue = $this->translator->getAliasCatalogue();

        foreach ($this->strings as $string) {
            $key = $string['name'];
            if ($this->language->hasKey($key)) {
                continue;
            }
            if (isset($alias_catalogue[strtolower($key)])) {
                continue;
            }
            if ($previous_file !== $string['file']) {
                $previous_file = $string['file'];
                $dir = str_replace(rtrim($this->directory, '/').'/', '', $previous_file);
                if (substr($dir, 0, 5) === 'site/') {
                    $dir = substr($dir, 5);
                }
                $output .= "\n;".$dir."\n";
            }
            $output .= $this->translator->getKey($key).'="'.str_replace('"', '"_QQ_"', $key)."\"\n";
        }
        
        return $output;
    }
    
    public function getErrorMessage()
    {
        if (!count($this->errors)) {
            return '';
        }
        
        $output = "Following calls to KTranslator are found as well but they are not called with strings:\n";
        $output .= "--------------------------------------------------------------------------------\n";
        foreach ($this->errors as $error) {
            $output .= 'File: '.str_replace(rtrim($this->directory, '/').'/', '', $error['file'])."\n";
            $output .= 'Line: '.$error['line']."\n";
            if (!empty($error['name'])) {
                $output .= "Type: Variable \n";
                $output .= 'Variable name: '.$error['name']."\n";
            } else {
                $output .= 'Type: '.$error['type']."\n";
            }
            $output .= "-----------------------------\n";
        }
        
        return $output;
    }
}



