<?php

namespace Drupal\env_libraries\Twig;

use Drupal\env_libraries\Services\LibraryManager;

/**
 * Twig extension.
 */
class EnvLibrariesTwigExtension extends \Twig_Extension
{

  /**
   * The env_libraries.library_manager service.
   *
   * @var LibraryManager
   */
  protected $libraryManager;

  /**
   * Constructs a new EnvLibrariesTwigExtension object.
   *
   * @param LibraryManager $libraryManager
   */
  public function __construct(LibraryManager $libraryManager)
  {
    $this->libraryManager = $libraryManager;
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions()
  {
    return [
      new \Twig_SimpleFunction('envPath', [$this, 'envPath']),
    ];
  }

  /**
   * @param $data
   */
  public function envPath($path)
  {
    return $this->libraryManager->getEnvPath($path);
  }

}
