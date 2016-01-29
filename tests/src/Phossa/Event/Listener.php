<?php

namespace Phossa\Event;

use Phossa\Event\Interfaces\EventListenerInterface;

class Listener implements EventListenerInterface
{
    /*
     * able to execute all methods defined in getEventsListening()
     */
    public function __call($name, $arguments) {
        /* @var $evt EventInterface */
        $evt = $arguments[0];
        $evt->setProperty($name, $name);

        // stop event propagation after 'testY'
        if ($name == 'testY') $evt->stopPropagation();
    }

    public function getEventsListening()
    {
        return [
            'evtTest1' => 'testC',
            'evtTest2' => [ 'testD', 20 ],
            'evtTest3' => [
                [ 'testA', 70 ],
                [ 'testB', 50 ]
            ],
            'evtTest4' => [
                [ 'testX', 70 ],
                [ 'testY', 40 ],
                [ 'testZ', 30 ]
            ],
            'evt*' => 'bingo',
            'evtTest*' => 'bingo2',
            '*' => 'wow',
        ];
    }
}

