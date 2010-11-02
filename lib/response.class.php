<?php

class Response {
    var $base;
    var $page_uri;

    var $status_messages = array(
            200 => "200 OK",
            301 => "301 Moved Permanently",
            302 => "302 Found",
            303 => "303 See Other",
            307 => "307 Temporary Redirect",
            400 => "400 Bad Request",
            403 => "403 Forbidden",
            404 => "404 Not Found",
            405 => "405 Method Not Allowed",
            500 => "500 Internal Server Error",
            503 => "503 Service Unavailable",
    );

    var $page_messages = array(
            405 => "Try HTTP GET.",
            500 => "Something went horribly wrong.",
    );

    function __construct($base, $page_uri) {
        $this->base = $base;
        $this->page_uri = $page_uri;
    }

    function absolute($uri) {
        if (preg_match('!https?://!', $uri)) {
            return $uri;
        }
        return $this->base . $uri;
    }

    function redirect($redirect_uri, $code = 302, $options = array()) {
        $redirect_uri = $this->absolute($redirect_uri);
        if (empty($options['title'])) {
            $options['title'] = 'redirect';
        }
        if (empty($options['link'])) {
            $options['link'] = $redirect_uri;
        }
        header("Location: $redirect_uri");
        $this->error($code, $options);
    }

    function error($code = 500, $options = array()) {
        if (empty($options['title'])) {
            $options['title'] = $this->get_status_message($code);
        }
        if (empty($options['message']) && !empty($this->page_messages[$code])) {
            $options['message'] = $this->page_messages[$code];
        }
        if (empty($options['template'])) {
            $options['template'] = 'message';
        }
        $this->status($code);
        if (isset($options['plaintext'])) {
            $this->plaintext($options['plaintext']);
        }
        $this->render($options['template'], $options);
        die();
    }

    function plaintext($text) {
        header('Content-Type: text/plain');
        echo "$text\n";
        die();
    }

    function status($code) {
        header("HTTP/1.0 " . $this->get_status_message($code));
    }

    function render($template, $options = array(), $as_page = true) {
        // Let's be super careful
        if (!preg_match("![a-zA-Z0-9/_-]+!", $template)) {
            die("Bad template name: $template");
        }
        $base = $this->base;
        if (preg_match('!^[^/]*:!', $base)) {
            // Relative URIs like 'dc:creator' don't work, so we use absolute instead
            $page_uri = $this->absolute($this->page_uri);
        } else {
            $page_uri = $this->page_uri;
        }
        foreach ($options as $varname => $value) {
            $$varname = $value;
        }
        if ($as_page) {
            include("templates/header.php");
        }
        include("templates/$template.php");
        if ($as_page) {
            include("templates/footer.php");
        }
    }

    function get_status_message($code) {
        if (!isset($this->status_messages[$code])) {
            return $this->status_messages[500];
        }
        return $this->status_messages[$code];
    }
}

// echo + escape function for use in templates
function e($s) {
    echo htmlspecialchars($s);
}
