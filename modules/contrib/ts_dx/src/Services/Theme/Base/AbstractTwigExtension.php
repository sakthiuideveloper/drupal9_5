<?php

namespace Drupal\ts_dx\Services\Theme\Base;

/**
 * Class AbstractTwigExtension.
 *
 * Permet de préfixer automatiquement les twig_extensions.
 * De cette manière elle ne seront pas ré-écraser par d'autres
 * modules tiers.
 *
 * @package Drupal\ts_dx\Services\Theme\Base
 */
abstract class AbstractTwigExtension extends \Twig_Extension {

  /**
   * Retourne le prefix des commandes.
   *
   * Les extension twigs ne sont pas namespacé. L'idée du prefix permet
   * de gérer les conflits entre différentes fonctions twig du même nom.
   * Une fonction déclarée ici est accessible via {son nom} ou {son
   * prefix}.{son nom} Ex: assets() <=> lyc_assets().
   *
   * @return string
   *   Le préfixe.
   */
  abstract public function getPrefix(): string;

  /**
   * Retourne la liste des fonctions nons préfixées.
   *
   * @return \Twig\TwigFunction[]
   *   La liste de fonctions.
   */
  abstract protected function getAllFunctions(): array;

  /**
   * REtourne la liste dse filtres nons préfixés.
   *
   * @return \Twig\TwigFilter[]
   *   La lsite des filtres.
   */
  abstract protected function getAllFilters(): array;

  /**
   * {@inheritdoc}
   */
  final public function getFunctions(): array {
    return $this->getPrefixedItems($this->getAllFunctions());
  }

  /**
   * {@inheritdoc}
   */
  public function getFilters(): array {
    return $this->getPrefixedItems($this->getAllFilters());
  }

  /**
   * Retourne un tableau d'élément avec les préfix de noms.
   *
   * @param array $extensions
   *   La liste des extensions.
   *
   * @return array
   *   Les extensions dupliquées.
   */
  protected function getPrefixedItems(array $extensions): array {
    $results = [];
    $prefix = $this->getPrefix();

    // Ajout des fonction sans préfix, et avec préfix.
    foreach ($extensions as $extension) {
      $results[] = $extension;
      $class = get_class($extension);
      $results[] = new $class($prefix . $extension->getName(), $extension->getCallable());
    }

    return $results;
  }

}
