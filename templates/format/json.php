{<?php
$i = 0;
foreach ($namespaces as $ns) {
    if ($i++ > 0) echo ",";
    echo "\n  \"$ns[prefix]\": ";
    if ($ns['uri']) {
        echo json_encode($ns['uri'], JSON_UNESCAPED_SLASHES);
    } else {
        echo "null";
    }
} ?>

}
