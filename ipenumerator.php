<?php

class IpEnumerator implements Iterator
{

	protected $offset = 0;

	protected $cidr = '';

	public function __construct($cidr)
	{
		if (strpos($cidr, '/') === false) {
			throw new CidrFormatException("'$cidr' is not a CIDR address");
		}

		$this->cidr = $cidr;
	}

	public function next()
	{
		$this->offset++;
	}

	public function rewind()
	{
		$this->offset = 0;
	}

	public function current()
	{
		return $this->get_ip_from_cidr($this->cidr, $this->offset);
	}

	public function count()
	{
		return $this->get_num_of_ips_in_cdir($this->cidr);
	}

	public function valid()
	{
		return ($this->offset < $this->count());
	}

	public function key()
	{
		return $this->offset;
	}

	public function network()
	{
		return $this->get_ip_from_cidr($this->cidr);
	}

	public function broadcast()
	{
		return $this->get_ip_from_cidr($this->cidr, $this->count()-1);
	}

	protected function get_num_of_ips_in_cdir($cidr)
	{
		if (strpos($cidr, '/') === false) {
			throw new CidrFormatException("That is not a CIDR address");
		}

		list($net, $mask) = explode('/', $cidr, 2);

		$num = floatval(pow(2, 32 - $mask));

		return $num;
	}

	/**
	* Given a CIDR formatted network description, returns all the ips in it
	*
	* @param string $cidr The Cidr formatted network
	*
	* @return array the array of ips
	*/
	protected function get_ip_from_cidr($cidr, $offset=0)
	{
		if (strpos($cidr, '/') === false) {
			throw new CidrFormatException("That is not a CIDR address");
		}

		list($net, $mask) = explode('/', $cidr, 2);

		$firstip = floatval(ip2long($net));

		return long2ip($firstip + $offset);
	}

}


class CidrFormatException extends Exception {}
