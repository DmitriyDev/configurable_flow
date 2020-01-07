<?php
include_once 'autoload.php';

use App\ThreadFactory;

echo '\n === Start Thread === \n';

$configFile = 'app/config/flowConfig.yml';

$threadFactory = new ThreadFactory($configFile);

$context = new App\Context\CalculationContextComposite();

$state = new App\State\CalculationState();
$thread = $threadFactory->getThreadByName("calculate_thread", $context);
$thread->run($state);

var_dump($state->getValue());


$state = new App\State\CalculationState();
$thread = $threadFactory->getThreadByName("calculate_thread_two", $context);
$thread->run($state);

var_dump($state->getValue());