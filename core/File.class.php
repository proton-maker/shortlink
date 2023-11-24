<?php 
/**
 * ====================================================================================
 *                           GemFramework (c) Xsantana
 * ----------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework owned by Xsantana Inc as such
 *  distribution or modification of this framework is not allowed before prior consent
 *  from Xsantana administrators. If you find that this framework is packaged in a 
 *  software not distributed by Xsantana or authorized parties, you must not use this
 *  sofware and contact Xsantana at https://piliruma.co.id/contact to inform them of this
 *  misuse otherwise you risk of being prosecuted in courts.
 * ====================================================================================
 *
 * @package Core\File
 * @author Xsantana (http://Xsantana.com)
 * @copyright 2020 Xsantana
 * @license http://Xsantana.com/license
 * @link http://Xsantana.com  
 * @since 1.0
 */
namespace Core;

use SplFileObject;
use Closure;

final class File extends SplFileObject {
	/**
	 * File handler
	 * @var null
	 */
	private $file = NULL;

	/**
	 * Current Data
	 * @var null
	 */
	private $currentdata = NULL;
	/**
	 * Construct a SFO 
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 * @param   [type] $file [description]
	 */
	public function __construct($file, $mode = 'r'){
		parent::__construct($file, $mode);
		$this->file = $file;		
	}
	/**
	 * Factory
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 * @param   mixed  $file   [description]
	 * @param   string $engine [description]
	 * @return  [type]         [description]
	 */
	public static function factory($input, $engine = 'public'){
		
		$mode = 'r';

		if(is_array($input) && count($input) >=2) {
			$file = $input[0];
			$mode = $input[1];
		}

		if(is_string($input)){
			$file = $input;			
		}
		$instance = new self($file, $mode);
		$storage = appConfig('app.storage');
		if(isset($storage[$engine])) $instance->currentdata = $storage[$engine];		
		return $instance;
	}
	/**
	 * Force download a file
	 *
	 * @example  {$file}->download("newname");
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 * @param   string|null $newname [description]
	 * @return  [type]               [description]
	 */
	public function download(?string $newname = null){
		$filename = explode("/", $this->file);
		$name = $newname ?? end($filename);

		header("Content-Description: File Transfer");
		header("Content-Type: application/octet-stream");
		header("Content-Transfer-Encoding: Binary"); 
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: public");
		header("Content-Length: " . filesize($this->file));		
		header("Content-disposition: attachment; filename=\"" . basename($name) . "\""); 
		readfile($this->file); 
		exit;
	}
	/**
	 * Force download content
	 *
	 * @example  File::contentDownload("pp.txt", function(){});
	 * 
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 * @param   [type]  $name [description]
	 * @param   Closure $fn   [description]
	 */
	public static function contentDownload($name, Closure $fn){
		header("Content-Description: File Transfer");
		header("Content-Type: application/octet-stream");
		header("Content-Transfer-Encoding: Binary"); 
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: public");
		header("Content-Disposition: attachment;filename={$name}");
		echo $fn();
		exit;
	}
	/**
	 * View Source
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 * @return  [type] [description]
	 */
	public function source(){
		return $this->fread($this->getSize());
	}
	/**
	 * Get Storage Link
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 * @param   string $engine [description]
	 * @return  [type]         [description]
	 */
	public function storage(string $engine){
		$storage = appConfig('app.storage');
		if(isset($storage[$engine])) $this->currentdata = $storage[$engine];
		return $this;
	}
	/**
	 * Get Link
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 * @return  [type] [description]
	 */
	public function link(){
		return ($this->currentdata['link']?? trim(url(), '/')).'/'.$this->file;
	}
	/**
	 * Get Path
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 * @return  [type] [description]
	 */
	public function path(){
		return ($this->currentdata['path']?? PUB).'/'.$this->file;
	}	
	/**
	 * Move File
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 1.0
	 * @param [type] $directory
	 * @return void
	 */
	public function move($directory){
		if(!\file_exists($this->path())){
			return \GemError::log("Cannot find file.", ['file' => $this->path()]);
		}
		return rename($this->path(), $directory.'/'.$this->file);
	}
	/**
	 * Copy File
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 6.0
	 * @return void
	 */
	public function copy($filename){
		return copy($this->file, $this->currentdata['path'].'/'.$filename);
	}
}