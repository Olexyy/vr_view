<?php

namespace Drupal\vr_view\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\vr_view\Entity\VrView;


/**
 * Provides a 'Vr View Landing' block.
 *
 * @Block(
 *   id = "vr_view_landing_block",
 *   admin_label = @Translation("Vr View Landing block"),
 * )
 */

class VrViewLanding extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $config = $this->getConfiguration();
    $vr_view_id = isset($config['vr_view_id']) ? $config['vr_view_id'] : '';
    $vr_view = VrView::load($vr_view_id);
    $content = '';
    if($vr_view) {
      $content = $vr_view->image->view(VrView::getDisplayDefinition(VrView::displayTypeLanding));
    }
    return $content;/*array(
      '#markup' => $content,
    );*/
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();

    // Add a form field to the existing block configuration form.
    $form['vr_view_id'] = array(
      '#type' => 'textfield',
      '#title' => t('Id of Vr view to be displayed'),
      '#default_value' => isset($config['vr_view_id']) ? $config['vr_view_id'] : '',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Save our custom settings when the form is submitted.
    $this->setConfigurationValue('vr_view_id', $form_state->getValue('vr_view_id'));
  }

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {
    $vr_view_id = $form_state->getValue('vr_view_id');

    if (!is_numeric($vr_view_id) || !VrView::load($vr_view_id)) {
      $form_state->setErrorByName('vr_view_id', t('Needs to be existing Vr View id.'));
    }
  }

}