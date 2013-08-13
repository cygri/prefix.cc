<?php

class Namespaces {
    var $db_host;
    var $db_user;
    var $db_password;
    var $db_name;
    var $block_time;    // in seconds

    function __construct($config) {
        $this->db_host = $config['db_host'];
        $this->db_user = $config['db_user'];
        $this->db_password = $config['db_password'];
        $this->db_name = $config['db_name'];
        $this->block_time = $config['block_time'];
    }

    function lookup($prefix) {
        $mapping = $this->multi_lookup(array($prefix));
        return $mapping[$prefix];
    }

    function lookup_by_votes($prefix) {
        $sql = sprintf("SELECT uri FROM prefixcc_namespaces " .
                "WHERE prefix='%s' AND upvotes-downvotes>0 ORDER BY upvotes-downvotes DESC", $this->escape($prefix));
        return $this->select_list($sql);
    }

    function multi_lookup($prefixes, $fetch_all = false) {
        if (!$prefixes) return array();
        $query = array();
        $mapping = array();
        $votes = array();
        foreach ($prefixes as $prefix) {
            $query[] = sprintf("prefix='%s'", $this->escape($prefix));
            $mapping[$prefix] = null;
            $votes[$prefix] = 0;
        }
        $sql = "SELECT prefix, uri, upvotes-downvotes AS votes FROM prefixcc_namespaces";
        if (!$fetch_all) {
            $sql .= " WHERE " . join(' OR ', $query);
        }
        foreach ($this->select_rows($sql) as $row) {
            $p = $row['prefix'];
            // Check if $p was actually requested, because of $fetch_all
            if (!in_array($p, $prefixes)) continue;
            if (!$mapping[$p] || $votes[$p] < $row['votes']) {
                $mapping[$p] = $row['uri'];
                $votes[$p] = $row['votes'];
            }
        }
        return $mapping;
    }

    function register_access($prefix) {
        if (!$this->is_valid_prefix_syntax($prefix)) {
            throw new Exception("not a valid prefix: '$prefix'");
        }
        $sql = sprintf(
                "UPDATE prefixcc_access SET count = count + 1 WHERE prefix='%s'", 
                $this->escape($prefix));
        if ($this->execute($sql) == 1) return;
        $sql = sprintf(
                "INSERT INTO prefixcc_access (prefix, count) VALUES ('%s', 1)",
                $this->escape($prefix));
        $this->execute($sql);
    }

    function get_popular($limit = null) {
        $sql = "SELECT prefix FROM prefixcc_access ORDER BY count DESC";
        if ($limit) {
            $sql .= sprintf(" LIMIT %u", $limit);
        }
        $prefixes = $this->select_list($sql);
        return $this->multi_lookup($prefixes, !$limit);
    }

    function latest_pages_count($per_page = 10) {
        $sql = "SELECT COUNT(*) FROM prefixcc_namespaces";
        return ceil($this->select_value($sql) / $per_page);
    }

    function get_latest($per_page = 10, $page = 1) {
        $sql = sprintf("SELECT prefix, uri, date, ip FROM prefixcc_namespaces ORDER BY date DESC LIMIT %u OFFSET %u",
                $per_page, ($page - 1) * $per_page);
        return $this->select_rows($sql);
    }

    function get_all() {
        $sql = "SELECT ns.uri, ns.prefix FROM (SELECT prefix, MAX(upvotes-downvotes) AS max_votes FROM prefixcc_namespaces GROUP BY prefix) AS votes, prefixcc_namespaces AS ns WHERE votes.max_votes = ns.upvotes-ns.downvotes AND votes.prefix = ns.prefix ORDER BY ns.prefix";
        return $this->select_rows($sql);
    }

    function register_vote($prefix, $uri, $up) {
        if (!$this->is_valid_prefix_syntax($prefix)) {
            throw new Exception("not a valid prefix: '$prefix'");
        }
        if (!$this->is_valid_namespace_URI($uri)) {
            throw new Exception("not a valid namespace URI: '$uri'");
        }
        $vote_column = $up ? 'upvotes' : 'downvotes';
        $sql = sprintf("UPDATE prefixcc_namespaces SET $vote_column=$vote_column+1 WHERE prefix='%s' AND uri='%s'", $this->escape($prefix), $this->escape($uri));
        if ($this->execute($sql) != 1) {
            throw new Exception('This prefix-to-URI mapping does not exist.');
        }
        $sql = sprintf("INSERT INTO prefixcc_vote_log (prefix, uri, date, ip, up) VALUES ('%s', '%s', NOW(), '%s', %d)", $this->escape($prefix), $this->escape($uri), $this->escape($_SERVER['REMOTE_ADDR']), $up);
        $this->execute($sql);
    }

    var $ip_block_scopes = array(
            'add' => 1,
            'vote' => 2,
    );

    function is_blocked_ip($scope) {
        $sql = sprintf("DELETE FROM prefixcc_ip_block WHERE NOW()>date");
        $this->execute($sql);
        $sql = sprintf("SELECT COUNT(*) FROM prefixcc_ip_block WHERE ip='%s' AND scope=%d",
                $this->escape($_SERVER['REMOTE_ADDR']),
                $this->ip_block_scopes[$scope]);
        return (bool) $this->select_value($sql);
    }

    function block_ip($scope) {
        $sql = sprintf("INSERT INTO prefixcc_ip_block (ip, scope, date) VALUES ('%s', %d, DATE_ADD(NOW(), INTERVAL %u SECOND))",
                $this->escape($_SERVER['REMOTE_ADDR']),
                $this->ip_block_scopes[$scope],
                $this->block_time);
        $this->execute($sql);
    }

    function is_valid_namespace_URI($uri) {
        // Not sure if this is quite correct. At least one alphanumeric
        // character, must start and end alphanumeric, might contain dash
        $domainpart = "([a-zA-Z0-9]([a-zA-Z0-9-]*[a-zA-Z0-9])?)";
        return preg_match("!^https?://$domainpart(\.$domainpart)+(:[0-9]+)?/([\!$&'()*+,;=._~?/:@%0-9a-zA-Z-]*[/#:])?$!", $uri);
    }

    function get_prefix_regex() {
        return "[a-z][a-z0-9]{1,9}";
    }

    function is_valid_prefix_syntax($prefix) {
        return preg_match('/^' . $this->get_prefix_regex() . '$/', $prefix);
    }

    function mapping_exists($prefix, $uri) {
        $sql = sprintf("SELECT COUNT(*) FROM prefixcc_namespaces WHERE prefix='%s' AND uri='%s'", $this->escape($prefix), $this->escape($uri));
        return (bool) $this->select_value($sql);
    }

    function log_rejected_URI($prefix, $uri, $reason) {
        $sql = sprintf("INSERT INTO prefixcc_rejected_uris (prefix, uri, date, ip, reason) VALUES ('%s', '%s', NOW(), '%s', '%s')", $this->escape($prefix), $this->escape($uri), $this->escape($_SERVER['REMOTE_ADDR']), $this->escape($reason));
        $this->execute($sql);
    }

    function add_declaration($prefix, $uri) {
        if (!$this->is_valid_prefix_syntax($prefix)) {
            throw new Exception("not a valid prefix: '$prefix'");
        }
        if (!$this->is_valid_namespace_URI($uri)) {
            throw new Exception("not a valid namespace URI: '$uri'");
        }
        $ip = empty($_SERVER['REMOTE_ADDR']) ? '127.0.0.1' : $_SERVER['REMOTE_ADDR'];
        $sql = sprintf("INSERT INTO prefixcc_namespaces (prefix, uri, date, ip, upvotes, downvotes) VALUES ('%s', '%s', NOW(), '%s', 5, 0)", $this->escape($prefix), $this->escape($uri), $this->escape($ip));
        $this->execute($sql);
    }

    function reverse_lookup($uri) {
        $sql = sprintf("SELECT ns1.prefix FROM
                (prefixcc_namespaces AS ns1 LEFT JOIN prefixcc_namespaces AS ns2 ON ns1.prefix=ns2.prefix AND ns1.uri!=ns2.uri)
                LEFT JOIN prefixcc_access AS acc ON ns1.prefix=acc.prefix
                WHERE ns1.uri='%s' AND (ns2.prefix IS NULL OR (ns1.upvotes-ns1.downvotes)>(ns2.upvotes-ns2.downvotes))
                ORDER BY count DESC LIMIT 1", $this->escape($uri));
        return $this->select_value($sql);
    }

    function escape($s) {
        if (empty($this->_conn)) $this->connect();
        return $this->_conn->real_escape_string($s);
    }

    function query($sql) {
        if (empty($this->_conn)) $this->connect();
        $result = $this->_conn->query($sql);
        if (!$result) {
            throw new DatabaseException($this->_conn->error, $sql);
        }
        return $result;
    }

    function execute($sql) {
        $this->query($sql);
        return $this->_conn->affected_rows;
    }

    function select_rows($sql) {
        $result = $this->query($sql);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $result->close();
        return $rows;
    }

    function select_map($sql) {
        $result = $this->query($sql);
        $map = array();
        while ($row = $result->fetch_row()) {
            $map[$row[0]] = $row[1];
        }
        $result->close();
        return $map;
    }

    function select_list($sql) {
        $result = $this->query($sql);
        $list = array();
        while ($row = $result->fetch_row()) {
            $list[] = $row[0];
        }
        $result->close();
        return $list;
    }

    function select_value($sql) {
        $result = $this->query($sql);
        $list = array();
        if (!($row = $result->fetch_row())) return null;
        return $row[0];
    }

    function connect() {
        $this->_conn = @new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name);
        if (mysqli_connect_errno()) {
            throw new DatabaseException(mysqli_connect_error());
        }
    }
}

class DatabaseException extends Exception {
    var $sql;

    function __construct($message = null, $query = null) {
        parent::__construct($message);
        $this->sql = $query;
    }

    function getQuery() {
        return $this->sql;
    }
}
