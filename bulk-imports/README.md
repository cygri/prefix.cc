# Data contributions

These files document various bulk imports into prefix.cc, to provide
a paper trail for all mappings in the database. Any mapping that's not
in here has been user-submitted. (There was another small bulk import
to initialise the site on day 1.)

## lov-not-in-prefix-cc.csv

- Creator: Bernard Vatant <bernard.vatant@mondeca.com>
- Date: 2013-03-01
- Source: Manual export of some prefixes that were in LOV but not in prefix.cc
- Processing: Converted from txt to CSV by Richard
- Processing: Richard removed one mapping we already had (crm)
- Status: Imported into prefix.cc live site via csv-import.php

## lov-mappings.csv, lov-mappings.sql, lov-mappings.sparql

- Creator: Richard
- Date: 2013-03-04
- Source: Running lov-mappings.sparql with Jena command line tool:
  jena --query lov-mappings.sparql --results csv > lov-mappings.csv
- Processing: Fixed mapping for tis prefix by adding trailing hash
- Status: Imported into prefix.cc live site via csv-import.php

## lov-resolved-1.csv, lov-resolved-1.sql

- Creator: Bernard Vatant <bernard.vatant@mondeca.com>
- Date: 2013-03-07
- Source: Bernard sent this to Richard as lov-prefixes-for-pcc.txt,
  the result of his manual reconciliation efforts of conflicting mappings
- Processing: Converted from txt to CSV by Richard
- Status: Imported into prefix.cc live site via csv-import.php

## last-lov-prefixes-for-pcc.csv, last-lov-prefixes-for-pcc.sql

- Creator: Bernard Vatant <bernard.vatant@mondeca.com>
- Date: 2013-03-20
- Source: Bernard sent this to Richard as last-lov-prefixes-for-pcc.txt,
  the result of his manual reconciliation efforts of missing prefixes
- Processing: Converted from txt to CSV by Richard
- Status: Imported into prefix.cc live site via csv-import.php

## lov-mappings-20150422.csv

- Creator: Ghislain Atemezing <ghislain.atemezing@mondeca.com>
- Date: 2015-04-22
- Source: Ghislain sent this to Richard in an effort to get prefix.cc
  updated with the latest additions to LOV. This is the third version after
  some back and forth regarding prefixes or namespace URIs that are not
  valid in prefix.cc.
- Processing: Richard changed line breaks to Unix style
- Status: Imported into prefix.cc live site via csv-import.php
