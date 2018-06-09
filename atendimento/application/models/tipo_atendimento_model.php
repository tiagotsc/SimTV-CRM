<?php

/**
 * Dashboard_model
 * 
 * Classe que realiza o tratamento do tipo atendimento
 * 
 * @package   
 * @author Tiago Silva Costa
 * @version 2014
 * @access public
 */
class Tipo_atendimento_model extends CI_Model{
	
	/**
	 * Tipo_atendimento_model::__construct()
	 * 
	 * @return
	 */
	function __construct(){
		parent::__construct();
	}
    
    /**
    * Tipo_atendimento_model::insere()
    * 
    * Função que realiza a inserção dos dados do tipo atendimento na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function insere(){
		
		$campo = array();
		$valor = array();
        
        #$campo[] = 'criador_tipo_atendimento';
        #$valor[] = $this->session->userdata('cd');
		foreach($_POST as $c => $v){
			
            if($c <> 'cd_tipo_atendimento'){
            
    			#$valorFormatado = $this->util->removeAcentos($this->input->post($c));
                $valorFormatado = $this->util->formaValorBanco(ucfirst($this->input->post($c)));
    			#$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
    			
    			$campo[] = $c;
    			$valor[] = $valorFormatado;
            
            }
            
		}
        
        # A senha inícial fica definida com o CPF
        #$campo[] = 'senha_tipo_atendimento';
		#$valor[] = $this->util->formaValorBanco(md5(str_replace('-', '', str_replace('.', '',$this->input->post('cpf_funcionario')))));
		
		$campos = implode(', ', $campo);
		$valores = implode(', ', $valor);
		
        $this->db->trans_begin();
        
		$sql = "INSERT INTO tipo_atendimento (".$campos.")\n VALUES(".$valores.");";
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
    * Tipo_atendimento_model::atualiza()
    * 
    * Função que realiza a atualização dos dados do tipo atendimento na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function atualiza(){
        
        #$campoValor[] = 'atualizador_tipo_atendimento = '.$this->session->userdata('cd');
        #$campoValor[] = "data_atualizacao_tipo_atendimento = '".date('Y-m-d h:i:s')."'";
        
		foreach($_POST as $c => $v){
			
			if($c != 'cd_tipo_atendimento'){
				#$valorFormatado = $this->util->removeAcentos($this->input->post($c));
                $valorFormatado = $this->util->formaValorBanco(ucfirst($this->input->post($c)));
				#$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
			
				$campoValor[] = $c.' = '.$valorFormatado;
			
			}
		}
		
		$camposValores = implode(', ', $campoValor);
		
        $this->db->trans_begin();
        
		$sql = "UPDATE tipo_atendimento SET ".$camposValores." WHERE cd_tipo_atendimento = ".$this->input->post('cd_tipo_atendimento').";";
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
    * Tipo_atendimento_model::dadosTipo_atendimento()
    * 
    * Função que monta um array com todos os dados do tipo atendimento
    * @param $cd Cd do tipo atendimento para recuperação de dados
    * @return Retorna todos os dados do tipo atendimento
    */
	public function dadosTipo_atendimento($cd){
	
		$this->db->where('cd_tipo_atendimento', $cd);
		$tipo_atendimento = $this->db->get('tipo_atendimento')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $tipo_atendimento[0];
	}
	
    /**
    * Tipo_atendimento_model::camposTipo_atendimento()
    * 
    * Função que pega os nomes de todos os campos existentes na tabela tipo atendimento
    * @return Os campos da tabela tipo atendimento
    */
	public function camposTipo_atendimento(){
		
		$campos = $this->db->get('tipo_atendimento')->list_fields();
		
		return $campos;
		
	}
    
    /**
     * Tipo_atendimento_model::psqTipo_atendimentos()
     * 
     * lista os tipo atendimentos existentes de acordo com os parâmetros informados
     * @param $nome do tipo atendimento que se deseja encontrar
     * @param $pagina Página da paginação
     * @param $mostra_por_pagina Página corrente da paginação
     * 
     * @return A lista dos tipo atendimentos
     */
    public function psqTipo_atendimentos($nome = null, $status = null, $pagina = null, $mostra_por_pagina = null){
        
        $this->db->select("
                            cd_tipo_atendimento,
                            nome_tipo_atendimento,
                            CASE WHEN status_tipo_atendimento = 'A'
                                THEN 'Ativo'
                            ELSE 'Inativo' END AS status_tipo_atendimento
                            ");       
        
        
        if($nome != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "nome_tipo_atendimento LIKE '%".strtoupper($nome)."%' OR email_tipo_atendimento LIKE '%".strtoupper($nome)."%'";
            $this->db->where($condicao);
        }
        
        if($status != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "status_tipo_atendimento = '".$status."'";
            $this->db->where($condicao);
        }
 
        $this->db->order_by("nome_tipo_atendimento", "asc");  
        
        return $this->db->get('tipo_atendimento', $mostra_por_pagina, $pagina)->result();
    }
    
    /**
     * Tipo_atendimento_model::psqQtdTipo_atendimentos()
     * 
     * Consulta a quantidade de tipo atendimentos da pesquisa
     * 
     * @param $nome Nome do tipo atendimento para filtrar a consulta
     * 
     * @param $status Status do tipo atendimento para filtrar a consulta
     * 
     * @return Retorna a quantidade
     */
    public function psqQtdTipo_atendimentos($nome = null, $status = null){
        
        if($nome != '0'){
            $condicao = "nome_tipo_atendimento LIKE '%".strtoupper($nome)."%'";
            $this->db->where($condicao);
        }
        
        if($status != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "status_tipo_atendimento = '".$status."'";
            $this->db->where($condicao);
        }
        
        $this->db->select('count(*) as total');
        return $this->db->get('tipo_atendimento')->result();
    }
    
    /**
     * Tipo_atendimento_model::deleteTipo_atendimento()
     * 
     * Apaga o tipo atendimento
     * 
     * @return Retorna o número de linhas afetadas
     */
    public function deleteTipo_atendimento(){
        
        $sql = "DELETE FROM tipo_atendimento WHERE cd_tipo_atendimento = ".$this->input->post('apg_cd_tipo_atendimento');
        $this->db->query($sql);
        return $this->db->affected_rows();
        
    }
    
    /**
     * Tipo_atendimento_model::tipos_atendimentos()
     * 
     * Lista todos tipo de atendimentos
     * 
     * @return Retorna todos os tipos
     */
    public function tipos_atendimentos($tipoWhere = ''){
        
        if($tipoWhere == 'ASS_CATEGORIA'){ # Tipo de atendimentos associados a categoria
            $this->db->where('cd_tipo_atendimento IN (SELECT DISTINCT sec.cd_tipo_atendimento FROM categ_tipo_atend AS sec)');
        }
        
        $this->db->order_by("nome_tipo_atendimento", "asc"); 
        $this->db->where('status_tipo_atendimento', 'A');
        return $this->db->get('tipo_atendimento')->result();
        
    }

}