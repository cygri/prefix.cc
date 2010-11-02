  <div class="title">
    <h1><?php e($title); ?></h1>
    <div class="note"></div>
  </div>
  <div class="content">
    <div class="expansions">
<?php if (!$uris) { ?>
      <h2 class="notfound">prefix not found</h2>
<?php } ?>
<?php foreach ($uris as $uri) {
    include 'templates/record-lookup-uri.php';
} ?>
    </div>
<?php if (@$show_form) { ?>
    <div id="declaration-form">
      <h2 class="uri"><input class="uri" type="text" size="<?php if (@$reference) { ?>32<?php } else { ?>38<?php } ?>" value="http://" name="create" /><span class="reference"><?php e(@$reference); ?></span></h2>
      <input id="button-declare" type="button" value="submit" />
      <input id="button-cancel" type="button" value="cancel" />
    </div>
    <div id="button-show-form"><a href="<?php e($page_uri); ?>" title="click to enter a<?php if ($uris) { ?>n alternative<?php } ?> URI for the “<?php e($prefix); ?>” prefix">Add <?php if ($uris) { ?>alternative URI<?php } else { ?>a URI for this prefix<?php } ?></a></div>
<?php } ?>
  </div>
