<?php

namespace Drupal\apitest;

use Drupal\Core\Http\ClientFactory;
use Drupal\apitest\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException; 
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class APIConnector {
  private $client;
  private $query;
  private $error;
  private $error_message;
  private $bearer;

  public function __construct(ClientFactory $client){
  }

  /**
   *   GENERIC
   */

  public function getNotProtected() {
    $endpoint = "facebook/notprotected.html";
    $method = "GET";
    $api_url = $this->getApiUrl();
    $data = [];
    return $this->perform_http_request($method,$api_url.$endpoint,$data);   
  }

  public function getSignedNotProtectedWithToken() {
    $endpoint = "nonprot/index.html";
    $method = "GET";
    $api_url = $this->getApiUrl();
    $data = $this->getHeader();
    echo var_dump($data);
    return $this->perform_http_request($method,$api_url.$endpoint,$data);   
  }

  public function getSignedNotProtectedWithoutToken() {
    $endpoint = "nonprot/index.html";
    $method = "GET";
    $api_url = $this->getApiUrl();
    $data = [];
    echo var_dump($data);
    return $this->perform_http_request($method,$api_url.$endpoint,$data);   
  }

  public function getAuthenticatedProtected() {
    $endpoint = "prot/auth/index.html";
    $method = "GET";
    $api_url = $this->getApiUrl();
    $data = $this->getHeader();
    return $this->perform_http_request($method,$api_url.$endpoint,$data);   
  }

  public function getAdministratorProtected() {
    $endpoint = "prot/admin/index.html";
    $method = "GET";
    $api_url = $this->getApiUrl();
    $data = $this->getHeader();
    return $this->perform_http_request($method,$api_url.$endpoint,$data);   
  }

  public function getAuthenticatedAdministratorProtected() {
    $endpoint = "prot/authadmin/index.html";
    $method = "GET";
    $api_url = $this->getApiUrl();
    $data = $this->getHeader();
    return $this->perform_http_request($method,$api_url.$endpoint,$data);   
  }

  /**
   *   ERROR METHODS      '<br><br>' . 

   */

  public function getError() {
    return $this->error;
  }

  public function getErrorMessage() {
    return $this->error_message;
  }

  /**
   *   AUXILIATY METHODS
   */

   public function getApiUrl() {
    return "http://192.168.1.13:9000/";
  }

  public function getHeader() {
    if ($this->bearer == NULL) {
      $this->bearer = "Bearer " . JWT::jwt();
    }
    return ['headers' => 
      [
        //'Authorization' => "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJPbmxpbmUgSldUIEJ1aWxkZXIiLCJpYXQiOjE2OTA4Mjk0MTAsImV4cCI6MTcyMjM2NTQxMCwiYXVkIjoid3d3LmV4YW1wbGUuY29tIiwic3ViIjoianJvY2tldEBleGFtcGxlLmNvbSIsIkdpdmVuTmFtZSI6IlBhdWxvIiwiU3VybmFtZSI6IlJpYmVpcm8iLCJFbWFpbCI6ImFwaXRlc3RAZ21haWwuY29tIiwiUm9sZSI6Ik1hbmFnZXIifQ.8yM3pqWir9PM_NLmrAWPDDDugWoGt3se0W3zjzFmZ0E"
        'Authorization' => $this->bearer
      ]
    ];
  }

  public function perform_http_request($method, $url, $data = false) {   
    //dpm($url);    
    $client = new Client();
    $res=NULL;
    $this->error=NULL;
    $this->error_message="";
    try {
      $res = $client->request($method,$url,$data);
    } 
    catch(ConnectException $e){
      $this->error="CON";
      $this->error_message = "Connection error the following message: " . $e->getMessage();
      return(NULL);
    }
    catch(ClientException $e){
      $res = $e->getResponse();
      if($res->getStatusCode() != '200') {
        $this->error=$res->getStatusCode();
        $this->error_message = "API request returned the following status code: " . $res->getStatusCode();
        return(NULL);
      }
    } 
    return($res->getBody());    
  }

  public function parseObjectResponse($response) {
    if ($response == NULL || $response == "") {
        return NULL;
    }
    $obj = json_decode($response);
    if ($obj->isSuccessful) {
      return $obj->body;
    }
    return NULL; 
  }

}