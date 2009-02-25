<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Query
 * @copyright	Copyright (C) 2008 Joomlatools. All rights reserved.
 * @license		GNU LGPL <http://www.gnu.org/licenses/lgpl.html>
 */

/**
 * A sql parser
 *
 * This class draws heavily on PEAR:SQL_Parser Copyright (c) 2002-2004 Brent Cook,
 * Erich Enke Released under the LGPL license
 *
 * @author     	Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Query
 */
class KDatabaseQueryParser
{
    /**
     * @var    KDatabaseParserQueryLexer
     */
    public $lexer;

    /**
     * @var    string
     */
    public $token;

    /**
     * @var    array
     */
    public $functions = array();

    /**
     * @var    array
     */
    public $types = array();

    /**
     * @var    array
     */
    public $symbols = array();

    /**
     * @var    array
     */
    public $operators = array();

    /**
     * @var    array
     */
    public $synonyms = array();

    /**
     * @var    array
     */
    public $lexeropts = array();

    /**
     * @var    array
     */
    public $parseropts = array();

    /**
     * @var    array
     */
    public $comments = array();

    /**
     * @var    array
     */
    public $quotes = array();

    /**
     * @var    array
     */
    static public $dialects = array(
        'ANSI',
        'MySQL',
    );

    /**
     *
     */
    const DIALECT_ANSI = 'ANSI';

    /**
     *
     */
    const DIALECT_MYSQL = 'MySQL';

    /**
     * Constructor
     *
     * @param string $string the SQL query to parse
     * @param string $dialect the SQL dialect
     */
    public function __construct($string = null, $dialect = 'MySQL')
    {
        $this->setDialect($dialect);

        if (is_string($string)) {
            $this->initLexer($string);
        }
    }

    function initLexer($string)
    {
        // Initialize the Lexer with a 3-level look-back buffer
        require_once dirname(__FILE__) . '/parser/lexer.php';
        $this->lexer = new KDatabaseQueryParserLexer($string, 3, $this->lexeropts);
        $this->lexer->symbols  =& $this->symbols;
        $this->lexer->comments =& $this->comments;
        $this->lexer->quotes   =& $this->quotes;
    }

    /**
     * loads SQL dialect specific data
     *
     * @param string $dialect the SQL dialect to use
     * @return mixed true on success, otherwise Error
     */
    public function setDialect($dialect)
    {
        if (! in_array($dialect, KDatabaseQueryParser::$dialects)) {
            return $this->raiseError('Unknown SQL dialect:' . $dialect);
        }

        include dirname(__FILE__).'/parser/dialect/' . strtolower($dialect) . '.php';
        $this->types      = array_flip($dialect['types']);
        $this->functions  = array_flip($dialect['functions']);
        $this->operators  = array_flip($dialect['operators']);
        $this->commands   = array_flip($dialect['commands']);
        $this->synonyms   = $dialect['synonyms'];
        $this->symbols    = array_merge(
            $this->types,
            $this->functions,
            $this->operators,
            $this->commands,
            array_flip($dialect['reserved']),
            array_flip($dialect['conjunctions']));
        $this->lexeropts  = $dialect['lexeropts'];
        $this->parseropts = $dialect['parseropts'];
        $this->comments   = $dialect['comments'];
        $this->quotes     = $dialect['quotes'];

        return true;
    }

    /**
     * extracts parameters from a function call
     *
     * this function should be called if an opening brace is found,
     * so the first call to $this->getTok() will return first param
     * or the closing )
     *
     * @param array   &$values to set it
     * @param array   &$types  to set it
     * @param integer $i       position
     * @return mixed true on success, otherwise Error
     */
    public function getParams(&$values, &$types, $i = 0)
    {
        $values = array();
        $types  = array();

        $this->getTok();
        $open_braces = 1;
        while ($open_braces > 0) {
            if (isset($values[$i])) {
                $values[$i] .= '' . $this->lexer->tokText;
                $types[$i]  .= $this->token;
            } else {
                $values[$i] = $this->lexer->tokText;
                $types[$i]  = $this->token;
            }

            $this->getTok();
            if ($this->token === ')') {
                $open_braces--;
            } elseif ($this->token === '(') {
                $open_braces++;
            } elseif ($this->token === ',') {
                $i++;
                $this->getTok();
            }
        }

        return true;
    }

    /**
     *
     * @param string $message error message
     * @return Error
     */
    public function raiseError($message)
    {
        $end = 0;
        if ($this->lexer->string != '') {
            while ($this->lexer->lineBegin + $end < $this->lexer->stringLen
             && $this->lexer->string{$this->lexer->lineBegin + $end} != "\n") {
                $end++;
            }
        }

        $message = 'Parse error: ' . $message . ' on line ' .
            ($this->lexer->lineNo + 1) . "\n";
        $message .= substr($this->lexer->string, $this->lexer->lineBegin, $end);
        $message .= "\n";
        $length   = is_null($this->token) ? 0 : strlen($this->lexer->tokText);
        $message .= str_repeat(' ', abs($this->lexer->tokPtr -
            $this->lexer->lineBegin - $length)) . "^";
        $message .= ' found: "' . $this->lexer->tokText . '"';

        $this->error_message = $message;
        $this->error = true;
        return false;
    }

    /**
     * Returns true if current token is a variable type name, otherwise false
     *
     * @return  boolean  true if current token is a variable type name
     */
    public function isType()
    {
        return isset($this->types[$this->token]);
    }

    /**
     * Returns true if current token is a value, otherwise false
     *
     * @return  boolean  true if current token is a value
     */
    public function isVal()
    {
        return ($this->token == 'real_val' ||
        $this->token == 'int_val' ||
        $this->token == 'text_val' ||
        $this->token == 'null');
    }

    /**
     * Returns true if current token is a function, otherwise false
     *
     * @return  boolean  true if current token is a function
     */
    public function isFunc()
    {
        return isset($this->functions[$this->token]);
    }

    /**
     * Returns true if current token is a command, otherwise false
     *
     * @return  boolean  true if current token is a command
     */
    public function isCommand()
    {
        return isset($this->commands[$this->token]);
    }

    /**
     * Returns true if current token is a reserved word, otherwise false
     *
     * @return  boolean  true if current token is a reserved word
     */
    public function isReserved()
    {
        return isset($this->symbols[$this->token]);
    }

    /**
     * Returns true if current token is an operator, otherwise false
     *
     * @return  boolean  true if current token is an operator
     */
    public function isOperator()
    {
        return isset($this->operators[$this->token]);
    }

    /**
     * retrieves next token
     *
     * @return void
     */
    public function getTok()
    {
        $this->token = $this->lexer->lex();
        //echo $this->token . "\t" . $this->lexer->tokText . "\n";
    }

    /**
     * Parses field/column options, usually  for an CREATE or ALTER TABLE statement
     *
     * @return  array   parsed field options
     */
    public function parseFieldOptions()
    {
        // parse field options
        $namedConstraint = false;
        $options         = array();
        while ($this->token != ',' && $this->token != ')' && $this->token != null ) {
            $option    = $this->token;
            $haveValue = true;
            switch ($option) {
                case 'constraint':
                    $this->getTok();
                    if ($this->token != 'ident') {
                        return $this->raiseError('Expected a constraint name');
                    }
                    $constraintName = $this->lexer->tokText;
                    $namedConstraint = true;
                    $haveValue = false;
                    break;
                case 'default':
                    $this->getTok();
                    if ($this->isVal()) {
                        $constraintOpts = array(
                            'type' => 'default_value',
                            'value' => $this->lexer->tokText,
                        );
                    } elseif ($this->isFunc()) {
                        $results = $this->parseFunctionOpts();
                        if (false === $results) {
                            return $results;
                        }
                        $results['type'] = 'default_function';
                        $constraintOpts  = $results;
                    } else {
                        return $this->raiseError('Expected default value');
                    }
                    break;
                case 'primary':
                    $this->getTok();
                    if ($this->token != 'key') {
                        return $this->raiseError('Expected "key"');
                    }
                    $constraintOpts = array(
                        'type'  => 'primary_key',
                        'value' => true,
                    );
                    break;
                case 'not':
                    $this->getTok();
                    if ($this->token != 'null') {
                        return $this->raiseError('Expected "null"');
                    }
                    $constraintOpts = array(
                        'type'  => 'not_null',
                        'value' => true,
                    );
                    break;
                case 'check':
                    $this->getTok();
                    if ($this->token != '(') {
                        return $this->raiseError('Expected (');
                    }

                    $results = $this->parseSearchClause();
                    if (false === $results) {
                        return $results;
                    }

                    $results['type'] = 'check';
                    $constraintOpts  = $results;
                    if ($this->token != ')') {
                        return $this->raiseError('Expected )');
                    }
                    break;
                case 'unique':
                    $this->getTok();
                    if ($this->token != '(') {
                        return $this->raiseError('Expected (');
                    }

                    $constraintOpts = array('type'=>'unique');
                    $this->getTok();
                    while ($this->token != ')') {
                        if ($this->token != 'ident') {
                            return $this->raiseError('Expected an identifier');
                        }
                        $constraintOpts['column_names'][] = $this->lexer->tokText;
                        $this->getTok();
                        if ($this->token != ')' && $this->token != ',') {
                            return $this->raiseError('Expected ) or ,');
                        }
                    }

                    if ($this->token != ')') {
                        return $this->raiseError('Expected )');
                    }
                    break;
                case 'month':
                case 'year':
                case 'day':
                case 'hour':
                case 'minute':
                case 'second':
                    $intervals = array(
                        array(
                            'month' => 0,
                            'year'  => 1,
                        ),
                        array(
                            'second' => 0,
                            'minute' => 1,
                            'hour'   => 2,
                            'day'    => 3,
                        )
                    );

                    foreach ($intervals as $class) {
                        if (isset($class[$option])) {
                            $constraintOpts = array(
                            'quantum_1' => $this->token,
                            );
                            $this->getTok();
                            if ($this->token == 'to') {
                                $this->getTok();
                                if (! isset($class[$this->token])) {
                                    return $this->raiseError(
                                        'Expected interval quanta');
                                }

                                if ($class[$this->token] >=
                                $class[$constraintOpts['quantum_1']]
                                ) {
                                    return $this->raiseError($this->token
                                        . ' is not smaller than ' .
                                        $constraintOpts['quantum_1']);
                                }
                                $constraintOpts['quantum_2'] = $this->token;
                            } else {
                                $this->lexer->unget();
                            }
                            break;
                        }
                    }
                    if (!isset($constraintOpts['quantum_1'])) {
                        return $this->raiseError('Expected interval quanta');
                    }
                    $constraintOpts['type'] = 'values';
                    break;
                case 'null':
                    $haveValue = false;
                    break;
                case 'auto_increment':
                    $constraintOpts = array(
                        'type'  => 'auto_increment',
                        'value' => true,
                    );
                    break;
                default:
                    return $this->raiseError('Unexpected token '
                            . $this->lexer->tokText);
            }

            if ($haveValue) {
                if ($namedConstraint) {
                    $options['constraints'][$constraintName] = $constraintOpts;
                    $namedConstraint = false;
                } else {
                    $options['constraints'][] = $constraintOpts;
                }
            }
            $this->getTok();
        }
        return $options;
    }

    /**
     * parses conditions usually used in WHERE or ON
     *
     * @param boolean $subSearch  deprecated?
     * @return  array   parsed condition
     */
    public function parseSearchClause($subSearch = false)
    {
        $clause = array();
        // parse the first argument
        $this->getTok();
        if ($this->token == 'not') {
            $clause['neg'] = true;
            $this->getTok();
        }

        $foundSubclause = false;

        if ($this->isReserved()) {
            return $this->raiseError('Expected a column name or value');
        }

        if ($this->token == '(') {
            $clause['arg_1']['value'] = $this->parseSearchClause(true);
            $clause['arg_1']['type']  = 'subclause';
            if ($this->token != ')') {
                return $this->raiseError('Expected ")"');
            }
            $foundSubclause = true;
        } else {
            $arg = $this->lexer->tokText;
            $argtype = $this->token;
            $this->getTok();
            if ($this->token == '.') {
                $this->getTok();
                if ($this->token != 'ident') {
                    return $this->raiseError('Expected a column name');
                }
                $arg .= '.'.$this->lexer->tokText;
            } else {
                $this->lexer->pushBack();
            }
            $clause['arg_1']['value'] = $arg;
            $clause['arg_1']['type']  = $argtype;
        }

        // parse the operator
        if (!$foundSubclause) {
            $this->getTok();
            if (!$this->isOperator()) {
                return $this->raiseError('Expected an operator');
            }
            $clause['op'] = $this->token;

            $this->getTok();
            switch ($clause['op']) {
                case 'is':
                    // parse for 'is' operator
                    if ($this->token == 'not') {
                        $clause['neg'] = true;
                        $this->getTok();
                    }
                    if ($this->token != 'null') {
                        return $this->raiseError('Expected "null"');
                    }
                    $clause['arg_2']['value'] = '';
                    $clause['arg_2']['type']  = $this->token;
                    break;
                case 'not':
                    // parse for 'not in' operator
                    if ($this->token != 'in') {
                        return $this->raiseError('Expected "in"');
                    }
                    $clause['op']  = $this->token;
                    $clause['neg'] = true;
                    $this->getTok();
                case 'in':
                    // parse for 'in' operator
                    if ($this->token != '(') {
                        return $this->raiseError('Expected "("');
                    }

                    // read the subset
                    $this->getTok();
                    // is this a subselect?
                    if ($this->token == 'select') {
                        $clause['arg_2']['value'] = $this->parseSelect(true);
                        $clause['arg_2']['type']  = 'command';
                    } else {
                        $this->lexer->pushBack();
                        // parse the set
                        $result = $this->getParams($clause['arg_2']['value'],
                            $clause['arg_2']['type']);
                        if (false === $result) {
                            return $result;
                        }
                    }
                    if ($this->token != ')') {
                        return $this->raiseError('Expected ")"');
                    }
                    break;
                case 'and': case 'or':
                    $this->lexer->unget();
                    break;
                default:
                    if ($this->isFunc()) {
                        $result = $this->parseFunctionOpts();
                        if (false === $result) {
                            return $result;
                        }
                        $clause['arg_2']['value'] = $result;
                        $clause['arg_2']['type']  = 'function';
                    } else if ($this->isReserved()) {
                        // parse for in-fix binary operators
                        return $this->raiseError('Expected a column name or value');
                    }
                    if ($this->token == '(') {
                        $clause['arg_2']['value'] = $this->parseSearchClause(true);
                        $clause['arg_2']['type']  = 'subclause';
                        $this->getTok();
                        if ($this->token != ')') {
                            return $this->raiseError('Expected ")"');
                        }
                    } else {
                        $arg = $this->lexer->tokText;
                        $argtype = $this->token;
                        $this->getTok();
                        if ($this->token == '.') {
                            $this->getTok();
                            if ($this->token != 'ident') {
                                return $this->raiseError('Expected a column name');
                            }
                            $arg .= '.'.$this->lexer->tokText;
                        } else {
                            $this->lexer->pushBack();
                        }
                        $clause['arg_2']['value'] = $arg;
                        $clause['arg_2']['type']  = $argtype;
                    }
            }
        }

        $this->getTok();
        if ($this->token == 'and' || $this->token == 'or') {
            $op = $this->token;
            $subClause = $this->parseSearchClause($subSearch);
            if (false === $subClause) {
                return $subClause;
            }
            $clause = array('arg_1' => $clause,
                'op' => $op,
                'arg_2' => $subClause,
            );
        } else {
            $this->lexer->unget();
        }
        return $clause;
    }

    /**
     *
     * @return mixed array parsed field list on success, otherwise Error
     */
    public function parseFieldList()
    {
        $this->getTok();
        if ($this->token != '(') {
            return $this->raiseError('Expected (');
        }

        $fields = array();
        while (1) {
            // parse field identifier
            $this->getTok();
            if ($this->token == ',') {
                continue;
            }
            // In this context, field names can be reserved words or function names
            if ($this->token == 'primary') {
                $this->getTok();
                if ($this->token != 'key') {
                    $this->raiseError('Expected key');
                }
                $this->getTok();
                if ($this->token != '(') {
                    $this->raiseError('Expected (');
                }
                $this->getTok();
                if ($this->token != 'ident') {
                    $this->raiseError('Expected identifier');
                }
                $name = $this->lexer->tokText;
                $this->getTok();
                if ($this->token != ')') {
                    $this->raiseError('Expected )');
                }
                $fields[$name]['constraints'][] = array(
                    'type'  => 'primary_key',
                    'value' => true,
                );
                continue;
            } elseif ($this->token == 'key') {
                $this->getTok();
                if ($this->token != 'ident') {
                    $this->raiseError('Expected identifier');
                }
                $key = $this->lexer->tokText;
                $this->getTok();
                if ($this->token != '(') {
                    $this->raiseError('Expected (');
                }
                $this->getTok();
                $rows = array();
                while ($this->token != ')') {
                    if ($this->token != 'ident') {
                        $this->raiseError('Expected identifier');
                    }
                    $name= $this->lexer->tokText;
                    $fields[$name]['constraints'][] = array(
                        'type'  => 'key',
                        'value' => $key,
                    );
                    $this->getTok();
                }
                continue;
            } elseif ($this->token == 'ident' || $this->isFunc()
             || $this->isReserved()) {
                $name = $this->lexer->tokText;
            } elseif ($this->token == ')') {
                return $fields;
            } else {
                //return $this->raiseError('Expected identifier');
            }

            // parse field type
            $this->getTok();
            if (! $this->isType($this->token)) {
                return $this->raiseError('Expected a valid type');
            }
            $type = $this->token;

            $this->getTok();
            // handle special case two-word types
            if ($this->token == 'precision') {
                // double precision == double
                if ($type == 'double') {
                    return $this->raiseError('Unexpected token');
                }
                $this->getTok();
            } elseif ($this->token == 'varying') {
                // character varying() == varchar()
                if ($type != 'character' && $type != 'varchar') {
                    return $this->raiseError('Unexpected token');
                }
                $this->getTok();
            }
            $fields[$name]['type'] = $this->synonyms[$type];
            // parse type parameters
            if ($this->token == '(') {
                $results = $this->getParams($values, $types);
                if (false === $results) {
                    return $results;
                }
                switch ($fields[$name]['type']) {
                    case 'numeric':
                        if (isset($values[1])) {
                            if ($types[1] != 'int_val') {
                                return $this->raiseError('Expected an integer');
                            }
                            $fields[$name]['decimals'] = $values[1];
                        }
                    case 'float':
                        if ($types[0] != 'int_val') {
                            return $this->raiseError('Expected an integer');
                        }
                        $fields[$name]['length'] = $values[0];
                        break;
                    case 'char':
                    case 'varchar':
                    case 'integer':
                    case 'int':
                    case 'tinyint' :
                        if (sizeof($values) != 1) {
                            return $this->raiseError('Expected 1 parameter');
                        }
                        if ($types[0] != 'int_val') {
                            return $this->raiseError('Expected an integer');
                        }
                        $fields[$name]['length'] = $values[0];
                        break;
                    case 'set':
                    case 'enum':
                        if (! sizeof($values)) {
                            return $this->raiseError('Expected a domain');
                        }
                        $fields[$name]['domain'] = $values;
                        break;
                    default:
                        if (sizeof($values)) {
                            return $this->raiseError('Unexpected )');
                        }
                }
                $this->getTok();
            }

            $options = $this->parseFieldOptions();
            if (false === $options) {
                return $options;
            }

            $fields[$name] += $options;

            if ($this->token == ')') {
                return $fields;
            } elseif (is_null($this->token)) {
                return $this->raiseError('Expected )');
            }
        }

        return $fields;
    }

    /**
     * Parses parameters in a function call
     *
     * @return mixed array parsed function options on success, otherwise Error
     */
    public function parseFunctionOpts()
    {
        $function     = $this->token;
        $opts['name'] = $function;
        $this->getTok();
        if ($this->token != '(') {
            return $this->raiseError('Expected "("');
        }

        $this->getParams($opts['arg'], $opts['type']);

        switch ($function . '--') {
            case 'count':
                $this->getTok();
                switch ($this->token) {
                    case 'distinct':
                        $opts['distinct'] = true;
                        $this->getTok();
                        /*
                        if ($this->token != 'ident') {
                            return $this->raiseError('Expected a column name');
                        }
                    case 'ident':
                    case '*':
                        $arg = $this->lexer->tokText;
                        $argtype = $this->token;
                        $this->getTok();
                        if ($this->token == '.') {
                            $this->getTok();
                            if ($this->token != 'ident') {
                                return $this->raiseError('Expected a column name');
                            }
                            $arg .= '.'.$this->lexer->tokText;
                        } else {
                            $this->lexer->pushBack();
                        }
                        $opts['arg'][]  = $arg;
                        $opts['type'][] = $argtype;
                        break;
                    default:
                        return $this->raiseError('Invalid argument');
                        */
                }
                //break;
            case 'concat':
                /*
                while ($this->token != ')') {
                    switch ($this->token) {
                        case 'ident':
                        case 'text_val':
                            $opts['arg'][]  = $this->lexer->tokText;
                            $opts['type'][] = $this->token;
                            break;
                        case ',':
                            // do nothing
                            break;
                        default:
                            return $this->raiseError('Expected a string or a column name');
                    }
                    $this->getTok();
                }
                $this->lexer->pushBack();
                break;
                */
            case 'date_format':
                /*
                if ($this->token != 'ident' && $this->token != 'text_val') {
                    return $this->raiseError('Expected a string or column name');
                }

                $opts['arg'][]  = $this->lexer->tokText;
                $opts['type'][] = $this->token;
                $this->getTok();
                if ($this->token != ',') {
                    return $this->raiseError('Expected a comma');
                }
                $this->getTok();
                if ($this->token != 'text_val') {
                    return $this->raiseError('Expected a string value');
                }
                $opts['arg'][] = $this->lexer->tokText;
                $opts['type'][] = $this->token;
                break;
                */
            default:
                //$this->getParams($opts['arg'], $opts['type']);
                /*
                if ($this->token != ')') {
                    $arg = $this->lexer->tokText;
                    $argtype = $this->token;
                    $this->getTok();
                    if ($this->token == '.') {
                        $this->getTok();
                        if ($this->token != 'ident') {
                            return $this->raiseError('Expected a column name');
                        }
                        $arg .= '.'.$this->lexer->tokText;
                    } else {
                        $this->lexer->pushBack();
                    }
                    $opts['arg'][]  = $arg;
                    $opts['type'][] = $argtype;
                } else {
                    $this->lexer->pushBack();
                }
                break;
                */
        }
        //echo $this->token;
        /*
        $this->getTok();
        if ($this->token != ')') {
            return $this->raiseError('Expected ")" ');
        }
        */

        // check for an alias
        $this->getTok();
        if ($this->token == ',' || $this->token == 'from') {
            $this->lexer->pushBack();
        } elseif ($this->token == 'as') {
            $this->getTok();
            if ($this->token != 'ident' ) {
                return $this->raiseError('Expected column alias');
            }
            $opts['alias'] = $this->lexer->tokText;
        } elseif ($this->token != null) {
            if ($this->token != 'ident' ) {
                return $this->raiseError('Expected column alias, from, or comma');
            }
            $opts['alias'] = $this->lexer->tokText;
        }
        return $opts;
    }

    /**
     *
     * @return mixed array parsed create on success, otherwise Error
     */
    public function parseCreate()
    {
        $this->getTok();
        switch ($this->token) {
            case 'table':
                $tree = array('command' => 'create_table');
                $this->getTok();
                if ($this->token != 'ident') {
                    return $this->raiseError('Expected table name');
                }
                $tree['table_names'][] = $this->lexer->tokText;
                $fields = $this->parseFieldList();
                if (false === $fields) {
                    return $fields;
                }
                $tree['column_defs'] = $fields;
                // $tree['column_names'] = array_keys($fields);
                break;
            case 'index':
                $tree = array('command' => 'create_index');
                break;
            case 'constraint':
                $tree = array('command' => 'create_constraint');
                break;
            case 'sequence':
                $tree = array('command' => 'create_sequence');
                break;
            default:
                return $this->raiseError('Unknown object to create');
        }
        return $tree;
    }

    /**
     *
     * @return mixed array parsed insert on success, otherwise Error
     */
    public function parseInsert()
    {
        $this->getTok();
        if ($this->token != 'into') {
            return $this->raiseError('Expected "into"');
        }
        $tree = array('command' => 'insert');
        $this->getTok();
        if ($this->token != 'ident') {
            return $this->raiseError('Expected table name');
        }
        $tree['table_names'][] = $this->lexer->tokText;

        $this->getTok();
        if ($this->token == '(') {
            $results = $this->getParams($values, $types);
            if (false === $results) {
                return $results;
            } elseif (sizeof($values)) {
                $tree['column_names'] = $values;
            }
            $this->getTok();
        }

        if ($this->token != 'values') {
            return $this->raiseError('Expected "values"');
        }
        // get opening brace '('
        $this->getTok();
        $results = $this->getParams($values, $types);
        if (false === $results) {
            return $results;
        }
        if (isset($tree['column_defs'])
         && sizeof($tree['column_defs']) != sizeof($values)) {
            return $this->raiseError('field/value mismatch');
        }
        if (! sizeof($values)) {
            return $this->raiseError('No fields to insert');
        }
        foreach ($values as $key => $value) {
            $values[$key] = array(
                'value' => $value,
                'type'  => $types[$key],
            );
        }
        $tree['values'] = $values;
        return $tree;
    }

    /**
     * UPDATE tablename SET (colname = (value|colname) (,|WHERE searchclause))+
     *
     * @todo This is incorrect.  multiple where clauses would parse
     * @return mixed array parsed update on success, otherwise Error
     */
    public function parseUpdate()
    {
        $this->getTok();
        if ($this->token != 'ident') {
            return $this->raiseError('Expected table name, found: ' . $this->token);
        }

        $tree = array('command' => 'update');
        $tree['table_names'][] = $this->lexer->tokText;

        $this->getTok();
        if ($this->token != 'set') {
            return $this->raiseError('Expected "set"');
        }

        while (true) {
            $this->getTok();
            if ($this->token != 'ident') {
                return $this->raiseError('Expected a column name');
            }
            $tree['column_names'][] = $this->lexer->tokText;
            $this->getTok();
            if ($this->token != '=') {
                return $this->raiseError('Expected =');
            }
            $this->getTok();
            if ($this->isVal($this->token) || $this->token == 'ident') {
                $tree['values'][] = array(
                    'value' => $this->lexer->tokText,
                    'type'  => $this->token,
                );
            } else {
                $this->getParams($values, $types);
                $tree['values'][] = array(
                    'value' => $values[0],
                    'type'  => $types[0],
                );
            }
            $this->getTok();
            if ($this->token == 'where') {
                $clause = $this->parseSearchClause();
                if (false === $clause) {
                    return $clause;
                }
                $tree['where_clause'] = $clause;
                break;
            } elseif ($this->token != ',') {
                return $this->raiseError('Expected "where" or ","');
            }
        }
        return $tree;
    }

    /**
     * DELETE FROM tablename WHERE searchclause
     *
     * @return mixed array parsed delete on success, otherwise Error
     */
    public function parseDelete()
    {
		$this->getTok();
        if ($this->token != 'from') {
            return $this->raiseError('Expected "from"');
        }
        $tree = array('command' => 'delete');
        $this->getTok();
        if ($this->token != 'ident') {
            return $this->raiseError('Expected a table name');
        }
        $tree['table_names'][] = $this->lexer->tokText;
        $this->getTok();
        if ($this->token != 'where') {
            return $this->raiseError('Expected "where"');
        }
        $clause = $this->parseSearchClause();
        if (false === $clause) {
            return $clause;
        }
        $tree['where_clause'] = $clause;
        return $tree;
    }

    /**
     *
     * @return mixed array parsed drop on success, otherwise Error
     */
    public function parseDrop()
    {
        $this->getTok();
        switch ($this->token) {
            case 'table':
                $tree = array('command' => 'drop_table');
                $this->getTok();
                if ($this->token != 'ident') {
                    return $this->raiseError('Expected a table name');
                }
                $tree['table_names'][] = $this->lexer->tokText;
                $this->getTok();
                if ($this->token == 'restrict'
                 || $this->token == 'cascade') {
                    $tree['drop_behavior'] = $this->token;
                }
                $this->getTok();
                if (! is_null($this->token)) {
                    return $this->raiseError('Unexpected token');
                }
                return $tree;
                break;
            case 'index':
                $tree = array('command' => 'drop_index');
                break;
            case 'constraint':
                $tree = array('command' => 'drop_constraint');
                break;
            case 'sequence':
                $tree = array('command' => 'drop_sequence');
                break;
            default:
                return $this->raiseError('Unknown object to drop');
        }
        return $tree;
    }

    /**
     *
     * @return mixed array parsed select on success, otherwise Error
     */
    public function parseSelect($subSelect = false)
    {
        $tree = array('command' => 'select');
        $this->getTok();
        if ($this->token == 'distinct' || $this->token == 'all') {
            $tree['set_quantifier'] = $this->token;
            $this->getTok();
        }
        /*
        if ($this->token != 'ident'
         && ! $this->isFunc()
         && $this->token != '*') {
            return $this->raiseError('Expected columns or a set function');
        }
        */

        while ($this->token != 'from' && $this->token != null) {
            if ($this->token == 'ident') {
                $prevTok = $this->token;
                $prevTokText = $this->lexer->tokText;
                $this->getTok();
                if ($this->token == '.') {
                    $columnTable = $prevTokText;
                    $this->getTok();
                    $prevTok     = $this->token;
                    $prevTokText = $this->lexer->tokText;
                } else {
                    $columnTable = '';
                }

                if ($prevTok != 'ident' && $this->token != '*') {
                    return $this->raiseError('Expected column name');
                }
                $columnName = $prevTokText;

                if ($this->token == 'as') {
                    $this->getTok();
                    if ($this->token != 'ident' ) {
                        return $this->raiseError('Expected column alias');
                    }
                    $columnAlias = $this->lexer->tokText;
                } else {
                    $columnAlias = '';
                }

                $tree['column_tables'][]  = $columnTable;
                $tree['column_names'][]   = $columnName;
                $tree['column_aliases'][] = $columnAlias;
                if ($this->token != 'from') {
                    $this->getTok();
                }
                if ($this->token == ',') {
                    $this->getTok();
                }
            } elseif ($this->token == '*') {
                $tree['column_names'][]   = '*';
                $tree['column_tables'][]  = '';
                $tree['column_aliases'][] = '';
                $this->getTok();
            } elseif ($this->isFunc()) {
                if (! isset($tree['set_quantifier'])) {
                    $result = $this->parseFunctionOpts();
                    if (false === $result) {
                        return $result;
                    }
                    $tree['set_function'][] = $result;
                    $this->getTok();

                    if ($this->token == 'as') {
                        $this->getTok();
                        if ($this->token != 'ident' ) {
                            return $this->raiseError('Expected column alias');
                        }
                        $columnAlias = $this->lexer->tokText;
                    } else {
                        $columnAlias = '';
                    }
                } else {
                    return $this->raiseError('Cannot use "'
                        . $tree['set_quantifier'] . '" with ' . $this->token);
                }
            } elseif ($this->token == ',') {
                $this->getTok();
            } else {
                $tree['column_values'][] = $this->lexer->tokText;
                $tree['column_names'][] = $this->lexer->tokText;
                $tree['column_tables'][] = '';
                $tree['column_aliases'][] = '';
                //return $this->raiseError('Unexpected token "'.$this->token.'"');
                $this->getTok();
            }
        }
        if ($this->token != 'from') {
            // from is not required in SELECT statements
            //return $this->raiseError('Expected "from"');
            return $tree;
        }
        $this->getTok();
        while ($this->token == 'ident') {
            $tree['table_names'][] = $this->lexer->tokText;
            $this->getTok();
            if ($this->token == 'ident') {
                $tree['table_aliases'][] = $this->lexer->tokText;
                $this->getTok();
            } elseif ($this->token == 'as') {
                $this->getTok();
                if ($this->token != 'ident') {
                    return $this->raiseError('Expected table alias');
                }
                $tree['table_aliases'][] = $this->lexer->tokText;
                $this->getTok();
            } else {
                $tree['table_aliases'][] = '';
            }
            if ($this->token == 'on') {
                $clause = $this->parseSearchClause();
                if (false === $clause) {
                    return $clause;
                }
                $tree['table_join_clause'][] = $clause;
            } else {
                $tree['table_join_clause'][] = '';
            }
            if ($this->token == ',') {
                $tree['table_join'][] = ',';
                $this->getTok();
            } elseif ($this->token == 'join') {
                $tree['table_join'][] = 'join';
                $this->getTok();
            } elseif (($this->token == 'cross') ||
            ($this->token == 'inner')) {
                // (CROSS|INNER) JOIN
                $join = $this->lexer->tokText;
                $this->getTok();
                if ($this->token != 'join') {
                    return $this->raiseError('Expected token "join"');
                }
                $tree['table_join'][] = $join.' join';
                $this->getTok();
            } elseif (($this->token == 'left') ||
            ($this->token == 'right')) {
                // (LEFT|RIGHT) OUTER? JOIN
                $join = $this->lexer->tokText;
                $this->getTok();
                if ($this->token == 'join') {
                    $tree['table_join'][] = $join.' join';
                } elseif ($this->token == 'outer') {
                    $join .= ' outer';
                    $this->getTok();
                    if ($this->token != 'join') {
                        return $this->raiseError('Expected token "join"');
                    }
                    $tree['table_join'][] = $join.' join';
                } else {
                    return $this->raiseError('Expected token "outer" or "join"');
                }
                $this->getTok();
            } elseif ($this->token == 'natural') {
                // NATURAL ((LEFT|RIGHT) OUTER?)? JOIN
                $join = $this->lexer->tokText;
                $this->getTok();
                if ($this->token == 'join') {
                    $tree['table_join'][] = $join.' join';
                } elseif (($this->token == 'left') ||
                ($this->token == 'right')) {
                    $join .= ' '.$this->token;
                    $this->getTok();
                    if ($this->token == 'join') {
                        $tree['table_join'][] = $join.' join';
                    } elseif ($this->token == 'outer') {
                        $join .= ' '.$this->token;
                        $this->getTok();
                        if ($this->token == 'join') {
                            $tree['table_join'][] = $join.' join';
                        } else {
                            return $this->raiseError('Expected token "join" or "outer"');
                        }
                    } else {
                        return $this->raiseError('Expected token "join" or "outer"');
                    }
                } else {
                    return $this->raiseError('Expected token "left", "right" or "join"');
                }
                $this->getTok();
            } elseif ($this->token == 'where'
                   || $this->token == 'order'
                   || $this->token == 'limit'
                   || is_null($this->token)) {
                break;
            }
        }
        while (! is_null($this->token) && (!$subSelect || $this->token != ')')
         && $this->token != ')') {
            switch ($this->token) {
                case 'where':
                    $clause = $this->parseSearchClause();
                    if (false === $clause) {
                        return $clause;
                    }
                    $tree['where_clause'] = $clause;
                    break;
                case 'order':
                    $this->getTok();
                    if ($this->token != 'by') {
                        return $this->raiseError('Expected "by"');
                    }
                    $this->getTok();
                    while ($this->token == 'ident') {
                        $arg = $this->lexer->tokText;
                        $this->getTok();
                        if ($this->token == '.') {
                            $this->getTok();
                            if ($this->token == 'ident') {
                                $arg .= '.'.$this->lexer->tokText;
                            } else {
                                return $this->raiseError('Expected a column name');
                            }
                        } else {
                            $this->lexer->pushBack();
                        }
                        $col = $arg;
                        //$col = $this->lexer->tokText;
                        $this->getTok();
                        if (isset($this->synonyms[$this->token])) {
                            $order = $this->synonyms[$this->token];
                            if (($order != 'asc') && ($order != 'desc')) {
                                return $this->raiseError('Unexpected token');
                            }
                            $this->getTok();
                        } else {
                            $order = 'asc';
                        }
                        if ($this->token == ',') {
                            $this->getTok();
                        }
                        $tree['sort_order'][$col] = $order;
                    }
                    break;
                case 'limit':
                    $this->getTok();
                    if ($this->token != 'int_val') {
                        return $this->raiseError('Expected an integer value');
                    }
                    $length = $this->lexer->tokText;
                    $start = 0;
                    $this->getTok();
                    if ($this->token == ',') {
                        $this->getTok();
                        if ($this->token != 'int_val') {
                            return $this->raiseError('Expected an integer value');
                        }
                        $start  = $length;
                        $length = $this->lexer->tokText;
                        $this->getTok();
                    }
                    $tree['limit_clause'] = array('start'=>$start,
                    'length'=>$length);
                    break;
                case 'group':
                    $this->getTok();
                    if ($this->token != 'by') {
                        return $this->raiseError('Expected "by"');
                    }
                    $this->getTok();
                    while ($this->token == 'ident') {
                        $arg = $this->lexer->tokText;
                        $this->getTok();
                        if ($this->token == '.') {
                            $this->getTok();
                            if ($this->token == 'ident') {
                                $arg .= '.'.$this->lexer->tokText;
                            } else {
                                return $this->raiseError('Expected a column name');
                            }
                        } else {
                            $this->lexer->pushBack();
                        }
                        $col = $arg;
                        //$col = $this->lexer->tokText;
                        $this->getTok();
                        if ($this->token == ',') {
                            $this->getTok();
                        }
                        $tree['group_by'][] = $col;
                    }
                    break;
                default:
                    return $this->raiseError('Unexpected clause');
            }
        }
        return $tree;
    }

    /**
     *
     * @param string $string the SQL
     * @return  array   parsed data
     */
    public function parse($string = null)
    {
        if (is_string($string)) {
            $this->initLexer($string);
        } elseif (! $this->lexer instanceof SQL_Parser_Lexer) {
            $this->raiseError('No initial string specified');
            return array('empty' => true);
        }

        // get query action
        $this->getTok();
        switch ($this->token) {
            case null:
                // null == end of string
                $this->raiseError('Nothing to do');
                return array('empty' => true);
            case 'select':
                return $this->parseSelect();
            case 'update':
                return $this->parseUpdate();
            case 'insert':
                return $this->parseInsert();
            case 'delete':
                return $this->parseDelete();
            case 'create':
                return $this->parseCreate();
            case 'drop':
                return $this->parseDrop();
            default:
                $this->raiseError('Unknown action: ' . $this->token);
                return array('command' => 'unknown');
        }
    }
}