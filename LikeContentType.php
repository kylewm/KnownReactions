<?php

    namespace IdnoPlugins\IndieReactions {

        class LikeContentType extends \Idno\Common\ContentType {

            public $title = 'Like';
            public $category_title = 'Likes';
            public $entity_class = 'IdnoPlugins\\IndieReactions\\IndieLike';
            public $indieWebContentType = array('like');

        }

    }