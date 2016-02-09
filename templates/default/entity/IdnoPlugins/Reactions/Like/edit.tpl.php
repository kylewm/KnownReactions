<?php

$object = $vars['object'];
$type = $object->getActivityStreamsObjectType();
$target = $type == 'like' ? $object->likeof : $object->repostof;
$target_name = $type == 'like' ? 'like-of' : 'repost-of';
$desc = $vars['object']->description;
$body = $vars['object']->body;
$body_id = 'body'.rand(0,9999);

if ($type == 'like') {
    $title = $object->getID() ? 'Edit Like' : 'New Like';
} else {
    $title = $object->getID() ? 'Edit Repost' : 'New Repost';
}

?>

<?= $this->draw('entity/edit/header'); ?>
<form action="<?= $object->getURL() ?>" method="post">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 edit-pane">
            <h4><?= $title ?></h4>

            <div class="content-form">

                <label for="target">URL</label>
                <input required class="form-control" type="url" name="<?= $target_name ?>" id="target"
                       placeholder="http://..." value="<?= $target ?>" />

                <div id="description-spinner-container">
                    <div class="spinner" id="description-spinner" style="display:none">
                        <div class="bounce1"></div>
                        <div class="bounce2"></div>
                        <div class="bounce3"></div>
                    </div>
                </div>

                <div id="description-container">
                    <label for="description">Description</label>
                    <input required class="form-control" type="text" name="description" id="description" value="<?= $desc ?>"/>
                    <?php
                    if ($type == 'share') {
                        echo "<label for=\"$body_id\">Body</label>";
                        echo $this->__([
                            'name' => 'body',
                            'unique_id' => $body_id,
                            'value' => $body,
                        ])->draw('forms/input/richtext');
                    }
                    ?>
                </div>
            </div>

            <?php echo $this->drawSyndication($type, $object->getPosseLinks()); ?>

            <?= $this->draw('content/access'); ?>
            <p class="button-bar" >
                <?= \Idno\Core\Idno::site()->actions()->signForm('/like/edit') ?>
                <button type="button" class="btn btn-cancel" onclick="hideContentCreateForm();">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </p>

        </div>
    </div>

</form>
<?= $this->draw('entity/edit/footer'); ?>

<script>
 $(function() {
     if ($("#description").val() == '') {
         $('#description-container').hide();
     }
     $('#target').change(function () {
         var url = $(this).val();
         if (url != '') {
             $('#description-spinner').show();

             var endpoint = "<?= \Idno\Core\Idno::site()->config()->getDisplayURL() ?>reactions/fetch";
             $.get(endpoint, {"url": url}, function success(result) {
                 $('#description').val(result.description || '');
                 $('#<?= $body_id ?>').val(result.content || '');
                 $('#description-spinner').hide();
                 $('#description-container').show();
             });
         }
     });
 });


</script>
