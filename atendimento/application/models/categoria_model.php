<?php

/**
 * Dashboard_model
 * 
 * Classe que realiza o tratamento da categoria
 * 
 * @package   
 * @author Tiago Silva Costa
 * @version 2014
 * @access public
 */
class Categoria_model extends CI_Model{
	
	/**
	 * Categoria_model::__construct()
	 * 
	 * @return
	 */
	function __construct(){
		parent::__construct();
	}
    
    /**
    * Categoria_model::insere()
    * 
    * Função que realiza a inserção dos dados do categoria na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function insere(){
		
		$campo = array();
		$valor = array();
        
        #$campo[] = 'criador_categoria';
        #$valor[] = $this->session->userdata('cd');
		foreach($_POST as $c => $v){
			
            if($c <> 'cd_categoria'){
            
    			$valorFormatado = $this->util->removeAcentos($this->input->post($c));
    			$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
    			
    			$campo[] = $c;
    			$valor[] = $valorFormatado;
            
            }
            
		}
        
        # A senha inícial fica definida com o CPF
        #$campo[] = 'senha_categoria';
		#$valor[] = $this->util->formaValorBanco(md5(str_replace('-', '', str_replace('.', '',$this->input->post('cpf_funcionario')))));
		
		$campos = implode(', ', $campo);
		$valores = implode(', ', $valor);
		
        $this->db->trans_begin();
        
		$sql = "INSERT INTO categoria (".$campos.")\n VALUES(".$valores.");";
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
    * Categoria_model::atualiza()
    * 
    * Função que realiza a atualização dos dados do categoria na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function atualiza(){
        
        #$campoValor[] = 'atualizador_categoria = '.$this->session->userdata('cd');
        #$campoValor[] = "data_atualizacao_categoria = '".date('Y-m-d h:i:s')."'";
        
		foreach($_POST as $c => $v){
			
			if($c != 'cd_categoria'){
				$valorFormatado = $this->util->removeAcentos($this->input->post($c));
				$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
			
				$campoValor[] = $c.' = '.$valorFormatado;
			
			}
		}
		
		$camposValores = implode(', ', $campoValor);
		
        $this->db->trans_begin();
        
		$sql = "UPDATE categoria SET ".$camposValores." WHERE cd_categoria = ".$this->input->post('cd_categoria').";";
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
    * Categoria_model::dadosCategoria()
    * 
    * Função que monta um array com todos os dados do categoria
    * @param $cd Cd do categoria para recuperação de dados
    * @return Retorna todos os dados do categoria
    */
	public function dadosCategoria($cd){
	
		$this->db->where('cd_categoria', $cd);
		$categoria = $this->db->get('categoria')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $categoria[0];
	}
	
    /**
    * Categoria_model::camposCategoria()
    * 
    * Função que pega os nomes de todos os campos existentes na tabela categoria
    * @return Os campos da tabela categoria
    */
	public function camposCategoria(){
		
		$campos = $this->db->get('categoria')->list_fields();
		
		return $campos;
		
	}
    
    /**
     * Categoria_model::psqCategorias()
     * 
     * lista os categorias existentes de acordo com os parâmetros informados
     * @param $nome do categoria que se deseja encontrar
     * @param $pagina Página da paginação
     * @param $mostra_por_pagina Página corrente da paginação
     * 
     * @return A lista dos categorias
     */
    public function psqCategorias($nome = null, $status = null, $pagina = null, $mostra_por_pagina = null){
        
        $this->db->select("
                            cd_categoria,
                            nome_categoria,
                            CASE WHEN status_categoria = 'A'
                                THEN 'Ativo'
                            ELSE 'Inativo' END AS status_categoria
                            ");       
        
        
        if($nome != '0'){
            
            $condicao = "nome_categoria LIKE '%".strtoupper($nome)."%' OR email_categoria LIKE '%".strtoupper($nome)."%'";
            $this->db->where($condicao);
        }
        
        if($status != '0'){
            
            $condicao = "status_categoria = '".$status."'";
            $this->db->where($condicao);
        }
        
        $this->db->order_by("nome_categoria", "asc");  
        
        return $this->db->get('categoria', $mostra_por_pagina, $pagina)->result();
    }
    
    /**
     * Categoria_model::psqQtdCategorias()
     * 
     * Consulta a quantidade de categorias da pesquisa
     * 
     * @param $nome Nome do categoria para filtrar a consulta
     * 
     * @param $status Status do categoria para filtrar a consulta
     * 
     * @return Retorna a quantidade
     */
    public function psqQtdCategorias($nome = null, $status = null){
        
        if($nome != '0'){
            $condicao = "nome_categoria LIKE '%".strtoupper($nome)."%'";
            $this->db->where($condicao);
        }
        
        if($status != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "status_categoria = '".$status."'";
            $this->db->where($condicao);
        }
        
        $this->db->select('count(*) as total');
        return $this->db->get('categoria')->result();
    }
    
    /**
     * Categoria_model::deleteCategoria()
     * 
     * Apaga o categoria
     * 
     * @return Retorna o número de linhas afetadas
     */
    public function deleteCategoria(){
        
        $sql = "DELETE FROM categoria WHERE cd_categoria = ".$this->input->post('apg_cd_categoria');
        $this->db->query($sql);
        return $this->db->affected_rows();
        
    }
    
    public function todasCategorias($cd_categoria = false){
        
        if($cd_categoria){
            $this->db->where('cd_categoria', $cd_categoria);
            
        }
        
        $this->db->where('status_categoria', 'A');
        $this->db->order_by("nome_categoria", "asc"); 
        return $this->db->get('categoria')->result();
        
    }
    
    public function categoriasAssTipoAtend(){
        
        $sql = "SELECT 
                * 
                FROM categoria AS pri WHERE pri.cd_categoria IN (
                	SELECT 
                		cd_categoria 
                	FROM categ_tipo_atend AS sec 
                	WHERE sec.cd_tipo_atendimento = ".$this->input->post('cd_tipo_atendimento')."
                )";
                
        return $this->db->query($sql)->result();
        
    }
    
    public function tiposAtendDisp($cd_categoria){
        
        $sql = "SELECT
                	*
                FROM tipo_atendimento AS pri
                WHERE pri.cd_tipo_atendimento NOT IN (
                    SELECT sec.cd_tipo_atendimento 
                        FROM categ_tipo_atend AS sec 
                    WHERE sec.cd_tipo_atendimento = pri.cd_tipo_atendimento
                    AND cd_categoria = ".$cd_categoria."
                )";
                
        return $this->db->query($sql)->result();
        
    }
    
    public function tiposAtendAssoc($cd_categoria){
        
        $sql = "SELECT
                	*
                FROM tipo_atendimento AS pri
                WHERE pri.cd_tipo_atendimento IN (
                    SELECT sec.cd_tipo_atendimento 
                        FROM categ_tipo_atend AS sec 
                    WHERE sec.cd_tipo_atendimento = pri.cd_tipo_atendimento
                    AND cd_categoria = ".$cd_categoria."
                )";
                
        return $this->db->query($sql)->result();
        
    }
    
    public function associaTipoAtendAcateg($tiposAtend){
        
        $this->db->trans_begin();
        
        $sql = "DELETE FROM categ_tipo_atend WHERE cd_categoria = ".$this->input->post('cd_categoria');
        $this->db->query($sql);
        foreach($tiposAtend as $tA){
            
            $sql = "INSERT INTO categ_tipo_atend(cd_tipo_atendimento, cd_categoria) VALUES(".$tA.", ".$this->input->post('cd_categoria').")";
            $this->db->query($sql);
            
        }
        
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
        
    }

}