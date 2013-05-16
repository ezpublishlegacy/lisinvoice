<?php
/**
 * @copyright Copyright (C) 2009 land in sicht AG All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 */

class lisInvoiceGateway extends eZPaymentGateway
{
    const GATEWAY_STRING = "lisinvoice";    

    function execute( $process, $event )
    {
        $processParams = $process->attribute( 'parameter_list' );
        $processID = $process->attribute( 'id' );
        $orderID = $processParams['order_id'];
        $order = eZOrder::fetch( $orderID );
        
        $lisinvoiceINI = eZINI::instance( 'lisinvoice.ini' );
        
        // OrderITem speichern um später zu wissen, mit welchen Gateway gezahlt wurde
        $orderItem = new eZOrderItem( array( 'order_id' => $orderID,
                                             'description' => $lisinvoiceINI->variable("WordingSettings","Backend"),
                                             'price' => 0,
                                             'type' => lisInvoiceGateway::GATEWAY_STRING)
                                              );
        $orderItem->store();
        
        
        return eZWorkflowType::STATUS_ACCEPTED;
    }
    
       
}

$lisinvoiceINI = eZINI::instance( 'lisinvoice.ini' );
eZPaymentGatewayType::registerGateway( lisInvoiceGateway::GATEWAY_STRING, "lisinvoicegateway", $lisinvoiceINI->variable("WordingSettings","Frontend") );
?>