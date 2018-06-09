<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.validate.min.js") ?>"></script>

<div class="modal fade" id="configRegistroSenha" tabindex="-1" role="dialog" aria-labelledby="configRegistroSenha" aria-hidden="true">
    <div class="modal-dialog" style="width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">Configura&ccedil;&atilde;o do painel de registro de senha</h4>
            </div>
            <div class="modal-body">
                <?php              
                    $data = array('class'=>'pure-form','id'=>'form_registro');
                    echo form_open('painel/painelRegistro',$data);
                    
                    echo '<div class="row">';
                    
                        echo '<div class="col-md-12">';
                        $options = array('' => '');		
                		foreach($locaisPainel as $lP){
                			$options[$lP->cd_local] = html_entity_decode($lP->nome_local);
                		}	
                		echo form_label('Local<span class="obrigatorio">*</span>', 'cd_local');
                		echo form_dropdown('cd_local', $options, '', 'id="cd_local" class="form-control"');
                        echo '</div>';
                        
                        echo '<div class="col-md-12">';
                        echo form_label('Login<span class="obrigatorio">*</span>', 'login');
            			$data = array('name'=>'login', 'value'=>'','id'=>'login', 'placeholder'=>'Login da rede', 'class'=>'form-control', 'style' => 'width:100%');
            			echo form_input($data);
                        echo '</div>';
                        
                        echo '<div class="col-md-12">';
                        echo form_label('Senha<span class="obrigatorio">*</span>', 'senha');
            			$data = array('name'=>'senha', 'value'=>'','id'=>'senha', 'placeholder'=>'Senha da rede', 'class'=>'form-control', 'style' => 'width:100%');
            			echo form_password($data);
                        echo '</div>';
                    
                    echo '</div>';
                ?>
                <div id="conteudoModal"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Abrir painel</button>
        </div>
                <?php
                echo form_close();
                ?>
    </div>
  </div>
</div>

<div class="modal fade" id="configExibicaoSenha" tabindex="-1" role="dialog" aria-labelledby="configExibicaoSenha" aria-hidden="true">
    <div class="modal-dialog" style="width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">Configura&ccedil;&atilde;o do painel de exibi&ccedil;&atilde;o de senha</h4>
            </div>
            <div class="modal-body">
                <?php              
                    $data = array('class'=>'pure-form','id'=>'form_exibicao');
                    echo form_open('painel/painelExibicao',$data);
                    
                    echo '<div class="row">';
                    
                        echo '<div class="col-md-12">';
                        $options = array('' => '');		
                		foreach($locaisPainel as $lP){
                			$options[$lP->cd_local] = html_entity_decode($lP->nome_local);
                		}	
                		echo form_label('Local<span class="obrigatorio">*</span>', 'cd_local');
                		echo form_dropdown('cd_local', $options, '', 'id="cd_local" class="form-control"');
                        echo '</div>';
                        
                        echo '<div class="col-md-12">';
                        echo form_label('Login<span class="obrigatorio">*</span>', 'login');
            			$data = array('name'=>'login', 'value'=>'','id'=>'login', 'placeholder'=>'Login da rede', 'class'=>'form-control', 'style' => 'width:100%');
            			echo form_input($data);
                        echo '</div>';
                        
                        echo '<div class="col-md-12">';
                        echo form_label('Senha<span class="obrigatorio">*</span>', 'senha');
            			$data = array('name'=>'senha', 'value'=>'','id'=>'senha', 'placeholder'=>'Senha da rede', 'class'=>'form-control', 'style' => 'width:100%');
            			echo form_password($data);
                        echo '</div>';
                    
                    echo '</div>';
                ?>
                <div id="conteudoModal"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Abrir painel</button>
        </div>
                <?php
                echo form_close();
                ?>
    </div>
  </div>
</div>

  <div id="career" class="container">
        <div class="row">&nbsp</div>
        <?php echo $this->session->flashdata('statusOperacao'); ?>
        <div class="row">&nbsp</div>
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="row">
                    
                    <?php              
                    $data = array('class'=>'form-horizontal','id'=>'form_login');
                    echo form_open('home/autentica',$data);
                    ?>
                    <!--
                    <form id="form_login" class="form-horizontal" method="post" action="home/autentica" role="form">
                        
                               
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="login" type="text" class="form-control" name="login" value="" placeholder="Login da rede">                                        
                        </div>
                            
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="senha" type="password" class="form-control" name="senha" placeholder="Senha da rede">
                        </div>
                        -->        
                        
                          <div class="form-group">
                            <label for="login">login:</label>
                            <input type="text" class="form-control" id="login" name="login" placeholder="Login da rede">
                          </div>
                          
                          <div class="form-group">
                            <label for="senha">Senha:</label>
                            <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha da rede">
                          </div>
                                                
                         <!--   
                        <div class="input-group">
                          <div class="checkbox">
                            <label>
                              <input id="login-remember" type="checkbox" name="remember" value="1"> Remember me
                            </label>
                          </div>
                        </div>
                        -->
                
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
            
                            <div class="col-sm-12 controls">
                              <!--<a id="btn-login" href="#" class="btn btn-success">Login  </a>-->
                                <input id="btn_logar" class="btn btn-primary pull-right" type="submit" value="Logar" />
            
                            </div>
                        </div>
                        
                    <?php
                    echo form_close();
                    ?> 
                                    
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <a href="#" data-toggle="modal" data-target="#configRegistroSenha">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> 
                            Painel de entrada
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="#" data-toggle="modal" data-target="#configExibicaoSenha">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> 
                            Painel de exibi&ccedil;&atilde;o
                        </a>
                    </div>
                </div>
                
            </div>
        </div>    
  </div>
  <!-- /Career -->
  <script type="text/javascript">
$(document).ready(function(){
    
    // Valida o formulário
	$("#form_login").validate({
		debug: false,
		rules: {
			login: {
                required: true
            },
            senha: {
                required: true
            }
		},
		messages: {
			login: {
                required: "Digite o login."
            },
            senha: {
                required: "Digite a senha."
            }
	   }
   }); 
   
    // Valida o formulário do painel de entrada de senha
	$("#form_registro").validate({
		debug: false,
		rules: {
            cd_local: {
                required: true
            },
			login: {
                required: true
            },
            senha: {
                required: true
            }
		},
		messages: {
            cd_local: {
                required: "Selecione o local."
            },
			login: {
                required: "Digite o login."
            },
            senha: {
                required: "Digite a senha."
            }
	   }
   }); 
   
   // Valida o formulário do painel de exibição de senha
	$("#form_exibicao").validate({
		debug: false,
		rules: {
            cd_local: {
                required: true
            },
			login: {
                required: true
            },
            senha: {
                required: true
            }
		},
		messages: {
            cd_local: {
                required: "Selecione o local."
            },
			login: {
                required: "Digite o login."
            },
            senha: {
                required: "Digite a senha."
            }
	   }
   });    
   
});
</script>