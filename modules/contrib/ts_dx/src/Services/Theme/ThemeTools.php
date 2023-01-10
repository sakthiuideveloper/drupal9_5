<?php

namespace Drupal\ts_dx\Services\Theme;

use Drupal\Core\Routing\AdminContext;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Routing\RouteObjectInterface;

/**
 * Outils d'amélioration de la gestion des thèmes.
 *
 * @package Drupal\ts_dx\Services\Theme
 */
class ThemeTools {

  /**
   * Nom du service.
   *
   * @const string
   */
  const SERVICE_NAME = 'ts_dx.theme_tools';

  /**
   * Admin COntext.
   *
   * @var AdminContext
   */
  protected $adminContext;

  /**
   * Current route match.
   *
   * @var CurrentRouteMatch
   */
  protected $currentRouteMatch;

  /**
   * ThemeTools constructor.
   * @param AdminContext $adminContext
   */
  public function __construct(AdminContext $adminContext, CurrentRouteMatch $currentRouteMatch)
  {
    $this->adminContext = $adminContext;
    $this->currentRouteMatch = $currentRouteMatch;
  }


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
   * Trie les suggestions par view_mode.
   *
   * Par défaut, Drupal prend les suggestions dans cet ordre :
   * - node-type-view-mode
   * - node-type
   * - node-view-mode
   * - node.
   *
   * Ici on redéfinit dans cet ordre :
   * - node-type-view-mode
   * - node-view-mode
   * - node-type
   * - node.
   *
   * @param array $suggestions
   *   THe suggestinos.
   * @param array $variables
   *   Les variables.
   */
  public function sortSuggestions(array &$suggestions, array $variables) {
    if (isset($variables['elements']['#view_mode'])) {
      $viewMode = $variables['elements']['#view_mode'];

      /* Pour réordonner les suggestions en fonction du viewmode,
       * on est obligé de spliter la liste en deux index qu'on va reconcatène */
      $newSuggestions = [];
      foreach ($suggestions as $suggestion) {
        if (strpos($suggestion, $viewMode) !== FALSE) {
          $newSuggestions[0][] = $suggestion;
        }
        else {
          $newSuggestions[1][] = $suggestion;
        }
      }

      /* Si on a des suggestions avec view_mode et sans view mode, on concatène
       * les deux sous tableaux. */
      if (array_key_exists(0, $newSuggestions) && array_key_exists(1, $newSuggestions)) {
        $newSuggestions = array_merge($newSuggestions[1], $newSuggestions[0]);
      }
      // Si on n'a que des suggestions avec view_mode, on ne prend que celui-ci.
      elseif (array_key_exists(0, $newSuggestions)) {
        $newSuggestions = $newSuggestions[0];
      }
      // Si on n'a que des suggestions sans view_mode, on ne prend que celui-ci.
      elseif (array_key_exists(1, $newSuggestions)) {
        $newSuggestions = $newSuggestions[1];
      }

      // On réassocie des index indentique au paramètre en entrée.
      foreach ($newSuggestions as $key => $suggestion) {
        $suggestions[$key] = $suggestion;
      }
    }
  }

  /**
   * Check si la route passées ou le context courant est admin.
   *
   * @param RouteObjectInterface|null $route
   *   La route.
   *
   * @return bool
   *   Le contexte.
   */
  public function isAdminContext(RouteObjectInterface $route = null)
  {
    $route = $route ?: $this->currentRouteMatch->getRouteObject();
    return $this->adminContext->isAdminRoute($route);
  }

}
