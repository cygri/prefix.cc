<?php

class Site {
    var $page_uri;
    var $response;
    var $namespaces;
    var $block_time;

    var $formats = array(
        'ttl' => array(
                'lookup' => true,
                'type' => 'text/turtle;charset=utf-8',
                'description' => 'Turtle prefix declarations',
        ),
        'n3' => array(
                'type' => 'text/rdf+n3;charset=utf-8',
                'template' => 'ttl',
                'description' => 'N3 prefix declarations (same as <code>ttl</code>)',
        ),
        'rdf' => array(
                'type' => 'application/rdf+xml',
                'template' => 'xml',
                'description' => 'RDF/XML prefix declarations',
        ),
        'xml' => array(
                'lookup' => true,
                'type' => 'application/rdf+xml',
                'description' => 'Same as <code>rdf</code>',
        ),
        'rdfa' => array(
                'lookup' => true,
                'type' => 'text/html',
                'description' => 'RDFa template with prefix declarations',
        ),
        'html' => array(
                'type' => 'text/html',
                'template' => 'rdfa',
                'description' => 'Same as <code>rdfa</code>',
        ),
        'xmlns' => array(
                'description' => 'Generic XML namespace declarations',
        ),
        'sparql' => array(
                'lookup' => true,
                'description' => 'SPARQL prefix declarations',
        ),
        'txt' => array(
                'lookup' => true,
                'bulk' => true,
                'description' => 'Tab-separated text',
        ),
        'csv' => array(
                'bulk' => true,
                'type' => 'text/csv',
                'description' => 'CSV format, for import into spreadsheets',
        ),
        'json' => array(
                'lookup' => true,
                'bulk' => true,
                'type' => 'application/json',
                'description' => 'JSON object',
        ),
        'js' => array(
                'type' => 'application/json',
                'template' => 'json',
                'description' => 'Same as <code>json</code>',
        ),
        'jsonld' => array(
                'lookup' => true,
                'bulk' => true,
                'type' => 'application/ld+json',
                'template' => 'jsonld',
                'description' => 'JSON-LD Context',
        ),
        'ini' => array(
                'bulk' => true,
                'description' => 'INI file (key=value)',
        ),
        'vann' => array(
                'lookup' => true,
                'bulk' => true,
                'type' => 'application/rdf+xml',
                'description' => 'RDF/XML data, using the VANN vocabulary',
        ),
        'go' => array(
                'description' => 'Go to namespace URI (redirect)',
        ),
    );

    function __construct($page_uri, $response, $namespaces, $block_time) {
        $this->page_uri = $page_uri;
        $this->response = $response;
        $this->namespaces = $namespaces;
        $this->block_time = $block_time;
    }

    function exception_handler($ex) {
        if (is_a($ex, 'HTTPException')) {
            $ex->respond($this->response);
            return;
        }
        $this->response->error(500, array("page_hidden_message" => $ex->getMessage()));
    }

    function ensure_valid_format($format) {
        if (!$format) return;
        if (isset($this->formats[$format])) return;
        $this->response->error(404,
                array('title' => 'unsupported format', 'message' => 'Extension “' . $format . '” is not known.'));
    }

    function get_default_links() {
        return array(
                'popular' => array('popular', 'rel' => 'rdfs:seeAlso'),
                'latest' => array('latest', 'rel' => 'rdfs:seeAlso'),
                'about' => array('about', 'rel' => 'rdfs:seeAlso'),
                'json-ld context' => array('context', 'rel' => 'rdfs:seeAlso')
        );
    }

    function get_format_links($base, $bulk = false) {
        $result = array();
        foreach ($this->formats as $format => $options) {
            if (!$bulk && !@$options['lookup']) continue;
            if ($bulk && !@$options['bulk']) continue;
            $uri = $base . (@$options['bulk'] ? ".file." : ".") . $format;
            $result[$uri] = $format;
        }
        return $result;
    }

    function action_home() {
        $options = array(
                "title" => "namespace lookup for RDF developers",
                "examples" => array(
                        "foaf" => "foaf",
                        $this->response->absolute("foaf:knows") => "foaf:knows",
                        "dc,foaf" => "dc,foaf",
                        "rdfs,dc,foaf,geo.sparql" => "rdfs,dc,foaf,geo.sparql",
                        "?q=http://xmlns.com/foaf/0.1/name" => "http://xmlns.com/foaf/0.1/name",
                ),
                "links" => $this->get_default_links(),
        );
        $this->response->render("page-home", $options);
    }

    function action_about() {
        $options = array(
                "title" => "about",
                "links" => $this->get_default_links(),
                'rdfa' => true,
        );
        $this->response->render("page-about", $options);
    }

    function action_about_formats() {
        $options = array(
                "title" => "supported formats",
                "links" => $this->get_default_links(),
                'formats' => $this->formats,
        );
        $this->response->render("page-formats", $options);
    }

    function action_about_api() {
        $options = array(
                "title" => "namespace lookup api",
                "links" => $this->get_default_links(),
        );
        $this->response->render("page-api", $options);
    }

    function action_about_jsonld() {
        $options = array(
                "title" => "JSON-LD context",
                "links" => $this->get_default_links(),
        );
        $this->response->render("page-json-ld", $options);
    }

    function action_about_google() {
        $options = array(
                "title" => "google subscription",
                "links" => $this->get_default_links(),
        );
        $this->response->render("page-google", $options);
    }

    function action_page_reverse() {
        $options = array(
                "title" => "reverse lookup API",
                "links" => $this->get_default_links(),
        );
        $this->response->render("page-reverse", $options);
    }

    function action_curie($prefix, $reference) {
        $this->register_prefix_access($prefix);
        $uris = $this->namespaces->lookup_by_votes($prefix);
        $this->respond_termpage($prefix, $reference, $uris);
    }

    function action_prefix($prefix, $format = null, $plain = false) {
        $this->ensure_valid_format($format);
        $this->register_prefix_access($prefix);
        $uris = $this->namespaces->lookup_by_votes($prefix);
        if ($uris && $format == 'go') {
            $this->response->redirect($uris[0], 302);
        }
        if ($uris && $format) {
            $this->respond_source(array($prefix => $uris[0]), $format, $plain);
        }
        $this->respond_termpage($prefix, null, $uris);
    }

    function action_declare($prefix, $reference, $expansion) {
        if ($this->user_is_blocked('add')) {
            $this->response->error(403, array('plaintext' => "You can add only one per day. Please try again tomorrow."));
        }
        if (!$this->namespaces->is_valid_namespace_URI($expansion)) {
            $this->namespaces->log_rejected_URI($prefix, $expansion, 'uri-syntax');
            $this->response->error(400, array('plaintext' => "URI must start with http:// or https://, end in / or : or #."));
        }
        if ($this->namespaces->mapping_exists($prefix, $expansion)) {
            $this->response->error(400, array('plaintext' => "This mapping already exists."));
        }
        $this->namespaces->add_declaration($prefix, $expansion);
        $this->block_user('add');
        $options = array(
                'prefix' => $prefix,
                'reference' => $reference,
                'uri' => $expansion,
                'show_vote_links' => !$this->user_is_blocked('vote', true),
        );
        $this->response->render('record-lookup-uri', $options, false);
    }

    function action_vote($prefix, $uri, $up) {
        if ($this->user_is_blocked('vote')) {
            $this->response->error(403, array('plaintext' => "You can vote only once per day. Please try again tomorrow."));
        }
        try {
            $this->namespaces->register_vote($prefix, $uri, $up);
        } catch (Exception $ex) {
            $this->response->error(400, array('plaintext' => $ex->getMessage()));
        }
        $this->block_user('vote');
        $this->response->plaintext('Thanks for your vote. You can vote again tomorrow.');
    }

    function action_prefixes($prefixes, $format = null, $plain = false) {
        $this->ensure_valid_format($format);
        foreach ($prefixes as $prefix) {
            if (!$this->namespaces->is_valid_prefix_syntax($prefix)) {
                $this->failed_lookup($prefix);
            }
        }
        foreach ($prefixes as $prefix) {
            $this->register_prefix_access($prefix);
        }
        $mapping = $this->namespaces->multi_lookup($prefixes);
        if ($format) {
            $this->respond_source($mapping, $format, $plain);
        }
        $options = array(
                'title' => count($prefixes) . " prefixes",
                'links' => $this->get_format_links(join($prefixes, ',')),
                'namespaces' => $mapping,
        );
        $this->response->render("lookup-multi", $options);
    }

    function register_prefix_access($prefix) {
        // This hopefully produces better results by discounting
        // anything that follows links -- people and bots.
        if (isset($_SERVER['HTTP_REFERER'])) return;
        $this->namespaces->register_access($prefix);
    }

    function failed_lookup($prefix) {
        $title = 'bad prefix name “' . $prefix . '”';
        $message = 'prefix.cc supports a-z and 0-9 only; max 10 characters.';
        if ($prefix == '') {
            $title = 'empty prefix';
            $message = 'prefix.cc does not support the zero-length prefix.';
        } else if (!preg_match('/^[a-zA-Z]/', $prefix)) {
            $message = 'prefix.cc supports only prefixes starting with a letter.';
        } else if (strlen($prefix) == 1) {
            $message = 'prefix.cc requires at least two characters in a prefix.';
        } else if (strlen($prefix) > 10) {
            $message = 'prefix.cc supports prefixes of 10 or less characters.';
        } else if (preg_match('/[A-Z]/', $prefix)) {
            $message = 'prefix.cc supports only lower-case letters.';
        } else {
            $message = 'prefix.cc supports only a-z and 0-9 in prefixes.';
        }
        $this->response->error(400, array('title' => $title, 'message' => $message));
    }

    function action_query($query, $format = null) {
        $query = trim($query);
        if (preg_match('/[a-zA-Z0-9]+:\/\//', $query)) {
            $this->action_reverse($query, $format);
        }
        // Usual prefix access registering checks for the REFERER header and
        // does not count the access if it is present, to avoid counting bots
        // and people following example links. But that also removes all accesses
        // from the search form on the prefix.cc homepage. We count these
        // separately here with a crude parsing of the search string. This is
        // a bit smelly.
        if (preg_match('/^([^.:]*)/', $query, $match)) {
            $prefixes = explode(',', $match[1]);
            foreach ($prefixes as $prefix) {
                if ($this->namespaces->is_valid_prefix_syntax($prefix)) {
                    $this->namespaces->register_access($prefix);    // bypasses REFERER check
                }
            }
        }
        $this->response->redirect($query, 301);
    }

    function action_reverse($uri, $format = null) {
        $prefix = null;
        // On search for http://example.com/ns, check whether
        // http://example.com/ns# is defined
        if (!preg_match('![/#:]$!', $uri)) {
            $with_hash = "$uri#";
            $prefix = $this->namespaces->reverse_lookup($with_hash);
            if ($prefix) {
                $uri = $with_hash;
                $reference = null;
            }
        }
        // Split http://example.com/ns#something into
        // "http://example.com/ns#" and (potentially empty) "something"
        if (!$prefix) {
            if (!preg_match('!^(.*?)([^/#:]*)$!', $uri, $match)) {
                $this->response->error(404);
            }
            $uri = $match[1];
            $reference = $match[2];
            $prefix = $this->namespaces->reverse_lookup($uri);
        }
        if (!$prefix) {
            $this->response->error(404, array('title' => 'no registered prefix', 'message' => 'To add a new mapping, search for the desired prefix on the homepage.'));
        }
        $this->namespaces->register_access($prefix);
        $destination = $prefix;
        if ($reference) {
            $destination .= ":$reference";
        } else if ($format && isset($this->formats[$format])) {
            $destination .= ".file.$format";
        }
        $this->response->redirect($this->response->absolute($destination), 302);
    }

    function action_popular($all = false, $format = null, $plain = false) {
        $this->ensure_valid_format($format);
        $popular_namespaces = $this->namespaces->get_popular($all ? null : 10);
        if (!$popular_namespaces) {
            // Happens only on newly initialized size before any lookups
            $this->response->error(404, array("message" => "There are no popular prefixes."));
        }
        if ($format) {
            $this->respond_source($popular_namespaces, $format, $plain);
        }
        if ($all) {
            $links = array('popular' => 'top 10');
        } else {
            $links = array('popular/all' => 'all');
        }
        $links[] = '|';
        $options = array(
                'title' => 'popular',
                'links' => array_merge($links, $this->get_format_links($this->page_uri, true)),
                'popular_namespaces' => $popular_namespaces,
                'rdfa' => true,
        );
        $this->response->render("page-popular", $options);
    }

    function action_latest_feed() {
        $latest_namespaces = $this->namespaces->get_latest(25);
        require_once "vendor/feedcreator/feedcreator.class.php";
        $feed = new UniversalFeedCreator();
        $feed->title = "prefix.cc latest namespaces";
        $feed->link = $this->response->absolute('latest');
        $feed->description = "Notifications for new namespace mappings at prefix.cc, the namespace lookup site for RDF developers";
        $feed->syndicationURL = $this->response->absolute('latest.rss');
        foreach ($latest_namespaces as $ns) {
            $item = new FeedItem();
            $item->title = "$ns[prefix]";
            $item->link = $this->response->absolute($ns['prefix']);
            $item->date = strtotime($ns['date']);
            $item->description = "<p>New prefix mapping for <strong>$ns[prefix]</strong>: <a href=\"" . htmlspecialchars($ns['uri']) . "\">" . htmlspecialchars($ns['uri']) . "</a></p><p>Submitted at $ns[date]";
            if ($ns['ip']) {
                $item->description .= " from IP $ns[ip]";
            }
            $item->description .= ".</p>";
            $item->descriptionHtmlSyndicated = true;
            $feed->addItem($item);
        }
        $feed->outputFeed("RSS2.0");
        die();
    }

    function action_google_feed() {
        $all_namespaces = $this->namespaces->get_all();
        require_once "lib/rss2_feed_with_namespaces.class.php";
        $feed = new RSS2FeedWithNamespaces();
        $feed->title = "prefix.cc";
        $feed->link = "http://prefix.cc/";
        $feed->description = " | Namespace lookup for RDF developers";
        $feed->syndicationURL = $this->response->absolute('google-coop.rss');
        $feed->namespaces['coop'] = 'http://www.google.com/coop/namespace';
        foreach ($all_namespaces as $ns) {
            $item = new FeedItem();
            $item->title = "“$ns[prefix]” namespace via prefix.cc";
            $item->link = $ns['uri'];
            $item->description = $ns['uri'];
            $item->additionalElements['coop:keyword'] = $ns['prefix'];
            $feed->addItem($item);
        }
        $feed->outputFeed();
        die();
    }

    function action_latest($page = 1) {
        $total_pages = $this->namespaces->latest_pages_count(10);
        if ($total_pages == 0) {
            // Happens only for empty DB
            $this->response->error(404, array("message" => "There are no latest additions."));
        }
        if (!$page) {
            $this->response->redirect("latest/1", 303, array("message" => "Redirecting to first page."));
        }
        if ($page > $total_pages) {
            $this->response->redirect("latest/$total_pages", 303, array("message" => "No such page, redirecting to last page."));
        }
        $links = array();
        if ($page > 1) {
            $links["latest/" . ($page - 1)] = array('prev', 'rel' => 'rdfs:seeAlso');
        }
        if ($page < $total_pages) {
            $links["latest/" . ($page + 1)] = array('next', 'rel' => 'rdfs:seeAlso');
        }
        if ($links) {
            $links[] = '|';
        }
        $options = array(
                'latest_namespaces' => $this->namespaces->get_latest(10, $page),
                'title' => 'latest' . (($page > 1) ? ", page $page" : ''),
                // Work around a Safari 4.0.4 bug where <base> is ignored when resolving feed URIs
                'rss_link' => $this->response->absolute('latest.rss'),
                //'rss_link' => 'latest.rss',
                'links' => array_merge($links, $this->get_default_links()),
                'rdfa' => true,
        );
        $this->response->render("page-latest", $options);
    }

    function get_source($mappings, $format) {
        $namespaces = array();
        $longest = 0;
        foreach ($mappings as $prefix => $uri) {
            $namespaces[] = array('prefix' => $prefix, 'uri' => $uri);
            $longest = max($longest, strlen($prefix));
        }
        foreach ($namespaces as $key => $namespace) {
            $namespaces[$key]['padding'] =
                    str_repeat(' ', $longest - strlen($namespace['prefix']));
        }
        if (isset($this->formats[$format]['template'])) {
            $template = $this->formats[$format]['template'];
        } else {
            $template = $format;
        }
        ob_start();
        $this->response->render("format/$template", array('namespaces' => $namespaces), false);
        $source = ob_get_contents();
        ob_end_clean();
        return $source;
    }

    function respond_source($namespaces, $format, $plain = false) {
        if ($format == 'go') {
            $this->response->error(400, array('title' => 'unsupported format', 'message' => 'The “go” format cannot be used with more than one prefix.'));
        }
        if ($plain) {
            if (implode('', $namespaces) == '') {
                // None of the prefixes actually have a known URI
                header("HTTP/1.0 404 Not Found");
            }
            if (isset($this->formats[$format]['type'])) {
                $type = $this->formats[$format]['type'];
            } else {
                $type = 'text/plain';
            }
            header("Content-Type: " . $type);
            echo $this->get_source($namespaces, $format);
            die();
        }
        $generic_uri = substr($this->page_uri, 0, strlen($this->page_uri) - 1 - strlen($format));
        $options = array(
                'title' => count($namespaces) . " prefixes ($format)",
                'format' => $format,
                'source' => $this->get_source($namespaces, $format),
                'links' => array(
                        $generic_uri => 'back',
                        $generic_uri . '.file.' . $format => 'plain',
                ),
        );
        $this->response->render("source", $options);
        die();
    }

    function respond_termpage($prefix, $reference, $uris) {
        if (!$uris) {
            $this->response->status(404);
            $message = "The prefix “" . $prefix . "” is not in our database. " .
                    ($this->user_is_blocked('add', true) ? "You can add it tomorrow." : "You can add it.");
        }
        $links = $this->get_format_links($prefix);
        $links[] = '|';
        $links["http://lov.okfn.org/dataset/lov/details/vocabulary_$prefix.html"] = 'lov';
        $options = array(
                'title' => $prefix . ((strlen($reference) > 0) ? ":$reference" : ''),
                'prefix' => $prefix,
                'reference' => $reference,
                'uris' => $uris,
                'message' => @$message,
                'show_form' => !$this->user_is_blocked('add', true),
                'show_vote_links' => !$this->user_is_blocked('vote', true),
                'links' => $links
        );
        $this->response->render("lookup-term", $options);
        die();
    }

    function user_is_blocked($scope, $soft_check = false) {
        if ($soft_check) {
            return !empty($_COOKIE['blocked-' . $scope]);
        }
        return $this->namespaces->is_blocked_ip($scope);
    }

    function block_user($scope) {
        $this->namespaces->block_ip($scope);
        setcookie('blocked-' . $scope, 1, time() + $this->block_time, '/');
    }
}
