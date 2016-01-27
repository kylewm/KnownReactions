<?php

    namespace IdnoPlugins\Reactions\Pages\Like {

        use Idno\Core\Idno;
        use IdnoPlugins\Reactions\Like;

        class Edit extends \Idno\Common\Page {

            function getContent() {

                $this->createGatekeeper();

                if (!empty($this->arguments)) {
                    $title = 'Edit Like';
                    $object = Like::getByID($this->arguments[0]);
                } else {
                    $title = 'New Like';
                    $object = new Like();
                }

                if ($owner = $object->getOwner()) {
                    $this->setOwner($owner);
                }

                $t = Idno::site()->template();
                $body = $t->__([
                    'title' => $title,
                    'object' => $object,
                    'type' => 'like',
                ])->draw('entity/Reactions/edit');

                if (!empty($this->xhr)) {
                    echo $body;
                } else {
                    $t->__([
                        'body' => $body,
                        'title' => $title,
                    ])->drawPage();
                }
            }

            function postContent() {
                $this->createGatekeeper();

                if (!empty($this->arguments)) {
                    $object = Like::getByID($this->arguments[0]);
                } else {
                    $object = new Like();
                }

                if ($object->saveDataFromInput($this)) {
                    $forward = $this->getInput('forward-to', $object->getDisplayURL());
                    $this->forward($forward);
                }
            }

        }

    }