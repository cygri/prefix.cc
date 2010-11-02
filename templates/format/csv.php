<?php
foreach ($namespaces as $ns) {
    echo "$ns[prefix],\"$ns[uri]\"\r\n";
}
