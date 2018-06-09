<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Sim TV - Painel de Registro</title>
  
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
echo "<script type='text/javascript' src='".base_url('assets/js/jQuery.print.js')."'></script>";
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
            <div id="titulo" class="col-md-4 text-center">
                <strong>Preencha abaixo</strong>                                            
            </div>
            <div id="div_fullscreen" class="col-md-4">
                <button class="btn btn-success pull-right" id="fullscreen" onclick="$(document).toggleFullScreen();"><strong>Tela Cheia (Tecla F11)</strong></button>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4"> 
            </div>
            <div class="col-sm-4">
                <label for="nome">Nome<span class="obrigatorio">*</span></label>
                <input style="font-size: 30px; height: 50px;" type="text" name="nome" id="nome" placeholder="Digite o nome" class="form-control valid" aria-required="true" aria-invalid="false">
            </div>          
            <div class="col-md-4">
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4"> 
            </div>
            <div class="col-sm-4">
                <label for="sobrenome">Sobrenome<span class="obrigatorio">*</span></label>
                <input style="font-size: 30px; height: 50px;" type="text" name="sobrenome" id="sobrenome" placeholder="Digite o sobrenome" class="form-control valid" aria-required="true" aria-invalid="false">
            </div>            
            <div class="col-md-4">
            </div>
        </div>
        
        <div id="ocultaBotao" class="row">
            <div class="col-md-4"> 
            </div>
            <div class="col-md-4 text-center">
            	<input style="font-weight: bold; font-size: 25px; margin-top: 10px" id="registrar" type="button" class="btn btn-warning" value="DAR ENTRADA" />            
            </div>            
            <div class="col-md-4">
            </div>
        </div>
        <div class="row">
            <div id="img_ilustracao" class="col-md-12">
                                                         
            </div>
        </div>
    </div>
    <input type="hidden" id="cd_local" name="cd_local" value="<?php echo $this->input->post('cd_local'); ?>" /> 
    <input type="hidden" id="timezone" name="timezone" value="<?php echo $timezone; ?>" />   
		
<script>   
    
function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }
    alert(out);
}

$(document).ready(function() {
   
   $('#ocultaBotao').css('display', 'none');
   
   $("#fullscreen").css('visibility', 'hidden');
   
   $( "#div_fullscreen" ).mouseover(function() { // Executa quando o mouse esta encima

      $("#fullscreen").css('visibility', 'visible');
   });
   
   $( "#div_fullscreen" ).mouseout(function() { // Executa quando o mouse sai decima
   
      $("#fullscreen").css('visibility', 'hidden');
      
   });
   
    $('#alerta').css('display','none');
    
    $('#nome').keyup(function(){
        
        if($(this).val() != "" && $("#sobrenome").val() != ""){
            $('#ocultaBotao').css('display', 'block');
        }else{
            $('#ocultaBotao').css('display', 'none');
        }
        
    });
    
    $('#sobrenome').keyup(function(){
        
        if($(this).val() != "" && $("#nome").val() != ""){
            $('#ocultaBotao').css('display', 'block');
        }else{
            $('#ocultaBotao').css('display', 'none');
        }
        
    });
    
    $('#registrar').click(function(){
        
        $.ajax({
          type: "POST",
          url: '<?php echo base_url(); ?>ajax/geraSenha',
          data: {
            local: $("#cd_local").val(),
            timezone: $("#timezone").val(),
            nome: $("#nome").val(),
            sobrenome: $("#sobrenome").val()
          },
          dataType: "json",
          error: function(res) {
            $("#alerta").html('<span>Erro de execução</span>');
          },
          success: function(res) {
            
            if(res['senha'] == false){
                $('#alerta').text('Erro ao registrar!');
            }else{
                $('#alerta').text('Registrado! Aguarde chamar pelo nome');
                $("#nome").val('');
                $("#sobrenome").val('');
                $('#ocultaBotao').css('display', 'none');
            }
            
            $('#alerta').css('display','block');
          }
          
        });
        
        setTimeout(function() {
       	    $('#alerta').css('display','none');
        }, 4000);
        
    });
   
});

</script>    
</body>
</html>