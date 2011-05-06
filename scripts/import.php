<?php

if ($argc != 2) {
  echo "Usage: php scripts/import.php hostname\n";
  echo "  where 'hostname' is the site to import from (usually 'prefix.cc')\n";
  die(1);
}

$host = $argv[1];

$lines = file("http://$host/popular/all.file.txt");

include "config.php";
require_once('lib/namespaces.class.php');
$namespaces = new Namespaces($config);

$count = 0;
foreach ($lines as $line) {
  if (substr($line, 0, 1) == '#') continue;
  list($prefix, $uri) = explode("\t", trim($line));
  $namespaces->add_declaration($prefix, $uri);
  $count++;
}

echo "Imported $count prefix mappings from $host\n";
