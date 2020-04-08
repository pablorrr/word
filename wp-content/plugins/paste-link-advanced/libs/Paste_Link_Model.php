<?php

/*klasa ta sluzy do dodawania nowych wpisow do bazy danych komunikacja z baza danych wtyczki savelinkentry */

class Paste_Link_Model {
    
    private $table_name = 'paste_link_advanced';//nazwa tabeli wtyczki ktora bedzie w bazie danych
    private $wpdb;//zmienna wp DOSTEP DO BAZY DANYCH
    
    
    function __construct() {
        global $wpdb;//dostep do obiektu baz danych wp musi byc globalny
        $this->wpdb = $wpdb;
    }
    
    
    function getTableName(){//zwrocenie nazwy tablicy wraz z prefixem wp (dynamizacja)
        return $this->wpdb->prefix.$this->table_name;
    }

    
    function createDbTable(){//kreowanie tablicy w bazie danych
        
        $table_name = $this->getTableName();//zmienn aprzechowuje nazwe tablicy
      
		/*	UWAGA!!!!!NALEZY ZACHOWAC SPACJE W QWERENDZIE CREATE TABLE INACZEJ NIE DZILA , DODAC PIERWOTNE IF NOT EXIST POR ORYGINAL	 */
        $sql = 'CREATE TABLE IF NOT EXISTS ' . $table_name . '(
                id INT NOT NULL AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                caption VARCHAR(255) DEFAULT NULL,
                link VARCHAR(255) DEFAULT NULL,
                position INT NOT NULL,
				color enum("black", "red") NOT NULL DEFAULT "black",
				published enum("yes", "no") NOT NULL DEFAULT "yes",
                PRIMARY KEY(id)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8';
        
        require_once ABSPATH.'wp-admin/includes/upgrade.php';//umozliwia z alczenie funkcji dbdelta WP
		
        /* dbdelta - uzycie tej funkcji nie jest konieczne ( wystraczy query do stworzenia i dodania nowej tabeli do abzy dancyh wp) 
		 uzycie tej funkcji spowowduje jednak sprawdzenie wszelkich roznic w wartsciach nowow dodanej tabeli zz ewnatrz do bazy danych WP porownanaia tych wartosci z wbudowanymi tabelami WP i ewentualnie poprawienie tych roznic
		 Jest to najprwdp forma standaryzacji oraz formatowania nowo dodawanych przez programiste tabel*/
		
        dbDelta($sql);
		
    }
    
    //obsluga pustej pozycji obrazka w bazie danych
    function isEmptyPosition($position){
        $position = (int)$position;//konwersja(rzutowanie) na integer
        $table_name = $this->getTableName();
		
        /* tlumaczeni zapytania zlicz wszytskie wiersze z wyatkiem tych hdzie wartosc jest null z okreslonej nazwy tablicy gdzie kolumna ma w wierszu wartosc integer */
		
        $sql = "SELECT COUNT(*) FROM {$table_name} WHERE position = %d";
        $prep = $this->wpdb->prepare($sql, $position);//sporzadzanie dostepu do obrazka z bazy danych na podst //pozycji
        
        $count = (int)$this->wpdb->get_var($prep);//rzutowanie na integer ,get_var f., wpdb zwrca pojedyncza //wartosc //okreslonej kolumny oraz wiersza
        
        return ($count < 1);
    }
    
    
    function getLastFreePosition(){//uzyskaj ostania pozycje
        $table_name = $this->getTableName();
        $sql = "SELECT MAX(position) FROM {$table_name}";//uzyskaj najwyzsza wartosc z kolumny position tabeli //$table name
        $pos = (int)$this->wpdb->get_var($sql);//get var zwraca okreslona wartosc
        
        return ($pos+1);
    }
    //zachowanie wpisow do bazy danych linkentry jako obiket klasy moj plugin linkentry jest nosnikiem do zapisywania danych do bazy //dancyh  Paste_Link_Entry
	
    function savelinkentry(Paste_Link_Entry $linkentry){//zachowaj wpis do bzy danych linkentry ( ang) wejscie
        //Paste_Link_linkentry $linkentry - oznacza ze linkentry jest obiektem(instancja) klasy //Paste_Link_linkentry
        $toSave = array(//do zachowania
           // 'slide_url' => $linkentry->getField('slide_url'),
            'title' => $linkentry->getField('title'),
            'caption' => $linkentry->getField('caption'),
            'link' => $linkentry->getField('link'),
			'color' => $linkentry->getField('color'),
            'position' => $linkentry->getField('position'),
            'published' => $linkentry->getField('published'),
        );
        
        $maps = array('%s', '%s', '%s','%s', '%d', '%s');//przechowuje sposob formatowania: kolejno: 4 lancuchy
														//jeden integer, jeden lancuch
        
        $table_name = $this->getTableName();
        
        if($linkentry->hasId()){//czy wpis ma id ( kolumne z wartoscia id)
            /* update istniejacych juz wartosci na nowe , update jako wlasciwosc obiektu wpdb*/
		
		/*update param:  array('id' => $linkentry->getField('id'))- id jakie bedzie modyfikowane updateowane 
		 $maps- dane do mapowania czyli formatowania ( z calego wiersza)
		 '%d'- parametr do ostaczengo formatowania ( id musi byc integer  -d)
		*/
            if($this->wpdb->update($table_name, $toSave, array('id' => $linkentry->getField('id')), $maps, '%d')){
                return $linkentry->getField('id');//uzyskaj dostep do pola gdzie kolumna nazywa sie id
            }else{
                return FALSE;
            }
            
        }else{
        
            if($this->wpdb->insert($table_name, $toSave, $maps)){//wstawianie danych do bazy danych wtyczki
			//param insert kolejno: nazwa tablicy (do jakiej maja byc wstawiione warttosci) , dane do zachowania, dane do //sformatowania
                return $this->wpdb->insert_id;//odczytuje wartosc id nowego rekordu(wiersza),tutaj: gdy dodanie //danych sie //powiedzie insett id jest wlasciwoscia wbudowana ww wpdb
            }else{//gdy uzytkownik wstawi niedopowiedni format danych
                return FALSE;
            }
        
        }
        
    } 
    
    //fetch ang- sprowadzac, zwraca zawartosc wiersza w bazie danych, wyciaganie wartosci z bazy danych 
    function fetchRow($id){
        $table_name = $this->getTableName();
        $sql = "SELECT * FROM {$table_name} WHERE id = %d";//gdzie id jest intgerem
        $prep = $this->wpdb->prepare($sql, $id);
		
		
        return $this->wpdb->get_row($prep);
    }
    
    //paginacja
    function getPagination($curr_page, $limit = 10, $order_by = 'id', $order_dir = 'asc'){//asc sortowanie rosnaco
        
        $curr_page = (int)$curr_page;//rzutowanie na int
        if($curr_page < 1){//dla pewnosci ze strona nie ejst zero
            $curr_page = 1;//to i tak ustaw ja na jeden
        }
        
        $limit = (int)$limit;// rzutowanie na int
       // getOrderByOpts- zdef prze zusera bedzie zwraca kolumny mozliwe do sortowania 
	   //$order_by_opts- pobranie wszytskich opcji
        $order_by_opts = static::getOrderByOpts();//okreslenie dostepu do f., sttycznej ( wartosc "zyje " rowniez //poza funkcja)
		 //in_array php spr czy podanej tablicy nie ma podanej wartosci 
		
		//order by reprezentuje sortuj wg
		//in array sprawdzi czy order byopts  zanjduje sie w tablicy orderby 
		//jezeli sie  NIE znajduje to zwroc id jezeli tak to zwrc wartosc orederby
        $order_by = (!in_array($order_by, $order_by_opts)) ? 'id' : $order_by;
        
        //order dir reprezentuje kierunek sortowania ( rosnacy lub malejacy)
		//jezli w oreder dir znajduje sie asc lub desc to zwroc oreder dir jesli nie to asc -rosnaco
         $order_dir = in_array($order_dir, array('asc', 'desc')) ? $order_dir : 'asc';// asc rosnaco desc malejaco
		
		
        $offset = ($curr_page-1)*$limit;//offser wykorzystanyb w w linijce 182 zapytanie sql ,njprwd pozycja danej strony
        
        $table_name = $this->getTableName();
        
        
        $count_sql = "SELECT COUNT(*) FROM {$table_name}";//zliczenie wszytskich komorek w tabeli, potrzebne do wyswietlenie  //liczby wszytskich slidow w paginacji
        $total_count = $this->wpdb->get_var($count_sql);
        
        $last_page = ceil($total_count/$limit);//ceil php zaokrogla wzywz w przypadku osiagniecia liczby float
        
        
        $sql = "SELECT * FROM {$table_name} ORDER BY {$order_by} {$order_dir} LIMIT {$offset}, {$limit}";
        //get results pobierze wszytskie rekordy zgodne z zapytaniem
        $Links_list = $this->wpdb->get_results($sql);
        
        $Pagination = new Pagination($Links_list, $order_by, $order_dir, $limit, $total_count, $curr_page, $last_page);
        
        return $Pagination;
    } 
    
    //usuwanie wiersza
    function deleteRow($id){
        $id = (int)$id;
        
        $table_name = $this->getTableName();
        $sql = "DELETE FROM {$table_name} WHERE id = %d";
        $prep = $this->wpdb->prepare($sql, $id);
        
        return $this->wpdb->query($prep);
    }
    
    //likwidacja- masowe dzialanie
    function bulkDelete(array $ids_list){//id listy- liczba wpisow
		/* array_map PHP - daj tablece do przetworzenia przez funkcje , przetwrzanay bedzie kazdy eleemnt tablicy param: nazwa tablicy, funkcja przetwarzajaca */
        $ids_list = array_map('intval', $ids_list);//konwersja wszytskich lelemntow tablicy na intval czy integer
        
        $table_name = $this->getTableName();
        
        $ids_str = implode(',', $ids_list);//implode php lacz znaki odseparowane przecinkiem
        $sql = "DELETE FROM {$table_name} WHERE id IN ({$ids_str})";
        return $this->wpdb->query($sql);
    }
    //masowe dzilanie- upublczianie wpisu ( obrazka)
	//ids_list - lista id do zmiany, change_to- przelacznik w switch'u - zamien na
	
    function bulkChangePublic(array $ids_list, $change_to){//param: liczba wpisow, biezaca pidejmowana akcja( usuwanie //upublicznianie itd)
        $ids_list = array_map('intval', $ids_list);//zrzutowanie tablicy na elementy integer, jest to wymagane do spreparowania zapytaia sql
        
        $status = '';// wyzerowanie akcji
        switch($change_to){
            default:
			//domyslny public
            case 'public': $status = 'yes'; break;// wprzypadku gdy wpis publiczny
            case 'private': $status = 'no'; break;//w przypadku gdy wpis prywatny
        }
        
        $table_name = $this->getTableName();
        $ids_str = implode(',', $ids_list);//laczenie lancuch wg przecinka , jest to wymagane do spreparowania zapyania sql
        //nastaw klumne published na dynamiczn awartosc status tam gdzie id ma wartosc dynamiczna idsstr
        $sql = "UPDATE {$table_name} SET published = '{$status}' WHERE id IN ({$ids_str})";
        return $this->wpdb->query($sql);
    }//koniec bulk change public
	
	
    //uzyskiwanie dostepu do elementow ktre juz zostale upubicznione w bazie danych
    function getPublishedSlides(){
        $table_name = $this->getTableName();
        
        $sql = "SELECT * FROM {$table_name} WHERE published = 'yes' ORDER BY position";
        return $this->wpdb->get_results($sql);
    }
	
	
	
	//usuwanie tablicy, uzywane przy usuwaniu pluginu z systemu
    function dropTable(){
        $table_name = $this->getTableName();
        $sql = "DROP TABLE {$table_name}";//sql  usuwanie tablicy
        return $this->wpdb->query($sql);//zwroc zapytanie sql do funkcji query wlasciwosci wpdb 
    }
    //zwracania kolimn mozliwych do sortowania
    static function getOrderByOpts(){
        return array(
            'ID' => 'id',
            'Pozycja' => 'position',
            'Widoczność' => 'published'
        );
    }
    
}
