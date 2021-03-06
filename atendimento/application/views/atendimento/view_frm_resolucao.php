<?php date_default_timezone_set($this->session->userdata('localTimeZone')); ?>
<!-- Modal que exibe que n�o h� senha dispon�vel -->
<div class="modal fade" id="acaoReclassificar" tabindex="-1" role="dialog" aria-labelledby="acaoReclassificar" aria-hidden="true">
    <div class="modal-dialog" style="width:450px">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title"><strong>Deseja realmente reclassificar o atendimento?</strong></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning"><strong>Observa&ccedil;&atilde;o:<br/>Utilize essa op&ccedil;&atilde;o caso voc&ecirc; deseja somente reclassificar esse atendimento sem resolv&ecirc;-lo.</strong></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">N&atilde;o</button>
                <button type="button" id="ConfirmarReclassificao" class="btn btn-primary">Sim</button>
            </div>

    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>
<div class="container">
            <!--<div class="col-md-9 col-sm-8"> USADO PARA SIDEBAR -->
            <div class="col-lg-12">
                
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('home/inicio'); ?>">Principal</a>
                    </li>
                    <li class="active">Ficha resolu&ccedil;&atilde;o</li>
                </ol>
                <div id="divMain">
                    <?php
                        echo $this->session->flashdata('statusOperacao');
                        $data = array('class'=>'pure-form','id'=>'resolver_atendimento');
                    	echo form_open('atendimento/fechar',$data);
                            $attributes = array('id' => 'address_info', 'class' => 'address_info');
             
                    		echo form_fieldset("Ficha resolu&ccedil;&atilde;o", $attributes);
                    		
                                echo '<div class="row">';
                                
                                    echo '<div class="col-md-3">';
                                    echo form_label('Data atendimento');
                        			$data = array('value'=>$data_atendimento,'class'=>'form-control', 'readonly'=>'readonly');
                        			echo form_input($data);
                                    echo '</div>';
                                    
                                    echo '<div class="col-md-3">';
                                    echo form_label('Hora chegada');
                        			$data = array('value'=>$hora_chegada, 'class'=>'form-control', 'readonly'=>'readonly');
                        			echo form_input($data);
                                    echo '</div>';
                                    
                                    $hora_inicio = ($hora_inicio != '')? $hora_inicio: date('H:i:s');
                                    
                                    echo '<div class="col-md-3">';
                                    echo form_label('In&iacute;cio atendimento');
                        			$data = array('value'=>$hora_inicio, 'class'=>'form-control', 'readonly'=>'readonly');
                        			echo form_input($data);
                                    echo '</div>';
                                    
                                    if($tempo_espera == ''){
                                        $HoraEntrada = new DateTime($hora_chegada);
                                        $HoraSaida   = new DateTime($hora_inicio);
                                        $tempo_espera = $HoraSaida->diff($HoraEntrada)->format('%H:%I:%S');
                                    }
                                    
                                    echo '<div class="col-md-3">';
                                    echo form_label('Tempo de espera');
                        			$data = array('value'=>$tempo_espera, 'class'=>'form-control', 'readonly'=>'readonly');
                        			echo form_input($data);
                                    echo '</div>';
                                    
                                echo '</div>';
                                
                                echo '<div class="row">';
                                
                                    echo '<div class="col-md-3">';
                                    $options = array('' => '');	
                                    foreach($tipo_atendimento as $tA){
                            			$options[$tA->cd_tipo_atendimento] = $tA->nome_tipo_atendimento;
                            		}		
                            		echo form_label('Tipo de atendimento<span class="obrigatorio">*</span>', 'cd_tipo_atendimento');
                            		echo form_dropdown('cd_tipo_atendimento', $options, $cd_tipo_atendimento, 'id="cd_tipo_atendimento" class="form-control"');
                                    echo '</div>';
                                    
                                    echo '<div id="div_categoria" class="col-md-3">';
                                    
                                    if($categorias){
                                        
                                        $options = array('' => '');		
                                		foreach($categorias as $cat){
                                			$options[$cat->cd_categoria] = $cat->nome_categoria;
                                		}
                                          
                                    }else{
                                    
                                        $options = array('' => '');	
                                    
                                    }
                                    echo form_label('Categoria<span class="obrigatorio">*</span>', 'cd_categoria');
      		                        echo form_dropdown('cd_categoria', $options, $cd_categoria, 'id="cd_categoria" class="form-control"');
                                    echo '</div>';
                                    
                                    echo '<div id="div_motivo" class="col-md-3">';
                                    
                                    if($motivos){
                                        
                                        $options = array('' => '');		
                                		foreach($motivos as $mot){
                                			$options[$mot->cd_motivo] = $mot->nome_motivo;
                                		}
                                          
                                    }else{
                                    
                                        $options = array('' => '');	
                                    
                                    }
                                    
                                    echo form_label('Motivo<span class="obrigatorio">*</span>', 'cd_motivo');
      		                        echo form_dropdown('cd_motivo', $options, $cd_motivo, 'id="cd_motivo" class="form-control"');
                                    echo '</div>';
                                    
                                    echo '<div id="div_prazo" class="col-md-3">';
                                    echo form_label('Prazo');
                        			$data = array('value'=>$prazo, 'name'=>'prazo', 'id'=>'prazo','class'=>'form-control', 'readonly'=>'readonly');
                        			echo form_input($data);
                                    echo '</div>';
                                
                                echo '</div>';
                                
                                echo '<div class="row">';
                                    echo '<div class="col-md-6">';
                                    echo form_label('Descri&ccedil;&atilde;o do problema<span class="obrigatorio">*</span>', 'descricao_atendimento');
                                    $data = array('id'=>'descricao_atendimento', 'name'=>'descricao_atendimento', 'class'=>'form-control', 'readonly'=>'readonly');
  		                            echo form_textarea($data,$descricao_atendimento);
                                    echo '</div>';
                                    
                                    echo '<div id="div_resolucao" class="col-md-6">';
                                    echo form_label('Resolu&ccedil;&atilde;o do problema<span class="obrigatorio">*</span>', 'resolucao_atendimento');
                                    $data = array('id'=>'resolucao_atendimento', 'name'=>'resolucao_atendimento', 'class'=>'form-control');
  		                            echo form_textarea($data,$resolucao_atendimento);
                                    echo '</div>';
                                echo '</div>';
                                                              
                                echo '<div class="actions">';
                                 
                                echo form_hidden('cd_atendimento', $cd_atendimento);
                                
                                echo form_hidden('editar', $editar);
                                
                                echo form_button(array('id' => 'reclassificar', 'data-toggle' => 'modal', 'data-target' => '#acaoReclassificar', 'content' => 'Reclassificar', 'class' => 'btn btn-primary pull-left'));
                                
                                echo form_submit("btn_cadastro","Salvar", 'title="Salvar e finalizar o registro do atendimento" class="btn btn-primary pull-right"');
                                echo '</div>';   
                                                            
                    		echo form_fieldset_close();
                    	echo form_close(); 
                        
                        
                    ?>        
                </div>
            </div>
    </div>
<!-- /.container -->
    
    
<script type="text/javascript">

function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }
    alert(out);
}

$(document).ready(function(){
    
    $("#reclassificar").css("display", "none");
    <?php if(!$cd_categoria){ ?>
    $("#div_categoria").css("display", "none");
    $("#div_motivo").css("display", "none");
    $("#div_prazo").css("display", "none");
    <?php } ?>
    
    $("#ConfirmarReclassificao").click(function(){
        
        $("#resolver_atendimento").attr("action","<?php echo base_url('atendimento/reclassificar'); ?>");
        $( "#resolver_atendimento" ).submit();
       
    });        
    
    $("#cd_tipo_atendimento").change(function(){
        
        if($(this).val() != ''){
            
            $("#div_categoria").css("display", "block");
            
            $("#cd_categoria").html('<option value="">AGUARDE...</option>');
            
            $.ajax({
              type: "POST",
              url: '<?php echo base_url(); ?>ajax/carregaCategoria',
              data: {
                cd_tipo_atendimento: $(this).val()
              },
              dataType: "json",
              /*error: function(res) {
 	            $("#resMarcar").html('<span>Erro de execu��o</span>');
              },*/
              success: function(res) {
                
                content = '<option value=""></option>';
                
                $.each(res, function() {
                  
                  content += '<option value="'+ this.cd_categoria +'">'+ this.nome_categoria +'</option>';
                  
                });
                
                $("#cd_categoria").html('');
                $("#cd_categoria").append(content);
                
              }
            });                        
            
        }else{
            
            $("#div_categoria").css("display", "none");
            $("#cd_categoria").val("");
            
            $("#div_motivo").css("display", "none");
            $("#cd_motivo").val("");
            
            $("#div_prazo").css("display", "none");
            $("#prazo").val("");
            
        }
        
    });
    
    $("#cd_categoria").change(function(){
        
        if($(this).val() != ''){
            
            $("#div_motivo").css("display", "block");
            $("#cd_motivo").html('<option value="">AGUARDE...</option>');
            
            $.ajax({
              type: "POST",
              url: '<?php echo base_url(); ?>ajax/carregaMotivo',
              data: {
                cd_categoria: $(this).val()
              },
              dataType: "json",
              /*error: function(res) {
 	            $("#resMarcar").html('<span>Erro de execu��o</span>');
              },*/
              success: function(res) {
                
                content = '<option value=""></option>';
                
                $.each(res, function() {
                  
                  content += '<option value="'+ this.cd_motivo +'">'+ this.nome_motivo +'</option>';
                  
                });
                
                $("#cd_motivo").html('');
                $("#cd_motivo").append(content);
                
              }
            });     
            
        }else{
            
            $("#div_motivo").css("display", "none");
            $("#cd_motivo").val("");
            
            $("#div_prazo").css("display", "none");
            $("#prazo").val("");
            
        }
        
    });
    
    $("#cd_motivo").change(function(){
        
        if($(this).val() != ''){
            
            $("#div_prazo").css("display", "block");
            
            $.ajax({
              type: "POST",
              url: '<?php echo base_url(); ?>ajax/pegaPrazoMotivo',
              data: {
                cd_motivo: $(this).val()
              },
              dataType: "json",
              /*error: function(res) {
 	            $("#resMarcar").html('<span>Erro de execu��o</span>');
              },*/
              success: function(res) {
                
                $("#prazo").val(res[0].prazo_motivo);
                
                if(res[0].prazo_motivo > 0 && $("#cd_motivo").val() != <?php echo $cd_motivo; ?>){
                    $("#reclassificar").css("display", "block");
                }else{
                    $("#reclassificar").css("display", "none");
                } 
                
              }
            });  
            
        }else{
            
            $("#div_prazo").css("display", "none");
            $("#prazo").val("");
            $("#reclassificar").css("display", "none");
            
        }
        
    });
    
});

$(document).ready(function(){
    
    // Valida o formul�rio
	$("#resolver_atendimento").validate({
		debug: false,
		rules: {
			cd_tipo_atendimento: {
                required: true
            },
            cd_categoria: {
                required: true
            },
            cd_motivo: {
                required: true
            },
            /*descricao_atendimento: {
                required: true
            },*/
            resolucao_atendimento: {
                required: true
            }
		},
		messages: {
			cd_tipo_atendimento: {
                required: "Selecione o tipo do atendimento"
            },
            cd_categoria: {
                required: "Selecione a categoria do atendimento."
            },
            cd_motivo: {
                required: "Selecione o motivo / problema."
            },
            /*descricao_atendimento: {
                required: "Descreva o problema / d&uacute;vida / informa&ccedil;&atilde;o do atendimento."
            },*/
            resolucao_atendimento: {
                required: "Descreva a resolu&ccedil;&atilde;o do problema."
            }
	   }
   });   
   
});

</script>