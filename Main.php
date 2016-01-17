<?php


namespace IdnoPlugins\IndieReactions {

    require_once __DIR__ . '/vendor/autoload.php';

    use Idno\Core\Idno;

    class Main extends \Idno\Common\Plugin {

        function registerEventHooks()
        {
        }

        function registerPages()
        {
            Idno::site()->addPageHandler('/indielike/edit/?', '\IdnoPlugins\IndieReactions\Pages\IndieLike\Edit');
            Idno::site()->addPageHandler('/indierepost/edit/?', '\IdnoPlugins\IndieReactions\Pages\IndieRepost\Edit');
            Idno::site()->addPageHandler('/indiereactions/fetch/?', '\IdnoPlugins\IndieReactions\Pages\Fetch');

        }

    }
}