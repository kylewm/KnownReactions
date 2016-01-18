<?php

$likeof = $vars['object']->likeof;
$desc = $vars['object']->description;

?>

<?= $this->draw('entity/edit/header'); ?>
<form action="<?= $vars['object']->getURL() ?>" method="post">

    <div class="row">
        <div class="col-md-8 col-md-offset-2 edit-pane">
            <h4>                
                <?php
                if (empty($vars['object']->_id)) {
                    echo 'New Like';
                } else {
                    echo 'Edit Like';
                }
                ?>
            </h4>

            <div class="content-form">

                <label for="likeof">URL</label>
                <input required class="form-control" type="url" name="likeof" id="likeof"
                       placeholder="http://..." value="<?= $likeof ?>" />

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
     $('#likeof').change(function () {
         var url = $(this).val();
         if (url != '') {
             $('#description-spinner').show();
             
             var endpoint = "<?= \Idno\Core\Idno::site()->config()->getDisplayURL() ?>indiereactions/fetch";
             $.get(endpoint, {"url": url}, function success(result) {
                 var desc = '';
                 if (result.author && result.author.name) {
                     desc += result.author.name.trim() + "'s ";
                 }

                 if (result.name) {
                     var title = result.name.trim().replace(/\s{2,}/g, ' ');
                     desc += title;
                 } else if (url.contains('twitter.com')) {
                     if (!desc) { desc += 'a '; }
                     desc += 'tweet';
                 } else {
                     if (!desc) { desc += 'a '; }
                     desc += 'post on ' + url.replace(/^\w+:\/+([^\/]+).*/, '$1');;
                 }

                 $('#description').val(desc);
                 $('#description-spinner').hide();
                 $('#description-container').show();
             });
         }
     });
 }); 

 
</script>
