ALTER TABLE prefixcc_ip_block
    ADD scope INT NOT NULL AFTER ip,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (ip, scope) ;
UPDATE prefixcc_ip_block SET scope=1;
INSERT INTO prefixcc_ip_block
    SELECT ip, date, 2 AS scope from prefixcc_ip_block;
ALTER TABLE prefixcc_namespaces
    CHANGE uri uri VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;
ALTER TABLE prefixcc_vote_log
    ADD up BOOL NOT NULL DEFAULT '1';
ALTER TABLE prefixcc_namespaces
    CHANGE votes upvotes INT NOT NULL DEFAULT '0',
    ADD downvotes INT NOT NULL DEFAULT '0';
UPDATE prefixcc_namespaces SET upvotes=upvotes+4;
