<?php
   



# 
# implementa funcao de calculo de preços e prazos 
# para serviços dos correios
#
if( !function_exists( 'calculaFrete' ))
{
   function calculaFrete(
      $cod_servico, /* cod of service */
      $cep_origem,  /* origin zip code, only numbers */
      $cep_destino, /* destination zip code, apenas numeros */
      $peso,        /* value given in Kg including the packaging: 0.1, 0.3, 1, 2 ,3 , 4 */
      $altura,      /* product height in cm including packaging */
      $largura,     /* product width in cm including packaging */
      $comprimento, /* product length including packaging in cm */
      $valor_declarado='0' /* indicate 0 if you do not want the declared value */
   ){

      $cod_servico = strtoupper( $cod_servico );
      if( $cod_servico == 'SEDEX10' ) $cod_servico = 40215 ; 
      if( $cod_servico == 'SEDEXACOBRAR' ) $cod_servico = 40045 ; 
      if( $cod_servico == 'SEDEX' ) $cod_servico = 40010 ; 
      if( $cod_servico == 'PAC' ) $cod_servico = 41106 ;

      # ###########################################
      # Official Post Codes for services
      # 41106 PAC without contract
      # 40010 SEDEX without contract
      # 40045 SEDEX a Cobrar, without contract
      # 40215 SEDEX 10, without contract
      # ###########################################

      $correios = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=".$cep_origem."&sCepDestino=".$cep_destino."&nVlPeso=".$peso."&nCdFormato=1&nVlComprimento=".$comprimento."&nVlAltura=".$altura."&nVlLargura=".$largura."&sCdMaoPropria=n&nVlValorDeclarado=".$valor_declarado."&sCdAvisoRecebimento=n&nCdServico=".$cod_servico."&nVlDiametro=0&StrRetorno=xml";

      $xml = simplexml_load_file($correios);

      $_arr_ = array();
      if($xml->cServico->Erro == '0'):
         $_arr_['codigo'] = $xml -> cServico -> Codigo ;
         $_arr_['valor'] = $xml -> cServico -> Valor ;
         $_arr_['prazo'] = $xml -> cServico -> PrazoEntrega .' Dias' ;
         // return $xml->cServico->Valor;
         return $_arr_ ; 
      else:
         return false;
      endif;
   }
}


	$origem = $_GET['origem'];
	$destino = $_GET['destino'];
	$peso = $_GET['peso'];
	$altura = $_GET['altura'];
	$largura = $_GET['largura'];
	$comprimento = $_GET['comprimento'];
	$servico = $_GET['servico'];
    $_resultado = calculaFrete( 
        $servico, 
        $origem, 
        $destino, 
        $peso, 
        $altura, $largura, $comprimento, 0 );


	echo "VALOR: ".$_resultado['valor'];
	echo "<br>";
	echo "PRAZO: ".$_resultado['prazo'];
#
# fim da funcao
#
?>
