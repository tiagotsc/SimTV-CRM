<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.validate.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>
<div class="container">
            <!--<div class="col-md-9 col-sm-8"> USADO PARA SIDEBAR -->
            <div class="col-lg-12">
                
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('home/inicio'); ?>">Principal</a>
                    </li>
                    <li class="active">Atendentes online</li>
                </ol>
                
                <div id="divMain">
                
                <?php 
                echo $this->session->flashdata('statusOperacao');
                $this->table->set_heading('Nome', 'Local', 'Status');
                #echo '<pre>'; print_r($atendentesOnline);            
                foreach($atendentesOnline as $ate){
                    
                    $cell0 = array('data' => $ate->nome_usuario, 'class' => '');
                    $cell1 = array('data' => $ate->nome_local, 'class' => '');
                    
                    if($ate->online_usuario == 'S'){
                        $img = base_url('assets/images/user_online.png');
                        $title = 'Online';
                    }else{
                        $img = base_url('assets/images/user_offline.png');
                        $title = 'Offline';
                    }
                    
                    $cell2 = array('data' => '<img '.$title.' style="width:25px" src="'.$img.'">', 'class' => '');
                        
                    $this->table->add_row($cell0, $cell1, $cell2);
                    
                }
                
            	$template = array('table_open' => '<table class="table table-bordered">');
            	$this->table->set_template($template);
            	echo $this->table->generate();
                echo "<ul class='pagination pagination-lg'>" . utf8_encode($paginacao) . "</ul>"; 
                ?>
                </div>
                
            </div>
</div>
        <!-- container -->

    
<script type="text/javascript">

self.setInterval(function(){$(this).puxaAtendenteOnline()}, 1000); // Chama a função a cada 1 segundo

/*
Essa função verifica quais os paciente foram chamados pelo médico e os alertam na agenda para a secretária
*/
$.fn.puxaAtendenteOnline = function() {
    
    $.ajax({
      type: "POST",
      url: '<?php echo base_url(); ?>ajax/pegaAtendentesOnline',
      dataType: "json",
      error: function(res) {
        alert('Erro');
      },
      success: function(res) {
        //alert(res.length);
        var conteudoHtml = '';
        var online = '<img title="Online" style="width:25px" src="<?php echo base_url('assets/images/user_online.png'); ?>">';
        var offline = '<img title="Offline" style="width:25px" src="<?php echo base_url('assets/images/user_offline.png'); ?>">';
        var status = '';
        
        if(res.length > 0){
            
            conteudoHtml += '<table class="table table-bordered">';
            conteudoHtml += '<tr>';
            conteudoHtml += '<th><strong>Nome</strong></th>';
            conteudoHtml += '<th><strong>Local</strong></th>';
            conteudoHtml += '<th><strong>Status</strong></th>';
            conteudoHtml += '</tr>';
            
            $.each(res, function() {
                
                if(this.online_usuario == 'S'){
                    status = online;
                }else{
                    status = offline;
                }
            
            conteudoHtml += '<tr>';
            conteudoHtml += '<td>'+this.nome_usuario+'</td>';
            conteudoHtml += '<td>'+this.nome_local+'</td>';
            conteudoHtml += '<td>'+status+'</td>';
            conteudoHtml += '</tr>';
            
            });
            
            conteudoHtml += '</table>';
            
        }else{
            conteudoHtml += '';
        }
        
        $("#divMain").html(conteudoHtml);
        
      }
    });
    
};
</script>