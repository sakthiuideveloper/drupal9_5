<?php

namespace Drupal\Tests\env_libraries\Unit;

use Drupal\env_libraries\Services\LibraryManager;
use Drupal\Tests\UnitTestCase;

/**
 * Test description.
 *
 * @group env_libraries
 */
class DestinationTest extends UnitTestCase {

  /**
   * List of test cases.
   *
   * @var array
   *   The test cases.
   */
  protected $destinations = [
    // Explicit part.
    '__ENV_LIB=dev|dist__libraries/assets/__ENV_LIB=dev|dist__/js/main.js'  => [
      'devlibraries/assets/dev/js/main.js',
      'distlibraries/assets/dist/js/main.js',
    ],
    '__ENV_LIB=dev|dist__/libraries/assets/__ENV_LIB=dev|dist__/js/main.js' => [
      'dev/libraries/assets/dev/js/main.js',
      'dist/libraries/assets/dist/js/main.js',
    ],
    'libraries/assets/js/__ENV_LIB=main|main.min__.js'                      => [
      'libraries/assets/js/main.js',
      'libraries/assets/js/main.min.js',
    ],
    'libraries/assets/js/__ENV_LIB=dev/main|dist/main.min__.js'             => [
      'libraries/assets/js/dev/main.js',
      'libraries/assets/js/dist/main.min.js',
    ],
    'libraries/assets/js/__ENV_LIB=main__|main.min__.js'                    => [
      'libraries/assets/js/main|main.min__.js',
      'libraries/assets/js/|main.min__.js',
    ],
    'libraries/assets/js/__ENV_LIB=main|main.min__.other__.js'              => [
      'libraries/assets/js/main.other__.js',
      'libraries/assets/js/main.min.other__.js',
    ],

    // Implicit part.
    '__ENV_LIB=dev__libraries/assets/__ENV_LIB=dev__/js/main.js'            => [
      'devlibraries/assets/dev/js/main.js',
      'libraries/assets//js/main.js',
    ],
    '__ENV_LIB=dev__/libraries/assets/__ENV_LIB=dev__/js/main.js'           => [
      'dev/libraries/assets/dev/js/main.js',
      '/libraries/assets//js/main.js',
    ],
    'libraries/assets/js/__ENV_LIB=main__.js'                               => [
      'libraries/assets/js/main.js',
      'libraries/assets/js/.js',
    ],
    'libraries/assets/js/__ENV_LIB=dev/main/main.min__.js'                  => [
      'libraries/assets/js/dev/main/main.min.js',
      'libraries/assets/js/.js',
    ],
    'libraries/assets/js/__ENV_LIB=main__.other__.js'                       => [
      'libraries/assets/js/main.other__.js',
      'libraries/assets/js/.other__.js',
    ],

  ];

  /**
   * The env service.
   *
   * @var \Drupal\env_libraries\Services\LibraryManager
   */
  protected $envLibManager;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->envLibManager = new LibraryManager();
  }

  /**
   * Tests dev environment.
   */
  public function testExplicitDev() {
    $this->startTest(TRUE);
  }

  /**
   * Test prod environment.
   */
  public function testExplicitProd() {
    $this->startTest(FALSE);
  }

  /**
   * Play tests.
   *
   * @param bool $dev
   *   Dev state.
   */
  protected function startTest($dev) {
    $this->envLibManager->setDevEnvironment($dev);
    $valueIndex = $dev ? 0 : 1;
    foreach ($this->destinations as $path => $value) {
      $this->assertEquals(
        '[' . $path . '] ' . $value[$valueIndex],
        '[' . $path . '] ' . $this->envLibManager->getEnvPath($path));
    }
  }

}
