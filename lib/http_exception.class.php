<?php

class HTTPException extends Exception {

    var $status_code;
    var $options;

    function __construct($status_code, $options = array()) {
        parent::__construct("HTTP $status_code");
        $this->status_code = $status_code;
        $this->options = $options;
    }

    function respond($response) {
        $response->error($this->status_code, $this->options);
    }
}
