<?php

/*
 * Copyright (c) 2014 Palo Alto Networks, Inc. <info@paloaltonetworks.com>
 * Author: Christophe Painchaud cpainchaud _AT_ paloaltonetworks.com
 *
 * Permission to use, copy, modify, and distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.

 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
 * ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
 * OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
*/

/**
 * Class IPsecTunnelStore
 * @property $o IPsecTunnel[]
 */
class IPsecTunnelStore extends ObjStore
{

    /**
     * @var null|string[]|DOMElement
     */
    public $xmlroot=null;

    /**
     * @var null|PANConf
     */
    public $owner=null;

    public function IPsecTunnelStore($name, $owner)
    {
        $this->name = $name;
        $this->owner = $owner;
    }

    public function load_from_domxml($xml)
    {
        $this->xmlroot = $xml;

        foreach( $xml->childNodes as $node )
        {
            if( $node->nodeType != 1 ) continue;

            $ns = new IPsecTunnel('tmp',$this);
            $ns->load_from_domxml($node);
            //print $this->toString()." : new IPsec tunnel '".$ns->name()."' found\n";

            $this->o[] = $ns;
        }
    }

    /**
     * @param Array[] $xml
     */
    public function load_from_xml( &$xml)
    {
        $this->xmlroot = $xml;

        foreach( $xml['children'] as &$children )
        {
            $ns = new IPsecTunnel('tmp',$this);
            $ns->load_from_xml($children);
            //print $this->toString()." : new IPsec tunnel '".$ns->name()."' found\n";

            $this->o[] = $ns;
        }
    }

    /**
     * @return IPsecTunnel[]
     */
    public function tunnels()
    {
        return $this->o;
    }


} 