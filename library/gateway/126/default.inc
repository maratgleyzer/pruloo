<?php

$header = array ("Authorization: Basic ".base64_encode($gateway['gate_user'].":".$gateway['gate_pass']."\n"),"Content-Type: text/xml");

$post = 
"
<?xml version='1.0' encoding='UTF-8'?>
<WIRECARD_BXML xmlns:xsi='http://www.w3.org/1999/XMLSchema-instance' xsi:noNamespaceSchemaLocation='wirecard.xsd'>
    <W_REQUEST>
        <W_JOB>
            <JobID>job 2</JobID>
            <BusinessCaseSignature>".$gateway['gate_acct']."</BusinessCaseSignature>
            <FNC_CC_TRANSACTION>
                <FunctionID>WireCard Test</FunctionID>
                <CC_TRANSACTION>
                    <TransactionID>".$order_data['order_id']."</TransactionID>
                    <Amount>".$order_data['total_sale']."</Amount>
                    <Currency>".$order_data['currency']."</Currency>
                    <CountryCode>".$order_data['bill_country']."</CountryCode>
                    <RECURRING_TRANSACTION>
                        <Type>Single</Type>
                    </RECURRING_TRANSACTION>
                    <CREDIT_CARD_DATA>
                        <CreditCardNumber>".$order_data['card_number']."</CreditCardNumber>
                        <CVC2>".$extra_data['security_code']."</CVC2>
                        <ExpirationYear>20".$order_data['expires_yy']."</ExpirationYear>
                        <ExpirationMonth>".$order_data['expires_mm']."</ExpirationMonth>
                        <CardHolderName>".$order_data['bill_first']." ".$order_data['bill_last']."</CardHolderName>
                    </CREDIT_CARD_DATA>
                    <CONTACT_DATA>
                        <IPAddress>".$order_data['ip_address']."</IPAddress>
                    </CONTACT_DATA>
                    <CORPTRUSTCENTER_DATA>
                        <ADDRESS>
                            <Address1>".$order_data['bill_address']."</Address1>
                            <City>".$order_data['bill_city']."</City>
                            <ZipCode>".$order_data['bill_zip']."</ZipCode>
                            <State>".$order_data['bill_state']."</State>
                            <Country>".$order_data['bill_country']."</Country>
                            <Phone>+000(".$order_data['bill_phone_2'].")".$order_data['bill_phone_3']."-".$order_data['bill_phone_4']."000</Phone>
                            <Email>".$order_data['bill_email']."</Email>
                        </ADDRESS>
                    </CORPTRUSTCENTER_DATA>
                </CC_TRANSACTION>
             </FNC_CC_TRANSACTION>
        </W_JOB>
    </W_REQUEST>
</WIRECARD_BXML>
";

?>