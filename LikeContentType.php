<?php

    namespace IdnoPlugins\Reactions {

        class LikeContentType extends \Idno\Common\ContentType {

            public $title = 'Like';
            public $category_title = 'Likes';
            public $entity_class = 'IdnoPlugins\\Reactions\\Like';
            public $indieWebContentType = array('like');
            
        }

    }