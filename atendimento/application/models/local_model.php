<?php

/**
 * Dashboard_model
 * 
 * Classe que realiza o tratamento do local
 * 
 * @package   
 * @author Tiago Silva Costa
 * @version 2014
 * @access public
 */
class Local_model extends CI_Model{
	
	/**
	 * Local_model::__construct()
	 * 
	 * @return
	 */
	function __construct(){
		parent::__construct();
	}
    
    /**
    * Local_model::insere()
    * 
    * Função que realiza a inserção dos dados do local na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function insere(){
		
		$campo = array();
		$valor = array();
        
        #$campo[] = 'criador_local';
        #$valor[] = $this->session->userdata('cd');
		foreach($_POST as $c => $v){
			
            if($c <> 'cd_local'){
            
    			$valorFormatado = $this->util->removeAcentos($this->input->post($c));
    			$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
    			
    			$campo[] = $c;
    			$valor[] = $valorFormatado;
            
            }
            
		}
        
        # A senha inícial fica definida com o CPF
        #$campo[] = 'senha_local';
		#$valor[] = $this->util->formaValorBanco(md5(str_replace('-', '', str_replace('.', '',$this->input->post('cpf_funcionario')))));
		
		$campos = implode(', ', $campo);
		$valores = implode(', ', $valor);
		
        $this->db->trans_begin();
        
		$sql = "INSERT INTO local (".$campos.")\n VALUES(".$valores.");";
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
    * Local_model::atualiza()
    * 
    * Função que realiza a atualização dos dados do local na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function atualiza(){
        
        #$campoValor[] = 'atualizador_local = '.$this->session->userdata('cd');
        #$campoValor[] = "data_atualizacao_local = '".date('Y-m-d h:i:s')."'";
        
		foreach($_POST as $c => $v){
			
			if($c != 'cd_local'){
				$valorFormatado = $this->util->removeAcentos($this->input->post($c));
				$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
			
				$campoValor[] = $c.' = '.$valorFormatado;
			
			}
		}
		
		$camposValores = implode(', ', $campoValor);
		
        $this->db->trans_begin();
        
		$sql = "UPDATE local SET ".$camposValores." WHERE cd_local = ".$this->input->post('cd_local').";";
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
    * Local_model::dadosLocal()
    * 
    * Função que monta um array com todos os dados do local
    * @param $cd Cd do local para recuperação de dados
    * @return Retorna todos os dados do local
    */
	public function dadosLocal($cd){
	
		$this->db->where('cd_local', $cd);
		$local = $this->db->get('local')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $local[0];
	}
	
    /**
    * Local_model::camposLocal()
    * 
    * Função que pega os nomes de todos os campos existentes na tabela local
    * @return Os campos da tabela local
    */
	public function camposLocal(){
		
		$campos = $this->db->get('local')->list_fields();
		
		return $campos;
		
	}
    
    /**
     * Local_model::psqLocals()
     * 
     * lista os locals existentes de acordo com os parâmetros informados
     * @param $nome do local que se deseja encontrar
     * @param $pagina Página da paginação
     * @param $mostra_por_pagina Página corrente da paginação
     * 
     * @return A lista dos locals
     */
    public function psqLocals($nome = null, $status = null, $pagina = null, $mostra_por_pagina = null){
        
        $this->db->select("
                            cd_local,
                            nome_local,
                            nome_municipio,
                            CASE WHEN status_local = 'A'
                                THEN 'Ativo'
                            ELSE 'Inativo' END AS status_local
                            ");       
        
        
        if($nome != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "nome_local LIKE '%".strtoupper($nome)."%' OR email_local LIKE '%".strtoupper($nome)."%'";
            $this->db->where($condicao);
        }
        
        if($status != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "status_local = '".$status."'";
            $this->db->where($condicao);
        }
        
        $this->db->join('municipio', 'municipio.cd_municipio = local.cd_municipio');   
        $this->db->order_by("nome_local", "asc");  
        
        return $this->db->get('local', $mostra_por_pagina, $pagina)->result();
    }
    
    /**
     * Local_model::psqQtdLocals()
     * 
     * Consulta a quantidade de locals da pesquisa
     * 
     * @param $nome Nome do local para filtrar a consulta
     * 
     * @param $status Status do local para filtrar a consulta
     * 
     * @return Retorna a quantidade
     */
    public function psqQtdLocals($nome = null, $status = null){
        
        if($nome != '0'){
            $condicao = "nome_local LIKE '%".strtoupper($nome)."%'";
            $this->db->where($condicao);
        }
        
        if($status != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "status_local = '".$status."'";
            $this->db->where($condicao);
        }
        
        $this->db->select('count(*) as total');
        return $this->db->get('local')->result();
    }
    
    /**
     * Local_model::deleteLocal()
     * 
     * Apaga o local
     * 
     * @return Retorna o número de linhas afetadas
     */
    public function deleteLocal(){
        
        $sql = "DELETE FROM local WHERE cd_local = ".$this->input->post('apg_cd_local');
        $this->db->query($sql);
        return $this->db->affected_rows();
        
    }
    
    /**
     * Atendimento_model::todosLocais()
     * 
     * Pega todos os locais
     * 
     * @return Retorna as informações
     */
    public function todosLocais(){
        
        $this->db->where('status_local', 'A');
        return $this->db->get('local')->result();
        
    }
    
    /**
     * Atendimento_model::locaisPainel()
     * 
     * Pega as informações para montar a combo de município > local
     * 
     * @return Retorna as informações
     */
    public function locaisPainel(){
        
        $this->db->select("
                            cd_local,
                            CONCAT(UPPER(nome_municipio),' > ',nome_local) nome_local
                            ");
        $this->db->join('municipio', 'municipio.cd_municipio = local.cd_municipio'); 
        $this->db->where('status_local', 'A');
        return $this->db->get('local')->result();
        
    }
    
    /**
     * Atendimento_model::impressora()
     * 
     * Pega a impressora do local
     * 
     * @return Retorna a impressora
     */
    public function impressora(){
        
        $this->db->select('impressora_local');
        $this->db->where('cd_local', $this->input->post('local')); 
        return $this->db->get('local')->row()->impressora_local;
        
    } 

}