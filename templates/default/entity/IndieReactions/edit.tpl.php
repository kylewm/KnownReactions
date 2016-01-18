<?php

$object = $vars['object'];
$is_like = $object instanceof \IdnoPlugins\IndieReactions\IndieLike;
$target = $is_like ? $object->likeof : $object->repostof;
$desc = $vars['object']->description;
$body = $vars['object']->body;
$body_id = 'body'.rand(0,9999);

?>

<?= $this->draw('entity/edit/header'); ?>
<form action="<?= $vars['object']->getURL() ?>" method="post">

    <div class="row">
        <div class="col-md-8 col-md-offset-2 edit-pane">
            <h4><?= $vars['title'] ?></h4>

            <div class="content-form">

                <label for="target">URL</label>
                <input required class="form-control" type="url" name="target" id="target"
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
                    if (!$is_like) {
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
       
            <?= $this->draw('content/access'); ?>
            <p class="button-bar" >
                <?= \Idno\Core\Idno::site()->actions()->signForm('/like/edit') ?>
                <button class="btn btn-cancel" onclick="hideContentCreateForm();">Cancel</button>
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
             
             var endpoint = "<?= \Idno\Core\Idno::site()->config()->getDisplayURL() ?>indiereactions/fetch";
             $.get(endpoint, {"url": url}, function success(result) {
                 var desc = '', body = '';
                 if (result.author && result.author.name) {
                     desc += result.author.name.trim() + "'s ";
                 }
                 if (result.name) {
                     var title = result.name.trim().replace(/\s{2,}/g, ' ');
                     desc += title;
                 } else if (url.contains('twitter.com')) {
                     if (desc == '') { desc += 'a '; }
                     desc += 'tweet';
                 } else {
                     if (desc == '') { desc += 'a '; }
                     desc += 'post on ' + url.replace(/^\w+:\/+([^\/]+).*/, '$1');;
                 }

                 if (result.content) {
                     body = result.content;
                 }

                 $('#description').val(desc);
                 $('#<?= $body_id ?>').val(body);

                 $('#description-spinner').hide();
                 $('#description-container').show();
             });
         }
     });
 }); 

 
</script>
