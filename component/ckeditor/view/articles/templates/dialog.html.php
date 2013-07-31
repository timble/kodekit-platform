
<div id="article-compact">
    <div id="article-insert" class="content">
        <div id="article-tree-container">
            <div id="article-tree">
                <h3><?= @text('Categories')?></h3>
                <?= @template('com:ckeditor.view.menus.list.html', array('menus' => @object('com:pages.model.menus')->sort('title')->application('site')->getRowset())); ?>
            </div>
        </div>
        <div id="article-grid">
            <h3><?= @text('Articles')?></h3>
            <ul id="articles-list">

            </ul>
        </div>
        <div id="details">
            <div id="article-preview">

            </div>
        </div>
    </div>
</div>

<script>
    window.addEvent('domready', function() {
        document.id('details').adopt(document.id('link-insert-form'));
        var links = document.id('article-tree').getElements('a');

        links.each(function(link) {
            link.addEvent('click',function(e) {
                e.stop();
                var active = $$('.active');
                if(active){
                    active.removeClass('active');
                }
                this.set('class','active');
                $('articles-list').empty();
                var page_items = 'index.php?option=com_pages&view=pages&menu='+this.get('data-menu-id')+"&publishes=1&format=json";
                var items = new Array();

                var jsonRequest = new Request.JSON({
                    url: page_items,
                    onSuccess: function(result){

                        /* do something with results */
                        result.items.each(function(item){
                            //Create new li to insert

                            var newLi = new Element('li',{ class:'level'+item.data.level});
                            var sef = new Array();
                            var path = item.data.path.split("/");
                            items[item.data.id] = item.data.slug;

                            for(var i=0; i< path.length; i++){
                                sef.push(items[path[i]]);
                            }

                            var el = new Element('a', {

                                title: item.data.title,
                                text: item.data.title,
                                'sef-url': sef.join("/"),
                                href : item.data.link_url,

                                events: {
                                    'click': function(e) {
                                        e.preventDefault();
                                        document.id('link-url').set('value', this.get('sef-url'));
                                        document.id('link-title').set('value', this.get('title'));

                                    }
                                }

                            });

                            newLi.adopt(el);

                            $('articles-list').adopt(newLi);
                        });
                    }
                }).get();
            });
        });
    });
</script>

<div id="link-insert-form">
    <fieldset>
        <div>
            <label for="link-url"><?= @text('URL') ?></label>
            <div>
                <input type="text" id="link-url" value="" />
            </div>
        </div>
        <div>
            <label for="link-text"><?= @text('Text') ?></label>
            <div>
                <input type="text" id="link-text" value="" />
            </div>
        </div>
        <div>
            <label for="link-alt"><?= @text('Description') ?></label>
            <div>
                <input type="text" id="link-alt" value="" />
            </div>
        </div>
        <div>
            <label for="link-title"><?= @text('Title') ?></label>
            <div>
                <input type="text" id="link-title" value="" />
            </div>
        </div>
    </fieldset>
</div>