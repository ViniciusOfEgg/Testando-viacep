<?php

$servername = "localhost";
$username = "root";
$password = "";
$db = "test";

// Create connection
$conn = mysqli_connect($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error . "<br>");
}
echo "Connected successfully<br>";

//Recebe o cep
$cep = $_POST['cep'];

//Troca o Cep de exemplo pelo o enviado
$url = "viacep.com.br/ws/".$cep."/json";

//Baixa o conteudo
$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, false);

$data = curl_exec($curl); 

curl_close($curl);

//Decodifica os dados
$cep_data = json_decode($data); 

//Acessa e cria novas variaveis.
$logradouro = $cep_data->logradouro;
$bairro     = $cep_data->bairro;
$localidade = $cep_data->localidade;
$uf         = $cep_data->uf;
$ibge       = $cep_data->ibge;

//Insere o valor na database
$sql = "INSERT INTO cep VALUES ('".$logradouro."' , '".$bairro."' , '".$localidade."', '".$uf."', '".$ibge."')";

//Verifica se o valor foi inserido
if($conn->query($sql) === TRUE) {
  echo "New record created successfully<br>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();


?>