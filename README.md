# phossa-event
[![Build Status](https://travis-ci.org/phossa/phossa-event.svg?branch=master)](https://travis-ci.org/phossa/phossa-event.svg?branch=master)
[![Latest Stable Version](https://poser.pugx.org/phossa/phossa-event/v/stable)](https://packagist.org/packages/phossa/phossa-event)
[![License](https://poser.pugx.org/phossa/phossa-event/license)](https://packagist.org/packages/phossa/phossa-event)

Introduction
---

Phossa-event is an event management package for PHP. It decoupled from any
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
       "phossa/phossa-event": "^1.0.2"
    }
}
```

Features
---

- Simple event dispatching using `EventDispatcher`

  ```php
      $dispatcher = new EventDispatcher();

      // bind event 'user.login' to a callable
      $dispatcher->on('user.login', function(Event $evt) {
          ...
      });

      // trigger the 'user.login' event
      $dispatcher->trigger('user.login', [ 'user' => $user ]);
  ```

   or use statically (global dispatching)

  ```php
      // bind event with priority 60 (0 - 100, high # higher priority)
      EventDispatcher::on('user.login', function(Event $evt) {
          ...
      }, 60);

      // trigger the 'user.login' event
      EventDispatcher::trigger('user.login', [ 'user' => $user ]);
  ```

- Complex event management use `EventManager` with full fledge support for
  event listener, event subject, local event manager and global event manager
  etc.

- Event globbing. Able to listen to all events by using '*' or 'event*'.

  ```php
      // bind event 'user.login' to a callable
      $dispatcher->on('user.login', function(Event $evt) {
          ...
          return false; // stop event propagation
      });

      // globbing
      $dispatcher->on('user.*', function(Event $evt) {
          ...
      });
  ```

  or using event listener

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

- Support complext event management for static classes.

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
  // get global copy by using static method `getShareable()`
  $globalEventManager = ShareableEventManager::getShareable();

  // normal event managers
  $localEventManager  = new ShareableEventManager();

  // is this the global copy?
  if ($evtManager->isShareable()) {
      ...
  } else {
      ...
  }
  ```

- Priority in event handling queue. Higher number means higher priority.

- Support PHP 5.4.

- PHP7 ready for return type declarations and argument type declarations.

- PSR-1, PSR-2, PSR-4 compliant.

- Decoupled packages can be used seperately without the framework.

Usage
---

- The simple event dispatcher

- The listener

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

  or static listener class.(see examples in **Features**)

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

  or static class using events.(see examples in **Features**)

- The event manager/dispatcher

  ```php
  // create an event manager/dispatcher
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

- The last piece

  ```php
  // set manager/dispatcher to event-aware subject
  $subject->setEventManager($evtManager);

  // trigger event
  $subject->triggerEvent('eventName2');
  ```

  or static version.(see examples in **Features**)

Version
---

1.0.2

Dependencies
---

- PHP >= 5.4.0

- phossa/phossa-shared >= 1.0.2

License
---

[MIT License](http://spdx.org/licenses/MIT)
