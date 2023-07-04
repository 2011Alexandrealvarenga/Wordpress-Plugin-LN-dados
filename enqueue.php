<?php 
function ln_dados_admin_enqueue_css(){
    
    wp_register_style(
        'ln_dados_br_style',
        plugins_url('/assets/css/style.css', LN_DADOS_PLUGIN_URL)
    );

    wp_enqueue_style('ln_dados_br_style');
}

