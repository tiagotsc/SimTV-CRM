 <?php
echo link_tag(array('href' => 'assets/js/c3js/c3.css','rel' => 'stylesheet','type' => 'text/css'));
#echo link_tag(array('href' => 'assets/css/css_dashboard.css','rel' => 'stylesheet','type' => 'text/css'));
echo "<script type='text/javascript' src='".base_url('assets/js/c3js/d3.v3.min.js')."'></script>";
echo "<script type='text/javascript' src='".base_url('assets/js/c3js/c3.js')."'></script>";
?>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.validate.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>
  
  <section id="title" class="asbestos">
    <div class="container">
      <div class="row">
        <div class="col-sm-6">
          <h1><strong>Bem Vindo!</strong></h1>
          <p><strong>Sim TV - Sistema de atendimento</strong></p>
        </div>
      </div>
    </div>
  </section><!--/#title-->     

    <div class="container">
        <div class="row">
        <?php if(in_array(39, $this->session->userdata('permissoes'))){?>
            <div class="col-sm-12">
                <h3>Informa&ccedil;&otilde;es do m&ecirc;s corrente (<?php echo date('m/Y'); ?>)</h3><br />
                <h4>Quantidade de atendimentos no prazo e fora do prazo</h4>
                <div id="chart1"></div>
                <h4>Tempo m&eacute;dio da espera e dos atendimentos (HH.MM)</h4>
                <div id="chart2"></div>
                <h4>Atendentes que est&atilde;o online ou n&atilde;o</h4>
                <div id="chart3"></div>
            </div>
        <?php } ?>
        </div>
    </div>
  <!-- /Career -->
  
<script type="text/javascript">
<?php if(in_array(39, $this->session->userdata('permissoes'))){?>
$(document).ready(function(){
    
$(this).graficoBarraQtd(); 
$(this).graficoBarraTempoMedio(); 
$(this).graficoPizzaAtendenteOnline();
});

$.fn.graficoBarraQtd = function() {

    // GRÁFICO DE BARRA                        
    c3.generate({
        bindto: '#chart1',
        data: {
            //url: '<?php echo base_url('assets/c3_test.json')?>',
            url: '<?php echo base_url(); ?>ajax/qtdAtendPorPrazo',
            mimeType: 'json',
            type: 'bar',
            //onclick: function (d, element) { alert(d.value); },
            keys: {
                x: 'local', // it's possible to specify 'x' when category axis
                value: ['Qtd. no prazo', 'Qtd. fora prazo']
            }
        },
    	axis: {
    		x: {
    			type: 'category'
    		},
             y : {
            tick: {
                    format: d3.format(',')
                }
            }            
    	}/*,
        tooltip: {
            format: {
                value: function (value, ratio, id) {
                    var format = id === 'Quantidade baixado' ? d3.format(',') : d3.format(',');
                    return format(value).replace(',', '.').replace(',', '.');
                }
    //            value: d3.format(',') // apply this format to both y and y2
            }
        }*/
    });
    
};

$.fn.graficoBarraTempoMedio = function() {

    // GRÁFICO DE BARRA                        
    c3.generate({
        bindto: '#chart2',
        data: {
            //url: '<?php echo base_url('assets/c3_test.json')?>',
            url: '<?php echo base_url(); ?>ajax/tempoMedio',
            mimeType: 'json',
            type: 'bar',
            //onclick: function (d, element) { alert(d.value); },
            keys: {
                x: 'local', // it's possible to specify 'x' when category axis
                value: ['<?php echo utf8_encode('Média espera'); ?>', '<?php echo utf8_encode('Média atendimento'); ?>']
            }
        },
    	axis: {
    		x: {
    			type: 'category'
    		},
             y : {
            tick: {
                    //format: d3.format(',')
                    format: function (d) { return d.toFixed(2).replace('.', ':'); }
                }
            }            
    	}/*,
        tooltip: {
            format: {
                value: function (value, ratio, id) {
                    var format = id === 'Quantidade baixado' ? d3.format(',') : d3.format(',');
                    return format(value).replace(',', '.').replace(',', '.');
                }
    //            value: d3.format(',') // apply this format to both y and y2
            }
        }*/
    });
    
};

$.fn.graficoPizzaAtendenteOnline = function() {
    
    c3.generate({
        bindto: '#chart3',
        data: {
            url: '<?php echo base_url(); ?>ajax/qtdAtendenteOnline',
            mimeType: 'json',
            type: 'pie',
            keys: {
                //x: 'local', // it's possible to specify 'x' when category axis
                value: ['Qtd. atendentes Online', 'Qtd. atendentes Offline']
            }
        }/*,
        pie: {
            label: {
                format: function (value, ratio, id) {
                    return d3.format('$')(value);
                }
            }
        }*/
    }); 

};
<?php } ?>
</script>