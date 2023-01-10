<?php

namespace Drupal\ts_dx\Services\Tree;

use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;

/**
 * Outils de gestion de menu.
 */
class MenuTools {

  /**
   * Nom du service.
   *
   * @const string
   */
  const SERVICE_NAME = 'ts_dx.menu_tools';

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
   * The menu link tree service.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  protected $menuLinkTree;

  /**
   * Constructs a MenuTools object.
   *
   * @param \Drupal\Core\Menu\MenuLinkTreeInterface $menu_link_tree
   *   The menu link tree service.
   */
  public function __construct(MenuLinkTreeInterface $menu_link_tree) {
    $this->menuLinkTree = $menu_link_tree;
  }

  /**
   * Retourne le markup d'un menu.
   *
   * @param string $menuId
   *   L'id du menu.
   * @param array|\string[][] $manipulators
   *   Les manipulators.
   *
   * @return array
   *   Le markup.
   */
  public function getMenuBuildArray(string $menuId, array $manipulators): array {
    $tree = $this->getMenuLinkTree($menuId, $manipulators);

    return $this->menuLinkTree->build($tree);
  }

  /**
   * Retourne le menu tree.
   *
   * @param string $menuId
   *   L'id du menu.
   * @param array|\string[][] $manipulators
   *   Les manipulators.
   * @param null|string $rootId
   *   Plugin id de l'élément duquel effectuer le parcours.
   * @param null $maxDepth
   *   Max depth.
   *
   * @return array
   *   Le menu tree.
   */
  public function getMenuLinkTree(string $menuId='main', array $manipulators = NULL, $rootId = NULL, $maxDepth = NULL): array {

    // Default manipulators.
    $manipulators = $manipulators ?: [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];

    $parameters = new MenuTreeParameters();
    $parameters->excludeRoot()->onlyEnabledLinks();
    $parameters->setRoot($rootId);
    $parameters->setMaxDepth($maxDepth);
    $tree = $this->menuLinkTree->load($menuId, $parameters);

    if (empty($tree)) {
      return [];
    }

    return $this->menuLinkTree->transform($tree, $manipulators);
  }
}
