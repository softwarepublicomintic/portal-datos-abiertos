<?php

global $language;
global $base_path;

$agc_path = drupal_get_path("module","asopendata_core");
// drupal_add_js($agc_path."/socratacomp.js");




?>

<header id="site-chrome-header site-chrome-footer" class="" role="banner" template="rally">
  <div class="header-content footer-content">
<div class="navbar-right footer-navbar">
      <div class="site-chrome-social-links">
      </div>
        <div class="language-switcher-container">
          <div class="language-switcher noselect">
            <div data-dropdown="" data-orientation="bottom" class="dropdown dropdown-orientation-footer" role="button" aria-expanded="false" data-value="" data-index="-1" onclick="VisibilityOptions()">
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
                <ul class="dropdown-options" style="opacity:0; pointer-events:none">
                    <li><a class="language-switcher-option" href="<?php print $base_path; ?>en">English</a></li>
                    <li><a class="language-switcher-option" href="<?php print $base_path; ?>es">Español</a></li>
                </ul>
            </div>
          </div>
        </div>


</div>
</div>
</header>
