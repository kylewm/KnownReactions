<?php

    $class = "u-repost-of";
    $icon = '<i class="fa fa-retweet"></i> ';

if (!empty($vars['object']->pageTitle)) {
    $body = $vars['object']->pageTitle;
	$thisTitle = $vars['object']->pageTitle;
} else {
    $body = $vars['object']->body;
	$thisTitle = $vars['object']->description;
}

	$url = $vars['object']->repostof;
	$description = $vars['object']->description;
			
?>
<div class="known-bookmark">
    <?php

    if (empty($vars['feed_view'])) {

        ?>
            <h2 class="idno-bookmark"><?php echo $icon?><?php echo $thisTitle ?>
            </h2>
        <?php

    }

    if (!empty($vars['object']->body)) {
        echo '<div class="e-content">';
            echo $this->__(['value' => $vars['object']->body, 'object' => $vars['object']])->draw('forms/output/richtext');
        ?>
		<a href="<?php echo $url ?>" class="<?php echo $class ?> p-name"
                target="_blank">Link</a>
        <?php
        echo '</div>';

    }



    if (!empty($vars['object']->tags)) {

        echo $this->__(['tags' => $vars['object']->tags])->draw('forms/output/tags');
    }

    ?>
</div>
<?php echo $this->draw('entity/content/embed');
