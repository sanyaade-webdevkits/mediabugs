<div class="feature_archive">
<h3><? $doc->permalink(); ?></h3>
<p class="byline"><?= date('F j, Y',strtotime($doc->date)); ?> by <?= $doc->author()->nick; ?></p>
<p><?= $doc->shorten('body',200); ?></p>
</div>