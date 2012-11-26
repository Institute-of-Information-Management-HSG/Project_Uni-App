<?php
/**
 * SOAP Komponenten für Stundenplanmodul
 *
 * @author fabio.camichel@student.unisg.ch
 * @version 2012-07-18
 */

class TimetableAPIModule extends APIModule {

    protected $id = 'timetable';
    protected $vmin = 1;
    protected $vmax = 1;
    
    protected function initializeForCommand() {
		// SOAP functions
		//$dev = new SoapClient('http://integration.development.unisg.ch/Unisg.DataServices.Events/Lectures/V20100101/Services/InfosystemService.svc?wsdl');
		//$stag = new SoapClient('http://integration.staging.unisg.ch/Unisg.DataServices.Events/Lectures/V20100101/Services/InfosystemService.svc?wsdl');
		//$prod = new SoapClient('http://integration.unisg.ch/Unisg.DataServices.Events/Lectures/V20100101/Services/InfosystemService.svc?wsdl');
    }
	
}
?>