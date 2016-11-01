<?
// Neohub Smart Thermostat Module
class Neohub extends IPSModule
{
    public function Create()
    {
//Never delete this line!
        parent::Create();
		
		//These lines are parsed on Symcon Startup or Instance creation
        //You cannot use variables here. Just static values.
		
   		 $this->RegisterPropertyString("NeohubIP", ""); 
	        $this->RegisterPropertyString("NeohubPort", "4242"); 
		$this->RegisterPropertyInteger("NeohubUpdateInterval", "60");
	    /*
	    // Heizungsgruppe erstellen
	    $CatID = IPS_CreateCategory();       // Kategorie anlegen
            IPS_SetName($CatID, "Heizung"); // Kategorie benennen
$InsID = IPS_CreateInstance();
IPS_SetName($InsID, "1. Thermostat"); // Instanz benennen
IPS_SetParent($InsID, $CatID); // Instanz einsortieren unter der Kategorie "Heizung"
*/
    }
	        public function ApplyChanges() {
            // Diese Zeile nicht löschen
            parent::ApplyChanges();
        }
		$this->registerUpdateTimer("Update", $this->ReadPropertyInteger("NeohubUpdateInterval"));
		$this->validateNeohubConfiguration();
}
	########## private functions ##########
	/**
	 *		register the update timer
	 */

protected function registerUpdateTimer(string $UpdateTimerName, int $TimerInterval)
	{
		$NeohubInstanceId = $this->InstanceID;
   	$InstanceId = @IPS_GetObjectIDByIdent($UpdateTimerName, $NeohubInstanceId);
		if ($InstanceId && IPS_GetEvent($InstanceId)['EventType'] <> 1) {
      	IPS_DeleteEvent($InstanceId);
      	$InstanceId = 0;
    	}
		if (!$InstanceId) {
      	$InstanceId = IPS_CreateEvent(1);
      	IPS_SetParent($InstanceId, $this->InstanceID);
      	IPS_SetIdent($InstanceId, $UpdateTimerName);
    	}
    	IPS_SetName($InstanceId, $UpdateTimerName);
    	IPS_SetHidden($InstanceId, true);
    	IPS_SetEventScript($InstanceId, "\$InstanceId = {$BridgeInstanceId};\nNUKI_updateStateOfSmartLocks($NeohubInstanceId);");
    	if (!IPS_EventExists($InstanceId)) {
    		IPS_LogMessage("Neohub", "Ident with name $UpdateTimerName is used for wrong object type");
    	}	
    	if (!($TimerInterval > 0)) {
      	IPS_SetEventCyclic($InstanceId, 0, 0, 0, 0, 1, 1);
      	IPS_SetEventActive($InstanceId, false);
    	} 
    	else {
      	IPS_SetEventCyclic($InstanceId, 0, 0, 0, 0, 1, $TimerInterval);
      	IPS_SetEventActive($InstanceId, true);
    	}
    }


	########## private functions ##########
	/**
	 *		validates the configuration
	 */
	private function validateNeohubConfiguration()
	{
		if ($this->ReadPropertyString("NeohubIP") == "" || $this->ReadPropertyString("NeohubPort") == "" ) {
			$this->SetStatus(104);
		}
		else{
			$this->SetStatus(102);
		}
	}
?>
