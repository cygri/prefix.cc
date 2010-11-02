<?php echo '<?xml version="1.0" encoding="utf-8"?>'?>

<rdf:RDF
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"<?php
foreach ($namespaces as $ns) {
    if ($ns['uri'] && $ns['prefix'] != 'rdf') {
        echo "\n    xmlns:$ns[prefix]=\"" . htmlspecialchars($ns['uri']) . "\"";
    }
}
?>>
  <rdf:Description rdf:about="">
  </rdf:Description>
</rdf:RDF>
