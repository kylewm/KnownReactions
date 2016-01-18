<?php


namespace IdnoPlugins\Reactions {

    require_once __DIR__ . '/vendor/autoload.php';

    use Idno\Core\Idno;

    class Main extends \Idno\Common\Plugin {

        function registerEventHooks()
        {
        }

        function registerPages()
        {
            Idno::site()->addPageHandler('/like/edit/?', '\IdnoPlugins\Reactions\Pages\Like\Edit');
            Idno::site()->addPageHandler('/like/edit/(\w+)/?', '\IdnoPlugins\Reactions\Pages\Like\Edit');
            Idno::site()->addPageHandler('/repost/edit/?', '\IdnoPlugins\Reactions\Pages\Repost\Edit');
            Idno::site()->addPageHandler('/repost/edit/(\w+)/?', '\IdnoPlugins\Reactions\Pages\Repost\Edit');
            Idno::site()->addPageHandler('/reactions/fetch/?', '\IdnoPlugins\Reactions\Pages\Fetch');

        }

        function registerContentTypes()
        {
            parent::registerContentTypes();
            \Idno\Common\ContentType::register($this->getNamespace() . '\\LikeContentType');
            \Idno\Common\ContentType::register($this->getNamespace() . '\\RepostContentType');
        }


    }
}