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

      }
	
	public function ApplyChanges()
	{
		parent::ApplyChanges();
		/*
		$SmartLockSwitchObjectId = $this->RegisterVariableBoolean("NUKISmartLockSwitch", "NUKI SmartLock", "~Lock", 1);
		$this->EnableAction("NUKISmartLockSwitch");
		$HideSmartLockSwitchState = $this->ReadPropertyBoolean("HideSmartLockSwitch");
		IPS_SetHidden($SmartLockSwitchObjectId, $HideSmartLockSwitchState);
		
		$this->RegisterVariableString("NUKISmatLockStatus","NUKI SmartLock Status", "", 2);
		*/
		$this->SetStatus(102);
		
	}

}
?>
