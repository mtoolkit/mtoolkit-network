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
use mtoolkit\network\rpc\MRPCRequest;


/**
 * This class implement the standard RPC 2.0 using Json.
 * 
 * Example
 * {"jsonrpc": "2.0", "method": "subtract", "params": [42, 23], "id": 1} 
 */
class MRPCJsonRequest extends MRPCRequest
{
    /**
     * Return the class as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $array=array(
            'jsonrpc' => MRPCJson::VERSION
            , 'method' => $this->getMethod()
            , 'params' => $this->getParams()
            , 'id' => $this->getId()
        );
        
        return $array;
    }

    /**
     * Initializes a {@link MRPCJsonRequest} object from an <i>$json</i> array.
     *
     * @param array $json
     * @return MRPCJsonRequest
     */
    public static function fromArray(array $json)
    {
        $request=new MRPCJsonRequest();
        
        $request->setId($json["id"]);
        $request->setId($json["method"]);
        $request->setId($json["params"]);
        
        return $request;
    }
}
