<?php


namespace IdnoPlugins\Reactions {

    use Idno\Core\Idno;
    use Idno\Core\Event;
    use Idno\Common\ContentType;

    class Main extends \Idno\Common\Plugin {

        function registerEventHooks()
        {
            parent::registerEventHooks();
            Idno::site()->addEventHook('plugins/loaded', function (Event $evt) {
                // make sure our content types are before IdnoPlugins\Like\ContentType
                $bookmarkType = '\IdnoPlugins\Like\ContentType';
                if (class_exists($bookmarkType)) {

                    $bookmarkIndex = -1;
                    for ($ii = 0 ; $ii < count(ContentType::$registered) ; $ii++) {
                        $contentType = ContentType::$registered[$ii];
                        if ($contentType instanceof $bookmarkType) {
                            $bookmarkIndex = $ii;
                            break;
                        }
                    }

                    if ($bookmarkIndex > -1) {
                        foreach (['LikeContentType', 'RepostContentType'] as $targetType) {
                            $targetType = $this->getNamespace() . '\\' . $targetType;
                            for ($ii = 0 ; $ii < count(ContentType::$registered) ; $ii++) {
                                $contentType = ContentType::$registered[$ii];
                                if ($contentType instanceof $targetType) {
                                    array_splice(ContentType::$registered, $ii, 1);
                                    array_splice(ContentType::$registered, $bookmarkIndex, 0, [$contentType]);
                                    $bookmarkIndex++;
                                    break;
                                }
                            }
                        }
                    }
                }
            });

            Idno::site()->addEventHook('share/types', function (Event $evt) {
                $types = $evt->data()['types'];
                $newTypes = ['like'=>'Like', 'repost'=>'Repost'];
                $evt->setResponse(array_merge($types, $newTypes));
            });
        }

        function registerPages()
        {
            parent::registerPages();
            \Idno\Core\Idno::site()->routes()->addRoute('/indielike/edit/?', '\IdnoPlugins\Reactions\Pages\Like\Edit');
            \Idno\Core\Idno::site()->routes()->addRoute('/indielike/edit/(\w+)/?', '\IdnoPlugins\Reactions\Pages\Like\Edit');
            \Idno\Core\Idno::site()->routes()->addRoute('/like/delete/(\w+)/?', '\IdnoPlugins\Reactions\Pages\Delete');
            \Idno\Core\Idno::site()->routes()->addRoute('/repost/edit/?', '\IdnoPlugins\Reactions\Pages\Repost\Edit');
            \Idno\Core\Idno::site()->routes()->addRoute('/repost/edit/(\w+)/?', '\IdnoPlugins\Reactions\Pages\Repost\Edit');
            \Idno\Core\Idno::site()->routes()->addRoute('/repost/delete/(\w+)/?', '\IdnoPlugins\Reactions\Pages\Delete');
            \Idno\Core\Idno::site()->routes()->addRoute('/reactions/fetch/?', '\IdnoPlugins\Reactions\Pages\Fetch');

        }

        function registerContentTypes()
        {
            parent::registerContentTypes();
            \Idno\Common\ContentType::register($this->getNamespace() . '\\LikeContentType');
            \Idno\Common\ContentType::register($this->getNamespace() . '\\RepostContentType');
        }


    }
}
