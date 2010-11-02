<?php echo '<?xml version="1.0" encoding="utf-8"?>'?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xml:lang="en"
    xmlns="http://www.w3.org/1999/xhtml"<?php
foreach ($namespaces as $ns) {
    if ($ns['uri']) {
        echo "\n    xmlns:$ns[prefix]=\"" . htmlspecialchars($ns['uri']) . "\"";
    }
}
?>
>
  <head>
    <title>Hello World!</title>
  </head>
  <body>
    <h1>Hello World!</h1>
  </body>
</html>
