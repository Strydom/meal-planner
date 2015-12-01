<?php
if (isset($_POST['likes'])) {
    $likes = explode(",", $_POST['likes']);
    $combinedLikes = [];
    $combinedResults = [];
    $randomCombinedLikes = [];
    $count = count($likes);
    
    if ($count > 3) {
        for ($i = 0; $i < $count; $i++) {
            $random3Likes = array_rand($likes, 3);

            for ($r = 0; $r < 3; $r++) {
                $random3Likes[$r] = $likes[$random3Likes[$r]];
            }

            array_push($combinedLikes, $random3Likes);
        }

        $randomCombinedLikes = array_rand($combinedLikes, 10);

        for ($c = 0; $c < 10; $c++) {
            $randomCombinedLikes[$c] = $combinedLikes[$randomCombinedLikes[$c]];
        }
    } else {
        $randomCombinedLikes = [$likes];
    }
    
    foreach ($randomCombinedLikes as $ingredients) {
        $ingredients = implode($ingredients, ',');
        
        $ch = curl_init();
        
        //i = ingredients, q = query meal, p = page
        //i=onions,garlic&q=omelet&p=3
        curl_setopt($ch, CURLOPT_URL, 'http://www.recipepuppy.com/api/?i=' . $ingredients);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = json_decode(curl_exec($ch), true);
        
        curl_close($ch);
        
        $results = $data['results'];
        array_push($combinedResults, $results);
    }
    
    echo json_encode($combinedResults);
}