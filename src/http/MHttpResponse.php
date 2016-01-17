<?php
namespace mtoolkit\network\http;

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

use mtoolkit\core\MObject;

class MHttpResponse extends MObject
{
    /**
     * @var MHttpHeaders
     */
    private $headers;
    
    /**
     * @var string
     */
    private $body=null;
    
    /**
     * @var string
     */
    private $version=null;
    
    public function __construct( MObject $parent = null )
    {
        parent::__construct( $parent );
        
        $this->headers=new MHttpHeaders();
    }
    
    /**
     * @return MHttpHeaders
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody( $body )
    {
        $this->body = $body;
        return $this;
    }
    
    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion( $version )
    {
        $this->version = $version;
        return $this;
    }


}
