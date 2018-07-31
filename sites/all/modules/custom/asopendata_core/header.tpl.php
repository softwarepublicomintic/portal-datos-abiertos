<?php

/*
<link type="text/css" media="all" rel="stylesheet" href="custom.css">
<link type="text/css" media="all" rel="stylesheet" href="current_site.css">
<link type="text/css" media="all" rel="stylesheet" href="base.css">
<link type="text/css" media="all" rel="stylesheet" href="inline.css">
<link type="text/css" media="all" rel="stylesheet" href="opendata.css">
*/

global $language;
global $base_path;
global $current_path;

$agc_path = drupal_get_path("module","asopendata_core");
// drupal_add_js($agc_path."/socratacomp.js");

/* Estilos */
drupal_add_css($agc_path."/css/custom.css");
drupal_add_css($agc_path."/css/current_site.css");
// drupal_add_css($agc_path."/css/base.css");
drupal_add_css($agc_path."/css/inline.css");
drupal_add_css($agc_path."/css/opendata.css");

/* Javascript para funcionamiento del search y el combo */
// drupal_add_js("http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js");
//drupal_add_js($agc_path."/socrata_site_chrome/javascript/application.js");
drupal_add_js($agc_path."/socratacomp.js");


 ?>


<header id="site-chrome-header" class="" role="banner" template="rally">
  <div class="banner">
    <div class="rally-top">
      <div class="header-content">
        <a class="logo" href="/"><img src="https://www.datos.gov.co/api/assets/A69B8012-475F-460B-BCCF-D58B57AA48AD?LOGOS--DATOS-ABIERTOS.png" alt="Colombia Datos Abiertos" onerror="this.style.display=&quot;none&quot;"><span class="site-name"></span></a>

        <div class="searchbox" data-autocomplete="true" data-autocomplete-disable-animation="true" data-autocomplete-mobile="false" aria-expanded="false" style="display: block;">
            <div data-reactroot="" tabindex="-1" class="autocomplete-components-___autocomplete__container___2p2jZ">
              <form class="autocomplete-components-SearchBox-___search-box__form___1p39w" action="https://www.datos.gov.co/browse" method="get">
                <div class="autocomplete-components-SearchBox-___search-box__icon-container-static___1S6jR">
                  <span class="socrata-icon socrata-icon-search"><!--?xml version="1.0" encoding="UTF-8" standalone="no"?-->
                    <svg width="1024px" height="1024px" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <!-- Generator: Sketch 3.7.1 (28215) - http://www.bohemiancoding.com/sketch -->
                      <title>search</title>
                      <desc>Created with Sketch.</desc>
                      <defs></defs>
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g fill="#000000">
                          <path d="M709.327422,586.85332 C739.472695,535.59207 756.948203,475.44707 756.948203,411.515156 C756.948203,220.886484 602.290273,66.3741016 411.661602,66.3741016 C221.03293,66.3741016 66.375,221.032031 66.375,411.515156 C66.375,601.998281 221.03293,756.656211 411.661602,756.656211 C475.44707,756.656211 535.737617,739.180703 586.999766,709.03543 L835.589336,957.624102 L957.625898,835.587539 L709.32832,586.85332 L709.327422,586.85332 Z M411.661602,641.755039 C284.381758,641.755039 181.422617,538.795898 181.422617,411.516055 C181.422617,284.236211 284.381758,181.421719 411.661602,181.421719 C538.941445,181.421719 641.900586,284.380859 641.900586,411.516055 C641.900586,538.65125 538.941445,641.755039 411.661602,641.755039 L411.661602,641.755039 Z"></path>
                        </g>
                      </g>
                    </svg>
                  </span>
                </div>
                <label for="autocomplete-search-input-30192" class="autocomplete-components-SearchBox-___search-box__aria-not-displayed___26SOZ">Buscar</label>
                <input autocomplete="off" class="autocomplete-input autocomplete-components-SearchBox-___search-box__search-box-static___2VEX1" id="autocomplete-search-input-30192" placeholder="Buscar" value="" type="search" name="q">
                <input type="hidden" value="relevance" name="sortBy">
              </form>
              <noscript></noscript>
            </div>
          </div>

      </div>
    </div>

    <div class="rally-bottom">
      <div class="header-content">
        <nav class="desktop" aria-label="Encabezados del encabezamiento" style="display: block; opacity: 1;" aria-hidden="false">
          <div class="navbar-left">
        <!--    <div class="site-chrome-nav-links">
              <a class="site-chrome-nav-link noselect" href="/">Inicio</a>
              <a class="site-chrome-nav-link noselect" href="/browse?sortBy=newest">Descubre</a>
              <a class="site-chrome-nav-link noselect" href="/datasets/new">Publica</a>
              <a class="site-chrome-nav-link noselect" href="/browse?limitTo=charts">Visualiza</a>
              <a class="site-chrome-nav-link noselect" href="/nominate">Participa</a>
              <a class="site-chrome-nav-link noselect" href="https://herramientas.datos.gov.co/es/content/tools">Herramientas</a>
              <a class="site-chrome-nav-link noselect" href="https://herramientas.datos.gov.co/es/news">Novedades</a>
            </div> -->
            <div class="panel-pane pane-block pane-system-main-menu panel-pane main-menu">
            <?php
                   /* Llama al bloque main menú */
                    $block = module_invoke('menu', 'block_view', 'main-menu');
                    print render($block['content']);
            ?>
          </div>
          </div>

          <div class="navbar-right">
            <div class="site-chrome-social-links">

            </div>

              <div class="gtranslate_b">
                <?php
                        $gblock = module_invoke('gtranslate', 'block_view','gtranslate');
                        print render($gblock['content']); 
                 ?>

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
                            <span class="socrata-icon-arrow-down"></span>
                            </span>
                            <ul class="dropdown-options">
                              <li>
                                <a class="language-switcher-option" href="<?php print $base_path; ?>en/">English</a>
                              </li>
                              <li>
                                <a class="language-switcher-option" href="<?php print $base_path; ?>es/">Español</a>
                              </li>
                            </ul>
                        </div>
                  </div>
                </div>

<?php  /*
            <div class="user-actions noselect">
              <?php if (!$user->uid) { ?>
                    <a class="sign-in-toggle site-chrome-btn" href="http://www.datos.gov.co/login"><?php echo t("Sign In"); ?></a>
              <?php }
              else { ?>
                   <a class="sign-in-toggle site-chrome-btn" href="<?php echo $base_path; ?>user/logout"><?php echo t("Sign Out"); ?></a>

              <?php } ?>

           </div>  */
?>

          <!-- Login Switcher -->
            <?php if (!$user->uid) {
                      $posurl = request_uri();
                      if (strpos($posurl,"/usos") == false) { ?>
                             <a class="sign-in-toggle site-chrome-btn" href="http://www.datos.gov.co/login"><?php echo t("Sign In"); ?></a>
                      <?php }
                      else { ?>

                         <div class="login-switcher-container">
                <div class="login-switcher noselect">
                    <div data-dropdown="" data-orientation="bottom" class="dropdown dropdown-orientation-bottom" role="button" aria-expanded="false" data-value="" data-index="-1">
                        <span><?php echo t("Sign In"); ?>
                        <span class="socrata-icon-arrow-down"></span>
                        </span>
                        <ul class="dropdown-options">
                          <li>
                            <a class="login-switcher-option" href="<?php print $base_path; ?>social">Ingresar en Usos</a>
                          </li>
                        <li>
                            <a class="login-switcher-option" href="http://www.datos.gov.co/login">Ingresar por Socrata</a>
                          </li>
                        </ul>
                    </div>
              </div>
            </div>
          <?php
             }
         }
          else { ?>
               <div class="user-actions noselect">
                    <a class="sign-in-toggle site-chrome-btn" href="<?php echo $base_path; ?>user/logout"><?php echo t("Sign Out"); ?></a>
               </div>
          <?php } ?>


          <!-- End Login switcher -->



          </div>
        </nav>

        <nav class="mobile" aria-label="Encabezados del encabezamiento" style="display: none; opacity: 1;" aria-hidden="true">
          <a class="site-chrome-btn menu-toggle" href="#" onclick="mobileMenuToggle()">
            <span class="socrata-icon-hamburger"></span>
            Menú
          </a>
        </nav>
      </div>
    </div>
  </div>
  <div class="mobile-menu" aria-expanded="false" aria-hidden="true" hidden="hidden">
  <h2 class="menu-header-title">Menú</h2>
  <a class="menu-toggle" aria-label="Cerrar" href="#" onclick="mobileMenuToggle()">
    <span class="socrata-icon-close-2"><span class="aria-not-displayed">Close</span></span>
  </a>

  <div class="menu-content">
    <div class="site-chrome-nav-links">
      <a class="site-chrome-nav-link mobile-button noselect" href="http://www.datos.gov.co">Inicio</a>
      <a class="site-chrome-nav-link mobile-button noselect" href="http://www.datos.gov.co/browse?sortBy=newest">Descubre</a>
      <a class="site-chrome-nav-link mobile-button noselect" href="http://www.datos.gov.co/datasets/new">Publica</a>
      <a class="site-chrome-nav-link mobile-button noselect" href="http://www.datos.gov.co/browse?limitTo=charts">Visualiza</a>
      <a class="site-chrome-nav-link mobile-button noselect" href="http://www.datos.gov.co/nominate">Participa</a>
      <a class="site-chrome-nav-link mobile-button noselect" href="/es/content/tools">Herramientas</a>
      <a class="site-chrome-nav-link mobile-button noselect" href="/es/news">Novedades</a>
      <a class="site-chrome-nav-link mobile-button noselect" href="/es/usos">Usos</a>
      <a class="site-chrome-nav-link mobile-button noselect" href="/es/reportes">Reportes</a>
    </div>

    <div class="mobile-user-actions noselect">
      <?php if (!$user->uid) { ?>
          <a class="sign-in-toggle site-chrome-mobile-btn" href="http://www.datos.gov.co/login"><?php echo t("Sign In"); ?></a>
        <?php }
        else { ?>
            <a class="sign-in-toggle site-chrome-mobile-btn" href="<?php echo $base_path; ?>user/logout"><?php echo t("Sign Out"); ?></a>
              <?php } ?>
    </div>


    <div class="searchbox" data-autocomplete="true" data-autocomplete-disable-animation="true" data-autocomplete-mobile="true" aria-expanded="false">
      <div data-reactroot="" tabindex="-1" class="autocomplete-components-___autocomplete__container___2p2jZ">
        <form class="autocomplete-components-SearchBox-___search-box__form___1p39w" action="https://www.datos.gov.co/browse" method="get">
          <div class="autocomplete-components-SearchBox-___search-box__icon-container-static-mobile___2f4jC">
            <span class="socrata-icon socrata-icon-search"><!--?xml version="1.0" encoding="UTF-8" standalone="no"?-->
<svg width="1024px" height="1024px" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <!-- Generator: Sketch 3.7.1 (28215) - http://www.bohemiancoding.com/sketch -->
    <title>search</title>
    <desc>Created with Sketch.</desc>
    <defs></defs>
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g fill="#000000">
            <path d="M709.327422,586.85332 C739.472695,535.59207 756.948203,475.44707 756.948203,411.515156 C756.948203,220.886484 602.290273,66.3741016 411.661602,66.3741016 C221.03293,66.3741016 66.375,221.032031 66.375,411.515156 C66.375,601.998281 221.03293,756.656211 411.661602,756.656211 C475.44707,756.656211 535.737617,739.180703 586.999766,709.03543 L835.589336,957.624102 L957.625898,835.587539 L709.32832,586.85332 L709.327422,586.85332 Z M411.661602,641.755039 C284.381758,641.755039 181.422617,538.795898 181.422617,411.516055 C181.422617,284.236211 284.381758,181.421719 411.661602,181.421719 C538.941445,181.421719 641.900586,284.380859 641.900586,411.516055 C641.900586,538.65125 538.941445,641.755039 411.661602,641.755039 L411.661602,641.755039 Z"></path>
        </g>
    </g>
</svg>
</span>
</div>
<label for="autocomplete-search-input-9137" class="autocomplete-components-SearchBox-___search-box__aria-not-displayed___26SOZ">Buscar</label>
<input autocomplete="off" class="autocomplete-input autocomplete-components-SearchBox-___search-box__search-box-static-mobile___3wIaB" id="autocomplete-search-input-9137" placeholder="Buscar" value="" type="search" name="q">
<input type="hidden" value="relevance" name="sortBy">

</form>
<noscript></noscript>
</div>
</div>


    <div class="site-chrome-nav-links site-chrome-social-links">

    </div>

    <div class="mobile-language-dropdown noselect">
  <a href="#" class="mobile-language-dropdown-title mobile-button">
    Lenguaje
    <span class="socrata-icon-arrow-down"></span>
  </a>

  <div class="mobile-language-dropdown-options">
      <div class="mobile-language-dropdown-option mobile-button"><a class="language-switcher-option" href="<?php print $base_path; ?>en/">English</a></div>
      <div class="mobile-language-dropdown-option mobile-button"><a class="language-switcher-option" href="<?php print $base_path; ?>es/">Español</a></div>
  </div>
</div>

  </div>
</div>
</header>
