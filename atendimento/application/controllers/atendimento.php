<?php
#error_reporting(0);
if(!defined('BASEPATH')) exit('No direct script access allowed');               
#setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
/**
* Classe responsável pela atendimento
*/
class Atendimento extends CI_Controller
{
    
	/**
	 * Atendimento::__construct()
	 * 
	 * @return
	 */
	public function __construct(){
       
		parent::__construct();
        
        #date_default_timezone_set($this->session->userdata('localTimeZone')); # Define a localização 

        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home/'));
		}
        
        $this->load->library('Util', '', 'util');
        $this->load->model('dadosBanco_model','dadosBanco');
        $this->load->model('atendimento_model','atendimento');
        $this->load->model('tipo_atendimento_model','tipo_atendimento');
        $this->load->model('guiche_model','guiche');
        $this->load->model('motivo_model','motivo');
        $this->load->model('categoria_model','categoria');
        $this->load->model('grupo_resolucao_model','grupo_resolucao');
        $this->load->helper('url');
        $this->load->library('pagination');
		$this->load->helper('form');
        $this->load->library('table');
        $this->load->library('email');
        
        /* Se for atendente verifica se ainda esta conectado */
        if($this->session->userdata('atendente') == 'S'){
            
            $this->load->model('usuario_model','usuario');
            
            $verifOnline = $this->usuario->estaOnline();
            #echo $this->session->userdata('localTimeZone'); exit();
            if($verifOnline[0]->online_usuario == 'N'){
            
                $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger"><strong>Desconectado por outro atendente!</strong></div>');
                redirect(base_url('home/logout'));
            
            }
            
        }
        
    }
    
    /**
     * Atendimento::chamaSenha()
     * 
     * Prioriza o atendimento a uma determinada senha, 
     * caso a senha não esteja registrada dá a opção de registrar para logo em seguida atender
     * 
     * @return
     */
    public function chamaSenha(){
        
        if($this->input->post('senha_atendimento') <> ''){
            
            try{
            
                $cdAtendimento = $this->atendimento->senhaSolicitada();
                
                if($cdAtendimento){
                    
                    $iniciaAtendimento = $this->atendimento->iniciaAtendimento($cdAtendimento);
                    
                    if($iniciaAtendimento){
                       $resDados['dados'] = array('status' => 'OK', 'cd' => $cdAtendimento); 
                    }else{
                        $resDados['dados'] = array('status' => 'ERRO', 'cd' => '');
                    }
                    
                    
                }else{
                    $resDados['dados'] = array('status' => false, 'cd' => '');
                }
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
            $this->load->view('view_json',$resDados);
            
        }
        
    }
    
    /**
     * Atendimento::proximaSenha()
     * 
     * Chama a próxima senha caso ela exista
     * 
     * @return
     */
    public function proximaSenha(){
        
        try{
        
            $proximo = $this->atendimento->chamaProximaSenha();
        
        }catch( Exception $e ){
                
            log_message('error', $e->getMessage());
            
        }
        
        if($proximo){
            $senha = array('cd' => $proximo[0]->cd_atendimento, 'senha' => $proximo[0]->senha_atendimento);
        }else{
            $senha = array('cd' => false, 'senha' => false);
        }
        
        $resDados['dados'] = $senha;
        $this->load->view('view_json',$resDados);
        
    }
    
    /**
     * Atendimento::ficha()
     * 
     * Abre o atendimento a uma determinada senha
     * 
     * @return
     */
    public function ficha($cd = false){
        
        if($cd){
            
            $dados = $this->atendimento->dadosAtendimento($cd);
            
            $campos = array_keys($dados);
            
            foreach($campos as $campo){
             
				$info[$campo] = $dados[$campo]; # ALIMENTA OS CAMPOS COM OS DADOS
			}
            
            if($dados['cd_categoria']){
                
                $info['motivos'] = $this->motivo->motivoCategoria($dados['cd_categoria']);
                
            }
            
            $info['categorias'] = false;
            
            $prazo = $this->motivo->pegaPrazo($dados['cd_motivo']);
            $info['prazo'] = $prazo[0]->prazo_motivo;
            $info['editar'] = 'nao';
            $menu['senha_atendimento'] = $dados['senha_atendimento'];
            
        }else{
            
            $dados = array();
            
            $campos = $this->atendimento->camposAtendimento();
            
            foreach($campos as $campo){
                $info[$campo] = '';
            }
            
            $info['prazo'] = '';
            $info['categorias'] = '';
            $menu['senha_atendimento'] = false;
            
        }
        
        $info['tipo_atendimento'] = $this->tipo_atendimento->tipos_atendimentos('ASS_CATEGORIA');
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
       
	    #$dados['valores'] = $this->relatorio->arquivoRetornoDiario();
        #$dados['campos'] = ($dados['valores'][0]);
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        $this->layout->region('corpo', 'atendimento/view_frm_atende', $info);
        #$this->layout->region('corpo', 'view_conteudo');
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Atendimento::editar()
     * 
     * Abre o atendimento a uma determinada senha
     * 
     * @return
     */
    public function editar($cd = false){
        
        if($cd){
            
            $dados = $this->atendimento->dadosAtendimento($cd);
            
            $campos = array_keys($dados);
            
            foreach($campos as $campo){
             
				$info[$campo] = $dados[$campo]; # ALIMENTA OS CAMPOS COM OS DADOS
			}
            
            if($dados['cd_categoria']){
                
                $info['categorias'] = $this->categoria->todasCategorias($dados['cd_categoria']);
                $info['motivos'] = $this->motivo->motivoCategoria($dados['cd_categoria']);
                
            }
            
            $prazo = $this->motivo->pegaPrazo($dados['cd_motivo']);
            $info['prazo'] = $prazo[0]->prazo_motivo;
            $info['editar'] = 'sim';
            $menu['senha_atendimento'] = $dados['senha_atendimento'];
            
        }else{
            
            $dados = array();
            
            $campos = $this->atendimento->camposAtendimento();
            
            foreach($campos as $campo){
                $info[$campo] = '';
            }
            
            $info['prazo'] = '';
            $info['categorias'] = '';
            $menu['senha_atendimento'] = false;
            
        }
        
        $info['tipo_atendimento'] = $this->tipo_atendimento->tipos_atendimentos();
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
       
	    #$dados['valores'] = $this->relatorio->arquivoRetornoDiario();
        #$dados['campos'] = ($dados['valores'][0]);
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        $this->layout->region('corpo', 'atendimento/view_frm_atende', $info);
        #$this->layout->region('corpo', 'view_conteudo');
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Atendimento::ficha()
     * 
     * Abre o atendimento a uma determinada senha
     * 
     * @return
     */
    public function novamente($cd = false){
        
        if($cd){
            
            try{
            
                $this->atendimento->chamaNovamente($cd);
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
            $dados = $this->atendimento->dadosAtendimento($cd);
            
            $campos = array_keys($dados);
            
            foreach($campos as $campo){
             
				$info[$campo] = $dados[$campo]; # ALIMENTA OS CAMPOS COM OS DADOS
			}
            
            if($dados['cd_categoria']){
                
                $info['categorias'] = $this->categoria->todasCategorias($dados['cd_categoria']);
                $info['motivos'] = $this->motivo->motivoCategoria($dados['cd_categoria']);
                
            }
            
            $prazo = $this->motivo->pegaPrazo($dados['cd_motivo']);
            $info['prazo'] = $prazo[0]->prazo_motivo;
            $info['editar'] = 'nao';
            $menu['senha_atendimento'] = $dados['senha_atendimento'];
            
        }else{
            
            $dados = array();
            
            $campos = $this->atendimento->camposAtendimento();
            
            foreach($campos as $campo){
                $info[$campo] = '';
            }
            
            $info['prazo'] = '';
            $info['categorias'] = '';
            $menu['senha_atendimento'] = false;
            
        }
        
        $info['tipo_atendimento'] = $this->tipo_atendimento->tipos_atendimentos();
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
       
	    #$dados['valores'] = $this->relatorio->arquivoRetornoDiario();
        #$dados['campos'] = ($dados['valores'][0]);
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        $this->layout->region('corpo', 'atendimento/view_frm_atende', $info);
        #$this->layout->region('corpo', 'view_conteudo');
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Atendimento::atende()
     * 
     * Finaliza o atendimento dá baixa no caso o prazo seja zero
     * 
     * @return
     */
    public function finaliza(){
        
        array_pop($_POST);
        
        if($this->input->post('cd_atendimento')){
            
            try{
            
                $status = $this->atendimento->finaliza();
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
        }
        
        if($status){
            
            try{
            
                # Dispara e-mail para o grupo
                if($this->input->post('editar') == 'nao' and $this->input->post('prazo') > 0){
                    
                    $dadosAtendimento = $this->atendimento->dadosAtendimento($this->input->post('cd_atendimento'));
                    #$data = date('Y-m-d', strtotime("+1 days",strtotime($dadosAtendimento['data_atendimento_banco'])));
                    $data = $dadosAtendimento['data_atendimento_banco'];
                    
                    $grupo = $this->grupo_resolucao->grupoEnviarEmail();
                    
                    foreach($grupo as $gro){
                    
                        $titulo = "Atendimento aberto - ".$gro->nome_motivo;
                        
                        $msg = "Foi aberto um novo atendimento (".$gro->nome_motivo.").<br><br>"; 
                        $msg.= utf8_encode("Você tem ".$gro->prazo_motivo." dias úteis (Até ".$this->util->somarDiasUteis($data, $gro->prazo_motivo).") para resolvê-lo.<br><br>");
                        $msg.= utf8_encode("Acesse ".base_url()." para resolvê-lo");
                        
                        $this->email->initialize(); // Aqui carrega todo config criado anteriormente
                        $this->email->from('tiago.costa@simtv.com.br', 'Sim Tv - Sistema atendimento');
                        $this->email->to($gro->email_usuario); 
                        #$this->email->cc('outro@outro-site.com'); 
                        #$this->email->bcc('fulano@qualquer-site.com'); 
                        
                        $this->email->subject($titulo);
                        $this->email->message($msg);	
                        
                        $this->email->send();
            
                    }
                }
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Atendimento salvo com sucesso!</strong></div>');
            
            #redirect(base_url('atendimento/ficha/'.$this->input->post('cd_atendimento')));
            redirect(base_url('atendimento/meusAtendimentos')); 
            
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao salvar atendimento, caso o erro persista comunique o administrador!</div>');
            #redirect(base_url('atendimento/ficha'));
            redirect(base_url('atendimento/meusAtendimentos'));
            
        }
        
    }
    
    /**
     * Atendimento::meusAtendimentos()
     * 
     * Lista os atendimentos do usuário
     * 
     * @return
     */
    public function meusAtendimentos(){
        
        $menu['senha_atendimento'] = false;
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
       
	    $dados['atendimentos'] = $this->atendimento->atendimentosDoDia();
        
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        $this->layout->region('corpo', 'atendimento/view_meus_atendimentos', $dados);
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Atendimento::pendentes()
     * 
     * Lista os atendimentos pendentes de resolução
     * 
     * @return
     */
    public function pendentes(){
        
        $menu['senha_atendimento'] = false;
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
       
	    $dados['atendimentos'] = $this->atendimento->atendimentosPendentes();
        
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        $this->layout->region('corpo', 'atendimento/view_pendentes', $dados);
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Atendimento::resolver()
     * 
     * Abre a tela de resolução de atendimento
     * 
     * @return
     */
    public function resolver($cd = false){
        
        if($cd){
            
            $dados = $this->atendimento->dadosAtendimento($cd);
            
            $campos = array_keys($dados);
            
            foreach($campos as $campo){
             
				$info[$campo] = $dados[$campo]; # ALIMENTA OS CAMPOS COM OS DADOS
			}
            
            if($dados['cd_categoria']){
                
                $info['categorias'] = $this->categoria->todasCategorias($dados['cd_categoria']);
                $info['motivos'] = $this->motivo->motivoCategoria($dados['cd_categoria']);
                
            }
            
            $prazo = $this->motivo->pegaPrazo($dados['cd_motivo']);
            $info['prazo'] = $prazo[0]->prazo_motivo;
            $info['editar'] = 'sim';
            $menu['senha_atendimento'] = $dados['senha_atendimento'];
            
        }else{
            
            $dados = array();
            
            $campos = $this->atendimento->camposAtendimento();
            
            foreach($campos as $campo){
                $info[$campo] = '';
            }
            
            $info['prazo'] = '';
            $info['categorias'] = '';
            $menu['senha_atendimento'] = false;
            
        }
        
        # Verifica se o resolvedor tem permissão para resolver esse atendimento
        $autoriza = $this->atendimento->verificaAtendimentoResolucao($cd);
        #echo '<pre>'; print_r($autoriza); exit();
        $info['tipo_atendimento'] = $this->tipo_atendimento->tipos_atendimentos();
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
       
	    
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        if($autoriza[0]->cd_usuario == $this->session->userdata('cd')){
            $this->layout->region('corpo', 'atendimento/view_frm_resolucao', $info);
        }else{
            $this->layout->region('corpo', 'view_permissao', $info);
        }
        
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    /**
     * Atendimento::reclassificar()
     * 
     * Reclassifica o atendimento sem resolvê-lo
     * 
     * @return
     */
    public function reclassificar(){
        
        $_POST['resolucao_atendimento'] = '';
        array_pop($_POST);
        
        if($this->input->post('cd_atendimento')){
            
            try{
            
                $status = $this->atendimento->finaliza();
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
        }
        
        if($status){
            
            # Dispara e-mail para o grupo
            if($this->input->post('prazo') > 0){
                
                $dadosAtendimento = $this->atendimento->dadosAtendimento($this->input->post('cd_atendimento'));
                //$data = date('Y-m-d', strtotime("+1 days",strtotime($dadosAtendimento['data_atendimento_banco'])));
                $data = $dadosAtendimento['data_atendimento_banco'];
                
                $grupo = $this->grupo_resolucao->grupoEnviarEmail();
                
                try{
                
                    foreach($grupo as $gro){
                    
                        $titulo = "Atendimento aberto - ".$gro->nome_motivo;
                        
                        $msg = "Foi aberto um novo atendimento (".$gro->nome_motivo.").<br><br>"; 
                        $msg.= utf8_encode("Você tem ".$gro->prazo_motivo." dias úteis (Até ".$this->util->somarDiasUteis($data, $gro->prazo_motivo).") para resolvê-lo.<br><br>");
                        $msg.= utf8_encode("Acesse ".base_url()." para resolvê-lo");
                        
                        $this->email->initialize(); // Aqui carrega todo config criado anteriormente
                        $this->email->from('tiago.costa@simtv.com.br', 'Sim Tv - Sistema atendimento');
                        $this->email->to($gro->email_usuario); 
                        #$this->email->cc('outro@outro-site.com'); 
                        #$this->email->bcc('fulano@qualquer-site.com'); 
                        
                        $this->email->subject($titulo);
                        $this->email->message($msg);	
                        
                        $this->email->send();
            
                    }
                
                }catch( Exception $e ){
                
                    log_message('error', $e->getMessage());
                    
                }
            }
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Atendimento reclassificado com sucesso!</strong></div>');
            
            #redirect(base_url('atendimento/ficha/'.$this->input->post('cd_atendimento')));
            redirect(base_url('atendimento/pendentes')); 
            
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao reclassificado atendimento, caso o erro persista comunique o administrador!</div>');
            #redirect(base_url('atendimento/ficha'));
            redirect(base_url('atendimento/pendentes'));
            
        }
        
    }
    
    /**
     * Atendimento::fechar()
     * 
     * Fechar o atendimento passando ele para o status de resolvido
     * 
     * @return
     */
    public function fechar(){
        
        array_pop($_POST);
        $_POST['resolve'] = 'sim';
        
        if($this->input->post('cd_atendimento')){
            
            try{
            
                $status = $this->atendimento->finaliza();
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
        }
        
        if($status){
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Atendimento resolvido com sucesso!</strong></div>');
            
            #redirect(base_url('atendimento/ficha/'.$this->input->post('cd_atendimento')));
            redirect(base_url('atendimento/resolvidos')); 
            
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao resolver atendimento, caso o erro persista comunique o administrador!</div>');
            #redirect(base_url('atendimento/ficha'));
            redirect(base_url('atendimento/resolvidos'));
            
        }
        
    }
    
    /**
     * Atendimento::desconsiderar()
     * 
     * Desconsidera determinada senha (Provavelmente o usuário não esta presente)
     * 
     * @return
     */
    public function desconsiderar(){
        
        if($this->input->post('cd_atendimento')){
            
            try{
            
                $status = $this->atendimento->desconsiderar();
            
            }catch( Exception $e ){
                
                log_message('error', $e->getMessage());
                
            }
            
        }
        
        if($status){
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Atendimento desconsiderado com sucesso!</strong></div>');
            
            #redirect(base_url('atendimento/ficha/'.$this->input->post('cd_atendimento')));
            redirect(base_url('atendimento/meusAtendimentos')); 
            
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao desconsiderar atendimento, caso o erro persista comunique o administrador!</div>');
            #redirect(base_url('atendimento/ficha'));
            redirect(base_url('atendimento/meusAtendimentos'));
            
        }
        
    }
    
    /**
     * Atendimento::registraEatende()
     * 
     * Registra e dá início ao atendimento
     * 
     * @return
     */
    public function registraEatende(){
        
        try{
        
            $cd = $this->atendimento->registraSenha();
            
        }catch( Exception $e ){
                
            log_message('error', $e->getMessage());
            
        }
        
        if(!$cd){
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao registrar atendimento, caso o erro persista comunique o administrador!</div>');
            redirect(base_url('atendimento/meusAtendimentos'));
        }
        
        try{
                        
            $iniciaAtendimento = $this->atendimento->iniciaAtendimento($cd);
            
        }catch( Exception $e ){
                
            log_message('error', $e->getMessage());
            
        }
        
        if($iniciaAtendimento){
           redirect(base_url('atendimento/ficha/'.$cd));
        }else{
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao registrar atendimento, caso o erro persista comunique o administrador!</div>');
            redirect(base_url('atendimento/meusAtendimentos'));
        }
        
    }
    
    /**
     * Atendimento::resolvido()
     * 
     * Lista os atendimentos resolvidos
     * 
     * @return
     */
    public function resolvidos(){
        
        $menu['senha_atendimento'] = false;
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        $menu['guiches'] = $this->guiche->guiches();
       
	    $dados['atendimentos'] = $this->atendimento->atendimentosResolvidos();
        
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        $this->layout->region('corpo', 'atendimento/view_resolvidos', $dados);
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
                    
}
