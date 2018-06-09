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
                            usuario.cd_usuario,
                            nome_usuario,
                            nome_local,
                            online_usuario");
         
        $this->db->where('status_usuario', 'A');   
        $this->db->order_by("nome_usuario", "asc"); 
        $this->db->order_by("nome_local", "asc"); 
        $this->db->join('atendimento.config_usuario', 'usuario.cd_usuario = atendimento.config_usuario.cd_usuario', 'left');
        $this->db->join('local', 'local.cd_local = config_usuario.cd_local'); 
                                 
        return $this->db->get('adminti.usuario')->result();
        
    }
    
    public function qtdAtendPrazo(){
        
        $sql = "SELECT 
                	COUNT(*) AS qtd_no_prazo,
                    (
                    	SELECT 
                    		COUNT(*) AS qtd_atendimentos
                    	FROM atendimento AS sec
                    	WHERE
                    	sec.cd_status_atendimento <> 1
                    	#AND sec.cd_atendimento = pri.cd_atendimento
                    	AND sec.cd_local = pri.cd_local
                    	AND TIMEDIFF(
                    		sec.data_fim_atendimento,
                    		sec.data_inicio_atendimento
                    	) > '00:30:00' # Superou 30 minutos*/
                    
                    ) AS qtd_fora_prazo,
                    CONCAT(sigla_estado,' - ',nome_municipio) AS local
                FROM atendimento AS pri
                #INNER JOIN tipo_atendimento ON tipo_atendimento.cd_tipo_atendimento = pri.cd_tipo_atendimento
                #INNER JOIN categoria ON categoria.cd_categoria = pri.cd_categoria
                INNER JOIN local ON local.cd_local = pri.cd_local
                INNER JOIN municipio ON municipio.cd_municipio = local.cd_municipio
                INNER JOIN estado ON estado.cd_estado = municipio.cd_estado
                #INNER JOIN status_atendimento ON status_atendimento.cd_status_atendimento = pri.cd_status_atendimento
                WHERE
                SUBSTR(data_inicio_atendimento, 1, 7) = SUBSTR(CURDATE(), 1, 7)
                AND pri.cd_status_atendimento <> 1
                AND TIMEDIFF(
                	pri.data_fim_atendimento,
                	pri.data_inicio_atendimento
                ) < '00:30:00' # Superou 30 minutos*/
                GROUP BY nome_local";
                
        return $this->db->query($sql)->result();
        
    }
    
    public function tempoMedioAtendimento(){
        
        $sql = "SELECT
                	SUBSTR(
                		SEC_TO_TIME( 
                			SUM( 
                						TIME_TO_SEC(
                							TIMEDIFF(
                									data_inicio_atendimento,
                									data_chegada_atendimento
                								)
                						) 
                			) / COUNT(*)
                		)
                	, 1, 5) AS tempo_medio_espera,
                	SUBSTR(
                		SEC_TO_TIME( 
                			SUM( 
                						TIME_TO_SEC(
                							TIMEDIFF(
                									data_fim_atendimento,
                									data_inicio_atendimento
                								)
                						) 
                			) / COUNT(*)
                		)
                	, 1, 5) AS tempo_medio_atendimento,
                CONCAT(sigla_estado,' - ',nome_municipio) AS local
                FROM atendimento 
                INNER JOIN local ON local.cd_local = atendimento.cd_local
                INNER JOIN municipio ON municipio.cd_municipio = local.cd_municipio
                INNER JOIN estado ON estado.cd_estado = municipio.cd_estado
                WHERE #cd_atendimento IN (422,423,424)
                	SUBSTR(data_inicio_atendimento, 1, 7) = SUBSTR(CURDATE(), 1, 7)
                	AND cd_status_atendimento <> 1
                GROUP BY nome_local";
                
        return $this->db->query($sql)->result();
        
    }
    
    public function qtdAtendentesOnline(){
        
        $sql = "SELECT 
                	COUNT(*) AS atendente_online,
                	(
                		SELECT COUNT(*) FROM atendimento.config_usuario WHERE online_usuario = 'N' AND atendente_usuario = 'S' AND status_config_usuario = 'A'
                	) AS atendente_offline
                FROM atendimento.config_usuario WHERE 
                online_usuario = 'S' AND atendente_usuario = 'S' AND status_config_usuario = 'A'";
                
        return $this->db->query($sql)->result();
        
    }

}