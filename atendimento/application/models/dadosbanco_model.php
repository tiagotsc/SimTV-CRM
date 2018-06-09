<?php

/**
 * DadosBanco_model
 * 
 * Classe que realiza consultas genéricas no banco
 * 
 * @package   
 * @author Tiago Silva Costa
 * @version 2014
 * @access public
 */
class DadosBanco_model extends CI_Model{
	
	/**
	 * DadosBanco_model::__construct()
	 * 
	 * @return
	 */
	function __construct(){
		parent::__construct();
	}
    
    /**
    * DadosBanco_model::status()
    * 
    * Função que pega o status
    * @return Retorna os status existentes
    */
	public function status(){
		return $this->db->get('status')->result();
	}
    
    /**
    * DadosBanco_model::menu()
    * 
    * Função que pega os dados do menu
    * @return Retorna o menu
    */
	public function menu($permitidos = false){
	   
       if($permitidos){
            $this->db->where("cd_permissao IN (".implode(',',$permitidos).")");
        }
       
        $this->db->where("status_menu =  'A'");
        $this->db->order_by("ordem_menu", "asc"); 
		return $this->db->get('menu')->result();
	}
    
    /**
    * DadosBanco_model::paisMenu()
    * 
    * Função que pega o id dos menus pai
    * @return Retorna todos os dados do menu
    */
	public function paisMenu($permitidos = false){
	
        if($permitidos){
            $this->db->where("cd_permissao IN (".implode(',',$permitidos).")");
        }
    
        $this->db->distinct();
        $this->db->select('pai_menu');
		$this->db->where('pai_menu <> 0');
        $this->db->where("status_menu =  'A'");
		$paisMenu = $this->db->get('menu')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $paisMenu;
	}
    
    /**
    * DadosBanco_model::permissoes()
    * 
    * Função que pega os dados das permissões
    * @return Retorna as permissões
    */
	public function permissoes(){
	   
        $this->db->where("status_permissao =  'A'");
        $this->db->order_by("nome_permissao", "asc"); 
        #$this->db->order_by("ordem_permissao", "asc"); 
		return $this->db->get('permissao')->result();
	}
    
    /**
    * DadosBanco_model::paiPermissao()
    * 
    * Função que pega o id das permissões pai
    * @return Retorna todos os dados das permissões
    */
	public function paiPermissao(){
	
        $this->db->distinct();
        $this->db->select('pai_permissao');
		$this->db->where('pai_permissao <> 0');
        $this->db->where("status_permissao =  'A'");
		$paisPemissao = $this->db->get('permissao')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $paisPemissao;
	}
    
    /**
    * DadosBanco_model::departamento()
    * 
    * Função que pega os departamento
    * @return Retorna os departamentos ativos
    */
	public function departamento(){
	   
        $this->db->where("status_departamento =  'A'");
        $this->db->order_by("nome_departamento", "asc"); 
        
		return $this->db->get('adminti.departamento')->result();
        
	}
    
    /**
    * DadosBanco_model::estado()
    * 
    * Função que pega os estados
    * @return Retorna os estados
    */
	public function estado(){
	   
        $this->db->order_by("nome_estado", "asc"); 
		return $this->db->get('adminti.estado')->result();
	}
    
    /**
    * DadosBanco_model::parametro()
    * 
    * Função que pega os parâmetros
    * @return Retorna os parâmetros
    */
	public function parametro(){
	   
        $this->db->where("status_parametro =  'A'");
        #$this->db->order_by("nome_departamento", "asc"); 
        
		return $this->db->get('parametro')->result();
        
	}
    
    /**
    * DadosBanco_model::municipio()
    * 
    * Função que pega os municípios
    * @return Retorna os municípios
    */
	public function municipio(){
	   
        $this->db->order_by("nome_municipio", "asc"); 
		return $this->db->get('municipio')->result();
	}
    
    public function timezoneLocal($cd){
        
        $this->db->select("cd_local, nome_local, nome_municipio, timezone_municipio, ddd_municipio, impressora_local");
        $this->db->where("cd_local", $cd);
        
        $this->db->join("municipio", "municipio.cd_municipio = local.cd_municipio");
        $this->db->order_by("nome_local", "asc"); 
		return $this->db->get('local')->result();
        
    }
    
    /**
    * DadosBanco_model::departamentoAssociados()
    * 
    * Função que pega os departamento
    * @return Retorna os departamentos ativos
    */
	public function departamentoAssociados(){
	   
        $sql = "SELECT
                    cd_departamento,
                    nome_departamento
                FROM adminti.departamento
                WHERE 
                status_departamento = 'A'
                AND cd_departamento IN (
                	SELECT DISTINCT cd_departamento FROM adminti.usuario WHERE cd_departamento IS NOT NULL
                )
                ORDER BY nome_departamento ASC";
        
		return $this->db->query($sql)->result();  
        
	}

}