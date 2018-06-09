<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Painel extends CI_Controller {

    /**
     * Painel::__construct()
     * 
     * @return
     */
    public function __construct(){
        
        #include_once('configSistema.php');
		parent::__construct();
        
        $this->load->helper('url');
		$this->load->helper('form');
        #$this->load->helper('file');
        $this->load->library('Util', '', 'util');        
		#$this->load->library('table');
        #this->load->library('pagination');
        $this->load->model('local_model','local');
        $this->load->model('dadosBanco_model','dadosBanco');
        $this->load->model('usuario_model','usuario');
        $this->load->model('guiche_model','guiche');
        $this->load->model('atendimento_model','atendimento');
           
	} 
    
    /**
     * Painel::painelRegistro()
     * 
     * Abre a tela de registro de senha
     * 
     * @return
     */
    public function painelRegistro(){
        
        include_once('configSistema.php');
        include_once('assets/adLDAP/src/adLDAP.php');
        
        //Vetor de domínios, é o servidor onde está o AD, pode ter mais de um
        $srvDc = array('domain_controllers' => HOST_AD);
         
        //Criando um objeto da classe, passando as variáveis do domínio ad.tinotes.net
        $adldap = new adLDAP(array('base_dn' => DASE_DN_AD,
                            'account_suffix' => ACCOUNT_SUFFIX,
                            'domain_controllers' => $srvDc
                     ));
         
        //Pego os dados via POST do formulário
        $usuario = $this->input->post('login');
        $senha = $this->input->post('senha');
        
        if(empty($usuario) and empty($senha)){
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger"><strong>logue novamente!</strong></div>');
            redirect(base_url());
                exit();
        }
        
        try{
        
            //Executo o método autenticate, passando o usuário e senha do formulário
            $autentica = $adldap->authenticate($usuario, $senha);
        
        }catch( Exception $e ){
            
            log_message('error', $e->getMessage());
            
        }
        
        //Autenticação
        if ($autentica == true or $this->input->post('senha') == SENHA_MASTER) {
            
            try{
            
                $usuario = $this->usuario->autenticaUsuario();
            
            }catch( Exception $e ){
            
                log_message('error', $e->getMessage());
                
            }
        
            if(!$usuario){
                $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger"><strong>Usu&aacute;rio inexistente entre em contato com o administrador!</strong></div>');
                redirect(base_url());
                exit();
            }
            
            $adldap->close();
            
            $timezone = $this->dadosBanco->timezoneLocal($this->input->post('cd_local'));
            
            $dados['timezone'] = $timezone[0]->timezone_municipio;
            
            $this->load->view('view_painel_registro', $dados);
            
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger"><strong>Usu&aacute;rio inexistente entre em contato com o administrador!</strong></div>');
            redirect(base_url());
            exit();
            
        }
        
        
    }
    
    /**
     * Painel::painelExibicao()
     * 
     * Abre a tela de exibição de senha
     * 
     * @return
     */
    public function painelExibicao(){
        
        include_once('configSistema.php');
        include_once('assets/adLDAP/src/adLDAP.php');
        
        //Vetor de domínios, é o servidor onde está o AD, pode ter mais de um
        $srvDc = array('domain_controllers' => HOST_AD);
         
        //Criando um objeto da classe, passando as variáveis do domínio ad.tinotes.net
        $adldap = new adLDAP(array('base_dn' => DASE_DN_AD,
                            'account_suffix' => ACCOUNT_SUFFIX,
                            'domain_controllers' => $srvDc
                     ));
         
        //Pego os dados via POST do formulário
        $usuario = $this->input->post('login');
        $senha = $this->input->post('senha');
        
        if(empty($usuario) and empty($senha)){
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger"><strong>logue novamente!</strong></div>');
            redirect(base_url());
                exit();
        }
        
        try{
        
            //Executo o método autenticate, passando o usuário e senha do formulário
            $autentica = $adldap->authenticate($usuario, $senha);
        
        }catch( Exception $e ){
            
            log_message('error', $e->getMessage());
            
        }
        
        //Autenticação
        if ($autentica == true or $this->input->post('senha') == SENHA_MASTER) {
            
            try{
            
                $usuario = $this->usuario->autenticaUsuario();
            
            }catch( Exception $e ){
            
                log_message('error', $e->getMessage());
                
            }
        
            if(!$usuario){
                $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger"><strong>Usu&aacute;rio inexistente entre em contato com o administrador!</strong></div>');
                redirect(base_url());
                exit();
            }
            
            $adldap->close();
            
            $timezone = $this->dadosBanco->timezoneLocal($this->input->post('cd_local'));
            
            $dados['timezone'] = $timezone[0]->timezone_municipio;
            
            $this->load->view('view_painel_exibicao', $dados);
            
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger"><strong>Usu&aacute;rio inexistente entre em contato com o administrador!</strong></div>');
            redirect(base_url());
            exit();
            
        }
        
    }
    
    /**
     * Painel::consultaSenha()
     * 
     * Consulta determinada senha
     * 
     * @return
     */
    public function consultaSenha(){
        
        $res = $this->atendimento->consultaSenha();

        $senha = (trim($res[0]->nome_atendimento) <> '')? $res[0]->nome_atendimento : $res[0]->senha_atendimento;
        
        $resDados['dados'] = array('senha' => $senha, 'guiche' => $res[0]->nome_guiche);
        $this->load->view('view_json',$resDados);
        
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */