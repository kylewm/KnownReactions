<?php

    namespace IdnoPlugins\Reactions {

        use Idno\Core\Idno;
        use Idno\Core\Webmention;

        class Repost extends \Idno\Common\Entity {

            function getTitle()
            {
                $result = 'Repost of ';
                if (!empty($this->description)) {
                    $result .= $this->description;
                }
                return $result;
            }

            function getActivityStreamsObjectType()
            {
                return 'share';
            }

            function saveDataFromInput()
            {
                $page = Idno::site()->currentPage();
                if (empty($this->_id)) {
                    $new = true;
                } else {
                    $new = false;
                }

                $this->repostof = $page->getInput('repost-of');
                if ($this->repostof) {
                    foreach ((array) $this->repostof as $repostofurl) {
                        $this->syndicatedto = Webmention::addSyndicatedReplyTargets($repostofurl, $this->syndicatedto);
                    }
                }
                $this->description = $page->getInput('description');
                $this->body = $page->getInput('body');
                if (empty($this->description) && empty($this->body)) {
                    $result = \IdnoPlugins\Reactions\Pages\Fetch::fetch($this->repostof);
                    if (isset($result['description'])) {
                        $this->description = $result['description'];
                    }
                    if (isset($result['content'])) {
                        $this->body = $result['content'];
                    }
                }
                $this->setAccess($page->getInput('access'));
                if ($this->publish($new)) {
                    Webmention::sendWebmentionPayload($this->getURL(), $this->repostof);

                }
                return true;
            }

            function save()
            {
                // generate our own meaningful, unique(ish) slug
                if (!$this->getSlug() && !$this->_id
                      && $this->getTitle() && $this->repostof) {
                    $this->setSlugResilient($this->getTitle() . '-' . substr(md5($this->repostof), 0, 10));
                }

                return parent::save();
            }

        }

    }
