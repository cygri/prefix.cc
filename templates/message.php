  <div class="title">
    <h1><?php e($title); ?></h1>
  </div>
<?php if (@$link) { ?>
  <div class="content">
    <h2><a href="<?php e($link); ?>"><?php e($link); ?></h2>
  </div>
<?php } ?>
