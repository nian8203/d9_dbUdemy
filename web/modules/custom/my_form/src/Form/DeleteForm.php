<?php

namespace Drupal\my_Form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements an example form.
 */
class DeleteForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'my_form_deleteForm';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $arg = null) {
    
    $msg = 'El ID del registro a eliminar es:';
    $adv = '<br><br><i>Esta operación no se podra deshacer.</i><br><strong>Desea continuar?</strong>';

    $form['body'] = array(
      '#type' => 'label',
      '#title' => $msg.$arg.$adv,
      //'#format' => 'full_html',
      '#default_value' => '<p>The quick brown fox jumped over the lazy dog.</p>',
    );

    $form['idRegistro'] = array(
      '#type' => 'hidden',
      '#value' => $arg,
    );
    
   


    $form['actions']['#type'] = 'actions';
    
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Delete'),
      '#buton_type' => 'primary',
      // '#attributes' => array(  //modifica los estilos llamandolos desde el css
      //   'class' => array('.btn-send'),
      // ),
    );

    $form['actions']['cancelar'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Cancel'),
      '#submit' => array('custom_form_cancelar'), //llamar a la funcion en .module
      '#limit_validation_errors' => array(), //redireccionar sin que se ejecuten las validaciones de los campos
      // '#attributes' => array(  //sirve para modificar el estilo de los botones desde css
      //   'class' => array('.btn-send'),
    );

    
    return $form;
  }

  // /**
  //  * {@inheritdoc}
  //  */
  // public function validateForm(array &$form, FormStateInterface $form_state) {
  //   if (strlen($form_state->getValue('phone_number')) < 3) {
  //     $form_state->setErrorByName('phone_number', $this->t('The phone number is too short. Please enter a full phone number.'));
  //   }
  // }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

   
    $id = $form_state->getValue('idRegistro');

    $connection = \Drupal::database();
    $connection->delete('datosPersonales')
    ->condition('id',$id)
    ->execute();
    
    \Drupal::messenger()->addStatus(t('Registro eliminado con exito'));

    $form_state->setRedirect('my_form.mostrartodo');



    $this->messenger()->addStatus($this->t('Your phone number is @number', ['@number' => $form_state->getValue('phone_number')]));
  }

}
