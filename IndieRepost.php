<?php

    namespace IdnoPlugins\IndieReactions {

        use Idno\Core\Idno;

        class IndieRepost extends \Idno\Common\Entity {

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

            function saveDataFromInput($page)
            {
                if (empty($this->_id)) {
                    $new = true;
                } else {
                    $new = false;
                }

                $this->repostof = $page->getInput('target');
                $this->description = $page->getInput('description');
                $this->body = $page->getInput('body');
                $this->setAccess($page->getInput('access'));
                $this->save($new);
                return true;
            }

            function save($add_to_feed = false, $feed_verb = 'post')
            {
                // generate our own meaningful, unique(ish) slug
                if (!$this->getSlug() && empty($this->_id)
                      && !empty($title = $this->getTitle())
                      && !empty($this->repostof)) {
                    $this->setSlugResilient($title . '-' . substr(md5($this->repostof), 0, 10));
                }
                
                parent::save($add_to_feed, $feed_verb);
            }
            
        }

    }
