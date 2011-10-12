<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.tooltip') ?>
<?= @helper('behavior.validator') ?>

<script src="media://lib_koowa/js/koowa.js" />

<script>
if(Form && Form.Validator) {
    Form.Validator.add('validate-count', {
        errorMsg: <?= json_encode(@text('Please enter a higher number than 0.')) ?>,
        test: function(field){
            return field.get('value').toInt() > 0;
        }
    });
}
</script>

<form action="" method="post" class="-koowa-form">
    <div class="grid_8">
        <div class="panel title group">
        	<input class="inputbox required" type="text" name="name" id="title" size="40" maxlength="255" value="<?= $newsfeed->name; ?>" placeholder="<?= @text( 'Title' ); ?>" />
            <label for="slug">
                <?= @text( 'Slug' ); ?>:
                <input class="inputbox" type="text" name="slug" id="slug" size="40" maxlength="255" value="<?= $newsfeed->slug; ?>" placeholder="<?= @text( 'Slug' ); ?>" />
            </label>
        </div>
        <fieldset class="adminform">
            <legend><?= @text( 'Details' ) ?></legend>
            <table class="admintable">
            <tr>
                <td class="key">
                    <label for="link">
                        <?= @text( 'Link' ) ?>:
                    </label>
                </td>
                <td>
                    <input class="inputbox required validate-url" type="text" size="60" name="link" id="link" value="<?= $newsfeed->link ?>" />
                </td>
            </tr>
            <tr>
                <td class="key">
                    <label for="numarticles">
                        <?= @text( 'Number of Articles' ) ?>:
                    </label>
                </td>
                <td>
                    <input class="inputbox required validate-integer validate-count" type="text" size="2" name="numarticles" id="numarticles" value="<?= $newsfeed->numarticles ?>" />
                </td>
            </tr>
            <tr>
                <td class="key">
                    <label for="cache_time">
                        <?= @text( 'Cache time' ) ?>:
                    </label>
                </td>
                <td>
                    <input class="inputbox required validate-integer validate-count" type="text" size="4" name="cache_time" id="cache_time" value="<?= $newsfeed->cache_time ?>" />
                </td>
            </tr>
            <tr>
                <td class="key">
                    <label for="rtl">
                        <?= @text( 'RTL feed' ) ?>:
                    </label>
                </td>
                <td>
                    <?= @helper('select.booleanlist', array('name' => 'rtl', 'selected' => $newsfeed->rtl)) ?>
                </td>
            </tr>
            </table>
        </fieldset>
    </div>
    <div class="grid_4">
        <div class="panel">
            <h3><?= @text( 'Publish' ); ?></h3>
            <table class="admintable">
    		<tr>
    		    <td class="key">
    		        <label for="enabled">
    		            <?= @text( 'Published' ) ?>:
    		        </label>
    		    </td>
    		    <td>
    		        <?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $newsfeed->enabled)) ?>
    		    </td>
    		</tr>
    		<tr>
    		    <td class="key">
    		        <label for="catid">
    		            <?= @text( 'Category' ) ?>:
    		        </label>
    		    </td>
    		    <td>
	    			<?= @helper('listbox.category', array('name' => 'catid', 'selected' => $newsfeed->catid, 'attribs' => array('id' => 'catid', 'class' => 'required'))) ?>
    		    </td>
    		</tr>
    		</table>
    	</div>
    </div>
</form>