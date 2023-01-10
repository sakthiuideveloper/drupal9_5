<?php

namespace Drupal\ts_dx\Services;

use Drupal\Core\Field\EntityReferenceFieldItemListInterface;

/**
 * Misc quick access methods.
 */
class MiscTools
{

  /**
   * Service ID.
   *
   * @const string
   */
  const SERVICE_ID = 'ts_dx.misc_tools';

  /**
   * Singleton quick access.
   *
   * @return static
   *   The singleton.
   */
  public static function me()
  {
    return \Drupal::service(static::SERVICE_ID);
  }

  /**
   * Return the list of attributes of entity reference item list.
   *
   * @param \Drupal\Core\Field\EntityReferenceFieldItemList $field
   *   The field.
   * @param string $attribute
   *   The attributes.
   *
   * @return array
   *   The list of ids.
   */
  public function getAttributesFromReferenceFieldList(EntityReferenceFieldItemListInterface $field, $attribute = 'target_id')
  {
    $result = [];
    foreach ($field->getIterator() as $item) {
      $result[] = $item->{$attribute};
    }

    return $result;
  }

}
