<?php
error_reporting(0);
if(!defined('BASEPATH')) exit('No direct script access allowed');
#date_default_timezone_set('America/Sao_Paulo');
#setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
/**
* Classe criada para controlar todas as buscas sicronas (Sem refresh)
*/
class Ajax extends CI_Controller
{
    
	/**
	 * Ajax::__construct()
	 * 
	 * @return
	 */
	public function __construct(){
		parent::__construct();
        
        #$this->load->helper('url');
		#$this->load->helper('form');
    }
    
    
    /**
	 * Ajax::exibicaoDivAtendimento()
	 * 
     * Alimenta a session que exibe ou não a div de atendimento
     * 
	 */
    public function exibicaoDivAtendimento(){
        
        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home'));
		}
        
        $exibicao = $this->input->post('var_exibicao');

        $this->session->set_userdata('exibicao', $exibicao);

    }
    
    /**
	 * Ajax::atualizaGrupoResolvedores()
	 * 
     * Atualiza os grupos de resolvedores de acordo com os motivos / problemas informados
     * 
	 */
    public function atualizaGrupoResolvedores($tipo){
        
        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home'));
		}
        
        $this->load->model('grupo_resolucao_model','grupo_resolucao');
        
        if($tipo == 'motivo'){ # Condição utilizada na tela de edição de motivo
            
            foreach($_POST['motivos'] as $motivos){
            
                $extracao[] = str_replace('idMotivo_','',$motivos);
                
            }
            
            try{
            
                $this->grupo_resolucao->associaMotivosAoGrupo($extracao, 'motivo');
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
            echo '<div class="alert alert-success" role="alert"><strong>Associa&ccedil;&atilde;o do motivo / problema atualizado.</strong></div>';
            
        }elseif($tipo == 'grupo'){ # Condição utilizada na tela de edição de grupos
            
            foreach($_POST['grupos'] as $grupos){
            
                $extracao[] = str_replace('idGrupo_','',$grupos);
                
            }
            
            try{
            
                $this->grupo_resolucao->associaMotivosAoGrupo($extracao, 'grupo');
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
            echo '<div class="alert alert-success" role="alert"><strong>Associa&ccedil;&atilde;o do grupo atualizada.</strong></div>';
            
        }
        
    }
    
    /**
	 * Ajax::associacaoGruposUsuario()
	 * 
     * Associa o grupo ao usuário
     * 
	 */
    public function associacaoGruposUsuario(){
        
        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home'));
		}
        
        $this->load->model('grupo_resolucao_model','grupo_resolucao');
        
        foreach($_POST['grupos'] as $grupos){
        
            $extracao[] = str_replace('idGrupo_','',$grupos);
            
        }
        
        try{
        
            $this->grupo_resolucao->associaGruposAoUsuario($extracao);
            
        }catch( Exception $e ){
                
            log_message('error', $e->getMessage());
            
        }
        
        echo '<div class="alert alert-success" role="alert"><strong>Associa&ccedil;&atilde;o do grupo atualizada.</strong></div>';
        
    }
    
    /**
	 * Ajax::atualizaGrupoUsuarios()
	 * 
     * Associa usuário ao grupo
     * 
	 */
    public function atualizaGrupoUsuarios(){
        
        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home'));
		}
        
        $this->load->model('grupo_resolucao_model','grupo_resolucao');
        
        foreach($_POST['usuarios'] as $usuarios){
        
            $extracao[] = str_replace('idUsuario_','',$usuarios);
            
        }
        
        try{
        
            $this->grupo_resolucao->associaUsuariosAoGrupo($extracao);
            
        }catch( Exception $e ){
                
            log_message('error', $e->getMessage());
            
        }
        
        echo '<div class="alert alert-success" role="alert"><strong>Associa&ccedil;&atilde;o do grupo atualizada.</strong></div>';

    }
    
    /**
	 * Ajax::verificaGuiche()
	 * 
     * Verifica se o guichê esta disponível
     * 
	 */
    public function verificaGuiche(){
        
        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home'));
		}
        
        $this->load->model('usuario_model','usuario');
        $resDados['dados'] = $this->usuario->verificaGuiche();
		$this->load->view('view_json',$resDados);
        
    }
    
    /**
	 * Ajax::registraSenha()
	 * 
     * Registra a senha (Dá entrada)
     * 
	 */
    public function registraSenha(){
        
        $this->load->model('atendimento_model','atendimento');
        
        try{
        
            $resDados['dados'] = array('total' => $this->atendimento->registraSenha());
        
        }catch( Exception $e ){
                
            log_message('error', $e->getMessage());
            
        }
        
        $this->load->view('view_json',$resDados);
        
    }
    
    /**
	 * Ajax::carregaCategoria()
	 * 
     * Carrega a categoria
     * 
	 */
    public function carregaCategoria(){
        
        $this->load->model('categoria_model','categoria');
        
        $resDados['dados'] = $this->categoria->categoriasAssTipoAtend();
        $this->load->view('view_json',$resDados);
        
    }
    
    /**
	 * Ajax::carregaMotivo()
	 * 
     * Carrega o motivo
     * 
	 */
    public function carregaMotivo(){
        
        $this->load->model('motivo_model','motivo');
        
        $resDados['dados'] = $this->motivo->todosMotivos();
        $this->load->view('view_json',$resDados);
        
    }
    
    /**
	 * Ajax::pegaPrazoMotivo()
	 * 
     * Pega o prazo do motivo
     * 
	 */
    public function pegaPrazoMotivo(){
        
        $this->load->model('motivo_model','motivo');
        
        $resDados['dados'] = $this->motivo->pegaPrazo();
        $this->load->view('view_json',$resDados);
        
    }
    
    /**
	 * Ajax::usuariosDepartamento()
	 * 
     * Usuários disponíveis para adicionar ao grupo
     * 
	 */
    public function usuariosDepartamento(){
        
        $this->load->model('grupo_resolucao_model','grupo_resolucao');
        
        $resDados['dados'] = $this->grupo_resolucao->usuariosDisponiveis();
        
        $this->load->view('view_json',$resDados);
        
    }
    
    /**
	 * Ajax::pegaAtendentesOnline()
	 * 
     * Pega os atendentes online e offline
     * 
	 */
    public function pegaAtendentesOnline(){
        
        $this->load->model('dashboard_model','dashboard');
        $dados['dados'] = $this->dashboard->atendentesOnline();
        $this->load->view('view_json',$dados);
        
    }
    
    /**
	 * Ajax::geraSenha()
	 * 
     * Gera e imprime a senha do atendimento
     * 
	 */
    public function geraSenha(){
        
        $this->load->library('Util', '', 'util');
        $this->load->model('atendimento_model','atendimento');
        
        $senha = $this->atendimento->geraSenha();
        
        if($senha){
            
            // Pega a impressora via
            #$this->load->model('local_model','local');
            #$impressora = $this->local->impressora();
            
            try{
                
                // Imprime via linux
                #shell_exec('echo "###### SIM TV ######\n\nSENHA N. '.$senha.'\n\nAGUARDE O ATENDIMENTO" | lpr -P '.$impressora);
                #shell_exec('echo teste | lpr -P BOT_TI');
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
        }
        
        $resDados['dados'] = array('senha' => $senha);
        
        $this->load->view('view_json',$resDados);
    
    }
    
    /**
	 * Ajax::parametrosRelatorio()
	 * 
     * Pega os parâmetros do relatório para consulta
     * 
     * @param $cd_relatorio Cd do relatório
     * 
	 */
    public function parametrosRelatorio($cd_relatorio){
        
        $this->load->model('Relatorio_model','relatorio'); 
        $resDados['dados'] = $this->relatorio->parametrosDoRelatorio($cd_relatorio);
        /*
        $resDados['dados'] = Array(
                                array(
                                    "nome"=>"João",
                                    "sobreNome"=>"Silva",
                                    "cidade"=>"Maringá"
                                ),
                                array(
                                    "nome"=>"Ana",
                                    "sobreNome"=>"Rocha",
                                    "cidade"=>"Londrina"
                                ),
                                array(
                                    "nome"=>"Véra",
                                    "sobreNome"=>"Valério",
                                    "cidade"=>"Cianorte"
                                ));
         */                       
        $this->load->view('view_json',$resDados);                        
        
    }
    
    public function imprimeSenha(){
        
        $this->load->view('view_impressao');
        
    }
    
    public function atualizaTiposAtendCategoria(){
        
        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home'));
		}
        
        $this->load->model('categoria_model','categoria');
            
        foreach($_POST['tipos'] as $tipos){
        
            $extracao[] = str_replace('idTipoAtend_','',$tipos);
            
        }
        
        try{
        
            $this->categoria->associaTipoAtendAcateg($extracao);
        
        }catch( Exception $e ){
            
            log_message('error', $e->getMessage());
            
        }
        
        echo '<div class="alert alert-success" role="alert"><strong>Associa&ccedil;&atilde;o do tipo atendimento atualizado.</strong></div>';
        
        
    }
    
    public function pesquisaClienteAtp(){
        
        $this->load->library('Util', '', 'util');
        $this->load->model('atendimento_model','atendimento');
        
        $cliente = $this->atendimento->pegaClienteAtp();
        
        #$array[0] = array('value' => 'paulo');
        #$array[1] = array('value' => 'ricardo');
        #$array[2] = array('value' => 'diego');
        
        $resDados['dados'] = $cliente;
		$this->load->view('view_json',$resDados);
        
    }
    
    public function qtdAtendPorPrazo(){
        
        $this->load->model('dashboard_model','dashboard');
        
        $resultado = $this->dashboard->qtdAtendPrazo();
        
        $res[] = array('local'=>$resultado[0]->local, 'Qtd. no prazo'=>$resultado[0]->qtd_no_prazo, 'Qtd. fora prazo'=>$resultado[0]->qtd_fora_prazo);
        $resDados['dados'] = $res;
        
		$this->load->view('view_json',$resDados);
        
    }
    
    public function tempoMedio(){
        
        $this->load->model('dashboard_model','dashboard');
        
        $resultado = $this->dashboard->tempoMedioAtendimento();
        
        foreach($resultado as $res){
            
            if($res->tempo_medio_espera <> null){
                $mediaEspera = str_replace(':','.',$res->tempo_medio_espera);
            }else{
                $mediaEspera = 0;
            }
            
            $todosMediaEspera[] = $mediaEspera;
            
            if($res->tempo_medio_atendimento <> null){
                $mediaAtendimento = str_replace(':','.',$res->tempo_medio_atendimento);
            }else{
                $mediaAtendimento = 0;
            }
            
            $todosMediaAtendimento[] = $mediaAtendimento;
            
            $locais[] = $res->local;
            
        }
        
        $pronto[] = array('local'=>$locais, 'Média espera'=>$todosMediaEspera, 'Média atendimento'=>$todosMediaAtendimento);
        $resDados['dados'] = $pronto;
        
		$this->load->view('view_json',$resDados); 
    }
    
    public function qtdAtendenteOnline(){
        
        $this->load->model('dashboard_model','dashboard');
        
        $resultado = $this->dashboard->qtdAtendentesOnline();
        
        $pronto[] = array('Qtd. atendentes Online'=>$resultado[0]->atendente_online, 'Qtd. atendentes Offline' => $resultado[0]->atendente_offline);
        $resDados['dados'] = $pronto;
        
		$this->load->view('view_json',$resDados); 
    }
                
}
