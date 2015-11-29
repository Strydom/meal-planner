<?php
$ch = curl_init();

//i = ingredients, q = query meal, p = page
//i=onions,garlic&q=omelet&p=3
curl_setopt($ch, CURLOPT_URL, 'http://www.recipepuppy.com/api/?i=chicken');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = json_decode(curl_exec($ch), true);
$results = $data['results'];