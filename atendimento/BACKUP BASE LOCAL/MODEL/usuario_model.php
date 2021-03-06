<?php

/**
 * Dashboard_model
 * 
 * Classe que realiza o tratamento do usu�rio
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
    * Fun��o que realiza a inser��o dos dados do usu�rio na base de dados
    * @return O n�mero de linhas afetadas pela opera��o
    */
	public function insere(){
		
		$campo = array();
		$valor = array();
        
        $campo[] = 'criador_usuario';
        $valor[] = $this->session->userdata('cd');
        
        $campo[] = "data_criacao_usuario";
        $valor[] = "'".date('Y-m-d h:i:s')."'";
        
		foreach($_POST as $c => $v){
			
            if($c <> 'cd_usuario'){
            
    			$valorFormatado = $this->util->removeAcentos($this->input->post($c));
    			$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
    			
    			$campo[] = $c;
    			$valor[] = $valorFormatado;
            
            }
            
		}
        
        # A senha in�cial fica definida com o CPF
        #$campo[] = 'senha_usuario';
		#$valor[] = $this->util->formaValorBanco(md5(str_replace('-', '', str_replace('.', '',$this->input->post('cpf_funcionario')))));
		
		$campos = implode(', ', $campo);
		$valores = implode(', ', $valor);
		
        $this->db->trans_begin();
        
		$sql = "INSERT INTO usuario (".$campos.")\n VALUES(".$valores.");";
		$this->db->query($sql);
        $cd = $this->db->insert_id();
        
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
    * Fun��o que realiza a atualiza��o dos dados do usu�rio na base de dados
    * @return O n�mero de linhas afetadas pela opera��o
    */
	public function atualiza(){
        
        $campoValor[] = 'atualizador_usuario = '.$this->session->userdata('cd');
        $campoValor[] = "data_atualizacao_usuario = '".date('Y-m-d h:i:s')."'";
        
		foreach($_POST as $c => $v){
			
			if($c != 'cd_usuario'){
				$valorFormatado = $this->util->removeAcentos($this->input->post($c));
				$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
			
				$campoValor[] = $c.' = '.$valorFormatado;
			
			}
		}
		
		$camposValores = implode(', ', $campoValor);
		
        $this->db->trans_begin();
        
		$sql = "UPDATE usuario SET ".$camposValores." WHERE cd_usuario = ".$this->input->post('cd_usuario').";";
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
        
		return $this->db->query($sql); # RETORNA O N�MERO DE LINHAS AFETADAS
		
	}
	
    /**
    * Usuario_model::dadosUsuario()
    * 
    * Fun��o que monta um array com todos os dados do usu�rio
    * @param $cd Cd do usu�rio para recupera��o de dados
    * @return Retorna todos os dados do usu�rio
    */
	public function dadosUsuario($cd){
	
		$this->db->where('cd_usuario', $cd);
		$usuario = $this->db->get('usuario')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $usuario[0];
	}
	
    /**
    * Usuario_model::camposUsuario()
    * 
    * Fun��o que pega os nomes de todos os campos existentes na tabela usu�rio
    * @return Os campos da tabela usu�rio
    */
	public function camposUsuario(){
		
		$campos = $this->db->get('usuario')->list_fields();
		
		return $campos;
		
	}
    
    /**
     * Usuario_model::psqUsuarios()
     * 
     * lista os usu�rios existentes de acordo com os par�metros informados
     * @param $nome do usu�rio que se deseja encontrar
     * @param $pagina P�gina da pagina��o
     * @param $mostra_por_pagina P�gina corrente da pagina��o
     * 
     * @return A lista dos usu�rios
     */
    public function psqUsuarios($nome = null, $status = null, $pagina = null, $mostra_por_pagina = null){
        
        $this->db->select("
                            cd_usuario,
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
        
        $this->db->join('departamento', 'departamento.cd_departamento = usuario.cd_departamento', 'left');      
        $this->db->join('estado', 'estado.cd_estado = usuario.cd_estado', 'left');    
        $this->db->join('perfil', 'perfil.cd_perfil = usuario.cd_perfil', 'left'); 
        $this->db->order_by("nome_usuario", "asc");  
        
        return $this->db->get('usuario', $mostra_por_pagina, $pagina)->result();
    }
    
    /**
     * Usuario_model::psqQtdUsuarios()
     * 
     * Consulta a quantidade de usu�rios da pesquisa
     * 
     * @param $nome Nome do usu�rio para filtrar a consulta
     * 
     * @param $status Status do usu�rio para filtrar a consulta
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
        return $this->db->get('usuario')->result();
    }
    
    /**
     * Usuario_model::deleteUsuario()
     * 
     * Apaga o usu�rio
     * 
     * @return Retorna o n�mero de linhas afetadas
     */
    public function deleteUsuario(){
        
        $sql = "DELETE FROM usuario WHERE cd_usuario = ".$this->input->post('apg_cd_usuario');
        $this->db->query($sql);
        return $this->db->affected_rows();
        
    }
    
    /**
     * Usuario_model::autenticaUsuario()
     * 
     * Autentica o usu�rio
     * 
     * @return Retorna os dados do usu�rio caso ele exista
     */
    public function autenticaUsuario(){
        
        $this->db->select('cd_usuario, login_usuario, nome_usuario, email_usuario, cd_perfil, atendente_usuario, status_usuario, cd_local, atendente_usuario');
        $this->db->where('login_usuario', $this->input->post('login'));
        return $this->db->get('usuario')->result();
        
    }
    
    /**
     * Usuario_model::iniConfigAtendente()
     * 
     * Configura o atendente no guich�
     * 
     * @return 
     */
    public function iniConfigAtendente(){
        
        /* Consulta se tem algum atendente associado ao guich� */
        $this->db->select('cd_usuario');
        $this->db->where('online_usuario', 'S');
        $this->db->where('cd_guiche', $this->input->post('cd_guiche_config'));
        $this->db->where('cd_local', $this->session->userdata('cd_local'));
        $usuarioLogado = $this->db->get('usuario')->result();
        
        /* Coloca o atedente online e o assiocia ao guich� */
        $sql = "UPDATE usuario SET online_usuario = 'S', cd_guiche = ".$this->input->post('cd_guiche_config')." WHERE cd_usuario = ".$this->session->userdata('cd');
        $this->db->query($sql);
        
        /* Registra o log de acesso do atendente */
        $sql = "INSERT INTO acesso_atendente (cd_usuario, tipo_registro, navegador, ip, descricao) VALUES(".$this->session->userdata('cd').", 'ENTRADA', '".$_SERVER["HTTP_USER_AGENT"]."', '".$_SERVER["REMOTE_ADDR"]."', 'Entrada no sistema');";
        $this->db->query($sql);
    
        if($usuarioLogado){
            
            foreach($usuarioLogado as $uL){
                
                $sql = "UPDATE usuario SET online_usuario = 'N', cd_guiche = NULL WHERE cd_usuario = ".$uL->cd_usuario;
                $this->db->query($sql);
                
                $sql = "INSERT INTO acesso_atendente (cd_usuario, tipo_registro, navegador, ip, descricao) VALUES(".$uL->cd_usuario.", 'DESCONECTADO', '".$_SERVER["HTTP_USER_AGENT"]."', '".$_SERVER["REMOTE_ADDR"]."', 'Desconectado por outro atendente (".$this->session->userdata('cd').")');";
                $this->db->query($sql);
                
            }
            
        }
        
        /* Define os par�metros do atendente */
        $this->db->where('cd_guiche', $this->input->post('cd_guiche_config'));
        $guiche = $this->db->get('guiche')->result();
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
        
        $sql = "UPDATE usuario SET online_usuario = 'N', cd_guiche = NULL WHERE cd_usuario = ".$this->session->userdata('cd');
        $this->db->query($sql);
        
        $sql = "INSERT INTO acesso_atendente (cd_usuario, tipo_registro, navegador, ip, descricao) VALUES(".$this->session->userdata('cd').", 'SAIDA', '".$_SERVER["HTTP_USER_AGENT"]."', '".$_SERVER["REMOTE_ADDR"]."', 'Saida do sistema');";
        $this->db->query($sql);
        
    }
    
    /**
     * Usuario_model::verificaGuiche()
     * 
     * Verifica se o guich� esta associado a algum atendente
     * 
     * @return Os dados do atendente que esta associado
     */
    public function verificaGuiche(){
        
        $this->db->select('cd_usuario, nome_usuario');
        $this->db->where('online_usuario', 'S');
        $this->db->where('cd_guiche', $this->input->post('cd_guiche'));
        $this->db->where('cd_local', $this->input->post('cd_local'));
        $this->db->where('cd_usuario NOT IN ('.$this->input->post('cd_usuario').')');
        return $this->db->get('usuario')->result();
        
    }
    
    /**
     * Usuario_model::estaOnline()
     * 
     * Verifica se o usu�rio esta online
     * 
     * @return o status
     */
    public function estaOnline(){
        
        $this->db->select('online_usuario');
        $this->db->where('cd_usuario', $this->session->userdata('cd'));
        return $this->db->get('usuario')->result();
        
    }

}