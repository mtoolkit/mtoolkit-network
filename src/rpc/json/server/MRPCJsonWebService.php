<?php
namespace mtoolkit\network\rpc\json\server;

/*
 * This file is part of MToolkit.
 *
 * MToolkit is free software: you can redistribute it and/or modify
 * it under the terms of the LGNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * MToolkit is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * LGNU Lesser General Public License for more details.
 *
 * You should have received a copy of the LGNU Lesser General Public License
 * along with MToolkit.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @author  Michele Pagnin
 */
use mtoolkit\controller\MAbstractController;
use mtoolkit\core\enum\ContentType;
use mtoolkit\core\MObject;
use mtoolkit\network\rpc\json\MRPCJsonError;
use mtoolkit\network\rpc\json\MRPCJsonRequest;
use mtoolkit\network\rpc\json\MRPCJsonResponse;

/**
 * <b>MRPCJsonWebService</b> is the base class for the web service classes.
 * A tipical implementation of web service RPC in JSON is:
 *
 * class DummyWS extends MRPCJsonWebService
 * {
 *      public function __construct()
 *      {
 *          parent::__construct();
 *      }
 *
 *      public function add( $params )
 *      {
 *          $a=(int)$params['a'];
 *          $b=(int)$params['b'];
 *
 *          $response=new MRPCJsonResponse();
 *
 *          // The result must be an array.
 *          $response->setResult( array( $a+$b ) );
 *
 *          $this->setResponse( $response );
 *      }
 * }
 *
 * An example of JSON request could be:
 * <pre>
 *      {"jsonrpc": "2.0", "method": "add", "params": { "a": 2, "b":3 }, "id": 1}
 * </pre>
 */
abstract class MRPCJsonWebService extends MAbstractController
{
    /**
     * The response from the web service.
     *
     * @var MRPCJsonResponse
     */
    private $response = null;

    /**
     * @var string
     */
    private $className = '';

    /**
     * The request received by the web service.
     *
     * @var MRPCJsonRequest
     */
    private $request = null;

    public function __construct( MObject $parent = null )
    {
        parent::__construct( $parent );
        $this->init();

        $this->response = new MRPCJsonResponse();
    }

    /**
     * Mehtod called in the construct.
     * It permits to initialize the object in the derived classes.
     */
    public abstract function init();

    /**
     * @return MRPCJsonResponse
     */
    public function &getResponse()
    {
        return $this->response;
    }

    /**
     * @return MRPCJsonRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set the response of the web service.
     * It must be valorizated by derived classes.
     *
     * @param MRPCJsonResponse $response
     */
    protected function setResponse( MRPCJsonResponse $response )
    {
        $this->response = $response;
    }

    /**
     * Reads the request and run the web method.
     *
     * @param $className
     * @throws MRPCJsonServerException
     */
    public function execute( $className )
    {
        $this->className = $className;
        
        // Parse the request
        $rawRequest = file_get_contents( 'php://input' );
        /* @var $request array */
        $request = json_decode( $rawRequest, true );

        // Is valid request?
        if( $request == false )
        {
            throw new MRPCJsonServerException( sprintf( 'Invalid body (%s).', $rawRequest ) );
        }

        // Does the request respect the 2.0 specification?
        if( $request['jsonrpc'] != '2.0' )
        {
            throw new MRPCJsonServerException( sprintf( 'The request does not respect the 2.0 specification.' ) );
        }

        // Set the request properties
        $this->request = new MRPCJsonRequest();
        $this->request
            ->setMethod( $request['method'] )
            ->setParams( $request['params'] )
            ->setId( $request['id'] );

        // Call the procedure/member
        $callResponse = call_user_func(
            array( $this, $this->request->getMethod() )
            , $this->request->getParams() );

        // Does the call fail?
        if( $callResponse === false )
        {
            throw new MRPCJsonServerException( 'Invalid method name.' );
        }
    }

    /**
     * Run the instance of the web service.
     */
    public static function autorun()
    {
        /* @var $classes string[] */
        $classes = array_reverse( get_declared_classes() );

        foreach( $classes as $class )
        {
            $type = new \ReflectionClass( $class );
            $abstract = $type->isAbstract();

            if( is_subclass_of( $class, MRPCJsonWebService::class ) === false || $abstract === true )
            {
                continue;
            }

            /* @var $webService MRPCJsonWebService */
            $webService = new $class();

            // If the definitions are requested
            if( $_SERVER['QUERY_STRING'] == 'definition' )
            {
                $definition = new MRPCJsonWebServiceDefinition( $class );

                $webService->getHttpResponse()->setContentType( ContentType::TEXT_HTML );
                $webService->getHttpResponse()->setOutput( (string)$definition );
            }
            // Normal web service execution
            else
            {
                $webService->getHttpResponse()->setContentType( ContentType::APPLICATION_JSON );

                try
                {
                    $webService->execute( $class );
                    $webService->getResponse()->setId( $webService->getRequest()->getId() );
                } catch( MRPCJsonServerException $ex )
                {
                    $error = new MRPCJsonError();
                    $error->setCode( -1 );
                    $error->setMessage( $ex->getMessage() );

                    $webService->response = new MRPCJsonResponse();
                    $webService->response->setError( $error );
                }

                $webService->getHttpResponse()->setOutput( $webService->getResponse()->toJSON() );
            }

            return $webService;
        }

        return null;
    }
}

register_shutdown_function( function ()
{
    // Don't run the controller in cli mode
    if( php_sapi_name() == 'cli' )
    {
        return;
    }

    // Run the controller
    /* @var $webService MRPCJsonWebService */
    $webService = MRPCJsonWebService::autorun();

    if( $webService != null )
    {
        header( $webService->getHttpResponse()->getContentType() );
        echo $webService->getHttpResponse()->getOutput();
    }
} );
