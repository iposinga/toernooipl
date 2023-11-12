<?php
require_once('dbconfig.class.php');	
	
//een Query is een array met cellen	
class Query {
	/*private $command;
	private $_fields;
    private $_tables;
    private $_relaties;
    private $_voorwaarden;
    private $groupby;
    private $_orderby;
    private $_velden;
    private $_veldwaarden;
    private $conn;*/
    //protected ipv private: anders kun je deze variabelen niet gebruiken in de child-class
    protected $command;
	protected $_fields;
    protected $_tables;
    protected $_relaties;
    protected $_voorwaarden;
    protected $groupby;
    protected $_orderby;
    protected $_velden;
    protected $_veldwaarden;
    protected $conn;
    
    public function __construct() {
	    $database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
		$this->_fields = array();
        $this->_tables = array();
        $this->_relaties = array();
        $this->_voorwaarden = array();
        $this->_orderby = array();
        $this->_velden = array();
        $this->_veldwaarden = array();        
    }
    
    public function appendCommand($command) {
	    $this->command = $command;
    }
    
    public function getCommand() {
	    $stmt=$this->command.' ';
        return $stmt;
    }
    
    public function appendField($field) {
        $this->_fields[] = $field;
    }
	
	public function appendVeld($veld) {
        $this->_velden[] = $veld;
    }
    
    public function getFields() {
	    $stmt = '';
	    foreach($this->_fields as $field) {
            //if (!empty($stmt))
            if ($stmt <> '')
		    	$stmt .= ', ';
            $stmt .= $field;
        }
        return $stmt;
    }
    
    
    public function appendTable($table) {
        $this->_tables[] = $table;
    }
    
    public function getTables() {
	    $stmt = '';
	    foreach($this->_tables as $tabel) {
            //if (!empty($stmt))
            if ($stmt <> '')
		    	$stmt .= ', ';
            $stmt .= $tabel;
        }
        return $stmt;
    }
	
	public function getCopyTable() {
	    $stmt = $this->_tables[0];
        return $stmt;
    }
	
	public function getOriginalTable() {
	    $stmt = $this->_tables[1];
        return $stmt;
    }
    
    public function appendRelatie($relatie) {
        $this->_relaties[] = $relatie;
    }
    
   public function getRelations() {
	    if (in_array('leerlingen_lesgroep', $this->_tables) AND in_array('leerlingen_naw', $this->_tables))
	    {
		    $this->appendRelatie('lllesgr_stamnr=stamnr');
	    }
	    if (in_array('personeel_lesgroep', $this->_tables) AND in_array('vakken', $this->_tables))
	    {
		    $this->appendRelatie('perslesgr_vakafk=vakafk');
	    }
	    if (in_array('personeel_lesgroep', $this->_tables) AND in_array('afdelingen', $this->_tables))
	    {
		    $this->appendRelatie('perslesgr_afdnaam=afd_naam');
	    }
	    if (in_array('personeel_lesgr_import', $this->_tables) AND in_array('vakken', $this->_tables))
	    {
		    $this->appendRelatie('imp_perslesgr_vakafk=vakafk');
	    }
	    if (in_array('personeel_lesgr_import', $this->_tables) AND in_array('afdelingen', $this->_tables))
	    {
		    $this->appendRelatie('imp_perslesgr_afdnaam=afd_naam');
	    }
	    if (in_array('personeel_lesgroep', $this->_tables) AND in_array('leerlingen_lesgroep', $this->_tables))
	    {
		    $this->appendRelatie('pers_lesgrID=lllesgr_perslesgrid');
	    } 
	    if (in_array('inhaaluur', $this->_tables) AND in_array('leerlingen_naw', $this->_tables))
	    {
		    $this->appendRelatie('inhuur_stamnr=stamnr');
	    }
	    if (in_array('stamklassen', $this->_tables) AND in_array('afdelingen', $this->_tables))
	    {
		    $this->appendRelatie('stamkl_afd=afd_naam');
	    } 
	    if (in_array('leerlingen_NAW', $this->_tables) AND in_array('stamklassen', $this->_tables))
	    {
		    $this->appendRelatie('stamklas=stamkl_naam');
	    }
	    if (in_array('stamklassen', $this->_tables) AND in_array('afdelingen', $this->_tables))
	    {
		    $this->appendRelatie('stamkl_afd=afd_naam');
	    }
	    if (in_array('inhaaluur', $this->_tables) AND in_array('inhaaluur_teams', $this->_tables))
	    {
		    $this->appendRelatie('inhuur_team=inhuur_teamnaam');
	    } 
	    if (in_array('inhaaluur_teams', $this->_tables) AND in_array('afdelingen', $this->_tables))
	    {
		    $this->appendRelatie('afd_inhteam=inhuur_teamnaam');
	    }
	    if (in_array('examens', $this->_tables) AND in_array('vakken', $this->_tables))
	    {
		    $this->appendRelatie('ex_vakafk=vakken.vakafk');
	    } 
	    if (in_array('leerlingen_cijfers', $this->_tables) AND in_array('leerlingen_NAW', $this->_tables))
	    {
		    $this->appendRelatie('cijf_stamnr=stamnr');
	    } 
	    if (in_array('personeel_lesgroep', $this->_tables) AND in_array('examens', $this->_tables) AND in_array('afdelingen', $this->_tables))
	    {
		    $this->appendRelatie('afd_nivafk=ex_niveauafk AND perslesgr_vakafk=ex_vakafk');
	    } 
	    if (in_array('leerlingen_cijfers', $this->_tables) AND in_array('vakken', $this->_tables))
	    {
		    $this->appendRelatie('cijf_vakafk=vakken.vakafk');
	    } 
	    if (in_array('personeel_lesgroep', $this->_tables) AND in_array('personeel_naw', $this->_tables))
	    {
		    $this->appendRelatie('perslesgr_docafk=perscode');
	    }
	    if (in_array('examens', $this->_tables) AND in_array('leerlingen_cijfers', $this->_tables) AND in_array('leerlingen_NAW', $this->_tables))
	    {
		    $this->appendRelatie('ex_vakafk=cijf_vakafk');
		    $this->appendRelatie('ex_niveauafk=niveauafk');
	    } 
	    if (in_array('leerlingen_NAW', $this->_tables) AND in_array('leerlingen_examenuitslag', $this->_tables))
	    {
		    $this->appendRelatie('stamnr=exuitsl_stamnr');
	    }
	     if (in_array('leerlingen_NAW', $this->_tables) AND in_array('leerlingen_dyslectisch', $this->_tables))
	    {
		    $this->appendRelatie('stamnr=dysl_stamnr');
	    }
        $stmt = '';
        foreach($this->_relaties as $relatie) {
            if ($stmt <> '')
		    	$stmt .= ' AND ';
            $stmt .= $relatie;
        }
        return $stmt;
    }
    
    public function appendVoorwaarde($veld,$waarde) {
        $aangehaaldewaarde=$this->conn->quote($waarde);
        $this->_voorwaarden[] = $veld.'='.$aangehaaldewaarde;
    }
    
    public function appendBindvoorwaarde($veld) {
        //$test=$this->conn->quote($waarde);
        $this->_voorwaarden[] = $veld.'=?';
    }
    
    public function appendBindWithOperator($veld,$operator) {
	    $this->_voorwaarden[] = $veld.$operator.'?';
    }
    
    public function appendBindInsertfield($veld) {
        $this->_velden[] = $veld;
       // $this->_veldwaarden[] = '?';
    }
    
    public function appendVoorwaardestring($string) {
        $this->_voorwaarden[] = $string;
    }
    
    public function getVoorwaarden() {
	    $stmt = '';
	    foreach($this->_voorwaarden as $voorw) {
            if ($stmt <> '')
		    	$stmt .= ' AND ';	
            $stmt .= $voorw;
        }
        return $stmt;
    }
    
    public function appendGroupBy($group) {
        $this->groupby = $group;
    }
    
    public function getGroupBy() {
        $stmt = '';
        if (!empty($this->groupby))
		    	$stmt=' GROUP BY '.$this->groupby;
        return $stmt;
    }
    
     public function appendOrderBy($order) {
        $this->_orderby[] = $order;
    }
    
    public function getOrderBy() {
        $stmt = '';
        if (!empty($this->_orderby))
        {	
	        $stmt = ' ORDER BY ';
        	foreach($this->_orderby as $ord)
        	{
            	if ($stmt <> ' ORDER BY ')
		    		$stmt .= ', ';
				$stmt .= $ord;
        	}
        }
        return $stmt;
    }
    
    public function appendVeldEnWaarde($veld,$waarde) {
        $this->_velden[] = $veld;
        $this->_veldwaarden[] = $this->conn->quote($waarde);
        //$this->_veldwaarden[] = $waarde;
    }
    
    public function getFieldsAndValues() {
	    $stmt = '';
	    $i = 0;
	    foreach($this->_velden as $field) {
            //if (!empty($stmt))
            if ($stmt <> '')
		    	$stmt .= ', ';
		    $stmt .= $field.'='.$this->_veldwaarden[$i];	
            //$stmt .= $field.'='.'\''.$this->_veldwaarden[$i].'\'';
            $i++;
        }
        return $stmt;
    }
    
    public function getBindFields() {
	    $stmt = '';
	    $i = 0;
	    foreach($this->_fields as $field) {
            //if (!empty($stmt))
            if ($stmt <> '')
		    	$stmt .= ', ';
		    $stmt .= $field.'=?';	
            //$stmt .= $field.'='.'\''.$this->_veldwaarden[$i].'\'';
            $i++;
        }
        return $stmt;
    }
    
    
    public function getVelden() {
	    $stmt = '';
	    foreach($this->_velden as $field) {
            //if (!empty($stmt))
            if ($stmt <> '')
		    	$stmt .= ', ';
            $stmt .= $field;
        }
        return $stmt;
    }
    
    public function getVeldwaarden() {
	    $stmt = '';
	    foreach($this->_veldwaarden as $value) {
            //if (!empty($stmt))
            if ($stmt <> '')
		    	$stmt .= ', ';
		    $stmt .= $value;
            //$stmt .= '\''.$value.'\'';
        }
        return $stmt;
    }

    public function prepQuery()
	{
		$query = $this->getCommand();
		$stmt = $this->conn->prepare($query);
		return $stmt;
	}
    
    public function prepSelectQuery()
	{
		$query = 'SELECT '.$this->getFields().' FROM '.$this->getTables();
		//$wherestmtdeel0 = $this->addRelations();
		$wherestmtdeel1 = $this->getRelations();
		$wherestmtdeel2 = $this->getVoorwaarden();
		if ($wherestmtdeel1 <> '' OR $wherestmtdeel2 <> '')
			$query .= ' WHERE ';
		if ($wherestmtdeel1 <> '')
		{
			$query .= $wherestmtdeel1;
			if ($wherestmtdeel2 <> '')
				$query .= ' AND ';
		}			
		$query .= $wherestmtdeel2;
		$query .= $this->getGroupBy();
		$query .= $this->getOrderBy();
		$stmt = $this->conn->prepare($query);
		return $stmt;
	}
	
	public function echoSelectQuery()
	{
		$query = 'SELECT '.$this->getFields().' FROM '.$this->getTables();
		$wherestmtdeel1 = $this->getRelations();
		$wherestmtdeel2 = $this->getVoorwaarden();
		if ($wherestmtdeel1 <> '' OR $wherestmtdeel2 <> '')
			$query .= ' WHERE ';
		if ($wherestmtdeel1 <> '')
		{
			$query .= $wherestmtdeel1;
			if ($wherestmtdeel2 <> '')
				$query .= ' AND ';
		}			
		$query .= $wherestmtdeel2;
		$query .= $this->getGroupBy();
		$query .= $this->getOrderBy();
		//$stmt = $this->conn->prepare($query);
		echo $query;
	}
	
	public function prepInsertQuery()
	{
		$query = 'INSERT INTO '.$this->getTables().' (';
		$query .= $this->getVelden().') VALUES (';
		$query .= $this->getVeldwaarden().')';
		$stmt = $this->conn->prepare($query);
		return $stmt;
	}
	
	public function prepInsertBindQuery()
	{
		$query = 'INSERT INTO '.$this->getTables().' (';
		$query .= $this->getFields().') VALUES (';
		$i=0;
		while($i<count($this->_fields))
		{
			if(substr_count($query, '?') > 0)
				$query.=', ';
			$query.='?';
			$i++;
		}
		$query.=')';
		$stmt = $this->conn->prepare($query);
		return $stmt;
	}
	
	public function echoInsertQuery()
	{
		$query = 'INSERT INTO '.$this->getTables().' (';
		$query .= $this->getVelden().') VALUES (';
		$i=0;
		while($i<count($this->_velden))
		{
			if(substr_count($query, '?') > 0)
				$query.=', ';
			$query.='?';
			$i++;
		}
		$query.=')';
		//$query .= $this->getVeldwaarden().')';
		$stmt = $this->conn->prepare($query);
		echo $query;
	}
	
	public function prepUpdateQuery()
	{
		$query = 'UPDATE '.$this->getTables().' SET ';
		$query .= $this->getFieldsAndValues().' WHERE ';
		$query .= $this->getVoorwaarden();
		$stmt = $this->conn->prepare($query);
		//echo $query;
		return $stmt;

	}
	
	public function prepUpdateBindQuery()
	{
		$query = 'UPDATE '.$this->getTables().' SET ';
		$query .= $this->getBindFields().' WHERE ';
		$query .= $this->getVoorwaarden();
		$stmt = $this->conn->prepare($query);
		//echo $query;
		return $stmt;

	}
	
	public function prepDeleteQuery()
	{
		$query = 'DELETE FROM '.$this->getTables().' WHERE ';
		$query .= $this->getVoorwaarden();
		$stmt = $this->conn->prepare($query);
		return $stmt;

	}
	
	public function prepTruncateQuery()
	{
		$query = 'TRUNCATE TABLE '.$this->getTables();
		//$query .= $this->getVoorwaarden();
		$stmt = $this->conn->prepare($query);
		return $stmt;
	}  
	
	public function prepCopyQuery()
	{
		$query = 'INSERT INTO '.$this->getCopyTable().' ('.$this->getFields().') SELECT '.$this->getVelden().' FROM '.$this->getOriginalTable();
		$stmt = $this->conn->prepare($query);
		return $stmt;
	}
	
	public function geefLastInsertId()
	{
		return $this->conn->lastInsertId();
	}
	
}                
    

?>