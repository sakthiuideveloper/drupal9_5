<?php

namespace Drupal\ts_dx\Commands;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 *
 * See these files for an example of injecting Drupal services:
 *   - http://cgit.drupalcode.org/devel/tree/src/Commands/DevelCommands.php
 *   - http://cgit.drupalcode.org/devel/tree/drush.services.yml
 */
class DxCommands extends DrushCommands {

  use StringTranslationTrait;

  /**
   * Separator.
   *
   * @const string
   */
  const SEPARATOR = ',';

  /**
   * NB_ITEMS.
   *
   * @const int
   */
  const NB_ITEMS = 50;

  /**
   * EntityType Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Cache entity Types manager.
   *
   * @var array
   */
  protected $storages = [];

  /**
   * Cache definitions.
   *
   * @var array
   */
  protected $definitions = [];

  /**
   * DxCommands constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity Type Manager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * CDelete entities by id.
   *
   * @param string $entityTypeId
   *   Entity Type id.
   * @param string $id
   *   Ids to delete (ex: 1,12,1000).
   *
   * @option option-name
   *   Description.
   * @usage x:delete-entity-by-id node 1,2,34
   *   Usage description.
   *
   * @command dx:delete-entity-by-id
   * @aliases dx:dei
   */
  public function deleteEntitiesByIds($entityTypeId = NULL, $id = NULL): void {
    $entityTypeId = $entityTypeId ?: $this->io()->ask(
      'The entity type id ? ',
      'node',
      function ($value) {
        $this->getStorage($value);

        return $value;
      }
    );

    $id = $id ?: $this->io()->ask(
      'The entity id ? ',
      '',
      function ($value) use ($entityTypeId) {
        if (strpos(static::SEPARATOR, $value) === FALSE) {
          if (is_null($this->getStorage($entityTypeId)->load($value))) {
            throw new \Exception('id does not exists');
          }
        }

        return $value;
      }
    );
    $this->deleteAllEntities($entityTypeId, explode(static::SEPARATOR, $id));
  }

  /**
   * CDelete entities by id.
   *
   * @param string $entityTypeId
   *   Entity Type id.
   * @param string $bundles
   *   Bundles to delete.
   *
   * @usage dx:delete-entity-by-bundle node article,posts
   *   Usage description
   *
   * @command dx:delete-entity-by-bundles
   * @aliases dx:deb
   */
  public function deleteEntitiesByBundle($entityTypeId, $bundles): void {
    // Get Ids :
    if ($storage = $this->getStorage($entityTypeId)) {
      $type = $this->getDefinition($entityTypeId);

      $entityQuery = $storage->getQuery()
        ->condition($type->getKey('bundle'), explode(static::SEPARATOR, $bundles), 'IN');
      $entityIds = $entityQuery->execute();

      $this->deleteAllEntities($entityTypeId, $entityIds);
    }
    else {
      $this->noStorage($entityTypeId);
    }
  }

  /**
   * Delete all entities by type.
   *
   * @param string $entityTypeId
   *   Entity Type id.
   *
   * @usage dx:delete-entity-by-type node
   *   Usage description
   *
   * @command dx:delete-entity-by-type
   * @aliases dx:det
   */
  public function deleteEntitiesByType($entityTypeId): void {
    // Get Ids :
    if ($storage = $this->getStorage($entityTypeId)) {
      $entityIds = $storage->getQuery()->execute();

      $this->deleteAllEntities($entityTypeId, $entityIds);
    }
    else {
      $this->noStorage($entityTypeId);
    }
  }

  /**
   * Delete entities by packages.
   *
   * @param string $entityTypeId
   *   The entity type id.
   * @param array $entitiesIds
   *   THe entity ids.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function deleteAllEntities(string $entityTypeId, array $entitiesIds): void {
    $entityType = $this->getStorage($entityTypeId);
    $total = count($entitiesIds);
    if ($entityType) {
      // Loop by packages.
      $count = 0;
      $errors = 0;
      for ($i = 0; $i < $total; $i += static::NB_ITEMS) {
        $chunk = array_slice($entitiesIds, $i, $i + static::NB_ITEMS);
        $entities = $entityType->loadMultiple($chunk);
        // Add entities not found.
        $errors += count($chunk) - count($entities);

        foreach ($entities as $entity) {
          try {
            $entity->delete();
            $count++;
          }
          catch (\Exception $e) {
            $errors++;
          }
        }

        // Clean memory.
        $this->logger->success($this->t('@count/@total (@errors errors) deleted', [
          '@count'  => $count,
          '@total'  => $total,
          '@errors' => $errors,
        ]));
        $this->cleanMemory();
      }
    }
    else {
      $this->noStorage($entityTypeId);
    }
  }

  /**
   * Clean memory.
   */
  protected function cleanMemory() {
  }

  /**
   * Return the storage.
   *
   * @param string $entityTypeId
   *   The entity type id.
   *
   * @return \Drupal\Core\Entity\EntityStorageInterface
   *   The entity storage.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function getStorage($entityTypeId) {
    if (!array_key_exists($entityTypeId, $this->storages)) {
      $this->storages[$entityTypeId] = $this->entityTypeManager->getStorage($entityTypeId);
    }

    return $this->storages[$entityTypeId];
  }

  /**
   * Return the storage.
   *
   * @param string $entityTypeId
   *   The entity type id.
   *
   * @return \Drupal\Core\Entity\EntityTypeInterface
   *   The entity type.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function getDefinition($entityTypeId) {
    if (!array_key_exists($entityTypeId, $this->definitions)) {
      $this->definitions[$entityTypeId] = $this->entityTypeManager->getDefinition($entityTypeId);
    }

    return $this->definitions[$entityTypeId];
  }

  /**
   * Log no storage.
   *
   * @param string $entityTypeId
   *   The entity type id.
   */
  protected function noStorage($entityTypeId) {
    $this->logger->error($this->t('No entity type @type', [
      '@type' => $entityTypeId,
    ]));
  }

}
