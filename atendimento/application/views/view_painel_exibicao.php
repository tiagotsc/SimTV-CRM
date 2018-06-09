<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Painel exibi&ccedil;&atilde;o</title>
  
<?php

# Bootstrap core CSS
echo link_tag(array('href' => 'assets/css/bootstrap.min.css','rel' => 'stylesheet','type' => 'text/css'));
#echo link_tag(array('href' => 'assets/css/font-awesome.min.css','rel' => 'stylesheet','type' => 'text/css'));
#echo link_tag(array('href' => 'assets/css/prettyPhoto.css','rel' => 'stylesheet','type' => 'text/css'));
#echo link_tag(array('href' => 'assets/css/animate.css','rel' => 'stylesheet','type' => 'text/css'));
#echo link_tag(array('href' => 'assets/css/main.css','rel' => 'stylesheet','type' => 'text/css'));

echo link_tag(array('href' => 'assets/css/painel_exibicao.css','rel' => 'stylesheet','type' => 'text/css'));

# JavaScript
echo "<script type='text/javascript' src='".base_url('assets/js/jquery.js')."'></script>";
echo "<script type='text/javascript' src='".base_url('assets/js/bootstrap.min.js')."'></script>";
#echo "<script type='text/javascript' src='".base_url('assets/js/jquery.prettyPhoto.js')."'></script>";
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
<body style="padding: 0px; margin: 0px;">
    <div class="col-md-12">
        <div class="row">
            <div id="titulo" class="col-md-8">
                <strong>Atendimento</strong>
            </div>
            <div id="div_fullscreen" class="col-md-4">
                <button class="btn btn-success pull-right" id="fullscreen" onclick="$(document).toggleFullScreen();"><strong>Tela Cheia (Tecla F11)</strong></button>
            </div>
        </div>
        <div class="row">
            <div id="senha_atendimento" class="col-md-12">
                
            </div>
        </div>
        <div class="row">
            <div id="guiche" class="col-md-6">
                <strong>Guich&ecirc;</strong>
            </div>
            <div id="numero_guiche" class="col-md-6">
                
            </div>
        </div>
    </div>
    <div id="audio"></div>
    <input type="hidden" id="senhaExibida"/>
<script type="text/javascript">

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
       
});

function verifica(){ 
	/*$.post(
		'<?php echo base_url(); ?>painel/consultaSenha',
		{
			ver_res : $("#ver_res").val(),
            local: 1
		},
		function(res){
			
			var	var_audio = '<audio autoplay src="<?php echo base_url('assets/audio/som.mp3'); ?>"><p>Seu nevegador não suporta o elemento audio.</p></audio>';
			
			if($("#senhaExibida").val() != res){
				$("#audio").html(var_audio);
			}
			
			$("#senhaExibida").val(res);
			$("#senha_atendimento").html('<strong>'+ res +'</strong>');
		}
	);*/
    
    $.ajax({
      type: "POST",
      url: '<?php echo base_url(); ?>painel/consultaSenha',
      data: {
        local: <?php echo $this->input->post('cd_local'); ?>
      },
      dataType: "json",
      /*error: function(res) {
        $("#alerta").html('<span>Erro de execução</span>');
      },*/
      success: function(res) {

        var	var_audio = '<audio autoplay src="<?php echo base_url('assets/audio/som.mp3'); ?>"><p>Seu nevegador não suporta o elemento audio.</p></audio>';
		var var_senha = '';
        var res_senha = '';
        var var_guiche = '';
        
		if($("#senhaExibida").val() != res['senha']){
            if(res['senha'] != null){
	           $("#audio").html(var_audio);
            }
		}
		
        if(res['senha'] == null){
            var_senha = '&nbsp';
            var_guiche = '&nbsp';
        }else{
            var_senha = res['senha'];
            var_guiche = res['guiche'];
        }
        
        if($.isNumeric( var_senha )){
            res_senha = $("#senha_atendimento").css('font-size', '350px');
        }else{
            $("#senha_atendimento").css('font-size', '175px');
        }
        
        if(jQuery.type( var_senha ) === "string"){
            res_senha = var_senha.split(' ').join('<br>');
        }
        
		$("#senhaExibida").val(res['senha']);
		$("#senha_atendimento").html('<strong>'+ res_senha +'</strong>');
        $("#numero_guiche").html('<strong>'+ var_guiche +'</strong>');
        
      }
    });
    
};

self.setInterval(function(){verifica()},500);

</script>    
</body>
</html>