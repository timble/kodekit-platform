<?
/**
 * @version     $Id: form_component.php 3035 2011-10-09 16:57:12Z johanjanssens $
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

<fieldset class="form-horizontal">
    <? $model = $this->getView()->getModel() ?>

    <? if($state->type['name'] == 'component') : ?>
        <?= $page->getParams('url')->render('urlparams') ?>
    <? endif ?>
</fieldset>

<fieldset class="form-horizontal">
    <?= $page->getParams('component')->render() ?>
</fieldset>

<? $advanced_parameters = $page->params_advanced ?>
<? if($rendered_parameters = $advanced_parameters->render('params')) : ?>
<fieldset class="form-horizontal">
    <legend><?= @text('Advanced') ?></legend>
    <?= $rendered_parameters ?>
</fieldset>
<? endif ?>
