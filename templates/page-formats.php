  <div class="page-about">
    <h1>supported formats</h1>
    <p>You can look up prefixes directly by typing URLs into your browser bar, such as <code><a href="foaf.ttl"><?php echo $base; ?>foaf.ttl</a></code>. You can choose between different syntaxes for displaying the prefix mapping by replacing <code>ttl</code> with values from the following table.</p>

    <table>
      <tr><th id="format">Format</th><th>Description</th></tr>
<?php foreach ($formats as $format => $details) { ?>
      <tr><td><code><?php e($format); ?></code></td><td><?php echo $details['description']; ?></td></tr>
<?php } ?>
    </table>
  </div>
