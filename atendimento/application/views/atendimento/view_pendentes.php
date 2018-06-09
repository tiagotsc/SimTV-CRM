<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.validate.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>
<div class="container">
            <!--<div class="col-md-9 col-sm-8"> USADO PARA SIDEBAR -->
            <div class="col-lg-12">
                
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('home/inicio'); ?>">Principal</a>
                    </li>
                    <li class="active">Atendimentos pendentes</li>
                </ol>
                
                <div id="divMain">
                
                <?php 
                echo $this->session->flashdata('statusOperacao');
                $this->table->set_heading('Id', 'Data abertura', 'Problema / Motivo', 'Prazo', 'Dias restantes', 'A&ccedil;&atilde;o');
                            
                foreach($atendimentos as $ate){
                    
                    $data_prazo = $this->util->somarDiasUteis($ate->data_atendimento, $ate->prazo_motivo);
                    $dtAtual = new DateTime(date('Y-m-d'));
                    $dtPrazo = new DateTime(implode("-", array_reverse(explode("/",$data_prazo))));
                    // Resgata diferença entre as datas
                    $dateInterval = $dtAtual->diff($dtPrazo);
                    
                    if ($dtPrazo >= $dtAtual){
                    	$dtRestam = 'Restam '.$dateInterval->days;
                        $class = '';
                    }else{
                        $dtRestam = 'Passou '.$dateInterval->days;
                        $class = 'textoVermelho';
                    }
                    
                    $cell0 = array('data' => $ate->cd_atendimento, 'class' => $class);
                    $cell1 = array('data' => $ate->data_atendimento, 'class' => $class);
                    $cell2 = array('data' => $ate->nome_motivo, 'class' => $class);
                    
                    $cell3 = array('data' => $ate->prazo_motivo.' dias &uacute;teis (At&eacute; '.$data_prazo.')', 'class' => $class);
                    
                    
                    
                    $cell4 = array('data' => $dtRestam.' dias', 'class' => $class);
                    
                    $botaoEditar = (in_array(35, $this->session->userdata('permissoes')))? '<a title="Atender" href="'.base_url('atendimento/resolver/'.$ate->cd_atendimento).'" class="icone glyphicon glyphicon-search"></a>': '';
                    $cell5 = array('data' => $botaoEditar);
                        
                    $this->table->add_row($cell0, $cell1, $cell2, $cell3, $cell4, $cell5);
                    
                }
                
            	$template = array('table_open' => '<table class="table table-bordered">');
            	$this->table->set_template($template);
            	echo $this->table->generate();
                echo "<ul class='pagination pagination-lg'>" . utf8_encode($paginacao) . "</ul>"; 
                ?>
                </div>
                
            </div>
</div>
        <!-- container -->

    
<script type="text/javascript">

function apagarRegistro(cd, nome){
    $("#apg_cd_guiche").val(cd);
    $("#apg_nome_guiche").val(nome);
}

</script>