<?php date_default_timezone_set($this->session->userdata('localTimeZone')); ?>
<!-- Modal que exibe que não há senha disponível -->
<div class="modal fade" id="acaoDesconsiderar" tabindex="-1" role="dialog" aria-labelledby="acaoDesconsiderar" aria-hidden="true">
    <div class="modal-dialog" style="width:450px">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title"><strong>Deseja realmente desconsiderar o atendimento?</strong></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning"><strong>Observa&ccedil;&atilde;o:<br/>Se voc&ecirc; clicar em SIM esse atendimento ser&aacute; ignorado e todo seu conte&uacute;do ser&aacute; apagado.</strong></div>
            </div>
            <div class="modal-footer">
            <?php
            echo form_open('atendimento/desconsiderar');
                echo form_hidden('cd_atendimento', $cd_atendimento);
                echo form_button(array('id' => 'desconsiderar', 'data-dismiss' => 'modal', 'content' => 'N&atilde;o', 'class' => 'btn btn-primary pull-left'));
                echo form_submit("btn_cadastro","Sim", 'class="btn btn-primary pull-right"');
            echo form_close(); 
            ?>
            </div>

    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.timer.js") ?>"></script>
<div class="container">
            <!--<div class="col-md-9 col-sm-8"> USADO PARA SIDEBAR -->
            <div class="col-lg-12">
                
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('home/inicio'); ?>">Principal</a>
                    </li>
                    <li class="active">Ficha atendimento</li>
                </ol>
                <div id="divMain">
                    <?php 
                        echo $this->session->flashdata('statusOperacao');
                        $data = array('class'=>'pure-form','id'=>'salvar_atendimento');
                    	echo form_open('atendimento/finaliza',$data);
                            $attributes = array('id' => 'address_info', 'class' => 'address_info');
             
                    		echo form_fieldset("Ficha atendimento - <b id='stopwatch'></b>", $attributes);
                    		
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
                                    $data = array('id'=>'descricao_atendimento', 'name'=>'descricao_atendimento', 'class'=>'form-control');
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
                                
                                if(in_array($cd_status_atendimento, array(5,6))){
                                    echo form_button(array('id' => 'desconsiderar', 'data-toggle' => 'modal', 'data-target' => '#acaoDesconsiderar', 'content' => 'Desconsiderar', 'class' => 'btn btn-primary pull-left'));
                                }
                                
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
    
    <?php if(!$cd_categoria){ ?>
    $("#div_categoria").css("display", "none");
    $("#div_motivo").css("display", "none");
    $("#div_prazo").css("display", "none");
    <?php } ?>
    
    <?php if($prazo != 0 or $prazo == null){ ?>
    $("#div_resolucao").css("display", "none");
    <?php } ?>
    
    $("#cd_tipo_atendimento").change(function(){
        
        if($(this).val() != ''){
            
            $(this).carregaCategoria();
            if($(this).val() != '' && $("#cd_categoria").val()){
                $(this).carregaMotivo();   
            }                 
            
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
        
        if($(this).val() != '' && $("#cd_tipo_atendimento").val() != ''){
            
            $(this).carregaMotivo();     
            
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
 	            $("#resMarcar").html('<span>Erro de execução</span>');
              },*/
              success: function(res) {
                
                $("#prazo").val(res[0].prazo_motivo);
                
                if(res[0].prazo_motivo == 0){
                    
                    $("#div_resolucao").css("display", "block");
                    
                }else{
                    
                    $("#div_resolucao").css("display", "none");
                    $("#resolucao_atendimento").val("");
                    
                }
                
              }
            });   
            
        }else{
            
            $("#div_prazo").css("display", "none");
            $("#prazo").val("");
            $("#div_resolucao").css("display", "none");
            $("#resolucao_atendimento").val("");
            
        }
        
    });
    
    $.fn.carregaMotivo = function() {
        
        $("#div_motivo").css("display", "block");
        $("#cd_motivo").html('<option value="">AGUARDE...</option>');
            
        $.ajax({
          type: "POST",
          url: '<?php echo base_url(); ?>ajax/carregaMotivo',
          data: {
            cd_tipo_atendimento: $("#cd_tipo_atendimento").val(),
            cd_categoria: $("#cd_categoria").val()
          },
          dataType: "json",
          /*error: function(res) {
            $("#resMarcar").html('<span>Erro de execução</span>');
          },*/
          success: function(res) {
            
            if(res.length > 0){
            
            content = '<option value=""></option>';
            
            $.each(res, function() {
              
              content += '<option value="'+ this.cd_motivo +'">'+ this.nome_motivo +'</option>';
              
            });
            
            $("#cd_motivo").html('');
            $("#cd_motivo").append(content);
            
            }else{
                $("#cd_motivo").html('');
                $("#div_motivo").css("display", "none");
                $("#div_prazo").css("display", "none");
                $("#prazo").val("");
                $("#div_resolucao").css("display", "none");
                $("#resolucao_atendimento").val("");
            }
            
          }
        });
        
    };
    
    $.fn.carregaCategoria = function() {
            
        $.ajax({
          type: "POST",
          url: '<?php echo base_url(); ?>ajax/carregaCategoria',
          data: {
            cd_tipo_atendimento: $("#cd_tipo_atendimento").val()
          },
          dataType: "json",
          /*error: function(res) {
            $("#resMarcar").html('<span>Erro de execução</span>');
          },*/
          success: function(res) {
            
            if(res.length > 0){
                
                $("#div_categoria").css("display", "block");
                $("#cd_categoria").html('<option value="">AGUARDE...</option>');
                
                content = '<option value=""></option>';
                
                $.each(res, function() {
                  
                  content += '<option value="'+ this.cd_categoria +'">'+ this.nome_categoria +'</option>';
                  
                });
                
                $("#cd_categoria").html('');
                $("#cd_categoria").append(content);
            
            }else{
                $("#cd_categoria").html('');
                $("#div_categoria").css("display", "none");
                $("#cd_motivo").html('');
                $("#div_motivo").css("display", "none");
                $("#div_prazo").css("display", "none");
                $("#prazo").val("");
                $("#div_resolucao").css("display", "none");
                $("#resolucao_atendimento").val("");
            }
            
          }
        });
        
    };
    
});

$(document).ready(function(){
    
    // Valida o formulário
	$("#salvar_atendimento").validate({
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
            descricao_atendimento: {
                required: true
            },
            resolucao_atendimento: {
                required: function(element) {
                    return $("#prazo").val() == 0;
                }
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
            descricao_atendimento: {
                required: "Descreva o problema / d&uacute;vida / informa&ccedil;&atilde;o do atendimento."
            },
            resolucao_atendimento: {
                required: "Descreva a resolu&ccedil;&atilde;o do problema."
            }
	   }
   });   
   
});

var Example1 = new (function() {
    var $stopwatch, // Stopwatch element on the page
        incrementTime = 70, // Timer speed in milliseconds
        //currentTime = 0, // Current time in hundredths of a second
        currentTime = <?php echo $this->util->horaParaSegundos($tempo_atendimento) * 100; ?>, // Current time in hundredths of a second
        updateTimer = function() {
            //$stopwatch.html(formatTime(currentTime)); //Exibe somente a minuto/ segundo/ miliseguntos 
            $stopwatch.html(formatTime(currentTime).substr(0, 5)); //Exibe somente a minuto/ segundo
            currentTime += incrementTime / 10;
            
            if(currentTime > 150000){
    			$('#stopwatch').css('color', 'red');
    		}
            
        },
        init = function() {
            $stopwatch = $('#stopwatch');
            Example1.Timer = $.timer(updateTimer, incrementTime, true);
        };
    this.resetStopwatch = function() {
        currentTime = 0;
        this.Timer.stop().once();
    };
    $(init);
});

// Common functions
function pad(number, length) {
    var str = '' + number;
    while (str.length < length) {str = '0' + str;}
    return str;
}
function formatTime(time) {
    var min = parseInt(time / 6000),
        sec = parseInt(time / 100) - (min * 60),
        hundredths = pad(time - (sec * 100) - (min * 6000), 2);
    return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2) + ":" + hundredths;
}

</script>