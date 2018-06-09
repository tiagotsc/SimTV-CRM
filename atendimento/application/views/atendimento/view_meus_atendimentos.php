<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.validate.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>
<div class="container">
            <!--<div class="col-md-9 col-sm-8"> USADO PARA SIDEBAR -->
            <div class="col-lg-12">
                
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('home/inicio'); ?>">Principal</a>
                    </li>
                    <li class="active">Meus atendimentos</li>
                </ol>
                
                <div id="divMain">
                
                <?php 
                echo $this->session->flashdata('statusOperacao');
                $this->table->set_heading('Id', 'Senha', 'Data', 'Chegada', 'In&iacute;cio', 'Fim', 'Tempo espera', 'Tempo atendimento', 'A&ccedil;&atilde;o');
                            
                foreach($atendimentos as $ate){
                    
                    if($ate->cd_status_atendimento == 6){
                        $desconsiderado = 'riscaTexto';
                        $chamaFuncao = 'ficha';
                        $botaoNovamente = (in_array(33, $this->session->userdata('permissoes')))? '<a title="Chama novamente" href="'.base_url('atendimento/novamente/'.$ate->cd_atendimento).'" class="icone glyphicon glyphicon-circle-arrow-left"></a>': '';
                    }else{
                        $desconsiderado = '';
                        $chamaFuncao = 'editar';
                        $botaoNovamente = '';
                    }
                    
                    $cell1 = array('data' => $ate->cd_atendimento, 'class' => $desconsiderado);
                    $cell2 = array('data' => $ate->senha_atendimento, 'class' => $desconsiderado);
                    $cell3 = array('data' => $ate->data_atendimento, 'class' => $desconsiderado);
                    $cell4 = array('data' => $ate->hora_chegada, 'class' => $desconsiderado);
                    $cell5 = array('data' => $ate->hora_inicio, 'class' => $desconsiderado);
                    $cell6 = array('data' => $ate->hora_fim, 'class' => $desconsiderado);
                    $cell7 = array('data' => $ate->tempo_espera, 'class' => $desconsiderado);
                    $cell8 = array('data' => $ate->tempo_atendimento, 'class' => $desconsiderado);
                    
                    $botaoEditar = (in_array(33, $this->session->userdata('permissoes')))? '<a title="Editar" href="'.base_url('atendimento/'.$chamaFuncao.'/'.$ate->cd_atendimento).'" class="icone glyphicon glyphicon glyphicon-pencil"></a>': '';
                    
                    $botaoEditar = ($ate->cd_status_atendimento != 6)? $botaoEditar: '';
                    
                    $cell9 = array('data' => $botaoNovamente.$botaoEditar);
                        
                    $this->table->add_row($cell1, $cell2, $cell3, $cell4, $cell5, $cell6, $cell7, $cell8, $cell9);
                    
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