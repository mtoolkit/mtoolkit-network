MToolkit - Network
==================

The network module of [MToolkit](https://github.com/mtoolkit/mtoolkit) framework.

## RPC-JSON

JSON-RPC is a remote procedure call protocol encoded in JSON

### Server

Create a RPC-JSON server is simple. This example implements an web service
to sum 2 number:
```php
class RPCJsonWebService extends MRPCJsonWebService
{
    public function __construct(){}
    
    public function sum($data)
    {
        $response = new MRPCJsonResponse();
        $response->setId( $this->getRequest()->getId() );
        $response->setResult( array( 'sum' => $data['a'] + $data['b'] ) );
        $this->setResponse( $response );
    }
}
```

### Client
This is the client of the above example:
```php
$request = new MRPCJsonRequest();
$request->setId(1)
    ->setMethod('sum')
    ->setParams( array('a'=>1, 'b'=>2) );

$client = new MRPCJsonClient($url);
$client->call($request);

$response = $client->getResponse();
```