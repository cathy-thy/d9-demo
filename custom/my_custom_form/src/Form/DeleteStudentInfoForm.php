<?php

namespace Drupal\my_custom_form\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;

/**
 * Delete Form Data
 */
class DeleteStudentInfoForm extends ConfirmFormBase
{
    public function getFormId()
    {
        return 'delete_studentinfo_form';
    }

    public $cid;

    public function getQuestion()
    {
        return t('Do you confirm to delete student record?');
    }

    public function getCancelUrl()
    {
        return new Url('my_custom_form.studentinfo_table_controller_display');
    }

    public function getDescription()
    {
        return t('Confirm if you want to delete selected student record');
    }

    public function getConfirmText()
    {
        return t('Confirm Delete');
    }

    public function getCancelText()
    {
        return t('Cancel');
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $cid = null)
    {
        $this->id = $cid;
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        parent::validateForm($form, $form_state);
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $query = \Drupal::database();
        $query->delete('studentinfo')
              ->condition('id', $this->id)
              ->execute();
        \Drupal::messenger()->addMessage('Successfully delete data.');
        //drupal_flush_all_caches();
        cache_clear_all('*', 'cache_data', true);
        $form_state->setRedirect('my_custom_form.studentinfo_table_controller_display');
    }
}
