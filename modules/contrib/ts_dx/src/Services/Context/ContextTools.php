<?php

namespace Drupal\ts_dx\Services\Context;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Routing\CurrentRouteMatch;

/**
 * Provides tools for context.
 *
 * @package Drupal\ts_dx\Services\Context
 */
class ContextTools {

  /**
   * Nom du service.
   *
   * @const string
   */
  const SERVICE_NAME = 'ts_dx.context_tools';

  /**
   * Current route match.
   *
   * @var CurrentRouteMatch
   */
  protected CurrentRouteMatch $currentRouteMatch;

  /**
   * Entity Repository.
   *
   * @var EntityRepositoryInterface
   */
  protected $entityRepository;

  /**
   * Current context entity.
   *
   * @var EntityInterface|bool|null
   */
  protected $currentContextEntity = FALSE;

  /**
   * Constructor.
   *
   * @param CurrentRouteMatch $currentRouteMatch
   */
  public function __construct(CurrentRouteMatch $currentRouteMatch, EntityRepositoryInterface $entityRepository) {
    $this->currentRouteMatch = $currentRouteMatch;
    $this->entityRepository = $entityRepository;
  }


  /**
   * Singleton quick access.
   *
   * @return static
   *   The singleton.
   */
  public static function me() {
    return \Drupal::service(static::SERVICE_NAME);
  }


  /**
   * Return the entity of the current page.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   *   The current context entity.
   */
  public function getCurrentContextEntity() {
    if ($this->currentContextEntity === FALSE) {
      $routeParameters = $this->currentRouteMatch->getParameters()->all();
      $this->currentContextEntity = $this->getMainEntityFromRouteParameters($routeParameters);
    }

    return $this->currentContextEntity;
  }

  /**
   * Return the main (first) entity from route parameters.
   *
   * @param array $route_parameters
   *
   * @return void
   */
  public function getMainEntityFromRouteParameters(array $route_parameters) {
    foreach ($route_parameters as $entity_type_id => $value) {
      if ($value instanceof EntityInterface) {
        return $value;
      }
      try{
        if($entity = $this->entityRepository->getActive($entity_type_id, $value)){
          return $entity;
        }
      }
      catch(\Exception $e){
        // Mute exception...
      }
    }
    return NULL;
  }

}
