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

class MHttpHeaders
{
    private $headers = array();

    public function add( $key, $value )
    {
        $this->headers[$key] = $value;
    }

    public function count()
    {
        return count( $this->headers );
    }

    public function getValue( $i )
    {
        $keys = array_keys( $this->headers );
        $key = $keys[$i];

        return $this->headers[$key];
    }

    public function getKey( $i )
    {
        $keys = array_keys( $this->headers );
        $key = $keys[$i];

        return $key;
    }

    public function toArray()
    {
        return $this->headers;
    }
    
    public function __toString()
    {
        $toReturn='';
        
        foreach( $this->headers as $key => $value )
        {
            $toReturn.=sprintf('%s: %s\r\n', $key, $value);
        }
        
        return $toReturn;
    }
}

