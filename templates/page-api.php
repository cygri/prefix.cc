  <div class="page-about">
    <h1>namespace lookup API</h1>
    <p>A namespace lookup finds the namespace URI for a given prefix. You can perform lookups by entering a prefix into the box on the start page, or by using the <em>namespace lookup API</em>.</p>
    <p><strong>Query URL:</strong><br />
      <?php echo $base; ?><em>{prefix}</em>.file.<em>{format}</em></p>
    <p><strong>Parameters:</strong><br />
      <code>{prefix}</code> &#8211; prefix to be expanded<br />
      <code>{format}</code> &#8211; any of the <a href="about/formats">supported formats</a></p>
    <p><strong>Examples:</strong><br /><small>
      <a href="foaf.file.txt"><?php echo $base; ?>foaf.file.txt</a><br />
      <a href="gr.file.json"><?php echo $base; ?>gr.file.json</a><br />
      <a href="dcat.file.vann"><?php echo $base; ?>dcat.file.vann</a><br />
    </small></p>
    <p>“Bulk lookup” of many prefixes in a single request is not supported. Please download the <a href="popular/all">entire namespace data</a> and perform bulk operations locally.</p>
  </div>
