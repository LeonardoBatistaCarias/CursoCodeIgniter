<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Restrict extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("session");
    }

    public function index()
    {        
        if ($this->session->userdata("user_id")) {
            $data = array(
                "styles" => array(
                    "dataTables.bootstrap.min.css",
                    "datatables.min.css"
                ),
                "scripts" => array(                    
                    "datatables.min.js",     
                    "dataTables.bootstrap.min.js",               
                    "sweetalert2.all.min.js",                    
                    "util.js",                    
                    "restrict.js"
                ),
                "user_id" => $this->session->userdata("user_id")
            );

            $this->template->show("restrict.php", $data);
        } else {
            $data = array(
                "scripts" => array(
                    "util.js",
                    "login.js"
                )
            );
            $this->template->show("login.php",$data);
        }
    }
        
    public function logoff() {
        $this->session->sess_destroy();
        header("Location: " . base_url() . "restrict");
    }
    public function ajax_login() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }

        $json = array();
        $json["status"] = 1;
        $json["error_list"] = array();

        $username = $this->input->post("username");
        $password = $this->input->post("password");

        if(empty($username)) {
            $json["status"] = 0;
            $json["error_list"]["#username"] = "Usuário não pode ser vazio!"; 
        } else {
            $this->load->model("users_model");
            $result = $this->users_model->get_user_data($username);
            if ($result) {
                $user_id = $result->user_id;
                $password_hash = $result->password_hash;

                if(password_verify($password, $password_hash)) {
                    $this->session->set_userdata("user_id", $user_id);
                } else {
                    $json["status"] = 0;                    
                }
            } else {
                $json["status"] = 0;
            }

            if ($json["status"] == 0) {
                $json["error_list"]["#btn_login"] = "Usuário e/ou senha incorretos!";
            }
        }

        echo json_encode($json);

    }

    public function ajax_import_image() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$config["upload_path"] = "./tmp/";
		$config["allowed_types"] = "gif|png|jpg";
		$config["overwrite"] = TRUE;

		$this->load->library("upload", $config);

		$json = array();
		$json["status"] = 1;

		if (!$this->upload->do_upload("image_file")) {
			$json["status"] = 0;
			$json["error"] = $this->upload->display_errors("","");
		} else {
			if ($this->upload->data()["file_size"] <= 1024) {
				$file_name = $this->upload->data()["file_name"];
				$json["img_path"] = base_url() . "tmp/" . $file_name;
			} else {
				$json["status"] = 0;
				$json["error"] = "Arquivo não deve ser maior que 1 MB!";
			}

		}

		echo json_encode($json);
	}
    
    public function ajax_save_service() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$config["upload_path"] = "./tmp/";
		
		$json = array();
        $json["status"] = 1;
        $json["error_list"] = array();

        $this->load->model("services_model");

        $data = $this->input->post();

        if (empty($data["service_name"])) {
            $json["error_list"]["#service_name"] = "Nome do serviço é obrigatório!";
        } else {
            if ($this->services_model->is_duplicated("service_name", $data["service_name"], $data["service_id"])) {
                $json["error_list"]["#service_name"] = "Nome de serviço já existente!";
            }

        }
        
        $data["service_duration"] = floatval($data["service_duration"]);
        if (empty($data["service_duration"])) {
            $json["error_list"]["#service_duration"] = "Duração do serviço é obrigatório";
        } else {
            if (!($data["service_duration"] > 0 && $data["service_duration"] < 100)) {
                $json["error_list"]["#service_duration"] = "Duração do serviço deve ser maior que 0 (h) e maior que 100 (h)!";
            }
        }

        if (!empty($json["error_list"])) {
            $json["status"] = 0;            
        } else {
            if (!empty($data["service_img"])) {

				$file_name = basename($data["service_img"]);
				$old_path = getcwd() . "/tmp/" . $file_name;
				$new_path = getcwd() . "/public/images/services/" . $file_name;
				rename($old_path, $new_path);

				$data["service_img"] = "/public/images/services/" . $file_name;

			} else {
				unset($data["service_img"]);
			}

			if (empty($data["service_id"])) {
				$this->services_model->insert($data);
			} else {
				$service_id = $data["service_id"];
				unset($data["service_id"]);
				$this->services_model->update($service_id, $data);
			}
        }

		echo json_encode($json);
    }
    
    public function ajax_save_member() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$json = array();
		$json["status"] = 1;
		$json["error_list"] = array();

		$this->load->model("team_model");

		$data = $this->input->post();

		if (empty($data["member_name"])) {
			$json["error_list"]["#member_name"] = "Nome do membro é obrigatório!";
		} 

		if (!empty($json["error_list"])) {
			$json["status"] = 0;
		} else {

			if (!empty($data["member_photo"])) {

				$file_name = basename($data["member_photo"]);
				$old_path = getcwd() . "/tmp/" . $file_name;
				$new_path = getcwd() . "/public/images/team/" . $file_name;
				rename($old_path, $new_path);

				$data["member_photo"] = "/public/images/team/" . $file_name;

			} else {
				unset($data["member_photo"]);
			}

			if (empty($data["member_id"])) {
				$this->team_model->insert($data);
			} else {
				$member_id = $data["member_id"];
				unset($data["member_id"]);
				$this->team_model->update($member_id, $data);
			}
		}

		echo json_encode($json);
    }
    
    public function ajax_save_user() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$config["upload_path"] = "./tmp/";
		
		$json = array();
        $json["status"] = 1;
        $json["error_list"] = array();

        $this->load->model("users_model");

        $data = $this->input->post();

        if (empty($data["user_login"])) {
            $json["error_list"]["#user_login"] = "Login é obrigatório!";
        } else {
            if ($this->users_model->is_duplicated("user_login", $data["user_login"], $data["user_id"])) {
                $json["error_list"]["#user_login"] = "Login já existente!";
            }
        }
        
        if (empty($data["user_full_name"])) {
            $json["error_list"]["#user_full_name"] = "Nome completo é obrigatório!";
        }

        if (empty($data["user_email"])) {
            $json["error_list"]["#user_email"] = "E-mail é obrigatório!";
        } else {
            if ($this->users_model->is_duplicated("user_email", $data["user_email"], $data["user_id"])) {
                $json["error_list"]["#user_email"] = "E-mail já existente!";
            } else {
                if ($data["user_email"] != $data["user_email_confirm"]) {
                    $json["error_list"]["#user_email"] = "";
                    $json["error_list"]["#user_email_confirm"] = "E-mails não conferem!";
                }
            }
        }
        
        if (empty($data["user_password"])) {
            $json["error_list"]["#user_password"] = "Senha é obrigatório!";
        } else {            
                if ($data["user_password"] != $data["user_password_confirm"]) {
                    $json["error_list"]["#user_password"] = "";
                    $json["error_list"]["#user_password_confirm"] = "Senhas não conferem!";
                }            
        }       

        if (!empty($json["error_list"])) {
            $json["status"] = 0;            
        } else {            
            $data["password_hash"] = password_hash($data["user_password"], PASSWORD_DEFAULT);

            unset($data["user_password"]);
            unset($data["user_password_confirm"]);            
            unset($data["user_email_confirm"]);

			if (empty($data["user_id"])) {
				$this->users_model->insert($data);
			} else {
				$user_id = $data["user_id"];
				unset($data["user_id"]);
				$this->users_model->update($user_id, $data);
			}
        }

		echo json_encode($json);
    }
    
    public function ajax_get_user_data() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$config["upload_path"] = "./tmp/";
		
		$json = array();
        $json["status"] = 1;
        $json["input"] = array();

        $this->load->model("users_model");

        $user_id = $this->input->post("user_id");
        $data = $this->users_model->get_data($user_id)->result_array()[0];
        $json["input"]["user_id"] = $data["user_id"];
        $json["input"]["user_login"] = $data["user_login"];
        $json["input"]["user_full_name"] = $data["user_full_name"];
        $json["input"]["user_email"] = $data["user_email"];
        $json["input"]["user_email_confirm"] = $data["user_email"];
        $json["input"]["user_password"] = $data["password_hash"];
        $json["input"]["user_password_confirm"] = $data["password_hash"];

        echo json_encode($json);
    }
    
    public function ajax_get_service_data() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$config["upload_path"] = "./tmp/";
		
		$json = array();
        $json["status"] = 1;
        $json["input"] = array();

        $this->load->model("services_model");

        $service_id = $this->input->post("service_id");
        $data = $this->services_model->get_data($service_id)->result_array()[0];
        $json["input"]["service_id"] = $data["service_id"];
        $json["input"]["service_name"] = $data["service_name"];
        $json["img"]["service_img_path"] = base_url() . $data["service_img"];
        $json["input"]["service_duration"] = $data["service_duration"];
        $json["input"]["service_description"] = $data["service_description"];        

        echo json_encode($json);
    }    

    public function ajax_get_member_data() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$config["upload_path"] = "./tmp/";
		
		$json = array();
        $json["status"] = 1;
        $json["input"] = array();

        $this->load->model("team_model");

        $member_id = $this->input->post("member_id");
        $data = $this->team_model->get_data($member_id)->result_array()[0];
        $json["input"]["member_id"] = $data["member_id"];
        $json["input"]["member_name"] = $data["member_name"];
        $json["img"]["member_photo_path"] = base_url() . $data["member_photo"];
        $json["input"]["member_description"] = $data["member_description"];        

        echo json_encode($json);
    }

    public function ajax_delete_service_data() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}
		
		$json = array();
        $json["status"] = 1;
        

        $this->load->model("services_model");

        $service_id = $this->input->post("service_id");
        $data = $this->services_model->delete($service_id);    

        echo json_encode($json);
    }

    public function ajax_delete_member_data() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}
		
		$json = array();
        $json["status"] = 1;
        

        $this->load->model("team_model");

        $member_id = $this->input->post("member_id");
        $data = $this->team_model->delete($member_id);    

        echo json_encode($json);
    }

    public function ajax_list_service() {
        if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
        }
        
        $this->load->model("services_model");
        $services = $this->services_model->get_datatable();

        $data = array();

        foreach ($services as $service) {

            $row = array();
            $row[] = $service->service_name;
            
            if ($service->service_img) {
                $row[] = '<img src="'.base_url().$service->service_img.'" style="max-height: 100px; 
                    max-width: 100px;">';
            } else {
                $row[] ="";
            }

            $row[] = $service->service_duration;
            $row[] = '<div class="description">'.$service->service_description."</div>";

            $row[] = '<div style="display: inline-block;">
                        <button class="btn btn-primary btn-edit-service" 
                        service_id="'.$service->service_id.'">
                        <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-del-service" 
                        service_id="'.$service->service_id.'">
                        <i class="fa fa-times"></i>
                        </button>
                    </div>';
            $data[]  = $row;
        }

        $json = array(
            "draw" => $this->input->post("draw"),
            "recordsTotal" => $this->services_model->records_total(),
            "recordsFiltered" => $this->services_model->records_filtered(),
            "data" => $data
        );

        echo json_encode($json);
    }

    public function ajax_list_member() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$this->load->model("team_model");
		$team = $this->team_model->get_datatable();

		$data = array();
		foreach ($team as $member) {

			$row = array();
			$row[] = $member->member_name;

			if ($member->member_photo) {
				$row[] = '<img src="'.base_url().$member->member_photo.'" style="max-height: 100px; max-width: 100px;">';
			} else {
				$row[] = "";
			}

			$row[] = '<div class="description">'.$member->member_description.'</div>';

			$row[] = '<div style="display: inline-block;">
						<button class="btn btn-primary btn-edit-member" 
							member_id="'.$member->member_id.'">
							<i class="fa fa-edit"></i>
						</button>
						<button class="btn btn-danger btn-del-member" 
							member_id="'.$member->member_id.'">
							<i class="fa fa-times"></i>
						</button>
					</div>';

			$data[] = $row;

		}

		$json = array(
			"draw" => $this->input->post("draw"),
			"recordsTotal" => $this->team_model->records_total(),
			"recordsFiltered" => $this->team_model->records_filtered(),
			"data" => $data,
		);

		echo json_encode($json);
    }
    
    public function ajax_list_user() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$this->load->model("users_model");
		$users = $this->users_model->get_datatable();

		$data = array();
		foreach ($users as $user) {

			$row = array();
            $row[] = $user->user_login;			
            $row[] = $user->user_full_name;			
            $row[] = $user->user_email;						

			$row[] = '';

			$data[] = $row;

		}

		$json = array(
			"draw" => $this->input->post("draw"),
			"recordsTotal" => $this->users_model->records_total(),
			"recordsFiltered" => $this->users_model->records_filtered(),
			"data" => $data,
		);

		echo json_encode($json);
	}

}
