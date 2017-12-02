<?php

namespace Drupal\vr_view\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a list controller for vr_view entity.
 *
 * @ingroup vr_view
 */
class VrViewListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   *
   * We override ::render() so that we can add our own content above the table.
   * parent::render() is where EntityListBuilder creates the table using our
   * buildHeader() and buildRow() implementations.
   */
  public function render() {
    $build['description'] = [
      '#markup' => $this->t('You can manage fields on the <a href="@adminlink">Vr View admin page</a>.', array(
        '@adminlink' => \Drupal::urlGenerator()
          ->generateFromRoute('vr_view.vr_view_settings'),
      )),
    ];

    $build += parent::render();
    return $build;
  }

  /**
   * {@inheritdoc}
   *
   * Building the header and content lines for the vr_view list.
   *
   * Calling the parent::buildHeader() adds a column for the possible actions
   * and inserts the 'edit' and 'delete' links as defined for the entity type.
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Name');
    $header['is_starting'] = $this->t('Is starting');
    $header['hotspots'] = $this->t('Hotspots');
    $header['relative'] = $this->t('Relative');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\vr_view\Entity\VrView */
    $row['id'] = $entity->id();
    $row['name'] = $entity->toLink();
    $row['is_starting'] = $entity->is_starting? $this->t('yes') : $this->t('no');
    $row['hotspots'] = $entity->hotspots->count();
    $row['relative'] = $entity->getRelativeCount();
    return $row + parent::buildRow($entity);
  }

  /**
   * Overrides parent::getOperations().
   *
   * @inheritdoc
   */
  public function getOperations(EntityInterface $entity) {
    $operations = parent::getOperations($entity);

    $operations['interactive'] = array(
      'title' => t('Interactive'),
      'url' => $entity->toUrl('interactive'),
      'weight' => 20,
    );

    return $operations;
  }

}