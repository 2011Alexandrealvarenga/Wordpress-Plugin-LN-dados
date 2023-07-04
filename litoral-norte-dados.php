<?php
/*
 * Plugin Name: Litoral Norte - Dados
 * Description: Plugin para Inserir, atualizar, ler e deletar Unidade PAT
 * Version: 2.0
 * Author: Alexandre Alvarenga
 * Plugin URI: 
 * Author URI: 
 */

if(!function_exists('add_action')){
    echo 'Opa! Eu sou só um plugin, não posso ser chamado diretamente!';
    exit;
}

// setup
define('LN_DADOS_PLUGIN_URL', __FILE__);

register_activation_hook(LN_DADOS_PLUGIN_URL, 'ln_dados_table_creator');
register_uninstall_hook(LN_DADOS_PLUGIN_URL, 'ln_dados_plugin');

// includes
include('functions.php');
include('enqueue.php');


add_action('admin_menu', 'ln_dados_da_display_esm_menu');
add_action('admin_enqueue_scripts', 'ln_dados_admin_enqueue_css');

