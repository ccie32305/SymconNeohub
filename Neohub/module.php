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
		
        $this->RegisterPropertyString("Host", "");
		$this->RegisterPropertyInteger("PortDoorbell", 80);

    }
?>
