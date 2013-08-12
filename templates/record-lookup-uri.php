      <h2 class="expansion">
        <span class="uri"><?php e($uri); ?></span><?php e(@$reference); ?>
        <a class="namespace-link no-caption" href="<?php e($uri . $reference); ?>" rel="nofollow"><img src="images/link.png" alt="link to <?php echo $reference ? 'term' : 'namespace'; ?> URI" title="go to the <?php echo $reference ? 'term' : 'namespace'; ?> URI" /></a>
<?php if (@$show_vote_links) { ?>
        <span class="toolbox">
          <a href="<?php e($prefix . '/vote'); ?>" class="vote up"><img title="vote for this URI as a good expansion of the <?php e($prefix . ':' . @$reference); ?> <?php e($reference ? 'CURIE' : 'prefix'); ?>" src="images/vote-up.png" alt="+1" /></a><a href="<?php e($prefix . '/vote'); ?>" class="vote down"><img title="vote against this URI because it is incorrect or spam" src="images/vote-down.png" alt="+1" /></a>
        </span>
<?php } ?>
      </h2>
