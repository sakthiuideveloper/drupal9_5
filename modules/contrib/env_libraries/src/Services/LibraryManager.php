<?php

namespace Drupal\env_libraries\Services;

use Drupal\Core\Site\Settings;

/**
 * Class LibraryManager.
 *
 * @package Drupal\gulp_theme\Services
 */
class LibraryManager {

  /**
   * Env variable name.
   *
   * @const string
   */
  const ENV_LIB = 'ENV_LIB';

  /**
   * Replacement identifier.
   *
   * @const string
   */
  const TOKEN = '__ENV_LIB=';

  /**
   * Destination separator.
   *
   * @const string
   */
  const SEPARATOR = '|';

  /**
   * Token end.
   *
   * @const string
   */
  const END = '__';

  /**
   * Setting Prod.
   *
   * @const string
   */
  const SETTING_PROD = 'prod';

  /**
   * Setting Dev.
   *
   * @const string
   */
  const SETTING_DEV = 'dev';

  /**
   * Service name.
   *
   * @const string
   */
  const SERVICE_NAME = 'env_libraries.library_manager';

  /**
   * Destination index.
   *
   * @var int
   */
  protected $destinationIndex;

  /**
   * LibraryManager constructor.
   */
  public function __construct() {
    // Init the destination index according to env var.
    $this->setDevEnvironment($this->isDevelopment());
  }

  /**
   * Update the index.
   *
   * @param bool $isDev
   *   The dev state.
   */
  public function setDevEnvironment($isDev = FALSE) {
    $this->destinationIndex = $isDev ? 0 : 1;
  }

  /**
   * Singleton.
   *
   * @return static
   *   Le singleton.
   */
  public static function me() {
    return \Drupal::service(static::SERVICE_NAME);
  }

  /**
   * Initialize libraries.
   *
   * @param array $libraries
   *   Libraries.
   */
  public function initLibraries(array &$libraries) {
    $serialize = \json_encode($libraries);
    if (strpos($serialize, static::TOKEN)) {
      $this->updateLibrariesPath($libraries);
    }
  }

  /**
   * Initialize libraries paths.
   *
   * @param mixed $array
   *   Les données de libraries.
   */
  public function updateLibrariesPath(&$array) {
    if (is_array($array)) {
      foreach ($array as $key => &$value) {
        if (strpos($key, static::TOKEN) !== FALSE) {
          $newKey = $this->getEnvPath($key);
          $array[$newKey] = $array[$key];
          unset($array[$key]);
        }
        if (is_string($value) && strpos($value, static::TOKEN) !== FALSE) {
          $value = $this->getEnvPath($value);
        }
        if (is_array($value)) {
          $this->updateLibrariesPath($value);
        }
      }
    }
  }

  /**
   * Return true if ENV_LIB is 'dev'.
   *
   * @return bool
   *   The dev state.
   */
  public function isDevelopment(): bool {
    $settings = Settings::get(static::ENV_LIB) ?: getenv(static::ENV_LIB);
    return $settings === static::SETTING_DEV;
  }

  /**
   * Return the current path to use.
   *
   * @param string $path
   *   The library defined path.
   * @param int $destination_index
   *   Force the env index 0:dev, 1:prod.
   *
   * @return string
   *   The real path.
   */
  public function getEnvPath($path, $destination_index = NULL): string {
    $destination_index = $destination_index ?? $this->destinationIndex;

    // Ex: foo/__ENV_LIB=dest|div__/js/main.js.
    // pathData = [foo, dest|div__/js/main.js].
    $pathData = explode(static::TOKEN, $path);

    $destination = [];

    foreach ($pathData as $pathDatum) {
      $replaceData = explode(static::END, $pathDatum);
      if (count($replaceData) > 1) {
        // Get the good part according to env.
        $envData = explode(static::SEPARATOR, $replaceData[0]);
        $destination[] = isset($envData[$destination_index]) ? $envData[$destination_index] : '';
        $destination[] = implode(static::END, array_slice($replaceData, 1));
      }
      else {
        $destination[] = $pathDatum;
      }
    }

    $file_destination = implode($destination);
    // If no file exists in dev, then fallbakc to prod.
    if (!file_exists($file_destination) && $destination_index === 0) {
      $file_destination = $this->getEnvPath($path, $destination_index + 1);
    }

    return $file_destination;
  }

}
