<?php
echo link_tag(array('href' => 'assets/js/drag_drop/style.css','rel' => 'stylesheet','type' => 'text/css'));
?>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/drag_drop/fieldChooser.js") ?>"></script>
<div class="container">
            <!--<div class="col-md-9 col-sm-8"> USADO PARA SIDEBAR -->
            <div class="col-lg-12">
                
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('home/inicio'); ?>">Principal</a>
                    </li>
                    <li class="active">Ficha motivo</li>
                </ol>
                <div id="divMain">
                    <?php
                        echo $this->session->flashdata('statusOperacao');
                        $data = array('class'=>'pure-form','id'=>'salvar_motivo');
                    	echo form_open('motivo/salvar',$data);
                            $attributes = array('id' => 'address_info', 'class' => 'address_info');
             
                    		echo form_fieldset("Ficha motivo<a href='".base_url('motivo/motivos')."' class='linkDireita'><span class='glyphicon glyphicon-arrow-left'></span>&nbspVoltar Pesquisar</a>", $attributes);
                    		
                                echo '<div class="row">';
                                
                                    echo '<div class="col-md-3">';
                                    echo form_label('Nome<span class="obrigatorio">*</span>', 'nome_motivo');
                        			$data = array('name'=>'nome_motivo', 'value'=>$nome_motivo,'id'=>'nome_motivo', 'placeholder'=>'Digite o nome', 'class'=>'form-control');
                        			echo form_input($data);
                                    echo '</div>';
                                    
                                    echo '<div class="col-md-3">';
                                    $options = array('' => '');	
                                    foreach($tipo_atendimento as $tat){
                            			$options[$tat->cd_tipo_atendimento] = $tat->nome_tipo_atendimento;
                            		}		
                            		echo form_label('Tipo de atendimento<span class="obrigatorio">*</span>', 'cd_tipo_atendimento');
                            		echo form_dropdown('cd_tipo_atendimento', $options, $cd_tipo_atendimento, 'id="cd_tipo_atendimento" class="form-control"');
                                    echo '</div>';
                                    
                                    echo '<div class="col-md-3">';
                                    $options = array('' => '');	
                                    foreach($categoria as $cat){
                            			$options[$cat->cd_categoria] = $cat->nome_categoria;
                            		}		
                            		echo form_label('Categoria<span class="obrigatorio">*</span>', 'cd_categoria');
                            		echo form_dropdown('cd_categoria', $options, $cd_categoria, 'id="cd_categoria" class="form-control"');
                                    echo '</div>';
                                    
                                    echo '<div class="col-md-1">';
                                    echo form_label('Prazo<span class="obrigatorio">*</span>', 'prazo_motivo');
                        			$data = array('name'=>'prazo_motivo', 'value'=>$prazo_motivo,'id'=>'prazo_motivo', 'placeholder'=>'Digite o prazo (Dias)', 'class'=>'form-control');
                        			echo form_input($data);
                                    echo '</div>';
                                    
                                    echo '<div class="col-md-2">';
                                    $options = array('A' => 'Ativo', 'I' => 'Inativo');		
                            		echo form_label('Status', 'status_motivo');
                            		echo form_dropdown('status_motivo', $options, $status_motivo, 'id="status_motivo" class="form-control"');
                                    echo '</div>';
                                    
                                echo '</div>';
                                                              
                                echo '<div class="actions">';
                                
                                echo form_hidden('cd_motivo', $cd_motivo);
                                
                                echo form_submit("btn_cadastro","Salvar", 'class="btn btn-primary pull-right"');
                                echo '</div>';   
                                                            
                    		echo form_fieldset_close();
                    	echo form_close();     
                        
                      
                    if($cd_motivo){ // Se existir motivo
                                   
                    ?>
                        <div class="row center grupos">
                            <div><strong>Grupos associados ao motivo / problema</strong></div>
                            <div id="res_associacao"></div>
                        </div>
                        <!-- FALTA TERMINAR ASSOCIAR USUÁRIOS AOS GRUPOS QUE ESTÃO ASSOCIADOS AO PROBLEMA MOTIVO
                        <div class="row">
                            <div class="col-md-6">
                            <?php
                                $options = array('' => '');		
                        		foreach($departamento as $dep){
                        			$options[$dep->cd_departamento] = html_entity_decode($dep->nome_departamento);
                        		}	
                        		echo form_label('Selecione o departamento', 'cd_departamento');
                        		echo form_dropdown('cd_departamento', $options, '', 'id="cd_departamento" class="form-control"');
                            ?>
                            </div>
                        </div>
                        -->
                        <div class="row grupos" id="fieldChooser" tabIndex="1">
                            <div class="col-md-6">
                            Grupos dispon&iacute;veis
                            </div>
                            <div class="col-md-6">
                            Grupos que possuem o motivo / problema
                            </div>
                            <div id="sourceFields">
                                <!--
                                <div id="idUsuario_1">First name</div>
                                <div id="idUsuario_2">Last name</div>
                                <div id="idUsuario_3">Home</div>
                                <div id="idUsuario_4">Work</div>
                                <div id="idUsuario_5">Direct</div>
                                <div id="idUsuario_6">Cell</div>
                                <div id="idUsuario_7">Fax</div>
                                <div id="idUsuario_8">Work email</div>
                                <div id="idUsuario_9">Personal email</div>
                                <div id="idUsuario_10">Website</div>
                                -->
                                <?php foreach($todosGrupos as $tG){ ?>
                                <div id="idGrupo_<?php echo $tG->cd_grupo_resolucao;?>"><?php echo $tG->nome_grupo_resolucao;?></div>
                                <?php } ?>
                            </div>
                            <div id="destinationFields">
                            <?php foreach($gruposPossuiMotivo as $gPm){ ?>
                                <div id="idGrupo_<?php echo $gPm->cd_grupo_resolucao; ?>"><?php echo $gPm->nome_grupo_resolucao; ?></div>
                            <?php } ?>
                            </div>
                        </div>                        
                    <?php             
                    } 
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
  
    <?php if($prazo_motivo == 0){ ?>
        $('.grupos').css('display', 'none');
    <?php } ?>
    
    // Valida o formulário
	$("#salvar_motivo").validate({
		debug: false,
		rules: {
			nome_motivo: {
                required: true
            },
            cd_tipo_atendimento: {
                required: true
            },
            cd_categoria: {
                required: true
            },
            prazo_motivo: {
                required: true
            }
		},
		messages: {
			nome_motivo: {
                required: "Digite o nome do motivo."
            },
            cd_tipo_atendimento: {
                required: "Selecione o tipo de atendimento."
            },
            cd_categoria: {
                required: "Selecione a categoria."
            },
            prazo_motivo: {
                required: "Informe o prazo."
            }
	   }
   });
   
   $("#prazo_motivo").keyup(function(){
    
        if($(this).val() != ''){

            if($(this).val() == 0){
                $(".grupos").css("display", "none");
            }else{
                $(".grupos").css("display", "block");
            }
        
        }

    });  
     
/*
    $("#cd_departamento").change(function(){

        $.ajax({
          type: "POST",
          url: '<?php echo base_url(); ?>ajax/usuariosDepartamento',
          data: {
            cd_departamento: $(this).val(),
            usuariosGrupo: $("#destinationFields").sortable('toArray')
            
          },
          dataType: "json",
          error: function(res) {
            $("#sourceFields").html('<span>Erro de execução</span>');
          },
          success: function(res) {
            var conteudoHtml = '';
            
            $.each(res, function() {
            
                conteudoHtml += '<div class="fc-field" id="idUsuario_'+this.cd_usuario+'">'+this.nome_usuario+'</div>';
            
            });
               
            $("#sourceFields").html(conteudoHtml);
             
          }
        });

    });
*/   
});

<?php if($cd_motivo){ ?>

var $sourceFields = $("#sourceFields");
var $destinationFields = $("#destinationFields");
var $chooser = $("#fieldChooser").fieldChooser(sourceFields, destinationFields);  

// atualizar dinamicamente
$(function(){
	$("#destinationFields").sortable({
		opacity: 0.6,
		cursor: 'move',
		update: function(){
/*
            var usuariosGrupo = $("#destinationFields").sortable('serialize');
                
            $.post('<?php echo base_url(); ?>ajax/atualizaGrupoResolvedores', usuariosGrupo, function(retorno){
    			$("#array").html(retorno);
    		});  

            var valores = new Array();
            $(this).each(function(){
                        valores.push( $(this).html() );
            });
            alert(valores);
*/     
            setTimeout(function() {
                var usuariosGrupo = $("#destinationFields").sortable('toArray');
                $.post('<?php echo base_url(); ?>ajax/atualizaGrupoResolvedores/grupo', {'grupos':usuariosGrupo, 'cd_motivo': <?php echo $cd_motivo; ?>}, function(retorno){
        			$("#res_associacao").html(retorno);
        		}); 
            }, 1);  
            
            setTimeout(function() {
           	    $("#res_associacao").html('');
            }, 3000);     
		}
        
	});
});

<?php } ?>

</script>