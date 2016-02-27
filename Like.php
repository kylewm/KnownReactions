<?php

    namespace IdnoPlugins\Reactions {

        use Idno\Core\Idno;
        use Idno\Core\Webmention;

        class Like extends \Idno\Common\Entity {

            function getTitle()
            {
                $result = 'Liked ';
                if (!empty($this->description)) {
                    $result .= $this->description;
                }
                return $result;
            }

            function getActivityStreamsObjectType()
            {
                return 'like';
            }

            function saveDataFromInput()
            {
                $page = Idno::site()->currentPage();
                if (empty($this->_id)) {
                    $new = true;
                } else {
                    $new = false;
                }

                $this->likeof = $page->getInput('like-of');
                if ($this->likeof) {
                    foreach ((array) $this->likeof as $likeofurl) {
                        $this->syndicatedto = Webmention::addSyndicatedReplyTargets($likeofurl, $this->syndicatedto);
                    }
                }
                $this->description = $page->getInput('description');
                if (empty($this->description)) {
                    $result = \IdnoPlugins\Reactions\Pages\Fetch::fetch($this->likeof);
                    if (!empty($result['description'])) {
                        $this->description = $result['description'];
                    }
                }
                $this->setAccess($page->getInput('access'));
                Idno::site()->logging()->log("saving like. new: $new");
                if ($this->publish($new)) {
                    Idno::site()->logging()->log("sending webmentions from {$this->getURL()} to {$this->likeof}");
                    Webmention::sendWebmentionPayload($this->getURL(), $this->likeof);
                }
                return true;
            }

            function save($add_to_feed = false, $feed_verb = 'post')
            {
                // generate our own meaningful, unique(ish) slug
                if (!$this->getSlug() && !$this->_id
                        && $this->getTitle() && $this->likeof) {
                    $this->setSlugResilient($this->getTitle() . '-' . substr(md5($this->likeof), 0, 10));
                }

                return parent::save($add_to_feed, $feed_verb);
            }

            public function getEditURL()
            {
                return \Idno\Core\Idno::site()->config()->getDisplayURL() . 'indielike/edit/' . $this->getID();
            }

        }

    }
