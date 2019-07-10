<?php
namespace IdnoPlugins\Reactions\Pages {

    use Idno\Core\Idno;
    use Idno\Core\Webservice;
    require_once __DIR__ . '/../autoloader.php';

    class Fetch extends \Idno\Common\Page {

        function getContent()
        {
            $t = Idno::site()->template();
            $t->setTemplateType('json');

            $url = $this->getInput('url');
            $t->__(self::fetch($url))->drawPage();
        }

        public static function fetch($url)
        {
            $result = [];
            $response = Webservice::get($url);

            $status = $response['response'];
            $html   = $response['content'];

            if ($status < 200 || $status >= 300) {
                $result['status'] = $status;
                $result['error']  = $response['error'];
                return $result;
            }

            $host = parse_url($url)['host'];
            if (preg_match('/(www\.|m\.)?twitter.com/', $host)) {
                $parsed = self::parseTwitter($html, $url);
            } else {
                $parsed = (new \Mf2\Parser($html, $url))->parse();
            }

            $hentries = \BarnabyWalters\Mf2\findMicroformatsByType($parsed, 'h-entry');
            if (!empty($hentries)) {
                $hentry = $hentries[0];
                $author = \BarnabyWalters\Mf2\getAuthor($hentry, $parsed);
                if (!empty($author)) {
                    if (is_string($author)) {
                        $result['author'] = ['url' => $author];
                    } else {
                        $result['author'] = [
                            'name' => \BarnabyWalters\Mf2\getPlaintext($author, 'name'),
                            'url' => \BarnabyWalters\Mf2\getPlaintext($author, 'url'),
                        ];
                    }
                }
                $name = \BarnabyWalters\Mf2\getPlaintext($hentry, 'name');
                $content_plain = \BarnabyWalters\Mf2\getPlaintext($hentry, 'content');
                $content_html = \BarnabyWalters\Mf2\getHtml($hentry, 'content');

                $result['content'] = $content_html;
                if (!strstr($name, $content_plain) && !strstr($content_plain, $name)) {
                    $result['name'] = $name;
                }
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
                    $result['name'] = $metaprops['twitter:title'];
                } else if (!empty($metaprops['og:title'])) {
                    $result['name'] = $metaprops['og:title'];
                } else {
                    $titles = $doc->getElementsByTagName('title');
                    foreach ($titles as $title) {
                        $result['name'] = $title->nodeValue;
                        break;
                    }
                }

                if (!empty($metaprops['twitter:description'])) {
                    $result['content'] = $metaprops['twitter:description'];
                } else if (!empty($metaprops['og:description'])) {
                    $result['content'] = $metaprops['og:description'];
                }
            }

            $desc = '';
            if (!empty($result['author']) && !empty($result['author']['name'])) {
                $desc .= trim($result['author']['name']) . '\'s ';
            }

            if (!empty($result['name'])) {
                $desc .= preg_replace('/\s{2,}/', ' ', $result['name']);
            } else if (strstr($url, 'twitter.com')) {
                if ($desc == '') { $desc .= 'a '; }
                $desc .= 'tweet';
            } else {
                if ($desc == '') { $desc .= 'a '; }
                $desc .= 'post on ' . preg_replace('/^\w+:\/+([^\/]+).*/', '$1', $url);
            }

            $result['description'] = $desc;
            return $result;
        }

        private static function parseTwitter($html, $url) {
            $parsed = \Mf2\Shim\parseTwitter($html, $url);

            if (!empty($parsed['items'][0]['properties']['content'][0]['html'])) {
                $content = $parsed['items'][0]['properties']['content'][0]['html'];

                $config = \HTMLPurifier_Config::createDefault();
                $config->set('URI.Base', $url);
                $config->set('URI.MakeAbsolute', true);
                $config->set('HTML.Allowed', 'a[href],img[src],p,br');
                $purifier = new \HTMLPurifier($config);
                $content = $purifier->purify($content);

                $parsed['items'][0]['properties']['content'][0]['html'] = $content;
            }

            return $parsed;
        }
    }

}
