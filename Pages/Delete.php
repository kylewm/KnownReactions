<?php

    namespace IdnoPlugins\Reactions\Pages {

        use Idno\Core\Idno;
        use Idno\Common\Entity;
        
        class Delete extends \Idno\Common\Page {

            function postContent() {
                $this->createGatekeeper();

                if (!empty($this->arguments)) {
                    $object = Entity::getByID($this->arguments[0]);
                }

                if ($object) { 
                    if (!$object->canEdit()) {
                        $this->setResponse(403);
                        Idno::site()->session()->addErrorMessage("You don't have permission to delete this object.");
                    } else {
                        if ($object->delete()) {
                            Idno::site()->session()->addMessage($object->getTitle() . ' was deleted.');
                        } else {
                            Idno::site()->session()->addErrorMessage("We couldn't delete " . $object->getTitle() . ".");
                        }
                    }
                } 

                $this->forward($_SERVER['HTTP_REFERER']);
            }

        }

    }