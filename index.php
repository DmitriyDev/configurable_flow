<?php
include_once 'autoload.php';

use App\State\CalculationState;
use App\ThreadFactory;


function logHistory(\Workflow\Interfaces\StateInterface $state)
{
    /** @var \Workflow\HistoryEvent $event */
    foreach ($state->history() as $event) {
        echo "- " .
            $event->getTime()->format('Y-m-d H:i:s') .
            "\t" .
            $event->getEvent()->name() .
            "\t" .
            $event->getStatus() .
            "\t \t" .
            $event->getMessage() . "\n";

    }
}


echo "\n === Start Thread === \n";

$configFile = 'app/config/flowConfig.yml';

$threadFactory = new ThreadFactory($configFile);

$context = new App\Context\CalculationContextComposite();

$state = new App\State\CalculationState();
$thread = $threadFactory->getThreadByName("calculate_thread", $context);
$thread->run($state);

echo "Result: ".$state->getValue() . "\n";

echo "History: \n";
logHistory($state);

$state = new App\State\CalculationState();
$thread = $threadFactory->getThreadByName("calculate_with_business_logic_thread", $context);
$thread->run($state);

echo "Result: ".$state->getValue() . "\n";
echo "History: \n";
logHistory($state);

