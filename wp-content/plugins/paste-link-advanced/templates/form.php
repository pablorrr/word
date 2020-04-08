<?php //UWAGA!!!! NIE ZMIERNIAC NP.lte-hs-slide-form POWODUJE BLAD W ZAPISIE 
$action_params = array('view' => 'form', 'action' => 'save');//wykoorzytsnaie do atrybutu action formularza, dziekie temeu ///aktualna strona bedzie formularzem a akcja bedzie zchowywwyala dane zapisane w formularzu i przesylac je do bazy danych
if($Link->hasId()){//jesli wpis ma swoje id , slide obiektem klasy slidelinkentry
    $action_params['linkid'] = $Link->getField('id');//nadanie w tablicy asocjacyjnej nowej pary linkid=>id, dzieki temu //bedzie wyswietlany aktualny wpis z bazy danych w formularzu
}

?>
<form action="<?php echo $this->getAdminPageUrl($action_params); ?>" method="post" id="link-hs-slide-form">
    
    <?php wp_nonce_field($this->action_token);//generowanie tokenow w polach typu hidden input ?>
   
    <table class="form-table">
        
        <tbody>
            
            <tr class="form-field">
                <th>
                    <label for="link-hs-title">Tytuł linka:</label>
                </th>
                <td>
                    <input type="text" name="link[title]" id="link-hs-title" value="<?php echo $Link->getField('title'); ?>" />
                    
                    <?php if($Link->hasError('title'))://jesli w slide jest error ?>
                    <p class="description error"><?php echo $Link->getError('title'); ?></p>
                    <?php else: ?>
                    <p class="description">To pole jest wymagane</p>
                    <?php endif; ?>
                </td>
            </tr>
            
            
            
            <tr class="form-field">
                <th>
                    <label for="link-hs-caption">Podpis:</label>
                </th>
                <td>
                    <input type="text" name="link[caption]" id="link-hs-caption" value="<?php echo $Link->getField('caption'); ?>" />
                    
                    <?php if($Link->hasError('caption')): ?>
                    <p class="description error"><?php echo $Link->getError('caption'); ?></p>
                    <?php else: ?>
                    <p class="description">To pole jest opcjonalne</p>
                    <?php endif; ?>
                    
                </td>
            </tr>
            
            <tr class="form-field">
                <th>
                    <label for="link-hs-url">Link (url)</label>
                </th>
                <td>
                    <input type="text" name="link[link]" id="link-hs-url" value="<?php echo $Link->getField('link'); ?>" />
                    
                    <?php if($Link->hasError('link')): ?>
                    <p class="description error"><?php echo $Link->getError('link'); ?></p>
                    <?php else: ?>
                    <p class="description">To pole jest wymagana</p>
                    <?php endif; ?>
                </td>
            </tr>
			
			
			
			
			
			
      
            <tr>
                <th>
                    <label for="link-hs-position">Pozycja:</label>
                </th>
                <td>
				<!-- funkcja get-field zdefinkowana w ltehome slider slide linkentry linia 41-->
                    <input type="text" name="link[position]" id="link-hs-position" value="<?php echo $Link->getField('position'); ?>" />
                    <a class="button-secondary" id="get-last-pos">Pobierz ostatnią wolną pozycję</a>
                    
                    <?php if($Link->hasError('position')): ?>
                    <p id="pos-info" class="description error"><?php echo $Link->getError('position'); ?></p>
                    <?php else: ?>
                    <p id="pos-info" class="description">To pole jest wymagane</p>
                    <?php endif; ?>
                    
                </td>
            </tr>
            
            <tr>
                <th>
                    <label for="link-hs-published">Opublikowany:</label>
                </th>
                <td>
                    <input type="checkbox" name="link[published]" id="link-hs-published" value="yes" <?php echo ($Link->isPublished()) ? 'checked="checked"' : ''; //zaaznaczenie chceckboxa jesli ispublished wroci true ?> />
                </td>
            </tr>
			<!-- DLA KOLOR-->
			
			
            
        </tbody>
        
    </table>
    
    <p class="submit">
        <a href="#" class="button-secondary">Wstecz</a>
        &nbsp;
        <input type="submit" class="button-primary" value="Zapisz zmiany" />
    </p>
    
</form>