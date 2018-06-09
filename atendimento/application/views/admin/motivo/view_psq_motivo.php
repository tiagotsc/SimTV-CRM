<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.validate.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>
<div class="container">

    <!-- INÍCIO Modal Apaga registro de telecom -->
    <div class="modal fade" id="apaga" tabindex="-1" role="dialog" aria-labelledby="apaga" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Deseja apagar o motivo?</h4>
                </div>
                <div class="modal-body">               
                    <?php              
                        $data = array('class'=>'pure-form','id'=>'apagaRegistro');
                        echo form_open('motivo/apaga',$data);
                        
                            echo form_label('Nome', 'apg_nome_motivo');
                    		$data = array('id'=>'apg_nome_motivo', 'name'=>'apg_nome_motivo', 'class'=>'form-control data', 'style'=>'width:100%', 'readonly'=>'readonly');
                    		echo form_input($data,'');
                        
                    ?>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="apg_cd_motivo" name="apg_cd_motivo" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">N&atilde;o</button>
                    <button type="submit" class="btn btn-primary">Sim</button>
            </div>
                    <?php
                    echo form_close();
                    ?>
        </div>
      </div>
    </div>
    <!-- FIM Modal Apaga registro de telecom -->
            <!--<div class="col-md-9 col-sm-8"> USADO PARA SIDEBAR -->
            <div class="col-lg-12">
                
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('home/inicio'); ?>">Principal</a>
                    </li>
                    <li class="active">Pesquisar motivo</li>
                </ol>
                <div id="divMain">
                    <?php
                        
                        echo $this->session->flashdata('statusOperacao');
                        $data = array('class'=>'pure-form','id'=>'pesquisa_motivo');
                    	echo form_open('motivo/pesquisar',$data);
                            $attributes = array('id' => 'address_info', 'class' => 'address_info');
                            
                            $botaoCadastrar = (in_array(8, $this->session->userdata('permissoes')))? "<a href='".base_url('motivo/ficha')."' class='linkDireita'>Cadastrar&nbsp<span class='glyphicon glyphicon-plus'></span></a>": '';
                            
                    		echo form_fieldset("Pesquisar motivo".$botaoCadastrar, $attributes);
                    		  
                                echo '<div class="row">';
                                
                                    echo '<div class="col-md-8">';
                                    #print_r(array_keys($listaBancos));
                                    echo form_label('Nome do motivo', 'nome_motivo');
                        			$data = array('name'=>'nome_motivo', 'value'=>$this->input->post('nome_motivo'),'id'=>'nome_motivo', 'placeholder'=>'Digite o nome', 'class'=>'form-control');
                        			echo form_input($data);
                                    echo '</div>';
                                    
                                    echo '<div class="col-md-4">';
                                    $options = array(''=>'', 'A' => 'Ativo', 'I' => 'Inativo');		
                            		echo form_label('Status', 'status_motivo');
                            		echo form_dropdown('status_motivo', $options, $postStatus, 'id="status_motivo" class="form-control"');
                                    echo '</div>';
                                       
                                echo '</div>';                      
                                                                
                                echo '<div class="actions">';
                                echo form_submit("btn_cadastro",utf8_encode("Pesquisar motivo"), 'class="btn btn-primary pull-right"');
                                echo '</div>';
                                                            
                    		echo form_fieldset_close();
                    	echo form_close(); 
                    
                    ?>        
                </div>
                
                <div class="row">&nbsp</div>
                <?php
                if($pesquisa == 'sim'){
                ?>
                <div class="well">
                
                <?php 
                $this->table->set_heading('Nome', 'Tipo atendimento', 'Categoria', 'Status', 'A&ccedil;&atilde;o');
                            
                foreach($motivos as $usu){
                    
                    $cell1 = array('data' => $usu->nome_motivo);
                    $cell2 = array('data' => $usu->nome_tipo_atendimento);
                    $cell3 = array('data' => $usu->nome_categoria);
                    $cell4 = array('data' => $usu->status_motivo);
                    
                    $botaoEditar = (in_array(15, $this->session->userdata('permissoes')))? '<a title="Editar" href="'.base_url('motivo/ficha/'.$usu->cd_motivo).'" class="icone glyphicon glyphicon glyphicon-pencil"></a>': '';
                    $botaoExcluir = (in_array(16, $this->session->userdata('permissoes')))? '<a title="Apagar" href="#" onclick="apagarRegistro('.$usu->cd_motivo.',\''.$usu->nome_motivo.'\')" data-toggle="modal"  data-target="#apaga" class="icone glyphicon glyphicon glyphicon glyphicon-remove"></a>': '';
                    
                    $cell5 = array('data' => $botaoEditar.$botaoExcluir);
                        
                    $this->table->add_row($cell1, $cell2, $cell3, $cell4, $cell5);
                    
                }
                
            	$template = array('table_open' => '<table class="table table-bordered">');
            	$this->table->set_template($template);
            	echo $this->table->generate();
                echo "<ul class='pagination pagination-lg'>" . utf8_encode($paginacao) . "</ul>"; 
                ?>
                </div>
                <?php
                }
                ?>
                
            </div>
</div>
        <!-- container -->

    
<script type="text/javascript">

function apagarRegistro(cd, nome){
    $("#apg_cd_motivo").val(cd);
    $("#apg_nome_motivo").val(nome);
}

</script>