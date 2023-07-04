<?php
 
global $wpdb;
require_once('../../../wp-config.php');

if(isset($_POST['input'])){
    $input = $_POST['input'];
    $query = $wpdb->get_results("SELECT * FROM ln_dados WHERE 
        nome LIKE '%{$input}%' OR 
        email LIKE '%{$input}%'
        LIMIT 5
    ");

    if($query > 0){
        
        ?>
        <div class="content-pat">

            <table class="table table-bordered table-striped mt-4" border="1" cellpadding="10" width="90%">
                <thead>
                    <tr>
                        <th>ID</th>  
                        <th>Nome</th>  
                        <th>E-mail</th>

                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach($query as $row){?>
                    <tr>
                        <td><?php echo $row->id;?></td>
                        <td><?php echo $row->nome;?></td>
                        <td><?php echo $row->email;?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    <?php
    }else{
        echo "<h6 class='text-danger text-center mt-3'>Não foi encontrado informações</h6>";
    }
}
