<?php

/**
 * Dashboard_model
 * 
 * Classe que realiza o tratamento do Dashboard
 * 
 * @package   
 * @author Tiago Silva Costa
 * @version 2014
 * @access public
 */
class Dashboard_model extends CI_Model{
	
	/**
	 * Dashboard_model::__construct()
	 * 
	 * @return
	 */
	function __construct(){
		parent::__construct();
	}
	
    /**
    * Dashboard_model::dadosDashboard()
    * 
    * Função que monta um array com todos os dados do motivo
    * @param $cd Cd do motivo para recuperação de dados
    * @return Retorna todos os dados do motivo
    */
	public function dadosDashboard($cd){
	
		$this->db->where('cd_motivo', $cd);
		$motivo = $this->db->get('motivo')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $motivo[0];
	}
	
    /**
    * Dashboard_model::camposDashboard()
    * 
    * Função que pega os nomes de todos os campos existentes na tabela motivo
    * @return Os campos da tabela motivo
    */
	public function camposDashboard(){
		
		$campos = $this->db->get('motivo')->list_fields();
		
		return $campos;
		
	}
    
    /**
     * Dashboard_model::psqDashboards()
     * 
     * lista os motivos existentes de acordo com os parâmetros informados
     * @param $nome do motivo que se deseja encontrar
     * @param $pagina Página da paginação
     * @param $mostra_por_pagina Página corrente da paginação
     * 
     * @return A lista dos motivos
     */
    public function psqDashboards($nome = null, $status = null, $pagina = null, $mostra_por_pagina = null){
        
        $this->db->select("
                            cd_motivo,
                            nome_motivo,
                            CASE WHEN status_motivo = 'A'
                                THEN 'Ativo'
                            ELSE 'Inativo' END AS status_motivo
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
        
        return $this->db->get('motivo', $mostra_por_pagina, $pagina)->result();
    }
    
    public function atendentesOnline(){
        
        $this->db->select("
                            cd_usuario,
                            nome_usuario,
                            nome_local,
                            online_usuario");
        
        $this->db->join('local', 'local.cd_local = usuario.cd_local');  
        $this->db->where('status_usuario', 'A');   
        $this->db->order_by("nome_usuario", "asc"); 
        $this->db->order_by("nome_local", "asc"); 
                                 
        return $this->db->get('usuario')->result();
        
    }

}