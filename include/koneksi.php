<?php
	class DB{

		protected $koneksi;

		function bukaKoneksi(){
			try{
				$this->koneksi = new PDO("mysql:host=localhost;dbname=quarterians","root","", array(PDO::ATTR_PERSISTENT=>TRUE));
			}catch(PDOException $e){
				echo $e->getMessage();
			}
			return $this->koneksi;
		}

		function LoginAdmin($username, $password){
			try{
				$sql = $this->bukaKoneksi()->prepare("select * from admin where username = :username and password = :password");
				$sql->bindParam(':username', $username);
				$sql->bindParam(':password', $password);
				$sql->execute();
				return $sql;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}
	}

	class Lowongan extends DB{
		private $sqlInsert;
		private $sqlEdit;
		private $sqlHapus;
		private $sqlUmumkan;

		function __construct(){
			try{
				$this->sqlInsert = $this->bukaKoneksi()->prepare("insert into lowongan values ('', :lowongan, :kuota, :status)");
				$this->sqlHapus = $this->bukaKoneksi()->prepare("delete from lowongan where id_lowongan = :id_lowongan");
				$this->sqlEdit = $this->bukaKoneksi()->prepare("update lowongan set lowongan=:lowongan, kuota=:kuota, status=:status where id_lowongan=:id_lowongan");
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function GetData($qry_custom){
			try{
				$sql = $this->bukaKoneksi()->prepare("select * from lowongan " . $qry_custom);
				$sql->execute();
				return $sql;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function InsertData($lowongan, $kuota, $status){
			try{
				$this->sqlInsert->bindParam(':lowongan', $lowongan);
				$this->sqlInsert->bindParam(':kuota', $kuota);
				$this->sqlInsert->bindParam(':status', $status);
				$this->sqlInsert->execute();
				return $this->sqlInsert;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function HapusData($id_lowongan){
			try{
				$this->sqlHapus->bindParam(':id_lowongan', $id_lowongan);
				$this->sqlHapus->execute();
				return $this->sqlHapus;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function EditData($lowongan, $kuota, $status, $id_lowongan){
			try{
				$this->sqlEdit->bindParam(':lowongan', $lowongan);
				$this->sqlEdit->bindParam(':kuota', $kuota);
				$this->sqlEdit->bindParam(':status', $status);
				$this->sqlEdit->bindParam(':id_lowongan', $id_lowongan);
				$this->sqlEdit->execute();
				return $this->sqlEdit;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

	}

	class LowonganRinci extends DB{
		private $sqlDataLowongan;
		private $sqlInsert;
		private $sqlEdit;
		private $sqlHapus;
		private $sqlHapusLamaran;

		function __construct(){
			$this->sqlDataLowongan = $this->bukaKoneksi()->prepare("select * from lowongan_rinci where id_lowongan=:id_lowongan");
			$this->sqlInsert = $this->bukaKoneksi()->prepare("insert into lowongan_rinci values ('', :id_lowongan, :kriteria, :nilai, :upload)");
			$this->sqlEdit = $this->bukaKoneksi()->prepare("update lowongan_rinci set kriteria=:kriteria, status_nilai=:nilai, status_upload=:upload where id_lowongan_rinci=:id_lowongan_rinci");
			$this->sqlHapus = $this->bukaKoneksi()->prepare("delete from lowongan_rinci where id_lowongan_rinci=:id_lowongan_rinci");
			$this->sqlHapusLamaran = $this->bukaKoneksi()->prepare("delete from pelamar where id_lowongan=:id_lowongan and kriteria=:kriteria");
		}

		function GetData($qry_custom){
			try{
				$sql = $this->bukaKoneksi()->prepare("select * from lowongan_rinci " . $qry_custom);
				$sql->execute();
				return $sql;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function InsertData($id_lowongan, $kriteria, $nilai, $upload){
			try{
				$this->sqlInsert->bindParam(':id_lowongan', $id_lowongan);
				$this->sqlInsert->bindParam(':kriteria', $kriteria);
				$this->sqlInsert->bindParam(':nilai', $nilai);
				$this->sqlInsert->bindParam(':upload', $upload);
				$this->sqlInsert->execute();
				return $this->sqlInsert;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function GetDataLowongan($id_lowongan){
			try{
				$this->sqlDataLowongan->bindParam(':id_lowongan', $id_lowongan);
				$this->sqlDataLowongan->execute();
				return $this->sqlDataLowongan;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function EditData($kriteria, $nilai, $upload, $id_lowongan_rinci){
			try{
				$this->sqlEdit->bindParam(':kriteria', $kriteria);
				$this->sqlEdit->bindParam(':nilai', $nilai);
				$this->sqlEdit->bindParam(':upload', $upload);
				$this->sqlEdit->bindParam(':id_lowongan_rinci', $id_lowongan_rinci);
				$this->sqlEdit->execute();
				return $this->sqlEdit;
			}catch (PDOException $e){
				$e->getMessage();
			}
		}

		function HapusData($id_lowongan_rinci){
			try{
				$this->sqlHapus->bindParam(':id_lowongan_rinci', $id_lowongan_rinci);
				$this->sqlHapus->execute();
				return $this->sqlHapus;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function HapusKriteriaLamaran($id_lowongan, $kriteria){
			try{
				$this->sqlHapusLamaran->bindParam(':id_lowongan', $id_lowongan);
				$this->sqlHapusLamaran->bindParam(':kriteria', $kriteria);
				$this->sqlHapusLamaran->execute();
				return $this->sqlHapusLamaran;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

	}

	class User extends DB{
		private $sqlRegister;
		private $sqlUpdate;

		function __construct(){
			$this->sqlRegister = $this->bukaKoneksi()->prepare("insert into users (nama_lengkap, username, password, email) values (:nama_lengkap, :username, :password, :email)");
			$this->sqlUpdate = $this->bukaKoneksi()->prepare("update users set nama_lengkap=:nama_lengkap, alamat=:alamat, tempat_lahir=:tempat_lahir, tanggal_lahir=:tanggal_lahir, no_hp=:no_hp, email=:email, foto=:foto where id_user=:id_user");
		}

		function GetData($qry_custom){
			try{
				$sql = $this->bukaKoneksi()->prepare("select * from users " . $qry_custom);
				$sql->execute();
				return $sql;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function Register($nama_lengkap, $username, $password, $email){
			try{
				$this->sqlRegister->bindParam(':nama_lengkap', $nama_lengkap);
				$this->sqlRegister->bindParam(':username', $username);
				$this->sqlRegister->bindParam(':password', $password);
				$this->sqlRegister->bindParam(':email', $email);
				$this->sqlRegister->execute();
				return $this->sqlRegister;
			}catch (PDOException $e){
				$e->getMessage();
			}
		}

		function LoginUser($username, $password){
			try{
				$sql = $this->bukaKoneksi()->prepare("select * from users where username=:username and password=:password");
				$sql->bindParam(':username', $username);
				$sql->bindParam(':password', $password);
				$sql->execute();
				return $sql;
			}catch (PDOException $e){
				$e->getMessage();
			}
		}

		function UpdateData($nama_lengkap, $alamat, $tempat_lahir, $tanggal_lahir, $no_hp, $email, $foto,  $id_user){
			try{
				$this->sqlUpdate->bindParam(':nama_lengkap', $nama_lengkap);
				$this->sqlUpdate->bindParam(':alamat', $alamat);
				$this->sqlUpdate->bindParam(':tempat_lahir', $tempat_lahir);
				$this->sqlUpdate->bindParam(':tanggal_lahir', $tanggal_lahir);
				$this->sqlUpdate->bindParam(':no_hp', $no_hp);
				$this->sqlUpdate->bindParam(':email', $email);
				$this->sqlUpdate->bindParam(':foto', $foto);
				$this->sqlUpdate->bindParam(':id_user', $id_user);
				$this->sqlUpdate->execute();
				return $this->sqlUpdate;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}
	}

	class Pendidikan extends DB{
		private $sqlInsert;
		private $sqlEdit;
		private $sqlHapus;

		function __construct(){
			$this->sqlInsert = $this->bukaKoneksi()->prepare("insert into pendidikan values ('',:id_user, :jenjang, :nama_pend, :tahun)");
			$this->sqlEdit = $this->bukaKoneksi()->prepare("update pendidikan set id_user=:id_user, jenjang=:jenjang, nama_pend=:nama_pend, tahun=:tahun where id_pendidikan=:id_pendidikan");
			$this->sqlHapus = $this->bukaKoneksi()->prepare("delete from pendidikan where id_pendidikan=:id_pendidikan");
		}

		function GetData($qry_custom){
			try{
				$sql = $this->bukaKoneksi()->prepare("select * from pendidikan " . $qry_custom);
				$sql->execute();
				return $sql;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function InsertData($id_user, $jenjang, $nama_pend, $tahun){
			try{
				$this->sqlInsert->bindParam(':jenjang', $jenjang);
				$this->sqlInsert->bindParam(':id_user', $id_user);
				$this->sqlInsert->bindParam(':nama_pend', $nama_pend);
				$this->sqlInsert->bindParam(':tahun', $tahun);
				$this->sqlInsert->execute();
				return $this->sqlInsert;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function EditData($id_user, $jenjang, $nama_pend, $tahun, $id_pendidikan){
			try{
				$this->sqlEdit->bindParam(':id_user', $id_user);
				$this->sqlEdit->bindParam(':jenjang', $jenjang);
				$this->sqlEdit->bindParam(':nama_pend', $nama_pend);
				$this->sqlEdit->bindParam(':tahun', $tahun);
				$this->sqlEdit->bindParam(':id_pendidikan', $id_pendidikan);
				$this->sqlEdit->execute();
				return $this->sqlEdit;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function HapusData($id_pendidikan){
			try{
				$this->sqlHapus->bindParam(':id_pendidikan', $id_pendidikan);
				$this->sqlHapus->execute();
				return $this->sqlHapus;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}
	}

	class Pengalaman extends DB{
		private $sqlInsert;
		private $sqlEdit;
		private $sqlHapus;

		function __construct(){
			$this->sqlInsert = $this->bukaKoneksi()->prepare("insert into pengalaman values ('',:id_user, :organisasi, :jabatan, :masa)");
			$this->sqlEdit = $this->bukaKoneksi()->prepare("update pengalaman set id_user=:id_user, organisasi=:organisasi, jabatan=:jabatan, masa=:masa where id_pengalaman=:id_pengalaman");
			$this->sqlHapus = $this->bukaKoneksi()->prepare("delete from pengalaman where id_pengalaman=:id_pengalaman");
		}

		function GetData($qry_custom){
			try{
				$sql = $this->bukaKoneksi()->prepare("select * from pengalaman " . $qry_custom);
				$sql->execute();
				return $sql;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function InsertData($id_user, $organisasi, $jabatan, $masa){
			try{
				$this->sqlInsert->bindParam(':organisasi', $organisasi);
				$this->sqlInsert->bindParam(':id_user', $id_user);
				$this->sqlInsert->bindParam(':jabatan', $jabatan);
				$this->sqlInsert->bindParam(':masa', $masa);
				$this->sqlInsert->execute();
				return $this->sqlInsert;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function EditData($id_user, $organisasi, $jabatan, $masa, $id_pengalaman){
			try{
				$this->sqlEdit->bindParam(':id_user', $id_user);
				$this->sqlEdit->bindParam(':organisasi', $organisasi);
				$this->sqlEdit->bindParam(':jabatan', $jabatan);
				$this->sqlEdit->bindParam(':masa', $masa);
				$this->sqlEdit->bindParam(':id_pengalaman', $id_pengalaman);
				$this->sqlEdit->execute();
				return $this->sqlEdit;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function HapusData($id_pengalaman){
			try{
				$this->sqlHapus->bindParam(':id_pengalaman', $id_pengalaman);
				$this->sqlHapus->execute();
				return $this->sqlHapus;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}
	}


	class Prestasi extends DB{
		private $sqlInsert;
		private $sqlEdit;
		private $sqlHapus;

		function __construct(){
			$this->sqlInsert = $this->bukaKoneksi()->prepare("insert into prestasi values ('',:id_user, :jenis_lomba, :juara, :tingkat, :lembaga, :tahun)");
			$this->sqlEdit = $this->bukaKoneksi()->prepare("update prestasi set id_user=:id_user, jenis_lomba=:jenis_lomba, juara=:juara, tingkat=:tingkat, lembaga=:lembaga, tahun=:tahun where id_prestasi=:id_prestasi");
			$this->sqlHapus = $this->bukaKoneksi()->prepare("delete from prestasi where id_prestasi=:id_prestasi");
		}

		function GetData($qry_custom){
			try{
				$sql = $this->bukaKoneksi()->prepare("select * from prestasi " . $qry_custom);
				$sql->execute();
				return $sql;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function InsertData($id_user, $jenis_lomba, $juara,  $tingkat, $lembaga, $tahun){
			try{
				$this->sqlInsert->bindParam(':jenis_lomba', $jenis_lomba);
				$this->sqlInsert->bindParam(':id_user', $id_user);
				$this->sqlInsert->bindParam(':juara', $juara);
				$this->sqlInsert->bindParam(':tingkat', $tingkat);
				$this->sqlInsert->bindParam(':lembaga', $lembaga);
				$this->sqlInsert->bindParam(':tahun', $tahun);
				$this->sqlInsert->execute();
				return $this->sqlInsert;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function EditData($id_user, $jenis_lomba, $juara,  $tingkat, $lembaga, $tahun, $id_prestasi){
			try{
				$this->sqlEdit->bindParam(':id_user', $id_user);
				$this->sqlEdit->bindParam(':jenis_lomba', $jenis_lomba);
				$this->sqlEdit->bindParam(':juara', $juara);
				$this->sqlEdit->bindParam(':tingkat', $tingkat);
				$this->sqlEdit->bindParam(':lembaga', $lembaga);
				$this->sqlEdit->bindParam(':tahun', $tahun);
				$this->sqlEdit->bindParam(':id_prestasi', $id_prestasi);
				$this->sqlEdit->execute();
				return $this->sqlEdit;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function HapusData($id_prestasi){
			try{
				$this->sqlHapus->bindParam(':id_prestasi', $id_prestasi);
				$this->sqlHapus->execute();
				return $this->sqlHapus;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}
	}


	class Pelamar extends DB{
		private $sqlCekLamaran;
		private $sqlInsertAwal;
		private $sqlUploadBerkas;
		private $sqlSetNilai;
		private $sqlInsertAwalHitung;

		function __construct(){
			$this->sqlCekLamaran = $this->bukaKoneksi()->prepare("select * from pelamar where id_user=:id_user and id_lowongan=:id_lowongan");
			$this->sqlInsertAwal = $this->bukaKoneksi()->prepare("insert into pelamar (id_user, id_lowongan, kriteria) values (:id_user, :id_lowongan, :kriteria)");
			$this->sqlUploadBerkas = $this->bukaKoneksi()->prepare("update pelamar set file=:file where id_user=:id_user and id_lowongan=:id_lowongan and kriteria=:kriteria");
			$this->sqlSetNilai = $this->bukaKoneksi()->prepare("update pelamar set nilai=:nilai where id_user=:id_user and id_lowongan=:id_lowongan and kriteria=:kriteria");
			$this->sqlInsertAwalHitung = $this->bukaKoneksi()->prepare("insert into hitung (id_user, id_lowongan) values (:id_user, :id_lowongan)");
		}

		function GetData($qry_custom){
			try{
				$sql = $this->bukaKoneksi()->prepare("select * from pelamar " . $qry_custom);
				$sql->execute();
				return $sql;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function CekLamaran($id_user, $id_lowongan){
			try{
				$this->sqlCekLamaran->bindParam(':id_user', $id_user);
				$this->sqlCekLamaran->bindParam(':id_lowongan', $id_lowongan);
				$this->sqlCekLamaran->execute();
				return $this->sqlCekLamaran;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function InsertAwal($id_user, $id_lowongan, $kriteria){
			try{
				$this->sqlInsertAwal->bindParam(':id_user', $id_user);
				$this->sqlInsertAwal->bindParam(':id_lowongan', $id_lowongan);
				$this->sqlInsertAwal->bindParam(':kriteria', $kriteria);
				$this->sqlInsertAwal->execute();
				return $this->sqlInsertAwal;
			}catch (PDOException $e){
				print $e->getMessage();
			}	
		}

		function InsertAwalHitung($id_user, $id_lowongan){
			try{
				$this->sqlInsertAwalHitung->bindParam(':id_user', $id_user);
				$this->sqlInsertAwalHitung->bindParam(':id_lowongan', $id_lowongan);
				$this->sqlInsertAwalHitung->execute();
				return $this->sqlInsertAwalHitung;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function UploadBerkas($file, $id_user, $id_lowongan, $kriteria){
			try{
				$this->sqlUploadBerkas->bindParam(':file', $file);
				$this->sqlUploadBerkas->bindParam(':id_user', $id_user);
				$this->sqlUploadBerkas->bindParam(':id_lowongan', $id_lowongan);
				$this->sqlUploadBerkas->bindParam(':kriteria', $kriteria);
				$this->sqlUploadBerkas->execute();
				return $this->sqlUploadBerkas;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function SetNilai($nilai, $id_user, $id_lowongan, $kriteria){
			try{
				$this->sqlSetNilai->bindParam(':nilai', $nilai);
				$this->sqlSetNilai->bindParam(':id_user', $id_user);
				$this->sqlSetNilai->bindParam(':id_lowongan', $id_lowongan);
				$this->sqlSetNilai->bindParam(':kriteria', $kriteria);
				$this->sqlSetNilai->execute();
				return $this->sqlSetNilai;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}
	}

	class File extends DB{
		private $sqlInsert;
		private $sqlEdit;
		private $sqlHapus;

		function __construct(){
			$this->sqlInsert = $this->bukaKoneksi()->prepare("insert into file values ('', :nama_file, :file)");
			$this->sqlEdit = $this->bukaKoneksi()->prepare("update file set nama_file=:nama_file, file=:file where id_file=:id_file");
			$this->sqlHapus = $this->bukaKoneksi()->prepare("delete from file where id_file=:id_file");
		}

		function GetData($qry_custom){
			try{
				$sql = $this->bukaKoneksi()->prepare("select * from file " . $qry_custom);
				$sql->execute();
				return $sql;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function InsertData($nama_file, $file){
			try{
				$this->sqlInsert->bindParam(':nama_file', $nama_file);
				$this->sqlInsert->bindParam(':file', $file);
				$this->sqlInsert->execute();
				return $this->sqlInsert;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function EditData($nama_file, $file, $id_file){
			try{
				$this->sqlEdit->bindParam(':nama_file', $nama_file);
				$this->sqlEdit->bindParam(':file', $file);
				$this->sqlEdit->bindParam(':id_file', $id_file);
				$this->sqlEdit->execute();
				return $this->sqlEdit;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function HapusData($id_file){
			try{
				$this->sqlHapus->bindParam(':id_file', $id_file);
				$this->sqlHapus->execute();
				return $this->sqlHapus;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}
	}

	class Pengumuman extends DB{
		private $sqlInsert;
		private $sqlEdit;
		private $sqlHapus;

		function __construct(){
			$this->sqlInsert = $this->bukaKoneksi()->prepare("insert into pengumuman values ('', :nama_pengumuman, :file)");
			$this->sqlEdit = $this->bukaKoneksi()->prepare("update pengumuman set nama_pengumuman=:nama_pengumuman, file=:file where id_pengumuman=:id_pengumuman");
			$this->sqlHapus = $this->bukaKoneksi()->prepare("delete from pengumuman where id_pengumuman=:id_pengumuman");
		}

		function GetData($qry_custom){
			try{
				$sql = $this->bukaKoneksi()->prepare("select * from pengumuman " . $qry_custom);
				$sql->execute();
				return $sql;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function InsertData($nama_pengumuman, $file){
			try{
				$this->sqlInsert->bindParam(':nama_pengumuman', $nama_pengumuman);
				$this->sqlInsert->bindParam(':file', $file);
				$this->sqlInsert->execute();
				return $this->sqlInsert;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function EditData($nama_pengumuman, $file, $id_pengumuman){
			try{
				$this->sqlEdit->bindParam(':nama_pengumuman', $nama_pengumuman);
				$this->sqlEdit->bindParam(':file', $file);
				$this->sqlEdit->bindParam(':id_pengumuman', $id_pengumuman);
				$this->sqlEdit->execute();
				return $this->sqlEdit;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}

		function HapusData($id_pengumuman){
			try{
				$this->sqlHapus->bindParam(':id_pengumuman', $id_pengumuman);
				$this->sqlHapus->execute();
				return $this->sqlHapus;
			}catch (PDOException $e){
				print $e->getMessage();
			}
		}
	}

?>