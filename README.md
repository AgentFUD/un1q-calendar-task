# un1q-calendar-task

## Installation
1. You have to install docker and docker-compose
2. You have to install git
3. Clone the repository
4. run ```make up```
5. run ```make init```

If you open http://localhost:8000 you should see Laravel default welcome page.

## Running tests
1. Run installation steps
2. Run ```make test```

# Development

## Preparation Steps
* Dockerize a barebone laravel app  ```[DONE]```
* Create Makefile for easy use  ```[DONE]```
* Create migration for events  ```[DONE]```
* Create factory for events  ```[DONE]```

## First development steps
* Create Event model  ```[DONE]```
* Create routes for certain actions  ```[DONE]```
* Create EventController  ```[DONE]```
* Create CreateEventRequest, UpdateEventRequest  ```[DONE]```
* Create custom validation rule: event_overlap  ```[DONE]```
* Create custom observer for saving recurrent events  ```[DONE]```

## List of requirements
* Adding unit tests
* Adding/utilizing static analytics tools  ```[DONE]```
* Create an API endpoint to create an event  ```[DONE]```
* Create should support recurring events ```[DONE]```
* If the event somehow overlaps with another event, an error should be thrown ```[DONE]```
* New event parameters
  * title: required|string ```[DONE]```
  * description: optional  ```[DONE]```
  * start: required  ```[DONE]```
    * should follow ISO 8601 date format```[DONE]```
    * If any event overlaps with the start date it should throw a validation error```[DONE]```
  * end: required|date  ```[DONE]```
    * should follow ISO 8601 date format ```[DONE]```
    * If any event overlaps with the start date it should throw a validation error```[DONE]```
  * frequency: enum = daily, weekly, monthly and yearly are the possible values  ```[DONE]```
  * repeat_until: date ```[DONE]```
    * should follow ISO 8601 date format ```[DONE]```

* Updating an event
  * Only a single event can be updated ```[DONE]```
  * title can be updated ```[DONE]```
  * description can be updated ```[DONE]```
  * frequency can not be changed ```[DONE]```
  * For the sake of simplicity repeat_until can not be changed ```[DONE]```
  * start and end can be updated ```[DONE]```
  * If the event overlaps with another event, an error should be thrown ```[DONE]```


* List events
  * Create an API endpoint to list all the events with pagination * This endpoint should be able to filter the result within a specific time range

* Delete an event
  * Only single events we allow to delete ```[DONE]```
  * The first in the recurrence list we do not allow to delete ```[DONE]```

* General
  * Convert it to Domain Driven Design
  * Add phpunit tests ```[DONE]```
  * Use of code quality/static analysis tools ```[DONE]```


# My development approach
For every item in the List of requirements section I'll try to write a phpunit test. 

## Static analysis tools
### **Pint**
Run ```make pint```

Pint is fairly new as the official linting tool for Laravel. It can firstly be used in any PHP project despite being made for Laravel and is a little bit nicer than PHP CS Fixer which Pint uses under the hood. Linting is a common name for a static analysis tool which will format code and try to apply small changes to make code readable by a set of standards.

### **Larastan**
```Not implemented```

Larastan focuses on finding errors in your code. It catches whole classes of bugs even before you write tests for the code.

### **PHPInsights**
```Not implemented```

Insights will provide you with 4 groups of scores, code, complexity, architecture, and style.

I’ll start with the style insights first, this is essentially going to be exactly the same kind of processing as Pint, but it’s nice to at least have a scoring. It will also pick up a few other things that Pint won’t such as line counts.

Complexity is purely cyclomatic complexity, which is a very simple way of saying how many branches occur within code. You might want to brush up on the concept. Personally, I find the default for this a bit low so I’ll typically pump it up. That said it’s a good metric for when classes become unwieldy.

That goes onto the next category architecture. This covers a few things but is often just simple naming conventions or the length of functions or classes. Again I would normally bump up the length of classes allowed as I don’t like to be held to such a high standard I end up creating lots of classes just to encapsulate simple functionality right away.

The final stat is code and really just follows a lot of the similar things that you might find with PHPStan or Pint. These might range from useless variables to avoiding using some functions due to the way they can be unpredictable. It can be a little opinionated at times so do remove some rules if they’re just generating more noise for you.

## Possible improvements
* Introducing .env.testing to set up a separate database for running phpunit tests
* Doing stress testing on the app to see which operations are expensive and try to optimize code/database.
* Letting a QA to check the code and find more edge cases
* Improving tests for example checking time boundaries, overlaps, data structures returned, presence of data in the database.
* Checking delete/update edgecases, ie. if an event has recurrence, the first event can not be deleted.
* If we allow any event to be deleted, then what happens if someone wants to delete the first event in a recurrence series?
* Writing unit tests for the code.
* Adding XDebug for code coverage reports