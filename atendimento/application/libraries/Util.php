<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
setlocale(LC_ALL, 'pt_BR.UTF-8');
class Util{
	
    private $controlaClass = 0;
    private $menuCompleto = '';
    
	/**
	 * Util::formaValorBanco()
	 * 
     * Formata os dados para salvar no banco de dados
     * 
	 * @param mixed $valor Conteúdo para formação
	 * @return
	 */
	public function formaValorBanco($valor){
		
		#strtoupper();
		
		if(empty($valor) and $valor !== "0"){
			$valor = 'null';
		}elseif(preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/', $valor)){ # DATA
        	$valor = "'".$this->formataData($valor, 'USA')."'";
        }elseif(preg_match('/^[0-9]{2}\/[0-9]{4}$/', $valor)){ # DATA (MÊS ANO)
        	$valor = "'".$this->formataData($valor, 'USA')."'";
        }elseif(preg_match('/^[0-9]+[.,]{1}[0-9]{2}$/',$valor)){ # NUMÉRICO (PONTO FLUTUANTE)
        	$valor = "'".preg_replace('/,/', '.', $valor)."'";
        }elseif(preg_match('/^[0-9]+$/', $valor)){ # INTEIRO
        	$valor = $valor;
        }else{ # STRING
            $valor = "'".$valor."'";
        }
		
		return $valor;
	}
	
	/**
	 * Util::removeAcentos()
	 * 
     * Remove os acentos da string
     * 
	 * @param mixed $string String para remoção de acentos
	 * @return
	 */
	public function removeAcentos($string) {
	
        $string = htmlentities($string, ENT_COMPAT, 'UTF-8');
        $string = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil);/', '$1',$string);
    
		/*$string = preg_replace("/[ÁÀÂÃÄáàâãä]/", "a", $string);
		$string = preg_replace("/[ÉÈÊéèê]/", "e", $string);
		$string = preg_replace("/[ÍÌíì]/", "i", $string);
		$string = preg_replace("/[ÓÒÔÕÖóòôõö]/", "o", $string);
		$string = preg_replace("/[ÚÙÜúùü]/", "u", $string);
		$string = preg_replace("/[Çç]/", "c", $string);
		$string = preg_replace("/[][><}{;,!?*%~^`&#]/", "", $string);*/
		#$string = preg_replace("/[][><}{)(:;,!?*%~^`&#@]/", "", $string);
		#$string = preg_replace("/ /", "_", $string);
		#$string = strtolower($string);
		
		return $string;
		
	}
	
	/**
	 * Util::formataData()
	 * 
     * Formata a data para USA ou BR
     * 
	 * @param mixed $data Data para formatação
	 * @param mixed $tipo Tipo de formatação
	 * @return
	 */
	public function formataData($data, $tipo){
	
		if($tipo == 'USA'){
			
			$data = implode('-',array_reverse(explode('/', $data)));
			
		}else{
		
			$data = implode('/',array_reverse(explode('-', $data)));
		
		}
	
		return $data;
	
	}
    
    /**
     * Util::listaMesesAteAtual()
     * 
     * Cria uma lista de meses
     * 
     * @param string $atual Se atual lista até mês atual senão lista todos os meses
     * @return
     */
    public function listaMesesAteAtual($atual = 'nao'){
        
        $meses = array(
          '1' => 'JANEIRO',
          '2' => 'FEVEREIRO',
          '3' => 'MARÇO',
          '4' => 'ABRIL',
          '5' => 'MAIO',
          '6' => 'JUNHO',
          '7' => 'JULHO',
          '8' => 'AGOSTO',
          '9' => 'SETEMBRO',
          '10' => 'OUTUBRO',
          '11' => 'NOVEMBRO',
          '12' => 'DEZEMBRO'
       );
       
       if($atual == 'sim'){
        
           for($i=1; $i<date('m'); $i++){
                $lista[] = $meses[$i];
           }
           
           return $lista;
       
       }
       
       return $meses;
       
    }
    
    /**
    * Função que formata o valor numérico (Moeda)
    * @return Retorna o valor formatado
    *  
    * @param mixed $valor
    * @return Retorna o valor formatado
    */
    public function formataValor($valor){
 		$valor = array (substr($valor,0,strlen($valor)-2), substr($valor,strlen($valor)-2,2));
		$valor = ($valor[0] * 1).".".$valor[1];
		return $valor;
		//return $valor;
 	}
    
    /**
     * Util::montaMenu()
     * 
     * @param mixed $menus Todos os menus
     * @param mixed $paisMenu Somente os pais
     * @return
     */
    public function montaMenu($menus, $paisMenu){
 
        foreach($paisMenu as $pM){
            $pais[] = $pM['pai_menu'];
        }

        $this->paisMenu = $pais;

        foreach($menus as $me){
            
            $menuItens[$me->pai_menu][$me->cd_menu] = array('link' => $me->link_menu,'nome' => $me->nome_menu);
            
        }
        
        return $this->loopMenu($menuItens);
        
    }
    
    /**
     * Util::loopMenu()
     * 
     * Auxilia na montagem do menu
     * 
     * @param mixed $menuTotal
     * @param integer $idPai
     * @param string $filho
     * @return
     */
    public function loopMenu(array $menuTotal , $idPai = 0, $filho = 'nao'){
        
        if($filho == 'nao'){ # Se não é filho define class pai
            $classUl = 'class="nav navbar-nav navbar-right"';
        }else{ # Se é filho define classe filho
            $classUl = 'class="dropdown-menu"';
        }
        
        $this->menuCompleto .= '<ul '.$classUl.'>';
   
        foreach( $menuTotal[$idPai] as $idMenu => $menuItem){
            
            if(in_array($idMenu, $this->paisMenu)){ # É filho então configura filho
                $classLi = 'class="dropdown"';
                $link = '#';
                $classLinkPai = 'class="dropdown-toggle" data-toggle="dropdown"';
                $auxLinkPai = '<b class="caret"></b>';
            }else{ # Configura pai
                $classLi = '';
                $link = base_url($menuItem['link']);
                $classLinkPai = '';
                $auxLinkPai = '';
            }
            #echo $menuItem['link']; echo '<br>';
            #echo '1 '; echo $_SERVER['REDIRECT_QUERY_STRING']; echo ' -- '; echo '2 '; echo $menuItem['link']; echo '<br>';
            # Verifica se o link esta presente na url atual
            #if(stripos($_SERVER['REDIRECT_QUERY_STRING'], $menuItem['link']) !== false){
            if($_SERVER['REDIRECT_QUERY_STRING'] == $menuItem['link']){
                $ativo = 'class="active"';
            }else{
                $ativo = '';
            }
          
            $this->menuCompleto .= '<li '.$ativo.' '.$classLi.'>';
            
            $this->menuCompleto .= '<a href="'.$link.'" '.$classLinkPai.'>'.html_entity_decode($menuItem['nome']).$auxLinkPai.'</a>';

            if( isset( $menuTotal[$idMenu] ) ) $this->loopMenu($menuTotal,$idMenu, 'sim');
            
            $this->menuCompleto .= '</li>';
            
        }
        
        $this->menuCompleto .= '</ul>';
        
        return $this->menuCompleto;
        
    }
    
    /**
     * Util::montaPermissao()
     * 
     * Monta a árvore de permissões
     * 
     * @param mixed $permissoes Todas permissões
     * @param mixed $paiPermissoes Pai das permissões
     * @param bool $permissoesUsuario Permissões que o usuário pussui
     * @return
     */
    public function montaPermissao($permissoes, $paiPermissoes, $permissoesUsuario = false){
        
        foreach($paiPermissoes as $paiP){
            
            $perm[] = $paiP['pai_permissao'];
            
        }
        
        $this->paiPermissao = $perm;
        
        foreach($permissoes as $permi){
            
            $permItem[$permi->pai_permissao][$permi->cd_permissao] = array('nome'=>$permi->nome_permissao);
            
        }
        
        return $this->loopPermissoes($permItem, 0, 'nao', $permissoesUsuario);
    
    }
    
    /**
     * Util::loopPermissoes()
     * 
     * Auxilida a montagem das permissões
     * 
     * @param mixed $permissoesTotal
     * @param integer $idPai
     * @param string $filho
     * @param bool $permissoesUsuario
     * @return
     */
    public function loopPermissoes(array $permissoesTotal , $idPai = 0, $filho = 'nao', $permissoesUsuario = false){
        
        $this->permissoesCompleto .= '<ul id="idPermissoes">';
   
        foreach( $permissoesTotal[$idPai] as $idPermissao => $permissaoItem){
            
            //if(in_array($idPermissao, $this->paiPermissao)){ # Se não é filho define class pai
            if(preg_match('/^MENU/', $permissaoItem['nome'])){
                
                $this->controlaClass++;
            
                $sequencia = $this->controlaClass;
            
                $classUl = 'class="classItem'.$this->controlaClass.'"';
                $marcaTodos = 'onclick="marcaGrupo(\'.classItem'.$this->controlaClass.'\', this)"';
                
            }else{ # Se é filho define classe filho
            
                $classUl = 'class="classItem'.$this->controlaClass.'"';
                $marcaTodos = '';
            }
            
            if(in_array($idPermissao, $permissoesUsuario)){
                $marcado = 'checked';
            }else{
                $marcado = ''; 
            }
          
            $this->permissoesCompleto .= '<li>';
            
            $this->permissoesCompleto .= '<label>';
            $this->permissoesCompleto .= '<input '.$marcado.' type="checkbox" '.$marcaTodos.' '.$classUl.' name="permissao[]" value="'.$idPermissao.'" />&nbsp';
            $this->permissoesCompleto .= html_entity_decode($permissaoItem['nome']);
            $this->permissoesCompleto .= '</label>';

            if( isset( $permissoesTotal[$idPermissao] ) ) $this->loopPermissoes($permissoesTotal,$idPermissao, 'sim', $permissoesUsuario);
            
            $this->permissoesCompleto .= '</li>';
            
        }
        
        $this->permissoesCompleto .= '</ul>';
        
        #$this->controlaClass++;
        
        return $this->permissoesCompleto;
        
    }
    
    /**
     * Util::somarDiasUteis()
     * 
     * Auxilida a montagem das permissões
     * 
     * @param date $str_data Data que será acrescentada (formato 2014-12-05)
     * @param integer $int_qtd_dias_somar Quantidade de dias uteis
     * @return
     */
    /*function somarDiasUteis($str_data, $int_qtd_dias_somar = 7) {
    
        // Caso seja informado uma data do tipo DATETIME - aaaa-mm-dd 00:00:00
        // Transforma para DATE - aaaa-mm-dd
        $str_data = substr($str_data,0,10);
        
        // Se a data estiver no formato brasileiro: dd/mm/aaaa
        // Converte-a para o padrão americano: aaaa-mm-dd
        if ( preg_match("@/@",$str_data) == 1 ) {
            $str_data = implode("-", array_reverse(explode("/",$str_data)));
        }
        
        $array_data = explode('-', $str_data);
        $count_days = 0;
        $int_qtd_dias_uteis = 0;
        while ( $int_qtd_dias_uteis < $int_qtd_dias_somar ) {
            $count_days++;
                    if ( ( $dias_da_semana = gmdate('w', strtotime('+'.$count_days.' day', mktime(0, 0, 0, $array_data[1], $array_data[2], $array_data[0]))) ) != '0' && $dias_da_semana != '6' ) {
                $int_qtd_dias_uteis++;
            }
        }
        #return gmdate('Y-m-d',strtotime('+'.$count_days.' day',strtotime($str_data)));
        return gmdate('d/m/Y',strtotime('+'.$count_days.' day',strtotime($str_data)));
    }*/
    function somarDiasUteis($data, $qtdDias = 7){
        
        date_default_timezone_set('America/Sao_Paulo');
        setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
        
        // Se a data estiver no formato brasileiro: dd/mm/aaaa
        // Converte-a para o padrão americano: aaaa-mm-dd
        if ( preg_match("@/@",$data) == 1 ) {
            $data = implode("-", array_reverse(explode("/",$data)));
        }
        
        // Adiciona 1 dia para iniciar a contagem do dia seguinte
        #$data = date('Y-m-d', strtotime("+1 days",strtotime($data)));
        $contDias = 1;
        $diasSomados = 0;
        while($contDias < $qtdDias){
    
            $diaVerificado = date('w', strtotime("+".$contDias." days",strtotime($data)));
            #echo $diaVerificado; echo '<br>';
            switch($diaVerificado){
                case 0: // Se domingo adiciona mais um dia para virar segunda
                    $diasSomados += 1;
                    break;  
                case 6: // Se sábado
                    $diasSomados += 3;
                    break;
                default: // O padrão é mais um dia
                    $diasSomados += 1;
            }
            
            $contDias++;
        }
        
        return date('d/m/Y', strtotime("+".$diasSomados." days",strtotime($data)));
        
    }
    
    // CRIAMOS A FUNCAO PARA CONVERTER PARA SEGUNDOS AONDE HORA É A HORA QUE ELE VAI PEGAR
    public function horaParaSegundos($hora) {
     
        $horas = substr($hora, 0, -6);
        $minutos = substr($hora, -5, 2);
        $segundos = substr($hora, -2);
         
        return $horas* 3600 + $minutos * 60 + $segundos;
        
        # Exemplo de uso:
        # $this->horaParaSegundos(date('H:i:s', strtotime("00:05:00")));
    }

    //E aqui a função segue a mesma idéia que a de cima, porém agora vamos converter os segundos para horas
    public function segundosParaHoras($segundos){
     
        $horas = floor($segundos / 3600);
        $segundos -= $horas* 3600;
        $minutos = floor($segundos / 60);
        $segundos -= $minutos * 60;
         
        return "$horas:$minutos:$segundos";
        
        # Exemplo de uso:
        # $this->segundosParaHoras(date('H:i:s', strtotime("00:05:00")));
     
    }

}