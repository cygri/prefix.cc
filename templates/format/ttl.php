<?php
foreach ($namespaces as $ns) {
    if ($ns['uri']) {
        echo "@prefix $ns[prefix]:$ns[padding] <$ns[uri]>.\n";
    } else {
        echo "#@prefix $ns[prefix]:$ns[padding] <??? not found>.\n";
    }
}
