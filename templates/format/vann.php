<?php echo '<?xml version="1.0" encoding="utf-8"?>'?>

<rdf:RDF
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:owl="http://www.w3.org/2002/07/owl#"
    xmlns:foaf="http://xmlns.com/foaf/0.1/"
    xmlns:vann="http://purl.org/vocab/vann/">
  <foaf:Document rdf:about="">
<?php foreach ($namespaces as $ns) { ?>
    <foaf:topic>
      <owl:Ontology>
        <vann:preferredNamespacePrefix><?php e($ns['prefix']); ?></vann:preferredNamespacePrefix>
<?php if ($ns['uri']) { ?>
        <vann:preferredNamespaceUri><?php e($ns['uri']); ?></vann:preferredNamespaceUri>
<?php } ?>
      </owl:Ontology>
    </foaf:topic>
<?php } ?>
  </foaf:Document>
</rdf:RDF>
