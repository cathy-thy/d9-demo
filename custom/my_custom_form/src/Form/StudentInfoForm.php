<?php

namespace Drupal\my_custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Form Data
 */
class StudentInfoForm extends FormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'studentinfo_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $conn = Database::getConnection();
        $record = [];
        if (isset($_GET['id'])) {
            $query = $conn->select('studentinfo', 'si')
                ->condition('id', $_GET['id'])
                ->fields('si');
            $record = $query->execute()->fetchAssoc();
        }

        //Description of the form to be displayed on the user interface
        $form['text_header'] = [
        '#prefix' => '<strong>',
        '#suffix' => '<br><br></strong>',
        '#markup' => $this->t('This is for recording student information.'),
        '#weight' => -100,
        ];

        //Different fields
        $form['student_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Student Name'),
        '#required' => true,
        '#default_value' => (isset($record['student_name']) && $_GET['id']) ? $record['student_name'] : '',
        ];

        $form['student_gender'] = [
            '#type' => 'select',
            '#title' => $this->t('Gender'),
            '#options' => [
                'male' => t('Male'),
              'female' => t('Female'),
              'na' => t('Prefer not to say'),
              '#default_value' => (isset($record['student_gender']) && $_GET['id']) ? $record['student_gender'] : '',
            ],
        ];

        $form['student_email'] = [
        '#type' => 'email',
        '#title' => $this->t('Student Email'),
        '#required' => true,
        '#default_value' => (isset($record['student_email']) && $_GET['id']) ? $record['student_email'] : '',
        ];

        //Submit and Reset buttons
        $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'save',
        ];

        $form['reset'] = [
        '#type' => 'button',
        '#value' => 'reset',
        '#attributes' => ['onclick' => 'this.form.reset();return false;',],
        ];
        return $form;
    }

    /**
    * {@inheritdoc}
    */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $student_name = $form_state->getValue('student_name');
        if (preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $student_name)) {
            $form_state->setErrorByName('student_name', $this->t('Your name should not include special character(s).'));
        }
        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        $field = $form_state->getValues();

        $student_name = $field['student_name'];
        $student_gender = $field['student_gender'];
        $student_email = $field['student_email'];

        $field = [
            'student_name' => $student_name,
            'student_gender' => $student_gender,
            'student_email' => $student_email,
        ];
        $query = \Drupal::database();
        if (isset($_GET['id'])) {
            //for updating existing record
            $query->update('studentinfo')
                ->fields($field)
                ->condition('id', $_GET['id'])
                ->execute();
            \Drupal::messenger()->addMessage('Successfully saved data from custom form.');
        } else {
            //for inserting new value
            $query->insert('studentinfo')
                ->fields($field)
                ->execute();
            \Drupal::messenger()->addMessage('Successfully saved new data from custom form.');
        }
        drupal_flush_all_caches();
        $form_state->setRedirect('my_custom_form.studentinfo_table_controller_display');
    }
}
