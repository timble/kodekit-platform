<script src="assets://attachments/js/jquery.Jcrop.min.js" />
<style src="assets://attachments/css/jquery.Jcrop.min.css" />

<script src="assets://js/koowa.js" />
<script src="assets://attachments/js/attachments.list.js" />
<script src="assets://files/js/uri.js" />

<script>
    window.addEvent('domready', function() {
        new Attachments.List({
            container: 'attachment',
            action: '<?= route('view=attachments') ?>',
            token: '<?= $this->getObject('user')->getSession()->getToken() ?>'
        });
    });
</script>



<div id="attachment">
    <img id="target" src="attachments/<?= $attachment->path ?>" />

    <a class="btn btn-mini btn-success" href="#" data-action="crop" data-id="<?= $attachment->id; ?>">
        <i class="icon-ok icon-white"></i>
    </a>
</div>


