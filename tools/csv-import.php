<?php /*

  CSV IMPORTER FOR prefix.cc
  --------------------------

  Reads a CSV file with prefix mappings, and generates SQL commands that can
  be run against the live prefix.cc database to import the mappings.

  The CSV file needs two columns, prefix and URI. The first row is assumed
  to contain a column header and will be ignored. Example:

    prefix,URI
    foo,http://example.com/foo#
    bar,"http://example.com/bar,baz#"

  Running:

    php csv-import.php file.csv

  This first validates the prefix mappings and reports any invalid prefixes
  or namespace URIs. It will *not* check if the mappings already exist in the
  live database.

  If everything validates, then a SQL dump will be written to STDOUT. These
  commands can then by run against the live database using the phpMyAdmin
  interface.

*/

if (!isset($argv) || count($argv) < 2) {
  die("Syntax: php csv-import.php mappings.csv\n");
}

$dir = dirname($argv[0]);
require "$dir/../config.php";
require_once("$dir/../lib/namespaces.class.php");

$filename = $argv[1];
$lines = @file($filename);
if (!$lines) {
  die("Not found or empty: $filename\n");
}
if (count($lines) == 1) {
  die("Input file contains only one line; probably wrong type of line endings?");
}
$namespaces = new Namespaces($config);
$mappings = array();
$prefixes = array();
$total_invalid = 0;
foreach ($lines as $i => $line) {
  if (!trim($line)) continue; // Skip empty lines
  $line_number = $i + 1;
  if ($line_number == 1) continue;  // Skip header
  $parsed = str_getcsv($line);
  if (count($parsed) != 2) {
    die("Wrong number of columns in line $line_number (should be 2 columns)\n");
  }
  list($prefix, $uri) = $parsed;
  // Skip empty URIs, to make importing the dump from
  // http://prefix.cc/popular/all.file.csv simpler
  if ($uri == '') continue;
  $valid = true;
  if (!$namespaces->is_valid_prefix_syntax($prefix)) {
    echo "Invalid prefix: '$prefix' (line $line_number)\n";
    $valid = false;
  }
  if (!$namespaces->is_valid_namespace_URI($uri)) {
    echo "Invalid namespace URI: '$uri' (line $line_number)\n";
    $valid = false;
  }
  if ($valid) {
    $mappings[] = array('prefix' => $prefix, 'uri' => $uri);
    $prefixes[] = $prefix;
  } else {
    $total_invalid++;
  }
}
if ($total_invalid > 0) {
  echo 'Found ' . count($mappings) . " valid and $total_invalid invalid mappings\n";
  die("There were invalid mappings. Aborting.\n");
}
$lookup_url = "http://prefix.cc/" . join($prefixes, ",");
@date_default_timezone_set(@date_default_timezone_get());
$date = date("Y-m-d");
echo "# Mappings for insertion into the prefix.cc database\n";
echo "# " . count($mappings) . " mappings generated on $date from $filename\n";
echo "# To check for existing mappings: $lookup_url\n";
foreach ($mappings as $mapping) {
  // TODO: Refactor so that this uses the same SQL command as in Namespaces::add_declaration
  printf("INSERT INTO prefixcc_namespaces (prefix, uri, date, ip, upvotes, downvotes) VALUES ('%s', '%s', NOW(), '%s', 5, 0)", $mapping['prefix'], $mapping['uri'], '127.0.0.1');
  echo ";\n";
}
