<?php
/**
 * @file
 * Returns the HTML for a single Drupal page.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728148
 */

 global $language;
 global $base_path;
?>

<div class="layout-center">

  <header class="header" role="banner">

    <?php if ($logo): ?>
      <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" class="header__logo"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" class="header__logo-image" /></a>
    <?php endif; ?>

    <?php if ($site_name || $site_slogan): ?>
      <div class="header__name-and-slogan">
        <?php if ($site_name): ?>
          <h1 class="header__site-name">
            <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" class="header__site-link" rel="home"><span><?php print $site_name; ?></span></a>
          </h1>
        <?php endif; ?>

        <?php if ($site_slogan): ?>
          <div class="header__site-slogan"><?php print $site_slogan; ?></div>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <?php if ($secondary_menu): ?>
      <nav class="header__secondary-menu" role="navigation">
        <?php print theme('links__system_secondary_menu', array(
          'links' => $secondary_menu,
          'attributes' => array(
            'class' => array('links', 'inline', 'clearfix'),
          ),
          'heading' => array(
            'text' => $secondary_menu_heading,
            'level' => 'h2',
            'class' => array('visually-hidden'),
          ),
        )); ?>
      </nav>
    <?php endif; ?>

    <?php print render($page['header']); ?>

  </header>

  <div class="layout-3col layout-swap">

    <?php
      // Render the sidebars to see if there's anything in them.
      $sidebar_first  = render($page['sidebar_first']);
      $sidebar_second = render($page['sidebar_second']);
      // Decide on layout classes by checking if sidebars have content.
      $content_class = 'layout-3col__full';
      $sidebar_first_class = $sidebar_second_class = '';
      if ($sidebar_first && $sidebar_second):
        $content_class = 'layout-3col__right-content';
        $sidebar_first_class = 'layout-3col__first-left-sidebar';
        $sidebar_second_class = 'layout-3col__second-left-sidebar';
      elseif ($sidebar_second):
        $content_class = 'layout-3col__left-content';
        $sidebar_second_class = 'layout-3col__right-sidebar';
      elseif ($sidebar_first):
        $content_class = 'layout-3col__right-content';
        $sidebar_first_class = 'layout-3col__left-sidebar';
      endif;
    ?>

    <main class="<?php print $content_class; ?>" role="main">
      <?php print render($page['highlighted']); ?>
      <?php print $breadcrumb; ?>
      <a href="#skip-link" class="visually-hidden visually-hidden--focusable" id="main-content">Back to top</a>

      <?php if (!$page_title_hide): ?>
        <?php print render($title_prefix); ?>
        <?php if ($title): ?>
          <h1><?php print $title; ?></h1>
        <?php endif; ?>
        <?php print render($title_suffix); ?>
      <?php endif; ?>



      <?php print $messages; ?>
      <?php /* print render($tabs); */?>
      <?php print render($page['help']); ?>

      <?php if (($action_links) && (!$page_title_hide)): ?>
        <ul class="action-links"><?php print render($action_links); ?></ul>
      <?php endif; ?>

      <header id="site-chrome-header" class="" role="banner" template="rally">
        <div class="header-content">
      <div class="navbar-right">
            <div class="site-chrome-social-links">
            </div>
              <div class="language-switcher-container">
                <div class="language-switcher noselect">
                  <div data-dropdown="" data-orientation="bottom" class="dropdown dropdown-orientation-bottom" role="button" aria-expanded="false" data-value="" data-index="-1">
                    <span>
                        <?php
                           if ($language->name=="Spanish")
                           {
                             print "Español";
                           }
                           else {
                             print $language->name;
                           }
                        ?>

                        <span class="socrata-icon-arrow-down"></span></span>
                      <ul class="dropdown-options">
                          <li><a class="language-switcher-option" href="<?php print $base_path; ?>en">English</a></li>
                          <li><a class="language-switcher-option" href="<?php print $base_path; ?>es">Español</a></li>
                      </ul>
                  </div>
                </div>
              </div>

              <?php
                  if(!$user->uid) { ?>
                      <div class="user-actions noselect">
                        <a class="sign-in-toggle site-chrome-btn" href="https://www.datos.gov.co/login"><?php echo t("Login"); ?></a>
                      </div>
                    <?php    }
                  else { ?>
                    <div class="user-actions noselect">
                      <a class="sign-in-toggle site-chrome-btn" href="https://herramientas.datos.gov.co/user/logout"><?php echo t("Logout"); ?></a>
                    </div>
                    <?php
                  }; ?>
      </div>
      <!-- Menu Mobile -->
      <nav class="mobile" aria-label="Encabezados del encabezamiento" aria-hidden="true" >
          <a class="site-chrome-btn menu-toggle" href="#" onclick="mobileMenuToggle()">
            <span class="socrata-icon-hamburger"></span>
            Menú
          </a>
        </nav>
        <div class="mobile-menu" aria-expanded="false" aria-hidden="true" hidden="hidden">
  <h2 class="menu-header-title">Menú</h2>
  <a class="menu-toggle" aria-label="Cerrar" href="#" onclick="mobileMenuToggle()">
    <span class="socrata-icon-close-2"><span class="aria-not-displayed">Close</span></span>
  </a>

  <div class="menu-content">
    <div class="site-chrome-nav-links">
          <a class="site-chrome-nav-link mobile-button noselect" href="https://www.datos.gov.co/e">Inicio</a>
          <a class="site-chrome-nav-link mobile-button noselect" href="https://www.datos.gov.co/browse">Descubre</a>
          <a class="site-chrome-nav-link mobile-button noselect" href="https://www.datos.gov.co/datasets/new">Publica</a>
          <a class="site-chrome-nav-link mobile-button noselect" href="https://www.datos.gov.co/browse?limitTo=charts">Visualiza</a>
          <a class="site-chrome-nav-link mobile-button noselect" href="https://www.datos.gov.co/nominate">Participa</a>
          <a class="site-chrome-nav-link mobile-button noselect" href="https://herramientas.datos.gov.co/es/content/tools">Herramientas</a>
          <a class="site-chrome-nav-link mobile-button noselect" href="https://herramientas.datos.gov.co/es/news">Novedades</a>
    </div>

    <div class="mobile-user-actions noselect">
    <?php
        if(!$user->uid) { ?>
            <a class="sign-in-toggle site-chrome-mobile-btn" href="http://www.datos.gov.co/login">Iniciar sesión</a>
          <?php }
          else {  ?>
            <a class="sign-in-toggle site-chrome-mobile-btn" href="https://herramientas.datos.gov.co/user/logout">Cerrar sesión</a>
          <?php } ?>
</div>

   <!-- Search box mobile -->
  <div class="panel-pane pane-block pane-search-form panel-pane search-form searchbox">
      <form action="/c4c-datos/en" method="post" id="search-block-form" accept-charset="UTF-8" class="autocomplete-components-SearchBox-___search-box__form___1p39w">
          <div>
            <div class="container-inline searchbox"><h2 class="element-invisible">Search form</h2>
                <div class="form-item form-type-textfield form-item-search-block-form">
                  <label class="element-invisible" for="edit-search-block-form--2">Search </label>
                  <input title="Enter the terms you wish to search for." type="text" id="edit-search-block-form--2"  name="search_block_form" value="" size="15" maxlength="128" class="form-text" placeholder="Search">
                </div>
              <div class="form-actions form-wrapper" id="edit-actions">
                  <input type="submit" id="edit-submit" name="op" value="Search" class="form-submit">
              </div>
              <input type="hidden" name="form_build_id" value="form--lFspRgzuGfqjK7BQ1pwvEQ_Eia3PmIVtklUaA5IU_k">
              <input type="hidden" name="form_id" value="search_block_form">
           </div>
         </div>
       </form>
  </div>
<!-- End Search box mobile -->



    <div class="site-chrome-nav-links site-chrome-social-links">

    </div>

    <div class="mobile-language-dropdown noselect">
  <a href="#" class="mobile-language-dropdown-title mobile-button">
    Lenguaje
    <span class="socrata-icon-arrow-down"></span>
  </a>

  <div class="mobile-language-dropdown-options" style="overflow: hidden; display: none;">
      <div class="mobile-language-dropdown-option mobile-button"><a class="language-switcher-option" href="/en/">English</a></div>
      <div class="mobile-language-dropdown-option mobile-button"><a class="language-switcher-option" href="/es/">Español</a></div>
  </div>
</div>

  </div>
</div>

    </div>
  </header>

      <?php print render($page['content']); ?>
      <?php print $feed_icons; ?>

    </main>


    <div class="layout-swap__top layout-3col__full">

      <a href="#skip-link" class="visually-hidden visually-hidden--focusable" id="main-menu" tabindex="-1">Back to top</a>

      <?php if ($main_menu): ?>
        <nav class="main-menu" role="navigation">
          <?php
          // This code snippet is hard to modify. We recommend turning off the
          // "Main menu" on your sub-theme's settings form, deleting this PHP
          // code block, and, instead, using the "Menu block" module.
          // @see https://drupal.org/project/menu_block
          print theme('links__system_main_menu', array(
            'links' => $main_menu,
            'attributes' => array(
              'class' => array('navbar', 'clearfix'),
            ),
            'heading' => array(
              'text' => t('Main menu'),
              'level' => 'h2',
              'class' => array('visually-hidden'),
            ),
          )); ?>
        </nav>
      <?php endif; ?>

      <?php print render($page['navigation']); ?>

    </div>

    <?php if ($sidebar_first): ?>
      <aside class="<?php print $sidebar_first_class; ?>" role="complementary">
        <?php print $sidebar_first; ?>
      </aside>
    <?php endif; ?>

    <?php if ($sidebar_second): ?>
      <aside class="<?php print $sidebar_second_class; ?>" role="complementary">
        <?php print $sidebar_second; ?>
      </aside>
    <?php endif; ?>

  </div>

  <?php print render($page['footer']); ?>

</div>

<?php print render($page['bottom']); ?>
