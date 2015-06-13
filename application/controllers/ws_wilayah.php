<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * WS Client Feeder Wilayah Module
 * 
 * @author      Yusuf Ayuba
 * @copyright   2015
 * @Url         http://jago.link
 * @Github      https://github.com/virbo/wsfeeder
 * 
 */
 
class Ws_wilayah extends CI_Controller {
        
    //private $data;
    private $limit;
    private $filter;
    private $order;
    private $offset;
    private $tabel;
    
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('login')) {
            redirect('ws');
        } else {
            $this->limit = $this->config->item('limit');
            $this->filter = $this->config->item('filter');
            $this->order = $this->config->item('order');
            $this->offset = $this->config->item('offset');
            $this->tabel = 'wilayah';
            
            $this->load->model('m_feeder','feeder');
            $this->load->helper('directory');
        }
    }
    
    public function index()
    {
        $this->view();
    }
    
    public function view($offset=0)
    {
        $temp_wil = $this->input->post('nm_wil');
        if ($temp_wil=='') {
            $temp_rec = $this->feeder->getrset($this->session->userdata('token'), 
                                                            $this->tabel, $this->filter, 
                                                            $this->order, $this->limit, 
                                                            $offset
                                                         );
            $temp_count = $this->feeder->count_all($this->session->userdata('token'), $this->tabel);
            
            $temp_jml = $temp_count['result'];
            
            $data['temp_wil'] = '';    
        } else {
            $filter_wil = "nm_wil like '%".$temp_wil."%'";
            $temp_rec = $this->feeder->getrset($this->session->userdata('token'), 
                                                            $this->tabel, $filter_wil, 
                                                            $this->order, $this->limit, 
                                                            $offset
                                                         );
            $temp_jml = count($temp_rec['result']);
            $data['temp_wil'] = $temp_wil;
        }
        
        
        
        //pagination
        $config['base_url'] = site_url('ws_wilayah/view');
        $config['total_rows'] = $temp_jml;
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);
        //
        $data['pagination'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        $data['listsrec'] = $temp_rec['result'];
        $data['total'] = $temp_jml;
        
        $offset==0? $start=$this->pagination->cur_page: $start=$offset+1;
        $data['start'] = $start;
        $data['end'] = $this->pagination->cur_page * $this->pagination->per_page;

        tampil('wilayah/__view_wilayah',$data);
    }
    
}

/* End of file ws_wilayah.php */
/* Location: ./application/controllers/ws_wilayah.php */