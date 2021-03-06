<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class CustomerTest extends TestCase
{

    private $_customerProperties = [
        'cname' => 'Plesk',
        'pname' => 'John Smith',
        'login' => 'john-unit-test',
        'passwd' => 'simple-password',
        'email' => 'john@smith.com',
        'external-id' => 'link:12345',
        'description' => 'Good guy',
    ];

    public function testCreate()
    {
        $customer = static::$_client->customer()->create($this->_customerProperties);
        $this->assertInternalType('integer', $customer->id);
        $this->assertGreaterThan(0, $customer->id);

        static::$_client->customer()->delete('id', $customer->id);
    }

    public function testDelete()
    {
        $customer = static::$_client->customer()->create($this->_customerProperties);
        $result = static::$_client->customer()->delete('id', $customer->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $customer = static::$_client->customer()->create($this->_customerProperties);
        $customerInfo = static::$_client->customer()->get('id', $customer->id);
        $this->assertEquals('Plesk', $customerInfo->company);
        $this->assertEquals('John Smith', $customerInfo->personalName);
        $this->assertEquals('john-unit-test', $customerInfo->login);
        $this->assertEquals('john@smith.com', $customerInfo->email);
        $this->assertEquals('Good guy', $customerInfo->description);
        $this->assertEquals('link:12345', $customerInfo->externalId);

        static::$_client->customer()->delete('id', $customer->id);
    }

    public function testGetAll()
    {
        static::$_client->customer()->create([
            'pname' => 'John Smith',
            'login' => 'customer-a',
            'passwd' => 'simple-password',
        ]);
        static::$_client->customer()->create([
            'pname' => 'Mike Black',
            'login' => 'customer-b',
            'passwd' => 'simple-password',
        ]);

        $customersInfo = static::$_client->customer()->getAll();
        $this->assertCount(2, $customersInfo);
        $this->assertEquals('John Smith', $customersInfo[0]->personalName);
        $this->assertEquals('customer-a', $customersInfo[0]->login);

        static::$_client->customer()->delete('login', 'customer-a');
        static::$_client->customer()->delete('login', 'customer-b');
    }

}
