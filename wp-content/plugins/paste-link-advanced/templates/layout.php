<div class="wrap">
    
    <h2>
        <a href="<?php echo $this->getAdminPageUrl();//przekierowanie na strone glowna ?>">Link</a>
        <a class="add-new-h2" href="<?php echo $this->getAdminPageUrl(array('view' => 'form'));//przeniesienie do strony //formularza po kliknieciu dodaj nowy slide ?>">Dodaj nowy Link</a>
    </h2>
    
    
    <?php if($this->hasFlashMsg()): ?>

    <div id="message" class="<?php echo $this->getFlashMsgStatus();//okreslanaie klasy css wiadomosci wtyczki ?>">
        <p><?php echo $this->getFlashMsg(); //pobranietrescu=i te wiadomosci?></p>
    </div>
    <?php endif; ?>
    
    
    <?php require_once $view; //pobranie akrtualnej tresci widoku strony plugina?>
    
    
    
    <br style="clear: both;">
    
</div>