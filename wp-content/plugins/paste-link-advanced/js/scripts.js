(function($){//taki sposb kodwania zapobiega konfliktom js- jquery
    
    $(document).ready(function(){//w ten sposb kod bedzie zaldowany gdy drzewo DOM bedzie zaladowane do dokumnetu
        
        
        //obsluga pola pozycja wstaw nowa pozycje
        $('#link-hs-position').keyup(function(){//zdarzenie upuszczeni klawisza
            var $this = $(this);//tworzenie referencji do obiektu
            
            $('#pos-info').text('Trwa sprawdzanie pozycji...');//wstawianie textu do el ., posinfo
            
            var post_data = {//tworzenie obiektu literalnego
                position: $this.val(),//odzcytanie wartoaci pozycji obiektu lte hs position wartosc wstawiana //przez usera wyuczki
                action: 'checkValidPosition'//przypisanie akcji odpowidniej funkcji
            };
            /* $.post - WP f., wysyla zadania do ajax
			param:
			ajaxurl- zmienna WP obsluguje wysylanie zaddan do ajax adres url żądania 
			post_data- zmienna -obiekt zawierajace dane do przeslania
			function(result)- funkcja callback reagujaca na zwrocona info
			*/
            $.post(ajaxurl, post_data, function(result){//uzycie ajax
                $('#pos-info').text(result);
            });
            
        });
        
        //wykorzytsanie ajax
        $('#get-last-pos').click(function(){
            
            $('#pos-info').text('Trwa pobieranie pozycji...');
            
            var get_data = {
                action: 'getLastFreePosition'//nazwa akcji ajax f., callback
            };
            
            $.get(ajaxurl, get_data, function(result){//wysylanie zadania do ajax metoda get
                $('#link-hs-position').val(result);
                $('#pos-info').text('Pozycja została pobrana');
            });
            
        });
        
       
        
    });
	
    
})(jQuery);//taki sposb kodwania zapobiega konfliktom js- jquery

$(document).ready(function(){

});

