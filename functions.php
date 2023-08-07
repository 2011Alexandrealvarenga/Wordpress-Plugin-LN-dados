<?php 
function ln_dados_table_creator()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'ln_dados';
    $sql = "DROP TABLE IF EXISTS $table_name;
            CREATE TABLE $table_name(
            id mediumint(11) NOT NULL AUTO_INCREMENT,

            nome varchar(50) NOT NULL,
            email varchar (50) NOT NULL,
            data varchar (50) NOT NULL,

            PRIMARY KEY id(id)
            )$charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function ln_dados_da_display_esm_menu()
{
    add_menu_page('Litoral Norte', 'Litoral Norte', 'manage_options', 'ln-dados-emp-list', 'da_ln_dados_list_callback','', 8);
    add_submenu_page('ln-dados-emp-list', 'LN - DADOS - Lista', 'LN - DADOS - Lista', 'manage_options', 'ln-dados-emp-list', 'da_ln_dados_list_callback');
    add_submenu_page(null, 'LN DADOS Atualiza', 'LN DADOS Atualiza', 'manage_options', 'update-ln-dados', 'ln_dados_da_emp_update_call');
    add_submenu_page(null, 'Delete Employee', 'Delete Employee', 'manage_options', 'delete-ln-dados', 'ln_dados_da_emp_delete_call');
}

//[employee_list]
// add_shortcode('employee_list', 'da_ln_dados_list_callback');

function da_ln_dados_list_callback()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ln_dados';
    $msg = '';
    if (isset($_REQUEST['submit'])) {
        $wpdb->insert("$table_name", [
            "nome" => $_REQUEST['nome'],
            'email' => $_REQUEST['email']
        ]);

        if ($wpdb->insert_id > 0) {
            $msg = "Gravado com sucesso!";
        } else {
            $msg = "Falha ao gravar!";
        }
    }

    ?>
    <div class="content-pat">
        <h1 class="title">Litoral Norte - Relatório de Prestação de contas</h1>
        <h2 class="subtitle">Preencha o formulário para ser redirecionado ao relatório</h2>
        <!-- <form method="post">
            <div class="cont">
                <div class="esq">
                    <span>nome</span>
                </div>
                <input type="text" name="nome" required><br>
            </div>
            
            <div class="cont">
                <div class="esq">
                    <span>Email</span>
                </div>
                <input type="text" name="email" ><br>
            </div>
            <div class="cont">
                <div class="esq">
                    <h4 id="msg" class="alert"><?php echo $msg; ?></h4>
                    <button class="btn-pat" type="submit" name="submit">CADASTRAR</button>

                </div>
            </div>           
        </form> -->
    </div>
    <?php 

    $table_name = $wpdb->prefix . 'ln_dados';
    $employee_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name ORDER BY id desc "), ARRAY_A);
    if (count($employee_list) > 0): ?>  

        <div class="busca">
            <!-- <h3 class="subtitle">Realize a busca da unidade</h3>
            <input type="text" class="form-control" id="live_search" autocomplete="off" placeholder="Ex.: Município, CEP, Endereço ..."> -->
        </div>   
        <div id="searchresult" style="margin: 24px 10px 0 0; display: block;"></div>
        <!-- <script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

        <script type="text/javascript">
            $(document).ready(function(){
                $("#live_search").keyup(function(){
                    var input = $(this).val();
                    // alert(input);
                    var url_search =  "<?php echo site_url(); ?>/wp-content/plugins/Wordpress-Plugin-LN-dados/busca-resultado.php";
                    
                    if(input != ""){
                        $.ajax({                      
                            url:url_search,
                            method: "POST",
                            data:{input:input},

                            success:function(data){
                                $("#searchresult").html(data);
                                $("#searchresult").css('display','block');
                                $("#registros-todos-dados-tabela").css('display','none');
                            }
                        });
                    }else{
                        $("#searchresult").css("display","none");
                        $("#registros-todos-dados-tabela").css('display','block');
                    }
                });
            });
        </script>   
        <div id="registros-todos-dados-tabela" style="margin: 24px 10px 0 0;">
            <?php da_ln_dados_resultado_busca($employee_list);?>
        </div>
    <?php else:echo "<h2>Não há Informação</h2>";endif;
}


function da_ln_dados_resultado_busca($employee_list){?>
    <table border="1" cellpadding="5" width="100%">
        <tr>
            <th>ID</th>
            <th>nome</th>
            <th>Email</th>

        </tr>
        <?php $i = 1;
        foreach ($employee_list as $index => $employee): ?>
            <tr>
                <td><?php echo $employee['id']; ?></td>
                <td><?php echo $employee['nome']; ?></td>
                <td><?php echo $employee['email']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php }

function ln_dados_da_emp_update_call()
{
    global $wpdb;
    
    $url = site_url();
    $url2 = '/wp-admin/admin.php?page=ln-dados-emp-list';
    $urlvoltar = $url.$url2;

    $table_name = $wpdb->prefix . 'ln_dados';
    $msg = '';
    $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : "";
    
    $employee_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name where id = %d", $id), ARRAY_A); ?>
   <div class="content-pat">
        <h1 class="title">Litoral norte</h1>
        <!-- <h2 class="subtitle">Atualização de Cadastro de Unidade</h2> -->
        <form method="post">     
            <div class="cont">
                <div class="esq">
                    <span>nome</span>
                </div>
                <input type="text" name="nome" value="<?php echo $employee_details['nome']; ?>" required><br>
            </div>  
            
            <div class="cont">
                <div class="esq">
                    <span>E-mail</span>
                </div>
                <input type="text" name="email" value="<?php echo $employee_details['email']; ?>" ><br>
            </div>
            <div class="cont">
                <div class="esq">
                    <button class="btn-pat" type="submit" name="update">ATUALIZAR</button>
                </div>
            </div>
            <div class="cont">
                <div class="esq">
                    <?php                     
                        if (isset($_REQUEST['update'])) {
                            if (!empty($id)) {
                                $wpdb->update("$table_name", [
                                    "nome" => $_REQUEST['nome'], 
                                    'email' => $_REQUEST['email']            
                            ], ["id" => $id]);
                                $msg = 'Atualização realizada!';
                                echo '<h4 class="alert">    '. $msg .'</h4>';
                                echo '<a href="'. $urlvoltar.'" class="link-back">Voltar para a lista</a>';
                            }
                        }
                    ?>
                    
                </div>
            </div> 
            
            


        </form>
<?php }

function ln_dados_da_emp_delete_call()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ln_dados';
    $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : "";
    if (isset($_REQUEST['delete'])) {
        if ($_REQUEST['conf'] == 'yes') {
            $row_exits = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
            if (count($row_exits) > 0) {
                $wpdb->delete("$table_name", array('id' => $id,));
            }
        } ?>
        <script>location.href = "<?php echo site_url(); ?>/wp-admin/admin.php?page=ln-dados-emp-list";</script>
    <?php } ?>
    <form method="post">
        <div class="content-pat">
            <h1 class="title">Litoral Norte</h1>
            <h2 class="subtitle">Exclusão de cadastro de Unidade</h2>

            <h3 class="description">Deseja realmente apagar?</h3 >
            <input type="radio" name="conf" value="yes" checked>Sim
            <input type="radio" name="conf" value="no" >Não  <br><br>      
        
            <button class="btn-pat" type="submit" name="delete">OK</button>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
        </div>        
    </form>
<?php }