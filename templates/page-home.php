  <div class="title home">
    <h1>prefix.cc</h1>
    <div>namespace lookup for RDF developers</div>
  </div>
  <form class="content" method="get" action="">
    <div>
      <input class="home autofocus" type="text" name="q" size="24" value="foaf" />
      <input type="submit" value="look up" />
    </div>
    <div class="note examples">
      <small>examples: 
<?php foreach ($examples as $example_uri => $example_label) { ?>
        <a href="<?php e($example_uri); ?>" rel="nofollow"><?php echo e($example_label); ?></a>
<?php } ?>
      </small>
    </div>
  </form>
