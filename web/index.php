<?php

require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers
$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('home.twig');
});

$app->get('/suggestions', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('suggestions.twig');
});


$foods = [
    1 => [
        'name'      => 'Chicken',
        'image'    => 'chicken.jpg',
        'next' => 2
    ],
    2 => [
        'name'      => 'Beef',
        'image'    => 'beef.jpg',
        'next' => 1
    ]
];


$app->get('/suggestions/swipe/{id}', function($id) use($app, $foods) {
    $app['monolog']->addDebug('logging output.');
    return $app['twig']->render('swipe.twig', $foods[$id]);
});

$app->get('/suggestions/meals/{ingredients}', function($ingredients) use($app) {
    $app['monolog']->addDebug('logging output.');
    
    $ch = curl_init();
    //i = ingredients, q = query meal, p = page
    //i=onions,garlic&q=omelet&p=3
    curl_setopt($ch, CURLOPT_URL, 'http://www.recipepuppy.com/api/?i=' . $ingredients);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = json_decode(curl_exec($ch), true);
    $results = ['meals'=>$data['results']];
    
    return $app['twig']->render('meal_suggestions.twig', $results);
});



$app->run();