<?php

namespace Drupal\apitest\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\apitest\JWT;

class InitController extends ControllerBase{
  
  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function content() {
    $user_id = \Drupal::currentUser()->id();
    $user_entity = \Drupal::entityTypeManager()->getStorage('user')->load($user_id);
    //$user_json = var_dump($user_entity);
    $api = \Drupal::service('apitest.api_connector');

    $user = JWT::show_user();

    $token = "JWT Token: [" . JWT::jwt() . "]";

    $message_not_protected_with_token = "Calling: " . "General With Token<br>";
    $api->getSignedNotProtectedWithToken(); 
    if ($api->getError() == NULL) {
      $message_not_protected_with_token .= "Response: Ok<br>";
    } else {
      $message_not_protected_with_token .= "Response: " . $api->getErrorMessage() . "<br>";
    }   

    $message_not_protected_without_token = "Calling: " . "General Without Token<br>";
    $api->getSignedNotProtectedWithoutToken(); 
    if ($api->getError() == NULL) {
      $message_not_protected_without_token .= "Response: Ok<br>";
    } else {
      $message_not_protected_without_token .= "Response: " . $api->getErrorMessage() . "<br>";
    }   

    $message_authenticated_protected = "Calling: " . "AuthenticatedProtected<br>";
    $api->getAuthenticatedProtected(); 
    if ($api->getError() == NULL) {
      $message_authenticated_protected .= "Response: Ok<br>";
    } else {
      $message_authenticated_protected .= "Response: " . $api->getErrorMessage() . "<br>";
    }   

    $message_administrator_protected = "Calling: " . "AdmininstratorProtected<br>";
    $api->getAdministratorProtected(); 
    if ($api->getError() == NULL) {
      $message_administrator_protected .= "Response: Ok<br>";
    } else {
      $message_administrator_protected .= "Response: " . $api->getErrorMessage() . "<br>";
    }   

    $message_authenticatedadministrator_protected = "Calling: " . "AuthenticatedAdministratorProtected<br>";
    $api->getAuthenticatedAdministratorProtected(); 
    if ($api->getError() == NULL) {
      $message_authenticatedadministrator_protected .= "Response: Ok<br>";
    } else {
      $message_authenticatedadministrator_protected .= "Response: " . $api->getErrorMessage() . "<br>";
    }   

    $content =
      $user . 
      $token .
      '<br><br>' . 
      $message_not_protected_with_token . 
      '<br>' . 
      $message_not_protected_without_token . 
      '<br>' . 
      $message_authenticated_protected . 
      '<br>' . 
      $message_administrator_protected .
      '<br>' . 
      $message_authenticatedadministrator_protected .
      '<br><br>'; 

    $build = [
      '#markup' => $this->t($content),
    ];
    return $build;
  }
  
}
