<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function index()
	{
		$this->load->model("services_model");
		$services = $this->services_model->show_services();

		$this->load->model("team_model");
		$team = $this->team_model->show_team();

		$data = array(
			"scripts" => array(
				"owl.carousel.min.js",
				"theme-scripts.js" 
			),
			"services" => $services,
			"team" => $team
		);
		$this->template->show("home.php", $data);
	}
}
