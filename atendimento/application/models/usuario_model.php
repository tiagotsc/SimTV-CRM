<?php

/**
 * Dashboard_model
 * 
 * Classe que realiza o tratamento do usuário
 * 
 * @package   
 * @author Tiago Silva Costa
 * @version 2014
 * @access public
 */
class Usuario_model extends CI_Model{
	
	/**
	 * Usuario_model::__construct()
	 * 
	 * @return
	 */
	function __construct(){
		parent::__construct();
	}
    
    /**
    * Usuario_model::insere()
    * 
    * Função que realiza a inserção dos dados do usuário na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function insere(){
		
		$campo = array();
		$valor = array();
        
        $campo[] = 'criador_usuario';
        $valor[] = $this->session->userdata('cd');
        
        $campo[] = "data_criacao_usuario";
        $valor[] = "'".date('Y-m-d h:i:s')."'";
        
		foreach($_POST as $c => $v){
			
            if($c <> 'cd_usuario' and $c <> 'cd_perfil' and $c <> 'status_usuario' and $c <> 'atendente_usuario' and $c <> 'cd_local'){
            
    			$valorFormatado = $this->util->removeAcentos($this->input->post($c));
    			$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
    			
    			$campo[] = $c;
    			$valor[] = $valorFormatado;
            
            }
            
		}
        
        # A senha inícial fica definida com o CPF
        #$campo[] = 'senha_usuario';
		#$valor[] = $this->util->formaValorBanco(md5(str_replace('-', '', str_replace('.', '',$this->input->post('cpf_funcionario')))));
		
		$campos = implode(', ', $campo);
		$valores = implode(', ', $valor);
		
        $this->db->trans_begin();
        
		$sql = "INSERT INTO adminti.usuario (".$campos.")\n VALUES(".$valores.");";
		$this->db->query($sql);
        $cd = $this->db->insert_id();
        
        # Registra Perfil
        $perfil = $this->util->formaValorBanco($this->input->post('cd_perfil'));
        $atendente = $this->util->formaValorBanco($this->input->post('atendente_usuario'));
        $local = $this->util->formaValorBanco($this->input->post('cd_local'));
        $sql = "INSERT INTO atendimento.config_usuario (cd_usuario, cd_perfil, atendente_usuario, cd_local, status_config_usuario)\n VALUES(".$cd.",".$perfil.", ".$atendente.", ".$local.",'".$this->input->post('status_usuario')."');";
        $this->db->query($sql);
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            
            return $cd;
        }
        
	}
	
    /**
    * Usuario_model::atualiza()
    * 
    * Função que realiza a atualização dos dados do usuário na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function atualiza(){
        
        $campoValor[] = 'atualizador_usuario = '.$this->session->userdata('cd');
        $campoValor[] = "data_atualizacao_usuario = '".date('Y-m-d h:i:s')."'";
        
		foreach($_POST as $c => $v){
			
			if($c != 'cd_usuario' and $c != 'cd_perfil' and $c != 'status_usuario' and $c != 'atendente_usuario' and $c != 'cd_local'){
				$valorFormatado = $this->util->removeAcentos($this->input->post($c));
				$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
			
				$campoValor[] = $c.' = '.$valorFormatado;
			
			}
		}
		
		$camposValores = implode(', ', $campoValor);
		
        $this->db->trans_begin();
        
		$sql = "UPDATE adminti.usuario SET ".$camposValores." WHERE cd_usuario = ".$this->input->post('cd_usuario').";";
		$this->db->query($sql);
        
        # Registra Perfil
        $perfil = $this->util->formaValorBanco($this->input->post('cd_perfil'));
        $atendente = $this->util->formaValorBanco($this->input->post('atendente_usuario'));
        $local = $this->util->formaValorBanco($this->input->post('cd_local'));
        $sql = "UPDATE atendimento.config_usuario SET cd_perfil = ".$perfil.", atendente_usuario= ".$atendente.", cd_local = ".$local.", status_config_usuario = '".$this->input->post('status_usuario')."' WHERE cd_usuario = ".$this->input->post('cd_usuario');
        $this->db->query($sql);
        
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
        
		return $this->db->query($sql); # RETORNA O NÚMERO DE LINHAS AFETADAS
		
	}
	
    /**
    * Usuario_model::dadosUsuario()
    * 
    * Função que monta um array com todos os dados do usuário
    * @param $cd Cd do usuário para recuperação de dados
    * @return Retorna todos os dados do usuário
    */
	public function dadosUsuario($cd){
        
        $this->db->select('usuario.cd_usuario, login_usuario, nome_usuario, email_usuario, nome_usuario, cd_departamento, cd_perfil, status_config_usuario, cd_estado, atendente_usuario, cd_local');
		$this->db->where('usuario.cd_usuario', $cd);
        $this->db->join('atendimento.config_usuario', 'usuario.cd_usuario = atendimento.config_usuario.cd_usuario', 'left');
		$usuario = $this->db->get('adminti.usuario')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $usuario[0];
	}
	
    /**
    * Usuario_model::camposUsuario()
    * 
    * Função que pega os nomes de todos os campos existentes na tabela usuário
    * @return Os campos da tabela usuário
    */
	public function camposUsuario(){
		
        $this->db->select('usuario.cd_usuario, login_usuario, nome_usuario, email_usuario, nome_usuario, cd_departamento, cd_perfil, status_config_usuario, cd_estado, atendente_usuario, cd_local');
		$this->db->join('atendimento.config_usuario', 'usuario.cd_usuario = atendimento.config_usuario.cd_usuario', 'left');
		$campos = $this->db->get('adminti.usuario')->list_fields();
		
		return $campos;
		
	}
    
    /**
     * Usuario_model::psqUsuarios()
     * 
     * lista os usuários existentes de acordo com os parâmetros informados
     * @param $nome do usuário que se deseja encontrar
     * @param $pagina Página da paginação
     * @param $mostra_por_pagina Página corrente da paginação
     * 
     * @return A lista dos usuários
     */
    public function psqUsuarios($nome = null, $status = null, $pagina = null, $mostra_por_pagina = null){
        
        $this->db->select("
                            usuario.cd_usuario,
                            login_usuario,
                            nome_usuario,
                            email_usuario,
                            CASE WHEN status_usuario = 'A'
                                THEN 'Ativo'
                            ELSE 'Inativo' END AS status_usuario,
                            nome_estado,
                            nome_departamento,
                            nome_perfil
                            ");       
        
        
        if($nome != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "nome_usuario LIKE '%".strtoupper($nome)."%' OR email_usuario LIKE '%".strtoupper($nome)."%'";
            $this->db->where($condicao);
        }
        
        if($status != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "status_usuario = '".$status."'";
            $this->db->where($condicao);
        }
        
        $this->db->join('adminti.departamento', 'adminti.departamento.cd_departamento = adminti.usuario.cd_departamento', 'left');      
        $this->db->join('adminti.estado', 'adminti.estado.cd_estado = adminti.usuario.cd_estado', 'left');    
        $this->db->join('atendimento.config_usuario', 'usuario.cd_usuario = atendimento.config_usuario.cd_usuario', 'left');
        $this->db->join('atendimento.perfil', 'atendimento.perfil.cd_perfil = atendimento.config_usuario.cd_perfil', 'left'); 
        $this->db->order_by("nome_usuario", "asc");  
        
        return $this->db->get('adminti.usuario', $mostra_por_pagina, $pagina)->result();
    }
    
    /**
     * Usuario_model::psqQtdUsuarios()
     * 
     * Consulta a quantidade de usuários da pesquisa
     * 
     * @param $nome Nome do usuário para filtrar a consulta
     * 
     * @param $status Status do usuário para filtrar a consulta
     * 
     * @return Retorna a quantidade
     */
    public function psqQtdUsuarios($nome = null, $status = null){
        
        if($nome != '0'){
            $condicao = "nome_usuario LIKE '%".strtoupper($nome)."%'";
            $this->db->where($condicao);
        }
        
        if($status != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "status_usuario = '".$status."'";
            $this->db->where($condicao);
        }
        
        $this->db->select('count(*) as total');
        return $this->db->get('adminti.usuario')->result();
    }
    
    /**
     * Usuario_model::deleteUsuario()
     * 
     * Apaga o usuário
     * 
     * @return Retorna o número de linhas afetadas
     */
    public function deleteUsuario(){
        
        $sql = "DELETE FROM adminti.usuario WHERE cd_usuario = ".$this->input->post('apg_cd_usuario');
        $this->db->query($sql);
        return $this->db->affected_rows();
        
    }
    
    /**
     * Usuario_model::autenticaUsuario()
     * 
     * Autentica o usuário
     * 
     * @return Retorna os dados do usuário caso ele exista
     */
    public function autenticaUsuario(){
        
        $this->db->select('usuario.cd_usuario, login_usuario, nome_usuario, email_usuario, cd_perfil, status_usuario AS status_pai, status_config_usuario AS status_filho, atendente_usuario, cd_local');
        $this->db->where('login_usuario', strtoupper($this->input->post('login')));
        $this->db->join('atendimento.config_usuario', 'usuario.cd_usuario = atendimento.config_usuario.cd_usuario', 'left');
        return $this->db->get('adminti.usuario')->result();
        
    }
    
    /**
     * Usuario_model::iniConfigAtendente()
     * 
     * Configura o atendente no guichê
     * 
     * @return 
     */
    public function iniConfigAtendente(){
        
        /* Consulta se tem algum atendente associado ao guichê */
        $this->db->select('cd_usuario');
        $this->db->where('online_usuario', 'S');
        $this->db->where('cd_guiche', $this->input->post('cd_guiche_config'));
        $this->db->where('cd_local', $this->session->userdata('cd_local'));
        $usuarioLogado = $this->db->get('atendimento.config_usuario')->result();
        
        /* Coloca o atedente online e o assiocia ao guichê */
        $sql = "UPDATE atendimento.config_usuario SET online_usuario = 'S', cd_guiche = ".$this->input->post('cd_guiche_config')." WHERE cd_usuario = ".$this->session->userdata('cd');
        $this->db->query($sql);
        
        /* Registra o log de acesso do atendente */
        $sql = "INSERT INTO atendimento.acesso_atendente (cd_usuario, tipo_registro, navegador, ip, descricao) VALUES(".$this->session->userdata('cd').", 'ENTRADA', '".$_SERVER["HTTP_USER_AGENT"]."', '".$_SERVER["REMOTE_ADDR"]."', 'Entrada no sistema');";
        $this->db->query($sql);
    
        if($usuarioLogado){
            
            foreach($usuarioLogado as $uL){
                
                $sql = "UPDATE atendimento.config_usuario SET online_usuario = 'N', cd_guiche = NULL WHERE cd_usuario = ".$uL->cd_usuario;
                $this->db->query($sql);
                
                $sql = "INSERT INTO atendimento.acesso_atendente (cd_usuario, tipo_registro, navegador, ip, descricao) VALUES(".$uL->cd_usuario.", 'DESCONECTADO', '".$_SERVER["HTTP_USER_AGENT"]."', '".$_SERVER["REMOTE_ADDR"]."', 'Desconectado por outro atendente (".$this->session->userdata('cd').")');";
                $this->db->query($sql);
                
            }
            
        }
        
        /* Define os parâmetros do atendente */
        $this->db->where('cd_guiche', $this->input->post('cd_guiche_config'));
        $guiche = $this->db->get('atendimento.guiche')->result();
        $this->session->set_userdata('cd_guiche', $this->input->post('cd_guiche_config'));
        $this->session->set_userdata('guiche', $guiche[0]->nome_guiche);
        $this->session->set_userdata('configAtendente', 'S');
        
    }
    
    /**
     * Usuario_model::finConfigAtendente()
     * 
     * Descofigura o atendente
     * 
     * @return 
     */
    public function finConfigAtendente(){
        
        $sql = "UPDATE atendimento.config_usuario SET online_usuario = 'N', cd_guiche = NULL WHERE cd_usuario = ".$this->session->userdata('cd');
        $this->db->query($sql);
        
        $sql = "INSERT INTO atendimento.acesso_atendente (cd_usuario, tipo_registro, navegador, ip, descricao) VALUES(".$this->session->userdata('cd').", 'SAIDA', '".$_SERVER["HTTP_USER_AGENT"]."', '".$_SERVER["REMOTE_ADDR"]."', 'Saida do sistema');";
        $this->db->query($sql);
        
    }
    
    /**
     * Usuario_model::verificaGuiche()
     * 
     * Verifica se o guichê esta associado a algum atendente
     * 
     * @return Os dados do atendente que esta associado
     */
    public function verificaGuiche(){
        
        $this->db->select('usuario.cd_usuario, nome_usuario');
        $this->db->where('online_usuario', 'S');
        $this->db->where('cd_guiche', $this->input->post('cd_guiche'));
        $this->db->where('cd_local', $this->input->post('cd_local'));
        $this->db->where('usuario.cd_usuario NOT IN ('.$this->input->post('cd_usuario').')');
        $this->db->join('atendimento.config_usuario', 'usuario.cd_usuario = atendimento.config_usuario.cd_usuario', 'left');
        return $this->db->get('adminti.usuario')->result();
        
    }
    
    /**
     * Usuario_model::estaOnline()
     * 
     * Verifica se o usuário esta online
     * 
     * @return o status
     */
    public function estaOnline(){
        
        $this->db->select('online_usuario');
        $this->db->where('cd_usuario', $this->session->userdata('cd'));
        return $this->db->get('atendimento.config_usuario')->result();
        
    }

}