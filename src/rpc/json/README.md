# RPC JSON

## What's RPC
From Wikipedia: "_In computer science, a remote procedure call (RPC) is client/server system in which a computer program causes a subroutine or procedure to execute in another address space (commonly on another computer on a shared network) without the programmer explicitly coding the details for this remote interaction.[1] That is, the programmer writes essentially the same code whether the subroutine is local to the executing program, or remote. When the software in question uses object-oriented principles, RPC might be called remote invocation or remote method invocation (RMI)._"

In other words, RPC is a standard which also permits to call a back-end method over the network.

## RPC using JSON
If you want using JSON with the RPC standard you have to use the right sintax to create a request and to parse the response.
A simple request could be the following:
```JSON
{"jsonrpc": "2.0", "method": "subtract", "params": [42, 23], "id": 1}
```

And the response could be:
```JSON
{"jsonrpc": "2.0", "result": 19, "id": 1}
```

As you can see, the request executes a the method called "subtract" with the parameters 42 and 23 and, in the response, the result of the substraction is 19.
For a complete specification about the request and response, we suggest you to see https://en.wikipedia.org/wiki/JSON-RPC

## How do a RPC JSON web service with MToolkit.

If you have configured in the right way your project, it's simple to write a RPC JSON web service with MToolkit.
Following, we explain how to do this with an example:

```php
<?php

namespace webservices;

require_once '../../Settings.php';

use MToolkit\Network\RPC\Json\Server\MRPCJsonWebService;
use MToolkit\Network\RPC\Json\MRPCJsonResponse;

class SettingsWebService extends MRPCJsonWebService
{

    public function subtract( $params )
    {
        $a = $params[0];
        $b = $params[1];
        $result = $a - $b;

        $response = new MRPCJsonResponse();
        $response->setId( $this->getResponse()->getId() );
        $response->setResult( array("result" => $result) );

        $this->setResponse( $response );
    }

}

```

That's it.

You can call a RPC JSON web service using a Javascript library as https://github.com/datagraph/jquery-jsonrpc or using MToolkit.

