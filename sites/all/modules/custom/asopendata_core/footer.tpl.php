<?php

global $language;
global $base_path;



 ?>

<footer id="site-chrome-footer" class="" role="contentinfo">
      <div class="footer-content title-absent">
        <div class="logo">
          <a href="http://www.mintic.gov.co/"><img src="//www.datos.gov.co/api/assets/18AE049A-A65E-4537-AD77-CB77B6AFF112?channels-541_logos_pais.png" alt="www.datos.gov.co" onerror="this.style.display=&quot;none&quot;"><span class="site-name"></span></a>
        </div>
        <div class="links">
          <ul class="links-col">
                <li>
                  <a class="footer-link" href="https://herramientas.datos.gov.co/es/terms-and-conditions-es">Políticas de Privacidad y Condiciones de uso</a>
                </li>
                <li>
                  <a class="footer-link" href="http://www.mintic.gov.co">Todos Por Un Nuevo País</a>
                </li>
                <li>
                  <a class="footer-link" href="mailto:soporteccc@mintic.gov.co">Soporte</a>
                </li>
                <li>
                 <a class="footer-link" href="http://www.datosabiertos.gob.pe/">Peru</a>
               </li>
               <li>
                 <a class="footer-link" href="https://datos.gob.mx/">México</a>
               </li>
               <li>
                 <a class="footer-link" href="http://datos.gob.cl/">Chile</a>
               </li>
          </ul>
        </div>
        <div class="addendum">
          <div class="site-chrome-copyright">© 2017 Ministerio de Tecnologías de la Información y las Comunicaciones Edificio Murillo Toro Cra. 8a entre calles 12 y 13, Bogotá, Colombia - Código Postal 111711 Línea gratuita: 01-8000-910742, Bogotá (571) 390 79 51 - Lunes a Viernes de 7 a.m. a 6 p.m. y Sábados de 8 a.m. a 1 p.m.</div>

            <div class="language-switcher-container">
  <div class="language-switcher noselect">
    <div data-dropdown="" data-orientation="top" class="dropdown dropdown-orientation-top" role="button" aria-expanded="false" data-value="" data-index="-1">
      <span>  <?php
           if ($language->name=="Spanish")
           {
             print "Español";
           }
           else {
             print $language->name;
           }
        ?>
        <span class="socrata-icon-arrow-up"></span>
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

        </div>
      </div>
    </footer>
