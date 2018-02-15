<?php
/*
Controlador Base para las acciones de la 
aplicación. Se encarga de canalizar los datos
suministrados por el usuario, hasta el recipiente
de correos de destino.

@author : Jesus Guevara Berbesi. 

*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
		Muestra el formulario para 
		los usuarios y recibe los datos
	 */
	public function index()
	{
		// caja de envio que almacena los datos del
		// usuario
		$shipping_box = array();

		// Verifica si el mensaje se envio con exito
		$send_check = false;

		if( count($_POST) > 0)
		{
			$shipping_box['booking_date'] = $this->input->post('booking_date');
			$shipping_box['booking_name'] = $this->input->post('booking_name');
			$shipping_box['booking_tlf'] = $this->input->post('booking_tlf');
			$shipping_box['booking_email'] = $this->input->post('booking_email');

			if( $this->send_data($shipping_box) )
				$send_check = true;
		}
		
		$data['send_check'] = $send_check;
		$this->load->view('welcome_message',$data);
	}


	/*
		Recibe un array con los datos que serán 
		enviados al recipiente de correos electronicos
		suministrado en la configuración

		$shipping_box type @array
	*/
	public function send_data($shipping_box)
	{
		$message = "Fecha: ".$shipping_box['booking_date']." ";
		$message .= "Nombre: ".$shipping_box['booking_name']." ";
		$message .= "Tlf: ".$shipping_box['booking_tlf']." ";
		$message .= "Email: ".$shipping_box['booking_email']." ";
		
		$this->load->library('email');

		$this->email->from(CONFIG_EMAIL_FROM, CONFIG_EMAIL_NAME);
		$this->email->to($shipping_box['booking_email']);
		//$this->email->cc('another@another-example.com');
		//$this->email->bcc('them@their-example.com');

		$this->email->subject(CONFIG_EMAIL_SUBJECT);
		$this->email->message($message);

		if( $this->email->send() )
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
}
