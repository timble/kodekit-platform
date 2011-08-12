<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.mootools'); ?>
<?= @helper('behavior.keepalive'); ?>
<?= @helper('behavior.tooltip'); ?>

<?= @helper('behavior.modal'); ?>

<style src="media://system/css/mootree.css" />
<style src="media://com_files/css/files.css" />

<? if (version_compare(JVERSION, '1.7.0', '<')): ?>
<script src="media://com_files/js/delegation.js" />
<? endif; ?>

<script src="media://com_files/js/ejs/ejs.js" />

<script src="media://lib_koowa/js/koowa.js" />
<script src="media://system/js/mootree.js" />

<script src="media://com_files/js/files.filesize.js" />
<script src="media://com_files/js/files.template.js" />
<script src="media://com_files/js/files.container.js" />
<script src="media://com_files/js/files.tree.js" />
<script src="media://com_files/js/files.row.js" />
<script src="media://com_files/js/files.paginator.js" />
<script src="media://com_files/js/files.pathway.js" />

<script src="media://com_files/js/files.app.js" />
<script>

if (SqueezeBox.open === undefined) {
	SqueezeBox = $extend(SqueezeBox, {
		open: function(subject, options) {
			this.initialize();

			if (this.element != null) this.trash();
			this.element = document.id(subject) || false;

			this.setOptions($merge(this.presets, options || {}));

			if (this.element && this.options.parse) {
				var obj = this.element.getProperty(this.options.parse);
				if (obj && (obj = JSON.decode(obj, this.options.parseSecure))) this.setOptions(obj);
			}
			this.url = ((this.element) ? (this.element.get('href')) : subject) || this.options.url || '';

			this.assignOptions();

			var handler = handler || this.options.handler;
			if (handler) return this.setContent(handler, this.parsers[handler].call(this, true));
			var ret = false;
			return this.parsers.some(function(parser, key) {
				var content = parser.call(this);
				if (content) {
					ret = this.setContent(key, content);
					return true;
				}
				return false;
			}, this);
		},
		trash: function() {
			this.element = this.asset = null;
			this.content.empty();
			this.options = {};
			this.removeEvents().setOptions(this.presets).callChain();
		}
	});
}

</script>