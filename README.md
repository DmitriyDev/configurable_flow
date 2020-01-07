# This is proof of concept, but will be improved.
Basic implementation of execution flow, described in config file

`app` folder contains application files.

`Workflow` folder contains reusable interfaces and processing

--- 

**Main idea:** 

Any flow can be divided into such components:

**Thread** - process, that contains different steps or links to another threads.

**Step** - simple action that operate with state and using one context.

**Context** - Composite immutable object that contains data for specific thread and steps.

**State** - Mutable object that will be changes during thread processing.

---

**Final idea:**

Request as a context
Response as a state

---

**Basic config file that describes flow (example: flowConfig.yml):** 
    
    # flowConfig.yml
    App\Steps\IncrementStep: &IncrementStep
      class: App\Steps\IncrementStep
      dependencies: []
    
    App\Steps\DecrementStep: &DecrementStep
      class: App\Steps\DecrementStep
      dependencies: []
    
    App\Steps\NotProcessedStep: &NotProcessedStep
      class: App\Steps\NotProcessedStep
      dependencies: []
    
    App\Steps\NotProcessedStep: &BrokenStep
      class: App\Steps\BrokenStep
      dependencies: []
    
    
    calculate_thread: &calculate_thread
      class: App\Thread\CalculationThread
      context: App\Context\CalculationContextComposite
      steps:
        - *IncrementStep
        - *DecrementStep
        - *IncrementStep
        - *NotProcessedStep
        - *IncrementStep
        - *NotProcessedStep
        - *BrokenStep
        - *IncrementStep
        - *IncrementStep
    
    App\Steps\BusinessLogicMaximumStep: &BusinessLogicMaximumStep
      class: App\Steps\BusinessLogicMaximumStep
      dependencies: []
    
    App\Steps\BusinessLogicMinimumStep: &BusinessLogicMinimumStep
      class: App\Steps\BusinessLogicMinimumStep
      dependencies: []
    
    
    business_logic_thread: &business_logic_thread
      class: App\Thread\BusinessLogicThread
      context: App\Context\CalculationContextComposite
      steps:
        - *BusinessLogicMaximumStep
        - *BusinessLogicMinimumStep
    
    
    calculate_with_business_logic_thread: &calculate_with_business_logic_thread
      class: App\Thread\CalculationThread
      context: App\Context\CalculationContextComposite
      steps:
        - *calculate_thread
        - *IncrementStep
        - *IncrementStep
        - *NotProcessedStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *DecrementStep
        - *business_logic_thread
    
    max_calculate_with_business_logic_thread: &calculate_with_business_logic_thread
      class: App\Thread\CalculationThread
      context: App\Context\CalculationContextComposite
      steps:
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *business_logic_thread
    
    min_calculate_with_business_logic_thread: &calculate_with_business_logic_thread
      class: App\Thread\CalculationThread
      context: App\Context\CalculationContextComposite
      steps:
        - *DecrementStep
        - *DecrementStep
        - *DecrementStep
        - *business_logic_thread




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

     === Start Thread === 
    Result: 40
    History: 
    - 2020-01-07 17:33:11   Calculate thread        Created         
    - 2020-01-07 17:33:11   Calculate thread        In progress             
    - 2020-01-07 17:33:11   Increment step  Created         
    - 2020-01-07 17:33:11   Increment step  In progress             
    - 2020-01-07 17:33:11   Increment step  Success         
    - 2020-01-07 17:33:11   Decrement step  Created         
    - 2020-01-07 17:33:11   Decrement step  In progress             
    - 2020-01-07 17:33:11   Decrement step  Success         
    - 2020-01-07 17:33:11   Increment step  Created         
    - 2020-01-07 17:33:11   Increment step  In progress             
    - 2020-01-07 17:33:11   Increment step  Success         
    - 2020-01-07 17:33:11   Non-processable step    Created         
    - 2020-01-07 17:33:11   Non-processable step    Skipped         
    - 2020-01-07 17:33:11   Increment step  Created         
    - 2020-01-07 17:33:11   Increment step  In progress             
    - 2020-01-07 17:33:11   Increment step  Success         
    - 2020-01-07 17:33:11   Non-processable step    Created         
    - 2020-01-07 17:33:11   Non-processable step    Skipped         
    - 2020-01-07 17:33:11   Broken step     Created         
    - 2020-01-07 17:33:11   Broken step     In progress             
    - 2020-01-07 17:33:11   Broken step     Failure         Step should throw exception
    - 2020-01-07 17:33:11   Increment step  Created         
    - 2020-01-07 17:33:11   Increment step  In progress             
    - 2020-01-07 17:33:11   Increment step  Success         
    - 2020-01-07 17:33:11   Increment step  Created         
    - 2020-01-07 17:33:11   Increment step  In progress             
    - 2020-01-07 17:33:11   Increment step  Success         
    - 2020-01-07 17:33:11   Calculate thread        Success         
    Result: 70
    History: 
    - 2020-01-07 17:33:11   Calculate thread        Created         
    - 2020-01-07 17:33:11   Calculate thread        In progress             
    - 2020-01-07 17:33:11   Calculate thread        Created         
    - 2020-01-07 17:33:11   Calculate thread        In progress             
    - 2020-01-07 17:33:11   Increment step  Created         
    - 2020-01-07 17:33:11   Increment step  In progress             
    - 2020-01-07 17:33:11   Increment step  Success         
    - 2020-01-07 17:33:11   Decrement step  Created         
    - 2020-01-07 17:33:11   Decrement step  In progress             
    - 2020-01-07 17:33:11   Decrement step  Success         
    - 2020-01-07 17:33:11   Increment step  Created         
    - 2020-01-07 17:33:11   Increment step  In progress             
    - 2020-01-07 17:33:11   Increment step  Success         
    - 2020-01-07 17:33:11   Non-processable step    Created         
    - 2020-01-07 17:33:11   Non-processable step    Skipped         
    - 2020-01-07 17:33:11   Increment step  Created         
    - 2020-01-07 17:33:11   Increment step  In progress             
    - 2020-01-07 17:33:11   Increment step  Success         
    - 2020-01-07 17:33:11   Non-processable step    Created         
    - 2020-01-07 17:33:11   Non-processable step    Skipped         
    - 2020-01-07 17:33:11   Broken step     Created         
    - 2020-01-07 17:33:11   Broken step     In progress             
    - 2020-01-07 17:33:11   Broken step     Failure         Step should throw exception
    - 2020-01-07 17:33:11   Increment step  Created         
    - 2020-01-07 17:33:11   Increment step  In progress             
    - 2020-01-07 17:33:11   Increment step  Success         
    - 2020-01-07 17:33:11   Increment step  Created         
    - 2020-01-07 17:33:11   Increment step  In progress             
    - 2020-01-07 17:33:11   Increment step  Success         
    - 2020-01-07 17:33:11   Calculate thread        Success         
    - 2020-01-07 17:33:11   Increment step  Created         
    - 2020-01-07 17:33:11   Increment step  In progress             
    - 2020-01-07 17:33:11   Increment step  Success         
    - 2020-01-07 17:33:11   Increment step  Created         
    - 2020-01-07 17:33:11   Increment step  In progress             
    - 2020-01-07 17:33:11   Increment step  Success         
    - 2020-01-07 17:33:11   Non-processable step    Created         
    - 2020-01-07 17:33:11   Non-processable step    Skipped         
    - 2020-01-07 17:33:11   Increment step  Created         
    - 2020-01-07 17:33:11   Increment step  In progress             
    - 2020-01-07 17:33:11   Increment step  Success         
    - 2020-01-07 17:33:11   Increment step  Created         
    - 2020-01-07 17:33:11   Increment step  In progress             
    - 2020-01-07 17:33:11   Increment step  Success         
    - 2020-01-07 17:33:11   Decrement step  Created         
    - 2020-01-07 17:33:11   Decrement step  In progress             
    - 2020-01-07 17:33:11   Decrement step  Success         
    - 2020-01-07 17:33:11   Calculate thread        Success 


---

**Examples:** 

config: 
    
    max_calculate_with_business_logic_thread: &calculate_with_business_logic_thread
      class: App\Thread\CalculationThread
      context: App\Context\CalculationContextComposite
      steps:
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *business_logic_thread


result:
    
     === Start Thread === 
    Result: 100
    History: 
    - 2020-01-07 19:42:55   Calculate thread        Created         
    - 2020-01-07 19:42:55   Calculate thread        In progress             
    - 2020-01-07 19:42:55   Increment step  Created         
    - 2020-01-07 19:42:55   Increment step  In progress             
    - 2020-01-07 19:42:55   Increment step  Success         
    - 2020-01-07 19:42:55   Increment step  Created         
    - 2020-01-07 19:42:55   Increment step  In progress             
    - 2020-01-07 19:42:55   Increment step  Success         
    - 2020-01-07 19:42:55   Increment step  Created         
    - 2020-01-07 19:42:55   Increment step  In progress             
    - 2020-01-07 19:42:55   Increment step  Success         
    - 2020-01-07 19:42:55   Increment step  Created         
    - 2020-01-07 19:42:55   Increment step  In progress             
    - 2020-01-07 19:42:55   Increment step  Success         
    - 2020-01-07 19:42:55   Increment step  Created         
    - 2020-01-07 19:42:55   Increment step  In progress             
    - 2020-01-07 19:42:55   Increment step  Success         
    - 2020-01-07 19:42:55   Increment step  Created         
    - 2020-01-07 19:42:55   Increment step  In progress             
    - 2020-01-07 19:42:55   Increment step  Success         
    - 2020-01-07 19:42:55   Increment step  Created         
    - 2020-01-07 19:42:55   Increment step  In progress             
    - 2020-01-07 19:42:55   Increment step  Success         
    - 2020-01-07 19:42:55   Increment step  Created         
    - 2020-01-07 19:42:55   Increment step  In progress             
    - 2020-01-07 19:42:55   Increment step  Success         
    - 2020-01-07 19:42:55   Increment step  Created         
    - 2020-01-07 19:42:55   Increment step  In progress             
    - 2020-01-07 19:42:55   Increment step  Success         
    - 2020-01-07 19:42:55   Increment step  Created         
    - 2020-01-07 19:42:55   Increment step  In progress             
    - 2020-01-07 19:42:55   Increment step  Success         
    - 2020-01-07 19:42:55   Increment step  Created         
    - 2020-01-07 19:42:55   Increment step  In progress             
    - 2020-01-07 19:42:55   Increment step  Success         
    - 2020-01-07 19:42:55   Business logic thread   Created         
    - 2020-01-07 19:42:55   Business logic thread   In progress             
    - 2020-01-07 19:42:55   Business logic (Max) step       Created         
    - 2020-01-07 19:42:55   Business logic (Max) step       In progress             
    - 2020-01-07 19:42:55   Business logic (Max) step       Success         
    - 2020-01-07 19:42:55   Business logic (Min) step       Created         
    - 2020-01-07 19:42:55   Business logic (Min) step       In progress             
    - 2020-01-07 19:42:55   Business logic (Min) step       Success         
    - 2020-01-07 19:42:55   Business logic thread   Success         
    - 2020-01-07 19:42:55   Calculate thread        Success  
--

config: 
    
    min_calculate_with_business_logic_thread: &calculate_with_business_logic_thread
      class: App\Thread\CalculationThread
      context: App\Context\CalculationContextComposite
      steps:
        - *DecrementStep
        - *DecrementStep
        - *DecrementStep
        - *business_logic_thread

result:

     === Start Thread === 
    Result: 0
    History: 
    - 2020-01-07 19:41:43   Calculate thread        Created         
    - 2020-01-07 19:41:43   Calculate thread        In progress             
    - 2020-01-07 19:41:43   Decrement step  Created         
    - 2020-01-07 19:41:43   Decrement step  In progress             
    - 2020-01-07 19:41:43   Decrement step  Success         
    - 2020-01-07 19:41:43   Decrement step  Created         
    - 2020-01-07 19:41:43   Decrement step  In progress             
    - 2020-01-07 19:41:43   Decrement step  Success         
    - 2020-01-07 19:41:43   Decrement step  Created         
    - 2020-01-07 19:41:43   Decrement step  In progress             
    - 2020-01-07 19:41:43   Decrement step  Success         
    - 2020-01-07 19:41:43   Business logic thread   Created         
    - 2020-01-07 19:41:43   Business logic thread   In progress             
    - 2020-01-07 19:41:43   Business logic (Max) step       Created         
    - 2020-01-07 19:41:43   Business logic (Max) step       In progress             
    - 2020-01-07 19:41:43   Business logic (Max) step       Success         
    - 2020-01-07 19:41:43   Business logic (Min) step       Created         
    - 2020-01-07 19:41:43   Business logic (Min) step       In progress             
    - 2020-01-07 19:41:43   Business logic (Min) step       Success         
    - 2020-01-07 19:41:43   Business logic thread   Success         
    - 2020-01-07 19:41:43   Calculate thread        Success  

--

config:

    calculate_with_business_logic_thread: &calculate_with_business_logic_thread
      class: App\Thread\CalculationThread
      context: App\Context\CalculationContextComposite
      steps:
        - *calculate_thread
        - *IncrementStep
        - *IncrementStep
        - *NotProcessedStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *IncrementStep
        - *DecrementStep
        - *business_logic_thread


result:

     === Start Thread === 
    Result: 100
    History: 
    - 2020-01-07 19:45:59   Calculate thread        Created         
    - 2020-01-07 19:45:59   Calculate thread        In progress             
    - 2020-01-07 19:45:59   Calculate thread        Created         
    - 2020-01-07 19:45:59   Calculate thread        In progress             
    - 2020-01-07 19:45:59   Increment step  Created         
    - 2020-01-07 19:45:59   Increment step  In progress             
    - 2020-01-07 19:45:59   Increment step  Success         
    - 2020-01-07 19:45:59   Decrement step  Created         
    - 2020-01-07 19:45:59   Decrement step  In progress             
    - 2020-01-07 19:45:59   Decrement step  Success         
    - 2020-01-07 19:45:59   Increment step  Created         
    - 2020-01-07 19:45:59   Increment step  In progress             
    - 2020-01-07 19:45:59   Increment step  Success         
    - 2020-01-07 19:45:59   Non-processable step    Created         
    - 2020-01-07 19:45:59   Non-processable step    Skipped         
    - 2020-01-07 19:45:59   Increment step  Created         
    - 2020-01-07 19:45:59   Increment step  In progress             
    - 2020-01-07 19:45:59   Increment step  Success         
    - 2020-01-07 19:45:59   Non-processable step    Created         
    - 2020-01-07 19:45:59   Non-processable step    Skipped         
    - 2020-01-07 19:45:59   Broken step     Created         
    - 2020-01-07 19:45:59   Broken step     In progress             
    - 2020-01-07 19:45:59   Broken step     Failure         Step should throw exception
    - 2020-01-07 19:45:59   Increment step  Created         
    - 2020-01-07 19:45:59   Increment step  In progress             
    - 2020-01-07 19:45:59   Increment step  Success         
    - 2020-01-07 19:45:59   Increment step  Created         
    - 2020-01-07 19:45:59   Increment step  In progress             
    - 2020-01-07 19:45:59   Increment step  Success         
    - 2020-01-07 19:45:59   Calculate thread        Success         
    - 2020-01-07 19:45:59   Increment step  Created         
    - 2020-01-07 19:45:59   Increment step  In progress             
    - 2020-01-07 19:45:59   Increment step  Success         
    - 2020-01-07 19:45:59   Increment step  Created         
    - 2020-01-07 19:45:59   Increment step  In progress             
    - 2020-01-07 19:45:59   Increment step  Success         
    - 2020-01-07 19:45:59   Non-processable step    Created         
    - 2020-01-07 19:45:59   Non-processable step    Skipped         
    - 2020-01-07 19:45:59   Increment step  Created         
    - 2020-01-07 19:45:59   Increment step  In progress             
    - 2020-01-07 19:45:59   Increment step  Success         
    - 2020-01-07 19:45:59   Increment step  Created         
    - 2020-01-07 19:45:59   Increment step  In progress             
    - 2020-01-07 19:45:59   Increment step  Success         
    - 2020-01-07 19:45:59   Increment step  Created         
    - 2020-01-07 19:45:59   Increment step  In progress             
    - 2020-01-07 19:45:59   Increment step  Success         
    - 2020-01-07 19:45:59   Increment step  Created         
    - 2020-01-07 19:45:59   Increment step  In progress             
    - 2020-01-07 19:45:59   Increment step  Success         
    - 2020-01-07 19:45:59   Increment step  Created         
    - 2020-01-07 19:45:59   Increment step  In progress             
    - 2020-01-07 19:45:59   Increment step  Success         
    - 2020-01-07 19:45:59   Increment step  Created         
    - 2020-01-07 19:45:59   Increment step  In progress             
    - 2020-01-07 19:45:59   Increment step  Success         
    - 2020-01-07 19:45:59   Increment step  Created         
    - 2020-01-07 19:45:59   Increment step  In progress             
    - 2020-01-07 19:45:59   Increment step  Success         
    - 2020-01-07 19:45:59   Decrement step  Created         
    - 2020-01-07 19:45:59   Decrement step  In progress             
    - 2020-01-07 19:45:59   Decrement step  Success         
    - 2020-01-07 19:45:59   Business logic thread   Created         
    - 2020-01-07 19:45:59   Business logic thread   In progress             
    - 2020-01-07 19:45:59   Business logic (Max) step       Created         
    - 2020-01-07 19:45:59   Business logic (Max) step       In progress             
    - 2020-01-07 19:45:59   Business logic (Max) step       Success         
    - 2020-01-07 19:45:59   Business logic (Min) step       Created         
    - 2020-01-07 19:45:59   Business logic (Min) step       In progress             
    - 2020-01-07 19:45:59   Business logic (Min) step       Success         
    - 2020-01-07 19:45:59   Business logic thread   Success         
    - 2020-01-07 19:45:59   Calculate thread        Success  




---
Functionality in progress:
- dependency injection
- rollback functionality
- error handling
- more examples of usage
