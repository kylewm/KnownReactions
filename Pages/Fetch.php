<?php
namespace IdnoPlugins\Reactions\Pages {

    use Idno\Core\Idno;
    
    class Fetch extends \Idno\Common\Page {
        
        function getContent()
        {
            $t = Idno::site()->template();
            $t->setTemplateType('json');

            $url = $this->getInput('url');
            $html = file_get_contents($url);
            $host = parse_url($url)['host'];

            if (preg_match('/(www\.|m\.)?twitter.com/', $host)) {
                $parsed = \Mf2\Shim\parseTwitter($html, $url);
            } else {
                $parsed = \Mf2\parse($html, $url);
            }

            $hentries = \BarnabyWalters\Mf2\findMicroformatsByType($parsed, 'h-entry');
            if (!empty($hentries)) {
                $hentry = $hentries[0];
                if (!empty($author = \BarnabyWalters\Mf2\getAuthor($hentry, $parsed))) {
                    $t->author = [
                        "name" => \BarnabyWalters\Mf2\getPlaintext($author, 'name'),
                        "url" => \BarnabyWalters\Mf2\getPlaintext($author, 'url'),
                    ];
                }
                $name = \BarnabyWalters\Mf2\getPlaintext($hentry, 'name');
                $content_plain = \BarnabyWalters\Mf2\getPlaintext($hentry, 'content');
                $content_html = \BarnabyWalters\Mf2\getHtml($hentry, 'content');

                if (strstr($name, $content_plain) || strstr($content_plain, $name)) {
                    $name = false;
                }

                $t->content = $content_html;
                $t->name = $name;
            }
            // let's try OGP and Twitter cards
            else {
                $doc = new \DOMDocument();
                $doc->loadHTML($html);
                $metas = $doc->getElementsByTagName('meta');
                $metaprops = [];
                foreach ($metas as $meta) {
                    if ($meta->hasAttribute('name')) {
                        $metaprops[$meta->getAttribute('name')] = $meta->getAttribute('content');
                    } else if ($meta->hasAttribute('property')) {
                        $metaprops[$meta->getAttribute('property')] = $meta->getAttribute('content');
                    }
                }

                if (!empty($metaprops['twitter:title'])) {
                    $t->name = $metaprops['twitter:title'];
                } else if (!empty($metaprops['og:title'])) {
                    $t->name = $metaprops['og:title'];
                } else {
                    if (!empty($titles = $doc->getElementsByTagName('title'))) {
                        $t->name = $titles[0]->nodeValue;
                    }
                }

                if (!empty($metaprops['twitter:description'])) {
                    $t->content = $metaprops['twitter:description'];
                } else if (!empty($metaprops['og:description'])) {
                    $t->content = $metaprops['og:description'];
                }
            }

            $t->drawPage();
        }
        
    }
}
    