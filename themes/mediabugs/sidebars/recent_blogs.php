<?

    define('MAGPIE_CACHE_DIR', $POD->libOptions('cacheDir'));
	define('MAGPIE_INPUT_ENCODING', 'UTF-8');
	define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');

	include_once("magpierss/rss_fetch.inc");


	$er = error_reporting(0);



	$max_items = 3;	
	$posts = array();
	$feed = "http://www.mediabugs.org/blog/feed/";
	$rss = fetch_rss($feed);
	if ($rss && $rss->items) { 
	$count = 0;	
	foreach ($rss->items as $item) {
		if ($count < $max_items) { 
		
			$post = array();
			$post['date'] = strtotime($item['pubdate']);
			$post['author'] = $item['dc']['creator'];
			$post['link'] = $item['link'];
			$post['title'] = $item['title'];
			$post['short'] = $item['description'];
			$post['long'] = $item['content']['encoded'];

			$posts[] = $post;
		}
		$count++;
	
	}
	} 
	
	?>

<div class="column_padding" id="recent_blogs_sidebar">
	<h3 class="big"><a href="http://mediabugs.org/blog">From the MediaBugs Blog</a></h3>

	<? foreach ($posts as $item) { ?>
	<div class="blog_post">
		<h3><a href="<?= $item['link']; ?>"><?= htmlspecialchars($item['title']); ?></a></h3>
		<p class="byline"><?= date('F d, Y',$item['date']); ?> | <?= $item['author'] ?></p>
		<p><?= $item['short']; ?></p>
	</div>
	<? } ?>
</div>