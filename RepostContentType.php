<?php

    namespace IdnoPlugins\IndieReactions {

        class RepostContentType extends \Idno\Common\ContentType {

            public $title = 'Repost';
            public $category_title = 'Reposts';
            public $entity_class = 'IdnoPlugins\\IndieReactions\\IndieRepost';
            public $indieWebContentType = array('repost');

        }

    }