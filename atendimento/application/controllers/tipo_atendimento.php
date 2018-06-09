<?php
#error_reporting(0);
if(!defined('BASEPATH')) exit('No direct script access allowed');
//date_default_timezone_set('America/Sao_Paulo');
#setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
/**
* Classe responsável pela tipo_atendimento
*/
class Tipo_atendimento extends CI_Controller
{
    
	/**
	 * Tipo_atendimento::__construct()
	 * 
	 * @return
	 */
	public function __construct(){
       
		parent::__construct();
        
        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home'));
		}
        
        $this->load->library('Util', '', 'util');
        $this->load->model('dadosBanco_model','dadosBanco');
        #$this->load->model('permissaoPerfil_model','permissaoPerfil');
        $this->load->model('tipo_atendimento_model','tipo_atendimento');
        $this->load->model('guiche_model','guiche');
        $this->load->helper('url');
        $this->load->library('pagination');
		$this->load->helper('form');
        $this->load->library('table');
    }
    
    
    /**
     * Tipo_atendimento::tipo_atendimentos()
     * 
     * @return
     */
    public function tipo_atendimentos(){
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
       
	    #$dados['valores'] = $this->relatorio->arquivoRetornoDiario();
        #$dados['campos'] = ($dados['valores'][0]);
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        $this->layout->region('corpo', 'admin/tipo_atendimento/view_psq_tipo_atendimento');
        #$this->layout->region('corpo', 'view_conteudo');
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Tipo_atendimento::ficha()
     * 
     * Exibe a ficha para cadastro e atualização do tipo_atendimento
     * 
     * @param bool $cd Cd do tipo_atendimento que quando informado carrega os dados do tipo_atendimento
     * @return
     */
    public function ficha($cd = false){
        /*
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);        
        */
        if($cd){
            
            $dados = $this->tipo_atendimento->dadosTipo_atendimento($cd);
            
            $campos = array_keys($dados);
            
            foreach($campos as $campo){
			 
                #Data
                #if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $dados[$campo])){
                    #$dados[$campo] = $this->util->formataData($dados[$campo],'BR');
                #}
             
				$info[$campo] = $dados[$campo]; # ALIMENTA OS CAMPOS COM OS DADOS
			}
            
        }else{
            
            $dados = array();
            
            $campos = $this->tipo_atendimento->camposTipo_atendimento();
            
            foreach($campos as $campo){
                $info[$campo] = '';
            }
        
        }
        #echo '<pre>'; print_r($campos); exit();
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
       
   	 #$dados['valores'] = $this->relatorio->arquivoRetornoDiario();
        #$dados['campos'] = ($dados['valores'][0]);
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        
        if(in_array(21, $this->session->userdata('permissoes'))){
        
            $this->layout->region('corpo', 'admin/tipo_atendimento/view_frm_tipo_atendimento', $info);
        
        }else{
            
            $this->layout->region('corpo', 'view_permissao');
            
        }
        
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Tipo_atendimento::salvar()
     * 
     * Cadastra ou atualiza o tipo_atendimento
     * 
     * @return
     */
    public function salvar(){
        
        array_pop($_POST);
        
        if($this->input->post('cd_tipo_atendimento')){
            
            try{
            
                $status = $this->tipo_atendimento->atualiza();
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
        }else{
            
            try{
            
                $status = $this->tipo_atendimento->insere();
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
            $_POST['cd_tipo_atendimento'] = $status;
        }
        
        if($status){
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Tipo atendimento salvo com sucesso!</strong></div>');
            
            redirect(base_url('tipo_atendimento/ficha/'.$this->input->post('cd_tipo_atendimento'))); 
            
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao salvar tipo atendimento, caso o erro persiste comunique o administrador!</div>');
            redirect(base_url('tipo_atendimento/ficha'));
            
        }
        
    }
    
    /**
     * Tipo_atendimento::pesquisar()
     * 
     * Pesquisa o tipo_atendimento
     * 
     * @param mixed $nome Nome do tipo_atendimento para pesquisa
     * @param mixed $pagina Página corrente
     * @return
     */
    public function pesquisar($nome = null, $status = null, $pagina = null){
        
        $nome = ($nome == null)? '0': $nome;
        $status = ($status == null)? '0': $status;
        
        $this->load->library('pagination');
        
        $dados['pesquisa'] = 'sim';
        $dados['postNome'] = ($this->input->post('nome_tipo_atendimento') != '')? $this->input->post('nome_tipo_atendimento') : $nome;
        $dados['postStatus'] = ($this->input->post('status_tipo_atendimento') != '')? $this->input->post('status_tipo_atendimento') : $status;
        
        $mostra_por_pagina = 30;
        $dados['tipo_atendimentos'] = $this->tipo_atendimento->psqTipo_atendimentos($dados['postNome'], $dados['postStatus'], $pagina, $mostra_por_pagina);   
        $dados['qtdTipo_atendimentos'] = $this->tipo_atendimento->psqQtdTipo_atendimentos($dados['postNome'], $dados['postStatus']);                     
        
        $config['base_url'] = base_url('tipo_atendimento/pesquisar/'.$dados['postNome'].'/'.$dados['postStatus']); 
		$config['total_rows'] = $dados['qtdTipo_atendimentos'][0]->total;
		$config['per_page'] = $mostra_por_pagina;
		$config['uri_segment'] = 5;
        $config['first_link'] = '&lsaquo; Primeiro';
        $config['last_link'] = '&Uacute;ltimo &rsaquo;';
        $config['full_tag_open'] = '<li>';
        $config['full_tag_close'] = '</li>';
        $config['first_tag_open']	= '';
       	$config['first_tag_close']	= '';
        $config['last_tag_open']		= '';
	    $config['last_tag_close']		= '';
	    $config['first_url']			= ''; // Alternative URL for the First Page.
	    $config['cur_tag_open']		= '<a id="paginacaoAtiva" class="active"><strong>';
	    $config['cur_tag_close']		= '</strong></a>';
	    $config['next_tag_open']		= '';
        $config['next_tag_close']		= '';
	    $config['prev_tag_open']		= '';
	    $config['prev_tag_close']		= '';
	    $config['num_tag_open']		= '';
		$this->pagination->initialize($config);
		$dados['paginacao'] = $this->pagination->create_links();
        
        $dados['postNome'] = ($dados['postNome'] == '0')? '': $dados['postNome'];
        $dados['postStatus'] = ($dados['postStatus'] == '0')? '': $dados['postStatus'];
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
        
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        $this->layout->region('corpo', 'admin/tipo_atendimento/view_psq_tipo_atendimento', $dados);
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Tipo_atendimento::apaga()
     * 
     * Apaga o tipo_atendimento
     * 
     * @return
     */
    public function apaga(){
        
        try{
        
            $status = $this->tipo_atendimento->deleteTipo_atendimento();  
        
        }catch( Exception $e ){
            
            log_message('error', $e->getMessage());
            
        }
        
        if($status){
        
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Tipo atendimento apagado com sucesso!</strong></div>');
            redirect(base_url('tipo_atendimento/tipo_atendimentos'));      
        
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao apagar tipo atendimento, caso o erro persiste comunique o administrador!</div>');
            redirect(base_url('tipo_atendimento/tipo_atendimentos'));
        
        }
    }
                
}
