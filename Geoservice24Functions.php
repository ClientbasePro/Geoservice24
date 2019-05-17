<?php

  // Интеграция CRM Clientbase с сервисом GPS-трекинга Geoservice24
  // https://ClientbasePro.ru
  // http://api.geoservice24.ru/api/doc
  

  // функция возвращает PHPSESSID для запросов в других методах
function GetGeoservice24PHPSESSID() {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => GEOSERVICE_URL.'login',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => json_encode(array('login'=>GEOSERVICE_LOGIN,'password'=>GEOSERVICE_PASSWORD))
  ));
  if ($response=curl_exec($curl)) if ($answer=json_decode($response,true)) { curl_close($curl); return ($answer['PHPSESSID']) ? $answer['PHPSESSID'] : false; }             
  curl_close($curl);
  return false;
}


  // функция - общий запрос метода $method
function GetGeoservice24Data($method, $PHPSESSID) {
    // проверка наличия входных данных
  if (!$method) return false;
  if (!$PHPSESSID) $PHPSESSID = GetGeoservice24PHPSESSID();
  if (!$PHPSESSID) return false;
    // отправка запроса
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => GEOSERVICE_URL.$method,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => json_encode(array('PHPSESSID'=>$PHPSESSID))
  ));
  if ($response=curl_exec($curl)) if ($answer=json_decode($response,true)) { curl_close($curl); return $answer; }             
  curl_close($curl);
  return false;
}



  // функция возвращает список пользователей Геосервис24
function GetGeoservice24Accounts($PHPSESSID) {
  if (!$PHPSESSID) $PHPSESSID = GetGeoservice24PHPSESSID();
  if (!$PHPSESSID) return false;
    // запрос
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => GEOSERVICE_URL.'account/list',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array("cookie: PHPSESSID=".$PHPSESSID)
  ));
  if ($response=curl_exec($curl)) if ($answer=json_decode($response,true)) { curl_close($curl); return $answer; }             
  curl_close($curl);
  return false;
}


  // функция разлогинивается в Геосервис24
function Geoservice24Logout($PHPSESSID) {
  if (!$PHPSESSID) $PHPSESSID = GetGeoservice24PHPSESSID();
  if (!$PHPSESSID) return false;
    // запрос
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.geoservice24.ru/logout',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array("cookie: PHPSESSID=".$PHPSESSID)
  ));
  curl_exec($curl);             
  curl_close($curl);
  return true;
}






?>