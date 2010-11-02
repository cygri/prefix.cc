<?php
foreach ($namespaces as $ns) {
    if ($ns['uri']) {
        echo "$ns[prefix]=$ns[uri]\n";
    } else {
        echo "#$ns[prefix]=\n";
    }
}
