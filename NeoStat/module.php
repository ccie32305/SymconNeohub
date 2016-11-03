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
		
		 $this->RegisterVariableFloat("current_temperature", "Current Temperature");
		 $this->RegisterVariableFloat("current_set_temperature", "Current Set Temperature");
		 $this->RegisterVariableBoolean("heating", "Heating");
      }
	
	public function ApplyChanges()
	{
		parent::ApplyChanges();
		/*
		$this->ConnectParent("{B41AE29B-39C1-4144-878F-94C0F7EEC725}");
		$SmartLockSwitchObjectId = $this->RegisterVariableBoolean("NUKISmartLockSwitch", "NUKI SmartLock", "~Lock", 1);
		$this->EnableAction("NUKISmartLockSwitch");
		$HideSmartLockSwitchState = $this->ReadPropertyBoolean("HideSmartLockSwitch");
		IPS_SetHidden($SmartLockSwitchObjectId, $HideSmartLockSwitchState);
		
		$this->RegisterVariableString("NUKISmatLockStatus","NUKI SmartLock Status", "", 2);
		$this->SetStatus(102);
		*/
	}

}
?>
