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
                    <li class="active">Ficha grupo resolu&ccedil;&atilde;o</li>
                </ol>
                <div id="divMain">             
                    <?php
                        echo $this->session->flashdata('statusOperacao');
                        $data = array('class'=>'pure-form','id'=>'salvar_grupo_resolucao');
                    	echo form_open('grupo_resolucao/salvar',$data);
                            $attributes = array('id' => 'address_info', 'class' => 'address_info');
             
                    		echo form_fieldset("Ficha grupo resolu&ccedil;&atilde;o<a href='".base_url('grupo_resolucao/grupos_resolucoes')."' class='linkDireita'><span class='glyphicon glyphicon-arrow-left'></span>&nbspVoltar Pesquisar</a>", $attributes);
                    		
                                echo '<div class="row">';
                                
                                    echo '<div class="col-md-6">';
                                    echo form_label('Nome<span class="obrigatorio">*</span>', 'nome_grupo_resolucao');
                        			$data = array('name'=>'nome_grupo_resolucao', 'value'=>$nome_grupo_resolucao,'id'=>'nome_grupo_resolucao', 'placeholder'=>'Digite o nome', 'class'=>'form-control');
                        			echo form_input($data);
                                    echo '</div>';
                                    
                                    echo '<div class="col-md-6">';
                                    $options = array('A' => 'Ativo', 'I' => 'Inativo');		
                            		echo form_label('Status<span class="obrigatorio">*</span>', 'status_grupo_resolucao');
                            		echo form_dropdown('status_grupo_resolucao', $options, $status_grupo_resolucao, 'id="status_grupo_resolucao" class="form-control"');
                                    echo '</div>';
                                    
                                echo '</div>';
                                                              
                                echo '<div class="row">';
                                
                                echo form_hidden('cd_grupo_resolucao', $cd_grupo_resolucao);
                                
                                echo form_submit("btn_cadastro","Salvar", 'class="btn btn-primary pull-right"');
                                echo '</div>'; 
                                                            
                    		echo form_fieldset_close();
                    	echo form_close(); 
                        
                    if($cd_grupo_resolucao){    
                    ?> 
                    
                    <div id="accordion">
                      <h3>Associe os motivos / problemas ao grupo</h3>
                      <div>
                        <p>
                            <div class="row center" id="res_associacao"></div>
                            <div class="row" id="fieldChooser" tabIndex="1">
                                <div class="col-md-6">
                                Motivos / problemas dispon&iacute;veis
                                </div>
                                <div class="col-md-6">
                                Motivos / problemas associados ao grupo
                                </div>
                                <div id="sourceFields">
                                    <?php foreach($todosMotivos as $tM){ ?>
                                    <div id="idMotivo_<?php echo $tM->cd_motivo; ?>"><?php echo $tM->nome_motivo; ?></div>
                                    <?php } ?>                            
                                </div>
                                <div id="destinationFields">
                                    <?php foreach($motivosGrupo as $mG){ ?>
                                    <div id="idMotivo_<?php echo $mG->cd_motivo; ?>"><?php echo $mG->nome_motivo; ?></div>
                                    <?php } ?>
                                </div>
                            </div>                        
                        </p>
                      </div>
                      <h3>Associe usu&aacute;rios ao grupo</h3>
                      <div>
                        <p>
                            <div class="row center" id="res_associacao2"></div>
                            <div class="row" id="fieldChooser2" tabIndex="1">
                                <div class="row">
                                    <div class="col-md-6">
                                    <?php
                                    $options = array('' => '');	
                                    foreach($departamentos as $depart){
                                        $options[$depart->cd_departamento] = $depart->nome_departamento;
                                    }	
                            		echo form_label('Selecione o departamento<span class="obrigatorio">*</span>', 'departamento');
                            		echo form_dropdown('departamento', $options, '', 'id="departamento" class="form-control"');
                                    ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                Usu&aacute;rios dispon&iacute;veis
                                </div>
                                <div class="col-md-6">
                                Usu&aacute;rios associados ao grupo
                                </div>
                                <div id="sourceFields2">
                                                              
                                </div>
                                <div id="destinationFields2">
                                    <?php foreach($usuariosGrupo as $usG){ ?>
                                    <div id="idUsuario_<?php echo $usG->cd_usuario; ?>"><?php echo $usG->nome_usuario; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </p>
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
    
    $(".data").mask("00/00/0000");
    
    $(".actions").click(function() {
        $('#aguarde').css({display:"block"});
    });
});


$(document).ready(function(){
    
    $("#departamento").change(function(){
        
        if($(this).val() != ''){
            
            $.ajax({
              type: "POST",
              url: '<?php echo base_url(); ?>ajax/usuariosDepartamento',
              data: {
                cd_departamento: $(this).val(),
                cd_grupo_resolucao: <?php echo $cd_grupo_resolucao; ?>
              },
              dataType: "json",
              error: function(res) {
 	            //$("#resQtdArquivosDia").html('<span>Erro de execução</span>');
                alert('erro');
              },
              success: function(res) {
                
               var conteudoHtml = '';
                if(res.length > 0){
                
                    $.each(res, function() {
                      
                      conteudoHtml += '<div class="fc-field" id="idUsuario_'+this.cd_usuario+'">'+this.nome_usuario+'</div>';
                      
                    });
                    
                    $("#sourceFields2").html(conteudoHtml);
                
                }else{
                    
                    $("#sourceFields2").html('');
                    
                }
                
              }
            });
            
        }else{
            $("#sourceFields2").html('');
        }
        
    });
    
    $(function() {
        $( "#accordion" ).accordion({
            collapsible: true, // Habilita a opção de expandir e ocultar ao clicar
            heightStyle: "content",
            active: false
        });
    });
    
    // Valida o formulário
	$("#salvar_grupo_resolucao").validate({
		debug: false,
		rules: {
			nome_grupo_resolucao: {
                required: true
            }
		},
		messages: {
			nome_grupo_resolucao: {
                required: "Digite o nome do grupo resolução."
            }
	   }
   });   
   
});

<?php if($cd_grupo_resolucao){ ?>

var $sourceFields = $("#sourceFields");
var $destinationFields = $("#destinationFields");
var $chooser = $("#fieldChooser").fieldChooser(sourceFields, destinationFields);  

var $sourceFields = $("#sourceFields2");
var $destinationFields = $("#destinationFields2");
var $chooser = $("#fieldChooser2").fieldChooser(sourceFields2, destinationFields2);  

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
                var motivosGrupo = $("#destinationFields").sortable('toArray');              
                $.post('<?php echo base_url(); ?>ajax/atualizaGrupoResolvedores/motivo', {'motivos':motivosGrupo, 'cd_grupo_resolucao': <?php echo $cd_grupo_resolucao; ?>}, function(retorno){
        			$("#res_associacao").html(retorno);
        		}); 
            }, 1);  
            
            setTimeout(function() {
           	    $("#res_associacao").html('');
            }, 3000);     
		}
        
	});
});

$(function(){
	$("#destinationFields2").sortable({
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
                var usuariosGrupo = $("#destinationFields2").sortable('toArray');              
                $.post('<?php echo base_url(); ?>ajax/atualizaGrupoUsuarios', {'usuarios':usuariosGrupo, 'cd_grupo_resolucao': <?php echo $cd_grupo_resolucao; ?>}, function(retorno){
        			$("#res_associacao2").html(retorno);
        		}); 
            }, 1);  
            
            setTimeout(function() {
           	    $("#res_associacao2").html('');
            }, 3000);     
		}
        
	});
});

<?php } ?>

</script>