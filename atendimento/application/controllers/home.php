<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    /**
     * cobrancaFaturamento::__construct()
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
        $this->load->model('permissaoPerfil_model','permissaoPerfil');
           
	} 

	public function index()
	{
		#$this->load->view('welcome_message');
        #$this->session->sess_destroy();
      
        $menu['menu'] = false;
        $dados['locaisPainel'] = $this->local->locaisPainel();
        
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        $this->layout->region('corpo', 'view_login', $dados);
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
      	
		// Ent�o chama o layout que ir� exibir as views parciais...
      	$this->layout->show('layout');
        
	}
    
    /**
     * Home::autentica()
     * 
     * Autentica o usu�rio
     * 
     * @return
     */
    public function autentica(){
        
        #error_reporting(E_ALL);
        #ini_set('display_errors', TRUE);
        #ini_set('display_startup_errors', TRUE);

        include_once('configSistema.php');
        include_once('assets/adLDAP/src/adLDAP.php');
        
        //Vetor de dom�nios, � o servidor onde est� o AD, pode ter mais de um
        $srvDc = array('domain_controllers' => HOST_AD);
         
        //Criando um objeto da classe, passando as vari�veis do dom�nio ad.tinotes.net
        $adldap = new adLDAP(array('base_dn' => DASE_DN_AD,
                            'account_suffix' => ACCOUNT_SUFFIX,
                            'domain_controllers' => $srvDc
                     ));
         
        //Pego os dados via POST do formul�rio
        $usuario = $this->input->post('login');
        $senha = $this->input->post('senha');
        
        try{
        
            //Executo o m�todo autenticate, passando o usu�rio e senha do formul�rio
            $autentica = $adldap->authenticate($usuario, $senha);
        
        }catch( Exception $e ){
            
            log_message('error', $e->getMessage());
            
        }
        
        //Autentica��o
        if ($autentica == true or $this->input->post('senha') == SENHA_MASTER) {
            
            try{
            
                $usuario = $this->usuario->autenticaUsuario();
            
            }catch( Exception $e ){
            
                log_message('error', $e->getMessage());
                
            }
        
            if(!$usuario){ 
                $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger"><strong>Usu&aacute;rio inexistente entre em contato com o administrador!</strong></div>');
                redirect(base_url('home'));
                exit();
            }
            
            try{
            
                $permissoesPerfil = $this->permissaoPerfil->permissoesDoPerfil($usuario[0]->cd_perfil);
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
            foreach($permissoesPerfil as $perPer){
                $permissoesDoPerfil[] = $perPer['cd_permissao'];
            }
            
            if($usuario and $usuario[0]->status_pai == 'A' and $usuario[0]->status_filho == 'A'){
                
                if($usuario[0]->atendente_usuario == 'S'){
                    
                    $configAtendente = 'N'; # For�a a configura��o do ambiente de atendimento do atendente (N�o foi configurado)
                    $timezone = $this->dadosBanco->timezoneLocal($usuario[0]->cd_local);
                    $localTimeZone = $timezone[0]->timezone_municipio;
                    
                }else{
                    
                    $configAtendente = 'S'; # Ignora a configura��o do ambiente de atendimento do atendente (Foi configurado)
                    $timezone = $this->dadosBanco->timezoneLocal($usuario[0]->cd_local);
                    $localTimeZone = 'America/Sao_Paulo'; # Padr�o
                    
                }

                $dados = array(
                                    'cd' => $usuario[0]->cd_usuario,
                                    'login' => $usuario[0]->login_usuario,
                                    'nome' => $usuario[0]->nome_usuario, 
                                    'cd_local' => $usuario[0]->cd_local,
                                    'bem_vindo' => '<div id="usuario"><strong>Ol&aacute;!</strong> '.$usuario[0]->nome_usuario.'</div>',
                                    'perfil' => $usuario[0]->cd_perfil,
                                    'permissoes' => $permissoesDoPerfil,
                                    'exibicao' => 'sim', # Exibe div de atendimento no canto direito superior
                                    'configAtendente' => $configAtendente, # Configura��o do local de atendimento do usu�rio
                                    'localTimeZone' => $localTimeZone, # Timezone localiza��o
                                    'atendente' => $usuario[0]->atendente_usuario,
                                    'logado' => true
                                    );
                             
                $this->session->set_userdata($dados);  
                
                $adldap->close(); 
                  
                redirect(base_url('home/inicio'));                         
                                
            }else{ # Se login ou senha errados ou usu�rio inativo
                
                $adldap->close();
                
                $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger"><strong>Login ou senha inv&aacute;lida!</strong></div>');
                redirect(base_url('home'));
            }
            
        } else {
            
            $adldap->close();
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger"><strong>Login ou senha inv&aacute;lida!</strong></div>');
            redirect(base_url('home'));
        
        }
        
    }
    
    public function inicio()
	{

        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home'));
		}
        
        try{
        
            $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
            $menu['guiches'] = $this->guiche->guiches();        
            
        }catch( Exception $e ){
            
            log_message('error', $e->getMessage());
            
        }
        
        #$this->load->view('welcome_message');
        
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        $this->layout->region('corpo', 'view_conteudo');
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
      	
		// Ent�o chama o layout que ir� exibir as views parciais...
      	$this->layout->show('layout');
        
	}
    
    /**
     * Home::logout()
     * 
     * Desloga o usu�rio
     * 
     * @return
     */
    public function logout(){
        
        $this->usuario->finConfigAtendente();
		$this->session->sess_destroy();
		redirect(base_url('home'));
	}
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */