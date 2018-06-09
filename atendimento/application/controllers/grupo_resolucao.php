<?php
#error_reporting(0);
if(!defined('BASEPATH')) exit('No direct script access allowed');
#date_default_timezone_set('America/Sao_Paulo');
#setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
/**
* Classe responsável pela grupo resolução
*/
class Grupo_resolucao extends CI_Controller
{
    
	/**
	 * Grupo_resolucao::__construct()
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
        $this->load->model('motivo_model','motivo');
        $this->load->model('grupo_resolucao_model','grupo_resolucao');
        $this->load->model('guiche_model','guiche');
        $this->load->helper('url');
        $this->load->library('pagination');
		$this->load->helper('form');
        $this->load->library('table');
    }
    
    /**
     * Grupo_resolucao::grupos_resolucoes()
     * 
     * @return
     */
    public function grupos_resolucoes(){
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
	    #$dados['valores'] = $this->relatorio->arquivoRetornoDiario();
        #$dados['campos'] = ($dados['valores'][0]);
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        $this->layout->region('corpo', 'admin/grupo_resolucao/view_psq_grupo_resolucao');
        #$this->layout->region('corpo', 'view_conteudo');
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Grupo_resolucao::ficha()
     * 
     * Exibe a ficha para cadastro e atualização do grupo resolução
     * 
     * @param bool $cd Cd do grupo resolução que quando informado carrega os dados do grupo resolução
     * @return
     */
    public function ficha($cd = false){
        /*
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);        
        */
        if($cd){
            
            $dados = $this->grupo_resolucao->dadosGrupo_resolucao($cd);
            
            $campos = array_keys($dados);
            
            foreach($campos as $campo){
			 
                #Data
                #if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $dados[$campo])){
                    #$dados[$campo] = $this->util->formataData($dados[$campo],'BR');
                #}
             
				$info[$campo] = $dados[$campo]; # ALIMENTA OS CAMPOS COM OS DADOS
			}
            
            $info['motivosGrupo'] = $this->grupo_resolucao->motivosDoGrupo($cd);
        
            foreach($info['motivosGrupo'] as $motivos){
                
                $motivosGrupo[] = $motivos->cd_motivo;
                
            }
            
            $motivosGrupo = implode(',', $motivosGrupo);
            
            $info['departamentos'] = $this->dadosBanco->departamentoAssociados();
            $info['usuariosGrupo'] = $this->grupo_resolucao->usuariosDoGrupo($cd);
            
        }else{
            
            $dados = array();
            
            $campos = $this->grupo_resolucao->camposGrupo_resolucao();
            
            foreach($campos as $campo){
                $info[$campo] = '';
            }
            
            $motivosGrupo = false;
        
        }

        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
        
        $info['todosMotivos'] = $this->motivo->todosMotivos($motivosGrupo, true);

        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        
        if(in_array(30, $this->session->userdata('permissoes'))){
        
            $this->layout->region('corpo', 'admin/grupo_resolucao/view_frm_grupo_resolucao', $info);
        
        }else{
            
            $this->layout->region('corpo', 'view_permissao');
            
        }
        
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Grupo_resolucao::salvar()
     * 
     * Cadastra ou atualiza o grupo resolução
     * 
     * @return
     */
    public function salvar(){
        
        array_pop($_POST);
        
        if($this->input->post('cd_grupo_resolucao')){
            
            try{
            
                $status = $this->grupo_resolucao->atualiza();
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
        }else{
            
            try{
            
                $status = $this->grupo_resolucao->insere();
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
            $_POST['cd_grupo_resolucao'] = $status;
        }
        
        if($status){
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Grupo resolu&ccedil;&atilde;o salvo com sucesso!</strong></div>');
            
            redirect(base_url('grupo_resolucao/ficha/'.$this->input->post('cd_grupo_resolucao'))); 
            
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao salvar grupo resolu&ccedil;&atilde;o, caso o erro persiste comunique o administrador!</div>');
            redirect(base_url('grupo_resolucao/ficha'));
            
        }
        
    }
    
    /**
     * Grupo_resolucao::pesquisar()
     * 
     * Pesquisa o grupo resolução
     * 
     * @param mixed $nome Nome do grupo resolução para pesquisa
     * @param mixed $pagina Página corrente
     * @return
     */
    public function pesquisar($nome = null, $status = null, $pagina = null){
        
        $nome = ($nome == null)? '0': $nome;
        $status = ($status == null)? '0': $status;
        
        $this->load->library('pagination');
        
        $dados['pesquisa'] = 'sim';
        $dados['postNome'] = ($this->input->post('nome_grupo_resolucao') != '')? $this->input->post('nome_grupo_resolucao') : $nome;
        $dados['postStatus'] = ($this->input->post('status_grupo_resolucao') != '')? $this->input->post('status_grupo_resolucao') : $status;
        
        $mostra_por_pagina = 30;
        $dados['grupos_resolucoes'] = $this->grupo_resolucao->psqGrupo_resolucaos($dados['postNome'], $dados['postStatus'], $pagina, $mostra_por_pagina);   
        $dados['qtdGrupos_resolucoes'] = $this->grupo_resolucao->psqQtdGrupo_resolucaos($dados['postNome'], $dados['postStatus']);                     
        
        $config['base_url'] = base_url('grupo_resolucao/pesquisar/'.$dados['postNome'].'/'.$dados['postStatus']); 
		$config['total_rows'] = $dados['qtdGrupos_resolucoes'][0]->total;
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
        $this->layout->region('corpo', 'admin/grupo_resolucao/view_psq_grupo_resolucao', $dados);
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Grupo_resolucao::apaga()
     * 
     * Apaga o grupo resolução
     * 
     * @return
     */
    public function apaga(){
        
        try{
        
            $status = $this->grupo_resolucao->deleteGrupo_resolucao();  
        
        }catch( Exception $e ){
            
            log_message('error', $e->getMessage());
            
        }
        
        if($status){
        
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Grupo resolu&ccedil;&atilde;o apagado com sucesso!</strong></div>');
            redirect(base_url('grupo_resolucao/grupos_resolucoes'));      
        
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao apagar grupo resolu&ccedil;&atilde;o, caso o erro persiste comunique o administrador!</div>');
            redirect(base_url('grupo_resolucao/grupos_resolucoes'));
        
        }
    }
                
}
