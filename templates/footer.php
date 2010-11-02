  <div class="footer">
<?php if (@$message) { ?>
    <span class="message"><span><?php e($message); ?></span> | </span>
<?php } ?>
<?php if (@$links) { ?>
    <span class="links<?php if (@$message) { ?> hidden<?php } ?>">
<?php foreach ($links as $uri => $label) { ?>
<?php if (is_array($label)) { $rel = @$label['rel']; $rev = @$label['rev']; $label = @$label[0]; } ?>
<?php if ($label == '|') { echo " | "; continue; } ?>
      <a<?php if (@$rel) { ?> rel="<?php e($rel); ?>"<?php } if (@$rev) { ?> rev="<?php e($rev); ?>"<?php } ?> href="<?php echo e($uri); ?>"><?php echo e($label); ?></a>
<?php } ?>
      |
    </span>
<?php } ?>
    <a rel="home" href="<?php e($base); ?>">prefix.cc</a>
<?php if (@$rdfa) { ?>
    <span class="message" title="This page contains data in RDFa format.">[rdfa]</span>
<?php } ?>
  </div>
  <div id="logos">
    <a id="nuig-logo" href="http://www.nuigalway.ie/" title="National University of Ireland, Galway"><img src="images/nuig-logo-32px.png" alt="" /></a><a id="deri-logo" href="http://deri.ie/" title="Digital Enterprise Research Institute"><img src="images/deri-logo-32px.png" alt="" /></a>
  </div>
<?php include 'templates/google-analytics.html'; ?>
</body>
</html>
<?php if (@$page_hidden_message) { ?>
<!-- <?php e($page_hidden_message); ?> -->
<?php } ?>
