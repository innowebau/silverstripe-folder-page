<?php

namespace Innoweb\FolderPage\Pages;

use Page;
use SilverStripe\CMS\Model\RedirectorPage;

class FolderPage extends Page
{
    private static $table_name = 'FolderPage';
    private static $singular_name = 'Folder';
    private static $plural_name = 'Folders';
    private static $description = 'Folder to organise pages and redirect to first child page.';

    private static $show_stage_link = false;
    private static $show_live_link = false;

    private static $cms_fields_remove;

    private static $defaults = [
        'ShowInMenus' => true,
        'ShowInSearch' => false,
        'ShowInSitemap' => true
    ];

    public function getTargetPage(): ?Page
    {
        $firstChildPage = null;
        $aChildren = $this->AllChildren();
        if ($aChildren->Count() > 0)
        {
            /** @var Page $firstChildPage */
            $firstChildPage = $aChildren->first();
            if ($firstChildPage && $firstChildPage->exists())
            {
                $firstChildPageID = (int) $firstChildPage->ID;
                if ($firstChildPageID < 1 || $firstChildPageID === (int) $this->ID) {
                    $firstChildPage = null;
                }
            }
        }
        return $firstChildPage;
    }

    public function ContentSource(): Page
    {
        return $this->getTargetPage() ?? $this;
    }

    public function Link($action = null)
    {
        $targetPage = $this->getTargetPage();
        if (is_null($targetPage)) {
            $link = parent::Link($action);
        } elseif (is_a($targetPage, RedirectorPage::class)) {
            $link = $targetPage->regularLink($action);
        } else {
            $link = $targetPage->Link();
        }
        return $link;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $removeFields = static::config()->get('cms_fields_remove');
        if (is_array($removeFields) && !empty($removeFields)) {
            $removeFields = array_values($removeFields);
            $fields->removeByName($removeFields);
        }
        return $fields;
    }

    public function subPagesToCache()
    {
        return [];
    }
}
