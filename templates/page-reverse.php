  <div class="page-about">
    <h1>reverse lookup API</h1>
    <p>A “reverse lookup” finds a prefix for a given namespace URI. You can perform reverse lookups by entering a namespace URI into the box on the start page, or by using the <em>reverse lookup API</em>.</p>
    <p><strong>Query URL:</strong><br />
      <?php echo $base; ?>reverse</p>
    <p><strong>GET query parameters:</strong><br />
      <code>uri</code> &#8211; URLencoded namespace URI<br />
      <code>format</code> &#8211; any of the <a href="about/formats">supported formats</a> (optional)</p>
    <p><strong>Examples:</strong><br /><small>
      <a href="reverse?uri=http://xmlns.com/foaf/0.1/"><?php echo $base; ?>reverse?uri=http://xmlns.com/foaf/0.1/</a><br />
      <a href="reverse?uri=http://xmlns.com/foaf/0.1/name"><?php echo $base; ?>reverse?uri=http://xmlns.com/foaf/0.1/name</a><br />
      <a href="reverse?uri=http://xmlns.com/foaf/0.1/&amp;format=n3"><?php echo $base; ?>reverse?uri=http://xmlns.com/foaf/0.1/&amp;format=n3</a><br />
      <a href="reverse?uri=http%3A%2F%2Frdfs.org%2Fsioc%2Fns%23site"><?php echo $base; ?>reverse?uri=http%3A%2F%2Frdfs.org%2Fsioc%2Fns%23site</a><br />
    </small></p>
    <p>“Bulk lookup” of many URIs in a single request is not supported by the URIs. Please download the <a href="popular/all">entire namespace data</a> and perform bulk operations locally.</p>
  </div>
