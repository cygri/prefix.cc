<?php

include "config.php";

require_once('lib/namespaces.class.php');
require_once('lib/response.class.php');
require_once('lib/request.class.php');
require_once('lib/site.class.php');
require_once('lib/http_exception.class.php');

// Main objects
$namespaces = new Namespaces($config);
$request = new Request();
$response = new Response($config['site_base'], $request->uri);
$site = new Site($request->uri, $response, $namespaces, $config['block_time']);
set_exception_handler(array($site, 'exception_handler'));

// Helper regexes
$prefix_regex = $namespaces->get_prefix_regex();
$relaxed_prefix_regex = '[a-zA-Z0-9]*';
$extensions_regex = '(?:([a-z0-9]+)(\.plain)?|(file\.)?([a-z0-9]+))';

// Dispatch based on URI and method
if ($q = $request->matches('/^(robots|favicon)/')) {
    // The “robots” and “favicon” prefixes do not exist, to avoid clashes with robots.txt and favicon.ico
    $response->error(404);
} else if ($q = $request->matches('/^$/', array('q'))) {
    $request->enforce_get();
    $site->action_query($q['q']);
} else if ($q = $request->matches('/^$/')) {
    $request->enforce_get();
    $site->action_home();
} else if ($q = $request->matches('/^about$/')) {
    $request->enforce_get();
    $site->action_about();
} else if ($q = $request->matches('/^about\/formats$/')) {
    $request->enforce_get();
    $site->action_about_formats();
} else if ($q = $request->matches('/^about\/google$/')) {
    $request->enforce_get();
    $site->action_about_google();
} else if ($q = $request->matches('/^about\/api$/')) {
    $request->enforce_get();
    $site->action_about_api();
} else if ($q = $request->matches("/^popular(\/all)?(\.$extensions_regex)?$/")) {
    $request->enforce_get();
    $site->action_popular(!empty($q[1]), @$q[3] . @$q[6], !empty($q[4]) || !empty($q[5]));
} else if ($q = $request->matches("/^context(\.$extensions_regex)?$/", $q)) {
    $request->enforce_get();
    $site->action_popular(true, @$q[2] ? $q[2] : 'jsonld', true);
} else if ($q = $request->matches("/^latest(\/([0-9]+))?$/")) {
    $request->enforce_get();
    $site->action_latest(@$q[2]);
} else if ($q = $request->matches("/^latest\.rss$/")) {
    $request->enforce_get();
    $site->action_latest_feed();
} else if ($q = $request->matches("/^google-coop.rss$/")) {
    $request->enforce_get();
    $site->action_google_feed();
} else if ($q = $request->matches("/^reverse$/", array("uri"))) {
    $request->enforce_get();
    $site->action_reverse($q['uri'], @$_GET['format']);
} else if ($q = $request->matches("/^reverse$/")) {
    $request->enforce_get();
    $site->action_page_reverse();
} else if ($q = $request->matches("/^($prefix_regex)(:(.*)|\.$extensions_regex|)$/")) {
    if ($request->is_get()) {
        if (strlen(@$q[3]) > 0) {
            $site->action_curie($q[1], $q[3]);
        } else {
            $site->action_prefix($q[1], @$q[4] . @$q[7], !empty($q[5]) || !empty($q[6]));
        }
    } else {
        $request->enforce_post(array('create'));
        $site->action_declare($q[1], @$q[3], $_POST['create']);
    }
} else if ($q = $request->matches("!^($prefix_regex)/vote$!")) {
    $request->enforce_post(array('uri', 'vote'));
    $site->action_vote($q[1], $_POST['uri'], $_POST['vote'] == 'up');
} else if ($q = $request->matches("/^($relaxed_prefix_regex(,$relaxed_prefix_regex)+)(\.$extensions_regex)?$/")) {
    // We use a forgiving regex here, and do a check against the full prefix regex for each
    // prefix inside the method
    $request->enforce_get();
    $site->action_prefixes(explode(',', $q[1]), @$q[4] . @$q[7], !empty($q[5]) || !empty($q[6]));
} else if ($q = $request->matches("/^([^\/?]+)$/")) {
    $site->failed_lookup($q[1]);
} else {
    $response->error(404);
}
