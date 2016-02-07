<?php
/**
 * Phossa Project
 *
 * PHP version 5.4
 *
 * @category  Package
 * @package   Phossa\Event
 * @author    Hong Zhang <phossa@126.com>
 * @copyright 2015 phossa.com
 * @license   http://mit-license.org/ MIT License
 * @link      http://www.phossa.com/
 */

/**
 * Chinese translation for Message.php
 *
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Message\Message
 * @version 1.0.4
 * @since   1.0.1 added
 */
return [
    Message::PROPERTY_NOT_FOUND         => '"%s" 没有此属性 "%s"',
    Message::MANAGER_NOT_FOUND          => '类库 "%s" 中还未设置事件管理员',
    Message::INVALID_EVENT_CONTEXT      => '事件 "%s" 中触发者出错',
    Message::INVALID_EVENT_LISTENER     => '"%s" 不是事件聆听者',
    Message::INVALID_EVENT_CALLABLE     => '事件 "%s" 中发现不可执行函数',
    Message::CALLABLE_NOT_FOUND         => '事件 "%s" 无可执行函数',
    Message::CALLABLE_RUNTIME           => '事件执行错误: %s',
    Message::INVALID_EVENT_NAME         => '事件名称 "%s" 错误',
    Message::INVALID_EVENT_PROPERTY     => '无效的事件属性 "%s"',
    Message::INVALID_EVENT_MANAGER      => '无效的事件管理员类型 "%s"',
    Message::IMMUTABLE_EVENT_METHOD     => '非法执行了固化方法 "%s"',
    Message::METHOD_NOT_FOUND           => '"%s" 没有方法 "%s"',
    Message::INVALID_EVENT_PRIORITY     => '事件优先级错误 "%s"',
];
