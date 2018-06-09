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
                    <li class="active">Ficha usu&aacute;rio</li>
                </ol>
                <div id="divMain">
                    <?php
                        echo $this->session->flashdata('statusOperacao');
                        $data = array('class'=>'pure-form','id'=>'salvar_usuario');
                    	echo form_open('usuario/salvar',$data);
                            $attributes = array('id' => 'address_info', 'class' => 'address_info');
             
                    		echo form_fieldset("Ficha usu&aacute;rio<a href='".base_url('usuario/usuarios')."' class='linkDireita'><span class='glyphicon glyphicon-arrow-left'></span>&nbspVoltar Pesquisar</a>", $attributes);
                    		
                                echo '<div class="row">';
                                
                                    echo '<div class="col-md-3">';
                                    echo form_label('Login da rede (AD)<span class="obrigatorio">*</span>', 'login_usuario');
                        			$data = array('name'=>'login_usuario', 'value'=>$login_usuario,'id'=>'login_usuario', 'placeholder'=>'Digite o login do AD', 'class'=>'form-control');
                        			echo form_input($data);
                                    echo '</div>';
                                
                                    echo '<div class="col-md-3">';
                                    echo form_label('Nome<span class="obrigatorio">*</span>', 'nome_usuario');
                        			$data = array('name'=>'nome_usuario', 'value'=>$nome_usuario,'id'=>'nome_usuario', 'placeholder'=>'Digite o nome', 'class'=>'form-control');
                        			echo form_input($data);
                                    echo '</div>';
                                    
                                    echo '<div class="col-md-3">';
                                    echo form_label('E-mail<span class="obrigatorio">*</span>', 'email_usuario');
                        			$data = array('name'=>'email_usuario', 'value'=>$email_usuario,'id'=>'email_usuario', 'placeholder'=>'Digite o e-mail', 'class'=>'form-control');
                        			echo form_input($data);
                                    echo '</div>';
                                    
                                    echo '<div class="col-md-3">';
                                    echo form_label('Ramal', 'ramal_usuario');
                        			$data = array('name'=>'ramal_usuario', 'value'=>$ramal_usuario,'id'=>'ramal_usuario', 'placeholder'=>'Digite o ramal', 'maxlength'=>4, 'class'=>'form-control');
                        			echo form_input($data);
                                    echo '</div>';
                                    
                                echo '</div>';
                                
                                echo '<div class="row">';
                                
                                    echo '<div class="col-md-3">';
                                    $options = array('' => '');		
                            		foreach($estado as $loc){
                            			$options[$loc->cd_estado] = $loc->nome_estado;
                            		}	
                            		echo form_label('Cidade<span class="obrigatorio">*</span>', 'cd_estado');
                            		echo form_dropdown('cd_estado', $options, $cd_estado, 'id="cd_estado" class="form-control"');
                                    echo '</div>';
                                    
                                    echo '<div class="col-md-3">';
                                    $options = array('' => '');		
                            		foreach($departamento as $dep){
                            			$options[$dep->cd_departamento] = html_entity_decode($dep->nome_departamento);
                            		}	
                            		echo form_label('Departamento<span class="obrigatorio">*</span>', 'cd_departamento');
                            		echo form_dropdown('cd_departamento', $options, $cd_departamento, 'id="cd_departamento" class="form-control"');
                                    echo '</div>';
                                    
                                    echo '<div class="col-md-3">';
                                    $options = array('' => '');		
                            		foreach($perfil as $per){
                            			$options[$per->cd_perfil] = $per->nome_perfil;
                            		}	
                            		echo form_label('Perfil<span class="obrigatorio">*</span>', 'cd_perfil');
                            		echo form_dropdown('cd_perfil', $options, $cd_perfil, 'id="cd_perfil" class="form-control"');
                                    echo '</div>';
                                    
                                    echo '<div class="col-md-3">';
                                    $options = array('' => '', 'S' => 'Sim', 'N' => 'N&atilde;o');		
                            		echo form_label('&Eacute; atendente?<span class="obrigatorio">*</span>', 'atendente_usuario');
                            		echo form_dropdown('atendente_usuario', $options, $atendente_usuario, 'id="atendente_usuario" class="form-control"');
                                    echo '</div>';
                                    
                                echo '</div>';
                                
                                echo '<div class="row">';
                                    
                                    echo '<div id="div_local" class="col-md-3">';
                                    $options = array('' => '');	
                                    foreach($local as $lo){
                                        $options[$lo->cd_local] = $lo->nome_local;
                                    }	
                            		echo form_label('Local atendimento<span class="obrigatorio">*</span>', 'cd_local');
                            		echo form_dropdown('cd_local', $options, $cd_local, 'id="cd_local" class="form-control"');
                                    echo '</div>';
                                    
                                    echo '<div class="col-md-3">';
                                    $options = array('A' => 'Ativo', 'I' => 'Inativo');		
                            		echo form_label('Status', 'status_usuario');
                            		echo form_dropdown('status_usuario', $options, $status_config_usuario, 'id="status_usuario" class="form-control"');
                                    echo '</div>';
                                
                                echo '</div>';
                                                              
                                echo '<div class="row">';
                                
                                echo form_hidden('cd_usuario', $cd_usuario);
                                
                                echo form_submit("btn_cadastro","Salvar", 'class="btn btn-primary pull-right"');
                                echo '</div>';
                                                            
                    		echo form_fieldset_close();
                    	echo form_close(); 
                        
                        
                    ?>  
                    
                    <?php if($cd_usuario){ ?>
                    <div id="divGrupo" class="row center">
                        <div><strong>Quais s&atilde;o os grupos resolvedores que o usu&aacute;rio far&aacute; parte?</strong></div>
                        <div id="res_associacao"></div>
                        <div class="row" id="fieldChooser" tabIndex="1">
                            <div class="col-md-6">
                            Grupos dispon&iacute;veis
                            </div>
                            <div class="col-md-6">
                            Grupos que o usu&aacute;rio faz parte
                            </div>
                            <div id="sourceFields">
                                <?php foreach($grupos as $g){ ?>
                                <div id="idGrupo_<?php echo $g->cd_grupo_resolucao;?>"><?php echo $g->nome_grupo_resolucao;?></div>
                                <?php } ?>
                            </div>
                            <div id="destinationFields">
                            <?php foreach($gruposUsuario as $gU){ ?>
                                <div id="idGrupo_<?php echo $gU->cd_grupo_resolucao; ?>"><?php echo $gU->nome_grupo_resolucao; ?></div>
                            <?php } ?>
                            </div>
                        </div>                         
                    </div>
                    <?php } ?>      
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
    
    <?php if(empty($cd_local)){ ?>
    $("#div_local").css('display','none');
    <?php } ?>
    
    $(".data").mask("00/00/0000");
    
    $("#atendente_usuario").change(function() {
        if($(this).val() == 'N' || $(this).val() == ''){
            $("#cd_local").val('');
            $("#div_local").css('display','none');
        }else{
            $("#div_local").css('display','block'); 
        }
    });
    
    // Valida o formulário
	$("#salvar_usuario").validate({
		debug: false,
		rules: {
			nome_usuario: {
                required: true,
                minlength: 10
            },
            email_usuario: {
                required: true,
				email: true
			},
            login_usuario: {
                required: true
			},
            cd_estado: {
                required: true
			},
            cd_departamento: {
                required: true
			},
            cd_perfil: {
                required: true
			},
            atendente_usuario: {
                required: true
			},
            cd_local: {
                required: function(element) {
                    return $("#atendente_usuario").val() == 'S';
                }
			}          
		},
		messages: {
			nome_usuario: {
                required: "Digite o nome do usu&aacute;rio.",
                minlength: "Digite o nome completo"
            },
            email_usuario: {
                required: "Digite o e-mail.",
                email: "E-mail inv&aacute;lido."
            },
            login_usuario: {
                required: "Digite o login da rede (AD)."
            },
            cd_estado: {
                required: "Selecione a cidade."
            },
            cd_departamento: {
                required: "Selecione o departamento."
            },
            cd_perfil: {
                required: "Selecione o perfil."
            },
            atendente_usuario: {
                required: "O usu&aacute;rio &eacute; atendente."
            },
            cd_local: {
                required: "O usu&aacute;rio &eacute; atendente."
            }         
	   }
   });        
   
});

<?php if($cd_usuario){ ?>

var $sourceFields = $("#sourceFields");
var $destinationFields = $("#destinationFields");
var $chooser = $("#fieldChooser").fieldChooser(sourceFields, destinationFields);  

// atualizar dinamicamente
$(function(){
	$("#destinationFields").sortable({
		opacity: 0.6,
		cursor: 'move',
		update: function(){     
            setTimeout(function() {
                var grupo = $("#destinationFields").sortable('toArray');              
                $.post('<?php echo base_url(); ?>ajax/associacaoGruposUsuario', {'grupos':grupo, 'cd_usuario': <?php echo $cd_usuario; ?>}, function(retorno){
        			$("#res_associacao").html(retorno);
        		}); 
            }, 1);  
            
            /*setTimeout(function() {
           	    $("#res_associacao").html('');
            }, 3000);  */   
		}
        
	});
});

<?php } ?>

</script>