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
    	}
	
	public function ApplyChanges() 
	{
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
		/*
	IPS_LogMessage("Neohub", "Timer läuft");
	*/
	$NeohubInstanceId = $this->InstanceID;
   	$InstanceId = @IPS_GetObjectIDByIdent($UpdateTimerName, $NeohubInstanceId);
	if ($InstanceId && IPS_GetEvent($InstanceId)['EventType'] <> 1) 
	{
		IPS_DeleteEvent($InstanceId);
		$InstanceId = 0;
    	}
	if (!$InstanceId)
	{
		$InstanceId = IPS_CreateEvent(1);
		IPS_SetParent($InstanceId, $this->InstanceID);
		IPS_SetIdent($InstanceId, $UpdateTimerName);
    	}
    	IPS_SetName($InstanceId, $UpdateTimerName);
    	IPS_SetHidden($InstanceId, true);
    	IPS_SetEventScript($InstanceId, "\$InstanceId = {$NeohubInstanceId};\nNeohub_updateNeoStats($NeohubInstanceId);");
    	if (!IPS_EventExists($InstanceId)) 
	{
    		IPS_LogMessage("Neohub", "Ident with name $UpdateTimerName is used for wrong object type");
    	}	
    	if (!($TimerInterval > 0)) 
	{
		IPS_SetEventCyclic($InstanceId, 0, 0, 0, 0, 1, 1);
		IPS_SetEventActive($InstanceId, false);
    	} 
    	else 
	{
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
		return "{0584CFEA-43DD-4DF8-A46E-F3198BA3D29B}";
	}

        ########## public functions ##########
	/**
	 * 	Neohub_updateStateOfNeoStats(int $NeohubInstanceID)
	 *		updates the state of all SmartStat
	 */
	public function updateNeoStats() 
	{
		$NeohubInstanceId = $this->InstanceID;
		$NeoStatInstanceIds = IPS_GetInstanceListByModuleID($this->getNeoStatModuleGuid());
		foreach($NeoStatInstanceIds as $NeoStatInstanceId) {
	    		if(IPS_GetInstance($NeoStatInstanceId)['ConnectionID'] == $NeohubInstanceId) {
	      			$NeoStatUniqueId = IPS_GetProperty($NeoStatInstanceId, "NeoStatName");
				/*
				IPS_LogMessage("$NeoStatUniqueId",$NeoStatUniqueId);
				*/
				$this->updateNeoStat($NeoStatUniqueId,$NeoStatInstanceId);
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
			/* throws error while validate
			$this->SetStatus(101);
			*/
		}
	}
	########## public functions ##########
	/**
	 * 	Neohub_TestConnect()
	 *		updates the state of all SmartStat
	 */
	public function TestConnect() 
	{	
		$NeohubJSON = $this->GetInfo();
		if(!$NeohubJSON == NULL)
		{
			$this->SetStatus(102);
		}
		else
		{
			$this->SetStatus(202);
		}
	}
	
	############### private functions ###########
	/**
	*	Neohub_getStateOfNeoStat(string $NeoHubUniqueId)
	*		retrieve infos for specific NeoStat
	*/
	public function updateNeoStat(string $NeoHubUniqueId, int $NeoStatInstanceId)
	{
		/*
		       			IPS_LogMessage("updateNeoStat",$NeoHubUniqueId);
		*/
		$NeohubJSON = $this->GetInfo();
		
		$NeoStats = $NeohubJSON['devices'];
        	foreach($NeoStats as $NeoStat ) 
		{
        		if($NeoStat["device"] == $NeoHubUniqueId)
        		{
				/*
             			IPS_LogMessage("NeoStat:",$NeoStat["device"]);
               			IPS_LogMessage("NeoStat_temp:",$NeoStat["CURRENT_TEMPERATURE"]);
				*/
				$current_temperature = $NeoStat["CURRENT_TEMPERATURE"];
				$current_set_temperature = $NeoStat["CURRENT_SET_TEMPERATURE"];
				$heating = $NeoStat["HEATING"];
       			}
        	}

		$NeoStatCurrentTemperatureObjectId = IPS_GetObjectIDByIdent("current_temperature", $NeoStatInstanceId);
		$UpdateNeoStatCurrentTemperature = SetValue($NeoStatCurrentTemperatureObjectId, $current_temperature);
		$NeoStatCurrentSetTemperatureObjectId = IPS_GetObjectIDByIdent("current_set_temperature", $NeoStatInstanceId);
		$UpdateNeoStatCurrentSetTemperature = SetValue($NeoStatCurrentSetTemperatureObjectId, $current_set_temperature);
		$NeoStatHeatingObjectId = IPS_GetObjectIDByIdent("heating", $NeoStatInstanceId);
		$UpdateNeoStatHeating = SetValue($NeoStatHeatingObjectId, $heating);
	}
	
	############### public functions ###########
	/**
	*	   Neohub_GetInfo()
	*		retrieve {INFO:0} API Call from NeoHub
	*/
	public function GetInfo()
	{
		$NeohubIP = $this->ReadPropertyString('NeohubIP');
		$NeohubPort = $this->ReadPropertyString('NeohubPort');
		/*
		IPS_LogMessage("Neohub", "IP:".$NeohubIP);
		IPS_LogMessage("Neohub", "Port:".$NeohubPort);
		*/
		$NeohubData='{"INFO":0}'.chr(0);
		$NeohubSocket=@pfsockopen($NeohubIP,$NeohubPort, $errstr, $errno, 5);
		@fputs($NeohubSocket,$NeohubData);
		$NeohubReply=@fgets($NeohubSocket, 64000);
		@fclose($NeohubSocket);
	        $NeohubJSON = str_replace("\u0022","\\\\\"",json_decode( $NeohubReply,JSON_HEX_QUOT));
		return $NeohubJSON;
	}
		############### public functions ###########
	/**
	*	   Neohub_SetTemp()
	*		set temperature
	*/
	public function SetTemp(int $SetTempID, int $SetTemp)
	{
		$NeohubIP = $this->ReadPropertyString('NeohubIP');
		$NeohubPort = $this->ReadPropertyString('NeohubPort');
		$NeohubData='{"SET_TEMP":['.$SetTemp.',"'.IPS_GetProperty($SetTempID,"NeoStatName").'"]}'.chr(0);
		$NeohubSocketSetTemp=@pfsockopen($NeohubIP,$NeohubPort, $errstr, $errno, 5);
		@fputs($NeohubSocketSetTemp,$NeohubData);
		@fclose($NeohubSocketSetTemp);
	}
}
?>
