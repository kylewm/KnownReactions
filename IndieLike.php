<?php

    namespace IdnoPlugins\IndieReactions {

        use Idno\Core\Idno;

        class IndieLike extends \Idno\Common\Entity {

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

                $this->likeof = $page->getInput('likeof');
                $this->description = $page->getInput('description');
                $this->setAccess($page->getInput('access'));
                $this->save($new);
                return true;
            }

            function save($add_to_feed = false, $feed_verb = 'post')
            {
                // generate our own meaningful, unique(ish) slug
                if (!$this->getSlug() && empty($this->_id) && !empty($title = $this->getTitle())) {
                    $this->setSlugResilient($title . '-' . substr(md5($this->likeof), 0, 10));
                }
                
                parent::save($add_to_feed, $feed_verb);
            }
            
        }

    }
