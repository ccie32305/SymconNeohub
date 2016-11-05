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
		$this->ConnectParent("{434a3a30-77cc-4a17-ab15-0d1f757d6bbf}"); 
	   	 $this->RegisterPropertyString("NeoStatName","");
		 $this->RegisterVariableFloat("current_temperature", "Current Temperature");
		 $this->RegisterVariableFloat("current_set_temperature", "Current Set Temperature");
		 $this->RegisterVariableBoolean("heating", "Heating", "", 0);
	    /*
	IPS_LogMessage("NeoStat getNeoHubInstanceID", $this->getNeoHubInstanceId());
	*/

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
