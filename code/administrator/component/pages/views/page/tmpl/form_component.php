<?
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?
$query = array(
    'option' => $state->type['option'],
    'view'   => $state->type['view']
);

if(!empty($state->type['layout']) && $state->layout != 'default') {
    $query['layout'] = $state->layout;
}
?>

<input type="hidden" name="link_url" value="<?= http_build_query($query) ?>" />
<? $model = $this->getView()->getModel() ?>

<? if($state->type['name'] == 'component') : ?>
<?= $page->getParams('url')->render('urlparams') ?>
<? endif ?> 
<?= $page->getParams('component')->render() ?>

<? if($rendered_params = $page->getParams('layout')->render('params')) : ?>
    <?= $rendered_params ?>
<? endif ?>