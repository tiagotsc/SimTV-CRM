<?php

/**
 * Dashboard_model
 * 
 * Classe que realiza o tratamento do guichê
 * 
 * @package   
 * @author Tiago Silva Costa
 * @version 2014
 * @access public
 */
class Guiche_model extends CI_Model{
	
	/**
	 * Guiche_model::__construct()
	 * 
	 * @return
	 */
	function __construct(){
		parent::__construct();
	}
    
    /**
    * Guiche_model::insere()
    * 
    * Função que realiza a inserção dos dados do guichê na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function insere(){
		
		$campo = array();
		$valor = array();
        
        #$campo[] = 'criador_guiche';
        #$valor[] = $this->session->userdata('cd');
		foreach($_POST as $c => $v){
			
            if($c <> 'cd_guiche'){
            
    			$valorFormatado = $this->util->removeAcentos($this->input->post($c));
    			$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
    			
    			$campo[] = $c;
    			$valor[] = $valorFormatado;
            
            }
            
		}
        
        # A senha inícial fica definida com o CPF
        #$campo[] = 'senha_guiche';
		#$valor[] = $this->util->formaValorBanco(md5(str_replace('-', '', str_replace('.', '',$this->input->post('cpf_funcionario')))));
		
		$campos = implode(', ', $campo);
		$valores = implode(', ', $valor);
		
        $this->db->trans_begin();
        
		$sql = "INSERT INTO guiche (".$campos.")\n VALUES(".$valores.");";
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
    * Guiche_model::atualiza()
    * 
    * Função que realiza a atualização dos dados do guichê na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function atualiza(){
        
        #$campoValor[] = 'atualizador_guiche = '.$this->session->userdata('cd');
        #$campoValor[] = "data_atualizacao_guiche = '".date('Y-m-d h:i:s')."'";
        
		foreach($_POST as $c => $v){
			
			if($c != 'cd_guiche'){
				$valorFormatado = $this->util->removeAcentos($this->input->post($c));
				$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
			
				$campoValor[] = $c.' = '.$valorFormatado;
			
			}
		}
		
		$camposValores = implode(', ', $campoValor);
		
        $this->db->trans_begin();
        
		$sql = "UPDATE guiche SET ".$camposValores." WHERE cd_guiche = ".$this->input->post('cd_guiche').";";
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
    * Guiche_model::dadosGuiche()
    * 
    * Função que monta um array com todos os dados do guichê
    * @param $cd Cd do guichê para recuperação de dados
    * @return Retorna todos os dados do guichê
    */
	public function dadosGuiche($cd){
	
		$this->db->where('cd_guiche', $cd);
		$guiche = $this->db->get('guiche')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $guiche[0];
	}
	
    /**
    * Guiche_model::camposGuiche()
    * 
    * Função que pega os nomes de todos os campos existentes na tabela guichê
    * @return Os campos da tabela guichê
    */
	public function camposGuiche(){
		
		$campos = $this->db->get('guiche')->list_fields();
		
		return $campos;
		
	}
    
    /**
     * Guiche_model::psqGuiches()
     * 
     * lista os guichês existentes de acordo com os parâmetros informados
     * @param $nome do guichê que se deseja encontrar
     * @param $pagina Página da paginação
     * @param $mostra_por_pagina Página corrente da paginação
     * 
     * @return A lista dos guichês
     */
    public function psqGuiches($nome = null, $status = null, $pagina = null, $mostra_por_pagina = null){
        
        $this->db->select("
                            cd_guiche,
                            nome_guiche,
                            CASE WHEN status_guiche = 'A'
                                THEN 'Ativo'
                            ELSE 'Inativo' END AS status_guiche
                            ");       
        
        
        if($nome != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "nome_guiche LIKE '%".strtoupper($nome)."%' OR email_guiche LIKE '%".strtoupper($nome)."%'";
            $this->db->where($condicao);
        }
        
        if($status != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "status_guiche = '".$status."'";
            $this->db->where($condicao);
        }
 
        $this->db->order_by("nome_guiche", "asc");  
        
        return $this->db->get('guiche', $mostra_por_pagina, $pagina)->result();
    }
    
    /**
     * Guiche_model::psqQtdGuiches()
     * 
     * Consulta a quantidade de guichês da pesquisa
     * 
     * @param $nome Nome do guichê para filtrar a consulta
     * 
     * @param $status Status do guichê para filtrar a consulta
     * 
     * @return Retorna a quantidade
     */
    public function psqQtdGuiches($nome = null, $status = null){
        
        if($nome != '0'){
            $condicao = "nome_guiche LIKE '%".strtoupper($nome)."%'";
            $this->db->where($condicao);
        }
        
        if($status != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "status_guiche = '".$status."'";
            $this->db->where($condicao);
        }
        
        $this->db->select('count(*) as total');
        return $this->db->get('guiche')->result();
    }
    
    /**
     * Guiche_model::deleteGuiche()
     * 
     * Apaga o guichê
     * 
     * @return Retorna o número de linhas afetadas
     */
    public function deleteGuiche(){
        
        $sql = "DELETE FROM guiche WHERE cd_guiche = ".$this->input->post('apg_cd_guiche');
        $this->db->query($sql);
        return $this->db->affected_rows();
        
    }
    
    /**
     * Guiche_model::guiches()
     * 
     * Pega todos os guichês ativos
     * 
     * @return Retorna os guichês
     */
    public function guiches(){
        
        $this->db->where("status_guiche", "A");
        return $this->db->get('guiche')->result();
        
    }

}