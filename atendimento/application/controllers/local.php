<?php
#error_reporting(0);
if(!defined('BASEPATH')) exit('No direct script access allowed');
#date_default_timezone_set('America/Sao_Paulo');
#setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
/**
* Classe responsável pela local
*/
class Local extends CI_Controller
{
    
	/**
	 * Local::__construct()
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
        $this->load->model('local_model','local');
        $this->load->model('guiche_model','guiche');
        $this->load->helper('url');
        $this->load->library('pagination');
		$this->load->helper('form');
        $this->load->library('table');
    }
    
    /**
     * Local::locais()
     * 
     * @return
     */
    public function locais(){
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
        
	    #$dados['valores'] = $this->relatorio->arquivoRetornoDiario();
        #$dados['campos'] = ($dados['valores'][0]);
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        $this->layout->region('corpo', 'admin/local/view_psq_local');
        #$this->layout->region('corpo', 'view_conteudo');
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Local::ficha()
     * 
     * Exibe a ficha para cadastro e atualização do local
     * 
     * @param bool $cd Cd do local que quando informado carrega os dados do local
     * @return
     */
    public function ficha($cd = false){
        
        /*error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);  */      
        
        if($cd){
            
            $dados = $this->local->dadosLocal($cd);
            
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
            
            $campos = $this->local->camposLocal();
            
            foreach($campos as $campo){
                $info[$campo] = '';
            }
        
        }
        #echo '<pre>'; print_r($campos); exit();
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
        $info['municipio'] = $this->dadosBanco->municipio();
       
   	 #$dados['valores'] = $this->relatorio->arquivoRetornoDiario();
        #$dados['campos'] = ($dados['valores'][0]);
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        
        if(in_array(15, $this->session->userdata('permissoes'))){
        
            $this->layout->region('corpo', 'admin/local/view_frm_local', $info);
        
        }else{
            
            $this->layout->region('corpo', 'view_permissao');
            
        }
        
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Local::salvar()
     * 
     * Cadastra ou atualiza o local
     * 
     * @return
     */
    public function salvar(){
        
        array_pop($_POST);
        
        if($this->input->post('cd_local')){
            
            try{
            
                $status = $this->local->atualiza();
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
        }else{
            
            try{
            
                $status = $this->local->insere();
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
            $_POST['cd_local'] = $status;
        }
        
        if($status){
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Guich&ecirc; salvo com sucesso!</strong></div>');
            
            redirect(base_url('local/ficha/'.$this->input->post('cd_local'))); 
            
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao salvar guich&ecirc;, caso o erro persiste comunique o administrador!</div>');
            redirect(base_url('local/ficha'));
            
        }
        
    }
    
    /**
     * Local::pesquisar()
     * 
     * Pesquisa o local
     * 
     * @param mixed $nome Nome do local para pesquisa
     * @param mixed $pagina Página corrente
     * @return
     */
    public function pesquisar($nome = null, $status = null, $pagina = null){
        
        $nome = ($nome == null)? '0': $nome;
        $status = ($status == null)? '0': $status;
        
        $this->load->library('pagination');
        
        $dados['pesquisa'] = 'sim';
        $dados['postNome'] = ($this->input->post('nome_local') != '')? $this->input->post('nome_local') : $nome;
        $dados['postStatus'] = ($this->input->post('status_local') != '')? $this->input->post('status_local') : $status;
        
        $mostra_por_pagina = 30;
        $dados['locais'] = $this->local->psqLocals($dados['postNome'], $dados['postStatus'], $pagina, $mostra_por_pagina);   
        $dados['qtdLocals'] = $this->local->psqQtdLocals($dados['postNome'], $dados['postStatus']);                     
        
        $config['base_url'] = base_url('local/pesquisar/'.$dados['postNome'].'/'.$dados['postStatus']); 
		$config['total_rows'] = $dados['qtdLocals'][0]->total;
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
        $this->layout->region('corpo', 'admin/local/view_psq_local', $dados);
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Local::apaga()
     * 
     * Apaga o local
     * 
     * @return
     */
    public function apaga(){
        
        try{
        
            $status = $this->local->deleteLocal();  
        
        }catch( Exception $e ){
            
            log_message('error', $e->getMessage());
            
        }
        
        if($status){
        
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Guich&ecirc; apagado com sucesso!</strong></div>');
            redirect(base_url('local/locais'));      
        
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao apagar guich&ecirc;, caso o erro persiste comunique o administrador!</div>');
            redirect(base_url('local/locais'));
        
        }
    }
                
}
