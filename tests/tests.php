<?php /*

Run this file on the command line to execute some tests.

*/

function assert_equals($msg, $expected, $actual) {
    global $count, $fail;
    $count++;
    if ($expected !== $actual) {
        $fail++;
        echo "Expected '$expected', got '$actual': $msg\n";
    }
}
$count = 0;
$fail = 0;

require_once(dirname(__FILE__) . '/../lib/namespaces.class.php');

$namespace_URI_tests = array(
    'http://prefix.cc/' => true,

    // We arbitrarily require a "typical" final character
    'http://prefix.cc/path/' => true,
    'http://prefix.cc/path:' => true,
    'http://prefix.cc/path#' => true,
    'http://prefix.cc/path=' => true,
    'http://prefix.cc/path_' => true,
    'http://prefix.cc/path' => false,
    'http://prefix.cc/path.' => false,
    'http://prefix.cc/path$' => false,

    // We arbitrarily only support HTTP and HTTPS
    'https://prefix.cc/' => true,
    'ftp://prefix.cc/' => false,

    // We don't place any restrictions on query and fragment structure
    'http://prefix.cc/path?query=/' => true,
    'http://prefix.cc/path#fragment/' => true,
    'http://prefix.cc/path#fragment#' => true,

    // Various characters not allowed in URIs
    'http://prefix.cc/path\\/' => false,
    'http://prefix.cc/path /' => false,
    "http://prefix.cc/path\n/" => false,
    "http://prefix.cc/path\r/" => false,
    "http://prefix.cc/path\t/" => false,
    'http://prefix.cc/path{/' => false,
    'http://prefix.cc/path}/' => false,
    'http://prefix.cc/path</' => false,
    'http://prefix.cc/path>/' => false,

    // Percent-encoded anything is allowed
    'http://prefix.cc/path%20/' => true,
    'http://prefix.cc/path%0A/' => true,
    "http://prefix.cc/path%61/" => true,

    // Percent-encoded delimiters do not count
    'http://prefix.cc/path%2F' => false,

    // Reject malformed or lower-case percent-encoding
    'http://prefix.cc/path%/' => false,
    'http://prefix.cc/path%6/' => false,
    'http://prefix.cc/path%6G/' => false,
    'http://prefix.cc/path%6f/' => false,

    // Subdomain labels can't start or end with hyphen
    'http://-prefix.cc/' => false,
    'http://prefix-.cc/' => false,
    'http://pre-fix.cc/' => true,

    // Some valid UTF-8 encoded characters
    "http://prefix.cc/\xC3\xA4/" => true,
    "http://pr\xC3\xA4fix.cc/" => true,

    // Some invalid UTF-8 sequences
    "http://prefix.cc/\xC3/" => false,
    "http://pr\xC3fix.cc/" => false,
);
foreach ($namespace_URI_tests as $test => $expected) {
    assert_equals("namespace_URI_tests: $test",
        $expected, Namespaces::is_valid_namespace_URI($test));
}

echo "Completed $count tests, " . ($count - $fail) . " passed, $fail failed.\n";
exit($fail > 0 ? 1 : 0);
