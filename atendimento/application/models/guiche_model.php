<?php

/**
 * Dashboard_model
 * 
 * Classe que realiza o tratamento do guich�
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
    * Fun��o que realiza a inser��o dos dados do guich� na base de dados
    * @return O n�mero de linhas afetadas pela opera��o
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
        
        # A senha in�cial fica definida com o CPF
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
    * Fun��o que realiza a atualiza��o dos dados do guich� na base de dados
    * @return O n�mero de linhas afetadas pela opera��o
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
        
		return $this->db->query($sql); # RETORNA O N�MERO DE LINHAS AFETADAS
		
	}
	
    /**
    * Guiche_model::dadosGuiche()
    * 
    * Fun��o que monta um array com todos os dados do guich�
    * @param $cd Cd do guich� para recupera��o de dados
    * @return Retorna todos os dados do guich�
    */
	public function dadosGuiche($cd){
	
		$this->db->where('cd_guiche', $cd);
		$guiche = $this->db->get('guiche')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $guiche[0];
	}
	
    /**
    * Guiche_model::camposGuiche()
    * 
    * Fun��o que pega os nomes de todos os campos existentes na tabela guich�
    * @return Os campos da tabela guich�
    */
	public function camposGuiche(){
		
		$campos = $this->db->get('guiche')->list_fields();
		
		return $campos;
		
	}
    
    /**
     * Guiche_model::psqGuiches()
     * 
     * lista os guich�s existentes de acordo com os par�metros informados
     * @param $nome do guich� que se deseja encontrar
     * @param $pagina P�gina da pagina��o
     * @param $mostra_por_pagina P�gina corrente da pagina��o
     * 
     * @return A lista dos guich�s
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
     * Consulta a quantidade de guich�s da pesquisa
     * 
     * @param $nome Nome do guich� para filtrar a consulta
     * 
     * @param $status Status do guich� para filtrar a consulta
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
     * Apaga o guich�
     * 
     * @return Retorna o n�mero de linhas afetadas
     */
    public function deleteGuiche(){
        
        $sql = "DELETE FROM guiche WHERE cd_guiche = ".$this->input->post('apg_cd_guiche');
        $this->db->query($sql);
        return $this->db->affected_rows();
        
    }
    
    /**
     * Guiche_model::guiches()
     * 
     * Pega todos os guich�s ativos
     * 
     * @return Retorna os guich�s
     */
    public function guiches(){
        
        $this->db->where("status_guiche", "A");
        return $this->db->get('guiche')->result();
        
    }

}