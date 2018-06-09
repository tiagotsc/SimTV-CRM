<?php
#error_reporting(0);
if(!defined('BASEPATH')) exit('No direct script access allowed');
#date_default_timezone_set('America/Sao_Paulo');
#setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
/**
* Classe responsável pela categoria
*/
class Categoria extends CI_Controller
{
    
	/**
	 * Categoria::__construct()
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
        $this->load->model('guiche_model','guiche');
        $this->load->model('categoria_model','categoria');
        $this->load->model('tipo_atendimento_model','tipo_atendimento');
        $this->load->helper('url');
        $this->load->library('pagination');
		$this->load->helper('form');
        $this->load->library('table');
    }
    
    
    /**
     * Categoria::categorias()
     * 
     * @return
     */
    public function categorias(){
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
        
	    #$dados['valores'] = $this->relatorio->arquivoRetornoDiario();
        #$dados['campos'] = ($dados['valores'][0]);
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        $this->layout->region('corpo', 'admin/categoria/view_psq_categoria');
        #$this->layout->region('corpo', 'view_conteudo');
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Categoria::ficha()
     * 
     * Exibe a ficha para cadastro e atualização do categoria
     * 
     * @param bool $cd Cd do categoria que quando informado carrega os dados do categoria
     * @return
     */
    public function ficha($cd = false){
        
        /*error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE); */       
        
        if($cd){
            
            $dados = $this->categoria->dadosCategoria($cd);
            
            $campos = array_keys($dados);
            
            foreach($campos as $campo){
			 
                #Data
                #if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $dados[$campo])){
                    #$dados[$campo] = $this->util->formataData($dados[$campo],'BR');
                #}
             
				$info[$campo] = $dados[$campo]; # ALIMENTA OS CAMPOS COM OS DADOS
			}
            
            $info['tiposAtendDisp'] = $this->categoria->tiposAtendDisp($cd);
            $info['tiposAtendAssoc'] = $this->categoria->tiposAtendAssoc($cd);
            
        }else{
            
            $dados = array();
            
            $campos = $this->categoria->camposCategoria();
            
            foreach($campos as $campo){
                $info[$campo] = '';
            }
            
            $info['tiposAtendDisp'] = false;
            $info['tiposAtendAssoc'] = false;
        
        }
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
        
        $info['tipo_atendimentos'] = $this->tipo_atendimento->tipos_atendimentos();
    
   	 #$dados['valores'] = $this->relatorio->arquivoRetornoDiario();
        #$dados['campos'] = ($dados['valores'][0]);
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        
        if(in_array(18, $this->session->userdata('permissoes'))){
        
            $this->layout->region('corpo', 'admin/categoria/view_frm_categoria', $info);
        
        }else{
            
            $this->layout->region('corpo', 'view_permissao');
            
        }
        
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Categoria::salvar()
     * 
     * Cadastra ou atualiza o categoria
     * 
     * @return
     */
    public function salvar(){
        
        array_pop($_POST);
        
        if($this->input->post('cd_categoria')){
            
            try{
            
                $status = $this->categoria->atualiza();
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
        }else{
            
            try{
            
                $status = $this->categoria->insere();
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
            $_POST['cd_categoria'] = $status;
        }
        
        if($status){
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>categoria salvo com sucesso!</strong></div>');
            
            redirect(base_url('categoria/ficha/'.$this->input->post('cd_categoria'))); 
            
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao salvar categoria, caso o erro persiste comunique o administrador!</div>');
            redirect(base_url('categoria/ficha'));
            
        }
        
    }
    
    /**
     * Categoria::pesquisar()
     * 
     * Pesquisa o categoria
     * 
     * @param mixed $nome Nome do categoria para pesquisa
     * @param mixed $pagina Página corrente
     * @return
     */
    public function pesquisar($nome = null, $status = null, $pagina = null){
        
        $nome = ($nome == null)? '0': $nome;
        $status = ($status == null)? '0': $status;
        
        $this->load->library('pagination');
        
        $dados['pesquisa'] = 'sim';
        $dados['postNome'] = ($this->input->post('nome_categoria') != '')? $this->input->post('nome_categoria') : $nome;
        $dados['postStatus'] = ($this->input->post('status_categoria') != '')? $this->input->post('status_categoria') : $status;
        
        $mostra_por_pagina = 30;
        $dados['categorias'] = $this->categoria->psqCategorias($dados['postNome'], $dados['postStatus'], $pagina, $mostra_por_pagina);   
        $dados['qtdCategorias'] = $this->categoria->psqQtdCategorias($dados['postNome'], $dados['postStatus']);                     
        
        $config['base_url'] = base_url('categoria/pesquisar/'.$dados['postNome'].'/'.$dados['postStatus']); 
		$config['total_rows'] = $dados['qtdCategorias'][0]->total;
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
        $this->layout->region('corpo', 'admin/categoria/view_psq_categoria', $dados);
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Categoria::apaga()
     * 
     * Apaga o categoria
     * 
     * @return
     */
    public function apaga(){
        
        try{
        
            $status = $this->categoria->deleteCategoria();  
        
        }catch( Exception $e ){
            
            log_message('error', $e->getMessage());
            
        }
        
        if($status){
        
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Categoria apagado com sucesso!</strong></div>');
            redirect(base_url('categoria/categorias'));      
        
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao apagar categoria, caso o erro persiste comunique o administrador!</div>');
            redirect(base_url('categoria/categorias'));
        
        }
    }
                
}
