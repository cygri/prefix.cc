prefix.cc Source Code
=====================

This is the source code to the web site running at http://prefix.cc/
and operated by Richard Cyganiak <richard@cyganiak.de> <@cygri>.


Requirements
------------

- Apache with mod_rewrite enabled
- PHP
- MySQL


Setting up a test site
----------------------

1. Clone the repository into a local directory

2. Set up a virtual host 'prefixcc.local' with that directory as document root

3. Create a new MySQL database:
$ echo CREATE DATABASE prefixcc | mysql -u root 

4. Set up database tables:
$ mysql -u root prefixcc2 < db_schema/schema.sql

5. Make a copy of the default configuration:
$ cp default.config.php config.php

6. Edit database credentials in config.php

7. Import prefixes from the public prefix.cc site:
$ php scripts/import.php prefix.cc

8. Go to http://prefixcc.local/ and you should have a functional site!


Ideas/Improvements
------------------

- Add a "copy to clipboard" button, perhaps big in the top left corner
  based on http://code.google.com/p/zeroclipboard
- All literals have @en; not really appropriate!
- Content negotiation with .file.txt as default if no Accept header set
    <drewp> i wish "curl http://prefix.cc/sioc" would return a
    plain-text version of the answer (curl doesnt make a good
    Accept header, so i guess the user-agent is the clue)
- Show preferred prefix for alternative URIs, e.g. if the old DC
  namespace is considered second best for “dc”, then it should show
  “dc11” somewhere
- Show list of all ambiguous URIs
    SELECT prefix, upvotes, uri FROM prefixcc_namespaces WHERE
    prefix IN (SELECT prefix FROM prefixcc_namespaces GROUP BY
    prefix HAVING COUNT(uri) > 1) ORDER BY prefix, upvotes DESC
- JSONP?
- Offer autocompletion widget for use on other sites
- Old mappings should get bonus in vote calculations, especially if
  the prefix is frequently looked up
- Get mapping as Turtle statement instead of just @prefix declarations
    tobyink: cygri: re prefix.cc changes - feature request:
    <http://prefix.cc/all-vann.ttl> should return a turtle
    file listing all your known prefixes in the format: []
    vann:preferredNamespaceUri <http://purl.org/dc/terms/> ;
    vann:preferredNamespacePrefix "dcterms" .
  Or http://prefix.cc/popular/all/data.ttl ?
- People don't find the "all" under "popular" -- have bulk output
  somewhere else
    http://prefix.cc/all
    http://prefix.cc/all.ttl
- Add reverse lookup from term URIs to CURIEs – currently the reverse
  lookup only allows reversal of “naked” namespace URIs without a
  local part