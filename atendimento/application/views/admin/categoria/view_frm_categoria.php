<?php
echo link_tag(array('href' => 'assets/js/drag_drop/style.css','rel' => 'stylesheet','type' => 'text/css'));
?>
<script type="text/javascript" src="<?php echo base_url("assets/js/drag_drop/fieldChooser.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>
<div class="container">
            <!--<div class="col-md-9 col-sm-8"> USADO PARA SIDEBAR -->
            <div class="col-lg-12">
                
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('home/inicio'); ?>">Principal</a>
                    </li>
                    <li class="active">Ficha categoria</li>
                </ol>
                <div id="divMain">
                    <?php
                        echo $this->session->flashdata('statusOperacao');
                        $data = array('class'=>'pure-form','id'=>'salvar_categoria');
                    	echo form_open('categoria/salvar',$data);
                            $attributes = array('id' => 'address_info', 'class' => 'address_info');
             
                    		echo form_fieldset("Ficha categoria<a href='".base_url('categoria/categorias')."' class='linkDireita'><span class='glyphicon glyphicon-arrow-left'></span>&nbspVoltar Pesquisar</a>", $attributes);
                    		
                                echo '<div class="row">';
                                
                                    echo '<div class="col-md-6">';
                                    echo form_label('Nome<span class="obrigatorio">*</span>', 'nome_categoria');
                        			$data = array('name'=>'nome_categoria', 'value'=>$nome_categoria,'id'=>'nome_categoria', 'placeholder'=>'Digite o nome', 'class'=>'form-control');
                        			echo form_input($data);
                                    echo '</div>';
                                    
                                    echo '<div class="col-md-6">';
                                    $options = array('A' => 'Ativo', 'I' => 'Inativo');		
                            		echo form_label('Status<span class="obrigatorio">*</span>', 'status_categoria');
                            		echo form_dropdown('status_categoria', $options, $status_categoria, 'id="status_categoria" class="form-control"');
                                    echo '</div>';
                                    
                                echo '</div>';
                                                              
                                echo '<div class="actions">';
                                
                                echo form_hidden('cd_categoria', $cd_categoria);
                                
                                echo form_submit("btn_cadastro","Salvar", 'class="btn btn-primary pull-right"');
                                echo '</div>';   
                                                            
                    		echo form_fieldset_close();
                    	echo form_close(); 
                        
                    if($cd_categoria){    
                    ?> 
                    
                    <div class="row center">
                        <div><strong>Grupos associados ao motivo / problema</strong></div>
                        <div id="res_associacao"></div>
                    </div>
                    <div class="row" id="fieldChooser" tabIndex="1">
                        <div class="col-md-6">
                        Tipos de atendimentos dispon&iacute;veis
                        </div>
                        <div class="col-md-6">
                        Tipos de atendimentos associados a categoria
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
                            <?php foreach($tiposAtendDisp as $tAd){ ?>
                            <div id="idTipoAtend_<?php echo $tAd->cd_tipo_atendimento;?>"><?php echo $tAd->nome_tipo_atendimento;?></div>
                            <?php } ?>
                        </div>
                        <div id="destinationFields">
                        <?php foreach($tiposAtendAssoc as $tAa){ ?>
                            <div id="idTipoAtend_<?php echo $tAa->cd_tipo_atendimento; ?>"><?php echo $tAa->nome_tipo_atendimento; ?></div>
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

function marcaTodos(){
    
    if($('#todos').prop('checked') == true){
        $('input:checkbox').prop('checked', true);
    }else{
        $('input:checkbox').prop('checked', false);
    }
    
}

function marcaGrupo(classe, campo){
    
    if(campo.checked == true){
        $(classe).prop('checked', true);
    }else{
        $(classe).prop('checked', false);
    }

}

$(document).ready(function(){
    
    $(".data").mask("00/00/0000");
    
    $(".actions").click(function() {
        $('#aguarde').css({display:"block"});
    });
});


/*
CONFIGURA O CALENDÁRIO DATEPICKER NO INPUT INFORMADO
*/
$("#data,#data2").datepicker({
	dateFormat: 'dd/mm/yy',
	dayNames: ['Domingo','Segunda','Ter&ccedil;a','Quarta','Quinta','Sexta','S&aacute;bado','Domingo'],
	dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
	dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','S&aacute;b','Dom'],
	monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
	monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
	nextText: 'Pr&oacute;ximo',
	prevText: 'Anterior',
    
    // Traz o calendário input datepicker para frente da modal
    beforeShow :  function ()  { 
        setTimeout ( function (){ 
            $ ( '.ui-datepicker' ). css ( 'z-index' ,  99999999999999 ); 
        },  0 ); 
    } 
});

$(document).ready(function(){
    
    // Valida o formulário
	$("#salvar_categoria").validate({
		debug: false,
		rules: {
			nome_categoria: {
                required: true,
                minlength: 5
            }
		},
		messages: {
			nome_categoria: {
                required: "Digite o nome do categoria.",
                minlength: "Digite o nome completo"
            }
	   }
   });   
   
});

<?php 
if($cd_categoria){   
?>
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
                var tiposAtend = $("#destinationFields").sortable('toArray');
                $.post('<?php echo base_url(); ?>ajax/atualizaTiposAtendCategoria', {'tipos':tiposAtend, 'cd_categoria': <?php echo $cd_categoria; ?>}, function(retorno){
        			$("#res_associacao").html(retorno);
        		}); 
            }, 1);  
            
            setTimeout(function() {
           	    $("#res_associacao").html('');
            }, 3000);     
		}
        
	});
});
<?php
}
?>
</script>