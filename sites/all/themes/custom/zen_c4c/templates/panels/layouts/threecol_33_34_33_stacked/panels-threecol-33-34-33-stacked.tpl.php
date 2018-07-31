<?php
/**
 * @file
 * Template for a 3 column panel layout.
 *
 * This template provides a three column 25%-50%-25% panel display layout, with
 * additional areas for the top and the bottom.
 *
 * Variables:
 * - $id: An optional CSS id to use for the layout.
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['top']: Content in the top row.
 *   - $content['left']: Content in the left column.
 *   - $content['middle']: Content in the middle column.
 *   - $content['right']: Content in the right column.
 *   - $content['bottom']: Content in the bottom row.
 */
?>
<div class="pd--3col-33-stacked" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>
  <?php if ($content['top']): ?>
    <div class="col--top">
      <?php print $content['top']; ?>
    </div>
  <?php endif ?>

  <div class="center-wrapper">
    <div class="col--left">
      <?php print $content['left']; ?>
    </div>

    <div class="col--middle">
      <?php print $content['middle']; ?>
    </div>

    <div class="col--right">
      <?php print $content['right']; ?>
    </div>
  </div>

  <?php if ($content['bottom']): ?>
    <div class="col--bottom">
      <?php print $content['bottom']; ?>
    </div>
  <?php endif ?>
</div>
