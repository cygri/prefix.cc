<?php
foreach ($namespaces as $ns) {
    if ($ns['uri']) {
        echo "PREFIX $ns[prefix]:$ns[padding] <$ns[uri]>\n";
    } else {
        echo "#PREFIX $ns[prefix]:$ns[padding] <??? not found> .\n";
    }
} ?>
