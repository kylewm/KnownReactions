<?php

    namespace IdnoPlugins\IndieReactions\Pages\IndieLike {

        use Idno\Core\Idno;
        use IdnoPlugins\IndieReactions\IndieLike;
        
        class Edit extends \Idno\Common\Page {

            function getContent() {

                $this->createGatekeeper();

                if (!empty($this->arguments)) {
                    $title = 'Edit Like';
                    $object = IndieLike::getByID($this->arguments[0]);
                } else {
                    $title = 'New Like';
                    $object = new IndieLike();
                }
                
                if ($owner = $object->getOwner()) {
                    $this->setOwner($owner);
                }

                $t = Idno::site()->template();
                $body = $t->__([
                    'object' => $object,
                ])->draw('entity/IndieLike/edit');

                $t->__([
                    'body' => $body,
                    'title' => $title,
                ])->drawPage();
            }

            function postContent() {
                $object = new \IdnoPlugins\IndieReactions\IndieLike();
                if ($object->saveDataFromInput($this)) {
                    $forward = $this->getInput('forward-to', $object->getDisplayURL());
                    $this->forward($forward);
                }
            }

        }

    }