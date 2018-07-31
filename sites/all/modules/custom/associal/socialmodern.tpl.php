<?php

global $user;
  global $language;

$appid = variable_get('FAppID', '');
$Gappid = variable_get('GAppID', '');
$LAppID = variable_get('LAppID','');
$TAppID = variable_get('TAppID','');
$TAppSecret = variable_get('TAppSecret','');
$TCallBack = variable_get('TCallBack','');

$ruta_cms = $GLOBALS['base_url'];
$ase_path = drupal_get_path("module","associal");

if ($language->name=="Spanish")
{
  $route_lang = "/es";
}
else {
  $route_lang = "";
}


drupal_add_css('https://fonts.googleapis.com/css?family=Roboto');
drupal_add_js('https://apis.google.com/js/api:client.js');

// Coloca el encabezado de Linkedin

$page_keywords = array(
       '#type' => 'html_tag',
       '#tag' => 'script',
       '#attributes' => array(
         'type' => 'text/javascript',
         'src' => 'https://platform.linkedin.com/in.js',
       ),
       '#value' => 'api_key: '.$LAppID.'
                    onLoad: onLinkedInLoad
                    authorize: true
                    lang: es_ES',
     );

drupal_add_html_head($page_keywords, 'Linkedin_conf');


// Coloca en el encabezado el javascript
 drupal_add_js('

      // ****************    Facebook Script Login   ************************



  function validarUsuario() {
              FB.getLoginStatus(function(response) {
                  if(response.status == "connected") {
                      FB.api("/me", {fields: "last_name,email,first_name,name"} ,function(response) {
                          location.href = "'.$ruta_cms.'/social/auth/"+response.email;
                        });

                  } else if(response.status == "not_authorized") {
                      alert("Debes autorizar la app!");
                  } else {
                      alert("Debes ingresar a tu cuenta de Facebook!");
                  }
              });
         }



       // ********************  Google Script Login ************************************

          var googleUser = {};
          var startApp = function() {
            gapi.load("auth2", function(){
              // Retrieve the singleton for the GoogleAuth library and set up the client.
              auth2 = gapi.auth2.init({
                client_id: "'.$Gappid.'",
                cookiepolicy: "single_host_origin",
                // Request scopes in addition to "profile" and "email"
                //scope: "additional_scope"
                scope:"profile"
              });

              // Se adiciona timeout para evitar error en chrome
               setTimeout(attachSignin(document.getElementById("GButton")),5000);


            });
          };

          function attachSignin(element) {
            console.log(element.id);
            auth2.attachClickHandler(element, {},
              function(googleUser) {
                  location.href = "'.$ruta_cms.'/social/auth/"+googleUser.getBasicProfile().getEmail();
              }, function(error) {
                alert(JSON.stringify(error, undefined, 2));
              });
          }

   // ********************  Linkedin Script New ************************************


function liAuth(){
     IN.User.authorize(function(){
     });
 }

  //  Runs when the JavaScript framework is loaded
  function onLinkedInLoad() {
    IN.Event.on(IN, "auth", onLinkedInAuth);
  }


  //  Runs when the viewer has authenticated
  function onLinkedInAuth() {

    IN.API.Profile("me").fields("id","first-name", "last-name", "email-address").result(displayProfiles);
  }

  //  Runs when the Profile() API call returns successfully
  function displayProfiles(profiles) {
    member = profiles.values[0];
    if(member.emailAddress=="undefined") {
      member.emailAddress=member.lastName+"."+member.firstName+"@linkedin.com";
    }

      location.href = "'.$ruta_cms.'/social/auth/"+member.emailAddress;

  }

          '
,'inline');


   // Estilos para botón de google
    drupal_add_css($ase_path."/css/gstyle.css");

?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.11&appId=<?php echo $appid;  ?>';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php

  if (!$user->uid) {

?>

<div class="AS_social_login" style="width: 48%;  text-align: center;  margin: 0 auto; ">


    <div class="AS_item-list">
            <h2 class="components-_signin-module_header_1HYWl">Iniciar sesión a la plataforma de datos abiertos del gobierno colombiano</h2>
            <table class="social-widget">
                <tr>
                    <td class="clm_social" id="FacebookButton" >
                      <span style="margin: 0 auto; ">
                  <!--     <fb:login-button size="xlarge" scope="public_profile,email" onlogin="validarUsuario();" >Ingresar con Facebook</fb:login-button> -->
                        <div class="fb-login-button" data-max-rows="1" data-size="large" data-button-type="login_with" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false"  data-width="307px" data-scope="public_profile,email" onlogin="validarUsuario();"></div>
                     </span>
                    </td>
                </tr>
                <tr>
                    <td class="clm_social" id="GoogleButton">
                      <div id="gSignInWrapper">
                            <div id="GButton" class="customGPlusSignIn">
                              <span class="icon"></span>
                              <span class="buttonText">Ingresar con Google</span>
                            </div>
                              <script>startApp();</script>
                      </div>
                    </td>
                </tr>
                <tr>
                    <td class="clm_social" id="LinkedinButton">
                      <div onclick="liAuth()"><img src="<?php echo $ruta_cms."/".$ase_path; ?>/images/signlink.png" /></div>
                    </td>
                </tr>
                <tr>
                    <td class="clm_social" id="TwitterButton">
                      <a href="<?php echo $ruta_cms."/"; ?>twitter/redirect"><img src="<?php echo $ruta_cms."/".$ase_path; ?>/images/signtwitter.png" /></a>
                            </td>
                </tr>
              </table>

      </div>
</div>

 <?php
}
else {
  $url_goto = $ruta_cms.$route_lang."/usos";
  drupal_goto($url_goto);
}
 ?>
