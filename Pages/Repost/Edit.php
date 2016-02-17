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
                    if ($this->getInput('url')) {
                        $object->repostof = $this->getInput('url');
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
                    $object = Repost::getByID($this->arguments[0]);
                } else {
                    $object = new Repost();
                }

                if ($object->saveDataFromInput($this)) {
                    $forward = $this->getInput('forward-to', $object->getDisplayURL());
                    $this->forward($forward);
                }
            }

        }

    }