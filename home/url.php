<?php
$url = "https://battuta.medunes.net/api/city/in/search/?region=West%20Bengal&key=f2a7fbb4d99d09d17fc8df7431d248e0";
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$res = curl_exec($ch);
curl_close($ch);
$dcd = json_decode($res);
foreach ($dcd as $k) {
$inst[] = "('".$k->city."',".$row['id'].",'".$k->latitude."','".$k->longitude."')";
}

echo implode(",",$inst);

?>