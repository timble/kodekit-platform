<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access');

$parts   = $url->getQuery(true);
$package = substr($parts['option'], 4);
$view    = $parts['view'];

unset($parts['option']);
unset($parts['view']);

$action =  KInflector::isSingular($view) ? 'read' : 'browse';

echo @service('admin::com.'.$package.'.controller.'.KInflector::singularize($view))->setRequest($parts)->$action();