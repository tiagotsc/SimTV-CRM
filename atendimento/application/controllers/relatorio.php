<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class relatorio extends CI_Controller {
     
    /**
     * relatorio::__construct()
     * 
     * Classe resposável por processar os relatórios
     * 
     * @return
     */
    public function __construct(){
        
		parent::__construct();
        
        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home'));
		}
        
        # Configurações do sistema
        include_once('configSistema.php');
        
        $this->load->library('Util', '', 'util'); 
		$this->load->helper('url');
        $this->load->library('table');
        $this->load->helper('text');
		$this->load->helper('form');
        $this->load->model('Relatorio_model','relatorio'); 
        $this->load->model('DadosBanco_model','dadosBanco');
        $this->load->model('permissaoPerfil_model','permissaoPerfil');
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        
        $this->layout->region('menu', 'view_menu', $menu);
           
	} 
     
	/**
     * relatorio::index()
     * 
     * Lista os relatórios existentes
     * 
     */
	public function index()
	{ 
	   
       $departamentos = $this->relatorio->departamentosRelatorios();
       
       foreach($departamentos as $depar){
            $relatorios[$depar->cd_departamento] = $this->relatorio->relatoriosCategorias($depar->cd_departamento, $this->session->userdata('permissoes'));
       }
       
       $dados['departamentos'] = $departamentos;
       $dados['relatorios'] = $relatorios;
       
       #$menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
       
	    #$dados['valores'] = $this->relatorio->arquivoRetornoDiario();
        #$dados['campos'] = ($dados['valores'][0]);
        $this->layout->region('html_header', 'view_html_header');
      	#$this->layout->region('menu', 'view_menu', $menu);
        ##$this->layout->region('menu_lateral', 'view_menu_lateral');
        $this->layout->region('corpo', 'admin/relatorio/view_relatorio',$dados);
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
	}
    
    public function gerenciar(){
        
        #$menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        
        $dados['departamento'] = $this->relatorio->departamentosRelatorios();
        
	    #$dados['valores'] = $this->relatorio->arquivoRetornoDiario();
        #$dados['campos'] = ($dados['valores'][0]);
        $this->layout->region('html_header', 'view_html_header');
      	#$this->layout->region('menu', 'view_menu', $menu);
        #$this->layout->region('menu_lateral', 'view_menu_lateral');
        $this->layout->region('corpo', 'admin/relatorio/view_psq_relatorio', $dados);
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    public function ficha($cd = false){
        
        if($cd){
            
            $pegaPerfisRelatorio = $this->relatorio->perfilRelatorio($cd);
            
            foreach($pegaPerfisRelatorio as $pPr){
                
                $perfilRelatorio[] = $pPr->cd_perfil;
                
            }
            
            $parametros = $this->relatorio->relatorio_parametro($cd);
            
            $nomes_parametros = $this->relatorio->parametrosDoRelatorio($cd);
            
            foreach($parametros as $param){
                
                $rel_param[] = $param->cd_parametro;
                
            }
            
            $rel_param[] = 'A';
            $rel_param[] = 'B';
            
            #echo '<pre>'; print_r($parametros); exit();
            $dados = $this->relatorio->dadosRelatorio($cd);
            
            $campos = array_keys($dados);
            
            foreach($campos as $campo){
			 
                #Data
                #if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $dados[$campo])){
                    #$dados[$campo] = $this->util->formataData($dados[$campo],'BR');
                #}
             
				$dados[$campo] = $dados[$campo]; # ALIMENTA OS CAMPOS COM OS DADOS
			}
            
            $dados['rel_param'] = $rel_param;
            
            $dados['nome_parametros'] = $nomes_parametros;
            
        }else{
            
            $perfilRelatorio[] = array();
            
            $campos = $this->relatorio->camposRelatorio();
            
            foreach($campos as $campo){
                $dados[$campo] = '';
            }
        
            $dados['rel_param'] = array('A','B');
            
            $dados['nome_parametros'] = false;
        
        }
        
        #$menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
       
        $dados['departamento'] = $this->dadosBanco->departamento();
        $dados['parametros'] = $this->dadosBanco->parametro();
        
        $dados['perfis'] = $this->permissaoPerfil->perfil();
        
        $dados['perfilRelatorio'] = $perfilRelatorio;
        
	    #$dados['valores'] = $this->relatorio->arquivoRetornoDiario();
        #$dados['campos'] = ($dados['valores'][0]);
        $this->layout->region('html_header', 'view_html_header');
      	#$this->layout->region('menu', 'view_menu', $menu);
        #$this->layout->region('menu_lateral', 'view_menu_lateral');
        
        if(in_array(34, $this->session->userdata('permissoes'))){
        
            $this->layout->region('corpo', 'admin/relatorio/view_frm_relatorio', $dados);
        
        }else{
            
            $this->layout->region('corpo', 'view_permissao');   
            
        }
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    public function salvar(){
        
        array_pop($_POST);
        
        if($this->input->post('cd_relatorio')){
            
            try{
            
                $status = $this->relatorio->atualiza();
            
            }catch( Exception $e ){
            
                log_message('error', $e->getMessage());
                
            }
            
        }else{
            
            try{
            
                $status = $this->relatorio->insere();
            
            }catch( Exception $e ){
            
                log_message('error', $e->getMessage());
                
            }
            
            $_POST['cd_relatorio'] = $status;
        }
        
        if($status){
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Relat&oacute;rio salvo com sucesso!</strong></div>');
            
            redirect(base_url('relatorio/ficha/'.$this->input->post('cd_relatorio'))); 
            
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao salvar relat&oacute;rio, caso o erro persiste comunique o administrador!</div>');
            redirect(base_url('relatorio/ficha'));
            
        }
        
    }
    
    /**
     * Relatorio::pesquisar()
     * 
     * Pesquisa o relatório
     * 
     * @param mixed $nome Nome do relatório para pesquisa
     * @param mixed $pagina Página corrente
     * @return
     */
    public function pesquisar($nome = null, $departamento = null, $status = null, $pagina = null){
        
        $nome = ($nome == null)? '0': $nome;
        $status = ($status == null)? '0': $status;
        $departamento = ($departamento == null)? '0': $departamento;
        
        $this->load->library('pagination');
        
        $dados['pesquisa'] = 'sim';
        $dados['postNome'] = ($this->input->post('nome_relatorio') != '')? $this->input->post('nome_relatorio') : $nome;
        $dados['postStatus'] = ($this->input->post('status_relatorio') != '')? $this->input->post('status_relatorio') : $status;
        $dados['postDepartamento'] = ($this->input->post('cd_departamento') != '')? $this->input->post('cd_departamento') : $departamento;
        
        $mostra_por_pagina = 30;
        $dados['relatorios'] = $this->relatorio->psqRelatorios($dados['postNome'], $dados['postDepartamento'], $dados['postStatus'], $pagina, $mostra_por_pagina);   
        $dados['qtdRelatorios'] = $this->relatorio->psqQtdRelatorios($dados['postNome'], $dados['postDepartamento'], $dados['postStatus']);  
        
        $dados['departamento'] = $this->relatorio->departamentosRelatorios();                   
        
        $config['base_url'] = base_url('relatorio/pesquisar/'.$dados['postNome'].'/'.$dados['postDepartamento'].'/'.$dados['postStatus']); 
		$config['total_rows'] = $dados['qtdRelatorios'][0]->total;
		$config['per_page'] = $mostra_por_pagina;
		$config['uri_segment'] = 6;
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
        $dados['postDepartamento'] = ($dados['postDepartamento'] == '0')? '': $dados['postDepartamento'];
        
        #$menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        
        $this->layout->region('html_header', 'view_html_header');
      	#$this->layout->region('menu', 'view_menu', $menu);
        #$this->layout->region('menu_lateral', 'view_menu_lateral');
        $this->layout->region('corpo', 'admin/relatorio/view_psq_relatorio', $dados);
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Relatório::apaga()
     * 
     * Apaga o relatório
     * 
     * @return
     */
    public function apaga(){
        
        try{
        
            $status = $this->relatorio->deleteRelatorio();  
        
        }catch( Exception $e ){
            
            log_message('error', $e->getMessage());
            
        }
        
        if($status){
        
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Relat&oacute;rio apagado com sucesso!</strong></div>');
            redirect(base_url('relatorio/gerenciar'));      
        
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao apagar Relat&oacute;rio, caso o erro persiste comunique o administrador!</div>');
            redirect(base_url('relatorio/gerenciar'));
        
        }
    }
    
    /**
     * Relatorio::baixarRelatorio()
     * 
     * Gera o excel do relatório solicitado
     * 
     */
    public function baixarRelatorio(){ 
        
        set_time_limit(0);
        
        $this->relatorio->registraAcessoRelatorio();
        
        $queryBancoRelatorio = $this->relatorio->dadosBancoRelatorio();
        
        $query = $queryBancoRelatorio[0]->query_relatorio;
        
        foreach($_POST as $campo => $valor){
            
            $valor = $this->util->formaValorBanco($valor);
            
            $query = str_ireplace('**'.$campo.'**', $valor, $query);
            
        }
        
        try{
            
            $executa = $this->relatorio->rodaQuery($query, $queryBancoRelatorio[0]->banco_relatorio);
            
            
        }catch( Exception $e ){
            
            log_message('error', $e->getMessage());
            
        }
        
        $dados['valores'] = ($executa)? $executa: '';
        $dados['campos'] = ($executa)? array_keys($dados['valores'][0]): '';

        $this->load->view('admin/relatorio/view_baixa_relatorio', $dados);
        
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */