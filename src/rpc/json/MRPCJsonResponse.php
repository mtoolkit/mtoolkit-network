<?php
namespace mtoolkit\network\rpc\json;

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
use mtoolkit\network\rpc\MRPCResponse;


/**
 * Examples:
 * <ul>
 * <li>{"jsonrpc": "2.0", "result": 19, "id": 1}</li>
 * <li>{"jsonrpc": "2.0", "error": {"code": -32601, "message": "Procedure not found."}, "id": 10} </li>
 * </ul>
 */
class MRPCJsonResponse extends MRPCResponse
{
    private $options = 0;

    /**
     * Returns the JSON options.<br>
     * <br>
     * Bitmask consisting of JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP,
     * JSON_HEX_APOS, JSON_NUMERIC_CHECK, JSON_PRETTY_PRINT,
     * JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT, JSON_PRESERVE_ZERO_FRACTION,
     * JSON_UNESCAPED_UNICODE, JSON_PARTIAL_OUTPUT_ON_ERROR. <br>
     * The behaviour of these constants is described on the
     * <a href="http://php.net/manual/en/json.constants.php">JSON constants</a>
     * page.
     *
     * @return int
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets the JSON options.<br>
     * <br>
     * Bitmask consisting of JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP,
     * JSON_HEX_APOS, JSON_NUMERIC_CHECK, JSON_PRETTY_PRINT,
     * JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT, JSON_PRESERVE_ZERO_FRACTION,
     * JSON_UNESCAPED_UNICODE, JSON_PARTIAL_OUTPUT_ON_ERROR. <br>
     * The behaviour of these constants is described on the
     * <a href="http://php.net/manual/en/json.constants.php">JSON constants</a>
     * page.
     *
     * @param int $options
     * @return \MToolkit\Network\RPC\Json\MRPCJsonResponse
     */
    public function setOptions( $options )
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = array(
            'jsonrpc' => MRPCJson::VERSION
        , 'result' => $this->getResult()
        , 'id' => $this->getId()
        );

        if( $this->getError() != null )
        {
            $array['error'] = $this->getError()->toArray();
        }

        return $array;
    }

    /**
     * @return string
     */
    public function toJSON()
    {
        return json_encode( $this->toArray() );
    }

    /**
     * @param array $json
     * @return MRPCJsonResponse
     */
    public static function fromArray( array $json )
    {
        $response = new MRPCJsonResponse();

        if( isset($json["error"]) )
        {
            $response->setError( MRPCJsonError::fromArray( $json["error"] ) );
        }

        $response->setId( $json["id"] );
        $response->setResult( $json["result"] );
        return $response;
    }

}
