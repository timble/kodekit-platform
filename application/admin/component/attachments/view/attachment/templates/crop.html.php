<script src="assets://attachments/js/jquery.Jcrop.min.js" />
<style src="assets://attachments/css/jquery.Jcrop.min.css" />

<script src="assets://js/koowa.js" />
<script src="assets://attachments/js/attachments.list.js" />
<script src="assets://files/js/uri.js" />

<script>
    window.addEvent('domready', function() {
        new Attachments.List({
            container: 'attachment',
            action: '<?= route('view=attachment&format=json&layout=crop&id='.$attachment->id) ?>',
            token: '<?= $this->getObject('user')->getSession()->getToken() ?>'
        });
    });
</script>



<div id="attachment">

    <a class="btn btn-mini btn-success" href="#" data-action="crop" data-id="<?= $attachment->id; ?>">
        <i class="icon-ok icon-white"></i>
    </a>
    <img id="target" src="attachments/<?= $attachment->path ?>" />
</div>


