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

class Rule
{
	
	use PathableName;
	use centralServiceStoreUser;
	use centralAddressStoreUser;
	
	protected $name = 'temporaryname';
	protected $disabled = false;
	protected $description;
	
	/**
	* @var ZoneRuleContainer
	*/
	public $from = null;
	/**
	* @var ZoneRuleContainer
	*/
	public $to = null;
	/**
	* @var AddressStore
	*/
	public $source;
	/**
	* @var AddressStore
	*/
	public $destination;

	/**
	* @var TagRuleContainer
	*/
	public $tags;

	/**
	* @var ServiceStore
	*/
	public $services;
	
	
	public $owner = null;

    /**
     * @var null|string[]|DOMNode
     */
	public $xmlroot = null;

    /**
     * @var null|string[]|DOMNode
     */
	protected $disabledroot = null;
    /**
     * @var null|string[]|DOMNode
     */
	protected $descroot = null;
	
	/**
	* Returns name of this rule
	* @return string
	*/
	public function name()
	{
		return $this->name;
	}
	
	/**
	*
	* @return bool
	*/
	public function isDisabled()
	{
		return $this->disabled;
	}
	
	/**
	*
	* @return bool
	*/
	public function isEnabled()
	{
		if ($this->disabled)
			return false;
		
		return true;
	}
	
	/**
	* Returns description for this rule
	* @var string
	*/
	public function description()
	{
		return $this->description;
	}
	
	
	/**
	* For developper use only
	*
	*/
	protected function init_from_with_store()
	{
		$this->from = new ZoneRuleContainer($this);
		$this->from->setName('from');	
	}

    /**
     * For developper use only
     */
    protected function load_from()
    {
        if( !PH::$UseDomXML)
        {
            $fromRoot = &searchForName('name', 'from', $this->xmlroot['children']);

            if (!$fromRoot)
            {
                $fromRoot = Array('name' => 'from');
                $this->xmlroot['children'][] = &$fromRoot;
            }
            if (!isset($fromRoot['children']))
                $fromRoot['children'] = array();

            $this->from->load_from_xml($fromRoot);
        }
        else
        {
            $tmp = DH::findFirstElementOrCreate('from', $this->xmlroot);
            $this->from->load_from_domxml($tmp);
        }
    }


    /**
     * For developer use only
     */
    protected function load_to()
    {
        if( !PH::$UseDomXML)
        {
            $toRoot = &searchForName('name', 'to', $this->xmlroot['children']);


            if (!$toRoot)
            {
                $toRoot = Array('name' => 'to');
                $this->xmlroot['children'][] = &$toRoot;
            }
            if (!isset($toRoot['children']))
                $toRoot['children'] = array();

            $this->to->load_from_xml($toRoot);
        }
        else
        {
            $tmp = DH::findFirstElementOrCreate('to', $this->xmlroot);
            $this->to->load_from_domxml($tmp);
        }
    }


    /**
     * For developer use only
     */
    protected function load_tags()
    {
        if( !PH::$UseDomXML)
        {
            $tagRoot = &searchForName('name', 'tag', $this->xmlroot['children']);

            if (!$tagRoot)
            {
                $tagRoot = Array('name' => 'ignme');
                $xml['children'][] = &$tagRoot;
            }
            if (!isset($tagRoot['children']))
                $tagRoot['children'] = Array();

            $this->tags->load_from_xml($tagRoot);
        }
        else
        {
            $tmp = DH::findFirstElementOrCreate('tag', $this->xmlroot);
            $this->tags->load_from_domxml($tmp);
        }
    }


    /**
     * For developper use only
     */
    protected function load_source()
    {
        if( !PH::$UseDomXML)
        {
            $srcRoot = &searchForName('name', 'source', $this->xmlroot['children']);
            if (is_null($srcRoot))
            {
                $srcRoot = Array('name' => 'source');
                $this->xmlroot['children'][] = &$srcRoot;
            }
            if (!isset($srcRoot['children']))
            {
                $srcRoot['children'] = Array();
            }
            $this->source->load_local_objects_xml($srcRoot);
        }
        else
        {
            $tmp = DH::findFirstElementOrCreate('source', $this->xmlroot);
            $this->source->load_local_objects_domxml($tmp);
        }
    }

    /**
     * For developper use only
     */
    protected function load_destination()
    {
        if( !PH::$UseDomXML)
        {
            $dstRoot = &searchForName('name', 'destination', $this->xmlroot['children']);
            if (is_null($dstRoot))
            {
                $dstRoot = Array('name' => 'destination');
                $this->xmlroot['children'][] = &$dstRoot;
            }
            if (!isset($dstRoot['children']))
            {
                $dstRoot['children'] = Array();
            }
            $this->destination->load_local_objects_xml($dstRoot);
        }
        else
        {
            $tmp = DH::findFirstElementOrCreate('destination', $this->xmlroot);
            $this->destination->load_local_objects_domxml($tmp);
        }
    }


	
	/**
	* For developper use only
	*
	*/
	protected function init_to_with_store()
	{
		$this->to = new ZoneRuleContainer($this);
		$this->to->setName('to');
	}
	
	/**
	* For developper use only
	*
	*/
	protected function init_source_with_store()
	{
		$this->source = new AddressStore($this);
		$this->source->name = 'source';
	}
	
	/**
	* For developper use only
	*
	*/
	protected function init_destination_with_store()
	{
		$this->destination = new AddressStore($this);
		$this->destination->name = 'destination';
	}
	
	/**
	* For developper use only
	*
	*/
	protected function init_services_with_store()
	{
		$this->services = new ServiceStore($this);
		$this->services->name = 'services';
	}
	
	/**
	* For developper use only
	*
	*/
	protected function init_tags_with_store()
	{
		$this->tags = new TagRuleContainer($this);
		$this->tags->setName('tags');
	}
	
	/**
	* For developper use only
	*
	*/
	protected function init_apps_with_store()
	{
		$this->apps = new AppRuleContainer($this);
		$this->apps->setName('apps');
	}
	
	/**
	* For developper use only
	*
	*/
	protected function extract_disabled_from_xml()
	{
		$xml = &$this->xmlroot;
		
		$this->disabledroot = &searchForName('name', 'disabled', $xml['children']);
		if( ! $this->disabledroot )
		{
			$this->disabledroot = Array('name' => 'disabled' , 'content'=>'no');
			$xml['children'][] = &$this->disabledroot;
		}

		//print "this rule has a <disabled>\n";
		$lstate = strtolower($this->disabledroot['content']);
		if( $lstate == 'yes' )
		{
			//print "rule '".$this->name."' is <disabled>\n";
			$this->disabled = true;
		}
	}

	/**
	* For developper use only
	*
	*/
	protected function extract_disabled_from_domxml()
	{
		$xml = $this->xmlroot;
		
		$this->disabledroot = DH::findFirstElementOrCreate('disabled', $xml, 'no');

		//print "this rule has a <disabled>\n";
		$lstate = strtolower($this->disabledroot->textContent);
		if( $lstate == 'yes' )
		{
			//print "rule '".$this->name."' is <disabled>\n";
			$this->disabled = true;
		}
	}
	
	/**
	* For developper use only
	*
	*/
	protected function extract_description_from_xml()
	{
		$xml = &$this->xmlroot;
		
		$this->descroot = &searchForName('name', 'description', $xml['children']);
		if( $this->descroot === null )
		{
			return;
		}

		if( !isset($this->descroot['content']) )
			$this->descroot['content'] = '';
		$this->description = $this->descroot['content'];

	}

	/**
	* For developper use only
	*
	*/
	protected function extract_description_from_domxml()
	{
		$xml = $this->xmlroot;
		
		$this->descroot = DH::findFirstElement('description', $xml );
        if( $this->descroot === false )
        {
            $this->descroot = null;
            return;
        }

		$this->description = $this->descroot->textContent;

	}
	

	/**
	* For developper use only
	*
	*/
	protected function rewriteSDisabled_XML()
	{
		if( PH::$UseDomXML === TRUE )
		{
			if( $this->disabled )
				DH::setDomNodeText($this->disabledroot, 'yes');
			else
				DH::setDomNodeText($this->disabledroot , 'no');
			return;
		}

		if( $this->disabled )
			$this->disabledroot['content'] = 'yes';
		else
			$this->disabledroot['content'] = 'no';
	}
	
	/**
	* disable rule if $disabled = true, enable it if not
	* @param bool $disabled
	*/
	public function setDisabled($disabled)
	{
		$old = $this->disabled;
		$this->disabled = $disabled;
		
		if( $disabled != $old )
		{
			$this->rewriteSDisabled_XML();
			return true;
		}

		return false;
	}

	/**
	* disable rule if $disabled = true, enable it if not
	* @param bool $disabled
	*/
	public function API_setDisabled($disabled)
	{
		$ret = $this->setDisabled($disabled);

		if( $ret )
		{
			$xpath = $this->getXPath().'/disabled';
			$con = findConnectorOrDie($this);
			if( $this->disabled )
				$con->sendEditRequest( $xpath, '<disabled>yes</disabled>');
			else
				$con->sendEditRequest( $xpath, '<disabled>no</disabled>');
		}

		return $ret;
	}
	
	public function setEnabled($enabled)
	{
		if( $enabled )
			return $this->setDisabled(false);
		else
			return $this->setDisabled(true);
	}

	public function API_setEnabled($enabled)
	{
		if( $enabled )
			return $this->API_setDisabled(false);
		else
			return $this->API_setDisabled(true);
	}
	
	public function setDescription($newdesc)
	{
		$this->description = $newdesc;

        if( PH::$UseDomXML )
        {
            if( strlen($this->description) < 1 )
            {
                $this->xmlroot->removeChild($this->descroot);
            }
            else
            {
                if( $this->descroot === null )
                    $this->descroot = DH::createElement($this->xmlroot, 'description');

                DH::setDomNodeText($this->descroot, $this->description);
                if( !$this->xmlroot->isSameNode($this->descroot->parentNode) )
                    $this->descroot->appendChild($this->descroot);
            }
            return;
        }

		$this->descroot['content'] = $newdesc;

        if( strlen($this->description) < 1 )
            $this->descroot['name'] = 'ignme';
        else
        {
            if( $this->descroot === null )
            {
                $this->descroot = Array( 'name' => 'description' , 'content' => '');
                $xml['children'][] = &$this->descroot;
            }
            $this->descroot['name'] = 'description';
        }
	}

    public function &getXPath()
    {
        $str = $this->owner->getXPath()."/entry[@name='".$this->name."']";

        return $str;
    }
	
	public function &xmlroot()
	{
		return $this->xmlroot;
	}
	
	/**
	* return true if change was successful false if not (duplicate rulename?)
	* @return bool
	* @param string $name new name for the rule
	*/
	public function setName($name)
	{

		if( $this->name == $name )
			return true;
		
		if( isset($this->owner) )
		{
			if( $this->owner->isRuleNameAvailable($name) )
			{
				$oldname = $this->name;
				$this->name = $name;
				$this->owner->ruleWasRenamed($this,$oldname);
                if( PH::$UseDomXML === TRUE )
                    $this->xmlroot->getAttributeNode('name')->value = $name;
				else
                    $this->xmlroot['attributes']['name'] = $name;
			}
			else
				return false;
		}
		
		$this->name = $name;


		return true;
		
	}

	public function API_setName($newname)
	{
		$con = findConnectorOrDie($this);
		$xpath = $this->getXPath();

		$con->sendRenameRequest($xpath, $newname);

		$this->setName($newname);	
	}
	
	
	
	
}



