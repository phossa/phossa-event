<?php

use Phossa\Event\Message\Message;

return [
    Message::PROPERTY_NOT_FOUND     => '事件 "%s" 没有此属性 "%s"',
    Message::MANAGER_NOT_FOUND      => '类库 "%s" 中还未设置事件管理员',
    Message::WRONG_EVENT_TARGET     => '事件 "%s" 中触发者出错',
    Message::WRONG_EVENT_LISTENER   => '"%s" 不是事件聆听者',
    Message::WRONG_EVENT_CALLABLE   => '事件 "%s" 中发现不可执行函数',
    Message::CALLABLE_NOT_FOUND     => '事件 "%s" 无可执行函数',
    Message::CALLABLE_RUNTIME       => '事件执行错误: %s',
];