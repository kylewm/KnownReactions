<?php

    namespace IdnoPlugins\IndieReactions {

        use Idno\Core\Idno;

        class IndieLike extends \Idno\Common\Entity {
            
            function getActivityStreamsObjectType() {
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
                $this->setAccess('PUBLIC');
                $this->save($new);
                return true;
            }
            
        }

    }
