<?
// Neohub Smart Thermostat Module
class Neohub extends IPSModule
{
	private $NeohubIP = "";
	private $NeohubPort = "";
	private $NeohubUpdateInterval = "";
	    
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
		$this->registerUpdateTimer("Update", $this->ReadPropertyInteger("NeohubUpdateInterval"));
		$this->validateNeohubConfiguration();
	}
	########## private functions ##########
	/**
	 *		register the update timer
	 */

protected function registerUpdateTimer(string $UpdateTimerName, int $TimerInterval)
	{
	IPS_LogMessage("Neohub", "Timer läuft");
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
    	IPS_SetEventScript($InstanceId, "\$InstanceId = {$NeohubInstanceId};\nNeohub_updateStateOfSmartLocks($NeohubInstanceId);");
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
	
        ########## public functions ##########
       /**
	*		gets the guid of the smartlock module
	*/
	private function getNeoStatModuleGuid()
	{
		return "{0584cfea-43dd-4df8-a46e-f3198ba3d29b}";
	}

        ########## public functions ##########
	/**
	 * 	Neohub_updateStateOfNeoStats(int $NeohubInstanceID)
	 *		updates the state of all SmartStat
	 */
	public function updateStateOfSmartLocks() 
	{
		$NeohubInstanceId = $this->InstanceID;
		$NeoStatInstanceIds = IPS_GetInstanceListByModuleID($this->getNeoStatModuleGuid());
		foreach($NeoStatInstanceIds as $NeoStatInstanceId) {
	    		if(IPS_GetInstance($NeoStatInstanceId)['ConnectionID'] == $NeohubInstanceId) {
	      			$NeoStatUniqueId = IPS_GetProperty($NeoStatInstanceId, "NeoStatName");
				$NeoStatData = $this->getStateOfNeoStat($NeoStatUniqueId);
				$current_temperature = $NeoStatkData["current_temperature"];
				$current_set_temperature = $NeoStatData["current_set_temperature"];
				$NeoStatCurrentTemperatureObjectId = IPS_GetObjectIDByIdent("NeoStat_CurrentTemperature", $NeoStatInstanceId);
				$UpdateNeoStatCurrentTemperature = SetValue($NeoStatCurrentTemperatureObjectId, $current_temperature);
				$NeoStatCurrentSetTemperatureObjectId = IPS_GetObjectIDByIdent("NeoStat_CurrentSetTemperature", $NeoStatInstanceId);
				$UpdateNeoStatCurrentSetTemperature = SetValue($NeoStatCurrentSetTemperatureObjectId, $current_set_temperature);
			}
		}
  	}

	########## private functions ##########
	/**
	 *		validates the configuration
	 */
	private function validateNeohubConfiguration()
	{
		if ($this->ReadPropertyString("NeohubIP") == "" || $this->ReadPropertyString("NeohubPort") == "" ) {
			$this->SetStatus(201);
		}
		else
		{
			/*
			$this->SetStatus(101);
			*/
		}
	}
	        ########## public functions ##########
	/**
	 * 	Neohub_TestConnect(string $NeohubIP,integer $NeohubPort)
	 *		updates the state of all SmartStat
	 */
	public function TestConnect() 
	{	
	
	$NeohubIP = $this->ReadPropertyString('NeohubIP');
			$NeohubPort = $this->ReadPropertyString('NeohubPort');
			
			IPS_LogMessage("Neohub", "IP:".$NeohubIP);
				IPS_LogMessage("Neohub", "Port:".$NeohubPort);
	$NeohubData='{"INFO":0}'.chr(0);
	$NeohubSocket=pfsockopen($NeohubIP,$NeohubPort, $errstr, $errno, 5); 
	fputs($NeohubSocket,$NeohubData);
	$NeohubJSON=fgets($NeohubSocket, 64000); 
	fclose($NeohubSocket);
	$NeohubJSON = str_replace("\u0022","\\\\\"",json_decode( $NeohubJSON,JSON_HEX_QUOT)); 
	if(!empty(json_decode($NeohubJSON)))
		{
			$this->SetStatus(102);
		}
			else
		{
			$this->SetStatus(202);
		}
	  }
}
?>
