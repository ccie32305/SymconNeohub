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
		 $this->RegisterVariableBoolean("heating", "heating", "", 0);
	IPS_LogMessage("NeoStat getNeoHubInstanceID", getNeoHubInstanceId());

      }
	
	public function ApplyChanges()
	{
		//Never delete this line!
		parent::ApplyChanges();
		IPS_Log
		
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
}
?>
