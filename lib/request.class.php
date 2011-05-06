<?php

class Request {
    var $uri;
    var $path;

    function __construct() {
        $uri = substr($_SERVER['REQUEST_URI'], 1);
        $this->uri = $uri;
        preg_match('/([^\?]*)(\?.*)?$/', $uri, $match);
        $this->path = $match[1];
    }

    function is_get() {
        return $_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'HEAD';
    }

    function enforce_get() {
        if (!$this->is_get()) {
            throw new HTTPException(405);
        }
    }

    function is_post($required_params = array()) {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return false;
        }
        foreach ($required_params as $arg) {
            if (!isset($_POST[$arg])) return false;
        }
        return true;
    }

    function enforce_post($required_params = array()) {
        if (!$this->is_post()) {
            throw new HTTPException(405, array('message' => 'Try HTTP POST.'));
        }
        foreach ($required_params as $arg) {
            if (!isset($_POST[$arg])) {
                throw new HTTPException(400, 
                        array('message' => 'Missing POST parameter: “' . $arg . '”'));
            }
        }
    }

    function matches($regex, $required_get_params = null) {
        if (!preg_match($regex, $this->path, $match)) {
            return false;
        }
//        if (count($_GET) != count($required_get_params)) {
//            return false;
//        }
        if (!empty($required_get_params)) {
            foreach ($required_get_params as $param) {
                if (!isset($_GET[$param])) {
                    return false;
                }
                $match[$param] = $_GET[$param];
            }
        }
        return $match;
    }
}
