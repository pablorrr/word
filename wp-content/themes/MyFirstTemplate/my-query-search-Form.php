<section class="content">
<h1>cwiczenia z formularzem wp query search</h1>
	


<form  method="get" action="<?php echo home_url() . '/moja-customowa-strona-wyszukiwania/' ;//fraza w stringu jako nazwa tytulu jaki dales w nazwie strony ?>">
	
	<select name="categoryname">
			<?php
		// generate list of categories
				$categoriess = get_categories();
				
				foreach ($categoriess as $categoryy) {
				echo '<option  value="', $categoryy->slug, '">', $categoryy->name, "</option>\n";
			}
			
			?>
	</select>
	
	<select name="tagname">
			<?php
		// generate list of categories
				$tags = get_tags();
			foreach ( $tags as $tag ) {
				echo '<option value="', $tag->slug, '">', $tag->name, "</option>\n";
			}
			
			?>
	</select>
	
  <input type="radio" name="order" value="asc" checked> asc<br>
  <input type="radio" name="order" value="desc"> desc<br>
  <input type="radio" name="order" value="rand"> random<br>
	
	
<input type="submit" value="Submit">

</form>


</section>