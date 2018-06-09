<!-- Modal que exibe que não há senha disponível -->
<div class="modal fade" id="semSenha" tabindex="-1" role="dialog" aria-labelledby="semSenha" aria-hidden="true">
    <div class="modal-dialog" style="width:250px">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title"><strong>N&atilde;o h&aacute; senha!</strong></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
            </div>

    </div>
  </div>
</div>

<!-- Modal de senha não registrada que possibilita a gravação da nova senha -->
<div class="modal fade" id="senhaNaoRegistrada" tabindex="-1" role="dialog" aria-labelledby="senhaNaoRegistrada" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title"><strong>Senha sem entrada. Deseja registrar e atender?</strong></h4>
            </div>
            <div class="modal-body">
                <?php              
                    $data = array('class'=>'pure-form','id'=>'frm_registraEatende');
                    echo form_open('atendimento/registraEatende',$data);
                ?>
                <div id="conteudoModal"></div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="senhaCaptada" name="senhaCaptada" value="" />
                <input type="hidden" id="timezone" name="timezone" value="<?php echo $this->session->userdata('localTimeZone'); ?>" />                                
                <input type="hidden" id="local" name="local" value="<?php echo $this->session->userdata('cd_local'); ?>" />                            
                <button type="button" class="btn btn-primary" data-dismiss="modal">N&atilde;o</button>
                <button type="submit" class="btn btn-primary">Sim</button>
        </div>
                <?php
                echo form_close();
                ?>
    </div>
  </div>
</div>

<!-- Modal que configura o guichê do atendente -->
<div id="config_atendente" class="modal fade">
    <div class="modal-dialog" style="width:300px">
        <div class="modal-content">
            <div class="modal-header center">
                <h4 class="modal-title">Configura&ccedil;&atilde;o do Atendente</h4>
            </div>
            <hr />
            <div class="modal-body">
                    <?php
                        $data = array('class'=>'pure-form','id'=>'frm_config_atendente');
                        echo form_open('usuario/iniConfigAtendente',$data);
                        
                        echo '<div id="verGuiche" class="row"></div>';
                        
                        echo '<div class="row">';
                            echo '<div class="col-md-12">';
                            $options = array('' => '');	
                            foreach($guiches as $gui){
                    			$options[$gui->cd_guiche] = $gui->nome_guiche;
                    		}		
                    		echo form_label('Selecione o guich&ecirc;<span class="obrigatorio">*</span>', 'cd_guiche_config');
                    		echo form_dropdown('cd_guiche_config', $options, $this->session->userdata('cd_guiche'), 'id="cd_guiche_config" class="form-control"');
                            echo '</div>';
                        echo '</div>';
                    ?>              
                <!--<p>Do you want to save changes you made to document before closing?</p>
                <p class="text-warning"><small>If you don't save, your changes will be lost.</small></p>-->
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Salvar configura&ccedil;&atilde;o</button>
            </div>
                    <?php
                    	echo form_close(); 
                    ?>            
        </div>
    </div>
</div>

<header class="navbar navbar-inverse navbar-fixed-top wet-asphalt" role="banner">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a style="font-size: 20px;" class="navbar-brand" href="<?php echo base_url('home/inicio');?>">
            <!--<img src="<?php echo base_url('assets/images/logo.png');?>" alt="logo">-->
            Gest&atilde;o de atendimentos            
        </a>
      </div>
      <div id="exibe_nome"><?php echo $this->session->userdata('bem_vindo'); ?></div>
      <div class="collapse navbar-collapse">
        <?php echo $menu; ?>
        
      </div>
      
    </div>
    
  </header><!--/header-->

<?php 

if($this->session->userdata('atendente') == 'S'){ // ABRIR

if(is_numeric($senha_atendimento)){
    $classExibicao = "numero_exibicao";
}else{ 
    $classExibicao = "nome_exibicao";
}

?>
<div id="barra_top">
    <a id="link_atend" class="expander" href="#">ATENDIMENTOS</a>
</div>
<div id="dados_barra_top" class="content">
    <div class="row">
        <div id="info_guiche" class="col-md-12">
        <strong>Seu guich&ecirc;: <?php echo $this->session->userdata('guiche'); ?></strong> <a data-toggle="modal" data-target="#config_atendente" href="#">alterar</a>
        </div>
    </div>
    <div class="row" id="numero_atendido">
        <div class="center"><strong>Voc&ecirc; est&aacute; atendendo a senha<br /></strong></div>
        <div id="<?php echo $classExibicao; ?>" class="center"><strong><?php echo $senha_atendimento; ?></strong></div>
    </div>
    <div class="row">
      <!--<div class="col-md-3 voltar_avancar">
        <span class="glyphicon glyphicon-chevron-left"></span>
      </div>
      <div id="numero_especifico" class="col-md-6">
        <input type="text" id="numero_atendimento" value="" />
        <button id="bt_atender" type="button" class="btn btn-primary pull-right">Atender</button>
      </div>
      <div id="proximo_senha" class="col-md-3 voltar_avancar">
        <span class="glyphicon glyphicon-chevron-right"></span>
      </div>-->
      <div class="col-md-1 voltar_avancar">
        <!--<span class="glyphicon glyphicon-chevron-left"></span>-->
      </div>
      <div id="numero_especifico" class="col-md-8">
        <!--<input class="form-control" style="float: left; width: 120px;" type="text" id="numero_atendimento" value="" />-->
        <?php
        $options = array('' => '', '1' => '1','2' => '2');	
        /*foreach($tipo_atendimento as $tA){
			$options[$tA->cd_tipo_atendimento] = $tA->nome_tipo_atendimento;
		}*/		
		#echo form_dropdown('cd_tipo_atendimento', $options, $cd_tipo_atendimento, 'id="cd_tipo_atendimento" class="form-control" style="float: left; width: 120px;"');
        ?>
        <input type="text" class="form-control" id="numero_atendimento" style="float: left; width: 120px; font-size: 16px" value="" />
        <button style="float: right;" id="bt_atender" type="button" class="btn btn-primary pull-right">Atender</button>
      </div>
      <div id="proximo_senha" class="col-md-3 voltar_avancar">
        <span class="glyphicon glyphicon-chevron-right"></span>
      </div>
    </div>
</div>
<?php
}
?>
<script type="text/javascript">

function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }
    alert(out);
}

$(document).ready(function(){    
    
    // Só permiti digitar números
    /*$(function() {
    
        $('#numero_atendimento').keypress(function(event) {
            var tecla = (window.event) ? event.keyCode : event.which;
            if ((tecla > 47 && tecla < 58)) return true;
            else {
                if (tecla != 8) return false;
                else return true;
            }
        });
    
    });*/
      
<?php 
if($this->session->userdata('configAtendente') == 'S'){ 
?>     
    // Click no botão próxima senha
    $("#proximo_senha").click(function(){
        
        $.ajax({
          type: "POST",
          url: '<?php echo base_url(); ?>atendimento/proximaSenha',
          data: {
            local: <?php echo $this->session->userdata('cd_local'); ?>,
            atendente: <?php echo $this->session->userdata('cd'); ?>,
            guiche: <?php echo $this->session->userdata('cd_guiche'); ?>,
            timezone: '<?php echo $this->session->userdata('localTimeZone'); ?>'       
          },
          dataType: "json",
          /*error: function(res) {
            $("#verGuiche").html('<div class="alert alert-warning">Erro de execução</div>');
          },*/
          success: function(res) {
          
            if(res.cd){
                
                $(location).attr('href','<?php echo base_url(); ?>atendimento/ficha/'+res.cd);
                //alert(res.senha);
            }else{
                
                $("#semSenha").modal({
                    
                });

            }

          }
          
        });
        
    });

<?php 
}
?>
    // Click no botão atender determinada senha
    $("#bt_atender").click(function(){
        
        if($("#numero_atendimento").val() != ''){
        /*
            $.post('<?php echo base_url(); ?>atendimento/chamaSenha', {'senha_atendimento':$("#numero_atendimento").val()}, function(retorno){
                if($.isNumeric(retorno)){
                    $(location).attr('href','<?php echo base_url(); ?>home/inicio');
                }else{
                    $("#senhaNaoRegistrada").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            }); 
        */
        
            $.ajax({
              type: "POST",
              url: '<?php echo base_url(); ?>atendimento/chamaSenha',
              data: {
                senha_atendimento: $("#numero_atendimento").val()
              },
              dataType: "json",
              /*error: function(res) {
                $("#verGuiche").html('<div class="alert alert-warning">Erro de execução</div>');
              },*/
              success: function(res) {
              
                if(res.status == 'OK'){
                    
                    $(location).attr('href','<?php echo base_url(); ?>atendimento/ficha/'+res.cd);
                    
                }else{
                    
                    $("#senhaCaptada").val($("#numero_atendimento").val());
                                                            
                    $("#senhaNaoRegistrada").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    
                }

              }
              
            });
        }
        
    });
    
    // Modal de config atendente
    $('#cd_guiche_config').change(function(){
        
        if($(this).val() != ''){
        
            $.ajax({
              type: "POST",
              url: '<?php echo base_url(); ?>ajax/verificaGuiche',
              data: {
                cd_guiche: $(this).val(),
                cd_local: <?php echo $this->session->userdata('cd_local'); ?>,
                cd_usuario: <?php echo $this->session->userdata('cd'); ?>
              },
              dataType: "json",
              error: function(res) {
                $("#verGuiche").html('<div class="alert alert-warning">Erro de execução</div>');
              },
              success: function(res) {
              
                //$("#verGuiche").html('<div class="alert alert-warning">OK</div>');
                if(res.length > 0){
                    
                    $("#verGuiche").html('<div class="alert alert-warning">O atendente '+res[0].nome_usuario+' esta associado a esse guich&ecirc; caso voc&ecirc; prosiga esse atendente ser&aacute; derrubado no sistema.</div>');
                    
                }else{
                    
                    $("#verGuiche").html('');
                    
                }
                
                //dump(res);
              }
            });
       
       }else{
        
            $("#verGuiche").html('');
        
       }
        
    });
    
<?php 
if($this->session->userdata('configAtendente') == 'N'){ 
?>    
    
	$("#config_atendente").modal({
        backdrop: 'static',
        keyboard: false
    });

<?php 
}
?>    

    // Valida o formulário
	$("#frm_config_atendente").validate({
		debug: false,
		rules: {
			cd_guiche_config: {
                required: true
            }         
		},
		messages: {
			cd_guiche_config: {
                required: "Selecione o guich&ecirc;."
            }        
	   }
   });    

    
});

$('.expander').click(function(){
    if($('#link_atend').hasClass("collapsed") == true){ // DIV ABRIR  
        var exibicao = 'sim';    
    }else if($('#link_atend').hasClass("expanded") == true){ // DIV FECHADA
        var exibicao = 'nao';  
    }else{
        var exibicao = 'nao';
    }
    
    $.ajax({
        type: "POST",
        url: '<?php echo base_url(); ?>ajax/exibicaoDivAtendimento',
        data: {
            var_exibicao: exibicao
        }
    }).done(function() {
      //alert(1);
    });

});
    
$('.expander').simpleexpand();

<?php 

# Se a sessão estiver configurada para exibir o painel de chamar próxima senha
if($this->session->userdata('exibicao') == 'sim'){ // ABRIR
?>
    $('.content').css('display', 'block');
    $( "#link_atend" ).removeClass( "collapsed" ).addClass( "expanded" );
<?php    
}else{
?>
    $('.content').css('display', 'none');
    $( "#link_atend" ).removeClass( "expanded" ).addClass( "collapsed" );
<?php    
}

# Se a senha estiver em atendimento fecha painel de chamar próxima senha
if($senha_atendimento){ 
?>   
    $('.content').css('display', 'none');
    $( "#link_atend" ).removeClass( "expanded" ).addClass( "collapsed" );
<?php 
}
?>   

$("#numero_atendimento").autocomplete({
	source: '<?php echo base_url(); ?>ajax/pesquisaClienteAtp',
    minLength: 1/*,
    select: function( event, ui ) {
        window.location.href = '<?php echo base_url(); ?>paciente/iniPesquisa/'+ui.item.value;
    }*/
});
 
</script>