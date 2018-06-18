<?php
$this->_t = 'Articles';
Session::flash();

foreach($posts as $post):?>
<a style = "color: #FFFFFF;" href="article&id=<?= $post->id() ?>"><h3><?= $post->title() ?></h3></a>
<p><?=  nl2br($post->content()) ?></p>
<hr>
<?php endforeach; ?>
