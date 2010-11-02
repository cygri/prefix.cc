<?php
foreach ($namespaces as $ns) {
    if ($ns['uri']) {
        echo "$ns[prefix]\t$ns[uri]\n";
    } else {
        echo "#$ns[prefix]\t???\n";
    }
}
