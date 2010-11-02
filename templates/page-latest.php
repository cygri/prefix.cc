  <div class="page-latest">
    <h1><?php echo htmlspecialchars($title); ?></h1>
    <table>
<?php foreach($latest_namespaces as $ns) { ?>
      <tbody typeof="owl:Ontology" property="vann:preferredNamespacePrefix" content="<?php e($ns['prefix']); ?>">
        <tr>
          <th class="prefix"><a href="<?php echo htmlspecialchars($base . $ns['prefix']); ?>"><?php echo htmlspecialchars($ns['prefix']); ?>:</a></th>
          <td class="uri">
            <a rel="rdfs:seeAlso" property="vann:preferredNamespaceUri" href="<?php echo htmlspecialchars($ns['uri']); ?>"><?php echo htmlspecialchars($ns['uri']); ?></a>
          </td>
        </tr>
        <tr>
          <td></td>
          <td class="meta">
            submitted <span class="date" property="dc:created" content="<?php e(str_replace(' ', 'T', $ns['date'])); ?>Z" datatype="xsd:dateTime"><?php echo htmlspecialchars($ns['date']); ?></span>
<?php if ($ns['ip']) { ?>
            from IP address <span class="ip" property="dc:creator"><?php echo htmlspecialchars($ns['ip']); ?></span>
<?php } ?>
          </td>
        </tr>
      </tbody>
<?php } ?>
    </table>
    <p class="feed-link"><a href="latest.rss" title="RSS feed of the latest additions"><img src="images/feed-icon-28x28.png" alt="RSS feed" /></a></p>
  </div>
