<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_item extends MX_controller{

	function __construct(){
		parent::__construct();
		$this->is_logged_in();
		$this->load->model('Mdl_item');
	}

	function is_logged_in(){
		$is_logged_in=$this->session->userdata('is_logged_in');
		if(!isset($is_logged_in)||$is_login_in=FALSE){				
		echo "<div class='container-fluid'>
			<div class='alert alert-danger' role='alert'>You do not have persmission to access this page.</div>
			</div>";
		$data['title']="Login";
		$data['module']="item";
		$data['view_file']="admin/login";
		echo Modules::run('template/login',$data);
		die();
		}
	}

	function index(){
		$data['qrs']=$this->Mdl_item->show();
		$data['title']="Admin item";
		$data['module']="item";
		$data['view_file']="admin/index";
		$this->parser->parse('template/admin',$data);
	}

	function create(){
		$data['title']="Create new item";
		$data['category']=$this->Mdl_item->getCategory();
		$data['subcategory']=$this->Mdl_item->getSubcategory();
		$data['module']="item";
		$data['view_file']="admin/create";
		$this->parser->parse('template/admin',$data);
	}

	function store(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('heading','Heading','trim|required');
		$this->form_validation->set_rules('description','Description','trim|required');
		if($this->form_validation->run()==FALSE){
		echo "<div class='container-fluid'>
			<div class='alert alert-danger' role='alert'>Please enter complete and valid data!</div>
			</div>";
		$data['title']="Store item";
		$data['module']="item";
		$data['view_file']="admin/create";
		$this->parser->parse('template/admin',$data);
		}else{
		$userfile="image";
		$config['upload_path']          = './assets/upload/items';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 1000000;
        $config['max_width']            = 2000;
        $config['max_height']           = 2000;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload($userfile))
            {
                $error = array('error' => $this->upload->display_errors());
            }
        else
            {
                $data = array('upload_data' => $this->upload->data($userfile));
            }
        $filename=array('image'=>$this->upload->data());
        $fname=$filename['image']['file_name'];

        $slug = $this->input->post('heading');
        $slug = url_title($slug,'dash',true);
        $cat = $this->input->post('category');
        $catslug = url_title($cat,'dash',true);
        $subcat = $this->input->post('subcategory');
        $subcatslug = url_title($subcat,'dash',true);

		$data=array(
			'heading'=>$this->input->post('heading'),
			'slug'=>$slug,
			'page_title'=>$this->input->post('page_title'),
			'meta_desc'=>$this->input->post('meta_desc'),
			'meta_keyword'=>$this->input->post('meta_keyword'),
			'description'=>$this->input->post('description'),
			'image'=>$fname,
			'image_thumb'=>$this->input->post('image_thumb'),
			'status'=>$this->input->post('status'),
			'order'=>$this->input->post('order'),
			'category'=>$catslug,
			'subcategory'=>$subcatslug,
			'price'=>$this->input->post('price'),
			'created_on'=>date('Y')
		);
		$this->Mdl_item->create($data);
		echo "<div class='container-fluid'>
					<div class='alert alert-success' role='alert'>New Item Created!</div>
				</div>";
			$data['title']="Item Created";
			$data['module']="item";
			$data['view_file']="admin/create";
			$this->parser->parse('template/admin',$data);		
		}
	}

	function show(){
		// show used in index
	}

	function edit(){
		$id=$this->input->post('id');
		$data['qrs']=$this->Mdl_item->edit();
		$data['category']=$this->Mdl_item->getCategory();
		$data['subcategory']=$this->Mdl_item->getSubcategory();
		$data['title']="Edit item";
		$data['module']="item";
		$data['view_file']="admin/edit";
		$this->parser->parse('template/admin',$data);
	}

	function update(){
		$userfile="image";
		$id=$this->input->post('id');
		$config['upload_path']          = './assets/upload/items';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 1000000;
        $config['max_width']            = 2000;
        $config['max_height']           = 2000;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload($userfile))
            {
                $error = array('error' => $this->upload->display_errors());
            }
        else
            {
                $data = array('upload_data' => $this->upload->data($userfile));
            }
        $filename=array('image'=>$this->upload->data());
        $fname=$filename['image']['file_name'];

        $slug = $this->input->post('heading');
        $slug = url_title($slug,'dash',true);
        $cat = $this->input->post('category');
        $catslug = url_title($cat,'dash',true);
        $subcat = $this->input->post('subcategory');
        $subcatslug = url_title($subcat,'dash',true);

		$data=array(
			'heading'=>$this->input->post('heading'),
			'slug'=>$slug,
			'page_title'=>$this->input->post('page_title'),
			'meta_desc'=>$this->input->post('meta_desc'),
			'meta_keyword'=>$this->input->post('meta_keyword'),
			'description'=>$this->input->post('description'),
			'image'=>$fname,
			'image_thumb'=>$this->input->post('image_thumb'),
			'status'=>$this->input->post('status'),
			'category'=>$catslug,
			'subcategory'=>$subcatslug,
			'price'=>$this->input->post('price'),
			'order'=>$this->input->post('order')
		);
		$this->Mdl_item->update($id,$data);
		echo "<div class='container-fluid'>
			<div class='alert alert-info' role='alert'>Data Updated!</div>
			</div>";
		$data['title']="Update";
		$data['module']="item";
		$data['view_file']="admin/create";
		$this->parser->parse('template/admin',$data);
	}

	function destroy(){
		$id=$this->uri->segment(4);
		$this->Mdl_item->destroy($id);
		echo "<div class='container-fluid'>
			<div class='alert alert-danger' role='alert'>Data Deleted!</div>
			</div>";
		$data['title']="Delete";
		$data['module']="item";
		$data['view_file']="admin/create";
		$this->parser->parse('template/admin',$data);
	}
}
