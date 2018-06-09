<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Painel registro</title>
  
<?php

# Bootstrap core CSS
echo link_tag(array('href' => 'assets/css/bootstrap.min.css','rel' => 'stylesheet','type' => 'text/css'));

echo link_tag(array('href' => 'assets/css/painel_registro.css','rel' => 'stylesheet','type' => 'text/css'));

# JavaScript
#echo "<script type='text/javascript' src='".base_url('assets/js/jquery.js')."'></script>";
echo "<script type='text/javascript' src='".base_url('assets/js/bootstrap.min.js')."'></script>";
echo "<script type='text/javascript' src='".base_url('assets/js/main.js')."'></script>";

echo link_tag(array('href' => 'assets/js/jquery-ui/jquery-ui.css','rel' => 'stylesheet','type' => 'text/css'));
echo "<script type='text/javascript' src='".base_url("assets/js/jquery-ui/jquery-ui.js")."'></script>";

?>
<script language="JavaScript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script language="JavaScript" src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
<?php
echo "<script type='text/javascript' src='".base_url('assets/js/fullscreen/jquery.fullscreen-min.js')."'></script>";
?>
 
  
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]       
  <link rel="shortcut icon" href="<?php echo base_url('assets/images/ico/favicon.ico'); ?>">-->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('assets/images/ico/apple-touch-icon-144-precomposed.png'); ?>">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('assets/images/ico/apple-touch-icon-114-precomposed.png');?>">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('assets/images/ico/apple-touch-icon-72-precomposed.png');?>">
  <link rel="apple-touch-icon-precomposed" href="<?php echo base_url('images/ico/apple-touch-icon-57-precomposed.png'); ?>">
</head><!--/head-->
<body>
    <div class="col-md-12">
        <div class="row">
            <div id="alerta" class="col-md-12 alert alert-success">   
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"> 
            </div>
            <div id="titulo" class="col-md-4" class="col-md-12">
                <strong>Aproxime o qr code na web cam</strong>                                            
            </div>
            <div id="div_fullscreen" class="col-md-4">
                <button class="btn btn-success pull-right" id="fullscreen" onclick="$(document).toggleFullScreen();"><strong>Tela Cheia (Tecla F11)</strong></button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"> 
            </div>
            <div id="divWebCam" class="col-md-4">
            	<div id="webcam">
            	</div>
            	<div id="divCombo">
            		<select id="cameraNames" size="1" onChange="changeCamera()" >
            		</select>
                    <input type="hidden" id="decoded" name="decoded" value="" />
            	</div>            
            </div>            
            <div class="col-md-4">
            </div>
        </div>
        <div class="row">
            <div id="img_ilustracao" class="col-md-12">
                <img src="<?php echo base_url('assets/images/qr_in_ticket.jpg');?>" />                                             
            </div>
        </div>
    </div>
    <input type="hidden" id="cd_local" name="cd_local" value="<?php echo $this->input->post('cd_local'); ?>" /> 
    <input type="hidden" id="timezone" name="timezone" value="<?php echo $timezone; ?>" />    
		
<script> 
    
    self.setInterval(function(){ $(this).registraSenha() }, 1000); // Realiza a cada 1 segunda para verifica o qr code
    //self.setInterval(function(){ $(this).verificaSenha() }, 2000); // Inpede que o qr code seja lido novamente 
    var senha = '';
    var ultimo = ''; // Variável que armazena o último qr code processado pela câmera
    var cont = 0; // Controla quantas vezes o qr code foi lido (Zero (0) significa leitura inicial)
    
    $.fn.verificaSenha = function() {
        
        // Variável que armazena o qr code corrente capturado
        senha = $.scriptcam.getBarCode();

        if(cont == 1){ // Um (1) significa que o mesmo qr code esta sendo lido pela segunda vez
            
            // Se o qr code que esta sendo lido novamente for igual ao qr code armazenado na variável 'ultimo' (Lido novamente)
            if(senha == ultimo){                    
                
                // Informe a o qr code já foi lido                                    
                setTimeout(function() { 
                    $('#alerta').css('display','block');
                    $('#alerta').text('A senha já foi registrada! Aguarde o atendimento');
                }, 5000);
                
                setTimeout(function() {
               	    $('#alerta').css('display','none');
                }, 8000);
                
                // Zera a variável para que a condição 'if(senha == ultimo)' não seja executada novamente para o mesmo qr code
                cont = 0;
            
            }                                                           
                                        
        }
        
    };
    
    // Realiza o registro da senha
    $.fn.registraSenha = function() {
        
        // Variável que armazena o qr code corrente capturado
        senha = $.scriptcam.getBarCode();

        // Se o qr code for diferente de branco
        if(senha != ''){

            //if(senha != ultimo){
                
                $.ajax({
                  type: "POST",
                  url: '<?php echo base_url(); ?>ajax/registraSenha',
                  data: {
                    senhaCaptada: senha,
                    local: $("#cd_local").val(),
                    timezone: $("#timezone").val()
                  },
                  dataType: "json",
                  error: function(res) {
                    $("#alerta").html('<span>Erro de execução</span>');
                  },
                  success: function(res) {
                    
                    if(res['total'] == 'repetida'){
                        // Esse senha foi registrada a menos de 5 minutos! Registre outra, por favor.
                        $('#alerta').text('A senha já foi registrada! Aguarde o atendimento.');
                    }else if(res['total'] == false){
                        $('#alerta').text('Erro! passe a senha novamente.');
                    }else{
                        $('#alerta').text('Registrado! Aguarde o atendimento!');
                    }
                    
                    $('#alerta').css('display','block');
                    
                  }
                });
                
                setTimeout(function() {
               	    $('#alerta').css('display','none');
                }, 4000);
            
                $('#decoded').val(senha);
                
                senha = '';
                // Armazena o qr code corrente na variável última para efeitos de comparação com as futuras solicitações
                //ultimo = senha;
                //cont = 1;                
            
            /*}else{
                
                $('#alerta').text('Esse senha acabou de ser registrada! Registre outra, por favor.');
                
                setTimeout(function() {
               	    $('#alerta').css('display','none');
                }, 4000);
                
            }*/
            
        }
        
    };    
    
    var dirSwf = '<?php echo base_url('assets/js/scriptcam'); ?>/scriptcam.swf';
	$(document).ready(function() {
       
       //$("#fullscreen").hide();
       $("#fullscreen").css('visibility', 'hidden');
       
       $( "#div_fullscreen" ).mouseover(function() { // Executa quando o mouse esta encima
          //$("#fullscreen").show();
          $("#fullscreen").css('visibility', 'visible');
       });
       
       $( "#div_fullscreen" ).mouseout(function() { // Executa quando o mouse sai decima
          //$("#fullscreen").hide();
          $("#fullscreen").css('visibility', 'hidden');
       });
       
       $( "#fullscreen" ).on( "click", function() {
          $('#cameraNames').val(1);
       });
       
       // Criado por Tiago
       // Função criada para setar o id da câmera usb na combo
       $( "#cameraNames" ).on( "change", function() {
          $('#cameraNames').val(0);
       });
       
       // Criado por Tiago
       // Função que chama a função que seleciona a câmera correta 1 segundo após o carregamento da página
       setTimeout(function() {
          $('#cameraNames').trigger("change");
       }, 1000);
       
        $('#alerta').css('display','none');
       
		$("#webcam").scriptcam({
			onError:onError,
			cornerRadius:0,
			onWebcamReady:onWebcamReady
		});
	});

	function onError(errorId,errorMsg) {
		alert(errorMsg);
	}	
    		
	function changeCamera() {
		$.scriptcam.changeCamera($('#cameraNames').val());
	}
    
	function onWebcamReady(cameraNames,camera,microphoneNames,microphone,volume) {
		$.each(cameraNames, function(index, text) {
			$('#cameraNames').append( $('<option></option>').val(index).html(text) )
		}); 
		
	}
</script>    
<script language="JavaScript" src="<?php echo base_url('assets/js/scriptcam');?>/scriptcam.js"></script>
</body>
</html>