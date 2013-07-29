
<div id="article-compact">
    <div id="article-insert" class="content">
        <div id="article-tree-container">
            <div id="article-tree">
                <h3><?= @text('Categories')?></h3>
                <?= @template('com:articles.view.categories.list.html', array('categories' => @object('com:articles.model.categories')->sort('title')->table('articles')->getRowset())); ?>
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
                $('articles-list').empty();
                var jsonRequest = new Request.JSON({
                    url: this.get('href')+"&format=json",
                    onSuccess: function(result){

                        /* do something with results */
                        result.items.each(function(item){
                            //Create new li to insert
                            var newLi = new Element('li');
                            var el = new Element('a', {

                                title: item.data.title,
                                text: item.data.title,
                                href: item.href,
                                events: {
                                    'click': function(e) {
                                        e.preventDefault();
                                        var article = new Request.JSON({
                                            url: this.get('href'),
                                            onSuccess :function(article){
                                                console.log(article);
                                                $('article-preview').empty();
                                                var text = new Element ('div');

                                                text.set('html',article.item.introtext+article.item.fulltext)
                                                $('article-preview').adopt(text);

                                                document.id('link-url').set('value', article.href);
                                                document.id('link-title').set('value', article.item.title);
                                            }
                                        }).get();

                                    }
                                }

                            });

                            newLi.adopt(el);
                            // Insert li into UL
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