<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>
<div class="container">
            <!--<div class="col-md-9 col-sm-8"> USADO PARA SIDEBAR -->
            <div class="col-lg-12">
                
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('home/inicio'); ?>">Principal</a>
                    </li>
                    <li class="active">Ficha tipo atendimento</li>
                </ol>
                <div id="divMain">
                    <?php
                        echo $this->session->flashdata('statusOperacao');
                        $data = array('class'=>'pure-form','id'=>'salvar_tipo_atendimento');
                    	echo form_open('tipo_atendimento/salvar',$data);
                            $attributes = array('id' => 'address_info', 'class' => 'address_info');
             
                    		echo form_fieldset("Ficha tipo atendimento<a href='".base_url('tipo_atendimento/tipo_atendimentos')."' class='linkDireita'><span class='glyphicon glyphicon-arrow-left'></span>&nbspVoltar Pesquisar</a>", $attributes);
                    		
                                echo '<div class="row">';
                                
                                    echo '<div class="col-md-8">';
                                    echo form_label('Nome<span class="obrigatorio">*</span>', 'nome_tipo_atendimento');
                        			$data = array('name'=>'nome_tipo_atendimento', 'value'=>$nome_tipo_atendimento,'id'=>'nome_tipo_atendimento', 'placeholder'=>'Digite o nome', 'class'=>'form-control');
                        			echo form_input($data);
                                    echo '</div>';
                                    
                                    echo '<div class="col-md-4">';
                                    $options = array('A' => 'Ativo', 'I' => 'Inativo');		
                            		echo form_label('Status<span class="obrigatorio">*</span>', 'status_tipo_atendimento');
                            		echo form_dropdown('status_tipo_atendimento', $options, $status_tipo_atendimento, 'id="status_tipo_atendimento" class="form-control"');
                                    echo '</div>';
                                    
                                echo '</div>';
                                                              
                                echo '<div class="actions">';
                                
                                echo form_hidden('cd_tipo_atendimento', $cd_tipo_atendimento);
                                
                                echo form_submit("btn_cadastro","Salvar", 'class="btn btn-primary pull-right"');
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
	$("#salvar_tipo_atendimento").validate({
		debug: false,
		rules: {
			nome_tipo_atendimento: {
                required: true
            }
		},
		messages: {
			nome_tipo_atendimento: {
                required: "Digite o nome do tipo atendimento."
            }
	   }
   });   
   
});

</script>