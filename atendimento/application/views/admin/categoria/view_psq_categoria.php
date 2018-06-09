<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.validate.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>
<div class="container">

    <!-- INÍCIO Modal Apaga registro de telecom -->
    <div class="modal fade" id="apaga" tabindex="-1" role="dialog" aria-labelledby="apaga" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Deseja apagar a categoria?</h4>
                </div>
                <div class="modal-body">               
                    <?php              
                        $data = array('class'=>'pure-form','id'=>'apagaRegistro');
                        echo form_open('categoria/apaga',$data);
                        
                            echo form_label('Nome', 'apg_nome_categoria');
                    		$data = array('id'=>'apg_nome_categoria', 'name'=>'apg_nome_categoria', 'class'=>'form-control data', 'style'=>'width:100%', 'readonly'=>'readonly');
                    		echo form_input($data,'');
                        
                    ?>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="apg_cd_categoria" name="apg_cd_categoria" />
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
                    <li class="active">Pesquisar categoria</li>
                </ol>
                <div id="divMain">
                    <?php
                        
                        echo $this->session->flashdata('statusOperacao');
                        $data = array('class'=>'pure-form','id'=>'pesquisa_categoria');
                    	echo form_open('categoria/pesquisar',$data);
                            $attributes = array('id' => 'address_info', 'class' => 'address_info');
                            
                            $botaoCadastrar = (in_array(18, $this->session->userdata('permissoes')))? "<a href='".base_url('categoria/ficha')."' class='linkDireita'>Cadastrar&nbsp<span class='glyphicon glyphicon-plus'></span></a>": '';
                            
                    		echo form_fieldset("Pesquisar categoria".$botaoCadastrar, $attributes);
                    		  
                                echo '<div class="row">';
                                
                                    echo '<div class="col-md-8">';
                                    #print_r(array_keys($listaBancos));
                                    echo form_label('Nome da categoria', 'nome_categoria');
                        			$data = array('name'=>'nome_categoria', 'value'=>$this->input->post('nome_categoria'),'id'=>'nome_categoria', 'placeholder'=>'Digite o nome', 'class'=>'form-control');
                        			echo form_input($data);
                                    echo '</div>';
                                    
                                    echo '<div class="col-md-4">';
                                    $options = array(''=>'', 'A' => 'Ativo', 'I' => 'Inativo');		
                            		echo form_label('Status', 'status_categoria');
                            		echo form_dropdown('status_categoria', $options, $postStatus, 'id="status_categoria" class="form-control"');
                                    echo '</div>';
                                       
                                echo '</div>';                      
                                                                
                                echo '<div class="actions">';
                                echo form_submit("btn_cadastro","Pesquisar categoria", 'class="btn btn-primary pull-right"');
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
                $this->table->set_heading('Nome', 'Status', 'A&ccedil;&atilde;o');
                            
                foreach($categorias as $usu){
                    
                    $cell1 = array('data' => html_entity_decode($usu->nome_categoria));
                    $cell2 = array('data' => html_entity_decode($usu->status_categoria));
                    
                    $botaoEditar = (in_array(18, $this->session->userdata('permissoes')))? '<a title="Editar" href="'.base_url('categoria/ficha/'.$usu->cd_categoria).'" class="icone glyphicon glyphicon glyphicon-pencil"></a>': '';
                    $botaoExcluir = (in_array(19, $this->session->userdata('permissoes')))? '<a title="Apagar" href="#" onclick="apagarRegistro('.$usu->cd_categoria.',\''.$usu->nome_categoria.'\')" data-toggle="modal"  data-target="#apaga" class="icone glyphicon glyphicon glyphicon glyphicon-remove"></a>': '';
                    
                    $cell3 = array('data' => $botaoEditar.$botaoExcluir);
                        
                    $this->table->add_row($cell1, $cell2, $cell3);
                    
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
    $("#apg_cd_categoria").val(cd);
    $("#apg_nome_categoria").val(nome);
}

</script>