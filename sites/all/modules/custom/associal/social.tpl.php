<?php


$appid = variable_get('FAppID', '');
$Gappid = variable_get('GAppID', '');
$LAppID = variable_get('LAppID','');

$ruta_cms = $GLOBALS['base_url'];
$ase_path = drupal_get_path("module","associal");

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

      (function(d,s,id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if(d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "http://connect.facebook.net/es_ES/sdk.js";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, "script", "facebook-jssdk"));
        window.fbAsyncInit = function() {
          FB.init({
            appId    : "'.$appid.'",
            cookie   : true,
            xfbml    : true,
            version  : "v2.9"
          });
        }
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
              });

              // Se adiciona timeout para evitar error en chrome
               setTimeout(attachSignin(document.getElementById("GButton")),1000);
            

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
<div class="AS_social_login" style="width: 50%;  text-align: center;  margin: 0 auto; ">
    <div class="AS_item-list">
              <h3><?php echo t("O ingrese con...") ?></h3>
              <table class="social-widget">
                <tr>
                    <td class="clm_social" id="FacebookButton" >
                      <span style="margin: 0 auto; ">
                       <fb:login-button size="xlarge" scope="public_profile,email" onlogin="validarUsuario();" >Facebook</fb:login-button>
                     </span>
                    </td>
                    <td class="clm_social" id="GoogleButton">
                      <div id="gSignInWrapper">
                            <div id="GButton" class="customGPlusSignIn">
                              <span class="icon"></span>
                              <span class="buttonText">Google</span>
                            </div>
                              <script>startApp();</script>
                      </div>
                    </td>
                    <td class="clm_social" id="LinkedinButton">
                      <div onclick="liAuth()"><img src="<?php echo $ruta_cms."/".$ase_path; ?>/images/linkedin.png" /></div>
                    </td>
                </tr>
              </table>

      </div>
</div>
