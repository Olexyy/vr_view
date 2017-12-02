<?php

namespace Drupal\vr_view\Plugin\views\field;

use Drupal\Core\Entity\Entity;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * A handler to provide proper displays for booleans.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("related_vr_views")
 */
class RelatedVrViews extends FieldPluginBase
{
    /**
     * {@inheritdoc}
     */
    protected function defineOptions() {
        $options = parent::defineOptions();
        $options['native_language'] = ['default' => FALSE];

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function buildOptionsForm(&$form, FormStateInterface $form_state) {
        parent::buildOptionsForm($form, $form_state);
        $form['native_language'] = [
            '#title' => $this->t('Display in native language'),
            '#type' => 'checkbox',
            '#default_value' => $this->options['native_language'],
        ];
    }

    /**
     * @{inheritdoc}
     */
    public function query() {
        // Leave empty to avoid a query on this field.
    }

    /**
     * @{inheritdoc}
     */
    public function render(ResultRow $values) {
        $vr_view = $values->_entity;
        return $this->renderRelative($vr_view);
    }

    private function renderRelative(EntityInterface $vr_view) {
        $html = '';
        $display = [
            'type' => 'image',
            'settings' => [
                'type' => 'medium',
            ]
        ];
        foreach ($vr_view->getRelative() as $relative) {
            $html .= $relative->image->view($display);
        }
        return $html;
    }
}