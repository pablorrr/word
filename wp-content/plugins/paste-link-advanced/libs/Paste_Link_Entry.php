<?php

class Paste_Link_Entry {//klasa ta bedzie sluzyc do tworzenia obiektow ktre beda nosnikiem do zapisywania danych w bazie //danych, (pendrive)
    //skecja info panelu dotyczcego poj obrazka i jednoczesnie deklaracja wlasciwosci klasy
	//nadanie null wyzeruje dla pewnosci wartosci poczatkowe
	
	/* wlasciwociami tej klasy beda pola w bazie danych    save */
	
    private $id = NULL;//id obrazka
 
    private $title = NULL;//tytul
    private $caption = NULL;//opis
    private $link = NULL;//url linka
    private $position = NULL;//nr pozycji
    private $published = 'yes';//ustawienie domyslnej wartosci opcni publikacji w chceckboxie na "tak"
								/* kazdy wpis bedzie domyslnie upubliczniony */
	private $color = 'black';							
    private $errors = array();//przechowywanie bledow w tablicy
    
    private $exists = FALSE;//ustawienie oznacza ze domyslnie jeest zalozenie ze dana (wpis -nowy wiersz)instancja nie //istnieje w bazie danych
    
    //konstruktor klasy Paste_Link_Entry
	/* w momencie instnacji obiektu klasy lte home slider entry obiekt bedzie mial juz nadane wartosci    wlasciwosci z konstruktora */
    function __construct($id = NULL) {//ustawienei domyslengo id na null id bedzie wykorzystywany jako parametr w url, id //jesy rowniez kolumna w bazie danych
        $this->id = $id;
        $this->load();
    }
    //ladowanie danych z bazy anych z uzyciem ferch row zdef w klasie modelowej( obsluga bazy danych)
    private function load(){
        if(isset($this->id)){
            $Model = new Paste_Link_Model();//stworzenie obiektu lte home slider model
            $row = $Model->fetchRow($this->id);//zwrocenie zawartosci wiersza
            
            if(isset($row)){//jesli istnieje wiersz w bazie danych
                $this->setFields($row);//ustaw pole na dany wiersz
                $this->exists = TRUE;//ustaw instancje  ( wpis -nowy wiersz) w bazzie danych na true
            }
        }
    }
    //zwraca istniejaca instancje wpis nowy wiersz( rekord)
    public function exists(){
        return $this->exists;
    }
                
    //uzyskajj pole z bazy danych
    function getField($field){
        if(isset($this->{$field})){//zapis zmiennej zmiennej dynamicznej
            return $this->{$field};
        }
        
        return NULL;
    }
    
    function hasId(){
        return isset($this->id);//zwroc wartosc id
    }
    
    //funkcja oblsuguje checkbox
    function isPublished(){
        return ($this->published == 'yes');//zwroc opublikowana zawartosc na yes
    }
	//checkbox dla kolor
	 function isColorBlack(){
        return ( $this->color == 'black');//zwroc opublikowana zawartosc na black
    }
    
    //ustawia wartosc  wszytskicj pol ( polami sa wlasciwosci klasy ltehome slider, wykoryzstywanie do pobierania danych z //formularza i bazy danych
    function setFields($fields){
        foreach($fields as $key => $val){//przypisanie klucza wartosci
            $this->{$key} = $val;
        }
    }
    
    //ustawianie bledow, komunikatow bledow
    function setError($field, $error){//$field-nazwa pola,$error- komunikat tresc bledu
        $this->errors[$field] = $error;//ustawienie pola tablicy jako error
    }
    //pobieranie bloedow
    function getError($field){
        if(isset($this->errors[$field])){//sprawdzenie czy pole ma blad
            return $this->errors[$field];
        }
        
        return NULL;//jesli nie ma
    }
    //sprawdzanie czy pole ma bledy
    function hasError($field){
        return isset($this->errors[$field]);
    }
    //sprawdza czy obiekt ma bledy
    function hasErrors(){
        return (count($this->errors) > 0);//zwraca liczbe bledow
    }
    
    ////////////////////WALIDACJA/////////////////////////////////////////////////
    function validate(){
        
        
        
        /*
         * POLE TITLE:
         * - nie może być puste
         * - maksymalna długość 255 znaków
         */
        if(empty($this->title)){//jesli pole title w pluginie jest puste
            $this->setError('title', 'To pole nie może być puste');
        }else
        if(strlen($this->title) > 255){//strlen zwraca dlugosc lnacucha( php)
            $this->setError('title', 'To pole nie może być dłuższe niż 255 znaków.');
        }
        
        ///////////////////////////////////
        
        /*
         * POLE CAPTION:
         * - może być puste
         * - jeżeli nie puste:
         *      - usuń niebezpieczny html (zostaw tylko strong i b)
         *      - maksymalna długość to 255 znaków
         * 
         */
        if(!empty($this->caption)){//jesli pole opis nie jest puste
            $allowed_tags = array(//dopusczone tagi
                'strong' => array(),//tag strong bez atrybutow, tablica pusta bo strong nie przyjmuje zadnych atrybuow
                'b' => array()//tag b bez atrybutow
            );
			
			
            $this->caption = wp_kses($this->caption, $allowed_tags);
            
            if(strlen($this->caption) > 255){
                $this->setError('caption', 'To pole nie może być dłuższe niż 255 znaków.');
            }
        }
        
        /////////////////////////////////////////////////////
        
        /*
         * POLE LINK:
         * -  NIE może być puste
         * - jeżeli nie puste:
         *  - po wyczyszczeniu ( i formatowaniu)  url nie może być dłuższy niż 255 znaków
         */
		 
		 if(empty($this->link)){//jesli pole link w pluginie jest puste
            $this->setError('link', 'To pole nie może być puste');
		 }else    $this->link = esc_url($this->link);//wp esc url oczywszcza url oraz formatuje go na http//
            
         if(strlen($this->link) > 255){
                $this->setError('link', 'To pole nie może być dłuższe niż 255 znaków.');
            }
		
		 
	  /*
         * POLE POSITION:
         * - pole wymagane, nie moze być puste
         * - rzutowanie wartości do integera
         * - musi być to liczba większa od 0
         */
        if(empty($this->position)){//jezeli pole jest puste
            $this->setError('position', 'To pole nie może być puste.');
        }else{
            $this->position = (int)$this->position;//rzutowanie( konwersja) poz obrazka na int(position jest //wlasciwoscia //klasy)
            if($this->position < 1){//jezeli pozycja kolejnosc obrazka jezt zero lub liczba ujemna
                $this->setError('position', 'To pole musi być liczbą większą od 0.');
            }
        }
		
        
        
        /*
         * pole published:publikacja obrazka
         * - musi zostać ustawione na 'yes' lub 'no' zapobiega przypadkowej publikacji
         */
        if(isset($this->published) && $this->published == 'yes'){//jesli istnieje wlasciwosc published i ma //wartosc yes
            $this->published = 'yes';
        }else{
            $this->published = 'no';
        }
		
		 if(isset($this->color) && $this->color == 'black'){//jesli istnieje wlasciwosc published i ma //wartosc yes
            $this->color = 'black';
        }else{
            $this->red = 'red';
        }
		
        /* na koniec validate spr czy wogole zostaly ustawione jakiekolwiek bledy
			has errors zwraca liczbe bledow (true) */
        
        return (!$this->hasErrors());//f. validujaca zwraca brak bledow
    }//koniec f., walidujacej
    
}//koniec klasy slideentry, istancja klasy slideentry bedzie ustalona w funcji print amdin page plku glownym //pluginu
