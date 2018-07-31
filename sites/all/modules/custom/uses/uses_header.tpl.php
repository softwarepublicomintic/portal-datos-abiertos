<?php

  global $language;

    $uses_path = drupal_get_path("module","uses");

  drupal_add_css($uses_path."/css/usos.css");

  if ($language->name=="Spanish")
  {
    $route_lang = "/es";
  }
  else {
    $route_lang = "";
  }



$backgroundimage =  variable_get('backgroundimage','');
$Description_uses = variable_get('Description_uses','Usos de datos del portal de datos abiertos');


// Carga el objeto file para poder encontrar la imagen

$file_img  = file_load($backgroundimage);
$imgpath = $imgpath->filename;

// Si no existe crea el tamaño personalizado



$image_uri      = $file_img->uri;
$style          = 'usos_head';
$derivative_uri = image_style_path($style, $image_uri);
$success        = file_exists($derivative_uri) || image_style_create_derivative(image_style_load($style), $image_uri, $derivative_uri);
$new_image_url  = file_create_url($derivative_uri);



$image_style = theme_image_style(array('style_name' => $style, 'path' => basename($new_image_url),'alt' => "",  'attributes' => array('class' => 'image'),'width' => NULL, 'height' => NULL,'data-u' => 'image'  ));

?>


<div class="header_uses">
  <div class="img_uses">
    <?php print $image_style; ?>
  </div>
  <div class="Desc_uses">
    <span class="description_use"><?php print $Description_uses; ?></span>
  </div>
</div>
<div class = "uses_buttons">
  <div class="postula_use">
     <div class="cont-postula">
       <div class="text-postula">
         <a href="<?php echo $route_lang; ?>/node/add/uses"><?php print t("<b>Postulate your</b> use of open data"); ?></a>
       </div>
    </div>
  </div>
  <div class="view_uses">
     <div class="cont-view">
       <div class="text-view">
          <a href="https://www.datos.gov.co/Ciencia-Tecnolog-a-e-Innovaci-n/Reportes-sobre-la-secci-n-de-usos/mfgt-ttb7"><?php print t("<b>View</b> Usage"); ?></a>
        </div>
    </div>
  </div>
</div>
