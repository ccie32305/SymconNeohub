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
		$this->ConnectParent("{0584cfea-43dd-4df8-a46e-f3198ba3d29b}"); 
	   	 $this->RegisterPropertyString("NeoStatName","");
		 $this->RegisterVariableFloat("current_temperature", "Current Temperature");
		 $this->RegisterVariableFloat("current_set_temperature", "Current Set Temperature");
		 $this->RegisterVariableBoolean("heating", "heating", "", 0);

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

}
?>
