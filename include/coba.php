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