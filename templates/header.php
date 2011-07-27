<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"
    xmlns:dc="http://purl.org/dc/terms/"
    xmlns:foaf="http://xmlns.com/foaf/0.1/"
    xmlns:vann="http://purl.org/vocab/vann/"
    xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
    xmlns:owl="http://www.w3.org/2002/07/owl#"
    xmlns:ov="http://open.vocab.org/terms/"
    xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
  >
<head about="<?php e($page_uri); ?>" profile="http://www.w3.org/1999/xhtml/vocab">
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <title property="dc:title"><?php e($title); ?> | prefix.cc</title>
  <base href="<?php e($base); ?>" />
  <link rel="stylesheet" href="style.css" type="text/css" />
<?php if (@$rss_link) { ?>
  <link rel="alternate" type="application/rss+xml" title="RSS" href="<?php e($rss_link); ?>" />
<?php } ?>
<?php if ($page_uri == '') { ?>
  <link rev="foaf:homepage" href="about#prefix.cc" />
<?php } ?>
  <script src="jquery.js" type="text/javascript"></script>
  <script src="script.js" type="text/javascript"></script>
</head>
<body about="<?php e($page_uri); ?>">
