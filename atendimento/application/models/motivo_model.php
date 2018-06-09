<?php

/**
 * Motivo_model
 * 
 * Classe que realiza o tratamento do motivo
 * 
 * @package   
 * @author Tiago Silva Costa
 * @version 2014
 * @access public
 */
class Motivo_model extends CI_Model{
	
	/**
	 * Motivo_model::__construct()
	 * 
	 * @return
	 */
	function __construct(){
		parent::__construct();
	}
    
    /**
    * Motivo_model::insere()
    * 
    * Função que realiza a inserção dos dados do motivo na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function insere(){
		
		$campo = array();
		$valor = array();
        
        #$campo[] = 'criador_motivo';
        #$valor[] = $this->session->userdata('cd');
		foreach($_POST as $c => $v){
			
            if($c <> 'cd_motivo'){
            
    			$valorFormatado = $this->util->removeAcentos($this->input->post($c));
    			$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
    			
    			$campo[] = $c;
    			$valor[] = $valorFormatado;
            
            }
            
		}
        
        # A senha inícial fica definida com o CPF
        #$campo[] = 'senha_motivo';
		#$valor[] = $this->util->formaValorBanco(md5(str_replace('-', '', str_replace('.', '',$this->input->post('cpf_funcionario')))));
		
		$campos = implode(', ', $campo);
		$valores = implode(', ', $valor);
		
        $this->db->trans_begin();
        
		$sql = "INSERT INTO motivo (".$campos.")\n VALUES(".$valores.");";
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
    * Motivo_model::atualiza()
    * 
    * Função que realiza a atualização dos dados do motivo na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function atualiza(){
        
        #$campoValor[] = 'atualizador_motivo = '.$this->session->userdata('cd');
        #$campoValor[] = "data_atualizacao_motivo = '".date('Y-m-d h:i:s')."'";
        
        if($this->input->post('prazo_motivo') == '0'){
            $sql = "DELETE FROM grupo_resolucao_motivo WHERE cd_motivo = ".$this->input->post('cd_motivo');
            $this->db->query($sql);
        }
        
		foreach($_POST as $c => $v){
			
			if($c != 'cd_motivo'){
				$valorFormatado = $this->util->removeAcentos($this->input->post($c));
				$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
			
				$campoValor[] = $c.' = '.$valorFormatado;
			
			}
		}
		
		$camposValores = implode(', ', $campoValor);
		
        $this->db->trans_begin();
        
		$sql = "UPDATE motivo SET ".$camposValores." WHERE cd_motivo = ".$this->input->post('cd_motivo').";";
        
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
    * Motivo_model::dadosMotivo()
    * 
    * Função que monta um array com todos os dados do motivo
    * @param $cd Cd do motivo para recuperação de dados
    * @return Retorna todos os dados do motivo
    */
	public function dadosMotivo($cd){
	
		$this->db->where('cd_motivo', $cd);
		$motivo = $this->db->get('motivo')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $motivo[0];
	}
	
    /**
    * Motivo_model::camposMotivo()
    * 
    * Função que pega os nomes de todos os campos existentes na tabela motivo
    * @return Os campos da tabela motivo
    */
	public function camposMotivo(){
		
		$campos = $this->db->get('motivo')->list_fields();
		
		return $campos;
		
	}
    
    /**
     * Motivo_model::psqMotivos()
     * 
     * lista os motivos existentes de acordo com os parâmetros informados
     * @param $nome do motivo que se deseja encontrar
     * @param $pagina Página da paginação
     * @param $mostra_por_pagina Página corrente da paginação
     * 
     * @return A lista dos motivos
     */
    public function psqMotivos($nome = null, $status = null, $pagina = null, $mostra_por_pagina = null){
        
        $this->db->select("
                            cd_motivo,
                            nome_motivo,
                            CASE WHEN status_motivo = 'A'
                                THEN 'Ativo'
                            ELSE 'Inativo' END AS status_motivo,
                            nome_tipo_atendimento,
                            nome_categoria
                            ");       
        
        
        if($nome != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "nome_motivo LIKE '%".strtoupper($nome)."%' OR email_motivo LIKE '%".strtoupper($nome)."%'";
            $this->db->where($condicao);
        }
        
        if($status != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "status_motivo = '".$status."'";
            $this->db->where($condicao);
        }
 
        $this->db->order_by("nome_motivo", "asc");  
        $this->db->join('tipo_atendimento', 'tipo_atendimento.cd_tipo_atendimento = motivo.cd_tipo_atendimento', 'left'); 
        $this->db->join('categoria', 'categoria.cd_categoria = motivo.cd_categoria', 'left'); 
        
        return $this->db->get('motivo', $mostra_por_pagina, $pagina)->result();
    }
    
    /**
     * Motivo_model::psqQtdMotivos()
     * 
     * Consulta a quantidade de motivos da pesquisa
     * 
     * @param $nome Nome do motivo para filtrar a consulta
     * 
     * @param $status Status do motivo para filtrar a consulta
     * 
     * @return Retorna a quantidade
     */
    public function psqQtdMotivos($nome = null, $status = null){
        
        if($nome != '0'){
            $condicao = "nome_motivo LIKE '%".strtoupper($nome)."%'";
            $this->db->where($condicao);
        }
        
        if($status != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "status_motivo = '".$status."'";
            $this->db->where($condicao);
        }
        
        $this->db->select('count(*) as total');
        return $this->db->get('motivo')->result();
    }
    
    /**
     * Motivo_model::deleteMotivo()
     * 
     * Apaga o motivo
     * 
     * @return Retorna o número de linhas afetadas
     */
    public function deleteMotivo(){
        
        $sql = "DELETE FROM motivo WHERE cd_motivo = ".$this->input->post('apg_cd_motivo');
        $this->db->query($sql);
        return $this->db->affected_rows();
        
    }
    
    public function registraGrupoMotivo($grupo){
        
        $this->db->trans_begin();
        
        $sql = "DELETE FROM grupo_motivo WHERE cd_motivo = ".$this->input->post('cd_motivo');
        $this->db->query($sql);
        
        foreach($grupo as $gru){
            
            $sql = "INSERT INTO grupo_motivo (cd_motivo, cd_usuario) VALUES(".$this->input->post('cd_motivo').", ".$gru.");";
            $this->db->query($sql);
            
        }
        
        if($this->db->trans_status() === TRUE){
            $this->db->trans_commit();
        }else{
            $this->db->trans_rollback();
        }
        
    }
    
    public function todosMotivos($motivos = false, $prazoZero = false){
        
        if($motivos){
            $this->db->where('cd_motivo NOT IN ('.$motivos.')');
        }
        
        if($this->input->post('cd_categoria') and $this->input->post('cd_tipo_atendimento')){
            $this->db->where('cd_categoria', $this->input->post('cd_categoria'));
            $this->db->where('cd_tipo_atendimento', $this->input->post('cd_tipo_atendimento'));
        }
        
        $this->db->where('status_motivo', 'A');
        
        if($prazoZero == true){
            $this->db->where('prazo_motivo <> 0');
        }
        $this->db->order_by("nome_motivo", "asc");
        return $this->db->get('motivo')->result();
        
    }
    
    public function motivoCategoria($cd_categoria = false){
        
        if($cd_categoria){
            $this->db->where('cd_categoria', $cd_categoria);
        }
        
        $this->db->order_by("nome_motivo", "asc");
        $this->db->where('status_motivo', 'A');
        return $this->db->get('motivo')->result();
        
    }
    
    public function pegaPrazo($cd_motivo = false){
        
        $this->db->select("prazo_motivo");
        
        if($cd_motivo){
            
            $this->db->where('cd_motivo', $cd_motivo);
            
        }else{
            
            $this->db->where('cd_motivo', $this->input->post('cd_motivo'));
            
        }

        return $this->db->get('motivo')->result();
        
    }

}