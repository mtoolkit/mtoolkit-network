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

use mtoolkit\core\MString;

class MRPCJsonWebServiceDefinition
{
    /**
     * @var string
     */
    private $className = "";

    /**
     * @var array
     */
    private $methodDefinitionList = array();

    public function __construct( $className )
    {
        $this->className = $className;

        $this->initMethodDefinitionList();
    }

    /**
     * Sets the array of the definitions of the methods.
     */
    private function initMethodDefinitionList()
    {
        $class = new \ReflectionClass( $this->className );
        $methods = $class->getMethods( \ReflectionMethod::IS_PUBLIC );

        foreach( $methods as /* @var $reflect \ReflectionMethod */
                 $reflect )
        {
            if( $reflect->class != $this->className )
            {
                continue;
            }

            $docComment = $reflect->getDocComment();
            $phpDoc = array(
                'name' => $reflect->getName(),
                'definition' => implode( "\n", array_map( 'trim', explode( "\n", $docComment ) ) )
            );

            $this->methodDefinitionList[] = $phpDoc;
        }
    }

    public function __toString()
    {
        $output = new MString();

        $output->append( "<html><body>" );
        $output->append( "<h1>Methods definitions</h1>" );

        foreach( $this->methodDefinitionList as $methodDefinition )
        {
            $output->append( "<h2>" . $methodDefinition["name"] . "</h2>" );
            $output->append( "<pre>" . $methodDefinition["definition"] . "</pre>" );

            $output->append( "<h3>Request example</h3>" );
            $output->append( "<pre>" . '{"jsonrpc": "2.0", "method": "' . $methodDefinition["name"] . '", "params": {"name_1": value_1, "name_2": value_2, ...}, "id": 3}' . "</pre>" );
        }

        $output->append( "</body></html>" );

        return (string)$output;
    }

}
