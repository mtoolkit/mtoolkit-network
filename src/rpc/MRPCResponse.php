<?php
namespace mtoolkit\network\rpc;

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

use mtoolkit\network\rpc\json\MRPCJsonError;

abstract class MRPCResponse
{    
    /**
     * @var array 
     */
    private $result=null;
    
    /**
     * @var int
     */
    private $id=null;
    
    /**
     * @var MRPCJsonError
     */
    private $error=null;

    /**
     * @return array 
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param array $result
     * @return MRPCResponse
     */
    public function setResult( array $result)
    {
        $this->result = $result;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return MRPCResponse
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return MRPCJsonError
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param \MToolkit\Network\RPC\Json\MRPCJsonError $error
     * @return MRPCResponse
     */
    public function setError(MRPCJsonError $error)
    {
        $this->error = $error;
        
        return $this;
    }
}
