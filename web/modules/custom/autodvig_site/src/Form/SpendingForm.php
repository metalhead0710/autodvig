<?php

namespace Drupal\autodvig_site\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for Spending edit forms.
 *
 * @ingroup autodvig_site
 */
class SpendingForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created new spending.'));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the spending.'));
    }
    $form_state->setRedirect('view.spendings.admin_list');
  }

}
