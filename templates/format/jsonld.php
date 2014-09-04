<?php

$context = array();

foreach ($namespaces as $ns) {
    $context[$ns['prefix']] = $ns['uri'];
}

$context = array('@context' => $context);

echo json_encode($context, JSON_PRETTY_PRINT |JSON_UNESCAPED_SLASHES);
