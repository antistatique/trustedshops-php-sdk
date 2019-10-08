<?php

namespace Antistatique\TrustedShops\Tests\Traits;

trait TestPrivateTrait
{
  /**
   * Modify a method accessibility to make it testable.
   *
   * @param string|Object $obj
   *    Object.
   * @param string $name
   *    Method Name.
   * @param array $args
   *   Optional arguments.
   *
   * @return mixed
   *   the method result.
   *
   * @throws \ReflectionException
   */
  public function callPrivateMethod($obj, $name, array $args = array())
  {
    $class = new \ReflectionClass($obj);
    $method = $class->getMethod($name);
    $method->setAccessible(true);
    return $method->invokeArgs($obj, $args);
  }
}
