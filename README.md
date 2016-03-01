# phossa-event
[![Build Status](https://travis-ci.org/phossa/phossa-event.svg?branch=master)](https://travis-ci.org/phossa/phossa-event.svg?branch=master)
[![HHVM Status](http://hhvm.h4cc.de/badge/phossa/phossa-event.svg)](http://hhvm.h4cc.de/package/phossa/phossa-event)
[![Latest Stable Version](https://poser.pugx.org/phossa/phossa-event/v/stable)](https://packagist.org/packages/phossa/phossa-event)
[![License](https://poser.pugx.org/phossa/phossa-event/license)](https://packagist.org/packages/phossa/phossa-event)

Introduction
---

Phossa-event is an event management library for PHP. It decoupled from any
packages other than the `phossa/phossa-shared`. It requires PHP5.4 only.

Installation
---

Install via the `composer` utility.

```
composer require "phossa/phossa-event=1.*"
```

or add the following lines to your `composer.json`

```json
{
    "require": {
       "phossa/phossa-event": "^1.0.4"
    }
}
```

Simple `EventDispatcher`
---

Simple event dispatching using `Phossa\Event\EventDispatcher`

- Simple usage

  ```php
  $dispatcher = new EventDispatcher();

  // bind event 'user.login' to a callable
  $dispatcher->on('user.login', function(Event $evt) {
      // ...
  });

  // trigger the 'user.login' event, passing data
  $dispatcher->trigger('user.login', [ 'user' => $user ]);
  ```

   or use statically (global dispatching)

  ```php
  // bind event with priority 60 (0 - 100, high # higher priority)
  EventDispatcher::on('user.login', function(Event $evt) {
      // ...
  }, 60);

  // trigger the 'user.login' event
  EventDispatcher::trigger('user.login', [ 'user' => $user ]);
  ```

- Trigger for limited times

  ```php
  // bind event for once
  $dispatcher->one('user.login', function(Event $evt) {
      // ...
  });

  // allow 3 times
  $dispatcher->many('user.tag', 3, function(Event $evt) {
      // ...
  });
  ```

- Event globbing

  ```php
  // globbing
  $dispatcher->on('user.*', function(Event $evt) {
      //...
  });
  ```

- Detach events

  ```php
  // detach
  $dispatcher->off('user.*');
  ```

- Monitoring PHP errors

  Execute a callable when PHP error happens.

  ```php
  // callable returns bool
  $dispatcher->error(function($errno, $errstr, $errfile, $errline) {
      // ...
      return true;
  });
  ```

- Execute a callable when script finishes

  ```php
  // run this after script ends
  $dispatcher->ready(function() {
      // ...
  });
  ```

Full-fledged `EventManager`
---

Complex event management using `Phossa\Event\EventManager` with full fledge
support for event listener, event subject, local event manager and global
event manageretc.

- Event listener

  ```php
  use Phossa\Event\Interfaces;

  class MyListener implements Interfaces\EventListenerInteface
  {
      /*
       * Get events and callables MyListener listens to
       */
      public function getEventsListening()
      {
          return array(
              'eventName1' => 'method1', // method1 of $this
              'eventName2' => array('method2', 20), // priority 20
              'eventName3' => array( // multiple callables
                  [ 'method3', 70 ],
                  [ 'method4', 50 ]
              )
          );
      }
  }

  $listener = new MyListener();
  ```

- Event manager

  ```php
  // create an event manager
  $evtManager = new Event\EventManager();

  // attach a listener object
  $evtManager->attachListener($listener);

  // attach a callable directly to 'oneSpecialEvent'
  $callable = function(Event\Event $evt) {
      ...
  };
  $evtManager->attachListener($callable, 'oneSpecialEvent');

  // detach a callable
  $evtManager->detachListener($callable);
  ```

- The event-aware subject

  ```php
  use Phossa\Event\Interfaces;

  class MyEventAware implements Interfaces\EventAwareInterface
  {
      // add setEventManager() and triggerEvent()
      use Interfaces\EventAwareTrait;

      ...
  }

  $subject = new MyEventAware();
  ```

- Combine together

  ```php
  // set manager to event-aware subject
  $subject->setEventManager($evtManager);

  // trigger event
  $subject->triggerEvent('eventName2');
  ```

- Event globbing

  Able to listen to all events by using '*' or 'event*'.

  ```php
  use Phossa\Event\Interfaces\EventListenerInterface;

  class Listener implements EventListenerInterface
  {
      public function getEventsListening()
      {
          return [
              'evtTest1' => 'testC',
              'evtTest2' => [ 'testD', 20 ],
              'evtTest3' => [
                  [ 'testA', 70 ],
                  [ 'testB', 50 ]
              ],
              // globbing
              'evt*' => 'bingo',
              'evtTest*' => 'bingo2',
              '*' => 'wow',
          ];
      }
  }
  ```

- Event management for static classes.

  The static listener class,

  ```php
  use Phossa\Event\Interfaces;

  class StaticListener implements Interfaces\EventListenerStaticInteface
  {
      /*
       * Get events and callables StaticListener listens to
       */
      public static function getEventsListening()
      {
          return array(
              'eventName1' => 'method1', // method1 of $this
              'eventName2' => array('method2', 20), // priority 20
              'eventName3' => array( // multiple callables
                  [ 'method3', 70 ],
                  [ 'method4', 50 ]
              )
          );
      }
  }
  ```

  The static subject class to use events,

  ```php
  use Phossa\Event\Interfaces;

  class StaticEventAware implements Interfaces\EventAwareStaticInterface
  {
      // add setEventManager() and triggerEvent()
      use Interfaces\EventAwareStaticTrait;

      ...
  }
  ```

  The static subject class trigger events as follows,

  ```php
  // create an event manager/dispatcher
  $evtManager = new Event\EventManager();

  // attach a static listener class
  $evtManager->attachListener(StaticListener::CLASS);

  // set manager/dispatcher to event-aware static subject class
  StaticEventAware::setEventManager($evtManager);

  // trigger event by the static subject class
  StaticEventAware::triggerEvent('eventName2');
  ```

- Composite event manager

  Able to use composite event manager as follows,

  ```php
  use Phossa\Event;

  // global event manager
  $global_manager = new Event\EventManager();
  $global_manager->attachListener($some_global_event_listener);

  // local event manager
  $local_manager  = new Event\Variation\EventManagerComposite();
  $local_manager->attachListener($local_listener);

  // allow local event manager dispatch events to global event manager
  $local_manager->setOtherManager('global', $global_manager);

  // the event aware subject
  $subject = new MyEventAware();

  // set event manager
  $subject->setEventManager($local_manager);

  // fire up an event, will look into event handling queue from both
  // $local_manager and $global_manager
  $subject->triggerEvent('some_event');
  ```

- Immutable event manager

  ```php
  use Phossa\Event;

  // low-level manager to hide
  $_evtManager = new EventManager();
  $_evtManager->attachListener(...);
  ...

  // expose an immutable event managet to user
  $evtManager = new Variation\ImmutableEventManager($_evtManager);

  // cause an exception
  $evtManager->detachListener( ... );
  ```

- Shareable event manager,  a single copy of global manager and lots of
  local managers.

  ```php
  // get global copy by using static method `getInstance()`
  $globalEventManager = ShareableEventManager::getInstance();

  // normal event managers
  $localEventManager  = new ShareableEventManager();

  // is this the global copy?
  if ($evtManager->isShareable()) {
      ...
  } else {
      ...
  }
  ```

Features
---

- Supports PHP 5.4+, PHP 7.0+, HHVM.

- PHP7 ready for return type declarations and argument type declarations.

- PSR-1, PSR-2, PSR-4 compliant.

- Decoupled packages can be used seperately without the framework.

Dependencies
---

- PHP >= 5.4.0

- phossa/phossa-shared >= 1.0.8

License
---

[MIT License](http://spdx.org/licenses/MIT)
