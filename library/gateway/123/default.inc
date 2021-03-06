POST /v16/api.asmx HTTP/1.1
Host: api.vitalpay.com
Content-Type: application/soap+xml; charset=utf-8
Content-Length: length

<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Header>
    <AuthHeader xmlns="http://api.evenpay.com/api/v14/">
      <Username>string</Username>
      <Password>string</Password>
    </AuthHeader>
  </soap12:Header>
  <soap12:Body>
    <PaymentImmediate xmlns="http://api.evenpay.com/api/v14/">
      <paymentToProcess>
        <PaymentId>long</PaymentId>
        <PaymentGuid>string</PaymentGuid>
        <TransactionType>int</TransactionType>
        <User>
          <UserId>string</UserId>
          <FirstName>string</FirstName>
          <MiddleName>string</MiddleName>
          <LastName>string</LastName>
          <CompanyName>string</CompanyName>
          <BillingAddress>
            <AddressId>string</AddressId>
            <AddressNickname>string</AddressNickname>
            <RecipientName>string</RecipientName>
            <CareOf>string</CareOf>
            <Address1>string</Address1>
            <Address2>string</Address2>
            <City>string</City>
            <StateProvince>string</StateProvince>
            <PostalCode>string</PostalCode>
            <Country>string</Country>
          </BillingAddress>
          <HomeAddress>
            <AddressId>string</AddressId>
            <AddressNickname>string</AddressNickname>
            <RecipientName>string</RecipientName>
            <CareOf>string</CareOf>
            <Address1>string</Address1>
            <Address2>string</Address2>
            <City>string</City>
            <StateProvince>string</StateProvince>
            <PostalCode>string</PostalCode>
            <Country>string</Country>
          </HomeAddress>
          <ShippingAddress>
            <AddressId>string</AddressId>
            <AddressNickname>string</AddressNickname>
            <RecipientName>string</RecipientName>
            <CareOf>string</CareOf>
            <Address1>string</Address1>
            <Address2>string</Address2>
            <City>string</City>
            <StateProvince>string</StateProvince>
            <PostalCode>string</PostalCode>
            <Country>string</Country>
          </ShippingAddress>
          <OtherAddresses>
            <Address xsi:nil="true" />
            <Address xsi:nil="true" />
          </OtherAddresses>
          <PhoneHome>string</PhoneHome>
          <PhoneCell>string</PhoneCell>
          <PhoneFax>string</PhoneFax>
          <PhoneOthers>
            <string>string</string>
            <string>string</string>
          </PhoneOthers>
          <MerchantUserId>string</MerchantUserId>
          <Identity>
            <HashBlockA>string</HashBlockA>
            <HashBlockB>string</HashBlockB>
            <HashBlockC>string</HashBlockC>
            <HashMerchant>string</HashMerchant>
          </Identity>
          <Email>string</Email>
        </User>
        <MerchantId>string</MerchantId>
        <CardholderName>string</CardholderName>
        <CompanyName>string</CompanyName>
        <CCNumber>long</CCNumber>
        <CCLastFour>int</CCLastFour>
        <ExpirationMonth>int</ExpirationMonth>
        <ExpirationDay>int</ExpirationDay>
        <ExpirationYear>int</ExpirationYear>
        <StartMonth>int</StartMonth>
        <StartDay>int</StartDay>
        <StartYear>int</StartYear>
        <CardSecurityCode>int</CardSecurityCode>
        <PayDataCrypto>string</PayDataCrypto>
        <CardBillingAddress>
          <AddressId>string</AddressId>
          <AddressNickname>string</AddressNickname>
          <RecipientName>string</RecipientName>
          <CareOf>string</CareOf>
          <Address1>string</Address1>
          <Address2>string</Address2>
          <City>string</City>
          <StateProvince>string</StateProvince>
          <PostalCode>string</PostalCode>
          <Country>string</Country>
        </CardBillingAddress>
        <RsaInUse>boolean</RsaInUse>
        <Currency>string</Currency>
        <MerchantTracking>string</MerchantTracking>
        <OfflineAuthorization>string</OfflineAuthorization>
        <Origin>int</Origin>
        <EmailPurchaser>string</EmailPurchaser>
        <EmailShipTo>string</EmailShipTo>
        <PhonePrimary>string</PhonePrimary>
        <UDF1>string</UDF1>
        <UDF2>string</UDF2>
        <ParentPaymentId>long</ParentPaymentId>
        <OperatorId>string</OperatorId>
        <AffiliateId>string</AffiliateId>
        <CampaignId>string</CampaignId>
        <TrackingGroup>string</TrackingGroup>
        <TrackingCollection>
          <CollectionItem>
            <Name>string</Name>
            <Value>string</Value>
            <ValueType>string</ValueType>
          </CollectionItem>
          <CollectionItem>
            <Name>string</Name>
            <Value>string</Value>
            <ValueType>string</ValueType>
          </CollectionItem>
        </TrackingCollection>
        <PaymentItems>
          <PaymentItem>
            <ItemId>string</ItemId>
            <StockId>string</StockId>
            <CommonName>string</CommonName>
            <Quantity>int</Quantity>
            <Amount>decimal</Amount>
            <Tax xsi:nil="true" />
            <ServiceSicCode>string</ServiceSicCode>
            <Currency>string</Currency>
            <TrackingCollection xsi:nil="true" />
          </PaymentItem>
          <PaymentItem>
            <ItemId>string</ItemId>
            <StockId>string</StockId>
            <CommonName>string</CommonName>
            <Quantity>int</Quantity>
            <Amount>decimal</Amount>
            <Tax xsi:nil="true" />
            <ServiceSicCode>string</ServiceSicCode>
            <Currency>string</Currency>
            <TrackingCollection xsi:nil="true" />
          </PaymentItem>
        </PaymentItems>
        <RetrieveMissingInformation>boolean</RetrieveMissingInformation>
        <RetrieveMarketingData>boolean</RetrieveMarketingData>
        <BankName>string</BankName>
        <PaymentResult>
          <ResultId>string</ResultId>
          <PaymentId>string</PaymentId>
          <PaymentGuid>string</PaymentGuid>
          <Amount>decimal</Amount>
          <UserId>string</UserId>
          <TimeStampUtc>dateTime</TimeStampUtc>
          <Result>int</Result>
          <DeclineCode>string</DeclineCode>
          <UserFriendlyResponse>string</UserFriendlyResponse>
          <InteralAdvice>string</InteralAdvice>
          <Captured>boolean</Captured>
          <ReferencedAmoutRemaining>decimal</ReferencedAmoutRemaining>
          <RepeatedRequest>boolean</RepeatedRequest>
        </PaymentResult>
        <AchRouting>string</AchRouting>
        <AchAccount>string</AchAccount>
        <IssueNumber>long</IssueNumber>
        <PerformDeclineRecovery>boolean</PerformDeclineRecovery>
        <Amount>decimal</Amount>
        <AmountTax>decimal</AmountTax>
        <TimeStampUtc>dateTime</TimeStampUtc>
        <LegacyDetail>
          <LogId>long</LogId>
          <AssociateId>int</AssociateId>
          <Processor>int</Processor>
          <LegacyResult>int</LegacyResult>
          <Code>string</Code>
          <Description>string</Description>
          <ProcessorRef>string</ProcessorRef>
          <LegacyUpdateDate>dateTime</LegacyUpdateDate>
        </LegacyDetail>
        <ThreeDAuth>string</ThreeDAuth>
        <ThreeDCavv>string</ThreeDCavv>
        <ThreeDXid>string</ThreeDXid>
        <ThreeDEci>string</ThreeDEci>
        <IpAddress>string</IpAddress>
        <TrackingToNetworkOverride>string</TrackingToNetworkOverride>
        <EciFlag>string</EciFlag>
      </paymentToProcess>
    </PaymentImmediate>
  </soap12:Body>
</soap12:Envelope>