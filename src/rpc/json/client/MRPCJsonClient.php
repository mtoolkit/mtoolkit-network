<?php
namespace mtoolkit\network\rpc\json\client;

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

use mtoolkit\network\rpc\json\MRPCJsonRequest;
use mtoolkit\network\rpc\json\MRPCJsonResponse;

class MRPCJsonClient
{
    /**
     * @var MRPCJsonRequest
     */
    private $request;

    /**
     * @var MRPCJsonResponse
     */
    private $response;

    /**
     * @var string
     */
    private $url;

    public function __construct( $url = null )
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return \MToolkit\Network\RPC\Json\Client\MRPCJsonClient
     */
    public function setUrl( $url )
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    private function generateId()
    {
        $chars = array_merge( range( 'A', 'Z' ), range( 'a', 'z' ), range( 0, 9 ) );
        $id = '';

        for( $c = 0; $c < 16; ++$c )
        {
            $id .= $chars[mt_rand( 0, count( $chars ) - 1 )];
        }

        return $id;
    }

    public function call( MRPCJsonRequest $request )
    {
        $this->request = $request;
        if( is_null( $this->generateId() ) )
        {
            $this->request->setId( $this->generateId() );
        }

        $jsonRequest = json_encode( $this->request->toArray() );

        $ctx = stream_context_create( array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/json\r\n',
                'content' => $jsonRequest
            )
        ) );
        $jsonResponse = file_get_contents( $this->url, false, $ctx );

        if( $jsonResponse === false )
        {
            throw new MRPCJsonClientException( 'file_get_contents failed', -32603 );
        }

        $response = json_decode( $jsonResponse, true );

        if( $response === null )
        {
            throw new MRPCJsonClientException( 'JSON cannot be decoded', -32603 );
        }

        if( $response['id'] != $request->getId() )
        {
            throw new MRPCJsonClientException( 'Mismatched JSON-RPC IDs', -32603 );
        }

        if( isset($response['result']) === false )
        {
            throw new MRPCJsonClientException( 'Invalid JSON-RPC response', -32603 );
        }

        $this->response = MRPCJsonResponse::fromArray( $response );
    }

    /**
     * @return MRPCJsonRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest( MRPCJsonRequest $request )
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return MRPCJsonResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse( MRPCJsonResponse $response )
    {
        $this->response = $response;
        return $this;
    }
}
