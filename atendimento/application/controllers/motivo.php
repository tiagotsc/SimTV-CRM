<?php
#error_reporting(0);
if(!defined('BASEPATH')) exit('No direct script access allowed');
#date_default_timezone_set('America/Sao_Paulo');
#setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
/**
* Classe responsável pela motivo
*/
class Motivo extends CI_Controller
{
    
	/**
	 * Motivo::__construct()
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
        $this->load->model('categoria_model','categoria');
        $this->load->model('motivo_model','motivo');
        $this->load->model('grupo_resolucao_model','grupo_resolucao');
        $this->load->model('tipo_atendimento_model','tipo_atendimento');
        $this->load->model('guiche_model','guiche');
        $this->load->helper('url');
        $this->load->library('pagination');
		$this->load->helper('form');
        $this->load->library('table');
    }
    
    /**
     * Motivo::motivos()
     * 
     * @return
     */
    public function motivos(){
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
       
	    #$dados['valores'] = $this->relatorio->arquivoRetornoDiario();
        #$dados['campos'] = ($dados['valores'][0]);
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        $this->layout->region('corpo', 'admin/motivo/view_psq_motivo');
        #$this->layout->region('corpo', 'view_conteudo');
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Motivo::ficha()
     * 
     * Exibe a ficha para cadastro e atualização do motivo
     * 
     * @param bool $cd Cd do motivo que quando informado carrega os dados do motivo
     * @return
     */
    public function ficha($cd = false){
        
        /*error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE); */       
        
        if($cd){
            
            $dados = $this->motivo->dadosMotivo($cd);
            
            $campos = array_keys($dados);
            
            foreach($campos as $campo){
			 
                #Data
                #if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $dados[$campo])){
                    #$dados[$campo] = $this->util->formataData($dados[$campo],'BR');
                #}
             
				$info[$campo] = $dados[$campo]; # ALIMENTA OS CAMPOS COM OS DADOS
			}
            
            $info['gruposPossuiMotivo'] = $this->grupo_resolucao->gruposPossuiMotivo($cd);
            
            foreach($info['gruposPossuiMotivo'] as $gPm){
                
                $extracaoGrupo[] = $gPm->cd_grupo_resolucao;
                
            }
            
            $grupos = implode(',', $extracaoGrupo);
            
        }else{
            
            $dados = array();
            
            $campos = $this->motivo->camposMotivo();
            
            foreach($campos as $campo){
                $info[$campo] = '';
            }
            
            $info['grupoUsuario'] = array();
            $info['gruposPossuiMotivo'] = false;
            
            $grupos = false;
            
        }
        #echo '<pre>'; print_r($campos); exit();
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
        $info['departamento'] = $this->dadosBanco->departamento();
        $info['tipo_atendimento'] = $this->tipo_atendimento->tipos_atendimentos();
        $info['categoria'] = $this->categoria->todasCategorias();
        
        $info['todosGrupos'] = $this->grupo_resolucao->todosGruposDisponiveis($cd);
        
   	 #$dados['valores'] = $this->relatorio->arquivoRetornoDiario();
        #$dados['campos'] = ($dados['valores'][0]);
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        
        if(in_array(15, $this->session->userdata('permissoes'))){
        
            $this->layout->region('corpo', 'admin/motivo/view_frm_motivo', $info);
        
        }else{
            
            $this->layout->region('corpo', 'view_permissao');
            
        }
        
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Motivo::salvar()
     * 
     * Cadastra ou atualiza o motivo
     * 
     * @return
     */
    public function salvar(){
        
        array_pop($_POST);
        
        if($this->input->post('cd_motivo')){
            
            try{
            
                $status = $this->motivo->atualiza();
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
        }else{
            
            try{
            
                $status = $this->motivo->insere();
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
            $_POST['cd_motivo'] = $status;
        }
        
        if($status){
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Motivo salvo com sucesso!</strong></div>');
            
            redirect(base_url('motivo/ficha/'.$this->input->post('cd_motivo'))); 
            
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao salvar motivo, caso o erro persiste comunique o administrador!</div>');
            redirect(base_url('motivo/ficha'));
            
        }
        
    }
    
    /**
     * Motivo::pesquisar()
     * 
     * Pesquisa o motivo
     * 
     * @param mixed $nome Nome do motivo para pesquisa
     * @param mixed $pagina Página corrente
     * @return
     */
    public function pesquisar($nome = null, $status = null, $pagina = null){
        
        $nome = ($nome == null)? '0': $nome;
        $status = ($status == null)? '0': $status;
        
        $this->load->library('pagination');
        
        $dados['pesquisa'] = 'sim';
        $dados['postNome'] = ($this->input->post('nome_motivo') != '')? $this->input->post('nome_motivo') : $nome;
        $dados['postStatus'] = ($this->input->post('status_motivo') != '')? $this->input->post('status_motivo') : $status;
        
        $mostra_por_pagina = 30;
        $dados['motivos'] = $this->motivo->psqMotivos($dados['postNome'], $dados['postStatus'], $pagina, $mostra_por_pagina);   
        $dados['qtdMotivos'] = $this->motivo->psqQtdMotivos($dados['postNome'], $dados['postStatus']);                     
        
        $config['base_url'] = base_url('motivo/pesquisar/'.$dados['postNome'].'/'.$dados['postStatus']); 
		$config['total_rows'] = $dados['qtdMotivos'][0]->total;
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
        $this->layout->region('corpo', 'admin/motivo/view_psq_motivo', $dados);
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Motivo::apaga()
     * 
     * Apaga o motivo
     * 
     * @return
     */
    public function apaga(){
        
        try{
        
            $status = $this->motivo->deleteMotivo();  
        
        }catch( Exception $e ){
            
            log_message('error', $e->getMessage());
            
        }
        
        if($status){
        
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Motivo apagado com sucesso!</strong></div>');
            redirect(base_url('motivo/motivos'));      
        
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao apagar motivo, caso o erro persiste comunique o administrador!</div>');
            redirect(base_url('motivo/motivos'));
        
        }
    }
                
}
