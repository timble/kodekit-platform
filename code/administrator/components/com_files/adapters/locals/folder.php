<?php

class ComFilesAdapterLocalFolder extends ComFilesAdapterLocalAbstract
{
	public function move($target)
	{
		$result = false;
		$encoded = $this->encodePath($target);
		$dir = dirname($encoded);

		if (!is_dir($encoded)) {
			$result = mkdir($encoded, 0755, true);
		}

		if (is_dir($encoded)) {
			$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->_encoded), RecursiveIteratorIterator::SELF_FIRST);
			foreach ($iterator as $f) {
				if ($f->isDir()) {
					$path = $encoded.'/'.$iterator->getSubPathName();
					if (!is_dir($path)) {
						$result = mkdir($path);
					}
				} else {
					$result = copy($f, $encoded.'/'.$iterator->getSubPathName());
				}
				
				if ($result === false) {
					break;
				}
			}
		}
		
		if ($result && $this->delete()) {
			$this->setPath($target);
		} else {
			$result = false;
		}

		return $result;
	}
	
	public function copy($target)
	{
		$result = false;
		$encoded = $this->encodePath($target);
		$dir = dirname($encoded);

		if (!is_dir($encoded)) {
			$result = mkdir($encoded, 0755, true);
		}

		if (is_dir($encoded)) {
			$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->_encoded), RecursiveIteratorIterator::SELF_FIRST);
			foreach ($iterator as $f) {
				if ($f->isDir()) {
					$path = $encoded.'/'.$iterator->getSubPathName();
					if (!is_dir($path)) {
						$result = mkdir($path);
					}
				} else {
					$result = copy($f, $encoded.'/'.$iterator->getSubPathName());
				}
				
				if ($result === false) {
					break;
				}
			}
		}
		
		if ($result) {
			$this->setPath($target);
		}

		return $result;
	}	

	public function delete()
	{
		if (!file_exists($this->_encoded)) {
			return true;
		}

		$iter = new RecursiveDirectoryIterator($this->_encoded);
		foreach (new RecursiveIteratorIterator($iter, RecursiveIteratorIterator::CHILD_FIRST) as $f) {
			if ($f->isDir()) {
				rmdir($f->getPathname());
			} else {
				unlink($f->getPathname());
			}
		}

		return rmdir($this->_encoded);
	}

	public function create()
	{
		$result = true;

		if (!is_dir($this->_encoded)) {
			$result = mkdir($this->_encoded, 0755, true);
		}

		return $result;
	}

	public function exists()
	{
		return is_dir($this->_encoded);
	}
}
