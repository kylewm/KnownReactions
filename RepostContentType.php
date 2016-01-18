<?php

    namespace IdnoPlugins\Reactions {

        class RepostContentType extends \Idno\Common\ContentType {

            public $title = 'Repost';
            public $category_title = 'Reposts';
            public $entity_class = 'IdnoPlugins\\Reactions\\Repost';
            public $indieWebContentType = array('repost');

        }

    }