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
                    if ($this->getInput('url')) {
                        $object->likeof = $this->getInput('url');
                    }
                }

                if ($owner = $object->getOwner()) {
                    $this->setOwner($owner);
                }

                $body = $object->drawEdit();

                if (!empty($this->xhr)) {
                    echo $body;
                } else {
                    Idno::site()->template()->__([
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

                if ($object->saveDataFromInput()) {
                    $forward = $this->getInput('forward-to', $object->getDisplayURL());
                    $this->forward($forward);
                }
            }

        }

    }