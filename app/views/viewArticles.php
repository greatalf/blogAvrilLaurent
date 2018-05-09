<?php
$this->_t = 'Articles';
foreach ($posts as $post):?>
<a style = "color: white;" href="article&id=<?= $post->id() ?>"><h3><?= $post->title() ?></h3></a>
<p><?=  nl2br($post->content()) ?></p>
<hr>
<?php endforeach; ?>
