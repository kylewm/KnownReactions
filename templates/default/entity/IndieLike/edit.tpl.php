<?php

if (empty($likeof = $vars['likeof'])) {
    $likeof = '';
}

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

                <label for="likeof">URL to Like</label>
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
                  <input required class="form-control" type="text" name="description" id="description"/>
                </div>       
            </div>
       
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
     $('#description-container').hide();
     $('#likeof').on('change', function () {
         var url = $(this).val();
         if (url != '') {
             $('#description-spinner').show();
             
             var endpoint = "<?= \Idno\Core\Idno::site()->config()->getDisplayURL() ?>indiereactions/fetch";
             $.get(endpoint, {"url": url}, function success(result) {
                 var desc = '';
                 if (result.author && result.author.name) {
                     desc += result.author.name + "'s ";
                 }

                 if (result.name) {
                     desc += result.name;
                 } else if (url.contains('twitter.com')) {
                     if (!desc) { desc += 'a '; }
                     desc += 'tweet';
                 } else {
                     var host = url.replace(/^https?:\/\//, '');
                     host = host.substring(0,host.indexOf('/')); 
                     if (!desc) { desc += 'a '; }
                     desc += 'post on ' + host;
                 }

                 $('#description').val(desc);
                 $('#description-spinner').hide();
                 $('#description-container').show();
             });
         }
     });

 }); 

 
</script>
