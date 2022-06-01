<?php
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://driver-vehicle-licensing.api.gov.uk/vehicle-enquiry/v1/vehicles',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{"registrationNumber":"BD69P0W"}',
  CURLOPT_HTTPHEADER => array(
    'x-api-key: auOqUGGMw33GvCZk3kGFD1oNxsknqWUt1NvR4oGS',
    'Content-Type: application/json',
  ),
));

$response = curl_exec($curl);
curl_close($curl);
echo $response;
?>