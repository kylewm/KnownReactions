<?php

    namespace IdnoPlugins\IndieReactions\Pages\IndieRepost {

        use Idno\Core\Idno;
        use IdnoPlugins\IndieReactions\IndieRepost;
        
        class Edit extends \Idno\Common\Page {

            function getContent() {

                $this->createGatekeeper();

                if (!empty($this->arguments)) {
                    $title = 'Edit Repost';
                    $object = IndieRepost::getByID($this->arguments[0]);
                } else {
                    $title = 'New Repost';
                    $object = new IndieRepost();
                }

                if ($owner = $object->getOwner()) {
                    $this->setOwner($owner);
                }

                $t = Idno::site()->template();
                $body = $t->__([
                    'title' => $title,
                    'object' => $object,
                ])->draw('entity/IndieReactions/edit');

                $t->__([
                    'body' => $body,
                    'title' => $title,
                ])->drawPage();
            }

            function postContent() {
                $object = new IndieRepost();
                if ($object->saveDataFromInput($this)) {
                    $forward = $this->getInput('forward-to', $object->getDisplayURL());
                    $this->forward($forward);
                }
            }

        }

    }