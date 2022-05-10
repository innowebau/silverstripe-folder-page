<?php

namespace Innoweb\FolderPage\Pages;

use PageController;
use SilverStripe\Control\HTTPRequest;

class FolderPageController extends PageController
{
    private static $allowed_actions = ['index'];

    public function index(HTTPRequest $request)
    {
        /** @var FolderPage $page */
        $page = $this->data();
        if (!$this->getResponse()->isFinished()) {
            $targetPage = $page->getTargetPage();
            if (!is_null($targetPage)) {
                return $this->redirect($targetPage->Link(), 301);
            }
        }
        return parent::handleAction($request, 'handleIndex');
    }

    public function getContent()
    {
        $targetPage = $this->data()->getTargetPage();
        if ($targetPage) {
            return $this->redirect($targetPage->Link(), 301);
        }
        return "<p class=\"message-setupWithoutRedirect\">" .
            _t(__CLASS__ . '.HASBEENSETUP', 'A redirector page has been set up without anywhere to redirect to.') .
            "</p>";
    }
}
