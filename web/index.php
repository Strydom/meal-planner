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
  return $app->redirect('/profile');
});

$string = file_get_contents("foods.json");
$foods = json_decode($string, true);

$app->get('/suggestions', function() use($app, $foods) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('suggestions.twig', $foods);
});

$app->get('/suggestions/swipe', function() use($app, $foods) {
    $app['monolog']->addDebug('logging output.');
    return $app['twig']->render('swipe.twig', $foods);
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


$app->get('/profile', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('profile.twig');
});

$app->get('/profile/ingredients', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('profile_ingredients.twig');
});

$app->get('/profile/meals', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('profile_meals.twig');
});

$app->get('/profile/groups', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('profile_groups.twig');
});

$app->get('/profile/calendar', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('profile_calendar.twig');
});


$app->run();