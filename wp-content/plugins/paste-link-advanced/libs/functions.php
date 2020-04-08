<?php 

function paste_link_template(){
	

    $model = new Paste_Link_Model();//stworzenie nowego obiektu  instancja 
    
    $Links_list = $model->getPublishedSlides();
	
	  
    if(!empty($Links_list)){
        foreach($Links_list as $linkentry){ ?>
							<p class="link" >
							<?php if(!empty($linkentry->link))://link do reszty tresci artukulu ?>
							<a target= "_blank" href="<?php echo $linkentry->link; ?>"><?php echo $linkentry->link; ?></a>
							<?php endif; ?>
						   </p>	
<?php
											}
							}
	
}
function _paste_link_advanced_shortcode($link_shortcode){
	
	$link_shortcode ='<div>'.paste_link_template().'</div>';
	return $link_shortcode;
}
add_shortcode('paste-link-advanced','_paste_link_advanced_shortcode');
?>


	
   