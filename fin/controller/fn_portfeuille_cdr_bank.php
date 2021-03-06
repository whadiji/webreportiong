<?php ini_set("display_errors",0); 
/*
	c'est une fonction qui permet de retourner la liste de protfeuille
*/
function portfeuille_cdr_bank()
{
				include('../../../cnx/database.php');
				$pdo = Database::connect();
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$pdo1 = Database1::connect();
				$pdo1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				

$sql = "
select 

(select name from ad_gb_rsm where employee_id=rm.rsm_id) 'rsm'
,(select branch_no from ad_gb_rsm where employee_id=rm.rsm_id) 'agence'
,ln.acct_no 'Numcompte'
,rm.rim_no 'rim'
,rm.first_name+' '+rm.last_name 'Nomclient'
,ln.status 'statut'
,ln.amt 'disbursedamt'
,dis.cur_bal 'encours'
,(select identification from ad_rm_ident where ident_id=rm.ident_id) 'typeid'
,isnull(bank_1,'') 'bank'
,isnull(bank_1_acct ,'') 'compte'

from ln_acct ln,ln_display dis,rm_acct rm,rm_personal_info info
where ln.acct_no = dis.acct_no
and ln.rim_no = rm.rim_no
and info.rim_no=rm.rim_no
and ln.status not in ('Incomplete','closed') order by 'agence' ,'rsm'";

$result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
$return = array();
foreach ($result as $row){
	$row['bank']=iconv("UTF-8","ISO-8859-2//IGNORE",$row['bank']);
	$row['compte']=iconv("UTF-8","ISO-8859-2//IGNORE",$row['compte']);
    $return[]=array(
					'rim'=>$row['rim'],
					'rsm'=>$row['rsm'],
					'age'=>$row['agence'],
					'NomCLT'=>$row['Nomclient'],
                    'produit'=>$row['statut'],
					'Numcompte'=>$row['Numcompte'],
					'amt_financed'=>$row['disbursedamt'],
					'encours'=>$row['encours'],
					'typeid'=>$row['typeid'],
					'id'=>$row['bank'],
					'datenaiss'=>$row['compte']);
}
$dbh = null;

header('Content-type: application/json');
echo json_encode($return);
Database::disconnect();
}
?>