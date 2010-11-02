<?php

foreach ($namespaces as $ns) {
    if ($ns['uri']) {
        echo "xmlns:$ns[prefix]=\"" . htmlspecialchars($ns['uri']) . "\"\n";
    }
}
?>
