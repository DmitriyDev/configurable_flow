# This is proof of concept, but will be improved.
Basic implementation of execution flow, described in config file

`app` folder contains application files.

`Workflow` folder contains reusable interfaces and processing


*Basic example of config*

**Main idea:** 

Any flow can be divided into such components:

**Thread** - process, that contains different steps or links to another threads.

**Step** - simple action that operate with state and using one context.

**Context** - Composite immutable object that contains data for specific thread and steps.

**State** - Mutable object that will be changes during thread processing.

**Basic config file that describes flow (example: flowConfig.yml):** 
    
    # flowConfig.yml
    App\Steps\IncrementStep: &IncrementStep
      class: App\Steps\IncrementStep
      dependencies: []
    
    App\Steps\DecrementStep: &DecrementStep
      class: App\Steps\DecrementStep
      dependencies: []
    
    calculate_thread: &calculate_thread
      class: App\Thread\CalculationThread
      context: App\Context\CalculationContextComposite
      steps:
        - *IncrementStep
        - *DecrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
    
    calculate_thread_two:
      class: App\Thread\CalculationThread
      context: App\Context\CalculationContextComposite
      steps:
        - *calculate_thread
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *DecrementStep


Here we can see that `calculate_thread` contains only threads, but `calculate_thread_two` call `calculate_thread` as a first step.


**Example usage**

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

Output: 

    /var/www/app/index.php:19:
    int(40)
    /var/www/app/index.php:26:
    int(70)


---
Functionality in progress:
- history log
- dependency injection
- rollback functionality
- error handling
- more examples of usage
