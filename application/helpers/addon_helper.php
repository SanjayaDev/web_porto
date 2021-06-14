<?php 

function create_response() {
  $response = new stdClass();
  $response->success = FALSE;
  $response->message = "Unknown Failure";
  $response->found = FALSE;
  $response->data = [];

  return $response;
}