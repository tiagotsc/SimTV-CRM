<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="Tiago" />
    <meta charset="utf8">
	<title></title>
    <style type="text/css">
        /* Deixa div cupom oculta para visualização*/
        p#cupomSenha{
            font-weight: bold;
                font-size: 40px;
        } 
        
        /* CSS para impressão do cupom de senha */
        @media print {
            
            body#cupomSenha{
                display: block;
                padding: 0px;
                margin: 0px;
                font-weight: bold;
                font-size: 40px;
            }
            
            body {
                margin:0px;
                padding:0px;
                line-height: 1.4em;
            }  
            
            @page {
                margin: 0.5cm;
            }
            
        }
    </style>
</head>

<body>


<?php
//if($_POST['senhaResposta']){
?>
    <p style="font-size: 15px; font-weight: bold;" id="cupomSenha">
        ###### SIM TV ######<br/><br/>SENHA N. 12<br/><br/>AGUARDE O ATENDIMENTO
    </p>
    
    <script type="text/javascript">
        window.print();
    </script>
<?php
//}
?>


</body>
</html>