<?php
/*
=========================================================================================================================
Project				: 	
Versi				: 	1.0.0
Deskripsi			: 	Class FTP
Function			:	- delete_temp			: menghapus temp di client
						- FTP upload			: upload file using FTP
Disusun Oleh		: 	IT Solution Department - PT Triputra Agro Persada	
Developer			: 	Sabrina Ingrid Davita
Dibuat Tanggal		: 	18/02/2014
Update Terakhir		:	18/02/2014
Revisi				:	
=========================================================================================================================
*/

class class_ftp_upload {

	//deklarasi variable yang digunakan
	var $local_temp;			//local temp utk menampung file hasil generate file
	var $ftp_server;			//FTP Server
	var $ftp_username;			//FTP User
	var $ftp_password;			//FTP Pass
	var $ftp_remotedir;			//FTP Path Remote Dir
	var $file;					//File yg akan diupload
	var $conn_id;
	var $log_file_err;
	var $remote_file;	
	/* DELETE OLD TEMP */
	public function delete_temp($local_temp){
		foreach(scandir($local_temp) as $old_file) {
			if ('.' === $old_file || '..' === $old_file) continue;
			if (is_dir("$local_temp/$old_file")) rmdir_recursive("$local_temp/$old_file");
			else unlink("$local_temp/$old_file");
		}
		rmdir($local_temp);
	}
	
	/* FTP CONNECTION */
	public function ftp_config($ftp_server, $ftp_username, $ftp_password, $ftp_remotedir, $local_temp){
		/* DELETE OLD TEMP */
		//$this->delete_temp($local_temp);
		
		/* VARIABLE TO CONNECT TO FTP SERVER */
		$this->ftp_remotedir = $ftp_remotedir;
		$this->local_temp = $local_temp;
		
		
		$this->conn_id = ftp_connect($ftp_server);
		
		$login_result = @ftp_login($this->conn_id, $ftp_username, $ftp_password);
		
		if(!$login_result){
			die("Cannot connect to FTP server at " . $ftp_server);
		}
		return $login_result;
	}
	
	public function upload_file($file, $query){
		
		/* TRANSFER FILE USING FTP */
		$ret = ftp_put($this->conn_id, $query, $query, FTP_BINARY, FTP_AUTORESUME);
			
		
		
		if($ret == "1") {
				$return = "File '" . $query . "' Telah selesai di upload.";
				unlink($query);
		} else {
				$return = "$query Failed";
		}

while(FTP_MOREDATA == $ret) {
			//$ret = ftp_continue($this->conn_id);
$ret = "OK";
		}
		echo $return."<br>";
		return $return;
	}
	
	public function ftp_close_conn(){
		ftp_close($this->conn_id);
		
		/* DELETE OLD TEMP */
		//$this->delete_temp($this->local_temp);
	}

	
	/* COMPRESS FILE */
	public function log_file($file, $query, $ftp_clientdir){

		//file name yang akan diupload
		$local_file = $file.date('Y-m-d-H').".log";

		//nama file setelah di-compress
		//$file_name = $file.date('YmdHis');
		$file_name = $file;
		
		//masuk ke folder temp
		if ( ! is_dir($ftp_clientdir) ) {
			$oldumask = umask(0);
			mkdir($ftp_clientdir, 0777, true); // or even 01777 so you get the sticky bit set
			umask($oldumask);
		}
		chdir($ftp_clientdir);
		
		$fp = fopen($local_file,"a");
		fwrite($fp,$query);
		return $this->remote_file;
	}
}
?>