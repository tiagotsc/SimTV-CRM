<?php

/**
 * Dashboard_model
 * 
 * Classe que realiza o tratamento do atendimento
 * 
 * @package   
 * @author Tiago Silva Costa
 * @version 2014
 * @access public
 */
class Atendimento_model extends CI_Model{
	
	/**
	 * Atendimento_model::__construct()
	 * 
	 * @return
	 */
	function __construct(){
		parent::__construct();
	}
    
    /**
    * Atendimento_model::insere()
    * 
    * Função que realiza a inserção dos dados do atendimento na base de dados
    * @return O número de linhas afetadas pela operação
    */
	/*public function insere(){
		
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
        
	}*/
    
    /**
     * Atendimento_model::psqAtendimentos()
     * 
     * lista os atendimentos existentes de acordo com os parâmetros informados
     * @param $nome do atendimento que se deseja encontrar
     * @param $pagina Página da paginação
     * @param $mostra_por_pagina Página corrente da paginação
     * 
     * @return A lista dos atendimentos
     */
    /*public function psqAtendimentos($nome = null, $status = null, $pagina = null, $mostra_por_pagina = null){
        
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
    }*/
    
    /**
     * Atendimento_model::psqQtdAtendimentos()
     * 
     * Consulta a quantidade de atendimentos da pesquisa
     * 
     * @param $nome Nome do atendimento para filtrar a consulta
     * 
     * @param $status Status do atendimento para filtrar a consulta
     * 
     * @return Retorna a quantidade
     */
    /*public function psqQtdAtendimentos($nome = null, $status = null){
        
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
    }*/
    
    /**
     * Atendimento_model::deleteAtendimento()
     * 
     * Apaga o atendimento
     * 
     * @return Retorna o número de linhas afetadas
     */
    /*public function deleteAtendimento(){
        
        $sql = "DELETE FROM guiche WHERE cd_guiche = ".$this->input->post('apg_cd_guiche');
        $this->db->query($sql);
        return $this->db->affected_rows();
        
    }*/
    
    /**
     * Atendimento_model::atendimentos()
     * 
     * Pega todos os atendimentos ativos
     * 
     * @return Retorna os atendimentos
     */
    /*public function atendimentos(){
        
        $this->db->where("status_guiche", "A");
        return $this->db->get('guiche')->result();
        
    }*/
    
###########################################################
    
    /**
     * Atendimento_model::registraSenha()
     * 
     * Registra a entrada da senha
     * 
     * @return boleano
     */
    public function registraSenha(){
        
        date_default_timezone_set($this->input->post('timezone'));
        
        $contSenha = $this->verificaUltimasSenhas();
        
        if($contSenha == 0){ // Se não há senha cadastrada a menos de 5 minutos
            
            $id = $this->idProximoAtendimento();
            
            $protocolo = date('Ymd').str_pad($id, 6, "0", STR_PAD_LEFT);
            
            $this->db->trans_begin();
        
            $cliente = strtoupper($this->util->removeAcentos($this->input->post('senhaCaptada')));
        
    		$sql = "INSERT INTO atendimento (cd_local, protocolo_atendimento, senha_atendimento, nome_atendimento, cd_status_atendimento, data_chegada_atendimento)";
            $sql .= " VALUES(".$this->input->post('local').", '".$protocolo."', 500, '".$cliente."', 1, '".date('Y-m-d H:i:s')."');";
    		$this->db->query($sql);
            
            $id = $this->db->insert_id();
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                return false;
            }
            else
            {
                $this->db->trans_commit();
                return $id;
            }
            
        }else{
            
            return 'repetida'; 
            
        }
        
    }
    
    /**
     * Atendimento_model::atendimentos()
     * 
     * Verifica se a senha informada foi registrada nos últimos 5 minutos
     * 
     * @return Retorna os atendimentos
     */
    public function verificaUltimasSenhas(){
        
        date_default_timezone_set($this->input->post('timezone'));
        
        $this->db->where('senha_atendimento', $this->input->post('senhaCaptada'));
        $this->db->where('cd_local', $this->input->post('local'));
        #$this->db->where('senha_atendimento', 1);
        #$this->db->where('cd_local', 1);
        $condicao = "data_chegada_atendimento BETWEEN DATE_ADD('".date('Y-m-d H:i:s')."', INTERVAL -5 MINUTE) AND '".date('Y-m-d H:i:s')."'";
        $this->db->where($condicao);
        $this->db->from('atendimento');
        return $this->db->count_all_results();
        
    } 
    
    /**
     * Atendimento_model::geraSenha()
     * 
     * Gera a senha para o atendimento
     * 
     * @return Retorna os atendimentos
     */
    public function geraSenha(){
        
        date_default_timezone_set($this->input->post('timezone'));
        
        $senha = $this->ultimaSenha() + 1;
        
        $id = $this->idProximoAtendimento();
        
        // Protocolo 150127000164 (ano, mês, dia id com formatado com 6 digitos)
        $protocolo = date('Ymd').str_pad($id, 6, "0", STR_PAD_LEFT);
        
        // Se o cliente for chamado pelo nome
        if(trim($this->input->post('nome')) <> '' and trim($this->input->post('sobrenome')) <> ''){
            
            $nomeCampo = ', nome_atendimento';
            
            $nome = strtoupper($this->util->removeAcentos(trim($this->input->post('nome'))));
            
            $sobrenome = strtoupper($this->util->removeAcentos(trim($this->input->post('sobrenome'))));                               
                        
            $nomeConteudo = ", '".$nome.' '.$sobrenome."'";
            
        }
            
        $this->db->trans_begin();
        
		$sql = "INSERT INTO atendimento (cd_local, protocolo_atendimento, senha_atendimento ".$nomeCampo.", cd_status_atendimento, data_chegada_atendimento)";
        $sql .= " VALUES(".$this->input->post('local').", '".$protocolo."', ".$senha.$nomeConteudo.", 1, '".date('Y-m-d H:i:s')."');";
		$this->db->query($sql);
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return $senha;
        }
        
    }
    
    /**
     * Atendimento_model::ultimaSenha()
     * 
     * Pega a última senha registrada de acordo com a localidade
     * 
     * @return Retorna a última senha
     */
    public function ultimaSenha(){
        
        $this->db->select_max('senha_atendimento');
        $this->db->where('cd_local', $this->input->post('local'));
        //$this->db->where('cd_status_atendimento', 1);
        $this->db->like('data_chegada_atendimento', date('Y-m-d'), 'after'); 
        return $this->db->get('atendimento')->row()->senha_atendimento;
        
    } 
    
    /**
     * Atendimento_model::chamaProximaSenha()
     * 
     * Atualiza a senha para o status de atendendo
     * 
     * @return Retorna a senha no caso de sucesso ou false no caso de nenhuma senha encontrada
     */
    public function chamaProximaSenha(){
        
        date_default_timezone_set($this->input->post('timezone'));
        
        $senha = $this->primeiraSenha();
        
        if($senha){
            
            $this->db->trans_begin();
            
            $sql = "UPDATE atendimento SET cd_status_atendimento = 5, data_inicio_atendimento = '".date('Y-m-d H:i:s')."', cd_atendente = ".$this->input->post('atendente').", cd_local = ".$this->input->post('local').", cd_guiche = ".$this->input->post('guiche')." WHERE cd_atendimento = ".$senha[0]->cd_atendimento;
            $this->db->query($sql);
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                return false;
            }
            else
            {
                $this->db->trans_commit();
                return $senha;
            }
            
        }else{
            
            return false;
            
        }
        
    }  
    
    /**
     * Atendimento_model::primeiraSenha()
     * 
     * Verifica a primeira senha caso exista registrada para atendimento
     * 
     * @return Retorna o cd da primeira senha em caso de sucesso
     */
    public function primeiraSenha(){
        
        $this->db->select_min('cd_atendimento');
        $this->db->where('cd_local', $this->input->post('local'));
        $this->db->where('cd_status_atendimento', 1);
        $this->db->like('data_chegada_atendimento', date('Y-m-d'), 'after'); 
        $minAt = $this->db->get('atendimento')->row()->cd_atendimento;
        
        if($minAt){
            
            $this->db->select('cd_atendimento, senha_atendimento');
            $this->db->where('cd_atendimento', $minAt);
            return $this->db->get('atendimento')->result();
            
        }else{
            
            return false;
            
        }
        
    }
    
    /**
    * Atendimento_model::dadosAtendimento()
    * 
    * Função que monta um array com todos os dados do atendimento
    * @param $cd Cd do atendimento para recuperação de dados
    * @return Retorna todos os dados do atendimento
    */
	public function dadosAtendimento($cd){
	       
        $this->db->select("cd_atendimento,
                            TIMEDIFF(data_inicio_atendimento,data_chegada_atendimento) AS tempo_espera, 
                            DATE_FORMAT(data_chegada_atendimento,'%d/%m/%Y') AS data_atendimento,
                            DATE_FORMAT(data_inicio_atendimento,'%Y-%m-%d') AS data_atendimento_banco,
                            SUBSTR(data_chegada_atendimento, 12, 9) AS hora_chegada,
                            SUBSTR(data_inicio_atendimento, 12, 9) AS hora_inicio,
                            TIMEDIFF(CURRENT_TIMESTAMP(), data_inicio_atendimento) AS tempo_atendimento,
                            cd_tipo_atendimento,
                            cd_status_atendimento,
                            cd_categoria,
                            cd_motivo,
                            descricao_atendimento,
                            resolucao_atendimento,
                            CASE WHEN nome_atendimento IS NOT NULL
                                THEN nome_atendimento
                            ELSE senha_atendimento END AS senha_atendimento");
		$this->db->where('cd_atendimento', $cd);
		$atendimento = $this->db->get('atendimento')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $atendimento[0];
	}
	
    /**
    * Atendimento_model::camposAtendimento()
    * 
    * Função que pega os nomes de todos os campos existentes na tabela atendimento
    * @return Os campos da tabela atendimento
    */
	public function camposAtendimento(){
		
		$campos = $this->db->get('atendimento')->list_fields();
		
		return $campos;
		
	}
    
    /**
    * Atendimento_model::atualiza()
    * 
    * Função que realiza a atualização dos dados do atendimento na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function finaliza(){
        
        date_default_timezone_set($this->session->userdata('localTimeZone'));
        
        # O atendimento esta sendo resolvido
        if($this->input->post("prazo") == 0 || $this->input->post("resolve") == 'sim'){
        
            $campoValor[] = 'cd_resolvedor = '.$this->session->userdata('cd');
            $campoValor[] = "data_resolucao_atendimento = '".date('Y-m-d H:i:s')."'";
            $campoValor[] = "cd_status_atendimento = 3";
        
        }else{ # O atendimento não esta sendo resolvido
            
            $campoValor[] = "cd_resolvedor = NULL";
            $campoValor[] = "data_resolucao_atendimento = NULL";
            $campoValor[] = "cd_status_atendimento = 2";
            
        }
        
        # O atendimento esta sendo finalizado pelo atendente
        if($this->input->post('editar') == 'nao'){
        
            $campoValor[] = "data_fim_atendimento = '".date('Y-m-d H:i:s')."'";
        
        }
        
		foreach($_POST as $c => $v){
			
			if($c != 'cd_atendimento' and $c != 'prazo' and $c != 'editar' and $c != 'data_inicio_atendimento' and $c != 'resolve'){
				$valorFormatado = $this->util->removeAcentos($this->input->post($c));
				$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
			
				$campoValor[] = $c.' = '.$valorFormatado;
			
			}
		}
		
		$camposValores = implode(', ', $campoValor);
		
        $this->db->trans_begin();
        
		$sql = "UPDATE atendimento SET ".$camposValores." WHERE cd_atendimento = ".$this->input->post('cd_atendimento').";";
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
		
	}
    
    /**
    * Atendimento_model::idProximoAtendimento()
    * 
    * Função que pega o id do próximo atendimento
    * @return Retorna o ID
    */
    public function idProximoAtendimento(){
        
        $this->db->select_max('cd_atendimento');
        $ultimo = $this->db->get('atendimento')->row()->cd_atendimento;
        $proximo = $ultimo + 1;
        
        /*$ultimo = $this->db->query("SHOW TABLE STATUS LIKE 'atendimento'");
        $proximo = $ultimo->Auto_increment + 1;*/
        
        /*$sql = "SELECT 
                    AUTO_INCREMENT
                FROM  INFORMATION_SCHEMA.TABLES
                WHERE TABLE_SCHEMA = 'atendimento'
                AND   TABLE_NAME   = 'atendimento'";
        $ultimo = $this->db->query($sql);
        $proximo = $ultimo[0]->AUTO_INCREMENT + 1;*/
        
        return $proximo;
        
    }
    
    /**
    * Atendimento_model::atendimentosDoDia()
    * 
    * Lista todos os atendimentos realizados no dia para o atendente
    * @return Retorna a lista
    */
    public function atendimentosDoDia(){
        
        $this->db->select("cd_atendimento,
                            TIMEDIFF(data_inicio_atendimento,data_chegada_atendimento) AS tempo_espera, 
                            TIMEDIFF(data_fim_atendimento, data_inicio_atendimento) AS tempo_atendimento, 
                            DATE_FORMAT(data_chegada_atendimento,'%d/%m/%Y') AS data_atendimento,
                            SUBSTR(data_chegada_atendimento, 12, 9) AS hora_chegada,
                            SUBSTR(data_inicio_atendimento, 12, 9) AS hora_inicio,
                            SUBSTR(data_fim_atendimento, 12, 9) AS hora_fim,
                            cd_tipo_atendimento,
                            cd_status_atendimento,
                            cd_categoria,
                            cd_motivo,
                            descricao_atendimento,
                            resolucao_atendimento,
                            senha_atendimento");
		$this->db->where('cd_atendente', $this->session->userdata('cd'));
        $this->db->like('data_chegada_atendimento', date('Y-m-d'), 'after');
        $this->db->order_by("cd_atendimento", "asc");
		return $this->db->get('atendimento')->result();
        
    }
    
    /**
    * Atendimento_model::atendimentosPendentes()
    * 
    * Lista todos os atendimentos pendentes de resolução
    * @return Retorna a lista
    */
    public function atendimentosPendentes(){
        
        $this->db->select("	cd_atendimento,
                        	TIMEDIFF(data_inicio_atendimento,data_chegada_atendimento) AS tempo_espera, 
                        	TIMEDIFF(data_fim_atendimento, data_inicio_atendimento) AS tempo_atendimento, 
                        	DATE_FORMAT(data_chegada_atendimento,'%d/%m/%Y') AS data_atendimento,
                        	SUBSTR(data_chegada_atendimento, 12, 9) AS hora_chegada,
                        	SUBSTR(data_inicio_atendimento, 12, 9) AS hora_inicio,
                        	SUBSTR(data_fim_atendimento, 12, 9) AS hora_fim,
                        	atendimento.cd_tipo_atendimento,
                        	cd_status_atendimento,
                        	atendimento.cd_categoria,
                        	atendimento.cd_motivo,
                        	descricao_atendimento,
                        	resolucao_atendimento,
                        	senha_atendimento,
                        	nome_motivo,
                        	prazo_motivo");
		$this->db->where('cd_usuario', $this->session->userdata('cd'));
        $this->db->where('cd_status_atendimento', 2);
        $this->db->join('motivo', 'motivo.cd_motivo = atendimento.cd_motivo');
        $this->db->join('grupo_resolucao_motivo', 'grupo_resolucao_motivo.cd_motivo = motivo.cd_motivo');
        $this->db->join('grupo_resolucao_usuario', 'grupo_resolucao_usuario.cd_grupo_resolucao = grupo_resolucao_motivo.cd_grupo_resolucao');
        $this->db->order_by("data_inicio_atendimento", "asc");
        $this->db->order_by("prazo_motivo", "asc");
		return $this->db->get('atendimento')->result();
        
    }
    
    /**
    * Atendimento_model::atendimentosResolvidos()
    * 
    * Lista todos os atendimentos resolvidos
    * @return Retorna a lista
    */
    public function atendimentosResolvidos(){
        
        $this->db->select("	cd_atendimento,
                        	TIMEDIFF(data_inicio_atendimento,data_chegada_atendimento) AS tempo_espera, 
                        	TIMEDIFF(data_fim_atendimento, data_inicio_atendimento) AS tempo_atendimento, 
                        	DATE_FORMAT(data_chegada_atendimento,'%d/%m/%Y') AS data_atendimento,
                            DATE_FORMAT(data_resolucao_atendimento,'%d/%m/%Y') AS data_resolucao,
                            data_resolucao_atendimento AS data_resolucao_banco,
                        	SUBSTR(data_chegada_atendimento, 12, 9) AS hora_chegada,
                        	SUBSTR(data_inicio_atendimento, 12, 9) AS hora_inicio,
                        	SUBSTR(data_fim_atendimento, 12, 9) AS hora_fim,
                        	atendimento.cd_tipo_atendimento,
                        	cd_status_atendimento,
                        	atendimento.cd_categoria,
                        	atendimento.cd_motivo,
                        	descricao_atendimento,
                        	resolucao_atendimento,
                        	senha_atendimento,
                        	nome_motivo,
                        	prazo_motivo");
		$this->db->where('cd_usuario', $this->session->userdata('cd'));
        $this->db->where('cd_status_atendimento', 3);
        $this->db->join('motivo', 'motivo.cd_motivo = atendimento.cd_motivo');
        $this->db->join('grupo_resolucao_motivo', 'grupo_resolucao_motivo.cd_motivo = motivo.cd_motivo');
        $this->db->join('grupo_resolucao_usuario', 'grupo_resolucao_usuario.cd_grupo_resolucao = grupo_resolucao_motivo.cd_grupo_resolucao');
        $this->db->order_by("data_inicio_atendimento", "asc");
        $this->db->order_by("prazo_motivo", "asc");
		return $this->db->get('atendimento')->result();
        
    }
    
    /**
    * Atendimento_model::desconsiderar()
    * 
    * Desconsidera o atendimento
    * @return bool
    */
    public function desconsiderar(){
        
        $this->db->trans_begin();
        $sql = "UPDATE atendimento SET cd_status_atendimento = 6, cd_tipo_atendimento = null, cd_categoria = null, cd_motivo = null, descricao_atendimento = null, resolucao_atendimento = null, data_inicio_atendimento = null, data_fim_atendimento = null, cd_atendente = ".$this->session->userdata('cd')." WHERE cd_atendimento = ".$this->input->post('cd_atendimento').";"; 
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
        
    }
    
    /**
    * Atendimento_model::chamaNovamente()
    * 
    * Chama novamente determinada senha
    * @return bool
    */
    public function chamaNovamente($cd){
        
        date_default_timezone_set($this->session->userdata('localTimeZone'));
        
        $this->db->trans_begin();
        
		$sql = "UPDATE atendimento SET cd_status_atendimento = 5, data_inicio_atendimento = '".date('Y-m-d H:i:s')."', cd_atendente = ".$this->session->userdata('cd').", cd_local = ".$this->session->userdata('cd_local').", cd_guiche = ".$this->session->userdata('cd_guiche')." WHERE cd_atendimento = ".$cd;
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
        
    }
    
    /**
    * Atendimento_model::senhaSolicitada()
    * 
    * Pega o cd da senha solicitada caso ela exista
    * @return cd_atendimento
    */
    public function senhaSolicitada(){
        
        $this->db->select("cd_atendimento");
        $this->db->where('cd_status_atendimento IN (1,6)');
        $this->db->where('cd_local',$this->session->userdata('cd_local'));
        $this->db->where('senha_atendimento', $this->input->post('senha_atendimento'));
        $this->db->or_where('nome_atendimento', $this->input->post('senha_atendimento'), 'after'); 
        $this->db->like('data_chegada_atendimento', date('Y-m-d'), 'after');
        $this->db->order_by("cd_atendimento", "DESC");
		return $this->db->get('atendimento')->row()->cd_atendimento;
        
    }
    
    public function pegaClienteAtp(){
        
        $dadoPesquisa = strtoupper($this->util->removeAcentos(str_ireplace('.', '', str_ireplace('-', '', $this->input->get('term')))));
		$condicao = "data_chegada_atendimento LIKE '".date('Y-m-d')."%' ";
        $condicao .= "AND (nome_atendimento LIKE '".$dadoPesquisa."%' OR senha_atendimento = '".$dadoPesquisa."')";
        
        $this->db->select('nome_atendimento AS value');
        #$this->db->like('data_chegada_atendimento', date('Y-m-d'), 'after');
        $this->db->where('cd_status_atendimento IN (1,6)');
		$this->db->where($condicao);
        $this->db->limit(30);
        
        $this->db->order_by("nome_atendimento", "asc"); 
            
		return $this->db->get('atendimento')->result();
        
    }
    
    /**
    * Atendimento_model::iniciaAtendimento()
    * 
    * Atualiza o atendimento para o status de atendendo
    * @return bool
    */
    public function iniciaAtendimento($cd_atendimento){
        
        date_default_timezone_set($this->session->userdata('localTimeZone'));
        
        $this->db->trans_begin();
            
        $sql = "UPDATE atendimento SET cd_status_atendimento = 5, data_inicio_atendimento = '".date('Y-m-d H:i:s')."', cd_atendente = ".$this->session->userdata('cd').", cd_local = ".$this->session->userdata('cd_local').", cd_guiche = ".$this->session->userdata('cd_guiche')." WHERE cd_atendimento = ".$cd_atendimento;
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
        
    }
    
    /**
     * Atendimento_model::senhaChamada()
     * 
     * Verifica a primeira senha caso exista registrada para atendimento
     * 
     * @return Retorna o cd da primeira senha em caso de sucesso
     */
    public function senhaChamada(){
        
        $this->db->select_min('cd_atendimento');
        $this->db->where('cd_local', $this->input->post('local'));
        $this->db->where('cd_status_atendimento', 1);
        $this->db->like('data_chegada_atendimento', date('Y-m-d'), 'after'); 
        $minAt = $this->db->get('atendimento')->row()->cd_atendimento;
        
        if($minAt){
            
            $this->db->select('cd_atendimento, senha_atendimento');
            $this->db->where('cd_atendimento', $minAt);
            return $this->db->get('atendimento')->result();
            
        }else{
            
            return false;
            
        }
        
    }
    
    /**
     * Atendimento_model::consultaSenha()
     * 
     * Verifica qual a senha que foi chamada para atendimento
     * 
     * @return O número da senha e o número do guichê
     */
    public function consultaSenha(){
        
        $this->db->select('senha_atendimento, nome_atendimento, nome_guiche');
        $this->db->where('cd_local', $this->input->post('local'));
        $this->db->where('cd_status_atendimento', 5);
        $this->db->like('data_chegada_atendimento', date('Y-m-d'), 'after'); 
        $this->db->join('guiche', 'atendimento.cd_guiche = guiche.cd_guiche');
        $this->db->limit(1);
        $this->db->order_by("data_inicio_atendimento", "DESC");
        return $this->db->get('atendimento')->result();
        
    }
    
    /**
     * Atendimento_model::verificaAtendimentoResolucao()
     * 
     * Verifica se o usuário tem permissão / habilitação para resolver o atendimento
     * 
     * @return O cd do usuário
     */
    public function verificaAtendimentoResolucao($cd_atendimento = null){
        
        $this->db->select('cd_usuario');
        $this->db->where('cd_atendimento', $cd_atendimento);
        $this->db->where('cd_usuario', $this->session->userdata('cd'));
        $this->db->join('grupo_resolucao_motivo', 'grupo_resolucao_motivo.cd_motivo = atendimento.cd_motivo');
        $this->db->join('grupo_resolucao_usuario', 'grupo_resolucao_usuario.cd_grupo_resolucao = grupo_resolucao_motivo.cd_grupo_resolucao');
        return $this->db->get('atendimento')->result();
        
    }

}