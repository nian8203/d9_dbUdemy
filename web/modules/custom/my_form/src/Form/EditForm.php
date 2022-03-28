<?php

namespace Drupal\my_Form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements an example form.
 */
class EditForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'my_form_editForm';
  }

  public static function listarRegistro($arg){

    $database = \Drupal::database();
    $query = $database->query("SELECT * FROM {datosPersonales} WHERE id = :id", [
      ':id' => $arg,
    ]);
    $result = $query->fetchAssoc();

    return $result;

  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $arg = null) {

    $form['#attached']['library'][] = 'seven/global-styling'; //add stilos del tema seven 


    //ksm($this->listarRegistro($arg));

    $registro = array();
    $registro = $this->listarRegistro($arg); //cargar datos en formulario para editar

    //creacion de contenedor para insertar los campos descritos abajo
    $form['datos_personales'] = array( 
      '#type' => 'fieldset',
      '#title' => $this->t('Datos personales'),
      '#attributes' => array(
        'class' => array('mi_clase'),
      ),
    );

    //en los campos se debe add el nombre del contenedor antes
    //del nombre del campo ej = $form['datos_personales']['nombre'] 
    $form['datos_personales']['nombre'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Digite su nombre'),
      '#default_value' => $registro['nombre'],
      '#size' => 60,
      '#maxlength' => 30,
      //'#pattern' => 'some-prefix-[a-z]+',
      '#required' => true,
    );
    
    $form['datos_personales']['apellido'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Digite su apellido'),
      '#default_value' => $registro['apellido'],
      '#size' => 60,
      '#maxlength' => 30,
      //'#pattern' => 'some-prefix-[a-z]+',
      '#required' => TRUE,
    );

    $form['datos_personales']['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Digite su correo'),
      '#default_value' => $registro['email'],
      //'#pattern' => '*@example.com',
      '#required' => true,
    ];


    $form['actions']['#type'] = 'actions';
    
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Send'),
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

    //nuevo container datos institucionales
    $form['datos_institucionales'] = array(
      '#type' => 'details',
      '#title' => $this->t('Datos Institucionales'),
      '#open' => TRUE,
    );
    
    $form['datos_institucionales'] ['telefono'] = array(
      '#type' => 'tel',
      '#title' => $this->t('Digite un telefono'),
      '#default_value' => $registro['celular'],
      //'#pattern' => '[^\\d]*',
      '#required' => true,
    );
    
    $form['datos_institucionales']['expiration'] = [
      '#type' => 'date',
      '#title' => $this->t('Fecha de ingreso'),
      '#default_value' => $registro['fecha'],
      //'#default_value' => '2020-02-05',
    ];

    $form['idRegistro'] = array(
      '#type' => 'hidden',
      '#value' => $arg,
    );

    // $form['phone_number'] = [
    //   '#type' => 'tel',
    //   '#title' => $this->t('Your phone number'),
    // ];
    // $form['actions']['#type'] = 'actions';
    // $form['actions']['submit'] = [
    //   '#type' => 'submit',
    //   '#value' => $this->t('Save'),
    //   '#button_type' => 'primary',
    // ];
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

    $campos = array(
      'nombre' => $form_state->getValue('nombre'),
      'apellido' => $form_state->getValue('apellido'),
      'email' => $form_state->getValue('email'),
      'celular' => $form_state->getValue('telefono'),
      'fecha' => $form_state->getValue('expiration'),
    );
    ksm($campos);

    $id = $form_state->getValue('idRegistro');


    $connection = \Drupal::database();
    $connection->update('datosPersonales')
    ->fields($campos)
    ->condition('id', $id)
    ->execute();

    
    \Drupal::messenger()->addStatus('Datos actualizados con éxito!!!');

    $form_state->setRedirect('my_form.mostrartodo');



    $this->messenger()->addStatus($this->t('Your phone number is @number', ['@number' => $form_state->getValue('phone_number')]));
  }

}
