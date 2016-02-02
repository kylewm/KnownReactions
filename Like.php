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

            function saveDataFromInput($page)
            {
                if (empty($this->_id)) {
                    $new = true;
                } else {
                    $new = false;
                }

                $this->likeof = $page->getInput('like-of');
                $this->description = $page->getInput('description');
                if (empty($this->description)) {
                    $result = \IdnoPlugins\Reactions\Pages\Fetch::fetch($this->likeof);
                    if (!empty($result['description'])) {
                        $this->description = $result['description'];
                    }
                }
                $this->setAccess($page->getInput('access'));
                if ($this->save($new)) {
                    Webmention::sendWebmentionPayload($this->getURL(), $this->likeof);
                }
                return true;
            }

            function save($add_to_feed = false, $feed_verb = 'post')
            {
                // generate our own meaningful, unique(ish) slug
                if (!$this->getSlug() && empty($this->_id)
                      && !empty($this->getTitle())
                      && !empty($this->likeof)) {
                    $this->setSlugResilient($this->getTitle() . '-' . substr(md5($this->likeof), 0, 10));
                }

                return parent::save($add_to_feed, $feed_verb);
            }

        }

    }
