<?php


function  checkuser($username) {
  $result = db_query("SELECT name FROM {users} WHERE name = :uname",array(':uname' => $username))->fetchField();
  $existe = 0;
  if($result) {
    // el usuario existe
    $existe = 1;
  }
  return $existe;

}

function social_FBAuth($args) {
      /* El usuario se ha logueado por facebook, procede a crear el usuario en drupal  */



      $email = $args;

      if(checkuser($email) != 1) {

        // El usuario no existe, lo crea con rol de usuario autenticado
        $account = entity_create('user', array());
        $account->name = $email;
        $account->mail = $email;
        $account->password = "c4nc3r83r0";
        $account->status = 1;
        $account->roles = array(
              DRUPAL_AUTHENTICATED_RID => 'authenticated user',
              );
        entity_save('user', $account);

      }

        // Loguea al usuario

        $user_obj = user_load_by_name($email);
        $form_state = array();
        $form_state['uid'] = $user_obj->uid;
        user_login_submit(array(), $form_state);

        drupal_goto("usos");
}
 ?>
