<?php

    namespace IdnoPlugins\Reactions\Pages\Repost {

        use Idno\Core\Idno;
        use IdnoPlugins\Reactions\Repost;
        
        class Edit extends \Idno\Common\Page {

            function getContent() {

                $this->createGatekeeper();

                if (!empty($this->arguments)) {
                    $title = 'Edit Repost';
                    $object = Repost::getByID($this->arguments[0]);
                } else {
                    $title = 'New Repost';
                    $object = new Repost();
                }

                if ($owner = $object->getOwner()) {
                    $this->setOwner($owner);
                }

                $t = Idno::site()->template();
                $body = $t->__([
                    'title' => $title,
                    'object' => $object,
                    'type' => 'repost',
                ])->draw('entity/Reactions/edit');

                $t->__([
                    'body' => $body,
                    'title' => $title,
                ])->drawPage();
            }

            function postContent() {
                $object = new Repost();
                if ($object->saveDataFromInput($this)) {
                    $forward = $this->getInput('forward-to', $object->getDisplayURL());
                    $this->forward($forward);
                }
            }

        }

    }