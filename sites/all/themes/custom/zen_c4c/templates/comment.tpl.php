<?php
/**
 * @file
 * Returns the HTML for comments.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728216
 */
?>
<article class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <header>
    <p class="submitted submitted-author">
      <?php print $picture; ?>
    </p>
    <?php print render($title_prefix); ?>
    <?php if ($title): ?>
      <h3<?php print $title_attributes; ?>>
        <?php print $title; ?>
        <?php if ($new): ?>
          <mark class="new"><?php print $new; ?></mark>
        <?php endif; ?>
      </h3>
    <?php elseif ($new): ?>
      <mark class="new"><?php print $new; ?></mark>
    <?php endif; ?>
    <?php print render($title_suffix); ?>
    <p class="submitted submitted-time">
      <?php 
        //print $submitted;
        print t('Published by @author on @day of @month, at @hour hours and @minutes minutes', 
                array(
                      '@author' => strip_tags($author), 
                      '@day' => format_date($comment->created, 'custom', 'd'), 
                      '@month' => format_date($comment->created, 'custom', 'F'), 
                      '@hour' => format_date($comment->created, 'custom', 'h'), 
                      '@minutes' => format_date($comment->created, 'custom', 'i'),
                )
              );
      ?>
    </p>
    <p class="submitted submitted-permalink">
      <?php print $permalink; ?>
    </p>

    <?php if ($unpublished): ?>
      <mark class="watermark"><?php print t('Unpublished'); ?></mark>
    <?php elseif ($preview): ?>
      <mark class="watermark"><?php print t('Preview'); ?></mark>
    <?php endif; ?>
  </header>

  <?php
    // We hide the comments and links now so that we can render them later.
    hide($content['links']);
    print render($content);
  ?>

  <?php if ($signature): ?>
    <footer class="user-signature clearfix">
      <?php print $signature; ?>
    </footer>
  <?php endif; ?>

  <?php print render($content['links']) ?>
</article>
