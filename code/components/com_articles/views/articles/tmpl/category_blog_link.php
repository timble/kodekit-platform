<? $article_slug  = $article->id.($article->slug ? ':'.$article->slug : '') ?>
<? $category_slug = $article->category_id.($article->category_slug ? ':'.$article->category_slug : '') ?>

<li>
	<a class="blogsection" href="<?= @route(ContentHelperRoute::getArticleRoute($article_slug, $category_slug, $article->section_id)) ?>">
		<?= @escape($article->title) ?>
	</a>
</li>