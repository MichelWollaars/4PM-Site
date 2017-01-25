<?php
    // Hier komt de code om alle berichten op te slaan
    $input          = array();    
    $foutmelding    = '';
    $jsonstring     = '';
    $berichten      = '';

    if(isset($_POST['naam']) == true) {
        // Haal de verzonden gegevens op, en zet de speciale characters om t.b.v. veiligheid
        $input['naam']      = htmlentities($_POST['naam']);
        $input['email']     = htmlentities($_POST['emailadres']);
        $input['bericht']   = htmlentities($_POST['bericht']);
        
        // Controleer of alle velden zijn ingevuld, zoniet stuur dan een foutmelding
        if(empty($input['naam']) == true  ||  empty($input['email']) == true ||  empty($input['bericht']) == true) 
        {
            $foutmelding = "Niet alle velden zijn ingevuld";
        } 
        
        // Als alle velden goed zijn ingevuld, dan gaan we het bericht opslaan
        else 
        {
            // Kijk of eerste lijn is, zoniet dan moet er een komma voor de string komen
            if(filesize('berichten.txt') > 0) {
                $jsonstring = ',';
            }
            
            // Zet de array om in een json string, zodat we het makkelijker kunnen opslaan
            $jsonstring .= json_encode($input);
            
            // Open het tekstdocumentje, in een schrijf-modus waarbij de pointer op het einde wordt geplaatst(a)
            $handle = fopen('berichten.txt', 'a');
            
            // Schrijf de regel weg in het bestandje
            fwrite($handle, $jsonstring);
            
            // Sluit de handle weer af om geheugen vrij te maken
            fclose($handle);
            
            // Gooi statcache leeg, anders is de filesize niet geupdate aangezien deze de bestandsgrootte uit de statcache pakt
            clearstatcache();
        }
        
    }
    
    
    // Haal alle berichten op uit het document
    // Controleer of er berichten zijn om te tonen
    if(filesize('berichten.txt') > 0) {
        // Open het bestand
        $handle = fopen('berichten.txt', 'r');
        
        // Haal de berichten op
        $berichten_string = fread($handle, filesize('berichten.txt'));
        
        // Sluit de handle weer af om geheugen vrij te maken
        fclose($handle);

        // Zet de json string terug naar een array
        $berichten = json_decode("[" . $berichten_string . "]", true);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Gastenboek</title>
        
        <style type="text/css">
            body {
				background: url("http://localhost/html/images/achtergrond02.png");
				background-position: center no-repeat;
                font-family: 'Georgia';
            }
            
            label, input, textarea {
                display: block;
               
            }
            
            input {
                margin-bottom: 20px;
                width: 300px;
                height: 20px;
            }
            
            input[type='submit'] {
                height: 40px;
                cursor: pointer;
            }
            
            textarea {
                width: 80%;
                height: 100px;
            }
                        
            .formulier {
                float: left;
                width: 98%;
                height: auto;
                padding: 1%;
                margin-bottom: 20px;
                
                
            }
            
            .overzicht {
                float: left;
                margin-top: 20px;
				margin-right: 25px;
            }
            
            .overzicht .labelnaam {
                display: inline-block;
                width: 100px;
                margin-bottom: 5px;
                font-weight: 600;
            }
			
			
			
	
	
	
	
ul.topnav {
  list-style-type: none;
  margin: auto;
  padding: 0;
  overflow: hidden;
  background-color:#000000;
  
  
}


ul.topnav li {
float: left;
margin-left: 10px;
margin-right: 10px;
}

ul.topnav li a {
  display: inline-block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  transition: 0.3s;
  font-size: 17px;
}

ul.topnav li a:hover {background-color: #9b341b;}

ul.topnav li.icon {display: none;}

@media screen and (max-width:680px) {
  ul.topnav li:not(:first-child) {display: none;}
  ul.topnav li.icon {
    float: right;
    display: inline-block;
  }
}

@media screen and (max-width:680px) {
  ul.topnav.responsive {position: relative;}
  ul.topnav.responsive li.icon {
    position: absolute;
    right: 0;
    top: 0;
  }
  ul.topnav.responsive li {
    float: none;
    display: inline;
  }
  ul.topnav.responsive li a {
    display: block;
    text-align: left;
  } 
        </style>

		
		<ul class="topnav" id="myTopnav">
  <li><a href="http://localhost/html/home.html">Home</a></li>
  <li><a href="http://localhost/html/contact.html">Contact</a></li>
  <li><a href="http://localhost/html/video.html">Video</a></li>
  <li><a href="http://localhost/html/audio.html">Audio</a></li>
  <li><a href="http://localhost/html/boekingen.html">Boekingen</a></li>
  <li><a href="http://localhost/html/enquete.html">Enquete</a></li>
  <li><a href="http://localhost/index.php">Recensies</a></li>
  <li class="icon">
    <a href="javascript:void(0);" style="font-size:15px;" onclick="myFunction()">☰</a>
  </li>
</ul>
		
    </head>
    <body>
        
        
        
        <div class="formulier">
            <h3>Laat een bericht achter...  </h3>
            
            <?php if(isset($_POST['naam']) == true && empty($foutmelding) == true) {
                echo "Bedankt voor uw bericht!";
            }
            
            // Als er niets verstuurd is, of er is een foutmelding, toon dan het formulier weer
            else {
            ?>

                <?php echo $foutmelding; ?>

                <form method="post">
                    <label>Naam</label>
                    <input type="text" name="naam" />

                    <label>E-mailadres</label>
                    <input type="email" name="emailadres" />

                    <label>Bericht</label>
                    <textarea name="bericht"></textarea>

                    <label>&nbsp;</label>
                    <input type="submit" value="Versturen" />
                </form>
            
            <?php 
            } // Sluit de else weer af
            ?>
        </div>
        
        <div class="overzicht">
            <h2 style="font-family: Georgia; margin-left: 20px;">Berichten van onze klanten</h2>
        
            <!-- Toon hier de berichten -->
            <?php
                // Als er berichten zijn om te tonen, toon deze dan ook op de pagina
                if(empty($berichten) === false) {
                    
                    foreach($berichten as $key => $inputveld) {
                        echo "<span class='labelnaam'>Naam:</span> " . $inputveld['naam'] . "<br/>"
                                . "<span class='labelnaam'>E-mailadres:</span> " . $inputveld['email'] . "<br/>"
                                . "<span class='labelnaam'>Bericht:</span><br/>" . $inputveld['bericht'] . "<br/><br/><hr><br/>";
                    }
                    
                } 
                
                // Als er geen berichten zijn, meld dit dan netjes aan de bezoeker
                else {
                    echo "Er zijn geen berichten om te tonen";
                }
            ?>  
            
        </div>
        
    </body>
</html>
