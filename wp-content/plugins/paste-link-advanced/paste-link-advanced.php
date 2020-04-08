<?php
ob_start(); //http://www.lessthanweb.com/blog/wordpress-and-wp_redirect-function-problem, pozwla na uruchomienie sie strony //nawet gdy ma bledny id w url-u natmiast nie wykaze bledu gdy nie jest uruchommiona session start
//http://www.lessthanweb.com/blog/wordpress-and-wp_redirect-function-problem problem headres already //sent nankoniec jest obflush
/* ob_start php This function will turn output buffering on. While output buffering is active no output is sent from the script (other than headers), instead the output is stored in an internal buffer.

The contents of this internal buffer may be copied into a string variable using ob_get_contents(). To output what is stored in the internal buffer, use ob_end_flush(). Alternatively, ob_end_clean() will silently discard the buffer contents.
Warning

Some web servers (e.g. Apache) change the working directory of a script when calling the callback function. You can change it back by e.g. chdir(dirname($_SERVER['SCRIPT_FILENAME'])) in the callback function.

Output buffers are stackable, that is, you may call ob_start() while another ob_start() is active. Just make sure that you call ob_end_flush() the appropriate number of times. If multiple output callback functions are active, output is being filtered sequentially through each of them in nesting order.  */


session_start();
    /*
     * Plugin Name: PASTE LINK ADVANCED
     * Plugin URI: http://www.websitecreator.pl
     * Description: Praca z obiektem bazy danych wpdb.
     * Version: 1.0.0
     * Author: Pablozzz
     * Author URI: http://www.websitecreator.pl
     * License: GPL2
     */
	 
	 require_once 'libs/Paste_Link_Model.php';//zaladuj tylko raz a drugim razem zgloszenie bledu  bulkChangePublic
	 require_once 'libs/Paste_Link_Entry.php';
	 require_once 'libs/functions.php';
	 require_once 'libs/Pagination.php';
	 require_once 'libs/Request.php';
	 
/* UWAGA!!!! ZACHODZI njprwd zaleznosc miedzy nazwa klasy , nazwa obiektu a slugiem zawartym w plugin id postepowac wg wzorca:
class paste_link- $plugin_id = 'paste-link';- $PasteLinkAdvanced = new paste_link();*/

	  class paste_link {
			//wlasciwosci klasy
			private static $plugin_id = 'paste-link';//id pluginu
            private $plugin_version = '1.0.0';
         
            private $user_capability = 'manage_options';//parametr okr role dostepowe uzytkownikkow  niezb do //tworenia menu
         
            private $model;//model jest wlasciwoscia biezacej klasy reprezentujaca klaase ltehome sider model 	//sluzaca do komunikacji(interface) z baza danych (podobnie jak wpdb)
         
            private $action_token = 'paste-link-hs-action';//posluzy do wpsolpracy z wpnonce fields WP
         
            private $pagination_limit = 3;//ograniczenie liczby wczytanych wpisow obrazkow do 3
		   
		   
		   
		   
		   
		   //konstruktor klasy
		   function __construct() {
             $this->model = new Paste_Link_Model();//wlasciwosc model jest obiektem klasy Moj_PluginMode
             
             //uruchamianie podczas aktywacji
			 /* register_activation_hook WP- rejestruje f., callback ktora sie uruchamia w momnecie aktywacji pluginu
			 param: sciezka pluginu, f., callback uruchamiajaca sie w momencie aktywacji pluginu*/
			 
            register_activation_hook(__FILE__, array($this, 'onActivate'));//onactivate def., lin., 123
           // $this->onActivate();
             //uruchamianie podczas deinstalacji register_uninstall_hook WP , jest rejestracja haka deinstalacji //pluginu oraz jej funkcji callback
             register_uninstall_hook(__FILE__, array('paste_link', 'onUninstall'));
             
             //rejestracja menu admin
             add_action('admin_menu', array($this, 'createAdminMenu'));
             
             
             //rejestracja skryptów panelu admina
             add_action('admin_enqueue_scripts', array($this, 'addAdminPageScripts'));
          
			 add_action('wp_enqueue_scripts',  array($this, 'addPasteLinkStyles')); //podobnie iiiny hak oraz style po stronie //front end
			 
             //rejestracja akcji AJAX
			 /* wp_ajax_ - jest hakiem WP 
               checkValidPosition - jest dolaczona nazwa akcji podpieta do ajax bierze sie z action:checkValidPosition w plku scipt.js	
				array($this, 'checkValidPosition') - nazwa funkcji podpietej do haka*/
				
           add_action('wp_ajax_checkValidPosition', array($this, 'checkValidPosition'));
           add_action('wp_ajax_getLastFreePosition', array($this, 'getLastFreePosition'));
		  
		  
		}

    
    
		  
	function addAdminPageScripts(){
		  
					  
             
             wp_register_script(
                     'paste-admin-script', //id rejestrowanego skryptu
                     plugins_url('/js/scripts.js', __FILE__), array('jquery')//ustalenie jakie skrypty maja wspirac plik 
                );
			
			
			 
			 
			 
             if(get_current_screen()->id == 'toplevel_page_'.static::$plugin_id){//get current pobiera aktualna stone admina
                 //top level page jest prefixem klasy w body strony glownej pluginu jako koncuwka dodawana id pluginu
				 //ponizej kolejnosc wkonywania jq spowoduje ze skrypty sie zalduja tylkow tedy gdy zalduje sie strona panelu //pluginu
                 wp_enqueue_script('jquery');
                  wp_enqueue_script( 'paste-admin-script');
				 
			 }
             
         }
	//rejestracja styli dla galeri
	function addPasteLinkStyless(){
         wp_register_style('link-styles', plugins_url('css/styles.css', __FILE__));
         wp_enqueue_style('link-styles');
     }	 
		 
             
         /* tlum kodu:
							jesli istnieje zmienna bedaca parametrem position przesylana metoda post to ja konwetuj na int jesli nie to wyzeruj zmienna position*/
							
		  function checkValidPosition(){//funkcja powiazana jest z ajax , jej nazwa powiazana z parametrem action: w pliku js
             //jesli istnieje pozycja to ja skonwetuj do int jesli nie nadaj jeej wartosc zero ( aby nie bylo przypadowyc wart)
             $position = isset($_POST['position']) ? (int)$_POST['position']: 0;
             
             $message = '';
             
             if($position < 1){
                 $message = 'Podana wartość jest niepoprawna. Pozycja musi być liczbą większą od 0.';
                 
             }else
             if(!$this->model->isEmptyPosition($position)){
                 $message = 'Dana pozycja jest już zajęta';
                 
             }else{
                 $message = 'Ta pozycja jest wolna';
                 
             }
             
             echo $message;
             die;
         }
         
         function getLastFreePosition(){
             echo $this->model->getLastFreePosition();//printowanie cyfry ostaniej pozycji
             die;//zapobiega wykonana sie dodatkowym nipotrebny m instrukcjom
         }
         
         
         
		 
	 
	 //odinstalowanie pluginu 
         static function onUninstall(){//static w przeciwnym razie wp zglosilby blad ( wartosc fukcji musi byc stala i //zapamietana)
             $model = new Paste_Link_Model();
             $model->dropTable();//usunie tablice pluginu( zawiera aapytania sql drop table)
             
             $ver_opt = static::$plugin_id.'-version';//dostanie sie do wersji pluginu wlasciowsc jest statyczna ( wartosc //stlaa nie zmienna)
             delete_option($ver_opt);//delete_ option WP likwiduje opcje
         }
		 
         function onActivate(){
			 //spr cy juz wczesniej zostal zainstalowany taki plugin
            $ver_opt = static::$plugin_id.'-version';
            $installed_version = get_option($ver_opt, NULL);//param: nazwa opcji, domyslna wartosc opcji gdy opcja nie //istnieje
             
            if($installed_version == NULL){//jesli taki plugin nieistnieje
                 
                 $this->model->createDbTable();//stworz tabele
                 update_option($ver_opt, $this->plugin_version);//update_option WP aktualizuje tabele opcji WP o //nowy plugin
                 
            }else{
                 /* version_compare php - porownuje dwa stringi dotyczace wersji, param; dwa stringi do porownanania */
                switch (version_compare($installed_version, $this->plugin_version)) {
                    case 0:
                        // zainstalowana wersja jest identyczna z tą
                        break;
                     
                    case 1:
                         //zainstalowana wersja jest nowsza niż ta
                        break;
                     
                    case -1:
                        // zainstalowana wersja jest starsza niż ta
                        break;
                }
                 
              }
        }
	 
	 //stworzenie menu w adminie
         function createAdminMenu(){
             
             add_menu_page(
                'Paste Link Advanced', //tytol naglowka na stronie samego panelu
                'Paste Link Advanced', //nazwa-opis linka prowadzacego do strony
                $this->user_capability, //jako manage_options
                static::$plugin_id, //slug menu
                array($this, 'printAdminPage')//f., callback do printowanaia menu, uzywany array z codex w kontekscie klasy
           );
             
         }
	 
	 
	  function printAdminPage(){
             
             $request = Request::instance();//instnacja klasy request bez tworzenia nowego obiektu tej klasy uzycie metody //staycznej instance tej klasy
             
             $view = $request->getQuerySingleParam('view', 'index');//obsluga param url
             $action = $request->getQuerySingleParam('action');
			
             $linkid = (int)$request->getQuerySingleParam('linkid');//konwersja parametru id w url-u na int printuje w url //po wybraniu pojedynczego linka(slidu)
            
             
             switch($view){//view ma rozne wartosci index, form, ( layout - tutja nie wykorzystywany) wyswietlanie tresci //strony w zaleznosci od wybranego kontekstu
                 
                 case 'index':
                     
                     if($action == 'delete'){
                        
                         $token_name = $this->action_token.$linkid;
						
                         $wpnonce = $request->getQuerySingleParam('_wpnonce', NULL);
                         
                         if(wp_verify_nonce($wpnonce, $token_name)){
                             /* wp_verify_nonce WP
								param; nazwa pola nonce do weryfikacji, akcja jaka jest kojarzona z tym nonce
								tutaj: token w parametrze url , nazwa tokena*/
								
                             if($this->model->deleteRow($linkid) !== FALSE){
								
                                 $this->setFlashMsg('Poprawnie usunięto slajd!');
								 
                             }else{
                                 $this->setFlashMsg('Nie udało się usunąć slajdu', 'error');
                             }
                             
                         }else{
                             $this->setFlashMsg('Nie poprawny token akcji', 'error');
                         }
                         
                         $this->redirect($this->getAdminPageUrl());
                         
                     }else
                     if($action == 'bulk'){//akcja bulk czyli masowe dzialanie
                     //is method klasy request sprawdza jaka metoda przeylane sa parametry przez serwer
						 /* check_admin_referer- WP sprawdza czy pole nonce jest poprawne param: nazwa tokena powiazana z akcja */   
                         
                         if($request->isMethod('POST') && check_admin_referer($this->action_token.'bulk')){
                              //jezli zostala wynrana aakcja masowaych dzilan to daj wartosc wybrana przez usera
							 //jesli wartosc bulkcheck zostala wybrana to daj jej wartosc jesli nie podadt pusta tabclicee
                             $bulk_action = (isset($_POST['bulkaction'])) ? $_POST['bulkaction'] : NULL;
                             $bulk_check = (isset($_POST['bulkcheck'])) ? $_POST['bulkcheck'] : array();//bulkcheck odnosis //sie do nazwy tablicy-bulkcheck[] jako wartosc parametru name  tagu input  plik index linia 131 //(masowe dzilanaia0
                              //bulkcheck - ilosc wpisow
							 //bulkaction- akcja masowych dzilana np delete
                             
                             if(count($bulk_check) < 1){
                                 $this->setFlashMsg('Brak linków do zmiany', 'error');
                             }else{
                                 //delete-akcja ususniecia wartosc
                                 if($bulk_action == 'delete'){
                                     
                                     if($this->model->bulkDelete($bulk_check) !== FALSE){
                                         $this->setFlashMsg('Poprawnie usunięto zaznaczone linki!');
                                     }else{
                                         $this->setFlashMsg('Nie udało się usunąć zaznaczonych linków', 'error');
                                     }
                                     
                                 }else//upubliczniaanie -masowe dzilaania
                                 if($bulk_action == 'public' || $bulk_action == 'private'){
                                     
                                     if($this->model->bulkChangePublic($bulk_check, $bulk_action) !== FALSE){
                                         $this->setFlashMsg('Poprawnie zmieniono status linków');
                                     }else{
                                         $this->setFlashMsg('Nie udało się zmienić statusu linków', 'error');
                                     }
                                     
                                 }
                                 
                             }
                             
                         }
                         //przekierwoanie na flowna stone admina puginu po wykonaniu masowych dzialan
                         $this->redirect($this->getAdminPageUrl());
                     }
                     
                     $curr_page = (int)$request->getQuerySingleParam('paged', 1);//biezaca strona
                     $order_by = $request->getQuerySingleParam('orderby', 'id');//sortuj wg
                     $order_dir = $request->getQuerySingleParam('orderdir', 'asc');//kierunek sortowania
                     
                     
                     $pagination = $this->model->getPagination($curr_page, $this->pagination_limit, $order_by, $order_dir);
                     // renderowanie pagincji
                     $this->render('index', array(
                         'Pagination' => $pagination
                     ));
                     break;
                 
                 case 'form':
                     
                     if($linkid > 0){
                         
                         $LinkEntry = new Paste_Link_Entry($linkid);//instancja, obiekt ma zawartosc linkid
                     
                         if(!$LinkEntry->exists()){//jesli wpis obrazka nie istnieje
                             $this->setFlashMsg('Brak takiego linku w bazie danych', 'error');
                             $this->redirect($this->getAdminPageUrl());//przekierowanie do  glownejstrony admina w momencie //braku wpisu
				
                         }
                         
                         
                     }else{// jesli dodano poprawnie wpis stworz obiekt slide entry klasy moj plugin slide entry
                       
                         $LinkEntry = new Paste_Link_Entry();
                       
                     }
                     
                   
                     //jesli akcja jest save (przycisk save w formularzu)i zadanie jest przesylane postem i istieje post entry
                     if($action == 'save' && $request->isMethod('POST') && isset($_POST['link'])){
                   
                         if(check_admin_referer($this->action_token)){//sprawdzenie poprawengo tokena
                        
                            $LinkEntry->setFields($_POST['link']);// wstawienie wszytskich danych wysyslanaych przez pst
							
                            if($LinkEntry->validate()){//wywolanie walidacji danych przesylanych postem i spr czy sa poprawne
							
                                //jesli tak to zapisz obrazek
                                $link_id = $this->model->savelinkentry($LinkEntry);
                                //jesli wpis nie ma wartosci logocznej false czyli zapisanie wpisu odbylo sie poprawnie
                                if($link_id !== FALSE){
                                    
                                    if($LinkEntry->hasId()){// w przypadku modyfikacji wpisu( obrazka)
                                        $this->setFlashMsg('Poprawnie zmodyfikowano link.');
                                    }else{// w przypadku dodanie nowego wpisu
                                        $this->setFlashMsg('Poprawnie dodano nowy link.');
                                    }
                                    //przekierwoanie na widok juz zapisanego aktulanego biezacego  slidu , po to jest klucz //linkid
                                    $this->redirect($this->getAdminPageUrl(array('view' => 'form', 'linkid' => $link_id)));
                            
                                    
                                }else{//jesli entry id jest false, wszytskie f., obslugujace wiadomosci obsluguja sesje
                                    $this->setFlashMsg('Wystąpiły błędy z zapisem do bazy danych', 'error');
                                }
                            }else{//jesli walidacja nie przebiegla prawidlowo
                                $this->setFlashMsg('Popraw błędy formularza', 'error');
                            }
                         
                         }else{//odwolanie do check admin referer sprawdzanie tokena jesli bledne
                             $this->setFlashMsg('Błędny token formularza!', 'error');
                             
                         }
                         
                     }
                     ///////////osdpowiedzilany  zza nazwe $Link przy form.php//////////////
                     $this->render('form', array(
                         'Link' => $LinkEntry
                     ));
                     break;
					 
                 
                 default:
                     $this->render('404');
                     break;
                 
             }
         }
         
         
		 
		 
         private function render($view, array $args = array()){
			 
             
             extract($args);//wyciagnie (phhp) zmiennych
             
             
             $tmpl_dir = plugin_dir_path(__FILE__).'templates/';
             
             $view = $tmpl_dir.$view.'.php';
             
             require_once $tmpl_dir.'layout.php';
             
         }
         
         //zwracanie url do strny admina plugina nie mylic z wbyd wp get admin url
		 
		 /* The admin_url template tag retrieves the url to the admin area for the current site with the appropriate protocol, 'https' if is_ssl() and 'http' otherwise. If scheme is 'http' or 'https', is_ssl() is overridden. param: tutaj; sceizka, parametry sa opcjonalane */
		 
         public function getAdminPageUrl(array $params = array()){//parametrem jest typ tablicowy jako wartosc pusta tablica
             $admin_url = admin_url('admin.php?page='.static::$plugin_id);
             $admin_url = add_query_arg($params, $admin_url);//add_query_arg WP Retrieves a modified URL query string.
             //dzieki add query arg mozna dodawac parametry do urla takie ja viev form i uzaleznic od tego co ma sie w wdanej //chwili wysweitlac na stronie
             return $admin_url;
         }
		
		 
		 
		 
		 //ustawienie wiadomosci w naglowku pluginu
		 public function setFlashMsg($message, $status = 'updated'){// drugi param oznacza update wiadmosci
			 //tworzenie danych sesyjnych , beda istnialy tylko na czas sesji
             $_SESSION[__CLASS__]['message'] = $message;//$_SESSION[__CLASS__] tablica danych sesyjnych klasy biezacej
             $_SESSION[__CLASS__]['status'] = $status;
         }
         //pobranie parametrow sesji
         public function getFlashMsg(){
             if(isset($_SESSION[__CLASS__]['message'])){//jesli istnieje zmienna sesyjna message
                 $msg = $_SESSION[__CLASS__]['message'];//przechowanie wiadomosci w msg
                 unset($_SESSION[__CLASS__]);//niszczenie tej zmiennej i jej zawartosci
                 return $msg;//zwrocenie pustej wiadomosci-mechanizm ten zapobiega pijawianiu ssie wiadomosci po refresh page
             }
             
             return NULL;// jesli zmienna sesyjna nie istnieje nastaw tablice session na null
         }
         //pobranie ststusu wiadomosci, dzieki temu jest okrelsana kalsa css okr wyglad wiadomosci w layout
         public function getFlashMsgStatus(){
             if(isset($_SESSION[__CLASS__]['status'])){
                 return $_SESSION[__CLASS__]['status'];//zwrocenie stsauu jesli istnieje
             }
             
             return NULL;
         }
         //sprawdzenie czy wogole jest ustwiona wiadmosc
         public function hasFlashMsg(){
             return isset($_SESSION[__CLASS__]['message']);
         }
         
         //przekierowania
         public function redirect($location){
             wp_safe_redirect($location);//przekirwoanie na inna strone f built-in WP
             exit;//alternatywa dla php  die dzieki temu zadnea instr nie wykona sie pon przekierowaniu
         } 
}		
$PasteLinkAdvanced = new paste_link();
ob_flush();//zamkniecie dla ob_start
?>