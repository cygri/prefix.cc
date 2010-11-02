<?php

require_once "vendor/feedcreator/feedcreator.class.php";

class RSS2FeedWithNamespaces extends RSSCreator20 {
    var $namespaces = array();

    function createFeed() {
        $xmlns = '';
        foreach ($this->namespaces as $prefix => $uri) {
            $xmlns .= " xmlns:$prefix=\"$uri\"";
        }
        $feed = preg_replace('/<rss [^>]*/', '$0' .  $xmlns, parent::createFeed());
        $feed = preg_replace('/encoding="ISO-8859-1"/i', 'encoding="utf-8"', $feed);
        return $feed;
    }

    function outputFeed() {
        header("Content-Type: application/rss+xml");
        parent::outputFeed();
    }
}
