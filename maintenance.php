<?php
header("HTTP/1.0 503 Service Unavailable");
echo '<?xml version="1.0" encoding="utf-8"?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">'; ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>site maintenance | prefix.cc</title>
  <base href="http://prefix.cc/" />
  <style type="text/css">
<?php readfile('style.css'); ?>
  </style>
</head>
<body>
  <div class="title">
    <h1>site maintenance</h1>
  </div>
  <div class="footer">
    <span class="message"><span>prefix.cc is undergoing site maintenance. Please check back later.</span> | </span>
    <a rel="home" href="http://prefix.cc/">prefix.cc</a>
  </div>
</body>
</html>
