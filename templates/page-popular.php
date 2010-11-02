  <div class="title">
    <h1>popular</h1>
  </div>
  <ol class="content popular">
<?php $i = 0; foreach ($popular_namespaces as $prefix => $uri) { $i++; ?>
<?php if ($uri) { ?>
    <li typeof="owl:Ontology" property="vann:preferredNamespaceUri" content="<?php e($uri); ?>">
      <span property="ov:rank" content="<?php e($i); ?>" datatype="xsd:int" />
      <a rel="rdfs:seeAlso nofollow" resource="<?php e($uri); ?>" property="vann:preferredNamespacePrefix" href="<?php e($prefix); ?>"><?php e($prefix); ?></a>
    </li>
<?php } else { ?>
    <li typeof="owl:Ontology" class="missing">
      <span property="ov:rank" content="<?php e($i); ?>" datatype="xsd:int" />
      <a rel="nofollow" property="vann:preferredNamespacePrefix" href="<?php e($prefix); ?>"><?php e($prefix); ?></a>
    </li>
<?php } ?>
<?php } ?>
  </ol>
