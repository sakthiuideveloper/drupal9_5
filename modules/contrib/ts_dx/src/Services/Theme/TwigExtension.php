<?php

namespace Drupal\ts_dx\Services\Theme;

use Drupal\Core\Theme\ThemeManagerInterface;
use Drupal\ts_dx\Services\Theme\Base\AbstractTwigExtension;

/**
 * Différentes outils twig.
 *
 * @package Drupal\ts_dx\Services\Theme
 */
class TwigExtension extends AbstractTwigExtension {

  /**
   * Nom du service.
   *
   * @const string
   */
  const SERVICE_NAME = 'ts_dx.twig_extension';

  /**
   * Retourne le singleton.
   *
   * @return static
   *   Le singleton.
   */
  public static function me() {
    return \Drupal::service(static::SERVICE_NAME);
  }

  /**
   * Prefix des extensions.
   *
   * @const string
   */
  const PREFIX = 'lyc_';

  /**
   * Theme Manager.
   *
   * @var \Drupal\Core\Theme\ThemeManagerInterface
   */
  protected $themeManager;

  /**
   * TwigExtension constructor.
   *
   * @param \Drupal\Core\Theme\ThemeManagerInterface $themeManager
   *   Le theme manager.
   */
  public function __construct(ThemeManagerInterface $themeManager) {
    $this->themeManager = $themeManager;
  }

  /**
   * {@inheritdoc}
   */
  public function getPrefix(): string {
    return static::PREFIX;
  }

  /**
   * {@inheritdoc}
   */
  protected function getAllFunctions(): array {
    return [
      new \Twig_SimpleFunction('module_path', [
        $this,
        'getCurrentModulePath',
      ]),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function getAllFilters(): array {
    return [];
  }

  /**
   * Retourne le chemin compléter à partir du chemin du theme courant ou passé.
   *
   * @param string $path
   *   Le chemin relatif.
   * @param string $theme
   *   Le theme. Si non précisé, on prend le theme courant.
   *
   * @return string
   *   Le chemin path dans le contexte du theme passé.
   */
  public function getCurrentModulePath($path, $theme = NULL) {
    if ($theme) {
      $asset_dir = base_path() . drupal_get_path('theme', $theme);
    }
    else {
      $asset_dir = $this->themeManager->getActiveTheme()->getPath();
    }

    return '/' . $asset_dir . '/' . $path;
  }

}
