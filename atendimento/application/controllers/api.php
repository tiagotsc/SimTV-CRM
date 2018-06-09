<?php
error_reporting(0);
if(!defined('BASEPATH')) exit('No direct script access allowed');
#date_default_timezone_set('America/Sao_Paulo');
#setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
/**
* Classe criada para controlar todas as buscas sicronas (Sem refresh)
*/
class Api extends CI_Controller
{
    
	/**
	 * Api::__construct()
	 * 
	 * @return
	 */
	public function __construct(){
		parent::__construct();
        
        #$this->load->helper('url');
		#$this->load->helper('form');
    }
    
    
    public function locais(){
        
        $this->load->model('local_model','local');
        $resDados['dados'] = $this->local->locaisPainel();
		$this->load->view('view_json',$resDados);
        
    }
    
    public function habilitaTelaRegistro(){
        
        $this->load->model('dadosBanco_model','dadosBanco');
        $this->load->model('usuario_model','usuario');
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
        
        if(empty($usuario) and empty($senha)){ // Se login e senha estiverem em branco
            // Logue novamente
            $resDados['dados'] = array('status' => 1);
            $this->load->view('view_json',$resDados);
           
        }else{
        
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
                    // Usuário inexistente
                    $resDados['dados'] = array('status' => 2);
                    $this->load->view('view_json',$resDados);
                    
                    
                }else{
                    
                    $timezone = $this->dadosBanco->timezoneLocal($this->input->post('cd_local'));
                    
                    $resDados['dados'] = array('status' => 3, 'timezone' => $timezone[0]->timezone_municipio, 'impressora' => $timezone[0]->impressora_local, 'cd_local' => $timezone[0]->cd_local);
                    $this->load->view('view_json',$resDados);
                
                }
                
            }else{
                
                // Usuário inexistente
                $resDados['dados'] = array('status' => 2);
                $this->load->view('view_json',$resDados);
                
                
            }
        }
    }
    
    public function geraSenha(){
        
        if(empty($_POST)){
            
            $resDados['dados'] = array('Erro' => 'Par&acirc;metros n&atilde;o definidos.');
           $this->load->view('view_json',$resDados); 
           
        }else{
        
            $this->load->model('atendimento_model','atendimento');
            
            $resDados['dados'] = array('senha' => $this->atendimento->geraSenha());
            
            $this->load->view('view_json',$resDados);
            
        }
        
    }
                
}
