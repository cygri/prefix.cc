  <table class="multi">
    <tbody>
<?php foreach($namespaces as $prefix => $uri) { ?>
<?php if ($uri) { ?>
      <tr>
        <th class="prefix"><a href="<?php e($prefix); ?>"><?php e($prefix); ?>:</a></th>
        <td class="uri"><a href="<?php e($uri); ?>"><?php e($uri); ?></a></td>
      </tr>
<?php } else { ?>
      <tr class="missing">
        <th class="prefix"><a href="<?php e($prefix); ?>"><?php e($prefix); ?>:</a></th>
        <td>not found</td>
      </tr>
<?php } ?>
<?php } ?>
    </tbody>
  </table>
