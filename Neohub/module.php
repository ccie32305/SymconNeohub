<?
// Modul für Doorbird
class Neohub extends IPSModule
{
    public function Create()
    {
//Never delete this line!
        parent::Create();
		
		//These lines are parsed on Symcon Startup or Instance creation
        //You cannot use variables here. Just static values.
		
   		 $this->RegisterPropertyString("IP", ""); 
	        $this->RegisterPropertyString("Port", "4242"); 

	    // Heizungsgruppe erstellen
	    $CatID = IPS_CreateCategory();       // Kategorie anlegen
IPS_SetName($CatID, "Heizung"); // Kategorie benennen


    }
}
?>
