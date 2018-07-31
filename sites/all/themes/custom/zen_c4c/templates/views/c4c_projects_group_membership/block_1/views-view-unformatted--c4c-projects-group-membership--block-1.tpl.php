<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<?php if (!empty($title)): ?>
  <h3><?php print $title; ?></h3>
<?php endif; ?>

<?php
  $count = 0;
  foreach ($rows as $id => $row): ?>
  <?php if ( $count ) { print ", "; } $count++; ?>
  <span<?php if ($classes_array[$id]) { print ' class="' . $classes_array[$id] .'"';  } ?>>
    <?php print $row; ?>
  </span>
<?php endforeach; ?>