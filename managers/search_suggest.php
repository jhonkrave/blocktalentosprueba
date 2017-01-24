<?php
//se lee el archivo 
$string = file_get_contents('../files/infopilos.json');
$json_a = json_decode($string, true);

$talentos = $json_a['talentos'];
$pilos = $json_a['pilos'];


//se obtiene el valor del input actualmente
$q = $_GET['q'];

//se realiza la busqueda
$results = array('talentos' => array(), 'pilos' => array());

foreach ($talentos as $username => $data)
{
	if ((stripos($username, $q) !== false) || (stripos($data['firstname'], $q) !== false) || (stripos($data['lastname'], $q) !== false) || (stripos($data['num_doc'], $q) !== false) )
	{
		$results['talentos'][$username] = $data;
	}
}
foreach ($pilos as $username => $data)
{
	if ((stripos($username, $q) !== false) || (stripos($data['firstname'], $q) !== false) || (stripos($data['lastname'], $q) !== false) || (stripos($data['num_doc'], $q) !== false) )
	{
		$results['pilos'][$username] = $data;
	}
}

// se  foramatea la informcion para que lo reconosca el script


//para los talentos
$final_talentos = array('header' => array(), 'data' => array());
$final_talentos['header'] = array(
								'title' => 'Talentos Pilos',					//lo que aparece en la parte superior de esta categoria
								'num' => count($results['talentos']),			// el numero de resultados encontrados
								'limit' => 3									// número de resultados qeu van a aparecer en la sugerencia
							);
foreach ($results['talentos'] as $username => $data)
{
	$final_talentos['data'][] = array(
									'primary' => $username,					// titulo del  resultado actual
									'secondary' => $data['firstname']." ".$data['lastname']." ".$data['tipo_doc']." ".$data['num_doc'],    // Descripcion del resultado actual
									'image' => $data['image'],								// imagen
									'onclick' => '',	# JavaScript se llama en case de este elemento sea cliqueado
									'codigo' => substr ($username, 0 , -5)	 // se usa para autocompletar
								);
}

//para los ser pilo paga
$final_pilos = array('header' => array(), 'data' => array());
$final_pilos['header'] = array(
								'title' => 'Ser pilo paga',					//lo que aparece en la parte superior de esta categoria
								'num' => count($results['pilos']),			// el numero de resultados encontrados
								'limit' => 3									// número de resultados qeu van a aparecer en la sugerencia
							);
foreach ($results['pilos'] as $username => $data)
{
	$final_pilos['data'][] = array(
									'primary' => $username,					// titulo del  resultado actual
									'secondary' => $data['firstname']." ".$data['lastname']." ".$data['tipo_doc']." ".$data['num_doc'],    // Descripcion del resultado actual
									'image' => $data['image'],								// imagen
									'onclick' => '',	# JavaScript se llama en case de este elemento sea cliqueado
									'codigo' => substr ($username, 0 , -5) // se usa para autocompletar
								);
}

//se envia el resultado
$final = array($final_talentos, $final_pilos);
header('Content-type: application/json');
echo json_encode($final);
die();
?>