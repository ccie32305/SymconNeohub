<?
// NeoStat Smart Thermostat Module
class NeoStat extends IPSModule
{
    public function Create()
    {
//Never delete this line!

        parent::Create();
	
		//These lines are parsed on Symcon Startup or Instance creation
        //You cannot use variables here. Just static values.
		$this->ConnectParent("{434A3A30-77CC-4A17-AB15-0D1F757D6BBF}"); 
	   	 $this->RegisterPropertyString("NeoStatName","");
		 $this->RegisterVariableFloat("current_temperature", "Current Temperature");
		 $this->RegisterVariableFloat("current_set_temperature", "Current Set Temperature");
		 $this->RegisterVariableBoolean("heating", "Heating", "", 0);
	    /*
	IPS_LogMessage("NeoStat getNeoHubInstanceID", $this->getNeoHubInstanceId());
	*/
	if (!IPS_VariableProfileExists("Neohub_Heating"))
	{
   		 IPS_CreateVariableProfile("Neohub_Heating", 0);
		IPS_SetVariableCustomProfile(@IPS_GetVariableIDByName("heating", $this->InstanceID), "Neohub_Heating");
	}
	if (!IPS_VariableProfileExists("Neohub_Temp"))
	{
   		 IPS_CreateVariableProfile("Neohub_Temp", 0);
	}
	if (!IPS_VariableProfileExists("Neohub_SetTemp"))
	{
   		 IPS_CreateVariableProfile("Neohub_SetTemp", 0);
	}
      }
	
	public function ApplyChanges()
	{
		//Never delete this line!
		parent::ApplyChanges();
		
	}
	
	public function AvailabilityCheck()
	{
		//Never delete this line!
		
	}
	public function SetTemp(int $setTemp)
	{
		@Neohub_SetTemp(IPS_GetInstance($this->InstanceID)['ConnectionID'],$this->InstanceID,$setTemp);
	}

		/**
	 *		gets the instance id of the related bridge
	 */
	protected function getNeoHubInstanceId() 
	{
    		$NeoHubInstanceId = IPS_GetInstance($this->InstanceID);
    		return ($NeoHubInstanceId['ConnectionID'] > 0) ? $NeoHubInstanceId['ConnectionID'] : false;
  	}
	
	public function RequestAction($Ident, $Value) 
	{
 
	    switch($Ident) {
		case "TestVariable":
		    //Hier würde normalerweise eine Aktion z.B. das Schalten ausgeführt werden
		    //Ausgaben über 'echo' werden an die Visualisierung zurückgeleitet

		    //Neuen Wert in die Statusvariable schreiben
		    SetValue($this->GetIDForIdent($Ident), $Value);
		    break;
		default:
		    throw new Exception("Invalid Ident");
	    }
	}
}
?>
