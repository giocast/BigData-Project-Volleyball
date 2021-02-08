<?php

//FASE DI LETTURA DEI NOMI DEI FILES E POI ANALISI DEI SINGOLI 
//(PRIMA LANCIARE SCRIPT riempiElencoFilesScout.php PER RIEMPIMENTO DI NOMI FILES)

$nomiFiles = fopen("elenco_files_scout_def.js","r"); //contiene tutti i files da analizzare
$stack = array();
$numeroFiles = 0;
$indice=0;

while(! feof($nomiFiles)) //lettura ciclica di ogni nome di file scout
  {
  	$nomeFile = fgets($nomiFiles);
    $stack[$indice] = $nomeFile; //array_push($stack, $nomeFile); //riempio vettore
    $numeroFiles++;
    $indice++;
  } //while della lettura dei nomi dei files
  
 fclose($nomiFiles);
 
 //Per stampa vettore 
 print_r($stack);
 echo "<br>";
  foreach ($stack as $in => $path) { //PER OGNI FILE, DOVRò APRIRLO E FARE LA MIA ANALISI
		echo "Il file " . $in . " si trova a _" . $path . "_ \n";


echo "<script>alert('".$in."');</script>";

  
  //$file = fopen("_01and_SantaCroce-Castellana_mp.dvw","r"); 
  
  //trim toglie spazi (prima e DOPO il path) che causano problema in fread
	$file = fopen(trim($path),"r"); 
    
echo "<br>";    
print_r(error_get_last());


//---------variabili-------------- 

$dataMatch="";
$codiceSquadraA="";
$codiceSquadraB="";
$esitoGara="";
$esitoGaraPerML="";

$attacchiPiuPiuA = 0; //attacchi # A
$attacchiMenoMenoA = 0; //attacchi = A
$attacchiSlashA = 0; //attacchi / A
$attacchiTotA = 0; //attacchi TOT A

$attacchiPiuPiuB = 0; //attacchi # B
$attacchiMenoMenoB = 0; //attacchi = B
$attacchiSlashB = 0; //attacchi / B
$attacchiTotB = 0; //attacchi TOT B

$muriPiuPiuA = 0; //muri # A
$muriMenoMenoA = 0; //muri = A
$muriSlashA = 0; //muri / A
$muriPiuA = 0; //muri + A
$muriTotA = 0; //attacchi TOT A

$muriPiuPiuB = 0; //muri # B
$muriMenoMenoB = 0; //muri = B
$muriSlashB = 0; //muri / B
$muriPiuB = 0; //muri + B
$muriTotB = 0; //attacchi TOT B

$battutePiuPiuA = 0; //battute # A
$battuteMenoMenoA = 0; //battute = A
$battuteSlashA = 0; //battute / A
$battutePiuA = 0; //battute + A
$battuteTotA = 0; //battute TOT A

$battutePiuPiuB = 0; //battute # B
$battuteMenoMenoB = 0; //battute = B
$battuteSlashB = 0; //battute / B
$battutePiuB = 0; //battute + B
$battuteTotB = 0; //battute TOT B


//Percentuali

$percentualeAttaccoA = 0;
$percentualeAttaccoB = 0;
$percentualeMuroA = 0;
$percentualeMuroB = 0;
$percentualeBattutaA = 0;
$percentualeBattutaB = 0;

//--------fine variabili----------

while(! feof($file)) //lettura ciclica righe del file
  {
  $linea = fgets($file); //prelievo di una linea
  echo $linea . "<br />"; 
  

if (strpos($linea, '[3MATCH]') !== false) { //se linea contiene 3MATCH utile per identificare data match (alla riga successiva)
    $stringaConData = fgets($file); //ed elaborazione
    echo $stringaConData."<br>";
    $getData = explode(";", $stringaConData);
    $data = $getData[0]." ".$getData[1];
    //echo "_________________________".$data."<br>";
    	$provaFormat = str_replace('/', '-', $data);
        $provaFormat = str_replace('.', ':', $provaFormat);
        
        $dataMatch = date('Y-m-d H:i:s', strtotime($provaFormat));
		//echo "_________________".date('Y-m-d H:i:s', strtotime($provaFormat))."<br>";
}

if (strpos($linea, '[3TEAMS]') !== false) { //se linea contiene 3TEAMS utile per prendere 2 righe successive
 	$stringaSquadraA = fgets($file); //ed elaborazione
    $stringaSquadraB = fgets($file); //ed elaborazione
    echo $stringaSquadraA."<br>".$stringaSquadraB."<br>";
    $getA = explode(";", $stringaSquadraA);
    $codiceSquadraA = $getA[0];
    $getB = explode(";", $stringaSquadraB);
    $codiceSquadraB = $getB[0];
    //echo "__________________________".$codiceSquadraA."-".$codiceSquadraB."<br>";
}

if (strpos($linea, '[3SET]') !== false) { //se linea contiene 3SET utile per calcolare esito partita
        /* Esempio
        [3SET]
        True;6 -8;16-13;19-21;21-25;30;
        True;6 -8;15-16;21-19;24-26;41;
        True;6 -8;11-16;15-21;17-25;27;
        True;;;;;;
        True;;;;;;
        */
      
      //G: mi prendo a sentimento tutti e 5 gli eventuali set e gli analizzo
      $primoSet = fgets($file);
	  $secondoSet = fgets($file);
      $terzoSet = fgets($file);
      $quartoSet = fgets($file);
      $quintoSet = fgets($file);
      
      echo $primoSet."<br>".$secondoSet."<br>".$terzoSet."<br>".$quartoSet."<br>".$quintoSet."<br>";
      
      $setVintiA = 0;
      $setVintiB = 0;
      
      $sets = array($primoSet,$secondoSet,$terzoSet,$quartoSet,$quintoSet);
      
      
      for($i = 0; $i < count($sets); $i++) {
    		$getSet = explode(";", $sets[$i]);
            $esitoSet = $getSet[4];
            
            if($esitoSet!="") { //se non c'è stato set iesimo, lo scarto
            
            $puntiSet = explode("-", $esitoSet);
            $puntiA = $puntiSet[0];
        	$puntiB = $puntiSet[1];
            if($puntiA>$puntiB) {
        			echo "<script>alert('Set numero ".($i+1)." vinto da A');</script>";
        			$setVintiA++;
        	} else {
        		$setVintiB++;
            	echo "<script>alert('Set numero ".($i+1)." vinto da B');</script>";
        	}
            
            echo "<script>alert('Punteggio ".($i+1)."° set ".$setVintiA."-".$setVintiB."');</script>";
	
    }//if considero set giocati effettivamente
    
    }//fine for sets
    
    //esito gara
    
    $esitoGara = $setVintiA."-".$setVintiB; //definire in che formato vogliamo salvarlo in db -> stringa es 0-3 oppure due campi SetA e SetB
    echo "<script>alert('Final result ".$esitoGara."');</script>";
    
    
    //lavoriamo per esito ML
    //$esitoGaraPerML	
    /*
    Tipi di risultato
    	1) 3-0
        2) 3-1
        3) 3-2
        4) 0-3
        5) 1-3
        6) 2-3
    */
    
    
    switch ($setVintiA) {
          case 3:
          	if($setVintiB==0) {
            	$esitoGaraPerML = "1";
            } elseif($setVintiB==1) {
            	$esitoGaraPerML = "2";
            } elseif($setVintiB==2) {
            	$esitoGaraPerML = "3";
            }
            break;
            
          case 2:
          	if($setVintiB==3) {
            	$esitoGaraPerML = "6";
            }
            break;
          case 1:
          	if($setVintiB==3) {
            	$esitoGaraPerML = "5";
            }
            break;
          case 0:
          	if($setVintiB==3) {
            	$esitoGaraPerML = "4";
            }
            break;
	}
    
    
      	/* FULLY WORKING->ora proviamo con for per evitare di fare copia e incolla 5 volte
      	$getPrimo = explode(";", $primoSet);
    	$esitoPrimo = $getPrimo[4];
		$puntiPrimo = explode("-", $esitoPrimo);
        $puntiA = $puntiPrimo[0];
        $puntiB = $puntiPrimo[1];
        echo "<script>alert('Punti di A nel primo set ".$puntiA."');</script>";
		echo "<script>alert('Punti di B nel primo set ".$puntiB."');</script>";
        if($puntiA>$puntiB) {
        echo "<script>alert('Set vinto da A');</script>";
        	$setVintiA++;
        } else {
        	$setVintiB++;
            echo "<script>alert('Set vinto da B');</script>";
        }
        
        echo "<script>alert('Punteggio primo set ".$setVintiA."-".$setVintiB."');</script>";
        
        */
}
 
if (strpos($linea, '[3SCOUT]') !== false) { //inizio dati statistici associati ad ogni singolo fondamentale di ogni giocatore delle due squadre

//N.B. squadra home * squadra away a (per Matteo Mater è sempre *)
//formato *|NUM2CIFRE|fondamentale2cifre|esito1cifra ->
 $lineaScout = fgets($file);
 
 while(trim($lineaScout) !== '')
 {
 	//IL CODICE NON è MOLTO OTTIMIZZATO, MA CIO CHE CONTA è IL DATASET FINALE
 	$elaborazioneLinea = explode(";", $lineaScout);
 	$interesse = $elaborazioneLinea[0];
    $squadra = $interesse[0]; //*-> home/mater e a-> away/avversari
    $fondamentale = $interesse[3]; //quelli che valuteremo sono A(attaccto),S(battuta),B(muro)   Un'altra volta [E(errore alzata),F(errore freeball)]
    $esitoFondamentale = $interesse[5]; //quelli che valuteremo sono #, + = o / a seconda del fondamentale analizzato
 
 	if($squadra == '*') {
              switch ($fondamentale) {
          			case 'A':
              			//echo $squadra."attacco<br>";
                        if($esitoFondamentale=='#') {
                        	$attacchiPiuPiuA++;
                        } elseif($esitoFondamentale=='=') {
                        	$attacchiMenoMenoA++;
                        } elseif($esitoFondamentale=='/') {
                        	$attacchiSlashA++;
                        }
                        
                        $attacchiTotA++;
                        
              			break;
                        
          			case 'S':
              			//echo $squadra."service<br>";
                        if($esitoFondamentale=='#') {
                        	$battutePiuPiuA++;
                        } elseif($esitoFondamentale=='=') {
                        	$battuteMenoMenoA++;
                        } elseif($esitoFondamentale=='/') {
                        	$battuteSlashA++;
                        } elseif($esitoFondamentale=='+') {
                        	$battutePiuA++;
                        }
                        
                        $battuteTotA++;
                        
              			break;
                        
          			case 'B':
              			//echo $squadra."block<br>";
                        
                        if($esitoFondamentale=='#') {
                        	$muriPiuPiuA++;
                        } elseif($esitoFondamentale=='=') {
                        	$muriMenoMenoA++;
                        } elseif($esitoFondamentale=='/') {
                        	$muriSlashA++;
                        } elseif($esitoFondamentale=='+') {
                        	$muriPiuA++;
                        }
                        
                        $muriTotA++;
                        
             			break;
						}
    
    } elseif($squadra == 'a') {
    	switch ($fondamentale) {
          			case 'A':
              			//echo $squadra."attacco<br>";
                        if($esitoFondamentale=='#') {
                        	$attacchiPiuPiuB++;
                        } elseif($esitoFondamentale=='=') {
                        	$attacchiMenoMenoB++;
                        } elseif($esitoFondamentale=='/') {
                        	$attacchiSlashB++;
                        }
                        
                        $attacchiTotB++;
                        
              			break;
                        
          			case 'S':
              			//echo $squadra."service<br>";
                        
                         if($esitoFondamentale=='#') {
                        	$battutePiuPiuB++;
                          } elseif($esitoFondamentale=='=') {
                              $battuteMenoMenoB++;
                          } elseif($esitoFondamentale=='/') {
                              $battuteSlashB++;
                          } elseif($esitoFondamentale=='+') {
                              $battutePiuB++;
                          }
                        
                        $battuteTotB++;
                        
              			break;
                        
          			case 'B':
              			//echo $squadra."block<br>";
                        
                          if($esitoFondamentale=='#') {
                              $muriPiuPiuB++;
                          } elseif($esitoFondamentale=='=') {
                              $muriMenoMenoB++;
                          } elseif($esitoFondamentale=='/') {
                              $muriSlashB++;
                          } elseif($esitoFondamentale=='+') {
                              $muriPiuB++;
                          }
                        
                        $muriTotB++;
                        
             			break;
                        
						}
    }
    
    
    //vado avanti 
    $lineaScout = fgets($file); //continuo a prendermi tutte linee ciclicamente-> alla fine il while grande non vedrà più righe (si spera)
 }
 
 //faccio i conti finali sui fondamentali dell squadre per questa partita
 //------SEZIONE CALCOLI E SUCCESSIVA CONN DB----------
 
 $percentualeAttaccoA = round((($attacchiPiuPiuA-$attacchiMenoMenoA-$attacchiSlashA)/$attacchiTotA)*100);
 echo "attacchi # A sono ".$attacchiPiuPiuA."<br>";
 echo "attacchi = A sono ".$attacchiMenoMenoA."<br>";
 echo "attacchi / A sono ".$attacchiSlashA."<br>";
 echo "attacchi TOT A sono ".$attacchiTotA."<br>";
 echo "Percentuale attacco di A ".$percentualeAttaccoA."%<br>";
 echo "<br><br>";
 $percentualeAttaccoB = round((($attacchiPiuPiuB-$attacchiMenoMenoB-$attacchiSlashB)/$attacchiTotB)*100);
 echo "attacchi # B sono ".$attacchiPiuPiuB."<br>";
 echo "attacchi = B sono ".$attacchiMenoMenoB."<br>";
 echo "attacchi / B sono ".$attacchiSlashB."<br>";
 echo "attacchi TOT B sono ".$attacchiTotB."<br>";
 echo "Percentuale attacco di B ".$percentualeAttaccoB."%<br>";
 echo "<br><br>";
 $percentualeMuroA = round((($muriPiuPiuA+$muriPiuA-$muriSlashA-$muriMenoMenoA)/$muriTotA)*100);
 echo "muri # A sono ".$muriPiuPiuA."<br>";
 echo "muri = A sono ".$muriMenoMenoA."<br>";
 echo "muri / A sono ".$muriSlashA."<br>";
 echo "muri + A sono ".$muriPiuA."<br>";
 echo "muri TOT A sono ".$muriTotA."<br>";
 echo "Percentuale muro di A ".$percentualeMuroA."%<br>";
 echo "<br><br>";
 $percentualeMuroB = round((($muriPiuPiuB+$muriPiuB-$muriSlashB-$muriMenoMenoB)/$muriTotB)*100);
 echo "muri # B sono ".$muriPiuPiuB."<br>";
 echo "muri = B sono ".$muriMenoMenoB."<br>";
 echo "muri / B sono ".$muriSlashB."<br>";
 echo "muri + B sono ".$muriPiuB."<br>";
 echo "muri TOT B sono ".$muriTotB."<br>";
 echo "Percentuale muro di B ".$percentualeMuroB."%<br>";
 echo "<br><br>";
 $percentualeBattutaA = round((($battutePiuPiuA+$battutePiuA+$battuteSlashA-$battuteMenoMenoA)/$battuteTotA)*100);
 echo "battute # A sono ".$battutePiuPiuA."<br>";
 echo "battute = A sono ".$battuteMenoMenoA."<br>";
 echo "battute / A sono ".$battuteSlashA."<br>";
 echo "battute + A sono ".$battutePiuA."<br>";
 echo "battute TOT A sono ".$battuteTotA."<br>";
 echo "Percentuale battuta di A ".$percentualeBattutaA."%<br>";
 echo "<br><br>";                      	
 $percentualeBattutaB = round((($battutePiuPiuB+$battutePiuB+$battuteSlashB-$battuteMenoMenoB)/$battuteTotB)*100);
 echo "battute # B sono ".$battutePiuPiuB."<br>";
 echo "battute = B sono ".$battuteMenoMenoB."<br>";
 echo "battute / B sono ".$battuteSlashB."<br>";
 echo "battute + B sono ".$battutePiuB."<br>";
 echo "battute TOT B sono ".$battuteTotB."<br>";
 echo "Percentuale battuta di B ".$percentualeBattutaB."%<br>";
 echo "<br><br>";  
 
//-------------------------------DB-----------------------------

$host="127.0.0.1";
$usern="bldg";
$pass="";
$db="my_bldg";


$connM = mysqli_connect($host,$usern,$pass) or die (mysqli_connect_error());
if($connM)
{

	$query="USE my_bldg;";
	
	if(mysqli_query($connM,$query))
	{
    
    $queryInserimento="INSERT INTO `BigDataVolleyballProject`(`DataIncontro`, `SquadraA`, `SquadraB`, `EsitoIncontro`, `EsitoIncontroPerML`, `PercAttaccoA`, `PercAttaccoB`, `PercMuroA`, `PercMuroB`, `PercBattutaA`, `PercBattutaB`) VALUES ('$dataMatch','$codiceSquadraA','$codiceSquadraB','$esitoGara','$esitoGaraPerML','$percentualeAttaccoA','$percentualeAttaccoB','$percentualeMuroA','$percentualeMuroB','$percentualeBattutaA','$percentualeBattutaB')";
    	if(mysqli_query($connM,$queryInserimento))
		{
       



        }
        else {
        echo "<script>alert('".mysqli_error($connM)."');</script>";
        }
        
     }
     
}


//-------------------------------FINE DB--------------------------

 
//occhio a righe finali set
} //if scout
 
 
 
 } //while lettura righe file
fclose($file);

//Stampo dati ricavati
echo "<script>alert('Il match in data ".$dataMatch." tra la formazione con codice ".$codiceSquadraA." e la formazione con codice ".$codiceSquadraB." ha avuto esito ".$esitoGara."');</script>";

} //foreach per ogni file

?>